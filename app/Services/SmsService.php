<?php

namespace App\Services;

use App\Models\SmsSablon;
use App\Models\SmsLog;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SmsService
{
    public function gonder(string $telefon, string $mesaj, ?string $sablonAnahtar = null): bool
    {
        $numara = $this->formatTelefon($telefon);
        if (!$numara) {
            return false;
        }

        $sonuc = $this->api([$numara], $mesaj);

        SmsLog::create([
            'alici'          => $numara,
            'mesaj'          => $mesaj,
            'sablon_anahtar' => $sablonAnahtar,
            'basarili'       => $sonuc['basarili'],
            'hata'           => $sonuc['hata'] ?? null,
        ]);

        return $sonuc['basarili'];
    }

    public function topluGonder(array $telefonlar, string $mesaj, ?string $sablonAnahtar = null): int
    {
        $numaralar = array_values(array_filter(array_map(
            fn($t) => $this->formatTelefon($t),
            $telefonlar
        )));

        if (empty($numaralar)) {
            return 0;
        }

        $basarili = 0;

        foreach (array_chunk($numaralar, 500) as $parca) {
            $sonuc = $this->api($parca, $mesaj);

            foreach ($parca as $numara) {
                SmsLog::create([
                    'alici'          => $numara,
                    'mesaj'          => $mesaj,
                    'sablon_anahtar' => $sablonAnahtar,
                    'basarili'       => $sonuc['basarili'],
                    'hata'           => $sonuc['hata'] ?? null,
                ]);
            }

            if ($sonuc['basarili']) {
                $basarili += count($parca);
            }
        }

        return $basarili;
    }

    public function sablonGonder(string $anahtar, string $telefon, array $degiskenler): bool
    {
        $sablon = SmsSablon::where('anahtar', $anahtar)->where('aktif', true)->first();
        if (!$sablon) {
            return false;
        }

        return $this->gonder($telefon, $sablon->metinOlustur($degiskenler), $anahtar);
    }

    public function sablonTopluGonder(string $anahtar, array $telefonlar, array $degiskenler): int
    {
        $sablon = SmsSablon::where('anahtar', $anahtar)->where('aktif', true)->first();
        if (!$sablon) {
            return 0;
        }

        return $this->topluGonder($telefonlar, $sablon->metinOlustur($degiskenler), $anahtar);
    }

    private function api(array $numaralar, string $mesaj): array
    {
        if (!config('sms.enabled')) {
            return ['basarili' => true]; // test modunda log ama gerçek gönderim yok
        }

        $key        = icerik('sistem', 'iletimerkezi_key');
        $secret     = icerik('sistem', 'iletimerkezi_secret');
        $originator = icerik('sistem', 'iletimerkezi_originator', 'SUCEK');

        if (!$key || !$secret) {
            Log::warning('SMS yapılandırması eksik (İletimerkezi key/secret admin panelinde tanımsız)');
            return ['basarili' => false, 'hata' => 'SMS yapılandırması eksik'];
        }

        $payload = [
            'request' => [
                'authentication' => [
                    'key'  => $key,
                    'hash' => md5($key . '0' . $secret),
                ],
                'order' => [
                    'sender'    => $originator,
                    'iys'       => 0,
                    'iysList'   => 'BIREYSEL',
                    'message'   => [
                        'text'       => $mesaj,
                        'receipents' => [
                            'number' => $numaralar,
                        ],
                    ],
                ],
            ],
        ];

        try {
            $response = Http::timeout(15)->post(config('sms.api_url'), $payload);
            $data     = $response->json();
            $code     = $data['response']['status']['code'] ?? 0;

            if ($code === 200) {
                return ['basarili' => true];
            }

            $hata = $data['response']['status']['message'] ?? 'API hatası: ' . $response->status();
            Log::error('İletimerkezi SMS hatası: ' . $hata, ['numaralar' => $numaralar]);

            return ['basarili' => false, 'hata' => $hata];
        } catch (\Exception $e) {
            Log::error('İletimerkezi bağlantı hatası: ' . $e->getMessage());
            return ['basarili' => false, 'hata' => $e->getMessage()];
        }
    }

    private function formatTelefon(string $tel): ?string
    {
        // Sadece rakamları al
        $tel = preg_replace('/\D/', '', $tel);

        // +90 veya 90 ülke kodu varsa kaldır
        if (strlen($tel) === 12 && str_starts_with($tel, '90')) {
            $tel = substr($tel, 2);
        }

        // 0 ile başlıyorsa kaldır (05XX → 5XX)
        if (strlen($tel) === 11 && str_starts_with($tel, '0')) {
            $tel = substr($tel, 1);
        }

        // Geçerli Türk cep numarası: 5XX XXX XX XX (10 hane, 5 ile başlar)
        if (strlen($tel) === 10 && str_starts_with($tel, '5')) {
            return $tel;
        }

        return null;
    }
}
