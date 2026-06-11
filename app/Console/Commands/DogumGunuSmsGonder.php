<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Services\SmsService;
use Illuminate\Console\Command;

class DogumGunuSmsGonder extends Command
{
    protected $signature   = 'sms:dogum-gunu';
    protected $description = 'Bugün doğum günü olan Suçek üyelerine SMS gönderir';

    public function handle(SmsService $sms): int
    {
        $bugun = now();

        $kullanicilar = User::where('dogum_gun', $bugun->day)
            ->where('dogum_ay', $bugun->month)
            ->where('rol', 'sucek')
            ->where('sms_izni', true)
            ->whereNotNull('telefon')
            ->get();

        if ($kullanicilar->isEmpty()) {
            $this->info('Bugün doğum günü olan üye yok.');
            return self::SUCCESS;
        }

        $this->info("Bulunan üye: {$kullanicilar->count()}");

        foreach ($kullanicilar as $user) {
            $gonderildi = $sms->sablonGonder('dogum_gunu', $user->telefon, [
                'ad_soyad' => $user->name,
            ]);

            $durum = $gonderildi ? 'OK' : 'HATA';
            $this->line("[{$durum}] {$user->name} → {$user->telefon}");
        }

        $this->info('Doğum günü SMS gönderimi tamamlandı.');
        return self::SUCCESS;
    }
}
