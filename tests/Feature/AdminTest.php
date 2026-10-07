<?php

namespace Tests\Feature;

use App\Enums\BookingStatus;
use App\Enums\CarStatus;
use App\Enums\PaymentStatus;
use App\Enums\UserRole;
use App\Models\Booking;
use App\Models\Car;
use App\Models\Payment;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class AdminTest extends TestCase
{
    use RefreshDatabase;

    private function admin(): User
    {
        $admin = User::factory()->create();
        $admin->forceFill(['role' => UserRole::Admin])->save();

        return $admin;
    }

    /** A real 1x1 PNG (the GD extension, needed by fake()->image(), isn't installed here). */
    private function png(string $name): UploadedFile
    {
        return UploadedFile::fake()->createWithContent($name, base64_decode('iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAADUlEQVR42mP8z8BQDwAEhQGAhKmMIQAAAABJRU5ErkJggg=='));
    }

    private function car(array $attrs = []): Car
    {
        return Car::create($attrs + [
            'brand' => 'Toyota', 'model' => 'Vios', 'year' => 2023, 'category' => 'Sedan',
            'price_per_day' => 1800, 'seats' => 5, 'transmission' => 'Automatic',
            'fuel_type' => 'Gasoline', 'status' => CarStatus::Available,
        ]);
    }

    private function booking(?User $user = null, ?Car $car = null, BookingStatus $status = BookingStatus::Pending, int $pickupIn = 1, int $days = 2): Booking
    {
        $car ??= $this->car();
        $booking = Booking::create([
            'user_id' => ($user ?? User::factory()->create())->id, 'car_id' => $car->id,
            'pickup_date' => today()->addDays($pickupIn), 'return_date' => today()->addDays($pickupIn + $days),
            'total_days' => $days, 'total_price' => $car->price_per_day * $days, 'status' => $status,
        ]);
        $booking->payment()->create(['amount' => $booking->total_price, 'payment_status' => PaymentStatus::Pending]);

        return $booking;
    }

    private function validCar(array $overrides = []): array
    {
        return $overrides + [
            'brand' => 'Honda', 'model' => 'City', 'year' => 2024, 'category' => 'Sedan',
            'description' => 'Nice', 'price_per_day' => 2100, 'seats' => 5,
            'transmission' => 'Automatic', 'fuel_type' => 'Gasoline', 'status' => 'available',
        ];
    }

    // ---- Authorization ----

    public function test_guests_are_redirected_and_customers_are_forbidden_everywhere(): void
    {
        $customer = User::factory()->create();
        $car = $this->car();
        $booking = $this->booking($customer, $car);

        $gets = [route('admin.dashboard'), route('admin.cars.index'), route('admin.cars.create'), route('admin.cars.show', $car),
            route('admin.cars.edit', $car), route('admin.bookings.index'), route('admin.bookings.show', $booking),
            route('admin.users.index'), route('admin.payments.index')];

        foreach ($gets as $url) {
            $this->get($url)->assertRedirect(route('login'));
        }

        $this->actingAs($customer);
        foreach ($gets as $url) {
            $this->get($url)->assertForbidden();
        }

        $this->post(route('admin.cars.store'), $this->validCar())->assertForbidden();
        $this->put(route('admin.cars.update', $car), $this->validCar())->assertForbidden();
        $this->patch(route('admin.cars.status', $car), ['status' => 'rented'])->assertForbidden();
        $this->delete(route('admin.cars.destroy', $car))->assertForbidden();
        $this->post(route('admin.bookings.transition', [$booking, 'confirm']))->assertForbidden();
        $this->patch(route('admin.payments.paid', $booking->payment), ['payment_method' => 'Cash'])->assertForbidden();

        $this->assertSame(1, Car::count());
        $this->assertSame(BookingStatus::Pending, $booking->fresh()->status);
    }

    public function test_admin_can_open_every_page(): void
    {
        $admin = $this->admin();
        $car = $this->car();
        $booking = $this->booking(null, $car);

        $this->actingAs($admin);
        foreach ([route('admin.dashboard'), route('admin.cars.index'), route('admin.cars.create'), route('admin.cars.show', $car),
            route('admin.cars.edit', $car), route('admin.bookings.index'), route('admin.bookings.show', $booking),
            route('admin.users.index'), route('admin.payments.index')] as $url) {
            $this->get($url)->assertOk();
        }
    }

    // ---- Dashboard stats ----

    public function test_dashboard_statistics(): void
    {
        $admin = $this->admin();
        $a = $this->car(['model' => 'A']);
        $this->car(['model' => 'B', 'status' => CarStatus::Rented]);
        $this->car(['model' => 'C', 'status' => CarStatus::Maintenance]);

        $this->booking(null, $a, BookingStatus::Pending);
        $this->booking(null, $a, BookingStatus::Pending, 10);
        $this->booking(null, $a, BookingStatus::Confirmed, -1, 3);          // in progress today
        $this->booking(null, $a, BookingStatus::Confirmed, 20);              // confirmed but future: not active
        $paid = $this->booking(null, $a, BookingStatus::Completed, -30);     // 3600
        $paid->payment->update(['payment_status' => PaymentStatus::Paid, 'paid_at' => now()]);
        $this->booking(null, $a, BookingStatus::Completed, -50);             // payment still pending: not revenue

        $stats = $this->actingAs($admin)->get(route('admin.dashboard'))->assertOk()->viewData('stats');

        $this->assertSame(3, $stats['total_cars']);
        $this->assertSame(1, $stats['available_cars']);
        $this->assertSame(1, $stats['active_rentals']);
        $this->assertSame(2, $stats['pending_bookings']);
        $this->assertSame(3600.0, $stats['revenue']);
    }

    // ---- Cars ----

    public function test_admin_can_create_a_car_with_an_uploaded_image(): void
    {
        Storage::fake('public');

        $this->actingAs($this->admin())
            ->post(route('admin.cars.store'), $this->validCar(['image_file' => $this->png('city.png')]))
            ->assertSessionHasNoErrors();

        $car = Car::firstOrFail();
        $this->assertSame('Honda', $car->brand);
        $this->assertSame(CarStatus::Available, $car->status);
        Storage::disk('public')->assertExists($car->image);
    }

    public function test_car_validation(): void
    {
        $this->actingAs($this->admin())
            ->post(route('admin.cars.store'), $this->validCar(['category' => 'Spaceship', 'price_per_day' => -5, 'status' => 'gone', 'seats' => 0]))
            ->assertSessionHasErrors(['category', 'price_per_day', 'status', 'seats']);

        $this->assertSame(0, Car::count());
    }

    public function test_admin_can_edit_a_car_and_replace_its_image(): void
    {
        Storage::fake('public');
        $admin = $this->admin();
        $car = $this->car(['image' => $this->png('old.png')->store('cars', 'public')]);
        $old = $car->image;

        $this->actingAs($admin)->put(route('admin.cars.update', $car), $this->validCar(['model' => 'Civic']))->assertSessionHasNoErrors();
        $this->assertSame($old, $car->fresh()->image, 'image is kept when nothing new is sent');

        $this->actingAs($admin)->put(route('admin.cars.update', $car), $this->validCar(['image_file' => $this->png('new.png')]));
        $this->assertNotSame($old, $car->fresh()->image);
        Storage::disk('public')->assertMissing($old);
        Storage::disk('public')->assertExists($car->fresh()->image);
        $this->assertSame('Honda', $car->fresh()->brand);
    }

    public function test_admin_can_change_car_status(): void
    {
        $car = $this->car();

        $this->actingAs($this->admin())->patch(route('admin.cars.status', $car), ['status' => 'maintenance'])->assertSessionHasNoErrors();
        $this->assertSame(CarStatus::Maintenance, $car->fresh()->status);

        $this->patch(route('admin.cars.status', $car), ['status' => 'nonsense'])->assertSessionHasErrors('status');
        $this->assertSame(CarStatus::Maintenance, $car->fresh()->status);
    }

    public function test_admin_can_delete_an_unbooked_car_but_not_a_booked_one(): void
    {
        $admin = $this->admin();
        $free = $this->car(['model' => 'Free']);
        $booked = $this->car(['model' => 'Booked']);
        $this->booking(null, $booked);

        $this->actingAs($admin)->delete(route('admin.cars.destroy', $free))->assertRedirect(route('admin.cars.index'));
        $this->assertModelMissing($free);

        $this->delete(route('admin.cars.destroy', $booked))->assertSessionHas('error');
        $this->assertModelExists($booked);
    }

    // ---- Bookings ----

    public function test_booking_lifecycle_transitions(): void
    {
        $admin = $this->admin();
        $booking = $this->booking();

        $this->actingAs($admin)->post(route('admin.bookings.transition', [$booking, 'complete']))->assertSessionHas('error');
        $this->assertSame(BookingStatus::Pending, $booking->fresh()->status);

        $this->post(route('admin.bookings.transition', [$booking, 'confirm']))->assertSessionHas('status');
        $this->assertSame(BookingStatus::Confirmed, $booking->fresh()->status);

        $this->post(route('admin.bookings.transition', [$booking, 'complete']))->assertSessionHas('status');
        $this->assertSame(BookingStatus::Completed, $booking->fresh()->status);

        $this->post(route('admin.bookings.transition', [$booking, 'cancel']))->assertSessionHas('error');
        $this->assertSame(BookingStatus::Completed, $booking->fresh()->status);
    }

    public function test_pending_and_confirmed_bookings_can_be_cancelled(): void
    {
        $admin = $this->admin();
        $pending = $this->booking();
        $confirmed = $this->booking(null, null, BookingStatus::Confirmed);

        $this->actingAs($admin)->post(route('admin.bookings.transition', [$pending, 'cancel']));
        $this->post(route('admin.bookings.transition', [$confirmed, 'cancel']));

        $this->assertSame(BookingStatus::Cancelled, $pending->fresh()->status);
        $this->assertSame(BookingStatus::Cancelled, $confirmed->fresh()->status);
    }

    public function test_unknown_booking_action_is_404(): void
    {
        $this->actingAs($this->admin())->post('/admin/bookings/'.$this->booking()->id.'/explode')->assertNotFound();
    }

    public function test_booking_list_filters_by_status(): void
    {
        $this->booking(null, null, BookingStatus::Pending);
        $this->booking(null, null, BookingStatus::Completed);

        $bookings = $this->actingAs($this->admin())->get(route('admin.bookings.index', ['status' => 'completed']))->viewData('bookings');
        $this->assertCount(1, $bookings);
        $this->assertSame(BookingStatus::Completed, $bookings->first()->status);
    }

    // ---- Users & payments ----

    public function test_users_can_be_filtered_by_role(): void
    {
        $admin = $this->admin();
        User::factory()->count(2)->create();

        $this->actingAs($admin);
        $this->assertCount(2, $this->get(route('admin.users.index', ['role' => 'customer']))->viewData('users'));
        $this->assertCount(1, $this->get(route('admin.users.index', ['role' => 'admin']))->viewData('users'));
        $this->assertCount(1, $this->get(route('admin.users.index', ['q' => $admin->email]))->viewData('users'));
    }

    public function test_admin_can_mark_a_payment_as_paid_once(): void
    {
        $payment = $this->booking()->payment;

        $this->actingAs($this->admin())->patch(route('admin.payments.paid', $payment), ['payment_method' => 'GCash'])->assertSessionHas('status');
        $payment->refresh();
        $this->assertSame(PaymentStatus::Paid, $payment->payment_status);
        $this->assertSame('GCash', $payment->payment_method);
        $this->assertNotNull($payment->paid_at);

        $this->patch(route('admin.payments.paid', $payment), ['payment_method' => 'Cash'])->assertSessionHas('error');
        $this->assertSame('GCash', $payment->fresh()->payment_method);

        $this->patch(route('admin.payments.paid', $this->booking()->payment), ['payment_method' => 'Bitcoin'])->assertSessionHasErrors('payment_method');
    }
}
