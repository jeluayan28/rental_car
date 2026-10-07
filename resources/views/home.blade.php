<x-public-layout>
    <section class="bg-ink text-white">
        <div class="mx-auto max-w-7xl px-4 py-24 sm:px-6 sm:py-32 lg:px-8">
            <p class="text-sm font-semibold uppercase tracking-widest text-brand-400">Car rental &amp; adventure</p>
            <h1 class="mt-4 max-w-3xl text-4xl font-extrabold tracking-tight sm:text-6xl">
                Find the right car. Go somewhere worth going.
            </h1>
            <p class="mt-6 max-w-xl text-lg text-slate-300">
                Browse our fleet, pick your dates and book in minutes.
            </p>

            {{-- Availability search: wired up when booking is built. --}}
            <form action="{{ route('cars.index') }}" method="GET"
                  class="mt-10 grid max-w-3xl gap-4 rounded-2xl bg-white p-4 text-ink shadow-xl sm:grid-cols-3">
                <div>
                    <label for="pickup" class="block text-xs font-semibold uppercase text-slate-500">Pick-up</label>
                    <input id="pickup" name="pickup" type="date" class="mt-1 w-full rounded-lg border-slate-300 focus:border-brand-500 focus:ring-brand-500">
                </div>
                <div>
                    <label for="return" class="block text-xs font-semibold uppercase text-slate-500">Return</label>
                    <input id="return" name="return" type="date" class="mt-1 w-full rounded-lg border-slate-300 focus:border-brand-500 focus:ring-brand-500">
                </div>
                <button class="self-end rounded-lg bg-brand-600 px-4 py-2.5 font-semibold text-white hover:bg-brand-700">
                    Check availability
                </button>
            </form>
        </div>
    </section>

    <section class="mx-auto max-w-7xl px-4 py-20 sm:px-6 lg:px-8">
        <h2 class="text-2xl font-bold">Why ROAMR</h2>
        <div class="mt-8 grid gap-6 sm:grid-cols-3">
            @foreach ([
                ['Browse the fleet', 'Compact city cars to rugged 4x4s, all in one place.'],
                ['Instant availability', 'Choose your dates and see what is free right away.'],
                ['Manage your trips', 'View and manage all your bookings from your account.'],
            ] as [$heading, $copy])
                <div class="rounded-2xl border border-slate-200 p-6">
                    <h3 class="font-semibold">{{ $heading }}</h3>
                    <p class="mt-2 text-sm text-slate-600">{{ $copy }}</p>
                </div>
            @endforeach
        </div>
    </section>
</x-public-layout>
