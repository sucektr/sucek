<?php

namespace App\Http\Controllers;

use App\Mail\SiparisDurumMail;
use App\Models\Siparis;
use App\Services\MailService;
use App\Services\SmsService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class OdemeController extends Controller
{
    public function goster(string $referans)
    {
        $siparis = Siparis::with('kalemler')
            ->where('referans', $referans)
            ->where('odeme_yontemi', 'kredi_karti')
            ->firstOrFail();

        if ($siparis->user_id && auth()->id() !== $siparis->user_id) {
            abort(403);
        }

        if (in_array($siparis->durum, ['odeme_alindi', 'hazirlaniyor', 'kargolandi', 'teslim_edildi'])) {
            return redirect()->route('siparis.onay', $referans);
        }

        $userIp = request()->header('X-Forwarded-For')
            ? explode(',', request()->header('X-Forwarded-For'))[0]
            : request()->ip();
        if ($userIp === '127.0.0.1' || $userIp === '::1') {
            $userIp = '88.247.0.1'; // test için geçici genel IP
        }
        [$iframeToken, $paytrHata] = $this->paytrToken($siparis, trim($userIp));

        if (!$iframeToken) {
            return redirect()->route('siparis.onay', $referans)
                ->with('hata', $paytrHata ?: 'Kredi kartı ile ödeme şu an kullanılamıyor. Havale/EFT ile ödeme yapabilirsiniz.');
        }

        return view('siparis.odeme', compact('siparis', 'iframeToken'));
    }

    public function basari(string $referans)
    {
        $siparis = Siparis::where('referans', $referans)->firstOrFail();

        if ($siparis->user_id && auth()->id() !== $siparis->user_id) {
            abort(403);
        }

        return view('siparis.basari', compact('siparis'));
    }

    public function hata(string $referans)
    {
        $siparis = Siparis::where('referans', $referans)->firstOrFail();

        if ($siparis->user_id && auth()->id() !== $siparis->user_id) {
            abort(403);
        }

        return view('siparis.hata', compact('siparis'));
    }

    public function bildirim(Request $request)
    {
        $merchantKey  = icerik('sistem', 'paytr_merchant_key')  ?: config('services.paytr.merchant_key');
        $merchantSalt = icerik('sistem', 'paytr_merchant_salt') ?: config('services.paytr.merchant_salt');
        $post         = $request->all();

        $hashStr = ($post['merchant_oid'] ?? '') . $merchantSalt . ($post['status'] ?? '') . ($post['total_amount'] ?? '');
        $token   = base64_encode(hash_hmac('sha256', $hashStr, $merchantKey, true));

        if ($token !== ($post['hash'] ?? '')) {
            Log::warning('PayTR: Geçersiz hash bildirimi', ['merchant_oid' => $post['merchant_oid'] ?? null]);
            return response('PAYTR_INVALID_HASH', 400);
        }

        $merchantOid = $post['merchant_oid'] ?? '';
        $siparis = Siparis::where('referans', $merchantOid)->first()
            ?? Siparis::whereRaw("REPLACE(REPLACE(referans, '-', ''), '_', '') = ?", [$merchantOid])->first();

        if (!$siparis) {
            return response('OK');
        }

        if (($post['status'] ?? '') === 'success') {
            if ($siparis->durum === 'bekliyor') {
                $siparis->update(['durum' => 'odeme_alindi']);

                if ($siparis->telefon) {
                    app(SmsService::class)->sablonGonder('siparis_odeme_alindi', $siparis->telefon, [
                        'ad_soyad' => $siparis->ad_soyad,
                        'referans' => $siparis->referans,
                        'toplam'   => number_format((float) $siparis->toplam, 2, ',', '.') . ' TL',
                    ]);
                }

                if ($siparis->email) {
                    app(MailService::class)->gonder($siparis->email, new SiparisDurumMail($siparis), 'siparis_durum');
                }
            }
        }

        return response('OK');
    }

    private function paytrToken(Siparis $siparis, string $userIp): array
    {
        $merchantId   = icerik('sistem', 'paytr_merchant_id')   ?: config('services.paytr.merchant_id');
        $merchantKey  = icerik('sistem', 'paytr_merchant_key')  ?: config('services.paytr.merchant_key');
        $merchantSalt = icerik('sistem', 'paytr_merchant_salt') ?: config('services.paytr.merchant_salt');
        $testMode     = icerik('sistem', 'paytr_test_mode', (string) config('services.paytr.test_mode', '0'));

        $merchantOid   = preg_replace('/[^a-zA-Z0-9]/', '', $siparis->referans);
        $email         = $siparis->email;
        $paymentAmount = (int) round((float) $siparis->toplam * 100);

        $basket = [];
        foreach ($siparis->kalemler as $kalem) {
            $basket[] = [
                $kalem->urun_adi,
                number_format((float) $kalem->birim_fiyat, 2, '.', ''),
                (string) $kalem->adet,
            ];
        }
        $userBasket = base64_encode(json_encode($basket));

        $noInstallment  = 0;
        $maxInstallment = 0;
        $currency       = 'TL';
        $userName       = $siparis->ad_soyad;
        $userPhone      = $siparis->telefon ?: '05000000000';
        $userAddress    = implode(', ', array_filter([
            $siparis->teslimat_adresi['adres_satiri'] ?? '',
            $siparis->teslimat_adresi['ilce'] ?? '',
            $siparis->teslimat_adresi['sehir'] ?? '',
        ]));

        $baseUrl         = rtrim(config('app.url'), '/');
        $merchantOkUrl   = $baseUrl . '/siparis/' . $siparis->referans . '/odeme/basari';
        $merchantFailUrl = $baseUrl . '/siparis/' . $siparis->referans . '/odeme/hata';

        $hashStr    = $merchantId . $userIp . $merchantOid . $email . $paymentAmount . $userBasket . $noInstallment . $maxInstallment . $currency . $testMode;
        $paytrToken = base64_encode(hash_hmac('sha256', $hashStr . $merchantSalt, $merchantKey, true));

        Log::info('PayTR token isteği', [
            'merchant_id'      => $merchantId,
            'merchant_oid'     => $merchantOid,
            'email'            => $email,
            'payment_amount'   => $paymentAmount,
            'user_ip'          => $userIp,
            'user_basket'      => $userBasket,
            'merchant_ok_url'  => $merchantOkUrl,
            'merchant_fail_url'=> $merchantFailUrl,
            'test_mode'        => $testMode,
            'hash_str_preview' => substr($hashStr, 0, 80) . '...',
        ]);

        try {
            $response = Http::asForm()->timeout(30)->post('https://www.paytr.com/odeme/api/get-token', [
                'merchant_id'       => $merchantId,
                'user_ip'           => $userIp,
                'merchant_oid'      => $merchantOid,
                'email'             => $email,
                'payment_amount'    => $paymentAmount,
                'paytr_token'       => $paytrToken,
                'user_basket'       => $userBasket,
                'debug_on'          => $testMode === '1' ? '1' : '0',
                'no_installment'    => $noInstallment,
                'max_installment'   => $maxInstallment,
                'user_name'         => $userName,
                'user_address'      => $userAddress,
                'user_phone'        => $userPhone,
                'merchant_ok_url'   => $merchantOkUrl,
                'merchant_fail_url' => $merchantFailUrl,
                'timeout_limit'     => '30',
                'currency'          => $currency,
                'test_mode'         => $testMode,
                'lang'              => 'tr',
            ]);

            $result = $response->json();

            if (($result['status'] ?? '') === 'success') {
                return [$result['token'], null];
            }

            $hata = $result['reason'] ?? $result['err_msg'] ?? ('PayTR API hatası: ' . json_encode($result));
            Log::error('PayTR token hatası', ['result' => $result, 'http_status' => $response->status()]);
            return [null, $hata];
        } catch (\Throwable $e) {
            Log::error('PayTR bağlantı hatası', ['error' => $e->getMessage()]);
            return [null, 'PayTR bağlantı hatası: ' . $e->getMessage()];
        }
    }
}
