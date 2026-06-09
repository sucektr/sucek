<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class RecaptchaService
{
    public function siteKey(): string
    {
        return icerik('sistem', 'recaptcha_site_key', config('services.recaptcha.site', ''));
    }

    public function dogrula(string $token, string $ip): bool
    {
        $secret  = icerik('sistem', 'recaptcha_secret_key', config('services.recaptcha.secret', ''));
        $minSkor = (float) icerik('sistem', 'recaptcha_min_score', config('services.recaptcha.score', '0.5'));

        if (empty($secret)) {
            return true; // Anahtar tanımlı değilse geç (geliştirme ortamı)
        }

        try {
            $yanit = Http::asForm()->post('https://www.google.com/recaptcha/api/siteverify', [
                'secret'   => $secret,
                'response' => $token,
                'remoteip' => $ip,
            ])->json();

            return ($yanit['success'] ?? false) && (($yanit['score'] ?? 0) >= $minSkor);
        } catch (\Throwable $e) {
            Log::warning('reCAPTCHA doğrulaması başarısız', ['hata' => $e->getMessage()]);
            return false;
        }
    }
}
