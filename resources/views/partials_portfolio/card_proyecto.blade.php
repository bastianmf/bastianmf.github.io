{{--
    Card de proyecto con pestaña recortada (skill: notch-card).

    Espera:
    $project = [numeral, category, image, kicker, title, description, tags[], url]
    $index   = posición en el listado (para la dirección de entrada)

    Ojo: el recorte funciona porque la sección usa .section-solid y el
    --bleed de .project-card es exactamente ese color de fondo.
--}}
@php
    // La grilla repite cada 3: media / media / ancha. Debe seguir al
    // `.project-card:nth-child(3n)` de portfolio.css, que hace ancha la 3ª.
    // Cada card entra desde el lado que ocupa; la ancha, desde abajo.
    $slot = $index % 3;

    $direction = match ($slot) {
        0 => 'reveal-left',
        1 => 'reveal-right',
        default => 'reveal-up',
    };
@endphp

<article
    class="project-card reveal {{ $direction }}"
    style="--i: {{ $slot === 1 ? 1 : 0 }}"
    data-category="{{ $project['category'] }}"
>
    @php
        // Portada = primera IMAGEN de la galería (los ítems de video no sirven
        // de portada; la galería puede empezar con videos).
        $cover = collect($project['gallery'])->firstWhere('img', '!=', null)['img'] ?? null;
    @endphp

    <div class="project-image {{ $project['image'] }}">
        @if ($cover)
            <img
                class="project-shot"
                src="{{ asset($cover) }}"
                alt="Captura de {{ $project['title'] }}"
                loading="lazy"
            >
        @else
            @include('partials_portfolio.mock_browser')
        @endif
    </div>

    <div class="project-overlay"></div>

    {{-- La pestaña es del color del fondo: "recorta" la esquina de la card --}}
    <div class="project-tab">
        <span class="project-pill">
            Obra
            <span class="project-numeral">{{ $project['numeral'] }}</span>
        </span>
    </div>

    <div class="project-content">
        <div class="project-category">{{ $project['kicker'] }}</div>

        <h3 class="project-title">{{ $project['title'] }}</h3>

        <p class="project-description">{{ $project['description'] }}</p>

        <div class="project-footer">
            <div class="project-tags">
                @foreach ($project['tags'] as $tag)
                    <span class="project-tag">{{ $tag }}</span>
                @endforeach
            </div>

            {{-- Abre el panel de detalle (partials_portfolio/detalle_proyecto) --}}
            <button
                type="button"
                class="project-link"
                data-project-open="{{ $project['numeral'] }}"
                aria-haspopup="dialog"
                aria-label="Ver detalle de {{ $project['title'] }}"
            >
                ↗
            </button>
        </div>
    </div>
</article>
