<section id="how-it-works" class="relative px-4 py-24 pl-14 sm:px-6 sm:pl-20 lg:px-8 lg:py-32 lg:pl-28">
    <x-landing.waypoint n="05" />

    <x-landing.section-heading step="05" label="How it works" accent="text-electric">
        Three steps. <span class="text-tangerine">Zero friction.</span>
        <x-slot:intro>From scrolling to steering in a few minutes. No paperwork pile, no phone tag.</x-slot:intro>
    </x-landing.section-heading>

    <ol class="relative mt-16 grid gap-6 md:grid-cols-3">
        {{-- Dashed route joining the steps on desktop --}}
        <div class="pointer-events-none absolute left-[16%] right-[16%] top-7 hidden h-px border-t border-dashed border-cream/25 md:block" aria-hidden="true"></div>

        @foreach ([
            ['Pick your ride', 'Browse the fleet by destination, vibe or category. Every car shows its seats, gearbox and daily price up front.', '#FF6B35',
                'M5 16h14M5 16v-3.5l1.7-4A1.5 1.5 0 018.1 7.5h7.8a1.5 1.5 0 011.4 1l1.7 4V16M5 16v2M19 16v2M8 13h.01M16 13h.01'],
            ['Choose your dates', 'Set pickup and return. We check availability and show your exact total before you commit.', '#2EC4FF',
                'M7 3v3M17 3v3M4 9h16M5 5h14a1 1 0 011 1v13a1 1 0 01-1 1H5a1 1 0 01-1-1V6a1 1 0 011-1z'],
            ['Reserve and roll', 'Reserve in one tap and track it from your dashboard. We confirm it and the keys are yours.', '#FFC857',
                'M5 12l4 4L19 7'],
        ] as [$title, $copy, $color, $icon])
            <li data-reveal style="--d: {{ $loop->index * 0.12 }}s" class="relative rounded-3xl border border-cream/10 bg-graphite/60 p-7 pt-12 transition duration-300 hover:-translate-y-1 hover:border-cream/25">
                <span class="absolute -top-7 left-7 flex h-14 w-14 items-center justify-center rounded-full border-2 bg-midnight" style="border-color: {{ $color }}; color: {{ $color }}">
                    <svg viewBox="0 0 24 24" class="h-6 w-6" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="{{ $icon }}"/></svg>
                </span>
                <p class="text-xs font-bold uppercase tracking-[0.3em]" style="color: {{ $color }}">Step 0{{ $loop->iteration }}</p>
                <h3 class="mt-3 text-2xl font-extrabold text-cream">{{ $title }}</h3>
                <p class="mt-3 leading-relaxed text-cream/65">{{ $copy }}</p>
            </li>
        @endforeach
    </ol>
</section>
