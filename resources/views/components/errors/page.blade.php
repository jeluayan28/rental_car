@props(['code', 'title', 'message'])

{{-- Standalone (no session, DB or auth calls) so it still renders when something is badly broken. --}}
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="robots" content="noindex">
        <meta name="theme-color" content="#0B1020">
        <title>{{ $title }} · {{ config('app.name') }}</title>
        <link rel="icon" href="{{ asset('favicon.svg') }}" type="image/svg+xml">
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800&display=swap" rel="stylesheet" />
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="grain relative isolate flex min-h-screen flex-col overflow-hidden bg-midnight font-sans text-cream antialiased">
        <div class="pointer-events-none absolute inset-0 -z-10" aria-hidden="true">
            <div class="absolute -right-[10%] top-[10%] h-[30rem] w-[30rem] rounded-full bg-tangerine/20 blur-[130px]"></div>
            <div class="absolute -left-[10%] bottom-[10%] h-[26rem] w-[26rem] rounded-full bg-electric/10 blur-[130px]"></div>
        </div>

        <header class="mx-auto w-full max-w-7xl px-4 py-6 sm:px-6 lg:px-8">
            <a href="{{ url('/') }}" class="text-2xl font-extrabold tracking-[0.2em]">ROAM<span class="text-tangerine">R</span></a>
        </header>

        <main class="mx-auto flex w-full max-w-7xl flex-1 flex-col justify-center px-4 pb-24 sm:px-6 lg:px-8">
            <p class="flex items-center gap-3 text-xs font-semibold uppercase tracking-[0.3em] text-sun"><span class="h-px w-10 bg-sun"></span> Error {{ $code }}</p>
            <p class="mt-4 bg-gradient-to-r from-tangerine to-sun bg-clip-text text-[clamp(7rem,26vw,18rem)] font-extrabold leading-[0.85] tracking-tight text-transparent" aria-hidden="true">{{ $code }}</p>
            <h1 class="mt-6 text-[clamp(2rem,5vw,3.5rem)] font-extrabold uppercase leading-none tracking-tight">{{ $title }}</h1>
            <p class="mt-4 max-w-lg text-lg text-cream/65">{{ $message }}</p>

            <div class="mt-10 flex flex-wrap gap-4">
                <a href="{{ url('/') }}" class="group inline-flex items-center gap-2 rounded-full bg-tangerine px-7 py-3.5 font-bold text-midnight shadow-[0_10px_40px_-10px_rgba(255,107,53,.8)] transition hover:bg-sun">
                    Back to home <span class="transition-transform group-hover:translate-x-1" aria-hidden="true">&rarr;</span>
                </a>
                <a href="{{ url('/cars') }}" class="inline-flex items-center rounded-full border border-cream/30 px-7 py-3.5 font-semibold transition hover:border-electric hover:text-electric">Browse cars</a>
            </div>
        </main>
    </body>
</html>
