<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Musteri extends Model
{
    use HasFactory;

    protected $table = 'musteriler';

    protected $fillable = [
        'ad_soyad',
        'tip',
        'yetkili_kisi',
        'telefon',
        'email',
        'adres',
        'vergi_dairesi',
        'vergi_no',
        'notlar',
        'aktif',
    ];

    protected $casts = [
        'aktif' => 'boolean',
    ];

    public function hareketler(): HasMany
    {
        return $this->hasMany(CariHareket::class)->orderByDesc('tarih')->orderByDesc('id');
    }

    public function belgeler(): HasMany
    {
        return $this->hasMany(CariBelge::class)->orderByDesc('id');
    }

    public function bakiye(): float
    {
        return (float) $this->hareketler()
            ->reorder()
            ->selectRaw("COALESCE(SUM(CASE WHEN tur = 'borc' THEN tutar ELSE -tutar END), 0) as bakiye")
            ->value('bakiye');
    }

    public function vadesiYaklasanlar()
    {
        return $this->hareketler()
            ->where('tur', 'borc')
            ->where('odendi_mi', false)
            ->whereNotNull('vade_tarihi')
            ->whereDate('vade_tarihi', '<=', now()->addDays(3))
            ->orderBy('vade_tarihi');
    }
}
