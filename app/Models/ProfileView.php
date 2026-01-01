<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProfileView extends Model
{
    use HasFactory;

    protected $fillable = [
        'viewer_id',
        'profile_id',
        'viewed_at',
        'duration_seconds',
        'source',
    ];

    protected $casts = [
        'viewed_at' => 'datetime',
        'duration_seconds' => 'integer',
    ];

    /**
     * Get the user who viewed the profile.
     */
    public function viewer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'viewer_id');
    }

    /**
     * Get the user whose profile was viewed.
     */
    public function profile(): BelongsTo
    {
        return $this->belongsTo(User::class, 'profile_id');
    }

    /**
     * Scope to get views for a specific profile.
     */
    public function scopeForProfile($query, $profileId)
    {
        return $query->where('profile_id', $profileId);
    }

    /**
     * Scope to get views by a specific viewer.
     */
    public function scopeByViewer($query, $viewerId)
    {
        return $query->where('viewer_id', $viewerId);
    }

    /**
     * Scope to get views from a specific source.
     */
    public function scopeFromSource($query, $source)
    {
        return $query->where('source', $source);
    }

    /**
     * Scope to get unique viewers.
     */
    public function scopeUniqueViewers($query, $profileId)
    {
        return $query->where('profile_id', $profileId)
            ->distinct('viewer_id');
    }

    /**
     * Scope to get views within a date range.
     */
    public function scopeInDateRange($query, $startDate, $endDate)
    {
        return $query->whereBetween('viewed_at', [$startDate, $endDate]);
    }

    /**
     * Scope to get recent views.
     */
    public function scopeRecent($query, $days = 30)
    {
        return $query->where('viewed_at', '>=', now()->subDays($days));
    }
}
