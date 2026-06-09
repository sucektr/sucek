<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class RecaptchaService
{
    public function siteKey(): string
    {
        return icerik('sistem', 'recaptcha_site_key', config('services.recaptcha.site') ?? '');
    }

    public function aktif(): bool
    {
        return $this->siteKey() !== '';
    }

    // reCAPTCHA v2 doğrulama — score yok, sadece success kontrolü
    public function dogrula(string $token, string $ip): bool
    {
        $secret = icerik('sistem', 'recaptcha_secret_key', config('services.recaptcha.secret') ?? '');

        if (empty($secret)) {
            return true;
        }

        try {
            $yanit = Http::asForm()->post('https://www.google.com/recaptcha/api/siteverify', [
                'secret'   => $secret,
                'response' => $token,
                'remoteip' => $ip,
            ])->json();

            return $yanit['success'] ?? false;
        } catch (\Throwable $e) {
            Log::warning('reCAPTCHA doğrulaması başarısız', ['hata' => $e->getMessage()]);
            return false;
        }
    }
}
