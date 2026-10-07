<?php

namespace Tests\Feature;

use App\Enums\CarStatus;
use App\Enums\UserRole;
use App\Http\Middleware\EnsureUserIsAdmin;
use App\Models\Booking;
use App\Models\Car;
use App\Models\User;
use Illuminate\Auth\Middleware\Authenticate;
use Illuminate\Foundation\Http\Middleware\ValidateCsrfToken;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Routing\Route as LaravelRoute;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Route;
use Tests\TestCase;

class SecurityTest extends TestCase
{
    use RefreshDatabase;

    /** @return list<string> resolved middleware class names for a route */
    private function middlewareOf(LaravelRoute $route): array
    {
        // Resolving the HTTP kernel syncs the middleware aliases and groups ("web", "auth", ...) to the router.
        app(\Illuminate\Contracts\Http\Kernel::class);

        return array_map(
            fn ($m) => is_string($m) ? explode(':', $m)[0] : get_class($m),
            app('router')->resolveMiddleware($route->gatherMiddleware(), $route->excludedMiddleware()),
        );
    }

    private function car(): Car
    {
        return Car::create([
            'brand' => 'Toyota', 'model' => 'Vios', 'year' => 2023, 'category' => 'Sedan',
            'price_per_day' => 1800, 'seats' => 5, 'transmission' => 'Automatic',
            'fuel_type' => 'Gasoline', 'status' => CarStatus::Available,
        ]);
    }

    // ---- Route-level guarantees ----

    public function test_every_admin_route_requires_login_and_the_admin_role(): void
    {
        $checked = 0;
        foreach (Route::getRoutes() as $route) {
            if (! str_starts_with((string) $route->getName(), 'admin.')) {
                continue;
            }
            $middleware = $this->middlewareOf($route);
            $this->assertContains(Authenticate::class, $middleware, "{$route->getName()} is missing auth");
            $this->assertContains(EnsureUserIsAdmin::class, $middleware, "{$route->getName()} is missing the admin check");
            $checked++;
        }
        $this->assertGreaterThan(10, $checked);
    }

    public function test_account_and_booking_routes_require_login(): void
    {
        $checked = 0;
        foreach (Route::getRoutes() as $route) {
            $name = (string) $route->getName();
            if (preg_match('/^(bookings|profile)\./', $name) || in_array($name, ['dashboard', 'logout', 'password.update', 'password.confirm'], true)) {
                $this->assertContains(Authenticate::class, $this->middlewareOf($route), "$name is missing auth");
                $checked++;
            }
        }
        $this->assertGreaterThan(8, $checked);
    }

    public function test_every_state_changing_route_has_csrf_protection(): void
    {
        $checked = 0;
        foreach (Route::getRoutes() as $route) {
            if (array_diff($route->methods(), ['GET', 'HEAD']) === []) {
                continue;
            }
            $this->assertContains(ValidateCsrfToken::class, $this->middlewareOf($route), "{$route->methods()[0]} {$route->uri()} has no CSRF protection");
            $checked++;
        }
        $this->assertGreaterThan(15, $checked);
    }

    public function test_every_rendered_post_form_carries_a_csrf_token(): void
    {
        $admin = User::factory()->create();
        $admin->forceFill(['role' => UserRole::Admin])->save();
        $car = $this->car();

        $pages = [
            [null, route('login')], [null, route('register')], [null, route('password.request')], [null, route('cars.show', $car)],
            [$admin, route('admin.dashboard')], [$admin, route('admin.cars.index')], [$admin, route('admin.cars.create')],
            [$admin, route('admin.cars.show', $car)], [$admin, route('admin.payments.index')], [$admin, route('profile.edit')],
        ];

        foreach ($pages as [$user, $url]) {
            $user ? $this->actingAs($user) : auth()->logout();
            $html = $this->get($url)->assertOk()->getContent();
            $this->assertGreaterThanOrEqual(
                preg_match_all('/<form[^>]+method="POST"/i', $html),
                preg_match_all('/name="_token"/', $html),
                "A POST form on $url has no CSRF token",
            );
        }
    }

    // ---- Mass assignment / privilege escalation ----

    public function test_registration_ignores_a_submitted_role_and_hashes_the_password(): void
    {
        $this->post('/register', [
            'name' => 'Eve', 'email' => 'eve@example.com', 'password' => 'password123', 'password_confirmation' => 'password123',
            'role' => 'admin', 'is_admin' => true,
        ])->assertRedirect(route('dashboard', absolute: false));

        $user = User::where('email', 'eve@example.com')->firstOrFail();
        $this->assertSame(UserRole::Customer, $user->role);
        $this->assertFalse($user->isAdmin());
        $this->assertNotSame('password123', $user->password);
        $this->assertTrue(Hash::check('password123', $user->password));
        $this->get(route('admin.dashboard'))->assertForbidden();
    }

    public function test_profile_update_cannot_change_the_role(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)->patch('/profile', ['name' => 'New Name', 'email' => $user->email, 'role' => 'admin'])
            ->assertSessionHasNoErrors();

        $this->assertSame('New Name', $user->fresh()->name);
        $this->assertSame(UserRole::Customer, $user->fresh()->role);
    }

    public function test_role_is_not_mass_assignable_on_the_user_model(): void
    {
        $user = User::create(['name' => 'X', 'email' => 'x@example.com', 'password' => 'secret123', 'role' => 'admin']);

        $this->assertSame(UserRole::Customer, $user->fresh()->role);
    }

    // ---- Customers vs. other customers' data ----

    public function test_customers_cannot_modify_or_delete_bookings_at_all(): void
    {
        $car = $this->car();
        $owner = User::factory()->create();
        $this->actingAs($owner)->post(route('bookings.store', $car), [
            'pickup_date' => today()->addDay()->toDateString(), 'return_date' => today()->addDays(3)->toDateString(),
        ]);
        $booking = Booking::firstOrFail();

        // No route exists for editing/deleting a booking as a customer...
        foreach (['patch', 'put', 'delete'] as $verb) {
            $this->actingAs($owner)->{$verb}("/bookings/{$booking->id}", ['status' => 'cancelled', 'total_price' => 1])->assertStatus(405);
        }

        // ...and a stranger can neither read it nor use the admin transition endpoint.
        $stranger = User::factory()->create();
        $this->actingAs($stranger)->get(route('bookings.show', $booking))->assertForbidden();
        $this->post(route('admin.bookings.transition', [$booking, 'confirm']))->assertForbidden();

        $fresh = $booking->fresh();
        $this->assertSame('pending', $fresh->status->value);
        $this->assertSame('3600.00', $fresh->total_price);
    }

    public function test_customers_cannot_change_car_prices(): void
    {
        $car = $this->car();
        $customer = User::factory()->create();

        $this->actingAs($customer)->put(route('admin.cars.update', $car), ['price_per_day' => 1])->assertForbidden();
        $this->patch(route('admin.cars.status', $car), ['status' => 'maintenance'])->assertForbidden();
        $this->patch("/cars/{$car->id}", ['price_per_day' => 1])->assertStatus(405);

        $this->assertSame('1800.00', $car->fresh()->price_per_day);
    }

    // ---- Booking input hardening ----

    public function test_absurd_rental_lengths_and_far_future_dates_are_rejected(): void
    {
        $this->actingAs(User::factory()->create());
        $car = $this->car();

        $this->post(route('bookings.store', $car), ['pickup_date' => today()->addDay()->toDateString(), 'return_date' => '9999-12-31'])
            ->assertSessionHasErrors('return_date');
        $this->post(route('bookings.store', $car), ['pickup_date' => today()->addYears(3)->toDateString(), 'return_date' => today()->addYears(3)->toDateString()])
            ->assertSessionHasErrors('pickup_date');
        $this->post(route('bookings.store', $car), ['pickup_date' => 'garbage', 'return_date' => ['x']])
            ->assertSessionHasErrors(['pickup_date', 'return_date']);

        $this->assertSame(0, Booking::count());
    }

    public function test_booking_submissions_are_rate_limited(): void
    {
        $this->actingAs(User::factory()->create());
        $car = $this->car();

        foreach (range(1, 10) as $i) {
            $this->post(route('bookings.store', $car), [])->assertSessionHasErrors();
        }
        $this->post(route('bookings.store', $car), [])->assertStatus(429);
    }

    public function test_login_is_rate_limited(): void
    {
        $user = User::factory()->create();

        foreach (range(1, 5) as $i) {
            $this->post('/login', ['email' => $user->email, 'password' => 'wrong'])->assertSessionHasErrors('email');
        }
        $this->post('/login', ['email' => $user->email, 'password' => 'wrong'])
            ->assertSessionHasErrors('email');
        $this->assertStringContainsString('Too many login attempts', session('errors')->first('email'));
    }

    public function test_logout_requires_post_and_ends_the_session(): void
    {
        $this->actingAs(User::factory()->create());

        $this->get('/logout')->assertStatus(405);
        $this->post('/logout')->assertRedirect('/');
        $this->assertGuest();
    }
}
