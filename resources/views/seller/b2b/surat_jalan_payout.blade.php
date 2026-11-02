<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Decor Seller - Surat Jalan & Pelunasan Escrow B2B</title>
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

        /* KHUSUS CETAK DOKUMEN SURAT JALAN RESMI */
        @media print {
            @page {
                size: A4 portrait;
                margin: 12mm;
            }
            body {
                background: #ffffff !important;
                color: #000000 !important;
                -webkit-print-color-adjust: exact !important;
                print-color-adjust: exact !important;
            }
            #sidebar, header, .no-print, button, #right-column, #header-banner, #escrow-summary-cards {
                display: none !important;
            }
            #main-content {
                margin-left: 0 !important;
                padding: 0 !important;
                min-height: auto !important;
            }
            .page-container {
                padding: 0 !important;
                max-width: 100% !important;
            }
            #surat-jalan-document {
                width: 100% !important;
                max-width: 100% !important;
                box-shadow: none !important;
                border: 2px solid #000000 !important;
                padding: 24px !important;
                border-radius: 0 !important;
                page-break-inside: avoid;
            }
            #surat-jalan-document table {
                border-collapse: collapse !important;
            }
            #surat-jalan-document th, #surat-jalan-document td {
                border: 1px solid #444444 !important;
                color: #000000 !important;
            }
        }
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
            $delivery = $order->delivery;
            $bast = $order->bast;
            $items = collect($rfq->items ?? []);
            $totalUnits = $items->sum('qty');
            $dpAmount = (float) ($quotation->dp_amount ?? 0);
            $remainingAmount = (float) ($quotation->remaining_amount ?? 0);
            $statusLabels = [
                'PREPARING' => ['label' => 'Disiapkan / Muat Barang', 'class' => 'bg-amber-100 text-amber-800'],
                'IN_TRANSIT' => ['label' => 'Dalam Perjalanan', 'class' => 'bg-blue-100 text-blue-800'],
                'DELIVERED' => ['label' => 'Tiba di Lokasi', 'class' => 'bg-emerald-100 text-emerald-800'],
            ];
            $deliveryStatus = $delivery ? ($statusLabels[$delivery->status] ?? ['label' => $delivery->status, 'class' => 'bg-gray-100 text-gray-700']) : null;
        @endphp

        <!-- Topbar Header -->
        <header class="h-16 bg-primary flex items-center justify-between px-8 sticky top-0 z-40 shadow-sm text-white no-print">
            <div class="flex items-center space-x-4">
                <button id="toggle-sidebar" class="text-white hover:text-gray-200 focus:outline-none">
                    <i class="fa-solid fa-bars text-lg"></i>
                </button>
                <div class="flex items-center space-x-2 text-xs">
                    <a href="{{ b2b_route('seller.b2b.inbox') }}" class="text-white/80 hover:text-white">Pesanan B2B</a>
                    <span class="text-white/50">/</span>
                    <a href="{{ b2b_route('seller.b2b.produksi', $order) }}" class="text-white/80 hover:text-white">{{ $rfq->rfq_number }}</a>
                    <span class="text-white/50">/</span>
                    <span class="font-bold text-white">Surat Jalan & Pencairan Escrow</span>
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
        <div class="page-container p-8 space-y-6 max-w-7xl">
            @if(session('success'))
                <div class="bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-2xl p-4 text-xs font-semibold no-print">
                    <i class="fa-solid fa-circle-check mr-1"></i> {{ session('success') }}
                </div>
            @endif
            @if($errors->any())
                <div class="bg-red-50 border border-red-200 text-red-700 rounded-2xl p-4 text-xs no-print">
                    <ul class="list-disc list-inside space-y-0.5">
                        @foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach
                    </ul>
                </div>
            @endif

            <!-- Header Banner -->
            <div id="header-banner" class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm flex flex-col md:flex-row md:items-center justify-between gap-4 no-print">
                <div>
                    <div class="flex items-center space-x-3 mb-1">
                        @if($delivery)
                            <span class="{{ $deliveryStatus['class'] }} text-[10px] font-extrabold px-3 py-1 rounded-full uppercase tracking-wider">
                                <i class="fa-solid fa-truck-moving mr-1"></i> {{ $deliveryStatus['label'] }}
                            </span>
                            <span class="text-xs text-gray-400">No. Surat Jalan: {{ $delivery->surat_jalan_number }}</span>
                        @else
                            <span class="bg-gray-100 text-gray-700 text-[10px] font-extrabold px-3 py-1 rounded-full uppercase tracking-wider">
                                <i class="fa-solid fa-file-circle-plus mr-1"></i> Surat Jalan Belum Dibuat
                            </span>
                            <span class="text-xs text-gray-400">Status order: {{ str_replace('_', ' ', $order->status) }}</span>
                        @endif
                    </div>
                    <h2 class="text-xl font-extrabold text-gray-900">Pengiriman {{ $totalUnits ?: $items->count() }} Unit Furnitur {{ $rfq->project_name }}</h2>
                    <p class="text-xs text-gray-500 mt-1">
                        @if($delivery)
                            Ekspedisi: <b class="text-gray-700">{{ $delivery->courier_service }}</b>
                            @if($delivery->vehicle_plate) &bull; Nopol {{ $delivery->vehicle_plate }} @endif
                            @if($delivery->driver_name) &bull; Driver: {{ $delivery->driver_name }} @endif
                        @else
                            Lengkapi data ekspedisi untuk menerbitkan Surat Jalan resmi.
                        @endif
                    </p>
                </div>

                <div class="flex items-center space-x-3">
                    @if($delivery)
                        <button onclick="window.print()" class="px-5 py-2.5 rounded-xl bg-primary text-white text-xs font-bold hover:bg-[#8C4E1E] transition-all shadow-md flex items-center">
                            <i class="fa-solid fa-print mr-2"></i> Cetak Surat Jalan (A4)
                        </button>
                    @endif
                    <a href="{{ b2b_route('seller.b2b.inbox') }}" class="px-4 py-2.5 rounded-xl border border-gray-200 text-xs font-bold text-gray-600 hover:bg-gray-50 transition-all flex items-center">
                        <i class="fa-solid fa-arrow-left mr-2"></i> Kembali ke Inbox B2B
                    </a>
                </div>
            </div>

            <!-- Escrow Status & Payout Card -->
            <div id="escrow-summary-cards" class="grid grid-cols-1 md:grid-cols-3 gap-6 no-print">
                <!-- Termin 1: DP -->
                <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm space-y-2">
                    <div class="flex items-center justify-between">
                        <span class="text-[10px] font-bold text-gray-400 uppercase tracking-wider">Termin 1 (DP {{ $quotation->dp_percentage ?? 0 }}%)</span>
                        <span class="{{ $order->dp_paid_at ? 'bg-emerald-100 text-emerald-700' : 'bg-gray-100 text-gray-500' }} text-[9px] font-bold px-2 py-0.5 rounded-full">
                            {{ $order->dp_paid_at ? 'Telah Disetor' : 'Belum Disetor' }}
                        </span>
                    </div>
                    <p class="text-2xl font-black text-emerald-600">Rp {{ number_format($dpAmount, 0, ',', '.') }}</p>
                    <p class="text-[11px] text-gray-500">
                        @if($order->dp_paid_at)
                            Disetor ke escrow pada {{ $order->dp_paid_at->format('d M Y') }}.
                        @else
                            Menunggu pembayaran DP dari desainer.
                        @endif
                    </p>
                </div>

                <!-- Termin 2: Pelunasan -->
                <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm space-y-2 ring-2 ring-primary/20">
                    <div class="flex items-center justify-between">
                        <span class="text-[10px] font-bold text-primary uppercase tracking-wider">Termin 2 (Pelunasan)</span>
                        <span class="{{ $order->payment_status === 'RELEASED_TO_SELLER' ? 'bg-emerald-100 text-emerald-800' : 'bg-amber-100 text-amber-800' }} text-[9px] font-bold px-2 py-0.5 rounded-full">
                            {{ $order->payment_status === 'RELEASED_TO_SELLER' ? 'Sudah Dirilis' : 'Terkunci di Escrow' }}
                        </span>
                    </div>
                    <p class="text-2xl font-black text-primary">Rp {{ number_format($remainingAmount, 0, ',', '.') }}</p>
                    <p class="text-[11px] text-gray-500">Otomatis cair setelah desainer menandatangani BAST di lokasi proyek.</p>
                </div>

                <!-- Payout -->
                <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm space-y-2">
                    <div class="flex items-center justify-between">
                        <span class="text-[10px] font-bold text-gray-400 uppercase tracking-wider">Status Pencairan Dana</span>
                        <span class="bg-blue-100 text-blue-800 text-[9px] font-bold px-2 py-0.5 rounded-full">{{ str_replace('_', ' ', $order->payout_status) }}</span>
                    </div>
                    <p class="text-2xl font-black text-gray-900">Rp {{ number_format((float) $order->total_amount, 0, ',', '.') }}</p>
                    <p class="text-[11px] text-gray-500">
                        @if($order->payout_status === 'REQUESTED')
                            Permintaan payout diajukan {{ $order->payout_requested_at?->format('d M Y H:i') ?? '' }}.
                        @elseif($order->payout_status === 'PAID')
                            Dana telah ditransfer ke rekening pabrik.
                        @elseif($order->payout_status === 'AVAILABLE')
                            Dana siap dicairkan.
                        @else
                            Payout tersedia setelah order selesai & BAST ditandatangani.
                        @endif
                    </p>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
                <!-- Kolom Kiri: Dokumen Fisik Surat Jalan / Form Pembuatan -->
                @if($delivery)
                    <div id="surat-jalan-document" class="lg:col-span-8 bg-white p-8 rounded-2xl border border-gray-100 shadow-sm space-y-6">
                        <!-- Kop Surat Jalan -->
                        <div class="border-b-2 border-gray-800 pb-6 flex items-start justify-between">
                            <div>
                                <h3 class="text-lg font-black text-gray-900 tracking-wider">SURAT JALAN PENGIRIMAN FURNITUR B2B</h3>
                                <p class="text-xs text-gray-500 font-mono mt-0.5">NOMOR: {{ $delivery->surat_jalan_number }}</p>
                            </div>
                            <div class="text-right">
                                <span class="text-lg font-black tracking-widest text-primary uppercase">DECOR</span>
                                <p class="text-[10px] text-gray-400">Integrated B2B Logistic</p>
                            </div>
                        </div>

                        <!-- Detail Pengirim & Penerima -->
                        <div class="grid grid-cols-2 gap-6 text-xs">
                            <div class="p-4 bg-gray-50 rounded-xl space-y-1">
                                <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider">Pengirim / Workshop:</p>
                                <p class="font-bold text-gray-900">{{ $seller->store_name }}</p>
                                <p class="text-gray-600">{{ $seller->store_address ?: ($seller->city ?: 'Indonesia') }}</p>
                                <p class="text-gray-500">PIC: {{ $seller->phone_number ?: '-' }}</p>
                            </div>
                            <div class="p-4 bg-gray-50 rounded-xl space-y-1">
                                <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider">Tujuan Penerima:</p>
                                <p class="font-bold text-gray-900">{{ $delivery->recipient_name ?: ($designerUser->full_name ?? 'Desainer') }}</p>
                                <p class="text-gray-600">{{ $delivery->destination_address }}</p>
                                <p class="text-gray-500">PIC Lapangan: {{ $delivery->recipient_phone ?: '-' }}</p>
                            </div>
                        </div>

                        <!-- Info Ekspedisi -->
                        <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 text-xs">
                            <div class="p-3 bg-gray-50 rounded-xl">
                                <p class="text-[9px] text-gray-400 uppercase font-bold">Ekspedisi</p>
                                <p class="font-bold text-gray-800">{{ $delivery->courier_service }}</p>
                            </div>
                            <div class="p-3 bg-gray-50 rounded-xl">
                                <p class="text-[9px] text-gray-400 uppercase font-bold">Driver</p>
                                <p class="font-bold text-gray-800">{{ $delivery->driver_name ?: '-' }}</p>
                            </div>
                            <div class="p-3 bg-gray-50 rounded-xl">
                                <p class="text-[9px] text-gray-400 uppercase font-bold">Nopol</p>
                                <p class="font-bold text-gray-800">{{ $delivery->vehicle_plate ?: '-' }}</p>
                            </div>
                            <div class="p-3 bg-gray-50 rounded-xl">
                                <p class="text-[9px] text-gray-400 uppercase font-bold">Tanggal Kirim</p>
                                <p class="font-bold text-gray-800">{{ $delivery->delivery_date?->format('d M Y') ?? '-' }}</p>
                            </div>
                        </div>

                        <!-- Tabel Manifes Muatan -->
                        <div>
                            <h4 class="text-xs font-bold text-gray-900 mb-3 uppercase tracking-wider">Daftar Barang Muatan Truk:</h4>
                            <div class="overflow-x-auto border border-gray-200 rounded-xl">
                                <table class="w-full text-left text-xs">
                                    <thead class="bg-gray-100 text-gray-600 font-bold border-b border-gray-200">
                                        <tr>
                                            <th class="p-3">No</th>
                                            <th class="p-3">Item Furnitur</th>
                                            <th class="p-3">Dimensi & Spek</th>
                                            <th class="p-3 text-center">Jumlah</th>
                                            <th class="p-3">Packaging & Segel</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-gray-100">
                                        @forelse($items as $index => $item)
                                            <tr>
                                                <td class="p-3 font-bold text-gray-500">{{ $index + 1 }}</td>
                                                <td class="p-3 font-bold text-gray-900">{{ $item['name'] ?? 'Item furnitur' }}</td>
                                                <td class="p-3 text-gray-600">{{ $item['note'] ?? '-' }}</td>
                                                <td class="p-3 text-center font-bold text-primary">{{ $item['qty'] ?? 0 }} {{ $item['unit'] ?? 'Unit' }}</td>
                                                <td class="p-3 text-emerald-700 font-medium">Corner Foam + Segel (Aman)</td>
                                            </tr>
                                        @empty
                                            <tr><td colspan="5" class="p-4 text-center text-gray-500">Tidak ada rincian item.</td></tr>
                                        @endforelse
                                    </tbody>
                                    <tfoot class="bg-gray-50 font-bold text-gray-800 border-t border-gray-200">
                                        <tr>
                                            <td colspan="3" class="p-3 text-right">Total Muatan Fisik:</td>
                                            <td class="p-3 text-center text-primary text-sm font-extrabold">{{ $totalUnits ?: $items->count() }} Unit</td>
                                            <td class="p-3 text-gray-500 font-normal">Kondisi 100% Baru & Tersegel</td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>

                        <!-- Tanda Tangan Digital -->
                        @php
                            $signers = [
                                [
                                    'role' => 'workshop',
                                    'label' => 'Petugas Workshop',
                                    'name' => $seller->store_name,
                                    'url' => $delivery->workshop_signature_url,
                                    'at' => $delivery->workshop_signed_at,
                                    'tone' => 'gray',
                                    'can_sign' => true,
                                    'pending' => 'Belum ditandatangani',
                                ],
                                [
                                    'role' => 'driver',
                                    'label' => 'Driver Ekspedisi Kargo',
                                    'name' => $delivery->driver_name ?: '-',
                                    'url' => $delivery->driver_signature_url,
                                    'at' => $delivery->driver_signed_at,
                                    'tone' => 'gray',
                                    'can_sign' => false,
                                    'pending' => 'Ditandatangani driver saat barang tiba',
                                ],
                                [
                                    'role' => 'recipient',
                                    'label' => 'Penerima Lokasi Proyek',
                                    'name' => $delivery->recipient_name ?: ($designerUser->full_name ?? 'Desainer'),
                                    'url' => $delivery->recipient_signature_url,
                                    'at' => $delivery->recipient_signed_at,
                                    'tone' => 'amber',
                                    'can_sign' => false,
                                    'pending' => 'Ditandatangani saat serah terima (BAST)',
                                ],
                            ];
                        @endphp
                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 pt-6 border-t border-gray-100 text-center text-xs"
                             data-signature-url="{{ b2b_route('seller.b2b.surat_jalan.signature', $delivery) }}">
                            @foreach($signers as $signer)
                                <div class="p-4 rounded-xl border space-y-2 {{ $signer['url'] ? 'bg-emerald-50/40 border-emerald-200' : ($signer['tone'] === 'amber' ? 'bg-amber-50 border-amber-200' : 'bg-gray-50 border-gray-100') }}">
                                    <p class="font-bold text-gray-600">{{ $signer['label'] }}</p>
                                    <div class="h-16 flex items-center justify-center">
                                        @if($signer['url'])
                                            <img src="{{ asset('storage/' . $signer['url']) }}" alt="Tanda tangan {{ $signer['label'] }}" class="max-h-16 object-contain">
                                        @elseif($signer['can_sign'])
                                            <button type="button"
                                                    class="sig-open inline-flex items-center gap-2 px-4 py-2 rounded-lg border border-dashed border-gray-300 text-[11px] font-bold text-gray-500 hover:border-primary hover:text-primary transition-all"
                                                    data-role="{{ $signer['role'] }}"
                                                    data-label="{{ $signer['label'] }}"
                                                    data-name="{{ $signer['name'] }}">
                                                <i class="fa-solid fa-signature"></i> Tanda Tangan
                                            </button>
                                        @else
                                            <div class="flex flex-col items-center gap-1 text-gray-400">
                                                <i class="fa-solid fa-lock text-base"></i>
                                                <span class="text-[9px] font-bold uppercase tracking-wider">Saat Barang Tiba</span>
                                            </div>
                                        @endif
                                    </div>
                                    <p class="font-bold text-gray-800">{{ $signer['name'] }}</p>
                                    @if($signer['url'])
                                        <p class="text-[9px] font-bold text-emerald-600">
                                            <i class="fa-solid fa-circle-check"></i> Ditandatangani {{ $signer['at']?->format('d M Y H:i') }}
                                        </p>
                                        @if($signer['can_sign'])
                                            <button type="button"
                                                    class="sig-open text-[9px] text-gray-400 underline hover:text-primary"
                                                    data-role="{{ $signer['role'] }}"
                                                    data-label="{{ $signer['label'] }}"
                                                    data-name="{{ $signer['name'] }}">
                                                Ganti tanda tangan
                                            </button>
                                        @endif
                                    @else
                                        <p class="text-[9px] text-gray-400">{{ $signer['pending'] }}</p>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    </div>
                @else
                    <div class="lg:col-span-8 bg-white p-8 rounded-2xl border border-gray-100 shadow-sm space-y-5">
                        <div>
                            <h3 class="text-lg font-black text-gray-900 tracking-wider">TERBITKAN SURAT JALAN</h3>
                            <p class="text-xs text-gray-500 mt-1">
                                Surat jalan hanya dapat diterbitkan saat order berstatus <b>READY FOR DELIVERY</b>.
                                Status saat ini: <b>{{ str_replace('_', ' ', $order->status) }}</b>.
                            </p>
                        </div>

                        @if($order->status === 'READY_FOR_DELIVERY')
                            <form method="POST" action="{{ b2b_route('seller.b2b.surat_jalan.store') }}" class="space-y-4 text-xs">
                                @csrf
                                <input type="hidden" name="order_id" value="{{ $order->id }}">

                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                    <div class="space-y-1">
                                        <label class="font-bold text-gray-700">Ekspedisi / Kargo *</label>
                                        <input type="text" name="courier_service" value="{{ old('courier_service') }}" placeholder="cth: Kargo Kayu Nusantara" required class="w-full border border-gray-200 rounded-xl px-4 py-2.5 focus:outline-none focus:border-primary">
                                    </div>
                                    <div class="space-y-1">
                                        <label class="font-bold text-gray-700">Tanggal Kirim *</label>
                                        <input type="date" name="delivery_date" value="{{ old('delivery_date', now()->toDateString()) }}" required class="w-full border border-gray-200 rounded-xl px-4 py-2.5 focus:outline-none focus:border-primary">
                                    </div>
                                    <div class="space-y-1">
                                        <label class="font-bold text-gray-700">Nama Driver</label>
                                        <input type="text" name="driver_name" value="{{ old('driver_name') }}" class="w-full border border-gray-200 rounded-xl px-4 py-2.5 focus:outline-none focus:border-primary">
                                    </div>
                                    <div class="space-y-1">
                                        <label class="font-bold text-gray-700">Nomor Polisi</label>
                                        <input type="text" name="vehicle_plate" value="{{ old('vehicle_plate') }}" placeholder="cth: K-8921-FA" class="w-full border border-gray-200 rounded-xl px-4 py-2.5 focus:outline-none focus:border-primary">
                                    </div>
                                    <div class="space-y-1 sm:col-span-2">
                                        <label class="font-bold text-gray-700">Alamat Tujuan *</label>
                                        <textarea name="destination_address" rows="2" required class="w-full border border-gray-200 rounded-xl px-4 py-2.5 focus:outline-none focus:border-primary">{{ old('destination_address', $rfq->delivery_address) }}</textarea>
                                    </div>
                                    <div class="space-y-1">
                                        <label class="font-bold text-gray-700">Nama Penerima</label>
                                        <input type="text" name="recipient_name" value="{{ old('recipient_name', $designerUser->full_name ?? '') }}" class="w-full border border-gray-200 rounded-xl px-4 py-2.5 focus:outline-none focus:border-primary">
                                    </div>
                                    <div class="space-y-1">
                                        <label class="font-bold text-gray-700">Telepon Penerima</label>
                                        <input type="text" name="recipient_phone" value="{{ old('recipient_phone') }}" class="w-full border border-gray-200 rounded-xl px-4 py-2.5 focus:outline-none focus:border-primary">
                                    </div>
                                </div>

                                <button type="submit" class="bg-primary hover:bg-[#8C4E1E] text-white px-6 py-3 rounded-xl font-bold text-xs transition-all shadow-md flex items-center">
                                    <i class="fa-solid fa-file-circle-plus mr-2"></i> Terbitkan Surat Jalan & Tandai Dikirim
                                </button>
                            </form>
                        @else
                            <div class="p-5 bg-amber-50 border border-amber-200 rounded-xl text-xs text-amber-800">
                                <p class="font-bold mb-1"><i class="fa-solid fa-hourglass-half mr-1"></i> Belum siap dikirim</p>
                                <p>Pastikan seluruh 5 pos produksi sudah 100% dan pembayaran pelunasan sudah masuk escrow.</p>
                            </div>
                            <a href="{{ b2b_route('seller.b2b.produksi', $order) }}" class="inline-flex bg-primary hover:bg-[#8C4E1E] text-white px-5 py-3 rounded-xl font-bold text-xs transition-all shadow-md items-center">
                                <i class="fa-solid fa-list-check mr-2"></i> Kembali ke Manajemen Produksi
                            </a>
                        @endif
                    </div>
                @endif

                <!-- Kolom Kanan: Tracking & BAST Readiness -->
                <div id="right-column" class="lg:col-span-4 space-y-6 no-print">
                    @if($delivery)
                        <!-- Update Status Pengiriman -->
                        <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm space-y-4">
                            <h4 class="text-xs font-bold text-gray-900 flex items-center">
                                <i class="fa-solid fa-location-dot text-primary mr-2"></i> Perbarui Status Pengiriman
                            </h4>
                            <form method="POST" action="{{ b2b_route('seller.b2b.surat_jalan.update', $delivery) }}" class="space-y-3 text-xs">
                                @csrf
                                @method('PUT')
                                <div class="space-y-1">
                                    <label class="font-bold text-gray-700">Status</label>
                                    <select name="status" class="w-full border border-gray-200 rounded-xl px-3 py-2 focus:outline-none focus:border-primary" required>
                                        <option value="PREPARING" @selected($delivery->status === 'PREPARING')>Disiapkan / Muat Barang</option>
                                        <option value="IN_TRANSIT" @selected($delivery->status === 'IN_TRANSIT')>Dalam Perjalanan</option>
                                        <option value="DELIVERED" @selected($delivery->status === 'DELIVERED')>Tiba di Lokasi</option>
                                    </select>
                                </div>
                                <div class="space-y-1">
                                    <label class="font-bold text-gray-700">Driver</label>
                                    <input type="text" name="driver_name" value="{{ $delivery->driver_name }}" class="w-full border border-gray-200 rounded-xl px-3 py-2 focus:outline-none focus:border-primary">
                                </div>
                                <div class="space-y-1">
                                    <label class="font-bold text-gray-700">Nomor Polisi</label>
                                    <input type="text" name="vehicle_plate" value="{{ $delivery->vehicle_plate }}" class="w-full border border-gray-200 rounded-xl px-3 py-2 focus:outline-none focus:border-primary">
                                </div>
                                <button type="submit" class="w-full bg-gray-900 hover:bg-black text-white py-2.5 rounded-xl font-bold text-[11px] transition-all">
                                    Simpan Status
                                </button>
                            </form>
                        </div>
                    @endif

                    <!-- Info BAST & Settlement -->
                    <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm space-y-4">
                        <div class="flex items-center space-x-2 text-primary font-bold text-xs">
                            <i class="fa-solid fa-file-signature text-base"></i>
                            <span>Berita Acara Serah Terima (BAST)</span>
                        </div>
                        <p class="text-xs text-gray-600 leading-relaxed">
                            Setelah seluruh {{ $totalUnits ?: $items->count() }} unit dibongkar dan dicek oleh
                            {{ $designerUser->full_name ?? 'desainer' }} di lokasi proyek, desainer akan menandatangani dokumen BAST digital.
                        </p>

                        @if($bast?->signed_at)
                            <div class="p-3 bg-emerald-50 border border-emerald-200 rounded-xl text-xs text-emerald-800 space-y-1">
                                <p class="font-bold"><i class="fa-solid fa-check-double mr-1 text-emerald-600"></i> BAST Ditandatangani</p>
                                <p class="text-[11px]">{{ $bast->bast_number }} &bull; {{ $bast->signed_at->format('d M Y H:i') }}</p>
                                <p class="text-[11px]">{{ $bast->is_accepted ? 'Diterima tanpa cacat.' : 'Ditandatangani dengan catatan perbaikan.' }}</p>
                            </div>
                        @else
                            <div class="p-3 bg-amber-50 border border-amber-200 rounded-xl text-xs text-amber-800 space-y-1">
                                <p class="font-bold"><i class="fa-solid fa-hourglass-half mr-1"></i> Menunggu BAST</p>
                                <p class="text-[11px]">Dana pelunasan Rp {{ number_format($remainingAmount, 0, ',', '.') }} cair otomatis setelah BAST ditandatangani.</p>
                            </div>
                        @endif

                        @if($order->payout_status === 'AVAILABLE')
                            <form method="POST" action="{{ b2b_route('seller.b2b.payout.request', $order) }}">
                                @csrf
                                <button type="submit" class="w-full bg-emerald-600 hover:bg-emerald-700 text-white py-3 rounded-xl font-bold text-xs flex items-center justify-center transition-all shadow-md">
                                    <i class="fa-solid fa-money-bill-transfer mr-2"></i> Ajukan Pencairan Dana
                                </button>
                            </form>
                        @endif

                        <a href="{{ b2b_route('seller.b2b.negosiasi', $rfq) }}" class="w-full bg-primary/10 hover:bg-primary hover:text-white text-primary py-3 rounded-xl font-bold text-xs flex items-center justify-center transition-all">
                            <i class="fa-solid fa-comments mr-2"></i> Hubungi {{ $designerUser->full_name ?? 'Desainer' }} (B2B Chat)
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <!-- Modal Tanda Tangan Digital -->
    <div id="signature-modal" class="fixed inset-0 z-[70] hidden items-center justify-center p-4 no-print">
        <div class="absolute inset-0 bg-black/60" data-sig-close></div>
        <div class="relative w-full max-w-lg bg-white rounded-2xl shadow-2xl p-6 space-y-4">
            <div>
                <h3 class="font-bold text-sm text-gray-900">
                    Tanda Tangan <span id="sig-role-label" class="text-primary"></span>
                </h3>
                <p class="text-[11px] text-gray-500">Nama: <b id="sig-role-name" class="text-gray-700"></b></p>
            </div>

            <div class="border-2 border-dashed border-gray-200 rounded-xl bg-gray-50 p-1">
                <canvas id="signature-canvas" class="w-full bg-white rounded-lg touch-none" style="height: 200px;"></canvas>
            </div>

            <p class="text-[10px] text-gray-400">
                <i class="fa-solid fa-hand-pointer mr-1"></i>
                Gambar tanda tangan menggunakan mouse atau layar sentuh.
            </p>

            <p id="sig-error" class="hidden text-[11px] text-red-600 font-semibold"></p>

            <div class="flex items-center justify-between gap-2 pt-1">
                <button type="button" id="sig-clear" class="px-4 py-2.5 rounded-xl border border-gray-200 text-xs font-bold text-gray-600 hover:bg-gray-50 transition-all">
                    <i class="fa-solid fa-eraser mr-1"></i> Hapus
                </button>
                <div class="flex items-center gap-2">
                    <button type="button" data-sig-close class="px-4 py-2.5 rounded-xl border border-gray-200 text-xs font-bold text-gray-600 hover:bg-gray-50 transition-all">
                        Batal
                    </button>
                    <button type="button" id="sig-save" class="px-5 py-2.5 rounded-xl bg-primary hover:bg-[#8C4E1E] text-white text-xs font-bold transition-all shadow-md disabled:opacity-40 disabled:cursor-not-allowed">
                        <i class="fa-solid fa-check mr-1"></i> Simpan
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
        const toggleBtn = document.getElementById('toggle-sidebar');
        const sidebar = document.getElementById('sidebar');
        const main = document.getElementById('main-content');
        toggleBtn.addEventListener('click', () => {
            sidebar.classList.toggle('sidebar-hidden');
            main.classList.toggle('main-expanded');
        });

        // ------------------------------------------------ Tanda tangan digital
        (function () {
            const modal = document.getElementById('signature-modal');
            const canvas = document.getElementById('signature-canvas');
            if (!modal || !canvas) { return; }

            const ctx = canvas.getContext('2d');
            const signatureUrl = document.querySelector('[data-signature-url]')?.dataset.signatureUrl;
            const roleLabel = document.getElementById('sig-role-label');
            const roleName = document.getElementById('sig-role-name');
            const errorBox = document.getElementById('sig-error');
            const saveBtn = document.getElementById('sig-save');

            let role = null;
            let drawing = false;
            let hasInk = false;
            let lastPoint = null;

            function resizeCanvas() {
                const dpr = window.devicePixelRatio || 1;
                const rect = canvas.getBoundingClientRect();
                canvas.width = Math.max(1, Math.round(rect.width * dpr));
                canvas.height = Math.max(1, Math.round(rect.height * dpr));
                ctx.setTransform(dpr, 0, 0, dpr, 0, 0);
                ctx.lineWidth = 2.2;
                ctx.lineCap = 'round';
                ctx.lineJoin = 'round';
                ctx.strokeStyle = '#111827';
                hasInk = false;
            }

            function openModal(button) {
                if (!signatureUrl) { return; }
                role = button.dataset.role;
                roleLabel.textContent = button.dataset.label;
                roleName.textContent = button.dataset.name;
                errorBox.classList.add('hidden');
                saveBtn.disabled = false;
                modal.classList.remove('hidden');
                modal.classList.add('flex');
                requestAnimationFrame(resizeCanvas);
            }

            function closeModal() {
                modal.classList.add('hidden');
                modal.classList.remove('flex');
                role = null;
            }

            function pointFrom(event) {
                const rect = canvas.getBoundingClientRect();
                return { x: event.clientX - rect.left, y: event.clientY - rect.top };
            }

            document.querySelectorAll('.sig-open').forEach(function (button) {
                button.addEventListener('click', function () { openModal(button); });
            });

            modal.querySelectorAll('[data-sig-close]').forEach(function (element) {
                element.addEventListener('click', closeModal);
            });

            canvas.addEventListener('pointerdown', function (event) {
                event.preventDefault();
                drawing = true;
                hasInk = true;
                lastPoint = pointFrom(event);
                ctx.beginPath();
                ctx.moveTo(lastPoint.x, lastPoint.y);
                ctx.lineTo(lastPoint.x + 0.1, lastPoint.y + 0.1);
                ctx.stroke();
                if (canvas.setPointerCapture) { canvas.setPointerCapture(event.pointerId); }
            });

            canvas.addEventListener('pointermove', function (event) {
                if (!drawing || !lastPoint) { return; }
                event.preventDefault();
                const point = pointFrom(event);
                ctx.beginPath();
                ctx.moveTo(lastPoint.x, lastPoint.y);
                ctx.lineTo(point.x, point.y);
                ctx.stroke();
                lastPoint = point;
            });

            ['pointerup', 'pointercancel', 'pointerleave'].forEach(function (eventName) {
                canvas.addEventListener(eventName, function () { drawing = false; });
            });

            document.getElementById('sig-clear').addEventListener('click', function () {
                ctx.clearRect(0, 0, canvas.width, canvas.height);
                hasInk = false;
            });

            saveBtn.addEventListener('click', async function () {
                if (!hasInk || !role) {
                    errorBox.textContent = 'Silakan gambar tanda tangan terlebih dahulu.';
                    errorBox.classList.remove('hidden');
                    return;
                }

                saveBtn.disabled = true;
                errorBox.classList.add('hidden');

                try {
                    const response = await fetch(signatureUrl, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        },
                        body: JSON.stringify({
                            role: role,
                            signature: canvas.toDataURL('image/png'),
                        }),
                    });

                    if (!response.ok) {
                        let message = 'Gagal menyimpan tanda tangan.';
                        try {
                            const data = await response.json();
                            message = data.message || message;
                        } catch (ignored) {}
                        throw new Error(message);
                    }

                    window.location.reload();
                } catch (error) {
                    errorBox.textContent = error.message;
                    errorBox.classList.remove('hidden');
                    saveBtn.disabled = false;
                }
            });
        })();
    </script>
</body>
</html>
