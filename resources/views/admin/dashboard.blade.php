<x-admin-layout title="Dashboard">
    <x-slot:actions>
        <a href="{{ route('admin.cars.create') }}" class="rounded-full bg-tangerine px-5 py-2.5 text-sm font-bold text-midnight transition hover:bg-sun">+ Add car</a>
    </x-slot:actions>

    <dl class="grid grid-cols-2 gap-4 lg:grid-cols-5">
        @foreach ([
            ['Total cars', number_format($stats['total_cars']), route('admin.cars.index'), 'text-cream'],
            ['Available cars', number_format($stats['available_cars']), route('admin.cars.index', ['status' => 'available']), 'text-electric'],
            ['Active rentals', number_format($stats['active_rentals']), route('admin.bookings.index', ['status' => 'confirmed']), 'text-cream'],
            ['Pending bookings', number_format($stats['pending_bookings']), route('admin.bookings.index', ['status' => 'pending']), 'text-sun'],
            ['Total revenue', '₱'.number_format($stats['revenue'], 2), route('admin.payments.index', ['status' => 'paid']), 'text-sun'],
        ] as [$label, $value, $href, $color])
            <a href="{{ $href }}" class="block rounded-2xl border border-cream/10 bg-graphite/60 p-5 transition hover:border-tangerine/50 focus:outline-none focus-visible:ring-2 focus-visible:ring-electric {{ $loop->last ? 'col-span-2 lg:col-span-1' : '' }}">
                <dt class="text-[11px] font-semibold uppercase tracking-widest text-cream/50">{{ $label }}</dt>
                <dd class="mt-2 text-3xl font-extrabold tabular-nums {{ $color }}">{{ $value }}</dd>
            </a>
        @endforeach
    </dl>

    <p class="mt-3 text-xs text-cream/45">Active rentals are confirmed bookings whose dates include today. Revenue counts paid payments only.</p>

    <div class="mt-10 grid grid-cols-1 gap-8 xl:grid-cols-2">
        <section class="min-w-0">
            <div class="flex items-center justify-between">
                <h2 class="text-sm font-bold uppercase tracking-widest text-cream/60">Needs confirmation</h2>
                <a href="{{ route('admin.bookings.index', ['status' => 'pending']) }}" class="text-sm font-semibold text-sun hover:underline">All pending &rarr;</a>
            </div>
            <div class="mt-4 divide-y divide-cream/10 rounded-2xl border border-cream/10 bg-graphite/60">
                @forelse ($pending as $booking)
                    <div class="flex flex-wrap items-center justify-between gap-3 p-4">
                        <a href="{{ route('admin.bookings.show', $booking) }}" class="min-w-0 hover:text-sun">
                            <p class="truncate font-bold">#{{ $booking->id }} &middot; {{ $booking->car->brand }} {{ $booking->car->model }}</p>
                            <p class="truncate text-sm text-cream/60">{{ $booking->user->name }} &middot; {{ $booking->pickup_date->format('M j') }} &rarr; {{ $booking->return_date->format('M j') }}</p>
                        </a>
                        <div class="flex gap-2">
                            @foreach (['confirm' => 'bg-electric text-midnight', 'cancel' => 'border border-cream/25 text-cream/80 hover:border-tangerine hover:text-tangerine'] as $action => $style)
                                <form method="POST" action="{{ route('admin.bookings.transition', [$booking, $action]) }}">
                                    @csrf
                                    <button class="rounded-full px-4 py-1.5 text-xs font-bold capitalize {{ $style }}">{{ $action }}</button>
                                </form>
                            @endforeach
                        </div>
                    </div>
                @empty
                    <p class="p-6 text-sm text-cream/55">Nothing waiting. All caught up.</p>
                @endforelse
            </div>
        </section>

        <section class="min-w-0">
            <div class="flex items-center justify-between">
                <h2 class="text-sm font-bold uppercase tracking-widest text-cream/60">Recent bookings</h2>
                <a href="{{ route('admin.bookings.index') }}" class="text-sm font-semibold text-sun hover:underline">All bookings &rarr;</a>
            </div>
            <div class="mt-4 divide-y divide-cream/10 rounded-2xl border border-cream/10 bg-graphite/60">
                @forelse ($recent as $booking)
                    <a href="{{ route('admin.bookings.show', $booking) }}" class="flex items-center justify-between gap-3 p-4 hover:bg-cream/5">
                        <div class="min-w-0">
                            <p class="truncate font-bold">#{{ $booking->id }} &middot; {{ $booking->car->brand }} {{ $booking->car->model }}</p>
                            <p class="truncate text-sm text-cream/60">{{ $booking->user->name }}</p>
                        </div>
                        <div class="flex shrink-0 flex-col items-end gap-1">
                            <x-badge :value="$booking->status" />
                            <span class="text-sm font-bold tabular-nums text-sun">&#8369;{{ number_format($booking->total_price) }}</span>
                        </div>
                    </a>
                @empty
                    <p class="p-6 text-sm text-cream/55">No bookings yet.</p>
                @endforelse
            </div>
        </section>
    </div>
</x-admin-layout>
