<?php

namespace App\Http\Controllers;

use App\Models\Conversation;
use App\Models\Icebreaker;
use App\Models\Message;
use App\Models\User;
use App\Services\AudioService;
use App\Services\ChatService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MessageController extends Controller
{
    protected ChatService $chatService;
    protected AudioService $audioService;

    public function __construct(ChatService $chatService, AudioService $audioService)
    {
        $this->chatService = $chatService;
        $this->audioService = $audioService;
    }

    /**
     * Display a listing of conversations.
     */
    public function index()
    {
        $user = Auth::user();
        $conversations = $this->chatService->getUserConversations($user);
        $totalUnread = $this->chatService->getTotalUnreadCount($user);

        return view('messages.index', [
            'conversations' => $conversations,
            'totalUnread' => $totalUnread,
        ]);
    }

    /**
     * Show a specific conversation.
     */
    public function show(Conversation $conversation)
    {
        $user = Auth::user();

        // Ensure user is part of this conversation
        if ($conversation->user_one_id !== $user->id && $conversation->user_two_id !== $user->id) {
            abort(403, 'Unauthorized');
        }

        $messages = $this->chatService->getConversationMessages($conversation, $user);
        $otherUser = $conversation->getOtherUser($user->id);

        // Mark as read
        $this->chatService->markConversationAsRead($conversation, $user);

        return view('messages.show', [
            'conversation' => $conversation,
            'messages' => $messages,
            'otherUser' => $otherUser,
        ]);
    }

    /**
     * Start a conversation with a user.
     */
    public function create(User $user)
    {
        $currentUser = Auth::user();

        // Check if they can message (must be matched)
        if (!$this->chatService->canMessage($currentUser, $user)) {
            return redirect()->back()->with('error', 'You must match with this user first.');
        }

        // Get or create conversation
        $conversation = $this->chatService->getOrCreateConversation($currentUser, $user);

        return redirect()->route('messages.show', $conversation);
    }

    /**
     * Store a new message.
     */
    public function store(Request $request, Conversation $conversation)
    {
        $user = Auth::user();

        // Ensure user is part of this conversation
        if ($conversation->user_one_id !== $user->id && $conversation->user_two_id !== $user->id) {
            abort(403, 'Unauthorized');
        }

        $request->validate([
            'content' => 'required|string|max:1000',
            'type' => 'sometimes|in:text,image,voice,icebreaker',
            'icebreaker_id' => 'sometimes|exists:icebreakers,id',
        ]);

        $receiver = $conversation->getOtherUser($user->id);

        $messageData = [
            'conversation_id' => $conversation->id,
            'sender_id' => $user->id,
            'receiver_id' => $receiver->id,
            'type' => $request->input('type', 'text'),
            'content' => $request->input('content'),
        ];

        // Add icebreaker if provided
        if ($request->has('icebreaker_id')) {
            $messageData['icebreaker_id'] = $request->input('icebreaker_id');
            $messageData['type'] = 'icebreaker';

            // Increment icebreaker usage
            $icebreaker = Icebreaker::find($request->input('icebreaker_id'));
            if ($icebreaker) {
                $icebreaker->incrementUsage();
            }
        }

        $message = Message::create($messageData);

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => $message->load(['sender.profile']),
            ]);
        }

        return redirect()->back();
    }

    /**
     * Get messages (for AJAX polling).
     */
    public function getMessages(Request $request, Conversation $conversation)
    {
        $user = Auth::user();

        // Ensure user is part of this conversation
        if ($conversation->user_one_id !== $user->id && $conversation->user_two_id !== $user->id) {
            abort(403, 'Unauthorized');
        }

        $since = $request->input('since');

        $query = $conversation->messages()
            ->visibleTo($user->id)
            ->with(['sender.profile']);

        if ($since) {
            $query->where('id', '>', $since);
        } else {
            $query->orderByDesc('created_at')->limit(50);
        }

        $messages = $query->orderBy('created_at')->get();

        // Mark as read
        $this->chatService->markConversationAsRead($conversation, $user);

        return response()->json([
            'messages' => $messages,
            'unread_count' => $this->chatService->getTotalUnreadCount($user),
        ]);
    }

    /**
     * Delete a message.
     */
    public function destroy(Message $message)
    {
        $user = Auth::user();

        // Ensure user is sender or receiver
        if ($message->sender_id !== $user->id && $message->receiver_id !== $user->id) {
            abort(403, 'Unauthorized');
        }

        $this->chatService->deleteMessageForUser($message, $user);

        if (request()->expectsJson()) {
            return response()->json(['success' => true]);
        }

        return redirect()->back()->with('success', 'Message deleted');
    }

    /**
     * Mark conversation as read.
     */
    public function markAsRead(Conversation $conversation)
    {
        $user = Auth::user();

        // Ensure user is part of this conversation
        if ($conversation->user_one_id !== $user->id && $conversation->user_two_id !== $user->id) {
            abort(403, 'Unauthorized');
        }

        $this->chatService->markConversationAsRead($conversation, $user);

        return response()->json([
            'success' => true,
            'unread_count' => $this->chatService->getTotalUnreadCount($user),
        ]);
    }

    /**
     * Search messages in conversation.
     */
    public function search(Request $request, Conversation $conversation)
    {
        $user = Auth::user();

        // Ensure user is part of this conversation
        if ($conversation->user_one_id !== $user->id && $conversation->user_two_id !== $user->id) {
            abort(403, 'Unauthorized');
        }

        $request->validate([
            'query' => 'required|string|min:2',
        ]);

        $results = $this->chatService->searchMessages(
            $conversation,
            $user,
            $request->input('query')
        );

        return response()->json(['results' => $results]);
    }

    /**
     * Get icebreakers for a conversation.
     */
    public function getIcebreakers(Conversation $conversation)
    {
        $user = Auth::user();

        // Ensure user is part of this conversation
        if ($conversation->user_one_id !== $user->id && $conversation->user_two_id !== $user->id) {
            abort(403, 'Unauthorized');
        }

        $otherUser = $conversation->getOtherUser($user->id);

        // Get personalized icebreakers
        $icebreakers = Icebreaker::getPersonalized($user, $otherUser, 10);

        return response()->json(['icebreakers' => $icebreakers]);
    }

    /**
     * Upload and send voice message.
     */
    public function uploadVoice(Request $request, Conversation $conversation)
    {
        $user = Auth::user();

        // Ensure user is part of this conversation
        if ($conversation->user_one_id !== $user->id && $conversation->user_two_id !== $user->id) {
            abort(403, 'Unauthorized');
        }

        $request->validate([
            'voice' => 'required|file|mimes:webm,ogg,mp4,mpeg,wav|max:5120', // 5MB max
            'duration' => 'sometimes|integer|max:60', // 60 seconds max
        ]);

        try {
            // Upload voice file
            $voiceData = $this->audioService->uploadVoiceMessage(
                $request->file('voice'),
                $user->id
            );

            // Validate duration if provided
            $duration = $request->input('duration');
            if ($duration && !$this->audioService->validateDuration($duration)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Voice message duration exceeds maximum limit of 60 seconds',
                ], 422);
            }

            $receiver = $conversation->getOtherUser($user->id);

            // Create voice message
            $message = Message::create([
                'conversation_id' => $conversation->id,
                'sender_id' => $user->id,
                'receiver_id' => $receiver->id,
                'type' => 'voice',
                'media_url' => $voiceData['url'],
                'content' => $duration ? "Voice message ({$duration}s)" : 'Voice message',
            ]);

            return response()->json([
                'success' => true,
                'message' => $message->load(['sender.profile']),
                'voice_data' => $voiceData,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }
}
