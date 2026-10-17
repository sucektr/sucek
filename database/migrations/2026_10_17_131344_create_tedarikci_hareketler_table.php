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
        Schema::create('tedarikci_hareketler', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tedarikci_id')->constrained('tedarikciler')->cascadeOnDelete();
            $table->enum('tur', ['borc', 'odeme']);
            $table->decimal('tutar', 12, 2);
            $table->string('aciklama');
            $table->date('tarih');
            $table->date('vade_tarihi')->nullable();
            $table->boolean('odendi_mi')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tedarikci_hareketler');
    }
};
