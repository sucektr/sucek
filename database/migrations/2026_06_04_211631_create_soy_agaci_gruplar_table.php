<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('soy_agaci_gruplar', function (Blueprint $table) {
            $table->id();
            $table->string('label');
            $table->string('renk_hex', 20);
            $table->string('renk_bg', 80);
            $table->json('uye_idler');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('soy_agaci_gruplar');
    }
};
