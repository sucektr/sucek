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
        Schema::create('projeler', function (Blueprint $table) {
            $table->id();
            $table->string('baslik');
            $table->string('slug')->unique();
            $table->text('aciklama')->nullable();
            $table->string('kategori'); // mimarlik, insaat
            $table->string('alt_kategori')->nullable(); // ic-mimari, anahtar-teslim vb.
            $table->string('konum')->nullable();
            $table->year('yil')->nullable();
            $table->string('kapak_gorsel')->nullable();
            $table->json('gorseller')->nullable();
            $table->json('detaylar')->nullable(); // alan m2, sure vb.
            $table->boolean('aktif')->default(true);
            $table->boolean('one_cikan')->default(false);
            $table->integer('sira')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('projeler');
    }
};
