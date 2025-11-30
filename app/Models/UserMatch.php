<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserMatch extends Model
{
    protected $fillable = [
        'user_id',
        'matched_user_id',
        'matched_at',
        'is_active',
    ];

    protected $casts = [
        'matched_at' => 'datetime',
        'is_active' => 'boolean',
    ];

    /**
     * User who owns this match
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * The matched user
     */
    public function matchedUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'matched_user_id');
    }

    /**
     * Unmatch (soft delete relationship)
     */
    public function unmatch(): void
    {
        $this->update(['is_active' => false]);

        // Also deactivate the reciprocal match
        static::where('user_id', $this->matched_user_id)
            ->where('matched_user_id', $this->user_id)
            ->update(['is_active' => false]);
    }
}
