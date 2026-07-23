{{--
    Panel de detalle a pantalla completa que baja al pulsar una card. Es un
    "case study" scrolleable con los 3 efectos de PagREF, todos ligados al
    scroll del propio panel (los maneja setupProjectDetail en portfolio.js):

    · Fold Scroll Video  → el video del hero se EXPANDE de tarjeta a full-bleed
                           mientras se scrollea (variable --fold, hero sticky).
    · Side Scroll Reveal → en el recorrido, cada pantalla y su texto entran
                           desde lados opuestos, alternando.
    · Spiral Motion      → los anillos de los numerales giran con el scroll.

    La clave que une card ↔ case study es el numeral de la obra.
--}}
<div class="gp-detail" id="projectDetail" aria-hidden="true">
    <div
        class="gp-detail-panel"
        role="dialog"
        aria-modal="true"
        aria-label="Detalle del proyecto"
    >
        <div class="gp-detail-top">
            <span class="gp-detail-brand">Obras · Bastián</span>

            <button
                class="gp-detail-close"
                type="button"
                id="projectDetailClose"
                aria-label="Cerrar detalle"
            >
                <span></span>
                <span></span>
            </button>
        </div>

        <div class="gp-detail-progress" aria-hidden="true">
            <span class="gp-detail-progress-track">
                <span class="gp-detail-progress-fill"></span>
            </span>
            <span class="gp-detail-progress-value" data-detail-progress>00</span>
        </div>

        <div class="gp-detail-scroll" id="projectDetailScroll">
            @foreach ($projects as $project)
                @php
                    $items = $project['gallery'] ?? [];
                    $hero = $items[0] ?? null;
                    $rest = array_slice($items, 1);
                @endphp

                <article
                    class="gp-case"
                    data-project-case="{{ $project['numeral'] }}"
                >
                    {{-- ---- Hero con fold/expand ---- --}}
                    <header class="gp-case-hero" data-fold-hero>
                        <div class="gp-case-folio" aria-hidden="true">
                            <span>Case study</span>
                            <span>Obra {{ $project['numeral'] }}</span>
                        </div>

                        <div class="gp-case-hero-head">
                            <p class="gp-eyebrow">
                                Obra {{ $project['numeral'] }} · {{ $project['kicker'] }}
                            </p>

                            <h2 class="gp-case-title">{{ $project['title'] }}</h2>

                            <p class="gp-case-lead">{{ $project['description'] }}</p>

                            <span class="gp-scroll-hint" aria-hidden="true">Scrollea ↓</span>
                        </div>

                        @if ($hero)
                            <figure class="gp-case-hero-media" data-fold>
                                @include('partials_portfolio.gp_media', ['item' => $hero, 'featured' => true])

                                {{-- Aparecen al expandir --}}
                                <span class="gp-fold-corner tl"></span>
                                <span class="gp-fold-corner tr"></span>
                                <span class="gp-fold-corner bl"></span>
                                <span class="gp-fold-corner br"></span>
                                <span class="gp-fold-label gp-fold-label-l" aria-hidden="true">Grabado en vivo</span>
                                <span class="gp-fold-label gp-fold-label-r" aria-hidden="true">Cursor · clics · flujo real</span>

                                <figcaption class="gp-hero-caption">
                                    <span class="gp-media-caption-title">{{ $hero['title'] }}</span>
                                    <span class="gp-media-caption-text">{{ $hero['caption'] }}</span>
                                </figcaption>
                            </figure>
                        @endif
                    </header>

                    {{-- ---- Sobre el proyecto + ficha ---- --}}
                    <section class="gp-case-about">
                        <div class="gp-case-about-text gp-reveal" data-reveal="left">
                            <p class="gp-section-tag">— El proyecto</p>
                            <p class="gp-case-summary">{{ $project['summary'] }}</p>
                        </div>

                        <dl class="gp-case-facts gp-reveal" data-reveal="right">
                            @foreach ($project['facts'] as $fact)
                                <div>
                                    <dt>{{ $fact['k'] }}</dt>
                                    <dd>{{ $fact['v'] }}</dd>
                                </div>
                            @endforeach
                        </dl>
                    </section>

                    {{-- ---- Recorrido: side-scroll reveal alternado ---- --}}
                    @if (count($rest))
                        <section class="gp-case-tour">
                            <p class="gp-section-tag gp-reveal">— Recorrido</p>

                            <div class="gp-steps">
                                @foreach ($rest as $item)
                                    @php $side = $loop->index % 2 === 0 ? 'left' : 'right'; @endphp
                                    <div class="gp-step gp-step-{{ $side }}" data-case-step>
                                        <figure class="gp-step-media gp-reveal" data-reveal="{{ $side }}">
                                            @include('partials_portfolio.gp_media', ['item' => $item])
                                        </figure>

                                        <div class="gp-step-copy gp-reveal" data-reveal="{{ $side === 'left' ? 'right' : 'left' }}">
                                            <span
                                                class="gp-step-index-orbit"
                                                data-spin="{{ $loop->iteration % 2 === 0 ? '-1' : '1' }}"
                                                aria-hidden="true"
                                            >
                                                <span class="gp-step-index">
                                                    {{ str_pad($loop->iteration, 2, '0', STR_PAD_LEFT) }}
                                                </span>
                                            </span>
                                            <h3 class="gp-step-title">{{ $item['title'] }}</h3>
                                            <p class="gp-step-text">{{ $item['caption'] }}</p>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </section>
                    @endif

                    {{-- ---- Stack ---- --}}
                    <section class="gp-case-stack gp-reveal">
                        <p class="gp-section-tag">— Stack</p>
                        <div class="gp-case-tags">
                            @foreach ($project['tags'] as $tag)
                                <span class="gp-case-tag">{{ $tag }}</span>
                            @endforeach
                        </div>
                    </section>

                    {{-- ---- Cierre ---- --}}
                    <section class="gp-case-cta gp-reveal">
                        <p class="gp-cta-kicker">¿Te gustó este proyecto?</p>
                        <h3 class="gp-cta-title">Construyamos el <span>tuyo</span>.</h3>
                        <div class="gp-cta-actions">
                            <a href="#contacto" class="gp-cta-btn gp-cta-btn-primary" data-detail-close>
                                Trabajemos juntos
                            </a>
                            <a href="#proyectos" class="gp-cta-btn" data-detail-close>
                                Ver más obras
                            </a>
                        </div>
                    </section>
                </article>
            @endforeach
        </div>
    </div>
</div>
