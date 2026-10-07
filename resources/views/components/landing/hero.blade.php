<section data-hero class="hero grain relative isolate flex min-h-[100svh] flex-col overflow-hidden bg-midnight pb-20 pt-24 sm:pt-28 lg:pb-0">
    {{-- Atmosphere: warm glow behind the car, cool glow top-left --}}
    <div class="pointer-events-none absolute inset-0 -z-10" aria-hidden="true">
        <div class="absolute -right-[10%] top-[18%] h-[70%] w-[70%] rounded-full bg-tangerine/20 blur-[120px]"></div>
        <div class="absolute -left-[10%] -top-[10%] h-[45%] w-[45%] rounded-full bg-electric/10 blur-[120px]"></div>
    </div>

    {{-- Speed lines streaming past the car --}}
    <div class="pointer-events-none absolute inset-0 -z-10 overflow-hidden" aria-hidden="true">
        @foreach ([
            ['top' => '30%', 'w' => '22rem', 'delay' => '0s', 'dur' => '3.4s', 'c' => 'via-cream/50'],
            ['top' => '40%', 'w' => '14rem', 'delay' => '1.1s', 'dur' => '2.8s', 'c' => 'via-sun/60'],
            ['top' => '50%', 'w' => '26rem', 'delay' => '.5s', 'dur' => '3.8s', 'c' => 'via-cream/40'],
            ['top' => '58%', 'w' => '16rem', 'delay' => '2s', 'dur' => '3s', 'c' => 'via-electric/60'],
            ['top' => '67%', 'w' => '20rem', 'delay' => '1.6s', 'dur' => '3.6s', 'c' => 'via-cream/40'],
        ] as $l)
            <span class="speed-line absolute left-full h-px bg-gradient-to-r from-transparent {{ $l['c'] }} to-transparent"
                  style="top: {{ $l['top'] }}; width: {{ $l['w'] }}; animation-delay: {{ $l['delay'] }}; animation-duration: {{ $l['dur'] }}"></span>
        @endforeach
    </div>

    {{-- Road --}}
    <div class="pointer-events-none absolute inset-x-0 bottom-0 -z-10 h-20 border-t border-cream/10 bg-gradient-to-b from-graphite to-midnight sm:h-24" aria-hidden="true">
        <div class="absolute left-0 top-1/2 h-[3px] w-[calc(100%+120px)] -translate-y-1/2 opacity-70">
            <div class="road-dashes h-full w-full"></div>
        </div>
        <div class="absolute inset-x-0 top-2 h-px bg-tangerine/40"></div>
    </div>

    {{-- Route indicator (desktop) --}}
    <div class="pointer-events-none absolute right-[6%] top-28 hidden items-center gap-3 text-[11px] font-semibold uppercase tracking-[0.2em] text-cream/60 lg:flex" aria-hidden="true">
        <span class="h-2 w-2 rounded-full bg-electric"></span>
        <span>MNL</span>
        <span class="h-px w-16 border-t border-dashed border-cream/40"></span>
        <span class="h-2 w-2 rounded-full bg-sun"></span>
        <span>Baguio · 250 km</span>
    </div>

    {{-- Floating dots --}}
    <span class="dot absolute left-[48%] top-[22%] hidden h-1.5 w-1.5 rounded-full bg-sun lg:block" style="animation-delay: 0s" aria-hidden="true"></span>
    <span class="dot absolute left-[60%] top-[30%] hidden h-1.5 w-1.5 rounded-full bg-electric lg:block" style="animation-delay: .8s" aria-hidden="true"></span>
    <span class="dot absolute right-[8%] top-[48%] h-1.5 w-1.5 rounded-full bg-tangerine" style="animation-delay: 1.5s" aria-hidden="true"></span>
    <span class="dot absolute right-[30%] top-[16%] h-1 w-1 rounded-full bg-cream" style="animation-delay: 2.2s" aria-hidden="true"></span>

    {{-- Copy --}}
    <div class="relative z-20 mx-auto w-full max-w-7xl flex-1 px-4 sm:px-6 lg:flex lg:items-center lg:px-8 lg:pb-28">
        <div class="max-w-3xl">
            <p class="mb-6 flex items-center gap-3 text-xs font-semibold uppercase tracking-[0.3em] text-sun">
                <span class="h-px w-10 bg-sun"></span> Car rental &amp; adventure
            </p>

            <h1 class="text-[11.5vw] font-extrabold uppercase leading-[0.88] tracking-tight text-cream sm:text-[9vw] lg:text-[clamp(3.5rem,6vw,5.75rem)]">
                Your next<br>
                <span class="bg-gradient-to-r from-tangerine via-tangerine to-sun bg-clip-text text-transparent">Adventure</span><br>
                starts here.
            </h1>

            <p class="mt-6 text-lg text-cream/75 sm:text-xl">Pick a ride. Make it yours.</p>

            <div class="mt-9 flex flex-wrap items-center gap-4">
                <a href="{{ route('cars.index') }}"
                   class="group inline-flex items-center gap-2 rounded-full bg-tangerine px-7 py-3.5 font-bold text-midnight shadow-[0_10px_40px_-10px_rgba(255,107,53,.8)] transition hover:bg-sun focus:outline-none focus-visible:ring-2 focus-visible:ring-electric focus-visible:ring-offset-2 focus-visible:ring-offset-midnight">
                    Explore Cars
                    <span class="transition-transform group-hover:translate-x-1" aria-hidden="true">&rarr;</span>
                </a>
                <a href="#how-it-works"
                   class="inline-flex items-center rounded-full border border-cream/30 px-7 py-3.5 font-semibold text-cream transition hover:border-electric hover:text-electric focus:outline-none focus-visible:ring-2 focus-visible:ring-electric">
                    How It Works
                </a>
            </div>
        </div>
    </div>

    {{-- Car: bleeds off the right edge, overlapping the road --}}
    <div class="car-in relative z-10 -mb-3 ml-auto mt-10 w-[125%] max-w-none translate-x-[8%] sm:w-[105%] lg:absolute lg:bottom-4 lg:right-[-6vw] lg:mt-0 lg:w-[62vw] lg:max-w-[1100px] lg:translate-x-0">
        <div class="parallax"><x-landing.hero-car :spin="true" class="w-full" /></div>

        {{-- Spec badges --}}
        <div class="badge-float absolute left-[36%] top-[-2%] flex items-center gap-2 rounded-full border border-cream/15 bg-midnight/60 px-3 py-1.5 text-[11px] font-semibold text-cream backdrop-blur sm:text-xs">
            <span class="h-1.5 w-1.5 rounded-full bg-electric"></span> 3.0L Twin-Turbo
        </div>
        <div class="badge-float absolute right-[18%] top-[4%] hidden items-center gap-2 rounded-full border border-cream/15 bg-midnight/60 px-3 py-1.5 text-xs font-semibold text-cream backdrop-blur sm:flex" style="animation-delay: -2s">
            <span class="h-1.5 w-1.5 rounded-full bg-sun"></span> 0–100 in 3.9s
        </div>
        <div class="badge-float absolute left-[2%] top-[40%] hidden items-center gap-2 rounded-full border border-cream/15 bg-midnight/60 px-3 py-1.5 text-xs font-semibold text-cream backdrop-blur md:flex" style="animation-delay: -4s">
            <span class="h-1.5 w-1.5 rounded-full bg-tangerine"></span> Automatic · 4 seats
        </div>
    </div>
    {{-- Scroll cue: points at the road that starts below the hero --}}
    <a href="#destinations" class="scroll-cue absolute bottom-4 left-1/2 z-20 hidden -translate-x-1/2 items-center gap-3 text-[11px] font-semibold uppercase tracking-[0.3em] text-cream/55 transition hover:text-sun lg:flex" aria-label="Scroll to start the trip">
        <span class="flex h-9 w-5 items-start justify-center rounded-full border border-cream/30 pt-1.5"><span class="block h-2 w-0.5 rounded-full bg-sun"></span></span>
        Start the trip
    </a>
</section>