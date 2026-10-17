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

    protected $description = 'Vadesi yaklaşan/geçen müşteri alacakları ve tedarikçi borçları için günlük özet email/SMS gönderir';

    public function handle(MailService $mail, SmsService $sms): int
    {
        $vadesiYaklasan = fn ($q) => $q
            ->where('tur', 'borc')
            ->where('odendi_mi', false)
            ->whereNotNull('vade_tarihi')
            ->whereDate('vade_tarihi', '<=', now()->addDays(3))
            ->orderBy('vade_tarihi');

        $musteriHareketleri = CariHareket::with('musteri')
            ->whereHas('musteri', fn ($q) => $q->where('tip', 'musteri'))
            ->tap($vadesiYaklasan)
            ->get();

        $tedarikciHareketleri = CariHareket::with('musteri')
            ->whereHas('musteri', fn ($q) => $q->where('tip', 'tedarikci'))
            ->tap($vadesiYaklasan)
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
