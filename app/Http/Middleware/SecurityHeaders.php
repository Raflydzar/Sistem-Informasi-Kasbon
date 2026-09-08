<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SecurityHeaders
{
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        // Tambahkan header keamanan
        $response->headers->set('X-Content-Type-Options', 'nosniff');
        $response->headers->set('Referrer-Policy', 'strict-origin-when-cross-origin');
        $response->headers->set('Strict-Transport-Security', 'max-age=31536000; includeSubDomains');
        
        // CSP yang dilonggarkan sedikit agar Alpine.js & Tailwind tetap berjalan normal
        $response->headers->set('Content-Security-Policy', "default-src 'self' 'unsafe-inline' 'unsafe-eval' https: data:;");

        return $response;
    }
}