<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PaymentReceivedNotification extends Notification
{
    use Queueable;

    public function __construct(
        public string $renterName,
        public string $propertyTitle,
        public float $amount,
    ) {}

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'type'           => 'payment_received',
            'icon'           => 'banknotes',
            'color'          => 'green',
            'title'          => "Payment received from {$this->renterName}",
            'subtitle'       => "{$this->propertyTitle} — \${$this->amount}",
            'renter_name'    => $this->renterName,
            'property_title' => $this->propertyTitle,
            'amount'         => $this->amount,
        ];
    }
}
