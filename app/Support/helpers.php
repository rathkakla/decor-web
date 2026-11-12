<?php

use Illuminate\Support\Facades\Route;

if (! function_exists('storage_url')) {
    /**
     * URL publik untuk file di disk "public".
     *
     * Sebagian data lama menyimpan URL absolut (hasil asset()) atau sudah
     * berprefix "storage/", sehingga tidak boleh ditambahi prefix lagi.
     */
    function storage_url(?string $path, ?string $default = null): ?string
    {
        if ($path === null || trim($path) === '') {
            return $default;
        }

        if (preg_match('#^https?://#i', $path)) {
            // Data lama bisa menyimpan URL absolut ke host lokal (127.0.0.1),
            // yang tidak bisa diakses dari HP. Ubah kembali menjadi URL relatif
            // agar mengikuti host yang sedang dipakai (mis. domain ngrok).
            $host = parse_url($path, PHP_URL_HOST);
            $urlPath = parse_url($path, PHP_URL_PATH) ?: '';
            $localHosts = ['127.0.0.1', 'localhost', '::1'];

            if (! in_array($host, $localHosts, true)) {
                return $path;
            }

            $path = $urlPath;
        }

        // Sebagian data hanya menyimpan path relatif disk "public" tanpa
        // prefix "storage/" (mis. "designers/portfolios/x.jpg"). Tanpa prefix
        // tersebut URL yang dihasilkan akan 404, jadi tambahkan bila belum ada.
        $relative = ltrim($path, '/');

        if ($relative !== '' && ! str_starts_with($relative, 'storage/')) {
            $relative = 'storage/' . $relative;
        }

        return asset($relative);
    }
}

if (! function_exists('b2b_route')) {
    /**
     * Generate URL route B2B dengan aman.
     *
     * Tautan antar-tahap B2B kadang menunjuk model yang belum ada pada tahap
     * tersebut (mis. breadcrumb menuju quotation padahal RFQ belum dikutip).
     * route() biasa akan melempar UrlGenerationException dan membuat halaman
     * 500. Helper ini mengembalikan fallback (default "#") bila route tidak
     * terdaftar atau parameternya belum tersedia.
     */
    function b2b_route(string $name, mixed $params = [], string $fallback = '#'): string
    {
        if (! Route::has($name)) {
            return $fallback;
        }

        try {
            return route($name, $params);
        } catch (\Throwable $e) {
            return $fallback;
        }
    }
}
