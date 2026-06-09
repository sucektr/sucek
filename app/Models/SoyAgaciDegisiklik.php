<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SoyAgaciDegisiklik extends Model
{
    protected $table = 'soy_agaci_degisiklikler';

    protected $fillable = [
        'user_id', 'tip', 'veri', 'durum',
        'admin_notu', 'onaylayan_id', 'kararlandi_at',
    ];

    protected $casts = [
        'veri'          => 'array',
        'kararlandi_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function onaylayan()
    {
        return $this->belongsTo(User::class, 'onaylayan_id');
    }

    public function tipLabel(): string
    {
        return match($this->tip) {
            'kisi_ekle'   => 'Kişi Ekle',
            'kisi_duzenle'=> 'Kişi Düzenle',
            'kisi_sil'    => 'Kişi Sil',
            'iliski_ekle' => 'İlişki Ekle',
            'iliski_sil'  => 'İlişki Sil',
            default       => $this->tip,
        };
    }
}
