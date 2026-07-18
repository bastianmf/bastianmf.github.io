{{--
    Arco ojival que se dibuja al entrar en pantalla (skill: svg-draw-in).
    Cada trazo es continuo: sube por una pata, cruza el vértice y baja por la
    otra, así el arco se "levanta" de una sola pasada.

    Espera: $delay (segundos, para escalonar los 4 arcos de la arcada).
--}}
<svg
    class="process-arch"
    viewBox="0 0 100 150"
    fill="none"
    preserveAspectRatio="xMidYMax meet"
    aria-hidden="true"
    focusable="false"
>
    {{-- Arco exterior --}}
    <path
        class="draw arch-mid"
        style="--d: {{ $delay }}s"
        d="M10 150 V52 Q10 18 50 4 Q90 18 90 52 V150"
        pathLength="1"
    />

    {{-- Arco interior --}}
    <path
        class="draw arch-thin"
        style="--d: {{ $delay + 0.18 }}s"
        d="M24 150 V60 Q24 34 50 22 Q76 34 76 60 V150"
        pathLength="1"
    />
</svg>
