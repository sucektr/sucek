<?php

namespace App\Console\Commands;

use App\Mail\CariHatirlatmaMail;
use App\Models\CariHareket;
use App\Services\MailService;
use App\Services\SmsService;
use Illuminate\Console\Command;

class CariHatirlatmaGonder extends Command
{
    protected $signature = 'cari:hatirlatma';

    protected $description = 'Vadesi yaklaşan/geçen cari hareketler için günlük özet email/SMS gönderir';

    public function handle(MailService $mail, SmsService $sms): int
    {
        $hareketler = CariHareket::with('musteri')
            ->where('tur', 'borc')
            ->where('odendi_mi', false)
            ->whereNotNull('vade_tarihi')
            ->whereDate('vade_tarihi', '<=', now()->addDays(3))
            ->orderBy('vade_tarihi')
            ->get();

        if ($hareketler->isEmpty()) {
            $this->info('Vadesi yaklaşan/geçen hareket bulunmuyor, bildirim gönderilmedi.');
            return self::SUCCESS;
        }

        $alicEmail = icerik('sistem', 'cari_hatirlatma_email', 'info@sucek.com.tr');
        $alicTelefon = icerik('sistem', 'cari_hatirlatma_telefon');

        if ($alicEmail) {
            $mail->gonder($alicEmail, new CariHatirlatmaMail($hareketler), 'cari_hatirlatma');
        }

        if ($alicTelefon) {
            $gecenSayisi = $hareketler->filter(fn ($h) => $h->vade_tarihi->isPast())->count();
            $mesaj = "Cari hatırlatma: {$hareketler->count()} ödemenin vadesi yaklaşıyor/geçti ({$gecenSayisi} tanesi vadesi geçmiş). Detay için admin panele bakın.";
            $sms->gonder($alicTelefon, $mesaj, 'cari_hatirlatma');
        }

        $this->info($hareketler->count() . ' hareket için hatırlatma bildirimi gönderildi.');

        return self::SUCCESS;
    }
}
