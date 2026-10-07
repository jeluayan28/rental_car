<x-admin-layout :title="'Booking #'.$booking->id">
    <x-slot:actions>
        <a href="{{ route('admin.bookings.index') }}" class="text-sm font-semibold text-cream/70 hover:text-sun">&larr; All bookings</a>
    </x-slot:actions>

    <div class="flex flex-wrap items-center gap-3">
        <x-badge :value="$booking->status" class="text-xs" />
        @foreach (['confirm' => \App\Enums\BookingStatus::Confirmed, 'complete' => \App\Enums\BookingStatus::Completed, 'cancel' => \App\Enums\BookingStatus::Cancelled] as $action => $to)
            @if ($booking->status->canBecome($to))
                <form method="POST" action="{{ route('admin.bookings.transition', [$booking, $action]) }}"
                      @if ($action === 'cancel') onsubmit="return confirm('Cancel this booking?')" @endif>
                    @csrf
                    <button class="rounded-full px-5 py-2 text-sm font-bold capitalize transition {{ $action === 'cancel' ? 'border border-cream/25 text-cream/80 hover:border-tangerine hover:text-tangerine' : 'bg-tangerine text-midnight hover:bg-sun' }}">{{ $action }}</button>
                </form>
            @endif
        @endforeach
        @if ($booking->status->allowedTransitions() === [])
            <span class="text-sm text-cream/50">This booking is closed. No further actions.</span>
        @endif
    </div>

    <div class="mt-8 grid gap-6 lg:grid-cols-3">
        <section class="rounded-2xl border border-cream/10 bg-graphite/60 p-6">
            <h2 class="text-[11px] font-semibold uppercase tracking-widest text-cream/50">Customer</h2>
            <p class="mt-3 text-lg font-bold">{{ $booking->user->name }}</p>
            <p class="text-sm text-cream/60">{{ $booking->user->email }}</p>
            <p class="mt-3 text-xs text-cream/45">Booked {{ $booking->created_at->format('M j, Y g:i A') }}</p>
        </section>

        <section class="rounded-2xl border border-cream/10 bg-graphite/60 p-6">
            <h2 class="text-[11px] font-semibold uppercase tracking-widest text-cream/50">Car</h2>
            <a href="{{ route('admin.cars.show', $booking->car) }}" class="mt-3 block text-lg font-bold hover:text-sun">{{ $booking->car->brand }} {{ $booking->car->model }}</a>
            <p class="text-sm text-cream/60">{{ $booking->car->category }} &middot; {{ $booking->car->year }}</p>
            <p class="mt-3 text-sm text-cream/60">&#8369;{{ number_format($booking->car->price_per_day) }} / day &middot; now <x-badge :value="$booking->car->status" /></p>
        </section>

        <section class="rounded-2xl border border-cream/10 bg-graphite/60 p-6">
            <h2 class="text-[11px] font-semibold uppercase tracking-widest text-cream/50">Rental</h2>
            <p class="mt-3 font-bold">{{ $booking->pickup_date->format('D, M j, Y') }} &rarr; {{ $booking->return_date->format('D, M j, Y') }}</p>
            <p class="text-sm text-cream/60">{{ $booking->total_days }} {{ \Illuminate\Support\Str::plural('day', $booking->total_days) }}</p>
            <p class="mt-3 text-3xl font-extrabold tabular-nums text-sun">&#8369;{{ number_format($booking->total_price, 2) }}</p>
        </section>
    </div>

    <section class="mt-6 rounded-2xl border border-cream/10 bg-graphite/60 p-6">
        <h2 class="text-[11px] font-semibold uppercase tracking-widest text-cream/50">Payment</h2>
        @if ($booking->payment)
            <div class="mt-3 flex flex-wrap items-center gap-x-8 gap-y-3">
                <x-badge :value="$booking->payment->payment_status" />
                <span class="font-bold tabular-nums text-sun">&#8369;{{ number_format($booking->payment->amount, 2) }}</span>
                <span class="text-sm text-cream/60">Method: {{ $booking->payment->payment_method ?? '—' }}</span>
                <span class="text-sm text-cream/60">Paid: {{ $booking->payment->paid_at?->format('M j, Y g:i A') ?? '—' }}</span>
            </div>
        @else
            <p class="mt-3 text-sm text-cream/55">No payment record.</p>
        @endif
    </section>
</x-admin-layout>
