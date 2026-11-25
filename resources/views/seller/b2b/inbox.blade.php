<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Decor Seller - Inbox RFQ B2B</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    @include('seller.partials.head')
</head>
<body class="text-gray-800">

    @include('seller.partials.sidebar')

    <div id="sidebar-backdrop" class="fixed inset-0 bg-black/40 z-40 hidden"></div>

    <!-- Main Content -->
    <main id="main-content" class="flex-1 flex flex-col ml-0 md:ml-64 sidebar-transition min-h-screen">
        <header class="h-16 bg-primary flex items-center justify-between px-4 sm:px-6 lg:px-8 sticky top-0 z-40 shadow-sm text-white">
            <div class="flex items-center">
                <button id="toggle-sidebar" class="text-white hover:opacity-80 mr-4 flex items-center justify-center transition-transform active:scale-95">
                    <i class="fa-solid fa-bars-staggered text-xl"></i>
                </button>
                <div class="flex flex-col">
                    <h2 class="font-bold text-xs uppercase tracking-widest leading-none">Pesanan Proyek</h2>
                    <span class="text-[10px] text-white/80 mt-1">Inbox Permintaan Penawaran (RFQ Inbound)</span>
                </div>
            </div>
            <div class="flex items-center space-x-3 sm:space-x-6">
                <i class="fa-regular fa-bell text-lg sm:text-xl cursor-pointer"></i>
                <div class="flex items-center space-x-2 sm:space-x-3 bg-white/10 px-2.5 sm:px-3 py-1.5 rounded-xl border border-white/20">
                    <span class="text-[10px] font-bold uppercase tracking-widest hidden sm:inline">{{ auth()->user()->seller->store_name ?? 'Seller' }}</span>
                    <img src="{{ auth()->user()->seller->store_image_url ?? 'https://ui-avatars.com/api/?name=Seller&background=B5733A&color=fff' }}" class="w-7 h-7 sm:w-8 sm:h-8 rounded-lg border border-white/30 object-cover">
                </div>
            </div>
        </header>

        <div class="p-4 sm:p-6 lg:p-8 space-y-6 sm:space-y-8 flex-1">
            <!-- Breadcrumbs & Heading -->
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                <div>
                    <h2 class="text-xl sm:text-2xl font-bold tracking-tight text-gray-900">Permintaan Masuk Proyek B2B (RFQ)</h2>
                    <p class="text-xs text-gray-500 mt-1">Kelola dan tinjau berkas gambar kerja CAD dari desainer interior untuk pengadaan mebel massal.</p>
                </div>
                <div class="flex items-center space-x-3">
                    <span class="bg-emerald-50 text-emerald-700 border border-emerald-200 text-xs font-semibold px-3 py-1.5 rounded-full flex items-center">
                        <span class="w-2 h-2 rounded-full bg-emerald-500 mr-2 animate-pulse"></span> {{ $stats['pending_quotation'] ?? 0 }} RFQ Membutuhkan Respon
                    </span>
                </div>
            </div>

            <!-- Stats Overview -->
            <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-4 sm:gap-6">
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
                @php
                    $paidRevenue = (float) ($stats['paid_revenue'] ?? 0);
                    $paidProjects = (int) ($stats['paid_projects'] ?? 0);
                    $completedProjects = (int) ($stats['completed_projects'] ?? 0);
                    $paidLabel = 'Total Rp ' . number_format($paidRevenue, 0, ',', '.') . ' diterima';
                    if ($paidProjects !== $completedProjects) {
                        $paidLabel .= " \u{00B7} {$paidProjects} proyek lunas";
                    }
                @endphp
                <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm flex items-center justify-between">
                    <div>
                        <p class="text-[10px] text-gray-400 font-bold uppercase tracking-wider">Proyek Selesai</p>
                        <h3 class="text-2xl font-bold mt-1 text-gray-900">{{ $completedProjects }} Proyek</h3>
                        <p class="text-xs text-emerald-600 font-medium mt-1">{{ $paidLabel }}</p>
                    </div>
                    <div class="w-12 h-12 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-xl">
                        <i class="fa-solid fa-circle-check"></i>
                    </div>
                </div>
            </div>

            <!-- RFQ Table Card -->
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
                <div class="p-4 sm:p-6 border-b border-gray-100 flex flex-col md:flex-row md:items-center justify-between gap-4">
                    <div>
                        <h3 class="font-bold text-sm sm:text-base text-gray-900">Daftar Permintaan Proyek</h3>
                        <p class="text-[10px] text-gray-400 font-medium mt-1">{{ $rfqs->total() }} permintaan proyek ditemukan</p>
                    </div>
                    <form method="GET" action="{{ b2b_route('seller.b2b.inbox') }}" class="flex flex-col sm:flex-row items-stretch sm:items-center gap-2">
                        <div class="relative flex-1 sm:w-72">
                            <i class="fa-solid fa-magnifying-glass absolute left-3 top-1/2 -translate-y-1/2 text-gray-300 text-xs"></i>
                            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama proyek atau desainer..." class="w-full h-10 text-xs border border-gray-200 rounded-lg pl-9 pr-3 focus:outline-none focus:border-primary focus:ring-2 focus:ring-primary/10">
                        </div>
                        <div class="relative">
                            <i class="fa-solid fa-filter absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-xs pointer-events-none"></i>
                            <select name="status" onchange="this.form.submit()" aria-label="Filter Status" class="w-full sm:w-48 h-10 appearance-none bg-white text-xs font-medium text-gray-600 border border-gray-200 rounded-lg pl-9 pr-9 focus:outline-none focus:border-primary focus:ring-2 focus:ring-primary/10 cursor-pointer">
                                <option value="">Semua Status</option>
                                <option value="PENDING" @selected(request('status') === 'PENDING')>Butuh Penawaran</option>
                                <option value="QUOTED" @selected(request('status') === 'QUOTED')>Penawaran Terkirim</option>
                                <option value="IN_PRODUCTION" @selected(request('status') === 'IN_PRODUCTION')>Dalam Produksi</option>
                                <option value="SHIPPING" @selected(request('status') === 'SHIPPING')>Pengiriman</option>
                                <option value="COMPLETED" @selected(request('status') === 'COMPLETED')>Selesai</option>
                                <option value="REJECTED" @selected(request('status') === 'REJECTED')>Ditolak</option>
                            </select>
                            <i class="fa-solid fa-chevron-down absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 text-[10px] pointer-events-none"></i>
                        </div>
                        <button type="submit" class="inline-flex items-center justify-center h-10 bg-primary hover:bg-[#8C4E1E] text-white text-xs font-semibold px-4 rounded-lg transition-all whitespace-nowrap">Cari</button>
                        @if(request('search') || request('status'))
                            <a href="{{ b2b_route('seller.b2b.inbox') }}" class="inline-flex items-center justify-center h-10 px-3 border border-gray-200 text-gray-600 hover:border-primary hover:text-primary rounded-lg text-xs font-semibold transition-all whitespace-nowrap">Reset</a>
                        @endif
<div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse text-xs min-w-[760px]">
                        <thead>
                            <tr class="bg-gray-50/70 border-b border-gray-100 text-gray-500 font-semibold uppercase text-[10px] tracking-wider">
                                <th class="py-3.5 px-5">Proyek</th>
                                <th class="py-3.5 px-5">Kebutuhan</th>
                                <th class="py-3.5 px-5 whitespace-nowrap">Anggaran</th>
                                <th class="py-3.5 px-5 whitespace-nowrap">Tenggat</th>
                                <th class="py-3.5 px-5 whitespace-nowrap">Status</th>
                                <th class="py-3.5 px-5 text-center w-28">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 font-medium">
                            @forelse($rfqs as $rfq)
                                @php
                                    $designerName = $rfq->designer->user->full_name ?? 'Designer';
                                    $studioName = $rfq->designer->studio_name ?? null;
                                    $designerLine = $studioName && $studioName !== $designerName ? "{$designerName} · {$studioName}" : $designerName;
                                    $quotation = $rfq->latestQuotation;
                                    $order = $rfq->order;
                                    $itemList = ($quotation && !empty($quotation->items)) ? $quotation->items : ($rfq->items ?? []);
                                    $totalQty = collect($itemList)->sum('qty');
                                    $firstItem = collect($itemList)->first();
                                    $firstItemName = $firstItem['name'] ?? ($rfq->wood_type ?? 'Kebutuhan kustom');
                                    $displayAmount = $quotation ? (float) $quotation->total_price : (float) $rfq->budget_ceiling;
                                    $daysLeft = $rfq->deadline ? now()->startOfDay()->diffInDays($rfq->deadline, false) : null;
                                    $isFinished = $order && ($order->status === 'COMPLETED' || $order->payout_status === 'PAID');

                                    if ($order && ($order->status === 'COMPLETED' || $order->payout_status === 'PAID')) {
                                        $cfg = ['label' => 'Selesai', 'class' => 'bg-emerald-100 text-emerald-700'];
                                    } elseif ($order && $order->status === 'DELIVERED') {
                                        $cfg = ['label' => 'Tiba di Lokasi', 'class' => 'bg-blue-100 text-blue-800'];
                                    } elseif ($order && $order->status === 'READY_FOR_DELIVERY') {
                                        $cfg = ['label' => 'Siap Dikirim', 'class' => 'bg-sky-100 text-sky-800'];
                                    } elseif ($rfq->status === 'IN_ORDER') {
                                        $cfg = ['label' => 'Dalam Produksi', 'class' => 'bg-amber-100 text-amber-800'];
                                    } elseif ($rfq->status === 'QUOTED') {
                                        $cfg = ['label' => 'Penawaran Terkirim', 'class' => 'bg-blue-100 text-blue-700'];
                                    } elseif ($rfq->status === 'PENDING') {
                                        $cfg = ['label' => 'Butuh Penawaran', 'class' => 'bg-orange-100 text-orange-700'];
                                    } elseif ($rfq->status === 'REJECTED') {
                                        $cfg = ['label' => 'Ditolak', 'class' => 'bg-red-100 text-red-700'];
                                    } else {
                                        $cfg = ['label' => 'Batal', 'class' => 'bg-gray-100 text-gray-600'];
                                    }

                                    $neutralIconBtn = 'w-9 h-9 rounded-lg border border-gray-200 bg-gray-50 text-gray-500 hover:bg-gray-100 hover:text-gray-800 inline-flex items-center justify-center transition-all';
                                    $primaryIconBtn = 'w-9 h-9 rounded-lg border border-primary/20 bg-primary/10 text-primary hover:bg-primary hover:text-white inline-flex items-center justify-center transition-all';
                                    $emeraldIconBtn = 'w-9 h-9 rounded-lg border border-emerald-200 bg-emerald-50 text-emerald-600 hover:bg-emerald-600 hover:text-white inline-flex items-center justify-center transition-all';
                                    $tooltipClass = 'pointer-events-none absolute right-full top-1/2 -translate-y-1/2 mr-2 whitespace-nowrap rounded-md bg-gray-900 px-2 py-1 text-[10px] font-semibold text-white opacity-0 transition-opacity group-hover:opacity-100 group-focus-within:opacity-100 z-20 shadow-sm';
                                @endphp
                                <tr class="hover:bg-gray-50/50 transition-colors">
                                    <td class="py-4 px-5 min-w-[240px] max-w-[340px]">
                                        <p class="font-bold text-gray-900 line-clamp-2" title="{{ $rfq->project_name }}">{{ $rfq->project_name }}</p>
                                        <p class="text-[10px] text-gray-500 mt-0.5 truncate">{{ $designerLine }}</p>
                                        <p class="text-[10px] text-gray-400 mt-0.5">Diajukan {{ $rfq->created_at->format('d M Y') }}</p>
                                    </td>
                                    <td class="py-4 px-5 min-w-[160px] max-w-[220px]">
                                        <p class="font-bold text-gray-800 whitespace-nowrap">{{ $totalQty ?: count($itemList) }} unit</p>
                                        <p class="text-[10px] text-gray-500 mt-0.5 truncate" title="{{ $firstItemName }}">{{ \Illuminate\Support\Str::limit($firstItemName, 28) }}</p>
                                    </td>
                                    <td class="py-4 px-5 whitespace-nowrap">
                                        <span class="font-bold text-gray-900 text-sm">Rp {{ number_format($displayAmount, 0, ',', '.') }}</span>
                                    </td>
                                    <td class="py-4 px-5 whitespace-nowrap">
                                        <span class="text-gray-700">{{ $rfq->deadline?->format('d M Y') ?? '-' }}</span>
                                        @if($isFinished)
                                            <p class="text-[10px] text-emerald-600 font-semibold mt-0.5">Selesai</p>
                                        @elseif($daysLeft !== null)
                                            <p class="text-[10px] {{ $daysLeft < 0 ? 'text-red-600' : 'text-orange-600' }} font-semibold mt-0.5">{{ abs($daysLeft) }} hari{{ $daysLeft < 0 ? ' lewat' : '' }}</p>
                                        @endif
                                    </td>
                                    <td class="py-4 px-5 whitespace-nowrap">
                                        <span class="{{ $cfg['class'] }} px-2.5 py-1 rounded-full text-[10px] font-bold whitespace-nowrap">{{ $cfg['label'] }}</span>
                                    </td>
                                    <td class="py-4 px-5 text-center w-28">
                                        <div class="flex items-center justify-center gap-1.5 flex-nowrap">
                                            <div class="relative group">
                                                <a href="{{ b2b_route('seller.b2b.rfq.review', $rfq) }}" aria-label="Detail" title="Detail" class="{{ $neutralIconBtn }}">
                                                    <i class="fa-solid fa-eye text-xs"></i>
                                                </a>
                                                <span class="{{ $tooltipClass }}">Detail</span>
                                            </div>
                                            @if($rfq->status === 'PENDING')
                                                <div class="relative group">
                                                    <a href="{{ b2b_route('seller.b2b.rfq.review', $rfq) }}" aria-label="Tinjau Proyek" title="Tinjau Proyek" class="{{ $primaryIconBtn }}">
                                                        <i class="fa-solid fa-file-signature text-xs"></i>
                                                    </a>
                                                    <span class="{{ $tooltipClass }}">Tinjau Proyek</span>
                                                </div>
                                            @elseif($rfq->status === 'QUOTED' && $rfq->latestQuotation)
                                                <div class="relative group">
                                                    <a href="{{ b2b_route('seller.b2b.quotation.edit', $rfq->latestQuotation) }}" aria-label="Edit Penawaran" title="Edit Penawaran" class="{{ $primaryIconBtn }}">
                                                        <i class="fa-solid fa-pen-to-square text-xs"></i>
                                                    </a>
                                                    <span class="{{ $tooltipClass }}">Edit Penawaran</span>
                                                </div>
                                            @elseif($rfq->status === 'IN_ORDER' && $order)
                                                <div class="relative group">
                                                    <a href="{{ $isFinished ? b2b_route('seller.b2b.surat_jalan', $order) : b2b_route('seller.b2b.produksi', $order) }}" aria-label="{{ $isFinished ? 'Surat Jalan & Dana' : 'Kelola Produksi' }}" title="{{ $isFinished ? 'Surat Jalan & Dana' : 'Kelola Produksi' }}" class="{{ $emeraldIconBtn }}">
                                                        <i class="fa-solid {{ $isFinished ? 'fa-file-invoice-dollar' : 'fa-hammer' }} text-xs"></i>
                                                    </a>
                                                    <span class="{{ $tooltipClass }}">{{ $isFinished ? 'Surat Jalan & Dana' : 'Kelola Produksi' }}</span>
                                                </div>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="py-12 text-center text-gray-500 text-sm">
                                        <i class="fa-solid fa-inbox text-3xl mb-2 block text-gray-300"></i>
                                        Belum ada permintaan proyek masuk untuk workshop ini.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                </div>

                @include('partials.pagination', ['paginator' => $rfqs, 'label' => 'RFQ'])
            </div>
        </div>
    </main>

    
<script async defer data-website-id="cd56ccb0-617d-4bec-8e77-0ba7ea566e9a" data-host-url="/umami-analytics" src="/umami-analytics/script.js"></script>
</body>
</html>
