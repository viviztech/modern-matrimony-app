<?php

namespace App\Services;

use App\Models\User;
use App\Models\UserMatch;
use App\Models\Like;
use App\Models\Preference;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class MatchingService
{
    /**
     * Calculate compatibility score between two users.
     */
    public function calculateCompatibilityScore(User $user1, User $user2): float
    {
        $score = 0;
        $maxScore = 100;
        $weights = [
            'religion' => 15,
            'caste' => 10,
            'mother_tongue' => 10,
            'education' => 10,
            'diet' => 5,
            'smoking' => 5,
            'drinking' => 5,
            'location' => 15,
            'age' => 10,
            'height' => 5,
            'income' => 10,
        ];

        $profile1 = $user1->profile;
        $profile2 = $user2->profile;

        if (!$profile1 || !$profile2) {
            return 0;
        }

        // Religion match
        if ($profile1->religion === $profile2->religion) {
            $score += $weights['religion'];
        }

        // Caste match
        if ($profile1->caste === $profile2->caste) {
            $score += $weights['caste'];
        }

        // Mother tongue match
        if ($profile1->mother_tongue === $profile2->mother_tongue) {
            $score += $weights['mother_tongue'];
        }

        // Education level compatibility
        $educationLevels = ['high_school', 'bachelors', 'masters', 'phd'];
        $edu1Index = array_search($profile1->education_level, $educationLevels);
        $edu2Index = array_search($profile2->education_level, $educationLevels);

        if ($edu1Index !== false && $edu2Index !== false) {
            $eduDiff = abs($edu1Index - $edu2Index);
            $score += max(0, $weights['education'] - ($eduDiff * 3));
        }

        // Diet preferences match
        if ($profile1->diet === $profile2->diet) {
            $score += $weights['diet'];
        }

        // Smoking compatibility
        if ($profile1->smoking === $profile2->smoking) {
            $score += $weights['smoking'];
        }

        // Drinking compatibility
        if ($profile1->drinking === $profile2->drinking) {
            $score += $weights['drinking'];
        }

        // Location proximity (same city = full, same state = partial)
        if ($user1->city === $user2->city) {
            $score += $weights['location'];
        } elseif ($user1->state === $user2->state) {
            $score += $weights['location'] * 0.5;
        } elseif ($user1->country === $user2->country) {
            $score += $weights['location'] * 0.3;
        }

        // Age compatibility (within preference range)
        $pref1 = $user1->preference;
        $pref2 = $user2->preference;
        $age1 = $user1->dob ? $user1->dob->age : 0;
        $age2 = $user2->dob ? $user2->dob->age : 0;

        if ($pref1 && $age2 >= $pref1->age_min && $age2 <= $pref1->age_max) {
            $score += $weights['age'] * 0.5;
        }
        if ($pref2 && $age1 >= $pref2->age_min && $age1 <= $pref2->age_max) {
            $score += $weights['age'] * 0.5;
        }

        // Height compatibility
        if ($pref1 && $profile2->height >= $pref1->height_min && $profile2->height <= $pref1->height_max) {
            $score += $weights['height'] * 0.5;
        }
        if ($pref2 && $profile1->height >= $pref2->height_min && $profile1->height <= $pref2->height_max) {
            $score += $weights['height'] * 0.5;
        }

        // Income compatibility
        if ($profile1->annual_income && $profile2->annual_income) {
            $incomeDiff = abs($profile1->annual_income - $profile2->annual_income);
            $maxIncome = max($profile1->annual_income, $profile2->annual_income);

            if ($maxIncome > 0) {
                $incomeScore = max(0, $weights['income'] * (1 - ($incomeDiff / $maxIncome)));
                $score += $incomeScore;
            }
        }

        return round(($score / $maxScore) * 100, 2);
    }

    /**
     * Get match suggestions for a user.
     */
    public function getMatchSuggestions(User $user, int $limit = 20)
    {
        $cacheKey = "match_suggestions_{$user->id}";

        return Cache::remember($cacheKey, now()->addMinutes(15), function () use ($user, $limit) {
            $preference = $user->preference;

            if (!$preference) {
                return collect();
            }

            // Get already liked/matched user IDs
            $excludeIds = $this->getExcludedUserIds($user);

            // Build query based on preferences
            $query = User::with(['profile', 'photos'])
                ->where('id', '!=', $user->id)
                ->whereNotIn('id', $excludeIds)
                ->where('gender', $preference->looking_for_gender)
                ->where('is_active', true)
                ->whereNull('banned_at')
                ->whereNull('suspended_until')
                ->whereHas('profile', function ($q) use ($preference) {
                    // Age filter
                    if ($preference->age_min && $preference->age_max) {
                        $minDate = now()->subYears($preference->age_max)->startOfYear();
                        $maxDate = now()->subYears($preference->age_min)->endOfYear();
                        $q->whereBetween('dob', [$minDate, $maxDate]);
                    }

                    // Religion filter
                    if ($preference->religions && count($preference->religions) > 0) {
                        $q->whereIn('religion', $preference->religions);
                    }

                    // Height filter
                    if ($preference->height_min) {
                        $q->where('height', '>=', $preference->height_min);
                    }
                    if ($preference->height_max) {
                        $q->where('height', '<=', $preference->height_max);
                    }

                    // Marital status filter
                    if ($preference->marital_statuses && count($preference->marital_statuses) > 0) {
                        $q->whereIn('marital_status', $preference->marital_statuses);
                    }
                });

            // Location filter
            if ($preference->locations && count($preference->locations) > 0) {
                $query->where(function ($q) use ($preference) {
                    foreach ($preference->locations as $location) {
                        $q->orWhere('city', $location)
                          ->orWhere('state', $location)
                          ->orWhere('country', $location);
                    }
                });
            }

            $potentialMatches = $query->limit($limit * 2)->get();

            // Calculate compatibility scores and sort
            $scoredMatches = $potentialMatches->map(function ($potentialMatch) use ($user) {
                $potentialMatch->compatibility_score = $this->calculateCompatibilityScore($user, $potentialMatch);
                return $potentialMatch;
            })->sortByDesc('compatibility_score')->take($limit);

            return $scoredMatches->values();
        });
    }

    /**
     * Get user IDs to exclude from suggestions.
     */
    protected function getExcludedUserIds(User $user): array
    {
        // Use a single query with UNION to get all excluded IDs efficiently
        $likedIds = Like::where('user_id', $user->id)->pluck('liked_user_id');
        
        // Get matched user IDs in a single query
        $matchedIds = UserMatch::where(function ($q) use ($user) {
                $q->where('user_id', $user->id)
                  ->orWhere('matched_user_id', $user->id);
            })->get()->map(function ($match) use ($user) {
                return $match->user_id === $user->id ? $match->matched_user_id : $match->user_id;
            });

        return array_unique(array_merge($likedIds->toArray(), $matchedIds->toArray()));
    }

    /**
     * Create a match between two users.
     */
    public function createMatch(User $user1, User $user2): UserMatch
    {
        $compatibilityScore = $this->calculateCompatibilityScore($user1, $user2);

        $match = UserMatch::create([
            'user_id' => $user1->id,
            'matched_user_id' => $user2->id,
            'matched_at' => now(),
            'is_active' => true,
            'compatibility_score' => $compatibilityScore,
        ]);

        // Clear cache
        Cache::forget("match_suggestions_{$user1->id}");
        Cache::forget("match_suggestions_{$user2->id}");

        // Fire match created event
        event(new \App\Events\MatchCreated($match));

        Log::info('Match created', [
            'match_id' => $match->id,
            'user1' => $user1->id,
            'user2' => $user2->id,
            'score' => $compatibilityScore,
        ]);

        return $match;
    }

    /**
     * Check if users can match (mutual like).
     */
    public function canCreateMatch(User $user1, User $user2): bool
    {
        $like1 = Like::where('user_id', $user1->id)
            ->where('liked_user_id', $user2->id)
            ->exists();

        $like2 = Like::where('user_id', $user2->id)
            ->where('liked_user_id', $user1->id)
            ->exists();

        $existingMatch = UserMatch::where(function ($q) use ($user1, $user2) {
            $q->where('user_id', $user1->id)->where('matched_user_id', $user2->id);
        })->orWhere(function ($q) use ($user1, $user2) {
            $q->where('user_id', $user2->id)->where('matched_user_id', $user1->id);
        })->exists();

        return $like1 && $like2 && !$existingMatch;
    }

    /**
     * Unmatch users.
     */
    public function unmatch(UserMatch $match): void
    {
        $match->update(['is_active' => false]);

        Log::info('Match deactivated', [
            'match_id' => $match->id,
            'user1' => $match->user_id,
            'user2' => $match->matched_user_id,
        ]);
    }

    /**
     * Get match statistics for a user.
     */
    public function getMatchStats(User $user): array
    {
        return [
            'total_matches' => UserMatch::where(function ($q) use ($user) {
                $q->where('user_id', $user->id)
                  ->orWhere('matched_user_id', $user->id);
            })->where('is_active', true)->count(),

            'total_likes_sent' => Like::where('user_id', $user->id)->count(),

            'total_likes_received' => Like::where('liked_user_id', $user->id)->count(),

            'mutual_likes' => $this->getMutualLikesCount($user),

            'average_compatibility' => UserMatch::where(function ($q) use ($user) {
                $q->where('user_id', $user->id)
                  ->orWhere('matched_user_id', $user->id);
            })->where('is_active', true)->avg('compatibility_score') ?? 0,
        ];
    }

    /**
     * Get count of mutual likes (potential matches).
     */
    protected function getMutualLikesCount(User $user): int
    {
        $likedByUser = Like::where('user_id', $user->id)->pluck('liked_user_id');

        return Like::where('liked_user_id', $user->id)
            ->whereIn('user_id', $likedByUser)
            ->count();
    }

    /**
     * Invalidate match suggestion cache.
     */
    public function invalidateMatchCache(User $user): void
    {
        Cache::forget("match_suggestions_{$user->id}");
    }
}
