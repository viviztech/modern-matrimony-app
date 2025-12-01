<?php

namespace App\Notifications;

use App\Models\Message;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewMessageNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public Message $message,
        public User $sender
    ) {}

    public function via(object $notifiable): array
    {
        $channels = ['database'];

        // Add email if user has email notifications enabled
        if ($notifiable->notification_preferences['new_message_email'] ?? false) {
            $channels[] = 'mail';
        }

        return $channels;
    }

    public function toMail(object $notifiable): MailMessage
    {
        $preview = $this->message->type === 'text'
            ? substr($this->message->content, 0, 100)
            : '[' . ucfirst($this->message->type) . ' message]';

        return (new MailMessage)
            ->subject('New message from ' . $this->sender->name)
            ->greeting('Hi ' . $notifiable->name . '!')
            ->line($this->sender->name . ' sent you a message:')
            ->line('"' . $preview . '"')
            ->action('Reply Now', route('messages.show', $this->message->conversation_id))
            ->line('Keep the conversation going!');
    }

    public function toArray(object $notifiable): array
    {
        $preview = $this->message->type === 'text'
            ? substr($this->message->content, 0, 50)
            : '[' . ucfirst($this->message->type) . ']';

        return [
            'type' => 'new_message',
            'message_id' => $this->message->id,
            'conversation_id' => $this->message->conversation_id,
            'sender_id' => $this->sender->id,
            'sender_name' => $this->sender->name,
            'sender_photo' => $this->sender->primaryPhoto?->thumbnail_url,
            'message_preview' => $preview,
            'message_type' => $this->message->type,
            'message' => $this->sender->name . ' sent you a message',
            'url' => route('messages.show', $this->message->conversation_id),
        ];
    }
}
