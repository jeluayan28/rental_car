<x-public-layout title="My bookings">
    <section class="relative isolate overflow-hidden">
        <div class="pointer-events-none absolute inset-0 -z-10" aria-hidden="true">
            <div class="absolute -right-[10%] top-0 h-[26rem] w-[26rem] rounded-full bg-tangerine/15 blur-[130px]"></div>
        </div>

        <div class="mx-auto max-w-4xl px-4 pb-24 pt-32 sm:px-6 sm:pt-40 lg:px-8">
            <a href="{{ route('dashboard') }}" class="text-sm font-semibold text-cream/60 transition hover:text-sun">&larr; Dashboard</a>
            <h1 class="mt-6 text-[clamp(2.5rem,7vw,5rem)] font-extrabold uppercase leading-[0.95] tracking-tight text-cream">
                My <span class="text-tangerine">bookings.</span>
            </h1>

            @if ($bookings->isEmpty())
                <x-empty-state class="mt-12" title="No bookings yet." text="When you reserve a car, your trips show up here.">
                    <a href="{{ route('cars.index') }}" class="rounded-full bg-tangerine px-7 py-3.5 font-bold text-midnight transition hover:bg-sun">Browse Cars &rarr;</a>
                </x-empty-state>
            @else
                <div class="mt-12 space-y-3">
                    @foreach ($bookings as $booking)
                        <x-booking-row :booking="$booking" />
                    @endforeach
                </div>
                <div class="mt-12">{{ $bookings->links('pagination.roamr') }}</div>
            @endif
        </div>
    </section>
</x-public-layout>
