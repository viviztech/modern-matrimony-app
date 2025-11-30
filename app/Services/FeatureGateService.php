<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Cache;

class FeatureGateService
{
    /**
     * Check if user can swipe more today.
     */
    public function canSwipeMore(User $user): bool
    {
        $plan = $user->currentPlan();
        $limit = $plan->getFeature('swipes_per_day', 0);

        // Unlimited swipes
        if ($limit === -1 || $plan->hasFeature('unlimited_swipes')) {
            return true;
        }

        // Check current usage
        $used = $this->getUsageCount($user->id, 'swipes', 'daily');

        return $used < $limit;
    }

    /**
     * Check if user can like more today.
     */
    public function canLike(User $user): bool
    {
        $plan = $user->currentPlan();
        $limit = $plan->getFeature('likes_per_day', 0);

        // Unlimited likes
        if ($limit === -1 || $plan->hasFeature('unlimited_likes')) {
            return true;
        }

        $used = $this->getUsageCount($user->id, 'likes', 'daily');

        return $used < $limit;
    }

    /**
     * Check if user can make video call this week.
     */
    public function canVideoCall(User $user): bool
    {
        $plan = $user->currentPlan();
        $limit = $plan->getFeature('video_calls_per_week', 0);

        // Unlimited video calls
        if ($limit === -1 || $plan->hasFeature('unlimited_video_calls')) {
            return true;
        }

        $used = $this->getUsageCount($user->id, 'video_calls', 'weekly');

        return $used < $limit;
    }

    /**
     * Check if user can use super like this week.
     */
    public function canUseSuperLike(User $user): bool
    {
        $plan = $user->currentPlan();
        $limit = $plan->getFeature('super_likes_per_week', 0);

        // Unlimited super likes (Elite plan)
        if ($limit === -1) {
            return true;
        }

        // No super likes available
        if ($limit === 0) {
            return false;
        }

        $used = $this->getUsageCount($user->id, 'super_likes', 'weekly');

        return $used < $limit;
    }

    /**
     * Check if user can rewind (undo swipe).
     */
    public function canRewind(User $user): bool
    {
        return $user->canAccessFeature('rewind_feature');
    }

    /**
     * Check if user can see who liked them.
     */
    public function canSeeWhoLiked(User $user): bool
    {
        return $user->canAccessFeature('see_who_liked');
    }

    /**
     * Check if user can use incognito mode.
     */
    public function canUseIncognito(User $user): bool
    {
        return $user->canAccessFeature('incognito_mode');
    }

    /**
     * Check if user can use advanced filters.
     */
    public function canUseAdvancedFilters(User $user): bool
    {
        return $user->canAccessFeature('advanced_filters');
    }

    /**
     * Check if user can use read receipts.
     */
    public function canUseReadReceipts(User $user): bool
    {
        return $user->canAccessFeature('read_receipts');
    }

    /**
     * Check if user can message before matching.
     */
    public function canMessageBeforeMatch(User $user): bool
    {
        return $user->canAccessFeature('message_before_matching');
    }

    /**
     * Check if user can use profile boost this month.
     */
    public function canUseProfileBoost(User $user): bool
    {
        $plan = $user->currentPlan();
        $limit = $plan->getFeature('profile_boost_per_month', 0);

        if ($limit === 0) {
            return false;
        }

        $used = $this->getUsageCount($user->id, 'profile_boosts', 'monthly');

        return $used < $limit;
    }

    /**
     * Increment usage count for a feature.
     */
    public function incrementUsage(int $userId, string $feature, string $period = 'daily'): void
    {
        $key = $this->getUsageKey($userId, $feature, $period);
        $ttl = $this->getTTL($period);

        Cache::increment($key);
        Cache::expire($key, $ttl);
    }

    /**
     * Get usage count for a feature.
     */
    public function getUsageCount(int $userId, string $feature, string $period = 'daily'): int
    {
        $key = $this->getUsageKey($userId, $feature, $period);
        return (int) Cache::get($key, 0);
    }

    /**
     * Get remaining count for a feature.
     */
    public function getRemainingCount(User $user, string $feature, string $period = 'daily'): int
    {
        $plan = $user->currentPlan();
        $limitKey = $feature . '_per_' . rtrim($period, 'ly');
        $limit = $plan->getFeature($limitKey, 0);

        // Unlimited
        if ($limit === -1) {
            return -1;
        }

        $used = $this->getUsageCount($user->id, $feature, $period);
        $remaining = max(0, $limit - $used);

        return $remaining;
    }

    /**
     * Reset usage for a user.
     */
    public function resetUsage(int $userId, string $feature, string $period = 'daily'): void
    {
        $key = $this->getUsageKey($userId, $feature, $period);
        Cache::forget($key);
    }

    /**
     * Get cache key for usage tracking.
     */
    protected function getUsageKey(int $userId, string $feature, string $period): string
    {
        $date = match ($period) {
            'daily' => now()->format('Y-m-d'),
            'weekly' => now()->startOfWeek()->format('Y-W'),
            'monthly' => now()->format('Y-m'),
            default => now()->format('Y-m-d'),
        };

        return "usage:{$userId}:{$feature}:{$period}:{$date}";
    }

    /**
     * Get TTL for cache period.
     */
    protected function getTTL(string $period): int
    {
        return match ($period) {
            'daily' => 86400, // 24 hours
            'weekly' => 604800, // 7 days
            'monthly' => 2592000, // 30 days
            default => 86400,
        };
    }

    /**
     * Get feature limit message for upgrade prompt.
     */
    public function getLimitMessage(string $feature): string
    {
        return match ($feature) {
            'swipes' => "You've reached your daily swipe limit. Upgrade to Gold for unlimited swipes!",
            'likes' => "You've reached your daily like limit. Upgrade to Gold for unlimited likes!",
            'video_calls' => "You've used all your video calls for this week. Upgrade to Gold for unlimited calls!",
            'super_likes' => "You've used all your super likes. Upgrade to Gold for 5 super likes per week!",
            'rewind' => "Rewind feature is available for Gold members and above. Upgrade now!",
            'see_who_liked' => "See who liked you with Gold membership. Upgrade now!",
            'incognito_mode' => "Incognito mode is available for Platinum members. Upgrade now!",
            'advanced_filters' => "Advanced filters are available for Gold members and above. Upgrade now!",
            'read_receipts' => "Read receipts are available for Gold members. Upgrade now!",
            'profile_boost' => "You've used your profile boost for this month. Upgrade to Elite for weekly boosts!",
            default => "This feature requires a premium membership. Upgrade now!",
        };
    }

    /**
     * Get recommended plan for a feature.
     */
    public function getRecommendedPlan(string $feature): string
    {
        return match ($feature) {
            'swipes', 'likes', 'video_calls', 'super_likes', 'rewind', 'see_who_liked', 'advanced_filters', 'read_receipts' => 'gold',
            'incognito_mode', 'profile_boost', 'message_before_matching' => 'platinum',
            'relationship_manager', 'coaching_session' => 'elite',
            default => 'gold',
        };
    }
}
