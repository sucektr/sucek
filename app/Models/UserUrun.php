<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserUrun extends Model
{
    protected $table = 'user_urunler';
    protected $fillable = ['user_id', 'ad', 'gorsel', 'aciklama'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
