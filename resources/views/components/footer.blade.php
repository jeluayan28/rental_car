<footer class="border-t border-cream/10 bg-midnight">
    <div class="mx-auto max-w-7xl px-4 py-14 sm:px-6 lg:px-8">
        <div class="grid gap-10 sm:grid-cols-2 lg:grid-cols-4">
            <div class="lg:col-span-2">
                <x-logo size="text-xl" />
                <p class="mt-4 max-w-sm text-sm leading-relaxed text-cream/60">Car rental for people who&rsquo;d rather be going somewhere. Pick a ride, pick your dates and hit the road.</p>
            </div>

            <nav aria-label="Explore">
                <p class="text-[11px] font-semibold uppercase tracking-[0.25em] text-cream/45">Explore</p>
                <ul class="mt-4 space-y-2.5 text-sm">
                    <li><a href="{{ route('cars.index') }}" class="text-cream/70 transition hover:text-sun">All cars</a></li>
                    <li><a href="{{ route('home') }}#how-it-works" class="text-cream/70 transition hover:text-sun">How it works</a></li>
                    <li><a href="{{ route('home') }}#about" class="text-cream/70 transition hover:text-sun">About</a></li>
                </ul>
            </nav>

            <nav aria-label="Account">
                <p class="text-[11px] font-semibold uppercase tracking-[0.25em] text-cream/45">Account</p>
                <ul class="mt-4 space-y-2.5 text-sm">
                    @auth
                        <li><a href="{{ route('dashboard') }}" class="text-cream/70 transition hover:text-sun">Dashboard</a></li>
                        <li><a href="{{ route('bookings.index') }}" class="text-cream/70 transition hover:text-sun">My bookings</a></li>
                        <li><a href="{{ route('profile.edit') }}" class="text-cream/70 transition hover:text-sun">Profile</a></li>
                    @else
                        <li><a href="{{ route('login') }}" class="text-cream/70 transition hover:text-sun">Log in</a></li>
                        <li><a href="{{ route('register') }}" class="text-cream/70 transition hover:text-sun">Sign up</a></li>
                    @endauth
                </ul>
            </nav>
        </div>

        <div class="mt-12 flex flex-col gap-2 border-t border-cream/10 pt-6 text-xs text-cream/45 sm:flex-row sm:justify-between">
            <p>&copy; {{ date('Y') }} ROAMR. All rights reserved.</p>
            <p>Prices in Philippine pesos (&#8369;) per day.</p>
        </div>
    </div>
</footer>
