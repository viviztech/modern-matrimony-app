<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SubscriptionPlan extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'price_monthly',
        'price_yearly',
        'features',
        'is_active',
        'display_order',
    ];

    protected function casts(): array
    {
        return [
            'features' => 'array',
            'is_active' => 'boolean',
            'price_monthly' => 'decimal:2',
            'price_yearly' => 'decimal:2',
        ];
    }

    /**
     * Get subscriptions for this plan.
     */
    public function subscriptions(): HasMany
    {
        return $this->hasMany(Subscription::class, 'plan_id');
    }

    /**
     * Check if plan is free.
     */
    public function isFree(): bool
    {
        return $this->slug === 'free';
    }

    /**
     * Check if plan is Gold.
     */
    public function isGold(): bool
    {
        return $this->slug === 'gold';
    }

    /**
     * Check if plan is Platinum.
     */
    public function isPlatinum(): bool
    {
        return $this->slug === 'platinum';
    }

    /**
     * Check if plan is Elite.
     */
    public function isElite(): bool
    {
        return $this->slug === 'elite';
    }

    /**
     * Get feature value.
     */
    public function getFeature(string $key, $default = null)
    {
        return $this->features[$key] ?? $default;
    }

    /**
     * Check if plan has feature.
     */
    public function hasFeature(string $key): bool
    {
        return isset($this->features[$key]) && $this->features[$key] === true;
    }

    /**
     * Get monthly price with discount applied.
     */
    public function getMonthlyPriceAttribute(): float
    {
        return (float) $this->price_monthly;
    }

    /**
     * Get yearly price with discount applied.
     */
    public function getYearlyPriceAttribute(): float
    {
        return (float) $this->price_yearly;
    }

    /**
     * Get yearly savings compared to monthly.
     */
    public function getYearlySavingsAttribute(): float
    {
        $monthlyTotal = $this->price_monthly * 12;
        return $monthlyTotal - $this->price_yearly;
    }

    /**
     * Get yearly discount percentage.
     */
    public function getYearlyDiscountPercentageAttribute(): int
    {
        if ($this->price_monthly == 0) {
            return 0;
        }

        $monthlyTotal = $this->price_monthly * 12;
        $savings = $monthlyTotal - $this->price_yearly;
        return (int) (($savings / $monthlyTotal) * 100);
    }

    /**
     * Scope to get active plans.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope to order by display order.
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('display_order');
    }
}
