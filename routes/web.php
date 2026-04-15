<?php

use App\Livewire\Settings\Password;
use App\Livewire\Settings\Profile;
use App\Livewire\Settings\TwoFactor;
use Illuminate\Support\Facades\Route;
use Laravel\Fortify\Features;

Route::get('/', App\Livewire\Home::class)->name('home');
Route::get('/properties', App\Livewire\PropertyList::class)->name('properties.index');
Route::get('/properties/{slug}', App\Livewire\PropertyShow::class)->name('properties.show');

Route::view('/terms', 'pages.terms')->name('terms');
Route::view('/privacy', 'pages.privacy')->name('privacy');
Route::view('/faq', 'pages.faq')->name('faq');

Route::post('/locale', function () {
    session(['locale' => request('locale')]);
    return redirect()->back();
})->name('locale.switch');

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware(['auth', 'landlord'])->prefix('landlord')->group(function () {
    Route::get('dashboard', App\Livewire\Landlord\Dashboard::class)->name('landlord.dashboard');
    Route::get('properties', App\Livewire\Landlord\PropertiesIndex::class)->name('landlord.properties');
    Route::get('bookings', App\Livewire\Landlord\BookingsIndex::class)->name('landlord.bookings');
    Route::get('viewing-requests', App\Livewire\Landlord\ViewingRequestsIndex::class)->name('landlord.viewing-requests');
    Route::get('traffic', App\Livewire\Landlord\TrafficIndex::class)->name('landlord.traffic');
    Route::get('notifications', App\Livewire\Landlord\NotificationsIndex::class)->name('landlord.notifications');
});

Route::middleware(['auth'])->group(function () {
    Route::get('account', App\Livewire\UserAccount::class)->name('account');
    Route::get('account/{section}', App\Livewire\UserAccount::class)->name('account.section');
});

Route::middleware(['auth'])->group(function () {
    Route::redirect('settings', 'settings/profile');

    Route::get('settings/profile', Profile::class)->name('profile.edit');
    Route::get('settings/password', Password::class)->name('user-password.edit');
    Route::get('settings/two-factor', TwoFactor::class)
        ->middleware(
            when(
                Features::canManageTwoFactorAuthentication()
                    && Features::optionEnabled(Features::twoFactorAuthentication(), 'confirmPassword'),
                ['password.confirm'],
                [],
            ),
        )
        ->name('two-factor.show');
});
