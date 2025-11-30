<?php

namespace Database\Seeders;

use App\Models\SubscriptionPlan;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SubscriptionPlanSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $plans = [
            [
                'name' => 'Free',
                'slug' => 'free',
                'description' => 'Get started with basic features',
                'price_monthly' => 0,
                'price_yearly' => 0,
                'display_order' => 1,
                'features' => [
                    'swipes_per_day' => 50,
                    'likes_per_day' => 10,
                    'video_calls_per_week' => 3,
                    'basic_filters' => true,
                    'see_limited_matches' => true,
                    'unlimited_swipes' => false,
                    'unlimited_likes' => false,
                    'see_who_liked' => false,
                    'unlimited_video_calls' => false,
                    'super_likes_per_week' => 0,
                    'advanced_filters' => false,
                    'read_receipts' => false,
                    'rewind_feature' => false,
                    'priority_placement' => false,
                    'incognito_mode' => false,
                    'background_verification' => false,
                    'profile_boost_per_month' => 0,
                    'message_before_matching' => false,
                    'video_call_recording' => false,
                    'relationship_manager' => false,
                    'coaching_session' => false,
                    'private_events' => false,
                    'featured_profile' => false,
                    'photo_shoot_per_year' => 0,
                    'priority_support' => false,
                ],
            ],
            [
                'name' => 'Gold',
                'slug' => 'gold',
                'description' => 'Unlock premium features for serious connections',
                'price_monthly' => 499,
                'price_yearly' => 4999, // 17% discount
                'display_order' => 2,
                'features' => [
                    'swipes_per_day' => -1, // Unlimited
                    'likes_per_day' => -1, // Unlimited
                    'video_calls_per_week' => -1, // Unlimited
                    'basic_filters' => true,
                    'see_limited_matches' => false,
                    'unlimited_swipes' => true,
                    'unlimited_likes' => true,
                    'see_who_liked' => true,
                    'unlimited_video_calls' => true,
                    'super_likes_per_week' => 5,
                    'advanced_filters' => true,
                    'read_receipts' => true,
                    'rewind_feature' => true,
                    'priority_placement' => false,
                    'incognito_mode' => false,
                    'background_verification' => false,
                    'profile_boost_per_month' => 0,
                    'message_before_matching' => false,
                    'video_call_recording' => false,
                    'relationship_manager' => false,
                    'coaching_session' => false,
                    'private_events' => false,
                    'featured_profile' => false,
                    'photo_shoot_per_year' => 0,
                    'priority_support' => false,
                ],
            ],
            [
                'name' => 'Platinum',
                'slug' => 'platinum',
                'description' => 'Maximum visibility and exclusive features',
                'price_monthly' => 999,
                'price_yearly' => 9999, // 17% discount
                'display_order' => 3,
                'features' => [
                    'swipes_per_day' => -1,
                    'likes_per_day' => -1,
                    'video_calls_per_week' => -1,
                    'basic_filters' => true,
                    'see_limited_matches' => false,
                    'unlimited_swipes' => true,
                    'unlimited_likes' => true,
                    'see_who_liked' => true,
                    'unlimited_video_calls' => true,
                    'super_likes_per_week' => 10,
                    'advanced_filters' => true,
                    'read_receipts' => true,
                    'rewind_feature' => true,
                    'priority_placement' => true, // 2x visibility
                    'incognito_mode' => true,
                    'background_verification' => true,
                    'profile_boost_per_month' => 1,
                    'message_before_matching' => true,
                    'video_call_recording' => true,
                    'relationship_manager' => false,
                    'coaching_session' => false,
                    'private_events' => false,
                    'featured_profile' => false,
                    'photo_shoot_per_year' => 0,
                    'priority_support' => false,
                ],
            ],
            [
                'name' => 'Elite',
                'slug' => 'elite',
                'description' => 'White-glove service with dedicated support',
                'price_monthly' => 2999,
                'price_yearly' => 29999, // 17% discount
                'display_order' => 4,
                'features' => [
                    'swipes_per_day' => -1,
                    'likes_per_day' => -1,
                    'video_calls_per_week' => -1,
                    'basic_filters' => true,
                    'see_limited_matches' => false,
                    'unlimited_swipes' => true,
                    'unlimited_likes' => true,
                    'see_who_liked' => true,
                    'unlimited_video_calls' => true,
                    'super_likes_per_week' => -1, // Unlimited
                    'advanced_filters' => true,
                    'read_receipts' => true,
                    'rewind_feature' => true,
                    'priority_placement' => true,
                    'incognito_mode' => true,
                    'background_verification' => true,
                    'profile_boost_per_month' => 4, // Once a week
                    'message_before_matching' => true,
                    'video_call_recording' => true,
                    'relationship_manager' => true,
                    'coaching_session' => true, // Monthly
                    'private_events' => true,
                    'featured_profile' => true,
                    'photo_shoot_per_year' => 2,
                    'priority_support' => true,
                ],
            ],
        ];

        foreach ($plans as $plan) {
            SubscriptionPlan::create($plan);
        }

        $this->command->info('✅ Created ' . count($plans) . ' subscription plans');
    }
}
