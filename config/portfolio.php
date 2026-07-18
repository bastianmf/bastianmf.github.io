<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Static export
    |--------------------------------------------------------------------------
    |
    | The Laravel app remains the editable source. During portfolio:export this
    | flag is forced to true so Blade can replace server-only behavior with
    | static-safe alternatives.
    |
    */

    'static_export' => env('PORTFOLIO_STATIC_EXPORT', false),

    /*
    |--------------------------------------------------------------------------
    | GitHub Pages paths
    |--------------------------------------------------------------------------
    |
    | Use an empty base path for https://user.github.io/ and "/repository" for
    | https://user.github.io/repository/.
    |
    */

    'github_pages_base_path' => env('GITHUB_PAGES_BASE_PATH', ''),
    'site_url' => env('PORTFOLIO_SITE_URL', ''),

    /*
    |--------------------------------------------------------------------------
    | Contact
    |--------------------------------------------------------------------------
    |
    | GitHub Pages cannot process Laravel POST requests. Static exports use this
    | address in a mailto fallback. The local Laravel form still uses the POST
    | route and ContactController.
    |
    */

    'contact_email' => env('PORTFOLIO_CONTACT_EMAIL', ''),
    'contact_subject' => env('PORTFOLIO_CONTACT_SUBJECT', 'Nuevo proyecto desde el portafolio'),

    /*
    |--------------------------------------------------------------------------
    | SEO
    |--------------------------------------------------------------------------
    */

    'meta' => [
        'title' => 'Bastián Medina | Portafolio',
        'description' => 'Portafolio profesional de Bastián Medina: desarrollo web, sistemas Laravel, frontend, diseño UI y experiencias digitales.',
        'image' => 'images/perfil/logo-bastian-medina.png',
    ],

    /*
    |--------------------------------------------------------------------------
    | Public static routes
    |--------------------------------------------------------------------------
    |
    | The current portfolio is a single-page site. Aliases render the same page
    | into folders that GitHub Pages can serve directly.
    |
    */

    'static_routes' => [
        ['path' => '/', 'output' => 'index.html', 'anchor' => null, 'title' => 'Inicio'],
        ['path' => '/sobre-mi', 'output' => 'sobre-mi/index.html', 'anchor' => 'sobre-mi', 'title' => 'Sobre mí'],
        ['path' => '/proyectos', 'output' => 'proyectos/index.html', 'anchor' => 'proyectos', 'title' => 'Proyectos'],
        ['path' => '/habilidades', 'output' => 'habilidades/index.html', 'anchor' => 'habilidades', 'title' => 'Habilidades'],
        ['path' => '/contacto', 'output' => 'contacto/index.html', 'anchor' => 'contacto', 'title' => 'Contacto'],
    ],

    'public_copy_excludes' => [
        '.htaccess',
        'hot',
        'index.php',
        'storage',
    ],
];
