<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IletisimMesaji extends Model
{
    use HasFactory;

    protected $table = 'iletisim_mesajlari';
    protected $fillable = ['ad','email','telefon','konu','mesaj','kaynak','okundu','okundu_at'];
    protected $casts = ['okundu' => 'boolean', 'okundu_at' => 'datetime'];
}
