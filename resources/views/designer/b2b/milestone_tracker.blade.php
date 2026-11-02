<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Decor Designer - Pelacak Milestone & QC Workshop B2B</title>
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
                    <a href="{{ b2b_route('designer.b2b.katalog') }}" class="text-white/80 hover:text-white">Pengadaan B2B</a>
                    <span class="text-white/50">/</span>
                    <a href="{{ b2b_route('designer.b2b.negosiasi', $order->rfq) }}" class="text-white/80 hover:text-white">Kafe Urban Loft</a>
                    <span class="text-white/50">/</span>
                    <span class="font-bold text-white">Live Pelacak Produksi Pabrik</span>
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
        @php
            $rfq = $order->rfq;
            $seller = $rfq->seller;
            $quotation = $order->quotation;
            $progress = $order->progress_percent;
            $totalUnits = collect($rfq->items ?? [])->sum('qty');
            $allMilestonesDone = $order->milestones->isNotEmpty() && $order->milestones->every(fn ($m) => $m->progress_percent >= 100);

            $delivery = $order->delivery;
            $finalPaid = in_array($order->payment_status, [\App\Models\B2bOrder::PAYMENT_FULL_HELD, \App\Models\B2bOrder::PAYMENT_RELEASED], true);
            $released = $order->payment_status === \App\Models\B2bOrder::PAYMENT_RELEASED;
            $isShipped = in_array($order->status, [\App\Models\B2bOrder::STATUS_DELIVERED, \App\Models\B2bOrder::STATUS_COMPLETED], true);
            $awaitingDelivery = $order->status === \App\Models\B2bOrder::STATUS_READY_FOR_DELIVERY;
            $deliveryStatusLabels = [
                'PREPARING' => ['label' => 'Disiapkan / Muat Barang', 'class' => 'bg-amber-100 text-amber-800'],
                'IN_TRANSIT' => ['label' => 'Dalam Perjalanan', 'class' => 'bg-blue-100 text-blue-800'],
                'DELIVERED' => ['label' => 'Tiba di Lokasi', 'class' => 'bg-emerald-100 text-emerald-800'],
            ];
            $deliveryStatus = $delivery ? ($deliveryStatusLabels[$delivery->status] ?? ['label' => $delivery->status, 'class' => 'bg-gray-100 text-gray-700']) : null;
        @endphp

        <div class="p-8 space-y-6 max-w-7xl">
            @if(session('success'))
                <div class="bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-2xl p-4 text-xs font-semibold">
                    <i class="fa-solid fa-circle-check mr-1"></i> {{ session('success') }}
                </div>
            @endif
            @if($errors->any())
                <div class="bg-red-50 border border-red-200 text-red-700 rounded-2xl p-4 text-xs">
                    <ul class="list-disc list-inside space-y-0.5">
                        @foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach
                    </ul>
                </div>
            @endif

            <!-- Header Bar -->
            <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm flex flex-col md:flex-row md:items-center justify-between gap-4">
                <div>
                    <div class="flex items-center space-x-3 mb-1">
                        <span class="bg-amber-100 text-amber-800 text-[10px] font-extrabold px-3 py-1 rounded-full uppercase tracking-wider">
                            <i class="fa-solid fa-industry mr-1"></i> Status Order: {{ str_replace('_', ' ', $order->status) }}
                        </span>
                        <span class="text-xs text-gray-400">PO: {{ $order->order_number }}</span>
                    </div>
                    <h2 class="text-xl font-extrabold text-gray-900">Pantau Progres & Kendali Mutu (QC) Furnitur Kustom</h2>
                    <p class="text-xs text-gray-500 mt-0.5">
                        Workshop Mitra: <b class="text-gray-700">{{ $seller->store_name }}</b>
                        &bull; Total Pesanan: <b class="text-primary">{{ $totalUnits ?: count($rfq->items ?? []) }} Unit</b>
                    </p>
                </div>

                <div class="flex items-center space-x-3">
                    <a href="{{ b2b_route('designer.b2b.negosiasi', $order->rfq) }}" class="px-4 py-2.5 rounded-xl border border-gray-200 text-xs font-bold text-gray-600 hover:bg-gray-50 transition-all flex items-center">
                        <i class="fa-solid fa-comments mr-2"></i> Chat Produsen
                    </a>
                    @if($isShipped)
                        <a href="{{ b2b_route('designer.b2b.bast', $order) }}" class="bg-primary hover:bg-[#8C4E1E] text-white px-5 py-2.5 rounded-xl font-bold text-xs transition-all shadow-md flex items-center">
                            Serah Terima & BAST <i class="fa-solid fa-file-signature ml-2"></i>
                        </a>
                    @elseif($awaitingDelivery)
                        <span class="bg-blue-50 text-blue-700 border border-blue-200 px-5 py-2.5 rounded-xl font-bold text-xs flex items-center cursor-not-allowed" title="Pabrik sedang menerbitkan Surat Jalan">
                            <i class="fa-solid fa-truck-fast mr-2"></i> Menunggu Pengiriman
                        </span>
                    @else
                        <span class="bg-gray-100 text-gray-400 px-5 py-2.5 rounded-xl font-bold text-xs flex items-center cursor-not-allowed" title="BAST aktif setelah barang tiba di lokasi proyek">
                            Serah Terima & BAST <i class="fa-solid fa-file-signature ml-2"></i>
                        </span>
                    @endif
                </div>
            </div>

            <!-- Total Progres & Escrow Status Bar -->
            <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm space-y-4">
                <div class="flex items-center justify-between text-xs font-bold">
                    <div class="flex items-center space-x-2">
                        <i class="fa-solid fa-bars-progress text-primary text-base"></i>
                        <span class="text-gray-900">Tingkat Penyelesaian Manufaktur Fisik</span>
                    </div>
                    <span class="text-primary text-sm font-extrabold">{{ $progress }}% Rampung</span>
                </div>
                <div class="w-full bg-gray-100 rounded-full h-3 overflow-hidden">
                    <div class="bg-gradient-to-r from-amber-500 to-primary h-3 rounded-full transition-all duration-500" style="width: {{ $progress }}%"></div>
                </div>

                <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 pt-2 text-center text-xs">
                    <div class="p-3 bg-gray-50 rounded-xl">
                        <p class="text-[10px] text-gray-400 uppercase font-bold">Target Pengiriman</p>
                        <p class="font-extrabold text-gray-800 mt-0.5">{{ $rfq->deadline?->format('d M Y') ?? '-' }}</p>
                    </div>
                    <div class="p-3 bg-gray-50 rounded-xl">
                        <p class="text-[10px] text-gray-400 uppercase font-bold">Estimasi Selesai</p>
                        <p class="font-extrabold {{ $allMilestonesDone ? 'text-emerald-600' : 'text-amber-600' }} mt-0.5">
                            {{ $allMilestonesDone ? 'Selesai Produksi' : ($quotation->estimated_days ?? '-') . ' Hari Kerja' }}
                        </p>
                    </div>
                    <div class="p-3 bg-gray-50 rounded-xl">
                        <p class="text-[10px] text-gray-400 uppercase font-bold">Milestone Lolos QC</p>
                        <p class="font-extrabold text-gray-800 mt-0.5">{{ $order->milestones->where('progress_percent', '>=', 100)->count() }} / {{ $order->milestones->count() }} Pos</p>
                    </div>
                    <div class="p-3 {{ $finalPaid ? 'bg-emerald-50 border border-emerald-200' : 'bg-gray-50' }} rounded-xl">
                        <p class="text-[10px] {{ $finalPaid ? 'text-emerald-700' : 'text-gray-400' }} uppercase font-bold">{{ $released ? 'Dana Dirilis' : 'Dana di Escrow' }}</p>
                        <p class="font-extrabold {{ $finalPaid ? 'text-emerald-700' : 'text-gray-800' }} mt-0.5">
                            @if($released)
                                Selesai
                            @else
                                Rp {{ number_format((float) $order->escrow_balance, 0, ',', '.') }}
                            @endif
                        </p>
                    </div>
                </div>
            </div>

            <!-- Stepper Alur Pasca Produksi -->
            @php
                $steps = [
                    ['label' => 'Produksi & QC', 'hint' => $progress . '% rampung', 'done' => $allMilestonesDone, 'active' => ! $allMilestonesDone],
                    ['label' => 'Pelunasan Escrow', 'hint' => $finalPaid ? 'Dana 100% aman' : 'Menunggu pelunasan', 'done' => $finalPaid, 'active' => $allMilestonesDone && ! $finalPaid],
                    ['label' => 'Pengiriman', 'hint' => $delivery ? ($deliveryStatus['label'] ?? $delivery->status) : 'Menunggu surat jalan', 'done' => $isShipped, 'active' => $finalPaid && ! $isShipped],
                    ['label' => 'BAST & Rilis Dana', 'hint' => $released ? 'Dana dirilis ke pabrik' : 'Setelah barang tiba', 'done' => $released, 'active' => $isShipped && ! $released],
                ];
            @endphp
            <div class="bg-white p-5 rounded-2xl border border-gray-100 shadow-sm">
                <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
                    @foreach($steps as $i => $step)
                        <div class="flex items-start space-x-3 p-3 rounded-xl {{ $step['done'] ? 'bg-emerald-50/60' : ($step['active'] ? 'bg-amber-50/60 ring-1 ring-amber-500/30' : 'bg-gray-50') }}">
                            <span class="w-7 h-7 shrink-0 rounded-full flex items-center justify-center text-[11px] font-bold {{ $step['done'] ? 'bg-emerald-600 text-white' : ($step['active'] ? 'bg-amber-500 text-white' : 'bg-gray-200 text-gray-500') }}">
                                @if($step['done'])
                                    <i class="fa-solid fa-check"></i>
                                @else
                                    {{ $i + 1 }}
                                @endif
                            </span>
                            <div class="min-w-0">
                                <p class="text-[11px] font-bold {{ $step['done'] ? 'text-emerald-800' : ($step['active'] ? 'text-amber-800' : 'text-gray-500') }}">{{ $step['label'] }}</p>
                                <p class="text-[10px] text-gray-400 truncate">{{ $step['hint'] }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
                <!-- Kolom Kiri: Milestone Detail -->
                <div class="lg:col-span-7 space-y-4">
                    <h3 class="text-sm font-bold text-gray-900 flex items-center">
                        <i class="fa-solid fa-list-check text-primary mr-2"></i> Status Milestone Produksi Sesuai Gambar CAD
                    </h3>

                    @forelse($order->milestones as $milestone)
                        @php
                            $done = $milestone->progress_percent >= 100;
                            $active = ! $done && $milestone->progress_percent > 0;
                            $border = $done ? 'border-emerald-500' : ($active ? 'border-amber-500' : 'border-gray-300');
                            $badge = $done ? 'bg-emerald-100 text-emerald-700' : ($active ? 'bg-amber-100 text-amber-800' : 'bg-gray-100 text-gray-500');
                        @endphp
                        <div class="bg-white p-5 rounded-2xl border-l-4 {{ $border }} border border-gray-100 shadow-sm space-y-2 {{ $done ? '' : ($active ? 'ring-1 ring-amber-500/30' : 'opacity-90') }}">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center space-x-3">
                                    <span class="w-7 h-7 {{ $done ? 'bg-emerald-100 text-emerald-600' : ($active ? 'bg-amber-100 text-amber-700' : 'bg-gray-100 text-gray-500') }} rounded-full flex items-center justify-center font-bold text-xs">
                                        @if($done)
                                            <i class="fa-solid fa-check"></i>
                                        @else
                                            {{ $milestone->stage_order }}
                                        @endif
                                    </span>
                                    <div>
                                        <h4 class="font-bold text-xs text-gray-900">Milestone {{ $milestone->stage_order }}: {{ $milestone->stage_name }}</h4>
                                        <p class="text-[10px] text-gray-400">
                                            {{ $milestone->target_date ? 'Target: ' . $milestone->target_date->format('d M Y') : 'Belum ada target tanggal' }}
                                            @if($milestone->is_approved_by_designer) &bull; Disetujui Desainer @endif
                                        </p>
                                    </div>
                                </div>
                                <span class="text-[10px] {{ $badge }} font-bold px-2.5 py-1 rounded-full">
                                    {{ $done ? 'Selesai 100%' : 'Progres: ' . $milestone->progress_percent . '%' }}
                                </span>
                            </div>
                            @if($milestone->qc_notes)
                                <p class="text-xs text-gray-600 pl-10">{{ $milestone->qc_notes }}</p>
                            @endif
                            <div class="pl-10">
                                <div class="w-full bg-gray-100 rounded-full h-1.5 overflow-hidden">
                                    <div class="{{ $done ? 'bg-emerald-500' : 'bg-amber-500' }} h-1.5 rounded-full" style="width: {{ $milestone->progress_percent }}%"></div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="bg-white p-8 rounded-2xl border border-gray-100 text-center text-xs text-gray-500">
                            Milestone produksi belum dibuat oleh pabrik.
                        </div>
                    @endforelse
                </div>

                <!-- Kolom Kanan: Galeri Bukti Foto Workshop & Hasil Inspeksi Desainer -->
                <div class="lg:col-span-5 space-y-6">
                    <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm space-y-4">
                        <div class="flex items-center justify-between">
                            <h3 class="text-sm font-bold text-gray-900 flex items-center">
                                <i class="fa-solid fa-camera text-primary mr-2"></i> Foto Dokumentasi Fisik Workshop
                            </h3>
                            <span class="text-[10px] bg-primary/10 text-primary font-bold px-2 py-0.5 rounded-full">
                                {{ $order->milestones->whereNotNull('photo_url')->count() }} Foto
                            </span>
                        </div>

                        <div class="grid grid-cols-2 gap-3">
                            @forelse($order->milestones->whereNotNull('photo_url') as $milestone)
                                <div class="space-y-1">
                                    <div class="rounded-xl overflow-hidden aspect-square border border-gray-100 group relative">
                                        <img src="{{ asset('storage/' . $milestone->photo_url) }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                                        <span class="absolute bottom-1 left-1 bg-black/70 text-white text-[9px] px-1.5 py-0.5 rounded">Pos {{ $milestone->stage_order }}</span>
                                    </div>
                                    <p class="text-[10px] text-gray-500">{{ \Illuminate\Support\Str::limit($milestone->stage_name, 28) }}</p>
                                </div>
                            @empty
                                <div class="col-span-2 text-center text-gray-400 py-6 text-xs">
                                    <i class="fa-solid fa-image text-2xl mb-2 block"></i>
                                    Belum ada foto QC yang diunggah pabrik.
                                </div>
                            @endforelse
                        </div>

                        <!-- Quality Verification Log -->
                        <div class="pt-4 border-t border-gray-100 space-y-2">
                            <p class="text-[11px] font-bold text-gray-700 uppercase tracking-wider">Catatan QC Pabrik:</p>
                            <div class="space-y-1.5 text-xs">
                                @forelse($order->milestones->whereNotNull('qc_notes') as $milestone)
                                    <div class="flex items-start justify-between gap-3 p-2 bg-emerald-50 text-emerald-800 rounded-lg">
                                        <span><i class="fa-solid fa-circle-check text-emerald-600 mr-2"></i> {{ $milestone->qc_notes }}</span>
                                        <span class="font-bold text-[10px] whitespace-nowrap">Pos {{ $milestone->stage_order }}</span>
                                    </div>
                                @empty
                                    <p class="text-xs text-gray-400">Belum ada catatan QC.</p>
                                @endforelse
                            </div>
                        </div>
                    </div>

                    <!-- Pelunasan Escrow -->
                    <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm space-y-4">
                        <div class="flex items-center space-x-3">
                            <div class="w-10 h-10 rounded-xl {{ $finalPaid ? 'bg-emerald-50 text-emerald-600' : 'bg-amber-50 text-amber-600' }} flex items-center justify-center text-lg">
                                <i class="fa-solid {{ $finalPaid ? 'fa-lock' : 'fa-hourglass-half' }}"></i>
                            </div>
                            <div>
                                <h4 class="font-bold text-sm text-gray-900">Pelunasan Escrow</h4>
                                <p class="text-[11px] text-gray-500">
                                    @if($order->remaining_amount > 0)
                                        Sisa tagihan: Rp {{ number_format((float) $order->remaining_amount, 0, ',', '.') }}
                                    @else
                                        Seluruh pembayaran sudah masuk escrow.
                                    @endif
                                </p>
                            </div>
                        </div>

                        @if($order->payment_status === 'DP_HELD')
                            @if($allMilestonesDone)
                                <form method="POST" action="{{ b2b_route('designer.b2b.milestone.pay_final', $order) }}" enctype="multipart/form-data" class="space-y-2">
                                    @csrf
                                    <label class="block text-[10px] font-bold text-gray-600">Bukti Transfer Pelunasan (opsional)</label>
                                    <input type="file" name="payment_proof" accept=".jpg,.jpeg,.png,.pdf" class="w-full text-[10px] border border-gray-200 rounded-lg p-2">
                                    <button type="submit" class="w-full bg-emerald-600 hover:bg-emerald-700 text-white py-3 px-4 rounded-xl font-bold text-xs flex items-center justify-center transition-all shadow-md">
                                        Lunasi Sisa Pembayaran ke Escrow <i class="fa-solid fa-arrow-right ml-2"></i>
                                    </button>
                                </form>
                            @else
                                <p class="text-xs text-amber-700 bg-amber-50 border border-amber-200 rounded-xl p-3">
                                    Pelunasan dapat dilakukan setelah seluruh 5 pos produksi mencapai 100% dan lulus QC.
                                </p>
                            @endif
                        @elseif($order->payment_status === 'FULL_HELD')
                            <div class="bg-emerald-50 border border-emerald-200 rounded-xl p-3 space-y-1.5">
                                <div class="flex items-center justify-between">
                                    <span class="text-[10px] font-bold uppercase tracking-wider text-emerald-700">Dana Terkunci di Escrow</span>
                                    <span class="bg-emerald-600 text-white text-[9px] font-bold px-2 py-0.5 rounded-full">LUNAS</span>
                                </div>
                                <p class="text-lg font-extrabold text-emerald-700">Rp {{ number_format((float) $order->escrow_balance, 0, ',', '.') }}</p>
                                <p class="text-[11px] text-emerald-700/80">
                                    Disetor {{ $order->settled_at?->format('d M Y, H:i') ?? '-' }} WIB &bull; 100% dana aman, menunggu pengiriman &amp; BAST.
                                </p>
                            </div>
                        @elseif($order->payment_status === 'RELEASED_TO_SELLER')
                            <div class="bg-emerald-50 border border-emerald-200 rounded-xl p-3 space-y-1.5">
                                <div class="flex items-center justify-between">
                                    <span class="text-[10px] font-bold uppercase tracking-wider text-emerald-700">Dana Dirilis ke Pabrik</span>
                                    <span class="bg-emerald-600 text-white text-[9px] font-bold px-2 py-0.5 rounded-full">SELESAI</span>
                                </div>
                                <p class="text-[11px] text-emerald-700/80">Escrow telah dicairkan setelah BAST ditandatangani dan disetujui.</p>
                            </div>
                        @else
                            <p class="text-xs text-gray-500">DP belum disetor.</p>
                        @endif
                    </div>

                    <!-- Status Pengiriman -->
                    <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm space-y-4">
                        <div class="flex items-center justify-between gap-3">
                            <div class="flex items-center space-x-3">
                                <div class="w-10 h-10 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center text-lg">
                                    <i class="fa-solid fa-truck-fast"></i>
                                </div>
                                <div>
                                    <h4 class="font-bold text-sm text-gray-900">Status Pengiriman</h4>
                                    <p class="text-[11px] text-gray-500">
                                        {{ $delivery?->surat_jalan_number ?? 'Surat Jalan belum diterbitkan' }}
                                    </p>
                                </div>
                            </div>
                            @if($delivery)
                                <span class="{{ $deliveryStatus['class'] }} text-[9px] font-bold px-2.5 py-1 rounded-full whitespace-nowrap">{{ $deliveryStatus['label'] }}</span>
                            @endif
                        </div>

                        @if($delivery)
                            <div class="grid grid-cols-2 gap-3 text-xs">
                                <div class="p-3 bg-gray-50 rounded-xl">
                                    <p class="text-[10px] text-gray-400 uppercase font-bold">Kurir / Ekspedisi</p>
                                    <p class="font-bold text-gray-800 mt-0.5">{{ $delivery->courier_service ?: '-' }}</p>
                                </div>
                                <div class="p-3 bg-gray-50 rounded-xl">
                                    <p class="text-[10px] text-gray-400 uppercase font-bold">Tanggal Kirim</p>
                                    <p class="font-bold text-gray-800 mt-0.5">{{ $delivery->delivery_date?->format('d M Y') ?? '-' }}</p>
                                </div>
                                <div class="p-3 bg-gray-50 rounded-xl">
                                    <p class="text-[10px] text-gray-400 uppercase font-bold">Driver</p>
                                    <p class="font-bold text-gray-800 mt-0.5">{{ $delivery->driver_name ?: '-' }}</p>
                                </div>
                                <div class="p-3 bg-gray-50 rounded-xl">
                                    <p class="text-[10px] text-gray-400 uppercase font-bold">Plat Kendaraan</p>
                                    <p class="font-bold text-gray-800 mt-0.5">{{ $delivery->vehicle_plate ?: '-' }}</p>
                                </div>
                                <div class="col-span-2 p-3 bg-gray-50 rounded-xl">
                                    <p class="text-[10px] text-gray-400 uppercase font-bold">Alamat Tujuan</p>
                                    <p class="font-medium text-gray-700 mt-0.5">{{ $delivery->destination_address }}</p>
                                </div>
                            </div>
                        @elseif($released)
                            <p class="text-xs text-emerald-700 bg-emerald-50 border border-emerald-200 rounded-xl p-3">
                                Pengiriman dan BAST sudah selesai. Dana telah dirilis ke pabrik.
                            </p>
                        @elseif($awaitingDelivery)
                            <p class="text-xs text-blue-700 bg-blue-50 border border-blue-200 rounded-xl p-3">
                                <i class="fa-solid fa-clock mr-1"></i>
                                Pabrik sedang menyiapkan barang dan menerbitkan Surat Jalan. Detail kurir akan muncul di sini setelah dokumen dibuat.
                            </p>
                        @else
                            <p class="text-xs text-gray-500 bg-gray-50 border border-gray-200 rounded-xl p-3">
                                Surat Jalan dapat diterbitkan pabrik setelah pelunasan escrow diterima.
                            </p>
                        @endif
                    </div>

                    <!-- Call to Action BAST -->
                    <div class="bg-gradient-to-br from-[#2C1810] to-primary p-6 rounded-2xl text-white space-y-4 shadow-lg">
                        <div class="flex items-center space-x-3">
                            <div class="w-10 h-10 rounded-xl bg-white/20 flex items-center justify-center text-lg">
                                <i class="fa-solid fa-file-signature"></i>
                            </div>
                            <div>
                                @if($isShipped)
                                    <h4 class="font-bold text-sm">Barang Sudah Tiba di Lokasi?</h4>
                                    <p class="text-[11px] text-white/80">Verifikasi Dokumen BAST &amp; Rilis Pelunasan</p>
                                @elseif($awaitingDelivery)
                                    <h4 class="font-bold text-sm">Menunggu Pengiriman Pabrik</h4>
                                    <p class="text-[11px] text-white/80">BAST aktif setelah barang tiba</p>
                                @else
                                    <h4 class="font-bold text-sm">BAST Belum Tersedia</h4>
                                    <p class="text-[11px] text-white/80">Selesaikan produksi &amp; pelunasan dahulu</p>
                                @endif
                            </div>
                        </div>

                        @if($isShipped)
                            <p class="text-xs text-white/90 leading-relaxed">
                                Ketika truk kargo tiba di {{ $rfq->delivery_address ? \Illuminate\Support\Str::limit($rfq->delivery_address, 60) : 'lokasi proyek' }}, lakukan inspeksi fisik lalu tanda tangani Berita Acara Serah Terima (BAST).
                            </p>
                            <a href="{{ b2b_route('designer.b2b.bast', $order) }}" class="w-full bg-white hover:bg-gray-100 text-primary py-3 px-4 rounded-xl font-bold text-xs flex items-center justify-center transition-all shadow-md">
                                {{ $released ? 'Lihat Dokumen BAST — Dana Sudah Dirilis' : 'Inspeksi & Tanda Tangan BAST' }}
                                <i class="fa-solid fa-arrow-right ml-2"></i>
                            </a>
                        @elseif($awaitingDelivery)
                            <p class="text-xs text-white/90 leading-relaxed">
                                Pelunasan sudah masuk escrow. Pabrik kini menyiapkan barang &amp; Surat Jalan. Tombol BAST akan aktif otomatis setelah status pengiriman tiba di lokasi.
                            </p>
                            <span class="w-full bg-white/20 text-white/70 py-3 px-4 rounded-xl font-bold text-xs flex items-center justify-center cursor-not-allowed">
                                <i class="fa-solid fa-clock mr-2"></i> Menunggu Surat Jalan dari Pabrik
                            </span>
                        @else
                            <p class="text-xs text-white/90 leading-relaxed">
                                BAST hanya dapat diakses setelah seluruh produksi selesai, pelunasan dibayar, dan barang dikirim ke lokasi proyek.
                            </p>
                            <span class="w-full bg-white/20 text-white/70 py-3 px-4 rounded-xl font-bold text-xs flex items-center justify-center cursor-not-allowed">
                                <i class="fa-solid fa-lock mr-2"></i> BAST Terkunci
                            </span>
                        @endif
                    </div>
                </div>
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
