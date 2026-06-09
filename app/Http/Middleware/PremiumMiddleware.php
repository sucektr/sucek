<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;

class PremiumMiddleware
{
    private const TUM_TIPLER = ['standart', 'sucek', 'teknik'];

    public function handle(Request $request, Closure $next, string ...$parametreler): Response
    {
        if (!auth()->check()) {
            return redirect()->route('giris');
        }

        $user = auth()->user();

        if ($user->is_admin) {
            return $next($request);
        }

        // Tek parametre varsa ve bir üyelik tipi değilse → bolum_slug olarak yorumla
        if (count($parametreler) === 1 && !in_array($parametreler[0], self::TUM_TIPLER)) {
            $bolumSlug = $parametreler[0];
            $tipler = Cache::remember("erisim_kural:{$bolumSlug}", 300, function () use ($bolumSlug) {
                $kural = DB::table('erisim_kurallari')->where('bolum_slug', $bolumSlug)->first();
                return $kural ? json_decode($kural->izin_tipler, true) : self::TUM_TIPLER;
            });
        } else {
            $tipler = empty($parametreler) ? self::TUM_TIPLER : $parametreler;
        }

        if (!in_array($user->rol, $tipler)) {
            return redirect()->route('premium.gerekli');
        }

        return $next($request);
    }
}
