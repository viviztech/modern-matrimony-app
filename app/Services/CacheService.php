<?php

namespace App\Services;

use App\Models\User;
use App\Models\SubscriptionPlan;
use App\Models\Preference;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CacheService
{
    // Cache TTL constants (in seconds)
    const USER_PROFILE_TTL = 3600; // 1 hour
    const SUBSCRIPTION_PLANS_TTL = 86400; // 24 hours
    const USER_PREFERENCES_TTL = 3600; // 1 hour
    const MATCH_SUGGESTIONS_TTL = 900; // 15 minutes
    const SEARCH_RESULTS_TTL = 300; // 5 minutes
    const ONLINE_USERS_TTL = 60; // 1 minute
    const POPULAR_PROFILES_TTL = 3600; // 1 hour
    const USER_STATS_TTL = 1800; // 30 minutes

    /**
     * Cache user profile data
     */
    public function cacheUserProfile(int $userId): ?array
    {
        $cacheKey = "user.profile.{$userId}";

        return Cache::remember($cacheKey, self::USER_PROFILE_TTL, function () use ($userId) {
            $user = User::with([
                'profile',
                'photos' => function ($query) {
                    $query->where('is_verified', true)
                          ->orderBy('is_primary', 'desc')
                          ->orderBy('created_at', 'desc');
                },
                'subscription'
            ])->find($userId);

            if (!$user) {
                return null;
            }

            return [
                'user' => $user->toArray(),
                'cached_at' => now()->toIso8601String(),
            ];
        });
    }

    /**
     * Cache subscription plans
     */
    public function cacheSubscriptionPlans(): array
    {
        $cacheKey = 'subscription.plans.all';

        return Cache::remember($cacheKey, self::SUBSCRIPTION_PLANS_TTL, function () {
            return SubscriptionPlan::where('is_active', true)
                ->orderBy('price')
                ->get()
                ->toArray();
        });
    }

    /**
     * Cache user preferences
     */
    public function cacheUserPreferences(int $userId): ?array
    {
        $cacheKey = "user.preferences.{$userId}";

        return Cache::remember($cacheKey, self::USER_PREFERENCES_TTL, function () use ($userId) {
            $preference = Preference::where('user_id', $userId)->first();

            return $preference ? $preference->toArray() : null;
        });
    }

    /**
     * Cache match suggestions for a user
     */
    public function cacheMatchSuggestions(int $userId, int $limit = 20): array
    {
        $cacheKey = "user.matches.suggestions.{$userId}.{$limit}";

        return Cache::remember($cacheKey, self::MATCH_SUGGESTIONS_TTL, function () use ($userId, $limit) {
            $user = User::with('profile', 'preference')->find($userId);

            if (!$user || !$user->profile || !$user->preference) {
                return [];
            }

            $preference = $user->preference;
            $oppositeGender = $user->gender === 'male' ? 'female' : 'male';

            $query = User::query()
                ->where('id', '!=', $userId)
                ->where('gender', $oppositeGender)
                ->where('is_active', true)
                ->whereHas('profile', function ($q) use ($preference) {
                    if ($preference->min_age && $preference->max_age) {
                        $q->whereBetween('age', [$preference->min_age, $preference->max_age]);
                    }

                    if ($preference->religion) {
                        $q->where('religion', $preference->religion);
                    }

                    if ($preference->min_height && $preference->max_height) {
                        $q->whereBetween('height', [$preference->min_height, $preference->max_height]);
                    }
                })
                ->with(['profile', 'photos' => function ($q) {
                    $q->where('is_verified', true)
                      ->where('is_primary', true);
                }])
                ->limit($limit)
                ->get();

            return $query->toArray();
        });
    }

    /**
     * Cache search results
     */
    public function cacheSearchResults(string $searchKey, array $filters, $callback): array
    {
        $cacheKey = "search.results." . md5(json_encode($filters));

        return Cache::remember($cacheKey, self::SEARCH_RESULTS_TTL, $callback);
    }

    /**
     * Cache online users count
     */
    public function cacheOnlineUsersCount(): int
    {
        $cacheKey = 'stats.online.users.count';

        return Cache::remember($cacheKey, self::ONLINE_USERS_TTL, function () {
            return User::where('is_active', true)
                ->where('last_active_at', '>=', now()->subMinutes(15))
                ->count();
        });
    }

    /**
     * Cache popular profiles (most viewed/liked)
     */
    public function cachePopularProfiles(int $limit = 10): array
    {
        $cacheKey = "profiles.popular.{$limit}";

        return Cache::remember($cacheKey, self::POPULAR_PROFILES_TTL, function () use ($limit) {
            return User::query()
                ->where('is_active', true)
                ->withCount(['profileViews', 'receivedLikes'])
                ->orderByDesc('profile_views_count')
                ->orderByDesc('received_likes_count')
                ->with(['profile', 'photos' => function ($q) {
                    $q->where('is_verified', true)
                      ->where('is_primary', true);
                }])
                ->limit($limit)
                ->get()
                ->toArray();
        });
    }

    /**
     * Cache user statistics
     */
    public function cacheUserStats(int $userId): array
    {
        $cacheKey = "user.stats.{$userId}";

        return Cache::remember($cacheKey, self::USER_STATS_TTL, function () use ($userId) {
            return [
                'profile_views' => DB::table('profile_views')
                    ->where('viewed_user_id', $userId)
                    ->count(),
                'received_likes' => DB::table('likes')
                    ->where('liked_user_id', $userId)
                    ->count(),
                'sent_likes' => DB::table('likes')
                    ->where('user_id', $userId)
                    ->count(),
                'matches' => DB::table('user_matches')
                    ->where(function ($query) use ($userId) {
                        $query->where('user_id', $userId)
                              ->orWhere('matched_user_id', $userId);
                    })
                    ->where('is_active', true)
                    ->count(),
                'messages_sent' => DB::table('messages')
                    ->where('sender_id', $userId)
                    ->count(),
            ];
        });
    }

    /**
     * Invalidate user-related cache
     */
    public function invalidateUserCache(int $userId): void
    {
        $keys = [
            "user.profile.{$userId}",
            "user.preferences.{$userId}",
            "user.stats.{$userId}",
        ];

        foreach ($keys as $key) {
            Cache::forget($key);
        }

        // Also invalidate match suggestions that might include this user
        $this->invalidateMatchSuggestionsCache();

        Log::info("Cache invalidated for user {$userId}");
    }

    /**
     * Invalidate match suggestions cache
     */
    public function invalidateMatchSuggestionsCache(): void
    {
        Cache::tags(['match_suggestions'])->flush();
    }

    /**
     * Invalidate search results cache
     */
    public function invalidateSearchCache(): void
    {
        // Clear all search result caches
        $keys = Cache::get('search.cache.keys', []);
        foreach ($keys as $key) {
            Cache::forget($key);
        }
        Cache::forget('search.cache.keys');
    }

    /**
     * Warm up cache with popular data
     */
    public function warmUpCache(): array
    {
        $warmedUp = [];

        try {
            // Cache subscription plans
            $this->cacheSubscriptionPlans();
            $warmedUp[] = 'subscription_plans';

            // Cache popular profiles
            $this->cachePopularProfiles();
            $warmedUp[] = 'popular_profiles';

            // Cache online users count
            $this->cacheOnlineUsersCount();
            $warmedUp[] = 'online_users_count';

            // Cache most active users' profiles
            $activeUsers = User::where('is_active', true)
                ->where('last_active_at', '>=', now()->subDays(7))
                ->orderByDesc('last_active_at')
                ->limit(50)
                ->pluck('id');

            foreach ($activeUsers as $userId) {
                $this->cacheUserProfile($userId);
                $this->cacheUserPreferences($userId);
            }
            $warmedUp[] = "active_users_profiles ({$activeUsers->count()} users)";

            Log::info('Cache warmed up successfully', ['items' => $warmedUp]);

            return $warmedUp;
        } catch (\Exception $e) {
            Log::error('Cache warm up failed', [
                'error' => $e->getMessage(),
                'warmed_up' => $warmedUp,
            ]);

            return $warmedUp;
        }
    }

    /**
     * Clear all application cache
     */
    public function clearAllCache(): void
    {
        Cache::flush();
        Log::info('All cache cleared');
    }

    /**
     * Get cache statistics
     */
    public function getCacheStats(): array
    {
        return [
            'driver' => config('cache.default'),
            'prefix' => config('cache.prefix'),
            'online_users' => $this->cacheOnlineUsersCount(),
            'timestamp' => now()->toIso8601String(),
        ];
    }

    /**
     * Remember with tags (for better cache invalidation)
     */
    public function rememberWithTags(array $tags, string $key, int $ttl, callable $callback)
    {
        return Cache::tags($tags)->remember($key, $ttl, $callback);
    }

    /**
     * Invalidate cache by tags
     */
    public function invalidateTags(array $tags): void
    {
        Cache::tags($tags)->flush();
    }
}
