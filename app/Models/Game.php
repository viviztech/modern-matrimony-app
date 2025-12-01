<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Game extends Model
{
    protected $fillable = [
        'user1_id',
        'user2_id',
        'type',
        'status',
        'compatibility_score',
        'results',
        'completed_at',
    ];

    protected function casts(): array
    {
        return [
            'results' => 'array',
            'completed_at' => 'datetime',
            'compatibility_score' => 'integer',
        ];
    }

    public function user1(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user1_id');
    }

    public function user2(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user2_id');
    }

    public function answers(): HasMany
    {
        return $this->hasMany(GameAnswer::class);
    }

    public function getOtherUser(User $currentUser): User
    {
        return $this->user1_id === $currentUser->id ? $this->user2 : $this->user1;
    }

    public function hasAnswered(User $user): bool
    {
        return $this->answers()->where('user_id', $user->id)->exists();
    }

    public function isComplete(): bool
    {
        return $this->status === 'completed';
    }

    public function markAsComplete(): void
    {
        $this->update([
            'status' => 'completed',
            'completed_at' => now(),
        ]);
    }
}
