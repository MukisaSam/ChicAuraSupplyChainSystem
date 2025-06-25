<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class OrderStatusUpdatedNotification extends Notification
{
    use Queueable;

    protected $order;
    protected $newStatus;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($order, $newStatus)
    {
        $this->order = $order;
        $this->newStatus = $newStatus;
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
                    ->line('Order status updated.')
                    ->action('View Order', url('/wholesaler/orders/' . $this->order->id))
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
            'order_id' => $this->order->id,
            'order_number' => $this->order->order_number,
            'new_status' => $this->newStatus,
            'message' => 'Order #' . $this->order->order_number . ' status updated to ' . ucfirst(str_replace('_', ' ', $this->newStatus)),
            'created_at' => now(),
        ];
    }
} 