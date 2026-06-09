<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('soy_agaci_kisiler', function (Blueprint $table) {
            $table->id();
            $table->string('ad');
            $table->string('soyad')->nullable();
            $table->enum('cinsiyet', ['male', 'female', 'unknown'])->default('unknown');
            $table->string('meslek')->nullable();
            $table->unsignedTinyInteger('bd_gun')->nullable();
            $table->unsignedTinyInteger('bd_ay')->nullable();
            $table->string('bd_yil', 4)->nullable();
            $table->string('olum_yil', 4)->nullable();
            $table->string('yer')->nullable();
            $table->text('notlar')->nullable();
            $table->string('foto')->nullable();
            $table->float('konum_x')->default(200);
            $table->float('konum_y')->default(150);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('soy_agaci_kisiler');
    }
};
