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

        $directives = [
            "default-src 'self'",
            "script-src 'self' 'unsafe-inline' 'unsafe-eval' https://cdn.jsdelivr.net https://js.pusher.com https://unpkg.com https://cdn.tailwindcss.com",
            "style-src 'self' 'unsafe-inline' https://fonts.googleapis.com https://cdn.jsdelivr.net",
            "font-src 'self' https://fonts.gstatic.com data:",
            "img-src 'self' data: https: blob:",
            "media-src 'self' blob:",
            "connect-src 'self' wss://{$domain} https://pusher.com https://sockjs.pusher.com",
            "frame-src 'self' https://www.youtube.com https://player.vimeo.com",
            "object-src 'none'",
            "base-uri 'self'",
            "form-action 'self'",
            "frame-ancestors 'none'",
            "upgrade-insecure-requests",
        ];

        // Add report-uri in production
        if (app()->environment('production')) {
            $directives[] = "report-uri /api/csp-report";
        }

        return implode('; ', $directives);
    }
}
