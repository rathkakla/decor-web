<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Decor Seller - Quotation Builder B2B</title>
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
                    <h2 class="font-bold text-xs uppercase tracking-widest leading-none">Penerbitan Quotation</h2>
                    <span class="text-[10px] text-white/80 mt-1">Form Surat Penawaran Resmi Proyek B2B</span>
                </div>
            </div>
            <div class="flex items-center space-x-6">
                <a href="{{ b2b_route('seller.b2b.rfq.review', $rfq) }}" class="text-xs bg-white/20 hover:bg-white/30 text-white px-3 py-1.5 rounded-lg font-bold transition-all flex items-center">
                    <i class="fa-solid fa-arrow-left mr-2"></i> Kembali ke Review Spek
                </a>
            </div>
        </header>

        <div class="p-8 space-y-8 flex-1">
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                <div>
                    <h2 class="text-2xl font-bold tracking-tight text-gray-900">{{ $isEdit ? 'Edit Surat Penawaran Harga' : 'Penerbitan Surat Penawaran Harga (Quotation B2B)' }}</h2>
                    <p class="text-xs text-gray-500 mt-1">Susun kalkulasi harga satuan mebel, ongkos kirim kargo truk peti kayu, dan skema termin pembayaran.</p>
                </div>
                <div class="text-right">
                    <span class="text-[10px] text-gray-400 uppercase tracking-widest font-bold">No. Dokumen:</span>
                    <p class="text-sm font-bold text-primary">{{ $quotation->quotation_number ?? '(Auto-generate saat disimpan)' }}</p>
                </div>
            </div>

            <form action="{{ $isEdit ? b2b_route('seller.b2b.quotation.update', $quotation) : b2b_route('seller.b2b.quotation.store') }}" method="POST" class="space-y-8">
                @csrf
                @if($isEdit)
                    @method('PUT')
                @endif
                <input type="hidden" name="rfq_id" value="{{ $rfq->id }}">

                <!-- Quotation Card -->
                <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-8 space-y-8">
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
                            <table class="w-full text-left border-collapse text-xs">
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
                                                <div class="flex items-center space-x-3">
                                                    <div class="w-10 h-10 rounded-lg bg-primary/10 text-primary flex items-center justify-center font-bold">
                                                        <i class="fa-solid fa-cube"></i>
                                                    </div>
                                                    <div>
                                                        <input type="text" name="items[{{ $index }}][name]" value="{{ $item['name'] ?? '' }}" class="font-bold text-gray-900 w-full border-0 focus:ring-0 bg-transparent" placeholder="Nama item" required>
                                                        <input type="text" name="items[{{ $index }}][note]" value="{{ $item['note'] ?? '' }}" class="text-[10px] text-gray-500 w-full border-0 focus:ring-0 bg-transparent" placeholder="Spesifikasi">
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="py-3 px-4 text-center">
                                                <input type="number" name="items[{{ $index }}][qty]" value="{{ $item['qty'] ?? 1 }}" class="font-bold text-gray-700 w-20 text-center border-0 focus:ring-0 bg-transparent" min="1" required>
                                            </td>
                                            <td class="py-3 px-4">
                                                <input type="number" name="items[{{ $index }}][unit_price]" value="{{ $item['unit_price'] ?? '' }}" class="font-bold text-gray-700 w-full border-0 focus:ring-0 bg-transparent" step="1000" min="0" placeholder="0" required>
                                            </td>
                                            <td class="py-3 px-4 text-right">
                                                <span class="font-bold text-gray-900 item-subtotal" data-index="{{ $index }}">Rp 0</span>
                                            </td>
                                            <td class="py-3 px-2 text-center">
                                                <button type="button" class="remove-item-btn text-gray-300 hover:text-red-500 transition-colors" title="Hapus item">
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
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 pt-4 border-t border-gray-100">
                        <div class="space-y-1">
                            <label class="text-[10px] font-bold text-gray-500 uppercase tracking-wider">Biaya Material (Rp)</label>
                            <input type="number" name="material_cost" id="material_cost" value="{{ $quotation->material_cost ?? '' }}" class="w-full text-sm border border-gray-200 rounded-lg px-3 py-2 focus:outline-none focus:border-primary" step="1000" min="0" required>
                        </div>
                        <div class="space-y-1">
                            <label class="text-[10px] font-bold text-gray-500 uppercase tracking-wider">Biaya Tukang/Pengerjaan (Rp)</label>
                            <input type="number" name="labor_cost" id="labor_cost" value="{{ $quotation->labor_cost ?? '' }}" class="w-full text-sm border border-gray-200 rounded-lg px-3 py-2 focus:outline-none focus:border-primary" step="1000" min="0" required>
                        </div>
                        <div class="space-y-1">
                            <label class="text-[10px] font-bold text-gray-500 uppercase tracking-wider">Biaya Ongkir (Rp)</label>
                            <input type="number" name="shipping_cost" id="shipping_cost" value="{{ $quotation->shipping_cost ?? 0 }}" class="w-full text-sm border border-gray-200 rounded-lg px-3 py-2 focus:outline-none focus:border-primary" step="1000" min="0">
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
                    <div class="pt-6 border-t border-gray-100 flex items-center justify-end space-x-4">
                        <a href="{{ b2b_route('seller.b2b.rfq.review', $rfq) }}" class="px-5 py-3 rounded-xl border border-gray-200 text-gray-700 font-bold text-xs hover:bg-gray-50 transition-all">
                            Kembali
                        </a>
                        <button type="submit" class="bg-primary hover:bg-[#8C4E1E] text-white px-8 py-3.5 rounded-xl font-bold text-xs flex items-center transition-all shadow-md">
                            {{ $isEdit ? 'Perbarui' : 'Terbitkan & Kirim' }} Surat Penawaran Resmi ke Desainer <i class="fa-solid fa-paper-plane ml-2"></i>
                        </button>
                    </div>
                </div>
            </form>
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

        // Quotation calculation logic
        function recalc() {
            let subtotal = 0;
            document.querySelectorAll('.quotation-item-row').forEach(row => {
                const qty = parseFloat(row.querySelector('input[name*="[qty]"]').value) || 0;
                const price = parseFloat(row.querySelector('input[name*="[unit_price]"]').value) || 0;
                const itemSubtotal = qty * price;
                row.querySelector('.item-subtotal').textContent = 'Rp ' + itemSubtotal.toLocaleString('id-ID');
                subtotal += itemSubtotal;
            });

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

        document.querySelectorAll('#quotation-items input').forEach(input => {
            input.addEventListener('input', recalc);
        });
        ['material_cost', 'labor_cost', 'shipping_cost'].forEach(id => {
            document.getElementById(id)?.addEventListener('input', recalc);
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
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 rounded-lg bg-primary/10 text-primary flex items-center justify-center font-bold">
                            <i class="fa-solid fa-cube"></i>
                        </div>
                        <div>
                            <input type="text" name="items[${index}][name]" class="font-bold text-gray-900 w-full border-0 focus:ring-0 bg-transparent" placeholder="Nama item" required>
                            <input type="text" name="items[${index}][note]" class="text-[10px] text-gray-500 w-full border-0 focus:ring-0 bg-transparent" placeholder="Spesifikasi">
                        </div>
                    </div>
                </td>
                <td class="py-3 px-4 text-center">
                    <input type="number" name="items[${index}][qty]" value="1" class="font-bold text-gray-700 w-20 text-center border-0 focus:ring-0 bg-transparent" min="1" required>
                </td>
                <td class="py-3 px-4">
                    <input type="number" name="items[${index}][unit_price]" value="" class="font-bold text-gray-700 w-full border-0 focus:ring-0 bg-transparent" step="1000" min="0" placeholder="0" required>
                </td>
                <td class="py-3 px-4 text-right">
                    <span class="font-bold text-gray-900 item-subtotal" data-index="${index}">Rp 0</span>
                </td>
                <td class="py-3 px-2 text-center">
                    <button type="button" class="remove-item-btn text-gray-300 hover:text-red-500 transition-colors" title="Hapus item">
                        <i class="fa-solid fa-trash-can text-xs"></i>
                    </button>
                </td>
            `;
            tbody.appendChild(tr);
            tr.querySelectorAll('input').forEach(input => input.addEventListener('input', recalc));
            reindexItems();
            recalc();
        });

        // Hapus baris item (minimal 1 baris tersisa).
        document.getElementById('quotation-items')?.addEventListener('click', (event) => {
            const btn = event.target.closest('.remove-item-btn');
            if (!btn) { return; }

            const tbody = document.getElementById('quotation-items');
            if (tbody.querySelectorAll('tr').length <= 1) { return; }

            btn.closest('tr').remove();
            reindexItems();
            recalc();
        });

        // Initial calculation
        recalc();
    </script>
</body>
</html>
