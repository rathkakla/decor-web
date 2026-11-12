<?php $site_name = "DECOR"; ?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Aplikasi — <?= $site_name ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#B5733A',
                        secondary: '#E3DCD6',
                    }
                }
            }
        }
    </script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;600;700&display=swap');

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
        }

        .content-container {
            max-width: 1200px;
            margin: 0 auto;
        }

        .editorial-text {
            line-height: 1.05;
            letter-spacing: -0.05em;
        }
    </style>
</head>

<body class="text-gray-800 bg-white">

    @include('customer.partials.navbar')

    <main>
        {{-- ── HERO ─────────────────────────────────────────────────────── --}}
        <section class="content-container px-6 pt-20 pb-16">
            <div class="grid grid-cols-1 lg:grid-cols-[1fr_auto] gap-14 items-start">

                <div>
                    <span class="text-[10px] uppercase tracking-[0.4em] text-primary font-bold">
                        Aplikasi Android
                    </span>

                    <h1 class="text-6xl md:text-7xl font-bold editorial-text mt-5 mb-6">
                        Decor di saku<br><span class="text-primary italic">Anda.</span>
                    </h1>

                    <p class="text-gray-500 leading-relaxed max-w-md mb-10">
                        Katalog furnitur, konsultasi desainer, dan AI room design — langsung dari HP.
                    </p>

                    @if ($release)
                        <a href="{{ $release['url'] }}" download data-umami-event="download-apk"
                            class="inline-flex items-center gap-3 bg-primary text-white px-8 py-4 rounded-sm text-xs font-bold uppercase tracking-widest shadow-lg shadow-primary/20 hover:bg-opacity-90 transition">
                            <i class="fa-solid fa-arrow-down-to-bracket text-[11px]"></i>
                            Unduh APK &middot; v{{ $release['version'] }}
                        </a>

                        <div
                            class="flex flex-wrap items-center gap-x-4 gap-y-2 mt-6 text-[10px] uppercase tracking-[0.2em] text-gray-400 font-bold">
                            <span>{{ $release['size_human'] }}</span>
                            <span class="w-1 h-1 bg-gray-300 rounded-full"></span>
                            <span>{{ config('app_download.android.min_os') }}+</span>
                            <span class="w-1 h-1 bg-gray-300 rounded-full"></span>
                            <span>{{ $release['released_at']->format('d M Y') }}</span>
                        </div>

                        @if ($release['sha256'])
                            <p class="mt-5 text-[10px] text-gray-400 font-mono break-all max-w-md leading-relaxed">
                                SHA-256: {{ $release['sha256'] }}
                            </p>
                        @endif

                        @if (config('app_download.android.notes'))
                            <p class="mt-5 text-xs text-gray-500 leading-relaxed max-w-md">
                                {{ config('app_download.android.notes') }}
                            </p>
                        @endif
                    @else
                        <span
                            class="inline-flex items-center gap-3 bg-gray-100 text-gray-400 px-8 py-4 rounded-sm text-xs font-bold uppercase tracking-widest cursor-not-allowed select-none">
                            <i class="fa-solid fa-arrow-down-to-bracket text-[11px]"></i>
                            Segera tersedia
                        </span>

                        <p class="mt-6 text-[10px] uppercase tracking-[0.2em] text-gray-400 font-bold">
                            Aplikasi sedang disiapkan
                        </p>

                        <p class="mt-3 text-xs text-gray-400 leading-relaxed max-w-sm">
                            Kami sedang menyelesaikan aplikasi Android Decor. Halaman ini akan otomatis
                            menampilkan tombol unduh begitu versi pertama siap.
                        </p>
                    @endif
                </div>

                {{-- Panel QR (desktop) --}}
                <div
                    class="hidden lg:flex flex-col items-center gap-5 bg-secondary/30 border border-secondary rounded-sm p-8 w-[220px]">
                    <span class="text-[9px] uppercase tracking-[0.3em] text-primary font-bold text-center">
                        Scan dari HP
                    </span>

                    <div id="qr" class="w-[150px] h-[150px] bg-white flex items-center justify-center"></div>

                    <p class="text-[9px] text-gray-400 text-center leading-relaxed">
                        Buka kamera HP, arahkan ke kode untuk membuka halaman ini.
                    </p>
                </div>
            </div>
        </section>

        {{-- ── LANGKAH PEMASANGAN ───────────────────────────────────────── --}}
        <section class="content-container px-6 py-16 border-t border-gray-100">
            <h2 class="text-[10px] uppercase tracking-[0.4em] text-primary font-bold mb-10">
                Cara memasang
            </h2>

            @php
                $steps = [
                    'Unduh berkas APK melalui tombol di atas.',
                    'Buka berkas yang sudah terunduh dari notifikasi atau folder Unduhan.',
                    'Jika HP meminta izin, aktifkan “Izinkan dari sumber ini” untuk browser yang dipakai, lalu kembali dan pasang.',
                    'Buka Decor dan masuk dengan akun yang sudah Anda miliki.',
                ];
            @endphp

            <div class="grid grid-cols-1 md:grid-cols-2 gap-x-16 gap-y-8 max-w-4xl">
                @foreach ($steps as $index => $step)
                    <div class="flex gap-5">
                        <span class="text-primary font-black text-lg leading-none w-8 shrink-0">
                            {{ sprintf('%02d', $index + 1) }}
                        </span>
                        <p class="text-sm text-gray-500 leading-relaxed">{{ $step }}</p>
                    </div>
                @endforeach
            </div>
        </section>

        {{-- ── CATATAN KEPERCAYAAN ──────────────────────────────────────── --}}
        <section class="content-container px-6 pb-16">
            <div class="bg-secondary/30 border border-secondary rounded-sm p-6 flex items-start gap-4 max-w-3xl">
                <i class="fa-solid fa-shield-halved text-primary mt-0.5"></i>
                <p class="text-xs text-gray-500 leading-relaxed">
                    Aplikasi ini berasal dari tim Decor. Unduh hanya dari halaman ini — jangan dari
                    sumber lain, karena berkas dari pihak ketiga tidak dapat kami jamin keamanannya.
                </p>
            </div>
        </section>

        {{-- ── RIWAYAT VERSI ────────────────────────────────────────────── --}}
        @if (count($history))
            <section class="content-container px-6 pb-20">
                <h2 class="text-[10px] uppercase tracking-[0.4em] text-primary font-bold mb-6">
                    Versi sebelumnya
                </h2>

                <ul class="divide-y divide-gray-100 max-w-2xl">
                    @foreach ($history as $old)
                        <li class="py-4 flex items-center justify-between gap-6">
                            <div>
                                <p class="text-sm font-bold">v{{ $old['version'] }}</p>
                                <p class="text-[10px] uppercase tracking-[0.2em] text-gray-400 font-bold mt-1">
                                    {{ $old['released_at']->format('d M Y') }} &middot; {{ $old['size_human'] }}
                                </p>
                            </div>
                            <a href="{{ $old['url'] }}" download
                                class="text-[10px] font-bold uppercase tracking-widest text-primary border-b border-primary/40 pb-0.5 hover:border-primary transition-colors">
                                Unduh
                            </a>
                        </li>
                    @endforeach
                </ul>
            </section>
        @endif
    </main>

    {{-- ── FOOTER RINGKAS ───────────────────────────────────────────────── --}}
    <footer class="border-t border-gray-100 py-10 px-6">
        <div
            class="content-container flex flex-col md:flex-row justify-between items-center gap-4 text-[10px] uppercase tracking-[0.2em] text-gray-400 font-bold">
            <p>&copy; 2026 Decor Marketplace</p>
            <div class="flex items-center gap-6">
                <a href="{{ route('homepage') }}" class="hover:text-primary transition-colors">Beranda</a>
                <a href="{{ route('customer.catalog') }}" class="hover:text-primary transition-colors">Katalog</a>
                <a href="{{ route('customer.help-center') }}" class="hover:text-primary transition-colors">Bantuan</a>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/qrcodejs@1.0.0/qrcode.min.js"></script>
    <script>
        (function () {
            var container = document.getElementById('qr');
            if (!container) return;

            if (typeof QRCode === 'undefined') {
                container.innerHTML = '<span class="text-[9px] text-gray-400 text-center px-2">QR tidak tersedia</span>';
                return;
            }

            new QRCode(container, {
                text: @json(route('download')),
                width: 150,
                height: 150,
                colorDark: '#1f2937',
                colorLight: '#ffffff',
                correctLevel: QRCode.CorrectLevel.M
            });
        })();
    </script>
</body>

</html>
