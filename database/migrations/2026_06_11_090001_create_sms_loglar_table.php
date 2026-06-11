<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sms_loglar', function (Blueprint $table) {
            $table->id();
            $table->string('alici', 20);
            $table->text('mesaj');
            $table->string('sablon_anahtar', 60)->nullable();
            $table->boolean('basarili')->default(false);
            $table->string('hata', 255)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sms_loglar');
    }
};
