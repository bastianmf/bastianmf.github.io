<section class="section section-dark" id="habilidades">
    <div class="container">
        <div class="skills-layout">
            <div class="skills-intro">
                {{-- Columna izquierda: entra desde la izquierda --}}
                <div class="section-header">
                    <div class="eyebrow reveal reveal-left" style="--i: 0">
                        Habilidades
                    </div>

                    <h2 class="section-title reveal reveal-left" style="--i: 1">
                        Las herramientas detrás de
                        <span>cada creación.</span>
                    </h2>

                    <p class="section-description reveal reveal-left" style="--i: 2">
                        Combino desarrollo, diseño, arquitectura de sistemas
                        e integración de servicios para construir productos
                        digitales completos.
                    </p>
                </div>
            </div>

            {{--
                Lista que se "forma" al entrar en pantalla (skill: svg-draw-in).
                Cada separador se dibuja con scaleX y el texto entra escalonado;
                --i es el índice que escalona la cascada.
            --}}
            <div class="skills-list draw-in-view">
                @foreach ($skills as [$index, $name, $tools])
                    <div class="skill-item draw-in-view" style="--i: {{ $loop->index }}">
                        <div class="skill-index">{{ $index }}</div>
                        <div class="skill-name">{{ $name }}</div>
                        <div class="skill-tools">{{ $tools }}</div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</section>
