<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Siparis extends Model
{
    protected $table = 'siparisler';

    protected $fillable = [
        'referans', 'user_id', 'ad_soyad', 'email', 'telefon',
        'teslimat_adresi', 'fatura_adresi', 'odeme_yontemi', 'durum',
        'musteri_notu', 'admin_notu', 'ara_toplam', 'kdv_tutari', 'kargo_ucreti', 'toplam',
    ];

    protected $casts = [
        'teslimat_adresi' => 'array',
        'fatura_adresi'   => 'array',
        'ara_toplam'      => 'decimal:2',
        'kdv_tutari'      => 'decimal:2',
        'kargo_ucreti'    => 'decimal:2',
        'toplam'          => 'decimal:2',
    ];

    public function kalemler(): HasMany
    {
        return $this->hasMany(SiparisKalemi::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function durumRenk(): string
    {
        return match($this->durum) {
            'bekliyor'      => 'bg-[#FEF3C7] text-[#D97706]',
            'odeme_alindi'  => 'bg-[#DBEAFE] text-[#1D4ED8]',
            'hazirlaniyor'  => 'bg-[#EDE9FE] text-[#7C3AED]',
            'kargolandi'    => 'bg-[#D1FAE5] text-[#065F46]',
            'teslim_edildi' => 'bg-[#E6F4EC] text-[#1A5C3A]',
            'iptal'         => 'bg-[#FEE2E2] text-[#DC2626]',
            default         => 'bg-[#F5F5F5] text-[#A0A0A0]',
        };
    }

    public function durumEtiketi(): string
    {
        return match($this->durum) {
            'bekliyor'      => 'Ödeme Bekleniyor',
            'odeme_alindi'  => 'Ödeme Alındı',
            'hazirlaniyor'  => 'Hazırlanıyor',
            'kargolandi'    => 'Kargolandı',
            'teslim_edildi' => 'Teslim Edildi',
            'iptal'         => 'İptal Edildi',
            default         => ucfirst($this->durum),
        };
    }

    public static function referansUret(): string
    {
        // 0/O ve 1/I/L karışıklığını önlemek için hariç tutuldu
        $chars = 'ABCDEFGHJKMNPQRSTUVWXYZ23456789';

        do {
            $referans = '';
            for ($i = 0; $i < 8; $i++) {
                $referans .= $chars[random_int(0, strlen($chars) - 1)];
            }
        } while (static::where('referans', $referans)->exists());

        return $referans;
    }
}
