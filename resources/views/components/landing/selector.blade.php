@props(['showcase'])

@php
    // Presentation only: accent colour per category. Car data itself comes from the database.
    $accents = ['Sport' => '#FF6B35', 'SUV' => '#2EC4FF', 'Sedan' => '#FFC857', 'Luxury' => '#F4F1DE', 'Adventure' => '#FF9F45'];
    $items = $showcase->map(fn ($c) => $c + ['accent' => $accents[$c['category']] ?? '#FF6B35'])->values();
@endphp

@if ($items->isNotEmpty())
<section class="relative px-4 py-24 pl-14 sm:px-6 sm:pl-20 lg:px-8 lg:py-32 lg:pl-28"
    x-data="{
        cars: @js($items),
        active: 0,
        shown: @js($items[0]['price']),
        raf: null,
        select(i) {
            if (i === this.active) return;
            this.active = i;
            this.tween(this.cars[i].price);
        },
        move(step) {
            this.select((this.active + step + this.cars.length) % this.cars.length);
            this.$nextTick(() => this.$refs.tabs.children[this.active]?.focus());
        },
        tween(to) {
            cancelAnimationFrame(this.raf);
            if (window.matchMedia('(prefers-reduced-motion: reduce)').matches) { this.shown = to; return; }
            const from = this.shown, start = performance.now(), duration = 650;
            const step = (now) => {
                const k = Math.min(1, (now - start) / duration);
                this.shown = Math.round(from + (to - from) * (1 - Math.pow(1 - k, 3)));
                if (k < 1) this.raf = requestAnimationFrame(step);
            };
            this.raf = requestAnimationFrame(step);
        },
    }">
    <x-landing.waypoint n="03" />

    <x-landing.section-heading step="03" label="Meet the lineup" accent="text-electric">
        Not all rides are <span class="text-tangerine">created equal.</span>
    </x-landing.section-heading>

    <div data-reveal class="mt-14 grid items-center gap-10 lg:grid-cols-12 lg:gap-6">
        {{-- Image stage: bleeds toward the right edge on large screens --}}
        <div class="relative order-first min-w-0 lg:order-last lg:col-span-7 lg:-mr-8">
            <div class="grid grid-cols-1">
                @foreach ($items as $i => $car)
                    <figure x-show="active === {{ $i }}"
                            x-transition:enter="transition duration-700 ease-out"
                            x-transition:enter-start="opacity-0 translate-x-12 scale-95"
                            x-transition:enter-end="opacity-100 translate-x-0 scale-100"
                            x-transition:leave="transition duration-300 ease-in"
                            x-transition:leave-start="opacity-100 translate-x-0"
                            x-transition:leave-end="opacity-0 -translate-x-8"
                            @if ($i > 0) x-cloak @endif
                            class="relative [grid-area:1/1]" style="--accent: {{ $car['accent'] }}">

                        <span class="pointer-events-none absolute top-0 right-2 select-none text-[clamp(3rem,10vw,7rem)] font-extrabold uppercase leading-none tracking-tight text-transparent [-webkit-text-stroke:1.5px_rgb(244_241_222/.14)]" aria-hidden="true">{{ $car['category'] }}</span>
                        <div class="pointer-events-none absolute inset-x-[10%] bottom-0 h-3/4 rounded-full opacity-30 blur-[90px]" style="background: var(--accent)" aria-hidden="true"></div>

                        <div class="relative mt-14 drop-shadow-[0_30px_40px_rgba(0,0,0,.55)]">
                            <div class="group aspect-[16/10] overflow-hidden bg-graphite [clip-path:polygon(9%_0,100%_0,100%_100%,0_100%)]">
                                <x-cars.media :car="(object) ['image_url' => $car['image'], 'brand' => $car['brand'], 'model' => $car['model'], 'category' => $car['category']]" />
                                <div class="absolute inset-0 bg-gradient-to-tr from-midnight/50 via-transparent to-transparent"></div>
                            </div>
                            <span class="absolute -bottom-2 left-[4%] h-1 w-1/3 rounded-full" style="background: var(--accent)"></span>
                        </div>
                    </figure>
                @endforeach
            </div>
        </div>

        {{-- Controls + details --}}
        <div class="min-w-0 lg:col-span-5">
            <div x-ref="tabs" role="tablist" aria-label="Car category"
                 x-on:keydown.right.prevent="move(1)" x-on:keydown.left.prevent="move(-1)"
                 x-on:keydown.down.prevent="move(1)" x-on:keydown.up.prevent="move(-1)"
                 class="-mx-4 flex gap-2 overflow-x-auto px-4 pb-2 sm:mx-0 sm:flex-wrap sm:overflow-visible sm:px-0">
                @foreach ($items as $i => $car)
                    <button type="button" role="tab" x-on:click="select({{ $i }})"
                            :aria-selected="active === {{ $i }}" :tabindex="active === {{ $i }} ? 0 : -1"
                            :style="active === {{ $i }} ? 'background: {{ $car['accent'] }}; border-color: {{ $car['accent'] }}; color: #0B1020' : ''"
                            class="shrink-0 rounded-full border border-cream/20 px-5 py-2 text-sm font-bold uppercase tracking-widest text-cream/70 transition hover:border-cream/60 hover:text-cream focus:outline-none focus-visible:ring-2 focus-visible:ring-electric">
                        {{ $car['category'] }}
                    </button>
                @endforeach
            </div>

            <div class="mt-8 grid grid-cols-1" aria-live="polite">
                @foreach ($items as $i => $car)
                    <div x-show="active === {{ $i }}"
                         x-transition:enter="transition duration-500 delay-150 ease-out"
                         x-transition:enter-start="opacity-0 translate-y-4"
                         x-transition:enter-end="opacity-100 translate-y-0"
                         x-transition:leave="transition duration-200 ease-in"
                         x-transition:leave-start="opacity-100"
                         x-transition:leave-end="opacity-0"
                         @if ($i > 0) x-cloak @endif
                         class="[grid-area:1/1]">
                        <p class="text-xs font-semibold uppercase tracking-[0.3em]" style="color: {{ $car['accent'] }}">{{ $car['category'] }} &middot; {{ $car['year'] }}</p>
                        <h3 class="mt-3 text-3xl font-extrabold leading-tight tracking-tight text-cream [text-wrap:balance] sm:text-4xl xl:text-5xl">{{ $car['name'] }}</h3>

                        <dl class="mt-8 grid grid-cols-3 gap-3">
                            @foreach ([['Seats', $car['seats']], ['Transmission', $car['transmission']], ['Fuel', $car['fuel']]] as [$label, $value])
                                <div class="rounded-2xl border border-cream/10 bg-graphite/50 px-4 py-3">
                                    <dt class="text-[10px] font-semibold uppercase tracking-widest text-cream/50">{{ $label }}</dt>
                                    <dd class="mt-1 text-base font-bold text-cream">{{ $value }}</dd>
                                </div>
                            @endforeach
                        </dl>
                    </div>
                @endforeach
            </div>

            <div class="mt-8 flex flex-wrap items-end justify-between gap-6">
                <p>
                    <span class="block text-[10px] font-semibold uppercase tracking-widest text-cream/50">Price per day</span>
                    <span class="text-5xl font-extrabold tabular-nums text-sun">&#8369;<span x-text="shown.toLocaleString('en-PH')">{{ number_format($items[0]['price']) }}</span></span>
                </p>
                <a :href="'{{ url('/cars') }}/' + cars[active].id"
                   href="{{ route('cars.show', $items[0]['id']) }}"
                   class="group inline-flex items-center gap-2 rounded-full bg-tangerine px-7 py-3.5 font-bold text-midnight shadow-[0_10px_40px_-10px_rgba(255,107,53,.8)] transition hover:bg-sun focus:outline-none focus-visible:ring-2 focus-visible:ring-electric">
                    Rent This Ride <span class="transition-transform group-hover:translate-x-1" aria-hidden="true">&rarr;</span>
                </a>
            </div>
        </div>
    </div>
</section>
@endif
