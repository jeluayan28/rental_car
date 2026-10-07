<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="theme-color" content="#0B1020">
        <title>{{ isset($title) ? $title.' · ' : '' }}{{ config('app.name') }}</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800&display=swap" rel="stylesheet" />

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="flex min-h-screen flex-col bg-midnight font-sans text-cream antialiased selection:bg-tangerine selection:text-midnight">
        <x-navbar />

        {{-- No top padding: pages decide, so a hero can sit underneath the transparent navbar. --}}
        <main class="flex-1">{{ $slot }}</main>

        <x-footer />
    </body>
</html>
