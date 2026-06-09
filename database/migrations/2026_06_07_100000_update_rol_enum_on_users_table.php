<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Geçici olarak tüm değerleri içeren enum (veri güvenliği için)
        DB::statement("ALTER TABLE users MODIFY COLUMN rol ENUM('normal','premium','standart','sucek','teknik') NOT NULL DEFAULT 'normal'");
        // Mevcut premium kullanıcıları standart'a taşı
        DB::statement("UPDATE users SET rol = 'standart' WHERE rol = 'premium'");
        // 'premium' değerini enum'dan kaldır
        DB::statement("ALTER TABLE users MODIFY COLUMN rol ENUM('normal','standart','sucek','teknik') NOT NULL DEFAULT 'normal'");
    }

    public function down(): void
    {
        DB::statement("UPDATE users SET rol = 'premium' WHERE rol IN ('standart','sucek','teknik')");
        DB::statement("ALTER TABLE users MODIFY COLUMN rol ENUM('normal','premium') NOT NULL DEFAULT 'normal'");
    }
};
