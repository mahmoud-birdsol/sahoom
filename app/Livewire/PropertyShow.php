<?php

namespace App\Livewire;

use App\Models\Contract;
use App\Models\Property;
use App\Models\PropertyFavorite;
use App\Models\PropertyReview;
use App\Models\States\ContractStatus;
use App\Models\States\PropertyStatus;
use App\Models\PropertyVisit;
use App\Models\States\ViewingRequestStatus;
use App\Models\ViewingRequest;
use App\Notifications\NewApplicationNotification;
use Livewire\Attributes\Computed;
use Livewire\Component;

class PropertyShow extends Component
{
    public Property $property;

    // ── Booking sidebar ───────────────────────────────────────────────────────
    public string $moveInDate = '';

    public bool $tourSent = false;

    // ── Schedule tour form ────────────────────────────────────────────────────
    public string $renterName = '';

    public string $renterEmail = '';

    public string $renterPhone = '';

    public string $tourMessage = '';

    public bool $showTourForm = false;

    // ── Book Now modal ────────────────────────────────────────────────────────
    public bool $showBookingModal = false;

    public string $bookingName = '';

    public string $bookingEmail = '';

    public string $bookingPhone = '';

    public string $bookingMessage = '';

    public string $bookingEndDate = '';

    public bool $bookingSent = false;

    // ── Review form ───────────────────────────────────────────────────────────
    public int $reviewRating = 0;

    public string $reviewText = '';

    public bool $reviewSent = false;

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

        PropertyVisit::create([
            'property_id' => $this->property->id,
            'source'      => request()->headers->get('referer') ? 'referral' : 'direct',
            'ip_address'  => request()->ip(),
            'visited_at'  => now(),
        ]);
    }

    #[Computed]
    public function displayPrice(): float
    {
        $price = match ($this->property->pricing_type) {
            'daily'  => $this->property->daily_rent,
            'weekly' => $this->property->weekly_rent,
            'yearly' => $this->property->yearly_rent,
            default  => $this->property->monthly_rent,
        };

        return (float) ($price
            ?? $this->property->daily_rent
            ?? $this->property->weekly_rent
            ?? $this->property->monthly_rent
            ?? $this->property->yearly_rent
            ?? 0);
    }

    #[Computed]
    public function displayPriceUnit(): string
    {
        return match ($this->property->pricing_type) {
            'daily'  => 'day',
            'weekly' => 'week',
            'yearly' => 'year',
            default  => 'month',
        };
    }

    #[Computed]
    public function monthlyRent(): float
    {
        return (float) ($this->property->monthly_rent
            ?? ($this->property->weekly_rent !== null ? $this->property->weekly_rent * 4.33 : null)
            ?? ($this->property->daily_rent !== null ? $this->property->daily_rent * 30 : null)
            ?? ($this->property->yearly_rent !== null ? $this->property->yearly_rent / 12 : null)
            ?? 0);
    }

    #[Computed]
    public function dailyRate(): float
    {
        return (float) ($this->property->daily_rent
            ?? ($this->property->weekly_rent !== null ? $this->property->weekly_rent / 7 : null)
            ?? ($this->property->monthly_rent !== null ? $this->property->monthly_rent / 30 : null)
            ?? ($this->property->yearly_rent !== null ? $this->property->yearly_rent / 365 : null)
            ?? 0);
    }

    #[Computed]
    public function firstPeriodRent(): float
    {
        if ($this->moveInDate && $this->bookingEndDate) {
            $days = \Carbon\Carbon::parse($this->moveInDate)
                ->diffInDays(\Carbon\Carbon::parse($this->bookingEndDate));

            if ($days > 0) {
                return $this->calculateRentForDays($days);
            }
        }

        return $this->displayPrice;
    }

    private function calculateRentForDays(int $days): float
    {
        $total     = 0;
        $remaining = $days;

        if ($this->property->monthly_rent !== null && $remaining >= 30) {
            $months    = intdiv($remaining, 30);
            $total    += $months * (float) $this->property->monthly_rent;
            $remaining -= $months * 30;
        }

        if ($this->property->weekly_rent !== null && $remaining >= 7) {
            $weeks     = intdiv($remaining, 7);
            $total    += $weeks * (float) $this->property->weekly_rent;
            $remaining -= $weeks * 7;
        }

        if ($remaining > 0) {
            $total += $remaining * $this->dailyRate;
        }

        return round($total, 2);
    }

    #[Computed]
    public function firstPeriodRentLabel(): string
    {
        if ($this->moveInDate && $this->bookingEndDate) {
            $days = \Carbon\Carbon::parse($this->moveInDate)
                ->diffInDays(\Carbon\Carbon::parse($this->bookingEndDate));

            if ($days > 0) {
                return $days . ' ' . __('day') . ($days > 1 ? 's' : '') . ' ' . __('stay');
            }
        }

        return ucfirst(__($this->displayPriceUnit)) . ' ' . __('rent');
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
            'AED' => 'AED ',
            'CFA' => 'CFA ',
            'EUR' => '€',
            'GBP' => '£',
            default => '$',
        };
    }

    #[Computed]
    public function totalDueAtSigning(): float
    {
        return $this->firstPeriodRent + $this->securityDeposit + $this->applicationFee;
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
                'user_id' => auth()->id(),
                'property_id' => $this->property->id,
            ]);
        }

        unset($this->isFavorited);
    }

    public function openBookingModal(): void
    {
        if (auth()->check()) {
            $this->bookingName = auth()->user()->name ?? '';
            $this->bookingEmail = auth()->user()->email ?? '';
            $this->bookingPhone = auth()->user()->phone ?? '';
        }
        $this->showBookingModal = true;
    }

    public function openTourForm(): void
    {
        if (auth()->check()) {
            $this->renterName = auth()->user()->name ?? '';
            $this->renterEmail = auth()->user()->email ?? '';
            $this->renterPhone = auth()->user()->phone ?? '';
        }
        $this->showTourForm = true;
    }

    public function submitBooking(): void
    {
        $this->validate([
            'bookingName' => 'required|string|max:100',
            'bookingEmail' => 'required|email|max:150',
            'bookingPhone' => 'required|string|max:30',
            'moveInDate' => 'required|date|after_or_equal:today',
            'bookingEndDate' => 'nullable|date|after:moveInDate',
        ]);

        ViewingRequest::create([
            'property_id' => $this->property->id,
            'renter_name' => $this->bookingName,
            'renter_email' => $this->bookingEmail,
            'renter_phone' => $this->bookingPhone,
            'message' => $this->bookingMessage ?: 'Booking inquiry via Book Now.',
            'preferred_date' => $this->moveInDate,
            'start_date' => $this->moveInDate,
            'end_date' => $this->bookingEndDate ?: null,
            'status' => ViewingRequestStatus::NEW->value,
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

        $this->reset(['bookingName', 'bookingEmail', 'bookingPhone', 'bookingMessage', 'moveInDate', 'bookingEndDate']);
        $this->bookingSent = true;
        $this->showBookingModal = false;
    }

    public function scheduleTour(): void
    {
        $this->validate([
            'renterName' => 'required|string|max:100',
            'renterEmail' => 'required|email|max:150',
            'renterPhone' => 'required|string|max:30',
        ]);

        ViewingRequest::create([
            'property_id' => $this->property->id,
            'renter_name' => $this->renterName,
            'renter_email' => $this->renterEmail,
            'renter_phone' => $this->renterPhone,
            'message' => $this->tourMessage ?: null,
            'status' => ViewingRequestStatus::NEW->value,
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
        $this->tourSent = true;
    }

    public function submitReview(): void
    {
        if (! auth()->check()) {
            $this->redirect(route('login'));

            return;
        }

        $this->validate([
            'reviewRating' => 'required|integer|min:1|max:5',
            'reviewText' => 'nullable|string|max:1000',
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
