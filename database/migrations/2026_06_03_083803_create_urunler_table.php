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
        Schema::create('urunler', function (Blueprint $table) {
            $table->id();
            $table->string('ad');
            $table->string('slug')->unique();
            $table->text('aciklama')->nullable();
            $table->decimal('fiyat', 10, 2);
            $table->decimal('eski_fiyat', 10, 2)->nullable();
            $table->string('kategori'); // spor, dekorasyon, insaat
            $table->string('alt_kategori')->nullable();
            $table->string('gorsel')->nullable();
            $table->json('gorseller')->nullable();
            $table->json('ozellikler')->nullable();
            $table->string('stok_kodu')->nullable();
            $table->integer('stok')->default(0);
            $table->boolean('aktif')->default(true);
            $table->boolean('one_cikan')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('urunler');
    }
};
