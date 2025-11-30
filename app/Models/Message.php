<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Message extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'conversation_id',
        'icebreaker_id',
        'sender_id',
        'receiver_id',
        'type',
        'content',
        'media_url',
        'read_at',
        'delivered_at',
        'deleted_by_sender_at',
        'deleted_by_receiver_at',
        'quality_score',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'read_at' => 'datetime',
        'delivered_at' => 'datetime',
        'deleted_by_sender_at' => 'datetime',
        'deleted_by_receiver_at' => 'datetime',
    ];

    /**
     * Get the conversation that owns the message.
     */
    public function conversation(): BelongsTo
    {
        return $this->belongsTo(Conversation::class);
    }

    /**
     * Get the sender of the message.
     */
    public function sender(): BelongsTo
    {
        return $this->belongsTo(User::class, 'sender_id');
    }

    /**
     * Get the receiver of the message.
     */
    public function receiver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'receiver_id');
    }

    /**
     * Get the icebreaker used for this message.
     */
    public function icebreaker(): BelongsTo
    {
        return $this->belongsTo(Icebreaker::class);
    }

    /**
     * Check if message is read.
     */
    public function isRead(): bool
    {
        return $this->read_at !== null;
    }

    /**
     * Check if message is delivered.
     */
    public function isDelivered(): bool
    {
        return $this->delivered_at !== null;
    }

    /**
     * Mark message as read.
     */
    public function markAsRead(): void
    {
        if (!$this->read_at) {
            $this->update([
                'read_at' => now(),
                'delivered_at' => $this->delivered_at ?? now(),
            ]);
        }
    }

    /**
     * Mark message as delivered.
     */
    public function markAsDelivered(): void
    {
        if (!$this->delivered_at) {
            $this->update(['delivered_at' => now()]);
        }
    }

    /**
     * Check if message is deleted by sender.
     */
    public function isDeletedBySender(): bool
    {
        return $this->deleted_by_sender_at !== null;
    }

    /**
     * Check if message is deleted by receiver.
     */
    public function isDeletedByReceiver(): bool
    {
        return $this->deleted_by_receiver_at !== null;
    }

    /**
     * Delete message for user.
     */
    public function deleteForUser(int $userId): void
    {
        if ($this->sender_id === $userId) {
            $this->update(['deleted_by_sender_at' => now()]);
        } elseif ($this->receiver_id === $userId) {
            $this->update(['deleted_by_receiver_at' => now()]);
        }

        // If both users deleted, hard delete
        if ($this->deleted_by_sender_at && $this->deleted_by_receiver_at) {
            $this->delete();
        }
    }

    /**
     * Check if message is visible to user.
     */
    public function isVisibleTo(int $userId): bool
    {
        if ($this->sender_id === $userId && $this->deleted_by_sender_at) {
            return false;
        }

        if ($this->receiver_id === $userId && $this->deleted_by_receiver_at) {
            return false;
        }

        return true;
    }

    /**
     * Scope messages visible to a user.
     */
    public function scopeVisibleTo($query, int $userId)
    {
        return $query->where(function ($q) use ($userId) {
            $q->where(function ($q) use ($userId) {
                $q->where('sender_id', $userId)
                  ->whereNull('deleted_by_sender_at');
            })->orWhere(function ($q) use ($userId) {
                $q->where('receiver_id', $userId)
                  ->whereNull('deleted_by_receiver_at');
            });
        });
    }

    /**
     * Scope unread messages.
     */
    public function scopeUnread($query)
    {
        return $query->whereNull('read_at');
    }

    /**
     * Boot the model.
     */
    protected static function booted(): void
    {
        // After creating a message, update conversation
        static::created(function (Message $message) {
            $message->conversation->update([
                'last_message_id' => $message->id,
                'last_message_at' => $message->created_at,
            ]);

            // Increment unread count for receiver
            $message->conversation->incrementUnreadCount($message->receiver_id);

            // Mark as delivered
            $message->markAsDelivered();
        });
    }
}
