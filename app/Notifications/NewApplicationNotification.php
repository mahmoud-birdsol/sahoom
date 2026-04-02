<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewApplicationNotification extends Notification
{
    use Queueable;

    public function __construct(
        public string $applicantName,
        public string $propertyTitle,
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
            'type'           => 'new_application',
            'icon'           => 'user-plus',
            'color'          => 'blue',
            'title'          => 'New application received',
            'subtitle'       => "{$this->propertyTitle} — {$this->applicantName}",
            'applicant_name' => $this->applicantName,
            'property_title' => $this->propertyTitle,
        ];
    }
}
