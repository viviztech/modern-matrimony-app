<?php

namespace App\Http\Controllers;

use App\Models\Story;
use App\Models\StoryView;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class StoryController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // Get stories from matches only
        $matchedUserIds = $user->matches()
            ->pluck('user_id')
            ->merge($user->matchedBy()->pluck('matcher_id'))
            ->unique()
            ->toArray();

        // Get active stories from matches, grouped by user
        $stories = Story::with(['user.primaryPhoto', 'views'])
            ->whereIn('user_id', $matchedUserIds)
            ->active()
            ->orderBy('created_at', 'desc')
            ->get()
            ->groupBy('user_id');

        // Get user's own stories
        $myStories = Story::where('user_id', $user->id)
            ->active()
            ->orderBy('created_at', 'desc')
            ->get();

        return view('stories.index', compact('stories', 'myStories'));
    }

    public function create()
    {
        return view('stories.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'type' => 'required|in:photo,video,text',
            'media' => 'required_if:type,photo,video|file|mimes:jpg,jpeg,png,mp4,mov|max:51200',
            'text_content' => 'required_if:type,text|string|max:500',
            'background_color' => 'nullable|string|regex:/^#[0-9A-Fa-f]{6}$/',
        ]);

        $story = new Story();
        $story->user_id = Auth::id();
        $story->type = $request->type;
        $story->expires_at = now()->addHours(24);

        if ($request->hasFile('media')) {
            $file = $request->file('media');
            $path = $file->store('stories', 'public');
            $story->media_url = Storage::url($path);

            // Generate thumbnail for videos
            if ($request->type === 'video') {
                // TODO: Implement video thumbnail generation
                $story->thumbnail_url = $story->media_url;
            } else {
                $story->thumbnail_url = $story->media_url;
            }
        }

        if ($request->type === 'text') {
            $story->text_content = $request->text_content;
            $story->background_color = $request->background_color ?? '#667eea';
        }

        $story->save();

        return redirect()->route('stories.index')->with('success', 'Story created successfully!');
    }

    public function show(Story $story)
    {
        $user = Auth::user();

        // Check if user can view this story (must be a match or own story)
        $matchedUserIds = $user->matches()
            ->pluck('user_id')
            ->merge($user->matchedBy()->pluck('matcher_id'))
            ->unique()
            ->toArray();

        if ($story->user_id !== $user->id && !in_array($story->user_id, $matchedUserIds)) {
            abort(403, 'You can only view stories from your matches.');
        }

        // Record view if not already viewed
        if (!$story->isViewedBy($user) && $story->user_id !== $user->id) {
            StoryView::create([
                'story_id' => $story->id,
                'viewer_id' => $user->id,
                'viewed_at' => now(),
            ]);
            $story->incrementViews();
        }

        // Get all stories from this user
        $userStories = Story::where('user_id', $story->user_id)
            ->active()
            ->orderBy('created_at', 'asc')
            ->get();

        return view('stories.show', compact('story', 'userStories'));
    }

    public function destroy(Story $story)
    {
        if ($story->user_id !== Auth::id()) {
            abort(403, 'You can only delete your own stories.');
        }

        // Delete media files
        if ($story->media_url) {
            $path = str_replace('/storage/', '', $story->media_url);
            Storage::disk('public')->delete($path);
        }

        $story->delete();

        return redirect()->route('stories.index')->with('success', 'Story deleted successfully!');
    }

    public function viewers(Story $story)
    {
        if ($story->user_id !== Auth::id()) {
            abort(403, 'You can only view your own story viewers.');
        }

        $viewers = $story->views()
            ->with('viewer.primaryPhoto')
            ->orderBy('viewed_at', 'desc')
            ->get();

        return view('stories.viewers', compact('story', 'viewers'));
    }

    public function like(Story $story)
    {
        $story->incrementLikes();

        return response()->json([
            'success' => true,
            'likes_count' => $story->likes_count,
        ]);
    }
}
