<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Urun extends Model
{
    use HasFactory;

    protected $table = 'urunler';
    protected $fillable = [
        'ad','slug','aciklama','fiyat','eski_fiyat','kdv_orani','kdv_dahil','kategori','alt_kategori',
        'gorsel','gorseller','dosyalar','ozellikler','stok_kodu','stok','aktif','one_cikan',
        'kargo_bedeli','kargo_kim_oder',
    ];
    protected $casts = [
        'gorseller'     => 'array',
        'dosyalar'      => 'array',
        'ozellikler'    => 'array',
        'aktif'         => 'boolean',
        'one_cikan'     => 'boolean',
        'kdv_dahil'     => 'boolean',
        'fiyat'         => 'decimal:2',
        'eski_fiyat'    => 'decimal:2',
        'kdv_orani'     => 'decimal:2',
        'kargo_bedeli'  => 'decimal:2',
    ];

    /** KDV hariç birim fiyat */
    public function kdvHaricFiyat(): float
    {
        $oran = (float) $this->kdv_orani;
        if ($oran <= 0) return (float) $this->fiyat;
        if ($this->kdv_dahil) {
            return round((float) $this->fiyat * 100 / (100 + $oran), 4);
        }
        return (float) $this->fiyat;
    }

    /** Birim KDV tutarı */
    public function kdvTutari(): float
    {
        $oran = (float) $this->kdv_orani;
        if ($oran <= 0) return 0.0;
        if ($this->kdv_dahil) {
            return round((float) $this->fiyat - $this->kdvHaricFiyat(), 4);
        }
        return round((float) $this->fiyat * $oran / 100, 4);
    }

    /** KDV dahil birim fiyat (müşterinin ödediği) */
    public function kdvDahilFiyat(): float
    {
        if ($this->kdv_dahil) return (float) $this->fiyat;
        return round((float) $this->fiyat + $this->kdvTutari(), 4);
    }

    /** Müşterinin ödeyeceği kargo ücreti (0 = ücretsiz) */
    public function musteriKargoUcreti(): float
    {
        if ($this->kargo_kim_oder === 'musteri') {
            return (float) $this->kargo_bedeli;
        }
        return 0.0;
    }

    public function getIndirimYuzdesiAttribute(): ?int
    {
        if ($this->eski_fiyat && $this->eski_fiyat > $this->fiyat) {
            return (int) round((1 - $this->fiyat / $this->eski_fiyat) * 100);
        }
        return null;
    }

    public function premiumFiyat(): ?float
    {
        if ($this->eski_fiyat && $this->eski_fiyat > $this->fiyat) {
            $pf = round((float) $this->eski_fiyat * 0.85, 2);
            return $pf < (float) $this->fiyat ? $pf : null;
        }
        return round((float) $this->fiyat * 0.85, 2);
    }
}
