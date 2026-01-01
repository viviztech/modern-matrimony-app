<?php

namespace App\Services;

use App\Models\User;
use App\Models\Notification;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class NotificationService
{
    /**
     * Send a notification to a user.
     */
    public function send(User $user, string $type, string $title, string $message, array $data = []): Notification
    {
        $notification = Notification::create([
            'user_id' => $user->id,
            'type' => $type,
            'title' => $title,
            'message' => $message,
            'data' => $data,
            'is_read' => false,
        ]);

        // Send email if user has email notifications enabled
        if ($this->shouldSendEmail($user, $type)) {
            $this->sendEmailNotification($user, $notification);
        }

        // Send push notification if enabled
        if ($this->shouldSendPush($user, $type)) {
            $this->sendPushNotification($user, $notification);
        }

        return $notification;
    }

    /**
     * Send new match notification.
     */
    public function sendMatchNotification(User $user, User $match): void
    {
        $this->send(
            $user,
            'match',
            'New Match!',
            "You have a new match with {$match->name}!",
            [
                'match_id' => $match->id,
                'match_name' => $match->name,
                'match_photo' => $match->profile_photo_url,
            ]
        );
    }

    /**
     * Send new message notification.
     */
    public function sendMessageNotification(User $user, User $sender, string $messagePreview): void
    {
        $this->send(
            $user,
            'message',
            'New Message',
            "{$sender->name}: {$messagePreview}",
            [
                'sender_id' => $sender->id,
                'sender_name' => $sender->name,
                'sender_photo' => $sender->profile_photo_url,
            ]
        );
    }

    /**
     * Send like notification.
     */
    public function sendLikeNotification(User $user, User $liker): void
    {
        // Only send to premium users (free users don't see who liked them)
        if (!$user->is_premium) {
            return;
        }

        $this->send(
            $user,
            'like',
            'Someone Liked You!',
            "{$liker->name} liked your profile",
            [
                'liker_id' => $liker->id,
                'liker_name' => $liker->name,
                'liker_photo' => $liker->profile_photo_url,
            ]
        );
    }

    /**
     * Send profile view notification.
     */
    public function sendProfileViewNotification(User $user, User $viewer): void
    {
        // Only send to premium users
        if (!$user->is_premium) {
            return;
        }

        $this->send(
            $user,
            'profile_view',
            'Profile View',
            "{$viewer->name} viewed your profile",
            [
                'viewer_id' => $viewer->id,
                'viewer_name' => $viewer->name,
                'viewer_photo' => $viewer->profile_photo_url,
            ]
        );
    }

    /**
     * Send subscription expiring notification.
     */
    public function sendSubscriptionExpiringNotification(User $user, int $daysRemaining): void
    {
        $this->send(
            $user,
            'subscription_expiring',
            'Subscription Expiring Soon',
            "Your subscription will expire in {$daysRemaining} days. Renew now to continue enjoying premium features!",
            [
                'days_remaining' => $daysRemaining,
                'subscription_plan' => $user->currentPlan()?->name,
            ]
        );
    }

    /**
     * Send verification approved notification.
     */
    public function sendVerificationApprovedNotification(User $user, string $verificationType): void
    {
        $this->send(
            $user,
            'verification_approved',
            'Verification Approved',
            "Your {$verificationType} verification has been approved!",
            [
                'verification_type' => $verificationType,
            ]
        );
    }

    /**
     * Send verification rejected notification.
     */
    public function sendVerificationRejectedNotification(User $user, string $verificationType, string $reason): void
    {
        $this->send(
            $user,
            'verification_rejected',
            'Verification Rejected',
            "Your {$verificationType} verification was rejected. Reason: {$reason}",
            [
                'verification_type' => $verificationType,
                'reason' => $reason,
            ]
        );
    }

    /**
     * Send daily digest notification.
     */
    public function sendDailyDigest(User $user, array $stats): void
    {
        $this->send(
            $user,
            'daily_digest',
            'Your Daily Digest',
            "You have {$stats['new_likes']} new likes, {$stats['profile_views']} profile views, and {$stats['new_matches']} new matches!",
            $stats
        );
    }

    /**
     * Check if email notification should be sent.
     */
    protected function shouldSendEmail(User $user, string $type): bool
    {
        $preferences = $user->notification_preferences ?? [];

        // Default to true for important notifications
        $importantTypes = ['match', 'message', 'subscription_expiring'];

        if (in_array($type, $importantTypes)) {
            return $preferences['email_notifications'] ?? true;
        }

        return $preferences["email_{$type}"] ?? false;
    }

    /**
     * Check if push notification should be sent.
     */
    protected function shouldSendPush(User $user, string $type): bool
    {
        $preferences = $user->notification_preferences ?? [];

        return $preferences['push_notifications'] ?? true;
    }

    /**
     * Send email notification.
     */
    protected function sendEmailNotification(User $user, Notification $notification): void
    {
        try {
            // Queue email sending
            // Mail::to($user->email)->queue(new GenericNotificationMail($notification));

            Log::info('Email notification queued', [
                'user_id' => $user->id,
                'notification_id' => $notification->id,
                'type' => $notification->type,
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to queue email notification', [
                'user_id' => $user->id,
                'notification_id' => $notification->id,
                'error' => $e->getMessage(),
            ]);
        }
    }

    /**
     * Send push notification.
     */
    protected function sendPushNotification(User $user, Notification $notification): void
    {
        // Implement push notification logic here
        // Could use Firebase Cloud Messaging, OneSignal, etc.

        Log::info('Push notification sent', [
            'user_id' => $user->id,
            'notification_id' => $notification->id,
            'type' => $notification->type,
        ]);
    }

    /**
     * Mark notification as read.
     */
    public function markAsRead(Notification $notification): void
    {
        $notification->update([
            'is_read' => true,
            'read_at' => now(),
        ]);
    }

    /**
     * Mark all user notifications as read.
     */
    public function markAllAsRead(User $user): int
    {
        return Notification::where('user_id', $user->id)
            ->where('is_read', false)
            ->update([
                'is_read' => true,
                'read_at' => now(),
            ]);
    }

    /**
     * Get unread notification count.
     */
    public function getUnreadCount(User $user): int
    {
        return Notification::where('user_id', $user->id)
            ->where('is_read', false)
            ->count();
    }

    /**
     * Delete old notifications.
     */
    public function deleteOldNotifications(User $user, int $daysOld = 30): int
    {
        return Notification::where('user_id', $user->id)
            ->where('created_at', '<', now()->subDays($daysOld))
            ->where('is_read', true)
            ->delete();
    }

    /**
     * Get notification preferences form user.
     */
    public function getNotificationPreferences(User $user): array
    {
        $defaults = [
            'email_notifications' => true,
            'push_notifications' => true,
            'email_match' => true,
            'email_message' => true,
            'email_like' => true,
            'email_profile_view' => false,
            'email_daily_digest' => true,
            'push_match' => true,
            'push_message' => true,
            'push_like' => true,
            'push_profile_view' => false,
        ];

        return array_merge($defaults, $user->notification_preferences ?? []);
    }

    /**
     * Update notification preferences.
     */
    public function updateNotificationPreferences(User $user, array $preferences): void
    {
        $user->update([
            'notification_preferences' => array_merge(
                $user->notification_preferences ?? [],
                $preferences
            ),
        ]);
    }

    /**
     * Send bulk notifications.
     */
    public function sendBulk(array $userIds, string $type, string $title, string $message, array $data = []): int
    {
        $count = 0;

        foreach ($userIds as $userId) {
            try {
                $user = User::find($userId);
                if ($user) {
                    $this->send($user, $type, $title, $message, $data);
                    $count++;
                }
            } catch (\Exception $e) {
                Log::error('Failed to send bulk notification', [
                    'user_id' => $userId,
                    'error' => $e->getMessage(),
                ]);
            }
        }

        return $count;
    }
}
