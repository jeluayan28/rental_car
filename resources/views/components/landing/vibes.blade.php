@php
    $lanes = [
        ['key' => 'city', 'word' => 'City', 'accent' => '#2EC4FF', 'copy' => 'Sharp, efficient and easy to park. Built for the grid.', 'types' => 'Sedans &middot; Sport'],
        ['key' => 'adventure', 'word' => 'Adventure', 'accent' => '#FF6B35', 'copy' => 'Ground clearance, torque and room for the gear.', 'types' => 'SUVs &middot; Off-road'],
        ['key' => 'getaway', 'word' => 'Getaway', 'accent' => '#FFC857', 'copy' => 'Space, comfort and style for the long way there.', 'types' => 'Family &middot; Luxury'],
    ];
@endphp

<section class="relative px-4 py-24 pl-14 sm:px-6 sm:pl-20 lg:px-8 lg:py-32 lg:pl-28">
    <x-landing.waypoint n="02" />

    <x-landing.section-heading step="02" label="Choose your vibe" accent="text-electric">
        What&rsquo;s your <span class="text-sun">vibe?</span>
        <x-slot:intro>Pick a lane. It will line up the right rides below.</x-slot:intro>
    </x-landing.section-heading>

    {{-- Three lanes, separated by dashed lane markings --}}
    <div class="mt-14 border-b border-dashed border-cream/20" role="group" aria-label="Choose a vibe">
        @foreach ($lanes as $lane)
            <button type="button" data-reveal style="--d: {{ $loop->index * 0.1 }}s; --accent: {{ $lane['accent'] }}"
                    x-on:click="pick('{{ $lane['key'] }}')"
                    :class="{ 'is-on': vibe === '{{ $lane['key'] }}' }"
                    :aria-pressed="vibe === '{{ $lane['key'] }}'"
                    class="lane group flex w-full flex-col gap-4 border-t border-dashed border-cream/20 py-6 text-left focus:outline-none focus-visible:bg-cream/5 sm:flex-row sm:items-center sm:justify-between sm:py-8">
                <span class="lane-word block text-[clamp(3rem,10.5vw,8.5rem)] font-extrabold uppercase leading-none tracking-tight">{{ $lane['word'] }}</span>

                <span class="flex items-center gap-5 sm:max-w-xs sm:flex-col sm:items-start sm:gap-2">
                    <span class="text-sm text-cream/65">{{ $lane['copy'] }}</span>
                    <span class="flex items-center gap-2 whitespace-nowrap text-[11px] font-semibold uppercase tracking-widest" style="color: {{ $lane['accent'] }}">
                        <span x-show="vibe !== '{{ $lane['key'] }}'">{!! $lane['types'] !!} &darr;</span>
                        <span x-show="vibe === '{{ $lane['key'] }}'" x-cloak>Selected &check; Tap to clear</span>
                    </span>
                </span>
            </button>
        @endforeach
    </div>
</section>
