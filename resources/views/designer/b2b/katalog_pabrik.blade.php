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
        @media (max-width: 767px) {
            #sidebar { transform: translateX(-100%); }
            #sidebar.sidebar-open { transform: translateX(0); box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.2); }
            #main-content { margin-left: 0 !important; }
        }
        @media (min-width: 768px) {
            #sidebar.sidebar-hidden, #sidebar.sidebar-closed { transform: translateX(-100%); }
            #main-content.main-expanded, #main-content.main-full { margin-left: 0 !important; }
        }
    </style>
</head>
<body class="text-gray-800">

    @include('designer.partials.sidebar')

    <div id="sidebar-backdrop" class="fixed inset-0 bg-black/40 z-40 hidden"></div>

    <!-- Main Content -->
    <main id="main-content" class="ml-0 md:ml-64 flex flex-col min-h-screen sidebar-transition">
        <!-- Topbar Header -->
        <header class="h-16 bg-primary flex items-center justify-between px-4 sm:px-6 lg:px-8 sticky top-0 z-40 shadow-sm text-white">
            <div class="flex items-center space-x-3 sm:space-x-4">
                <button id="toggle-sidebar" class="text-white hover:text-gray-200 focus:outline-none">
                    <i class="fa-solid fa-bars text-lg"></i>
                </button>
                <div class="flex items-center space-x-2 text-xs">
                    <span class="text-white/80">Pesanan Kustom B2B</span>
                    <span class="text-white/50">/</span>
                    <span class="font-bold text-white">Katalog Pabrik & Workshop Terverifikasi</span>
                </div>
            </div>

            <div class="flex items-center space-x-3 sm:space-x-4">
                <div class="text-right hidden sm:block">
                    <p class="text-xs font-bold leading-none">{{ auth()->user()->full_name ?? 'Designer' }}</p>
                    <p class="text-[10px] text-white/80 mt-1">Interior Designer &bull; {{ auth()->user()->designer->city ?? 'Indonesia' }}</p>
                </div>
                <img src="https://ui-avatars.com/api/?name={{ urlencode(auth()->user()->full_name ?? 'Designer') }}&background=FAF1E8&color=B5733A" class="w-8 h-8 rounded-full border border-white/20">
            </div>
        </header>

        <!-- Body Container -->
        <div class="p-4 sm:p-6 lg:p-8 space-y-6 sm:space-y-8 max-w-7xl">
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

            <!-- Hero Banner Minimalist -->
            <div class="bg-gradient-to-r from-[#2C1810] via-[#5C321E] to-[#B5733A] rounded-2xl px-6 py-6 sm:px-8 sm:py-7 text-white relative overflow-hidden shadow-md">
                <div class="relative z-10 flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
                    <div class="space-y-1.5 max-w-xl">
                        <span class="inline-block bg-white/15 text-white/90 text-[10px] font-bold px-2.5 py-0.5 rounded-full uppercase tracking-widest">
                            Pesanan Kustom B2B
                        </span>
                        <h2 class="text-xl sm:text-2xl font-extrabold leading-snug tracking-tight">
                            Katalog Workshop &amp; Pabrik Mebel Kustom
                        </h2>
                        <p class="text-xs text-white/80">
                            Partner produksi terverifikasi SVLK dengan jaminan transaksi Escrow.
                        </p>
                    </div>
                    <div class="flex flex-wrap items-center gap-2 text-[11px] font-semibold text-white/90 shrink-0">
                        <span class="bg-white/10 border border-white/15 px-3 py-1.5 rounded-xl backdrop-blur-sm">
                            <i class="fa-solid fa-shield-halved mr-1.5 text-amber-200"></i>Escrow 100%
                        </span>
                        <span class="bg-white/10 border border-white/15 px-3 py-1.5 rounded-xl backdrop-blur-sm">
                            <i class="fa-solid fa-certificate mr-1.5 text-amber-200"></i>SVLK Legal
                        </span>
                    </div>
                </div>
            </div>

            <!-- Filter & Search Controls -->
            <form method="GET" action="{{ b2b_route('designer.b2b.katalog') }}" class="bg-white p-3.5 sm:p-4 rounded-2xl border border-gray-100 shadow-sm flex flex-col lg:flex-row items-stretch lg:items-center gap-2.5">
                <div class="relative flex-1 min-w-0">
                    <i class="fa-solid fa-magnifying-glass absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-400 text-xs pointer-events-none"></i>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama pabrik, kota, atau material..." class="w-full h-10 pl-9 pr-4 text-xs bg-gray-50 border border-gray-200 rounded-xl focus:bg-white focus:outline-none focus:border-primary transition-colors">
                </div>

                <div class="grid grid-cols-2 sm:flex items-center gap-2.5 shrink-0">
                    <div class="relative w-full sm:w-auto">
                        <select name="city" onchange="this.form.submit()" class="appearance-none w-full sm:w-auto h-10 pl-3.5 pr-8 text-xs font-semibold border border-gray-200 rounded-xl bg-gray-50 hover:bg-white text-gray-700 focus:outline-none focus:border-primary transition-colors cursor-pointer">
                            <option value="">Semua Wilayah</option>
                            @foreach(($cities ?? collect()) as $city)
                                <option value="{{ $city }}" @selected(request('city') === $city)>{{ $city }}</option>
                            @endforeach
                        </select>
                        <i class="fa-solid fa-chevron-down text-[10px] text-gray-400 absolute right-3 top-1/2 -translate-y-1/2 pointer-events-none"></i>
                    </div>

                    <div class="relative w-full sm:w-auto">
                        <select name="wood_type" onchange="this.form.submit()" class="appearance-none w-full sm:w-auto h-10 pl-3.5 pr-8 text-xs font-semibold border border-gray-200 rounded-xl bg-gray-50 hover:bg-white text-gray-700 focus:outline-none focus:border-primary transition-colors cursor-pointer">
                            <option value="">Semua Material</option>
                            @foreach(($woodTypes ?? collect()) as $wood)
                                <option value="{{ $wood }}" @selected(request('wood_type') === $wood)>{{ $wood }}</option>
                            @endforeach
                        </select>
                        <i class="fa-solid fa-chevron-down text-[10px] text-gray-400 absolute right-3 top-1/2 -translate-y-1/2 pointer-events-none"></i>
                    </div>

                    <div class="relative w-full sm:w-auto col-span-2 sm:col-span-1">
                        <select name="min_capacity" onchange="this.form.submit()" class="appearance-none w-full sm:w-auto h-10 pl-3.5 pr-8 text-xs font-semibold border border-gray-200 rounded-xl bg-gray-50 hover:bg-white text-gray-700 focus:outline-none focus:border-primary transition-colors cursor-pointer">
                            <option value="">Semua Kapasitas</option>
                            <option value="50" @selected(request('min_capacity') === '50')>&ge; 50 unit/bln</option>
                            <option value="100" @selected(request('min_capacity') === '100')>&ge; 100 unit/bln</option>
                            <option value="500" @selected(request('min_capacity') === '500')>&ge; 500 unit/bln</option>
                        </select>
                        <i class="fa-solid fa-chevron-down text-[10px] text-gray-400 absolute right-3 top-1/2 -translate-y-1/2 pointer-events-none"></i>
                    </div>

                    <button type="submit" class="h-10 bg-primary hover:bg-[#8C4E1E] text-white text-xs font-bold px-5 rounded-xl transition-all w-full sm:w-auto col-span-2 sm:col-span-1 shadow-sm flex items-center justify-center cursor-pointer">
                        Cari
                    </button>
                </div>
            </form>

            <!-- List Vendor Pabrik B2B -->
            <div class="space-y-4">
                <div class="flex items-center justify-between gap-2">
                    <h3 class="text-sm font-bold text-gray-900">Pabrik &amp; Workshop Rekomendasi Teratas</h3>
                    <span class="text-xs text-gray-400 whitespace-nowrap">{{ $sellers->total() }} Vendor Terverifikasi SVLK</span>
                </div>

                <div class="grid grid-cols-1 xl:grid-cols-2 gap-5 sm:gap-6">
                    @forelse($sellers as $seller)
                        @php
                            $specialties = collect($seller->wood_specialties ?? []);
                            $rating = $seller->rating ?? 0;
                            $existingRfq = $rfqsBySeller[$seller->id] ?? null;
                            $initials = collect(explode(' ', trim($seller->store_name)))->filter()->take(2)->map(fn($w) => strtoupper(substr($w, 0, 1)))->implode('') ?: 'PD';
                        @endphp
                        <div class="bg-white rounded-2xl border border-gray-100 p-5 sm:p-6 shadow-sm hover:shadow-md transition-all space-y-4 relative flex flex-col justify-between">
                            <div class="flex items-start justify-between gap-3">
                                <div class="flex items-center space-x-3.5 sm:space-x-4 min-w-0">
                                    <div class="w-14 h-14 rounded-2xl overflow-hidden bg-[#FAF1E8] border border-primary/15 flex items-center justify-center shrink-0">
                                        @if($seller->store_image_url)
                                            <img src="{{ $seller->store_image_url }}" class="w-full h-full object-cover" alt="{{ $seller->store_name }}" onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                                            <span class="hidden w-full h-full items-center justify-center font-extrabold text-base text-primary">{{ $initials }}</span>
                                        @else
                                            <span class="w-full h-full flex items-center justify-center font-extrabold text-base text-primary">{{ $initials }}</span>
                                        @endif
                                    </div>
                                    <div class="min-w-0">
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
    // Sidebar drawer: satu implementasi untuk desktop & mobile.
    (function () {
        const sidebar = document.getElementById('sidebar');
        const main = document.getElementById('main-content');
        const toggleBtn = document.getElementById('toggle-sidebar');
        const backdrop = document.getElementById('sidebar-backdrop');
        if (!sidebar || !toggleBtn) return;

        const isMobile = () => window.innerWidth < 768;

        const closeMobile = () => {
            sidebar.classList.remove('sidebar-open');
            if (backdrop) backdrop.classList.add('hidden');
        };

        const toggleSidebar = () => {
            if (isMobile()) {
                if (sidebar.classList.contains('sidebar-open')) {
                    closeMobile();
                } else {
                    sidebar.classList.add('sidebar-open');
                    if (backdrop) backdrop.classList.remove('hidden');
                }
            } else {
                sidebar.classList.toggle('sidebar-hidden');
                if (main) main.classList.toggle('main-expanded');
            }
        };

        toggleBtn.addEventListener('click', toggleSidebar);
        if (backdrop) backdrop.addEventListener('click', closeMobile);
        document.querySelectorAll('.close-sidebar-btn').forEach((btn) => btn.addEventListener('click', closeMobile));
        window.addEventListener('resize', () => { if (!isMobile()) closeMobile(); });

        window.toggleSidebar = toggleSidebar;
    })();
    </script>
<script async defer data-website-id="cd56ccb0-617d-4bec-8e77-0ba7ea566e9a" data-host-url="/umami-analytics" src="/umami-analytics/script.js"></script>
</body>
</html>
