<x-public-layout title="Booking confirmation">
    <section class="relative isolate overflow-hidden">
        <div class="pointer-events-none absolute inset-0 -z-10" aria-hidden="true">
            <div class="absolute left-1/2 top-[8%] h-[28rem] w-[40rem] -translate-x-1/2 rounded-full bg-tangerine/15 blur-[130px]"></div>
            <div class="absolute -right-[10%] top-[50%] h-[24rem] w-[24rem] rounded-full bg-electric/10 blur-[130px]"></div>
        </div>

        <div class="mx-auto max-w-4xl px-4 pb-24 pt-32 sm:px-6 sm:pt-40 lg:px-8">
            <div class="flex items-center gap-3 text-xs font-semibold uppercase tracking-[0.3em] text-electric">
                <span class="flex h-8 w-8 items-center justify-center rounded-full border border-electric text-base" aria-hidden="true">&check;</span>
                Reservation received
            </div>
            <h1 class="mt-5 text-[clamp(2.5rem,7vw,5rem)] font-extrabold uppercase leading-[0.95] tracking-tight text-cream">
                You&rsquo;re all set, <span class="text-tangerine">{{ \Illuminate\Support\Str::of(auth()->user()->name)->before(' ') }}.</span>
            </h1>
            <p class="mt-5 max-w-xl text-lg text-cream/70">
                Your booking is <strong class="text-sun">{{ $booking->status->value }}</strong> while we confirm it. Keep your reference number handy.
            </p>

            <div class="mt-12 overflow-hidden rounded-[2rem] border border-cream/10 bg-graphite shadow-[0_30px_80px_-30px_rgba(0,0,0,.8)]">
                <div class="grid sm:grid-cols-5">
                    <div class="relative min-h-48 bg-midnight sm:col-span-2">
                        <div class="absolute inset-0"><x-cars.media :car="$booking->car" /></div>
                        <div class="absolute inset-0 bg-gradient-to-t from-graphite/80 to-transparent sm:bg-gradient-to-r sm:from-transparent sm:to-graphite/60"></div>
                    </div>

                    <div class="p-6 sm:col-span-3 sm:p-8">
                        <div class="flex items-start justify-between gap-4">
                            <div>
                                <p class="text-xs font-semibold uppercase tracking-widest text-cream/50">{{ $booking->car->brand }} &middot; {{ $booking->car->category }}</p>
                                <h2 class="mt-1 text-2xl font-extrabold text-cream">{{ $booking->car->model }}</h2>
                            </div>
                            <p class="rounded-full border border-sun/40 px-3 py-1 text-sm font-bold text-sun">#{{ $booking->id }}</p>
                        </div>

                        <dl class="mt-6 grid grid-cols-2 gap-x-6 gap-y-5 border-t border-dashed border-cream/15 pt-6">
                            @foreach ([
                                ['Pickup', $booking->pickup_date->format('D, M j, Y')],
                                ['Return', $booking->return_date->format('D, M j, Y')],
                                ['Rental days', $booking->total_days],
                                ['Rate', '₱'.number_format($booking->car->price_per_day).' / day'],
                                ['Booking status', ucfirst($booking->status->value)],
                                ['Payment', ucfirst($booking->payment?->payment_status->value ?? 'pending')],
                            ] as [$label, $value])
                                <div>
                                    <dt class="text-[10px] font-semibold uppercase tracking-widest text-cream/50">{{ $label }}</dt>
                                    <dd class="mt-1 font-bold text-cream">{{ $value }}</dd>
                                </div>
                            @endforeach
                        </dl>

                        <div class="mt-6 flex items-end justify-between border-t border-cream/10 pt-5">
                            <span class="font-semibold text-cream">Total</span>
                            <span class="text-4xl font-extrabold tabular-nums text-sun">&#8369;{{ number_format($booking->total_price, 2) }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="mt-10 flex flex-wrap gap-4">
                <a href="{{ route('cars.index') }}"
                   class="group inline-flex items-center gap-2 rounded-full bg-tangerine px-7 py-3.5 font-bold text-midnight shadow-[0_10px_40px_-10px_rgba(255,107,53,.8)] transition hover:bg-sun focus:outline-none focus-visible:ring-2 focus-visible:ring-electric">
                    Browse more cars <span class="transition-transform group-hover:translate-x-1" aria-hidden="true">&rarr;</span>
                </a>
                <a href="{{ route('home') }}" class="inline-flex items-center rounded-full border border-cream/30 px-7 py-3.5 font-semibold text-cream transition hover:border-electric hover:text-electric">Back to home</a>
            </div>
        </div>
    </section>
</x-public-layout>
