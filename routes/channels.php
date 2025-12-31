<?php

use App\Models\Conversation;
use Illuminate\Support\Facades\Broadcast;

/*
|--------------------------------------------------------------------------
| Broadcast Channels
|--------------------------------------------------------------------------
|
| Here you may register all of the event broadcasting channels that your
| application supports. The given channel authorization callbacks are
| used to check if an authenticated user can listen to the channel.
|
*/

/**
 * Private user channel - For user-specific notifications
 * Used for: new matches, video call incoming, etc.
 */
Broadcast::channel('user.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});

/**
 * Legacy private channel support
 */
Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});

/**
 * Private conversation channel - For messages between two users
 * Used for: new messages, typing indicators, message read receipts
 */
Broadcast::channel('conversation.{conversationId}', function ($user, $conversationId) {
    $conversation = Conversation::find($conversationId);

    if (!$conversation) {
        return false;
    }

    // User must be part of this conversation
    return (int) $conversation->user_one_id === (int) $user->id
        || (int) $conversation->user_two_id === (int) $user->id;
});

/**
 * Presence channel - For tracking online users
 * Used for: showing who's online, online status indicators
 */
Broadcast::channel('online', function ($user) {
    if (!$user) {
        return false;
    }

    return [
        'id' => $user->id,
        'name' => $user->name,
        'avatar' => $user->primaryPhoto?->url,
    ];
});

/**
 * Presence channel for a specific conversation
 * Used for: showing who's currently viewing the conversation
 */
Broadcast::channel('conversation.{conversationId}.presence', function ($user, $conversationId) {
    $conversation = Conversation::find($conversationId);

    if (!$conversation) {
        return false;
    }

    // User must be part of this conversation
    $isAuthorized = (int) $conversation->user_one_id === (int) $user->id
        || (int) $conversation->user_two_id === (int) $user->id;

    if (!$isAuthorized) {
        return false;
    }

    return [
        'id' => $user->id,
        'name' => $user->name,
        'avatar' => $user->primaryPhoto?->url,
    ];
});
