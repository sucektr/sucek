<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UrunVaryant extends Model
{
    protected $table = 'urun_varyantlar';
    protected $fillable = ['urun_id', 'degerler', 'stok', 'stok_kodu', 'aktif'];
    protected $casts = [
        'degerler' => 'array',
        'aktif'    => 'boolean',
        'stok'     => 'integer',
    ];

    public function urun(): BelongsTo
    {
        return $this->belongsTo(Urun::class);
    }

    public function etiket(): string
    {
        return implode(' / ', array_values($this->degerler ?? []));
    }
}
