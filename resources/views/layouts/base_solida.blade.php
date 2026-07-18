<!doctype html>
<html lang="es">
<head>
    @php
        $pageTitle = trim($__env->yieldContent('title', config('portfolio.current_page_title', config('portfolio.meta.title', config('app.name', 'Bastián Portfolio')))));
        $pageDescription = trim($__env->yieldContent('meta_description', config('portfolio.meta.description', 'Portafolio profesional de desarrollo web, diseño digital y experiencias frontend.')));
        $canonicalUrl = config('portfolio.current_canonical_url');
        $ogImageUrl = config('portfolio.current_og_image_url');
    @endphp
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta
        name="description"
        content="{{ $pageDescription }}"
    >
    <meta name="theme-color" content="#070707">

    <title>{{ $pageTitle }}</title>

    @if ($canonicalUrl)
        <link rel="canonical" href="{{ $canonicalUrl }}">
    @endif

    <meta property="og:type" content="website">
    <meta property="og:title" content="{{ $pageTitle }}">
    <meta property="og:description" content="{{ $pageDescription }}">
    @if ($canonicalUrl)
        <meta property="og:url" content="{{ $canonicalUrl }}">
    @endif
    @if ($ogImageUrl)
        <meta property="og:image" content="{{ $ogImageUrl }}">
    @endif

    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="{{ $pageTitle }}">
    <meta name="twitter:description" content="{{ $pageDescription }}">
    @if ($ogImageUrl)
        <meta name="twitter:image" content="{{ $ogImageUrl }}">
    @endif

    {{--
        Antes de pintar: marca que hay JS (el reveal y la intro sólo se ocultan
        si hay JS) y evita el flash de la intro cuando ya se vio en la sesión.
    --}}
    <script>
        (function () {
            var root = document.documentElement;
            root.classList.add('js');

            try {
                if (sessionStorage.getItem('gothicIntroSeen') === '1') {
                    root.classList.add('intro-seen');
                }
            } catch (error) {
                /* sessionStorage bloqueado: la intro se mostrará igual */
            }
        })();
    </script>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Cinzel:wght@400;500;600;700&family=Cormorant+Garamond:ital,wght@0,400;0,500;0,600;1,500&family=Inter:wght@300;400;500;600&display=swap"
        rel="stylesheet"
    >

    <link rel="icon" href="{{ asset('favicon.ico') }}">

    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <link rel="stylesheet" href="{{ asset('css/header.css') }}">
    <link rel="stylesheet" href="{{ asset('css/footer.css') }}">

    @stack('styles')

    {{-- Va al final para poder ajustar cualquier vista en pantallas chicas --}}
    <link rel="stylesheet" href="{{ asset('css/cssm/mobile.css') }}">
</head>
<body>
    @include('layouts.partials_base_solida.intro')

    <a href="#contenido" class="skip-link">Saltar al contenido</a>

    @include('layouts.partials_base_solida.header')

    <main id="contenido">
        @yield('content')
    </main>

    @include('layouts.partials_base_solida.footer')

    <script src="{{ asset('js/app.js') }}" defer></script>
    @stack('scripts')
</body>
</html>
