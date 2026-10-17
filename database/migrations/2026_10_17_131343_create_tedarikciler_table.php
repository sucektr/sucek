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
        Schema::create('tedarikciler', function (Blueprint $table) {
            $table->id();
            $table->string('unvan');
            $table->string('yetkili_kisi')->nullable();
            $table->string('telefon')->nullable();
            $table->string('email')->nullable();
            $table->text('adres')->nullable();
            $table->string('vergi_dairesi')->nullable();
            $table->string('vergi_no')->nullable();
            $table->text('notlar')->nullable();
            $table->boolean('aktif')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tedarikciler');
    }
};
