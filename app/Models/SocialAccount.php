<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SocialAccount extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'provider',
        'provider_id',
        'provider_username',
        'provider_email',
        'provider_avatar',
        'access_token',
        'refresh_token',
        'token_expires_at',
        'provider_data',
        'is_verified',
        'verified_at',
    ];

    protected function casts(): array
    {
        return [
            'provider_data' => 'array',
            'is_verified' => 'boolean',
            'verified_at' => 'datetime',
            'token_expires_at' => 'datetime',
        ];
    }

    protected $hidden = [
        'access_token',
        'refresh_token',
    ];

    /**
     * Get the user that owns the social account.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Check if this is a LinkedIn account.
     */
    public function isLinkedIn(): bool
    {
        return $this->provider === 'linkedin';
    }

    /**
     * Check if this is an Instagram account.
     */
    public function isInstagram(): bool
    {
        return $this->provider === 'instagram';
    }

    /**
     * Check if this is a Facebook account.
     */
    public function isFacebook(): bool
    {
        return $this->provider === 'facebook';
    }

    /**
     * Check if token is expired.
     */
    public function isTokenExpired(): bool
    {
        if (!$this->token_expires_at) {
            return false;
        }

        return $this->token_expires_at->isPast();
    }

    /**
     * Get or create social account.
     */
    public static function updateOrCreateFromProvider(User $user, string $provider, array $providerData): self
    {
        return static::updateOrCreate(
            [
                'user_id' => $user->id,
                'provider' => $provider,
            ],
            [
                'provider_id' => $providerData['id'],
                'provider_username' => $providerData['username'] ?? $providerData['name'] ?? null,
                'provider_email' => $providerData['email'] ?? null,
                'provider_avatar' => $providerData['avatar'] ?? $providerData['picture'] ?? null,
                'access_token' => $providerData['token'] ?? null,
                'refresh_token' => $providerData['refreshToken'] ?? null,
                'token_expires_at' => isset($providerData['expiresIn'])
                    ? now()->addSeconds($providerData['expiresIn'])
                    : null,
                'provider_data' => $providerData,
                'is_verified' => true,
                'verified_at' => now(),
            ]
        );
    }

    /**
     * Scope to get LinkedIn accounts.
     */
    public function scopeLinkedIn($query)
    {
        return $query->where('provider', 'linkedin');
    }

    /**
     * Scope to get Instagram accounts.
     */
    public function scopeInstagram($query)
    {
        return $query->where('provider', 'instagram');
    }

    /**
     * Scope to get Facebook accounts.
     */
    public function scopeFacebook($query)
    {
        return $query->where('provider', 'facebook');
    }

    /**
     * Scope to get verified accounts.
     */
    public function scopeVerified($query)
    {
        return $query->where('is_verified', true);
    }
}
