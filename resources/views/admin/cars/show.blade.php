<x-admin-layout :title="$car->brand.' '.$car->model">
    <x-slot:actions>
        <a href="{{ route('cars.show', $car) }}" target="_blank" class="text-sm font-semibold text-cream/70 hover:text-sun">View on site &nearr;</a>
        <a href="{{ route('admin.cars.edit', $car) }}" class="rounded-full bg-tangerine px-5 py-2.5 text-sm font-bold text-midnight transition hover:bg-sun">Edit</a>
        <form method="POST" action="{{ route('admin.cars.destroy', $car) }}"
              onsubmit="return confirm({{ \Illuminate\Support\Js::from('Delete '.$car->brand.' '.$car->model.'? This cannot be undone.') }})">
            @csrf @method('DELETE')
            <button class="rounded-full border border-cream/25 px-5 py-2.5 text-sm font-bold text-cream/80 transition hover:border-tangerine hover:text-tangerine">Delete</button>
        </form>
    </x-slot:actions>

    <div class="grid gap-8 lg:grid-cols-5">
        <div class="lg:col-span-2">
            <div class="aspect-[4/3] overflow-hidden rounded-2xl border border-cream/10 bg-graphite">
                @if ($car->image_url)<img src="{{ $car->image_url }}" alt="{{ $car->brand }} {{ $car->model }}" class="h-full w-full object-cover">@endif
            </div>
        </div>

        <div class="lg:col-span-3">
            <dl class="grid grid-cols-2 gap-3 sm:grid-cols-3">
                @foreach ([['Brand', $car->brand], ['Model', $car->model], ['Year', $car->year], ['Category', $car->category], ['Seats', $car->seats], ['Transmission', $car->transmission], ['Fuel', $car->fuel_type], ['Price/day', '₱'.number_format($car->price_per_day, 2)]] as [$k, $v])
                    <div class="rounded-xl border border-cream/10 bg-graphite/60 px-4 py-3">
                        <dt class="text-[10px] font-semibold uppercase tracking-widest text-cream/50">{{ $k }}</dt>
                        <dd class="mt-1 font-bold">{{ $v }}</dd>
                    </div>
                @endforeach
                <div class="rounded-xl border border-cream/10 bg-graphite/60 px-4 py-3">
                    <dt class="text-[10px] font-semibold uppercase tracking-widest text-cream/50">Status</dt>
                    <dd class="mt-1.5">
                        <form method="POST" action="{{ route('admin.cars.status', $car) }}">
                            @csrf @method('PATCH')
                            <select name="status" onchange="this.form.submit()" aria-label="Change status"
                                    class="rounded-lg border-cream/20 bg-midnight py-1 pl-2 pr-7 text-xs font-semibold uppercase tracking-wider text-cream focus:border-tangerine focus:ring-tangerine">
                                @foreach (\App\Enums\CarStatus::cases() as $s)
                                    <option value="{{ $s->value }}" @selected($car->status === $s)>{{ ucfirst($s->value) }}</option>
                                @endforeach
                            </select>
                        </form>
                    </dd>
                </div>
            </dl>

            @if ($car->description)
                <p class="mt-5 text-cream/70">{{ $car->description }}</p>
            @endif
        </div>
    </div>

    <h2 class="mt-12 text-sm font-bold uppercase tracking-widest text-cream/60">Recent bookings</h2>
    <div class="mt-4 divide-y divide-cream/10 rounded-2xl border border-cream/10 bg-graphite/60">
        @forelse ($bookings as $b)
            <a href="{{ route('admin.bookings.show', $b) }}" class="flex flex-wrap items-center justify-between gap-3 p-4 hover:bg-cream/5">
                <span><span class="font-bold">#{{ $b->id }}</span> &middot; {{ $b->user->name }}
                    <span class="text-cream/55">&middot; {{ $b->pickup_date->format('M j') }} &rarr; {{ $b->return_date->format('M j, Y') }}</span></span>
                <x-badge :value="$b->status" />
            </a>
        @empty
            <p class="p-6 text-sm text-cream/55">No bookings for this car yet.</p>
        @endforelse
    </div>
</x-admin-layout>
