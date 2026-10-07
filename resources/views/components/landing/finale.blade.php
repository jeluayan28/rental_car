@props(['stats'])

{{-- The end of the road: stats, a call to action and the "about" blurb. --}}
<section id="about" class="relative px-4 pb-28 pl-14 sm:px-6 sm:pl-20 lg:px-8 lg:pl-28">
    <x-landing.waypoint n="GO" top="top-0" />

    {{-- The car has arrived: parked at the end of the road --}}
    <div class="pointer-events-none absolute right-[-8vw] top-[22%] hidden w-[46vw] max-w-[760px] lg:block" aria-hidden="true">
        <div data-reveal><x-landing.hero-car /></div>
    </div>

    <div class="relative z-10 pt-1">
        <p data-reveal class="text-xs font-semibold uppercase tracking-[0.3em] text-electric">You&rsquo;ve arrived</p>
        <h2 data-reveal style="--d:.08s" class="mt-5 max-w-4xl text-[clamp(3rem,9vw,7.5rem)] font-extrabold uppercase leading-[0.9] tracking-tight text-cream">
            Ready to <span class="bg-gradient-to-r from-tangerine to-sun bg-clip-text text-transparent">roll?</span>
        </h2>
        <p data-reveal style="--d:.16s" class="mt-6 max-w-xl text-lg text-cream/65">Your next adventure is a few clicks away. Pick a ride, set your dates and go.</p>

        <div data-reveal style="--d:.24s" class="mt-10 flex flex-wrap items-center gap-4">
            <a href="{{ route('cars.index') }}"
               class="group inline-flex items-center gap-2 rounded-full bg-tangerine px-8 py-4 text-lg font-bold text-midnight shadow-[0_14px_50px_-12px_rgba(255,107,53,.9)] transition hover:bg-sun focus:outline-none focus-visible:ring-2 focus-visible:ring-electric focus-visible:ring-offset-2 focus-visible:ring-offset-midnight">
                Explore Cars <span class="transition-transform group-hover:translate-x-1" aria-hidden="true">&rarr;</span>
            </a>
            @auth
                <a href="{{ route('dashboard') }}" class="inline-flex items-center rounded-full border border-cream/30 px-8 py-4 text-lg font-semibold text-cream transition hover:border-electric hover:text-electric">My dashboard</a>
            @else
                <a href="{{ route('register') }}" class="inline-flex items-center rounded-full border border-cream/30 px-8 py-4 text-lg font-semibold text-cream transition hover:border-electric hover:text-electric">Create an account</a>
            @endauth
        </div>

        {{-- Live numbers from the fleet --}}
        <dl data-reveal style="--d:.3s" class="mt-16 grid max-w-3xl grid-cols-3 gap-4 border-t border-dashed border-cream/20 pt-8">
            @foreach ([
                ['Rides ready now', $stats['cars'], '', ''],
                ['Categories', $stats['categories'], '', ''],
                ['Daily rates from', $stats['from'], '₱', ''],
            ] as [$label, $value, $prefix, $suffix])
                <div>
                    <dd class="text-2xl font-extrabold tabular-nums text-sun min-[420px]:text-3xl sm:text-5xl" data-count="{{ $value }}" data-prefix="{{ $prefix }}" data-suffix="{{ $suffix }}">{{ $prefix }}{{ number_format($value) }}{{ $suffix }}</dd>
                    <dt class="mt-2 text-[11px] font-semibold uppercase tracking-widest text-cream/50 sm:text-xs">{{ $label }}</dt>
                </div>
            @endforeach
        </dl>

        <div data-reveal class="mt-16 max-w-2xl rounded-3xl border border-cream/10 bg-graphite/50 p-7">
            <p class="text-xs font-semibold uppercase tracking-[0.3em] text-sun">About ROAMR</p>
            <p class="mt-3 leading-relaxed text-cream/70">ROAMR is a car rental and adventure platform for people who would rather be going somewhere. We keep the fleet simple, the prices honest and the booking quick, so the trip starts the moment you pick a car.</p>
        </div>
    </div>
</section>
