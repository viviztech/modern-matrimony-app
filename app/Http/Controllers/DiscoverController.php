<?php

namespace App\Http\Controllers;

use App\Models\Like;
use App\Models\User;
use App\Models\UserMatch;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DiscoverController extends Controller
{
    /**
     * Show discover/swipe interface
     */
    public function index(Request $request)
    {
        $user = Auth::user();

        // Get potential matches based on preferences
        $potentialMatches = $this->getPotentialMatches($user);

        return view('discover.index', [
            'potentialMatches' => $potentialMatches,
        ]);
    }

    /**
     * Get potential matches for the user
     */
    private function getPotentialMatches(User $user, int $limit = 10)
    {
        $preference = $user->preference;

        // Get users already liked or passed
        $alreadyInteractedIds = Like::where('user_id', $user->id)
            ->pluck('liked_user_id')
            ->toArray();

        // Base query for potential matches
        $query = User::query()
            ->with(['profile', 'photos' => function ($q) {
                $q->where('status', 'approved')->orderBy('order');
            }])
            ->where('id', '!=', $user->id)
            ->whereNotIn('id', $alreadyInteractedIds)
            ->where('is_active', true);

        // Apply gender preference (opposite gender by default)
        if ($user->gender === 'male') {
            $query->where('gender', 'female');
        } elseif ($user->gender === 'female') {
            $query->where('gender', 'male');
        }

        // Apply age preferences if set
        if ($preference && $preference->age_min && $preference->age_max) {
            $minDob = now()->subYears($preference->age_max)->format('Y-m-d');
            $maxDob = now()->subYears($preference->age_min)->format('Y-m-d');
            $query->whereBetween('dob', [$minDob, $maxDob]);
        }

        // Apply location radius if set
        if ($preference && $preference->distance_radius && $user->latitude && $user->longitude) {
            $radiusKm = $preference->distance_radius;

            // Haversine formula for distance calculation
            $query->whereRaw("
                (6371 * acos(
                    cos(radians(?)) *
                    cos(radians(latitude)) *
                    cos(radians(longitude) - radians(?)) +
                    sin(radians(?)) *
                    sin(radians(latitude))
                )) <= ?
            ", [$user->latitude, $user->longitude, $user->latitude, $radiusKm]);
        }

        // Prefer verified and premium users
        $query->orderByDesc('email_verified_at')
              ->orderByDesc('phone_verified_at')
              ->orderByDesc('is_premium')
              ->orderByDesc('profile_completion_percentage')
              ->inRandomOrder();

        return $query->limit($limit)->get();
    }

    /**
     * Handle like action
     */
    public function like(Request $request, User $targetUser)
    {
        $user = Auth::user();

        // Prevent liking yourself
        if ($user->id === $targetUser->id) {
            return response()->json(['error' => 'Cannot like yourself'], 400);
        }

        // Check if already interacted
        $existing = Like::where('user_id', $user->id)
            ->where('liked_user_id', $targetUser->id)
            ->first();

        if ($existing) {
            return response()->json(['error' => 'Already interacted'], 400);
        }

        // Create like
        $like = Like::create([
            'user_id' => $user->id,
            'liked_user_id' => $targetUser->id,
            'type' => $request->input('type', 'like'), // like or super_like
        ]);

        // Check for match
        $match = $like->checkForMatch();

        if ($match) {
            // Also create reciprocal match
            UserMatch::firstOrCreate([
                'user_id' => $targetUser->id,
                'matched_user_id' => $user->id,
            ]);

            return response()->json([
                'success' => true,
                'matched' => true,
                'match' => $match,
            ]);
        }

        return response()->json([
            'success' => true,
            'matched' => false,
        ]);
    }

    /**
     * Handle pass action
     */
    public function pass(User $targetUser)
    {
        $user = Auth::user();

        // Prevent passing yourself
        if ($user->id === $targetUser->id) {
            return response()->json(['error' => 'Cannot pass yourself'], 400);
        }

        // Check if already interacted
        $existing = Like::where('user_id', $user->id)
            ->where('liked_user_id', $targetUser->id)
            ->first();

        if ($existing) {
            return response()->json(['error' => 'Already interacted'], 400);
        }

        // Create pass record
        Like::create([
            'user_id' => $user->id,
            'liked_user_id' => $targetUser->id,
            'type' => 'pass',
        ]);

        return response()->json(['success' => true]);
    }

    /**
     * Get next batch of potential matches (for infinite scroll)
     */
    public function nextBatch(Request $request)
    {
        $user = Auth::user();
        $potentialMatches = $this->getPotentialMatches($user);

        return response()->json([
            'matches' => $potentialMatches,
        ]);
    }
}
