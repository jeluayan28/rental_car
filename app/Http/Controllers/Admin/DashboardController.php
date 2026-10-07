<?php

namespace App\Http\Controllers\Admin;

use App\Enums\BookingStatus;
use App\Enums\CarStatus;
use App\Enums\PaymentStatus;
use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Car;
use App\Models\Payment;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $stats = [
            'total_cars' => Car::count(),
            'available_cars' => Car::where('status', CarStatus::Available)->count(),
            // A rental is "active" while a confirmed booking's dates include today.
            'active_rentals' => Booking::where('status', BookingStatus::Confirmed)
                ->whereDate('pickup_date', '<=', today())
                ->whereDate('return_date', '>=', today())
                ->count(),
            'pending_bookings' => Booking::where('status', BookingStatus::Pending)->count(),
            // Revenue only counts payments that have actually been received.
            'revenue' => (float) Payment::where('payment_status', PaymentStatus::Paid)->sum('amount'),
        ];

        $pending = Booking::with(['user', 'car'])
            ->where('status', BookingStatus::Pending)
            ->oldest()
            ->limit(5)
            ->get();

        $recent = Booking::with(['user', 'car', 'payment'])->latest()->limit(6)->get();

        return view('admin.dashboard', compact('stats', 'pending', 'recent'));
    }
}
