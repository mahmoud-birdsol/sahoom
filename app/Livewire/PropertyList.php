<?php

namespace App\Livewire;

use App\Models\Property;
use App\Models\States\ContractStatus;
use App\Models\States\PricingType;
use App\Models\States\PropertyStatus;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

class PropertyList extends Component
{
    use WithPagination;

    #[Url(as: 'q')]
    public string $search = '';

    #[Url]
    public string $pricingType = '';

    #[Url]
    public string $city = '';

    #[Url]
    public string $sort = 'newest';

    #[Url]
    public int $minPrice = 0;

    #[Url]
    public int $maxPrice = 0;

    #[Url]
    public bool $featuredOnly = false;

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function updatingPricingType(): void
    {
        $this->resetPage();
    }

    public function updatingCity(): void
    {
        $this->resetPage();
    }

    public function updatingSort(): void
    {
        $this->resetPage();
    }

    public function updatingMinPrice(): void
    {
        $this->resetPage();
    }

    public function updatingMaxPrice(): void
    {
        $this->resetPage();
    }

    public function updatingFeaturedOnly(): void
    {
        $this->resetPage();
    }

    public function clearFilters(): void
    {
        $this->reset(['search', 'pricingType', 'city', 'sort', 'minPrice', 'maxPrice', 'featuredOnly']);
        $this->sort = 'newest';
        $this->resetPage();
    }

    private function priceColumn(): string
    {
        return match ($this->pricingType) {
            'weekly' => 'weekly_rent',
            'yearly' => 'yearly_rent',
            'daily'  => 'daily_rent',
            default  => 'monthly_rent',
        };
    }

    public function render(): \Illuminate\View\View
    {
        $cities = Property::query()
            ->where('status', PropertyStatus::APPROVED->value)
            ->where('is_active', true)
            ->whereNotNull('city')
            ->distinct()
            ->pluck('city')
            ->sort()
            ->values();

        $priceCol = $this->priceColumn();

        $query = Property::query()
            ->where('status', PropertyStatus::APPROVED->value)
            ->where('is_active', true)
            ->withCount(['reviews', 'favorites'])
            ->withAvg('reviews', 'rating')
            ->with([
                'images'    => fn ($q) => $q->orderBy('order')->limit(1),
                'contracts' => fn ($q) => $q->where('contract_status', ContractStatus::ACTIVE->value),
            ]);

        if ($this->search !== '') {
            $query->where(function ($q) {
                $q->where('title', 'like', "%{$this->search}%")
                  ->orWhere('address_line_1', 'like', "%{$this->search}%")
                  ->orWhere('city', 'like', "%{$this->search}%");
            });
        }

        if ($this->pricingType !== '') {
            $query->where('pricing_type', $this->pricingType);
        }

        if ($this->city !== '') {
            $query->where('city', $this->city);
        }

        if ($this->featuredOnly) {
            $query->where('is_featured', true);
        }

        if ($this->minPrice > 0) {
            $query->where($priceCol, '>=', $this->minPrice);
        }

        if ($this->maxPrice > 0) {
            $query->where($priceCol, '<=', $this->maxPrice);
        }

        $query = match ($this->sort) {
            'price_asc'  => $query->orderBy($priceCol),
            'price_desc' => $query->orderByDesc($priceCol),
            'popular'    => $query->orderByDesc('favorites_count'),
            'rating'     => $query->orderByDesc('reviews_avg_rating'),
            default      => $query->latest(),
        };

        $properties = $query->paginate(12);

        return view('livewire.property-list', [
            'properties'   => $properties,
            'cities'       => $cities,
            'pricingTypes' => PricingType::cases(),
            'totalCount'   => $properties->total(),
        ])->layout('components.layouts.public', [
            'title' => __('Browse Properties'),
        ]);
    }
}
