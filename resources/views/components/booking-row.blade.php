@props(['booking'])

{{-- One past/present booking as a compact "ticket" row. --}}
<a href="{{ route('bookings.show', $booking) }}"
   class="group flex items-center gap-4 rounded-2xl border border-cream/10 bg-graphite/60 p-3 pr-5 transition duration-300 hover:-translate-y-0.5 hover:border-tangerine/50 hover:shadow-[0_20px_50px_-25px_rgba(255,107,53,.5)] focus:outline-none focus-visible:ring-2 focus-visible:ring-electric sm:gap-5">
    <div class="h-16 w-20 shrink-0 overflow-hidden rounded-xl bg-midnight sm:h-20 sm:w-28">
        <x-cars.media :car="$booking->car" img-class="h-full w-full object-cover transition duration-500 group-hover:scale-110" />
    </div>

    <div class="min-w-0 flex-1">
        <p class="truncate text-xs font-semibold uppercase tracking-widest text-cream/50">{{ $booking->car->brand }}</p>
        <p class="truncate text-lg font-extrabold text-cream">{{ $booking->car->model }}</p>
        <p class="mt-0.5 text-sm text-cream/60">
            {{ $booking->pickup_date->format('M j') }} &rarr; {{ $booking->return_date->format('M j, Y') }}
            <span class="text-cream/40">&middot; {{ $booking->total_days }} {{ \Illuminate\Support\Str::plural('day', $booking->total_days) }}</span>
        </p>
    </div>

    <div class="flex flex-col items-end gap-2 text-right">
        <x-booking-status :status="$booking->status" />
        <p class="font-extrabold tabular-nums text-sun">&#8369;{{ number_format($booking->total_price) }}</p>
    </div>
</a>
