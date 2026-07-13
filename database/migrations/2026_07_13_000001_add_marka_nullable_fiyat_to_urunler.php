<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('urunler', function (Blueprint $table) {
            $table->string('marka')->nullable()->after('slug');
        });

        // fiyat sütununu nullable yap (doctrine/dbal gerektirmeden raw SQL)
        DB::statement('ALTER TABLE urunler MODIFY fiyat DECIMAL(10,2) NULL DEFAULT NULL');
    }

    public function down(): void
    {
        Schema::table('urunler', function (Blueprint $table) {
            $table->dropColumn('marka');
        });

        DB::statement('ALTER TABLE urunler MODIFY fiyat DECIMAL(10,2) NOT NULL');
    }
};
