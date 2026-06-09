<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Teklif extends Model
{
    protected $table    = 'teklifler';
    protected $fillable = ['user_id', 'koleksiyon_id', 'miktar', 'mesaj', 'durum'];
    protected $casts    = ['miktar' => 'decimal:2'];

    public function user()       { return $this->belongsTo(User::class); }
    public function koleksiyon() { return $this->belongsTo(Koleksiyon::class); }
}
