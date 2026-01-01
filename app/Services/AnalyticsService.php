<?php

namespace App\Services;

use App\Models\User;
use App\Models\ProfileView;
use App\Models\UserActivity;
use App\Models\EngagementMetric;
use App\Models\Subscription;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use Carbon\Carbon;

class AnalyticsService
{
    /**
     * Track a profile view.
     */
    public function trackProfileView($viewer, $profile, string $source = 'direct', int $duration = 0): ProfileView
    {
        $profileView = ProfileView::create([
            'viewer_id' => $viewer->id,
            'profile_id' => $profile->id,
            'viewed_at' => now(),
            'duration_seconds' => $duration,
            'source' => $source,
        ]);

        // Also track as an activity
        $this->trackActivity($viewer, UserActivity::TYPE_PROFILE_VIEW, [
            'profile_id' => $profile->id,
            'source' => $source,
            'duration_seconds' => $duration,
        ]);

        return $profileView;
    }

    /**
     * Track a user activity.
     */
    public function trackActivity($user, string $type, array $data = []): UserActivity
    {
        $request = request();

        return UserActivity::create([
            'user_id' => $user->id,
            'activity_type' => $type,
            'activity_data' => $data,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'referrer' => $request->header('referer'),
            'device_type' => $this->detectDeviceType($request->userAgent()),
        ]);
    }

    /**
     * Get user engagement metrics for a specified number of days.
     */
    public function getUserEngagementMetrics($user, int $days = 30): array
    {
        $cacheKey = "user_engagement_{$user->id}_{$days}";

        return Cache::remember($cacheKey, 300, function () use ($user, $days) {
            $startDate = now()->subDays($days)->toDateString();
            $endDate = now()->toDateString();

            $metrics = EngagementMetric::forUser($user->id)
                ->inDateRange($startDate, $endDate)
                ->orderBy('date')
                ->get();

            $totals = [
                'total_profile_views' => $metrics->sum('profile_views_count'),
                'total_profile_viewed_by' => $metrics->sum('profile_viewed_by_count'),
                'total_likes_sent' => $metrics->sum('likes_sent_count'),
                'total_likes_received' => $metrics->sum('likes_received_count'),
                'total_messages_sent' => $metrics->sum('messages_sent_count'),
                'total_messages_received' => $metrics->sum('messages_received_count'),
                'total_matches' => $metrics->sum('matches_count'),
                'total_searches' => $metrics->sum('search_count'),
                'total_time_spent_minutes' => round($metrics->sum('time_spent_seconds') / 60),
                'total_logins' => $metrics->sum('login_count'),
                'avg_engagement_score' => round($metrics->avg(function ($metric) {
                    return $metric->getEngagementScore();
                })),
            ];

            // Get unique profile viewers
            $uniqueViewers = ProfileView::forProfile($user->id)
                ->where('viewed_at', '>=', now()->subDays($days))
                ->distinct('viewer_id')
                ->count('viewer_id');

            $totals['unique_profile_viewers'] = $uniqueViewers;

            // Calculate response rate
            $totals['response_rate'] = $this->calculateResponseRate($user, $days);

            // Get daily breakdown
            $dailyMetrics = $metrics->map(function ($metric) {
                return [
                    'date' => $metric->date->format('Y-m-d'),
                    'profile_views' => $metric->profile_views_count,
                    'profile_viewed_by' => $metric->profile_viewed_by_count,
                    'likes_sent' => $metric->likes_sent_count,
                    'likes_received' => $metric->likes_received_count,
                    'messages_sent' => $metric->messages_sent_count,
                    'messages_received' => $metric->messages_received_count,
                    'matches' => $metric->matches_count,
                    'searches' => $metric->search_count,
                    'time_spent_minutes' => round($metric->time_spent_seconds / 60),
                    'engagement_score' => round($metric->getEngagementScore()),
                ];
            });

            return [
                'totals' => $totals,
                'daily_metrics' => $dailyMetrics,
                'period' => [
                    'start_date' => $startDate,
                    'end_date' => $endDate,
                    'days' => $days,
                ],
            ];
        });
    }

    /**
     * Get admin metrics for a date range.
     */
    public function getAdminMetrics($startDate, $endDate): array
    {
        $cacheKey = "admin_metrics_{$startDate}_{$endDate}";

        return Cache::remember($cacheKey, 300, function () use ($startDate, $endDate) {
            // User metrics
            $totalUsers = User::count();
            $newUsers = User::whereBetween('created_at', [$startDate, $endDate])->count();
            $activeUsers = UserActivity::whereBetween('created_at', [$startDate, $endDate])
                ->distinct('user_id')
                ->count('user_id');
            $verifiedUsers = User::whereNotNull('email_verified_at')->count();

            // Engagement metrics
            $totalProfileViews = ProfileView::inDateRange($startDate, $endDate)->count();
            $uniqueProfileViews = ProfileView::inDateRange($startDate, $endDate)
                ->distinct('profile_id')
                ->count('profile_id');

            $totalActivities = UserActivity::inDateRange($startDate, $endDate)->count();

            // Message metrics
            $totalMessages = UserActivity::ofType(UserActivity::TYPE_MESSAGE_SENT)
                ->inDateRange($startDate, $endDate)
                ->count();

            // Match metrics
            $totalMatches = UserActivity::ofType(UserActivity::TYPE_MATCH_CREATED)
                ->inDateRange($startDate, $endDate)
                ->count();

            // Subscription metrics
            $activeSubscriptions = Subscription::where('status', 'active')->count();
            $newSubscriptions = UserActivity::ofType(UserActivity::TYPE_SUBSCRIPTION_PURCHASED)
                ->inDateRange($startDate, $endDate)
                ->count();

            // Revenue metrics
            $revenue = Subscription::where('status', 'active')
                ->whereBetween('created_at', [$startDate, $endDate])
                ->sum('amount');

            // Activity breakdown by type
            $activityBreakdown = UserActivity::inDateRange($startDate, $endDate)
                ->select('activity_type', DB::raw('count(*) as count'))
                ->groupBy('activity_type')
                ->orderByDesc('count')
                ->get()
                ->pluck('count', 'activity_type');

            // Device breakdown
            $deviceBreakdown = UserActivity::inDateRange($startDate, $endDate)
                ->select('device_type', DB::raw('count(*) as count'))
                ->groupBy('device_type')
                ->get()
                ->pluck('count', 'device_type');

            // Daily active users
            $dailyActiveUsers = UserActivity::inDateRange($startDate, $endDate)
                ->select(DB::raw('DATE(created_at) as date'), DB::raw('count(distinct user_id) as count'))
                ->groupBy('date')
                ->orderBy('date')
                ->get();

            return [
                'users' => [
                    'total' => $totalUsers,
                    'new' => $newUsers,
                    'active' => $activeUsers,
                    'verified' => $verifiedUsers,
                    'verification_rate' => $totalUsers > 0 ? round(($verifiedUsers / $totalUsers) * 100, 2) : 0,
                ],
                'engagement' => [
                    'total_profile_views' => $totalProfileViews,
                    'unique_profile_views' => $uniqueProfileViews,
                    'total_activities' => $totalActivities,
                    'total_messages' => $totalMessages,
                    'total_matches' => $totalMatches,
                    'avg_activities_per_user' => $activeUsers > 0 ? round($totalActivities / $activeUsers, 2) : 0,
                ],
                'subscriptions' => [
                    'active' => $activeSubscriptions,
                    'new' => $newSubscriptions,
                    'revenue' => $revenue,
                    'conversion_rate' => $totalUsers > 0 ? round(($activeSubscriptions / $totalUsers) * 100, 2) : 0,
                ],
                'activity_breakdown' => $activityBreakdown,
                'device_breakdown' => $deviceBreakdown,
                'daily_active_users' => $dailyActiveUsers,
                'period' => [
                    'start_date' => $startDate,
                    'end_date' => $endDate,
                ],
            ];
        });
    }

    /**
     * Get daily active users for a specific date.
     */
    public function getDailyActiveUsers($date): int
    {
        $cacheKey = "dau_{$date}";

        return Cache::remember($cacheKey, 3600, function () use ($date) {
            return UserActivity::whereDate('created_at', $date)
                ->distinct('user_id')
                ->count('user_id');
        });
    }

    /**
     * Get conversion funnel data.
     */
    public function getConversionFunnel(): array
    {
        $cacheKey = 'conversion_funnel';

        return Cache::remember($cacheKey, 600, function () {
            $totalSignups = User::count();
            $verifiedUsers = User::whereNotNull('email_verified_at')->count();
            $profileCompleted = User::whereNotNull('bio')
                ->where('bio', '!=', '')
                ->count();
            $sentMessage = UserActivity::ofType(UserActivity::TYPE_MESSAGE_SENT)
                ->distinct('user_id')
                ->count('user_id');
            $gotMatch = UserActivity::ofType(UserActivity::TYPE_MATCH_CREATED)
                ->distinct('user_id')
                ->count('user_id');
            $subscribedUsers = Subscription::where('status', 'active')
                ->distinct('user_id')
                ->count('user_id');

            return [
                [
                    'stage' => 'Signups',
                    'count' => $totalSignups,
                    'percentage' => 100,
                ],
                [
                    'stage' => 'Email Verified',
                    'count' => $verifiedUsers,
                    'percentage' => $totalSignups > 0 ? round(($verifiedUsers / $totalSignups) * 100, 2) : 0,
                ],
                [
                    'stage' => 'Profile Completed',
                    'count' => $profileCompleted,
                    'percentage' => $totalSignups > 0 ? round(($profileCompleted / $totalSignups) * 100, 2) : 0,
                ],
                [
                    'stage' => 'Sent Message',
                    'count' => $sentMessage,
                    'percentage' => $totalSignups > 0 ? round(($sentMessage / $totalSignups) * 100, 2) : 0,
                ],
                [
                    'stage' => 'Got Match',
                    'count' => $gotMatch,
                    'percentage' => $totalSignups > 0 ? round(($gotMatch / $totalSignups) * 100, 2) : 0,
                ],
                [
                    'stage' => 'Subscribed',
                    'count' => $subscribedUsers,
                    'percentage' => $totalSignups > 0 ? round(($subscribedUsers / $totalSignups) * 100, 2) : 0,
                ],
            ];
        });
    }

    /**
     * Get cohort analysis data.
     */
    public function getCohortAnalysis($cohortDate): array
    {
        $cacheKey = "cohort_analysis_{$cohortDate}";

        return Cache::remember($cacheKey, 3600, function () use ($cohortDate) {
            $cohortStartDate = Carbon::parse($cohortDate)->startOfDay();
            $cohortEndDate = Carbon::parse($cohortDate)->endOfDay();

            // Get users who signed up in this cohort
            $cohortUsers = User::whereBetween('created_at', [$cohortStartDate, $cohortEndDate])
                ->pluck('id');

            if ($cohortUsers->isEmpty()) {
                return [
                    'cohort_date' => $cohortDate,
                    'cohort_size' => 0,
                    'retention' => [],
                ];
            }

            $cohortSize = $cohortUsers->count();
            $retention = [];

            // Calculate retention for each week
            for ($week = 0; $week <= 12; $week++) {
                $weekStart = $cohortStartDate->copy()->addWeeks($week)->startOfWeek();
                $weekEnd = $cohortStartDate->copy()->addWeeks($week)->endOfWeek();

                $activeUsers = UserActivity::whereIn('user_id', $cohortUsers)
                    ->whereBetween('created_at', [$weekStart, $weekEnd])
                    ->distinct('user_id')
                    ->count('user_id');

                $retention[] = [
                    'week' => $week,
                    'active_users' => $activeUsers,
                    'retention_rate' => round(($activeUsers / $cohortSize) * 100, 2),
                ];
            }

            return [
                'cohort_date' => $cohortDate,
                'cohort_size' => $cohortSize,
                'retention' => $retention,
            ];
        });
    }

    /**
     * Get profile viewers for a user.
     */
    public function getProfileViewers($user, int $days = 30, int $perPage = 20)
    {
        return ProfileView::forProfile($user->id)
            ->with('viewer')
            ->where('viewed_at', '>=', now()->subDays($days))
            ->orderByDesc('viewed_at')
            ->paginate($perPage);
    }

    /**
     * Get activity sources for a user.
     */
    public function getActivitySources($user, int $days = 30): array
    {
        $sources = ProfileView::forProfile($user->id)
            ->where('viewed_at', '>=', now()->subDays($days))
            ->select('source', DB::raw('count(*) as count'))
            ->groupBy('source')
            ->get()
            ->pluck('count', 'source')
            ->toArray();

        return $sources;
    }

    /**
     * Calculate response rate for a user.
     */
    protected function calculateResponseRate($user, int $days = 30): float
    {
        $messagesReceived = UserActivity::where('user_id', $user->id)
            ->ofType(UserActivity::TYPE_MESSAGE_RECEIVED)
            ->where('created_at', '>=', now()->subDays($days))
            ->count();

        $messagesSent = UserActivity::where('user_id', $user->id)
            ->ofType(UserActivity::TYPE_MESSAGE_SENT)
            ->where('created_at', '>=', now()->subDays($days))
            ->count();

        if ($messagesReceived === 0) {
            return 0;
        }

        return round(($messagesSent / $messagesReceived) * 100, 2);
    }

    /**
     * Detect device type from user agent.
     */
    protected function detectDeviceType(?string $userAgent): string
    {
        if (!$userAgent) {
            return 'unknown';
        }

        if (preg_match('/mobile|android|iphone|ipod|blackberry|windows phone/i', $userAgent)) {
            return 'mobile';
        }

        if (preg_match('/tablet|ipad|playbook|silk/i', $userAgent)) {
            return 'tablet';
        }

        return 'desktop';
    }

    /**
     * Get peak usage hours.
     */
    public function getPeakUsageHours($days = 7): array
    {
        $cacheKey = "peak_usage_hours_{$days}";

        return Cache::remember($cacheKey, 1800, function () use ($days) {
            return UserActivity::where('created_at', '>=', now()->subDays($days))
                ->select(DB::raw('HOUR(created_at) as hour'), DB::raw('count(*) as count'))
                ->groupBy('hour')
                ->orderBy('hour')
                ->get()
                ->pluck('count', 'hour')
                ->toArray();
        });
    }

    /**
     * Get user demographics.
     */
    public function getUserDemographics(): array
    {
        $cacheKey = 'user_demographics';

        return Cache::remember($cacheKey, 1800, function () {
            $genderDistribution = User::select('gender', DB::raw('count(*) as count'))
                ->groupBy('gender')
                ->get()
                ->pluck('count', 'gender')
                ->toArray();

            $religionDistribution = User::select('religion', DB::raw('count(*) as count'))
                ->groupBy('religion')
                ->get()
                ->pluck('count', 'religion')
                ->toArray();

            $ageDistribution = User::select(
                    DB::raw('CASE
                        WHEN YEAR(CURDATE()) - YEAR(date_of_birth) < 25 THEN "18-24"
                        WHEN YEAR(CURDATE()) - YEAR(date_of_birth) BETWEEN 25 AND 30 THEN "25-30"
                        WHEN YEAR(CURDATE()) - YEAR(date_of_birth) BETWEEN 31 AND 35 THEN "31-35"
                        WHEN YEAR(CURDATE()) - YEAR(date_of_birth) BETWEEN 36 AND 40 THEN "36-40"
                        ELSE "40+"
                    END as age_group'),
                    DB::raw('count(*) as count')
                )
                ->whereNotNull('date_of_birth')
                ->groupBy('age_group')
                ->get()
                ->pluck('count', 'age_group')
                ->toArray();

            return [
                'gender' => $genderDistribution,
                'religion' => $religionDistribution,
                'age' => $ageDistribution,
            ];
        });
    }

    /**
     * Clear analytics cache.
     */
    public function clearCache(): void
    {
        Cache::flush();
    }
}
