<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Icerik extends Model
{
    protected $table = 'site_icerik';
    protected $fillable = ['sayfa', 'alan', 'dil', 'baslik', 'tip', 'deger', 'gorsel', 'sira'];
}
