<?php

namespace App\Notifications;

use App\Models\User;
use App\Models\VideoCall;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class VideoCallIncomingNotification extends Notification
{
    use Queueable;

    public function __construct(
        public VideoCall $call,
        public User $caller
    ) {}

    public function via(object $notifiable): array
    {
        // Real-time notification only, no email for incoming calls
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        return [
            'type' => 'video_call_incoming',
            'call_id' => $this->call->id,
            'caller_id' => $this->caller->id,
            'caller_name' => $this->caller->name,
            'caller_photo' => $this->caller->primaryPhoto?->thumbnail_url,
            'call_type' => $this->call->call_type,
            'message' => $this->caller->name . ' is calling you...',
            'url' => route('video-call.room', $this->call),
        ];
    }
}
