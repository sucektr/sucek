<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('mail_loglar', function (Blueprint $table) {
            $table->id();
            $table->string('alici', 150);
            $table->string('konu', 255);
            $table->string('sablon', 100)->nullable();
            $table->boolean('basarili')->default(false);
            $table->string('hata', 500)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('mail_loglar');
    }
};
