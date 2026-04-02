<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class MaintenanceRequestNotification extends Notification
{
    use Queueable;

    public function __construct(
        public string $propertyTitle,
        public string $issue,
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
            'type'           => 'maintenance_request',
            'icon'           => 'wrench-screwdriver',
            'color'          => 'amber',
            'title'          => 'Maintenance request',
            'subtitle'       => "{$this->propertyTitle} — {$this->issue}",
            'property_title' => $this->propertyTitle,
            'issue'          => $this->issue,
        ];
    }
}
