# Prompt — Integrar videos de demostración en "Obras seleccionadas"

> Copiá todo lo que está debajo de la línea y pegalo como prompt.
> Está escrito para que un agente que **no conoce el proyecto** pueda ejecutarlo:
> incluye rutas reales, nombres de clases, la estructura de datos actual y las
> trampas detectadas al analizar el código.

---

## Contexto del proyecto

Trabajás en `C:\Users\HP\Desktop\laravel_gothic_portfolio`, un portafolio hecho en
**Laravel + Blade + CSS/JS vanilla** (sin framework de front, sin build de JS: los
assets se sirven desde `public/`). La estética es gótica: negro con detalles
dorados, tipografía editorial, arcos y rosetones.

La sección **"Obras seleccionadas"** muestra 8 proyectos. Al pulsar una card se
abre un panel a pantalla completa (`#projectDetail`) que baja desde arriba, con:

- **Izquierda:** texto del proyecto (eyebrow, título, resumen, ficha `facts`).
- **Derecha:** una **galería tipo "image tabs"** — una imagen grande (`stage`) y
  una fila de miniaturas; al pulsar una miniatura cambian la imagen y su texto.

### Archivos que importan

| Archivo | Rol |
| --- | --- |
| `app/Http/Controllers/PortfolioController.php` | **Fuente de datos**: método privado `projects()` devuelve el array de proyectos. No hay base de datos ni modelo. |
| `resources/views/partials_portfolio/detalle_proyecto.blade.php` | Panel de detalle + galería (stage, caption, thumbs). |
| `resources/views/partials_portfolio/proyectos.blade.php` | Grilla de la sección. |
| `resources/views/partials_portfolio/card_proyecto.blade.php` | Card de cada obra. |
| `public/js/portfolio.js` | Lógica: función `setupProjectDetail()` maneja apertura, cierre y galería. |
| `public/css/portfolio.css` | Estilos de la galería (`.gp-*`). |

### Estructura de datos actual (por proyecto)

```php
[
    'numeral'     => 'I',                    // clave que une card ↔ panel ↔ galería
    'category'    => 'system',
    'image'       => 'one',
    'kicker'      => 'Sistema empresarial',
    'title'       => 'Sistema Cotizador 2026',
    'description' => '...',
    'summary'     => '...',
    'facts'       => [['k' => 'Rol', 'v' => '...'], ...],
    'gallery'     => [
        ['img' => 'images/proyectos/cotizador/1.jpg', 'title' => '...', 'caption' => '...'],
    ],
    'tags'        => ['Laravel', 'MySQL', 'APIs'],
    'url'         => '#',
]
```

### Marcado actual de la galería

```html
<figure class="gp-detail-gallery" data-project-visual="{{ numeral }}">
  <div class="gp-gallery-stage">
    <img class="gp-stage-img" src="..." alt="...">
  </div>
  <figcaption class="gp-gallery-caption">
    <span class="gp-cap-title">…</span>
    <span class="gp-cap-text">…</span>
  </figcaption>
  <div class="gp-gallery-thumbs" role="tablist">
    <button class="gp-thumb is-active" role="tab"
            data-src="…" data-title="…" data-caption="…">
      <img src="…" alt="">
    </button>
  </div>
</figure>
```

En `portfolio.js`, `activateThumb(thumb)` lee `data-src`, `data-title` y
`data-caption` y actualiza `.gp-stage-img`, `.gp-cap-title` y `.gp-cap-text`.

---

## Objetivo

Integrar **videos de demostración** (grabaciones reales de cada sistema en uso,
con cursor, clics y navegación) en la galería de cada obra, conviviendo con las
capturas que ya existen. Al abrir una obra, el visitante debe poder ver el
sistema **funcionando**, no solo imágenes fijas.

### Los videos

- Formato **MP4 (H.264), 1920×1080, 30 fps**, sin audio.
- Duración **23–44 s**, peso **3–7 MB** cada uno.
- Hay **1 a 4 videos por proyecto** (18 en total).
- Origen: `C:\Users\HP\Desktop\Prueba de video\ot-ordenes\output\`.
- Cada uno abre con una intro breve (título del proyecto sobre negro) y cierra
  con las tecnologías usadas.

### Mapeo video → obra

| Obra | Carpeta de galería | Videos |
| --- | --- | --- |
| I · Sistema Cotizador 2026 | `cotizador` | `cotiz-index`, `cotiz-crear` |
| II · SC Informática | `ecommerce-sc` | `ec-index`, `ec-compra` |
| III · SoyTrabajador | `soytrabajador` | `st-home`, `st-tour`, `st-hover` |
| IV · Plataforma SPA Offline | `encuestas-spa` | `spa-encuesta`, `spa-estadisticas` |
| V · Secmap SPA | `secmap` | `secmap-home`, `secmap-contacto` |
| VI · Centro Médico de Diálisis | `centro-medico` | `cmdd-index`, `cmdd-ficha` |
| VII · Sistema de Órdenes de Trabajo | `sistema-ot` | `ordenes`, `ordenes-rapido`, `firma`, `avances` |
| VIII · SoyHonorario.cl | `soyhonorarios` | `sh-home`, `sh-calculadora` |

---

## Qué hacer

### 1. Extender la estructura de datos

En `PortfolioController::projects()`, permitir que un ítem de `gallery` sea
imagen **o** video, sin romper los existentes. Sugerencia:

```php
[
    'type'    => 'video',                                  // 'image' por defecto
    'src'     => 'videos/proyectos/cotizador/cotiz-index.mp4',
    'poster'  => 'images/proyectos/cotizador/1.jpg',       // primer frame o captura
    'title'   => 'Recorrido del listado',
    'caption' => 'Búsqueda, filtros y apertura de una cotización.',
    'duration'=> '38s',
]
```

Mantené `'img' => ...` funcionando para los ítems actuales (no rehagas las 8
galerías existentes).

### 2. Renderizar video en el stage (Blade)

En `detalle_proyecto.blade.php`:

- El `stage` debe poder contener un `<video>` o un `<img>` según el ítem activo.
- El `<video>` va con `muted`, `playsinline`, `preload="none"`, `loop`,
  `controls` (o controles propios) y su `poster`.
- Las miniaturas de video deben distinguirse: usar el `poster` como imagen y
  superponer un **ícono de play** y la duración (`38s`). Agregá
  `data-type="video"`, `data-poster` y `data-duration` al botón.

### 3. Adaptar la lógica (portfolio.js)

En `setupProjectDetail()` → `activateThumb()`:

- Si el ítem es video: reemplazar/mostrar el `<video>` en el stage, setear `src`
  y `poster`, y reproducir. Si es imagen: volver al `<img>`.
- **Pausar y resetear el video** al cambiar de miniatura, al cerrar el panel
  (`projectDetailClose`, tecla Escape, click en el overlay) y al cambiar de obra.
  Hoy el panel se reutiliza para las 8 obras: si no pausás, queda un video
  sonando/consumiendo en segundo plano.

### 4. Respetar el autoplay existente ⚠️

`portfolio.js` define `AUTOPLAY_MS = 3000`: las miniaturas **rotan solas cada 3
segundos**. Un video de 38 s sería cortado a los 3 s.

→ **Mientras un ítem de video esté activo, suspender el autoplay** y reanudarlo
al volver a una imagen o al terminar el video (`ended`).

### 5. Respetar `prefers-reduced-motion`

El JS ya lee `window.matchMedia("(prefers-reduced-motion: reduce)")`. Con esa
preferencia activa: **no autoreproducir** los videos; mostrar el `poster` con el
botón de play y que el usuario decida.

### 6. Estilos (portfolio.css)

- `.gp-gallery-stage` tiene `aspect-ratio: 2 / 1`, pero **los videos son 16:9
  (1.78:1)**. Con `object-fit: contain` van a quedar franjas laterales. Elegí una
  y dejalo consistente: (a) `aspect-ratio: 16/9` cuando el ítem activo es video,
  o (b) mantener 2/1 y aceptar las franjas en el color `var(--black-card)`.
- `.gp-stage-img` usa `object-fit: contain` (a propósito: la captura se ve
  completa, sin recortes). El `<video>` debe seguir el mismo criterio.
- Reutilizá la animación de entrada `gpStageIn` para que el cambio se sienta
  igual que con las imágenes.
- Los estilos nuevos van con el prefijo `gp-` y usan las variables existentes
  (`var(--border)`, `var(--black-card)`); mantené la paleta gótica (negro/dorado).

### 7. Peso y carga

18 videos × ~5 MB ≈ **90 MB**. No se pueden cargar todos al abrir la página.

- `preload="none"` + `poster`: el video se descarga recién al activarlo.
- Los archivos van en `public/videos/proyectos/{carpeta}/{nombre}.mp4`.
- Si el hosting lo permite, serví los `.mp4` con cache larga.
- Opcional: generar una versión más liviana (720p, CRF más alto) para móvil.

### 8. Accesibilidad

- El `<video>` necesita un texto alternativo equivalente: usá el `title` y
  `caption` que ya existen (`aria-label` en el contenedor).
- Las miniaturas ya son `role="tab"` con `aria-selected`; mantené ese patrón e
  indicá en el `aria-label` que se trata de un video ("Video: recorrido del
  listado, 38 segundos").
- El foco no debe perderse al cambiar de ítem.

---

## Restricciones

- **No** introduzcas dependencias nuevas (ni npm, ni librerías de lightbox/player).
  El proyecto es Blade + CSS + JS vanilla y así debe quedar.
- **No** rompas las galerías de imágenes actuales: los 8 proyectos ya tienen
  capturas y deben seguir funcionando igual.
- Mantené el estilo del código existente: comentarios en español, clases con
  prefijo `gp-`, funciones dentro de `setupProjectDetail`.

## Criterios de aceptación

1. Al abrir cualquiera de las 8 obras, la galería muestra imágenes **y** videos.
2. Las miniaturas de video se distinguen (play + duración) de las de imagen.
3. Al activar un video se reproduce en el stage; al cambiar de miniatura o cerrar
   el panel, se pausa y resetea.
4. El autoplay de 3 s **no** interrumpe un video en curso.
5. Con `prefers-reduced-motion: reduce` ningún video arranca solo.
6. Al cargar la home no se descarga ningún `.mp4` (verificable en la pestaña Red).
7. El diseño gótico se mantiene: sin bordes, colores ni tipografías ajenas.
8. Funciona en escritorio y móvil (el panel es responsive).

## Entregables

- `PortfolioController.php` con la estructura extendida y los videos cargados en
  las 8 obras.
- `detalle_proyecto.blade.php` renderizando ambos tipos de ítem.
- `portfolio.js` con la lógica de video (play/pausa/reset + autoplay suspendido).
- `portfolio.css` con los estilos nuevos.
- Los `.mp4` ubicados en `public/videos/proyectos/…`.
- Un resumen de qué cambiaste y cómo probarlo localmente.
