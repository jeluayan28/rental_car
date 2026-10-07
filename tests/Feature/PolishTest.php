<?php

namespace Tests\Feature;

use App\Enums\BookingStatus;
use App\Enums\CarStatus;
use App\Enums\PaymentStatus;
use App\Enums\UserRole;
use App\Models\Booking;
use App\Models\Car;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PolishTest extends TestCase
{
    use RefreshDatabase;

    private function car(array $attrs = []): Car
    {
        return Car::create($attrs + [
            'brand' => 'Toyota', 'model' => 'Vios', 'year' => 2023, 'category' => 'Sedan',
            'price_per_day' => 1800, 'seats' => 5, 'transmission' => 'Automatic',
            'fuel_type' => 'Gasoline', 'status' => CarStatus::Available,
            'image' => 'https://placehold.co/800x500?text=Vios',
        ]);
    }

    public function test_landing_page_follows_the_road_trip_journey(): void
    {
        $this->car();

        $html = $this->get('/')->assertOk()->getContent();

        // Order of the journey: hero, destination, vibe, car, collection, how it works, call to action.
        $positions = array_map(fn ($needle) => strpos($html, $needle), [
            'Your next', 'Where are you', 'vibe?', 'created equal', 'your ride.', 'Three steps', 'Ready to',
        ]);
        $this->assertNotContains(false, $positions, 'a landing section is missing');
        $sorted = $positions;
        sort($sorted);
        $this->assertSame($sorted, $positions, 'landing sections are out of order');

        $html = str_replace('&rsquo;', "'", $html);
        $this->assertStringContainsString('id="how-it-works"', $html);
        $this->assertStringContainsString('id="about"', $html);
        $this->assertStringContainsString('data-count="1"', $html); // live fleet numbers
    }

    public function test_cars_without_a_real_photo_get_the_branded_scene_not_a_placeholder_tile(): void
    {
        $this->car();

        $html = $this->get('/cars')->assertOk()->getContent();

        $this->assertStringNotContainsString('placehold.co', $html);
        $this->assertStringContainsString('role="img"', $html);
    }

    public function test_real_photos_are_still_used_when_present(): void
    {
        $this->car(['image' => 'https://example.com/vios.jpg']);

        $this->get('/cars')->assertOk()->assertSee('https://example.com/vios.jpg', false);
    }

    public function test_error_pages_are_branded(): void
    {
        $this->get('/definitely-not-a-page')->assertNotFound()->assertSee('Wrong turn')->assertSee('Back to home');

        $customer = User::factory()->create();
        $this->actingAs($customer)->get('/admin')->assertForbidden()->assertSee('Off limits');
    }

    public function test_admin_actions_show_a_toast(): void
    {
        $admin = User::factory()->create();
        $admin->forceFill(['role' => UserRole::Admin])->save();
        $car = $this->car();
        $booking = Booking::create([
            'user_id' => User::factory()->create()->id, 'car_id' => $car->id,
            'pickup_date' => today()->addDay(), 'return_date' => today()->addDays(3),
            'total_days' => 2, 'total_price' => 3600, 'status' => BookingStatus::Pending,
        ]);
        $booking->payment()->create(['amount' => 3600, 'payment_status' => PaymentStatus::Pending]);

        $this->actingAs($admin)->followingRedirects()
            ->from(route('admin.bookings.index'))
            ->post(route('admin.bookings.transition', [$booking, 'confirm']))
            ->assertOk()->assertSee('toast-in', false)->assertSee("Booking #{$booking->id} is now confirmed.");
    }

    public function test_toast_ignores_internal_status_flags(): void
    {
        $this->actingAs(User::factory()->create())
            ->withSession(['status' => 'profile-updated'])
            ->get(route('profile.edit'))->assertOk()->assertDontSee('toast-in', false);
    }
}
