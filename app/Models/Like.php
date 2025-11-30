<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Like extends Model
{
    protected $fillable = [
        'user_id',
        'liked_user_id',
        'type',
    ];

    /**
     * User who liked
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * User who was liked
     */
    public function likedUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'liked_user_id');
    }

    /**
     * Check if this like creates a match
     */
    public function checkForMatch(): ?UserMatch
    {
        // Only like and super_like create matches (not pass)
        if ($this->type === 'pass') {
            return null;
        }

        // Check if the other user has also liked this user
        $reciprocalLike = static::where('user_id', $this->liked_user_id)
            ->where('liked_user_id', $this->user_id)
            ->whereIn('type', ['like', 'super_like'])
            ->first();

        if ($reciprocalLike) {
            // Create match for both users
            return UserMatch::firstOrCreate([
                'user_id' => $this->user_id,
                'matched_user_id' => $this->liked_user_id,
            ]);
        }

        return null;
    }
}
