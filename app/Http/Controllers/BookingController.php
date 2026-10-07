<?php

namespace App\Http\Controllers;

use App\Enums\BookingStatus;
use App\Enums\PaymentStatus;
use App\Http\Requests\StoreBookingRequest;
use App\Models\Booking;
use App\Models\Car;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class BookingController extends Controller
{
    public function store(StoreBookingRequest $request, Car $car): RedirectResponse
    {
        // Only the two dates are read from the request. Days and price are never accepted from the client.
        $pickup = $request->date('pickup_date');
        $return = $request->date('return_date');

        $booking = DB::transaction(function () use ($request, $car, $pickup, $return) {
            // Lock the car row so two simultaneous requests can't book the same dates.
            $car = Car::query()->whereKey($car->getKey())->lockForUpdate()->firstOrFail();

            // Re-check under the lock: the form request ran before it.
            if (! $car->isAvailable()) {
                throw ValidationException::withMessages(['car' => 'Sorry, this car is not available for booking.']);
            }

            if ($car->hasBookingBetween($pickup, $return)) {
                throw ValidationException::withMessages(['pickup_date' => 'This car was just booked for some of those dates. Please pick different dates.']);
            }

            $quote = $car->quote($pickup, $return);

            $booking = $request->user()->bookings()->create([
                'car_id' => $car->id,
                'pickup_date' => $pickup,
                'return_date' => $return,
                'total_days' => $quote['days'],
                'total_price' => $quote['total'],
                'status' => BookingStatus::Pending,
            ]);

            $booking->payment()->create([
                'amount' => $quote['total'],
                'payment_status' => PaymentStatus::Pending,
            ]);

            return $booking;
        });

        return redirect()->route('bookings.show', $booking);
    }

    public function index(Request $request): View
    {
        $bookings = $request->user()->bookings()
            ->with('car')
            ->latest('pickup_date')
            ->paginate(8);

        return view('bookings.index', compact('bookings'));
    }

    public function show(Booking $booking): View
    {
        Gate::authorize('view', $booking);

        $booking->load(['car', 'payment']);

        return view('bookings.show', compact('booking'));
    }
}
