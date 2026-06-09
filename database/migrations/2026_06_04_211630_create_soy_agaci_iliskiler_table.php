<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('soy_agaci_iliskiler', function (Blueprint $table) {
            $table->id();
            // tip=parent → kisi1 ebeveyn, kisi2 çocuk
            $table->foreignId('kisi1_id')->constrained('soy_agaci_kisiler')->cascadeOnDelete();
            $table->foreignId('kisi2_id')->constrained('soy_agaci_kisiler')->cascadeOnDelete();
            $table->enum('tip', ['parent', 'couple', 'sibling']);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('soy_agaci_iliskiler');
    }
};
