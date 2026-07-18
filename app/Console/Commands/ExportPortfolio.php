<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Contracts\Http\Kernel;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use RuntimeException;
use Symfony\Component\Process\Process;

class ExportPortfolio extends Command
{
    protected $signature = 'portfolio:export
        {--base-path= : GitHub Pages base path, for example /repository}
        {--site-url= : Public site URL used for canonical, sitemap and OG URLs}
        {--no-build : Skip npm run build}';

    protected $description = 'Export the Laravel portfolio as a static GitHub Pages site.';

    private Filesystem $files;

    /** @var array<int, string> */
    private array $warnings = [];

    public function handle(Filesystem $files): int
    {
        $this->files = $files;

        $basePathOption = $this->option('base-path');
        $siteUrlOption = $this->option('site-url');

        $basePath = $this->normalizeBasePath(
            (string) ($basePathOption !== null ? $basePathOption : config('portfolio.github_pages_base_path', ''))
        );
        $siteUrl = $this->normalizeSiteUrl(
            (string) ($siteUrlOption !== null ? $siteUrlOption : config('portfolio.site_url', ''))
        );
        $distPath = base_path('dist');

        $this->validateDistPath($distPath);
        $this->prepareRuntimeConfig($basePath, $siteUrl);

        $this->info('Preparing static export...');
        $this->cleanDist($distPath);

        if (! $this->option('no-build')) {
            $this->runFrontendBuild();
        }

        $assetCount = $this->copyPublicAssets($distPath);
        $pageCount = $this->renderPages($distPath);

        $this->writeNoJekyll($distPath);
        $this->writeRobots($distPath, $siteUrl);
        $this->writeSitemap($distPath, $siteUrl);

        $this->verifyDist($distPath, $basePath, $siteUrl);

        $this->newLine();
        $this->info('Static export complete.');
        $this->line("Pages generated: {$pageCount}");
        $this->line("Assets copied: {$assetCount}");
        $this->line('Output: '.base_path('dist'));

        if ($this->warnings !== []) {
            $this->newLine();
            $this->warn('Warnings:');
            foreach ($this->warnings as $warning) {
                $this->line('- '.$warning);
            }
        }

        return self::SUCCESS;
    }

    private function prepareRuntimeConfig(string $basePath, string $siteUrl): void
    {
        config([
            'app.asset_url' => $basePath === '' ? '/' : $basePath,
            'app.debug' => false,
            'cache.default' => 'file',
            'portfolio.static_base_path' => $basePath,
            'portfolio.static_export' => true,
            'portfolio.site_url' => $siteUrl,
            'queue.default' => 'sync',
            'session.driver' => 'file',
        ]);

        if ($siteUrl !== '') {
            config(['app.url' => $siteUrl]);
        }
    }

    private function normalizeBasePath(string $basePath): string
    {
        $basePath = trim($basePath);

        if ($basePath === '' || $basePath === '/') {
            return '';
        }

        return '/'.trim($basePath, '/');
    }

    private function normalizeSiteUrl(string $siteUrl): string
    {
        $siteUrl = trim($siteUrl);

        if ($siteUrl === '') {
            $this->warnings[] = 'PORTFOLIO_SITE_URL is empty; sitemap URLs will be root-relative.';
            return '';
        }

        if (! Str::startsWith($siteUrl, ['http://', 'https://'])) {
            $siteUrl = 'https://'.$siteUrl;
        }

        return rtrim($siteUrl, '/');
    }

    private function validateDistPath(string $distPath): void
    {
        $projectRoot = realpath(base_path());
        $distParent = realpath(dirname($distPath));

        if ($projectRoot === false || $distParent === false || ! Str::startsWith($distParent, $projectRoot)) {
            throw new RuntimeException('Refusing to write dist outside the project root.');
        }
    }

    private function cleanDist(string $distPath): void
    {
        if ($this->files->exists($distPath)) {
            $this->files->deleteDirectory($distPath);
        }

        $this->files->makeDirectory($distPath, 0755, true);
    }

    private function runFrontendBuild(): void
    {
        $packagePath = base_path('package.json');

        if (! $this->files->exists($packagePath)) {
            $this->line('No package.json found; skipping npm build.');
            return;
        }

        $package = json_decode((string) $this->files->get($packagePath), true);

        if (! isset($package['scripts']['build'])) {
            $this->line('No npm build script found; skipping npm build.');
            return;
        }

        $npm = PHP_OS_FAMILY === 'Windows' ? 'npm.cmd' : 'npm';
        $process = new Process([$npm, 'run', 'build'], base_path());
        $process->setTimeout(300);

        $this->line('Running npm run build...');
        $process->run(function (string $type, string $buffer): void {
            $this->output->write($buffer);
        });

        if (! $process->isSuccessful()) {
            throw new RuntimeException('npm run build failed.');
        }
    }

    private function copyPublicAssets(string $distPath): int
    {
        $publicPath = public_path();
        $exclude = collect(config('portfolio.public_copy_excludes', []))
            ->map(fn (string $item) => trim(str_replace('\\', '/', $item), '/'))
            ->filter()
            ->all();
        $count = 0;

        foreach ($this->files->allFiles($publicPath) as $file) {
            $relative = str_replace('\\', '/', $file->getRelativePathname());
            $segments = explode('/', $relative);

            if (in_array($segments[0], $exclude, true) || Str::endsWith($relative, '.php')) {
                continue;
            }

            $target = $distPath.DIRECTORY_SEPARATOR.str_replace('/', DIRECTORY_SEPARATOR, $relative);
            $this->files->ensureDirectoryExists(dirname($target));
            $this->files->copy($file->getPathname(), $target);
            $count++;
        }

        return $count;
    }

    private function renderPages(string $distPath): int
    {
        $routes = config('portfolio.static_routes', []);
        $count = 0;

        foreach ($routes as $route) {
            $html = $this->renderRoute(
                '/',
                (string) ($route['path'] ?? '/'),
                $route['anchor'] ?? null,
                (string) ($route['title'] ?? '')
            );

            $this->writeHtml($distPath, (string) $route['output'], $html);
            $count++;
        }

        $this->writeHtml($distPath, '404.html', $this->renderStatic404());
        $count++;

        return $count;
    }

    private function renderRoute(string $requestPath, string $canonicalPath, ?string $anchor, string $title): string
    {
        $this->setPageMeta($canonicalPath, $title);

        $kernel = app(Kernel::class);
        $request = Request::create($requestPath, 'GET');
        $response = $kernel->handle($request);
        $kernel->terminate($request, $response);

        if ($response->getStatusCode() !== 200) {
            throw new RuntimeException("Route {$requestPath} returned HTTP {$response->getStatusCode()}.");
        }

        $html = (string) $response->getContent();

        if ($anchor) {
            $html = $this->injectAnchorScroll($html, $anchor);
        }

        return $this->postProcessHtml($html);
    }

    private function renderStatic404(): string
    {
        $this->setPageMeta('/404', 'Página no encontrada');

        $request = Request::create('/404', 'GET');
        app()->instance('request', $request);

        return $this->postProcessHtml((string) view('static.404')->render());
    }

    private function setPageMeta(string $path, string $title): void
    {
        $siteUrl = $this->normalizeSiteUrlForMeta();
        $metaTitle = config('portfolio.meta.title');

        config([
            'portfolio.current_canonical_url' => $siteUrl === '' ? null : $this->absoluteUrl($siteUrl, $this->canonicalPath($path)),
            'portfolio.current_og_image_url' => $siteUrl === '' ? null : $this->absoluteUrl($siteUrl, config('portfolio.meta.image')),
            'portfolio.current_page_title' => trim($title) === '' ? $metaTitle : "{$title} | Bastián Medina",
        ]);
    }

    private function normalizeSiteUrlForMeta(): string
    {
        $siteUrl = (string) config('portfolio.site_url', '');

        return $siteUrl === '' ? '' : rtrim($siteUrl, '/');
    }

    private function absoluteUrl(string $siteUrl, string $path): string
    {
        return rtrim($siteUrl, '/').'/'.ltrim($path, '/');
    }

    private function canonicalPath(string $path): string
    {
        if ($path === '/' || trim($path) === '') {
            return '/';
        }

        return '/'.trim($path, '/').'/';
    }

    private function injectAnchorScroll(string $html, string $anchor): string
    {
        $encodedAnchor = json_encode($anchor, JSON_THROW_ON_ERROR);
        $script = <<<HTML

    <script>
        window.addEventListener('load', function () {
            var id = {$encodedAnchor};
            if (window.location.hash) return;
            var target = document.getElementById(id);
            if (!target) return;
            target.scrollIntoView();
            history.replaceState(null, '', '#' + id);
        });
    </script>
HTML;

        return str_replace('</body>', $script."\n</body>", $html);
    }

    private function postProcessHtml(string $html): string
    {
        $basePath = (string) config('portfolio.static_base_path', '');
        $siteUrl = $this->normalizeSiteUrlForMeta();
        $allowedLocalUrl = $this->isLocalSiteUrl($siteUrl) ? rtrim($siteUrl, '/') : null;

        return preg_replace_callback(
            '#https?://(?:localhost|127\.0\.0\.1)(?::\d+)?(?=/|["\'\s<>)])#i',
            function (array $matches) use ($basePath, $allowedLocalUrl): string {
                $origin = rtrim($matches[0], '/');

                if ($allowedLocalUrl !== null && strcasecmp($origin, $allowedLocalUrl) === 0) {
                    return $matches[0];
                }

                return $basePath;
            },
            $html
        ) ?? $html;
    }

    private function writeHtml(string $distPath, string $relativePath, string $html): void
    {
        $target = $distPath.DIRECTORY_SEPARATOR.str_replace('/', DIRECTORY_SEPARATOR, $relativePath);
        $this->files->ensureDirectoryExists(dirname($target));
        $this->files->put($target, $html);
    }

    private function writeNoJekyll(string $distPath): void
    {
        $this->files->put($distPath.DIRECTORY_SEPARATOR.'.nojekyll', '');
    }

    private function writeRobots(string $distPath, string $siteUrl): void
    {
        $content = "User-agent: *\nAllow: /\n";

        if ($siteUrl !== '') {
            $content .= 'Sitemap: '.$this->absoluteUrl($siteUrl, 'sitemap.xml')."\n";
        }

        $this->files->put($distPath.DIRECTORY_SEPARATOR.'robots.txt', $content);
    }

    private function writeSitemap(string $distPath, string $siteUrl): void
    {
        $routes = config('portfolio.static_routes', []);
        $xml = ['<?xml version="1.0" encoding="UTF-8"?>'];
        $xml[] = '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';

        foreach ($routes as $route) {
            $path = (string) ($route['path'] ?? '/');
            $loc = $siteUrl === ''
                ? $this->pathForSitemap($path)
                : $this->absoluteUrl($siteUrl, $this->canonicalPath($path));

            $xml[] = '    <url>';
            $xml[] = '        <loc>'.e($loc).'</loc>';
            $xml[] = '    </url>';
        }

        $xml[] = '</urlset>';
        $this->files->put($distPath.DIRECTORY_SEPARATOR.'sitemap.xml', implode("\n", $xml)."\n");
    }

    private function pathForSitemap(string $path): string
    {
        $basePath = (string) config('portfolio.static_base_path', '');
        $path = $path === '/' ? '/' : '/'.trim($path, '/').'/';

        return ($basePath === '' ? '' : $basePath).$path;
    }

    private function verifyDist(string $distPath, string $basePath, string $siteUrl): void
    {
        $brokenLinks = [];
        $htmlFiles = [];
        $requiredFiles = [
            'index.html',
            '404.html',
            '.nojekyll',
            'robots.txt',
            'sitemap.xml',
        ];

        foreach ($requiredFiles as $requiredFile) {
            if (! $this->files->exists($distPath.DIRECTORY_SEPARATOR.$requiredFile)) {
                throw new RuntimeException("Required file missing in dist: {$requiredFile}");
            }
        }

        foreach (config('portfolio.static_routes', []) as $route) {
            $output = (string) ($route['output'] ?? '');

            if ($output !== '' && ! $this->files->exists($distPath.DIRECTORY_SEPARATOR.str_replace('/', DIRECTORY_SEPARATOR, $output))) {
                throw new RuntimeException("Static alias missing in dist: {$output}");
            }
        }

        foreach ($this->files->allFiles($distPath) as $file) {
            $relative = str_replace('\\', '/', $file->getRelativePathname());

            if ($file->getExtension() === 'php') {
                throw new RuntimeException("PHP file found in dist: {$relative}");
            }

            if (in_array($file->getExtension(), ['html', 'css', 'js', 'json', 'txt', 'xml', 'svg'], true)) {
                $content = (string) $this->files->get($file->getPathname());

                foreach (['localhost:5173', '127.0.0.1:5173', 'C:\\', 'file://', '<?php'] as $needle) {
                    if (str_contains($content, $needle)) {
                        throw new RuntimeException("Forbidden reference '{$needle}' found in dist/{$relative}");
                    }
                }

                $this->assertNoUnexpectedLocalUrls($content, $relative, $siteUrl);
                $this->assertNoBadDoubleSlashes($content, $relative);

                if ($file->getExtension() === 'html') {
                    $htmlFiles[] = $relative;

                    foreach ($this->extractLocalLinks($content) as $link) {
                        $this->assertLinkUsesBasePath($link, $relative, $basePath);

                        if (! $this->localLinkExists($distPath, $relative, $link, $basePath)) {
                            $brokenLinks[] = "{$relative} -> {$link}";
                        }
                    }
                }

                if ($file->getExtension() === 'css') {
                    foreach ($this->extractCssUrls($content) as $link) {
                        $this->assertLinkUsesBasePath($link, $relative, $basePath);

                        if (! $this->localLinkExists($distPath, $relative, $link, $basePath)) {
                            $brokenLinks[] = "{$relative} -> {$link}";
                        }
                    }
                }
            }
        }

        if (! in_array('index.html', $htmlFiles, true) || ! in_array('404.html', $htmlFiles, true)) {
            throw new RuntimeException('Expected index.html and 404.html to be present as HTML files.');
        }

        $this->verifySeoFiles($distPath, $siteUrl);

        if ($brokenLinks !== []) {
            throw new RuntimeException("Broken local links found:\n".implode("\n", $brokenLinks));
        }
    }

    private function assertNoUnexpectedLocalUrls(string $content, string $relative, string $siteUrl): void
    {
        preg_match_all('#https?://(?:localhost|127\.0\.0\.1)(?::\d+)?[^\s"\'<>)]*#i', $content, $matches);
        $allowedLocalUrl = $this->isLocalSiteUrl($siteUrl) ? rtrim($siteUrl, '/') : null;

        foreach ($matches[0] ?? [] as $url) {
            if ($allowedLocalUrl !== null && Str::startsWith($url, $allowedLocalUrl)) {
                continue;
            }

            throw new RuntimeException("Unexpected local URL '{$url}' found in dist/{$relative}");
        }
    }

    private function isLocalSiteUrl(string $siteUrl): bool
    {
        $host = parse_url($siteUrl, PHP_URL_HOST);

        return in_array($host, ['localhost', '127.0.0.1'], true);
    }

    private function assertNoBadDoubleSlashes(string $content, string $relative): void
    {
        preg_match_all('/\s(?:href|src|data-src|action)=["\']([^"\']+)["\']/i', $content, $matches);

        foreach ($matches[1] ?? [] as $link) {
            $link = html_entity_decode($link);

            if (Str::startsWith($link, ['http://', 'https://', 'data:', 'mailto:'])) {
                continue;
            }

            if (preg_match('#//+#', $link)) {
                throw new RuntimeException("Bad double slash URL '{$link}' found in dist/{$relative}");
            }
        }
    }

    private function assertLinkUsesBasePath(string $link, string $relative, string $basePath): void
    {
        if ($basePath === '') {
            return;
        }

        $link = html_entity_decode($link);

        if (! Str::startsWith($link, '/')) {
            return;
        }

        if (! Str::startsWith($link, $basePath.'/') && $link !== $basePath) {
            throw new RuntimeException("Link '{$link}' in dist/{$relative} does not use base path '{$basePath}'.");
        }
    }

    private function verifySeoFiles(string $distPath, string $siteUrl): void
    {
        $sitemap = (string) $this->files->get($distPath.DIRECTORY_SEPARATOR.'sitemap.xml');
        $index = (string) $this->files->get($distPath.DIRECTORY_SEPARATOR.'index.html');

        if ($siteUrl === '') {
            return;
        }

        $expectedRoot = $this->absoluteUrl($siteUrl, '/');

        if (! str_contains($sitemap, '<loc>'.$expectedRoot.'</loc>')) {
            throw new RuntimeException("sitemap.xml does not include expected root URL {$expectedRoot}");
        }

        if (! str_contains($index, 'rel="canonical" href="'.$expectedRoot.'"')) {
            throw new RuntimeException("index.html canonical does not match {$expectedRoot}");
        }
    }

    /**
     * @return array<int, string>
     */
    private function extractLocalLinks(string $html): array
    {
        preg_match_all('/\s(?:href|src|data-src)=["\']([^"\']+)["\']/i', $html, $matches);

        return array_values(array_unique(array_filter($matches[1] ?? [], function (string $link): bool {
            $link = html_entity_decode($link);

            return $link !== ''
                && ! Str::startsWith($link, ['#', 'http://', 'https://', 'mailto:', 'tel:', 'data:', 'javascript:']);
        })));
    }

    /**
     * @return array<int, string>
     */
    private function extractCssUrls(string $css): array
    {
        preg_match_all('/url\(\s*([\'"]?)(.*?)\1\s*\)/i', $css, $matches);

        return array_values(array_unique(array_filter($matches[2] ?? [], function (string $link): bool {
            $link = html_entity_decode(trim($link));

            return $link !== ''
                && ! Str::startsWith($link, ['#', 'http://', 'https://', 'data:']);
        })));
    }

    private function localLinkExists(string $distPath, string $currentFile, string $link, string $basePath): bool
    {
        $link = html_entity_decode($link);
        $link = explode('#', explode('?', $link)[0])[0];

        if ($link === '') {
            return true;
        }

        if ($basePath !== '' && Str::startsWith($link, $basePath.'/')) {
            $link = substr($link, strlen($basePath));
        } elseif ($basePath !== '' && $link === $basePath) {
            $link = '/';
        }

        if (Str::startsWith($link, '/')) {
            $candidate = $distPath.DIRECTORY_SEPARATOR.ltrim(str_replace('/', DIRECTORY_SEPARATOR, $link), DIRECTORY_SEPARATOR);
        } else {
            $candidate = $distPath.DIRECTORY_SEPARATOR.dirname($currentFile).DIRECTORY_SEPARATOR.str_replace('/', DIRECTORY_SEPARATOR, $link);
        }

        if (Str::endsWith($link, '/')) {
            $candidate .= 'index.html';
        } elseif (! pathinfo($candidate, PATHINFO_EXTENSION) && $this->files->isDirectory($candidate)) {
            $candidate .= DIRECTORY_SEPARATOR.'index.html';
        }

        return $this->files->exists($candidate);
    }
}
