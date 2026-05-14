<?php

namespace App\Livewire;

use App\Models\Article;
use App\Models\Contract;
use App\Models\Landlord;
use App\Models\Property;
use App\Models\PropertyReview;
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
            ->with([
                'images'    => fn ($q) => $q->orderBy('order')->limit(1),
                'contracts' => fn ($q) => $q->where('contract_status', ContractStatus::ACTIVE->value),
            ])
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

        $totalActiveProperties = Property::query()
            ->where('status', PropertyStatus::APPROVED->value)
            ->where('is_active', true)
            ->count();

        $totalLandlords = Landlord::query()
            ->whereHas('properties', fn ($q) => $q->where('status', PropertyStatus::APPROVED->value))
            ->count();

        $totalContracts = Contract::query()->count();

        $latestArticles = Article::query()
            ->published()
            ->orderByDesc('published_at')
            ->take(3)
            ->get();

        return view('livewire.home', [
            'popularProperties'    => $popularProperties,
            'properties'           => $properties,
            'totalActiveProperties' => $totalActiveProperties,
            'totalLandlords'       => $totalLandlords,
            'totalContracts'       => $totalContracts,
            'latestArticles'       => $latestArticles,
        ])->layout('components.layouts.public');
    }
}
