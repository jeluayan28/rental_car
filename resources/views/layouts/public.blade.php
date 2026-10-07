<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>{{ isset($title) ? $title.' · ' : '' }}{{ config('app.name') }}</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800&display=swap" rel="stylesheet" />

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased bg-white text-ink">
        <header class="border-b border-slate-200 bg-white/90 backdrop-blur sticky top-0 z-30">
            <div class="mx-auto flex h-16 max-w-7xl items-center justify-between px-4 sm:px-6 lg:px-8">
                <a href="{{ route('home') }}" class="text-2xl font-extrabold tracking-tight">ROAM<span class="text-brand-600">R</span></a>

                <nav class="hidden items-center gap-8 text-sm font-medium text-slate-600 sm:flex">
                    <a href="{{ route('home') }}" class="hover:text-ink">Home</a>
                    <a href="{{ route('cars.index') }}" class="hover:text-ink">Cars</a>
                    @auth
                        <a href="{{ route('dashboard') }}" class="hover:text-ink">My bookings</a>
                        <a href="{{ route('profile.edit') }}" class="hover:text-ink">Profile</a>
                    @endauth
                </nav>

                <div class="flex items-center gap-3 text-sm font-medium">
                    @auth
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button class="text-slate-600 hover:text-ink">Log out</button>
                        </form>
                    @else
                        <a href="{{ route('login') }}" class="text-slate-600 hover:text-ink">Log in</a>
                        <a href="{{ route('register') }}" class="rounded-full bg-brand-600 px-4 py-2 text-white hover:bg-brand-700">Sign up</a>
                    @endauth
                </div>
            </div>
        </header>

        <main>{{ $slot }}</main>

        <footer class="mt-24 border-t border-slate-200 bg-slate-50">
            <div class="mx-auto flex max-w-7xl flex-col gap-2 px-4 py-8 text-sm text-slate-500 sm:flex-row sm:justify-between sm:px-6 lg:px-8">
                <span class="font-semibold text-ink">ROAM<span class="text-brand-600">R</span> — Car Rental &amp; Adventure</span>
                <span>&copy; {{ date('Y') }} ROAMR. All rights reserved.</span>
            </div>
        </footer>
    </body>
</html>
