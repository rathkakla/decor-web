<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Decor Designer - Negosiasi & Persetujuan Sampel B2B</title>
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
                    <a href="{{ b2b_route('designer.b2b.katalog') }}" class="text-white/80 hover:text-white hidden sm:inline">Kustom B2B</a>
                    <span class="text-white/50 hidden sm:inline">/</span>
                    <a href="{{ b2b_route('designer.b2b.quotation', $rfq->latestQuotation) }}" class="text-white/80 hover:text-white hidden sm:inline">Quotation</a>
                    <span class="text-white/50 hidden sm:inline">/</span>
                    <span class="font-bold text-white">Diskusi Teknis & Validasi Sampel</span>
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
        @php
            $seller = $rfq->seller;
            $quotation = $rfq->latestQuotation;
            $order = $rfq->order;
            $sampleLabels = [
                'NOT_REQUESTED' => ['label' => 'Sampel Belum Diminta', 'class' => 'bg-gray-100 text-gray-700'],
                'PREPARING' => ['label' => 'Sampel Sedang Disiapkan', 'class' => 'bg-amber-100 text-amber-800'],
                'SENT_BY_COURIER' => ['label' => 'Sampel Dalam Pengiriman', 'class' => 'bg-blue-100 text-blue-800'],
                'APPROVED_BY_DESIGNER' => ['label' => 'Sampel Disetujui (ACC)', 'class' => 'bg-emerald-100 text-emerald-800'],
            ];
            $sample = $sampleLabels[$rfq->sample_status] ?? ['label' => $rfq->sample_status, 'class' => 'bg-gray-100 text-gray-700'];
        @endphp

        <div class="p-4 sm:p-6 lg:p-8 space-y-6 max-w-7xl">
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
            <div class="bg-white p-5 sm:p-6 rounded-2xl border border-gray-100 shadow-sm flex flex-col md:flex-row md:items-center justify-between gap-4">
                <div class="space-y-1">
                    <div class="flex flex-wrap items-center gap-2.5">
                        <span class="{{ $sample['class'] }} text-[10px] font-bold px-2.5 py-0.5 rounded-full uppercase tracking-wider">
                            <i class="fa-solid fa-vial-circle-check mr-1"></i> {{ $sample['label'] }}
                        </span>
                        @if($quotation)
                            <span class="text-xs font-semibold text-gray-500">Nilai Kontrak: <b class="text-gray-800">Rp {{ number_format((float) $quotation->total_price, 0, ',', '.') }}</b></span>
                        @else
                            <span class="text-xs font-medium text-gray-400">Menunggu Penawaran Pabrik</span>
                        @endif
                    </div>
                    <h2 class="text-lg sm:text-xl font-bold text-gray-900 tracking-tight">{{ $rfq->project_name }}</h2>
                    <p class="text-xs text-gray-500">
                        Mitra Produsen: <span class="font-semibold text-gray-700">{{ $seller->store_name }}</span>
                        @if($rfq->wood_type && $rfq->wood_type !== 'Sesuai Berkas / Spesifikasi')
                            &bull; {{ $rfq->wood_type }}
                        @endif
                    </p>
                </div>

                <div class="flex flex-wrap sm:flex-nowrap items-center gap-2 sm:gap-3">
                    @if($quotation)
                        <a href="{{ b2b_route('designer.b2b.quotation', $quotation) }}" class="px-3 sm:px-4 py-2 sm:py-2.5 rounded-xl border border-gray-200 text-xs font-bold text-gray-600 hover:bg-gray-50 transition-all flex items-center">
                            <i class="fa-solid fa-file-invoice mr-1.5 sm:mr-2"></i> Dokumen Quotation
                        </a>
                    @endif
                    @if($order)
                        <a href="{{ b2b_route('designer.b2b.milestone', $order) }}" class="bg-primary hover:bg-[#8C4E1E] text-white px-4 sm:px-5 py-2 sm:py-2.5 rounded-xl font-bold text-xs transition-all shadow-md flex items-center">
                            Milestone Produksi <i class="fa-solid fa-arrow-right ml-1.5 sm:ml-2"></i>
                        </a>
                    @endif
                </div>
            </div>

            <div class="grid grid-cols-1 xl:grid-cols-12 gap-6">
                <!-- Sisi Kiri: Verifikasi Sampel Fisik & Escrow Box -->
                <div class="xl:col-span-4 space-y-6">
                    <div class="bg-white p-5 sm:p-6 rounded-2xl border border-gray-100 shadow-sm space-y-4">
                        <h3 class="text-xs font-bold text-gray-900 uppercase tracking-wider border-b border-gray-100 pb-2.5 flex items-center">
                            <i class="fa-solid fa-swatchbook text-primary mr-2"></i> Validasi Sampel Fisik
                        </h3>

                        <!-- Foto Sampel Material -->
                        <div class="space-y-3">
                            <div class="h-36 sm:h-40 w-full rounded-xl overflow-hidden border border-gray-200 relative">
                                <img src="https://images.unsplash.com/photo-1546484396-fb3fc6f95f98?auto=format&fit=crop&w=600&q=80" class="w-full h-full object-cover" alt="Sampel Material">
                                <div class="absolute bottom-2 left-2 right-2 bg-white/95 backdrop-blur-sm px-3 py-2 rounded-lg text-xs border border-gray-200/80 shadow-sm">
                                    <p class="font-bold text-gray-900 truncate">
                                        {{ $rfq->wood_type && $rfq->wood_type !== 'Sesuai Berkas / Spesifikasi' ? 'Sampel ' . $rfq->wood_type : 'Sampel Material Proyek' }}
                                    </p>
                                    @if($rfq->is_sample_approved)
                                        <p class="text-[10px] text-emerald-600 font-semibold mt-0.5">✓ Telah Anda Setujui (ACC)</p>
                                    @elseif($rfq->sample_status === 'SENT_BY_COURIER')
                                        <p class="text-[10px] text-amber-600 font-semibold mt-0.5">Sampel dikirim — menunggu ACC Anda</p>
                                    @elseif($rfq->sample_status === 'PREPARING')
                                        <p class="text-[10px] text-blue-600 font-semibold mt-0.5">Sedang disiapkan oleh pabrik</p>
                                    @else
                                        <p class="text-[10px] text-gray-500 font-medium mt-0.5">Belum diminta / belum dikirim</p>
                                    @endif
                                </div>
                            </div>
                            <div class="bg-gray-50 rounded-xl p-3 space-y-1.5 text-[11px] border border-gray-100">
                                <div class="flex justify-between gap-2">
                                    <span class="text-gray-400">Status</span>
                                    <span class="font-semibold text-gray-800">{{ $sample['label'] }}</span>
                                </div>
                                <div class="flex justify-between gap-2">
                                    <span class="text-gray-400">Ekspedisi</span>
                                    <span class="font-semibold text-gray-800">{{ $rfq->courier_name ?: '-' }}</span>
                                </div>
                                <div class="flex justify-between gap-2">
                                    <span class="text-gray-400">Nomor Resi</span>
                                    <span class="font-mono text-primary font-bold">{{ $rfq->tracking_number ?: '-' }}</span>
                                </div>
                            </div>
                        </div>

                        @if($rfq->sample_status === 'SENT_BY_COURIER')
                            <form method="POST" action="{{ b2b_route('designer.b2b.negosiasi.approve_sample', $rfq) }}">
                                @csrf
                                <button type="submit" class="w-full bg-emerald-600 hover:bg-emerald-700 text-white py-3 px-4 rounded-xl font-bold text-xs flex items-center justify-center transition-all shadow-md cursor-pointer">
                                    <i class="fa-solid fa-check mr-2"></i> Setujui Sampel Fisik (ACC)
                                </button>
                            </form>
                        @endif

                        <!-- Status Escrow Box -->
                        <div class="p-4 {{ $order && $order->payment_status !== 'UNPAID' ? 'bg-emerald-50 border-emerald-200' : 'bg-amber-50 border-amber-200' }} rounded-xl border space-y-1.5">
                            <div class="flex items-center space-x-2 {{ $order && $order->payment_status !== 'UNPAID' ? 'text-emerald-800' : 'text-amber-800' }} font-bold text-xs">
                                <i class="fa-solid {{ $order && $order->payment_status !== 'UNPAID' ? 'fa-circle-check' : 'fa-circle-info' }} text-sm"></i>
                                <span>{{ $order && $order->payment_status !== 'UNPAID' ? 'DP Termin 1 Sudah Disetor' : 'DP Belum Disetor' }}</span>
                            </div>
                            <p class="text-[11px] leading-relaxed {{ $order && $order->payment_status !== 'UNPAID' ? 'text-emerald-900' : 'text-amber-900' }}">
                                @if($order && $order->payment_status !== 'UNPAID')
                                    Dana DP sebesar <b>Rp {{ number_format((float) $order->escrow_balance, 0, ',', '.') }}</b> telah didepositkan ke Rekening Escrow Platform Decor.
                                @else
                                    Setujui penawaran dan setor DP agar produksi dapat dimulai oleh pabrik.
                                @endif
                            </p>
                        </div>

                        <!-- CTA Lanjut Pantau Milestone -->
                        @if($order)
                            <a href="{{ b2b_route('designer.b2b.milestone', $order) }}" class="w-full bg-primary hover:bg-[#8C4E1E] text-white py-3.5 px-4 rounded-xl font-bold text-xs flex items-center justify-center transition-all shadow-md">
                                Buka Pelacak Milestone Workshop <i class="fa-solid fa-arrow-right ml-2"></i>
                            </a>
                        @endif
                    </div>
                </div>

                <!-- Sisi Kanan: Live Thread Chat Diskusi Spesifikasi Teknis -->
                <div class="xl:col-span-8 flex flex-col bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden h-[540px] sm:h-[620px] lg:h-[680px]">
                    <!-- Chat Header -->
                    <div class="p-3 sm:p-4 px-4 sm:px-6 border-b border-gray-100 flex items-center justify-between bg-gray-50/50">
                        <div class="flex items-center space-x-3">
                            <img src="{{ $seller->store_image_url }}" class="w-8 h-8 sm:w-10 sm:h-10 rounded-full object-cover">
                            <div>
                                <h4 class="font-bold text-xs text-gray-900">{{ $seller->store_name }}</h4>
                                <p class="text-[10px] text-gray-500 font-bold">{{ $seller->city ?: 'Indonesia' }}</p>
                            </div>
                        </div>
                        <span class="text-[10px] bg-primary/10 text-primary font-bold px-3 py-1 rounded-full whitespace-nowrap">{{ $rfq->rfq_number }}</span>
                    </div>

                    <!-- Chat Messages List -->
                    <div class="flex-1 p-3 sm:p-6 overflow-y-auto space-y-4 text-xs">
                        @forelse($rfq->negotiations as $chat)
                            @if($chat->sender_id === auth()->id())
                                <div class="flex items-start space-x-2 sm:space-x-3 max-w-[88%] sm:max-w-xl ml-auto flex-row-reverse space-x-reverse">
                                    <img src="{{ auth()->user()->avatar_url ?? 'https://ui-avatars.com/api/?name=Me' }}" class="w-7 h-7 sm:w-8 sm:h-8 rounded-full border border-primary/20">
                                    <div class="bg-primary text-white p-3 sm:p-4 rounded-2xl rounded-tr-none space-y-1">
                                        <p class="font-bold text-[11px] text-white">{{ $chat->sender->full_name ?? 'Anda' }} (Anda)</p>
                                        <p class="text-white/95 whitespace-pre-line text-xs">{{ $chat->message }}</p>
                                        @if($chat->attachment_url)
                                            <a href="{{ asset('storage/' . $chat->attachment_url) }}" target="_blank" class="text-[10px] underline text-white/90 block">Lihat lampiran</a>
                                        @endif
                                        <span class="text-[9px] text-white/70 block text-right">{{ $chat->created_at->format('d M Y H:i') }}</span>
                                    </div>
                                </div>
                            @else
                                <div class="flex items-start space-x-2 sm:space-x-3 max-w-[88%] sm:max-w-xl">
                                    <img src="{{ $seller->store_image_url }}" class="w-7 h-7 sm:w-8 sm:h-8 rounded-full object-cover">
                                    <div class="bg-gray-100 p-3 sm:p-4 rounded-2xl rounded-tl-none space-y-1">
                                        <p class="font-bold text-[11px] text-gray-900">{{ $chat->sender->full_name ?? $seller->store_name }} (Seller)</p>
                                        <p class="text-gray-700 whitespace-pre-line text-xs">{{ $chat->message }}</p>
                                        @if($chat->attachment_url)
                                            <a href="{{ asset('storage/' . $chat->attachment_url) }}" target="_blank" class="text-[10px] underline text-primary block">Lihat lampiran</a>
                                        @endif
                                        <span class="text-[9px] text-gray-400 block text-right">{{ $chat->created_at->format('d M Y H:i') }}</span>
                                    </div>
                                </div>
                            @endif
                        @empty
                            <div class="text-center text-gray-400 py-10">
                                <i class="fa-solid fa-comments text-3xl mb-2 block"></i>
                                Belum ada diskusi. Mulai percakapan teknis dengan pabrik.
                            </div>
                        @endforelse
                    </div>

                    <!-- Chat Input -->
                    <form method="POST" action="{{ b2b_route('designer.b2b.negosiasi.message', $rfq) }}" enctype="multipart/form-data" class="p-3 sm:p-4 border-t border-gray-100 bg-gray-50 space-y-2">
                        @csrf
                        <div class="flex items-center space-x-2 sm:space-x-3">
                            <input type="text" name="message" required placeholder="Ketik pesan atau instruksi teknis revisi..." class="flex-1 text-xs border border-gray-200 rounded-xl px-3 sm:px-4 py-2.5 sm:py-3 focus:outline-none focus:border-primary">
                            <button type="submit" class="bg-primary hover:bg-[#8C4E1E] text-white px-4 sm:px-5 py-2.5 sm:py-3 rounded-xl font-bold text-xs transition-all flex items-center whitespace-nowrap">
                                <span class="hidden sm:inline mr-1.5">Kirim</span> <i class="fa-solid fa-paper-plane"></i>
                            </button>
                        </div>
                        <input type="file" name="attachment" accept=".jpg,.jpeg,.png,.pdf,.dwg,.dxf" class="text-[10px] text-gray-500">
                    </form>
                </div>
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
