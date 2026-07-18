# Portafolio Bastian Medina

Portafolio personal hecho en Laravel y Blade, con exportacion estatica para
GitHub Pages. Laravel sigue siendo la fuente editable; `dist/` se genera y se
despliega como HTML, CSS, JavaScript e imagenes.

> En PowerShell no uses `\` para continuar comandos. Usa una sola linea o el
> acento grave de PowerShell: `` ` ``.

## Requisitos

- PHP 8.3 o superior.
- Composer 2.
- Node.js 22 o superior.
- npm.

## Instalacion Local En PowerShell

```powershell
composer install
npm ci
Copy-Item .env.example .env
php artisan key:generate
```

Si no tienes `package-lock.json` actualizado despues de cambiar `package.json`:

```powershell
npm install --package-lock-only
```

## Ejecutar Laravel Local

```powershell
php artisan serve
```

Abre:

```text
http://127.0.0.1:8000
```

Laravel local mantiene el formulario `POST /contacto` con CSRF y validacion del
servidor.

## Build Frontend

Este proyecto no usa Vite real. Los assets viven directamente en:

```text
public/css
public/js
public/images
public/fonts
```

El build valida que no existan referencias al servidor de desarrollo de Vite.

```powershell
npm run build
```

## Exportar Version Estatica Local

Para probar en la raiz local:

```powershell
php artisan portfolio:export --base-path="" --site-url="http://localhost:8080" --no-build
```

El mismo comando en varias lineas con PowerShell:

```powershell
php artisan portfolio:export `
  --base-path="" `
  --site-url="http://localhost:8080" `
  --no-build
```

Luego sirve `dist/`:

```powershell
php -S localhost:8080 -t dist
```

Abre:

```text
http://localhost:8080/
http://localhost:8080/proyectos/
http://localhost:8080/contacto/
http://localhost:8080/404.html
```

Deten el servidor con `Ctrl + C`.

## Exportar Para GitHub Pages Con Subruta

Ejemplo para un repositorio llamado `portfolio-bastian`:

```powershell
php artisan portfolio:export --base-path="/portfolio-bastian" --site-url="https://usuario.github.io/portfolio-bastian" --no-build
```

Esto genera assets con rutas como:

```text
/portfolio-bastian/css/app.css
/portfolio-bastian/images/...
```

## Simular Subruta Local En PowerShell

Despues de exportar con `--base-path="/portfolio-bastian"`, ejecuta:

```powershell
.\scripts\preview-github-pages.ps1 -RepositoryName "portfolio-bastian"
```

El script crea:

```text
preview/
└── portfolio-bastian/
    ├── index.html
    ├── css/
    ├── js/
    ├── images/
    └── ...
```

Y sirve:

```text
http://localhost:8080/portfolio-bastian/
```

Tambien puedes cambiar el puerto:

```powershell
.\scripts\preview-github-pages.ps1 -RepositoryName "portfolio-bastian" -Port 8090
```

Deten el servidor con `Ctrl + C`.

## Scripts npm Disponibles

```powershell
npm run build
npm run export
npm run preview
npm run preview:pages
```

- `build`: valida assets estaticos.
- `export`: ejecuta `php artisan portfolio:export --no-build`.
- `preview`: sirve `dist/` en `http://localhost:8080/`.
- `preview:pages`: ejecuta el preview de subruta con PowerShell.

## Salida Generada

```text
dist/
├── index.html
├── sobre-mi/index.html
├── proyectos/index.html
├── habilidades/index.html
├── contacto/index.html
├── 404.html
├── .nojekyll
├── sitemap.xml
├── robots.txt
├── css/
├── js/
└── images/
```

`dist/` no se versiona porque GitHub Actions lo genera como artefacto.

## Variables De Configuracion

En `.env` local o en GitHub Actions variables:

```env
GITHUB_PAGES_BASE_PATH=/portfolio-bastian
PORTFOLIO_SITE_URL=https://usuario.github.io/portfolio-bastian
PORTFOLIO_CONTACT_EMAIL=correo-real@dominio.cl
PORTFOLIO_CONTACT_SUBJECT="Nuevo proyecto desde el portafolio"
```

Valores validos para `GITHUB_PAGES_BASE_PATH`:

```text
""
"/"
"/portfolio-bastian"
"/portfolio-bastian/"
```

El exportador los normaliza a:

```text
""
```

o:

```text
"/portfolio-bastian"
```

## Formulario De Contacto En GitHub Pages

GitHub Pages no ejecuta PHP. Durante `portfolio:export`, el formulario se
convierte en una version estatica que abre `mailto:` y arma el asunto/cuerpo con
JavaScript.

Si `PORTFOLIO_CONTACT_EMAIL` esta vacio, el formulario muestra un mensaje claro
y deshabilita el boton de envio.

## Configurar GitHub Pages

En GitHub:

1. Abre el repositorio.
2. Ve a `Settings > Pages`.
3. En `Build and deployment`, selecciona `GitHub Actions`.
4. Ve a `Settings > Secrets and variables > Actions > Variables`.
5. Crea estas variables:

```text
GITHUB_PAGES_BASE_PATH=/portfolio-bastian
PORTFOLIO_SITE_URL=https://usuario.github.io/portfolio-bastian
PORTFOLIO_CONTACT_EMAIL=correo-real@dominio.cl
```

Si el repositorio es `usuario.github.io`, puedes dejar:

```text
GITHUB_PAGES_BASE_PATH=
PORTFOLIO_SITE_URL=https://usuario.github.io
```

El workflow esta en:

```text
.github/workflows/deploy-pages.yml
```

Se ejecuta en cada push a `main`, instala PHP/Composer/Node, ejecuta `npm ci`,
`npm run build`, genera `.env`, corre `php artisan portfolio:export` y despliega
exclusivamente `dist/`.

## URL Final Esperada

Para un repositorio de proyecto:

```text
https://usuario.github.io/portfolio-bastian/
```

Para un sitio raiz:

```text
https://usuario.github.io/
```

## Validaciones Del Exportador

`php artisan portfolio:export` verifica:

- Existe `dist/index.html`.
- Existe `dist/404.html`.
- Existe `dist/.nojekyll`.
- No hay archivos `.php` en `dist/`.
- No hay referencias a `C:\`, `file://`, `localhost:5173` ni `127.0.0.1:5173`.
- Los assets enlazados existen.
- Los aliases existen.
- Los enlaces absolutos respetan `GITHUB_PAGES_BASE_PATH`.
- No hay dobles barras incorrectas.
- `sitemap.xml` y canonical usan `PORTFOLIO_SITE_URL` cuando esta configurado.

Para pruebas locales se permite `http://localhost:8080` si se entrega
explicitamente con `--site-url`.

## Problemas Frecuentes

Si CSS o imagenes no cargan en GitHub Pages:

- Revisa `GITHUB_PAGES_BASE_PATH`.
- Revisa `PORTFOLIO_SITE_URL`.
- Exporta otra vez y prueba con `preview-github-pages.ps1`.

Si PowerShell falla con comandos multilínea:

- No uses `\`.
- Usa una sola linea o el acento grave `` ` ``.

Si el formulario no envia en GitHub Pages:

- Configura `PORTFOLIO_CONTACT_EMAIL`.
- Recuerda que Pages no ejecuta Laravel ni PHP.
