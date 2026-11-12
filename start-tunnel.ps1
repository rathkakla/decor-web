<#
    Menjalankan backend DECOR untuk diakses lewat Cloudflare Quick Tunnel.

        .\start-tunnel.ps1

    Yang dilakukan:
    1. Menjalankan `php -S 127.0.0.1:8080 dev-server.php`
       (router dev-server.php menambahkan CORS untuk /storage/*).
    2. Menjalankan `cloudflared tunnel --url http://localhost:8080`.
    3. Menampilkan URL https://*.trycloudflare.com yang bisa dipakai
       sebagai base URL di Flutter.

    Hentikan dengan Ctrl+C pada jendela ini.
#>

$ErrorActionPreference = 'Stop'

$port       = 8080
$root       = $PSScriptRoot
$cloudflared = Join-Path $env:LOCALAPPDATA 'cloudflared\cloudflared.exe'
$logDir     = Join-Path $env:TEMP 'decor-tunnel'

if (-not (Test-Path $cloudflared)) {
    throw "cloudflared tidak ditemukan di $cloudflared. Download dari https://github.com/cloudflare/cloudflared/releases"
}

New-Item -ItemType Directory -Force -Path $logDir | Out-Null
$cfLog = Join-Path $logDir 'cloudflared.log'
Remove-Item $cfLog -ErrorAction SilentlyContinue

Write-Host "[1/2] Menjalankan PHP dev server di http://127.0.0.1:$port ..." -ForegroundColor Cyan
$php = Start-Process -FilePath 'php' `
    -ArgumentList '-S', "127.0.0.1:$port", '-t', 'public', 'dev-server.php' `
    -WorkingDirectory $root -WindowStyle Hidden -PassThru `
    -RedirectStandardOutput (Join-Path $logDir 'php.out.log') `
    -RedirectStandardError  (Join-Path $logDir 'php.err.log')

Start-Sleep -Seconds 2

Write-Host "[2/2] Menjalankan Cloudflare Quick Tunnel ..." -ForegroundColor Cyan
$cf = Start-Process -FilePath $cloudflared `
    -ArgumentList 'tunnel', '--url', "http://localhost:$port", '--no-autoupdate', '--logfile', $cfLog, '--loglevel', 'info' `
    -WindowStyle Hidden -PassThru

$url = $null
for ($i = 0; $i -lt 30 -and -not $url; $i++) {
    Start-Sleep -Seconds 1
    if (Test-Path $cfLog) {
        $match = Select-String -Path $cfLog -Pattern 'https://[a-z0-9-]+\.trycloudflare\.com' -ErrorAction SilentlyContinue |
                 Select-Object -First 1
        if ($match) { $url = $match.Matches[0].Value }
    }
}

if (-not $url) {
    Write-Host "Gagal mendapatkan URL tunnel. Cek log: $cfLog" -ForegroundColor Red
} else {
    Write-Host ''
    Write-Host '=========================================================' -ForegroundColor Green
    Write-Host ' BASE URL untuk Flutter (web & mobile):' -ForegroundColor Green
    Write-Host "   $url" -ForegroundColor Yellow
    Write-Host '=========================================================' -ForegroundColor Green
    Write-Host ''
    Write-Host "PHP PID          : $($php.Id)"
    Write-Host "cloudflared PID  : $($cf.Id)"
    Write-Host "Log              : $logDir"
    Write-Host ''
    Write-Host 'Catatan: URL trycloudflare berubah setiap kali skrip ini dijalankan ulang.'
}

Write-Host ''
Write-Host 'Tekan Ctrl+C untuk menghentikan...' -ForegroundColor DarkGray
try {
    Wait-Process -Id $cf.Id
} finally {
    Stop-Process -Id $cf.Id -Force -ErrorAction SilentlyContinue
    Stop-Process -Id $php.Id -Force -ErrorAction SilentlyContinue
}
