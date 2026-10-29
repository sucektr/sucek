<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class YardimciMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if (!Auth::check() || !(Auth::user()->is_yardimci || Auth::user()->is_admin)) {
            return redirect()->route('yardimci.giris')
                ->with('hata', 'Bu alana erişmek için yetkiniz yok.');
        }

        return $next($request);
    }
}
