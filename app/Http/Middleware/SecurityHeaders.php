<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SecurityHeaders
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        // Prevent clickjacking attacks
        $response->headers->set('X-Frame-Options', 'DENY');

        // Prevent MIME type sniffing
        $response->headers->set('X-Content-Type-Options', 'nosniff');

        // Enable XSS protection
        $response->headers->set('X-XSS-Protection', '1; mode=block');

        // Referrer policy
        $response->headers->set('Referrer-Policy', 'strict-origin-when-cross-origin');

        // Permissions policy - restrict access to device features
        $response->headers->set('Permissions-Policy', implode(', ', [
            'geolocation=(self)',
            'microphone=(self)',
            'camera=(self)',
            'payment=(self)',
            'usb=()',
            'magnetometer=()',
            'accelerometer=()',
            'gyroscope=()',
            'picture-in-picture=()',
        ]));

        // Content Security Policy
        $csp = $this->getContentSecurityPolicy($request);
        $response->headers->set('Content-Security-Policy', $csp);

        // HSTS - Force HTTPS (only in production)
        if (app()->environment('production')) {
            $response->headers->set('Strict-Transport-Security', 'max-age=31536000; includeSubDomains; preload');
        }

        // Expect-CT header for certificate transparency
        if (app()->environment('production')) {
            $response->headers->set('Expect-CT', 'max-age=86400, enforce');
        }

        // Remove identifying headers
        $response->headers->remove('X-Powered-By');
        $response->headers->remove('Server');

        return $response;
    }

    /**
     * Generate Content Security Policy
     */
    protected function getContentSecurityPolicy(Request $request): string
    {
        $appUrl = config('app.url');
        $parsed = parse_url($appUrl);
        $domain = $parsed['host'] ?? 'localhost';

        $isLocal = app()->environment('local', 'development');

        // Base script sources
        $scriptSrc = "'self' 'unsafe-inline' 'unsafe-eval' https://cdn.jsdelivr.net https://js.pusher.com https://unpkg.com https://cdn.tailwindcss.com";

        // Base style sources - include fonts.bunny.net (used by the app) and fonts.googleapis.com
        $styleSrc = "'self' 'unsafe-inline' https://fonts.googleapis.com https://fonts.bunny.net https://cdn.jsdelivr.net";

        // Base font sources
        $fontSrc = "'self' https://fonts.gstatic.com https://fonts.bunny.net data:";

        // Base connect sources
        $connectSrc = "'self' wss://{$domain} https://pusher.com https://sockjs.pusher.com";

        // In development, allow Vite dev server
        if ($isLocal) {
            $viteHost = 'http://localhost:5173 ws://localhost:5173 http://127.0.0.1:5173 ws://127.0.0.1:5173';
            $scriptSrc .= " {$viteHost}";
            $styleSrc .= " {$viteHost}";
            $connectSrc .= " {$viteHost}";
        }

        $directives = [
            "default-src 'self'",
            "script-src {$scriptSrc}",
            "style-src {$styleSrc}",
            "font-src {$fontSrc}",
            "img-src 'self' data: https: blob:",
            "media-src 'self' blob:",
            "connect-src {$connectSrc}",
            "frame-src 'self' https://www.youtube.com https://player.vimeo.com",
            "object-src 'none'",
            "base-uri 'self'",
            "form-action 'self'",
            "frame-ancestors 'none'",
        ];

        // Only enforce upgrade-insecure-requests in production
        if (!$isLocal) {
            $directives[] = "upgrade-insecure-requests";
        }

        // Add report-uri in production
        if (app()->environment('production')) {
            $directives[] = "report-uri /api/csp-report";
        }

        return implode('; ', $directives);
    }
}
