<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Decor Seller - Manajemen Produksi & QC B2B</title>
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
    <main id="main-content" class="ml-64 flex flex-col min-h-screen sidebar-transition">
        @php
            $rfq = $order->rfq;
            $seller = $rfq->seller;
            $designerUser = $rfq->designer->user ?? null;
            $quotation = $order->quotation;
            $progress = $order->progress_percent;
            $totalUnits = collect($rfq->items ?? [])->sum('qty');
            $doneStages = $order->milestones->where('progress_percent', '>=', 100)->count();
            $totalStages = $order->milestones->count();
            $daysLeft = $rfq->deadline ? now()->startOfDay()->diffInDays($rfq->deadline, false) : null;
        @endphp

        <!-- Topbar Header -->
        <header class="h-16 bg-primary flex items-center justify-between px-8 sticky top-0 z-40 shadow-sm text-white">
            <div class="flex items-center space-x-4">
                <button id="toggle-sidebar" class="text-white hover:text-gray-200 focus:outline-none">
                    <i class="fa-solid fa-bars text-lg"></i>
                </button>
                <div class="flex items-center space-x-2 text-xs">
                    <a href="{{ b2b_route('seller.b2b.inbox') }}" class="text-white/80 hover:text-white">Pesanan B2B</a>
                    <span class="text-white/50">/</span>
                    <a href="{{ b2b_route('seller.b2b.negosiasi', $order->rfq) }}" class="text-white/80 hover:text-white">{{ $rfq->rfq_number }}</a>
                    <span class="text-white/50">/</span>
                    <span class="font-bold text-white">Manajemen Produksi & QC</span>
                </div>
            </div>

            <div class="flex items-center space-x-4">
                <div class="text-right hidden sm:block">
                    <p class="text-xs font-bold leading-none">{{ $seller->store_name }}</p>
                    <p class="text-[10px] text-white/80 mt-1">{{ $seller->svlk_verified ? 'Produsen Terverifikasi SVLK' : 'Produsen Mebel' }}</p>
                </div>
                <img src="{{ $seller->store_image_url }}" class="w-8 h-8 rounded-full object-cover border border-white/20">
            </div>
        </header>

        <!-- Body Container -->
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

            <!-- Header Summary Proyek & Progress Bar -->
            <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm flex flex-col md:flex-row md:items-center justify-between gap-4">
                <div>
                    <div class="flex items-center space-x-3 mb-1">
                        <span class="bg-amber-100 text-amber-800 text-[10px] font-extrabold px-3 py-1 rounded-full uppercase tracking-wider">
                            <i class="fa-solid fa-spinner fa-spin mr-1"></i> {{ str_replace('_', ' ', $order->status) }} ({{ $doneStages }}/{{ $totalStages }} Pos)
                        </span>
                        <span class="text-xs text-gray-400">PO: {{ $order->order_number }}</span>
                    </div>
                    <h2 class="text-xl font-extrabold text-gray-900">{{ $rfq->project_name }} - {{ $totalUnits ?: count($rfq->items ?? []) }} Unit Furnitur Custom</h2>
                    <p class="text-xs text-gray-500 mt-1">
                        Klien Desainer: <b class="text-gray-700">{{ $designerUser->full_name ?? 'Desainer' }}</b>
                        &bull; Workshop: {{ $seller->city ?: 'Indonesia' }}
                    </p>
                </div>

                <div class="flex items-center space-x-3">
                    <a href="{{ b2b_route('seller.b2b.negosiasi', $order->rfq) }}" class="px-4 py-2.5 rounded-xl border border-gray-200 text-xs font-bold text-gray-600 hover:bg-gray-50 transition-all flex items-center">
                        <i class="fa-solid fa-comments mr-2"></i> Diskusi Teknis
                    </a>
                    <a href="{{ b2b_route('seller.b2b.surat_jalan', $order) }}" class="bg-primary hover:bg-[#8C4E1E] text-white px-5 py-2.5 rounded-xl font-bold text-xs transition-all shadow-md flex items-center">
                        Surat Jalan & Pengiriman <i class="fa-solid fa-truck-fast ml-2"></i>
                    </a>
                </div>
            </div>

            <!-- Overall Progress Widget -->
            <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm space-y-4">
                <div class="flex items-center justify-between text-xs font-bold">
                    <div class="flex items-center space-x-2">
                        <i class="fa-solid fa-chart-pie text-primary text-base"></i>
                        <span class="text-gray-900">Total Progres Manufaktur Pabrik</span>
                    </div>
                    <span class="text-primary text-sm font-extrabold">{{ $progress }}% Rampung</span>
                </div>
                <div class="w-full bg-gray-100 rounded-full h-3 overflow-hidden">
                    <div class="bg-gradient-to-r from-amber-500 to-primary h-3 rounded-full transition-all duration-500" style="width: {{ $progress }}%"></div>
                </div>
                <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 pt-2 text-center text-xs">
                    <div class="p-3 bg-gray-50 rounded-xl">
                        <p class="text-[10px] text-gray-400 uppercase font-bold">Batas Waktu</p>
                        <p class="font-extrabold text-gray-800 mt-0.5">{{ $rfq->deadline?->format('d M Y') ?? '-' }}</p>
                    </div>
                    <div class="p-3 bg-gray-50 rounded-xl">
                        <p class="text-[10px] text-gray-400 uppercase font-bold">Sisa Waktu</p>
                        <p class="font-extrabold {{ $daysLeft !== null && $daysLeft >= 0 ? 'text-emerald-600' : 'text-red-600' }} mt-0.5">
                            {{ $daysLeft === null ? '-' : ($daysLeft >= 0 ? $daysLeft . ' Hari Lagi' : abs($daysLeft) . ' Hari Lewat') }}
                        </p>
                    </div>
                    <div class="p-3 bg-gray-50 rounded-xl">
                        <p class="text-[10px] text-gray-400 uppercase font-bold">Pos Lolos QC</p>
                        <p class="font-extrabold text-gray-800 mt-0.5">{{ $doneStages }} / {{ $totalStages }} Pos</p>
                    </div>
                    <div class="p-3 bg-gray-50 rounded-xl">
                        <p class="text-[10px] text-gray-400 uppercase font-bold">Escrow Terkunci</p>
                        <p class="font-extrabold text-primary mt-0.5">Rp {{ number_format((float) $order->escrow_balance, 0, ',', '.') }}</p>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
                <!-- Kolom Kiri: Timeline Milestone Produksi -->
                <div class="lg:col-span-7 space-y-4">
                    <h3 class="text-sm font-bold text-gray-900 flex items-center">
                        <i class="fa-solid fa-list-check text-primary mr-2"></i> Timeline & Milestone Pekerjaan
                    </h3>

                    @forelse($order->milestones as $milestone)
                        @php
                            $done = $milestone->progress_percent >= 100;
                            $active = ! $done && $milestone->progress_percent > 0;
                            $border = $done ? 'border-emerald-500' : ($active ? 'border-amber-500' : 'border-gray-300');
                            $badge = $done ? 'bg-emerald-100 text-emerald-700' : ($active ? 'bg-amber-100 text-amber-800' : 'bg-gray-100 text-gray-500');
                        @endphp
                        <div class="bg-white p-5 rounded-2xl border-l-4 {{ $border }} border border-gray-100 shadow-sm space-y-3 {{ $active ? 'ring-1 ring-amber-500/30' : '' }}">
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
                                            Target: {{ $milestone->target_date?->format('d M Y') ?? '-' }}
                                            @if($milestone->is_approved_by_designer) &bull; Diverifikasi Desainer @endif
                                        </p>
                                    </div>
                                </div>
                                <span class="text-[10px] {{ $badge }} font-bold px-2.5 py-1 rounded-full">
                                    {{ $done ? '100% Selesai' : 'Progres: ' . $milestone->progress_percent . '%' }}
                                </span>
                            </div>

                            <div class="pl-10">
                                <div class="w-full bg-gray-100 rounded-full h-1.5 overflow-hidden">
                                    <div class="{{ $done ? 'bg-emerald-500' : 'bg-amber-500' }} h-1.5 rounded-full" style="width: {{ $milestone->progress_percent }}%"></div>
                                </div>
                            </div>

                            @if($milestone->qc_notes)
                                <p class="text-xs text-gray-600 pl-10">{{ $milestone->qc_notes }}</p>
                            @endif

                            <!-- Form Update Progress -->
                            <form method="POST" action="{{ b2b_route('seller.b2b.produksi.milestone.update', [$order, $milestone]) }}" class="pl-10 pt-1 grid grid-cols-1 sm:grid-cols-12 gap-2 items-end">
                                @csrf
                                <div class="sm:col-span-3">
                                    <label class="block text-[9px] font-bold text-gray-500 uppercase">Progres %</label>
                                    <input type="number" name="progress_percent" min="0" max="100" value="{{ $milestone->progress_percent }}" class="w-full text-xs border border-gray-200 rounded-lg px-2 py-1.5 focus:outline-none focus:border-primary" required>
                                </div>
                                <div class="sm:col-span-4">
                                    <label class="block text-[9px] font-bold text-gray-500 uppercase">Target Tanggal</label>
                                    <input type="date" name="target_date" value="{{ $milestone->target_date?->format('Y-m-d') }}" class="w-full text-xs border border-gray-200 rounded-lg px-2 py-1.5 focus:outline-none focus:border-primary">
                                </div>
                                <div class="sm:col-span-3">
                                    <label class="block text-[9px] font-bold text-gray-500 uppercase">Catatan QC</label>
                                    <input type="text" name="qc_notes" value="{{ $milestone->qc_notes }}" placeholder="Catatan singkat" class="w-full text-xs border border-gray-200 rounded-lg px-2 py-1.5 focus:outline-none focus:border-primary">
                                </div>
                                <div class="sm:col-span-2">
                                    <button type="submit" class="w-full bg-primary hover:bg-[#8C4E1E] text-white px-2 py-1.5 rounded-lg text-[10px] font-bold transition-all">
                                        Simpan
                                    </button>
                                </div>
                            </form>

                            <!-- Form Upload Foto QC -->
                            <form method="POST" action="{{ b2b_route('seller.b2b.produksi.milestone.photo', [$order, $milestone]) }}" enctype="multipart/form-data" class="pl-10 flex items-center gap-2">
                                @csrf
                                <input type="file" name="photo" accept=".jpg,.jpeg,.png" required class="flex-1 text-[10px] text-gray-500 border border-gray-200 rounded-lg p-1.5">
                                <button type="submit" class="bg-gray-900 hover:bg-black text-white px-3 py-1.5 rounded-lg text-[10px] font-bold whitespace-nowrap transition-all">
                                    <i class="fa-solid fa-camera mr-1"></i> Upload Bukti QC
                                </button>
                            </form>
                        </div>
                    @empty
                        <div class="bg-white p-8 rounded-2xl border border-gray-100 text-center text-xs text-gray-500">
                            Milestone produksi belum dibuat. Hubungi desainer / admin untuk inisialisasi order.
                        </div>
                    @endforelse
                </div>

                <!-- Kolom Kanan: Galeri Bukti QC & Log Catatan Workshop -->
                <div class="lg:col-span-5 space-y-6">
                    <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm space-y-4">
                        <div class="flex items-center justify-between">
                            <h3 class="text-sm font-bold text-gray-900 flex items-center">
                                <i class="fa-solid fa-images text-primary mr-2"></i> Foto Dokumentasi Workshop
                            </h3>
                            <span class="text-[10px] bg-primary/10 text-primary font-bold px-2 py-0.5 rounded-full">
                                {{ $order->milestones->whereNotNull('photo_url')->count() }} Foto Terupload
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
                                    Belum ada foto QC yang diunggah.
                                </div>
                            @endforelse
                        </div>

                        <!-- QC Log -->
                        <div class="pt-4 border-t border-gray-100 space-y-2">
                            <p class="text-[11px] font-bold text-gray-700 uppercase tracking-wider">Quality Control Checklist Pabrik:</p>
                            <div class="space-y-1.5 text-xs text-gray-600">
                                @forelse($order->milestones->whereNotNull('qc_notes') as $milestone)
                                    <div class="flex items-center justify-between gap-3 p-2 {{ $milestone->progress_percent >= 100 ? 'bg-emerald-50 text-emerald-800' : 'bg-amber-50 text-amber-800' }} rounded-lg">
                                        <span>
                                            <i class="fa-solid {{ $milestone->progress_percent >= 100 ? 'fa-circle-check text-emerald-600' : 'fa-spinner fa-spin text-amber-600' }} mr-2"></i>
                                            {{ $milestone->qc_notes }}
                                        </span>
                                        <span class="font-bold text-[10px] whitespace-nowrap">Pos {{ $milestone->stage_order }}</span>
                                    </div>
                                @empty
                                    <p class="text-xs text-gray-400">Belum ada catatan QC dari pabrik.</p>
                                @endforelse
                            </div>
                        </div>
                    </div>

                    <!-- Call to Action Card -->
                    <div class="bg-gradient-to-br from-amber-900 to-primary p-6 rounded-2xl text-white space-y-4 shadow-lg">
                        <div class="flex items-center space-x-3">
                            <div class="w-10 h-10 rounded-xl bg-white/20 flex items-center justify-center text-lg">
                                <i class="fa-solid fa-truck-ramp-box"></i>
                            </div>
                            <div>
                                <h4 class="font-bold text-sm">Produksi Siap Dikirim?</h4>
                                <p class="text-[11px] text-white/80">Cetak Surat Jalan & Ajukan Payout</p>
                            </div>
                        </div>
                        <p class="text-xs text-white/90 leading-relaxed">
                            Begitu barang dimuat ke truk kargo menuju {{ $rfq->delivery_address ? \Illuminate\Support\Str::limit($rfq->delivery_address, 60) : 'lokasi proyek' }},
                            Anda dapat menerbitkan Surat Jalan resmi dan mengajukan pencairan dana escrow.
                        </p>
                        <a href="{{ b2b_route('seller.b2b.surat_jalan', $order) }}" class="w-full bg-white hover:bg-gray-100 text-primary py-3 px-4 rounded-xl font-bold text-xs flex items-center justify-center transition-all shadow-md">
                            Lanjut ke Pengiriman & Surat Jalan <i class="fa-solid fa-arrow-right ml-2"></i>
                        </a>
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
