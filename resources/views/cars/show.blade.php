<x-public-layout :title="$car->brand.' '.$car->model">
    <section class="relative isolate overflow-hidden">
        <div class="pointer-events-none absolute inset-0 -z-10" aria-hidden="true">
            <div class="absolute -right-[10%] top-[5%] h-[30rem] w-[30rem] rounded-full bg-tangerine/15 blur-[130px]"></div>
            <div class="absolute -left-[10%] top-[40%] h-[26rem] w-[26rem] rounded-full bg-electric/10 blur-[130px]"></div>
        </div>

        <div class="mx-auto max-w-7xl px-4 pb-24 pt-28 sm:px-6 sm:pt-32 lg:px-8">
            <a href="{{ route('cars.index') }}" class="inline-flex items-center gap-2 text-sm font-semibold text-cream/60 transition hover:text-sun">&larr; All cars</a>

            @if (session('status'))
                <div role="status" class="mt-6 flex items-start gap-3 rounded-2xl border border-electric/40 bg-electric/10 px-5 py-4 text-sm text-cream">
                    <span class="mt-1 h-2 w-2 shrink-0 rounded-full bg-electric"></span>
                    <p>{{ session('status') }}</p>
                </div>
            @endif

            <div class="mt-8 grid gap-10 lg:grid-cols-12 lg:gap-12">
                {{-- Vehicle --}}
                <div class="lg:col-span-7">
                    <div class="relative aspect-[16/10] overflow-hidden rounded-[2rem] border border-cream/10 bg-graphite">
                        @if ($car->image_url)
                            <img src="{{ $car->image_url }}" alt="{{ $car->brand }} {{ $car->model }}" class="h-full w-full object-cover">
                        @endif
                        <div class="absolute inset-0 bg-gradient-to-t from-midnight/70 via-transparent to-transparent"></div>

                        <span class="absolute left-5 top-5 rounded-full bg-midnight/80 px-3 py-1 text-xs font-bold uppercase tracking-widest text-sun backdrop-blur">{{ $car->category }}</span>
                        <span class="absolute right-5 top-5 inline-flex items-center gap-2 rounded-full bg-midnight/80 px-3 py-1 text-xs font-semibold uppercase tracking-widest text-cream backdrop-blur">
                            <span class="h-2 w-2 rounded-full {{ $car->isAvailable() ? 'bg-electric' : 'bg-tangerine' }}"></span>
                            {{ ucfirst($car->status->value) }}
                        </span>
                    </div>

                    <p class="mt-10 text-xs font-semibold uppercase tracking-[0.3em] text-sun">{{ $car->brand }} &middot; {{ $car->year }}</p>
                    <h1 class="mt-3 text-[clamp(2.5rem,6vw,4.5rem)] font-extrabold leading-[0.95] tracking-tight text-cream">{{ $car->model }}</h1>

                    @if ($car->description)
                        <p class="mt-6 max-w-2xl text-lg leading-relaxed text-cream/70">{{ $car->description }}</p>
                    @endif

                    <dl class="mt-10 grid grid-cols-2 gap-3 sm:grid-cols-3">
                        @foreach ([
                            ['Brand', $car->brand],
                            ['Model', $car->model],
                            ['Year', $car->year],
                            ['Category', $car->category],
                            ['Seats', $car->seats],
                            ['Transmission', $car->transmission],
                            ['Fuel type', $car->fuel_type],
                            ['Price per day', '₱'.number_format($car->price_per_day)],
                            ['Availability', ucfirst($car->status->value)],
                        ] as [$label, $value])
                            <div class="rounded-2xl border border-cream/10 bg-graphite/50 px-4 py-3">
                                <dt class="text-[10px] font-semibold uppercase tracking-widest text-cream/50">{{ $label }}</dt>
                                <dd class="mt-1 font-bold text-cream">{{ $value }}</dd>
                            </div>
                        @endforeach
                    </dl>
                </div>

                {{-- Booking panel --}}
                <aside class="lg:col-span-5">
                    <div class="rounded-[2rem] border border-cream/10 bg-graphite p-6 shadow-[0_30px_80px_-30px_rgba(0,0,0,.8)] sm:p-8 lg:sticky lg:top-24"
                         x-data="{
                            price: {{ (float) $car->price_per_day }},
                            pickup: @js(old('pickup_date', '')),
                            ret: @js(old('return_date', '')),
                            today: @js(today()->toDateString()),
                            get days() {
                                if (!this.pickup || !this.ret) return 0;
                                const d = Math.round((new Date(this.ret) - new Date(this.pickup)) / 86400000);
                                return d < 0 ? -1 : Math.max(1, d);
                            },
                            get total() { return this.days > 0 ? this.days * this.price : 0; },
                            money(n) { return '₱' + n.toLocaleString('en-PH', { maximumFractionDigits: 2 }); },
                            syncReturn() { if (this.ret && this.ret < this.pickup) this.ret = ''; },
                         }">

                        <p class="flex items-baseline gap-2">
                            <span class="text-4xl font-extrabold text-sun">&#8369;{{ number_format($car->price_per_day) }}</span>
                            <span class="text-cream/55">/day</span>
                        </p>

                        @if (! $car->isAvailable())
                            <div class="mt-6 rounded-2xl border border-tangerine/40 bg-tangerine/10 p-5 text-sm text-cream">
                                <p class="font-bold text-tangerine">Currently unavailable</p>
                                <p class="mt-1 text-cream/70">This car is {{ $car->status === \App\Enums\CarStatus::Rented ? 'currently rented' : 'under maintenance' }} and can&rsquo;t be booked right now.</p>
                                <a href="{{ route('cars.index') }}" class="mt-4 inline-block font-semibold text-sun hover:underline">Browse available cars &rarr;</a>
                            </div>
                        @else
                            <form method="POST" action="{{ route('bookings.store', $car) }}" class="mt-6 space-y-5" novalidate>
                                @csrf

                                @error('car')
                                    <p class="rounded-xl border border-tangerine/40 bg-tangerine/10 px-4 py-3 text-sm text-tangerine">{{ $message }}</p>
                                @enderror

                                <div class="grid gap-4 sm:grid-cols-2">
                                    <div>
                                        <label for="pickup_date" class="block text-[11px] font-semibold uppercase tracking-widest text-cream/60">Pickup date</label>
                                        <input id="pickup_date" name="pickup_date" type="date" required
                                               x-model="pickup" x-on:change="syncReturn()" :min="today"
                                               class="mt-2 w-full rounded-xl border-cream/20 bg-midnight text-cream [color-scheme:dark] focus:border-tangerine focus:ring-tangerine">
                                        @error('pickup_date')<p class="mt-2 text-sm text-tangerine">{{ $message }}</p>@enderror
                                    </div>
                                    <div>
                                        <label for="return_date" class="block text-[11px] font-semibold uppercase tracking-widest text-cream/60">Return date</label>
                                        <input id="return_date" name="return_date" type="date" required
                                               x-model="ret" :min="pickup || today"
                                               class="mt-2 w-full rounded-xl border-cream/20 bg-midnight text-cream [color-scheme:dark] focus:border-tangerine focus:ring-tangerine">
                                        @error('return_date')<p class="mt-2 text-sm text-tangerine">{{ $message }}</p>@enderror
                                    </div>
                                </div>

                                <p x-show="days === -1" x-cloak class="text-sm text-tangerine">The return date cannot be earlier than the pickup date.</p>

                                {{-- Live quote --}}
                                <dl class="space-y-3 rounded-2xl border border-dashed border-cream/20 p-5 text-sm">
                                    <div class="flex justify-between text-cream/70">
                                        <dt>Rate</dt>
                                        <dd x-text="money(price) + ' / day'">&#8369;{{ number_format($car->price_per_day) }} / day</dd>
                                    </div>
                                    <div class="flex justify-between text-cream/70">
                                        <dt>Rental days</dt>
                                        <dd class="font-semibold text-cream" x-text="days > 0 ? days : '&mdash;'">&mdash;</dd>
                                    </div>
                                    <div class="flex items-end justify-between border-t border-cream/10 pt-3">
                                        <dt class="font-semibold text-cream">Total</dt>
                                        <dd class="text-3xl font-extrabold tabular-nums text-sun" x-text="days > 0 ? money(total) : '&mdash;'">&mdash;</dd>
                                    </div>
                                </dl>

                                <button type="submit" :disabled="days < 1"
                                        class="group inline-flex w-full items-center justify-center gap-2 rounded-full bg-tangerine px-7 py-4 font-bold text-midnight shadow-[0_10px_40px_-10px_rgba(255,107,53,.8)] transition hover:bg-sun focus:outline-none focus-visible:ring-2 focus-visible:ring-electric disabled:cursor-not-allowed disabled:opacity-40 disabled:shadow-none disabled:hover:bg-tangerine">
                                    Reserve This Ride <span class="transition-transform group-hover:translate-x-1" aria-hidden="true">&rarr;</span>
                                </button>

                                @guest
                                    <p class="text-center text-xs text-cream/50">You&rsquo;ll be asked to <a href="{{ route('login') }}" class="font-semibold text-sun hover:underline">log in</a> or sign up to reserve.</p>
                                @endguest
                            </form>

                            @if ($reserved->isNotEmpty())
                                <div class="mt-6 border-t border-cream/10 pt-5">
                                    <p class="text-[11px] font-semibold uppercase tracking-widest text-cream/50">Already reserved</p>
                                    <ul class="mt-3 space-y-1.5 text-sm text-cream/70">
                                        @foreach ($reserved as $b)
                                            <li>{{ $b->pickup_date->format('M j') }} &ndash; {{ $b->return_date->format('M j, Y') }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif
                        @endif
                    </div>
                </aside>
            </div>
        </div>
    </section>
</x-public-layout>
