<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ceviriler', function (Blueprint $table) {
            $table->id();
            $table->string('tr_metin', 500)->unique();
            $table->text('en_metin')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ceviriler');
    }
};
