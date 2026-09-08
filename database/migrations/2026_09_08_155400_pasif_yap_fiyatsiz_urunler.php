<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Fiyatı girilmemiş (NULL) ürünler mağazada/Google Shopping feed'inde 0 TL
     * olarak görünüyordu. Ürünler silinmiyor, sadece pasife alınıyor — fiyatı
     * girildikçe admin panelden ürün bazında tekrar aktif edilebilir.
     */
    public function up(): void
    {
        DB::table('urunler')->whereNull('fiyat')->update(['aktif' => false]);
    }

    public function down(): void
    {
        // Hangi ürünlerin bu migration'dan önce aktif olduğu bilinmediğinden
        // geri alma işlemi güvenli şekilde yapılamaz.
    }
};
