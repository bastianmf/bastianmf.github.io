{{--
    Rosetón gótico (rose window) que se dibuja al entrar en pantalla.
    Skill: svg-draw-in — cada trazo lleva pathLength="1" y se escalona con --d.

    Geometría: círculo exterior (r=92), anillo interior (r=74), 8 radios
    (r=32 → r=74), 8 lóbulos en el anillo (r=8 sobre un radio de 83) y el
    medallón central (r=32). Centro en (100,100).
--}}
<svg
    class="about-rose draw-in-view"
    viewBox="0 0 200 200"
    fill="none"
    aria-hidden="true"
    focusable="false"
>
    {{-- Anillos --}}
    <circle class="draw rose-mid" style="--d: 0s" cx="100" cy="100" r="92" pathLength="1" />
    <circle class="draw rose-thin" style="--d: .25s" cx="100" cy="100" r="74" pathLength="1" />

    {{-- Radios --}}
    <path class="draw rose-thin" style="--d: .45s" d="M132 100 H174" pathLength="1" />
    <path class="draw rose-thin" style="--d: .5s" d="M122.6 77.4 L152.3 47.7" pathLength="1" />
    <path class="draw rose-thin" style="--d: .55s" d="M100 68 V26" pathLength="1" />
    <path class="draw rose-thin" style="--d: .6s" d="M77.4 77.4 L47.7 47.7" pathLength="1" />
    <path class="draw rose-thin" style="--d: .65s" d="M68 100 H26" pathLength="1" />
    <path class="draw rose-thin" style="--d: .7s" d="M77.4 122.6 L47.7 152.3" pathLength="1" />
    <path class="draw rose-thin" style="--d: .75s" d="M100 132 V174" pathLength="1" />
    <path class="draw rose-thin" style="--d: .8s" d="M122.6 122.6 L152.3 152.3" pathLength="1" />

    {{-- Lóbulos --}}
    <circle class="draw rose-thin" style="--d: .85s" cx="183" cy="100" r="8" pathLength="1" />
    <circle class="draw rose-thin" style="--d: .89s" cx="158.7" cy="41.3" r="8" pathLength="1" />
    <circle class="draw rose-thin" style="--d: .93s" cx="100" cy="17" r="8" pathLength="1" />
    <circle class="draw rose-thin" style="--d: .97s" cx="41.3" cy="41.3" r="8" pathLength="1" />
    <circle class="draw rose-thin" style="--d: 1.01s" cx="17" cy="100" r="8" pathLength="1" />
    <circle class="draw rose-thin" style="--d: 1.05s" cx="41.3" cy="158.7" r="8" pathLength="1" />
    <circle class="draw rose-thin" style="--d: 1.09s" cx="100" cy="183" r="8" pathLength="1" />
    <circle class="draw rose-thin" style="--d: 1.13s" cx="158.7" cy="158.7" r="8" pathLength="1" />

    {{-- Medallón central: enmarca el monograma --}}
    <circle class="draw rose-bright" style="--d: 1.2s" cx="100" cy="100" r="32" pathLength="1" />
</svg>
