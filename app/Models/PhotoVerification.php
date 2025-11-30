<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PhotoVerification extends Model
{
    use HasFactory;

    protected $fillable = [
        'photo_id',
        'user_id',
        'verification_status',
        'quality_score',
        'face_count',
        'has_inappropriate_content',
        'flagged_categories',
        'estimated_age_low',
        'estimated_age_high',
        'matches_primary_photo',
        'face_match_score',
        'failure_reason',
        'metadata',
        'verified_at',
    ];

    protected function casts(): array
    {
        return [
            'quality_score' => 'decimal:2',
            'face_match_score' => 'decimal:2',
            'has_inappropriate_content' => 'boolean',
            'matches_primary_photo' => 'boolean',
            'flagged_categories' => 'array',
            'metadata' => 'array',
            'verified_at' => 'datetime',
        ];
    }

    /**
     * Get the photo that owns the verification.
     */
    public function photo(): BelongsTo
    {
        return $this->belongsTo(Photo::class, 'photo_id');
    }

    /**
     * Get the user that owns the verification.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Check if verification is pending.
     */
    public function isPending(): bool
    {
        return $this->verification_status === 'pending';
    }

    /**
     * Check if verification is processing.
     */
    public function isProcessing(): bool
    {
        return $this->verification_status === 'processing';
    }

    /**
     * Check if verification passed.
     */
    public function isPassed(): bool
    {
        return $this->verification_status === 'passed';
    }

    /**
     * Check if verification failed.
     */
    public function isFailed(): bool
    {
        return $this->verification_status === 'failed';
    }

    /**
     * Check if photo is flagged.
     */
    public function isFlagged(): bool
    {
        return $this->verification_status === 'flagged';
    }

    /**
     * Mark as processing.
     */
    public function markAsProcessing(): void
    {
        $this->update(['verification_status' => 'processing']);
    }

    /**
     * Mark as passed.
     */
    public function markAsPassed(array $data): void
    {
        $this->update(array_merge($data, [
            'verification_status' => 'passed',
            'verified_at' => now(),
        ]));
    }

    /**
     * Mark as failed.
     */
    public function markAsFailed(string $reason): void
    {
        $this->update([
            'verification_status' => 'failed',
            'failure_reason' => $reason,
        ]);
    }

    /**
     * Mark as flagged.
     */
    public function markAsFlagged(array $categories, string $reason): void
    {
        $this->update([
            'verification_status' => 'flagged',
            'has_inappropriate_content' => true,
            'flagged_categories' => $categories,
            'failure_reason' => $reason,
        ]);
    }

    /**
     * Create verification for photo.
     */
    public static function createForPhoto(Photo $photo): self
    {
        // Delete existing verification for this photo
        static::where('photo_id', $photo->id)->delete();

        return static::create([
            'photo_id' => $photo->id,
            'user_id' => $photo->user_id,
            'verification_status' => 'pending',
        ]);
    }

    /**
     * Get latest verification for photo.
     */
    public static function getLatestForPhoto(Photo $photo): ?self
    {
        return static::where('photo_id', $photo->id)
            ->latest()
            ->first();
    }

    /**
     * Scope to get pending verifications.
     */
    public function scopePending($query)
    {
        return $query->where('verification_status', 'pending');
    }

    /**
     * Scope to get processing verifications.
     */
    public function scopeProcessing($query)
    {
        return $query->where('verification_status', 'processing');
    }

    /**
     * Scope to get passed verifications.
     */
    public function scopePassed($query)
    {
        return $query->where('verification_status', 'passed');
    }

    /**
     * Scope to get failed verifications.
     */
    public function scopeFailed($query)
    {
        return $query->where('verification_status', 'failed');
    }

    /**
     * Scope to get flagged verifications.
     */
    public function scopeFlagged($query)
    {
        return $query->where('verification_status', 'flagged');
    }

    /**
     * Scope to get photos with inappropriate content.
     */
    public function scopeInappropriate($query)
    {
        return $query->where('has_inappropriate_content', true);
    }
}
