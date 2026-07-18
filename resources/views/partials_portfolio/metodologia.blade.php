<section class="section">
    <div class="container">
        <div class="section-header center">
            <div class="eyebrow reveal reveal-down" style="--i: 0">
                Metodología
            </div>

            <h2 class="section-title reveal reveal-up" style="--i: 1">
                Del concepto inicial a una
                <span>solución real.</span>
            </h2>
        </div>

        {{--
            Arcada: los 4 arcos ojivales se dibujan escalonados cuando la
            grilla entra en pantalla (.draw-in-view lo activa desde app.js).
        --}}
        <div class="process-grid draw-in-view reveal reveal-up" style="--i: 2">
            @foreach ($process as $step)
                <article class="process-card">
                    @include('partials_portfolio.arco_gotico', [
                        'delay' => $loop->index * 0.22,
                    ])

                    <div class="process-number">{{ $step['number'] }}</div>
                    <h3 class="process-title">{{ $step['title'] }}</h3>
                    <p class="process-text">{{ $step['text'] }}</p>
                </article>
            @endforeach
        </div>
    </div>
</section>
