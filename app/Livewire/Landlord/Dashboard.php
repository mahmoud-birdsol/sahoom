<?php

namespace App\Livewire\Landlord;

use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('components.layouts.landlord')]
#[Title('Landlord Dashboard')]
class Dashboard extends Component
{
    public function mount(): void
    {
        if (! auth()->user()->landlord) {
            $this->redirect(route('dashboard'), navigate: true);
        }
    }

    public function render(): \Illuminate\Contracts\View\View
    {
        return view('livewire.landlord.dashboard');
    }
}
