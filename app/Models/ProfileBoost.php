<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProfileBoost extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<string>
     */
    protected $fillable = [
        'user_id',
        'duration_type',
        'duration_hours',
        'boost_multiplier',
        'started_at',
        'expires_at',
        'views_gained',
        'likes_gained',
        'is_active',
    ];

    /**
     * The attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'started_at' => 'datetime',
            'expires_at' => 'datetime',
            'is_active' => 'boolean',
        ];
    }

    /**
     * Get the user that owns the boost.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Check if boost is currently active.
     */
    public function isActive(): bool
    {
        return $this->is_active && $this->expires_at->isFuture();
    }

    /**
     * Get remaining time in hours.
     */
    public function getRemainingHours(): int
    {
        if (!$this->isActive()) {
            return 0;
        }

        return max(0, (int) now()->diffInHours($this->expires_at, false));
    }

    /**
     * Get remaining time formatted.
     */
    public function getRemainingTimeAttribute(): string
    {
        if (!$this->isActive()) {
            return 'Expired';
        }

        $hours = $this->getRemainingHours();

        if ($hours < 1) {
            $minutes = now()->diffInMinutes($this->expires_at);
            return "{$minutes} minutes";
        }

        if ($hours < 24) {
            return "{$hours} hours";
        }

        $days = floor($hours / 24);
        return "{$days} days";
    }

    /**
     * Increment views gained.
     */
    public function incrementViews(int $count = 1): void
    {
        $this->increment('views_gained', $count);
    }

    /**
     * Increment likes gained.
     */
    public function incrementLikes(int $count = 1): void
    {
        $this->increment('likes_gained', $count);
    }

    /**
     * Deactivate the boost.
     */
    public function deactivate(): void
    {
        $this->update(['is_active' => false]);
    }

    /**
     * Scope to get active boosts.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true)
            ->where('expires_at', '>', now());
    }

    /**
     * Scope to get expired boosts.
     */
    public function scopeExpired($query)
    {
        return $query->where(function ($q) {
            $q->where('is_active', false)
                ->orWhere('expires_at', '<=', now());
        });
    }

    /**
     * Create a new boost for a user.
     */
    public static function createBoost(User $user, string $durationType): self
    {
        $durationMap = [
            '24h' => 24,
            '7d' => 168,  // 7 * 24
            '30d' => 720, // 30 * 24
        ];

        $hours = $durationMap[$durationType] ?? 24;
        $startedAt = now();
        $expiresAt = $startedAt->copy()->addHours($hours);

        return self::create([
            'user_id' => $user->id,
            'duration_type' => $durationType,
            'duration_hours' => $hours,
            'boost_multiplier' => 10,
            'started_at' => $startedAt,
            'expires_at' => $expiresAt,
            'is_active' => true,
        ]);
    }
}
