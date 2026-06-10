<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UrunSecenek extends Model
{
    protected $table = 'urun_secenekler';
    protected $fillable = ['urun_id', 'ad', 'sira'];

    public function degerler(): HasMany
    {
        return $this->hasMany(UrunSecenekDegeri::class, 'secenek_id')->orderBy('sira');
    }

    public function urun(): BelongsTo
    {
        return $this->belongsTo(Urun::class);
    }
}
