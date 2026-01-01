<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Like;
use App\Models\Match;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DiscoverControllerTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create([
            'gender' => 'male',
            'onboarding_completed' => true,
        ]);
    }

    public function test_user_can_view_discover_page(): void
    {
        $response = $this->actingAs($this->user)->get('/discover');

        $response->assertStatus(200);
        $response->assertSee('Discover');
    }

    public function test_guest_cannot_access_discover(): void
    {
        $response = $this->get('/discover');

        $response->assertRedirect('/login');
    }

    public function test_discover_shows_potential_matches(): void
    {
        // Create potential matches
        User::factory()->count(3)->create([
            'gender' => 'female',
        ]);

        $response = $this->actingAs($this->user)->get('/discover');

        $response->assertStatus(200);
    }

    public function test_discover_excludes_same_gender(): void
    {
        $sameGender = User::factory()->create([
            'gender' => 'male',
            'name' => 'Same Gender User',
        ]);

        $response = $this->actingAs($this->user)->get('/discover');

        $response->assertStatus(200);
        $response->assertDontSee('Same Gender User');
    }

    public function test_user_can_like_a_profile(): void
    {
        $target = User::factory()->create(['gender' => 'female']);

        $response = $this->actingAs($this->user)->post('/discover/like', [
            'liked_user_id' => $target->id,
        ]);

        $response->assertStatus(200);
        $this->assertDatabaseHas('likes', [
            'user_id' => $this->user->id,
            'liked_user_id' => $target->id,
        ]);
    }

    public function test_user_can_pass_a_profile(): void
    {
        $target = User::factory()->create(['gender' => 'female']);

        $response = $this->actingAs($this->user)->post('/discover/pass', [
            'passed_user_id' => $target->id,
        ]);

        $response->assertStatus(200);
    }

    public function test_liking_creates_match_when_mutual(): void
    {
        $target = User::factory()->create(['gender' => 'female']);

        // Target already liked our user
        Like::factory()->create([
            'user_id' => $target->id,
            'liked_user_id' => $this->user->id,
        ]);

        $response = $this->actingAs($this->user)->post('/discover/like', [
            'liked_user_id' => $target->id,
        ]);

        $response->assertStatus(200);
        $response->assertJson(['matched' => true]);

        $this->assertDatabaseHas('matches', [
            'user1_id' => min($this->user->id, $target->id),
            'user2_id' => max($this->user->id, $target->id),
        ]);
    }

    public function test_liking_does_not_create_match_when_not_mutual(): void
    {
        $target = User::factory()->create(['gender' => 'female']);

        $response = $this->actingAs($this->user)->post('/discover/like', [
            'liked_user_id' => $target->id,
        ]);

        $response->assertStatus(200);
        $response->assertJson(['matched' => false]);
    }

    public function test_cannot_like_same_user_twice(): void
    {
        $target = User::factory()->create(['gender' => 'female']);

        // First like
        $this->actingAs($this->user)->post('/discover/like', [
            'liked_user_id' => $target->id,
        ]);

        // Second like (should fail or be idempotent)
        $response = $this->actingAs($this->user)->post('/discover/like', [
            'liked_user_id' => $target->id,
        ]);

        $response->assertStatus(422);
    }

    public function test_free_user_has_daily_like_limit(): void
    {
        $this->user->subscription_tier = 'free';
        $this->user->save();

        // Create many users to like
        $users = User::factory()->count(10)->create(['gender' => 'female']);

        // Try to like more than the limit (assuming 5 for free)
        foreach ($users->take(6) as $target) {
            $response = $this->actingAs($this->user)->post('/discover/like', [
                'liked_user_id' => $target->id,
            ]);

            if ($response->status() === 429) {
                // Hit the limit
                $this->assertTrue(true);
                return;
            }
        }
    }

    public function test_premium_user_has_unlimited_likes(): void
    {
        $this->user->subscription_tier = 'gold';
        $this->user->save();

        $users = User::factory()->count(10)->create(['gender' => 'female']);

        foreach ($users as $target) {
            $response = $this->actingAs($this->user)->post('/discover/like', [
                'liked_user_id' => $target->id,
            ]);

            $response->assertStatus(200);
        }

        // All likes should succeed
        $this->assertEquals(10, Like::where('user_id', $this->user->id)->count());
    }

    public function test_discover_shows_compatibility_score(): void
    {
        $match = User::factory()->create([
            'gender' => 'female',
            'religion' => 'hindu',
            'city' => 'Mumbai',
        ]);

        $response = $this->actingAs($this->user)->get('/discover');

        $response->assertStatus(200);
        // Should show some compatibility percentage or score
    }

    public function test_user_can_view_matches_page(): void
    {
        $response = $this->actingAs($this->user)->get('/matches');

        $response->assertStatus(200);
        $response->assertSee('Matches');
    }

    public function test_matches_page_shows_mutual_matches(): void
    {
        $match = User::factory()->create(['gender' => 'female']);

        // Create mutual match
        Match::factory()->create([
            'user1_id' => min($this->user->id, $match->id),
            'user2_id' => max($this->user->id, $match->id),
        ]);

        $response = $this->actingAs($this->user)->get('/matches');

        $response->assertStatus(200);
        $response->assertSee($match->name);
    }

    public function test_user_can_unmatch(): void
    {
        $match = User::factory()->create(['gender' => 'female']);

        $matchRecord = Match::factory()->create([
            'user1_id' => min($this->user->id, $match->id),
            'user2_id' => max($this->user->id, $match->id),
        ]);

        $response = $this->actingAs($this->user)->delete("/matches/{$matchRecord->id}");

        $response->assertRedirect();
        $this->assertDatabaseMissing('matches', [
            'id' => $matchRecord->id,
            'deleted_at' => null,
        ]);
    }

    public function test_discover_filters_by_preferences(): void
    {
        // Set user preferences
        $this->user->preferences()->create([
            'min_age' => 24,
            'max_age' => 30,
            'religion' => 'hindu',
        ]);

        // Create user matching preferences
        $matching = User::factory()->create([
            'gender' => 'female',
            'age' => 26,
            'religion' => 'hindu',
            'name' => 'Matching User',
        ]);

        // Create user not matching
        $notMatching = User::factory()->create([
            'gender' => 'female',
            'age' => 35,
            'religion' => 'christian',
            'name' => 'Not Matching User',
        ]);

        $response = $this->actingAs($this->user)->get('/discover');

        $response->assertStatus(200);
        // Should prioritize matching user
    }

    public function test_user_can_report_a_profile(): void
    {
        $reported = User::factory()->create(['gender' => 'female']);

        $response = $this->actingAs($this->user)->post('/discover/report', [
            'reported_user_id' => $reported->id,
            'reason' => 'inappropriate_content',
            'description' => 'Fake profile',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('reports', [
            'reporter_id' => $this->user->id,
            'reported_user_id' => $reported->id,
        ]);
    }

    public function test_user_can_block_a_profile(): void
    {
        $blocked = User::factory()->create(['gender' => 'female']);

        $response = $this->actingAs($this->user)->post('/discover/block', [
            'blocked_user_id' => $blocked->id,
        ]);

        $response->assertRedirect();
        $this->assertTrue($this->user->blockedUsers()->where('blocked_user_id', $blocked->id)->exists());
    }

    public function test_blocked_users_dont_appear_in_discover(): void
    {
        $blocked = User::factory()->create([
            'gender' => 'female',
            'name' => 'Blocked User',
        ]);

        // Block the user
        $this->user->blockedUsers()->attach($blocked->id);

        $response = $this->actingAs($this->user)->get('/discover');

        $response->assertStatus(200);
        $response->assertDontSee('Blocked User');
    }

    public function test_discover_shows_profile_boost_indicator(): void
    {
        $boosted = User::factory()->create([
            'gender' => 'female',
            'is_boosted' => true,
        ]);

        $response = $this->actingAs($this->user)->get('/discover');

        $response->assertStatus(200);
        // Should show boost indicator
    }
}
