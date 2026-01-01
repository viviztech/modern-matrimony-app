<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EngagementMetric extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'date',
        'profile_views_count',
        'profile_viewed_by_count',
        'likes_sent_count',
        'likes_received_count',
        'messages_sent_count',
        'messages_received_count',
        'matches_count',
        'search_count',
        'time_spent_seconds',
        'login_count',
    ];

    protected $casts = [
        'date' => 'date',
        'profile_views_count' => 'integer',
        'profile_viewed_by_count' => 'integer',
        'likes_sent_count' => 'integer',
        'likes_received_count' => 'integer',
        'messages_sent_count' => 'integer',
        'messages_received_count' => 'integer',
        'matches_count' => 'integer',
        'search_count' => 'integer',
        'time_spent_seconds' => 'integer',
        'login_count' => 'integer',
    ];

    /**
     * Get the user for this metric.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Scope to get metrics for a specific date.
     */
    public function scopeForDate($query, $date)
    {
        return $query->where('date', $date);
    }

    /**
     * Scope to get metrics within a date range.
     */
    public function scopeInDateRange($query, $startDate, $endDate)
    {
        return $query->whereBetween('date', [$startDate, $endDate]);
    }

    /**
     * Scope to get recent metrics.
     */
    public function scopeRecent($query, $days = 30)
    {
        return $query->where('date', '>=', now()->subDays($days)->toDateString());
    }

    /**
     * Scope to get metrics for a specific user.
     */
    public function scopeForUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    /**
     * Get or create a metric for a specific user and date.
     */
    public static function getOrCreateForUserAndDate($userId, $date)
    {
        return static::firstOrCreate(
            [
                'user_id' => $userId,
                'date' => $date,
            ],
            [
                'profile_views_count' => 0,
                'profile_viewed_by_count' => 0,
                'likes_sent_count' => 0,
                'likes_received_count' => 0,
                'messages_sent_count' => 0,
                'messages_received_count' => 0,
                'matches_count' => 0,
                'search_count' => 0,
                'time_spent_seconds' => 0,
                'login_count' => 0,
            ]
        );
    }

    /**
     * Increment a specific metric.
     */
    public function incrementMetric(string $metric, int $amount = 1): void
    {
        $this->increment($metric, $amount);
    }

    /**
     * Get engagement score (0-100).
     */
    public function getEngagementScore(): float
    {
        $score = 0;

        // Profile activity (30 points max)
        $score += min($this->profile_views_count * 2, 20);
        $score += min($this->profile_viewed_by_count, 10);

        // Interaction activity (40 points max)
        $score += min($this->likes_sent_count * 3, 15);
        $score += min($this->messages_sent_count * 5, 15);
        $score += min($this->matches_count * 10, 10);

        // Platform usage (30 points max)
        $score += min($this->search_count * 2, 10);
        $score += min($this->time_spent_seconds / 60, 20); // 1 point per minute, max 20

        return min($score, 100);
    }
}
