<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Decor Seller - Negosiasi & Diskusi Teknis B2B</title>
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

    <main id="main-content" class="flex-1 flex flex-col ml-64 sidebar-transition min-h-screen">
        <header class="h-16 bg-primary flex items-center justify-between px-8 sticky top-0 z-40 shadow-sm text-white">
            <div class="flex items-center">
                <button id="toggle-sidebar" class="text-white hover:opacity-80 mr-4 flex items-center justify-center transition-transform active:scale-95">
                    <i class="fa-solid fa-bars-staggered text-xl"></i>
                </button>
                <div class="flex flex-col">
                    <h2 class="font-bold text-xs uppercase tracking-widest leading-none">Ruang Negosiasi Teknis</h2>
                    <span class="text-[10px] text-white/80 mt-1">Komunikasi Proyek {{ $rfq->project_name }}</span>
                </div>
            </div>
            <div class="flex items-center space-x-6">
                <span class="bg-emerald-500/20 text-white text-xs px-3 py-1 rounded-full font-bold border border-white/20">
                    <i class="fa-solid fa-shield-check mr-1.5"></i> Escrow Terlindungi
                </span>
            </div>
        </header>

        @php
            $designerUser = $rfq->designer->user ?? null;
            $quotation = $rfq->latestQuotation;
            $order = $rfq->order;
            $sampleLabels = [
                'NOT_REQUESTED' => ['label' => 'Belum Diminta', 'class' => 'bg-gray-100 text-gray-700', 'box' => 'text-gray-700'],
                'PREPARING' => ['label' => 'Sedang Disiapkan', 'class' => 'bg-amber-100 text-amber-800', 'box' => 'text-amber-700'],
                'SENT_BY_COURIER' => ['label' => 'Dikirim via Kurir', 'class' => 'bg-blue-100 text-blue-800', 'box' => 'text-blue-700'],
                'APPROVED_BY_DESIGNER' => ['label' => 'ACC & Tervalidasi Desainer', 'class' => 'bg-emerald-100 text-emerald-800', 'box' => 'text-emerald-600'],
            ];
            $sample = $sampleLabels[$rfq->sample_status] ?? ['label' => $rfq->sample_status, 'class' => 'bg-gray-100 text-gray-700', 'box' => 'text-gray-600'];
            $dpHeld = $order && $order->payment_status !== 'UNPAID';
        @endphp

        <div class="p-8 space-y-8 flex-1">
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

            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
                <!-- Sisi Kiri: Status Proyek & Sampel Material -->
                <div class="lg:col-span-4 space-y-6">
                    <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm space-y-6">
                        <div class="flex items-center space-x-3 pb-4 border-b border-gray-100">
                            <img src="https://ui-avatars.com/api/?name={{ urlencode($designerUser->full_name ?? 'Desainer') }}&background=FAF1E8&color=B5733A" class="w-12 h-12 rounded-full border-2 border-primary">
                            <div>
                                <h3 class="font-bold text-sm text-gray-900">{{ $designerUser->full_name ?? 'Desainer' }}</h3>
                                <p class="text-[10px] text-gray-500">Desainer Interior &bull; {{ $rfq->project_name }}</p>
                                <span class="bg-primary/10 text-primary text-[9px] font-bold px-2 py-0.5 rounded-full inline-block mt-1">{{ $rfq->rfq_number }}</span>
                            </div>
                        </div>

                        <!-- Card Sampel Kayu -->
                        <div class="space-y-3">
                            <div class="flex items-center justify-between">
                                <p class="text-[10px] font-bold uppercase tracking-wider text-gray-400">Persetujuan Sampel Fisik Material:</p>
                                <span class="{{ $sample['class'] }} text-[9px] font-bold px-2 py-0.5 rounded-full">{{ $sample['label'] }}</span>
                            </div>
                            <div class="rounded-xl overflow-hidden border border-gray-200 aspect-video relative">
                                <img src="https://images.unsplash.com/photo-1546484396-fb3fc6f95f98?auto=format&fit=crop&w=600&q=80" class="w-full h-full object-cover">
                                <div class="absolute bottom-2 left-2 right-2 bg-white/95 backdrop-blur-sm p-2 rounded-lg text-xs border border-gray-200">
                                    <p class="font-bold text-gray-900">Sampel {{ $rfq->wood_type }}</p>
                                    <p class="text-[10px] {{ $sample['box'] }} font-bold">
                                        @if($rfq->is_sample_approved)
                                            &check; ACC &amp; Tervalidasi Desainer
                                        @elseif($rfq->sample_status === 'SENT_BY_COURIER')
                                            Menunggu validasi desainer
                                        @else
                                            {{ $sample['label'] }}
                                        @endif
                                    </p>
                                </div>
                            </div>
                            <div class="text-[11px] text-gray-500 space-y-0.5">
                                <p>Ekspedisi: <b class="text-gray-800">{{ $rfq->courier_name ?: '-' }}</b></p>
                                <p>No. Resi: <span class="font-mono text-primary font-bold">{{ $rfq->tracking_number ?: '-' }}</span></p>
                            </div>

                            @if(in_array($rfq->sample_status, ['NOT_REQUESTED', 'PREPARING', 'SENT_BY_COURIER'], true))
                                <form method="POST" action="{{ b2b_route('seller.b2b.negosiasi.sample', $rfq) }}" class="space-y-2 pt-2 border-t border-gray-100">
                                    @csrf
                                    <label class="block text-[10px] font-bold text-gray-600">Perbarui Status Sampel</label>
                                    <select name="sample_status" class="w-full text-xs border border-gray-200 rounded-xl px-3 py-2 focus:outline-none focus:border-primary" required>
                                        <option value="PREPARING" @selected($rfq->sample_status === 'PREPARING')>Sedang Disiapkan</option>
                                        <option value="SENT_BY_COURIER" @selected($rfq->sample_status === 'SENT_BY_COURIER')>Dikirim via Kurir</option>
                                    </select>
                                    <div class="grid grid-cols-2 gap-2">
                                        <input type="text" name="courier_name" value="{{ $rfq->courier_name }}" placeholder="Ekspedisi (JNE)" class="text-xs border border-gray-200 rounded-xl px-3 py-2 focus:outline-none focus:border-primary">
                                        <input type="text" name="tracking_number" value="{{ $rfq->tracking_number }}" placeholder="No. Resi" class="text-xs border border-gray-200 rounded-xl px-3 py-2 focus:outline-none focus:border-primary">
                                    </div>
                                    <button type="submit" class="w-full bg-gray-900 hover:bg-black text-white py-2.5 rounded-xl font-bold text-[11px] transition-all">
                                        Simpan &amp; Kabari Desainer
                                    </button>
                                </form>
                            @endif
                        </div>

                        <!-- Status Escrow Box -->
                        <div class="p-4 {{ $dpHeld ? 'bg-emerald-50 border-emerald-200' : 'bg-amber-50 border-amber-200' }} rounded-xl border space-y-2">
                            <div class="flex items-center space-x-2 {{ $dpHeld ? 'text-emerald-800' : 'text-amber-800' }} font-bold text-xs">
                                <i class="fa-solid fa-circle-check text-base"></i>
                                <span>{{ $dpHeld ? 'DP Termin 1 Ditahan di Escrow!' : 'Menunggu DP dari Desainer' }}</span>
                            </div>
                            @if($dpHeld)
                                <p class="text-xs text-emerald-900">
                                    Desainer telah menyetor <b>Rp {{ number_format((float) $order->escrow_balance, 0, ',', '.') }}</b> ke rekening penampung resmi Decor.
                                </p>
                                <p class="text-[10px] text-emerald-700 font-medium">Workshop diizinkan resmi memulai pemotongan kayu dan perakitan rangka.</p>
                            @else
                                <p class="text-xs text-amber-900">
                                    Produksi baru dapat dimulai setelah desainer menyetujui penawaran dan menyetor DP ke escrow.
                                </p>
                            @endif
                        </div>

                        <!-- Link ke Produksi -->
                        @if($order)
                            <a href="{{ b2b_route('seller.b2b.produksi', $order) }}" class="w-full bg-primary hover:bg-[#8C4E1E] text-white py-3.5 px-4 rounded-xl font-bold text-xs flex items-center justify-center transition-all shadow-md">
                                Mulai Produksi Massal &amp; Buka Manajemen Workshop <i class="fa-solid fa-arrow-right ml-2"></i>
                            </a>
                        @elseif($quotation && in_array($quotation->status, ['DRAFT', 'REVISED'], true))
                            <a href="{{ b2b_route('seller.b2b.quotation.edit', $quotation) }}" class="w-full bg-primary hover:bg-[#8C4E1E] text-white py-3.5 px-4 rounded-xl font-bold text-xs flex items-center justify-center transition-all shadow-md">
                                Lengkapi &amp; Kirim Penawaran Harga <i class="fa-solid fa-file-invoice-dollar ml-2"></i>
                            </a>
                        @else
                            <a href="{{ b2b_route('seller.b2b.quotation.create', $rfq) }}" class="w-full bg-primary hover:bg-[#8C4E1E] text-white py-3.5 px-4 rounded-xl font-bold text-xs flex items-center justify-center transition-all shadow-md">
                                Buat Penawaran Harga <i class="fa-solid fa-file-invoice-dollar ml-2"></i>
                            </a>
                        @endif
                    </div>
                </div>

                <!-- Sisi Kanan: Thread Chat Negosiasi & Diskusi Teknis -->
                <div class="lg:col-span-8 flex flex-col bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden h-[680px]">
                    <!-- Chat Header -->
                    <div class="p-4 px-6 border-b border-gray-100 flex items-center justify-between bg-gray-50/50">
                        <div class="flex items-center space-x-3">
                            <i class="fa-solid fa-comments text-primary text-xl"></i>
                            <div>
                                <h4 class="font-bold text-xs text-gray-900">Thread Diskusi Spesifikasi Teknis Proyek</h4>
                                <p class="text-[10px] text-gray-500">Seluruh riwayat chat mengikat perjanjian kontrak B2B resmi Decor</p>
                            </div>
                        </div>
                        <span class="text-[10px] bg-orange-100 text-primary font-bold px-2.5 py-1 rounded-full">
                            {{ $quotation->quotation_number ?? 'Belum ada penawaran' }}
                            @if($quotation) &bull; {{ str_replace('_', ' ', $quotation->status) }} @endif
                        </span>
                    </div>

                    <!-- Chat Body -->
                    <div class="flex-1 p-6 overflow-y-auto space-y-4 text-xs">
                        @forelse($rfq->negotiations as $chat)
                            @php $isOwn = $chat->sender_id === auth()->id(); @endphp
                            @if($isOwn)
                                <div class="flex items-start space-x-3 max-w-xl ml-auto flex-row-reverse space-x-reverse">
                                    <img src="https://ui-avatars.com/api/?name={{ urlencode(auth()->user()->full_name ?? 'Saya') }}&background=B5733A&color=fff" class="w-8 h-8 rounded-full">
                                    <div class="bg-primary text-white p-4 rounded-2xl rounded-tr-none space-y-1">
                                        <p class="font-bold text-[11px] text-white">{{ $chat->sender->full_name ?? 'Anda' }} (Anda)</p>
                                        <p class="text-white/95 whitespace-pre-line">{{ $chat->message }}</p>
                                        @if($chat->attachment_url)
                                            <a href="{{ asset('storage/' . $chat->attachment_url) }}" target="_blank" class="text-[10px] underline text-white/90 block">Lihat lampiran</a>
                                        @endif
                                        <span class="text-[9px] text-white/70 block text-right">{{ $chat->created_at->format('d M Y H:i') }}</span>
                                    </div>
                                </div>
                            @else
                                <div class="flex items-start space-x-3 max-w-xl">
                                    <img src="https://ui-avatars.com/api/?name={{ urlencode($chat->sender->full_name ?? $designerUser->full_name ?? 'Desainer') }}&background=FAF1E8&color=B5733A" class="w-8 h-8 rounded-full border border-primary/20">
                                    <div class="bg-gray-100 p-4 rounded-2xl rounded-tl-none space-y-1">
                                        <p class="font-bold text-[11px] text-gray-900">{{ $chat->sender->full_name ?? 'Desainer' }} (Desainer)</p>
                                        <p class="text-gray-700 whitespace-pre-line">{{ $chat->message }}</p>
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
                                Belum ada diskusi. Mulai percakapan teknis dengan desainer.
                            </div>
                        @endforelse

                        @if($dpHeld)
                            <div class="p-3 bg-emerald-50 rounded-xl border border-emerald-200 text-center text-xs text-emerald-800 space-y-0.5">
                                <p class="font-bold"><i class="fa-solid fa-lock text-emerald-600 mr-1.5"></i> SISTEM ESCROW DECOR: DANA DP MASUK</p>
                                <p class="text-[10px]">
                                    Uang Muka Rp {{ number_format((float) $order->escrow_balance, 0, ',', '.') }}
                                    telah disetor oleh {{ $designerUser->full_name ?? 'desainer' }} dan diamankan di Rekening Escrow Platform.
                                </p>
                            </div>
                        @endif
                    </div>

                    <!-- Chat Input -->
                    <form method="POST" action="{{ b2b_route('seller.b2b.negosiasi.message', $rfq) }}" enctype="multipart/form-data" class="p-4 border-t border-gray-100 bg-gray-50 space-y-2">
                        @csrf
                        <div class="flex items-center space-x-3">
                            <input type="text" name="message" required placeholder="Tuliskan respon atau update teknis ke desainer..." class="flex-1 text-xs border border-gray-200 rounded-xl px-4 py-3 focus:outline-none focus:border-primary">
                            <button type="submit" class="bg-primary hover:bg-[#8C4E1E] text-white px-5 py-3 rounded-xl font-bold text-xs transition-all flex items-center">
                                Kirim <i class="fa-solid fa-paper-plane ml-2"></i>
                            </button>
                        </div>
                        <input type="file" name="attachment" accept=".jpg,.jpeg,.png,.pdf,.dwg,.dxf" class="text-[10px] text-gray-500">
                    </form>
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
