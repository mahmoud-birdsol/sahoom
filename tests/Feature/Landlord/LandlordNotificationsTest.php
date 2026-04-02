<?php

namespace Tests\Feature\Landlord;

use App\Models\Landlord;
use App\Models\User;
use App\Notifications\MaintenanceRequestNotification;
use App\Notifications\NewApplicationNotification;
use App\Notifications\PaymentReceivedNotification;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Livewire\Livewire;
use Tests\TestCase;

class LandlordNotificationsTest extends TestCase
{
    use RefreshDatabase;

    public function test_guests_are_redirected_to_login(): void
    {
        $this->get('/landlord/notifications')->assertRedirect('/login');
    }

    public function test_user_without_landlord_profile_is_redirected(): void
    {
        $this->actingAs(User::factory()->create());

        Livewire::test(\App\Livewire\Landlord\NotificationsIndex::class)
            ->assertRedirect(route('dashboard'));
    }

    public function test_landlord_can_visit_notifications_page(): void
    {
        $landlord = Landlord::factory()->create();
        $this->actingAs($landlord->user);

        $this->get(route('landlord.notifications'))->assertOk();
    }

    public function test_shows_empty_state_when_no_notifications(): void
    {
        $landlord = Landlord::factory()->create();
        $this->actingAs($landlord->user);

        Livewire::test(\App\Livewire\Landlord\NotificationsIndex::class)
            ->assertSee(__('No notifications yet'));
    }

    public function test_payment_notification_appears_in_list(): void
    {
        $landlord = Landlord::factory()->create();
        $landlord->user->notify(new PaymentReceivedNotification('Sarah Johnson', 'My Property', 2000));
        $this->actingAs($landlord->user);

        Livewire::test(\App\Livewire\Landlord\NotificationsIndex::class)
            ->assertSee('Payment received from Sarah Johnson');
    }

    public function test_new_application_notification_appears_in_list(): void
    {
        $landlord = Landlord::factory()->create();
        $landlord->user->notify(new NewApplicationNotification('Julia Smith', 'My Property'));
        $this->actingAs($landlord->user);

        Livewire::test(\App\Livewire\Landlord\NotificationsIndex::class)
            ->assertSee('New application received');
    }

    public function test_maintenance_notification_appears_in_list(): void
    {
        $landlord = Landlord::factory()->create();
        $landlord->user->notify(new MaintenanceRequestNotification('My Property', 'Plumbing issue'));
        $this->actingAs($landlord->user);

        Livewire::test(\App\Livewire\Landlord\NotificationsIndex::class)
            ->assertSee('Maintenance request');
    }

    public function test_mark_all_read_clears_unread_count(): void
    {
        $landlord = Landlord::factory()->create();
        $landlord->user->notify(new PaymentReceivedNotification('Sarah', 'My Property', 1000));
        $landlord->user->notify(new NewApplicationNotification('Tom', 'My Property'));
        $this->actingAs($landlord->user);

        $this->assertEquals(2, $landlord->user->unreadNotifications()->count());

        Livewire::test(\App\Livewire\Landlord\NotificationsIndex::class)
            ->call('markAllRead');

        $this->assertEquals(0, $landlord->user->fresh()->unreadNotifications()->count());
    }

    public function test_mark_single_notification_as_read(): void
    {
        $landlord = Landlord::factory()->create();
        $landlord->user->notify(new PaymentReceivedNotification('Sarah', 'My Property', 1000));
        $this->actingAs($landlord->user);

        $notifId = $landlord->user->notifications()->first()->id;

        Livewire::test(\App\Livewire\Landlord\NotificationsIndex::class)
            ->call('markRead', $notifId);

        $this->assertEquals(0, $landlord->user->fresh()->unreadNotifications()->count());
    }

    public function test_delete_notification_removes_it(): void
    {
        $landlord = Landlord::factory()->create();
        $landlord->user->notify(new PaymentReceivedNotification('Sarah', 'My Property', 1000));
        $this->actingAs($landlord->user);

        $notifId = $landlord->user->notifications()->first()->id;

        Livewire::test(\App\Livewire\Landlord\NotificationsIndex::class)
            ->call('deleteNotification', $notifId);

        $this->assertEquals(0, $landlord->user->fresh()->notifications()->count());
    }

    public function test_unread_count_is_passed_to_view(): void
    {
        $landlord = Landlord::factory()->create();
        $landlord->user->notify(new PaymentReceivedNotification('Sarah', 'My Property', 1000));
        $landlord->user->notify(new NewApplicationNotification('Tom', 'My Property'));
        $this->actingAs($landlord->user);

        Livewire::test(\App\Livewire\Landlord\NotificationsIndex::class)
            ->assertViewHas('unreadCount', 2);
    }
}
