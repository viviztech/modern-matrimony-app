<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SavedSearch extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<string>
     */
    protected $fillable = [
        'user_id',
        'name',
        'filters',
        'notify_on_match',
        'results_count',
        'last_checked_at',
    ];

    /**
     * The attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'filters' => 'array',
            'notify_on_match' => 'boolean',
            'last_checked_at' => 'datetime',
        ];
    }

    /**
     * Get the user that owns the saved search.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get a human-readable description of the filters.
     */
    public function getDescriptionAttribute(): string
    {
        $filters = $this->filters;
        $parts = [];

        if (!empty($filters['age_min']) || !empty($filters['age_max'])) {
            $min = $filters['age_min'] ?? 18;
            $max = $filters['age_max'] ?? 60;
            $parts[] = "Age: {$min}-{$max}";
        }

        if (!empty($filters['location'])) {
            $parts[] = "Location: {$filters['location']}";
        }

        if (!empty($filters['education'])) {
            $parts[] = "Education: {$filters['education']}";
        }

        if (!empty($filters['religion'])) {
            $parts[] = "Religion: {$filters['religion']}";
        }

        if (!empty($filters['occupation'])) {
            $parts[] = "Occupation: {$filters['occupation']}";
        }

        return implode(' • ', $parts) ?: 'All profiles';
    }

    /**
     * Update the results count and last checked timestamp.
     */
    public function updateResultsCount(int $count): void
    {
        $this->update([
            'results_count' => $count,
            'last_checked_at' => now(),
        ]);
    }

    /**
     * Check if this search has new results since last check.
     */
    public function hasNewResults(int $currentCount): bool
    {
        return $currentCount > $this->results_count;
    }

    /**
     * Scope to get searches with notification enabled.
     */
    public function scopeWithNotifications($query)
    {
        return $query->where('notify_on_match', true);
    }

    /**
     * Scope to get searches that need checking.
     */
    public function scopeNeedsCheck($query, int $hours = 24)
    {
        return $query->where('notify_on_match', true)
            ->where(function ($q) use ($hours) {
                $q->whereNull('last_checked_at')
                    ->orWhere('last_checked_at', '<=', now()->subHours($hours));
            });
    }
}
