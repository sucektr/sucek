<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SmsLog extends Model
{
    protected $table = 'sms_loglar';

    protected $fillable = ['alici', 'mesaj', 'sablon_anahtar', 'basarili', 'hata'];

    protected $casts = ['basarili' => 'boolean'];
}
