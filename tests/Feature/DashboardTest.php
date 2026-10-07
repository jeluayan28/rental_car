<?php

namespace Tests\Feature;

use App\Enums\BookingStatus;
use App\Enums\CarStatus;
use App\Models\Booking;
use App\Models\Car;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DashboardTest extends TestCase
{
    use RefreshDatabase;

    private function booking(User $user, BookingStatus $status, int $pickupInDays, int $days = 2): Booking
    {
        $car = Car::firstOrCreate(['brand' => 'Toyota', 'model' => 'Vios'], [
            'year' => 2023, 'category' => 'Sedan', 'price_per_day' => 1800, 'seats' => 5,
            'transmission' => 'Automatic', 'fuel_type' => 'Gasoline', 'status' => CarStatus::Available,
        ]);

        return Booking::create([
            'user_id' => $user->id, 'car_id' => $car->id,
            'pickup_date' => today()->addDays($pickupInDays), 'return_date' => today()->addDays($pickupInDays + $days),
            'total_days' => $days, 'total_price' => 1800 * $days, 'status' => $status,
        ]);
    }

    public function test_guests_are_redirected_to_login(): void
    {
        $this->get('/dashboard')->assertRedirect(route('login'));
        $this->get('/bookings')->assertRedirect(route('login'));
    }

    public function test_empty_dashboard_shows_empty_states(): void
    {
        $this->actingAs(User::factory()->create())->get('/dashboard')
            ->assertOk()->assertSee('No active booking')->assertSee('Browse Cars')
            ->assertSee('View Bookings')->assertSee('Edit Profile');
    }

    public function test_active_booking_is_the_next_pending_or_confirmed_one(): void
    {
        $user = User::factory()->create();
        $this->booking($user, BookingStatus::Completed, -20);
        $this->booking($user, BookingStatus::Cancelled, 5);
        $later = $this->booking($user, BookingStatus::Pending, 30);
        $next = $this->booking($user, BookingStatus::Confirmed, 3);

        $response = $this->actingAs($user)->get('/dashboard')->assertOk();

        $this->assertTrue($response->viewData('active')->is($next));
        $this->assertCount(3, $response->viewData('history'));
        $this->assertTrue($response->viewData('history')->contains($later));
        $response->assertSee('Booking history');
    }

    public function test_ended_pending_booking_is_history_not_active(): void
    {
        $user = User::factory()->create();
        $this->booking($user, BookingStatus::Pending, -10);

        $response = $this->actingAs($user)->get('/dashboard');
        $this->assertNull($response->viewData('active'));
        $this->assertCount(1, $response->viewData('history'));
    }

    public function test_users_only_see_their_own_bookings(): void
    {
        $this->booking(User::factory()->create(), BookingStatus::Confirmed, 2);

        $response = $this->actingAs(User::factory()->create())->get('/dashboard');
        $this->assertNull($response->viewData('active'));
        $this->assertCount(0, $response->viewData('history'));
    }

    public function test_bookings_list_is_paginated_and_private(): void
    {
        $user = User::factory()->create();
        foreach (range(1, 10) as $i) {
            $this->booking($user, BookingStatus::Completed, -$i * 5);
        }
        $this->booking(User::factory()->create(), BookingStatus::Completed, -3);

        $response = $this->actingAs($user)->get('/bookings')->assertOk();
        $this->assertSame(10, $response->viewData('bookings')->total());
        $this->assertCount(8, $response->viewData('bookings')->items());
    }
}
