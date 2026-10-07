@php
    $tabs = ['' => ['label' => 'All', 'url' => route('admin.bookings.index')]];
    foreach (\App\Enums\BookingStatus::cases() as $s) {
        $tabs[$s->value] = ['label' => ucfirst($s->value), 'url' => route('admin.bookings.index', ['status' => $s->value])];
    }
@endphp

<x-admin-layout title="Bookings">
    <x-admin.tabs :tabs="$tabs" :current="$status?->value ?? ''" />

    <div class="mt-6 overflow-x-auto rounded-2xl border border-cream/10 bg-graphite/60">
        <table class="w-full min-w-[52rem] text-left text-sm">
            <thead class="border-b border-cream/10 text-[11px] uppercase tracking-widest text-cream/50">
                <tr>
                    <th class="px-4 py-3 font-semibold">#</th>
                    <th class="px-4 py-3 font-semibold">Customer</th>
                    <th class="px-4 py-3 font-semibold">Car</th>
                    <th class="px-4 py-3 font-semibold">Dates</th>
                    <th class="px-4 py-3 text-right font-semibold">Total</th>
                    <th class="px-4 py-3 font-semibold">Status</th>
                    <th class="px-4 py-3 font-semibold">Payment</th>
                    <th class="px-4 py-3 text-right font-semibold">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-cream/10">
                @forelse ($bookings as $b)
                    <tr class="hover:bg-cream/[.03]">
                        <td class="px-4 py-3 font-bold">{{ $b->id }}</td>
                        <td class="px-4 py-3"><span class="block font-semibold">{{ $b->user->name }}</span><span class="text-xs text-cream/50">{{ $b->user->email }}</span></td>
                        <td class="px-4 py-3">{{ $b->car->brand }} {{ $b->car->model }}</td>
                        <td class="whitespace-nowrap px-4 py-3 text-cream/70">{{ $b->pickup_date->format('M j') }} &rarr; {{ $b->return_date->format('M j, Y') }}<span class="block text-xs text-cream/45">{{ $b->total_days }} {{ \Illuminate\Support\Str::plural('day', $b->total_days) }}</span></td>
                        <td class="px-4 py-3 text-right font-bold tabular-nums text-sun">&#8369;{{ number_format($b->total_price, 2) }}</td>
                        <td class="px-4 py-3"><x-badge :value="$b->status" /></td>
                        <td class="px-4 py-3">@if ($b->payment)<x-badge :value="$b->payment->payment_status" />@else<span class="text-cream/40">&mdash;</span>@endif</td>
                        <td class="whitespace-nowrap px-4 py-3 text-right">
                            <a href="{{ route('admin.bookings.show', $b) }}" class="px-2 text-cream/70 hover:text-sun">View</a>
                            @foreach (['confirm' => \App\Enums\BookingStatus::Confirmed, 'complete' => \App\Enums\BookingStatus::Completed, 'cancel' => \App\Enums\BookingStatus::Cancelled] as $action => $to)
                                @if ($b->status->canBecome($to))
                                    <form method="POST" action="{{ route('admin.bookings.transition', [$b, $action]) }}" class="inline"
                                          @if ($action === 'cancel') onsubmit="return confirm('Cancel booking #{{ $b->id }}?')" @endif>
                                        @csrf
                                        <button class="px-2 capitalize {{ $action === 'cancel' ? 'text-cream/70 hover:text-tangerine' : 'text-electric hover:underline' }}">{{ $action }}</button>
                                    </form>
                                @endif
                            @endforeach
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="8" class="px-4 py-10 text-center text-cream/55">No bookings found.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-8">{{ $bookings->links('pagination.roamr') }}</div>
</x-admin-layout>
