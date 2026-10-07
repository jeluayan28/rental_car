<section id="destinations" class="relative px-4 py-24 pl-14 sm:px-6 sm:pl-20 lg:px-8 lg:py-32 lg:pl-28">
    <x-landing.waypoint n="01" />

    <x-landing.section-heading step="01" label="Choose your destination">
        Where are you <span class="text-tangerine">heading?</span>
        <x-slot:intro>Every trip starts with a place. Pick one and we&rsquo;ll find the car that fits the road.</x-slot:intro>
    </x-landing.section-heading>

    <div class="mt-14 grid gap-5 sm:grid-cols-2 xl:grid-cols-4">
        <x-landing.destination-card type="city" name="City" accent="#2EC4FF" delay="0s"
            tagline="Tight streets, late-night eats and zero parking stress." trip="Metro Manila" />
        <x-landing.destination-card type="mountains" name="Mountains" accent="#FFC857" delay=".08s"
            tagline="Cool air, switchbacks and sunrise above the clouds." trip="Baguio · Sagada" />
        <x-landing.destination-card type="beach" name="Beach" accent="#2EC4FF" delay=".16s"
            tagline="Windows down, coast road, sand by noon." trip="La Union · Batangas" />
        <x-landing.destination-card type="weekend" name="Weekend Escape" accent="#FF6B35" delay=".24s"
            tagline="Two days. One full tank. No agenda." trip="Tagaytay · Subic" />
    </div>
</section>
