<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MailLog extends Model
{
    protected $table = 'mail_loglar';
    protected $fillable = ['alici', 'konu', 'sablon', 'basarili', 'hata'];
    protected $casts = ['basarili' => 'boolean'];
}
