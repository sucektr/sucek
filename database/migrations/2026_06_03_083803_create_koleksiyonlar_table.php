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
        Schema::create('koleksiyonlar', function (Blueprint $table) {
            $table->id();
            $table->string('ad');
            $table->string('slug')->unique();
            $table->text('aciklama')->nullable();
            $table->string('kategori'); // saat, numizmatik, antika
            $table->decimal('fiyat', 12, 2)->nullable();
            $table->string('gorsel')->nullable();
            $table->json('gorseller')->nullable();
            $table->json('ozellikler')->nullable(); // dönem, menşei, durum vb.
            $table->string('stok_kodu')->nullable();
            $table->enum('durum', ['satista', 'satildi', 'rezerve'])->default('satista');
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
        Schema::dropIfExists('koleksiyonlar');
    }
};
