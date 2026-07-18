@php
    $isStaticExport = config('portfolio.static_export', false);
    $staticBasePath = rtrim(config('portfolio.static_base_path', ''), '/');
    $staticHome = ($staticBasePath === '' ? '' : $staticBasePath) . '/';
    $sectionHref = fn (string $id) => $isStaticExport
        ? $staticHome . '#' . $id
        : '#' . $id;

    // El orden define la cascada: cada enlace entra un pelo después del anterior.
    $navLinks = [
        ['href' => $sectionHref('inicio'), 'label' => 'Inicio', 'active' => true],
        ['href' => $sectionHref('sobre-mi'), 'label' => 'Sobre mí', 'active' => false],
        ['href' => $sectionHref('proyectos'), 'label' => 'Proyectos', 'active' => false],
        ['href' => $sectionHref('habilidades'), 'label' => 'Habilidades', 'active' => false],
        ['href' => $sectionHref('contacto'), 'label' => 'Contacto', 'active' => false],
    ];
@endphp

<header class="header" id="header">
    <div class="container">
        <nav class="navbar">
            <a
                href="{{ $isStaticExport ? $staticHome . '#inicio' : '#inicio' }}"
                class="logo reveal reveal-left"
                style="--i: 0"
                aria-label="Ir al inicio"
            >
                <div class="logo-symbol">
                    <img
                        src="{{ asset('images/perfil/logo-bastian-medina.png') }}"
                        alt=""
                        aria-hidden="true"
                    >
                </div>
                <div class="logo-text">Bastián Medina</div>
            </a>

            <ul class="nav-list" id="navList">
                @foreach ($navLinks as $link)
                    {{-- --i arranca en 1: el logo es el 0 de la cascada --}}
                    <li class="reveal reveal-left" style="--i: {{ $loop->index + 1 }}">
                        <a
                            href="{{ $link['href'] }}"
                            class="nav-link @if ($link['active']) active @endif"
                        >
                            {{ $link['label'] }}
                        </a>
                    </li>
                @endforeach
            </ul>

            {{-- Sin reveal a propósito: en desktop va display:none y no debe
                 depender de una animación para existir en móvil. --}}
            <button
                class="menu-button"
                id="menuButton"
                aria-label="Abrir menú"
                aria-expanded="false"
            >
                <span></span>
                <span></span>
                <span></span>
            </button>
        </nav>
    </div>
</header>
