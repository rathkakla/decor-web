<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Decor Designer - Katalog Pabrik & Vendor B2B</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap');
        body { background-color: #F8F6F4; font-family: 'Plus Jakarta Sans', sans-serif; overflow-x: hidden; }
        .bg-primary { background-color: #B5733A; }
        .text-primary { color: #B5733A; }
        .border-primary { border-color: #B5733A; }
        .active-link { background-color: rgba(181, 115, 58, 0.1); color: #B5733A; border-right: 4px solid #B5733A; }
        .sidebar-transition { transition: transform 0.3s ease-in-out, margin 0.3s ease-in-out; }
        .sidebar-hidden { transform: translateX(-100%); }
        .main-expanded { margin-left: 0 !important; }
    </style>
</head>
<body class="text-gray-800">

    @include('designer.partials.sidebar')

    <!-- Main Content -->
    <main id="main-content" class="ml-64 flex flex-col min-h-screen sidebar-transition">
        <!-- Topbar Header -->
        <header class="h-16 bg-primary flex items-center justify-between px-8 sticky top-0 z-40 shadow-sm text-white">
            <div class="flex items-center space-x-4">
                <button id="toggle-sidebar" class="text-white hover:text-gray-200 focus:outline-none">
                    <i class="fa-solid fa-bars text-lg"></i>
                </button>
                <div class="flex items-center space-x-2 text-xs">
                    <span class="text-white/80">Pengadaan B2B</span>
                    <span class="text-white/50">/</span>
                    <span class="font-bold text-white">Katalog Pabrik & Workshop Terverifikasi</span>
                </div>
            </div>

            <div class="flex items-center space-x-4">
                <div class="text-right hidden sm:block">
                    <p class="text-xs font-bold leading-none">{{ auth()->user()->full_name ?? 'Designer' }}</p>
                    <p class="text-[10px] text-white/80 mt-1">Interior Designer &bull; {{ auth()->user()->designer->city ?? 'Indonesia' }}</p>
                </div>
                <img src="https://ui-avatars.com/api/?name={{ urlencode(auth()->user()->full_name ?? 'Designer') }}&background=FAF1E8&color=B5733A" class="w-8 h-8 rounded-full border border-white/20">
            </div>
        </header>

        <!-- Body Container -->
        <div class="p-8 space-y-8 max-w-7xl">
            @isset($seller)
                <!-- Mode Detail Pabrik -->
                <div class="bg-white p-5 rounded-2xl border border-gray-100 shadow-sm flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                    <div>
                        <p class="text-[10px] font-bold uppercase tracking-wider text-gray-400">Detail Pabrik Mitra B2B</p>
                        <h2 class="text-lg font-extrabold text-gray-900 mt-0.5">{{ $seller->store_name }}</h2>
                        <p class="text-xs text-gray-500 mt-0.5">
                            <i class="fa-solid fa-location-dot text-primary mr-1"></i> {{ $seller->city ?: 'Indonesia' }}
                            @if($seller->svlk_verified) &bull; <span class="text-emerald-600 font-bold">Terverifikasi SVLK</span> @endif
                        </p>
                    </div>
                    <div class="flex flex-wrap items-center gap-2">
                        <a href="{{ b2b_route('designer.b2b.katalog') }}" class="px-4 py-2.5 rounded-xl border border-gray-200 text-xs font-bold text-gray-600 hover:bg-gray-50 transition-all flex items-center">
                            <i class="fa-solid fa-arrow-left mr-2"></i> Semua Pabrik
                        </a>
                        @php $detailRfq = $rfqsBySeller[$seller->id] ?? null; @endphp
                        @if($detailRfq)
                            <a href="{{ b2b_route('designer.b2b.negosiasi', $detailRfq) }}" class="px-4 py-2.5 rounded-xl border border-gray-200 text-xs font-bold text-gray-600 hover:border-primary hover:text-primary transition-all flex items-center">
                                <i class="fa-solid fa-file-lines mr-2"></i> Lihat Detail RFQ
                            </a>
                        @endif
                        <a href="{{ b2b_route('designer.b2b.rfq.create.seller', $seller) }}" class="bg-primary hover:bg-[#8C4E1E] text-white px-5 py-2.5 rounded-xl font-bold text-xs transition-all shadow-md flex items-center">
                            Ajukan RFQ <i class="fa-solid fa-arrow-right ml-2"></i>
                        </a>
                    </div>
                </div>
            @endisset

            <!-- Hero Search & Banner -->
            <div class="bg-gradient-to-r from-[#2C1810] via-[#5C321E] to-primary rounded-3xl p-8 text-white relative overflow-hidden shadow-lg">
                <div class="relative z-10 max-w-2xl space-y-3">
                    <span class="bg-white/20 text-white text-[10px] font-extrabold px-3 py-1 rounded-full uppercase tracking-wider backdrop-blur-sm">
                        B2B Custom Furniture Procurement
                    </span>
                    <h2 class="text-2xl sm:text-3xl font-extrabold leading-tight">Cari Produsen & Workshop Mebel untuk Proyek Klien Anda</h2>
                    <p class="text-xs text-white/80 leading-relaxed">
                        Terhubung langsung dengan 150+ pabrik kayu jati, rotan, dan metal terverifikasi SVLK. Dapatkan garansi rekening penampung (Escrow), inspeksi mutu, dan sistem tender RFQ transparan.
                    </p>
                    <div class="pt-2 flex flex-wrap gap-2 text-[11px]">
                        <span class="bg-black/20 px-3 py-1 rounded-lg backdrop-blur-sm">&check; Perlindungan Escrow 100%</span>
                        <span class="bg-black/20 px-3 py-1 rounded-lg backdrop-blur-sm">&check; Gambar CAD &amp; BoQ Terstandarisasi</span>
                        <span class="bg-black/20 px-3 py-1 rounded-lg backdrop-blur-sm">&check; Legalitas SVLK Kayu Indonesia</span>
                    </div>
                </div>
                <div class="absolute right-0 bottom-0 opacity-15 translate-x-10 translate-y-10">
                    <i class="fa-solid fa-industry text-[220px]"></i>
                </div>
            </div>

            <!-- Filter & Search Controls -->
            <form method="GET" action="{{ b2b_route('designer.b2b.katalog') }}" class="bg-white p-5 rounded-2xl border border-gray-100 shadow-sm flex flex-col md:flex-row items-center justify-between gap-4">
                <div class="relative flex-1 w-full">
                    <i class="fa-solid fa-magnifying-glass absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 text-xs"></i>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama pabrik, kota, atau spesialisasi material..." class="w-full pl-10 pr-4 py-2.5 text-xs border border-gray-200 rounded-xl focus:outline-none focus:border-primary">
                </div>

                <div class="flex items-center space-x-3 w-full md:w-auto">
                    <select name="city" onchange="this.form.submit()" class="text-xs border border-gray-200 rounded-xl px-4 py-2.5 bg-white text-gray-700 focus:outline-none focus:border-primary">
                        <option value="">Semua Wilayah</option>
                        @foreach(($cities ?? collect()) as $city)
                            <option value="{{ $city }}" @selected(request('city') === $city)>{{ $city }}</option>
                        @endforeach
                    </select>

                    <select name="wood_type" onchange="this.form.submit()" class="text-xs border border-gray-200 rounded-xl px-4 py-2.5 bg-white text-gray-700 focus:outline-none focus:border-primary">
                        <option value="">Semua Material</option>
                        @foreach(($woodTypes ?? collect()) as $wood)
                            <option value="{{ $wood }}" @selected(request('wood_type') === $wood)>{{ $wood }}</option>
                        @endforeach
                    </select>

                    <select name="min_capacity" onchange="this.form.submit()" class="text-xs border border-gray-200 rounded-xl px-4 py-2.5 bg-white text-gray-700 focus:outline-none focus:border-primary">
                        <option value="">Semua Kapasitas</option>
                        <option value="50" @selected(request('min_capacity') === '50')>Kapasitas: &ge; 50 unit/bln</option>
                        <option value="100" @selected(request('min_capacity') === '100')>Kapasitas: &ge; 100 unit/bln</option>
                        <option value="500" @selected(request('min_capacity') === '500')>Kapasitas: &ge; 500 unit/bln</option>
                    </select>

                    <button type="submit" class="bg-primary hover:bg-[#8C4E1E] text-white text-xs font-bold px-4 py-2.5 rounded-xl transition-all">Cari</button>
                </div>
            </form>

            <!-- List Vendor Pabrik B2B -->
            <div class="space-y-4">
                <div class="flex items-center justify-between">
                    <h3 class="text-sm font-bold text-gray-900">Pabrik & Workshop Rekomendasi Teratas</h3>
                    <span class="text-xs text-gray-400">{{ $sellers->total() }} Vendor Terverifikasi SVLK</span>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    @forelse($sellers as $seller)
                        @php
                            $specialties = collect($seller->wood_specialties ?? []);
                            $rating = $seller->rating ?? 0;
                            $existingRfq = $rfqsBySeller[$seller->id] ?? null;
                        @endphp
                        <div class="bg-white rounded-2xl border border-gray-100 p-6 shadow-sm hover:shadow-md transition-all space-y-4 relative">
                            <div class="flex items-start justify-between">
                                <div class="flex items-center space-x-4">
                                    <img src="{{ $seller->store_image_url }}" class="w-14 h-14 rounded-2xl object-cover" alt="{{ $seller->store_name }}">
                                    <div>
                                        <div class="flex items-center space-x-2">
                                            <h4 class="font-extrabold text-sm text-gray-900">{{ $seller->store_name }}</h4>
                                            @if($seller->svlk_verified)
                                                <span class="bg-emerald-100 text-emerald-700 text-[9px] font-bold px-2 py-0.5 rounded-full">SVLK Legal</span>
                                            @endif
                                        </div>
                                        <p class="text-xs text-gray-500 mt-0.5">
                                            <i class="fa-solid fa-location-dot text-primary mr-1"></i>
                                            {{ $seller->city ?: 'Indonesia' }}
                                        </p>
                                        <div class="flex items-center space-x-1 text-amber-500 text-xs mt-1">
                                            @for($i = 1; $i <= 5; $i++)
                                                <i class="fa-solid fa-star {{ $i <= round($rating) ? '' : 'text-gray-200' }}"></i>
                                            @endfor
                                            <span class="text-gray-800 font-bold ml-1">{{ number_format((float) $rating, 1) }} / 5.0</span>
                                        </div>
                                    </div>
                                </div>
                                @if($seller->monthly_capacity)
                                    <span class="bg-primary/10 text-primary text-[10px] font-bold px-3 py-1 rounded-full uppercase whitespace-nowrap">{{ $seller->monthly_capacity }} Unit/bln</span>
                                @endif
                            </div>

                            <div class="space-y-2 text-xs text-gray-600">
                                <div class="flex justify-between gap-4 py-1 border-b border-gray-100">
                                    <span class="text-gray-400 whitespace-nowrap">Spesialisasi:</span>
                                    <span class="font-bold text-gray-800 text-right">
                                        {{ $specialties->isNotEmpty() ? $specialties->implode(', ') : ($seller->store_description ? \Illuminate\Support\Str::limit($seller->store_description, 60) : '-') }}
                                    </span>
                                </div>
                                <div class="flex justify-between py-1 border-b border-gray-100">
                                    <span class="text-gray-400">Kapasitas Produksi:</span>
                                    <span class="font-bold text-gray-800">{{ $seller->monthly_capacity ?: '-' }} Unit / Bulan</span>
                                </div>
                                <div class="flex justify-between py-1 border-b border-gray-100">
                                    <span class="text-gray-400">Alamat Bengkel:</span>
                                    <span class="font-bold text-gray-800 text-right">{{ \Illuminate\Support\Str::limit($seller->store_address ?: '-', 48) }}</span>
                                </div>
                            </div>

                            <div class="pt-2 flex flex-wrap items-center gap-2">
                                <a href="{{ b2b_route('designer.b2b.rfq.create.seller', $seller) }}" class="flex-1 min-w-[190px] bg-primary hover:bg-[#8C4E1E] text-white py-3 px-4 rounded-xl font-bold text-xs flex items-center justify-center transition-all shadow-md">
                                    Ajukan Permintaan Penawaran (RFQ) <i class="fa-solid fa-arrow-right ml-2"></i>
                                </a>
                                @if($existingRfq)
                                    <a href="{{ b2b_route('designer.b2b.negosiasi', $existingRfq) }}" title="Lihat detail RFQ terakhir dengan pabrik ini" class="whitespace-nowrap border border-gray-200 hover:border-primary hover:text-primary text-gray-600 py-3 px-4 rounded-xl font-bold text-xs flex items-center justify-center transition-all">
                                        <i class="fa-solid fa-file-lines mr-2"></i> Lihat Detail RFQ
                                    </a>
                                @endif
                                @if($seller->portfolio_url)
                                    <a href="{{ $seller->portfolio_url }}" target="_blank" rel="noopener" title="Portofolio" class="px-4 py-3 border border-gray-200 rounded-xl hover:bg-gray-50 text-gray-600 text-xs font-bold transition-all">
                                        <i class="fa-solid fa-images"></i>
                                    </a>
                                @endif
                            </div>
                        </div>
                    @empty
                        <div class="md:col-span-2 bg-white rounded-2xl border border-gray-100 p-12 text-center">
                            <i class="fa-solid fa-industry text-4xl text-gray-300 mb-3 block"></i>
                            <p class="text-sm font-bold text-gray-700">Belum ada pabrik B2B yang cocok dengan filter Anda.</p>
                            <p class="text-xs text-gray-500 mt-1">Coba longgarkan filter wilayah atau material.</p>
                        </div>
                    @endforelse
                </div>

                @if(method_exists($sellers, 'hasPages') && $sellers->hasPages())
                    <div class="pt-2">{{ $sellers->links() }}</div>
                @endif
            </div>
        </div>
    </main>

    <script>
        const toggleBtn = document.getElementById('toggle-sidebar');
        const sidebar = document.getElementById('sidebar');
        const main = document.getElementById('main-content');
        toggleBtn.addEventListener('click', () => {
            sidebar.classList.toggle('sidebar-hidden');
            main.classList.toggle('main-expanded');
        });
    </script>
</body>
</html>
