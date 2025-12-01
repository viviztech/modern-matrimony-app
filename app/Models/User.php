<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasFactory, Notifiable, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'phone',
        'password',
        'dob',
        'gender',
        'city',
        'state',
        'country',
        'latitude',
        'longitude',
        'is_active',
        'is_premium',
        'is_admin',
        'premium_until',
        'profile_completion_percentage',
        'notification_preferences',
        'last_active_at',
        'last_login_at',
        'last_login_ip',
        'banned_at',
        'ban_reason',
        'suspended_until',
        'suspension_reason',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'phone_verified_at' => 'datetime',
            'video_verified_at' => 'datetime',
            'password' => 'hashed',
            'dob' => 'date',
            'is_active' => 'boolean',
            'is_premium' => 'boolean',
            'is_admin' => 'boolean',
            'premium_until' => 'datetime',
            'last_active_at' => 'datetime',
            'last_login_at' => 'datetime',
            'deleted_at' => 'datetime',
            'banned_at' => 'datetime',
            'suspended_until' => 'datetime',
            'notification_preferences' => 'array',
        ];
    }

    /**
     * Get the user's profile.
     */
    public function profile(): HasOne
    {
        return $this->hasOne(Profile::class);
    }

    /**
     * Get the user's preferences.
     */
    public function preference(): HasOne
    {
        return $this->hasOne(Preference::class);
    }

    /**
     * Get the user's photos.
     */
    public function photos(): HasMany
    {
        return $this->hasMany(Photo::class)->orderBy('order');
    }

    /**
     * Get the user's primary photo.
     */
    public function primaryPhoto(): HasOne
    {
        return $this->hasOne(Photo::class)->where('is_primary', true)->where('status', 'approved');
    }

    /**
     * Get the user's verifications.
     */
    public function verifications(): HasMany
    {
        return $this->hasMany(Verification::class);
    }

    /**
     * Get the user's social accounts.
     */
    public function socialAccounts(): HasMany
    {
        return $this->hasMany(SocialAccount::class);
    }

    /**
     * Get the user's likes (users they liked).
     */
    public function likes(): HasMany
    {
        return $this->hasMany(Like::class);
    }

    /**
     * Get users who liked this user.
     */
    public function likedBy(): HasMany
    {
        return $this->hasMany(Like::class, 'liked_user_id');
    }

    /**
     * Get the user's matches.
     */
    public function matches(): HasMany
    {
        return $this->hasMany(UserMatch::class)->where('is_active', true);
    }

    /**
     * Get the user's conversations.
     */
    public function conversations(): HasMany
    {
        return $this->hasMany(Conversation::class, 'user_one_id')
            ->orWhere('user_two_id', $this->id)
            ->orderByDesc('last_message_at');
    }

    /**
     * Get the user's sent messages.
     */
    public function sentMessages(): HasMany
    {
        return $this->hasMany(Message::class, 'sender_id');
    }

    /**
     * Get the user's received messages.
     */
    public function receivedMessages(): HasMany
    {
        return $this->hasMany(Message::class, 'receiver_id');
    }

    /**
     * Get video calls initiated by this user.
     */
    public function initiatedCalls(): HasMany
    {
        return $this->hasMany(VideoCall::class, 'caller_id');
    }

    /**
     * Get video calls received by this user.
     */
    public function receivedCalls(): HasMany
    {
        return $this->hasMany(VideoCall::class, 'receiver_id');
    }

    /**
     * Get all video calls for this user (both initiated and received).
     */
    public function videoCalls(): HasMany
    {
        return $this->hasMany(VideoCall::class, 'caller_id')
            ->orWhere('receiver_id', $this->id)
            ->orderByDesc('created_at');
    }

    /**
     * Check if user has verified email.
     */
    public function hasVerifiedEmail(): bool
    {
        return !is_null($this->email_verified_at);
    }

    /**
     * Check if user has verified phone.
     */
    public function hasVerifiedPhone(): bool
    {
        return !is_null($this->phone_verified_at);
    }

    /**
     * Check if user has verified video selfie.
     */
    public function hasVerifiedVideo(): bool
    {
        return !is_null($this->video_verified_at);
    }

    /**
     * Check if user has LinkedIn verification.
     */
    public function hasLinkedInVerified(): bool
    {
        return $this->socialAccounts()->linkedIn()->verified()->exists();
    }

    /**
     * Check if user has Instagram verification.
     */
    public function hasInstagramVerified(): bool
    {
        return $this->socialAccounts()->instagram()->verified()->exists();
    }

    /**
     * Check if user has Facebook verification.
     */
    public function hasFacebookVerified(): bool
    {
        return $this->socialAccounts()->facebook()->verified()->exists();
    }

    /**
     * Get verification count.
     */
    public function getVerificationCount(): int
    {
        $count = 0;

        if ($this->hasVerifiedEmail()) $count++;
        if ($this->hasVerifiedPhone()) $count++;
        if ($this->hasVerifiedVideo()) $count++;
        if ($this->hasLinkedInVerified()) $count++;
        if ($this->hasInstagramVerified()) $count++;

        return $count;
    }

    /**
     * Check if user is premium.
     */
    public function isPremium(): bool
    {
        return $this->is_premium && $this->premium_until && $this->premium_until->isFuture();
    }

    /**
     * Get user's age.
     */
    public function getAgeAttribute(): ?int
    {
        return $this->dob ? $this->dob->age : null;
    }

    /**
     * Scope to get active users.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope to get premium users.
     */
    public function scopePremium($query)
    {
        return $query->where('is_premium', true)
            ->where('premium_until', '>', now());
    }

    /**
     * Scope to get recently active users.
     */
    public function scopeRecentlyActive($query, $days = 7)
    {
        return $query->where('last_active_at', '>=', now()->subDays($days));
    }

    /**
     * Update last active timestamp.
     */
    public function updateLastActive(): void
    {
        $this->update(['last_active_at' => now()]);
    }

    /**
     * Check if user is admin.
     */
    public function isAdmin(): bool
    {
        return $this->is_admin === true;
    }

    /**
     * Check if user is banned.
     */
    public function isBanned(): bool
    {
        return !is_null($this->banned_at);
    }

    /**
     * Check if user is suspended.
     */
    public function isSuspended(): bool
    {
        return !is_null($this->suspended_until) && $this->suspended_until->isFuture();
    }

    /**
     * Get reports made by this user.
     */
    public function reportsMade(): HasMany
    {
        return $this->hasMany(Report::class, 'reporter_id');
    }

    /**
     * Get reports against this user.
     */
    public function reportsReceived(): HasMany
    {
        return $this->hasMany(Report::class, 'reported_user_id');
    }

    /**
     * Get user's subscriptions.
     */
    public function subscriptions(): HasMany
    {
        return $this->hasMany(Subscription::class);
    }

    /**
     * Get user's active subscription.
     */
    public function activeSubscription(): ?Subscription
    {
        return $this->subscriptions()->active()->first();
    }

    /**
     * Get user's current subscription plan.
     */
    public function currentPlan(): ?SubscriptionPlan
    {
        $subscription = $this->activeSubscription();
        return $subscription ? $subscription->plan : SubscriptionPlan::where('slug', 'free')->first();
    }

    /**
     * Check if user has active subscription.
     */
    public function hasActiveSubscription(): bool
    {
        return $this->subscriptions()->active()->exists();
    }

    /**
     * Check if user has specific plan.
     */
    public function hasPlan(string $slug): bool
    {
        $subscription = $this->activeSubscription();
        return $subscription && $subscription->plan->slug === $slug;
    }

    /**
     * Check if user can access feature.
     */
    public function canAccessFeature(string $feature): bool
    {
        $plan = $this->currentPlan();
        return $plan && $plan->hasFeature($feature);
    }

    /**
     * Get feature limit for user.
     */
    public function getFeatureLimit(string $feature): int
    {
        $plan = $this->currentPlan();
        return $plan ? ($plan->getFeature($feature) ?? 0) : 0;
    }

    /**
     * Scope to get admin users.
     */
    public function scopeAdmins($query)
    {
        return $query->where('is_admin', true);
    }

    /**
     * Scope to get banned users.
     */
    public function scopeBanned($query)
    {
        return $query->whereNotNull('banned_at');
    }

    /**
     * Scope to get suspended users.
     */
    public function scopeSuspended($query)
    {
        return $query->whereNotNull('suspended_until')
            ->where('suspended_until', '>', now());
    }
}
