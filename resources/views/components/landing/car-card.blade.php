@props(['car', 'featured' => false])

{{-- Visibility is owned by the journey's Alpine scope (see journey.blade.php). Non-featured cars stay hidden until a vibe is picked. --}}
<article x-show="show('{{ $car->category }}', {{ $featured ? 'true' : 'false' }})"
         @unless ($featured) x-cloak @endunless
         x-transition:enter="transition duration-500 ease-out" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0"
         class="group flex flex-col overflow-hidden rounded-3xl border border-cream/10 bg-graphite/60 transition hover:border-tangerine/60">

    <div class="relative aspect-[16/10] overflow-hidden bg-midnight">
        @if ($car->image_url)
            <img src="{{ $car->image_url }}" alt="{{ $car->brand }} {{ $car->model }}" loading="lazy"
                 class="h-full w-full object-cover transition duration-500 group-hover:scale-105">
        @endif
        <div class="absolute inset-0 bg-gradient-to-t from-graphite via-transparent to-transparent"></div>
        <span class="absolute left-4 top-4 rounded-full bg-midnight/80 px-3 py-1 text-[11px] font-bold uppercase tracking-widest text-sun backdrop-blur">{{ $car->category }}</span>
    </div>

    <div class="flex flex-1 flex-col p-5">
        <p class="text-xs font-semibold uppercase tracking-widest text-cream/50">{{ $car->brand }} &middot; {{ $car->year }}</p>
        <h3 class="mt-1 text-xl font-extrabold text-cream">{{ $car->model }}</h3>

        <ul class="mt-4 flex flex-wrap gap-2 text-xs text-cream/70">
            <li class="rounded-full border border-cream/15 px-2.5 py-1">{{ $car->seats }} seats</li>
            <li class="rounded-full border border-cream/15 px-2.5 py-1">{{ $car->transmission }}</li>
            <li class="rounded-full border border-cream/15 px-2.5 py-1">{{ $car->fuel_type }}</li>
        </ul>

        <div class="mt-6 flex items-end justify-between gap-3">
            <p class="text-cream">
                <span class="text-2xl font-extrabold text-sun">&#8369;{{ number_format($car->price_per_day) }}</span>
                <span class="text-sm text-cream/55">/day</span>
            </p>
            <a href="{{ route('cars.show', $car) }}" class="rounded-full bg-tangerine px-4 py-2 text-sm font-bold text-midnight transition hover:bg-sun focus:outline-none focus-visible:ring-2 focus-visible:ring-electric">Book</a>
        </div>
    </div>
</article>
