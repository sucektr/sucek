<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CariBelge extends Model
{
    use HasFactory;

    protected $table = 'cari_belgeler';

    protected $fillable = [
        'musteri_id',
        'dosya_adi',
        'dosya_yolu',
        'dosya_boyutu',
        'mime_tipi',
    ];

    public function musteri(): BelongsTo
    {
        return $this->belongsTo(Musteri::class);
    }

    public function boyutMetin(): string
    {
        $boyut = $this->dosya_boyutu;
        if ($boyut < 1024) return $boyut . ' B';
        if ($boyut < 1048576) return round($boyut / 1024, 1) . ' KB';
        return round($boyut / 1048576, 1) . ' MB';
    }

    public function uzanti(): string
    {
        return strtolower(pathinfo($this->dosya_adi, PATHINFO_EXTENSION));
    }
}
