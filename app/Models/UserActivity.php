<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserActivity extends Model
{
    use HasFactory;

    const UPDATED_AT = null;

    // Activity type constants
    const TYPE_LOGIN = 'login';
    const TYPE_LOGOUT = 'logout';
    const TYPE_PROFILE_VIEW = 'profile_view';
    const TYPE_PROFILE_EDIT = 'profile_edit';
    const TYPE_PHOTO_UPLOAD = 'photo_upload';
    const TYPE_PHOTO_DELETE = 'photo_delete';
    const TYPE_MESSAGE_SENT = 'message_sent';
    const TYPE_MESSAGE_RECEIVED = 'message_received';
    const TYPE_LIKE_SENT = 'like_sent';
    const TYPE_LIKE_RECEIVED = 'like_received';
    const TYPE_MATCH_CREATED = 'match_created';
    const TYPE_SEARCH_PERFORMED = 'search_performed';
    const TYPE_FILTER_APPLIED = 'filter_applied';
    const TYPE_SUBSCRIPTION_PURCHASED = 'subscription_purchased';
    const TYPE_SUBSCRIPTION_CANCELLED = 'subscription_cancelled';
    const TYPE_PROFILE_VERIFIED = 'profile_verified';
    const TYPE_STORY_POSTED = 'story_posted';
    const TYPE_STORY_VIEWED = 'story_viewed';
    const TYPE_GAME_PLAYED = 'game_played';
    const TYPE_ICEBREAKER_SENT = 'icebreaker_sent';
    const TYPE_SAVE_SEARCH = 'save_search';
    const TYPE_PASSWORD_CHANGED = 'password_changed';
    const TYPE_SETTINGS_UPDATED = 'settings_updated';
    const TYPE_NOTIFICATION_CLICKED = 'notification_clicked';
    const TYPE_EMAIL_OPENED = 'email_opened';
    const TYPE_EMAIL_CLICKED = 'email_clicked';

    protected $fillable = [
        'user_id',
        'activity_type',
        'activity_data',
        'ip_address',
        'user_agent',
        'referrer',
        'device_type',
    ];

    protected $casts = [
        'activity_data' => 'array',
        'created_at' => 'datetime',
    ];

    /**
     * Get the user who performed the activity.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Scope to filter by activity type.
     */
    public function scopeOfType($query, $type)
    {
        return $query->where('activity_type', $type);
    }

    /**
     * Scope to get activities within a date range.
     */
    public function scopeInDateRange($query, $startDate, $endDate)
    {
        return $query->whereBetween('created_at', [$startDate, $endDate]);
    }

    /**
     * Scope to get recent activities.
     */
    public function scopeRecent($query, $days = 30)
    {
        return $query->where('created_at', '>=', now()->subDays($days));
    }

    /**
     * Scope to get activities by device type.
     */
    public function scopeByDevice($query, $deviceType)
    {
        return $query->where('device_type', $deviceType);
    }

    /**
     * Get all activity types.
     */
    public static function getActivityTypes(): array
    {
        return [
            self::TYPE_LOGIN,
            self::TYPE_LOGOUT,
            self::TYPE_PROFILE_VIEW,
            self::TYPE_PROFILE_EDIT,
            self::TYPE_PHOTO_UPLOAD,
            self::TYPE_PHOTO_DELETE,
            self::TYPE_MESSAGE_SENT,
            self::TYPE_MESSAGE_RECEIVED,
            self::TYPE_LIKE_SENT,
            self::TYPE_LIKE_RECEIVED,
            self::TYPE_MATCH_CREATED,
            self::TYPE_SEARCH_PERFORMED,
            self::TYPE_FILTER_APPLIED,
            self::TYPE_SUBSCRIPTION_PURCHASED,
            self::TYPE_SUBSCRIPTION_CANCELLED,
            self::TYPE_PROFILE_VERIFIED,
            self::TYPE_STORY_POSTED,
            self::TYPE_STORY_VIEWED,
            self::TYPE_GAME_PLAYED,
            self::TYPE_ICEBREAKER_SENT,
            self::TYPE_SAVE_SEARCH,
            self::TYPE_PASSWORD_CHANGED,
            self::TYPE_SETTINGS_UPDATED,
            self::TYPE_NOTIFICATION_CLICKED,
            self::TYPE_EMAIL_OPENED,
            self::TYPE_EMAIL_CLICKED,
        ];
    }
}
