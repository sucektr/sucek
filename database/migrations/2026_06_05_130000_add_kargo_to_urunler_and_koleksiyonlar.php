<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('urunler', function (Blueprint $table) {
            $table->decimal('kargo_bedeli', 10, 2)->default(0)->after('kdv_dahil');
            $table->enum('kargo_kim_oder', ['magaza', 'satici', 'musteri'])->default('magaza')->after('kargo_bedeli');
        });

        Schema::table('koleksiyonlar', function (Blueprint $table) {
            $table->decimal('kargo_bedeli', 10, 2)->default(0)->after('one_cikan');
            $table->enum('kargo_kim_oder', ['magaza', 'satici', 'musteri'])->default('magaza')->after('kargo_bedeli');
        });
    }

    public function down(): void
    {
        Schema::table('urunler', function (Blueprint $table) {
            $table->dropColumn(['kargo_bedeli', 'kargo_kim_oder']);
        });
        Schema::table('koleksiyonlar', function (Blueprint $table) {
            $table->dropColumn(['kargo_bedeli', 'kargo_kim_oder']);
        });
    }
};
