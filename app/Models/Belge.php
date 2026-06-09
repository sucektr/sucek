<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Belge extends Model
{
    use HasFactory;

    protected $table = 'belgeler';
    protected $fillable = [
        'baslik','slug','aciklama','kategori','dosya_yolu','dosya_turu',
        'dosya_boyutu','herkese_acik','aktif','sira',
    ];
    protected $casts = [
        'herkese_acik' => 'boolean',
        'aktif'        => 'boolean',
    ];
}
