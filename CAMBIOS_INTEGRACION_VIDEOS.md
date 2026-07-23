# Integración de videos en "Obras seleccionadas" — resumen de cambios

Se integraron videos de demostración (grabaciones reales de cada sistema en
uso) en la galería de las obras, conviviendo con las capturas existentes. Al
abrir una obra, la galería arranca mostrando el sistema **funcionando**.

Todo se hizo con Blade + CSS + JS vanilla, sin dependencias nuevas.

---

## 1. Videos: optimizados y ubicados

- **Origen:** `C:\Users\HP\Desktop\Prueba de video\ot-ordenes\output\` (16 MP4,
  1920×1080, 30 fps, sin audio, ~102 MB en total).
- **Optimización con ffmpeg** a 720p H.264 (CRF 27, `+faststart` para que hagan
  streaming): **de 102 MB a 25 MB** (~1–2,5 MB cada uno), sin perder legibilidad.
- **Destino:** `public/videos/proyectos/{carpeta}/{nombre}.mp4`.

Mapeo aplicado:

| Obra | Carpeta | Videos |
| --- | --- | --- |
| I · Sistema Cotizador 2026 | `cotizador` | `cotiz-index`, `cotiz-crear` |
| II · SC Informática | `ecommerce-sc` | `ec-index`, `ec-compra` |
| III · SoyTrabajador | `soytrabajador` | `st-tour`, `st-home`, `st-hover` |
| IV · Plataforma SPA Offline | `encuestas-spa` | `spa-encuesta`, `spa-estadisticas` |
| V · Secmap SPA | `secmap` | `secmap-home`, `secmap-contacto` |
| VI · Centro Médico de Diálisis | `centro-medico` | *(sin video; galería solo con capturas)* |
| VII · Sistema de Órdenes de Trabajo | `sistema-ot` | `ordenes`, `avances`, `firma` |
| VIII · SoyHonorario.cl | `soyhonorarios` | `sh-home`, `sh-calculadora` |

> Nota: el prompt listaba 18 videos; en el origen había 16. `ordenes-rapido`
> (Sistema OT) y los de Centro Médico no estaban. El sistema soporta galerías
> mixtas, así que esas obras funcionan igual (VI queda solo con capturas).

## 2. Pósters (primer frame visible, no la intro negra)

Cada video abre con una intro breve en negro. Para el `poster` se extrajo con
ffmpeg un frame al **45 % de la duración** (ya con el sistema en pantalla):
`public/images/proyectos/{carpeta}/poster-{nombre}.jpg`. Se usan como miniatura
del video y como imagen del `poster` del `<video>`.

## 3. Datos — `app/Http/Controllers/PortfolioController.php`

La galería (`gallery`) ahora acepta dos tipos de ítem, sin romper los actuales:

```php
// Imagen (como antes)
['img' => 'images/proyectos/cotizador/1.jpg', 'title' => '...', 'caption' => '...'],

// Video (nuevo)
['type' => 'video',
 'src' => 'videos/proyectos/cotizador/cotiz-index.mp4',
 'poster' => 'images/proyectos/cotizador/poster-cotiz-index.jpg',
 'title' => 'Recorrido del listado',
 'caption' => 'Búsqueda, filtros y apertura de una cotización, en vivo.',
 'duration' => '38 s'],
```

Los videos se pusieron **al inicio** de cada galería (la demo primero); las
capturas quedan después. La **portada de la card** sigue siendo la primera
**imagen** de la galería (los videos no sirven de portada) — esto se resolvió en
`card_proyecto.blade.php` con `firstWhere('img', '!=', null)`.

## 4. Vista — `resources/views/partials_portfolio/detalle_proyecto.blade.php`

- El `stage` ahora contiene un `<img class="gp-stage-img">` **y** un
  `<video class="gp-stage-video">` que se solapan; se muestra uno por vez.
- El `<video>` va `muted`, `playsinline`, `preload="none"`, `controls` y **sin
  `src`**: sólo se descarga cuando se activa esa pestaña.
- Las miniaturas de video usan el `poster` como imagen y le superponen un
  **ícono de play** y la **duración** (`38 s`). Llevan `data-type="video"`,
  `data-src` (mp4), `data-poster` y `data-duration`.
- Accesibilidad: `aria-label` del tipo "Video: recorrido del listado, 38 s"; las
  miniaturas siguen siendo `role="tab"` con `aria-selected`.

## 5. Lógica — `public/js/portfolio.js` (`setupProjectDetail`)

- `activateThumb()` distingue imagen/video: en video muestra el `<video>`, le
  pone `src`+`poster` y lo reproduce (muted); en imagen vuelve al `<img>`.
- **Pausa y reset** del video al cambiar de miniatura, al **cerrar** el panel
  (botón, `Escape`, click en overlay) y al **cambiar de obra**
  (`pauseAllVideos()` + `resetVideo()` que suelta el `src`).
- **Autoplay de 3 s suspendido** mientras un video está activo: `startAutoplay()`
  nunca arranca sobre una pestaña de video, así una demo de 38 s no se corta.
- Al **terminar** el video (`ended`) pasa a la siguiente pestaña y reanuda la
  rotación.
- **`prefers-reduced-motion`**: los videos **no** autoreproducen; se muestra el
  póster con los controles para que el visitante decida.

## 6. Estilos — `public/css/portfolio.css`

- `.gp-stage-img` y `.gp-stage-video` se solapan (`position:absolute`) con
  `object-fit: contain`. El stage mantiene `aspect-ratio: 2/1`; los videos 16:9
  quedan con franjas mínimas en `var(--black-card)` (consistente con las
  capturas). Se reutiliza la animación `gpStageIn`.
- Miniatura de video: `.gp-thumb-play` (círculo con triángulo dorado centrado) y
  `.gp-thumb-duration` (pill con la duración), con la paleta gótica existente
  (negro/dorado). El `<video>` usa `accent-color: var(--gold)`.

---

## Cómo probarlo localmente

```bash
cd C:\Users\HP\Desktop\laravel_gothic_portfolio
php artisan serve
```

Abrí `http://127.0.0.1:8000` y:

1. **Red / Network:** al cargar la home **no** se descarga ningún `.mp4` (los
   `<video>` no tienen `src` todavía).
2. Pulsá el `↗` de una obra (p. ej. la I): baja el panel y **arranca el video**
   solo (muted). Las 2 primeras miniaturas muestran play + duración.
3. Esperá varios segundos: el video **no** se corta a los 3 s (el autoplay queda
   suspendido mientras hay un video).
4. Pulsá una miniatura de captura: el video se **pausa** y aparece la imagen; la
   rotación automática se reanuda.
5. Cerrá con la X o `Escape`: el video se pausa y resetea.
6. Con "reducir movimiento" activo en el sistema: el video **no** arranca solo,
   muestra el póster y sus controles.

### Si cambian los videos

- Reemplazá el `.mp4` en `public/videos/proyectos/{carpeta}/` y su
  `poster-*.jpg` en `public/images/proyectos/{carpeta}/`.
- Para agregar/quitar un video, editá el array `gallery` del proyecto en
  `PortfolioController::projects()`.

## Verificado

- 8 galerías con `<video>`, 16 miniaturas de video (play + duración).
- 0 `.mp4` cargados al abrir la home (todos en `data-src`, ninguno en `<video src>`).
- Reproduce al abrir · autoplay no corta el video · pausa+reset al cambiar/cerrar
  · reduced-motion sin autoplay. Desktop y móvil (390 px).
- `php artisan test` → 2/2.
