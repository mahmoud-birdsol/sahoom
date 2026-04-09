<?php

namespace App\Livewire;

use App\Models\Contract;
use App\Models\Property;
use App\Models\PropertyFavorite;
use App\Models\PropertyReview;
use App\Models\States\ContractStatus;
use App\Models\States\PropertyStatus;
use App\Models\States\ViewingRequestStatus;
use App\Models\ViewingRequest;
use App\Notifications\NewApplicationNotification;
use Livewire\Attributes\Computed;
use Livewire\Component;

class PropertyShow extends Component
{
    public Property $property;

    // ── Booking sidebar ───────────────────────────────────────────────────────
    public string $moveInDate     = '';
    public int    $leaseDuration  = 0;
    public bool   $tourSent       = false;

    // ── Schedule tour form ────────────────────────────────────────────────────
    public string $renterName    = '';
    public string $renterEmail   = '';
    public string $renterPhone   = '';
    public string $tourMessage   = '';
    public bool   $showTourForm  = false;

    // ── Book Now modal ────────────────────────────────────────────────────────
    public bool   $showBookingModal = false;
    public string $bookingName      = '';
    public string $bookingEmail     = '';
    public string $bookingPhone     = '';
    public string $bookingMessage   = '';
    public bool   $bookingSent      = false;

    // ── Review form ───────────────────────────────────────────────────────────
    public int    $reviewRating  = 0;
    public string $reviewText    = '';
    public bool   $reviewSent    = false;

    public function mount(string $slug): void
    {
        $this->property = Property::query()
            ->where('slug', $slug)
            ->where('status', PropertyStatus::APPROVED->value)
            ->where('is_active', true)
            ->with([
                'images',
                'amenities',
                'reviews.user',
                'contracts' => fn ($q) => $q->where('contract_status', ContractStatus::ACTIVE->value),
            ])
            ->withCount(['reviews', 'favorites'])
            ->withAvg('reviews', 'rating')
            ->firstOrFail();
    }

    #[Computed]
    public function monthlyRent(): float
    {
        return (float) ($this->property->monthly_rent
            ?? $this->property->weekly_rent * 4.33
            ?? $this->property->daily_rent * 30
            ?? $this->property->yearly_rent / 12
            ?? 0);
    }

    #[Computed]
    public function securityDeposit(): float
    {
        return (float) ($this->property->security_deposit ?? $this->monthlyRent);
    }

    #[Computed]
    public function applicationFee(): float
    {
        return (float) ($this->property->application_fee ?? 0);
    }

    #[Computed]
    public function currencySymbol(): string
    {
        return match (strtoupper($this->property->currency ?? 'USD')) {
            'SAR' => 'SAR ',
            'EUR' => '€',
            'GBP' => '£',
            default => '$',
        };
    }

    /**
     * @return array<int, int>
     */
    #[Computed]
    public function leaseDurationOptions(): array
    {
        $min = $this->property->min_lease_months ?? 1;
        $max = $this->property->max_lease_months ?? 24;
        $candidates = [1, 3, 6, 12, 24];

        return array_values(array_filter($candidates, fn (int $m) => $m >= $min && $m <= $max));
    }

    #[Computed]
    public function totalDueAtSigning(): float
    {
        return $this->monthlyRent + $this->securityDeposit + $this->applicationFee;
    }

    #[Computed]
    public function isFavorited(): bool
    {
        if (! auth()->check()) {
            return false;
        }

        return PropertyFavorite::where('user_id', auth()->id())
            ->where('property_id', $this->property->id)
            ->exists();
    }

    #[Computed]
    public function canWriteReview(): bool
    {
        if (! auth()->check()) {
            return false;
        }

        return Contract::where('property_id', $this->property->id)
            ->whereIn('contract_status', [ContractStatus::ACTIVE->value, ContractStatus::COMPLETED->value])
            ->where('renter_email', auth()->user()->email)
            ->exists();
    }

    public function toggleFavorite(): void
    {
        if (! auth()->check()) {
            $this->redirect(route('login'));
            return;
        }

        $existing = PropertyFavorite::where('user_id', auth()->id())
            ->where('property_id', $this->property->id)
            ->first();

        if ($existing) {
            $existing->delete();
        } else {
            PropertyFavorite::create([
                'user_id'     => auth()->id(),
                'property_id' => $this->property->id,
            ]);
        }

        unset($this->isFavorited);
    }

    public function openBookingModal(): void
    {
        if (auth()->check()) {
            $this->bookingName  = auth()->user()->name ?? '';
            $this->bookingEmail = auth()->user()->email ?? '';
            $this->bookingPhone = auth()->user()->phone ?? '';
        }
        $this->showBookingModal = true;
    }

    public function submitBooking(): void
    {
        $this->validate([
            'bookingName'    => 'required|string|max:100',
            'bookingEmail'   => 'required|email|max:150',
            'bookingPhone'   => 'required|string|max:30',
            'moveInDate'     => 'required|date|after_or_equal:today',
        ]);

        ViewingRequest::create([
            'property_id'    => $this->property->id,
            'renter_name'    => $this->bookingName,
            'renter_email'   => $this->bookingEmail,
            'renter_phone'   => $this->bookingPhone,
            'message'        => $this->bookingMessage ?: 'Booking inquiry via Book Now.',
            'preferred_date' => $this->moveInDate,
            'status'         => ViewingRequestStatus::NEW->value,
        ]);

        $landlordUser = $this->property->landlord?->user;
        if ($landlordUser) {
            $landlordUser->notify(new NewApplicationNotification(
                $this->bookingName,
                $this->property->title,
                route('landlord.viewing-requests'),
                'View Booking Request',
            ));
        }

        $this->reset(['bookingName', 'bookingEmail', 'bookingPhone', 'bookingMessage']);
        $this->bookingSent      = true;
        $this->showBookingModal = false;
    }

    public function scheduleTour(): void
    {
        $this->validate([
            'renterName'  => 'required|string|max:100',
            'renterEmail' => 'required|email|max:150',
            'renterPhone' => 'required|string|max:30',
            'moveInDate'  => 'required|date|after_or_equal:today',
        ]);

        ViewingRequest::create([
            'property_id'    => $this->property->id,
            'renter_name'    => $this->renterName,
            'renter_email'   => $this->renterEmail,
            'renter_phone'   => $this->renterPhone,
            'message'        => $this->tourMessage ?: null,
            'preferred_date' => $this->moveInDate,
            'status'         => ViewingRequestStatus::NEW->value,
        ]);

        $landlordUser = $this->property->landlord?->user;
        if ($landlordUser) {
            $landlordUser->notify(new NewApplicationNotification(
                $this->renterName,
                $this->property->title,
                route('landlord.viewing-requests'),
                'View Showing Request',
            ));
        }

        $this->reset(['renterName', 'renterEmail', 'renterPhone', 'tourMessage']);
        $this->showTourForm = false;
        $this->tourSent     = true;
    }

    public function submitReview(): void
    {
        if (! auth()->check()) {
            $this->redirect(route('login'));
            return;
        }

        $this->validate([
            'reviewRating' => 'required|integer|min:1|max:5',
            'reviewText'   => 'nullable|string|max:1000',
        ]);

        PropertyReview::updateOrCreate(
            ['user_id' => auth()->id(), 'property_id' => $this->property->id],
            ['rating' => $this->reviewRating, 'review' => $this->reviewText ?: null],
        );

        $this->reset(['reviewRating', 'reviewText']);
        $this->reviewSent = true;

        $this->property->loadCount('reviews')->loadAvg('reviews', 'rating');
    }

    public function render(): \Illuminate\View\View
    {
        return view('livewire.property-show')
            ->layout('components.layouts.public', ['title' => $this->property->title]);
    }
}
