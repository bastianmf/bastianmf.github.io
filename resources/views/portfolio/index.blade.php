@extends('layouts.base_solida')

@section('title', 'Bastián Medina | Portafolio')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/portfolio.css') }}">
@endpush

@section('content')
    @include('partials_portfolio.social_rail')
    @include('partials_portfolio.hero')
    @include('partials_portfolio.about')
    @include('partials_portfolio.proyectos')
    @include('partials_portfolio.habilidades')
    @include('partials_portfolio.metodologia')
    @include('partials_portfolio.cita')
    @include('partials_portfolio.contacto')
@endsection

@push('scripts')
    <script src="{{ asset('js/portfolio.js') }}" defer></script>
@endpush
