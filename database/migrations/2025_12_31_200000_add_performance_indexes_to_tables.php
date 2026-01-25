<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Users table indexes
        $this->createIndexIfNotExists('users', 'idx_users_active_premium', '(is_active, is_premium)');
        $this->createIndexIfNotExists('users', 'idx_users_gender_active', '(gender, is_active)');
        $this->createIndexIfNotExists('users', 'idx_users_location', '(city, state, country)');
        $this->createIndexIfNotExists('users', 'idx_users_created_at', '(created_at)');
        $this->createIndexIfNotExists('users', 'idx_users_last_active', '(last_active_at)');

        // Profiles table indexes
        $this->createIndexIfNotExists('profiles', 'idx_profiles_user_complete', '(user_id, profile_completed_at)');
        $this->createIndexIfNotExists('profiles', 'idx_profiles_religion_caste', '(religion, caste)');
        $this->createIndexIfNotExists('profiles', 'idx_profiles_education', '(education_level)');
        $this->createIndexIfNotExists('profiles', 'idx_profiles_income', '(annual_income)');
        $this->createIndexIfNotExists('profiles', 'idx_profiles_marital_status', '(marital_status)');
        $this->createIndexIfNotExists('profiles', 'idx_profiles_height', '(height)');

        // Photos table indexes
        $this->createIndexIfNotExists('photos', 'idx_photos_user_verified_primary', '(user_id, is_verified, is_primary)');
        $this->createIndexIfNotExists('photos', 'idx_photos_created_at', '(created_at)');

        // Likes table indexes
        $this->createIndexIfNotExists('likes', 'idx_likes_user_liked', '(user_id, liked_user_id)');
        $this->createIndexIfNotExists('likes', 'idx_likes_created_at', '(created_at)');
        $this->createIndexIfNotExists('likes', 'idx_likes_liked_user', '(liked_user_id)');

        // User matches table indexes
        $this->createIndexIfNotExists('user_matches', 'idx_matches_user_matched_active', '(user_id, matched_user_id, is_active)');
        $this->createIndexIfNotExists('user_matches', 'idx_matches_created_at', '(created_at)');
        $this->createIndexIfNotExists('user_matches', 'idx_matches_matched_active', '(matched_user_id, is_active)');

        // Messages table indexes
        $this->createIndexIfNotExists('messages', 'idx_messages_conversation_created', '(conversation_id, created_at)');
        $this->createIndexIfNotExists('messages', 'idx_messages_sender_read', '(sender_id, is_read)');
        $this->createIndexIfNotExists('messages', 'idx_messages_created_at', '(created_at)');

        // Conversations table indexes
        $this->createIndexIfNotExists('conversations', 'idx_conversations_users', '(user_one_id, user_two_id)');
        $this->createIndexIfNotExists('conversations', 'idx_conversations_last_message', '(last_message_at)');
        $this->createIndexIfNotExists('conversations', 'idx_conversations_user_two', '(user_two_id)');

        // Subscriptions table indexes
        $this->createIndexIfNotExists('subscriptions', 'idx_subscriptions_user_status_ends', '(user_id, status, ends_at)');
        $this->createIndexIfNotExists('subscriptions', 'idx_subscriptions_status_ends', '(status, ends_at)');

        // Payments table indexes
        $this->createIndexIfNotExists('payments', 'idx_payments_user_status', '(user_id, status)');
        $this->createIndexIfNotExists('payments', 'idx_payments_created_at', '(created_at)');
        $this->createIndexIfNotExists('payments', 'idx_payments_status', '(status)');

        // Stories table indexes
        if (Schema::hasTable('stories')) {
            $this->createIndexIfNotExists('stories', 'idx_stories_user_expires', '(user_id, expires_at)');
            $this->createIndexIfNotExists('stories', 'idx_stories_created_at', '(created_at)');
        }

        // Story views table indexes
        if (Schema::hasTable('story_views')) {
            $this->createIndexIfNotExists('story_views', 'idx_story_views_story_user', '(story_id, user_id)');
            $this->createIndexIfNotExists('story_views', 'idx_story_views_created_at', '(created_at)');
        }

        // Profile boosts table indexes
        if (Schema::hasTable('profile_boosts')) {
            $this->createIndexIfNotExists('profile_boosts', 'idx_boosts_user_active', '(user_id, is_active)');
            $this->createIndexIfNotExists('profile_boosts', 'idx_boosts_active_expires', '(is_active, expires_at)');
        }

        // Profile views table indexes
        if (Schema::hasTable('profile_views')) {
            $this->createIndexIfNotExists('profile_views', 'idx_views_viewed_user_created', '(viewed_user_id, created_at)');
            $this->createIndexIfNotExists('profile_views', 'idx_views_viewer_user', '(viewer_user_id)');
        }

        // Notifications table indexes
        if (Schema::hasTable('notifications')) {
            $this->createIndexIfNotExists('notifications', 'idx_notifications_notifiable_read', '(notifiable_type, notifiable_id, read_at)');
            $this->createIndexIfNotExists('notifications', 'idx_notifications_created_at', '(created_at)');
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Users table indexes
        Schema::table('users', function (Blueprint $table) {
            $table->dropIndex('idx_users_active_premium');
            $table->dropIndex('idx_users_gender_active');
            $table->dropIndex('idx_users_location');
            $table->dropIndex('idx_users_created_at');
            $table->dropIndex('idx_users_last_active');
        });

        // Profiles table indexes
        Schema::table('profiles', function (Blueprint $table) {
            $table->dropIndex('idx_profiles_user_complete');
            $table->dropIndex('idx_profiles_religion_caste');
            $table->dropIndex('idx_profiles_education');
            $table->dropIndex('idx_profiles_income');
            $table->dropIndex('idx_profiles_marital_status');
            $table->dropIndex('idx_profiles_height');
        });

        // Photos table indexes
        Schema::table('photos', function (Blueprint $table) {
            $table->dropIndex('idx_photos_user_verified_primary');
            $table->dropIndex('idx_photos_created_at');
        });

        // Likes table indexes
        Schema::table('likes', function (Blueprint $table) {
            $table->dropIndex('idx_likes_user_liked');
            $table->dropIndex('idx_likes_created_at');
            $table->dropIndex('idx_likes_liked_user');
        });

        // User matches table indexes
        Schema::table('user_matches', function (Blueprint $table) {
            $table->dropIndex('idx_matches_user_matched_active');
            $table->dropIndex('idx_matches_created_at');
            $table->dropIndex('idx_matches_matched_active');
        });

        // Messages table indexes
        Schema::table('messages', function (Blueprint $table) {
            $table->dropIndex('idx_messages_conversation_created');
            $table->dropIndex('idx_messages_sender_read');
            $table->dropIndex('idx_messages_created_at');
        });

        // Conversations table indexes
        Schema::table('conversations', function (Blueprint $table) {
            $table->dropIndex('idx_conversations_users');
            $table->dropIndex('idx_conversations_last_message');
            $table->dropIndex('idx_conversations_user_two');
        });

        // Subscriptions table indexes
        Schema::table('subscriptions', function (Blueprint $table) {
            $table->dropIndex('idx_subscriptions_user_status_ends');
            $table->dropIndex('idx_subscriptions_status_ends');
        });

        // Payments table indexes
        Schema::table('payments', function (Blueprint $table) {
            $table->dropIndex('idx_payments_user_status');
            $table->dropIndex('idx_payments_created_at');
            $table->dropIndex('idx_payments_status');
        });

        // Stories table indexes
        if (Schema::hasTable('stories')) {
            Schema::table('stories', function (Blueprint $table) {
                $table->dropIndex('idx_stories_user_expires');
                $table->dropIndex('idx_stories_created_at');
            });
        }

        // Story views table indexes
        if (Schema::hasTable('story_views')) {
            Schema::table('story_views', function (Blueprint $table) {
                $table->dropIndex('idx_story_views_story_user');
                $table->dropIndex('idx_story_views_created_at');
            });
        }

        // Profile boosts table indexes
        if (Schema::hasTable('profile_boosts')) {
            Schema::table('profile_boosts', function (Blueprint $table) {
                $table->dropIndex('idx_boosts_user_active');
                $table->dropIndex('idx_boosts_active_expires');
            });
        }

        // Profile views table indexes
        if (Schema::hasTable('profile_views')) {
            Schema::table('profile_views', function (Blueprint $table) {
                $table->dropIndex('idx_views_viewed_user_created');
                $table->dropIndex('idx_views_viewer_user');
            });
        }

        // Notifications table indexes
        if (Schema::hasTable('notifications')) {
            Schema::table('notifications', function (Blueprint $table) {
                $table->dropIndex('idx_notifications_notifiable_read');
                $table->dropIndex('idx_notifications_created_at');
            });
        }
    }

    /**
     * Create an index only if it doesn't exist.
     */
    protected function createIndexIfNotExists(string $table, string $indexName, string $columns): void
    {
        try {
            $exists = DB::select("SHOW INDEX FROM `{$table}` WHERE Key_name = ?", [$indexName]);
            if (empty($exists)) {
                DB::statement("ALTER TABLE `{$table}` ADD INDEX `{$indexName}` {$columns}");
            }
        } catch (\Exception $e) {
            // Index might already exist or table doesn't exist, ignore
        }
    }
};
