<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class AiDesignController extends Controller
{
    // ─── Prompt berdasarkan kombinasi Room Type + Style ───────────────────────
    private function getPrompt(string $roomType, string $style): string
    {
        $roomDescriptions = [
            'living-room' => 'living room with sofa, coffee table, TV area, and ambient lighting',
            'bedroom'     => 'bedroom with bed, wardrobe, nightstand, and cozy sleeping area',
            'kitchen'     => 'kitchen with countertops, cabinets, cooking area, and dining space',
            'bathroom'    => 'bathroom with bathtub or shower, vanity mirror, sink, and tiles',
            'garden'      => 'garden or outdoor space with plants, seating area, and natural landscaping',
            'dining-room' => 'dining room with dining table, chairs, and elegant lighting',
            'home-office' => 'home office with desk, ergonomic chair, bookshelves, and work setup',
            'kids-room'   => 'kids bedroom with playful furniture, colorful decor, and study area',
        ];

        $styleDescriptions = [
            'Scandinavian' => 'Scandinavian style, light oak wood, white and warm beige tones, minimalist hygge decor, linen textures, indoor plants, soft diffused natural lighting',
            'Industrial'   => 'Industrial loft style, exposed brick walls, black metal fixtures, raw concrete surfaces, Edison bulb pendant lights, reclaimed wood accents, urban atmosphere',
            'Minimalist'   => 'Minimalist zen style, pure white walls, clean geometric lines, neutral palette, uncluttered open space, concealed storage, single statement decor piece',
            'Modern'       => 'Modern contemporary style, sleek furniture, warm neutral tones, large windows, metallic chrome accents, architectural lighting, abstract wall art',
            'Classic'      => 'Classic traditional style, ornate dark mahogany furniture, deep jewel tone walls, Persian area rug, crown molding, antique brass fixtures, heavy velvet drapes',
            'Bohemian'     => 'Bohemian boho style, layered colorful textiles, rattan and wicker furniture, macrame wall art, eclectic mix of patterns, warm candlelight, exotic plants',
            'Japanese'     => 'Japanese wabi-sabi style, low profile furniture, natural wood and bamboo, shoji screen dividers, zen minimalism, neutral earth tones, bonsai plants',
            'Mediterranean'=> 'Mediterranean coastal style, terracotta tiles, arched doorways, white stucco walls, vibrant blue and yellow accents, mosaic patterns, wrought iron details',
        ];

        $room  = $roomDescriptions[$roomType]  ?? $roomDescriptions['living-room'];
        $style = $styleDescriptions[$style]    ?? $styleDescriptions['Scandinavian'];

        return "Keep the original room layout, walls, windows and camera perspective. A professionally designed {$room}, {$style}, photorealistic, high resolution professional interior photography, architectural digest quality, 8k ultra detailed, beautiful lighting";
    }

    // ─── Response helper: selalu JSON + status code yang benar ────────────────
    private function fail(string $message, int $status, string $code, array $errors = []): JsonResponse
    {
        // Beberapa client mobile (Dio) melempar exception untuk SEMUA status
        // >= 400 sehingga pesan error tidak pernah sampai ke user. Jika
        // AI_FRIENDLY_HTTP=true, error yang sudah ditangani tetap dikirim
        // dengan HTTP 200 + success:false agar bisa dibaca aplikasi.
        if (config('services.stability.friendly_http', false)) {
            $status = 200;
        }

        return response()->json([
            'success' => false,
            'message' => $message,
            'code'    => $code,
            'errors'  => $errors ?: null,
        ], $status);
    }

    // ─── Main Generate ────────────────────────────────────────────────────────
    public function generate(Request $request): JsonResponse
    {
        // Jaring pengaman terakhir: request mobile TIDAK boleh menerima HTML
        // atau 500 tanpa body. Semua Throwable diubah jadi JSON yang terbaca.
        try {
            return $this->handleGenerate($request);
        } catch (\Throwable $e) {
            Log::error('AiDesignController fatal: ' . $e->getMessage(), [
                'exception' => get_class($e),
                'file'      => $e->getFile() . ':' . $e->getLine(),
            ]);

            return $this->fail(
                'Terjadi kesalahan pada server saat memproses desain. Coba lagi beberapa saat.',
                500,
                'AI_INTERNAL_ERROR'
            );
        }
    }

    private function handleGenerate(Request $request): JsonResponse
    {
        // Naikkan batas waktu bila server mengizinkan, lalu sesuaikan timeout
        // cURL dengan batas waktu efektif. Tanpa ini, request yang lebih lama
        // dari max_execution_time akan mematikan PHP -> 500 tanpa log.
        if (function_exists('set_time_limit')) {
            @set_time_limit(180);
        }

        $maxExecution = (int) ini_get('max_execution_time');

        if (!function_exists('curl_init')) {
            Log::error('AiDesignController: ekstensi cURL tidak aktif di server.');
            return $this->fail(
                'Server belum mendukung fitur desain AI (ekstensi cURL tidak aktif). Silakan hubungi admin.',
                503,
                'AI_SERVER_UNSUPPORTED'
            );
        }

        // ── Terima alias field dari client mobile (`image`, `roomType`, dll) ──
        $roomImage = $request->file('room_image') ?: $request->file('image');

        $roomType = $request->input('room_type', $request->input('roomType', 'living-room'));
        $style    = $request->input('style', $request->input('design_style', 'Scandinavian'));

        $roomType = is_string($roomType) && $roomType !== '' ? $roomType : 'living-room';
        $style    = is_string($style) && $style !== '' ? $style : 'Scandinavian';

        $input = [
            'room_image' => $roomImage,
            'room_type'  => $roomType,
            'style'      => $style,
        ];

        // ── Validasi manual agar selalu JSON 422 (bukan redirect 302) ─────────
        $validator = Validator::make($input, [
            'room_image' => 'required|image|mimes:jpeg,png,jpg,webp|max:20480',
            'room_type'  => 'nullable|string',
            'style'      => 'nullable|string',
        ], [
            'room_image.required' => 'Foto ruangan wajib diunggah. Kirim file pada field "room_image" (atau "image").',
            'room_image.image'    => 'File yang diunggah harus berupa gambar.',
            'room_image.mimes'    => 'Format gambar harus JPEG, PNG, atau WEBP.',
            'room_image.max'      => 'Ukuran gambar maksimal 20MB.',
            'room_image.uploaded' => 'Gambar gagal diunggah. Periksa koneksi atau ukuran file. Jika foto dari kamera terlalu besar, kompres dulu.',
        ]);

        if ($validator->fails()) {
            return $this->fail(
                $validator->errors()->first(),
                422,
                'VALIDATION_ERROR',
                $validator->errors()->toArray()
            );
        }

        $stabilityKey = config('services.stability.key');

        if (empty($stabilityKey)) {
            Log::error('AiDesignController: STABILITY_API_KEY tidak tersedia di konfigurasi.');
            return $this->fail(
                'Layanan desain AI belum dikonfigurasi. Silakan hubungi admin.',
                503,
                'AI_NOT_CONFIGURED'
            );
        }

        $file = $roomImage;

        // File bisa hilang kalau upload gagal di level PHP (post_max_size).
        if (!$file || !$file->isValid()) {
            return $this->fail(
                'Gambar gagal diunggah. Periksa koneksi atau ukuran file, lalu coba lagi.',
                422,
                'UPLOAD_FAILED'
            );
        }

        $prompt = $this->getPrompt($roomType, $style);

        // ── Sesuaikan timeout cURL dengan batas waktu PHP yang efektif ────────
        $timeout = (int) config('services.stability.timeout', 120);

        if ($maxExecution > 0) {
            // Sisakan margin agar response tetap bisa disimpan & dikirim.
            $timeout = min($timeout, max(5, $maxExecution - 10));
        }

        $connectTimeout = min(15, $timeout);

        $endpoint = (string) config('services.stability.endpoint');

        // ── Susun field sesuai jenis endpoint ────────────────────────────────
        $fields = [
            'image'           => new \CURLFile(
                $file->getRealPath(),
                $file->getMimeType(),
                $file->getClientOriginalName()
            ),
            'prompt'          => $prompt,
            'negative_prompt' => 'ugly, blurry, low quality, distorted, deformed, dark, messy, cluttered, unrealistic, cartoon, drawing',
            'output_format'   => 'jpeg',
        ];

        if (str_contains($endpoint, '/control/')) {
            // Control endpoints (structure/style) mempertahankan tata letak,
            // tapi biayanya lebih tinggi.
            $fields['control_strength'] = (string) config('services.stability.control_strength', 0.7);
        } else {
            // SD3.x image-to-image: butuh mode + strength.
            $fields['mode']     = (string) config('services.stability.mode', 'image-to-image');
            $fields['model']    = (string) config('services.stability.model', 'sd3.5-medium');
            $fields['strength'] = (string) config('services.stability.strength', 0.65);
        }

        // ── Kirim ke Stability AI via cURL (multipart/form-data) ──────────────
        $ch = curl_init();
        curl_setopt_array($ch, [
            CURLOPT_URL            => $endpoint,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST           => true,
            CURLOPT_CONNECTTIMEOUT => $connectTimeout,
            CURLOPT_TIMEOUT        => $timeout,
            CURLOPT_HTTPHEADER     => [
                'Authorization: Bearer ' . $stabilityKey,
                'Accept: image/*',
            ],
            CURLOPT_POSTFIELDS => $fields,
        ]);

        $responseBody = curl_exec($ch);
        $httpCode     = (int) curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $contentType  = (string) curl_getinfo($ch, CURLINFO_CONTENT_TYPE);
        $curlErrNo    = curl_errno($ch);
        $curlError    = curl_error($ch);
        curl_close($ch);

        // ── Gangguan jaringan ke provider ────────────────────────────────────
        if ($curlErrNo !== 0) {
            Log::error("AiDesignController cURL #{$curlErrNo}: {$curlError}");

            if (in_array($curlErrNo, [CURLE_OPERATION_TIMEDOUT, CURLE_COULDNT_CONNECT], true)) {
                return $this->fail(
                    'Layanan desain AI membutuhkan waktu terlalu lama. Coba lagi beberapa saat.',
                    504,
                    'AI_TIMEOUT'
                );
            }

            return $this->fail(
                'Gagal menghubungi layanan desain AI. Periksa koneksi lalu coba lagi.',
                502,
                'AI_UNREACHABLE'
            );
        }

        // ── Provider merespons error ──────────────────────────────────────────
        if ($httpCode !== 200) {
            Log::error('Stability AI error ' . $httpCode . ': ' . $responseBody);

            $errorData = json_decode((string) $responseBody, true);

            // 402 = kredit habis. Ini penyebab error yang paling sering terjadi.
            if ($httpCode === 402) {
                return $this->fail(
                    'Kredit layanan desain AI sedang habis. Fitur ini tidak dapat digunakan sementara — silakan hubungi admin.',
                    402,
                    'AI_CREDITS_EXHAUSTED'
                );
            }

            // 401/403 = key bermasalah. Dibalas 503 (bukan 401) supaya client
            // mobile tidak menganggap sesi login user sudah kedaluwarsa.
            if ($httpCode === 401 || $httpCode === 403) {
                return $this->fail(
                    'Layanan desain AI sedang tidak tersedia. Silakan hubungi admin.',
                    503,
                    'AI_UNAUTHORIZED'
                );
            }

            if ($httpCode === 429) {
                return $this->fail(
                    'Layanan desain AI sedang sibuk. Coba lagi dalam beberapa detik.',
                    429,
                    'AI_RATE_LIMITED'
                )->header('Retry-After', '10');
            }

            $detail = $errorData['message']
                ?? ($errorData['errors'][0] ?? null)
                ?? Str::limit((string) $responseBody, 200);

            return $this->fail(
                'Layanan desain AI gagal memproses permintaan: ' . $detail,
                502,
                'AI_PROVIDER_ERROR'
            );
        }

        if (!str_starts_with($contentType, 'image')) {
            Log::error('AiDesignController: response bukan gambar (' . $contentType . ')');
            return $this->fail(
                'Layanan desain AI mengirim respons yang tidak valid. Coba lagi.',
                502,
                'AI_INVALID_RESPONSE'
            );
        }

        // ── Simpan hasil ke storage ──────────────────────────────────────────
        $filename = 'ai-results/' . Str::uuid() . '.jpg';

        try {
            Storage::disk('public')->put($filename, $responseBody);
        } catch (\Throwable $e) {
            Log::error('AiDesignController storage error: ' . $e->getMessage());
            return $this->fail(
                'Hasil desain gagal disimpan. Coba lagi.',
                500,
                'AI_STORAGE_ERROR'
            );
        }

        $outputUrl = asset('storage/' . $filename);

        // Label yang ditampilkan ke user
        $roomLabels = [
            'living-room' => 'Ruang Tamu',
            'bedroom'     => 'Kamar Tidur',
            'kitchen'     => 'Dapur',
            'bathroom'    => 'Kamar Mandi',
            'garden'      => 'Taman',
            'dining-room' => 'Ruang Makan',
            'home-office' => 'Home Office',
            'kids-room'   => 'Kamar Anak',
        ];
        $roomLabel = $roomLabels[$roomType] ?? $roomType;

        return response()->json([
            'success' => true,
            'output'  => $outputUrl,
            'message' => "{$roomLabel} berhasil diredesain dengan gaya {$style}!",
        ]);
    }

    public function checkStatus($id): JsonResponse
    {
        return response()->json(['success' => true, 'status' => 'not_applicable']);
    }
}
