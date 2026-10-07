@props(['cars', 'featuredIds' => []])

<section id="rides" class="relative scroll-mt-16 px-4 py-24 pl-14 sm:px-6 sm:pl-20 lg:px-8 lg:py-32 lg:pl-28">
    <x-landing.waypoint n="04" />

    <x-landing.section-heading step="04" label="Choose your ride" accent="text-tangerine">
        Now pick <span class="text-sun">your ride.</span>
        <x-slot:intro>Fresh off the lot and ready to roll. Book by the day.</x-slot:intro>
    </x-landing.section-heading>

    {{-- Active vibe filter --}}
    <div class="mt-10 h-8">
        <p x-show="vibe" x-cloak x-transition.opacity class="flex flex-wrap items-center gap-3 text-sm text-cream/70">
            <span>Showing rides for <strong class="font-bold uppercase tracking-wider text-sun" x-text="vibe"></strong></span>
            <button type="button" x-on:click="vibe = null" class="rounded-full border border-cream/20 px-3 py-1 text-xs font-semibold text-cream transition hover:border-tangerine hover:text-tangerine">Clear &times;</button>
        </p>
    </div>

    @if ($cars->isEmpty())
        <x-empty-state class="mt-6" title="No cars available right now." text="Check back soon." />
    @else
        <div data-reveal class="grid gap-5 sm:grid-cols-2 xl:grid-cols-3">
            @foreach ($cars as $car)
                <x-landing.car-card :car="$car" :featured="in_array($car->id, $featuredIds)" />
            @endforeach
        </div>
    @endif
</section>
