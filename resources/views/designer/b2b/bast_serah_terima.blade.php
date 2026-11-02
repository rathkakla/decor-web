<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Decor Designer - Berita Acara Serah Terima (BAST) & Rilis Escrow</title>
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

        /* KHUSUS CETAK DOKUMEN BAST RESMI */
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
            #bast-document {
                width: 100% !important;
                max-width: 100% !important;
                box-shadow: none !important;
                border: 2px solid #000000 !important;
                padding: 24px !important;
                border-radius: 0 !important;
                page-break-inside: avoid;
            }
            #bast-document table {
                border-collapse: collapse !important;
            }
            #bast-document th, #bast-document td {
                border: 1px solid #444444 !important;
                color: #000000 !important;
            }
        }
    </style>
</head>
<body class="text-gray-800">

    @include('designer.partials.sidebar')

    <!-- Main Content -->
    <main id="main-content" class="ml-64 flex flex-col min-h-screen sidebar-transition">
        <!-- Topbar Header -->
        <header class="h-16 bg-primary flex items-center justify-between px-8 sticky top-0 z-40 shadow-sm text-white no-print">
            <div class="flex items-center space-x-4">
                <button id="toggle-sidebar" class="text-white hover:text-gray-200 focus:outline-none">
                    <i class="fa-solid fa-bars text-lg"></i>
                </button>
                <div class="flex items-center space-x-2 text-xs">
                    <a href="{{ b2b_route('designer.b2b.katalog') }}" class="text-white/80 hover:text-white">Pengadaan B2B</a>
                    <span class="text-white/50">/</span>
                    <a href="{{ b2b_route('designer.b2b.milestone', $order) }}" class="text-white/80 hover:text-white">Milestone #089</a>
                    <span class="text-white/50">/</span>
                    <span class="font-bold text-white">Berita Acara Serah Terima (BAST)</span>
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
            $bast = $order->bast;
            $totalUnits = collect($rfq->items ?? [])->sum('qty');
            $dpAmount = (float) ($quotation->dp_amount ?? 0);
            $totalAmount = (float) $order->total_amount;
            $remainingAmount = max($totalAmount - $dpAmount, 0);
        @endphp

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

            <!-- Header Bar -->
            <div id="header-banner" class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm flex flex-col md:flex-row md:items-center justify-between gap-4 no-print">
                <div>
                    <div class="flex items-center space-x-3 mb-1">
                        <span class="{{ $bast?->signed_at ? 'bg-emerald-100 text-emerald-800' : 'bg-amber-100 text-amber-800' }} text-[10px] font-extrabold px-3 py-1 rounded-full uppercase tracking-wider">
                            <i class="fa-solid fa-check-double mr-1"></i>
                            {{ $bast?->signed_at ? 'BAST Telah Ditandatangani' : 'Menunggu Tanda Tangan BAST' }}
                        </span>
                        <span class="text-xs text-gray-400">BAST No: {{ $bast->bast_number ?? '-' }}</span>
                    </div>
                    <h2 class="text-xl font-extrabold text-gray-900">Serah Terima Fisik {{ $rfq->project_name }}</h2>
                    <p class="text-xs text-gray-500 mt-0.5">
                        Lokasi: <b class="text-gray-700">{{ $rfq->delivery_address ? \Illuminate\Support\Str::limit($rfq->delivery_address, 70) : '-' }}</b>
                        &bull; Vendor: <b class="text-primary">{{ $seller->store_name }}</b>
                    </p>
                </div>

                <div class="flex items-center space-x-3">
                    <button onclick="window.print()" class="px-5 py-2.5 rounded-xl bg-primary text-white text-xs font-bold hover:bg-[#8C4E1E] transition-all shadow-md flex items-center">
                        <i class="fa-solid fa-print mr-2"></i> Cetak BAST Fisik (A4)
                    </button>
                    <a href="{{ b2b_route('designer.b2b.katalog') }}" class="px-4 py-2.5 rounded-xl border border-gray-200 text-xs font-bold text-gray-600 hover:bg-gray-50 transition-all flex items-center">
                        Katalog B2B <i class="fa-solid fa-arrow-right ml-2"></i>
                    </a>
                </div>
            </div>

            <!-- Escrow Summary Cards -->
            <div id="escrow-summary-cards" class="grid grid-cols-1 md:grid-cols-3 gap-6 no-print">
                <!-- Termin 1: DP -->
                <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm space-y-2">
                    <div class="flex items-center justify-between">
                        <span class="text-[10px] font-bold text-gray-400 uppercase tracking-wider">Termin 1 (DP {{ $quotation->dp_percentage ?? 0 }}%)</span>
                        <span class="{{ $order->dp_paid_at ? 'bg-emerald-100 text-emerald-700' : 'bg-gray-100 text-gray-500' }} text-[9px] font-bold px-2 py-0.5 rounded-full">
                            {{ $order->dp_paid_at ? 'Telah Disetor' : 'Belum Disetor' }}
                        </span>
                    </div>
                    <p class="text-2xl font-black text-gray-800">Rp {{ number_format($dpAmount, 0, ',', '.') }}</p>
                    <p class="text-[11px] text-gray-500">Disetor ke escrow saat penawaran disetujui.</p>
                </div>

                <!-- Termin 2: Pelunasan -->
                <div class="bg-white p-6 rounded-2xl border border-emerald-300 shadow-sm space-y-2 ring-2 ring-emerald-500/20">
                    <div class="flex items-center justify-between">
                        <span class="text-[10px] font-bold text-emerald-700 uppercase tracking-wider">Termin 2 (Pelunasan)</span>
                        <span class="{{ $order->payment_status === 'RELEASED_TO_SELLER' ? 'bg-emerald-100 text-emerald-800' : 'bg-amber-100 text-amber-800' }} text-[9px] font-bold px-2 py-0.5 rounded-full">
                            {{ $order->payment_status === 'RELEASED_TO_SELLER' ? 'Sudah Dirilis ke Pabrik' : 'Siap Rilis Pasca BAST' }}
                        </span>
                    </div>
                    <p class="text-2xl font-black text-emerald-600">Rp {{ number_format($remainingAmount, 0, ',', '.') }}</p>
                    <p class="text-[11px] text-emerald-800">Dicairkan dari escrow ke rekening pabrik setelah BAST ditandatangani.</p>
                </div>

                <!-- Total Nilai Kontrak -->
                <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm space-y-2">
                    <div class="flex items-center justify-between">
                        <span class="text-[10px] font-bold text-gray-400 uppercase tracking-wider">Total Nilai Kontrak</span>
                        <span class="bg-blue-100 text-blue-800 text-[9px] font-bold px-2 py-0.5 rounded-full">
                            {{ $quotation->warranty_notes ? \Illuminate\Support\Str::limit($quotation->warranty_notes, 24) : 'Garansi Aktif' }}
                        </span>
                    </div>
                    <p class="text-2xl font-black text-primary">Rp {{ number_format($totalAmount, 0, ',', '.') }}</p>
                    <p class="text-[11px] text-gray-500">Mencakup {{ $totalUnits ?: count($rfq->items ?? []) }} unit kustom mebel.</p>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
                <!-- Kolom Kiri: Dokumen BAST & Checklist Inspeksi Fisik di Lapangan -->
                <form id="bast-document" method="POST" action="{{ b2b_route('designer.b2b.bast.sign', $order) }}" enctype="multipart/form-data" class="lg:col-span-8 bg-white p-8 rounded-2xl border border-gray-100 shadow-sm space-y-6">
                    @csrf

                    <div class="border-b-2 border-gray-900 pb-6 flex items-start justify-between">
                        <div>
                            <span class="text-xs font-bold text-primary uppercase tracking-widest">OFFICIAL HANDOVER CERTIFICATE</span>
                            <h3 class="text-lg font-black text-gray-900 mt-1">BERITA ACARA SERAH TERIMA BARANG (BAST)</h3>
                            <p class="text-xs text-gray-500 font-mono mt-0.5">NOMOR: {{ $bast->bast_number ?? 'Akan dibuat saat ditandatangani' }}</p>
                        </div>
                        <div class="text-right">
                            <span class="text-lg font-black tracking-widest text-primary uppercase">DECOR</span>
                            <p class="text-[10px] text-gray-400">Escrow Verification Seal</p>
                        </div>
                    </div>

                    <p class="text-xs text-gray-600 leading-relaxed">
                        Pada hari ini, <b>{{ $bast?->signed_at?->translatedFormat('l, d F Y') ?? now()->translatedFormat('l, d F Y') }}</b>,
                        bertempat di {{ $rfq->delivery_address ?: 'lokasi proyek' }}, pihak Desainer Interior telah melakukan inspeksi fisik menyeluruh
                        terhadap seluruh barang kiriman dari pihak Produsen <b>{{ $seller->store_name }}</b>.
                    </p>

                    <!-- Rincian Item -->
                    <div class="space-y-3">
                        <h4 class="text-xs font-bold text-gray-900 uppercase tracking-wider">Rincian {{ $totalUnits ?: count($rfq->items ?? []) }} Unit Furnitur:</h4>
                        <div class="overflow-x-auto border border-gray-200 rounded-xl">
                            <table class="w-full text-left text-xs">
                                <thead class="bg-gray-100 text-gray-600 font-bold border-b border-gray-200">
                                    <tr>
                                        <th class="p-3">Item Furnitur</th>
                                        <th class="p-3 text-center">Qty</th>
                                        <th class="p-3">Spesifikasi</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-100">
                                    @forelse(collect($rfq->items ?? []) as $item)
                                        <tr>
                                            <td class="p-3 font-bold text-gray-900">{{ $item['name'] ?? 'Item furnitur' }}</td>
                                            <td class="p-3 text-center font-bold">{{ $item['qty'] ?? 0 }} {{ $item['unit'] ?? 'Unit' }}</td>
                                            <td class="p-3 text-gray-600">{{ $item['note'] ?? '-' }}</td>
                                        </tr>
                                    @empty
                                        <tr><td colspan="3" class="p-4 text-center text-gray-500">Tidak ada rincian item.</td></tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Checklist Inspeksi Fisik -->
                    <div class="space-y-3">
                        <h4 class="text-xs font-bold text-gray-900 uppercase tracking-wider">Ceklis Inspeksi Kelayakan Fisik & Kerapian Finishing:</h4>
                        <div class="border border-gray-200 rounded-xl divide-y divide-gray-100">
                            @foreach($checklist as $index => $row)
                                <label class="flex items-center justify-between gap-4 p-3 text-xs hover:bg-gray-50 cursor-pointer">
                                    <span class="flex items-center space-x-3">
                                        <input type="hidden" name="inspection_checklist[{{ $index }}][checked]" value="0">
                                        <input type="checkbox" name="inspection_checklist[{{ $index }}][checked]" value="1"
                                               @checked(!empty($row['checked']))
                                               class="rounded border-gray-300 text-primary focus:ring-primary">
                                        <input type="hidden" name="inspection_checklist[{{ $index }}][label]" value="{{ $row['label'] }}">
                                        <span class="text-gray-700">{{ $row['label'] }}</span>
                                    </span>
                                    <span class="text-[10px] text-gray-400">Wajib</span>
                                </label>
                            @endforeach
                        </div>
                    </div>

                    <!-- Pernyataan & Upload -->
                    <div class="pt-6 border-t border-gray-100 space-y-4">
                        <h4 class="text-xs font-bold text-gray-900 uppercase tracking-wider">Pernyataan Tanda Tangan & Persetujuan Desainer:</h4>

                        <div class="p-4 bg-emerald-50/50 rounded-xl border border-emerald-200 text-xs text-gray-700 space-y-2">
                            <p class="font-bold text-emerald-900 flex items-center">
                                <i class="fa-solid fa-stamp text-emerald-600 mr-2 text-base"></i> Konfirmasi Penerimaan
                            </p>
                            <p class="text-[11px] leading-relaxed">
                                Dengan menandatangani dokumen ini, saya <b>{{ auth()->user()->full_name ?? 'Desainer' }}</b> menyatakan bahwa seluruh
                                <b>{{ $totalUnits ?: count($rfq->items ?? []) }} unit</b> pesanan mebel telah diterima sesuai spesifikasi gambar CAD, dan menginstruksikan
                                sistem Escrow Platform Decor untuk mentransfer sisa dana <b>Rp {{ number_format($remainingAmount, 0, ',', '.') }}</b>
                                kepada vendor <b>{{ $seller->store_name }}</b>.
                            </p>
                        </div>

                        @if($bast?->signed_at)
                            <div class="grid grid-cols-2 gap-6 text-center text-xs pt-2">
                                <div class="p-4 bg-gray-50 rounded-xl space-y-3">
                                    <p class="text-gray-500">Pihak Produsen (Workshop):</p>
                                    <div class="h-12 flex items-center justify-center font-bold text-primary italic">{{ $seller->store_name }}</div>
                                    <p class="font-bold text-gray-900">{{ $seller->store_name }}</p>
                                </div>
                                <div class="p-4 bg-emerald-50 rounded-xl border border-emerald-300 space-y-3">
                                    <p class="text-emerald-800 font-bold">Pihak Desainer Interior (Penerima):</p>
                                    <div class="h-12 flex items-center justify-center">
                                        @if($bast->designer_signature_url)
                                            <img src="{{ asset('storage/' . $bast->designer_signature_url) }}" alt="Tanda tangan" class="h-12 object-contain">
                                        @else
                                            <span class="font-bold text-emerald-700 italic">✓ Digital Signed</span>
                                        @endif
                                    </div>
                                    <p class="font-bold text-gray-900">{{ auth()->user()->full_name ?? 'Desainer' }}</p>
                                </div>
                            </div>
                            <div class="pt-2 text-center text-xs font-bold {{ $bast->is_accepted ? 'text-emerald-700' : 'text-red-600' }}">
                                {{ $bast->is_accepted ? 'BAST disetujui — dana escrow telah dirilis ke pabrik.' : 'BAST ditandatangani dengan catatan perbaikan.' }}
                                ({{ $bast->signed_at->format('d M Y H:i') }})
                            </div>
                        @else
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-xs">
                                <div class="space-y-1">
                                    <label class="font-bold text-gray-700">Upload Tanda Tangan Digital (PNG/JPG) *</label>
                                    <input type="file" name="signature" accept=".png,.jpg,.jpeg" required class="w-full border border-gray-200 rounded-xl p-2.5">
                                </div>
                                <div class="space-y-1">
                                    <label class="font-bold text-gray-700">Upload Foto Furnitur Terpasang *</label>
                                    <input type="file" name="photo_installed" accept=".jpg,.jpeg,.png" required class="w-full border border-gray-200 rounded-xl p-2.5">
                                </div>
                                <div class="space-y-1 sm:col-span-2">
                                    <label class="font-bold text-gray-700">Keputusan Inspeksi *</label>
                                    <select name="is_accepted" class="w-full border border-gray-200 rounded-xl px-4 py-2.5" required>
                                        <option value="1">Diterima — semua item sesuai & lolos inspeksi</option>
                                        <option value="0">Ditolak — perlu perbaikan oleh pabrik</option>
                                    </select>
                                </div>
                            </div>

                            <div class="pt-2">
                                <button type="submit" class="w-full bg-emerald-600 hover:bg-emerald-700 text-white py-4 rounded-xl font-extrabold text-xs flex items-center justify-center transition-all shadow-lg space-x-2">
                                    <i class="fa-solid fa-signature text-base"></i>
                                    <span>Tandatangani BAST Digital & Rilis Dana Pelunasan Escrow (Rp {{ number_format($remainingAmount, 0, ',', '.') }})</span>
                                </button>
                            </div>
                        @endif
                    </div>
                </form>

                <!-- Kolom Kanan: Foto Pemasangan di Kafe & Sertifikat Garansi -->
                <div id="right-column" class="lg:col-span-4 space-y-6 no-print">
                    <!-- Foto Real Setup di Lokasi -->
                    <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm space-y-4">
                        <div class="flex items-center justify-between">
                            <h4 class="text-xs font-bold text-gray-900 flex items-center">
                                <i class="fa-solid fa-camera-retro text-primary mr-2"></i> Dokumentasi Terpasang di Lokasi
                            </h4>
                            <span class="text-[10px] bg-emerald-100 text-emerald-700 font-bold px-2 py-0.5 rounded-full">{{ $seller->city ?: 'Proyek' }}</span>
                        </div>

                        @if($bast?->photo_installed_url)
                            <div class="space-y-1">
                                <div class="rounded-xl overflow-hidden aspect-video border border-gray-100">
                                    <img src="{{ asset('storage/' . $bast->photo_installed_url) }}" class="w-full h-full object-cover" alt="Foto instalasi">
                                </div>
                                <p class="text-[10px] text-gray-500">Foto diunggah saat BAST ditandatangani {{ $bast->signed_at?->format('d M Y') }}.</p>
                            </div>
                        @else
                            <div class="rounded-xl border-2 border-dashed border-gray-200 p-8 text-center text-xs text-gray-400">
                                <i class="fa-solid fa-image text-3xl mb-2 block"></i>
                                Foto instalasi akan tersimpan otomatis saat Anda menandatangani BAST.
                            </div>
                        @endif
                    </div>

                    <!-- Sertifikat Garansi Resmi -->
                    <div class="bg-gradient-to-br from-[#2C1810] to-[#5C321E] p-6 rounded-2xl text-white space-y-4 shadow-lg">
                        <div class="flex items-center space-x-3">
                            <div class="w-10 h-10 rounded-xl bg-amber-500/20 text-amber-400 flex items-center justify-center text-xl">
                                <i class="fa-solid fa-award"></i>
                            </div>
                            <div>
                                <h4 class="font-bold text-sm">Garansi Resmi</h4>
                                <p class="text-[10px] text-white/70">Terproteksi Platform Decor B2B</p>
                            </div>
                        </div>
                        <p class="text-xs text-white/90 leading-relaxed">
                            {{ $quotation->warranty_notes ?: 'Ketentuan garansi konstruksi kayu sesuai penawaran pabrik.' }}
                            @if($bast?->signed_at)
                                <br>Polis garansi aktif sejak {{ $bast->signed_at->format('d M Y') }}.
                            @endif
                        </p>
                        <div class="pt-2">
                            <a href="{{ route('designer.dashboard') }}" class="w-full bg-primary hover:bg-[#8C4E1E] text-white py-3 rounded-xl font-bold text-xs flex items-center justify-center transition-all shadow-md">
                                <i class="fa-solid fa-table-columns mr-2"></i> Kembali ke Dashboard Desainer
                            </a>
                        </div>
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
