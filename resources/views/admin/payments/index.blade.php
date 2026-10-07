@php
    $tabs = ['' => ['label' => 'All', 'url' => route('admin.payments.index')]];
    foreach (\App\Enums\PaymentStatus::cases() as $s) {
        $tabs[$s->value] = ['label' => ucfirst($s->value), 'url' => route('admin.payments.index', ['status' => $s->value])];
    }
@endphp

<x-admin-layout title="Payments">
    <dl class="grid max-w-xl grid-cols-2 gap-4">
        <div class="rounded-2xl border border-cream/10 bg-graphite/60 p-5">
            <dt class="text-[11px] font-semibold uppercase tracking-widest text-cream/50">Received</dt>
            <dd class="mt-2 text-2xl font-extrabold tabular-nums text-electric">&#8369;{{ number_format($totals['paid'], 2) }}</dd>
        </div>
        <div class="rounded-2xl border border-cream/10 bg-graphite/60 p-5">
            <dt class="text-[11px] font-semibold uppercase tracking-widest text-cream/50">Outstanding</dt>
            <dd class="mt-2 text-2xl font-extrabold tabular-nums text-sun">&#8369;{{ number_format($totals['pending'], 2) }}</dd>
        </div>
    </dl>

    <div class="mt-8"><x-admin.tabs :tabs="$tabs" :current="$status?->value ?? ''" /></div>

    <div class="mt-6 overflow-x-auto rounded-2xl border border-cream/10 bg-graphite/60">
        <table class="w-full min-w-[50rem] text-left text-sm">
            <thead class="border-b border-cream/10 text-[11px] uppercase tracking-widest text-cream/50">
                <tr>
                    <th class="px-4 py-3 font-semibold">Booking</th>
                    <th class="px-4 py-3 font-semibold">Customer</th>
                    <th class="px-4 py-3 text-right font-semibold">Amount</th>
                    <th class="px-4 py-3 font-semibold">Status</th>
                    <th class="px-4 py-3 font-semibold">Method</th>
                    <th class="px-4 py-3 font-semibold">Paid at</th>
                    <th class="px-4 py-3 text-right font-semibold">Record payment</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-cream/10">
                @forelse ($payments as $p)
                    <tr class="hover:bg-cream/[.03]">
                        <td class="px-4 py-3">
                            <a href="{{ route('admin.bookings.show', $p->booking) }}" class="font-bold hover:text-sun">#{{ $p->booking_id }}</a>
                            <span class="block text-xs text-cream/50">{{ $p->booking->car->brand }} {{ $p->booking->car->model }}</span>
                        </td>
                        <td class="px-4 py-3">{{ $p->booking->user->name }}</td>
                        <td class="px-4 py-3 text-right font-bold tabular-nums text-sun">&#8369;{{ number_format($p->amount, 2) }}</td>
                        <td class="px-4 py-3"><x-badge :value="$p->payment_status" /></td>
                        <td class="px-4 py-3 text-cream/70">{{ $p->payment_method ?? '—' }}</td>
                        <td class="whitespace-nowrap px-4 py-3 text-cream/60">{{ $p->paid_at?->format('M j, Y') ?? '—' }}</td>
                        <td class="px-4 py-3 text-right">
                            @if ($p->payment_status === \App\Enums\PaymentStatus::Pending)
                                <form method="POST" action="{{ route('admin.payments.paid', $p) }}" class="flex justify-end gap-2">
                                    @csrf @method('PATCH')
                                    <label class="sr-only" for="m-{{ $p->id }}">Payment method</label>
                                    <select id="m-{{ $p->id }}" name="payment_method" class="rounded-lg border-cream/20 bg-midnight py-1 pl-2 pr-7 text-xs text-cream focus:border-tangerine focus:ring-tangerine">
                                        @foreach ($methods as $m)<option>{{ $m }}</option>@endforeach
                                    </select>
                                    <button class="rounded-full bg-electric px-3 py-1 text-xs font-bold text-midnight">Mark paid</button>
                                </form>
                            @else
                                <span class="text-cream/35">&mdash;</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="7" class="px-4 py-10 text-center text-cream/55">No payments found.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-8">{{ $payments->links('pagination.roamr') }}</div>
</x-admin-layout>
