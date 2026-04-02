<?php

namespace App\Livewire\Landlord;

use App\Models\Contract;
use App\Models\States\ContractStatus;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('components.layouts.landlord')]
#[Title('Booking & Calendar')]
class BookingsIndex extends Component
{
    use WithPagination;

    // ── View toggle ───────────────────────────────────────────────────────────
    public string $view = 'list';

    // ── Filter ────────────────────────────────────────────────────────────────
    public ?int $filterPropertyId = null;

    // ── Calendar state ────────────────────────────────────────────────────────
    public string $calendarMonth; // 'Y-m'

    // ── Details modal ─────────────────────────────────────────────────────────
    public bool $showDetails = false;
    public ?int $detailsContractId = null;

    // ── Lifecycle ─────────────────────────────────────────────────────────────

    public function mount(): void
    {
        if (! auth()->user()->landlord) {
            $this->redirect(route('dashboard'), navigate: true);
        }

        $this->calendarMonth = now()->format('Y-m');
    }

    // ── View actions ──────────────────────────────────────────────────────────

    public function switchView(string $view): void
    {
        $this->view = $view;
        $this->resetPage();
    }

    // ── Calendar navigation ───────────────────────────────────────────────────

    public function prevMonth(): void
    {
        $this->calendarMonth = Carbon::parse($this->calendarMonth . '-01')
            ->subMonth()
            ->format('Y-m');
    }

    public function nextMonth(): void
    {
        $this->calendarMonth = Carbon::parse($this->calendarMonth . '-01')
            ->addMonth()
            ->format('Y-m');
    }

    // ── Details modal ─────────────────────────────────────────────────────────

    public function openDetails(int $id): void
    {
        $this->detailsContractId = $id;
        $this->showDetails       = true;
    }

    public function closeDetails(): void
    {
        $this->showDetails       = false;
        $this->detailsContractId = null;
    }

    // ── Computed ──────────────────────────────────────────────────────────────

    #[Computed]
    public function landlordProperties(): Collection
    {
        return auth()->user()->landlord?->properties()->orderBy('title')->get(['id', 'title']) ?? collect();
    }

    #[Computed]
    public function detailsContract(): ?Contract
    {
        if (! $this->detailsContractId) {
            return null;
        }

        return Contract::with(['property.images'])->find($this->detailsContractId);
    }

    #[Computed]
    public function calendarDate(): Carbon
    {
        return Carbon::parse($this->calendarMonth . '-01');
    }

    #[Computed]
    public function calendarDays(): Collection
    {
        $start  = $this->calendarDate->copy()->startOfMonth()->startOfWeek(Carbon::SUNDAY);
        $end    = $this->calendarDate->copy()->endOfMonth()->endOfWeek(Carbon::SATURDAY);
        $cursor = $start->copy();
        $days   = collect();

        while ($cursor->lte($end)) {
            $days->push($cursor->copy());
            $cursor->addDay();
        }

        return $days;
    }

    #[Computed]
    public function calendarContracts(): Collection
    {
        $landlord = auth()->user()->landlord;
        if (! $landlord) {
            return collect();
        }

        $start = $this->calendarDate->copy()->startOfMonth()->startOfWeek(Carbon::SUNDAY);
        $end   = $this->calendarDate->copy()->endOfMonth()->endOfWeek(Carbon::SATURDAY);

        $query = $landlord->contracts()
            ->with(['property'])
            ->where(function ($q) use ($start, $end) {
                $q->whereBetween('start_date', [$start, $end])
                    ->orWhereBetween('end_date', [$start, $end])
                    ->orWhere(function ($q2) use ($start, $end) {
                        $q2->where('start_date', '<=', $start)->where('end_date', '>=', $end);
                    });
            });

        if ($this->filterPropertyId) {
            $query->where('property_id', $this->filterPropertyId);
        }

        return $query->get();
    }

    #[Computed]
    public function upcomingContracts(): Collection
    {
        $landlord = auth()->user()->landlord;
        if (! $landlord) {
            return collect();
        }

        return $landlord->contracts()
            ->with(['property'])
            ->where('start_date', '>=', now())
            ->where('contract_status', ContractStatus::ACTIVE->value)
            ->orderBy('start_date')
            ->limit(5)
            ->get();
    }

    // ── Render ────────────────────────────────────────────────────────────────

    public function render(): \Illuminate\Contracts\View\View
    {
        $landlord = auth()->user()->landlord;

        $contracts = $landlord
            ? $landlord->contracts()
                ->with(['property'])
                ->when($this->filterPropertyId, fn ($q) => $q->where('property_id', $this->filterPropertyId))
                ->orderBy('start_date', 'desc')
                ->paginate(12)
            : collect();

        return view('livewire.landlord.bookings-index', compact('contracts'));
    }
}
