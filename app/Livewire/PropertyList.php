<?php

namespace App\Livewire;

use App\Models\Property;
use App\Models\PropertyFavorite;
use App\Models\States\ContractStatus;
use App\Models\States\PricingType;
use App\Models\States\PropertyStatus;
use Illuminate\Database\Eloquent\Builder;
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

    public bool $showMap = false;
    public bool $mapFilter = false;
    public float $mapNorth = 0;
    public float $mapSouth = 0;
    public float $mapEast = 0;
    public float $mapWest = 0;
    public string $mapMarkersJson = '[]';

    public function updatingSearch(): void { $this->resetPage(); }
    public function updatingPricingType(): void { $this->resetPage(); }
    public function updatingCity(): void { $this->resetPage(); }
    public function updatingSort(): void { $this->resetPage(); }
    public function updatingMinPrice(): void { $this->resetPage(); }
    public function updatingMaxPrice(): void { $this->resetPage(); }
    public function updatingFeaturedOnly(): void { $this->resetPage(); }

    public function updateMapBounds(float $north, float $south, float $east, float $west): void
    {
        $this->mapNorth = $north;
        $this->mapSouth = $south;
        $this->mapEast  = $east;
        $this->mapWest  = $west;
        $this->resetPage();
    }

    public function toggleShowMap(): void
    {
        $this->showMap = ! $this->showMap;
        if (! $this->showMap) {
            $this->mapFilter = false;
            $this->mapNorth = $this->mapSouth = $this->mapEast = $this->mapWest = 0;
            $this->resetPage();
        }
    }

    public function toggleMapFilter(): void
    {
        $this->mapFilter = ! $this->mapFilter;
        if (! $this->mapFilter) {
            $this->mapNorth = $this->mapSouth = $this->mapEast = $this->mapWest = 0;
        }
        $this->resetPage();
    }

    public function toggleFavorite(int $propertyId): void
    {
        if (! auth()->check()) {
            $this->redirect(route('login'));
            return;
        }

        $existing = PropertyFavorite::where('user_id', auth()->id())
            ->where('property_id', $propertyId)
            ->first();

        if ($existing) {
            $existing->delete();
        } else {
            PropertyFavorite::create([
                'user_id'     => auth()->id(),
                'property_id' => $propertyId,
            ]);
        }
    }

    public function applyFilters(): void
    {
        $this->resetPage();
    }

    public function clearFilters(): void
    {
        $this->reset(['search', 'pricingType', 'city', 'sort', 'minPrice', 'maxPrice', 'featuredOnly', 'mapFilter', 'mapNorth', 'mapSouth', 'mapEast', 'mapWest']);
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

    private function applyBaseFilters(Builder $query, string $priceCol): void
    {
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

        // ── Map markers (lighter query, no bounds filter, no pagination) ──────
        $markersQuery = Property::query()
            ->where('status', PropertyStatus::APPROVED->value)
            ->where('is_active', true)
            ->whereNotNull('latitude')
            ->whereNotNull('longitude');

        $this->applyBaseFilters($markersQuery, $priceCol);

        $this->mapMarkersJson = $markersQuery
            ->limit(500)
            ->get(['id', 'title', 'slug', 'latitude', 'longitude', 'monthly_rent', 'weekly_rent', 'daily_rent'])
            ->map(fn ($p) => [
                'id'    => $p->id,
                'lat'   => (float) $p->latitude,
                'lng'   => (float) $p->longitude,
                'title' => $p->title,
                'slug'  => $p->slug,
                'price' => $p->monthly_rent ?? $p->weekly_rent ?? $p->daily_rent,
            ])
            ->toJson();

        // ── Main query (with eager loads for the card list) ───────────────────
        $query = Property::query()
            ->where('status', PropertyStatus::APPROVED->value)
            ->where('is_active', true)
            ->withCount(['reviews', 'favorites'])
            ->withAvg('reviews', 'rating')
            ->with([
                'images'    => fn ($q) => $q->orderBy('order')->limit(1),
                'contracts' => fn ($q) => $q->where('contract_status', ContractStatus::ACTIVE->value),
            ]);

        $this->applyBaseFilters($query, $priceCol);

        // Apply map bounds filter when active
        if ($this->mapFilter && $this->mapNorth !== 0.0) {
            $query->whereBetween('latitude',  [$this->mapSouth, $this->mapNorth])
                  ->whereBetween('longitude', [$this->mapWest,  $this->mapEast]);
        }

        $query = match ($this->sort) {
            'price_asc'  => $query->orderBy($priceCol),
            'price_desc' => $query->orderByDesc($priceCol),
            'popular'    => $query->orderByDesc('favorites_count'),
            'rating'     => $query->orderByDesc('reviews_avg_rating'),
            default      => $query->latest(),
        };

        $properties = $query->paginate(12);

        $favoritedIds = auth()->check()
            ? PropertyFavorite::where('user_id', auth()->id())
                ->whereIn('property_id', $properties->pluck('id'))
                ->pluck('property_id')
                ->all()
            : [];

        return view('livewire.property-list', [
            'properties'   => $properties,
            'cities'       => $cities,
            'pricingTypes' => PricingType::cases(),
            'totalCount'   => $properties->total(),
            'favoritedIds' => $favoritedIds,
        ])->layout('components.layouts.public', [
            'title' => __('Browse Properties'),
        ]);
    }
}
