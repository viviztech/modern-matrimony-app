<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\SavedSearch;
use App\Models\ProfileBoost;
use App\Services\SearchService;
use App\Services\FeatureGateService;
use App\Services\AnalyticsService;
use App\Models\UserActivity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Validator;

class SearchController extends Controller
{
    protected SearchService $searchService;
    protected FeatureGateService $featureGate;
    protected AnalyticsService $analyticsService;

    public function __construct(SearchService $searchService, FeatureGateService $featureGate, AnalyticsService $analyticsService)
    {
        $this->middleware('auth');
        $this->searchService = $searchService;
        $this->featureGate = $featureGate;
        $this->analyticsService = $analyticsService;
    }

    /**
     * Show search page with filters.
     */
    public function index(Request $request)
    {
        $user = auth()->user();

        // Get filter options from cache or database
        $filterOptions = $this->getFilterOptions();

        // Get saved searches
        $savedSearches = $user->savedSearches()
            ->latest()
            ->take(5)
            ->get();

        // Get search suggestions
        $suggestions = $this->searchService->getSuggestions($user);

        // Check if user can use advanced filters
        $canUseAdvancedFilters = $this->featureGate->canUseAdvancedFilters($user);

        return view('search.index', compact(
            'filterOptions',
            'savedSearches',
            'suggestions',
            'canUseAdvancedFilters'
        ));
    }

    /**
     * Perform search with filters.
     */
    public function search(Request $request)
    {
        $user = auth()->user();

        // Validate filters
        $validator = Validator::make($request->all(), [
            'keyword' => 'nullable|string|max:255',
            'age_min' => 'nullable|integer|min:18|max:100',
            'age_max' => 'nullable|integer|min:18|max:100',
            'height_min' => 'nullable|integer|min:100|max:250',
            'height_max' => 'nullable|integer|min:100|max:250',
            'location' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:255',
            'state' => 'nullable|string|max:255',
            'country' => 'nullable|string|max:255',
            'radius' => 'nullable|integer|min:1|max:500',
            'religion' => 'nullable',
            'caste' => 'nullable|string|max:255',
            'mother_tongue' => 'nullable',
            'education' => 'nullable',
            'occupation' => 'nullable|string|max:255',
            'annual_income_range' => 'nullable|string|max:255',
            'marital_status' => 'nullable',
            'diet' => 'nullable',
            'drinking' => 'nullable|string|max:255',
            'smoking' => 'nullable|string|max:255',
            'have_children' => 'nullable|boolean',
            'photo_verified' => 'nullable|boolean',
            'video_verified' => 'nullable|boolean',
            'has_photos' => 'nullable|boolean',
            'has_video_intro' => 'nullable|boolean',
            'premium_only' => 'nullable|boolean',
            'online_only' => 'nullable|boolean',
            'sort' => 'nullable|in:relevance,newest,active,age_asc,age_desc,height_asc,height_desc,profile_completion',
            'page' => 'nullable|integer|min:1',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $filters = $request->all();

        // Check if user can use advanced filters
        $requiresPremium = [
            'annual_income_range',
            'premium_only',
            'min_verification_count',
            'radius',
            'online_only',
        ];

        $usesAdvancedFilter = false;
        foreach ($requiresPremium as $field) {
            if (!empty($filters[$field])) {
                $usesAdvancedFilter = true;
                break;
            }
        }

        if ($usesAdvancedFilter && !$this->featureGate->canUseAdvancedFilters($user)) {
            return response()->json([
                'success' => false,
                'message' => $this->featureGate->getLimitMessage('advanced_filters'),
                'upgrade_required' => true,
                'recommended_plan' => $this->featureGate->getRecommendedPlan('advanced_filters'),
            ], 403);
        }

        // Add filter for Meilisearch
        $filters['filter'] = $this->searchService->buildSearchQuery($filters, $user);

        // Add facets
        $filters['facets'] = $this->searchService->getFacets();

        // Perform search
        try {
            if (!empty($filters['radius']) && !empty($user->latitude) && !empty($user->longitude)) {
                // Location-based search
                $results = $this->searchService->searchByRadius(
                    $user->latitude,
                    $user->longitude,
                    $filters['radius'],
                    $filters
                );

                $searchResults = [
                    'users' => $results->take($filters['per_page'] ?? 20),
                    'total' => $results->count(),
                    'page' => $filters['page'] ?? 1,
                    'perPage' => $filters['per_page'] ?? 20,
                    'facets' => [],
                ];
            } else {
                // Regular search
                $searchResults = $this->searchService->search($filters, $user, $filters['per_page'] ?? 20);
            }

            // Track search activity
            $this->analyticsService->trackActivity(
                $user,
                UserActivity::TYPE_SEARCH_PERFORMED,
                [
                    'filters' => array_filter($filters, function($value) {
                        return !is_null($value) && $value !== '';
                    }),
                    'results_count' => $searchResults['total']
                ]
            );

            // Return JSON for AJAX requests
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'data' => [
                        'users' => $searchResults['users']->load(['profile', 'primaryPhoto']),
                        'total' => $searchResults['total'],
                        'page' => $searchResults['page'],
                        'perPage' => $searchResults['perPage'],
                        'facets' => $searchResults['facets'],
                        'hasMore' => ($searchResults['page'] * $searchResults['perPage']) < $searchResults['total'],
                    ]
                ]);
            }

            // Return view for regular requests
            $filterOptions = $this->getFilterOptions();

            return view('search.results', [
                'users' => $searchResults['users'],
                'total' => $searchResults['total'],
                'page' => $searchResults['page'],
                'perPage' => $searchResults['perPage'],
                'facets' => $searchResults['facets'],
                'filters' => $filters,
                'filterOptions' => $filterOptions,
            ]);
        } catch (\Exception $e) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Search failed. Please try again.',
                    'error' => app()->environment('local') ? $e->getMessage() : null,
                ], 500);
            }

            return back()->with('error', 'Search failed. Please try again.');
        }
    }

    /**
     * Show who liked you (premium feature).
     */
    public function whoLikedYou(Request $request)
    {
        $user = auth()->user();

        // Check if user can see who liked them
        if (!$this->featureGate->canSeeWhoLiked($user)) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => $this->featureGate->getLimitMessage('see_who_liked'),
                    'upgrade_required' => true,
                    'recommended_plan' => $this->featureGate->getRecommendedPlan('see_who_liked'),
                ], 403);
            }

            return redirect()->route('pricing')
                ->with('info', $this->featureGate->getLimitMessage('see_who_liked'));
        }

        // Get users who liked the current user
        $likedBy = $user->likedBy()
            ->with(['profile', 'primaryPhoto'])
            ->latest()
            ->paginate(20);

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'data' => $likedBy,
            ]);
        }

        return view('search.who-liked-you', compact('likedBy'));
    }

    /**
     * Save search criteria.
     */
    public function saveSearch(Request $request)
    {
        $user = auth()->user();

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'filters' => 'required|array',
            'notify_on_match' => 'nullable|boolean',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        // Check if user has reached saved search limit
        $maxSavedSearches = $user->getFeatureLimit('saved_searches');
        if ($maxSavedSearches > 0 && $user->savedSearches()->count() >= $maxSavedSearches) {
            return response()->json([
                'success' => false,
                'message' => "You've reached your limit of {$maxSavedSearches} saved searches. Upgrade to save more!",
                'upgrade_required' => true,
            ], 403);
        }

        // Create saved search
        $savedSearch = $user->savedSearches()->create([
            'name' => $request->name,
            'filters' => $request->filters,
            'notify_on_match' => $request->notify_on_match ?? false,
        ]);

        // Get initial results count
        $filters = $request->filters;
        $filters['filter'] = $this->searchService->buildSearchQuery($filters, $user);
        $results = $this->searchService->search($filters, $user, 1);
        $savedSearch->updateResultsCount($results['total']);

        return response()->json([
            'success' => true,
            'message' => 'Search saved successfully!',
            'data' => $savedSearch,
        ]);
    }

    /**
     * List user's saved searches.
     */
    public function savedSearches(Request $request)
    {
        $user = auth()->user();

        $savedSearches = $user->savedSearches()
            ->latest()
            ->paginate(20);

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'data' => $savedSearches,
            ]);
        }

        return view('search.saved-searches', compact('savedSearches'));
    }

    /**
     * Delete a saved search.
     */
    public function deleteSavedSearch(Request $request, SavedSearch $savedSearch)
    {
        $user = auth()->user();

        // Check ownership
        if ($savedSearch->user_id !== $user->id) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized action.',
            ], 403);
        }

        $savedSearch->delete();

        return response()->json([
            'success' => true,
            'message' => 'Search deleted successfully!',
        ]);
    }

    /**
     * Execute a saved search.
     */
    public function runSavedSearch(Request $request, SavedSearch $savedSearch)
    {
        $user = auth()->user();

        // Check ownership
        if ($savedSearch->user_id !== $user->id) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized action.',
                ], 403);
            }

            return redirect()->route('search.index')
                ->with('error', 'Unauthorized action.');
        }

        // Get filters from saved search
        $filters = $savedSearch->filters;
        $filters['page'] = $request->get('page', 1);
        $filters['filter'] = $this->searchService->buildSearchQuery($filters, $user);
        $filters['facets'] = $this->searchService->getFacets();

        // Perform search
        $searchResults = $this->searchService->search($filters, $user);

        // Update results count
        $savedSearch->updateResultsCount($searchResults['total']);

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'data' => [
                    'users' => $searchResults['users']->load(['profile', 'primaryPhoto']),
                    'total' => $searchResults['total'],
                    'page' => $searchResults['page'],
                    'perPage' => $searchResults['perPage'],
                    'facets' => $searchResults['facets'],
                    'savedSearch' => $savedSearch,
                ]
            ]);
        }

        $filterOptions = $this->getFilterOptions();

        return view('search.results', [
            'users' => $searchResults['users'],
            'total' => $searchResults['total'],
            'page' => $searchResults['page'],
            'perPage' => $searchResults['perPage'],
            'facets' => $searchResults['facets'],
            'filters' => $filters,
            'filterOptions' => $filterOptions,
            'savedSearch' => $savedSearch,
        ]);
    }

    /**
     * Boost user profile (premium feature).
     */
    public function boostProfile(Request $request)
    {
        $user = auth()->user();

        // Check if user can use profile boost
        if (!$this->featureGate->canUseProfileBoost($user)) {
            return response()->json([
                'success' => false,
                'message' => $this->featureGate->getLimitMessage('profile_boost'),
                'upgrade_required' => true,
                'recommended_plan' => $this->featureGate->getRecommendedPlan('profile_boost'),
            ], 403);
        }

        // Check if user already has active boost
        if ($user->hasActiveBoost()) {
            return response()->json([
                'success' => false,
                'message' => 'You already have an active profile boost.',
            ], 400);
        }

        $validator = Validator::make($request->all(), [
            'duration_type' => 'required|in:24h,7d,30d',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        // Create boost
        $boost = ProfileBoost::createBoost($user, $request->duration_type);

        // Increment usage
        $this->featureGate->incrementUsage($user->id, 'profile_boosts', 'monthly');

        // Clear boosted users cache
        Cache::forget('boosted_user_ids');

        return response()->json([
            'success' => true,
            'message' => 'Your profile is now boosted! You\'ll get 10x more visibility.',
            'data' => $boost,
        ]);
    }

    /**
     * Get filter options for dropdowns.
     */
    protected function getFilterOptions(): array
    {
        return Cache::remember('search_filter_options', 3600, function () {
            return [
                'religions' => [
                    'Hindu',
                    'Muslim',
                    'Christian',
                    'Sikh',
                    'Buddhist',
                    'Jain',
                    'Other',
                ],
                'educations' => [
                    'High School',
                    'Associate Degree',
                    'Bachelor\'s Degree',
                    'Master\'s Degree',
                    'Doctorate',
                    'Professional Degree',
                ],
                'marital_statuses' => [
                    'Never Married',
                    'Divorced',
                    'Widowed',
                    'Separated',
                ],
                'diets' => [
                    'Vegetarian',
                    'Non-Vegetarian',
                    'Vegan',
                    'Eggetarian',
                ],
                'drinking_habits' => [
                    'Never',
                    'Socially',
                    'Regularly',
                ],
                'smoking_habits' => [
                    'Never',
                    'Occasionally',
                    'Regularly',
                ],
                'income_ranges' => [
                    '0-25k',
                    '25k-50k',
                    '50k-75k',
                    '75k-100k',
                    '100k-150k',
                    '150k+',
                ],
                'mother_tongues' => [
                    'Hindi',
                    'English',
                    'Tamil',
                    'Telugu',
                    'Marathi',
                    'Bengali',
                    'Gujarati',
                    'Kannada',
                    'Malayalam',
                    'Punjabi',
                    'Urdu',
                    'Other',
                ],
                'sort_options' => [
                    'relevance' => 'Most Relevant',
                    'newest' => 'Newest First',
                    'active' => 'Recently Active',
                    'age_asc' => 'Age: Low to High',
                    'age_desc' => 'Age: High to Low',
                    'height_asc' => 'Height: Low to High',
                    'height_desc' => 'Height: High to Low',
                    'profile_completion' => 'Profile Completion',
                ],
            ];
        });
    }
}
