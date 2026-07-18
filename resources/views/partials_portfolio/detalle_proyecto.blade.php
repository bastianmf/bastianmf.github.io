{{--
    Panel de detalle que baja desde arriba al pulsar el botón de una card.

    Técnica adaptada de la vista /servicios de SoyTrabajador: overlay fijo con
    un panel en translateY(-100%) que entra, líneas que se dibujan con scaleX/
    scaleY y el texto que sube escalonado. Aquí va en paleta gótica (negro con
    cuadrícula dorada) en vez del papel crema del original.

    La clave que une cada botón con su panel es el numeral de la obra.
--}}
<div class="gp-detail" id="projectDetail" aria-hidden="true">
    <div
        class="gp-detail-panel"
        role="dialog"
        aria-modal="true"
        aria-label="Detalle del proyecto"
    >
        <button
            class="gp-detail-close"
            type="button"
            id="projectDetailClose"
            aria-label="Cerrar detalle"
        >
            <span></span>
            <span></span>
        </button>

        <div class="gp-detail-top">
            <span class="gp-detail-line gp-detail-line-top"></span>
            <span class="gp-detail-brand">Obras · Bastián</span>
        </div>

        <div class="gp-detail-middle">
            <span class="gp-detail-line gp-detail-line-mid"></span>

            <div class="gp-detail-left">
                @foreach ($projects as $project)
                    <article
                        class="gp-detail-copy"
                        data-project-detail="{{ $project['numeral'] }}"
                    >
                        <p class="gp-detail-eyebrow">
                            Obra {{ $project['numeral'] }} · {{ $project['kicker'] }}
                        </p>

                        <h2>{{ $project['title'] }}</h2>

                        <p class="gp-detail-summary">{{ $project['summary'] }}</p>

                        <dl class="gp-detail-facts">
                            @foreach ($project['facts'] as $fact)
                                <div>
                                    <dt>{{ $fact['k'] }}</dt>
                                    <dd>{{ $fact['v'] }}</dd>
                                </div>
                            @endforeach
                        </dl>
                    </article>
                @endforeach
            </div>

            {{--
                Galería tipo "image tabs": una imagen grande + un grid de
                miniaturas. Al pulsar una miniatura cambian la imagen y su
                texto (lo maneja setupProjectDetail en portfolio.js).
                La miniatura activa por defecto es la primera.
            --}}
            <div class="gp-detail-right">
                @foreach ($projects as $project)
                    <figure
                        class="gp-detail-gallery"
                        data-project-visual="{{ $project['numeral'] }}"
                    >
                        @php $first = $project['gallery'][0] ?? null; @endphp

                        <div class="gp-gallery-stage">
                            @if ($first)
                                <img
                                    class="gp-stage-img"
                                    src="{{ asset($first['img']) }}"
                                    alt="{{ $first['title'] }} — {{ $project['title'] }}"
                                    loading="lazy"
                                >
                            @endif
                        </div>

                        <figcaption class="gp-gallery-caption">
                            <span class="gp-cap-title">{{ $first['title'] ?? '' }}</span>
                            <span class="gp-cap-text">{{ $first['caption'] ?? '' }}</span>
                        </figcaption>

                        <div class="gp-gallery-thumbs" role="tablist">
                            @foreach ($project['gallery'] as $shot)
                                <button
                                    type="button"
                                    role="tab"
                                    class="gp-thumb @if ($loop->first) is-active @endif"
                                    aria-selected="{{ $loop->first ? 'true' : 'false' }}"
                                    data-src="{{ asset($shot['img']) }}"
                                    data-title="{{ $shot['title'] }}"
                                    data-caption="{{ $shot['caption'] }}"
                                    aria-label="{{ $shot['title'] }}"
                                >
                                    <img src="{{ asset($shot['img']) }}" alt="" loading="lazy">
                                </button>
                            @endforeach
                        </div>
                    </figure>
                @endforeach
            </div>
        </div>

        <div class="gp-detail-bottom">
            <span class="gp-detail-line gp-detail-line-bottom"></span>
            <a href="#contacto" data-detail-close>Trabajemos juntos</a>
            <a href="#proyectos" data-detail-close>Ver más obras</a>
        </div>
    </div>
</div>
