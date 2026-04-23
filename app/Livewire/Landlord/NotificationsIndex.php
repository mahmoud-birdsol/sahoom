<?php

namespace App\Livewire\Landlord;

use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('components.layouts.landlord')]
#[Title('Notifications')]
class NotificationsIndex extends Component
{
    use WithPagination;

    public function mount(): void
    {
        if (! auth()->user()->landlord) {
            $this->redirect(route('dashboard'), navigate: true);
        }
    }

    public function markAllRead(): void
    {
        auth()->user()->unreadNotifications->markAsRead();
        $this->dispatch('notifications-updated');
    }

    public function markRead(string $id): void
    {
        auth()->user()->notifications()->where('id', $id)->update(['read_at' => now()]);
        $this->dispatch('notifications-updated');
    }

    public function deleteNotification(string $id): void
    {
        auth()->user()->notifications()->where('id', $id)->delete();
        $this->dispatch('notifications-updated');
    }

    public function render(): \Illuminate\Contracts\View\View
    {
        $notifications = auth()->user()
            ->notifications()
            ->paginate(20);

        $unreadCount = auth()->user()->unreadNotifications()->count();

        return view('livewire.landlord.notifications-index', compact('notifications', 'unreadCount'));
    }
}
