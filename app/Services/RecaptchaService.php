<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class RecaptchaService
{
    public function dogrula(string $token, string $ip): bool
    {
        if (empty(config('services.recaptcha.secret'))) {
            return true; // Anahtar tanımlı değilse geç (geliştirme ortamı)
        }

        try {
            $yanit = Http::asForm()->post('https://www.google.com/recaptcha/api/siteverify', [
                'secret'   => config('services.recaptcha.secret'),
                'response' => $token,
                'remoteip' => $ip,
            ])->json();

            $minSkor = (float) config('services.recaptcha.score', 0.5);

            return ($yanit['success'] ?? false) && (($yanit['score'] ?? 0) >= $minSkor);
        } catch (\Throwable $e) {
            Log::warning('reCAPTCHA doğrulaması başarısız', ['hata' => $e->getMessage()]);
            return false;
        }
    }
}
