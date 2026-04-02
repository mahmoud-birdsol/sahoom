<?php

namespace App\Livewire\Landlord;

use App\Models\Property;
use Illuminate\Support\Collection;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('components.layouts.landlord')]
#[Title('Traffic')]
class TrafficIndex extends Component
{
    use WithPagination;

    public string $period = '30'; // days

    public function mount(): void
    {
        if (! auth()->user()->landlord) {
            $this->redirect(route('dashboard'), navigate: true);
        }
    }

    #[Computed]
    public function totals(): array
    {
        $landlord = auth()->user()->landlord;
        if (! $landlord) {
            return ['views' => 0, 'favourites' => 0];
        }

        $propertyIds = $landlord->properties()->pluck('id');

        return [
            'views'      => \App\Models\PropertyVisit::whereIn('property_id', $propertyIds)
                ->where('visited_at', '>=', now()->subDays((int) $this->period))
                ->count(),
            'favourites' => \App\Models\PropertyFavorite::whereIn('property_id', $propertyIds)->count(),
        ];
    }

    public function render(): \Illuminate\Contracts\View\View
    {
        $landlord = auth()->user()->landlord;

        $properties = $landlord
            ? $landlord->properties()
                ->withCount([
                    'visits as total_views' => fn ($q) => $q->where(
                        'visited_at', '>=', now()->subDays((int) $this->period)
                    ),
                    'favorites as total_favourites',
                ])
                ->with(['images', 'contracts' => fn ($q) => $q->where('contract_status', 'active')->latest()->limit(1)])
                ->orderByDesc('total_views')
                ->paginate(12)
            : collect();

        return view('livewire.landlord.traffic-index', compact('properties'));
    }
}
