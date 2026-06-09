<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SoyAgaciGrup extends Model
{
    protected $table = 'soy_agaci_gruplar';

    protected $fillable = ['label', 'renk_hex', 'renk_bg', 'uye_idler'];

    protected $casts = ['uye_idler' => 'array'];
}
