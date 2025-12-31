<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Symfony\Component\HttpFoundation\Response;

class UpdateUserOnlineStatus
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check()) {
            $user = Auth::user();
            $expiresAt = now()->addMinutes(5);

            // Store online status in cache with 5-minute expiry
            Cache::put('user-online-' . $user->id, true, $expiresAt);

            // Update last_active_at timestamp in database (throttled to once per minute)
            $lastUpdate = Cache::get('user-last-activity-update-' . $user->id);

            if (!$lastUpdate || $lastUpdate->diffInMinutes(now()) >= 1) {
                $user->updateLastActive();
                Cache::put('user-last-activity-update-' . $user->id, now(), now()->addMinutes(2));
            }
        }

        return $next($request);
    }
}
