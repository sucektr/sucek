<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'is_admin',
        'rol',
        'telefon',
        'dogum_gun',
        'dogum_ay',
        'dogum_yil',
        'sms_izni',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'sms_izni'          => 'boolean',
        'dogum_gun'         => 'integer',
        'dogum_ay'          => 'integer',
        'dogum_yil'         => 'integer',
    ];

    public function isPremium(): bool
    {
        return in_array($this->rol, ['standart', 'sucek', 'teknik']);
    }

    public function isStandart(): bool { return $this->rol === 'standart'; }
    public function isSucek(): bool    { return $this->rol === 'sucek'; }
    public function isTeknik(): bool   { return $this->rol === 'teknik'; }

    public function hasUyelik(string ...$tipler): bool
    {
        return in_array($this->rol, $tipler);
    }

    public static function rolBilgi(string $rol): array
    {
        return match($rol) {
            'standart' => ['isim' => 'Standart', 'renk' => '#2563eb', 'bg' => '#eff6ff', 'sinir' => '#bfdbfe', 'ikon' => 'ti-star'],
            'sucek'    => ['isim' => 'Suçek',    'renk' => '#7c3aed', 'bg' => '#f5f3ff', 'sinir' => '#ddd6fe', 'ikon' => 'ti-crown'],
            'teknik'   => ['isim' => 'Teknik',   'renk' => '#d97706', 'bg' => '#fffbeb', 'sinir' => '#fde68a', 'ikon' => 'ti-tool'],
            default    => ['isim' => 'Standart',  'renk' => '#2563eb', 'bg' => '#eff6ff', 'sinir' => '#bfdbfe', 'ikon' => 'ti-star'],
        };
    }

    public function teklifler()
    {
        return $this->hasMany(\App\Models\Teklif::class);
    }

    public function adresler()
    {
        return $this->hasMany(\App\Models\Adres::class);
    }

    public function kargoAdresi()
    {
        return $this->hasOne(\App\Models\Adres::class)->where('tip', 'kargo');
    }

    public function faturaAdresi()
    {
        return $this->hasOne(\App\Models\Adres::class)->where('tip', 'fatura');
    }
}
