<?php

namespace App\Services;

use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;

/**
 * Mendeteksi berkas APK Android yang tersedia di disk "public".
 *
 * Cukup unggah berkas ke storage/app/public/mobile/decor-v1.0.0.apk maka
 * halaman unduhan dan endpoint versi otomatis memakai rilis terbaru.
 */
class AppRelease
{
    /**
     * Folder relatif di disk "public" tempat berkas APK disimpan.
     */
    public const DIRECTORY = 'mobile';

    /**
     * Rilis terbaru, atau null bila belum ada berkas APK.
     *
     * @return array<string, mixed>|null
     */
    public static function latest(): ?array
    {
        $latest = self::all()[0] ?? null;

        if ($latest !== null && filled($override = config('app_download.android.url'))) {
            $latest['url'] = $override;
        }

        return $latest;
    }

    /**
     * Semua rilis APK, terbaru lebih dulu.
     *
     * @return array<int, array<string, mixed>>
     */
    public static function all(): array
    {
        $disk = Storage::disk('public');

        if (! $disk->exists(self::DIRECTORY)) {
            return [];
        }

        return collect($disk->files(self::DIRECTORY))
            ->filter(fn (string $path) => strtolower(pathinfo($path, PATHINFO_EXTENSION)) === 'apk')
            ->sortByDesc(fn (string $path) => $disk->lastModified($path))
            ->values()
            ->map(fn (string $path) => self::describe($path))
            ->all();
    }

    /**
     * @return array<string, mixed>
     */
    private static function describe(string $path): array
    {
        $disk = Storage::disk('public');
        $filename = basename($path);
        $size = (int) $disk->size($path);
        $modified = (int) $disk->lastModified($path);

        return [
            'version' => self::versionFromFilename($filename, $modified),
            'filename' => $filename,
            'url' => storage_url(self::DIRECTORY . '/' . rawurlencode($filename)),
            'size_bytes' => $size,
            'size_human' => self::humanSize($size),
            'released_at' => Carbon::createFromTimestamp($modified),
            'sha256' => self::checksum($disk->path($path), $filename, $size, $modified),
        ];
    }

    /**
     * Ambil versi dari nama berkas, mis. "decor-v1.0.0.apk" => "1.0.0".
     * Bila tidak ada pola versi, pakai tanggal berkas sebagai label.
     */
    private static function versionFromFilename(string $filename, int $modified): string
    {
        $name = pathinfo($filename, PATHINFO_FILENAME);

        if (preg_match('/v?(\d+\.\d+\.\d+(?:[-+][A-Za-z0-9.]+)?)/i', $name, $matches)) {
            return $matches[1];
        }

        return date('Y.m.d', $modified);
    }

    /**
     * SHA-256 dihitung sekali per berkas lalu disimpan di cache, supaya
     * request berikutnya tidak membaca ulang seluruh isi APK.
     */
    private static function checksum(string $absolutePath, string $filename, int $size, int $modified): ?string
    {
        if (! is_file($absolutePath)) {
            return null;
        }

        $key = 'app-release:sha256:' . md5($filename . '|' . $size . '|' . $modified);

        return Cache::remember($key, now()->addDays(7), function () use ($absolutePath) {
            $hash = @hash_file('sha256', $absolutePath);

            return $hash === false ? null : $hash;
        });
    }

    private static function humanSize(int $bytes): string
    {
        if ($bytes >= 1048576) {
            return number_format($bytes / 1048576, 1, ',', '.') . ' MB';
        }

        if ($bytes >= 1024) {
            return number_format($bytes / 1024, 0, ',', '.') . ' KB';
        }

        return $bytes . ' B';
    }
}
