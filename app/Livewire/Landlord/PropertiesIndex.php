<?php

namespace App\Livewire\Landlord;

use App\Models\Property;
use App\Models\PropertyImage;
use App\Models\States\ContractStatus;
use App\Models\States\PropertyStatus;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\Validate;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;

#[Layout('components.layouts.landlord')]
#[Title('Properties')]
class PropertiesIndex extends Component
{
    use WithFileUploads, WithPagination;

    // ── Modal visibility ──────────────────────────────────────────────────────
    public bool $showPropertyForm = false;
    public bool $showPropertyDetails = false;

    // ── Form state ────────────────────────────────────────────────────────────
    public bool $isEditing = false;
    public ?int $editingPropertyId = null;

    #[Validate('required|string|max:255')]
    public string $formTitle = '';

    #[Validate('required|string|max:500')]
    public string $formAddress = '';

    #[Validate('required|in:daily,weekly,monthly,yearly')]
    public string $formPricingType = 'monthly';

    #[Validate('required|numeric|min:0')]
    public ?float $formPrice = null;

    #[Validate('nullable|string|max:5000')]
    public string $formDescription = '';

    #[Validate('nullable|string|max:100')]
    public string $formFloor = '';

    #[Validate('nullable|string|max:100')]
    public string $formSpaceNumber = '';

    #[Validate('nullable|string|max:255')]
    public string $formCity = '';

    #[Validate('nullable|numeric|between:-90,90')]
    public ?float $formLatitude = null;

    #[Validate('nullable|numeric|between:-180,180')]
    public ?float $formLongitude = null;

    #[Validate('required|string|max:10')]
    public string $formCurrency = 'USD';

    #[Validate('nullable|numeric|min:0')]
    public ?float $formSecurityDeposit = null;

    #[Validate('nullable|numeric|min:0')]
    public ?float $formApplicationFee = null;

    #[Validate('nullable|integer|min:1|max:120')]
    public ?int $formMinLeaseMonths = null;

    #[Validate('nullable|integer|min:1|max:120')]
    public ?int $formMaxLeaseMonths = null;

    #[Validate(['formImages.*' => 'nullable|image|max:10240'])]
    public array $formImages = [];

    // ── Details state ─────────────────────────────────────────────────────────
    public ?int $detailsPropertyId = null;

    // ── Lifecycle ─────────────────────────────────────────────────────────────

    public function mount(): void
    {
        if (! auth()->user()->landlord) {
            $this->redirect(route('dashboard'), navigate: true);
        }
    }

    // ── Modal actions ─────────────────────────────────────────────────────────

    public function openCreateForm(): void
    {
        $this->resetForm();
        $this->showPropertyForm = true;
    }

    public function openEditForm(int $id): void
    {
        $property = Property::findOrFail($id);

        $this->editingPropertyId = $id;
        $this->isEditing         = true;
        $this->formTitle         = $property->title;
        $this->formAddress       = $property->address_line_1 ?? '';
        $this->formFloor         = $property->floor ?? '';
        $this->formSpaceNumber   = $property->space_number ?? '';
        $this->formCity          = $property->city ?? '';
        $this->formPricingType   = $property->pricing_type ?? 'monthly';
        $this->formPrice         = (float) match ($this->formPricingType) {
            'daily'   => $property->daily_rent,
            'weekly'  => $property->weekly_rent,
            'yearly'  => $property->yearly_rent,
            default   => $property->monthly_rent,
        };
        $this->formDescription   = $property->description ?? '';
        $this->formLatitude         = $property->latitude ? (float) $property->latitude : null;
        $this->formLongitude        = $property->longitude ? (float) $property->longitude : null;
        $this->formCurrency         = $property->currency ?? 'USD';
        $this->formSecurityDeposit  = $property->security_deposit ? (float) $property->security_deposit : null;
        $this->formApplicationFee   = $property->application_fee ? (float) $property->application_fee : null;
        $this->formMinLeaseMonths   = $property->min_lease_months;
        $this->formMaxLeaseMonths   = $property->max_lease_months;
        $this->formImages           = [];
        $this->showPropertyForm     = true;
    }

    public function closeForm(): void
    {
        $this->showPropertyForm = false;
        $this->resetForm();
    }

    public function openDetails(int $id): void
    {
        $this->detailsPropertyId  = $id;
        $this->showPropertyDetails = true;
    }

    public function closeDetails(): void
    {
        $this->showPropertyDetails = false;
        $this->detailsPropertyId   = null;
    }

    // ── CRUD ──────────────────────────────────────────────────────────────────

    public function saveProperty(): void
    {
        $this->validate();

        $landlord  = auth()->user()->landlord;
        $rentField = match ($this->formPricingType) {
            'daily'  => 'daily_rent',
            'weekly' => 'weekly_rent',
            'yearly' => 'yearly_rent',
            default  => 'monthly_rent',
        };

        $data = [
            'title'          => $this->formTitle,
            'address_line_1' => $this->formAddress,
            'floor'          => $this->formFloor ?: null,
            'space_number'   => $this->formSpaceNumber ?: null,
            'city'           => $this->formCity ?: null,
            'latitude'       => $this->formLatitude,
            'longitude'      => $this->formLongitude,
            'pricing_type'     => $this->formPricingType,
            $rentField         => $this->formPrice,
            'description'      => $this->formDescription,
            'currency'         => $this->formCurrency,
            'security_deposit' => $this->formSecurityDeposit,
            'application_fee'  => $this->formApplicationFee,
            'min_lease_months' => $this->formMinLeaseMonths,
            'max_lease_months' => $this->formMaxLeaseMonths,
        ];

        if ($this->isEditing && $this->editingPropertyId) {
            $property = Property::findOrFail($this->editingPropertyId);
            abort_unless($property->landlord_id === $landlord->id, 403);
            $property->update($data);
        } else {
            $data['status']      = PropertyStatus::DRAFT->value;
            $data['landlord_id'] = $landlord->id;
            $property = $landlord->properties()->create($data);
        }

        $nextOrder = $property->images()->max('order') + 1;
        foreach ($this->formImages as $i => $image) {
            $path = $image->storePublicly('properties', 'public');
            $property->images()->create(['path' => $path, 'order' => $nextOrder + $i]);
        }

        $this->showPropertyForm = false;
        $this->resetForm();
        $this->resetPage();
    }

    public function removeFormImage(int $index): void
    {
        array_splice($this->formImages, $index, 1);
    }

    public function removeExistingImage(int $imageId): void
    {
        $image = PropertyImage::with('property')->findOrFail($imageId);
        abort_unless($image->property->landlord_id === auth()->user()->landlord->id, 403);
        Storage::disk('public')->delete($image->path);
        $image->delete();
    }

    public function publishProperty(int $id): void
    {
        $landlord = auth()->user()->landlord;
        $property = Property::findOrFail($id);
        abort_unless($property->landlord_id === $landlord->id, 403);
        $property->update(['status' => PropertyStatus::APPROVED->value]);
    }

    public function toggleActiveProperty(int $id): void
    {
        $landlord = auth()->user()->landlord;
        $property = Property::findOrFail($id);
        abort_unless($property->landlord_id === $landlord->id, 403);
        $property->update(['is_active' => ! $property->is_active]);
    }

    public function deleteProperty(int $id): void
    {
        $landlord = auth()->user()->landlord;
        $property = Property::findOrFail($id);
        abort_unless($property->landlord_id === $landlord->id, 403);
        foreach ($property->images as $img) {
            Storage::disk('public')->delete($img->path);
        }
        $property->delete();
        if ($this->detailsPropertyId === $id) {
            $this->showPropertyDetails = false;
            $this->detailsPropertyId   = null;
        }
        $this->resetPage();
    }

    // ── Helpers ───────────────────────────────────────────────────────────────

    #[Computed]
    public function existingImages()
    {
        if (! $this->editingPropertyId) {
            return collect();
        }

        return PropertyImage::where('property_id', $this->editingPropertyId)->orderBy('order')->get();
    }

    #[Computed]
    public function detailsProperty(): ?Property
    {
        if (! $this->detailsPropertyId) {
            return null;
        }

        return Property::with(['reviews.user', 'favorites', 'images', 'contracts' => fn ($q) => $q->where('contract_status', ContractStatus::ACTIVE->value)])
            ->withCount(['reviews', 'favorites'])
            ->withAvg('reviews', 'rating')
            ->find($this->detailsPropertyId);
    }

    private function resetForm(): void
    {
        $this->isEditing        = false;
        $this->editingPropertyId = null;
        $this->formTitle        = '';
        $this->formAddress      = '';
        $this->formPricingType  = 'monthly';
        $this->formPrice        = null;
        $this->formDescription  = '';
        $this->formFloor        = '';
        $this->formSpaceNumber  = '';
        $this->formCity         = '';
        $this->formLatitude         = null;
        $this->formLongitude        = null;
        $this->formCurrency         = 'USD';
        $this->formSecurityDeposit  = null;
        $this->formApplicationFee   = null;
        $this->formMinLeaseMonths   = null;
        $this->formMaxLeaseMonths   = null;
        $this->formImages           = [];
        $this->resetValidation();
    }

    // ── Render ────────────────────────────────────────────────────────────────

    public function render(): \Illuminate\Contracts\View\View
    {
        $landlord = auth()->user()->landlord;

        $properties = $landlord
            ? $landlord->properties()
                ->withCount(['reviews', 'favorites'])
                ->withAvg('reviews', 'rating')
                ->with(['images' => fn ($q) => $q->orderBy('order')->limit(1), 'contracts' => fn ($q) => $q->where('contract_status', ContractStatus::ACTIVE->value)->latest()])
                ->latest()
                ->paginate(9)
            : collect();

        return view('livewire.landlord.properties-index', compact('properties'));
    }
}
