<?php

namespace App\Http\Controllers;

use App\Models\Conversation;
use App\Models\User;
use App\Models\UserMatch;
use App\Models\VideoCall;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class VideoCallController extends Controller
{
    /**
     * Initiate a video call.
     */
    public function initiate(Request $request, User $receiver)
    {
        $caller = Auth::user();

        // Validate that they can initiate a call
        if (!VideoCall::canInitiate($caller, $receiver)) {
            return response()->json([
                'success' => false,
                'message' => 'Cannot initiate call. Either not matched or there is an active call.',
            ], 403);
        }

        $request->validate([
            'call_type' => 'required|in:video,audio',
            'conversation_id' => 'sometimes|exists:conversations,id',
        ]);

        // Get or find conversation
        $conversation = null;
        if ($request->has('conversation_id')) {
            $conversation = Conversation::find($request->input('conversation_id'));
        }

        // Get match
        $match = UserMatch::where('user_id', $caller->id)
            ->where('matched_user_id', $receiver->id)
            ->where('is_active', true)
            ->first();

        // Create video call
        $videoCall = VideoCall::create([
            'match_id' => $match?->id,
            'conversation_id' => $conversation?->id,
            'caller_id' => $caller->id,
            'receiver_id' => $receiver->id,
            'call_type' => $request->input('call_type', 'video'),
            'status' => 'initiated',
        ]);

        return response()->json([
            'success' => true,
            'call' => $videoCall->load(['caller', 'receiver']),
        ]);
    }

    /**
     * Update call status to ringing.
     */
    public function ring(VideoCall $videoCall)
    {
        $user = Auth::user();

        // Only receiver can mark as ringing
        if ($videoCall->receiver_id !== $user->id) {
            abort(403, 'Unauthorized');
        }

        $videoCall->markAsRinging();

        return response()->json([
            'success' => true,
            'call' => $videoCall->fresh(),
        ]);
    }

    /**
     * Accept a video call.
     */
    public function accept(VideoCall $videoCall)
    {
        $user = Auth::user();

        // Only receiver can accept
        if ($videoCall->receiver_id !== $user->id) {
            abort(403, 'Unauthorized');
        }

        // Can only accept initiated or ringing calls
        if (!in_array($videoCall->status, ['initiated', 'ringing'])) {
            return response()->json([
                'success' => false,
                'message' => 'Call cannot be accepted in current status.',
            ], 400);
        }

        $videoCall->start();

        return response()->json([
            'success' => true,
            'call' => $videoCall->fresh(),
        ]);
    }

    /**
     * Decline a video call.
     */
    public function decline(Request $request, VideoCall $videoCall)
    {
        $user = Auth::user();

        // Only receiver can decline
        if ($videoCall->receiver_id !== $user->id) {
            abort(403, 'Unauthorized');
        }

        $videoCall->decline($request->input('reason'));

        return response()->json([
            'success' => true,
            'call' => $videoCall->fresh(),
        ]);
    }

    /**
     * End a video call.
     */
    public function end(Request $request, VideoCall $videoCall)
    {
        $user = Auth::user();

        // Either caller or receiver can end
        if ($videoCall->caller_id !== $user->id && $videoCall->receiver_id !== $user->id) {
            abort(403, 'Unauthorized');
        }

        $videoCall->end($request->input('reason'));

        return response()->json([
            'success' => true,
            'call' => $videoCall->fresh(),
        ]);
    }

    /**
     * Mark call as missed (for timeout scenarios).
     */
    public function missed(VideoCall $videoCall)
    {
        $user = Auth::user();

        // Only caller can mark as missed (usually happens automatically)
        if ($videoCall->caller_id !== $user->id) {
            abort(403, 'Unauthorized');
        }

        $videoCall->markAsMissed();

        return response()->json([
            'success' => true,
            'call' => $videoCall->fresh(),
        ]);
    }

    /**
     * Get current call status.
     */
    public function status(VideoCall $videoCall)
    {
        $user = Auth::user();

        // Ensure user is part of this call
        if ($videoCall->caller_id !== $user->id && $videoCall->receiver_id !== $user->id) {
            abort(403, 'Unauthorized');
        }

        return response()->json([
            'call' => $videoCall->load(['caller', 'receiver']),
        ]);
    }

    /**
     * Rate a completed call.
     */
    public function rate(Request $request, VideoCall $videoCall)
    {
        $user = Auth::user();

        // Either caller or receiver can rate
        if ($videoCall->caller_id !== $user->id && $videoCall->receiver_id !== $user->id) {
            abort(403, 'Unauthorized');
        }

        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
        ]);

        $videoCall->rate($request->input('rating'));

        return response()->json([
            'success' => true,
            'call' => $videoCall->fresh(),
        ]);
    }

    /**
     * Report a call.
     */
    public function report(VideoCall $videoCall)
    {
        $user = Auth::user();

        // Either caller or receiver can report
        if ($videoCall->caller_id !== $user->id && $videoCall->receiver_id !== $user->id) {
            abort(403, 'Unauthorized');
        }

        $videoCall->report();

        return response()->json([
            'success' => true,
            'message' => 'Call reported successfully.',
        ]);
    }

    /**
     * Get call history for the authenticated user.
     */
    public function history(Request $request)
    {
        $user = Auth::user();

        $query = VideoCall::forUser($user->id)
            ->with(['caller.profile', 'receiver.profile'])
            ->orderByDesc('created_at');

        // Filter by status if provided
        if ($request->has('status')) {
            $query->where('status', $request->input('status'));
        }

        // Filter by call type if provided
        if ($request->has('call_type')) {
            $query->where('call_type', $request->input('call_type'));
        }

        $calls = $query->paginate(20);

        // Add other user info to each call
        $calls->getCollection()->transform(function ($call) use ($user) {
            $call->other_user = $call->caller_id === $user->id ? $call->receiver : $call->caller;
            $call->is_caller = $call->caller_id === $user->id;
            return $call;
        });

        if ($request->expectsJson()) {
            return response()->json($calls);
        }

        return view('video-calls.history', [
            'calls' => $calls,
        ]);
    }

    /**
     * Get call statistics for the authenticated user.
     */
    public function stats()
    {
        $user = Auth::user();

        $stats = VideoCall::getStatsForUser($user);

        return response()->json($stats);
    }

    /**
     * Check if user can initiate a call with another user.
     */
    public function canCall(User $receiver)
    {
        $caller = Auth::user();

        $canInitiate = VideoCall::canInitiate($caller, $receiver);

        return response()->json([
            'can_call' => $canInitiate,
        ]);
    }
}
