<?php

namespace App\Http\Controllers;

use App\Enums\BookingStatus;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    private const HISTORY_LIMIT = 5;

    public function index(Request $request): View
    {
        $user = $request->user();

        // The active booking is the next pending/confirmed one that hasn't ended yet.
        $active = $user->bookings()
            ->with(['car', 'payment'])
            ->whereIn('status', [BookingStatus::Pending, BookingStatus::Confirmed])
            ->whereDate('return_date', '>=', today())
            ->orderBy('pickup_date')
            ->first();

        $history = $user->bookings()
            ->with('car')
            ->when($active, fn ($query) => $query->whereKeyNot($active->id))
            ->latest('pickup_date')
            ->limit(self::HISTORY_LIMIT)
            ->get();

        $totalBookings = $user->bookings()->count();

        $completed = $user->bookings()->where('status', BookingStatus::Completed);
        $trips = $completed->count();
        $daysOnRoad = (int) $completed->sum('total_days');

        return view('dashboard', [
            'active' => $active,
            'history' => $history,
            'hasMoreHistory' => $totalBookings - ($active ? 1 : 0) > self::HISTORY_LIMIT,
            'trips' => $trips,
            'daysOnRoad' => $daysOnRoad,
        ]);
    }
}
