<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Proje extends Model
{
    use HasFactory;

    protected $table = 'projeler';
    protected $fillable = [
        'baslik','slug','aciklama','kategori','alt_kategori','konum','yil',
        'kapak_gorsel','gorseller','detaylar','video_url','video','aktif','one_cikan','sira',
    ];
    protected $casts = [
        'gorseller' => 'array',
        'aktif'     => 'boolean',
        'one_cikan' => 'boolean',
    ];
}
