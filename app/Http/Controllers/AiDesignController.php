<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class AiDesignController extends Controller
{
    private string $stabilityEndpoint = 'https://api.stability.ai/v2beta/stable-image/control/structure';

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

        return "A professionally designed {$room}, {$style}, photorealistic, high resolution professional interior photography, architectural digest quality, 8k ultra detailed, beautiful lighting";
    }

    // ─── Main Generate ────────────────────────────────────────────────────────
    public function generate(Request $request)
    {
        set_time_limit(180);

        $request->validate([
            'room_image' => 'required|image|mimes:jpeg,png,jpg,webp|max:20480',
            'room_type'  => 'required|string',
            'style'      => 'required|string',
        ]);

        try {
            $file     = $request->file('room_image');
            $style    = $request->input('style', 'Scandinavian');
            $roomType = $request->input('room_type', 'living-room');

            $stabilityKey = env('STABILITY_API_KEY');
            if (empty($stabilityKey)) {
                throw new \Exception('STABILITY_API_KEY belum diset di .env');
            }

            $prompt = $this->getPrompt($roomType, $style);

            // ── Kirim ke Stability AI via cURL (multipart/form-data) ──────────
            $ch = curl_init();
            curl_setopt_array($ch, [
                CURLOPT_URL            => $this->stabilityEndpoint,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_POST           => true,
                CURLOPT_TIMEOUT        => 120,
                CURLOPT_HTTPHEADER     => [
                    'Authorization: Bearer ' . $stabilityKey,
                    'Accept: image/*',
                ],
                CURLOPT_POSTFIELDS => [
                    'image'            => new \CURLFile(
                        $file->getRealPath(),
                        $file->getMimeType(),
                        $file->getClientOriginalName()
                    ),
                    'prompt'           => $prompt,
                    'negative_prompt'  => 'ugly, blurry, low quality, distorted, deformed, dark, messy, cluttered, unrealistic, cartoon, drawing',
                    'control_strength' => '0.7',
                    'output_format'    => 'jpeg',
                ],
            ]);

            $responseBody = curl_exec($ch);
            $httpCode     = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            $contentType  = curl_getinfo($ch, CURLINFO_CONTENT_TYPE);
            $curlError    = curl_error($ch);
            curl_close($ch);

            if ($curlError) {
                throw new \Exception('cURL error: ' . $curlError);
            }

            if ($httpCode !== 200) {
                \Log::error('Stability AI error ' . $httpCode . ': ' . $responseBody);
                $errorData = json_decode($responseBody, true);

                if ($httpCode === 402) throw new \Exception('Kredit Stability AI habis. Top up di platform.stability.ai');
                if ($httpCode === 401) throw new \Exception('STABILITY_API_KEY tidak valid.');
                if ($httpCode === 429) throw new \Exception('Rate limit. Coba lagi dalam beberapa detik.');

                $detail = $errorData['message'] ?? $errorData['errors'][0] ?? substr($responseBody, 0, 200);
                throw new \Exception('Stability AI error ' . $httpCode . ': ' . $detail);
            }

            if (!str_starts_with($contentType, 'image')) {
                throw new \Exception('Response bukan gambar: ' . $contentType);
            }

            // ── Simpan hasil ke storage ───────────────────────────────────────
            $filename  = 'ai-results/' . Str::uuid() . '.jpg';
            Storage::disk('public')->put($filename, $responseBody);
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

        } catch (\Exception $e) {
            \Log::error('AiDesignController error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function checkStatus($id)
    {
        return response()->json(['success' => true, 'status' => 'not_applicable']);
    }
}