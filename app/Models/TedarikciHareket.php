<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TedarikciHareket extends Model
{
    use HasFactory;

    protected $table = 'tedarikci_hareketler';

    protected $fillable = [
        'tedarikci_id',
        'tur',
        'tutar',
        'aciklama',
        'tarih',
        'vade_tarihi',
        'odendi_mi',
    ];

    protected $casts = [
        'tarih'       => 'date',
        'vade_tarihi' => 'date',
        'odendi_mi'   => 'boolean',
        'tutar'       => 'decimal:2',
    ];

    public function tedarikci(): BelongsTo
    {
        return $this->belongsTo(Tedarikci::class);
    }

    public function vadesiGecti(): bool
    {
        return $this->tur === 'borc'
            && !$this->odendi_mi
            && $this->vade_tarihi !== null
            && $this->vade_tarihi->isPast();
    }
}
