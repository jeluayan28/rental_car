<?php

namespace App\Http\Controllers;

use App\Enums\BookingStatus;
use App\Models\Car;
use Illuminate\View\View;

class CarController extends Controller
{
    private const PER_PAGE = 6;

    public function index(): View
    {
        $cars = Car::query()
            ->available()
            ->orderBy('category')
            ->orderBy('brand')
            ->orderBy('model')
            ->paginate(self::PER_PAGE)
            ->withQueryString();

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
