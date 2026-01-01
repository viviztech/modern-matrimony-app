<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Users table indexes
        Schema::table('users', function (Blueprint $table) {
            $table->index(['is_active', 'is_premium'], 'idx_users_active_premium');
            $table->index(['gender', 'is_active'], 'idx_users_gender_active');
            $table->index(['city', 'state', 'country'], 'idx_users_location');
            $table->index('created_at', 'idx_users_created_at');
            $table->index('last_active_at', 'idx_users_last_active');
        });

        // Profiles table indexes
        Schema::table('profiles', function (Blueprint $table) {
            $table->index(['user_id', 'is_complete'], 'idx_profiles_user_complete');
            $table->index(['religion', 'caste'], 'idx_profiles_religion_caste');
            $table->index('education_level', 'idx_profiles_education');
            $table->index('annual_income', 'idx_profiles_income');
            $table->index('marital_status', 'idx_profiles_marital_status');
            $table->index('height', 'idx_profiles_height');
        });

        // Photos table indexes
        Schema::table('photos', function (Blueprint $table) {
            $table->index(['user_id', 'is_verified', 'is_primary'], 'idx_photos_user_verified_primary');
            $table->index('created_at', 'idx_photos_created_at');
        });

        // Likes table indexes
        Schema::table('likes', function (Blueprint $table) {
            $table->index(['user_id', 'liked_user_id'], 'idx_likes_user_liked');
            $table->index('created_at', 'idx_likes_created_at');
            $table->index('liked_user_id', 'idx_likes_liked_user');
        });

        // User matches table indexes
        Schema::table('user_matches', function (Blueprint $table) {
            $table->index(['user_id', 'matched_user_id', 'is_active'], 'idx_matches_user_matched_active');
            $table->index('created_at', 'idx_matches_created_at');
            $table->index(['matched_user_id', 'is_active'], 'idx_matches_matched_active');
        });

        // Messages table indexes
        Schema::table('messages', function (Blueprint $table) {
            $table->index(['conversation_id', 'created_at'], 'idx_messages_conversation_created');
            $table->index(['sender_id', 'is_read'], 'idx_messages_sender_read');
            $table->index('created_at', 'idx_messages_created_at');
        });

        // Conversations table indexes
        Schema::table('conversations', function (Blueprint $table) {
            $table->index(['user_one_id', 'user_two_id'], 'idx_conversations_users');
            $table->index('last_message_at', 'idx_conversations_last_message');
            $table->index('user_two_id', 'idx_conversations_user_two');
        });

        // Subscriptions table indexes
        Schema::table('subscriptions', function (Blueprint $table) {
            $table->index(['user_id', 'status', 'ends_at'], 'idx_subscriptions_user_status_ends');
            $table->index(['status', 'ends_at'], 'idx_subscriptions_status_ends');
        });

        // Payments table indexes
        Schema::table('payments', function (Blueprint $table) {
            $table->index(['user_id', 'status'], 'idx_payments_user_status');
            $table->index('created_at', 'idx_payments_created_at');
            $table->index('status', 'idx_payments_status');
        });

        // Stories table indexes
        if (Schema::hasTable('stories')) {
            Schema::table('stories', function (Blueprint $table) {
                $table->index(['user_id', 'expires_at'], 'idx_stories_user_expires');
                $table->index('created_at', 'idx_stories_created_at');
            });
        }

        // Story views table indexes
        if (Schema::hasTable('story_views')) {
            Schema::table('story_views', function (Blueprint $table) {
                $table->index(['story_id', 'user_id'], 'idx_story_views_story_user');
                $table->index('created_at', 'idx_story_views_created_at');
            });
        }

        // Profile boosts table indexes
        if (Schema::hasTable('profile_boosts')) {
            Schema::table('profile_boosts', function (Blueprint $table) {
                $table->index(['user_id', 'is_active'], 'idx_boosts_user_active');
                $table->index(['is_active', 'expires_at'], 'idx_boosts_active_expires');
            });
        }

        // Profile views table indexes
        if (Schema::hasTable('profile_views')) {
            Schema::table('profile_views', function (Blueprint $table) {
                $table->index(['viewed_user_id', 'created_at'], 'idx_views_viewed_user_created');
                $table->index('viewer_user_id', 'idx_views_viewer_user');
            });
        }

        // Notifications table indexes
        if (Schema::hasTable('notifications')) {
            Schema::table('notifications', function (Blueprint $table) {
                $table->index(['notifiable_type', 'notifiable_id', 'read_at'], 'idx_notifications_notifiable_read');
                $table->index('created_at', 'idx_notifications_created_at');
            });
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
};
