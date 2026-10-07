<x-app-layout>
    <x-slot name="header">
        <a href="{{ route('dashboard') }}" class="text-sm font-semibold text-cream/60 transition hover:text-sun">&larr; Dashboard</a>
        <h1 class="mt-6 text-[clamp(2.5rem,7vw,5rem)] font-extrabold uppercase leading-[0.95] tracking-tight text-cream">
            Your <span class="text-tangerine">profile.</span>
        </h1>
    </x-slot>

    <div class="space-y-6">
        <div class="rounded-3xl border border-cream/10 bg-graphite/70 p-6 sm:p-8">
            <div class="max-w-xl">
                @include('profile.partials.update-profile-information-form')
            </div>
        </div>

        <div class="rounded-3xl border border-cream/10 bg-graphite/70 p-6 sm:p-8">
            <div class="max-w-xl">
                @include('profile.partials.update-password-form')
            </div>
        </div>

        <div class="rounded-3xl border border-tangerine/30 bg-graphite/70 p-6 sm:p-8">
            <div class="max-w-xl">
                @include('profile.partials.delete-user-form')
            </div>
        </div>
    </div>
</x-app-layout>
