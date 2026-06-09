<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('urunler', function (Blueprint $table) {
            $table->decimal('kdv_orani', 5, 2)->default(20)->after('eski_fiyat');
            $table->boolean('kdv_dahil')->default(true)->after('kdv_orani');
        });

        Schema::table('siparisler', function (Blueprint $table) {
            $table->decimal('kdv_tutari', 12, 2)->default(0)->after('ara_toplam');
        });

        Schema::table('siparis_kalemleri', function (Blueprint $table) {
            $table->unsignedSmallInteger('kdv_orani')->default(20)->after('birim_fiyat');
            $table->decimal('kdv_tutari', 12, 2)->default(0)->after('kdv_orani');
        });
    }

    public function down(): void
    {
        Schema::table('urunler', function (Blueprint $table) {
            $table->dropColumn(['kdv_orani', 'kdv_dahil']);
        });

        Schema::table('siparisler', function (Blueprint $table) {
            $table->dropColumn('kdv_tutari');
        });

        Schema::table('siparis_kalemleri', function (Blueprint $table) {
            $table->dropColumn(['kdv_orani', 'kdv_tutari']);
        });
    }
};
