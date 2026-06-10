<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('urun_secenekler', function (Blueprint $table) {
            $table->id();
            $table->foreignId('urun_id')->constrained('urunler')->cascadeOnDelete();
            $table->string('ad', 100);
            $table->unsignedTinyInteger('sira')->default(0);
            $table->timestamps();
        });

        Schema::create('urun_secenek_degerleri', function (Blueprint $table) {
            $table->id();
            $table->foreignId('secenek_id')->constrained('urun_secenekler')->cascadeOnDelete();
            $table->string('deger', 100);
            $table->unsignedTinyInteger('sira')->default(0);
            $table->timestamps();
        });

        Schema::create('urun_varyantlar', function (Blueprint $table) {
            $table->id();
            $table->foreignId('urun_id')->constrained('urunler')->cascadeOnDelete();
            $table->json('degerler');
            $table->integer('stok')->default(0);
            $table->string('stok_kodu', 100)->nullable();
            $table->boolean('aktif')->default(true);
            $table->timestamps();
        });

        Schema::table('siparis_kalemleri', function (Blueprint $table) {
            $table->unsignedBigInteger('varyant_id')->nullable()->after('urun_id');
            $table->json('varyant_bilgisi')->nullable()->after('varyant_id');
        });
    }

    public function down(): void
    {
        Schema::table('siparis_kalemleri', function (Blueprint $table) {
            $table->dropColumn(['varyant_id', 'varyant_bilgisi']);
        });
        Schema::dropIfExists('urun_varyantlar');
        Schema::dropIfExists('urun_secenek_degerleri');
        Schema::dropIfExists('urun_secenekler');
    }
};
