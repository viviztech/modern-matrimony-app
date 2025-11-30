<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PhoneVerification extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'phone',
        'otp',
        'expires_at',
        'attempts',
        'verified',
        'verified_at',
        'ip_address',
    ];

    protected function casts(): array
    {
        return [
            'expires_at' => 'datetime',
            'verified_at' => 'datetime',
            'verified' => 'boolean',
        ];
    }

    /**
     * Get the user that owns the phone verification.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Check if OTP is expired.
     */
    public function isExpired(): bool
    {
        return $this->expires_at->isPast();
    }

    /**
     * Check if verification is still valid (not expired and not verified).
     */
    public function isValid(): bool
    {
        return !$this->verified && !$this->isExpired();
    }

    /**
     * Check if max attempts reached.
     */
    public function maxAttemptsReached(): bool
    {
        return $this->attempts >= 3;
    }

    /**
     * Increment attempt count.
     */
    public function incrementAttempts(): void
    {
        $this->increment('attempts');
    }

    /**
     * Mark as verified.
     */
    public function markAsVerified(): void
    {
        $this->update([
            'verified' => true,
            'verified_at' => now(),
        ]);
    }

    /**
     * Generate a new 6-digit OTP.
     */
    public static function generateOTP(): string
    {
        return str_pad((string) random_int(100000, 999999), 6, '0', STR_PAD_LEFT);
    }

    /**
     * Create a new verification record or update existing one.
     */
    public static function createForUser(User $user, string $phone, ?string $ipAddress = null): self
    {
        // Delete any existing unverified verifications for this user
        static::where('user_id', $user->id)
            ->where('verified', false)
            ->delete();

        // Create new verification
        return static::create([
            'user_id' => $user->id,
            'phone' => $phone,
            'otp' => static::generateOTP(),
            'expires_at' => now()->addMinutes(10),
            'attempts' => 0,
            'ip_address' => $ipAddress,
        ]);
    }

    /**
     * Find active verification for user and phone.
     */
    public static function findActiveVerification(User $user, string $phone): ?self
    {
        return static::where('user_id', $user->id)
            ->where('phone', $phone)
            ->where('verified', false)
            ->where('expires_at', '>', now())
            ->latest()
            ->first();
    }

    /**
     * Verify OTP.
     */
    public function verifyOTP(string $otp): bool
    {
        // Check if already verified
        if ($this->verified) {
            return false;
        }

        // Check if expired
        if ($this->isExpired()) {
            return false;
        }

        // Check max attempts
        if ($this->maxAttemptsReached()) {
            return false;
        }

        // Increment attempts
        $this->incrementAttempts();

        // Check OTP
        if ($this->otp === $otp) {
            $this->markAsVerified();

            // Update user's phone_verified_at
            $this->user->update([
                'phone' => $this->phone,
                'phone_verified_at' => now(),
            ]);

            return true;
        }

        return false;
    }

    /**
     * Scope to get only active verifications.
     */
    public function scopeActive($query)
    {
        return $query->where('verified', false)
            ->where('expires_at', '>', now());
    }

    /**
     * Scope to get verified verifications.
     */
    public function scopeVerified($query)
    {
        return $query->where('verified', true);
    }
}
