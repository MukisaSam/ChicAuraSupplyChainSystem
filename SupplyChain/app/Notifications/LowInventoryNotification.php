<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class LowInventoryNotification extends Notification
{
    use Queueable;

    protected $item;

    public function __construct($item)
    {
        $this->item = $item;
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toArray($notifiable)
    {
        return [
            'item_id' => $this->item->id,
            'item_name' => $this->item->name,
            'stock_quantity' => $this->item->stock_quantity,
            'message' => 'Low inventory alert: ' . $this->item->name . ' has only ' . $this->item->stock_quantity . ' units left.',
            'created_at' => now(),
        ];
    }
} 