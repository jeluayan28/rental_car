<x-public-layout title="Dashboard">
    <section class="relative isolate overflow-hidden">
        <div class="pointer-events-none absolute inset-0 -z-10" aria-hidden="true">
            <div class="absolute -right-[10%] top-[2%] h-[30rem] w-[30rem] rounded-full bg-tangerine/15 blur-[130px]"></div>
            <div class="absolute -left-[10%] top-[45%] h-[26rem] w-[26rem] rounded-full bg-electric/10 blur-[130px]"></div>
        </div>

        <div class="mx-auto max-w-7xl px-4 pb-24 pt-32 sm:px-6 sm:pt-40 lg:px-8">
            {{-- Greeting --}}
            <p class="flex items-center gap-3 text-xs font-semibold uppercase tracking-[0.3em] text-sun">
                <span class="h-px w-10 bg-sun"></span> Your garage
            </p>
            <h1 class="mt-5 text-[clamp(2.5rem,7.5vw,6rem)] font-extrabold uppercase leading-[0.92] tracking-tight text-cream">
                Welcome back,<br><span class="text-tangerine">{{ \Illuminate\Support\Str::of(auth()->user()->name)->before(' ') }}.</span>
            </h1>
            <p class="mt-6 text-cream/65">
                @if ($trips > 0)
                    {{ $trips }} {{ \Illuminate\Support\Str::plural('trip', $trips) }} completed &middot; {{ $daysOnRoad }} {{ \Illuminate\Support\Str::plural('day', $daysOnRoad) }} on the road.
                @else
                    Your next adventure is one booking away.
                @endif
            </p>

            {{-- Quick actions --}}
            <nav aria-label="Quick actions" class="mt-12 grid gap-4 sm:grid-cols-3">
                @foreach ([
                    ['Browse Cars', 'Find your next ride', route('cars.index'), 'bg-tangerine text-midnight hover:bg-sun', true],
                    ['View Bookings', 'Every trip, past and upcoming', route('bookings.index'), 'border border-cream/20 text-cream hover:border-electric hover:text-electric', false],
                    ['Edit Profile', 'Your details and password', route('profile.edit'), 'border border-cream/20 text-cream hover:border-sun hover:text-sun', false],
                ] as [$label, $hint, $href, $classes, $primary])
                    <a href="{{ $href }}"
                       class="group flex items-center justify-between rounded-2xl px-6 py-5 transition duration-300 hover:-translate-y-1 focus:outline-none focus-visible:ring-2 focus-visible:ring-electric {{ $classes }} {{ $primary ? 'shadow-[0_12px_40px_-12px_rgba(255,107,53,.8)]' : 'bg-graphite/40' }}">
                        <span>
                            <span class="block text-lg font-extrabold">{{ $label }}</span>
                            <span class="mt-0.5 block text-sm {{ $primary ? 'text-midnight/70' : 'text-cream/55' }}">{{ $hint }}</span>
                        </span>
                        <span class="text-2xl transition-transform duration-300 group-hover:translate-x-1.5" aria-hidden="true">&rarr;</span>
                    </a>
                @endforeach
            </nav>

            {{-- Active booking --}}
            <div class="mt-16">
                <h2 class="text-xs font-semibold uppercase tracking-[0.3em] text-cream/50">Active booking</h2>

                @if ($active)
                    @php
                        $untilPickup = (int) today()->diffInDays($active->pickup_date, false);
                        $untilReturn = (int) today()->diffInDays($active->return_date, false);
                        $timing = $untilPickup > 0
                            ? 'Pickup in '.$untilPickup.' '.\Illuminate\Support\Str::plural('day', $untilPickup)
                            : ($untilPickup === 0 ? 'Pickup today' : ($untilReturn === 0 ? 'Return today' : 'On the road &middot; returns in '.$untilReturn.' '.\Illuminate\Support\Str::plural('day', $untilReturn)));
                    @endphp

                    <a href="{{ route('bookings.show', $active) }}"
                       class="group mt-5 grid overflow-hidden rounded-[2rem] border border-cream/10 bg-graphite shadow-[0_30px_80px_-30px_rgba(0,0,0,.8)] transition duration-300 hover:border-tangerine/50 hover:shadow-[0_30px_80px_-30px_rgba(255,107,53,.45)] focus:outline-none focus-visible:ring-2 focus-visible:ring-electric lg:grid-cols-5">
                        <div class="relative min-h-56 overflow-hidden bg-midnight lg:col-span-2">
                            @if ($active->car->image_url)
                                <img src="{{ $active->car->image_url }}" alt="{{ $active->car->brand }} {{ $active->car->model }}" class="absolute inset-0 h-full w-full object-cover transition duration-700 group-hover:scale-105">
                            @endif
                            <div class="absolute inset-0 bg-gradient-to-t from-graphite/80 to-transparent lg:bg-gradient-to-r lg:from-transparent lg:to-graphite/70"></div>
                            <span class="absolute left-5 top-5 rounded-full bg-midnight/80 px-3 py-1 text-[11px] font-bold uppercase tracking-widest text-sun backdrop-blur">{{ $active->car->category }}</span>
                        </div>

                        <div class="p-6 sm:p-8 lg:col-span-3">
                            <div class="flex flex-wrap items-start justify-between gap-3">
                                <div>
                                    <p class="text-xs font-semibold uppercase tracking-[0.25em] text-cream/50">{{ $active->car->brand }}</p>
                                    <h3 class="mt-1 text-3xl font-extrabold text-cream sm:text-4xl">{{ $active->car->model }}</h3>
                                </div>
                                <x-booking-status :status="$active->status" />
                            </div>

                            <p class="mt-4 inline-flex items-center gap-2 text-sm font-semibold text-electric">
                                <span class="h-1.5 w-1.5 animate-pulse rounded-full bg-electric"></span>{!! $timing !!}
                            </p>

                            {{-- Route-style date strip --}}
                            <div class="mt-6 flex items-center gap-4">
                                <div>
                                    <p class="text-[10px] font-semibold uppercase tracking-widest text-cream/50">Pickup</p>
                                    <p class="mt-1 text-lg font-bold text-cream">{{ $active->pickup_date->format('M j, Y') }}</p>
                                </div>
                                <div class="flex flex-1 items-center gap-1" aria-hidden="true">
                                    <span class="h-2.5 w-2.5 rounded-full border-2 border-electric"></span>
                                    <span class="h-px flex-1 border-t border-dashed border-cream/30"></span>
                                    <span class="h-2.5 w-2.5 rounded-full border-2 border-sun"></span>
                                </div>
                                <div class="text-right">
                                    <p class="text-[10px] font-semibold uppercase tracking-widest text-cream/50">Return</p>
                                    <p class="mt-1 text-lg font-bold text-cream">{{ $active->return_date->format('M j, Y') }}</p>
                                </div>
                            </div>

                            <div class="mt-7 flex items-end justify-between border-t border-dashed border-cream/15 pt-5">
                                <div>
                                    <p class="text-[10px] font-semibold uppercase tracking-widest text-cream/50">Total &middot; {{ $active->total_days }} {{ \Illuminate\Support\Str::plural('day', $active->total_days) }}</p>
                                    <p class="mt-1 text-4xl font-extrabold tabular-nums text-sun">&#8369;{{ number_format($active->total_price, 2) }}</p>
                                </div>
                                <span class="inline-flex items-center gap-2 text-sm font-bold text-cream transition group-hover:text-tangerine">Details <span class="transition-transform group-hover:translate-x-1" aria-hidden="true">&rarr;</span></span>
                            </div>
                        </div>
                    </a>
                @else
                    <div class="mt-5 rounded-[2rem] border border-dashed border-cream/20 p-10 text-center">
                        <p class="text-2xl font-extrabold text-cream">No active booking.</p>
                        <p class="mt-2 text-cream/60">The road&rsquo;s open. Pick a ride and set your dates.</p>
                        <a href="{{ route('cars.index') }}" class="group mt-6 inline-flex items-center gap-2 rounded-full bg-tangerine px-7 py-3.5 font-bold text-midnight transition hover:bg-sun">
                            Browse Cars <span class="transition-transform group-hover:translate-x-1" aria-hidden="true">&rarr;</span>
                        </a>
                    </div>
                @endif
            </div>

            {{-- History --}}
            <div class="mt-16">
                <div class="flex items-end justify-between">
                    <h2 class="text-xs font-semibold uppercase tracking-[0.3em] text-cream/50">Booking history</h2>
                    @if ($hasMoreHistory)
                        <a href="{{ route('bookings.index') }}" class="text-sm font-semibold text-sun hover:underline">View all &rarr;</a>
                    @endif
                </div>

                @if ($history->isEmpty())
                    <p class="mt-5 rounded-2xl border border-dashed border-cream/15 px-6 py-8 text-cream/55">Your past trips will show up here.</p>
                @else
                    <div class="mt-5 space-y-3">
                        @foreach ($history as $booking)
                            <x-booking-row :booking="$booking" />
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </section>
</x-public-layout>
