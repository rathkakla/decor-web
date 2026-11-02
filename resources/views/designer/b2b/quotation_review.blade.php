<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Decor Designer - Review Dokumen Penawaran (Quotation) B2B</title>
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
                    <a href="{{ b2b_route('designer.b2b.rfq.create.seller', $quotation->rfq->seller) }}" class="text-white/80 hover:text-white">RFQ #089</a>
                    <span class="text-white/50">/</span>
                    <span class="font-bold text-white">Dokumen Penawaran Resmi Pabrik</span>
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
            $rfq = $quotation->rfq;
            $seller = $rfq->seller;
            $order = $quotation->order;
            $lineItems = collect($quotation->items ?? []);
            if ($lineItems->isEmpty()) {
                $lineItems = collect($rfq->items ?? []);
            }
            $remaining = (float) $quotation->total_price - (float) $quotation->dp_amount;
        @endphp

        <div class="p-8 space-y-6 max-w-6xl">
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

            <!-- Header Summary Status -->
            <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <div>
                    <div class="flex items-center space-x-3 mb-1">
                        @php
                            $qStatus = [
                                'DRAFT' => ['label' => 'Draft Penawaran', 'class' => 'bg-gray-100 text-gray-700'],
                                'SENT' => ['label' => 'Penawaran Resmi Diterima', 'class' => 'bg-emerald-100 text-emerald-800'],
                                'REVISED' => ['label' => 'Penawaran Revisi', 'class' => 'bg-amber-100 text-amber-800'],
                                'ACCEPTED' => ['label' => 'Penawaran Disetujui', 'class' => 'bg-emerald-100 text-emerald-800'],
                                'EXPIRED' => ['label' => 'Penawaran Kedaluwarsa', 'class' => 'bg-red-100 text-red-700'],
                            ][$quotation->status] ?? ['label' => $quotation->status, 'class' => 'bg-gray-100 text-gray-700'];
                        @endphp
                        <span class="{{ $qStatus['class'] }} text-[10px] font-extrabold px-3 py-1 rounded-full uppercase tracking-wider">
                            <i class="fa-solid fa-envelope-open-text mr-1"></i> {{ $qStatus['label'] }}
                        </span>
                        <span class="text-xs text-gray-400">Ref: {{ $quotation->quotation_number }}</span>
                    </div>
                    <h2 class="text-xl font-extrabold text-gray-900">Penawaran Pengadaan Furnitur: {{ $rfq->project_name }}</h2>
                    <p class="text-xs text-gray-500 mt-0.5">
                        Produsen: <b class="text-gray-700">{{ $seller->store_name }}</b>
                        &bull; Berlaku hingga: <b class="text-gray-700">{{ $quotation->valid_until?->format('d M Y') ?? '-' }}</b>
                    </p>
                </div>

                <div class="flex items-center space-x-3">
                    <a href="{{ b2b_route('designer.b2b.negosiasi', $rfq) }}" class="bg-primary hover:bg-[#8C4E1E] text-white px-6 py-3 rounded-xl font-bold text-xs transition-all shadow-md flex items-center">
                        <i class="fa-solid fa-comments mr-2"></i> Negosiasi & Validasi Sampel Kayu
                    </a>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
                <!-- Kolom Kiri: Dokumen Resmi Penawaran (Quotation) -->
                <div class="lg:col-span-8 bg-white p-8 rounded-2xl border border-gray-100 shadow-sm space-y-6">
                    <!-- Kop Dokumen -->
                    <div class="border-b-2 border-primary/20 pb-6 flex items-start justify-between">
                        <div>
                            <span class="text-xs font-bold text-primary uppercase tracking-widest">OFFICIAL QUOTATION</span>
                            <h3 class="text-xl font-extrabold text-gray-900 mt-1">SURAT PENAWARAN HARGA PROYEK</h3>
                            <p class="text-xs text-gray-500 font-mono">No: {{ $quotation->quotation_number }}</p>
                        </div>
                        <div class="text-right">
                            <h4 class="font-extrabold text-sm text-gray-900">{{ $seller->store_name }}</h4>
                            <p class="text-xs text-gray-500">{{ $seller->store_address ? \Illuminate\Support\Str::limit($seller->store_address, 50) : ($seller->city ?: 'Indonesia') }}</p>
                            @if($seller->svlk_verified)
                                <p class="text-[11px] text-emerald-600 font-bold">SVLK Terverifikasi</p>
                            @endif
                        </div>
                    </div>

                    <!-- Ringkasan Proyek & Pemesan -->
                    <div class="grid grid-cols-2 gap-4 text-xs bg-gray-50 p-4 rounded-xl">
                        <div>
                            <p class="text-gray-400 uppercase font-bold text-[10px]">Ditujukan Kepada:</p>
                            <p class="font-bold text-gray-900 mt-1">{{ $rfq->designer->user->full_name ?? 'Desainer' }}</p>
                            <p class="text-gray-600">{{ $rfq->project_name }}</p>
                            <p class="text-gray-500">{{ $rfq->delivery_address ?: '-' }}</p>
                        </div>
                        <div>
                            <p class="text-gray-400 uppercase font-bold text-[10px]">Lead Time & Garansi:</p>
                            <p class="font-bold text-gray-900 mt-1">{{ $quotation->estimated_days }} Hari Kerja</p>
                            <p class="text-gray-600">Garansi: {{ $quotation->warranty_notes ?: '-' }}</p>
                            <p class="text-primary font-bold">
                                Sampel Fisik Kayu:
                                {{ [
                                    'NOT_REQUESTED' => 'Belum diminta',
                                    'PREPARING' => 'Sedang disiapkan pabrik',
                                    'SENT_BY_COURIER' => 'Dikirim via ' . ($rfq->courier_name ?: 'kurir'),
                                    'APPROVED_BY_DESIGNER' => 'Sudah disetujui',
                                ][$rfq->sample_status] ?? $rfq->sample_status }}
                            </p>
                        </div>
                    </div>

                    <!-- Tabel Item Rincian Biaya -->
                    <div>
                        <h4 class="text-xs font-bold text-gray-900 uppercase tracking-wider mb-3">Rincian Item Manufaktur Sesuai BoQ:</h4>
                        <div class="overflow-x-auto border border-gray-100 rounded-xl">
                            <table class="w-full text-left text-xs">
                                <thead class="bg-gray-100 text-gray-600 font-bold border-b border-gray-200">
                                    <tr>
                                        <th class="p-3">Deskripsi Item</th>
                                        <th class="p-3 text-center">Qty</th>
                                        <th class="p-3 text-right">Harga Satuan</th>
                                        <th class="p-3 text-right">Subtotal</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-100">
                                    @forelse($lineItems as $item)
                                        @php
                                            $qty = (int) ($item['qty'] ?? 0);
                                            $unitPrice = isset($item['unit_price']) ? (float) $item['unit_price'] : null;
                                        @endphp
                                        <tr>
                                            <td class="p-3">
                                                <p class="font-bold text-gray-900">{{ $item['name'] ?? 'Item furnitur' }}</p>
                                                @if(!empty($item['note']))
                                                    <p class="text-[10px] text-gray-400">{{ $item['note'] }}</p>
                                                @endif
                                            </td>
                                            <td class="p-3 text-center font-bold">{{ $qty }} {{ $item['unit'] ?? 'Unit' }}</td>
                                            <td class="p-3 text-right text-gray-600">{{ $unitPrice !== null ? 'Rp ' . number_format($unitPrice, 0, ',', '.') : '—' }}</td>
                                            <td class="p-3 text-right font-bold text-gray-900">{{ $unitPrice !== null ? 'Rp ' . number_format($unitPrice * $qty, 0, ',', '.') : '—' }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4" class="p-4 text-center text-gray-500">Tidak ada rincian item pada penawaran ini.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Komponen Biaya -->
                    <div class="border border-gray-100 rounded-xl overflow-hidden text-xs">
                        <div class="flex justify-between px-4 py-2 bg-white"><span class="text-gray-500">Biaya Bahan Baku</span><span class="font-bold text-gray-800">Rp {{ number_format((float) $quotation->material_cost, 0, ',', '.') }}</span></div>
                        <div class="flex justify-between px-4 py-2 bg-gray-50"><span class="text-gray-500">Ongkos Pengerjaan</span><span class="font-bold text-gray-800">Rp {{ number_format((float) $quotation->labor_cost, 0, ',', '.') }}</span></div>
                        <div class="flex justify-between px-4 py-2 bg-white"><span class="text-gray-500">Ongkos Kirim</span><span class="font-bold text-gray-800">Rp {{ number_format((float) $quotation->shipping_cost, 0, ',', '.') }}</span></div>
                        <div class="flex justify-between px-4 py-2 bg-gray-50"><span class="text-gray-500">PPN ({{ rtrim(rtrim(number_format((float) $quotation->tax_percent, 2, ',', '.'), '0'), ',') }}%)</span><span class="font-bold text-gray-800">Rp {{ number_format((float) $quotation->total_price - ((float) $quotation->material_cost + (float) $quotation->labor_cost + (float) $quotation->shipping_cost), 0, ',', '.') }}</span></div>
                        <div class="flex justify-between px-4 py-3 bg-primary/5 border-t border-primary/20">
                            <span class="font-extrabold text-gray-900">TOTAL KONTRAK PENAWARAN</span>
                            <span class="font-extrabold text-primary text-base">Rp {{ number_format((float) $quotation->total_price, 0, ',', '.') }}</span>
                        </div>
                    </div>

                    @if($quotation->notes)
                        <div class="p-4 bg-amber-50/50 rounded-xl border border-amber-100 text-xs text-gray-700 space-y-1">
                            <p class="font-bold text-amber-900"><i class="fa-solid fa-shield-halved mr-1 text-primary"></i> Catatan Vendor:</p>
                            <p>{{ $quotation->notes }}</p>
                        </div>
                    @endif
                </div>

                <!-- Kolom Kanan: Jadwal Termin Escrow & Action Approval -->
                <div class="lg:col-span-4 space-y-6">
                    <!-- Escrow Payment Schedule -->
                    <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm space-y-4">
                        <div class="flex items-center space-x-2 text-primary font-bold text-xs">
                            <i class="fa-solid fa-lock text-base"></i>
                            <span>Jadwal Termin Pembayaran Escrow</span>
                        </div>
                        <p class="text-xs text-gray-500">Dana Anda aman 100% di rekening penampung resmi Decor sampai pekerjaan tervalidasi.</p>

                        <!-- Termin 1 -->
                        <div class="p-4 bg-emerald-50 rounded-xl border border-emerald-200 space-y-2">
                            <div class="flex items-center justify-between">
                                <span class="font-bold text-xs text-emerald-900">Termin 1 (DP {{ $quotation->dp_percentage }}%)</span>
                                <span class="text-emerald-700 font-extrabold text-sm">Rp {{ number_format((float) $quotation->dp_amount, 0, ',', '.') }}</span>
                            </div>
                            <p class="text-[11px] text-emerald-800">
                                Disetor saat Anda menyetujui penawaran ini. Dana ditahan platform hingga produksi berjalan.
                            </p>
                        </div>

                        <!-- Termin 2 -->
                        <div class="p-4 bg-gray-50 rounded-xl border border-gray-200 space-y-2">
                            <div class="flex items-center justify-between">
                                <span class="font-bold text-xs text-gray-800">Termin 2 (Pelunasan {{ 100 - (int) $quotation->dp_percentage }}%)</span>
                                <span class="text-gray-900 font-extrabold text-sm">Rp {{ number_format($remaining, 0, ',', '.') }}</span>
                            </div>
                            <p class="text-[11px] text-gray-500">
                                Ditransfer setelah seluruh unit lulus QC pabrik dan Anda menandatangani BAST fisik.
                            </p>
                        </div>

                        <!-- CTA Button -->
                        <div class="pt-2 space-y-2">
                            @if(in_array($quotation->status, ['SENT', 'REVISED'], true))
                                <form method="POST" action="{{ b2b_route('designer.b2b.quotation.accept', $quotation) }}">
                                    @csrf
                                    <button type="submit" class="w-full bg-primary hover:bg-[#8C4E1E] text-white py-3.5 px-4 rounded-xl font-bold text-xs flex items-center justify-center transition-all shadow-md">
                                        Setujui Penawaran <i class="fa-solid fa-check ml-2"></i>
                                    </button>
                                </form>
                                <p class="text-[10px] text-gray-400 text-center">Menyetujui penawaran akan membuat kontrak order resmi.</p>
                            @elseif($quotation->status === 'ACCEPTED' && $order && $order->payment_status === 'UNPAID')
                                <form method="POST" action="{{ b2b_route('designer.b2b.quotation.pay_dp', $quotation) }}" enctype="multipart/form-data" class="space-y-2">
                                    @csrf
                                    <label class="block text-[10px] font-bold text-gray-600">Bukti Transfer DP (opsional)</label>
                                    <input type="file" name="payment_proof" accept=".jpg,.jpeg,.png,.pdf" class="w-full text-[10px] border border-gray-200 rounded-lg p-2">
                                    <button type="submit" class="w-full bg-emerald-600 hover:bg-emerald-700 text-white py-3.5 px-4 rounded-xl font-bold text-xs flex items-center justify-center transition-all shadow-md">
                                        Bayar DP ke Escrow <i class="fa-solid fa-lock ml-2"></i>
                                    </button>
                                </form>
                            @elseif($order)
                                <a href="{{ b2b_route('designer.b2b.milestone', $order) }}" class="w-full bg-primary hover:bg-[#8C4E1E] text-white py-3.5 px-4 rounded-xl font-bold text-xs flex items-center justify-center transition-all shadow-md">
                                    Pantau Milestone Produksi <i class="fa-solid fa-arrow-right ml-2"></i>
                                </a>
                                <p class="text-[10px] text-gray-400 text-center">
                                    Status escrow: <b>{{ str_replace('_', ' ', $order->payment_status) }}</b>
                                </p>
                            @endif

                            <a href="{{ b2b_route('designer.b2b.katalog') }}" class="w-full bg-gray-100 hover:bg-gray-200 text-gray-700 py-3 px-4 rounded-xl font-bold text-xs flex items-center justify-center transition-all">
                                <i class="fa-solid fa-arrow-left mr-2"></i> Kembali ke Katalog
                            </a>
                        </div>
                    </div>

                    <!-- Profil Workshop -->
                    <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm space-y-3 text-xs">
                        <h4 class="font-bold text-gray-900 uppercase tracking-wider text-[11px]">Kontak PIC Workshop</h4>
                        <div class="flex items-center space-x-3">
                            <img src="{{ $seller->store_image_url }}" class="w-10 h-10 rounded-full object-cover">
                            <div>
                                <p class="font-bold text-gray-900">{{ $seller->store_name }}</p>
                                <p class="text-[10px] text-gray-400">{{ $seller->city ?: 'Indonesia' }} &bull; {{ $seller->phone_number ?: '-' }}</p>
                            </div>
                        </div>
                        @if($seller->store_description)
                            <p class="text-gray-500 text-[11px]">"{{ \Illuminate\Support\Str::limit($seller->store_description, 160) }}"</p>
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
