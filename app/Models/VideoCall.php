<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class VideoCall extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'match_id',
        'conversation_id',
        'caller_id',
        'receiver_id',
        'status',
        'call_type',
        'started_at',
        'ended_at',
        'duration',
        'room_id',
        'recording_url',
        'quality_rating',
        'end_reason',
        'reported',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'started_at' => 'datetime',
        'ended_at' => 'datetime',
        'reported' => 'boolean',
    ];

    /**
     * Boot the model.
     */
    protected static function booted(): void
    {
        static::creating(function (VideoCall $call) {
            if (!$call->room_id) {
                $call->room_id = 'room_' . Str::random(32);
            }
        });
    }

    /**
     * Get the match associated with the call.
     */
    public function match(): BelongsTo
    {
        return $this->belongsTo(UserMatch::class, 'match_id');
    }

    /**
     * Get the conversation associated with the call.
     */
    public function conversation(): BelongsTo
    {
        return $this->belongsTo(Conversation::class);
    }

    /**
     * Get the caller.
     */
    public function caller(): BelongsTo
    {
        return $this->belongsTo(User::class, 'caller_id');
    }

    /**
     * Get the receiver.
     */
    public function receiver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'receiver_id');
    }

    /**
     * Start the call.
     */
    public function start(): void
    {
        $this->update([
            'status' => 'active',
            'started_at' => now(),
        ]);
    }

    /**
     * End the call.
     */
    public function end(?string $reason = null): void
    {
        $endedAt = now();
        $duration = $this->started_at ? $this->started_at->diffInSeconds($endedAt) : 0;

        $this->update([
            'status' => 'ended',
            'ended_at' => $endedAt,
            'duration' => $duration,
            'end_reason' => $reason,
        ]);
    }

    /**
     * Mark as missed.
     */
    public function markAsMissed(): void
    {
        $this->update(['status' => 'missed']);
    }

    /**
     * Mark as declined.
     */
    public function decline(?string $reason = null): void
    {
        $this->update([
            'status' => 'declined',
            'end_reason' => $reason,
        ]);
    }

    /**
     * Mark as ringing.
     */
    public function markAsRinging(): void
    {
        $this->update(['status' => 'ringing']);
    }

    /**
     * Rate the call quality.
     */
    public function rate(int $rating): void
    {
        $this->update(['quality_rating' => max(1, min(5, $rating))]);
    }

    /**
     * Report the call.
     */
    public function report(): void
    {
        $this->update(['reported' => true]);
    }

    /**
     * Check if call is active.
     */
    public function isActive(): bool
    {
        return $this->status === 'active';
    }

    /**
     * Check if call is ended.
     */
    public function isEnded(): bool
    {
        return in_array($this->status, ['ended', 'missed', 'declined', 'failed']);
    }

    /**
     * Check if call is in progress.
     */
    public function isInProgress(): bool
    {
        return in_array($this->status, ['initiated', 'ringing', 'active']);
    }

    /**
     * Get formatted duration.
     */
    public function getFormattedDurationAttribute(): string
    {
        if (!$this->duration) {
            return '0:00';
        }

        $minutes = floor($this->duration / 60);
        $seconds = $this->duration % 60;

        return sprintf('%d:%02d', $minutes, $seconds);
    }

    /**
     * Scope to get active calls.
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    /**
     * Scope to get calls for a user.
     */
    public function scopeForUser($query, int $userId)
    {
        return $query->where(function ($q) use ($userId) {
            $q->where('caller_id', $userId)
              ->orWhere('receiver_id', $userId);
        });
    }

    /**
     * Scope to get recent calls.
     */
    public function scopeRecent($query, int $days = 7)
    {
        return $query->where('created_at', '>=', now()->subDays($days));
    }

    /**
     * Check if user can initiate call with another user.
     */
    public static function canInitiate(User $caller, User $receiver): bool
    {
        // Check if they are matched
        $matched = UserMatch::where('user_id', $caller->id)
            ->where('matched_user_id', $receiver->id)
            ->where('is_active', true)
            ->exists();

        if (!$matched) {
            return false;
        }

        // Check if there's an active call already
        $activeCall = static::where(function ($q) use ($caller, $receiver) {
            $q->where('caller_id', $caller->id)->where('receiver_id', $receiver->id);
        })->orWhere(function ($q) use ($caller, $receiver) {
            $q->where('caller_id', $receiver->id)->where('receiver_id', $caller->id);
        })->whereIn('status', ['initiated', 'ringing', 'active'])->exists();

        return !$activeCall;
    }

    /**
     * Get call statistics for a user.
     */
    public static function getStatsForUser(User $user): array
    {
        $calls = static::forUser($user->id)->get();

        return [
            'total_calls' => $calls->count(),
            'completed_calls' => $calls->where('status', 'ended')->count(),
            'missed_calls' => $calls->where('status', 'missed')->count(),
            'declined_calls' => $calls->where('status', 'declined')->count(),
            'total_duration' => $calls->where('status', 'ended')->sum('duration'),
            'average_duration' => $calls->where('status', 'ended')->avg('duration'),
            'average_rating' => $calls->whereNotNull('quality_rating')->avg('quality_rating'),
        ];
    }
}
