<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SiparisKalemi extends Model
{
    protected $table = 'siparis_kalemleri';

    protected $fillable = [
        'siparis_id', 'urun_tipi', 'urun_id', 'urun_adi',
        'urun_gorsel', 'birim_fiyat', 'kdv_orani', 'kdv_tutari', 'adet', 'toplam',
    ];

    protected $casts = [
        'birim_fiyat' => 'decimal:2',
        'kdv_tutari'  => 'decimal:2',
        'toplam'      => 'decimal:2',
    ];

    public function siparis(): BelongsTo
    {
        return $this->belongsTo(Siparis::class);
    }
}
