<?php

use Illuminate\Support\Facades\Route;

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
