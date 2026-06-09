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
        Schema::create('belgeler', function (Blueprint $table) {
            $table->id();
            $table->string('baslik');
            $table->string('slug')->unique();
            $table->text('aciklama')->nullable();
            $table->string('kategori'); // ruhsat, ic-mimari, insaat, genel
            $table->string('dosya_yolu');
            $table->string('dosya_turu')->nullable(); // PDF, DOCX vb.
            $table->unsignedBigInteger('dosya_boyutu')->nullable();
            $table->boolean('herkese_acik')->default(false);
            $table->boolean('aktif')->default(true);
            $table->integer('sira')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('belgeler');
    }
};
