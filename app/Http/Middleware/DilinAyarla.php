<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class DilinAyarla
{
    public function handle(Request $request, Closure $next): Response
    {
        $dil = session('site_dil', 'tr');
        if (!in_array($dil, ['tr', 'en'])) {
            $dil = 'tr';
        }
        app()->setLocale($dil);
        return $next($request);
    }
}
