<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UrunSecenekDegeri extends Model
{
    protected $table = 'urun_secenek_degerleri';
    protected $fillable = ['secenek_id', 'deger', 'gorsel', 'sira'];

    public function secenek(): BelongsTo
    {
        return $this->belongsTo(UrunSecenek::class, 'secenek_id');
    }
}
