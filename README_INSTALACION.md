# Portafolio Gothic para Laravel

Proyecto Laravel con la portada del portafolio montada sobre Blade y assets
servidos desde `public` (sin Vite).

## Estructura clave

Flujo: **layout base → vista principal → partials de la vista → assets públicos**

```text
resources/views/
|-- layouts/
|   |-- base_solida.blade.php              <- layout público (head, header, footer, stacks)
|   `-- partials_base_solida/
|       |-- intro.blade.php                <- intro gótica (sigilo SVG que se dibuja)
|       |-- header.blade.php
|       `-- footer.blade.php
|-- partials_portfolio/                    <- secciones de la portada
|   |-- hero.blade.php
|   |-- about.blade.php
|   |-- proyectos.blade.php
|   |-- card_proyecto.blade.php            <- card con recorte (notch)
|   |-- habilidades.blade.php
|   |-- metodologia.blade.php
|   |-- cita.blade.php
|   `-- contacto.blade.php
`-- portfolio/index.blade.php              <- vista principal (ordena las secciones)
```

```text
public/css/
|-- app.css              <- tokens, base, botones, reveal, intro, utilidades de dibujo
|-- header.css           <- header fijo y navegación
|-- footer.css           <- pie de página
|-- portfolio.css        <- secciones de la portada
`-- cssm/mobile.css      <- responsive (1000px / 820px / 640px)

public/js/
|-- app.js               <- global: menú, header, reveal, nav activa, intro, año
`-- portfolio.js         <- portada: filtros y lista que se "forma"
```

- Rutas: `routes/web.php`
- Controladores: `app/Http/Controllers/PortfolioController.php` y `ContactController.php`

## Ejecutar

1. PHP 8.3 y una base de datos SQLite disponible (o ajusta tu `.env`).
2. Ejecuta:

```bash
php artisan key:generate
php artisan migrate
php artisan serve
```

3. Abre `http://127.0.0.1:8000`

## Dónde editar cada cosa

| Quiero cambiar… | Archivo |
|---|---|
| Colores, tipografías, radios | `public/css/app.css` (bloque `:root`) |
| Proyectos, filtros, habilidades | `app/Http/Controllers/PortfolioController.php` |
| El HTML de una sección | `resources/views/partials_portfolio/<seccion>.blade.php` |
| Orden de las secciones | `resources/views/portfolio/index.blade.php` |
| Responsive | `public/css/cssm/mobile.css` |

Los proyectos son **datos**, no HTML: se editan en el controlador y se pintan
con `card_proyecto.blade.php`. Para agregar uno, añade un elemento al array
`projects()` (su `category` debe coincidir con el `key` de un filtro).

## Detalle de proyecto (panel que baja)

Al pulsar el botón `↗` de una card baja un panel a pantalla completa con la
información del proyecto y su captura. La técnica está adaptada de la vista
`/servicios` de SoyTrabajador (`sv-d132`), aquí en paleta gótica: negro con
cuadrícula dorada y halo vino, en vez del panel de papel crema del original.

- Partial: `resources/views/partials_portfolio/detalle_proyecto.blade.php`
- Estilos: bloque `Detalle de proyecto` en `public/css/portfolio.css`
- Lógica: `setupProjectDetail()` en `public/js/portfolio.js`

**La clave que une todo es el numeral de la obra** (`I`, `II`, …): el botón
lleva `data-project-open="II"` y dentro del panel se activan el texto
(`data-project-detail="II"`) y la captura (`data-project-visual="II"`).

### Capturas de los proyectos (galería "image tabs")

Cada obra tiene una **galería** de capturas en
`PortfolioController::projects()`, campo `gallery`: una lista de imágenes, cada
una con `img`, `title` y `caption`.

- La **primera** imagen es la portada de la card (recortada y anclada arriba,
  con el scrim que da legibilidad al título).
- En el **panel de detalle** todas forman una galería tipo *image tabs*: una
  imagen grande + su texto + un grid de miniaturas. Al pulsar una miniatura
  cambian la imagen y el texto (lo maneja `setupProjectDetail` en
  `portfolio.js`). Al abrir un proyecto, su galería parte en la primera.

Las imágenes viven en `public/images/proyectos/<slug>/1.jpg, 2.jpg, …`. Para
cambiar una, reemplaza el archivo o edita su entrada en `gallery`. Para agregar
o quitar capturas, suma o resta entradas del array (no hay límite fijo; el grid
de miniaturas se adapta).

Se sirven como JPG optimizados (~1360px, calidad 82: las 46 capturas pesan
~3.4 MB en total). Los PNG originales quedaron en tu carpeta `Evidencias_png`.
Si un proyecto se quedara sin `gallery`, la card cae a la ventana de navegador
de mentira (`partials_portfolio/mock_browser.blade.php`).

## Hero: foto, trazo y sello

La tarjeta del hero (`partials_portfolio/hero.blade.php`) muestra:

- **Foto** (`public/images/perfil/bastian.jpg`) con tratamiento gótico: se
  desatura y se tiñe hacia vino con `mix-blend-mode: color`, más un
  desvanecido inferior y un brillo dorado arriba (`.portrait-veil`).
- **Trazo svg-draw-in** que se dibuja SOBRE la foto cuando la tarjeta termina
  de entrar: esquinas ornamentales, una línea con nodos y un sigilo (círculo +
  cruz). Lo dispara `setupPortraitDraw` en `portfolio.js` al terminar la
  transición de entrada de la tarjeta (con una red de seguridad por tiempo).
- **Sello** (`public/images/perfil/sello.png`) flotando a la izquierda, un poco
  afuera, en loop vertical suave (`@keyframes sealFloat`). Se oculta en móvil,
  donde no hay espacio "afuera". Está teñido de oro a partir del PNG original
  (la tinta oscura → oro, el fondo claro → transparente).

Para cambiar la foto o el sello, reemplaza esos archivos. Si quieres regenerar
el teñido del sello desde otro PNG, el criterio es: mapear la tinta oscura a oro
opaco y el fondo claro a transparente (no al revés).

## Entradas al hacer scroll (reveal)

Cada elemento entra **desde el lado donde vive** en la maqueta, no todo desde
abajo. Se controla con dos perillas, ambas opcionales:

```blade
<div class="reveal reveal-left" style="--i: 2">…</div>
```

| Clase | Entra desde |
|---|---|
| `reveal` | Abajo (por defecto) |
| `reveal reveal-left` | Izquierda |
| `reveal reveal-right` | Derecha |
| `reveal reveal-down` | Arriba |
| `reveal reveal-up` | Abajo (explícito) |

- `--i` es el índice dentro del grupo y escalona la cascada (0.09s por paso).
  En un `@foreach` se pasa con `$loop->index`.
- La dirección vive en la variable `--reveal-from`, no en clases que pisen
  `translate`. Si se hiciera con clases, `.reveal.visible` y `.reveal.reveal-left`
  tendrían la misma especificidad y el resultado dependería del orden del CSS.
- En móvil el desplazamiento lateral baja a 22px (`cssm/mobile.css`): en una
  columna, 46px se recortan demasiado.
- Mientras corre la intro, `app.js` pone `--intro-offset` en `<html>` para que
  lo que ya está en pantalla no se revele detrás del overlay, y lo quita al
  terminar. El `IntersectionObserver` siempre se crea en `DOMContentLoaded`.

## Técnicas aplicadas (skills)

- **notch-card** — las cards de proyectos tienen una pestaña "recortada" con
  esquinas cóncavas. Funciona porque la sección usa `.section-solid` (fondo
  plano) y `--bleed` en `.project-card` es exactamente ese color. Si le cambias
  el fondo a la sección, actualiza `--bleed` o el recorte se notará.
- **svg-draw-in** — todo lo que se dibuja solo usa `stroke-dashoffset` con
  `pathLength="1"` en cada trazo y se escalona con la variable `--d`:

  | Pieza | Dónde | Cuándo se dibuja |
  |---|---|---|
  | Sigilo (rombo, arco, cruz) | `partials_base_solida/intro.blade.php` | Al cargar, una vez por sesión (`sessionStorage`) |
  | Rosetón (rose window) | `partials_portfolio/roseton.blade.php` | Al llegar con el scroll |
  | Arcada de arcos ojivales | `partials_portfolio/arco_gotico.blade.php` | Al llegar, escalonada por paso |
  | Fleurón divisor | `partials_base_solida/divisor.blade.php` | Al llegar, del centro hacia afuera |
  | Habilidades y líneas de eyebrow | `habilidades.blade.php` | Al llegar, escalonadas por `--i` |

  **Cómo activar un trazo con el scroll:** ponle la clase `draw-in-view` al
  `<svg>` (o al contenedor) y `draw` a cada trazo. `app.js` le añade `.in` al
  entrar en pantalla y ahí arranca la animación. Sin `draw-in-view` el trazo se
  dibuja al cargar la página, que es lo que hace la intro.

  Para reutilizar el fleurón en otra sección:
  `@include('layouts.partials_base_solida.divisor')`.

## Notas

- No se usa Vite. Las vistas cargan CSS y JS directamente desde `public`.
- El CSS por página se carga con `@push('styles')` y el JS con `@push('scripts')`.
- Sin JavaScript el contenido se ve igual (la intro no aparece y nada queda
  oculto): las animaciones se activan sólo con la clase `js` en `<html>`.
- Se respeta `prefers-reduced-motion`.
- Para usar imágenes reales en los proyectos, reemplaza los fondos de
  `.project-image.one|two|three|four` en `public/css/portfolio.css` por
  imágenes dentro de `public/images`.
- El formulario de contacto valida campos y responde con un mensaje de
  confirmación. Hoy no envía correo: revisa el comentario en `ContactController`.
