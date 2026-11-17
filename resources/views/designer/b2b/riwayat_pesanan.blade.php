<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Decor Designer - Riwayat & Progres Pesanan B2B</title>
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

    <main id="main-content" class="ml-0 md:ml-64 flex flex-col min-h-screen sidebar-transition">
        <header class="h-16 bg-primary flex items-center justify-between px-4 sm:px-6 lg:px-8 sticky top-0 z-40 shadow-sm text-white">
            <div class="flex items-center space-x-3 sm:space-x-4">
                <button id="toggle-sidebar" class="text-white hover:text-gray-200 focus:outline-none">
                    <i class="fa-solid fa-bars text-lg"></i>
                </button>
                <div class="flex items-center space-x-2 text-xs">
                    <a href="{{ b2b_route('designer.b2b.katalog') }}" class="text-white/80 hover:text-white hidden sm:inline">Kustom B2B</a>
                    <span class="text-white/50 hidden sm:inline">/</span>
                    <span class="font-bold text-white">Riwayat &amp; Progres Proyek</span>
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

        <div class="p-4 sm:p-6 lg:p-8 space-y-6 max-w-7xl mx-auto w-full flex-1">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                <div>
                    <a href="{{ b2b_route('designer.b2b.katalog') }}" class="inline-flex items-center text-xs font-bold text-gray-500 hover:text-primary transition-colors mb-1.5">
                        <i class="fa-solid fa-arrow-left mr-2"></i> Kembali ke Katalog Pabrik
                    </a>
                    <h2 class="text-xl sm:text-2xl font-bold text-gray-900 tracking-tight">Riwayat &amp; Progres Pesanan Kustom B2B</h2>
                    <p class="text-xs text-gray-500 mt-0.5">Pantau status penawaran, pembayaran DP Escrow, progres produksi, hingga serah terima (BAST).</p>
                </div>
                <a href="{{ b2b_route('designer.b2b.katalog') }}" class="bg-primary hover:bg-[#8C4E1E] text-white px-4 py-2.5 rounded-xl font-bold text-xs inline-flex items-center justify-center shadow-sm transition-all shrink-0">
                    <i class="fa-solid fa-plus mr-2"></i> Ajukan RFQ Baru
                </a>
            </div>

            <!-- Summary Cards -->
            <div class="grid grid-cols-2 xl:grid-cols-4 gap-4">
                <div class="bg-white p-4 sm:p-5 rounded-2xl border border-gray-100 shadow-sm">
                    <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider">Total Proyek</p>
                    <h3 class="text-2xl font-bold text-gray-900 mt-1">{{ $stats['total'] }}</h3>
                </div>
                <div class="bg-white p-4 sm:p-5 rounded-2xl border border-gray-100 shadow-sm">
                    <p class="text-[10px] font-bold text-amber-600 uppercase tracking-wider">Menunggu Penawaran</p>
                    <h3 class="text-2xl font-bold text-gray-900 mt-1">{{ $stats['pending'] }}</h3>
                </div>
                <div class="bg-white p-4 sm:p-5 rounded-2xl border border-gray-100 shadow-sm">
                    <p class="text-[10px] font-bold text-blue-600 uppercase tracking-wider">Penawaran Masuk</p>
                    <h3 class="text-2xl font-bold text-gray-900 mt-1">{{ $stats['quoted'] }}</h3>
                </div>
                <div class="bg-white p-4 sm:p-5 rounded-2xl border border-gray-100 shadow-sm">
                    <p class="text-[10px] font-bold text-emerald-600 uppercase tracking-wider">Dalam Produksi / Order</p>
                    <h3 class="text-2xl font-bold text-gray-900 mt-1">{{ $stats['in_order'] }}</h3>
                </div>
            </div>

            <!-- Table Riwayat -->
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
                <div class="p-4 sm:p-6 border-b border-gray-100 flex flex-col md:flex-row md:items-center justify-between gap-3">
                    <h3 class="font-bold text-sm text-gray-900">Daftar Proyek Kustom B2B</h3>

                    <form method="GET" action="{{ b2b_route('designer.b2b.riwayat') }}" class="flex flex-col sm:flex-row items-stretch sm:items-center gap-2.5">
                        <div class="relative">
                            <i class="fa-solid fa-magnifying-glass absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-xs pointer-events-none"></i>
                            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari proyek atau pabrik..." class="h-9 pl-8 pr-3 text-xs bg-gray-50 border border-gray-200 rounded-xl focus:bg-white focus:outline-none focus:border-primary w-full sm:w-56">
                        </div>
                        <div class="relative">
                            <select name="status" onchange="this.form.submit()" class="appearance-none h-9 pl-3 pr-8 text-xs font-semibold bg-gray-50 border border-gray-200 rounded-xl focus:bg-white focus:outline-none focus:border-primary w-full sm:w-auto cursor-pointer">
                                <option value="">Semua Status</option>
                                <option value="PENDING" @selected(request('status') === 'PENDING')>Menunggu Penawaran</option>
                                <option value="QUOTED" @selected(request('status') === 'QUOTED')>Penawaran Masuk</option>
                                <option value="IN_ORDER" @selected(request('status') === 'IN_ORDER')>Produksi / Order</option>
                                <option value="REJECTED" @selected(request('status') === 'REJECTED')>Ditolak</option>
                            </select>
                            <i class="fa-solid fa-chevron-down text-[10px] text-gray-400 absolute right-3 top-1/2 -translate-y-1/2 pointer-events-none"></i>
                        </div>
                        <button type="submit" class="h-9 bg-primary hover:bg-[#8C4E1E] text-white text-xs font-bold px-4 rounded-xl transition-all cursor-pointer">Cari</button>
                    </form>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse text-xs min-w-[760px]">
                        <thead>
                            <tr class="bg-gray-50 border-b border-gray-100 text-gray-500 font-semibold uppercase text-[10px]">
                                <th class="py-3.5 px-5">ID &amp; Tanggal</th>
                                <th class="py-3.5 px-5">Proyek &amp; Pabrik Mitra</th>
                                <th class="py-3.5 px-5">Nilai Kontrak / Budget</th>
                                <th class="py-3.5 px-5">Tenggat</th>
                                <th class="py-3.5 px-5">Status</th>
                                <th class="py-3.5 px-5 text-right">Aksi Langsung</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse($rfqs as $rfq)
                                @php
                                    $statusMap = [
                                        'PENDING' => ['label' => 'Menunggu Penawaran', 'class' => 'bg-amber-100 text-amber-800'],
                                        'QUOTED' => ['label' => 'Penawaran Masuk', 'class' => 'bg-blue-100 text-blue-700'],
                                        'IN_ORDER' => ['label' => 'Dalam Produksi', 'class' => 'bg-emerald-100 text-emerald-700'],
                                        'REJECTED' => ['label' => 'Ditolak', 'class' => 'bg-red-100 text-red-700'],
                                        'CANCELLED' => ['label' => 'Dibatalkan', 'class' => 'bg-gray-100 text-gray-600'],
                                    ];
                                    $cfg = $statusMap[$rfq->status] ?? ['label' => $rfq->status, 'class' => 'bg-gray-100 text-gray-600'];
                                    $displayPrice = $rfq->latestQuotation ? $rfq->latestQuotation->total_price : $rfq->budget_ceiling;
                                @endphp
                                <tr class="hover:bg-gray-50/60 transition-colors">
                                    <td class="py-4 px-5">
                                        <span class="font-bold text-primary">#{{ $rfq->rfq_number }}</span>
                                        <p class="text-[10px] text-gray-400 mt-0.5">{{ $rfq->created_at?->format('d M Y') }}</p>
                                    </td>
                                    <td class="py-4 px-5">
                                        <p class="font-bold text-gray-900">{{ $rfq->project_name }}</p>
                                        <p class="text-[10px] text-gray-500 mt-0.5">
                                            Pabrik: <b class="text-gray-700">{{ $rfq->seller->store_name ?? '-' }}</b>
                                        </p>
                                    </td>
                                    <td class="py-4 px-5">
                                        <span class="font-bold text-gray-900">Rp {{ number_format((float) $displayPrice, 0, ',', '.') }}</span>
                                        <p class="text-[10px] text-gray-400 mt-0.5">
                                            {{ $rfq->latestQuotation ? 'Nilai Quotation' : 'Estimasi Budget' }}
                                        </p>
                                    </td>
                                    <td class="py-4 px-5 text-gray-600">
                                        {{ $rfq->deadline?->format('d M Y') ?? '-' }}
                                    </td>
                                    <td class="py-4 px-5">
                                        <span class="{{ $cfg['class'] }} px-2.5 py-1 rounded-full text-[10px] font-bold whitespace-nowrap">{{ $cfg['label'] }}</span>
                                    </td>
                                    <td class="py-4 px-5 text-right whitespace-nowrap">
                                        <div class="inline-flex items-center gap-2">
                                            @if($rfq->status === 'QUOTED' && $rfq->latestQuotation)
                                                <a href="{{ b2b_route('designer.b2b.quotation', $rfq->latestQuotation) }}" class="h-9 px-3.5 bg-primary hover:bg-[#8C4E1E] text-white rounded-lg font-bold text-xs inline-flex items-center justify-center whitespace-nowrap shadow-sm transition-all">
                                                    Review &amp; Bayar DP <i class="fa-solid fa-arrow-right ml-1.5"></i>
                                                </a>
                                            @elseif($rfq->status === 'IN_ORDER' && $rfq->order)
                                                <a href="{{ b2b_route('designer.b2b.milestone', $rfq->order) }}" class="h-9 px-3.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg font-bold text-xs inline-flex items-center justify-center whitespace-nowrap shadow-sm transition-all">
                                                    Pantau Progres <i class="fa-solid fa-arrow-right ml-1.5"></i>
                                                </a>
                                                <a href="{{ b2b_route('designer.b2b.bast', $rfq->order) }}" class="h-9 px-3.5 border border-gray-200 hover:border-primary text-gray-700 rounded-lg font-bold text-xs inline-flex items-center justify-center whitespace-nowrap transition-all">
                                                    BAST
                                                </a>
                                            @else
                                                <a href="{{ b2b_route('designer.b2b.negosiasi', $rfq) }}" class="h-9 px-3.5 border border-gray-200 hover:border-primary text-gray-700 rounded-lg font-bold text-xs inline-flex items-center justify-center whitespace-nowrap transition-all">
                                                    Negosiasi &amp; Chat <i class="fa-solid fa-arrow-right ml-1.5"></i>
                                                </a>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="py-12 text-center text-gray-500 text-xs">
                                        <i class="fa-solid fa-folder-open text-3xl mb-2 block text-gray-300"></i>
                                        Belum ada riwayat pengajuan proyek B2B.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if($rfqs->hasPages())
                    <div class="p-4 border-t border-gray-100">
                        {{ $rfqs->links() }}
                    </div>
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
