<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ceviri extends Model
{
    protected $table = 'ceviriler';
    protected $fillable = ['tr_metin', 'en_metin'];
}
