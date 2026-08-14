<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CanonicalDomain
{
    private const ALT_DOMAINS = ['sucek.tr', 'www.sucek.tr', 'sucek.com', 'www.sucek.com', 'www.sucek.com.tr'];
    private const CANONICAL = 'sucek.com.tr';

    public function handle(Request $request, Closure $next): Response
    {
        if (in_array(strtolower($request->getHost()), self::ALT_DOMAINS, true)) {
            return redirect()->to('https://' . self::CANONICAL . $request->getRequestUri(), 301);
        }

        return $next($request);
    }
}
