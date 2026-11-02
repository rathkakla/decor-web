<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Decor Seller - Review Spesifikasi Proyek B2B</title>
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
                    <h2 class="font-bold text-xs uppercase tracking-widest leading-none">Pengadaan Kustom B2B</h2>
                    <span class="text-[10px] text-white/80 mt-1">Review Spesifikasi & Dokumen Gambar Kerja</span>
                </div>
            </div>
            <div class="flex items-center space-x-6">
                <a href="{{ b2b_route('seller.b2b.inbox') }}" class="text-xs bg-white/20 hover:bg-white/30 text-white px-3 py-1.5 rounded-lg font-bold transition-all flex items-center">
                    <i class="fa-solid fa-arrow-left mr-2"></i> Kembali ke Inbox
                </a>
            </div>
        </header>

        <div class="p-8 space-y-8 flex-1">
            <!-- Header Banner -->
            <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm flex flex-col md:flex-row md:items-center justify-between gap-4">
                <div class="flex items-center space-x-4">
                    <div class="w-14 h-14 rounded-2xl bg-orange-50 border border-primary/20 flex items-center justify-center text-primary text-2xl">
                        <i class="fa-solid fa-file-pen"></i>
                    </div>
                    <div>
                        <div class="flex items-center space-x-3">
                            <h2 class="text-xl font-bold text-gray-900">{{ $rfq->project_name }}</h2>
                            <span class="bg-orange-100 text-orange-700 font-bold text-[10px] px-2.5 py-0.5 rounded-full">{{ $rfq->rfq_number }}</span>
                            @php
                                $rfqStatus = [
                                    'PENDING' => ['label' => 'Butuh Penawaran', 'class' => 'bg-amber-100 text-amber-800'],
                                    'QUOTED' => ['label' => 'Sudah Dikutip', 'class' => 'bg-blue-100 text-blue-800'],
                                    'IN_ORDER' => ['label' => 'Sudah Jadi Order', 'class' => 'bg-emerald-100 text-emerald-800'],
                                    'REJECTED' => ['label' => 'Ditolak', 'class' => 'bg-red-100 text-red-700'],
                                    'CANCELLED' => ['label' => 'Dibatalkan', 'class' => 'bg-gray-100 text-gray-600'],
                                ][$rfq->status] ?? ['label' => $rfq->status, 'class' => 'bg-gray-100 text-gray-600'];
                            @endphp
                            <span class="{{ $rfqStatus['class'] }} font-bold text-[10px] px-2.5 py-0.5 rounded-full">{{ $rfqStatus['label'] }}</span>
                        </div>
                        <p class="text-xs text-gray-500 mt-1">Diajukan oleh: <span class="font-bold text-primary">{{ $rfq->designer->user->full_name ?? 'Designer' }}</span> &bull; {{ $rfq->designer->studio_name ?? 'Studio' }} &bull; Batas Respon: {{ $rfq->deadline?->format('d M Y') ?? '-' }}</p>
                    </div>
                </div>
                <div class="flex items-center space-x-3">
                    <span class="text-xs text-gray-500">Estimasi Anggaran Klien:</span>
                    <span class="text-xl font-black text-gray-900">Rp {{ number_format($rfq->budget_ceiling, 0, ',', '.') }}</span>
                </div>
            </div>

            <!-- Two-column Layout -->
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
                <!-- Kiri: Berkas Gambar Kerja & BoQ -->
                <div class="lg:col-span-7 space-y-6">
                    <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm space-y-6">
                        <h3 class="font-bold text-sm text-gray-900 uppercase tracking-wider flex items-center">
                            <i class="fa-solid fa-draw-polygon text-primary mr-2"></i> Gambar Kerja & Visualisasi 3D
                        </h3>

                        <!-- Preview Render / Blueprint -->
                        <div class="relative rounded-xl overflow-hidden border border-gray-200 bg-gray-900 aspect-video group">
                            @if($rfq->cad_url)
                                <a href="{{ asset('storage/' . $rfq->cad_url) }}" target="_blank">
                                    <img src="{{ asset('storage/' . $rfq->cad_url) }}" class="w-full h-full object-cover opacity-85 group-hover:scale-105 transition-transform duration-500">
                                </a>
                            @else
                                <img src="https://images.unsplash.com/photo-1555041469-a586c61ea9bc?auto=format&fit=crop&w=1200&q=80" class="w-full h-full object-cover opacity-85 group-hover:scale-105 transition-transform duration-500">
                            @endif
                            <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-transparent to-transparent flex flex-col justify-end p-6 text-white">
                                <span class="bg-primary/90 text-white text-[10px] font-bold px-2 py-0.5 rounded w-fit mb-1">Gambar Kerja / CAD</span>
                                <h4 class="font-bold text-base">{{ $rfq->project_name }}</h4>
                                <p class="text-xs text-gray-300">Klik untuk memperbesar / unduh</p>
                            </div>
                        </div>

                        <!-- Dokumen Lampiran Teknis -->
                        <div class="space-y-3">
                            <p class="text-xs font-bold text-gray-700">Dokumen Lampiran dari Desainer:</p>
                            
                            @if($rfq->cad_url)
                            <div class="flex items-center justify-between p-3.5 bg-gray-50 rounded-xl border border-gray-200">
                                <div class="flex items-center space-x-3">
                                    <i class="fa-solid fa-file-lines text-blue-600 text-xl"></i>
                                    <div>
                                        <p class="text-xs font-bold text-gray-900">{{ basename($rfq->cad_url) }}</p>
                                        <p class="text-[10px] text-gray-500">Gambar kerja teknis (CAD/DWG/PDF)</p>
                                    </div>
                                </div>
                                <a href="{{ asset('storage/' . $rfq->cad_url) }}" target="_blank" class="text-xs font-bold text-primary hover:underline cursor-pointer"><i class="fa-solid fa-download mr-1"></i> Unduh</a>
                            </div>
                            @endif

                            @if($rfq->boq_url)
                            <div class="flex items-center justify-between p-3.5 bg-gray-50 rounded-xl border border-gray-200">
                                <div class="flex items-center space-x-3">
                                    <i class="fa-solid fa-file-excel text-emerald-600 text-xl"></i>
                                    <div>
                                        <p class="text-xs font-bold text-gray-900">{{ basename($rfq->boq_url) }}</p>
                                        <p class="text-[10px] text-gray-500">Bill of Quantities (Spreadsheet)</p>
                                    </div>
                                </div>
                                <a href="{{ asset('storage/' . $rfq->boq_url) }}" target="_blank" class="text-xs font-bold text-primary hover:underline cursor-pointer"><i class="fa-solid fa-download mr-1"></i> Unduh</a>
                            </div>
                            @endif

                            @if($rfq->moodboard_url)
                            <div class="flex items-center justify-between p-3.5 bg-gray-50 rounded-xl border border-gray-200">
                                <div class="flex items-center space-x-3">
                                    <i class="fa-solid fa-file-image text-amber-500 text-xl"></i>
                                    <div>
                                        <p class="text-xs font-bold text-gray-900">{{ basename($rfq->moodboard_url) }}</p>
                                        <p class="text-[10px] text-gray-500">Moodboard & Skema Finishing</p>
                                    </div>
                                </div>
                                <a href="{{ asset('storage/' . $rfq->moodboard_url) }}" target="_blank" class="text-xs font-bold text-primary hover:underline cursor-pointer"><i class="fa-solid fa-download mr-1"></i> Unduh</a>
                            </div>
                            @endif

                            @if(!$rfq->cad_url && !$rfq->boq_url && !$rfq->moodboard_url)
                            <div class="p-4 bg-gray-50 rounded-xl border border-gray-200 text-center text-xs text-gray-500">
                                Belum ada dokumen lampiran dari desainer.
                            </div>
                            @endif
                        </div>
                    </div>
                    <!-- Rincian Item & Catatan Proyek -->
                    <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm space-y-4">
                        <h3 class="font-bold text-sm text-gray-900 uppercase tracking-wider flex items-center">
                            <i class="fa-solid fa-cubes text-primary mr-2"></i> Rincian Kebutuhan Furnitur
                        </h3>

                        <div class="overflow-x-auto border border-gray-100 rounded-xl">
                            <table class="w-full text-left text-xs">
                                <thead class="bg-gray-50 text-gray-600 font-bold border-b border-gray-200">
                                    <tr>
                                        <th class="p-3">Item Furnitur</th>
                                        <th class="p-3">Spesifikasi / Dimensi</th>
                                        <th class="p-3 text-center">Qty</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-100">
                                    @forelse(collect($rfq->items ?? []) as $item)
                                        <tr>
                                            <td class="p-3 font-bold text-gray-900">{{ $item['name'] ?? 'Item furnitur' }}</td>
                                            <td class="p-3 text-gray-600">{{ $item['note'] ?? '-' }}</td>
                                            <td class="p-3 text-center font-bold text-primary">{{ $item['qty'] ?? 0 }} {{ $item['unit'] ?? 'Unit' }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="3" class="p-4 text-center text-gray-500">Desainer belum melampirkan rincian item.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                                <tfoot class="bg-gray-50 border-t border-gray-200 font-bold text-gray-800">
                                    <tr>
                                        <td colspan="2" class="p-3 text-right">Total Unit Diminta:</td>
                                        <td class="p-3 text-center text-primary">{{ collect($rfq->items ?? [])->sum('qty') }} Unit</td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>

                        @if($rfq->description)
                            <div class="p-4 bg-amber-50/60 border border-amber-100 rounded-xl text-xs text-gray-700 space-y-1">
                                <p class="font-bold text-amber-900"><i class="fa-solid fa-clipboard-check mr-1 text-primary"></i> Standar Mutu & Ketentuan Khusus:</p>
                                <p class="whitespace-pre-line">{{ $rfq->description }}</p>
                            </div>
                        @endif

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 text-xs">
                            <div class="p-3 bg-gray-50 rounded-xl">
                                <p class="text-[9px] text-gray-400 uppercase font-bold">Alamat Pengiriman</p>
                                <p class="font-medium text-gray-800 mt-0.5">{{ $rfq->delivery_address ?: '-' }}</p>
                            </div>
                            <div class="p-3 bg-gray-50 rounded-xl">
                                <p class="text-[9px] text-gray-400 uppercase font-bold">Status Sampel Material</p>
                                <p class="font-bold text-gray-800 mt-0.5">{{ str_replace('_', ' ', $rfq->sample_status) }}</p>
                                @if($rfq->tracking_number)
                                    <p class="text-[10px] text-gray-500">{{ $rfq->courier_name }} &bull; {{ $rfq->tracking_number }}</p>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Kanan: Analisis Workshop & Keputusan -->
                <div class="lg:col-span-5 space-y-6">
                    <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm space-y-6">
                        <h3 class="font-bold text-sm text-gray-900 uppercase tracking-wider flex items-center">
                            <i class="fa-solid fa-calculator text-primary mr-2"></i> Ketersediaan Bahan & Kapasitas
                        </h3>

                        <div class="space-y-4">
                            <div class="p-4 bg-orange-50/50 rounded-xl border border-orange-100">
                                <div class="flex justify-between items-center mb-1">
                                    <span class="text-xs font-bold text-gray-800">{{ $rfq->wood_type }}</span>
                                    <span class="text-xs font-bold text-emerald-600">Stok Siap</span>
                                </div>
                                <p class="text-xs text-gray-600">Jenis kayu yang diminta desainer</p>
                                <p class="text-[10px] text-gray-500">Silakan cek ketersediaan stok di gudang Anda.</p>
                            </div>

                            <div class="p-4 bg-gray-50 rounded-xl border border-gray-100">
                                <div class="flex justify-between items-center mb-1">
                                    <span class="text-xs font-bold text-gray-800">Finishing</span>
                                    <span class="text-xs font-bold text-emerald-600">Tersedia</span>
                                </div>
                                <p class="text-xs text-gray-600">Finishing standar untuk {{ $rfq->wood_type }}</p>
                                <p class="text-[10px] text-gray-500">Sesuaikan dengan spesifikasi desainer.</p>
                            </div>

                            <div class="p-4 bg-gray-50 rounded-xl border border-gray-100">
                                <div class="flex justify-between items-center mb-1">
                                    <span class="text-xs font-bold text-gray-800">Estimasi Waktu Produksi</span>
                                    <span class="text-xs font-bold text-blue-600">{{ $rfq->deadline ? max(0, (int) now()->startOfDay()->diffInDays($rfq->deadline, false)) . ' Hari' : '-' }}</span>
                                </div>
                                <p class="text-xs text-gray-600">Target Desainer: <b>{{ $rfq->deadline?->format('d M Y') ?? '-' }}</b></p>
                                <p class="text-[10px] text-gray-500">Pastikan workshop sanggup mengerjakan tepat waktu.</p>
                            </div>
                        </div>

                        <!-- Kesimpulan & Action Buttons -->
                        <div class="pt-4 border-t border-gray-100 space-y-3">
                            <p class="text-[11px] text-gray-500 italic">Silakan tinjau spesifikasi teknis dan kebutuhan material sebelum membuat penawaran.</p>
                            
                            @if($rfq->status === 'PENDING')
                                <a href="{{ b2b_route('seller.b2b.quotation.create', $rfq) }}" class="w-full bg-primary hover:bg-[#8C4E1E] text-white py-3.5 px-4 rounded-xl font-bold text-xs flex items-center justify-center transition-all shadow-md">
                                    Lanjut ke Pembuatan Penawaran (Quotation) <i class="fa-solid fa-arrow-right ml-2"></i>
                                </a>
                            @elseif($rfq->status === 'QUOTED' && $rfq->latestQuotation)
                                <a href="{{ b2b_route('seller.b2b.quotation.edit', $rfq->latestQuotation) }}" class="w-full bg-primary hover:bg-[#8C4E1E] text-white py-3.5 px-4 rounded-xl font-bold text-xs flex items-center justify-center transition-all shadow-md">
                                    Edit Quotation <i class="fa-solid fa-arrow-right ml-2"></i>
                                </a>
                            @endif

                            @if($rfq->status !== 'REJECTED' && $rfq->status !== 'CANCELLED')
                                <a href="{{ b2b_route('seller.b2b.negosiasi', $rfq) }}" class="w-full bg-white hover:bg-gray-50 text-gray-600 border border-gray-200 py-3 px-4 rounded-xl font-bold text-xs flex items-center justify-center transition-all">
                                    <i class="fa-solid fa-comments mr-2"></i> Diskusikan dengan Desainer
                                </a>
                            @endif
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
