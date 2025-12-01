<?php

namespace App\Notifications;

use App\Models\Like;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class LikeReceivedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public Like $like,
        public User $liker
    ) {}

    public function via(object $notifiable): array
    {
        $channels = ['database'];

        // Add email if user has email notifications enabled (premium feature)
        if (($notifiable->notification_preferences['likes_email'] ?? false) && $notifiable->isPremium()) {
            $channels[] = 'mail';
        }

        return $channels;
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Someone likes you!')
            ->greeting('Great news!')
            ->line($this->liker->name . ' likes your profile!')
            ->line('Like them back to start a conversation.')
            ->action('View Profile', route('profile.show', $this->liker))
            ->line('Don\'t keep them waiting!');
    }

    public function toArray(object $notifiable): array
    {
        return [
            'type' => 'like_received',
            'like_id' => $this->like->id,
            'liker_id' => $this->liker->id,
            'liker_name' => $this->liker->name,
            'liker_photo' => $this->liker->primaryPhoto?->thumbnail_url,
            'is_super_like' => $this->like->is_super_like,
            'message' => $this->like->is_super_like
                ? $this->liker->name . ' sent you a Super Like!'
                : $this->liker->name . ' likes you!',
            'url' => route('profile.show', $this->liker),
        ];
    }
}
