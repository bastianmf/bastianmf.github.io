# Análisis de exportación estática

## Rutas detectadas

- `GET /` -> `PortfolioController@index`, vista `portfolio.index`.
- `POST /contacto` -> `ContactController@store`, con `throttle:5,1`.

No hay rutas GET dinámicas, parámetros de ruta públicos, autenticación, panel
administrativo, Livewire, Inertia ni middleware protegido en la parte pública.

## Vistas Blade

La página pública usa:

- Layout: `resources/views/layouts/base_solida.blade.php`.
- Partials de layout: `header`, `footer`, `intro`.
- Vista principal: `resources/views/portfolio/index.blade.php`.
- Partials de portada: `hero`, `about`, `proyectos`, `detalle_proyecto`,
  `habilidades`, `metodologia`, `cita`, `contacto`, `social_rail` y SVGs de
  ornamentos.

Directivas encontradas:

- `@extends`, `@section`, `@push`, `@include`.
- `asset()` para CSS, JS e imágenes.
- `route()` sólo en el formulario Laravel de contacto.
- `@csrf`, `session()`, `$errors`, `old()` y `@error` sólo en contacto.
- No se usa `@vite`.

## Datos

Los proyectos, filtros, habilidades y metodología se generan desde arrays en
`PortfolioController`. No hay consultas a base de datos ni Eloquent en el
portafolio público.

## Exportable directamente

- HTML renderizado de la home.
- CSS y JS ubicados en `public/css` y `public/js`.
- Imágenes, favicon y robots existentes en `public`.
- Animaciones CSS/JS, panel de detalle de proyectos, filtros y responsive.

## Adaptaciones realizadas

- `php artisan portfolio:export` genera `dist/` como sitio estático.
- `asset()` se renderiza con `GITHUB_PAGES_BASE_PATH` para soportar subrutas.
- Se crean aliases estáticos con la página completa y salto automático a la
  sección:
  - `/sobre-mi/`
  - `/proyectos/`
  - `/habilidades/`
  - `/contacto/`
- El formulario de contacto cambia a `mailto:` sólo durante la exportación. Si
  `PORTFOLIO_CONTACT_EMAIL` está vacío, el envío queda deshabilitado con mensaje
  visible.
- Se generan `.nojekyll`, `404.html`, `robots.txt` y `sitemap.xml`.
- El exportador valida que no queden archivos PHP ni URLs locales en `dist`.

## No compatible con GitHub Pages

- El `POST /contacto` de Laravel no puede ejecutarse en Pages.
- Sesiones, validación server-side y flash messages no existen en la versión
  estática.

Solución aplicada: modo estático con `mailto:` configurable mediante
`PORTFOLIO_CONTACT_EMAIL` y armado de asunto/cuerpo en JavaScript. Laravel local
conserva el formulario POST original.
