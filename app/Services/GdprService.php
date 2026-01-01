<?php

namespace App\Services;

use App\Models\User;
use App\Models\AuditLog;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade\Pdf;
use ZipArchive;

class GdprService
{
    /**
     * Collect all user data for GDPR export
     */
    public function collectUserData(User $user): array
    {
        return [
            'personal_information' => $this->getPersonalInformation($user),
            'profile_data' => $this->getProfileData($user),
            'preferences' => $this->getPreferences($user),
            'photos' => $this->getPhotos($user),
            'matches' => $this->getMatches($user),
            'likes_given' => $this->getLikesGiven($user),
            'likes_received' => $this->getLikesReceived($user),
            'messages_sent' => $this->getMessagesSent($user),
            'messages_received' => $this->getMessagesReceived($user),
            'conversations' => $this->getConversations($user),
            'video_calls' => $this->getVideoCalls($user),
            'subscriptions' => $this->getSubscriptions($user),
            'payments' => $this->getPayments($user),
            'reports_made' => $this->getReportsMade($user),
            'reports_received' => $this->getReportsReceived($user),
            'verifications' => $this->getVerifications($user),
            'social_accounts' => $this->getSocialAccounts($user),
            'saved_searches' => $this->getSavedSearches($user),
            'profile_boosts' => $this->getProfileBoosts($user),
            'stories' => $this->getStories($user),
            'game_activities' => $this->getGameActivities($user),
            'notifications' => $this->getNotifications($user),
            'audit_logs' => $this->getAuditLogs($user),
            'export_metadata' => [
                'exported_at' => now()->toIso8601String(),
                'export_version' => '1.0',
                'user_id' => $user->id,
            ],
        ];
    }

    /**
     * Get personal information
     */
    protected function getPersonalInformation(User $user): array
    {
        return [
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'phone' => $user->phone,
            'date_of_birth' => $user->dob?->format('Y-m-d'),
            'age' => $user->age,
            'gender' => $user->gender,
            'city' => $user->city,
            'state' => $user->state,
            'country' => $user->country,
            'latitude' => $user->latitude,
            'longitude' => $user->longitude,
            'email_verified_at' => $user->email_verified_at?->toIso8601String(),
            'phone_verified_at' => $user->phone_verified_at?->toIso8601String(),
            'video_verified_at' => $user->video_verified_at?->toIso8601String(),
            'created_at' => $user->created_at->toIso8601String(),
            'updated_at' => $user->updated_at->toIso8601String(),
            'last_login_at' => $user->last_login_at?->toIso8601String(),
            'last_login_ip' => $user->last_login_ip,
            'last_active_at' => $user->last_active_at?->toIso8601String(),
        ];
    }

    /**
     * Get profile data
     */
    protected function getProfileData(User $user): ?array
    {
        $profile = $user->profile;
        if (!$profile) {
            return null;
        }

        return $profile->toArray();
    }

    /**
     * Get user preferences
     */
    protected function getPreferences(User $user): ?array
    {
        $preference = $user->preference;
        if (!$preference) {
            return null;
        }

        return $preference->toArray();
    }

    /**
     * Get photos
     */
    protected function getPhotos(User $user): array
    {
        return $user->photos->map(function ($photo) {
            return [
                'id' => $photo->id,
                'url' => $photo->url,
                'is_primary' => $photo->is_primary,
                'status' => $photo->status,
                'order' => $photo->order,
                'uploaded_at' => $photo->created_at->toIso8601String(),
            ];
        })->toArray();
    }

    /**
     * Get matches
     */
    protected function getMatches(User $user): array
    {
        return $user->matches->map(function ($match) {
            return [
                'matched_with_user_id' => $match->matched_user_id,
                'matched_at' => $match->created_at->toIso8601String(),
                'is_active' => $match->is_active,
            ];
        })->toArray();
    }

    /**
     * Get likes given
     */
    protected function getLikesGiven(User $user): array
    {
        return $user->likes->map(function ($like) {
            return [
                'liked_user_id' => $like->liked_user_id,
                'liked_at' => $like->created_at->toIso8601String(),
            ];
        })->toArray();
    }

    /**
     * Get likes received
     */
    protected function getLikesReceived(User $user): array
    {
        return $user->likedBy->map(function ($like) {
            return [
                'liked_by_user_id' => $like->user_id,
                'liked_at' => $like->created_at->toIso8601String(),
            ];
        })->toArray();
    }

    /**
     * Get messages sent
     */
    protected function getMessagesSent(User $user): array
    {
        return $user->sentMessages->map(function ($message) {
            return [
                'id' => $message->id,
                'receiver_id' => $message->receiver_id,
                'content' => $message->content,
                'type' => $message->type,
                'sent_at' => $message->created_at->toIso8601String(),
                'read_at' => $message->read_at?->toIso8601String(),
            ];
        })->toArray();
    }

    /**
     * Get messages received
     */
    protected function getMessagesReceived(User $user): array
    {
        return $user->receivedMessages->map(function ($message) {
            return [
                'id' => $message->id,
                'sender_id' => $message->sender_id,
                'content' => $message->content,
                'type' => $message->type,
                'received_at' => $message->created_at->toIso8601String(),
                'read_at' => $message->read_at?->toIso8601String(),
            ];
        })->toArray();
    }

    /**
     * Get conversations
     */
    protected function getConversations(User $user): array
    {
        return $user->conversations->map(function ($conversation) use ($user) {
            $otherUserId = $conversation->user_one_id === $user->id
                ? $conversation->user_two_id
                : $conversation->user_one_id;

            return [
                'id' => $conversation->id,
                'with_user_id' => $otherUserId,
                'created_at' => $conversation->created_at->toIso8601String(),
                'last_message_at' => $conversation->last_message_at?->toIso8601String(),
            ];
        })->toArray();
    }

    /**
     * Get video calls
     */
    protected function getVideoCalls(User $user): array
    {
        return $user->videoCalls->map(function ($call) use ($user) {
            return [
                'id' => $call->id,
                'caller_id' => $call->caller_id,
                'receiver_id' => $call->receiver_id,
                'role' => $call->caller_id === $user->id ? 'caller' : 'receiver',
                'status' => $call->status,
                'started_at' => $call->started_at?->toIso8601String(),
                'ended_at' => $call->ended_at?->toIso8601String(),
                'duration' => $call->duration,
            ];
        })->toArray();
    }

    /**
     * Get subscriptions
     */
    protected function getSubscriptions(User $user): array
    {
        return $user->subscriptions->map(function ($subscription) {
            return [
                'id' => $subscription->id,
                'plan_name' => $subscription->plan->name,
                'status' => $subscription->status,
                'started_at' => $subscription->starts_at->toIso8601String(),
                'expires_at' => $subscription->expires_at?->toIso8601String(),
                'cancelled_at' => $subscription->cancelled_at?->toIso8601String(),
            ];
        })->toArray();
    }

    /**
     * Get payments
     */
    protected function getPayments(User $user): array
    {
        return $user->payments->map(function ($payment) {
            return [
                'id' => $payment->id,
                'amount' => $payment->amount,
                'currency' => $payment->currency,
                'status' => $payment->status,
                'payment_method' => $payment->payment_method,
                'paid_at' => $payment->paid_at?->toIso8601String(),
                'created_at' => $payment->created_at->toIso8601String(),
            ];
        })->toArray();
    }

    /**
     * Get reports made
     */
    protected function getReportsMade(User $user): array
    {
        return $user->reportsMade->map(function ($report) {
            return [
                'id' => $report->id,
                'reported_user_id' => $report->reported_user_id,
                'reason' => $report->reason,
                'details' => $report->details,
                'status' => $report->status,
                'reported_at' => $report->created_at->toIso8601String(),
            ];
        })->toArray();
    }

    /**
     * Get reports received
     */
    protected function getReportsReceived(User $user): array
    {
        return $user->reportsReceived->map(function ($report) {
            return [
                'id' => $report->id,
                'reporter_id' => $report->reporter_id,
                'reason' => $report->reason,
                'status' => $report->status,
                'reported_at' => $report->created_at->toIso8601String(),
            ];
        })->toArray();
    }

    /**
     * Get verifications
     */
    protected function getVerifications(User $user): array
    {
        return $user->verifications->map(function ($verification) {
            return [
                'id' => $verification->id,
                'type' => $verification->type,
                'status' => $verification->status,
                'verified_at' => $verification->verified_at?->toIso8601String(),
            ];
        })->toArray();
    }

    /**
     * Get social accounts
     */
    protected function getSocialAccounts(User $user): array
    {
        return $user->socialAccounts->map(function ($account) {
            return [
                'provider' => $account->provider,
                'provider_id' => $account->provider_id,
                'is_verified' => $account->is_verified,
                'connected_at' => $account->created_at->toIso8601String(),
            ];
        })->toArray();
    }

    /**
     * Get saved searches
     */
    protected function getSavedSearches(User $user): array
    {
        return $user->savedSearches->map(function ($search) {
            return [
                'id' => $search->id,
                'name' => $search->name,
                'criteria' => $search->criteria,
                'created_at' => $search->created_at->toIso8601String(),
            ];
        })->toArray();
    }

    /**
     * Get profile boosts
     */
    protected function getProfileBoosts(User $user): array
    {
        return $user->profileBoosts->map(function ($boost) {
            return [
                'id' => $boost->id,
                'started_at' => $boost->started_at->toIso8601String(),
                'expires_at' => $boost->expires_at->toIso8601String(),
            ];
        })->toArray();
    }

    /**
     * Get stories
     */
    protected function getStories(User $user): array
    {
        if (!$user->relationLoaded('stories')) {
            $user->load('stories');
        }

        return $user->stories->map(function ($story) {
            return [
                'id' => $story->id,
                'type' => $story->type,
                'content' => $story->content,
                'views_count' => $story->views_count,
                'created_at' => $story->created_at->toIso8601String(),
                'expires_at' => $story->expires_at->toIso8601String(),
            ];
        })->toArray();
    }

    /**
     * Get game activities
     */
    protected function getGameActivities(User $user): array
    {
        if (!method_exists($user, 'gameActivities')) {
            return [];
        }

        return $user->gameActivities->map(function ($activity) {
            return [
                'game_type' => $activity->game_type,
                'score' => $activity->score,
                'played_at' => $activity->created_at->toIso8601String(),
            ];
        })->toArray();
    }

    /**
     * Get notifications
     */
    protected function getNotifications(User $user): array
    {
        return $user->notifications->map(function ($notification) {
            return [
                'id' => $notification->id,
                'type' => $notification->type,
                'data' => $notification->data,
                'read_at' => $notification->read_at?->toIso8601String(),
                'created_at' => $notification->created_at->toIso8601String(),
            ];
        })->toArray();
    }

    /**
     * Get audit logs
     */
    protected function getAuditLogs(User $user): array
    {
        if (!class_exists(AuditLog::class)) {
            return [];
        }

        return AuditLog::where('user_id', $user->id)
            ->get()
            ->map(function ($log) {
                return [
                    'action' => $log->action,
                    'ip_address' => $log->ip_address,
                    'user_agent' => $log->user_agent,
                    'created_at' => $log->created_at->toIso8601String(),
                ];
            })
            ->toArray();
    }

    /**
     * Export user data as JSON
     */
    public function exportAsJson(User $user): string
    {
        $data = $this->collectUserData($user);
        return json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    }

    /**
     * Export user data as PDF
     */
    public function exportAsPdf(User $user): string
    {
        $data = $this->collectUserData($user);

        $pdf = Pdf::loadView('gdpr.data-export-pdf', compact('data', 'user'));

        $filename = 'user-data-export-' . $user->id . '-' . now()->format('Y-m-d-His') . '.pdf';
        $path = 'exports/' . $filename;

        Storage::disk('local')->put($path, $pdf->output());

        return $path;
    }

    /**
     * Export user data as ZIP with JSON and photos
     */
    public function exportAsZip(User $user): string
    {
        $zipFilename = 'user-data-export-' . $user->id . '-' . now()->format('Y-m-d-His') . '.zip';
        $zipPath = storage_path('app/exports/' . $zipFilename);

        // Ensure directory exists
        if (!file_exists(storage_path('app/exports'))) {
            mkdir(storage_path('app/exports'), 0755, true);
        }

        $zip = new ZipArchive();
        if ($zip->open($zipPath, ZipArchive::CREATE | ZipArchive::OVERWRITE) !== true) {
            throw new \Exception('Cannot create ZIP file');
        }

        // Add JSON data
        $jsonData = $this->exportAsJson($user);
        $zip->addFromString('user-data.json', $jsonData);

        // Add photos
        foreach ($user->photos as $index => $photo) {
            $photoPath = public_path($photo->url);
            if (file_exists($photoPath)) {
                $zip->addFile($photoPath, 'photos/photo-' . ($index + 1) . '-' . basename($photo->url));
            }
        }

        // Add profile photo if exists
        if ($user->profile && $user->profile->avatar_url) {
            $avatarPath = public_path($user->profile->avatar_url);
            if (file_exists($avatarPath)) {
                $zip->addFile($avatarPath, 'avatar-' . basename($user->profile->avatar_url));
            }
        }

        $zip->close();

        return 'exports/' . $zipFilename;
    }

    /**
     * Request account deletion
     */
    public function requestDeletion(User $user, string $reason = null): void
    {
        $gracePeriodDays = config('gdpr.deletion_grace_period_days', 30);

        $user->update([
            'deletion_requested_at' => now(),
            'deletion_scheduled_for' => now()->addDays($gracePeriodDays),
            'deletion_reason' => $reason,
        ]);

        // Log the deletion request
        if (class_exists(AuditLog::class)) {
            AuditLog::create([
                'user_id' => $user->id,
                'action' => 'account_deletion_requested',
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent(),
                'metadata' => [
                    'reason' => $reason,
                    'scheduled_for' => $user->deletion_scheduled_for->toIso8601String(),
                ],
            ]);
        }

        // Send confirmation email
        $user->notify(new \App\Notifications\AccountDeletionRequested($user->deletion_scheduled_for));
    }

    /**
     * Cancel account deletion
     */
    public function cancelDeletion(User $user): void
    {
        $user->update([
            'deletion_requested_at' => null,
            'deletion_scheduled_for' => null,
            'deletion_reason' => null,
        ]);

        // Log the cancellation
        if (class_exists(AuditLog::class)) {
            AuditLog::create([
                'user_id' => $user->id,
                'action' => 'account_deletion_cancelled',
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent(),
            ]);
        }

        // Send confirmation email
        $user->notify(new \App\Notifications\AccountDeletionCancelled());
    }

    /**
     * Permanently delete user account and anonymize data
     */
    public function deleteAccount(User $user): void
    {
        DB::transaction(function () use ($user) {
            // Anonymize messages instead of deleting
            $user->sentMessages()->update([
                'content' => '[Message deleted]',
                'sender_id' => null,
            ]);

            $user->receivedMessages()->update([
                'receiver_id' => null,
            ]);

            // Delete photos
            foreach ($user->photos as $photo) {
                if (Storage::exists($photo->path)) {
                    Storage::delete($photo->path);
                }
                $photo->delete();
            }

            // Delete profile data
            $user->profile?->delete();
            $user->preference?->delete();

            // Delete relationships
            $user->likes()->delete();
            $user->matches()->delete();
            $user->conversations()->delete();
            $user->subscriptions()->delete();
            $user->savedSearches()->delete();
            $user->profileBoosts()->delete();
            $user->verifications()->delete();
            $user->socialAccounts()->delete();

            // Keep anonymized reports for safety
            $user->reportsMade()->update(['reporter_id' => null]);
            $user->reportsReceived()->update([
                'reported_user_id' => null,
                'details' => '[User deleted]',
            ]);

            // Log final deletion
            if (class_exists(AuditLog::class)) {
                AuditLog::create([
                    'user_id' => $user->id,
                    'action' => 'account_permanently_deleted',
                    'ip_address' => request()->ip(),
                    'user_agent' => request()->userAgent(),
                ]);
            }

            // Soft delete or anonymize user record
            $user->update([
                'name' => 'Deleted User',
                'email' => 'deleted_' . $user->id . '@deleted.local',
                'phone' => null,
                'password' => bcrypt(str()->random(64)),
                'remember_token' => null,
            ]);

            // Finally, soft delete the user
            $user->delete();
        });
    }

    /**
     * Check if user can cancel deletion
     */
    public function canCancelDeletion(User $user): bool
    {
        return $user->deletion_requested_at !== null &&
               $user->deletion_scheduled_for !== null &&
               $user->deletion_scheduled_for->isFuture();
    }

    /**
     * Get days remaining before deletion
     */
    public function getDaysRemainingBeforeDeletion(User $user): ?int
    {
        if (!$user->deletion_scheduled_for) {
            return null;
        }

        return now()->diffInDays($user->deletion_scheduled_for, false);
    }
}
