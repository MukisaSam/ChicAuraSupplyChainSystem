<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ChatMessageNotification extends Notification
{
    use Queueable;

    protected $chatMessage;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($chatMessage)
    {
        $this->chatMessage = $chatMessage;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['database'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
                    ->line('The introduction to the notification.')
                    ->action('Notification Action', url('/'))
                    ->line('Thank you for using our application!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            'chat_message_id' => $this->chatMessage->id,
            'sender_id' => $this->chatMessage->sender_id,
            'sender_name' => $this->chatMessage->sender->name ?? 'Unknown',
            'content' => $this->chatMessage->content,
            'message' => 'New chat message from ' . ($this->chatMessage->sender->name ?? 'a user'),
            'created_at' => now(),
        ];
    }
}
