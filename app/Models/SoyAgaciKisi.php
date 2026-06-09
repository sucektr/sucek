<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SoyAgaciKisi extends Model
{
    protected $table = 'soy_agaci_kisiler';

    protected $fillable = [
        'ad', 'soyad', 'cinsiyet', 'meslek',
        'bd_gun', 'bd_ay', 'bd_yil', 'olum_yil',
        'yer', 'notlar', 'foto',
        'konum_x', 'konum_y',
    ];

    protected $casts = [
        'bd_gun'  => 'integer',
        'bd_ay'   => 'integer',
        'konum_x' => 'float',
        'konum_y' => 'float',
    ];
}
