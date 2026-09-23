<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $path = database_path('data/ceviriler_seed.json');
        if (!file_exists($path)) {
            return;
        }

        $rows = json_decode(file_get_contents($path), true) ?? [];
        $now = now();

        foreach ($rows as $row) {
            DB::table('ceviriler')->updateOrInsert(
                ['tr_metin' => $row['tr']],
                ['en_metin' => $row['en'], 'updated_at' => $now, 'created_at' => $now]
            );
        }
    }

    public function down(): void
    {
        // Geri alma: en_metin degerlerini silmiyoruz, admin tarafindan elle girilmis
        // olabilecek cevirileri kaybetmemek icin bilincli olarak no-op birakildi.
    }
};
