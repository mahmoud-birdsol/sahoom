<?php

namespace App\Livewire;

use App\Models\Contract;
use App\Models\PropertyFavorite;
use App\Models\States\ContractStatus;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Livewire\Attributes\Computed;
use Livewire\Component;

class UserAccount extends Component
{
    public string $section = 'profile';

    // ── Profile form ─────────────────────────────────────────────────────────
    public string $name  = '';
    public string $email = '';
    public string $phone = '';
    public bool   $profileSaved = false;

    // ── Password form ────────────────────────────────────────────────────────
    public string $currentPassword  = '';
    public string $newPassword      = '';
    public string $confirmPassword  = '';
    public bool   $passwordSaved    = false;
    public ?string $passwordError   = null;

    // ── Book Now form (on property page) ─────────────────────────────────────
    public bool   $showBookingModal = false;

    public function mount(string $section = 'profile'): void
    {
        if (! Auth::check()) {
            $this->redirect(route('login'));
            return;
        }

        $this->section = $section;
        $this->fillProfileFromUser();
    }

    public function fillProfileFromUser(): void
    {
        $user        = Auth::user();
        $this->name  = $user->name ?? '';
        $this->email = $user->email ?? '';
        $this->phone = $user->phone ?? '';
    }

    public function setSection(string $section): void
    {
        $this->section      = $section;
        $this->profileSaved = false;
        $this->passwordSaved = false;
        $this->passwordError = null;
    }

    // ── Profile ───────────────────────────────────────────────────────────────
    public function saveProfile(): void
    {
        $this->validate([
            'name'  => 'required|string|max:150',
            'email' => 'required|email|max:200|unique:users,email,' . Auth::id(),
            'phone' => 'nullable|string|max:30',
        ]);

        Auth::user()->update([
            'name'  => $this->name,
            'email' => $this->email,
            'phone' => $this->phone,
        ]);

        $this->profileSaved = true;
    }

    // ── Password ──────────────────────────────────────────────────────────────
    public function changePassword(): void
    {
        $this->passwordError = null;
        $this->validate([
            'currentPassword' => 'required',
            'newPassword'     => 'required|string|min:8|confirmed:confirmPassword',
        ]);

        if (! Hash::check($this->currentPassword, Auth::user()->getAuthPassword())) {
            $this->passwordError = __('The current password is incorrect.');
            return;
        }

        Auth::user()->update(['password' => Hash::make($this->newPassword)]);

        $this->reset(['currentPassword', 'newPassword', 'confirmPassword']);
        $this->passwordSaved = true;
    }

    // ── My Rents ──────────────────────────────────────────────────────────────
    #[Computed]
    public function rents(): \Illuminate\Database\Eloquent\Collection
    {
        return Contract::query()
            ->where('renter_email', Auth::user()->email)
            ->with(['property' => fn ($q) => $q
                ->with('images')
                ->withAvg('reviews', 'rating'),
            ])
            ->latest()
            ->get();
    }

    // ── Favorites ─────────────────────────────────────────────────────────────
    #[Computed]
    public function favorites(): \Illuminate\Database\Eloquent\Collection
    {
        return PropertyFavorite::query()
            ->where('user_id', Auth::id())
            ->with(['property' => fn ($q) => $q
                ->with(['images'])
                ->withCount('reviews')
                ->withAvg('reviews', 'rating')
                ->withCount('contracts'),
            ])
            ->latest()
            ->get();
    }

    public function removeFavorite(int $propertyId): void
    {
        PropertyFavorite::where('user_id', Auth::id())
            ->where('property_id', $propertyId)
            ->delete();

        unset($this->favorites);
    }

    public function logout(): void
    {
        Auth::logout();
        $this->redirect(route('home'));
    }

    public function render(): \Illuminate\View\View
    {
        $titles = [
            'profile'   => __('Profile'),
            'password'  => __('Change Password'),
            'rents'     => __('My Rents'),
            'favorites' => __('Favorite List'),
        ];

        return view('livewire.user-account')
            ->layout('components.layouts.public', [
                'title' => $titles[$this->section] ?? __('My Account'),
            ]);
    }
}
