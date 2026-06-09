<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('soy_agaci_degisiklikler', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->enum('tip', [
                'kisi_ekle', 'kisi_duzenle', 'kisi_sil',
                'iliski_ekle', 'iliski_sil',
            ]);
            $table->json('veri');
            $table->enum('durum', ['beklemede', 'onaylandi', 'reddedildi'])->default('beklemede');
            $table->text('admin_notu')->nullable();
            $table->foreignId('onaylayan_id')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('kararlandi_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('soy_agaci_degisiklikler');
    }
};
