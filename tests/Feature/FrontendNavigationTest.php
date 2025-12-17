<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FrontendNavigationTest extends TestCase
{
    use RefreshDatabase;

    public function test_public_pages_and_authentication_forms_remain_available(): void
    {
        foreach (['/', '/services', '/about', '/contact', '/how-it-works'] as $url) {
            $this->get($url)->assertOk();
        }

        $this->get('/login')->assertOk()
            ->assertSee('name="_token"', false)
            ->assertSee('name="role"', false)
            ->assertSee('value="admin"', false)
            ->assertSee('name="remember"', false);
        $this->get('/register')->assertOk()
            ->assertSee('name="password_confirmation"', false)
            ->assertSee('name="registration_type"', false)
            ->assertSee(route('corporate.register'), false);
    }

    public function test_customer_workspace_preserves_booking_and_navigation_links(): void
    {
        $user = User::factory()->create(['role' => 'customer', 'onboarding_completed' => true]);
        $this->actingAs($user)->get(route('customer.dashboard'))->assertOk()
            ->assertSee('Active Bookings')
            ->assertSee(route('customer.history'), false)
            ->assertSee(route('customer.browse'), false)
            ->assertSee('name="currency"', false);
    }

    public function test_provider_workspace_preserves_jobs_and_location_controls(): void
    {
        $user = User::factory()->create(['role' => 'provider', 'onboarding_completed' => true]);
        $this->actingAs($user)->get(route('provider.dashboard'))->assertOk()
            ->assertSee('Recent Jobs')
            ->assertSee('id="provider-live-map"', false)
            ->assertSee(route('provider.jobs'), false)
            ->assertSee(route('provider.payouts.index'), false);
    }

    public function test_admin_navigation_is_available_with_mobile_toggle(): void
    {
        $user = User::factory()->create(['role' => 'admin', 'onboarding_completed' => true]);
        $this->actingAs($user)->get(route('admin.dashboard'))->assertOk()
            ->assertSee('aria-controls="admin-navigation"', false)
            ->assertSee(route('admin.users.index'), false)
            ->assertSee(route('admin.support.index'), false);
    }
}
