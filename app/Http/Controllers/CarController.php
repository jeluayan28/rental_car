<?php

namespace App\Http\Controllers;

use App\Enums\BookingStatus;
use App\Models\Car;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\View\View;
use Throwable;

class CarController extends Controller
{
    private const PER_PAGE = 6;

    public function index(): View
    {
        try {
            $cars = Car::query()
                ->available()
                ->orderBy('category')
                ->orderBy('brand')
                ->orderBy('model')
                ->paginate(self::PER_PAGE)
                ->withQueryString();
        } catch (Throwable $e) {
            // Database unreachable: show the empty state instead of an error page.
            report($e);
            $cars = new LengthAwarePaginator([], 0, self::PER_PAGE);
        }

        return view('cars.index', compact('cars'));
    }

    public function show(Car $car): View
    {
        // Upcoming reservations, so renters can see which dates are taken.
        $reserved = $car->bookings()
            ->whereIn('status', [BookingStatus::Pending, BookingStatus::Confirmed])
            ->whereDate('return_date', '>=', today())
            ->orderBy('pickup_date')
            ->limit(5)
            ->get(['pickup_date', 'return_date']);

        return view('cars.show', compact('car', 'reserved'));
    }
}
