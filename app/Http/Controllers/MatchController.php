<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MatchController extends Controller
{
    /**
     * Display user's matches
     */
    public function index()
    {
        $user = Auth::user();

        // Get active matches with user details
        $matches = $user->matches()
            ->with(['matchedUser.profile', 'matchedUser.photos' => function ($q) {
                $q->where('status', 'approved')->orderBy('order');
            }])
            ->orderByDesc('matched_at')
            ->get();

        return view('matches.index', [
            'matches' => $matches,
        ]);
    }

    /**
     * Unmatch with a user
     */
    public function unmatch($matchId)
    {
        $user = Auth::user();

        $match = $user->matches()->findOrFail($matchId);
        $match->unmatch();

        return response()->json(['success' => true]);
    }
}
