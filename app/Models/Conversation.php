<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Conversation extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'match_id',
        'user_one_id',
        'user_two_id',
        'last_message_id',
        'last_message_at',
        'user_one_unread_count',
        'user_two_unread_count',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'last_message_at' => 'datetime',
    ];

    /**
     * Get the match that owns the conversation.
     */
    public function match(): BelongsTo
    {
        return $this->belongsTo(UserMatch::class, 'match_id');
    }

    /**
     * Get user one (first participant).
     */
    public function userOne(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_one_id');
    }

    /**
     * Get user two (second participant).
     */
    public function userTwo(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_two_id');
    }

    /**
     * Get the last message.
     */
    public function lastMessage(): BelongsTo
    {
        return $this->belongsTo(Message::class, 'last_message_id');
    }

    /**
     * Get all messages in this conversation.
     */
    public function messages(): HasMany
    {
        return $this->hasMany(Message::class);
    }

    /**
     * Get the other participant in the conversation.
     */
    public function getOtherUser(int $userId): User
    {
        return $this->user_one_id === $userId
            ? $this->userTwo
            : $this->userOne;
    }

    /**
     * Get unread count for a specific user.
     */
    public function getUnreadCount(int $userId): int
    {
        return $this->user_one_id === $userId
            ? $this->user_one_unread_count
            : $this->user_two_unread_count;
    }

    /**
     * Mark messages as read for a user.
     */
    public function markAsRead(int $userId): void
    {
        if ($this->user_one_id === $userId) {
            $this->update(['user_one_unread_count' => 0]);
        } else {
            $this->update(['user_two_unread_count' => 0]);
        }

        // Mark all unread messages as read
        $this->messages()
            ->where('receiver_id', $userId)
            ->whereNull('read_at')
            ->update(['read_at' => now()]);
    }

    /**
     * Increment unread count for a user.
     */
    public function incrementUnreadCount(int $userId): void
    {
        if ($this->user_one_id === $userId) {
            $this->increment('user_one_unread_count');
        } else {
            $this->increment('user_two_unread_count');
        }
    }

    /**
     * Get conversation between two users.
     */
    public static function findBetweenUsers(int $userOneId, int $userTwoId): ?Conversation
    {
        return static::where(function ($query) use ($userOneId, $userTwoId) {
            $query->where('user_one_id', $userOneId)
                  ->where('user_two_id', $userTwoId);
        })->orWhere(function ($query) use ($userOneId, $userTwoId) {
            $query->where('user_one_id', $userTwoId)
                  ->where('user_two_id', $userOneId);
        })->first();
    }

    /**
     * Create or get conversation between users.
     */
    public static function createOrGet(int $userOneId, int $userTwoId, ?int $matchId = null): Conversation
    {
        $conversation = static::findBetweenUsers($userOneId, $userTwoId);

        if (!$conversation) {
            $conversation = static::create([
                'match_id' => $matchId,
                'user_one_id' => $userOneId,
                'user_two_id' => $userTwoId,
            ]);
        }

        return $conversation;
    }
}
