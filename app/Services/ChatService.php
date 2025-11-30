<?php

namespace App\Services;

use App\Models\Conversation;
use App\Models\Message;
use App\Models\User;
use Illuminate\Support\Collection;

class ChatService
{
    /**
     * Get all conversations for a user.
     */
    public function getUserConversations(User $user): Collection
    {
        return Conversation::where('user_one_id', $user->id)
            ->orWhere('user_two_id', $user->id)
            ->with([
                'userOne.profile',
                'userOne.primaryPhoto',
                'userTwo.profile',
                'userTwo.primaryPhoto',
                'lastMessage'
            ])
            ->orderByDesc('last_message_at')
            ->get()
            ->map(function ($conversation) use ($user) {
                $otherUser = $conversation->getOtherUser($user->id);
                $conversation->other_user = $otherUser;
                $conversation->unread_count = $conversation->getUnreadCount($user->id);
                return $conversation;
            });
    }

    /**
     * Get or create conversation between two users.
     */
    public function getOrCreateConversation(User $user, User $otherUser, ?int $matchId = null): Conversation
    {
        return Conversation::createOrGet($user->id, $otherUser->id, $matchId);
    }

    /**
     * Get messages for a conversation.
     */
    public function getConversationMessages(Conversation $conversation, User $user, int $limit = 50): Collection
    {
        return $conversation->messages()
            ->visibleTo($user->id)
            ->with(['sender.profile', 'receiver.profile'])
            ->orderByDesc('created_at')
            ->limit($limit)
            ->get()
            ->reverse()
            ->values();
    }

    /**
     * Send a message.
     */
    public function sendMessage(
        Conversation $conversation,
        User $sender,
        string $content,
        string $type = 'text',
        ?string $mediaUrl = null
    ): Message {
        $receiver = $conversation->getOtherUser($sender->id);

        return Message::create([
            'conversation_id' => $conversation->id,
            'sender_id' => $sender->id,
            'receiver_id' => $receiver->id,
            'type' => $type,
            'content' => $content,
            'media_url' => $mediaUrl,
        ]);
    }

    /**
     * Mark conversation as read for user.
     */
    public function markConversationAsRead(Conversation $conversation, User $user): void
    {
        $conversation->markAsRead($user->id);
    }

    /**
     * Delete message for user.
     */
    public function deleteMessageForUser(Message $message, User $user): void
    {
        $message->deleteForUser($user->id);
    }

    /**
     * Get total unread message count for user.
     */
    public function getTotalUnreadCount(User $user): int
    {
        return Conversation::where('user_one_id', $user->id)
            ->sum('user_one_unread_count')
            + Conversation::where('user_two_id', $user->id)
            ->sum('user_two_unread_count');
    }

    /**
     * Check if user can message another user (must be matched).
     */
    public function canMessage(User $user, User $otherUser): bool
    {
        // Check if they have a match
        return $user->matches()
            ->where('matched_user_id', $otherUser->id)
            ->where('is_active', true)
            ->exists();
    }

    /**
     * Search messages in a conversation.
     */
    public function searchMessages(Conversation $conversation, User $user, string $query): Collection
    {
        return $conversation->messages()
            ->visibleTo($user->id)
            ->where('content', 'like', "%{$query}%")
            ->where('type', 'text')
            ->with(['sender.profile'])
            ->orderByDesc('created_at')
            ->limit(50)
            ->get();
    }

    /**
     * Get conversation statistics.
     */
    public function getConversationStats(Conversation $conversation): array
    {
        $totalMessages = $conversation->messages()->count();
        $userOneMessages = $conversation->messages()
            ->where('sender_id', $conversation->user_one_id)
            ->count();
        $userTwoMessages = $conversation->messages()
            ->where('sender_id', $conversation->user_two_id)
            ->count();

        return [
            'total_messages' => $totalMessages,
            'user_one_messages' => $userOneMessages,
            'user_two_messages' => $userTwoMessages,
            'started_at' => $conversation->created_at,
            'last_message_at' => $conversation->last_message_at,
        ];
    }
}
