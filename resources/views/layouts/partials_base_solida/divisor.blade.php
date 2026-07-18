{{--
    Ornamento divisor gótico (skill: svg-draw-in).
    Rombo + cruz al centro y dos líneas que se dibujan hacia afuera: los path
    arrancan en el centro (por eso van "al revés", de 136 a 8 y de 184 a 312),
    porque stroke-dashoffset dibuja desde el inicio del trazo.

    Se dibuja al entrar en pantalla gracias a .draw-in-view (app.js).
--}}
<div class="ornament" aria-hidden="true">
    <svg class="ornament-svg draw-in-view" viewBox="0 0 320 40" fill="none" focusable="false">
        {{-- Rombo central --}}
        <path
            class="draw ornament-frame"
            style="--d: 0s"
            d="M160 4 L176 20 L160 36 L144 20 Z"
            pathLength="1"
        />

        {{-- Líneas: del centro hacia afuera --}}
        <path class="draw ornament-line" style="--d: .3s" d="M136 20 H8" pathLength="1" />
        <path class="draw ornament-line" style="--d: .3s" d="M184 20 H312" pathLength="1" />

        {{-- Cruz interior --}}
        <path class="draw ornament-cross" style="--d: .65s" d="M160 11 V29" pathLength="1" />
        <path class="draw ornament-cross" style="--d: .78s" d="M152 20 H168" pathLength="1" />
    </svg>
</div>
