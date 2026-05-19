<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewOrderNotification extends Notification
{
    use Queueable;

    protected $order;

    public function __construct($order)
    {
        $this->order = $order;
    }

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        return [
            'order_id' => $this->order->id,
            'customer_name' => $this->order->customer->user->full_name,
            'total_price' => $this->order->total_price,
            'message' => 'Anda menerima pesanan pending dari ' . $this->order->customer->user->full_name,
            'type' => 'new_order',
            'icon' => 'fa-bag-shopping'
        ];
    }
}