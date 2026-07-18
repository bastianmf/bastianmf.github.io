{{--
    Direcciones: la columna de texto entra desde la izquierda y la tarjeta
    desde la derecha, cada una desde el lado donde vive. --i escalona.
--}}
<section class="hero" id="inicio">
    <svg
        class="hero-constellation draw-in-view"
        viewBox="0 0 620 620"
        fill="none"
        aria-hidden="true"
        focusable="false"
    >
        <circle class="draw hc-thin" style="--d: 0s" cx="310" cy="310" r="238" pathLength="1" />
        <circle class="draw hc-thin" style="--d: .16s" cx="310" cy="310" r="164" pathLength="1" />
        <path class="draw hc-mid" style="--d: .32s" d="M310 72 L510 426 L110 426 Z" pathLength="1" />
        <path class="draw hc-mid" style="--d: .5s" d="M110 194 L510 194 L310 548 Z" pathLength="1" />
        <path class="draw hc-soft" style="--d: .68s" d="M310 72 L310 548 M72 310 L548 310" pathLength="1" />
        <circle class="draw hc-bright" style="--d: .86s" cx="310" cy="72" r="5" pathLength="1" />
        <circle class="draw hc-bright" style="--d: .96s" cx="510" cy="426" r="5" pathLength="1" />
        <circle class="draw hc-bright" style="--d: 1.06s" cx="110" cy="426" r="5" pathLength="1" />
        <circle class="draw hc-bright" style="--d: 1.16s" cx="110" cy="194" r="4" pathLength="1" />
        <circle class="draw hc-bright" style="--d: 1.26s" cx="510" cy="194" r="4" pathLength="1" />
        <circle class="draw hc-bright" style="--d: 1.36s" cx="310" cy="548" r="4" pathLength="1" />
    </svg>

    <div class="container">
        <div class="hero-grid">
            <div class="hero-content">
                <div class="hero-kicker reveal reveal-left" style="--i: 0">
                    Desarrollo web & diseño digital
                </div>

                <h1 class="hero-title reveal reveal-left" style="--i: 1">
                    Digital
                    <span class="editorial">Alchemy</span>
                </h1>

                <p class="hero-description reveal reveal-left" style="--i: 2">
                    Creo experiencias digitales con identidad, estrategia
                    y precisión técnica. Desarrollo sitios web y sistemas
                    que combinan una estética distintiva con soluciones
                    funcionales, escalables y orientadas a resultados.
                </p>

                <div class="hero-actions reveal reveal-left" style="--i: 3">
                    <a href="#proyectos" class="button button-primary">
                        Ver proyectos
                        <span class="button-arrow">→</span>
                    </a>

                    <a href="#contacto" class="button">
                        Trabajemos juntos
                    </a>
                </div>

                <div class="hero-meta reveal reveal-up" style="--i: 4">
                    <div class="hero-meta-item">
                        <span class="hero-meta-dot"></span>
                        Disponible para proyectos
                    </div>
                    <div class="hero-meta-item">Santiago, Chile</div>
                    <div class="hero-meta-item">Laravel · Frontend · UI</div>
                </div>
            </div>

            <div class="hero-card-wrapper reveal reveal-right" style="--i: 2">
                <span class="floating-symbol one">†</span>
                <span class="floating-symbol two">†</span>

                {{-- Sello a la izquierda, un poco afuera, flotando en loop --}}
                <img
                    class="hero-seal hero-seal-main"
                    src="{{ asset('images/perfil/simbolo-ritual-gold.png') }}"
                    alt=""
                    aria-hidden="true"
                >

                <img
                    class="hero-seal hero-seal-corner"
                    src="{{ asset('images/perfil/simbolo-ritual-arc-gold.png') }}"
                    alt=""
                    aria-hidden="true"
                >

                <div class="hero-card">
                    <div class="portrait">
                        <img
                            class="portrait-photo"
                            src="{{ asset('images/perfil/bastian.jpg') }}"
                            alt="Bastián Medina"
                        >
                        <div class="portrait-veil"></div>
                        <div class="portrait-lines"></div>

                        {{--
                            Trazo que se dibuja SOBRE la foto cuando la tarjeta
                            aparece (skill svg-draw-in). Lo dispara el fin de la
                            entrada de la tarjeta (setupPortraitDraw, portfolio.js).
                        --}}
                        <svg
                            class="portrait-draw"
                            viewBox="0 0 300 400"
                            fill="none"
                            aria-hidden="true"
                            focusable="false"
                        >
                            {{-- Esquinas ornamentales --}}
                            <path class="pd pd-mid" style="--d: 0s"   d="M14 54 L14 14 L54 14"    pathLength="1" />
                            <path class="pd pd-mid" style="--d: .1s"  d="M246 14 L286 14 L286 54" pathLength="1" />
                            <path class="pd pd-mid" style="--d: .2s"  d="M14 346 L14 386 L54 386" pathLength="1" />
                            <path class="pd pd-mid" style="--d: .3s"  d="M246 386 L286 386 L286 346" pathLength="1" />

                            {{-- Línea vertical con nodos, a la izquierda --}}
                            <path   class="pd pd-thin" style="--d: .5s"  d="M40 96 L40 300" pathLength="1" />
                            <circle class="pd pd-bright" style="--d: .9s"  cx="40" cy="116" r="3.5" pathLength="1" />
                            <circle class="pd pd-bright" style="--d: 1s"   cx="40" cy="200" r="3.5" pathLength="1" />
                            <circle class="pd pd-bright" style="--d: 1.1s" cx="40" cy="284" r="3.5" pathLength="1" />

                            {{-- Sigilo (círculo + cruz) que ecoa la intro, arriba-derecha --}}
                            <circle class="pd pd-mid"   style="--d: .7s"  cx="244" cy="86" r="20" pathLength="1" />
                            <path   class="pd pd-bright" style="--d: 1.1s" d="M244 72 L244 100" pathLength="1" />
                            <path   class="pd pd-bright" style="--d: 1.2s" d="M230 86 L258 86"  pathLength="1" />
                        </svg>

                        <div class="hero-card-info">
                            <div class="hero-card-name">
                                Bastián Medina
                            </div>
                            <div class="hero-card-role">
                                Full Stack Developer
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
