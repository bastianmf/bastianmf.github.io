param(
    [string] $RepositoryName = "",
    [int] $Port = 8080
)

$ErrorActionPreference = "Stop"

$ScriptRoot = Split-Path -Parent $MyInvocation.MyCommand.Path
$ProjectRoot = Resolve-Path (Join-Path $ScriptRoot "..")
$DistPath = Join-Path $ProjectRoot "dist"
$PreviewRoot = Join-Path $ProjectRoot "preview"

if (-not (Get-Command php -ErrorAction SilentlyContinue)) {
    throw "PHP was not found in PATH. Install PHP or add it to PATH before running the preview server."
}

if (-not (Test-Path (Join-Path $DistPath "index.html"))) {
    throw "dist/index.html was not found. Run php artisan portfolio:export before previewing."
}

if ([string]::IsNullOrWhiteSpace($RepositoryName)) {
    $RepositoryName = $env:GITHUB_PAGES_BASE_PATH
}

if ([string]::IsNullOrWhiteSpace($RepositoryName)) {
    $RepositoryName = "portfolio-bastian"
}

$RepositoryName = ($RepositoryName -replace '^[\\/]+|[\\/]+$', '')

if ([string]::IsNullOrWhiteSpace($RepositoryName)) {
    throw "RepositoryName cannot be empty when simulating a GitHub Pages subroute."
}

New-Item -ItemType Directory -Path $PreviewRoot -Force | Out-Null

$PreviewSite = Join-Path $PreviewRoot $RepositoryName
$PreviewRootFull = [System.IO.Path]::GetFullPath($PreviewRoot)
$PreviewSiteFull = [System.IO.Path]::GetFullPath($PreviewSite)

if (-not $PreviewSiteFull.StartsWith($PreviewRootFull, [System.StringComparison]::OrdinalIgnoreCase)) {
    throw "Refusing to write preview outside the preview directory."
}

if (Test-Path $PreviewSite) {
    Remove-Item -LiteralPath $PreviewSite -Recurse -Force
}

New-Item -ItemType Directory -Path $PreviewSite -Force | Out-Null
Get-ChildItem -LiteralPath $DistPath -Force | Copy-Item -Destination $PreviewSite -Recurse -Force

$Url = "http://localhost:$Port/$RepositoryName/"

Write-Host ""
Write-Host "GitHub Pages preview prepared at:" -ForegroundColor Cyan
Write-Host "  $PreviewSiteFull"
Write-Host ""
Write-Host "Open:" -ForegroundColor Cyan
Write-Host "  $Url"
Write-Host ""
Write-Host "Press Ctrl + C to stop the server."
Write-Host ""

$ErrorActionPreference = "Continue"
& php -S "localhost:$Port" -t $PreviewRoot
