<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('adresler', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->enum('tip', ['kargo', 'fatura']);
            $table->string('ad_soyad');
            $table->string('telefon')->nullable();
            $table->text('adres_satiri');
            $table->string('sehir');
            $table->string('ilce')->nullable();
            $table->string('posta_kodu')->nullable();
            $table->string('ulke')->default('Türkiye');
            // Fatura alanları
            $table->string('sirket_adi')->nullable();
            $table->string('vergi_dairesi')->nullable();
            $table->string('vergi_no')->nullable();
            $table->timestamps();

            $table->unique(['user_id', 'tip']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('adresler');
    }
};
