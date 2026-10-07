{{-- Authenticated account pages (e.g. profile) share the public ROAMR shell: dark navbar, footer, same palette. --}}
<x-public-layout title="Account">
    <section class="relative isolate overflow-hidden">
        <div class="pointer-events-none absolute inset-0 -z-10" aria-hidden="true">
            <div class="absolute -right-[10%] top-0 h-[26rem] w-[26rem] rounded-full bg-tangerine/15 blur-[130px]"></div>
            <div class="absolute -left-[10%] top-[40%] h-[24rem] w-[24rem] rounded-full bg-electric/10 blur-[130px]"></div>
        </div>

        <div class="mx-auto max-w-3xl px-4 pb-24 pt-32 sm:px-6 sm:pt-40 lg:px-8">
            @isset($header)
                <header>{{ $header }}</header>
            @endisset

            <div class="mt-10">{{ $slot }}</div>
        </div>
    </section>
</x-public-layout>
