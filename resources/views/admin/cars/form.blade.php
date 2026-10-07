@php
    $editing = $car->exists;
    $ctl = 'mt-1.5 w-full rounded-xl border-cream/20 bg-graphite text-cream placeholder-cream/40 focus:border-tangerine focus:ring-tangerine';
    $label = 'block text-[11px] font-semibold uppercase tracking-widest text-cream/60';
    $current = old('status', $car->status?->value);
@endphp

<x-admin-layout :title="$editing ? 'Edit '.$car->brand.' '.$car->model : 'Add car'">
    <x-slot:actions>
        <a href="{{ $editing ? route('admin.cars.show', $car) : route('admin.cars.index') }}" class="text-sm font-semibold text-cream/70 hover:text-sun">Cancel</a>
    </x-slot:actions>

    <form method="POST" enctype="multipart/form-data" class="max-w-3xl space-y-6 rounded-2xl border border-cream/10 bg-graphite/60 p-6 sm:p-8"
          action="{{ $editing ? route('admin.cars.update', $car) : route('admin.cars.store') }}">
        @csrf
        @if ($editing) @method('PUT') @endif

        @if ($errors->any())
            <div role="alert" class="rounded-xl border border-tangerine/50 bg-tangerine/10 px-4 py-3 text-sm text-cream">Please fix the highlighted fields.</div>
        @endif

        <div class="grid gap-5 sm:grid-cols-2">
            <div>
                <label for="brand" class="{{ $label }}">Brand</label>
                <input id="brand" name="brand" value="{{ old('brand', $car->brand) }}" required maxlength="60" class="{{ $ctl }}">
                @error('brand')<p class="mt-1.5 text-sm text-tangerine">{{ $message }}</p>@enderror
            </div>
            <div>
                <label for="model" class="{{ $label }}">Model</label>
                <input id="model" name="model" value="{{ old('model', $car->model) }}" required maxlength="80" class="{{ $ctl }}">
                @error('model')<p class="mt-1.5 text-sm text-tangerine">{{ $message }}</p>@enderror
            </div>
            <div>
                <label for="year" class="{{ $label }}">Year</label>
                <input id="year" name="year" type="number" value="{{ old('year', $car->year) }}" required class="{{ $ctl }}">
                @error('year')<p class="mt-1.5 text-sm text-tangerine">{{ $message }}</p>@enderror
            </div>
            <div>
                <label for="category" class="{{ $label }}">Category</label>
                <select id="category" name="category" required class="{{ $ctl }}">
                    @foreach (\App\Models\Car::CATEGORIES as $opt)
                        <option value="{{ $opt }}" @selected(old('category', $car->category) === $opt)>{{ $opt }}</option>
                    @endforeach
                </select>
                @error('category')<p class="mt-1.5 text-sm text-tangerine">{{ $message }}</p>@enderror
            </div>
            <div>
                <label for="price_per_day" class="{{ $label }}">Price per day (&#8369;)</label>
                <input id="price_per_day" name="price_per_day" type="number" step="0.01" min="1" value="{{ old('price_per_day', $car->price_per_day) }}" required class="{{ $ctl }}">
                @error('price_per_day')<p class="mt-1.5 text-sm text-tangerine">{{ $message }}</p>@enderror
            </div>
            <div>
                <label for="seats" class="{{ $label }}">Seats</label>
                <input id="seats" name="seats" type="number" min="1" max="20" value="{{ old('seats', $car->seats) }}" required class="{{ $ctl }}">
                @error('seats')<p class="mt-1.5 text-sm text-tangerine">{{ $message }}</p>@enderror
            </div>
            <div>
                <label for="transmission" class="{{ $label }}">Transmission</label>
                <select id="transmission" name="transmission" required class="{{ $ctl }}">
                    @foreach (\App\Models\Car::TRANSMISSIONS as $opt)
                        <option value="{{ $opt }}" @selected(old('transmission', $car->transmission) === $opt)>{{ $opt }}</option>
                    @endforeach
                </select>
                @error('transmission')<p class="mt-1.5 text-sm text-tangerine">{{ $message }}</p>@enderror
            </div>
            <div>
                <label for="fuel_type" class="{{ $label }}">Fuel type</label>
                <select id="fuel_type" name="fuel_type" required class="{{ $ctl }}">
                    @foreach (\App\Models\Car::FUEL_TYPES as $opt)
                        <option value="{{ $opt }}" @selected(old('fuel_type', $car->fuel_type) === $opt)>{{ $opt }}</option>
                    @endforeach
                </select>
                @error('fuel_type')<p class="mt-1.5 text-sm text-tangerine">{{ $message }}</p>@enderror
            </div>
            <div class="sm:col-span-2">
                <label for="status" class="{{ $label }}">Status</label>
                <select id="status" name="status" required class="{{ $ctl }}">
                    @foreach (\App\Enums\CarStatus::cases() as $s)
                        <option value="{{ $s->value }}" @selected($current === $s->value)>{{ ucfirst($s->value) }}</option>
                    @endforeach
                </select>
                @error('status')<p class="mt-1.5 text-sm text-tangerine">{{ $message }}</p>@enderror
            </div>
        </div>

        <div>
            <label for="description" class="{{ $label }}">Description</label>
            <textarea id="description" name="description" rows="4" maxlength="2000" class="{{ $ctl }}">{{ old('description', $car->description) }}</textarea>
            @error('description')<p class="mt-1.5 text-sm text-tangerine">{{ $message }}</p>@enderror
        </div>

        <fieldset class="rounded-xl border border-dashed border-cream/20 p-5">
            <legend class="px-2 {{ $label }}">Image</legend>
            @if ($car->image_url)
                <img src="{{ $car->image_url }}" alt="Current image" class="mb-4 h-28 rounded-lg border border-cream/10 object-cover">
            @endif
            <div class="grid gap-5 sm:grid-cols-2">
                <div>
                    <label for="image_file" class="{{ $label }}">Upload a file</label>
                    <input id="image_file" name="image_file" type="file" accept="image/png,image/jpeg,image/webp"
                           class="mt-1.5 block w-full text-sm text-cream/70 file:mr-3 file:rounded-full file:border-0 file:bg-tangerine file:px-4 file:py-2 file:text-sm file:font-bold file:text-midnight">
                    <p class="mt-1 text-xs text-cream/45">JPG, PNG or WebP, up to 4 MB.</p>
                    @error('image_file')<p class="mt-1.5 text-sm text-tangerine">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label for="image_url" class="{{ $label }}">&hellip;or an image URL</label>
                    <input id="image_url" name="image_url" type="url" value="{{ old('image_url') }}" placeholder="https://" class="{{ $ctl }}">
                    @error('image_url')<p class="mt-1.5 text-sm text-tangerine">{{ $message }}</p>@enderror
                </div>
            </div>
            @if ($editing)<p class="mt-3 text-xs text-cream/45">Leave both empty to keep the current image.</p>@endif
        </fieldset>

        <div class="flex items-center gap-4">
            <button class="rounded-full bg-tangerine px-7 py-3 font-bold text-midnight transition hover:bg-sun">{{ $editing ? 'Save changes' : 'Add car' }}</button>
        </div>
    </form>
</x-admin-layout>
