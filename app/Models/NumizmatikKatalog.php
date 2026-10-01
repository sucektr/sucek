<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NumizmatikKatalog extends Model
{
    use HasFactory;

    protected $table = 'numizmatik_kataloglari';
    protected $fillable = [
        'baslik', 'alt_baslik', 'kapak_ayarlari', 'koleksiyon_idler', 'user_id',
    ];
    protected $casts = [
        'kapak_ayarlari'   => 'array',
        'koleksiyon_idler' => 'array',
    ];
}
