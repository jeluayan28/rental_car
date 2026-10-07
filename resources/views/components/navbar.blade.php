@php
    $links = [
        ['label' => 'Cars', 'href' => route('cars.index'), 'active' => request()->routeIs('cars.*')],
        ['label' => 'How It Works', 'href' => route('home').'#how-it-works', 'active' => false],
        ['label' => 'About', 'href' => route('home').'#about', 'active' => false],
    ];
@endphp

{{-- Fixed and transparent over the hero; gains a midnight backdrop once the page scrolls. --}}
<header x-data="{ open: false, scrolled: window.scrollY > 8 }"
        x-on:scroll.window.passive="scrolled = window.scrollY > 8"
        class="fixed inset-x-0 top-0 z-50 transition-colors duration-300"
        :class="scrolled || open ? 'bg-midnight/85 backdrop-blur-md border-b border-cream/10' : 'bg-transparent border-b border-transparent'">
    <div class="mx-auto flex h-16 max-w-7xl items-center justify-between px-4 sm:px-6 lg:px-8">
        <x-logo />

        <nav class="hidden items-center gap-8 md:flex" aria-label="Primary">
            @foreach ($links as $link)
                <x-nav.link :href="$link['href']" :active="$link['active']">{{ $link['label'] }}</x-nav.link>
            @endforeach
        </nav>

        <div class="hidden items-center gap-2 md:flex">
            @auth
                @if (auth()->user()->isAdmin())<x-nav.link :href="route('admin.dashboard')" :active="request()->routeIs('admin.*')" class="mr-4">Admin</x-nav.link>@endif
                <x-nav.link :href="route('dashboard')" :active="request()->routeIs('dashboard')" class="mr-4">Dashboard</x-nav.link>
                <x-nav.link :href="route('profile.edit')" :active="request()->routeIs('profile.*')" class="mr-4">Profile</x-nav.link>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="rounded-full border border-cream/30 px-5 py-2 text-sm font-semibold text-cream transition hover:border-sun hover:text-sun">Logout</button>
                </form>
            @else
                <x-button-link :href="route('login')">Log in</x-button-link>
                <x-button-link :href="route('register')" variant="primary">Sign up</x-button-link>
            @endauth
        </div>

        <button type="button" x-on:click="open = !open" class="rounded-md p-2 text-cream md:hidden focus:outline-none focus-visible:ring-2 focus-visible:ring-electric"
                aria-label="Toggle menu" :aria-expanded="open">
            <svg x-show="!open" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" d="M4 7h16M4 12h16M4 17h16"/></svg>
            <svg x-show="open" x-cloak class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" d="M6 6l12 12M18 6L6 18"/></svg>
        </button>
    </div>

    <div x-show="open" x-cloak x-transition:enter="transition duration-200 ease-out" x-transition:enter-start="opacity-0 -translate-y-2" x-transition:enter-end="opacity-100 translate-y-0" x-transition:leave="transition duration-150 ease-in" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0 -translate-y-2" x-on:keydown.escape.window="open = false" class="border-t border-cream/10 md:hidden">
        <div class="space-y-1 px-4 py-4">
            @foreach ($links as $link)
                <x-nav.link :href="$link['href']" :active="$link['active']" class="block py-2 text-base">{{ $link['label'] }}</x-nav.link>
            @endforeach

            <div class="mt-4 flex flex-col gap-2 border-t border-cream/10 pt-4">
                @auth
                    @if (auth()->user()->isAdmin())<x-nav.link :href="route('admin.dashboard')" class="block py-2 text-base">Admin</x-nav.link>@endif
                    <x-nav.link :href="route('dashboard')" class="block py-2 text-base">Dashboard</x-nav.link>
                    <x-nav.link :href="route('profile.edit')" class="block py-2 text-base">Profile</x-nav.link>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="py-2 text-base font-medium text-cream/80 hover:text-sun">Logout</button>
                    </form>
                @else
                    <x-button-link :href="route('login')">Log in</x-button-link>
                    <x-button-link :href="route('register')" variant="primary">Sign up</x-button-link>
                @endauth
            </div>
        </div>
    </div>
</header>
