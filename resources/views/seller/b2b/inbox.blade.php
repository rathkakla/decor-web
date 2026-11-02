<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Decor Seller - Inbox RFQ B2B</title>
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

    @include('seller.partials.sidebar')

    <!-- Main Content -->
    <main id="main-content" class="flex-1 flex flex-col ml-64 sidebar-transition min-h-screen">
        <header class="h-16 bg-primary flex items-center justify-between px-8 sticky top-0 z-40 shadow-sm text-white">
            <div class="flex items-center">
                <button id="toggle-sidebar" class="text-white hover:opacity-80 mr-4 flex items-center justify-center transition-transform active:scale-95">
                    <i class="fa-solid fa-bars-staggered text-xl"></i>
                </button>
                <div class="flex flex-col">
                    <h2 class="font-bold text-xs uppercase tracking-widest leading-none">Pengadaan Kustom B2B</h2>
                    <span class="text-[10px] text-white/80 mt-1">Inbox Permintaan Penawaran (RFQ Inbound)</span>
                </div>
            </div>
            <div class="flex items-center space-x-6">
                <i class="fa-regular fa-bell text-xl cursor-pointer"></i>
                <div class="flex items-center space-x-3 bg-white/10 px-3 py-1.5 rounded-xl border border-white/20">
                    <span class="text-[10px] font-bold uppercase tracking-widest">{{ auth()->user()->seller->store_name ?? 'Seller' }}</span>
                    <img src="{{ auth()->user()->seller->store_image_url ?? 'https://ui-avatars.com/api/?name=Seller&background=B5733A&color=fff' }}" class="w-8 h-8 rounded-lg border border-white/30 object-cover">
                </div>
            </div>
        </header>

        <div class="p-8 space-y-8 flex-1">
            <!-- Breadcrumbs & Heading -->
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                <div>
                    <h2 class="text-2xl font-bold tracking-tight text-gray-900">Permintaan Masuk Proyek B2B (RFQ)</h2>
                    <p class="text-xs text-gray-500 mt-1">Kelola dan tinjau berkas gambar kerja CAD dari desainer interior untuk pengadaan mebel massal.</p>
                </div>
                <div class="flex items-center space-x-3">
                    <span class="bg-emerald-50 text-emerald-700 border border-emerald-200 text-xs font-semibold px-3 py-1.5 rounded-full flex items-center">
                        <span class="w-2 h-2 rounded-full bg-emerald-500 mr-2 animate-pulse"></span> {{ $stats['pending_quotation'] ?? 0 }} RFQ Membutuhkan Respon
                    </span>
                </div>
            </div>

            <!-- Stats Overview -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm flex items-center justify-between">
                    <div>
                        <p class="text-[10px] text-gray-400 font-bold uppercase tracking-wider">RFQ Butuh Penawaran</p>
                        <h3 class="text-2xl font-bold mt-1 text-gray-900">{{ $stats['pending_quotation'] ?? 0 }} Proyek</h3>
                        <p class="text-xs text-primary font-medium mt-1">Tenggat rata-rata 3 hari</p>
                    </div>
                    <div class="w-12 h-12 rounded-xl bg-orange-50 text-primary flex items-center justify-center text-xl">
                        <i class="fa-solid fa-inbox"></i>
                    </div>
                </div>
                <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm flex items-center justify-between">
                    <div>
                        <p class="text-[10px] text-gray-400 font-bold uppercase tracking-wider">Potensi Nilai Kontrak</p>
                        <h3 class="text-2xl font-bold mt-1 text-gray-900">Rp {{ number_format($stats['potential_value'] ?? 0, 0, ',', '.') }}</h3>
                        <p class="text-xs text-emerald-600 font-medium mt-1">Akumulasi estimasi desainer</p>
                    </div>
                    <div class="w-12 h-12 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-xl">
                        <i class="fa-solid fa-handshake"></i>
                    </div>
                </div>
                <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm flex items-center justify-between">
                    <div>
                        <p class="text-[10px] text-gray-400 font-bold uppercase tracking-wider">Kapasitas Workshop</p>
                        <h3 class="text-2xl font-bold mt-1 text-gray-900">{{ $stats['capacity_available'] ?? 0 }} Unit</h3>
                        <p class="text-xs text-blue-600 font-medium mt-1">Tersedia untuk bulan ini</p>
                    </div>
                    <div class="w-12 h-12 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center text-xl">
                        <i class="fa-solid fa-warehouse"></i>
                    </div>
                </div>
            </div>

            <!-- RFQ Table Card -->
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
                <div class="p-6 border-b border-gray-100 flex flex-col md:flex-row md:items-center justify-between gap-4">
                    <h3 class="font-bold text-base text-gray-900">Daftar Pengajuan Kustomisasi B2B Aktif</h3>
                    <form method="GET" action="{{ b2b_route('seller.b2b.inbox') }}" class="flex items-center space-x-3">
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama proyek atau desainer..." class="text-xs border border-gray-200 rounded-lg px-3 py-2 w-64 focus:outline-none focus:border-primary">
                        <select name="status" onchange="this.form.submit()" class="text-xs border border-gray-200 rounded-lg px-3 py-2 focus:outline-none focus:border-primary">
                            <option value="">Semua Status</option>
                            <option value="PENDING" @selected(request('status') === 'PENDING')>Butuh Penawaran</option>
                            <option value="QUOTED" @selected(request('status') === 'QUOTED')>Dalam Analisis</option>
                            <option value="IN_ORDER" @selected(request('status') === 'IN_ORDER')>Produksi</option>
                            <option value="REJECTED" @selected(request('status') === 'REJECTED')>Ditolak</option>
                        </select>
                        <button type="submit" class="bg-primary hover:bg-[#8C4E1E] text-white text-xs font-bold px-3 py-2 rounded-lg transition-all">Cari</button>
                    </form>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse text-xs">
                        <thead>
                            <tr class="bg-gray-50/70 border-b border-gray-100 text-gray-500 font-semibold uppercase text-[10px] tracking-wider">
                                <th class="py-4 px-6">ID & Tanggal</th>
                                <th class="py-4 px-6">Proyek & Desainer</th>
                                <th class="py-4 px-6">Kebutuhan Mebel</th>
                                <th class="py-4 px-6">Pagu Anggaran</th>
                                <th class="py-4 px-6">Tenggat Waktu</th>
                                <th class="py-4 px-6">Status</th>
                                <th class="py-4 px-6 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 font-medium">
                            @forelse($rfqs as $rfq)
                                <tr class="hover:bg-gray-50/50 transition-colors {{ $rfq->status === 'PENDING' ? 'bg-amber-50/30' : '' }}">
                                    <td class="py-4 px-6">
                                        <span class="font-bold {{ $rfq->status === 'PENDING' ? 'text-primary' : 'text-gray-600' }}">#{{ $rfq->rfq_number }}</span>
                                        <p class="text-[10px] text-gray-400 mt-0.5">{{ $rfq->created_at->format('d M Y') }}</p>
                                    </td>
                                    <td class="py-4 px-6">
                                        <div class="flex items-center space-x-3">
                                            <img src="https://ui-avatars.com/api/?name={{ urlencode($rfq->designer->user->full_name ?? 'Designer') }}&background=FAF1E8&color=B5733A" class="w-8 h-8 rounded-full border border-primary/20">
                                            <div>
                                                <p class="font-bold text-gray-900">{{ $rfq->project_name }}</p>
                                                <p class="text-[10px] text-gray-500">{{ $rfq->designer->user->full_name ?? 'Designer' }} ({{ $rfq->designer->studio_name ?? 'Studio' }})</p>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="py-4 px-6">
                                        @php
                                            $totalQty = collect($rfq->items ?? [])->sum('qty');
                                            $firstItem = collect($rfq->items ?? [])->first();
                                        @endphp
                                        <p class="font-bold text-gray-800">{{ $totalQty ?? count($rfq->items ?? []) }} Unit Mebel Kustom</p>
                                        <p class="text-[10px] text-gray-500">{{ $firstItem['name'] ?? $rfq->wood_type }} {{ $rfq->wood_type ? '&bull; ' . $rfq->wood_type : '' }}</p>
                                    </td>
                                    <td class="py-4 px-6">
                                        <span class="font-bold text-gray-900 text-sm">Rp {{ number_format($rfq->budget_ceiling, 0, ',', '.') }}</span>
                                    </td>
                                    <td class="py-4 px-6">
                                        <span class="text-gray-700">{{ $rfq->deadline?->format('d M Y') ?? '-' }}</span>
                                        @php
                                            $daysLeft = $rfq->deadline ? now()->startOfDay()->diffInDays($rfq->deadline, false) : null;
                                        @endphp
                                        @if($daysLeft !== null)
                                            <p class="text-[10px] {{ $daysLeft < 0 ? 'text-red-600' : 'text-orange-600' }} font-semibold">{{ abs($daysLeft) }} Hari {{ $daysLeft < 0 ? 'Lewat' : 'Lagi' }}</p>
                                        @endif
                                    </td>
                                    <td class="py-4 px-6">
                                        @php
                                            $statusConfig = [
                                                'PENDING' => ['label' => 'Butuh Penawaran', 'class' => 'bg-orange-100 text-orange-700'],
                                                'QUOTED' => ['label' => 'Dalam Analisis', 'class' => 'bg-blue-100 text-blue-700'],
                                                'IN_ORDER' => ['label' => 'Produksi', 'class' => 'bg-emerald-100 text-emerald-700'],
                                                'REJECTED' => ['label' => 'Ditolak', 'class' => 'bg-red-100 text-red-700'],
                                                'CANCELLED' => ['label' => 'Dibatalkan', 'class' => 'bg-gray-100 text-gray-600'],
                                            ];
                                            $cfg = $statusConfig[$rfq->status] ?? ['label' => $rfq->status, 'class' => 'bg-gray-100 text-gray-600'];
                                        @endphp
                                        <span class="{{ $cfg['class'] }} px-2.5 py-1 rounded-full text-[10px] font-bold">{{ $cfg['label'] }}</span>
                                    </td>
                                    <td class="py-4 px-6 text-right">
                                        @if($rfq->status === 'PENDING')
                                            <a href="{{ b2b_route('seller.b2b.rfq.review', $rfq) }}" class="bg-primary hover:bg-[#8C4E1E] text-white px-3.5 py-2 rounded-lg font-bold text-xs inline-flex items-center transition-all shadow-sm">
                                                Tinjau Proyek <i class="fa-solid fa-arrow-right ml-1.5"></i>
                                            </a>
                                        @elseif($rfq->status === 'QUOTED' && $rfq->latestQuotation)
                                            <a href="{{ b2b_route('seller.b2b.quotation.edit', $rfq->latestQuotation) }}" class="bg-primary hover:bg-[#8C4E1E] text-white px-3.5 py-2 rounded-lg font-bold text-xs inline-flex items-center transition-all shadow-sm">
                                                Edit Quotation <i class="fa-solid fa-arrow-right ml-1.5"></i>
                                            </a>
                                        @else
                                            <a href="{{ b2b_route('seller.b2b.rfq.review', $rfq) }}" class="border border-gray-200 hover:border-primary text-gray-700 px-3 py-2 rounded-lg font-semibold text-xs inline-flex items-center">
                                                Detail
                                            </a>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="py-12 text-center text-gray-500 text-sm">
                                        <i class="fa-solid fa-inbox text-3xl mb-2 block text-gray-300"></i>
                                        Belum ada RFQ masuk untuk workshop ini.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- Pagination --}}
                @if($rfqs->hasPages())
                    <div class="p-4 border-t border-gray-100">
                        {{ $rfqs->links() }}
                    </div>
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
