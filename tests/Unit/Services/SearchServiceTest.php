<?php

namespace Tests\Unit\Services;

use Tests\TestCase;
use App\Services\SearchService;
use App\Models\User;
use App\Models\ProfileBoost;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Mockery;

class SearchServiceTest extends TestCase
{
    use RefreshDatabase;

    protected SearchService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = new SearchService();
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    /** @test */
    public function it_builds_basic_search_query()
    {
        $currentUser = User::factory()->create([
            'id' => 1,
            'gender' => 'male',
        ]);

        $filters = [];

        $query = $this->service->buildSearchQuery($filters, $currentUser);

        $this->assertStringContainsString('id != 1', $query);
        $this->assertStringContainsString('is_active = true', $query);
        $this->assertStringContainsString("gender = 'female'", $query);
    }

    /** @test */
    public function it_applies_age_range_filter()
    {
        $currentUser = User::factory()->create(['gender' => 'male']);

        $filters = [
            'age_min' => 25,
            'age_max' => 35,
        ];

        $query = $this->service->buildSearchQuery($filters, $currentUser);

        $this->assertStringContainsString('age >= 25', $query);
        $this->assertStringContainsString('age <= 35', $query);
    }

    /** @test */
    public function it_applies_height_range_filter()
    {
        $currentUser = User::factory()->create(['gender' => 'female']);

        $filters = [
            'height_min' => 160,
            'height_max' => 180,
        ];

        $query = $this->service->buildSearchQuery($filters, $currentUser);

        $this->assertStringContainsString('height >= 160', $query);
        $this->assertStringContainsString('height <= 180', $query);
    }

    /** @test */
    public function it_applies_location_filters()
    {
        $currentUser = User::factory()->create(['gender' => 'male']);

        $filters = [
            'city' => 'Mumbai',
            'state' => 'Maharashtra',
            'country' => 'India',
        ];

        $query = $this->service->buildSearchQuery($filters, $currentUser);

        $this->assertStringContainsString("city = 'Mumbai'", $query);
        $this->assertStringContainsString("state = 'Maharashtra'", $query);
        $this->assertStringContainsString("country = 'India'", $query);
    }

    /** @test */
    public function it_applies_single_religion_filter()
    {
        $currentUser = User::factory()->create(['gender' => 'male']);

        $filters = [
            'religion' => 'Hindu',
        ];

        $query = $this->service->buildSearchQuery($filters, $currentUser);

        $this->assertStringContainsString("religion = 'Hindu'", $query);
    }

    /** @test */
    public function it_applies_multiple_religion_filter()
    {
        $currentUser = User::factory()->create(['gender' => 'male']);

        $filters = [
            'religion' => ['Hindu', 'Sikh'],
        ];

        $query = $this->service->buildSearchQuery($filters, $currentUser);

        $this->assertStringContainsString("religion = 'Hindu'", $query);
        $this->assertStringContainsString("religion = 'Sikh'", $query);
        $this->assertStringContainsString('OR', $query);
    }

    /** @test */
    public function it_applies_education_filter()
    {
        $currentUser = User::factory()->create(['gender' => 'female']);

        $filters = [
            'education' => ['Bachelors', 'Masters'],
        ];

        $query = $this->service->buildSearchQuery($filters, $currentUser);

        $this->assertStringContainsString("education = 'Bachelors'", $query);
        $this->assertStringContainsString("education = 'Masters'", $query);
    }

    /** @test */
    public function it_applies_marital_status_filter()
    {
        $currentUser = User::factory()->create(['gender' => 'male']);

        $filters = [
            'marital_status' => ['Never Married', 'Divorced'],
        ];

        $query = $this->service->buildSearchQuery($filters, $currentUser);

        $this->assertStringContainsString("marital_status = 'Never Married'", $query);
        $this->assertStringContainsString("marital_status = 'Divorced'", $query);
    }

    /** @test */
    public function it_applies_diet_filter()
    {
        $currentUser = User::factory()->create(['gender' => 'male']);

        $filters = [
            'diet' => ['Vegetarian', 'Vegan'],
        ];

        $query = $this->service->buildSearchQuery($filters, $currentUser);

        $this->assertStringContainsString("diet = 'Vegetarian'", $query);
        $this->assertStringContainsString("diet = 'Vegan'", $query);
    }

    /** @test */
    public function it_applies_lifestyle_filters()
    {
        $currentUser = User::factory()->create(['gender' => 'female']);

        $filters = [
            'drinking' => 'No',
            'smoking' => 'No',
        ];

        $query = $this->service->buildSearchQuery($filters, $currentUser);

        $this->assertStringContainsString("drinking = 'No'", $query);
        $this->assertStringContainsString("smoking = 'No'", $query);
    }

    /** @test */
    public function it_applies_children_filter()
    {
        $currentUser = User::factory()->create(['gender' => 'male']);

        $filters = [
            'have_children' => false,
        ];

        $query = $this->service->buildSearchQuery($filters, $currentUser);

        $this->assertStringContainsString('have_children = false', $query);
    }

    /** @test */
    public function it_applies_verification_filters()
    {
        $currentUser = User::factory()->create(['gender' => 'male']);

        $filters = [
            'photo_verified' => true,
            'video_verified' => true,
        ];

        $query = $this->service->buildSearchQuery($filters, $currentUser);

        $this->assertStringContainsString('email_verified = true', $query);
        $this->assertStringContainsString('video_verified = true', $query);
    }

    /** @test */
    public function it_applies_premium_filter()
    {
        $currentUser = User::factory()->create(['gender' => 'female']);

        $filters = [
            'premium_only' => true,
        ];

        $query = $this->service->buildSearchQuery($filters, $currentUser);

        $this->assertStringContainsString('is_premium = true', $query);
    }

    /** @test */
    public function it_applies_online_filter()
    {
        $currentUser = User::factory()->create(['gender' => 'male']);

        $filters = [
            'online_only' => true,
        ];

        $query = $this->service->buildSearchQuery($filters, $currentUser);

        $this->assertStringContainsString('is_online = true', $query);
    }

    /** @test */
    public function it_applies_profile_quality_filters()
    {
        $currentUser = User::factory()->create(['gender' => 'male']);

        $filters = [
            'has_photos' => true,
            'has_video_intro' => true,
            'min_profile_completion' => 80,
            'min_verification_count' => 2,
        ];

        $query = $this->service->buildSearchQuery($filters, $currentUser);

        $this->assertStringContainsString('has_photos = true', $query);
        $this->assertStringContainsString('has_video_intro = true', $query);
        $this->assertStringContainsString('profile_completion >= 80', $query);
        $this->assertStringContainsString('verification_count >= 2', $query);
    }

    /** @test */
    public function it_returns_correct_sort_options()
    {
        $filters = ['sort' => 'newest'];
        $sort = $this->invokeMethod($this->service, 'getSortOption', [$filters]);
        $this->assertEquals(['created_at:desc'], $sort);

        $filters = ['sort' => 'active'];
        $sort = $this->invokeMethod($this->service, 'getSortOption', [$filters]);
        $this->assertEquals(['last_active_at:desc'], $sort);

        $filters = ['sort' => 'age_asc'];
        $sort = $this->invokeMethod($this->service, 'getSortOption', [$filters]);
        $this->assertEquals(['age:asc'], $sort);

        $filters = ['sort' => 'age_desc'];
        $sort = $this->invokeMethod($this->service, 'getSortOption', [$filters]);
        $this->assertEquals(['age:desc'], $sort);

        $filters = ['sort' => 'profile_completion'];
        $sort = $this->invokeMethod($this->service, 'getSortOption', [$filters]);
        $this->assertEquals(['profile_completion:desc', 'verification_count:desc'], $sort);

        $filters = ['sort' => 'relevance'];
        $sort = $this->invokeMethod($this->service, 'getSortOption', [$filters]);
        $this->assertEquals([], $sort);
    }

    /** @test */
    public function it_calculates_distance_between_coordinates()
    {
        // Distance between Mumbai and Delhi (approx 1157 km)
        $mumbaiLat = 19.0760;
        $mumbaiLng = 72.8777;
        $delhiLat = 28.7041;
        $delhiLng = 77.1025;

        $distance = $this->invokeMethod(
            $this->service,
            'calculateDistance',
            [$mumbaiLat, $mumbaiLng, $delhiLat, $delhiLng]
        );

        // Should be approximately 1157 km (allow 50 km variance)
        $this->assertGreaterThan(1100, $distance);
        $this->assertLessThan(1200, $distance);
    }

    /** @test */
    public function it_calculates_short_distance_accurately()
    {
        // Same location should return 0
        $lat = 19.0760;
        $lng = 72.8777;

        $distance = $this->invokeMethod(
            $this->service,
            'calculateDistance',
            [$lat, $lng, $lat, $lng]
        );

        $this->assertEquals(0, $distance);
    }

    /** @test */
    public function it_applies_boost_priority_to_results()
    {
        $regularUser1 = User::factory()->create(['id' => 1, 'name' => 'Regular 1']);
        $boostedUser = User::factory()->create(['id' => 2, 'name' => 'Boosted']);
        $regularUser2 = User::factory()->create(['id' => 3, 'name' => 'Regular 2']);

        $users = collect([$regularUser1, $boostedUser, $regularUser2]);
        $boostedUserIds = [2];

        $sortedUsers = $this->invokeMethod(
            $this->service,
            'applyBoostPriority',
            [$users, $boostedUserIds]
        );

        // Boosted user should be first
        $this->assertEquals(2, $sortedUsers->first()->id);
        $this->assertEquals('Boosted', $sortedUsers->first()->name);
    }

    /** @test */
    public function it_caches_boosted_user_ids()
    {
        Cache::shouldReceive('remember')
            ->once()
            ->with('boosted_user_ids', 300, Mockery::type('Closure'))
            ->andReturn([1, 2, 3]);

        $boostedIds = $this->invokeMethod($this->service, 'getBoostedUserIds', []);

        $this->assertEquals([1, 2, 3], $boostedIds);
    }

    /** @test */
    public function it_returns_correct_facets()
    {
        $facets = $this->service->getFacets();

        $expectedFacets = [
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

        $this->assertEquals($expectedFacets, $facets);
    }

    /** @test */
    public function it_generates_search_suggestions_based_on_preferences()
    {
        $user = User::factory()->create(['gender' => 'male']);
        $user->preference()->create([
            'age_min' => 25,
            'age_max' => 30,
            'preferred_religion' => 'Hindu',
            'preferred_education' => 'Masters',
        ]);

        Cache::shouldReceive('remember')
            ->once()
            ->with("search_suggestions_{$user->id}", 3600, Mockery::type('Closure'))
            ->andReturnUsing(function ($key, $ttl, $callback) {
                return $callback();
            });

        $suggestions = $this->service->getSuggestions($user);

        $this->assertIsArray($suggestions);
        $this->assertGreaterThan(0, count($suggestions));

        // Should have preference-based suggestion
        $preferenceSuggestion = collect($suggestions)->firstWhere('title', 'Based on your preferences');
        $this->assertNotNull($preferenceSuggestion);
        $this->assertEquals(25, $preferenceSuggestion['filters']['age_min']);
        $this->assertEquals(30, $preferenceSuggestion['filters']['age_max']);
    }

    /** @test */
    public function it_builds_complex_query_with_multiple_filters()
    {
        $currentUser = User::factory()->create(['gender' => 'male']);

        $filters = [
            'age_min' => 25,
            'age_max' => 35,
            'height_min' => 160,
            'city' => 'Mumbai',
            'religion' => ['Hindu', 'Sikh'],
            'education' => ['Bachelors', 'Masters'],
            'marital_status' => 'Never Married',
            'diet' => 'Vegetarian',
            'drinking' => 'No',
            'smoking' => 'No',
            'premium_only' => true,
            'video_verified' => true,
            'min_profile_completion' => 80,
        ];

        $query = $this->service->buildSearchQuery($filters, $currentUser);

        // Verify all filters are applied
        $this->assertStringContainsString('age >= 25', $query);
        $this->assertStringContainsString('age <= 35', $query);
        $this->assertStringContainsString('height >= 160', $query);
        $this->assertStringContainsString("city = 'Mumbai'", $query);
        $this->assertStringContainsString("religion = 'Hindu'", $query);
        $this->assertStringContainsString("education = 'Bachelors'", $query);
        $this->assertStringContainsString("marital_status = 'Never Married'", $query);
        $this->assertStringContainsString("diet = 'Vegetarian'", $query);
        $this->assertStringContainsString("drinking = 'No'", $query);
        $this->assertStringContainsString("smoking = 'No'", $query);
        $this->assertStringContainsString('is_premium = true', $query);
        $this->assertStringContainsString('video_verified = true', $query);
        $this->assertStringContainsString('profile_completion >= 80', $query);

        // All conditions should be joined with AND
        $andCount = substr_count($query, ' AND ');
        $this->assertGreaterThan(10, $andCount);
    }

    /**
     * Helper method to invoke protected/private methods for testing
     */
    protected function invokeMethod($object, string $methodName, array $parameters = [])
    {
        $reflection = new \ReflectionClass(get_class($object));
        $method = $reflection->getMethod($methodName);
        $method->setAccessible(true);

        return $method->invokeArgs($object, $parameters);
    }
}
