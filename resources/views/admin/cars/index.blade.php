<x-admin-layout title="Cars">
    <x-slot:actions>
        <a href="{{ route('admin.cars.create') }}" class="rounded-full bg-tangerine px-5 py-2.5 text-sm font-bold text-midnight transition hover:bg-sun">+ Add car</a>
    </x-slot:actions>

    <form method="GET" class="flex flex-wrap items-center gap-3">
        <input type="search" name="q" value="{{ request('q') }}" placeholder="Search brand or model"
               class="w-full rounded-xl border-cream/20 bg-graphite text-sm text-cream placeholder-cream/40 focus:border-tangerine focus:ring-tangerine sm:w-72">
        <select name="status" onchange="this.form.submit()" class="rounded-xl border-cream/20 bg-graphite text-sm text-cream focus:border-tangerine focus:ring-tangerine">
            <option value="">All statuses</option>
            @foreach (\App\Enums\CarStatus::cases() as $s)
                <option value="{{ $s->value }}" @selected($status === $s)>{{ ucfirst($s->value) }}</option>
            @endforeach
        </select>
        <button class="rounded-xl border border-cream/25 px-4 py-2 text-sm font-semibold hover:border-sun hover:text-sun">Search</button>
        @if (request()->hasAny(['q', 'status']))<a href="{{ route('admin.cars.index') }}" class="text-sm text-cream/60 hover:text-sun">Clear</a>@endif
    </form>

    <div class="mt-6 overflow-x-auto rounded-2xl border border-cream/10 bg-graphite/60">
        <table class="w-full min-w-[46rem] text-left text-sm">
            <thead class="border-b border-cream/10 text-[11px] uppercase tracking-widest text-cream/50">
                <tr>
                    <th class="px-4 py-3 font-semibold">Car</th>
                    <th class="px-4 py-3 font-semibold">Category</th>
                    <th class="px-4 py-3 text-right font-semibold">Price/day</th>
                    <th class="px-4 py-3 font-semibold">Status</th>
                    <th class="px-4 py-3 text-right font-semibold">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-cream/10">
                @forelse ($cars as $car)
                    <tr class="hover:bg-cream/[.03]">
                        <td class="px-4 py-3">
                            <a href="{{ route('admin.cars.show', $car) }}" class="flex items-center gap-3 hover:text-sun">
                                <span class="h-11 w-16 shrink-0 overflow-hidden rounded-lg bg-midnight">
                                    @if ($car->image_url)<img src="{{ $car->image_url }}" alt="" loading="lazy" class="h-full w-full object-cover">@endif
                                </span>
                                <span>
                                    <span class="block text-xs uppercase tracking-widest text-cream/50">{{ $car->brand }} &middot; {{ $car->year }}</span>
                                    <span class="block font-bold">{{ $car->model }}</span>
                                </span>
                            </a>
                        </td>
                        <td class="px-4 py-3 text-cream/70">{{ $car->category }}</td>
                        <td class="px-4 py-3 text-right font-bold tabular-nums text-sun">&#8369;{{ number_format($car->price_per_day) }}</td>
                        <td class="px-4 py-3">
                            <form method="POST" action="{{ route('admin.cars.status', $car) }}">
                                @csrf @method('PATCH')
                                <label class="sr-only" for="status-{{ $car->id }}">Status for {{ $car->brand }} {{ $car->model }}</label>
                                <select id="status-{{ $car->id }}" name="status" onchange="this.form.submit()"
                                        class="rounded-lg border-cream/20 bg-midnight py-1.5 pl-3 pr-8 text-xs font-semibold uppercase tracking-wider text-cream focus:border-tangerine focus:ring-tangerine">
                                    @foreach (\App\Enums\CarStatus::cases() as $s)
                                        <option value="{{ $s->value }}" @selected($car->status === $s)>{{ ucfirst($s->value) }}</option>
                                    @endforeach
                                </select>
                            </form>
                        </td>
                        <td class="whitespace-nowrap px-4 py-3 text-right">
                            <a href="{{ route('admin.cars.show', $car) }}" class="px-2 text-cream/70 hover:text-sun">View</a>
                            <a href="{{ route('admin.cars.edit', $car) }}" class="px-2 text-cream/70 hover:text-sun">Edit</a>
                            <form method="POST" action="{{ route('admin.cars.destroy', $car) }}" class="inline"
                                  onsubmit="return confirm({{ \Illuminate\Support\Js::from('Delete '.$car->brand.' '.$car->model.'? This cannot be undone.') }})">
                                @csrf @method('DELETE')
                                <button class="px-2 text-cream/70 hover:text-tangerine">Delete</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="px-4 py-10 text-center text-cream/55">No cars found.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-8">{{ $cars->links('pagination.roamr') }}</div>
</x-admin-layout>
