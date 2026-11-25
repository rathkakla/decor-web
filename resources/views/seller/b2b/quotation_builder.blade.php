<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Decor Seller - Quotation Builder B2B</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    @include('seller.partials.head')
    <style>
        /* Hilangkan panah spinner atas/bawah pada input type number */
        input[type=number]::-webkit-inner-spin-button,
        input[type=number]::-webkit-outer-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }
        input[type=number] {
            -moz-appearance: textfield;
        }
    </style>
</head>
<body class="text-gray-800">

    @include('seller.partials.sidebar')

    <div id="sidebar-backdrop" class="fixed inset-0 bg-black/40 z-40 hidden"></div>

    <main id="main-content" class="flex-1 flex flex-col ml-0 md:ml-64 sidebar-transition min-h-screen">
        <header class="h-16 bg-primary flex items-center justify-between px-4 sm:px-6 lg:px-8 sticky top-0 z-40 shadow-sm text-white">
            <div class="flex items-center">
                <button id="toggle-sidebar" class="text-white hover:opacity-80 mr-4 flex items-center justify-center transition-transform active:scale-95">
                    <i class="fa-solid fa-bars-staggered text-xl"></i>
                </button>
                <div class="flex flex-col">
                    <h2 class="font-bold text-xs uppercase tracking-widest leading-none">Penerbitan Quotation</h2>
                    <span class="text-[10px] text-white/80 mt-1">Form Surat Penawaran Resmi Proyek B2B</span>
                </div>
            </div>
            <div class="flex items-center space-x-3 sm:space-x-6">
                <a href="{{ b2b_route('seller.b2b.rfq.review', $rfq) }}" class="text-xs bg-white/20 hover:bg-white/30 text-white px-2.5 sm:px-3 py-1.5 rounded-lg font-bold transition-all flex items-center">
                    <i class="fa-solid fa-arrow-left sm:mr-2"></i> <span class="hidden sm:inline">Kembali ke Review Spek</span>
                </a>
            </div>
        </header>

        <div class="p-4 sm:p-6 lg:p-8 space-y-6 sm:space-y-8 flex-1">
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                <div>
                    <h2 class="text-xl sm:text-2xl font-bold tracking-tight text-gray-900">{{ $isEdit ? 'Edit Surat Penawaran Harga' : 'Penerbitan Surat Penawaran Harga (Quotation B2B)' }}</h2>
                    <p class="text-xs text-gray-500 mt-1">Susun kalkulasi harga satuan mebel, ongkos kirim kargo truk peti kayu, dan skema termin pembayaran.</p>
                </div>
                <div class="text-left sm:text-right">
                    <span class="text-[10px] text-gray-400 uppercase tracking-widest font-bold">No. Dokumen:</span>
                    <p class="text-sm font-bold text-primary">{{ $quotation->quotation_number ?? '(Auto-generate saat disimpan)' }}</p>
                </div>
            </div>

            <form action="{{ $isEdit ? b2b_route('seller.b2b.quotation.update', $quotation) : b2b_route('seller.b2b.quotation.store') }}" method="POST" class="space-y-6 sm:space-y-8">
                @csrf
                @if($isEdit)
                    @method('PUT')
                @endif
                <input type="hidden" name="rfq_id" value="{{ $rfq->id }}">

                <!-- Quotation Card -->
                <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-4 sm:p-6 lg:p-8 space-y-6 sm:space-y-8">
                    <!-- Info Header Proyek -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 pb-6 border-b border-gray-100 text-xs">
                        <div>
                            <p class="text-[10px] text-gray-400 font-bold uppercase tracking-wider">Penerima Penawaran (Desainer):</p>
                            <p class="text-base font-bold text-gray-900 mt-1">{{ $rfq->designer->user->full_name ?? 'Designer' }}</p>
                            <p class="text-gray-500">{{ $rfq->designer->studio_name ?? 'Studio' }} ({{ $rfq->project_name }})</p>
                            <p class="text-gray-500">Email: {{ $rfq->designer->user->email ?? '-' }}</p>
                        </div>
                        <div>
                            <p class="text-[10px] text-gray-400 font-bold uppercase tracking-wider">Produsen / Seller:</p>
                            <p class="text-base font-bold text-primary mt-1">{{ $rfq->seller->store_name }}</p>
                            <p class="text-gray-500">{{ $rfq->seller->store_address ?? 'Alamat workshop' }}</p>
                            <p class="text-gray-500">Masa Berlaku Quotation: <span class="font-bold text-gray-800">{{ $quotation?->valid_until?->format('d M Y') ?? 's.d. ' . now()->addDays(14)->format('d M Y') }}</span></p>
                        </div>
                    </div>

                    <!-- Table Form Kalkulasi Item -->
                    <div class="space-y-4">
                        <h3 class="font-bold text-xs uppercase tracking-wider text-gray-900 flex items-center">
                            <i class="fa-solid fa-list-check text-primary mr-2"></i> Rincian Harga Satuan Item Mebel Kustom
                        </h3>

                        <div class="overflow-x-auto">
                            <table class="w-full text-left border-collapse text-xs min-w-[650px]">
                                <thead>
                                    <tr class="bg-gray-50 border-b border-gray-200 text-gray-500 font-semibold uppercase text-[10px]">
                                        <th class="py-3 px-4">Deskripsi Item & Spesifikasi</th>
                                        <th class="py-3 px-4 w-28 text-center">Volume (Unit)</th>
                                        <th class="py-3 px-4 w-44">Harga Satuan (Rp)</th>
                                        <th class="py-3 px-4 w-48 text-right">Subtotal (Rp)</th>
                                        <th class="py-3 px-2 w-10"></th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-100 font-medium" id="quotation-items">
                                    @foreach($items as $index => $item)
                                        <tr class="quotation-item-row">
                                            <td class="py-3 px-4">
                                                <div>
                                                    <input type="text" name="items[{{ $index }}][name]" value="{{ $item['name'] ?? '' }}" class="font-bold text-gray-900 w-full border-0 focus:ring-0 bg-transparent p-0" placeholder="Nama item" required>
                                                    <input type="text" name="items[{{ $index }}][note]" value="{{ $item['note'] ?? '' }}" class="text-[10px] text-gray-500 w-full border-0 focus:ring-0 bg-transparent p-0 mt-0.5" placeholder="Spesifikasi">
                                                </div>
                                            </td>
                                            <td class="py-3 px-4 text-center">
                                                <div class="inline-flex items-center border border-gray-200 rounded-lg overflow-hidden bg-white">
                                                    <button type="button" class="qty-minus-btn w-7 h-7 flex items-center justify-center text-gray-500 hover:bg-gray-100 hover:text-gray-900 font-bold transition-colors cursor-pointer select-none" aria-label="Kurangi jumlah">−</button>
                                                    <input type="number" name="items[{{ $index }}][qty]" value="{{ $item['qty'] ?? 1 }}" class="qty-input font-bold text-gray-700 w-10 text-center border-0 focus:ring-0 bg-transparent text-xs p-0" min="1" required>
                                                    <button type="button" class="qty-plus-btn w-7 h-7 flex items-center justify-center text-gray-500 hover:bg-gray-100 hover:text-gray-900 font-bold transition-colors cursor-pointer select-none" aria-label="Tambah jumlah">+</button>
                                                </div>
                                            </td>
                                            <td class="py-3 px-4">
                                                @php
                                                    $rawUnitPrice = isset($item['unit_price']) && (float) $item['unit_price'] > 0 ? (int) round((float) $item['unit_price']) : '';
                                                @endphp
                                                <div class="flex items-center border border-gray-200 rounded-lg px-2.5 py-1.5 bg-white focus-within:border-primary transition-colors">
                                                    <span class="text-[11px] font-bold text-gray-400 mr-1.5 select-none">Rp</span>
                                                    <input type="text" inputmode="numeric" value="{{ $rawUnitPrice !== '' ? number_format($rawUnitPrice, 0, ',', '.') : '' }}" class="rupiah-display font-bold text-gray-700 w-full border-0 focus:ring-0 focus:outline-none bg-transparent p-0 text-xs" placeholder="0" required>
                                                    <input type="hidden" name="items[{{ $index }}][unit_price]" value="{{ $rawUnitPrice }}" class="rupiah-raw">
                                                </div>
                                            </td>
                                            <td class="py-3 px-4 text-right">
                                                <span class="font-bold text-gray-900 item-subtotal" data-index="{{ $index }}">Rp 0</span>
                                            </td>
                                            <td class="py-3 px-2 text-center">
                                                <button type="button" class="remove-item-btn text-gray-300 hover:text-red-500 hover:bg-red-50 p-1.5 rounded-lg transition-all cursor-pointer" title="Hapus item">
                                                    <i class="fa-solid fa-trash-can text-xs"></i>
                                                </button>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="flex justify-end">
                            <button type="button" id="add-item-btn" class="text-xs text-primary hover:underline flex items-center">
                                <i class="fa-solid fa-plus mr-1"></i> Tambah Item
                            </button>
                        </div>
                    </div>

                    <!-- Total Summary Bar (calculated by JS) -->
                    <div class="bg-orange-50/60 p-6 rounded-2xl border border-primary/20 flex flex-col md:flex-row md:items-center justify-between gap-4">
                        <div>
                            <span class="text-xs font-bold text-primary uppercase tracking-wider">TOTAL NILAI KONTRAK PENGADAAN B2B:</span>
                            <p class="text-xs text-gray-500 mt-0.5">Sudah termasuk PPN 11%, packing peti kayu proteksi, dan asuransi kargo jalan raya.</p>
                        </div>
                        <div class="text-right">
                            <span class="text-3xl font-black text-gray-900" id="grand-total-display">Rp 0</span>
                            <input type="hidden" name="total_price" id="total_price" value="0">
                        </div>
                    </div>

                    <!-- Biaya Detail Inputs -->
                    @php
                        $rawMaterial = isset($quotation->material_cost) && (float) $quotation->material_cost > 0 ? (int) round((float) $quotation->material_cost) : '';
                        $rawLabor = isset($quotation->labor_cost) && (float) $quotation->labor_cost > 0 ? (int) round((float) $quotation->labor_cost) : '';

                        // Estimasi ongkir otomatis: bandingkan kota seller dengan alamat pengiriman.
                        $sellerCity = strtolower(trim((string) ($rfq->seller->city ?? '')));
                        $sellerCityFirst = $sellerCity !== '' ? explode(' ', $sellerCity)[0] : '';
                        $deliveryAddr = strtolower(trim((string) ($rfq->delivery_address ?? '')));
                        $isSameCity = $sellerCityFirst !== '' && $deliveryAddr !== '' && str_contains($deliveryAddr, $sellerCityFirst);
                        $initialUnits = max(1, (int) collect($items)->sum('qty'));
                        $autoShipping = $isSameCity ? (150000 + ($initialUnits * 25000)) : (350000 + ($initialUnits * 50000));
                        $rawShipping = ($isEdit && isset($quotation->shipping_cost)) ? (int) round((float) $quotation->shipping_cost) : $autoShipping;
                    @endphp
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 pt-4 border-t border-gray-100">
                        <div class="space-y-1">
                            <label class="text-[10px] font-bold text-gray-500 uppercase tracking-wider">Biaya Material (Rp)</label>
                            <div class="flex items-center border border-gray-200 rounded-lg px-3 py-2 bg-white focus-within:border-primary transition-colors">
                                <span class="text-xs font-bold text-gray-400 mr-2 select-none">Rp</span>
                                <input type="text" inputmode="numeric" id="material_cost_display" value="{{ $rawMaterial !== '' ? number_format($rawMaterial, 0, ',', '.') : '' }}" class="rupiah-display w-full text-sm font-semibold text-gray-800 border-0 focus:outline-none bg-transparent p-0" placeholder="0" required>
                                <input type="hidden" name="material_cost" id="material_cost" value="{{ $rawMaterial }}" class="rupiah-raw" data-manual="{{ $isEdit ? '1' : '0' }}">
                            </div>
                        </div>
                        <div class="space-y-1">
                            <label class="text-[10px] font-bold text-gray-500 uppercase tracking-wider">Biaya Tukang/Pengerjaan (Rp)</label>
                            <div class="flex items-center border border-gray-200 rounded-lg px-3 py-2 bg-white focus-within:border-primary transition-colors">
                                <span class="text-xs font-bold text-gray-400 mr-2 select-none">Rp</span>
                                <input type="text" inputmode="numeric" id="labor_cost_display" value="{{ $rawLabor !== '' ? number_format($rawLabor, 0, ',', '.') : '' }}" class="rupiah-display w-full text-sm font-semibold text-gray-800 border-0 focus:outline-none bg-transparent p-0" placeholder="0" required>
                                <input type="hidden" name="labor_cost" id="labor_cost" value="{{ $rawLabor }}" class="rupiah-raw">
                            </div>
                        </div>
                        <div class="space-y-1">
                            <div class="flex items-center justify-between gap-2">
                                <label class="text-[10px] font-bold text-gray-500 uppercase tracking-wider">Biaya Ongkir (Rp)</label>
                                <span class="text-[9px] font-bold text-primary bg-primary/10 px-1.5 py-0.5 rounded whitespace-nowrap">{{ $isSameCity ? 'Auto • Dalam Kota' : 'Auto • Antar Kota' }}</span>
                            </div>
                            <div class="flex items-center border border-gray-200 rounded-lg px-3 py-2 bg-white focus-within:border-primary transition-colors">
                                <span class="text-xs font-bold text-gray-400 mr-2 select-none">Rp</span>
                                <input type="text" inputmode="numeric" id="shipping_cost_display" value="{{ $rawShipping !== '' ? number_format($rawShipping, 0, ',', '.') : '' }}" class="rupiah-display w-full text-sm font-semibold text-gray-800 border-0 focus:outline-none bg-transparent p-0" placeholder="0">
                                <input type="hidden" name="shipping_cost" id="shipping_cost" value="{{ $rawShipping !== '' ? $rawShipping : 0 }}" class="rupiah-raw" data-default-zero="1" data-manual="{{ $isEdit ? '1' : '0' }}" data-same-city="{{ $isSameCity ? '1' : '0' }}">
                            </div>
                            <p class="text-[10px] text-gray-400" id="shipping-hint">Estimasi otomatis ({{ $initialUnits }} unit), dapat disesuaikan.</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div class="space-y-1">
                            <label class="text-[10px] font-bold text-gray-500 uppercase tracking-wider">PPN (%)</label>
                            <div class="w-full text-sm border border-gray-200 rounded-lg px-3 py-2 bg-gray-50 text-gray-500 font-bold flex items-center justify-between">
                                <span>11%</span>
                                <span class="text-[9px] font-bold uppercase tracking-wider text-gray-400">Tetap</span>
                            </div>
                        </div>
                        <div class="space-y-1">
                            <label class="text-[10px] font-bold text-gray-500 uppercase tracking-wider">DP (%)</label>
                            <div class="w-full text-sm border border-gray-200 rounded-lg px-3 py-2 bg-gray-50 text-gray-500 font-bold flex items-center justify-between">
                                <span>50%</span>
                                <span class="text-[9px] font-bold uppercase tracking-wider text-gray-400">Tetap</span>
                            </div>
                        </div>
                        <div class="space-y-1">
                            <label class="text-[10px] font-bold text-gray-500 uppercase tracking-wider">Estimasi Pengerjaan (Hari)</label>
                            <input type="number" name="estimated_days" id="estimated_days" value="{{ $quotation->estimated_days ?? '' }}" class="w-full text-sm border border-gray-200 rounded-lg px-3 py-2 focus:outline-none focus:border-primary" min="1" required>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="space-y-1">
                            <label class="text-[10px] font-bold text-gray-500 uppercase tracking-wider">Masa Berlaku Quotation</label>
                            <input type="date" name="valid_until" id="valid_until" value="{{ $quotation?->valid_until?->format('Y-m-d') ?? now()->addDays(14)->format('Y-m-d') }}" class="w-full text-sm border border-gray-200 rounded-lg px-3 py-2 focus:outline-none focus:border-primary" required>
                        </div>
                        <div class="space-y-1 md:col-span-2">
                            <label class="text-[10px] font-bold text-gray-500 uppercase tracking-wider">Ketentuan Garansi</label>
                            <input type="text" name="warranty_notes" value="{{ $quotation->warranty_notes ?? 'Garansi konstruksi 12 bulan, garansi finishing 6 bulan.' }}" class="w-full text-sm border border-gray-200 rounded-lg px-3 py-2 focus:outline-none focus:border-primary" maxlength="255">
                        </div>
                    </div>

                    <div class="space-y-1">
                        <label class="text-[10px] font-bold text-gray-500 uppercase tracking-wider">Catatan Tambahan</label>
                        <textarea name="notes" rows="3" class="w-full text-sm border border-gray-200 rounded-lg px-3 py-2 focus:outline-none focus:border-primary">{{ $quotation->notes ?? '' }}</textarea>
                    </div>

                    <!-- Skema Termin Pembayaran Escrow (Auto-calculated) -->
                    <div class="space-y-4 pt-4 border-t border-gray-100">
                        <h3 class="font-bold text-xs uppercase tracking-wider text-gray-900 flex items-center">
                            <i class="fa-solid fa-shield-halved text-primary mr-2"></i> Skema Termin Pembayaran via Rekening Escrow Platform
                        </h3>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="p-5 rounded-xl border-2 border-primary bg-primary/5 space-y-2">
                                <span class="bg-primary text-white text-[10px] font-bold px-2.5 py-0.5 rounded-full">Termin 1 (Uang Muka)</span>
                                <div class="flex justify-between items-center">
                                    <h4 class="font-bold text-sm text-gray-900">DP 50% Sebelum Produksi</h4>
                                    <span class="text-lg font-bold text-primary" id="dp-amount-display">Rp 0</span>
                                </div>
                                <p class="text-xs text-gray-600">Disetor ke Escrow Decor sebelum workshop mulai memotong kayu.</p>
                                <input type="hidden" name="dp_amount" id="dp_amount" value="0">
                            </div>

                            <div class="p-5 rounded-xl border border-gray-200 bg-gray-50 space-y-2">
                                <span class="bg-gray-200 text-gray-700 text-[10px] font-bold px-2.5 py-0.5 rounded-full">Termin 2 (Pelunasan)</span>
                                <div class="flex justify-between items-center">
                                    <h4 class="font-bold text-sm text-gray-900">Pelunasan 50% Pasca BAST</h4>
                                    <span class="text-lg font-bold text-gray-900" id="remaining-amount-display">Rp 0</span>
                                </div>
                                <p class="text-xs text-gray-600">Dicairkan ke rekening seller setelah barang diterima & diinspeksi desainer.</p>
                            </div>
                        </div>
                    </div>

                    <!-- Action Button -->
                    <div class="pt-6 border-t border-gray-100 flex flex-col sm:flex-row items-stretch sm:items-center justify-end gap-3">
                        <a href="{{ b2b_route('seller.b2b.rfq.review', $rfq) }}" class="px-5 py-3 rounded-xl border border-gray-200 text-gray-700 font-bold text-xs hover:bg-gray-50 transition-all text-center">
                            Kembali
                        </a>
                        <button type="submit" class="bg-primary hover:bg-[#8C4E1E] text-white px-6 sm:px-8 py-3.5 rounded-xl font-bold text-xs flex items-center justify-center transition-all shadow-md">
                            {{ $isEdit ? 'Perbarui' : 'Terbitkan & Kirim' }} Surat Penawaran Resmi ke Desainer <i class="fa-solid fa-paper-plane ml-2"></i>
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </main>

    <script>

        // Format input rupiah (titik ribuan, tanpa leading zero yang bandel)
        function syncRupiahInput(displayInput) {
            const container = displayInput.parentElement;
            const rawInput = container?.querySelector('.rupiah-raw');
            let digits = displayInput.value.replace(/\D/g, '');
            digits = digits.replace(/^0+/, '');

            if (digits === '') {
                displayInput.value = '';
                if (rawInput) {
                    rawInput.value = rawInput.dataset.defaultZero === '1' ? '0' : '';
                }
            } else {
                const num = parseInt(digits, 10);
                displayInput.value = num.toLocaleString('id-ID');
                if (rawInput) {
                    rawInput.value = String(num);
                }
            }
        }

        // Quotation calculation logic
        function recalc() {
            let subtotal = 0;
            let totalUnits = 0;
            document.querySelectorAll('.quotation-item-row').forEach(row => {
                const qty = parseFloat(row.querySelector('input[name*="[qty]"]')?.value) || 0;
                const price = parseFloat(row.querySelector('input[name*="[unit_price]"]')?.value) || 0;
                const itemSubtotal = qty * price;
                row.querySelector('.item-subtotal').textContent = 'Rp ' + itemSubtotal.toLocaleString('id-ID');
                subtotal += itemSubtotal;
                totalUnits += qty;
            });

            // Auto-sync Biaya Material dari subtotal item (kecuali diedit manual)
            const materialRaw = document.getElementById('material_cost');
            const materialDisplay = document.getElementById('material_cost_display');
            if (materialRaw && materialDisplay && materialRaw.dataset.manual !== '1') {
                materialRaw.value = subtotal > 0 ? String(Math.round(subtotal)) : '';
                materialDisplay.value = subtotal > 0 ? Math.round(subtotal).toLocaleString('id-ID') : '';
            }

            // Auto-estimasi ongkir berdasarkan jumlah unit (kecuali diedit manual)
            const shippingRaw = document.getElementById('shipping_cost');
            const shippingDisplay = document.getElementById('shipping_cost_display');
            const shippingHint = document.getElementById('shipping-hint');
            if (shippingRaw && shippingDisplay && shippingRaw.dataset.manual !== '1') {
                const units = Math.max(1, Math.round(totalUnits));
                const sameCity = shippingRaw.dataset.sameCity === '1';
                const autoShip = sameCity ? (150000 + units * 25000) : (350000 + units * 50000);
                shippingRaw.value = String(autoShip);
                shippingDisplay.value = autoShip.toLocaleString('id-ID');
                if (shippingHint) {
                    shippingHint.textContent = `Estimasi otomatis (${units} unit), dapat disesuaikan.`;
                }
            }

            const material = parseFloat(document.getElementById('material_cost').value) || 0;
            const labor = parseFloat(document.getElementById('labor_cost').value) || 0;
            const shipping = parseFloat(document.getElementById('shipping_cost').value) || 0;
            const taxPercent = 11;
            const dpPercent = 50;

            const costSubtotal = material + labor + shipping;
            const tax = costSubtotal * (taxPercent / 100);
            const total = costSubtotal + tax;
            const dpAmount = total * (dpPercent / 100);
            const remaining = total - dpAmount;

            document.getElementById('grand-total-display').textContent = 'Rp ' + total.toLocaleString('id-ID');
            document.getElementById('total_price').value = total.toFixed(2);
            document.getElementById('dp-amount-display').textContent = 'Rp ' + dpAmount.toLocaleString('id-ID');
            document.getElementById('dp_amount').value = dpAmount.toFixed(2);
            document.getElementById('remaining-amount-display').textContent = 'Rp ' + remaining.toLocaleString('id-ID');
        }

        // Delegated input listener for all rupiah-display & qty inputs
        document.addEventListener('input', (event) => {
            if (event.target.classList.contains('rupiah-display')) {
                if (event.target.id === 'material_cost_display') {
                    document.getElementById('material_cost').dataset.manual = '1';
                }
                if (event.target.id === 'shipping_cost_display') {
                    document.getElementById('shipping_cost').dataset.manual = '1';
                }
                syncRupiahInput(event.target);
                recalc();
            } else if (event.target.classList.contains('qty-input')) {
                recalc();
            }
        });

        // Stepper + / - volume item ala Shopee
        document.getElementById('quotation-items')?.addEventListener('click', (event) => {
            const minusBtn = event.target.closest('.qty-minus-btn');
            const plusBtn = event.target.closest('.qty-plus-btn');
            if (!minusBtn && !plusBtn) { return; }

            const row = (minusBtn || plusBtn).closest('tr');
            const qtyInput = row?.querySelector('.qty-input');
            if (!qtyInput) { return; }

            let current = parseInt(qtyInput.value, 10) || 1;
            if (plusBtn) {
                current += 1;
            } else if (minusBtn && current > 1) {
                current -= 1;
            }
            qtyInput.value = current;
            recalc();
        });

        function reindexItems() {
            document.querySelectorAll('#quotation-items .quotation-item-row').forEach((row, index) => {
                row.querySelectorAll('input[name]').forEach(input => {
                    input.name = input.name.replace(/items\[\d+\]/, `items[${index}]`);
                });
                const subtotal = row.querySelector('.item-subtotal');
                if (subtotal) { subtotal.dataset.index = index; }
            });
        }

        document.getElementById('add-item-btn')?.addEventListener('click', () => {
            const tbody = document.getElementById('quotation-items');
            const index = tbody.querySelectorAll('tr').length;
            const tr = document.createElement('tr');
            tr.className = 'quotation-item-row';
            tr.innerHTML = `
                <td class="py-3 px-4">
                    <div>
                        <input type="text" name="items[${index}][name]" class="font-bold text-gray-900 w-full border-0 focus:ring-0 bg-transparent p-0" placeholder="Nama item" required>
                        <input type="text" name="items[${index}][note]" class="text-[10px] text-gray-500 w-full border-0 focus:ring-0 bg-transparent p-0 mt-0.5" placeholder="Spesifikasi">
                    </div>
                </td>
                <td class="py-3 px-4 text-center">
                    <div class="inline-flex items-center border border-gray-200 rounded-lg overflow-hidden bg-white">
                        <button type="button" class="qty-minus-btn w-7 h-7 flex items-center justify-center text-gray-500 hover:bg-gray-100 hover:text-gray-900 font-bold transition-colors cursor-pointer select-none" aria-label="Kurangi jumlah">−</button>
                        <input type="number" name="items[${index}][qty]" value="1" class="qty-input font-bold text-gray-700 w-10 text-center border-0 focus:ring-0 bg-transparent text-xs p-0" min="1" required>
                        <button type="button" class="qty-plus-btn w-7 h-7 flex items-center justify-center text-gray-500 hover:bg-gray-100 hover:text-gray-900 font-bold transition-colors cursor-pointer select-none" aria-label="Tambah jumlah">+</button>
                    </div>
                </td>
                <td class="py-3 px-4">
                    <div class="flex items-center border border-gray-200 rounded-lg px-2.5 py-1.5 bg-white focus-within:border-primary transition-colors">
                        <span class="text-[11px] font-bold text-gray-400 mr-1.5 select-none">Rp</span>
                        <input type="text" inputmode="numeric" value="" class="rupiah-display font-bold text-gray-700 w-full border-0 focus:ring-0 focus:outline-none bg-transparent p-0 text-xs" placeholder="0" required>
                        <input type="hidden" name="items[${index}][unit_price]" value="" class="rupiah-raw">
                    </div>
                </td>
                <td class="py-3 px-4 text-right">
                    <span class="font-bold text-gray-900 item-subtotal" data-index="${index}">Rp 0</span>
                </td>
                <td class="py-3 px-2 text-center">
                    <button type="button" class="remove-item-btn text-gray-300 hover:text-red-500 hover:bg-red-50 p-1.5 rounded-lg transition-all cursor-pointer" title="Hapus item">
                        <i class="fa-solid fa-trash-can text-xs"></i>
                    </button>
                </td>
            `;
            tbody.appendChild(tr);
            reindexItems();
            recalc();
        });

        // Hapus baris item (minimal 1 baris tersisa) dengan dialog konfirmasi
        document.getElementById('quotation-items')?.addEventListener('click', (event) => {
            const btn = event.target.closest('.remove-item-btn');
            if (!btn) { return; }

            const tbody = document.getElementById('quotation-items');
            if (tbody.querySelectorAll('tr').length <= 1) {
                alert('Tidak dapat menghapus. Penawaran harus memiliki minimal 1 item.');
                return;
            }

            const row = btn.closest('tr');
            const itemName = row.querySelector('input[name*="[name]"]')?.value.trim() || 'item ini';

            if (!confirm(`Apakah Anda yakin ingin menghapus "${itemName}" dari rincian penawaran?`)) {
                return;
            }

            row.remove();
            reindexItems();
            recalc();
        });

        // Initial calculation
        recalc();
    </script>
<script async defer data-website-id="cd56ccb0-617d-4bec-8e77-0ba7ea566e9a" data-host-url="/umami-analytics" src="/umami-analytics/script.js"></script>
</body>
</html>
