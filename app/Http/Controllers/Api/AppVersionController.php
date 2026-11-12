<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\AppRelease;

class AppVersionController extends Controller
{
    /**
     * Versi aplikasi Android terbaru.
     *
     * Dipakai aplikasi mobile untuk memeriksa apakah ada pembaruan.
     * Berkas APK dideteksi otomatis dari storage/app/public/mobile/.
     */
    public function show()
    {
        $release = AppRelease::latest();

        if (! $release) {
            return response()->json([
                'success' => false,
                'message' => 'Belum ada aplikasi Android yang tersedia.',
                'data' => null,
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'Versi aplikasi terbaru.',
            'data' => [
                'platform' => 'android',
                'version' => $release['version'],
                'url' => $release['url'],
                'size' => $release['size_human'],
                'size_bytes' => $release['size_bytes'],
                'sha256' => $release['sha256'],
                'min_os' => config('app_download.android.min_os'),
                'released_at' => $release['released_at']->toIso8601String(),
                'notes' => config('app_download.android.notes'),
            ],
        ]);
    }
}
