@props(['n', 'top' => 'top-[5.25rem] lg:top-[7.25rem]'])

{{-- A stop on the road. Lights up (tangerine) once the scroll "car" has driven past it. --}}
<div data-waypoint aria-hidden="true"
     {{ $attributes->class(['waypoint absolute left-6 z-10 flex h-11 w-11 -translate-x-1/2 items-center justify-center rounded-full text-xs font-extrabold tracking-wider sm:left-10 lg:left-12', $top]) }}>
    {{ $n }}
</div>
