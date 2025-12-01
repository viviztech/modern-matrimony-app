<?php

namespace App\Http\Controllers;

use App\Models\Game;
use App\Models\GameAnswer;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GameController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $activeGames = Game::where(function ($query) use ($user) {
            $query->where('user1_id', $user->id)
                ->orWhere('user2_id', $user->id);
        })
            ->where('status', '!=', 'completed')
            ->with(['user1', 'user2'])
            ->latest()
            ->get();

        $completedGames = Game::where(function ($query) use ($user) {
            $query->where('user1_id', $user->id)
                ->orWhere('user2_id', $user->id);
        })
            ->where('status', 'completed')
            ->with(['user1', 'user2'])
            ->latest()
            ->limit(10)
            ->get();

        return view('games.index', compact('activeGames', 'completedGames'));
    }

    public function create(User $partner)
    {
        return view('games.create', compact('partner'));
    }

    public function store(Request $request, User $partner)
    {
        $request->validate([
            'type' => 'required|in:compatibility_quiz,would_you_rather,twenty_one_questions,two_truths_lie',
        ]);

        $game = Game::create([
            'user1_id' => Auth::id(),
            'user2_id' => $partner->id,
            'type' => $request->type,
            'status' => 'pending',
        ]);

        return redirect()->route('games.play', $game)->with('success', 'Game started! Answer the questions to see your results.');
    }

    public function play(Game $game)
    {
        $user = Auth::user();

        // Check if user is part of this game
        if ($game->user1_id !== $user->id && $game->user2_id !== $user->id) {
            abort(403, 'You are not part of this game.');
        }

        $questions = config("games.{$game->type}", []);
        $userAnswers = $game->answers()->where('user_id', $user->id)->pluck('answer', 'question_key');
        $hasAnswered = $game->hasAnswered($user);
        $otherUser = $game->getOtherUser($user);
        $otherUserAnswered = $game->hasAnswered($otherUser);

        return view('games.play', compact('game', 'questions', 'userAnswers', 'hasAnswered', 'otherUser', 'otherUserAnswered'));
    }

    public function submitAnswers(Request $request, Game $game)
    {
        $user = Auth::user();

        // Check if user is part of this game
        if ($game->user1_id !== $user->id && $game->user2_id !== $user->id) {
            abort(403, 'You are not part of this game.');
        }

        // Check if user already answered
        if ($game->hasAnswered($user)) {
            return redirect()->route('games.results', $game)->with('info', 'You have already answered these questions.');
        }

        $questions = config("games.{$game->type}", []);

        // Save answers
        foreach ($request->answers as $questionKey => $answer) {
            GameAnswer::create([
                'game_id' => $game->id,
                'user_id' => $user->id,
                'question_key' => $questionKey,
                'answer' => $answer,
            ]);
        }

        // Update game status
        if ($game->status === 'pending') {
            $game->update(['status' => 'in_progress']);
        }

        // Check if both users have answered
        $otherUser = $game->getOtherUser($user);
        if ($game->hasAnswered($otherUser)) {
            $this->calculateResults($game);
            $game->markAsComplete();
            return redirect()->route('games.results', $game)->with('success', 'Game completed! Check out your results.');
        }

        return redirect()->route('games.index')->with('success', 'Answers submitted! Waiting for your partner to answer.');
    }

    public function results(Game $game)
    {
        $user = Auth::user();

        // Check if user is part of this game
        if ($game->user1_id !== $user->id && $game->user2_id !== $user->id) {
            abort(403, 'You are not part of this game.');
        }

        // Check if game is completed
        if (!$game->isComplete()) {
            return redirect()->route('games.play', $game)->with('info', 'Waiting for both players to complete the game.');
        }

        $user1Answers = $game->answers()->where('user_id', $game->user1_id)->pluck('answer', 'question_key');
        $user2Answers = $game->answers()->where('user_id', $game->user2_id)->pluck('answer', 'question_key');
        $questions = config("games.{$game->type}", []);

        return view('games.results', compact('game', 'user1Answers', 'user2Answers', 'questions'));
    }

    private function calculateResults(Game $game)
    {
        if ($game->type === 'compatibility_quiz') {
            $user1Answers = $game->answers()->where('user_id', $game->user1_id)->pluck('answer', 'question_key');
            $user2Answers = $game->answers()->where('user_id', $game->user2_id)->pluck('answer', 'question_key');

            $matches = 0;
            $total = count($user1Answers);

            foreach ($user1Answers as $key => $answer) {
                if (isset($user2Answers[$key]) && $answer === $user2Answers[$key]) {
                    $matches++;
                }
            }

            $score = $total > 0 ? round(($matches / $total) * 100) : 0;

            $game->update([
                'compatibility_score' => $score,
                'results' => [
                    'matches' => $matches,
                    'total' => $total,
                ],
            ]);
        }
    }
}
