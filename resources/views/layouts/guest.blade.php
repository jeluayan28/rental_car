<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <meta name="theme-color" content="#0B1020">

        <title>{{ $attributes->get('title') ? $attributes->get('title').' · ' : '' }}{{ config('app.name') }}</title>

        <link rel="icon" href="{{ asset('favicon.svg') }}" type="image/svg+xml">
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800&display=swap" rel="stylesheet" />

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="bg-midnight font-sans text-cream antialiased selection:bg-tangerine selection:text-midnight">
        <div class="grid min-h-screen lg:grid-cols-2">

            {{-- Brand panel (desktop): the car pulling up to the road --}}
            <aside class="grain relative isolate hidden overflow-hidden border-r border-cream/10 lg:flex lg:flex-col lg:justify-between" aria-hidden="true">
                <div class="pointer-events-none absolute inset-0 -z-10">
                    <div class="absolute -right-[20%] top-[25%] h-[34rem] w-[34rem] rounded-full bg-tangerine/20 blur-[130px]"></div>
                    <div class="absolute -left-[15%] -top-[10%] h-[26rem] w-[26rem] rounded-full bg-electric/10 blur-[120px]"></div>
                </div>

                <div class="p-10 xl:p-14">
                    <x-logo />
                </div>

                <div class="px-10 xl:px-14">
                    <p class="flex items-center gap-3 text-xs font-semibold uppercase tracking-[0.3em] text-sun"><span class="h-px w-10 bg-sun"></span> Car rental &amp; adventure</p>
                    <p class="mt-5 text-[clamp(2.75rem,4.6vw,4.5rem)] font-extrabold uppercase leading-[0.92] tracking-tight">
                        The road<br>is <span class="text-tangerine">waiting.</span>
                    </p>
                </div>

                <div class="relative mt-10 pb-24">
                    <x-landing.hero-car :spin="true" class="car-in relative z-10 ml-auto w-[105%] max-w-none translate-x-[8%]" />
                    <div class="absolute inset-x-0 bottom-0 h-20 border-t border-cream/10 bg-gradient-to-b from-graphite to-midnight">
                        <div class="absolute left-0 top-1/2 h-[3px] w-[calc(100%+120px)] -translate-y-1/2 opacity-70"><div class="road-dashes h-full w-full"></div></div>
                    </div>
                </div>
            </aside>

            {{-- Form column --}}
            <main class="relative isolate flex flex-col justify-center px-4 py-10 sm:px-8 lg:px-14">
                <div class="pointer-events-none absolute inset-0 -z-10 lg:hidden" aria-hidden="true">
                    <div class="absolute -right-[20%] top-0 h-[22rem] w-[22rem] rounded-full bg-tangerine/15 blur-[110px]"></div>
                </div>

                <div class="mx-auto w-full max-w-md">
                    <x-logo class="mb-10 lg:hidden" />

                    @if ($attributes->get('title'))
                        <h1 class="text-4xl font-extrabold uppercase leading-none tracking-tight sm:text-5xl">{{ $attributes->get('title') }}</h1>
                    @endif
                    @if ($attributes->get('subtitle'))
                        <p class="mt-3 text-cream/65">{{ $attributes->get('subtitle') }}</p>
                    @endif

                    <div class="mt-8 rounded-3xl border border-cream/10 bg-graphite/70 p-6 shadow-[0_30px_80px_-30px_rgba(0,0,0,.8)] sm:p-8">
                        {{ $slot }}
                    </div>

                    <p class="mt-8 text-center text-sm"><a href="{{ route('home') }}" class="text-cream/55 transition hover:text-sun">&larr; Back to site</a></p>
                </div>
            </main>
        </div>
    </body>
</html>
