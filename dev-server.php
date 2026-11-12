<?php

/**
 * Dev server router untuk `php -S`.
 *
 *   php -S 127.0.0.1:8080 -t public dev-server.php
 *
 * Alasan file ini ada:
 * - `php artisan serve` memakai router bawaan PHP yang tidak membaca
 *   public/.htaccess, sehingga file di /storage/* tidak mendapat header CORS.
 * - Flutter Web (CanvasKit) mengambil gambar dengan mode CORS, jadi tanpa
 *   header Access-Control-Allow-Origin gambar akan diblokir browser.
 *
 * File statis non-gambar tetap dilayani oleh built-in server (return false),
 * sedangkan gambar/dokumen dilayani di sini agar bisa disisipi header CORS.
 */

$publicPath = __DIR__ . '/public';

$uri = urldecode(
    parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH) ?? ''
);

$mediaExtensions = [
    'jpg', 'jpeg', 'png', 'gif', 'webp', 'avif', 'svg', 'ico', 'bmp',
    'mp4', 'webm', 'pdf', 'glb', 'gltf',
];

if ($uri !== '/') {
    $candidate = $publicPath . $uri;

    if (is_file($candidate)) {
        $extension = strtolower(pathinfo($candidate, PATHINFO_EXTENSION));

        if (in_array($extension, $mediaExtensions, true)) {
            header('Access-Control-Allow-Origin: *');
            header('Access-Control-Allow-Methods: GET, HEAD, OPTIONS');
            header('Access-Control-Allow-Headers: *');
            header('Access-Control-Max-Age: 86400');
            header('Cross-Origin-Resource-Policy: cross-origin');

            $method = $_SERVER['REQUEST_METHOD'] ?? 'GET';

            if ($method === 'OPTIONS') {
                http_response_code(204);
                exit;
            }

            header('Content-Type: ' . (mime_content_type($candidate) ?: 'application/octet-stream'));
            header('Content-Length: ' . filesize($candidate));

            if ($method === 'HEAD') {
                exit;
            }

            readfile($candidate);
            exit;
        }

        // CSS/JS/font dsb: biarkan built-in server yang menyajikan.
        return false;
    }
}

// Semua request lain diteruskan ke Laravel.
$_SERVER['SCRIPT_NAME'] = '/index.php';
$_SERVER['SCRIPT_FILENAME'] = $publicPath . '/index.php';

require $publicPath . '/index.php';
