<?php

namespace App\Http\Controllers;

use App\Models\Siparis;
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
        $iframeToken = $this->paytrToken($siparis, trim($userIp));

        if (!$iframeToken) {
            return back()->with('hata', 'Ödeme sistemi şu an kullanılamıyor. Lütfen daha sonra tekrar deneyin veya havale ile ödeme yapın.');
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
        $merchantKey  = config('services.paytr.merchant_key');
        $merchantSalt = config('services.paytr.merchant_salt');
        $post         = $request->all();

        $hashStr = ($post['merchant_oid'] ?? '') . $merchantSalt . ($post['status'] ?? '') . ($post['total_amount'] ?? '');
        $token   = base64_encode(hash_hmac('sha256', $hashStr, $merchantKey, true));

        if ($token !== ($post['hash'] ?? '')) {
            Log::warning('PayTR: Geçersiz hash bildirimi', ['merchant_oid' => $post['merchant_oid'] ?? null]);
            return response('PAYTR_INVALID_HASH', 400);
        }

        $siparis = Siparis::where('referans', $post['merchant_oid'])->first();

        if (!$siparis) {
            return response('OK');
        }

        if (($post['status'] ?? '') === 'success') {
            if ($siparis->durum === 'bekliyor') {
                $siparis->update(['durum' => 'odeme_alindi']);
            }
        }

        return response('OK');
    }

    private function paytrToken(Siparis $siparis, string $userIp): ?string
    {
        $merchantId   = config('services.paytr.merchant_id');
        $merchantKey  = config('services.paytr.merchant_key');
        $merchantSalt = config('services.paytr.merchant_salt');
        $testMode     = config('services.paytr.test_mode', '0');

        $merchantOid   = $siparis->referans;
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
        $merchantOkUrl   = $baseUrl . '/siparis/' . $merchantOid . '/odeme/basari';
        $merchantFailUrl = $baseUrl . '/siparis/' . $merchantOid . '/odeme/hata';

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
                return $result['token'];
            }

            Log::error('PayTR token hatası', ['result' => $result, 'http_status' => $response->status()]);
            return null;
        } catch (\Throwable $e) {
            Log::error('PayTR bağlantı hatası', ['error' => $e->getMessage()]);
            return null;
        }
    }
}
