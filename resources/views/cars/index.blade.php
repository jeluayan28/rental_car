<x-public-layout title="Cars">
    <section class="relative isolate overflow-hidden">
        <div class="pointer-events-none absolute inset-0 -z-10" aria-hidden="true">
            <div class="absolute -right-[10%] top-0 h-[28rem] w-[28rem] rounded-full bg-tangerine/15 blur-[130px]"></div>
            <div class="absolute -left-[10%] top-[30%] h-[26rem] w-[26rem] rounded-full bg-electric/10 blur-[130px]"></div>
        </div>

        <div class="mx-auto max-w-7xl px-4 pb-24 pt-32 sm:px-6 sm:pt-40 lg:px-8">
            <p class="flex items-center gap-3 text-xs font-semibold uppercase tracking-[0.3em] text-sun">
                <span class="h-px w-10 bg-sun"></span> The collection
            </p>
            <h1 class="mt-5 max-w-4xl text-[clamp(2.75rem,8vw,6.5rem)] font-extrabold uppercase leading-[0.9] tracking-tight text-cream">
                Find your <span class="text-tangerine">perfect ride.</span>
            </h1>
            <p class="mt-6 text-lg text-cream/65">
                @if ($cars->total() > 0)
                    {{ $cars->total() }} {{ \Illuminate\Support\Str::plural('ride', $cars->total()) }} available now.
                @else
                    Nothing is available right now.
                @endif
            </p>

            @if ($cars->isEmpty())
                <div class="mt-16 rounded-3xl border border-dashed border-cream/20 p-12 text-center text-cream/60">
                    No cars are available at the moment. Please check back soon.
                </div>
            @else
                <div class="mt-14 grid gap-7 sm:grid-cols-2 xl:grid-cols-3">
                    @foreach ($cars as $car)
                        <x-cars.card :car="$car" :index="$loop->index" />
                    @endforeach
                </div>

                <div class="mt-16">
                    {{ $cars->links('pagination.roamr') }}
                </div>
            @endif
        </div>
    </section>
</x-public-layout>
