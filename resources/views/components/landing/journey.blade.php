@props(['cars', 'featuredIds' => [], 'showcase' => collect()])

{{-- The road trip: one road runs down the left edge through every stop. `--p` (0..1) is set by journey.js on scroll. --}}
<div data-journey
     x-data="{
        vibe: null,
        vibes: { city: ['Sedan', 'Sport'], adventure: ['SUV', 'Adventure'], getaway: ['Family', 'Luxury'] },
        show(category, featured) { return this.vibe ? this.vibes[this.vibe].includes(category) : featured; },
        pick(v) {
            this.vibe = this.vibe === v ? null : v;
            if (this.vibe) this.$nextTick(() => document.getElementById('rides')?.scrollIntoView({ behavior: 'smooth' }));
        },
     }"
     class="journey relative isolate overflow-hidden bg-midnight">

    {{-- Ambient glow --}}
    <div class="pointer-events-none absolute inset-0 -z-10" aria-hidden="true">
        <div class="absolute -right-[15%] top-[8%] h-[30rem] w-[30rem] rounded-full bg-electric/10 blur-[130px]"></div>
        <div class="absolute -left-[10%] top-[48%] h-[30rem] w-[30rem] rounded-full bg-tangerine/10 blur-[130px]"></div>
        <div class="absolute -right-[10%] bottom-[8%] h-[28rem] w-[28rem] rounded-full bg-sun/10 blur-[130px]"></div>
    </div>

    <div class="relative mx-auto max-w-7xl">
        {{-- The road --}}
        <div class="road-fade pointer-events-none absolute inset-y-0 left-6 w-[14px] -translate-x-1/2 sm:left-10 lg:left-12" aria-hidden="true">
            <div class="road-track absolute inset-0 rounded-full"></div>
            <div class="road-center absolute inset-y-0 left-1/2 w-[2px] -translate-x-1/2"></div>
            <div class="road-progress absolute inset-y-0 left-1/2 w-[2px] -translate-x-1/2"></div>
            <div class="road-car absolute left-1/2 z-10 h-6 w-3.5 -translate-x-1/2 -translate-y-1/2 rounded-[5px] bg-tangerine shadow-[0_0_18px_rgba(255,107,53,.9)]">
                <span class="absolute bottom-0.5 left-0.5 h-1 w-1 rounded-full bg-sun"></span>
                <span class="absolute bottom-0.5 right-0.5 h-1 w-1 rounded-full bg-sun"></span>
            </div>
        </div>

        <x-landing.destinations />
        <x-landing.vibes />
        <x-landing.rides :cars="$cars" :featured-ids="$featuredIds" />
        <x-landing.selector :showcase="$showcase" />

        {{-- Arrival --}}
        <section class="relative px-4 pb-28 pl-14 sm:px-6 sm:pl-20 lg:px-8 lg:pl-28">
            <x-landing.waypoint n="GO" top="top-0" />
            <div data-reveal class="flex flex-col items-start gap-5 pt-1">
                <p class="text-xs font-semibold uppercase tracking-[0.3em] text-electric">You&rsquo;ve arrived</p>
                <a href="{{ route('cars.index') }}"
                   class="group inline-flex items-center gap-2 rounded-full bg-tangerine px-7 py-3.5 font-bold text-midnight shadow-[0_10px_40px_-10px_rgba(255,107,53,.8)] transition hover:bg-sun focus:outline-none focus-visible:ring-2 focus-visible:ring-electric">
                    See the full fleet <span class="transition-transform group-hover:translate-x-1" aria-hidden="true">&rarr;</span>
                </a>
            </div>
        </section>
    </div>
</div>
