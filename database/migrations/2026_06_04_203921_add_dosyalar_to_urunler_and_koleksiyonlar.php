<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('urunler', function (Blueprint $table) {
            $table->json('dosyalar')->nullable()->after('gorseller');
        });
        Schema::table('koleksiyonlar', function (Blueprint $table) {
            $table->json('dosyalar')->nullable()->after('gorseller');
        });
    }

    public function down(): void
    {
        Schema::table('urunler', function (Blueprint $table) {
            $table->dropColumn('dosyalar');
        });
        Schema::table('koleksiyonlar', function (Blueprint $table) {
            $table->dropColumn('dosyalar');
        });
    }
};
