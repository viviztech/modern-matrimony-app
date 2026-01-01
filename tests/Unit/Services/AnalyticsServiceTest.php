<?php

namespace Tests\Unit\Services;

use Tests\TestCase;
use App\Services\AnalyticsService;
use App\Models\User;
use App\Models\ProfileView;
use App\Models\UserActivity;
use App\Models\EngagementMetric;
use App\Models\Subscription;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Mockery;

class AnalyticsServiceTest extends TestCase
{
    use RefreshDatabase;

    protected AnalyticsService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = new AnalyticsService();
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    /** @test */
    public function it_tracks_profile_view()
    {
        $viewer = User::factory()->create();
        $profile = User::factory()->create();

        $profileView = $this->service->trackProfileView(
            $viewer,
            $profile,
            'discover',
            120
        );

        $this->assertInstanceOf(ProfileView::class, $profileView);
        $this->assertEquals($viewer->id, $profileView->viewer_id);
        $this->assertEquals($profile->id, $profileView->profile_id);
        $this->assertEquals('discover', $profileView->source);
        $this->assertEquals(120, $profileView->duration_seconds);

        // Also verify activity was tracked
        $this->assertDatabaseHas('user_activities', [
            'user_id' => $viewer->id,
            'activity_type' => UserActivity::TYPE_PROFILE_VIEW,
        ]);
    }

    /** @test */
    public function it_tracks_user_activity()
    {
        $user = User::factory()->create();

        $activity = $this->service->trackActivity(
            $user,
            UserActivity::TYPE_MESSAGE_SENT,
            ['recipient_id' => 2, 'message' => 'Hello']
        );

        $this->assertInstanceOf(UserActivity::class, $activity);
        $this->assertEquals($user->id, $activity->user_id);
        $this->assertEquals(UserActivity::TYPE_MESSAGE_SENT, $activity->activity_type);
        $this->assertEquals(['recipient_id' => 2, 'message' => 'Hello'], $activity->activity_data);
        $this->assertNotNull($activity->ip_address);
        $this->assertNotNull($activity->device_type);
    }

    /** @test */
    public function it_detects_mobile_device()
    {
        $mobileUserAgent = 'Mozilla/5.0 (iPhone; CPU iPhone OS 14_0 like Mac OS X)';
        $deviceType = $this->invokeMethod(
            $this->service,
            'detectDeviceType',
            [$mobileUserAgent]
        );

        $this->assertEquals('mobile', $deviceType);
    }

    /** @test */
    public function it_detects_tablet_device()
    {
        $tabletUserAgent = 'Mozilla/5.0 (iPad; CPU OS 14_0 like Mac OS X)';
        $deviceType = $this->invokeMethod(
            $this->service,
            'detectDeviceType',
            [$tabletUserAgent]
        );

        $this->assertEquals('tablet', $deviceType);
    }

    /** @test */
    public function it_detects_desktop_device()
    {
        $desktopUserAgent = 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36';
        $deviceType = $this->invokeMethod(
            $this->service,
            'detectDeviceType',
            [$desktopUserAgent]
        );

        $this->assertEquals('desktop', $deviceType);
    }

    /** @test */
    public function it_handles_null_user_agent()
    {
        $deviceType = $this->invokeMethod(
            $this->service,
            'detectDeviceType',
            [null]
        );

        $this->assertEquals('unknown', $deviceType);
    }

    /** @test */
    public function it_calculates_user_engagement_metrics()
    {
        $user = User::factory()->create();

        // Create engagement metrics for the past 7 days
        for ($i = 0; $i < 7; $i++) {
            EngagementMetric::factory()->create([
                'user_id' => $user->id,
                'date' => now()->subDays($i)->toDateString(),
                'profile_views_count' => 10,
                'profile_viewed_by_count' => 5,
                'likes_sent_count' => 3,
                'likes_received_count' => 2,
                'messages_sent_count' => 4,
                'messages_received_count' => 3,
                'matches_count' => 1,
                'search_count' => 2,
                'time_spent_seconds' => 1800, // 30 minutes
                'login_count' => 1,
            ]);
        }

        Cache::shouldReceive('remember')
            ->once()
            ->andReturnUsing(function ($key, $ttl, $callback) {
                return $callback();
            });

        $metrics = $this->service->getUserEngagementMetrics($user, 7);

        $this->assertIsArray($metrics);
        $this->assertArrayHasKey('totals', $metrics);
        $this->assertArrayHasKey('daily_metrics', $metrics);
        $this->assertArrayHasKey('period', $metrics);

        // Verify totals
        $this->assertEquals(70, $metrics['totals']['total_profile_views']); // 10 * 7
        $this->assertEquals(35, $metrics['totals']['total_profile_viewed_by']); // 5 * 7
        $this->assertEquals(21, $metrics['totals']['total_likes_sent']); // 3 * 7
        $this->assertEquals(28, $metrics['totals']['total_messages_sent']); // 4 * 7
        $this->assertEquals(7, $metrics['totals']['total_matches']); // 1 * 7

        // Verify daily metrics count
        $this->assertCount(7, $metrics['daily_metrics']);
    }

    /** @test */
    public function it_calculates_response_rate()
    {
        $user = User::factory()->create();

        // Create 10 received messages
        for ($i = 0; $i < 10; $i++) {
            UserActivity::factory()->create([
                'user_id' => $user->id,
                'activity_type' => UserActivity::TYPE_MESSAGE_RECEIVED,
                'created_at' => now()->subDays($i),
            ]);
        }

        // Create 7 sent messages
        for ($i = 0; $i < 7; $i++) {
            UserActivity::factory()->create([
                'user_id' => $user->id,
                'activity_type' => UserActivity::TYPE_MESSAGE_SENT,
                'created_at' => now()->subDays($i),
            ]);
        }

        $responseRate = $this->invokeMethod(
            $this->service,
            'calculateResponseRate',
            [$user, 30]
        );

        $this->assertEquals(70.0, $responseRate); // 7/10 * 100 = 70%
    }

    /** @test */
    public function it_returns_zero_response_rate_when_no_messages_received()
    {
        $user = User::factory()->create();

        $responseRate = $this->invokeMethod(
            $this->service,
            'calculateResponseRate',
            [$user, 30]
        );

        $this->assertEquals(0, $responseRate);
    }

    /** @test */
    public function it_gets_daily_active_users()
    {
        $date = now()->toDateString();

        // Create activities for 5 different users on the same date
        for ($i = 1; $i <= 5; $i++) {
            $user = User::factory()->create();
            UserActivity::factory()->create([
                'user_id' => $user->id,
                'created_at' => $date,
            ]);
        }

        // Create multiple activities for the same user (should count as 1)
        $user = User::first();
        UserActivity::factory()->create([
            'user_id' => $user->id,
            'created_at' => $date,
        ]);

        Cache::shouldReceive('remember')
            ->once()
            ->andReturnUsing(function ($key, $ttl, $callback) {
                return $callback();
            });

        $dau = $this->service->getDailyActiveUsers($date);

        $this->assertEquals(5, $dau);
    }

    /** @test */
    public function it_gets_conversion_funnel_data()
    {
        // Create users at different stages
        User::factory()->count(10)->create(); // 10 signups

        // 8 verified
        User::factory()->count(8)->create([
            'email_verified_at' => now(),
        ]);

        // 6 with completed profiles
        User::factory()->count(6)->create([
            'email_verified_at' => now(),
            'bio' => 'Sample bio',
        ]);

        // 4 sent messages
        $messageSenders = User::factory()->count(4)->create();
        foreach ($messageSenders as $user) {
            UserActivity::factory()->create([
                'user_id' => $user->id,
                'activity_type' => UserActivity::TYPE_MESSAGE_SENT,
            ]);
        }

        // 2 got matches
        $matchedUsers = User::factory()->count(2)->create();
        foreach ($matchedUsers as $user) {
            UserActivity::factory()->create([
                'user_id' => $user->id,
                'activity_type' => UserActivity::TYPE_MATCH_CREATED,
            ]);
        }

        // 1 subscribed
        $subscribedUser = User::factory()->create();
        Subscription::factory()->create([
            'user_id' => $subscribedUser->id,
            'status' => 'active',
        ]);

        Cache::shouldReceive('remember')
            ->once()
            ->andReturnUsing(function ($key, $ttl, $callback) {
                return $callback();
            });

        $funnel = $this->service->getConversionFunnel();

        $this->assertIsArray($funnel);
        $this->assertCount(6, $funnel);

        // Verify stages exist
        $stages = collect($funnel)->pluck('stage')->toArray();
        $this->assertContains('Signups', $stages);
        $this->assertContains('Email Verified', $stages);
        $this->assertContains('Profile Completed', $stages);
        $this->assertContains('Sent Message', $stages);
        $this->assertContains('Got Match', $stages);
        $this->assertContains('Subscribed', $stages);
    }

    /** @test */
    public function it_gets_cohort_analysis()
    {
        $cohortDate = now()->subDays(14)->toDateString();

        // Create 5 users in the cohort
        $cohortUsers = User::factory()->count(5)->create([
            'created_at' => $cohortDate,
        ]);

        // Create activities for week 0 (all 5 users)
        foreach ($cohortUsers as $user) {
            UserActivity::factory()->create([
                'user_id' => $user->id,
                'created_at' => now()->subDays(14),
            ]);
        }

        // Create activities for week 1 (4 users)
        for ($i = 0; $i < 4; $i++) {
            UserActivity::factory()->create([
                'user_id' => $cohortUsers[$i]->id,
                'created_at' => now()->subDays(7),
            ]);
        }

        Cache::shouldReceive('remember')
            ->once()
            ->andReturnUsing(function ($key, $ttl, $callback) {
                return $callback();
            });

        $analysis = $this->service->getCohortAnalysis($cohortDate);

        $this->assertIsArray($analysis);
        $this->assertEquals($cohortDate, $analysis['cohort_date']);
        $this->assertEquals(5, $analysis['cohort_size']);
        $this->assertIsArray($analysis['retention']);
        $this->assertCount(13, $analysis['retention']); // 13 weeks (0-12)
    }

    /** @test */
    public function it_gets_profile_viewers()
    {
        $user = User::factory()->create();
        $viewers = User::factory()->count(5)->create();

        foreach ($viewers as $viewer) {
            ProfileView::factory()->create([
                'viewer_id' => $viewer->id,
                'profile_id' => $user->id,
                'viewed_at' => now()->subDays(rand(1, 10)),
            ]);
        }

        $profileViewers = $this->service->getProfileViewers($user, 30, 20);

        $this->assertCount(5, $profileViewers);
    }

    /** @test */
    public function it_gets_activity_sources()
    {
        $user = User::factory()->create();

        ProfileView::factory()->count(10)->create([
            'profile_id' => $user->id,
            'source' => 'discover',
            'viewed_at' => now(),
        ]);

        ProfileView::factory()->count(5)->create([
            'profile_id' => $user->id,
            'source' => 'search',
            'viewed_at' => now(),
        ]);

        ProfileView::factory()->count(3)->create([
            'profile_id' => $user->id,
            'source' => 'direct',
            'viewed_at' => now(),
        ]);

        $sources = $this->service->getActivitySources($user, 30);

        $this->assertIsArray($sources);
        $this->assertEquals(10, $sources['discover']);
        $this->assertEquals(5, $sources['search']);
        $this->assertEquals(3, $sources['direct']);
    }

    /** @test */
    public function it_gets_peak_usage_hours()
    {
        // Create activities at different hours
        for ($hour = 0; $hour < 24; $hour++) {
            $count = rand(1, 10);
            for ($i = 0; $i < $count; $i++) {
                UserActivity::factory()->create([
                    'created_at' => now()->setHour($hour),
                ]);
            }
        }

        Cache::shouldReceive('remember')
            ->once()
            ->andReturnUsing(function ($key, $ttl, $callback) {
                return $callback();
            });

        $peakHours = $this->service->getPeakUsageHours(7);

        $this->assertIsArray($peakHours);
        $this->assertLessThanOrEqual(24, count($peakHours));
    }

    /** @test */
    public function it_gets_user_demographics()
    {
        // Create users with different genders
        User::factory()->count(6)->create(['gender' => 'male']);
        User::factory()->count(4)->create(['gender' => 'female']);

        // Create users with different religions
        User::factory()->count(5)->create(['religion' => 'Hindu']);
        User::factory()->count(3)->create(['religion' => 'Muslim']);
        User::factory()->count(2)->create(['religion' => 'Christian']);

        Cache::shouldReceive('remember')
            ->once()
            ->andReturnUsing(function ($key, $ttl, $callback) {
                return $callback();
            });

        $demographics = $this->service->getUserDemographics();

        $this->assertIsArray($demographics);
        $this->assertArrayHasKey('gender', $demographics);
        $this->assertArrayHasKey('religion', $demographics);
        $this->assertArrayHasKey('age', $demographics);
    }

    /** @test */
    public function it_clears_analytics_cache()
    {
        Cache::shouldReceive('flush')
            ->once()
            ->andReturn(true);

        $this->service->clearCache();

        // If we reach here without exception, test passed
        $this->assertTrue(true);
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
