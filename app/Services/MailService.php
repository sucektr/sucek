<?php

namespace App\Services;

use App\Models\MailLog;
use Illuminate\Mail\Mailable;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class MailService
{
    public function gonder(string $alici, Mailable $mail, string $sablon = ''): bool
    {
        $this->configureSmtp();

        try {
            Mail::to($alici)->send($mail);

            MailLog::create([
                'alici'    => $alici,
                'konu'     => $mail->envelope()->subject,
                'sablon'   => $sablon ?: null,
                'basarili' => true,
            ]);

            return true;
        } catch (\Exception $e) {
            Log::error('Mail gönderilemedi: ' . $e->getMessage(), ['alici' => $alici]);

            MailLog::create([
                'alici'    => $alici,
                'konu'     => method_exists($mail, 'envelope') ? ($mail->envelope()->subject ?? '?') : '?',
                'sablon'   => $sablon ?: null,
                'basarili' => false,
                'hata'     => substr($e->getMessage(), 0, 500),
            ]);

            return false;
        }
    }

    public function topluGonder(array $alicilar, Mailable $mail, string $sablon = ''): int
    {
        $this->configureSmtp();
        $basarili = 0;

        foreach ($alicilar as $alici) {
            if ($this->gonder($alici, clone $mail, $sablon)) {
                $basarili++;
            }
        }

        return $basarili;
    }

    private function configureSmtp(): void
    {
        $host = icerik('sistem', 'mail_host');
        if (!$host) return;

        config([
            'mail.mailers.smtp.host'       => $host,
            'mail.mailers.smtp.port'       => (int) icerik('sistem', 'mail_port', '587'),
            'mail.mailers.smtp.username'   => icerik('sistem', 'mail_username'),
            'mail.mailers.smtp.password'   => icerik('sistem', 'mail_password'),
            'mail.mailers.smtp.encryption' => icerik('sistem', 'mail_encryption', 'tls'),
            'mail.from.address'            => icerik('sistem', 'mail_from', 'info@sucek.com.tr'),
            'mail.from.name'               => icerik('sistem', 'mail_from_name', 'SUÇEK'),
        ]);

        app('mail.manager')->purge('smtp');
    }
}
