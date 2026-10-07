@props(['top' => 'top-20'])

@php
    // Flash messages become toasts. Kebab-case keys (e.g. "profile-updated") are Breeze's internal
    // status flags that pages render themselves, so they are skipped here.
    $toasts = [];
    $status = session('status');
    if (is_string($status) && $status !== '' && ! preg_match('/^[a-z]+(-[a-z]+)+$/', $status)) {
        $toasts[] = ['success', $status];
    }
    if ($error = session('error')) {
        $toasts[] = ['error', $error];
    }
@endphp

@if ($toasts)
    <div class="pointer-events-none fixed right-4 {{ $top }} z-[60] flex w-[min(24rem,calc(100vw-2rem))] flex-col gap-3">
        @foreach ($toasts as [$type, $message])
            <div x-data="{ show: true }" @if ($type === 'success') x-init="setTimeout(() => show = false, 6000)" @endif
                 x-show="show" x-transition:leave="transition duration-300" x-transition:leave-end="opacity-0 translate-x-6"
                 role="{{ $type === 'error' ? 'alert' : 'status' }}"
                 class="toast-in pointer-events-auto flex items-start gap-3 rounded-2xl border bg-graphite/95 p-4 text-sm text-cream shadow-2xl backdrop-blur {{ $type === 'error' ? 'border-tangerine/60' : 'border-electric/50' }}">
                <span class="mt-0.5 flex h-5 w-5 shrink-0 items-center justify-center rounded-full text-xs font-extrabold text-midnight {{ $type === 'error' ? 'bg-tangerine' : 'bg-electric' }}" aria-hidden="true">{!! $type === 'error' ? '!' : '&check;' !!}</span>
                <p class="flex-1 leading-relaxed">{{ $message }}</p>
                <button type="button" x-on:click="show = false" class="-mr-1 rounded p-1 text-cream/50 transition hover:text-cream focus:outline-none focus-visible:ring-2 focus-visible:ring-electric" aria-label="Dismiss">&times;</button>
            </div>
        @endforeach
    </div>
@endif
