<?php

namespace App\Console\Commands;

use App\Mail\CariHatirlatmaMail;
use App\Models\CariHareket;
use App\Models\TedarikciHareket;
use App\Services\MailService;
use App\Services\SmsService;
use Illuminate\Console\Command;

class CariHatirlatmaGonder extends Command
{
    protected $signature = 'cari:hatirlatma';

    protected $description = 'Vadesi yaklaşan/geçen müşteri alacakları ve tedarikçi borçları için günlük özet email/SMS gönderir';

    public function handle(MailService $mail, SmsService $sms): int
    {
        $musteriHareketleri = CariHareket::with('musteri')
            ->where('tur', 'borc')
            ->where('odendi_mi', false)
            ->whereNotNull('vade_tarihi')
            ->whereDate('vade_tarihi', '<=', now()->addDays(3))
            ->orderBy('vade_tarihi')
            ->get();

        $tedarikciHareketleri = TedarikciHareket::with('tedarikci')
            ->where('tur', 'borc')
            ->where('odendi_mi', false)
            ->whereNotNull('vade_tarihi')
            ->whereDate('vade_tarihi', '<=', now()->addDays(3))
            ->orderBy('vade_tarihi')
            ->get();

        if ($musteriHareketleri->isEmpty() && $tedarikciHareketleri->isEmpty()) {
            $this->info('Vadesi yaklaşan/geçen hareket bulunmuyor, bildirim gönderilmedi.');
            return self::SUCCESS;
        }

        $alicEmail = icerik('sistem', 'cari_hatirlatma_email', 'info@sucek.com.tr');
        $alicTelefon = icerik('sistem', 'cari_hatirlatma_telefon');
        $toplamSayi = $musteriHareketleri->count() + $tedarikciHareketleri->count();

        if ($alicEmail) {
            $mail->gonder($alicEmail, new CariHatirlatmaMail($musteriHareketleri, $tedarikciHareketleri), 'cari_hatirlatma');
        }

        if ($alicTelefon) {
            $gecenSayisi = $musteriHareketleri->filter(fn ($h) => $h->vade_tarihi->isPast())->count()
                + $tedarikciHareketleri->filter(fn ($h) => $h->vade_tarihi->isPast())->count();
            $mesaj = "Cari hatırlatma: {$toplamSayi} ödemenin vadesi yaklaşıyor/geçti ({$gecenSayisi} tanesi vadesi geçmiş). Detay için admin panele bakın.";
            $sms->gonder($alicTelefon, $mesaj, 'cari_hatirlatma');
        }

        $this->info($toplamSayi . ' hareket için hatırlatma bildirimi gönderildi.');

        return self::SUCCESS;
    }
}
