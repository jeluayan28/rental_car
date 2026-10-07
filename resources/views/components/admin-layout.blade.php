@props(['title' => 'Admin'])

@php
    $nav = [
        ['Dashboard', route('admin.dashboard'), request()->routeIs('admin.dashboard')],
        ['Cars', route('admin.cars.index'), request()->routeIs('admin.cars.*')],
        ['Bookings', route('admin.bookings.index'), request()->routeIs('admin.bookings.*')],
        ['Users', route('admin.users.index'), request()->routeIs('admin.users.*')],
        ['Payments', route('admin.payments.index'), request()->routeIs('admin.payments.*')],
    ];
@endphp

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="robots" content="noindex">
        <title>{{ $title }} · Admin · {{ config('app.name') }}</title>
        <link rel="icon" href="{{ asset('favicon.svg') }}" type="image/svg+xml">
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800&display=swap" rel="stylesheet" />
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="min-h-screen bg-midnight font-sans text-cream antialiased lg:flex">
        {{-- Sidebar (desktop) / top bar (mobile) --}}
        <aside class="border-b border-cream/10 bg-graphite/60 lg:sticky lg:top-0 lg:flex lg:h-screen lg:w-60 lg:shrink-0 lg:flex-col lg:border-b-0 lg:border-r">
            <div class="flex items-center justify-between px-4 py-4 lg:px-6 lg:py-6">
                <x-logo size="text-xl" />
                <span class="rounded-full border border-sun/40 px-2.5 py-0.5 text-[10px] font-bold uppercase tracking-widest text-sun">Admin</span>
            </div>

            <nav class="flex gap-1 overflow-x-auto px-3 pb-3 lg:flex-1 lg:flex-col lg:overflow-visible lg:pb-0" aria-label="Admin">
                @foreach ($nav as [$label, $href, $active])
                    <a href="{{ $href }}" @if ($active) aria-current="page" @endif
                       class="whitespace-nowrap rounded-xl px-4 py-2.5 text-sm font-semibold transition focus:outline-none focus-visible:ring-2 focus-visible:ring-electric {{ $active ? 'bg-tangerine text-midnight' : 'text-cream/70 hover:bg-cream/5 hover:text-cream' }}">
                        {{ $label }}
                    </a>
                @endforeach
            </nav>

            <div class="hidden border-t border-cream/10 p-4 text-sm lg:block">
                <a href="{{ route('home') }}" class="block rounded-lg px-3 py-2 text-cream/70 hover:bg-cream/5 hover:text-cream">&larr; View site</a>
                <p class="mt-3 truncate px-3 text-xs text-cream/45">{{ auth()->user()->email }}</p>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button class="mt-1 w-full rounded-lg px-3 py-2 text-left text-cream/70 hover:bg-cream/5 hover:text-tangerine">Log out</button>
                </form>
            </div>
        </aside>

        <div class="min-w-0 flex-1">
            <main class="mx-auto max-w-6xl px-4 py-8 sm:px-6 lg:px-10 lg:py-10">
                <div class="flex flex-wrap items-center justify-between gap-4">
                    <h1 class="text-3xl font-extrabold tracking-tight text-cream">{{ $title }}</h1>
                    @isset($actions)<div class="flex flex-wrap items-center gap-3">{{ $actions }}</div>@endisset
                </div>


                <div class="mt-8">{{ $slot }}</div>
                <x-toast top="top-4" />
            </main>
        </div>
    </body>
</html>
