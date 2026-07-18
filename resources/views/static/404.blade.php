@extends('layouts.base_solida')

@section('title', 'Página no encontrada | Bastián Medina')

@section('meta_description', 'Página no encontrada en el portafolio de Bastián Medina.')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/portfolio.css') }}">
@endpush

@section('content')
    @php
        $isStaticExport = config('portfolio.static_export', false);
        $staticBasePath = rtrim(config('portfolio.static_base_path', ''), '/');
        $homeHref = $isStaticExport
            ? (($staticBasePath === '' ? '' : $staticBasePath) . '/#inicio')
            : route('portfolio.index');
    @endphp

    <section class="not-found-page">
        <div class="container">
            <div class="not-found-inner reveal reveal-up">
                <div class="eyebrow">Error 404</div>

                <h1 class="section-title">
                    Página no
                    <span>encontrada.</span>
                </h1>

                <p class="section-description">
                    La ruta que intentas abrir no existe o fue movida. Puedes
                    volver al inicio del portafolio y seguir navegando desde ahí.
                </p>

                <a href="{{ $homeHref }}" class="button button-primary">
                    Volver al inicio
                    <span class="button-arrow">→</span>
                </a>
            </div>
        </div>
    </section>
@endsection
