<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $alanlar = ['kvkk_icerik', 'gizlilik_icerik', 'sss_icerik', 'mesafeli_icerik', 'iade_icerik'];
        $now = now();

        foreach ($alanlar as $alan) {
            $path = database_path("data/yasal_en/{$alan}.html");
            if (!file_exists($path)) {
                continue;
            }

            $trRow = DB::table('site_icerik')
                ->where('sayfa', 'yasal')
                ->where('alan', $alan)
                ->where('dil', 'tr')
                ->first();

            if (!$trRow) {
                continue;
            }

            DB::table('site_icerik')->updateOrInsert(
                ['sayfa' => 'yasal', 'alan' => $alan, 'dil' => 'en'],
                [
                    'baslik'     => $trRow->baslik,
                    'tip'        => $trRow->tip,
                    'deger'      => file_get_contents($path),
                    'gorsel'     => $trRow->gorsel,
                    'sira'       => $trRow->sira,
                    'updated_at' => $now,
                    'created_at' => $now,
                ]
            );
        }
    }

    public function down(): void
    {
        // Geri alma: admin panelden elle girilmis olabilecek Ingilizce icerikleri
        // kaybetmemek icin bilincli olarak no-op birakildi.
    }
};
