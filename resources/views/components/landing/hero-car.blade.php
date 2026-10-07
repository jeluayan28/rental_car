{{-- Stylised side-view coupe, facing left (driving into the page). Pure SVG: no image request, scales crisply. --}}
<svg {{ $attributes->merge(['viewBox' => '0 0 900 360', 'class' => 'overflow-visible']) }} role="img" aria-label="Orange sports coupe driving into the page">
    <defs>
        <linearGradient id="car-body" x1="0" y1="0" x2="0" y2="1">
            <stop offset="0" stop-color="#FF8F5E"/>
            <stop offset=".45" stop-color="#FF6B35"/>
            <stop offset="1" stop-color="#B63A12"/>
        </linearGradient>
        <linearGradient id="car-glass" x1="0" y1="0" x2="1" y2="1">
            <stop offset="0" stop-color="#34456F"/>
            <stop offset="1" stop-color="#0E152B"/>
        </linearGradient>
        <radialGradient id="car-rim" cx=".4" cy=".35" r=".8">
            <stop offset="0" stop-color="#F4F1DE"/>
            <stop offset="1" stop-color="#7C8299"/>
        </radialGradient>
        <linearGradient id="car-beam" x1="1" y1="0" x2="0" y2="0">
            <stop offset="0" stop-color="#FFC857" stop-opacity=".4"/>
            <stop offset="1" stop-color="#FFC857" stop-opacity="0"/>
        </linearGradient>
        <filter id="car-blur" x="-20%" y="-50%" width="140%" height="200%"><feGaussianBlur stdDeviation="10"/></filter>
        <clipPath id="car-clip"><path id="car-shape" d="M44 284 L40 244 C44 222 90 206 160 196 L300 180 C340 150 395 112 445 106 L592 100 C652 100 702 132 748 170 L826 190 C858 198 868 222 868 252 L864 284 Z"/></clipPath>
    </defs>

    {{-- Ground shadow and electric underglow --}}
    <ellipse cx="450" cy="328" rx="420" ry="14" fill="#000" opacity=".55" filter="url(#car-blur)"/>
    <ellipse cx="450" cy="320" rx="340" ry="9" fill="#2EC4FF" opacity=".5" filter="url(#car-blur)"/>

    {{-- Headlight beam --}}
    <path d="M50 232 L-300 170 L-300 300 L50 252 Z" fill="url(#car-beam)"/>

    {{-- Body --}}
    <use href="#car-shape" fill="url(#car-body)"/>
    <g clip-path="url(#car-clip)">
        <path d="M60 272 L860 272 L870 290 L40 290 Z" fill="#1B0A04" opacity=".5"/>
        <circle cx="210" cy="262" r="68" fill="#05070F"/>
        <circle cx="700" cy="262" r="68" fill="#05070F"/>
    </g>
    <path d="M80 240 C300 224 600 222 852 238" fill="none" stroke="#F4F1DE" stroke-opacity=".28" stroke-width="2"/>
    <path d="M300 180 C340 150 395 112 445 106 L592 100 C652 100 702 132 748 170" fill="none" stroke="#F4F1DE" stroke-opacity=".4" stroke-width="2"/>

    {{-- Glass --}}
    <path d="M345 174 C372 146 405 124 445 118 L585 113 C640 114 684 140 724 170 Z" fill="url(#car-glass)"/>
    <path d="M522 114 L532 172" stroke="#E2562A" stroke-width="9"/>
    <path d="M360 165 C385 142 415 128 445 124 L470 123 L420 168 Z" fill="#F4F1DE" opacity=".12"/>
    <path d="M520 112 L530 266" stroke="#7A2509" stroke-opacity=".5" stroke-width="2"/>

    {{-- Spoiler, lights --}}
    <path d="M800 186 L852 174 L858 181 L806 196 Z" fill="#14192B"/>
    <path d="M850 208 L867 216 L867 234 L846 228 Z" fill="#FF3D2E"/>
    <ellipse cx="60" cy="238" rx="28" ry="12" fill="#FFC857" opacity=".7" filter="url(#car-blur)"/>
    <path d="M46 240 C70 227 110 221 152 219 L152 231 C112 233 76 241 46 252 Z" fill="#FFC857"/>

    {{-- Wheels --}}
    @foreach ([210, 700] as $cx)
        <g transform="translate({{ $cx }} 262)">
            <circle r="58" fill="#090C17" stroke="#1B2236" stroke-width="3"/>
            <circle r="44" fill="url(#car-rim)"/>
            <g class="wheel-spin">
                @foreach ([0, 72, 144, 216, 288] as $deg)
                    <line x1="0" y1="0" x2="0" y2="-40" stroke="#0B1020" stroke-width="8" stroke-linecap="round" transform="rotate({{ $deg }})"/>
                @endforeach
            </g>
            <circle r="13" fill="#0B1020"/>
            <circle r="5" fill="#FFC857"/>
        </g>
    @endforeach
</svg>
