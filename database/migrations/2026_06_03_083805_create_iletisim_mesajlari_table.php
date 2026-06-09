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
        Schema::create('iletisim_mesajlari', function (Blueprint $table) {
            $table->id();
            $table->string('ad');
            $table->string('email');
            $table->string('telefon')->nullable();
            $table->string('konu')->nullable();
            $table->text('mesaj');
            $table->string('kaynak')->default('iletisim'); // iletisim, mimarlik, insaat vb.
            $table->boolean('okundu')->default(false);
            $table->timestamp('okundu_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('iletisim_mesajlari');
    }
};
