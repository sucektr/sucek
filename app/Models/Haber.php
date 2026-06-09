<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Haber extends Model
{
    protected $table = 'haberler';

    protected $fillable = [
        'baslik', 'slug', 'ozet', 'icerik', 'kapak', 'kategori', 'yayinda',
    ];

    protected $casts = [
        'yayinda' => 'boolean',
    ];

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public static function slugOlustur(string $baslik, ?int $haricId = null): string
    {
        $slug = Str::slug($baslik, '-', 'tr');
        $original = $slug;
        $i = 2;

        while (
            static::where('slug', $slug)
                ->when($haricId, fn($q) => $q->where('id', '!=', $haricId))
                ->exists()
        ) {
            $slug = $original . '-' . $i++;
        }

        return $slug;
    }
}
