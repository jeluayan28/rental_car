@props(['car', 'index' => 0])

{{-- Collection card: the image dominates, with a slanted lower edge and the price overlapping it. --}}
<a href="{{ route('cars.show', $car) }}" style="--i: {{ $index }}"
   class="car-card group relative flex flex-col overflow-hidden rounded-[2rem] border border-cream/10 bg-graphite transition duration-300 ease-out hover:-translate-y-2 hover:border-tangerine/60 hover:shadow-[0_28px_70px_-20px_rgba(255,107,53,.5)] focus:outline-none focus-visible:ring-2 focus-visible:ring-electric">

    <div class="relative aspect-[4/3] overflow-hidden bg-midnight [clip-path:polygon(0_0,100%_0,100%_90%,0_100%)]">
        @if ($car->image_url)
            <img src="{{ $car->image_url }}" alt="{{ $car->brand }} {{ $car->model }}" loading="lazy"
                 class="h-full w-full object-cover transition duration-700 ease-out group-hover:scale-110">
        @else
            <div class="flex h-full items-center justify-center bg-gradient-to-br from-graphite to-midnight text-6xl font-extrabold text-cream/20">{{ $car->brand }}</div>
        @endif
        <div class="absolute inset-0 bg-gradient-to-t from-graphite via-graphite/10 to-transparent"></div>
        <div class="absolute inset-0 bg-tangerine/0 mix-blend-soft-light transition duration-500 group-hover:bg-tangerine/25"></div>

        <span class="absolute left-5 top-5 rounded-full bg-midnight/80 px-3 py-1 text-[11px] font-bold uppercase tracking-widest text-sun backdrop-blur">{{ $car->category }}</span>
        <span class="absolute right-5 top-5 inline-flex items-center gap-2 rounded-full bg-midnight/80 px-3 py-1 text-[11px] font-semibold uppercase tracking-widest text-cream backdrop-blur">
            <span class="relative flex h-2 w-2">
                <span class="dot absolute inline-flex h-full w-full rounded-full bg-electric"></span>
                <span class="relative inline-flex h-2 w-2 rounded-full bg-electric"></span>
            </span>
            {{ ucfirst($car->status->value) }}
        </span>
    </div>

    <div class="relative -mt-6 flex flex-1 flex-col px-6 pb-6">
        <p class="absolute -top-5 right-6 rounded-full bg-tangerine px-4 py-1.5 text-sm font-extrabold text-midnight shadow-lg shadow-black/30 transition group-hover:bg-sun">
            &#8369;{{ number_format($car->price_per_day) }}<span class="font-semibold opacity-70">/day</span>
        </p>

        <p class="mt-9 text-xs font-semibold uppercase tracking-[0.25em] text-cream/50">{{ $car->brand }}</p>
        <h3 class="mt-1 text-2xl font-extrabold leading-tight text-cream">{{ $car->model }}</h3>

        <dl class="mt-5 grid grid-cols-2 gap-3 border-t border-dashed border-cream/15 pt-5">
            <div>
                <dt class="text-[10px] font-semibold uppercase tracking-widest text-cream/45">Seats</dt>
                <dd class="mt-0.5 font-bold text-cream">{{ $car->seats }}</dd>
            </div>
            <div>
                <dt class="text-[10px] font-semibold uppercase tracking-widest text-cream/45">Transmission</dt>
                <dd class="mt-0.5 font-bold text-cream">{{ $car->transmission }}</dd>
            </div>
        </dl>

        <span class="mt-6 inline-flex items-center justify-between rounded-full border border-cream/20 px-5 py-3 text-sm font-bold text-cream transition duration-300 group-hover:border-tangerine group-hover:bg-tangerine group-hover:text-midnight">
            View Details
            <span class="transition-transform duration-300 group-hover:translate-x-1.5" aria-hidden="true">&rarr;</span>
        </span>
    </div>
</a>
