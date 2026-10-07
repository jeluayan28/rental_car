<?php

namespace Tests\Feature;

use App\Enums\BookingStatus;
use App\Enums\CarStatus;
use App\Enums\PaymentStatus;
use App\Models\Booking;
use App\Models\Car;
use App\Models\Payment;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BookingTest extends TestCase
{
    use RefreshDatabase;

    private function car(array $attributes = []): Car
    {
        return Car::create($attributes + [
            'brand' => 'Toyota', 'model' => 'Vios', 'year' => 2023, 'category' => 'Sedan',
            'price_per_day' => 1800, 'seats' => 5, 'transmission' => 'Automatic',
            'fuel_type' => 'Gasoline', 'status' => CarStatus::Available,
        ]);
    }

    private function dates(int $pickupInDays, int $returnInDays): array
    {
        return [
            'pickup_date' => today()->addDays($pickupInDays)->toDateString(),
            'return_date' => today()->addDays($returnInDays)->toDateString(),
        ];
    }

    public function test_details_page_renders(): void
    {
        $this->get(route('cars.show', $this->car()))->assertOk()->assertSee('Reserve This Ride');
    }

    public function test_guest_is_redirected_to_login(): void
    {
        $this->post(route('bookings.store', $this->car()), $this->dates(1, 3))->assertRedirect(route('login'));
    }

    public function test_booking_is_created_with_server_side_total(): void
    {
        $user = User::factory()->create();
        $car = $this->car();

        // Tampered values from the browser must be ignored.
        $this->actingAs($user)->post(route('bookings.store', $car), $this->dates(1, 4) + [
            'total_days' => 99, 'total_price' => 1, 'user_id' => 999, 'status' => 'confirmed',
        ])->assertRedirect();

        $booking = Booking::firstOrFail();
        $this->assertSame(3, $booking->total_days);
        $this->assertSame('5400.00', $booking->total_price);
        $this->assertSame(BookingStatus::Pending, $booking->status);
        $this->assertSame($user->id, $booking->user_id);
    }

    public function test_pending_payment_is_created_with_the_booking(): void
    {
        $this->actingAs(User::factory()->create())->post(route('bookings.store', $this->car()), $this->dates(1, 3));

        $booking = Booking::firstOrFail();
        $this->assertSame(1, Payment::count());
        $this->assertSame($booking->id, $booking->payment->booking_id);
        $this->assertSame('3600.00', $booking->payment->amount);
        $this->assertSame(PaymentStatus::Pending, $booking->payment->payment_status);
        $this->assertNull($booking->payment->paid_at);
    }

    public function test_user_is_sent_to_the_confirmation_page(): void
    {
        $user = User::factory()->create();
        $response = $this->actingAs($user)->post(route('bookings.store', $this->car()), $this->dates(1, 3));

        $booking = Booking::firstOrFail();
        $response->assertRedirect(route('bookings.show', $booking));
        $this->actingAs($user)->get(route('bookings.show', $booking))
            ->assertOk()->assertSee('Reservation received')->assertSee('3,600.00');
    }

    public function test_confirmation_page_is_private_to_the_owner(): void
    {
        $owner = User::factory()->create();
        $this->actingAs($owner)->post(route('bookings.store', $this->car()), $this->dates(1, 3));
        $booking = Booking::firstOrFail();

        $this->actingAs(User::factory()->create())->get(route('bookings.show', $booking))->assertForbidden();
        $this->actingAs($owner)->get(route('bookings.show', $booking))->assertOk();
        auth()->logout();
        $this->get(route('bookings.show', $booking))->assertRedirect(route('login'));
    }

    public function test_same_day_return_counts_as_one_day(): void
    {
        $this->actingAs(User::factory()->create())->post(route('bookings.store', $this->car()), $this->dates(2, 2));
        $this->assertSame(1, Booking::firstOrFail()->total_days);
    }

    public function test_return_date_cannot_be_earlier_than_pickup(): void
    {
        $this->actingAs(User::factory()->create())->post(route('bookings.store', $this->car()), $this->dates(5, 2))
            ->assertSessionHasErrors('return_date');
        $this->assertSame(0, Booking::count());
    }

    public function test_pickup_cannot_be_in_the_past(): void
    {
        $this->actingAs(User::factory()->create())->post(route('bookings.store', $this->car()), $this->dates(-2, 1))
            ->assertSessionHasErrors('pickup_date');
    }

    public function test_unavailable_car_cannot_be_booked(): void
    {
        foreach ([CarStatus::Rented, CarStatus::Maintenance] as $status) {
            $car = $this->car(['status' => $status]);
            $this->actingAs(User::factory()->create())->post(route('bookings.store', $car), $this->dates(1, 3))
                ->assertSessionHasErrors('car');
        }
        $this->assertSame(0, Booking::count());
    }

    public function test_overlapping_dates_are_rejected(): void
    {
        $car = $this->car();
        $first = User::factory()->create();
        $this->actingAs($first)->post(route('bookings.store', $car), $this->dates(2, 5));

        $this->actingAs(User::factory()->create())->post(route('bookings.store', $car), $this->dates(4, 7))
            ->assertSessionHasErrors('pickup_date');
        $this->actingAs(User::factory()->create())->post(route('bookings.store', $car), $this->dates(6, 8))
            ->assertSessionDoesntHaveErrors();

        $this->assertSame(2, Booking::count());
    }
}
