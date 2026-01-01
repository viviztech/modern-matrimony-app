<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Services\AnalyticsService;
use App\Models\UserActivity;
use App\Models\EngagementMetric;
use Symfony\Component\HttpFoundation\Response;

class TrackUserActivity
{
    protected $analyticsService;

    public function __construct(AnalyticsService $analyticsService)
    {
        $this->analyticsService = $analyticsService;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Store request start time for duration calculation
        $request->attributes->set('start_time', microtime(true));

        $response = $next($request);

        // Track activity after response is generated (for authenticated users only)
        if (Auth::check()) {
            $this->trackPageView($request);
        }

        return $response;
    }

    /**
     * Track page view activity.
     */
    protected function trackPageView(Request $request): void
    {
        $user = Auth::user();

        // Skip tracking for certain routes
        if ($this->shouldSkipTracking($request)) {
            return;
        }

        // Calculate time spent
        $startTime = $request->attributes->get('start_time');
        $duration = $startTime ? round((microtime(true) - $startTime) * 1000) : 0;

        // Get activity type based on route
        $activityType = $this->determineActivityType($request);

        // Track the activity
        if ($activityType) {
            $this->analyticsService->trackActivity($user, $activityType, [
                'url' => $request->fullUrl(),
                'method' => $request->method(),
                'duration_ms' => $duration,
                'route' => $request->route() ? $request->route()->getName() : null,
            ]);
        }

        // Update time spent in engagement metrics
        $this->updateTimeSpent($user, $duration);
    }

    /**
     * Determine if tracking should be skipped for this request.
     */
    protected function shouldSkipTracking(Request $request): bool
    {
        $skipRoutes = [
            'analytics.*',
            'livewire.*',
            'api.*',
            '_ignition.*',
        ];

        $currentRoute = $request->route() ? $request->route()->getName() : '';

        foreach ($skipRoutes as $pattern) {
            if (fnmatch($pattern, $currentRoute)) {
                return true;
            }
        }

        // Skip AJAX requests (except important ones)
        if ($request->ajax() && !$this->isImportantAjax($request)) {
            return true;
        }

        return false;
    }

    /**
     * Check if this is an important AJAX request that should be tracked.
     */
    protected function isImportantAjax(Request $request): bool
    {
        $importantPatterns = [
            '/messages',
            '/likes',
            '/matches',
            '/discover',
        ];

        foreach ($importantPatterns as $pattern) {
            if (str_contains($request->path(), $pattern)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Determine activity type based on request.
     */
    protected function determineActivityType(Request $request): ?string
    {
        $path = $request->path();
        $method = $request->method();

        // Profile views
        if (preg_match('/^profile\/(\d+)$/', $path)) {
            return UserActivity::TYPE_PROFILE_VIEW;
        }

        // Search
        if (str_contains($path, 'search') && $method === 'GET') {
            return UserActivity::TYPE_SEARCH_PERFORMED;
        }

        // Messages
        if (str_contains($path, 'messages') && $method === 'POST') {
            return UserActivity::TYPE_MESSAGE_SENT;
        }

        // Likes
        if (str_contains($path, 'like') && $method === 'POST') {
            return UserActivity::TYPE_LIKE_SENT;
        }

        // Profile edit
        if (str_contains($path, 'profile') && $method === 'PUT') {
            return UserActivity::TYPE_PROFILE_EDIT;
        }

        // Photo upload
        if (str_contains($path, 'photo') && $method === 'POST') {
            return UserActivity::TYPE_PHOTO_UPLOAD;
        }

        // Stories
        if (str_contains($path, 'stories') && $method === 'POST') {
            return UserActivity::TYPE_STORY_POSTED;
        }

        // Games
        if (str_contains($path, 'games') && $method === 'POST') {
            return UserActivity::TYPE_GAME_PLAYED;
        }

        return null;
    }

    /**
     * Update time spent in engagement metrics.
     */
    protected function updateTimeSpent($user, int $durationMs): void
    {
        if ($durationMs <= 0) {
            return;
        }

        // Convert milliseconds to seconds
        $durationSeconds = round($durationMs / 1000);

        // Only track if duration is reasonable (less than 30 minutes)
        if ($durationSeconds > 1800) {
            return;
        }

        $today = now()->toDateString();

        try {
            $metric = EngagementMetric::getOrCreateForUserAndDate($user->id, $today);
            $metric->incrementMetric('time_spent_seconds', $durationSeconds);
        } catch (\Exception $e) {
            // Silently fail to prevent tracking errors from affecting user experience
            \Log::error('Failed to update time spent metric', [
                'user_id' => $user->id,
                'error' => $e->getMessage(),
            ]);
        }
    }
}
