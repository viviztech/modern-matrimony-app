<?php

namespace App\Notifications;

use App\Models\User;
use App\Models\UserMatch;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewMatchNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public UserMatch $match,
        public User $matchedUser
    ) {}

    public function via(object $notifiable): array
    {
        $channels = ['database'];

        // Add email if user has email notifications enabled
        if ($notifiable->notification_preferences['new_match_email'] ?? true) {
            $channels[] = 'mail';
        }

        return $channels;
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('You have a new match!')
            ->greeting('Congratulations!')
            ->line('You matched with ' . $this->matchedUser->name . '!')
            ->line('Start chatting now to get to know each other better.')
            ->action('View Profile', route('profile.show', $this->matchedUser))
            ->line('Good luck!');
    }

    public function toArray(object $notifiable): array
    {
        return [
            'type' => 'new_match',
            'match_id' => $this->match->id,
            'matched_user_id' => $this->matchedUser->id,
            'matched_user_name' => $this->matchedUser->name,
            'matched_user_photo' => $this->matchedUser->primaryPhoto?->thumbnail_url,
            'message' => 'You matched with ' . $this->matchedUser->name . '!',
            'url' => route('profile.show', $this->matchedUser),
        ];
    }
}
