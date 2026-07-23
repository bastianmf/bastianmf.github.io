{{--
    Media del case study: imagen o video-player.

    Espera:
    $item     Ítem de la galería (imagen o video).
    $featured (opcional) true si es la media destacada del hero (autoplay).

    El <video> arranca sin src (data-src) y con preload="none": se carga al
    acercarse al viewport del panel y se reproduce al entrar en foco. Lo
    maneja setupProjectDetail en portfolio.js.
--}}
@php
    $isVideo = ($item['type'] ?? 'image') === 'video';
    $still = $isVideo ? ($item['poster'] ?? '') : ($item['img'] ?? '');
    $featured = $featured ?? false;
@endphp

<div
    class="gp-media {{ $isVideo ? 'is-video' : 'is-image' }}"
    @if ($isVideo)
        data-video
        @if ($featured) data-featured @endif
        data-src="{{ asset($item['src']) }}"
        data-poster="{{ asset($still) }}"
        data-video-title="{{ $item['title'] }}"
    @endif
>
    @if ($isVideo)
        <video
            class="gp-media-el"
            preload="none"
            muted
            playsinline
            poster="{{ asset($still) }}"
            aria-label="Video: {{ $item['title'] }}{{ isset($item['duration']) ? ', ' . $item['duration'] : '' }}"
        ></video>

        <span class="gp-media-status" aria-hidden="true">
            <span class="gp-media-status-dot"></span>
            <span class="gp-media-status-idle">Demo real</span>
            <span class="gp-media-status-live">Reproduciendo</span>
        </span>

        <button type="button" class="gp-media-play" aria-label="Reproducir video: {{ $item['title'] }}">
            <svg class="gp-media-icon-play" viewBox="0 0 24 24" fill="currentColor" focusable="false" aria-hidden="true">
                <path d="M8 5v14l11-7z" />
            </svg>
            <svg class="gp-media-icon-pause" viewBox="0 0 24 24" fill="currentColor" focusable="false" aria-hidden="true">
                <path d="M6 5h4v14H6zm8 0h4v14h-4z" />
            </svg>
        </button>

        @isset($item['duration'])
            <span class="gp-media-badge">{{ $item['duration'] }}</span>
        @endisset
    @else
        <img
            class="gp-media-el"
            src="{{ asset($still) }}"
            alt="{{ $item['title'] }} — captura"
            loading="lazy"
        >
    @endif
</div>
