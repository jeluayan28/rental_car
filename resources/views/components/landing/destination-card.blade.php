@props(['type', 'name', 'tagline', 'trip', 'accent' => '#2EC4FF', 'delay' => '0s'])

{{-- A destination as a mini route map. The map art is inline SVG; the route dashes flow on hover. --}}
<a href="{{ route('cars.index') }}" data-reveal style="--d: {{ $delay }}; --accent: {{ $accent }}"
   class="group relative flex flex-col overflow-hidden rounded-3xl border border-cream/10 bg-graphite/60 transition duration-300 hover:-translate-y-1 hover:border-[color:var(--accent)] focus:outline-none focus-visible:ring-2 focus-visible:ring-electric">

    <svg viewBox="0 0 240 150" class="h-40 w-full" fill="none" aria-hidden="true" preserveAspectRatio="xMidYMid slice">
        <rect width="240" height="150" fill="#10172B"/>

        @switch($type)
            @case('city')
                {{-- Street grid --}}
                <g stroke="#F4F1DE" stroke-opacity=".12" stroke-width="1">
                    @foreach ([20, 55, 90, 125, 160, 195, 225] as $x)<path d="M{{ $x }} 0V150"/>@endforeach
                    @foreach ([25, 60, 95, 130] as $y)<path d="M0 {{ $y }}H240"/>@endforeach
                </g>
                <path d="M55 130V95H125V60H195V25" class="route route-live" stroke="var(--accent)" stroke-width="3" stroke-linejoin="round"/>
                @break

            @case('mountains')
                {{-- Contours + peaks --}}
                <g stroke="#F4F1DE" stroke-opacity=".14" stroke-width="1.2">
                    <path d="M0 120C40 100 70 110 110 80S190 60 240 85"/>
                    <path d="M0 140C50 118 80 130 120 100S195 82 240 105"/>
                    <path d="M0 100C35 82 75 88 105 62S185 42 240 62"/>
                </g>
                <path d="M30 130L85 55L115 95L150 40L215 130Z" fill="#F4F1DE" fill-opacity=".05" stroke="#F4F1DE" stroke-opacity=".25"/>
                <path d="M40 135C70 115 60 95 95 85S150 90 140 60S160 35 150 40" class="route route-live" stroke="var(--accent)" stroke-width="3" stroke-linecap="round"/>
                @break

            @case('beach')
                {{-- Waves + sun --}}
                <circle cx="190" cy="38" r="16" fill="#FFC857" fill-opacity=".9"/>
                <g stroke="#2EC4FF" stroke-opacity=".35" stroke-width="1.5">
                    <path d="M0 105q15-9 30 0t30 0 30 0 30 0 30 0 30 0 30 0 30 0"/>
                    <path d="M0 125q15-9 30 0t30 0 30 0 30 0 30 0 30 0 30 0 30 0"/>
                    <path d="M0 145q15-9 30 0t30 0 30 0 30 0 30 0 30 0 30 0 30 0"/>
                </g>
                <path d="M10 85C60 60 90 90 130 70S190 55 230 75" class="route route-live" stroke="var(--accent)" stroke-width="3" stroke-linecap="round"/>
                @break

            @default
                {{-- Loop trip --}}
                <g stroke="#F4F1DE" stroke-opacity=".1" stroke-width="1">
                    <circle cx="120" cy="75" r="62"/><circle cx="120" cy="75" r="38"/>
                </g>
                <path d="M120 22C165 22 200 50 200 80S165 135 120 128 40 105 42 72 78 22 120 22Z" class="route route-live" stroke="var(--accent)" stroke-width="3" stroke-linejoin="round"/>
        @endswitch

        {{-- Start / end pins (positions are per-map) --}}
        @php
            $pins = match ($type) {
                'city' => [[55, 130], [195, 25]],
                'mountains' => [[40, 135], [150, 40]],
                'beach' => [[10, 85], [230, 75]],
                default => [[120, 22], [120, 128]],
            };
        @endphp
        <circle cx="{{ $pins[0][0] }}" cy="{{ $pins[0][1] }}" r="5" fill="#0B1020" stroke="#2EC4FF" stroke-width="2.5"/>
        <circle cx="{{ $pins[1][0] }}" cy="{{ $pins[1][1] }}" r="5" fill="#0B1020" stroke="#FFC857" stroke-width="2.5"/>
    </svg>

    <div class="flex flex-1 flex-col p-6">
        <div class="flex items-start justify-between gap-3">
            <h3 class="text-2xl font-extrabold uppercase tracking-tight text-cream">{{ $name }}</h3>
            <span class="mt-1 text-xl text-cream/40 transition group-hover:translate-x-1 group-hover:text-[color:var(--accent)]" aria-hidden="true">&rarr;</span>
        </div>
        <p class="mt-2 text-sm leading-relaxed text-cream/65">{{ $tagline }}</p>
        <p class="mt-5 inline-flex w-fit items-center gap-2 rounded-full border border-cream/15 px-3 py-1 text-[11px] font-semibold uppercase tracking-widest text-cream/70">
            <span class="h-1.5 w-1.5 rounded-full bg-[color:var(--accent)]"></span>{{ $trip }}
        </p>
    </div>
</a>
