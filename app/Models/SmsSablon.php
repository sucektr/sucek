<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SmsSablon extends Model
{
    protected $table = 'sms_sablonlar';

    protected $fillable = ['anahtar', 'baslik', 'sablon', 'aktif'];

    protected $casts = ['aktif' => 'boolean'];

    public function metinOlustur(array $degiskenler): string
    {
        $metin = $this->sablon;
        foreach ($degiskenler as $anahtar => $deger) {
            $metin = str_replace('{' . $anahtar . '}', (string) $deger, $metin);
        }
        return $metin;
    }
}
