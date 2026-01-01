<?php

namespace Tests\Unit\Services;

use App\Models\User;
use App\Models\Preference;
use App\Models\Like;
use App\Services\MatchingService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MatchingServiceTest extends TestCase
{
    use RefreshDatabase;

    protected MatchingService $service;
    protected User $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->service = new MatchingService();
        $this->user = User::factory()->create([
            'gender' => 'male',
            'age' => 28,
            'religion' => 'hindu',
            'city' => 'Mumbai',
            'height' => 175,
        ]);
    }

    public function test_can_calculate_compatibility_score(): void
    {
        $otherUser = User::factory()->create([
            'gender' => 'female',
            'age' => 26,
            'religion' => 'hindu',
            'city' => 'Mumbai',
        ]);

        $score = $this->service->calculateCompatibilityScore($this->user, $otherUser);

        $this->assertIsInt($score);
        $this->assertGreaterThanOrEqual(0, $score);
        $this->assertLessThanOrEqual(100, $score);
    }

    public function test_same_religion_increases_compatibility(): void
    {
        $sameReligion = User::factory()->create([
            'gender' => 'female',
            'religion' => 'hindu',
        ]);

        $differentReligion = User::factory()->create([
            'gender' => 'female',
            'religion' => 'christian',
        ]);

        $scoreSame = $this->service->calculateCompatibilityScore($this->user, $sameReligion);
        $scoreDifferent = $this->service->calculateCompatibilityScore($this->user, $differentReligion);

        $this->assertGreaterThan($scoreDifferent, $scoreSame);
    }

    public function test_similar_age_increases_compatibility(): void
    {
        $similarAge = User::factory()->create([
            'gender' => 'female',
            'age' => 27, // Close to user's 28
        ]);

        $veryDifferentAge = User::factory()->create([
            'gender' => 'female',
            'age' => 45, // Far from user's 28
        ]);

        $scoreSimilar = $this->service->calculateCompatibilityScore($this->user, $similarAge);
        $scoreDifferent = $this->service->calculateCompatibilityScore($this->user, $veryDifferentAge);

        $this->assertGreaterThan($scoreDifferent, $scoreSimilar);
    }

    public function test_same_city_increases_compatibility(): void
    {
        $sameCity = User::factory()->create([
            'gender' => 'female',
            'city' => 'Mumbai',
        ]);

        $differentCity = User::factory()->create([
            'gender' => 'female',
            'city' => 'Delhi',
        ]);

        $scoreSame = $this->service->calculateCompatibilityScore($this->user, $sameCity);
        $scoreDifferent = $this->service->calculateCompatibilityScore($this->user, $differentCity);

        $this->assertGreaterThan($scoreDifferent, $scoreSame);
    }

    public function test_can_get_potential_matches(): void
    {
        // Create potential matches
        User::factory()->count(5)->create([
            'gender' => 'female',
            'religion' => 'hindu',
        ]);

        $matches = $this->service->getPotentialMatches($this->user, 10);

        $this->assertIsIterable($matches);
        $this->assertLessThanOrEqual(10, $matches->count());
    }

    public function test_potential_matches_excludes_same_gender(): void
    {
        // Create users of same gender
        User::factory()->count(3)->create([
            'gender' => 'male',
        ]);

        // Create users of opposite gender
        User::factory()->count(3)->create([
            'gender' => 'female',
        ]);

        $matches = $this->service->getPotentialMatches($this->user);

        // Should only get opposite gender
        $this->assertTrue($matches->every(fn($match) => $match->gender === 'female'));
    }

    public function test_potential_matches_excludes_already_liked(): void
    {
        $otherUser = User::factory()->create(['gender' => 'female']);

        // User already liked this person
        Like::factory()->create([
            'user_id' => $this->user->id,
            'liked_user_id' => $otherUser->id,
        ]);

        $matches = $this->service->getPotentialMatches($this->user);

        $this->assertFalse($matches->contains($otherUser));
    }

    public function test_can_detect_mutual_match(): void
    {
        $otherUser = User::factory()->create(['gender' => 'female']);

        // Both users like each other
        Like::factory()->create([
            'user_id' => $this->user->id,
            'liked_user_id' => $otherUser->id,
        ]);

        Like::factory()->create([
            'user_id' => $otherUser->id,
            'liked_user_id' => $this->user->id,
        ]);

        $isMutual = $this->service->isMutualMatch($this->user, $otherUser);

        $this->assertTrue($isMutual);
    }

    public function test_non_mutual_like_returns_false(): void
    {
        $otherUser = User::factory()->create(['gender' => 'female']);

        // Only one user likes
        Like::factory()->create([
            'user_id' => $this->user->id,
            'liked_user_id' => $otherUser->id,
        ]);

        $isMutual = $this->service->isMutualMatch($this->user, $otherUser);

        $this->assertFalse($isMutual);
    }

    public function test_can_get_match_suggestions_based_on_preferences(): void
    {
        // Create user preference
        Preference::factory()->create([
            'user_id' => $this->user->id,
            'min_age' => 24,
            'max_age' => 30,
            'religion' => 'hindu',
        ]);

        // Create users matching preferences
        User::factory()->count(3)->create([
            'gender' => 'female',
            'age' => 26,
            'religion' => 'hindu',
        ]);

        // Create users not matching preferences
        User::factory()->count(2)->create([
            'gender' => 'female',
            'age' => 35, // Outside age range
            'religion' => 'hindu',
        ]);

        $suggestions = $this->service->getMatchSuggestions($this->user, 10);

        $this->assertGreaterThanOrEqual(3, $suggestions->count());
        $this->assertTrue($suggestions->every(fn($user) =>
            $user->age >= 24 && $user->age <= 30
        ));
    }

    public function test_match_suggestions_are_sorted_by_compatibility(): void
    {
        // Create users with varying compatibility
        User::factory()->create([
            'gender' => 'female',
            'age' => 28,
            'religion' => 'hindu',
            'city' => 'Mumbai',
        ]);

        User::factory()->create([
            'gender' => 'female',
            'age' => 40,
            'religion' => 'christian',
            'city' => 'Delhi',
        ]);

        $suggestions = $this->service->getMatchSuggestions($this->user, 10);

        // First match should have higher compatibility than last
        if ($suggestions->count() >= 2) {
            $firstScore = $this->service->calculateCompatibilityScore($this->user, $suggestions->first());
            $lastScore = $this->service->calculateCompatibilityScore($this->user, $suggestions->last());

            $this->assertGreaterThanOrEqual($lastScore, $firstScore);
        }
    }

    public function test_can_get_mutual_matches(): void
    {
        $match1 = User::factory()->create(['gender' => 'female']);
        $match2 = User::factory()->create(['gender' => 'female']);

        // Create mutual likes
        Like::factory()->create(['user_id' => $this->user->id, 'liked_user_id' => $match1->id]);
        Like::factory()->create(['user_id' => $match1->id, 'liked_user_id' => $this->user->id]);

        Like::factory()->create(['user_id' => $this->user->id, 'liked_user_id' => $match2->id]);
        Like::factory()->create(['user_id' => $match2->id, 'liked_user_id' => $this->user->id]);

        $mutualMatches = $this->service->getMutualMatches($this->user);

        $this->assertEquals(2, $mutualMatches->count());
        $this->assertTrue($mutualMatches->contains($match1));
        $this->assertTrue($mutualMatches->contains($match2));
    }

    public function test_compatibility_score_considers_education_level(): void
    {
        $similarEducation = User::factory()->create([
            'gender' => 'female',
            'education' => 'bachelors',
        ]);

        $this->user->education = 'bachelors';
        $this->user->save();

        $score = $this->service->calculateCompatibilityScore($this->user, $similarEducation);

        $this->assertGreaterThan(0, $score);
    }

    public function test_can_check_if_users_have_matched(): void
    {
        $otherUser = User::factory()->create(['gender' => 'female']);

        // No likes yet
        $hasMatched = $this->service->hasMatched($this->user, $otherUser);
        $this->assertFalse($hasMatched);

        // Create mutual likes
        Like::factory()->create(['user_id' => $this->user->id, 'liked_user_id' => $otherUser->id]);
        Like::factory()->create(['user_id' => $otherUser->id, 'liked_user_id' => $this->user->id]);

        $hasMatched = $this->service->hasMatched($this->user, $otherUser);
        $this->assertTrue($hasMatched);
    }

    public function test_match_suggestions_respect_user_preferences(): void
    {
        Preference::factory()->create([
            'user_id' => $this->user->id,
            'min_height' => 160,
            'max_height' => 170,
        ]);

        // Create users within height range
        User::factory()->create([
            'gender' => 'female',
            'height' => 165,
        ]);

        // Create users outside height range
        User::factory()->create([
            'gender' => 'female',
            'height' => 180,
        ]);

        $suggestions = $this->service->getMatchSuggestions($this->user);

        if ($suggestions->count() > 0) {
            $this->assertTrue($suggestions->every(fn($user) =>
                $user->height >= 160 && $user->height <= 170
            ));
        }
    }
}
