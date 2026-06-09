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
        Schema::create('site_icerik', function (Blueprint $table) {
            $table->id();
            $table->string('sayfa', 50)->index();
            $table->string('alan', 100);
            $table->string('baslik', 200);
            $table->string('tip', 20)->default('metin'); // metin, textarea, gorsel, url
            $table->text('deger')->nullable();
            $table->string('gorsel', 500)->nullable();
            $table->integer('sira')->default(0);
            $table->timestamps();
            $table->unique(['sayfa', 'alan']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('site_icerik');
    }
};
