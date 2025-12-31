<?php

namespace App\Events;

use App\Models\User;
use App\Models\UserMatch;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class MatchCreated implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public UserMatch $match;
    public User $user;
    public User $matchedUser;

    /**
     * Create a new event instance.
     */
    public function __construct(UserMatch $match, User $user, User $matchedUser)
    {
        $this->match = $match;
        $this->user = $user;
        $this->matchedUser = $matchedUser;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('user.' . $this->user->id),
            new PrivateChannel('user.' . $this->matchedUser->id),
        ];
    }

    /**
     * The event's broadcast name.
     */
    public function broadcastAs(): string
    {
        return 'match.created';
    }

    /**
     * Get the data to broadcast.
     *
     * @return array<string, mixed>
     */
    public function broadcastWith(): array
    {
        return [
            'match_id' => $this->match->id,
            'matched_user' => [
                'id' => $this->matchedUser->id,
                'name' => $this->matchedUser->name,
                'age' => $this->matchedUser->age,
                'avatar' => $this->matchedUser->primaryPhoto?->url,
                'city' => $this->matchedUser->city,
                'occupation' => $this->matchedUser->profile?->occupation,
            ],
            'created_at' => $this->match->created_at->toISOString(),
        ];
    }
}
