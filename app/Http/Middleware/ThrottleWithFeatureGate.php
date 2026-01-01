<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Symfony\Component\HttpFoundation\Response;

class ThrottleWithFeatureGate
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string $feature, int $freeLimit, int $premiumLimit = null): Response
    {
        $user = $request->user();

        // Determine the limit based on user subscription
        $limit = $freeLimit;
        $isPremium = false;

        if ($user && $user->isPremium()) {
            $limit = $premiumLimit ?? PHP_INT_MAX; // Unlimited if premium limit not specified
            $isPremium = true;
        }

        // Create a unique key for this user/IP and feature
        $key = $this->resolveRequestSignature($request, $feature);

        // Check if rate limit exceeded
        if (RateLimiter::tooManyAttempts($key, $limit)) {
            $retryAfter = RateLimiter::availableIn($key);

            return response()->json([
                'message' => $isPremium
                    ? 'Too many requests. Please try again later.'
                    : 'Free tier limit reached. Upgrade to premium for unlimited access.',
                'retry_after' => $retryAfter,
                'upgrade_available' => !$isPremium,
            ], 429);
        }

        // Increment the counter
        RateLimiter::hit($key, 60); // 60 seconds decay

        $response = $next($request);

        // Add rate limit headers
        $response->headers->set('X-RateLimit-Limit', $limit);
        $response->headers->set('X-RateLimit-Remaining', max(0, $limit - RateLimiter::attempts($key)));

        return $response;
    }

    /**
     * Resolve the request signature
     */
    protected function resolveRequestSignature(Request $request, string $feature): string
    {
        if ($user = $request->user()) {
            return sha1($feature . '|' . $user->id);
        }

        return sha1($feature . '|' . $request->ip());
    }
}
