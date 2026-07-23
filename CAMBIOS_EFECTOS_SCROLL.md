# Cambios — Efectos de PagREF DENTRO del detalle de cada obra

Los 3 efectos de referencia de `/PagREF` se aplicaron **dentro del panel de
detalle** de cada proyecto (nada en la home), ligados al scroll del propio
panel. El detalle es un case study que se recorre hacia abajo y ahora es más
llamativo.

> Nota: una primera versión había puesto una sección "En acción" en la home y
> giros en el rosetón/sellos. Eso se **revirtió por completo** (la home quedó
> igual que antes); todo vive ahora dentro del detalle.

---

## Los 3 efectos, dentro del case study

Archivos: `resources/views/partials_portfolio/detalle_proyecto.blade.php`,
`partials_portfolio/gp_media.blade.php`, `public/css/portfolio.css`,
`public/js/portfolio.js` (`setupProjectDetail`), `public/css/cssm/mobile.css`.

### 1. "Fold Scroll Video" → el video del hero se EXPANDE

En el hero del case study, la demo destacada empieza como **tarjeta con esquinas
redondeadas** (66% del ancho) y, al scrollear, se **expande a casi pantalla
completa** afilando las esquinas. El encabezado (título/bajada) cede: se
desvanece y sube. Al expandir aparecen **esquinas ornamentales doradas** y un
lower-third con el título de la demo.

- La media queda **sticky** mientras dura la expansión (variable `--fold` 0 → 1).
- Lo calcula `setupProjectDetail` con el scroll de `#projectDetailScroll` +
  `requestAnimationFrame`.

### 2. "Video Side Scroll Reveal" → recorrido alternado + labels laterales

- En el **recorrido**, cada pantalla y su texto entran desde **lados opuestos**
  y los pasos **alternan** (01 media izquierda, 02 media derecha, 03 izquierda…).
  Es un `data-reveal="left|right"` con `IntersectionObserver` sobre el scroll del
  panel.
- En el hero, mientras el video se expande, se **revelan labels verticales** a
  los lados ("Grabado en vivo", "Cursor · clics · flujo real").
- Cada video se precarga al acercarse al viewport interno y **se reproduce
  automáticamente al entrar en foco**. Al aparecer otro video, el anterior se
  pausa; nunca quedan dos reproduciéndose a la vez.

### 3. "Spiral Motion" → los numerales giran con el scroll

Los anillos ornamentales de los numerales (01, 02, …) **giran**
proporcionalmente al scroll del panel y en sentidos alternados (`data-spin`).
El número se contrarrota para mantenerse legible.

---

## Rendimiento y accesibilidad

- Un único listener `scroll` **pasivo** sobre el panel + `requestAnimationFrame`.
- Los videos conservan `preload="none"` + `data-src`: se cargan justo antes de
  entrar, se reproducen muted al ocupar el centro y se pausan al salir.
- Al cerrar el detalle se quitan todos los `src`, liberando los reproductores.
- **`prefers-reduced-motion`**: sin fold ni giros. El video del hero queda a
  tamaño fijo, los reveals se muestran sin animación y el autoplay queda
  desactivado.
- **Móvil**: el fold se desactiva (video a tamaño fijo, sin recorrido extra), los
  labels laterales se ocultan y el recorrido pasa a una columna (media arriba,
  texto abajo).

---

## Cómo probarlo

```bash
cd C:\Users\HP\Desktop\laravel_gothic_portfolio
php artisan serve
```

1. Pulsá el `↗` de una obra → baja el detalle; la demo del hero arranca sola.
2. **Scrollea dentro del panel**: el video se expande a pantalla completa
   (aparecen las esquinas doradas y los labels a los lados) y el título cede.
3. Sigue bajando: el **recorrido** aparece con las pantallas alternando lados y
   los anillos girando suave. Cada video inicia solo cuando llega al centro.
4. Con "reducir movimiento" activo: sin expansión ni giros; todo estático y legible.

## Verificado

- Fold del hero: diseño en los 3 estados (contraído / medio / expandido) dentro
  del panel.
- Recorrido alternado (side-reveal) en desktop y en una columna en móvil.
- Autoplay por visibilidad: un solo video activo, pausa al salir y reset al cerrar.
- `php artisan test` → 2/2. Sin errores JS/PHP. Home sin rastros de la versión
  anterior (showreel / scroll-fx / En acción eliminados).

> El comportamiento *scroll-driven* en vivo (que `--fold` y los giros cambien al
> scrollear el panel) no es reproducible en el navegador headless que uso para
> verificar; se validó el diseño en los estados clave y la matemática del cálculo
> (patrón estándar `scroll` + `requestAnimationFrame`). **Ábrelo y scrollea el
> detalle para confirmarlo**; si el ritmo de la expansión te parece rápido o
> lento, es un solo número (`min-height` del hero y el `/ 0.6` del cálculo).
