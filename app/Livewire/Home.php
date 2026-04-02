<?php

namespace App\Livewire;

use App\Models\Property;
use App\Models\States\ContractStatus;
use App\Models\States\PropertyStatus;
use Livewire\Component;

class Home extends Component
{
    public string $contactName    = '';
    public string $contactEmail   = '';
    public string $contactPhone   = '';
    public string $contactMessage = '';
    public bool   $messageSent    = false;

    protected function rules(): array
    {
        return [
            'contactName'    => 'required|string|max:100',
            'contactEmail'   => 'required|email|max:150',
            'contactPhone'   => 'nullable|string|max:30',
            'contactMessage' => 'required|string|max:2000',
        ];
    }

    public function sendMessage(): void
    {
        $this->validate();
        $this->reset(['contactName', 'contactEmail', 'contactPhone', 'contactMessage']);
        $this->messageSent = true;
    }

    public function render(): \Illuminate\View\View
    {
        $popularProperties = Property::query()
            ->where('status', PropertyStatus::APPROVED->value)
            ->where('is_active', true)
            ->where('is_featured', true)
            ->withCount(['reviews', 'favorites'])
            ->withAvg('reviews', 'rating')
            ->with(['images' => fn ($q) => $q->orderBy('order')->limit(1)])
            ->orderByDesc('favorites_count')
            ->take(4)
            ->get();

        $properties = Property::query()
            ->where('status', PropertyStatus::APPROVED->value)
            ->where('is_active', true)
            ->withCount(['reviews', 'favorites'])
            ->withAvg('reviews', 'rating')
            ->with([
                'images'    => fn ($q) => $q->orderBy('order')->limit(1),
                'contracts' => fn ($q) => $q->where('contract_status', ContractStatus::ACTIVE->value),
            ])
            ->latest()
            ->take(4)
            ->get();

        return view('livewire.home', [
            'popularProperties' => $popularProperties,
            'properties'        => $properties,
        ])->layout('components.layouts.public');
    }
}
