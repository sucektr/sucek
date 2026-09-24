<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $path = database_path('data/icerik_en_seed.json');
        if (!file_exists($path)) {
            return;
        }

        $rows = json_decode(file_get_contents($path), true) ?? [];
        $now = now();

        foreach ($rows as $row) {
            $trRow = DB::table('site_icerik')
                ->where('sayfa', $row['sayfa'])
                ->where('alan', $row['alan'])
                ->where('dil', 'tr')
                ->first();

            if (!$trRow) {
                continue;
            }

            DB::table('site_icerik')->updateOrInsert(
                ['sayfa' => $row['sayfa'], 'alan' => $row['alan'], 'dil' => 'en'],
                [
                    'baslik'     => $trRow->baslik,
                    'tip'        => $trRow->tip,
                    'deger'      => $row['en'],
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
