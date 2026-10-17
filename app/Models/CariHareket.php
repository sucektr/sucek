<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CariHareket extends Model
{
    use HasFactory;

    protected $table = 'cari_hareketler';

    protected $fillable = [
        'musteri_id',
        'tur',
        'tutar',
        'aciklama',
        'tarih',
        'vade_tarihi',
        'odendi_mi',
        'iptal',
    ];

    protected $casts = [
        'tarih'       => 'date',
        'vade_tarihi' => 'date',
        'odendi_mi'   => 'boolean',
        'iptal'       => 'boolean',
        'tutar'       => 'decimal:2',
    ];

    public function musteri(): BelongsTo
    {
        return $this->belongsTo(Musteri::class);
    }

    public function vadesiGecti(): bool
    {
        return $this->tur === 'borc'
            && !$this->odendi_mi
            && $this->vade_tarihi !== null
            && $this->vade_tarihi->isPast();
    }
}
