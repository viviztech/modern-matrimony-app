<?php

namespace App\Services;

use App\Models\User;
use App\Models\ProfileBoost;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Collection;

class SearchService
{
    /**
     * Build and execute search query.
     */
    public function search(array $filters, User $currentUser, int $perPage = 20): array
    {
        $query = $this->buildSearchQuery($filters, $currentUser);
        $sort = $this->getSortOption($filters);

        // Build Meilisearch query
        $searchQuery = $filters['keyword'] ?? '';
        $page = $filters['page'] ?? 1;

        // Execute search
        $results = User::search($searchQuery, function ($meilisearch, $query, $options) use ($filters, $sort, $perPage, $page) {
            // Apply filters
            if (!empty($filters['filter'])) {
                $options['filter'] = $filters['filter'];
            }

            // Apply sorting
            if (!empty($sort)) {
                $options['sort'] = $sort;
            }

            // Pagination
            $options['limit'] = $perPage;
            $options['offset'] = ($page - 1) * $perPage;

            // Get facets for filter counts
            if (!empty($filters['facets'])) {
                $options['facets'] = $filters['facets'];
            }

            return $meilisearch->search($query, $options);
        });

        // Get boosted profiles
        $boostedUserIds = $this->getBoostedUserIds();

        // Process and sort results
        $users = $results->get();

        // Apply boost logic
        if (!empty($boostedUserIds)) {
            $users = $this->applyBoostPriority($users, $boostedUserIds);
        }

        return [
            'users' => $users,
            'total' => $results->total(),
            'page' => $page,
            'perPage' => $perPage,
            'facets' => $results->raw()['facetDistribution'] ?? [],
        ];
    }

    /**
     * Build filter query string for Meilisearch.
     */
    public function buildSearchQuery(array $filters, User $currentUser): string
    {
        $conditions = [];

        // Exclude current user
        $conditions[] = "id != {$currentUser->id}";

        // Only active users
        $conditions[] = "is_active = true";

        // Gender filter (opposite of current user for heterosexual matches)
        if (!empty($filters['gender'])) {
            $conditions[] = "gender = '{$filters['gender']}'";
        } else {
            // Default: opposite gender
            $oppositeGender = $currentUser->gender === 'male' ? 'female' : 'male';
            $conditions[] = "gender = '{$oppositeGender}'";
        }

        // Age range
        if (!empty($filters['age_min'])) {
            $conditions[] = "age >= {$filters['age_min']}";
        }
        if (!empty($filters['age_max'])) {
            $conditions[] = "age <= {$filters['age_max']}";
        }

        // Height range
        if (!empty($filters['height_min'])) {
            $conditions[] = "height >= {$filters['height_min']}";
        }
        if (!empty($filters['height_max'])) {
            $conditions[] = "height <= {$filters['height_max']}";
        }

        // Location filters
        if (!empty($filters['city'])) {
            $conditions[] = "city = '{$filters['city']}'";
        }
        if (!empty($filters['state'])) {
            $conditions[] = "state = '{$filters['state']}'";
        }
        if (!empty($filters['country'])) {
            $conditions[] = "country = '{$filters['country']}'";
        }

        // Religion
        if (!empty($filters['religion'])) {
            if (is_array($filters['religion'])) {
                $religions = array_map(fn($r) => "religion = '{$r}'", $filters['religion']);
                $conditions[] = '(' . implode(' OR ', $religions) . ')';
            } else {
                $conditions[] = "religion = '{$filters['religion']}'";
            }
        }

        // Caste
        if (!empty($filters['caste'])) {
            $conditions[] = "caste = '{$filters['caste']}'";
        }

        // Mother tongue
        if (!empty($filters['mother_tongue'])) {
            if (is_array($filters['mother_tongue'])) {
                $tongues = array_map(fn($t) => "mother_tongue = '{$t}'", $filters['mother_tongue']);
                $conditions[] = '(' . implode(' OR ', $tongues) . ')';
            } else {
                $conditions[] = "mother_tongue = '{$filters['mother_tongue']}'";
            }
        }

        // Education
        if (!empty($filters['education'])) {
            if (is_array($filters['education'])) {
                $educations = array_map(fn($e) => "education = '{$e}'", $filters['education']);
                $conditions[] = '(' . implode(' OR ', $educations) . ')';
            } else {
                $conditions[] = "education = '{$filters['education']}'";
            }
        }

        // Occupation
        if (!empty($filters['occupation'])) {
            $conditions[] = "occupation = '{$filters['occupation']}'";
        }

        // Income range
        if (!empty($filters['annual_income_range'])) {
            $conditions[] = "annual_income_range = '{$filters['annual_income_range']}'";
        }

        // Marital status
        if (!empty($filters['marital_status'])) {
            if (is_array($filters['marital_status'])) {
                $statuses = array_map(fn($s) => "marital_status = '{$s}'", $filters['marital_status']);
                $conditions[] = '(' . implode(' OR ', $statuses) . ')';
            } else {
                $conditions[] = "marital_status = '{$filters['marital_status']}'";
            }
        }

        // Diet
        if (!empty($filters['diet'])) {
            if (is_array($filters['diet'])) {
                $diets = array_map(fn($d) => "diet = '{$d}'", $filters['diet']);
                $conditions[] = '(' . implode(' OR ', $diets) . ')';
            } else {
                $conditions[] = "diet = '{$filters['diet']}'";
            }
        }

        // Drinking
        if (!empty($filters['drinking'])) {
            $conditions[] = "drinking = '{$filters['drinking']}'";
        }

        // Smoking
        if (!empty($filters['smoking'])) {
            $conditions[] = "smoking = '{$filters['smoking']}'";
        }

        // Children
        if (isset($filters['have_children'])) {
            $value = $filters['have_children'] ? 'true' : 'false';
            $conditions[] = "have_children = {$value}";
        }

        // Photo verified only
        if (!empty($filters['photo_verified'])) {
            $conditions[] = "email_verified = true";
        }

        // Video verified only
        if (!empty($filters['video_verified'])) {
            $conditions[] = "video_verified = true";
        }

        // Has photos
        if (!empty($filters['has_photos'])) {
            $conditions[] = "has_photos = true";
        }

        // Has video intro
        if (!empty($filters['has_video_intro'])) {
            $conditions[] = "has_video_intro = true";
        }

        // Premium members only
        if (!empty($filters['premium_only'])) {
            $conditions[] = "is_premium = true";
        }

        // Online users only
        if (!empty($filters['online_only'])) {
            $conditions[] = "is_online = true";
        }

        // Minimum profile completion
        if (!empty($filters['min_profile_completion'])) {
            $conditions[] = "profile_completion >= {$filters['min_profile_completion']}";
        }

        // Minimum verification count
        if (!empty($filters['min_verification_count'])) {
            $conditions[] = "verification_count >= {$filters['min_verification_count']}";
        }

        return implode(' AND ', $conditions);
    }

    /**
     * Get sort option based on filters.
     */
    protected function getSortOption(array $filters): array
    {
        $sort = $filters['sort'] ?? 'relevance';

        return match ($sort) {
            'newest' => ['created_at:desc'],
            'active' => ['last_active_at:desc'],
            'age_asc' => ['age:asc'],
            'age_desc' => ['age:desc'],
            'height_asc' => ['height:asc'],
            'height_desc' => ['height:desc'],
            'profile_completion' => ['profile_completion:desc', 'verification_count:desc'],
            default => [], // Relevance (default Meilisearch ranking)
        };
    }

    /**
     * Get boosted user IDs.
     */
    protected function getBoostedUserIds(): array
    {
        return Cache::remember('boosted_user_ids', 300, function () {
            return ProfileBoost::active()
                ->pluck('user_id')
                ->toArray();
        });
    }

    /**
     * Apply boost priority to results.
     */
    protected function applyBoostPriority(Collection $users, array $boostedUserIds): Collection
    {
        return $users->sortByDesc(function ($user) use ($boostedUserIds) {
            return in_array($user->id, $boostedUserIds) ? 1 : 0;
        })->values();
    }

    /**
     * Search with location radius (geo search).
     */
    public function searchByRadius(float $lat, float $lng, float $radiusKm, array $filters = []): Collection
    {
        // Get all users that match filters
        $filterQuery = $this->buildSearchQuery($filters, auth()->user());

        $users = User::search('', function ($meilisearch, $query, $options) use ($filterQuery) {
            if (!empty($filterQuery)) {
                $options['filter'] = $filterQuery;
            }
            $options['limit'] = 1000; // Get more results for distance calculation
            return $meilisearch->search($query, $options);
        })->get();

        // Filter by distance
        return $users->filter(function ($user) use ($lat, $lng, $radiusKm) {
            if (!$user->latitude || !$user->longitude) {
                return false;
            }

            $distance = $this->calculateDistance($lat, $lng, $user->latitude, $user->longitude);
            return $distance <= $radiusKm;
        })->sortBy(function ($user) use ($lat, $lng) {
            return $this->calculateDistance($lat, $lng, $user->latitude, $user->longitude);
        })->values();
    }

    /**
     * Calculate distance between two points using Haversine formula.
     */
    protected function calculateDistance(float $lat1, float $lng1, float $lat2, float $lng2): float
    {
        $earthRadius = 6371; // Earth's radius in kilometers

        $latDiff = deg2rad($lat2 - $lat1);
        $lngDiff = deg2rad($lng2 - $lng1);

        $a = sin($latDiff / 2) * sin($latDiff / 2) +
            cos(deg2rad($lat1)) * cos(deg2rad($lat2)) *
            sin($lngDiff / 2) * sin($lngDiff / 2);

        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

        return $earthRadius * $c;
    }

    /**
     * Get facets for filters.
     */
    public function getFacets(): array
    {
        return [
            'gender',
            'religion',
            'education',
            'marital_status',
            'diet',
            'drinking',
            'smoking',
            'mother_tongue',
            'is_premium',
            'has_video_intro',
            'video_verified',
        ];
    }

    /**
     * Get search suggestions based on popular searches.
     */
    public function getSuggestions(User $user): array
    {
        $cacheKey = "search_suggestions_{$user->id}";

        return Cache::remember($cacheKey, 3600, function () use ($user) {
            $suggestions = [];

            // Based on user preferences
            if ($user->preference) {
                $pref = $user->preference;

                $suggestions[] = [
                    'title' => 'Based on your preferences',
                    'filters' => [
                        'age_min' => $pref->age_min,
                        'age_max' => $pref->age_max,
                        'religion' => $pref->preferred_religion,
                        'education' => $pref->preferred_education,
                    ],
                ];
            }

            // Similar to you
            $suggestions[] = [
                'title' => 'Similar to you',
                'filters' => [
                    'education' => $user->profile?->education,
                    'religion' => $user->profile?->religion,
                    'city' => $user->city,
                ],
            ];

            // Highly verified
            $suggestions[] = [
                'title' => 'Highly verified profiles',
                'filters' => [
                    'min_verification_count' => 3,
                    'video_verified' => true,
                ],
            ];

            // Recently active
            $suggestions[] = [
                'title' => 'Recently active',
                'filters' => [
                    'sort' => 'active',
                ],
            ];

            return $suggestions;
        });
    }
}
