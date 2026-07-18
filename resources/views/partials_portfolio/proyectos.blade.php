{{-- .section-solid: fondo plano, requisito del recorte de las cards --}}
<section class="section section-solid" id="proyectos">
    <div class="container">
        {{-- Encabezado centrado: entra desde abajo, en cascada --}}
        <div class="section-header center">
            <div class="eyebrow reveal reveal-down" style="--i: 0">
                Obras seleccionadas
            </div>

            <h2 class="section-title reveal reveal-up" style="--i: 1">
                Proyectos que transforman
                <span>ideas en experiencias.</span>
            </h2>

            <p class="section-description reveal reveal-up" style="--i: 2">
                Una selección de plataformas, sistemas y propuestas visuales
                desarrolladas con foco en experiencia de usuario, identidad
                gráfica y calidad técnica.
            </p>
        </div>

        <div class="project-filters reveal reveal-up" style="--i: 3">
            @foreach ($filters as $filter)
                <button
                    type="button"
                    class="filter-button @if ($loop->first) active @endif"
                    data-filter="{{ $filter['key'] }}"
                    aria-pressed="{{ $loop->first ? 'true' : 'false' }}"
                >
                    {{ $filter['label'] }}
                </button>
            @endforeach
        </div>

        <div class="projects-grid">
            @foreach ($projects as $project)
                @include('partials_portfolio.card_proyecto', [
                    'project' => $project,
                    'index' => $loop->index,
                ])
            @endforeach
        </div>
    </div>

    {{-- Fuera del .container: el panel ocupa toda la pantalla --}}
    @include('partials_portfolio.detalle_proyecto')
</section>
