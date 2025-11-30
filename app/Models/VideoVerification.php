<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class VideoVerification extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'video_path',
        'frame_path',
        'liveness_score',
        'face_match_score',
        'motion_detected',
        'passed',
        'verified',
        'verified_at',
        'verification_status',
        'failure_reason',
        'metadata',
        'ip_address',
    ];

    protected function casts(): array
    {
        return [
            'liveness_score' => 'decimal:2',
            'face_match_score' => 'decimal:2',
            'motion_detected' => 'boolean',
            'passed' => 'boolean',
            'verified' => 'boolean',
            'verified_at' => 'datetime',
            'metadata' => 'array',
        ];
    }

    /**
     * Get the user that owns the video verification.
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
     * Check if verification needs manual review.
     */
    public function needsManualReview(): bool
    {
        return $this->verification_status === 'manual_review';
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
    public function markAsPassed(float $livenessScore, float $faceMatchScore): void
    {
        $this->update([
            'verification_status' => 'passed',
            'passed' => true,
            'verified' => true,
            'verified_at' => now(),
            'liveness_score' => $livenessScore,
            'face_match_score' => $faceMatchScore,
        ]);

        // Update user's video_verified_at
        $this->user->update([
            'video_verified_at' => now(),
        ]);
    }

    /**
     * Mark as failed.
     */
    public function markAsFailed(string $reason): void
    {
        $this->update([
            'verification_status' => 'failed',
            'passed' => false,
            'failure_reason' => $reason,
        ]);
    }

    /**
     * Mark for manual review.
     */
    public function markForManualReview(string $reason): void
    {
        $this->update([
            'verification_status' => 'manual_review',
            'failure_reason' => $reason,
        ]);
    }

    /**
     * Create a new verification record.
     */
    public static function createForUser(User $user, string $videoPath, ?string $ipAddress = null): self
    {
        return static::create([
            'user_id' => $user->id,
            'video_path' => $videoPath,
            'verification_status' => 'pending',
            'ip_address' => $ipAddress,
        ]);
    }

    /**
     * Get the latest verification for user.
     */
    public static function getLatestForUser(User $user): ?self
    {
        return static::where('user_id', $user->id)
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
     * Scope to get verifications needing manual review.
     */
    public function scopeNeedsManualReview($query)
    {
        return $query->where('verification_status', 'manual_review');
    }
}
