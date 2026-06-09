<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Adres extends Model
{
    protected $table    = 'adresler';
    protected $fillable = [
        'user_id','tip','ad_soyad','telefon','adres_satiri',
        'sehir','ilce','posta_kodu','ulke',
        'sirket_adi','vergi_dairesi','vergi_no',
    ];

    public function user() { return $this->belongsTo(User::class); }
}
