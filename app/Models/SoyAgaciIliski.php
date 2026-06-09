<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SoyAgaciIliski extends Model
{
    protected $table = 'soy_agaci_iliskiler';

    protected $fillable = ['kisi1_id', 'kisi2_id', 'tip'];
}
