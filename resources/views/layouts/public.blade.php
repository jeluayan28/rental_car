<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="theme-color" content="#0B1020">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>{{ isset($title) ? $title.' · ' : '' }}{{ config('app.name') }}{{ isset($title) ? '' : ' — Car Rental & Adventure' }}</title>
        <meta name="description" content="{{ $description ?? 'ROAMR is a car rental and adventure platform. Pick a ride, choose your dates and hit the road.' }}">
        <meta property="og:title" content="{{ isset($title) ? $title.' · ' : '' }}{{ config('app.name') }}">
        <meta property="og:description" content="{{ $description ?? 'Pick a ride. Make it yours.' }}">
        <meta property="og:type" content="website">
        <link rel="icon" href="{{ asset('favicon.svg') }}" type="image/svg+xml">

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800&display=swap" rel="stylesheet" />

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="flex min-h-screen flex-col bg-midnight font-sans text-cream antialiased selection:bg-tangerine selection:text-midnight">
        <a href="#main" class="sr-only z-[70] rounded-full bg-tangerine px-4 py-2 font-bold text-midnight focus:not-sr-only focus:fixed focus:left-4 focus:top-4">Skip to content</a>

        <x-navbar />
        <x-toast />

        {{-- No top padding: pages decide, so a hero can sit underneath the transparent navbar. --}}
        <main id="main" class="flex-1">{{ $slot }}</main>

        <x-footer />
    </body>
</html>
