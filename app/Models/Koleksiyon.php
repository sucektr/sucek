<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Koleksiyon extends Model
{
    use HasFactory;

    protected $table = 'koleksiyonlar';
    protected $fillable = [
        'ad','slug','aciklama','kategori','fiyat','gorsel','gorseller',
        'dosyalar','ozellikler','stok_kodu','durum','aktif','one_cikan',
        'kargo_bedeli','kargo_kim_oder',
    ];
    protected $casts = [
        'gorseller'    => 'array',
        'dosyalar'     => 'array',
        'ozellikler'   => 'array',
        'aktif'        => 'boolean',
        'one_cikan'    => 'boolean',
        'fiyat'        => 'decimal:2',
        'kargo_bedeli' => 'decimal:2',
    ];

    public function musteriKargoUcreti(): float
    {
        if ($this->kargo_kim_oder === 'musteri') {
            return (float) $this->kargo_bedeli;
        }
        return 0.0;
    }

    public function premiumFiyat(): ?float
    {
        if (!$this->fiyat) return null;
        return round((float) $this->fiyat * 0.85, 2);
    }
}
