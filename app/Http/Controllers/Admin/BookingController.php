<?php

namespace App\Http\Controllers\Admin;

use App\Enums\BookingStatus;
use App\Http\Controllers\Controller;
use App\Models\Booking;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class BookingController extends Controller
{
    /** URL action => resulting status. */
    private const ACTIONS = [
        'confirm' => BookingStatus::Confirmed,
        'complete' => BookingStatus::Completed,
        'cancel' => BookingStatus::Cancelled,
    ];

    public function index(Request $request): View
    {
        $status = BookingStatus::tryFrom((string) $request->query('status'));

        $bookings = Booking::query()
            ->with(['user', 'car', 'payment'])
            ->when($status, fn ($q) => $q->where('status', $status))
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('admin.bookings.index', compact('bookings', 'status'));
    }

    public function show(Booking $booking): View
    {
        $booking->load(['user', 'car', 'payment']);

        return view('admin.bookings.show', compact('booking'));
    }

    public function transition(Booking $booking, string $action): RedirectResponse
    {
        $to = self::ACTIONS[$action] ?? abort(404);

        if (! $booking->status->canBecome($to)) {
            return back()->with('error', "A {$booking->status->value} booking can't be {$to->value}.");
        }

        $booking->update(['status' => $to]);

        return back()->with('status', "Booking #{$booking->id} is now {$to->value}.");
    }
}
