<?php

namespace App\Livewire\Landlord;

use App\Models\States\ViewingRequestStatus;
use App\Models\ViewingRequest;
use Illuminate\Support\Collection;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('components.layouts.landlord')]
#[Title('Viewing Requests')]
class ViewingRequestsIndex extends Component
{
    use WithPagination;

    public string $filterStatus = '';
    public ?int $filterPropertyId = null;
    public bool $showDetails = false;
    public ?int $detailsRequestId = null;

    public function mount(): void
    {
        if (! auth()->user()->landlord) {
            $this->redirect(route('dashboard'), navigate: true);
        }
    }

    public function updatingFilterStatus(): void { $this->resetPage(); }
    public function updatingFilterPropertyId(): void { $this->resetPage(); }

    #[Computed]
    public function landlordProperties(): Collection
    {
        return auth()->user()->landlord?->properties()->orderBy('title')->get(['id', 'title']) ?? collect();
    }

    #[Computed]
    public function detailsRequest(): ?ViewingRequest
    {
        if (! $this->detailsRequestId) {
            return null;
        }

        return ViewingRequest::with('property')->find($this->detailsRequestId);
    }

    public function openDetails(int $id): void
    {
        $this->detailsRequestId = $id;
        $this->showDetails = true;
    }

    public function closeDetails(): void
    {
        $this->showDetails = false;
        $this->detailsRequestId = null;
    }

    public function updateStatus(int $id, string $status): void
    {
        $request = ViewingRequest::whereHas('property', function ($q) {
            $q->whereHas('landlord', fn ($q2) => $q2->where('user_id', auth()->id()));
        })->findOrFail($id);

        $request->update(['status' => $status, 'handled_by_user_id' => auth()->id()]);

        if ($this->detailsRequestId === $id) {
            unset($this->detailsRequest);
        }
    }

    public function render(): \Illuminate\Contracts\View\View
    {
        $landlord = auth()->user()->landlord;

        $propertyIds = $landlord
            ? $landlord->properties()->pluck('id')
            : collect();

        $requests = ViewingRequest::with('property')
            ->whereIn('property_id', $propertyIds)
            ->when($this->filterStatus, fn ($q) => $q->where('status', $this->filterStatus))
            ->when($this->filterPropertyId, fn ($q) => $q->where('property_id', $this->filterPropertyId))
            ->orderByDesc('created_at')
            ->paginate(15);

        $pendingCount = ViewingRequest::whereIn('property_id', $propertyIds)
            ->where('status', ViewingRequestStatus::NEW->value)
            ->count();

        return view('livewire.landlord.viewing-requests-index', compact('requests', 'pendingCount'));
    }
}
