<?php

namespace App\Http\Controllers\Admin;

use App\Enums\PaymentStatus;
use App\Http\Controllers\Controller;
use App\Models\Payment;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class PaymentController extends Controller
{
    public const METHODS = ['Cash', 'GCash', 'Card', 'Bank transfer'];

    public function index(Request $request): View
    {
        $status = PaymentStatus::tryFrom((string) $request->query('status'));

        $payments = Payment::query()
            ->with(['booking.user', 'booking.car'])
            ->when($status, fn ($q) => $q->where('payment_status', $status))
            ->latest()
            ->paginate(12)
            ->withQueryString();

        $totals = [
            'paid' => (float) Payment::where('payment_status', PaymentStatus::Paid)->sum('amount'),
            'pending' => (float) Payment::where('payment_status', PaymentStatus::Pending)->sum('amount'),
        ];

        return view('admin.payments.index', ['payments' => $payments, 'status' => $status, 'totals' => $totals, 'methods' => self::METHODS]);
    }

    /** Record that a pending payment has been received. */
    public function markPaid(Request $request, Payment $payment): RedirectResponse
    {
        $data = $request->validate(['payment_method' => ['required', Rule::in(self::METHODS)]]);

        if ($payment->payment_status !== PaymentStatus::Pending) {
            return back()->with('error', 'Only pending payments can be marked as paid.');
        }

        $payment->update($data + ['payment_status' => PaymentStatus::Paid, 'paid_at' => now()]);

        return back()->with('status', "Payment for booking #{$payment->booking_id} marked as paid.");
    }
}
