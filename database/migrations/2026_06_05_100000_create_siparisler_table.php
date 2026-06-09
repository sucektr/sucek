<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('siparisler', function (Blueprint $table) {
            $table->id();
            $table->string('referans')->unique();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->string('ad_soyad');
            $table->string('email');
            $table->string('telefon')->nullable();
            $table->json('teslimat_adresi');
            $table->json('fatura_adresi')->nullable();
            $table->string('odeme_yontemi')->default('havale');
            $table->enum('durum', ['bekliyor', 'odeme_alindi', 'hazirlaniyor', 'kargolandi', 'teslim_edildi', 'iptal'])->default('bekliyor');
            $table->text('musteri_notu')->nullable();
            $table->text('admin_notu')->nullable();
            $table->decimal('ara_toplam', 12, 2);
            $table->decimal('kargo_ucreti', 10, 2)->default(0);
            $table->decimal('toplam', 12, 2);
            $table->timestamps();
        });

        Schema::create('siparis_kalemleri', function (Blueprint $table) {
            $table->id();
            $table->foreignId('siparis_id')->constrained('siparisler')->cascadeOnDelete();
            $table->string('urun_tipi'); // 'urun' | 'koleksiyon'
            $table->unsignedBigInteger('urun_id');
            $table->string('urun_adi');
            $table->string('urun_gorsel')->nullable();
            $table->decimal('birim_fiyat', 12, 2);
            $table->unsignedSmallInteger('adet');
            $table->decimal('toplam', 12, 2);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('siparis_kalemleri');
        Schema::dropIfExists('siparisler');
    }
};
