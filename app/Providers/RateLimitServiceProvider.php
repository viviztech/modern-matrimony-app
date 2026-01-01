<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;

class RateLimitServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // Login attempts: 5 per minute
        RateLimiter::for('login', function (Request $request) {
            return Limit::perMinute(config('security.rate_limits.login', 5))
                ->by($request->ip())
                ->response(function () {
                    return response()->json([
                        'message' => 'Too many login attempts. Please try again later.',
                    ], 429);
                });
        });

        // Registration: 3 per hour per IP
        RateLimiter::for('registration', function (Request $request) {
            return Limit::perHour(config('security.rate_limits.registration', 3))
                ->by($request->ip())
                ->response(function () {
                    return response()->json([
                        'message' => 'Too many registration attempts. Please try again later.',
                    ], 429);
                });
        });

        // Password reset: 3 per hour
        RateLimiter::for('password-reset', function (Request $request) {
            return Limit::perHour(config('security.rate_limits.password_reset', 3))
                ->by($request->input('email') ?? $request->ip())
                ->response(function () {
                    return response()->json([
                        'message' => 'Too many password reset attempts. Please try again later.',
                    ], 429);
                });
        });

        // Message sending: 60 per hour for free, unlimited for premium
        RateLimiter::for('messages', function (Request $request) {
            $user = $request->user();

            if ($user && $user->isPremium()) {
                return Limit::none();
            }

            return Limit::perHour(config('security.rate_limits.messages', 60))
                ->by($user ? $user->id : $request->ip())
                ->response(function () {
                    return response()->json([
                        'message' => 'Free tier message limit reached. Upgrade to premium for unlimited messaging.',
                        'upgrade_available' => true,
                    ], 429);
                });
        });

        // Like/swipe: 100 per hour for free, unlimited for premium
        RateLimiter::for('likes', function (Request $request) {
            $user = $request->user();

            if ($user && $user->isPremium()) {
                return Limit::none();
            }

            return Limit::perHour(config('security.rate_limits.likes', 100))
                ->by($user ? $user->id : $request->ip())
                ->response(function () {
                    return response()->json([
                        'message' => 'Daily like limit reached. Upgrade to premium for unlimited likes.',
                        'upgrade_available' => true,
                    ], 429);
                });
        });

        // Search queries: 20 per minute
        RateLimiter::for('search', function (Request $request) {
            $user = $request->user();

            $limit = $user && $user->isPremium()
                ? config('security.rate_limits.search_premium', 100)
                : config('security.rate_limits.search', 20);

            return Limit::perMinute($limit)
                ->by($user ? $user->id : $request->ip())
                ->response(function () use ($user) {
                    return response()->json([
                        'message' => 'Search limit reached. Please wait before searching again.',
                        'upgrade_available' => !($user && $user->isPremium()),
                    ], 429);
                });
        });

        // Profile updates: 10 per hour
        RateLimiter::for('profile-updates', function (Request $request) {
            return Limit::perHour(config('security.rate_limits.profile_updates', 10))
                ->by($request->user()?->id ?? $request->ip())
                ->response(function () {
                    return response()->json([
                        'message' => 'Too many profile updates. Please try again later.',
                    ], 429);
                });
        });

        // Photo uploads: 10 per day
        RateLimiter::for('photo-uploads', function (Request $request) {
            $user = $request->user();

            $limit = $user && $user->isPremium()
                ? config('security.rate_limits.photo_uploads_premium', 50)
                : config('security.rate_limits.photo_uploads', 10);

            return Limit::perDay($limit)
                ->by($user ? $user->id : $request->ip())
                ->response(function () use ($user) {
                    return response()->json([
                        'message' => 'Daily photo upload limit reached.',
                        'upgrade_available' => !($user && $user->isPremium()),
                    ], 429);
                });
        });

        // API rate limiting: 60 per minute for authenticated users
        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(60)
                ->by($request->user()?->id ?? $request->ip())
                ->response(function () {
                    return response()->json([
                        'message' => 'Too many API requests.',
                    ], 429);
                });
        });

        // Global rate limit: 1000 requests per minute per IP
        RateLimiter::for('global', function (Request $request) {
            return Limit::perMinute(1000)->by($request->ip());
        });

        // Admin actions: 100 per minute
        RateLimiter::for('admin', function (Request $request) {
            return Limit::perMinute(100)
                ->by($request->user()?->id ?? $request->ip());
        });

        // Video calls: 10 per day for free, unlimited for premium
        RateLimiter::for('video-calls', function (Request $request) {
            $user = $request->user();

            if ($user && $user->isPremium()) {
                return Limit::none();
            }

            return Limit::perDay(config('security.rate_limits.video_calls', 10))
                ->by($user ? $user->id : $request->ip())
                ->response(function () {
                    return response()->json([
                        'message' => 'Daily video call limit reached. Upgrade to premium for unlimited calls.',
                        'upgrade_available' => true,
                    ], 429);
                });
        });

        // Report abuse: 5 per day
        RateLimiter::for('reports', function (Request $request) {
            return Limit::perDay(config('security.rate_limits.reports', 5))
                ->by($request->user()?->id ?? $request->ip())
                ->response(function () {
                    return response()->json([
                        'message' => 'Too many reports submitted today.',
                    ], 429);
                });
        });
    }
}
