<?php

namespace App\Livewire\Landlord;

use App\Models\States\ContractStatus;
use Livewire\Attributes\Lazy;
use Livewire\Component;

#[Lazy]
class Properties extends Component
{
    public function placeholder(): \Illuminate\Contracts\View\View
    {
        return view('livewire.landlord.properties-placeholder');
    }

    public function render(): \Illuminate\Contracts\View\View
    {
        $landlord = auth()->user()->landlord;

        $properties = $landlord
            ? $landlord->properties()
                ->with(['contracts' => fn ($q) => $q->where('contract_status', ContractStatus::ACTIVE->value)->latest()])
                ->latest()
                ->take(3)
                ->get()
            : collect();

        return view('livewire.landlord.properties', compact('properties'));
    }
}
