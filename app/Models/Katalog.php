<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Katalog extends Model
{
    protected $table = 'kataloglar';

    protected $fillable = [
        'baslik', 'alt_baslik', 'kapak_ayarlari', 'urun_idler', 'user_id',
    ];

    protected $casts = [
        'kapak_ayarlari' => 'array',
        'urun_idler'     => 'array',
    ];
}
