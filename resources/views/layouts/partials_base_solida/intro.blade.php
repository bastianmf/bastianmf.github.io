{{--
    Intro / preloader gótico (skill: svg-draw-in).
    El sigilo se dibuja solo con stroke-dashoffset y se muestra una vez por
    sesión. Sin JS no se muestra nunca (ver .intro en app.css).
--}}
<div class="intro" id="intro" role="presentation" aria-hidden="true">
    <svg
        class="intro-sigil"
        viewBox="0 0 240 240"
        fill="none"
        focusable="false"
        aria-hidden="true"
    >
        {{-- Rombo exterior --}}
        <path
            class="draw draw-mid"
            style="--d: 0s"
            d="M120 18 L222 120 L120 222 L18 120 Z"
            pathLength="1"
        />

        {{-- Círculo interior (r < 72 para quedar dentro del rombo) --}}
        <circle
            class="draw draw-thin"
            style="--d: .35s"
            cx="120"
            cy="120"
            r="67"
            pathLength="1"
        />

        {{-- Arco ojival --}}
        <path
            class="draw draw-mid"
            style="--d: .6s"
            d="M88 172 L88 112 Q88 76 120 64 Q152 76 152 112 L152 172"
            pathLength="1"
        />

        {{-- Cruz --}}
        <path
            class="draw draw-bright"
            style="--d: 1s"
            d="M120 92 L120 164"
            pathLength="1"
        />
        <path
            class="draw draw-bright"
            style="--d: 1.2s"
            d="M100 116 L140 116"
            pathLength="1"
        />
    </svg>

    <div>
        <div class="intro-name">Bastián</div>
        <div class="intro-role">Full Stack Developer</div>
    </div>
</div>
