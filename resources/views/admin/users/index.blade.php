@php
    $tabs = [
        '' => ['label' => 'All', 'url' => route('admin.users.index'), 'count' => $counts['all']],
        'customer' => ['label' => 'Customers', 'url' => route('admin.users.index', ['role' => 'customer']), 'count' => $counts['customer']],
        'admin' => ['label' => 'Admins', 'url' => route('admin.users.index', ['role' => 'admin']), 'count' => $counts['admin']],
    ];
@endphp

<x-admin-layout title="Users">
    <div class="flex flex-wrap items-center justify-between gap-4">
        <x-admin.tabs :tabs="$tabs" :current="$role?->value ?? ''" />
        <form method="GET" class="flex gap-2">
            @if ($role)<input type="hidden" name="role" value="{{ $role->value }}">@endif
            <input type="search" name="q" value="{{ request('q') }}" placeholder="Search name or email"
                   class="w-64 rounded-xl border-cream/20 bg-graphite text-sm text-cream placeholder-cream/40 focus:border-tangerine focus:ring-tangerine">
            <button class="rounded-xl border border-cream/25 px-4 py-2 text-sm font-semibold hover:border-sun hover:text-sun">Search</button>
        </form>
    </div>

    <div class="mt-6 overflow-x-auto rounded-2xl border border-cream/10 bg-graphite/60">
        <table class="w-full min-w-[40rem] text-left text-sm">
            <thead class="border-b border-cream/10 text-[11px] uppercase tracking-widest text-cream/50">
                <tr>
                    <th class="px-4 py-3 font-semibold">Name</th>
                    <th class="px-4 py-3 font-semibold">Email</th>
                    <th class="px-4 py-3 font-semibold">Role</th>
                    <th class="px-4 py-3 text-right font-semibold">Bookings</th>
                    <th class="px-4 py-3 font-semibold">Joined</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-cream/10">
                @forelse ($users as $user)
                    <tr class="hover:bg-cream/[.03]">
                        <td class="px-4 py-3 font-semibold">{{ $user->name }}</td>
                        <td class="px-4 py-3 text-cream/70">{{ $user->email }}</td>
                        <td class="px-4 py-3"><x-badge :value="$user->role" /></td>
                        <td class="px-4 py-3 text-right tabular-nums">
                            @if ($user->bookings_count)
                                <a href="{{ route('admin.bookings.index') }}" class="hover:text-sun">{{ $user->bookings_count }}</a>
                            @else <span class="text-cream/40">0</span> @endif
                        </td>
                        <td class="whitespace-nowrap px-4 py-3 text-cream/60">{{ $user->created_at->format('M j, Y') }}</td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="px-4 py-10 text-center text-cream/55">No users found.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-8">{{ $users->links('pagination.roamr') }}</div>
</x-admin-layout>
