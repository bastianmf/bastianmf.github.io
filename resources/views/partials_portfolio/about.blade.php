{{-- Visual desde la izquierda, texto desde la derecha: cada uno desde su lado --}}
<section class="section section-dark" id="sobre-mi">
    <div class="container">
        <div class="about-grid">
            <div class="about-visual reveal reveal-left">
                <div class="about-frame">
                    @include('partials_portfolio.roseton')
                    <div class="about-monogram">
                        <img
                            src="{{ asset('images/perfil/logo-bastian-medina.png') }}"
                            alt=""
                            aria-hidden="true"
                        >
                    </div>
                </div>
                <div class="about-index">01</div>
            </div>

            <div>
                <div class="section-header">
                    <div class="eyebrow reveal reveal-right" style="--i: 0">
                        Sobre mí
                    </div>

                    <h2 class="section-title reveal reveal-right" style="--i: 1">
                        Tecnología con una
                        <span>identidad propia.</span>
                    </h2>
                </div>

                <div class="about-text reveal reveal-right" style="--i: 2">
                    <p>
                        Soy <strong>desarrollador full stack</strong>,
                        especializado en la creación de plataformas web,
                        sistemas administrativos, ecommerce y experiencias
                        frontend modernas.
                    </p>

                    <p>
                        Mi enfoque busca ir más allá de construir una página
                        funcional. Cada proyecto debe comunicar una identidad,
                        responder a una necesidad real y entregar una
                        experiencia clara para quien lo utiliza.
                    </p>

                    <p>
                        Trabajo principalmente con
                        <strong>Laravel, PHP, MySQL, JavaScript, HTML y CSS</strong>,
                        integrando servicios externos, APIs y procesos
                        automatizados cuando el proyecto lo requiere.
                    </p>
                </div>

                <div class="statistics reveal reveal-up" style="--i: 3">
                    <div class="statistic">
                        <div class="statistic-number">10+</div>
                        <div class="statistic-label">Proyectos</div>
                    </div>

                    <div class="statistic">
                        <div class="statistic-number">2+</div>
                        <div class="statistic-label">Años creando</div>
                    </div>

                    <div class="statistic">
                        <div class="statistic-number">100%</div>
                        <div class="statistic-label">Compromiso</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
