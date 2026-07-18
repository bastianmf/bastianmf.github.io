@php
    $isStaticExport = config('portfolio.static_export', false);
    $staticBasePath = rtrim(config('portfolio.static_base_path', ''), '/');
    $topHref = $isStaticExport
        ? (($staticBasePath === '' ? '' : $staticBasePath) . '/#inicio')
        : '#inicio';

    // Cada red entra desde abajo, una tras otra
    $socials = [
        ['href' => 'https://www.linkedin.com/in/bastianmf/', 'label' => 'LinkedIn', 'short' => 'IN'],
        ['href' => 'https://wa.me/56996176747', 'label' => 'WhatsApp', 'short' => 'WA'],
        ['href' => 'https://www.instagram.com/bhxzty/', 'label' => 'Instagram', 'short' => 'IG'],
    ];
@endphp

<footer class="footer">
    <div class="container">
        <div class="footer-content">
            <div class="footer-copy reveal reveal-left" style="--i: 0">
                © <span id="currentYear"></span>
                Bastián Medina. Diseñado y desarrollado con dedicación.
            </div>

            <div class="social-list">
                @foreach ($socials as $social)
                    <a
                        href="{{ $social['href'] }}"
                        class="social-link reveal reveal-up"
                        style="--i: {{ $loop->index + 1 }}"
                        target="_blank"
                        rel="noopener noreferrer"
                        aria-label="{{ $social['label'] }}"
                    >
                        {{ $social['short'] }}
                    </a>
                @endforeach
            </div>

            <a
                href="{{ $topHref }}"
                class="back-to-top reveal reveal-right"
                style="--i: 4"
            >
                Volver arriba ↑
            </a>
        </div>
    </div>
</footer>
