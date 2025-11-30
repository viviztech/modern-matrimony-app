<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Icebreaker extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'category',
        'question',
        'is_active',
        'usage_count',
        'success_rate',
        'display_order',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * Scope to get active icebreakers.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope to get by category.
     */
    public function scopeByCategory($query, string $category)
    {
        return $query->where('category', $category);
    }

    /**
     * Scope to get popular icebreakers.
     */
    public function scopePopular($query, int $limit = 10)
    {
        return $query->orderByDesc('success_rate')
                    ->orderByDesc('usage_count')
                    ->limit($limit);
    }

    /**
     * Increment usage count.
     */
    public function incrementUsage(): void
    {
        $this->increment('usage_count');
    }

    /**
     * Update success rate based on response.
     */
    public function updateSuccessRate(bool $gotResponse): void
    {
        if ($this->usage_count > 0) {
            $currentSuccess = ($this->success_rate / 100) * $this->usage_count;
            $newSuccess = $gotResponse ? $currentSuccess + 1 : $currentSuccess;
            $this->success_rate = (int) (($newSuccess / $this->usage_count) * 100);
            $this->save();
        }
    }

    /**
     * Get random icebreakers.
     */
    public static function getRandom(int $count = 5, ?string $category = null): \Illuminate\Support\Collection
    {
        $query = static::active();

        if ($category) {
            $query->byCategory($category);
        }

        return $query->inRandomOrder()->limit($count)->get();
    }

    /**
     * Get personalized icebreakers based on user profile.
     */
    public static function getPersonalized(User $user, User $otherUser, int $count = 5): \Illuminate\Support\Collection
    {
        // Get icebreakers based on common interests or profile data
        $categories = [];

        // Check common interests
        if ($user->profile && $otherUser->profile) {
            $userInterests = $user->profile->interests ?? [];
            $otherInterests = $otherUser->profile->interests ?? [];

            if (is_array($userInterests) && is_array($otherInterests)) {
                $commonInterests = array_intersect($userInterests, $otherInterests);

                if (in_array('Travel', $userInterests) || in_array('Travel', $otherInterests)) {
                    $categories[] = 'food_travel';
                }
                if (in_array('Fitness', $userInterests) || in_array('Fitness', $otherInterests)) {
                    $categories[] = 'fun_quirky';
                }
            }
        }

        // Default to getting to know you
        if (empty($categories)) {
            $categories[] = 'getting_to_know';
        }

        return static::active()
            ->whereIn('category', $categories)
            ->inRandomOrder()
            ->limit($count)
            ->get();
    }

    /**
     * Get all categories.
     */
    public static function getCategories(): array
    {
        return [
            'getting_to_know' => 'Getting to know you',
            'hobbies_interests' => 'Hobbies & interests',
            'life_goals' => 'Life goals & dreams',
            'food_travel' => 'Food & travel',
            'fun_quirky' => 'Fun & quirky',
            'values_beliefs' => 'Values & beliefs',
            'this_or_that' => 'This or that',
        ];
    }
}
