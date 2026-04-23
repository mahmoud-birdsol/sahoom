<?php

namespace App\Livewire\Landlord;

use Livewire\Attributes\On;
use Livewire\Component;

class NotificationBadge extends Component
{
    #[On('notifications-updated')]
    public function refresh(): void
    {
        // Re-render is triggered automatically by the event.
    }

    public function render(): \Illuminate\Contracts\View\View
    {
        $count = auth()->user()->unreadNotifications()->count();

        return view('livewire.landlord.notification-badge', compact('count'));
    }
}
