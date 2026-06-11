<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sms_sablonlar', function (Blueprint $table) {
            $table->id();
            $table->string('anahtar', 60)->unique();
            $table->string('baslik', 100);
            $table->text('sablon');
            $table->boolean('aktif')->default(true);
            $table->timestamps();
        });

        DB::table('sms_sablonlar')->insert([
            [
                'anahtar'    => 'siparis_olusturuldu',
                'baslik'     => 'Sipariş Oluşturuldu',
                'sablon'     => 'Sayın {ad_soyad}, {referans} numaralı siparişiniz alındı. Toplam: {toplam} TL. Teşekkürler! suçek.com.tr',
                'aktif'      => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'anahtar'    => 'siparis_odeme_alindi',
                'baslik'     => 'Ödeme Alındı',
                'sablon'     => 'Sayın {ad_soyad}, {referans} numaralı siparişinizin ödemesi alındı. Siparişiniz hazırlanıyor. suçek.com.tr',
                'aktif'      => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'anahtar'    => 'siparis_hazirlaniyor',
                'baslik'     => 'Sipariş Hazırlanıyor',
                'sablon'     => 'Sayın {ad_soyad}, {referans} numaralı siparişiniz hazırlanıyor. suçek.com.tr',
                'aktif'      => false,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'anahtar'    => 'siparis_kargolandi',
                'baslik'     => 'Sipariş Kargolandı',
                'sablon'     => 'Sayın {ad_soyad}, {referans} numaralı siparişiniz kargoya verildi. suçek.com.tr',
                'aktif'      => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'anahtar'    => 'siparis_teslim_edildi',
                'baslik'     => 'Sipariş Teslim Edildi',
                'sablon'     => 'Sayın {ad_soyad}, {referans} numaralı siparişiniz teslim edildi. İyi kullanımlar! suçek.com.tr',
                'aktif'      => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'anahtar'    => 'siparis_iptal',
                'baslik'     => 'Sipariş İptal',
                'sablon'     => 'Sayın {ad_soyad}, {referans} numaralı siparişiniz iptal edildi. Bilgi için bize ulaşabilirsiniz. suçek.com.tr',
                'aktif'      => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'anahtar'    => 'dogum_gunu',
                'baslik'     => 'Doğum Günü Kutlaması',
                'sablon'     => 'Sayın {ad_soyad}, doğum gününüz kutlu olsun! Suçek ailesi olarak sizi sevgilerimizle kutluyoruz.',
                'aktif'      => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'anahtar'    => 'yeni_haber',
                'baslik'     => 'Yeni Haber',
                'sablon'     => 'Suçek\'ten yeni bir haber: {baslik}. Detaylar için: {url}',
                'aktif'      => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('sms_sablonlar');
    }
};
