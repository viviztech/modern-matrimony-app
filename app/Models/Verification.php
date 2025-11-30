<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class Verification extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<string>
     */
    protected $fillable = [
        'user_id',
        'type',
        'status',
        'data',
        'otp',
        'otp_expires_at',
        'otp_attempts',
        'verification_media_url',
        'confidence_score',
        'social_token',
        'social_id',
        'verified_at',
        'rejected_at',
        'expires_at',
        'verified_by',
        'rejection_reason',
        'notes',
    ];

    /**
     * The attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'data' => 'array',
            'otp_expires_at' => 'datetime',
            'verified_at' => 'datetime',
            'rejected_at' => 'datetime',
            'expires_at' => 'datetime',
        ];
    }

    /**
     * The attributes that should be hidden.
     *
     * @var array<string>
     */
    protected $hidden = [
        'otp',
        'social_token',
    ];

    /**
     * Get the user that owns the verification.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the admin who verified.
     */
    public function verifiedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'verified_by');
    }

    /**
     * Generate OTP.
     */
    public function generateOTP(int $length = 6): string
    {
        $otp = str_pad((string) random_int(0, 999999), $length, '0', STR_PAD_LEFT);

        $this->update([
            'otp' => $otp,
            'otp_expires_at' => now()->addMinutes(10),
            'otp_attempts' => 0,
        ]);

        return $otp;
    }

    /**
     * Verify OTP.
     */
    public function verifyOTP(string $otp): bool
    {
        // Check if OTP is expired
        if ($this->otp_expires_at && $this->otp_expires_at->isPast()) {
            return false;
        }

        // Check if max attempts exceeded
        if ($this->otp_attempts >= 5) {
            return false;
        }

        // Increment attempts
        $this->increment('otp_attempts');

        // Verify OTP
        if ($this->otp === $otp) {
            $this->markAsVerified();
            return true;
        }

        return false;
    }

    /**
     * Mark as verified.
     */
    public function markAsVerified(?int $verifiedBy = null): bool
    {
        return $this->update([
            'status' => 'verified',
            'verified_at' => now(),
            'verified_by' => $verifiedBy,
        ]);
    }

    /**
     * Mark as rejected.
     */
    public function markAsRejected(string $reason, ?int $rejectedBy = null): bool
    {
        return $this->update([
            'status' => 'rejected',
            'rejected_at' => now(),
            'rejection_reason' => $reason,
            'verified_by' => $rejectedBy,
        ]);
    }

    /**
     * Check if verification is expired.
     */
    public function isExpired(): bool
    {
        return $this->expires_at && $this->expires_at->isPast();
    }

    /**
     * Check if verification is verified.
     */
    public function isVerified(): bool
    {
        return $this->status === 'verified' && !$this->isExpired();
    }

    /**
     * Scope to get verified verifications.
     */
    public function scopeVerified($query)
    {
        return $query->where('status', 'verified');
    }

    /**
     * Scope to get pending verifications.
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * Scope to get by type.
     */
    public function scopeOfType($query, string $type)
    {
        return $query->where('type', $type);
    }
}
