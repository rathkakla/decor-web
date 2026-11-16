<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Decor Designer - Pengajuan RFQ B2B Kustom</title>
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
                    <a href="{{ b2b_route('designer.b2b.katalog') }}" class="text-white/80 hover:text-white hidden sm:inline">Katalog Pabrik</a>
                    <span class="text-white/50 hidden sm:inline">/</span>
                    <span class="font-bold text-white">Formulir Pengajuan RFQ Proyek</span>
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
        <div class="p-4 sm:p-6 lg:p-8 space-y-5 max-w-5xl mx-auto w-full">
            <div>
                <a href="{{ b2b_route('designer.b2b.katalog') }}" class="inline-flex items-center text-xs font-bold text-gray-500 hover:text-primary transition-colors">
                    <i class="fa-solid fa-arrow-left mr-2"></i> Kembali ke Katalog Pabrik
                </a>
            </div>
            <!-- Header Bar -->
            @php
                $sellerInitials = collect(explode(' ', trim($seller->store_name)))->filter()->take(2)->map(fn($w) => strtoupper(substr($w, 0, 1)))->implode('') ?: 'PD';
            @endphp
            <div class="bg-white p-5 sm:p-6 rounded-2xl border border-gray-100 shadow-sm flex flex-col md:flex-row md:items-center justify-between gap-4 text-center md:text-left">
                <div class="space-y-1.5">
                    <span class="inline-block bg-primary/10 text-primary text-[10px] font-bold px-2.5 py-0.5 rounded-full uppercase tracking-wider">
                        Pengajuan RFQ B2B
                    </span>
                    <h2 class="text-lg sm:text-xl font-bold text-gray-900 tracking-tight">Ajukan Pesanan Kustom (RFQ)</h2>
                    <p class="text-xs text-gray-500">Lengkapi detail proyek dan unggah minimal 1 berkas referensi untuk pabrik mitra.</p>
                </div>
                <div class="flex items-center space-x-3 bg-[#FAF1E8]/60 px-4 py-3 rounded-xl border border-primary/20 shrink-0 mx-auto md:mx-0 w-fit text-left">
                    <div class="w-10 h-10 rounded-xl overflow-hidden bg-white border border-primary/15 flex items-center justify-center shrink-0">
                        @if($seller->store_image_url)
                            <img src="{{ $seller->store_image_url }}" class="w-full h-full object-cover" alt="{{ $seller->store_name }}" onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                            <span class="hidden w-full h-full items-center justify-center font-bold text-xs text-primary">{{ $sellerInitials }}</span>
                        @else
                            <span class="w-full h-full flex items-center justify-center font-bold text-xs text-primary">{{ $sellerInitials }}</span>
                        @endif
                    </div>
                    <div class="text-xs whitespace-nowrap">
                        <p class="text-[10px] text-gray-400 uppercase font-semibold tracking-wider">Pabrik Tujuan</p>
                        <p class="font-bold text-gray-900 text-sm leading-tight">{{ $seller->store_name }}</p>
                        <p class="text-[10px] {{ $seller->svlk_verified ? 'text-emerald-700' : 'text-gray-500' }} font-semibold mt-0.5">
                            {{ $seller->svlk_verified ? '✓ SVLK Terverifikasi' : 'Belum terverifikasi SVLK' }}
                        </p>
                    </div>
                </div>
            </div>

            @if($errors->any())
                <div class="bg-red-50 border border-red-200 text-red-700 rounded-2xl p-4 text-xs">
                    <p class="font-bold mb-1"><i class="fa-solid fa-circle-exclamation mr-1"></i> Periksa kembali isian berikut:</p>
                    <ul class="list-disc list-inside space-y-0.5">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form id="rfq-form" action="{{ b2b_route('designer.b2b.rfq.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                @csrf
                <input type="hidden" name="seller_id" value="{{ $seller->id }}">

                <!-- Section 1: Informasi Proyek -->
                <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm space-y-4">
                    <h3 class="text-xs font-bold text-gray-900 uppercase tracking-wider border-b border-gray-100 pb-2">
                        <i class="fa-solid fa-building text-primary mr-2"></i> 1. Informasi Proyek Desain Interior
                    </h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-xs">
                        <div class="space-y-1">
                            <label class="font-bold text-gray-700">Nama Proyek / Klien Komersil *</label>
                            <input type="text" name="project_name" value="{{ old('project_name') }}" placeholder="cth: Kafe Urban Loft Bandung" class="w-full border border-gray-200 rounded-xl px-4 py-2.5 focus:outline-none focus:border-primary font-medium" required>
                        </div>
                        <div class="space-y-1">
                            <label class="font-bold text-gray-700">Estimasi Tanggal Grand Opening / Deadline *</label>
                            <input type="date" name="deadline" value="{{ old('deadline') }}" min="{{ now()->toDateString() }}" class="w-full border border-gray-200 rounded-xl px-4 py-2.5 focus:outline-none focus:border-primary font-medium" required>
                        </div>
                        <div class="space-y-1 md:col-span-2">
                            <label class="font-bold text-gray-700">Alamat Lengkap Pengiriman Proyek *</label>
                            <textarea name="delivery_address" rows="2" class="w-full border border-gray-200 rounded-xl px-4 py-2.5 focus:outline-none focus:border-primary font-medium" required placeholder="Alamat lokasi proyek + catatan akses kendaraan">{{ old('delivery_address') }}</textarea>
                        </div>
                        @php
                            $oldBudgetRaw = old('budget_ceiling') ? (int) preg_replace('/\D/', '', (string) old('budget_ceiling')) : '';
                        @endphp
                        <div class="space-y-1">
                            <label class="font-bold text-gray-700">Estimasi Budget Maksimal (Rp)</label>
                            <div class="flex items-center border border-gray-200 rounded-xl px-4 py-2.5 bg-white focus-within:border-primary transition-colors">
                                <span class="text-xs font-bold text-gray-400 mr-2 select-none">Rp</span>
                                <input type="text" inputmode="numeric" id="budget_ceiling_display" value="{{ $oldBudgetRaw !== '' ? number_format($oldBudgetRaw, 0, ',', '.') : '' }}" placeholder="cth: 90.000.000" class="w-full border-0 focus:outline-none bg-transparent p-0 font-medium text-gray-800">
                                <input type="hidden" name="budget_ceiling" id="budget_ceiling" value="{{ $oldBudgetRaw }}">
                            </div>
                        </div>
                        <div class="space-y-1">
                            <label class="font-bold text-gray-700">Metode Pembayaran Termin</label>
                            <input type="text" value="Escrow Platform (DP 50% + Pelunasan pasca QC)" class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-2.5 text-gray-600 font-medium" readonly>
                        </div>
                    </div>
                </div>

                <!-- Section 2: Upload File CAD, BoQ, dan Moodboard -->
                <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm space-y-4">
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-1 border-b border-gray-100 pb-2">
                        <h3 class="text-xs font-bold text-gray-900 uppercase tracking-wider">
                            <i class="fa-solid fa-file-arrow-up text-primary mr-2"></i> 2. Berkas Spesifikasi &amp; Referensi Desain *
                        </h3>
                        <span class="text-[11px] font-semibold text-primary">Wajib unggah minimal 1 berkas</span>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <label class="p-5 bg-gray-50 border-2 border-dashed border-gray-200 rounded-2xl text-center space-y-3 hover:border-primary transition-all cursor-pointer flex flex-col items-center justify-between">
                            <div class="space-y-1">
                                <i class="fa-solid fa-compass-drafting text-2xl text-primary mb-1"></i>
                                <p class="text-xs font-bold text-gray-800">Gambar Kerja / CAD</p>
                                <p class="text-[10px] text-gray-400">DWG, DXF, PDF &bull; Maks 20 MB</p>
                            </div>
                            <input type="file" name="cad_file" accept=".dwg,.dxf,.pdf" class="rfq-file-input hidden">
                            <span class="rfq-file-label w-full flex items-center justify-center gap-1.5 px-3 py-2 rounded-xl bg-white border border-gray-200 text-[11px] font-bold text-gray-700 hover:border-primary transition-colors">
                                <i class="fa-solid fa-upload text-primary shrink-0"></i>
                                <span class="truncate">Pilih File</span>
                            </span>
                        </label>

                        <label class="p-5 bg-gray-50 border-2 border-dashed border-gray-200 rounded-2xl text-center space-y-3 hover:border-primary transition-all cursor-pointer flex flex-col items-center justify-between">
                            <div class="space-y-1">
                                <i class="fa-solid fa-file-excel text-2xl text-emerald-600 mb-1"></i>
                                <p class="text-xs font-bold text-gray-800">Daftar Item (BoQ)</p>
                                <p class="text-[10px] text-gray-400">XLSX, XLS, CSV &bull; Maks 10 MB</p>
                            </div>
                            <input type="file" name="boq_file" accept=".xlsx,.xls,.csv" class="rfq-file-input hidden">
                            <span class="rfq-file-label w-full flex items-center justify-center gap-1.5 px-3 py-2 rounded-xl bg-white border border-gray-200 text-[11px] font-bold text-gray-700 hover:border-primary transition-colors">
                                <i class="fa-solid fa-upload text-emerald-600 shrink-0"></i>
                                <span class="truncate">Pilih File</span>
                            </span>
                        </label>

                        <label class="p-5 bg-gray-50 border-2 border-dashed border-gray-200 rounded-2xl text-center space-y-3 hover:border-primary transition-all cursor-pointer flex flex-col items-center justify-between">
                            <div class="space-y-1">
                                <i class="fa-solid fa-palette text-2xl text-amber-600 mb-1"></i>
                                <p class="text-xs font-bold text-gray-800">Moodboard &amp; Warna</p>
                                <p class="text-[10px] text-gray-400">PDF, JPG, PNG &bull; Maks 10 MB</p>
                            </div>
                            <input type="file" name="moodboard_file" accept=".pdf,.jpg,.jpeg,.png" class="rfq-file-input hidden">
                            <span class="rfq-file-label w-full flex items-center justify-center gap-1.5 px-3 py-2 rounded-xl bg-white border border-gray-200 text-[11px] font-bold text-gray-700 hover:border-primary transition-colors">
                                <i class="fa-solid fa-upload text-amber-600 shrink-0"></i>
                                <span class="truncate">Pilih File</span>
                            </span>
                        </label>
                    </div>
                </div>

                <!-- Section 3: Rincian Kebutuhan Furnitur -->
                <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm space-y-4">
                    <div class="flex items-center justify-between border-b border-gray-100 pb-2">
                        <h3 class="text-xs font-bold text-gray-900 uppercase tracking-wider">
                            <i class="fa-solid fa-cubes text-primary mr-2"></i> 3. Rincian Item Furnitur yang Dibutuhkan
                        </h3>
                        <button type="button" id="add-item" class="text-[11px] bg-primary/10 text-primary font-bold px-2.5 py-1 rounded-full hover:bg-primary/20 transition-all">
                            <i class="fa-solid fa-plus mr-1"></i> Tambah Item
                        </button>
                    </div>

                    <div class="overflow-x-auto border border-gray-100 rounded-xl">
                        <table class="w-full text-left text-xs min-w-[540px]">
                            <thead class="bg-gray-50 text-gray-600 font-bold border-b border-gray-200">
                                <tr>
                                    <th class="p-3">Item Furnitur</th>
                                    <th class="p-3">Spesifikasi / Dimensi</th>
                                    <th class="p-3 w-28 text-center">Qty</th>
                                    <th class="p-3 w-32">Satuan</th>
                                    <th class="p-3 w-12"></th>
                                </tr>
                            </thead>
                            <tbody id="items-body" class="divide-y divide-gray-100">
                                <tr>
                                    <td class="p-2.5"><input type="text" name="items[0][name]" class="w-full border-0 focus:ring-0 focus:outline-none bg-transparent font-bold text-gray-900" placeholder="cth: Meja Kopi Resto Jati Solid"></td>
                                    <td class="p-2.5"><input type="text" name="items[0][note]" class="w-full border-0 focus:ring-0 focus:outline-none bg-transparent text-gray-600" placeholder="120 x 70 x 75 cm, PU Matte"></td>
                                    <td class="p-2.5 text-center">
                                        <div class="inline-flex items-center border border-gray-200 rounded-lg overflow-hidden bg-white">
                                            <button type="button" class="rfq-qty-minus w-7 h-7 flex items-center justify-center text-gray-500 hover:bg-gray-100 hover:text-gray-900 font-bold transition-colors cursor-pointer select-none">−</button>
                                            <input type="number" name="items[0][qty]" value="1" min="1" class="rfq-qty-input font-bold text-gray-700 w-10 text-center border-0 focus:ring-0 focus:outline-none bg-transparent text-xs p-0">
                                            <button type="button" class="rfq-qty-plus w-7 h-7 flex items-center justify-center text-gray-500 hover:bg-gray-100 hover:text-gray-900 font-bold transition-colors cursor-pointer select-none">+</button>
                                        </div>
                                    </td>
                                    <td class="p-2.5">
                                        <div class="relative">
                                            <select name="items[0][unit]" class="appearance-none w-full bg-gray-50 hover:bg-white border border-gray-200 rounded-lg pl-3 pr-7 py-1.5 text-xs font-semibold text-gray-700 focus:outline-none focus:border-primary transition-colors cursor-pointer">
                                                <option value="unit">Unit</option>
                                                <option value="set">Set</option>
                                                <option value="m2">m²</option>
                                                <option value="meter">Meter</option>
                                            </select>
                                            <i class="fa-solid fa-chevron-down text-[10px] text-gray-400 absolute right-2.5 top-1/2 -translate-y-1/2 pointer-events-none"></i>
                                        </div>
                                    </td>
                                    <td class="p-2.5 text-center">
                                        <button type="button" class="remove-item text-gray-300 hover:text-red-500 hover:bg-red-50 p-1.5 rounded-lg transition-all cursor-pointer" title="Hapus item">
                                            <i class="fa-solid fa-trash-can text-xs"></i>
                                        </button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Section 4: Catatan Khusus & Standar Kualitas -->
                <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm space-y-3">
                    <h3 class="text-xs font-bold text-gray-900 uppercase tracking-wider border-b border-gray-100 pb-2">
                        <i class="fa-solid fa-clipboard-check text-primary mr-2"></i> 4. Standar Mutu & Ketentuan Khusus
                    </h3>
                    <textarea name="description" rows="4" class="w-full text-xs border border-gray-200 rounded-xl p-4 focus:outline-none focus:border-primary font-medium" placeholder="cth: Wajib kayu jati oven kiln-dry MC < 12%, kirim sampel fisik sebelum produksi massal, dll.">{{ old('description') }}</textarea>
                </div>

                <!-- Buttons Footer -->
                <div class="flex justify-end pt-2">
                    <button type="submit" class="w-full sm:w-auto bg-primary hover:bg-[#8C4E1E] text-white px-8 py-3.5 rounded-xl font-bold text-xs transition-all shadow-md flex items-center justify-center cursor-pointer">
                        Kirim RFQ ke Pabrik <i class="fa-solid fa-paper-plane ml-2"></i>
                    </button>
                </div>
            </form>
        </div>
    </main>

    <script>

        // ---- Rincian item dinamis ----
        let itemIndex = 1;
        const itemsBody = document.getElementById('items-body');

        document.getElementById('add-item').addEventListener('click', () => {
            const row = document.createElement('tr');
            row.innerHTML = `
                <td class="p-2.5"><input type="text" name="items[${itemIndex}][name]" class="w-full border-0 focus:ring-0 focus:outline-none bg-transparent font-bold text-gray-900" placeholder="Nama item furnitur"></td>
                <td class="p-2.5"><input type="text" name="items[${itemIndex}][note]" class="w-full border-0 focus:ring-0 focus:outline-none bg-transparent text-gray-600" placeholder="Dimensi & finishing"></td>
                <td class="p-2.5 text-center">
                    <div class="inline-flex items-center border border-gray-200 rounded-lg overflow-hidden bg-white">
                        <button type="button" class="rfq-qty-minus w-7 h-7 flex items-center justify-center text-gray-500 hover:bg-gray-100 hover:text-gray-900 font-bold transition-colors cursor-pointer select-none">−</button>
                        <input type="number" name="items[${itemIndex}][qty]" value="1" min="1" class="rfq-qty-input font-bold text-gray-700 w-10 text-center border-0 focus:ring-0 focus:outline-none bg-transparent text-xs p-0">
                        <button type="button" class="rfq-qty-plus w-7 h-7 flex items-center justify-center text-gray-500 hover:bg-gray-100 hover:text-gray-900 font-bold transition-colors cursor-pointer select-none">+</button>
                    </div>
                </td>
                <td class="p-2.5">
                    <div class="relative">
                        <select name="items[${itemIndex}][unit]" class="appearance-none w-full bg-gray-50 hover:bg-white border border-gray-200 rounded-lg pl-3 pr-7 py-1.5 text-xs font-semibold text-gray-700 focus:outline-none focus:border-primary transition-colors cursor-pointer">
                            <option value="unit">Unit</option>
                            <option value="set">Set</option>
                            <option value="m2">m²</option>
                            <option value="meter">Meter</option>
                        </select>
                        <i class="fa-solid fa-chevron-down text-[10px] text-gray-400 absolute right-2.5 top-1/2 -translate-y-1/2 pointer-events-none"></i>
                    </div>
                </td>
                <td class="p-2.5 text-center">
                    <button type="button" class="remove-item text-gray-300 hover:text-red-500 hover:bg-red-50 p-1.5 rounded-lg transition-all cursor-pointer" title="Hapus item">
                        <i class="fa-solid fa-trash-can text-xs"></i>
                    </button>
                </td>`;
            itemsBody.appendChild(row);
            itemIndex++;
        });

        itemsBody.addEventListener('click', (e) => {
            const minusBtn = e.target.closest('.rfq-qty-minus');
            const plusBtn = e.target.closest('.rfq-qty-plus');
            if (minusBtn || plusBtn) {
                const row = (minusBtn || plusBtn).closest('tr');
                const qtyInput = row?.querySelector('.rfq-qty-input');
                if (!qtyInput) return;
                let val = parseInt(qtyInput.value, 10) || 1;
                if (plusBtn) val += 1;
                else if (minusBtn && val > 1) val -= 1;
                qtyInput.value = val;
                return;
            }

            const btn = e.target.closest('.remove-item');
            if (!btn) return;

            if (itemsBody.querySelectorAll('tr').length <= 1) {
                alert('Tidak dapat menghapus. Daftar rincian harus memiliki minimal 1 item.');
                return;
            }

            const row = btn.closest('tr');
            const itemName = row.querySelector('input[name*="[name]"]')?.value.trim() || 'item ini';
            if (!confirm(`Apakah Anda yakin ingin menghapus "${itemName}" dari rincian item?`)) {
                return;
            }
            row.remove();
        });
        // ---- Format Rupiah Estimasi Budget ----
        const budgetDisplay = document.getElementById('budget_ceiling_display');
        const budgetRaw = document.getElementById('budget_ceiling');
        budgetDisplay?.addEventListener('input', () => {
            let digits = budgetDisplay.value.replace(/\D/g, '').replace(/^0+/, '');
            if (digits === '') {
                budgetDisplay.value = '';
                if (budgetRaw) budgetRaw.value = '';
            } else {
                const num = parseInt(digits, 10);
                budgetDisplay.value = num.toLocaleString('id-ID');
                if (budgetRaw) budgetRaw.value = String(num);
            }
        });

        // ---- Custom file label & validasi minimal 1 berkas (Opsi A) ----
        document.querySelectorAll('.rfq-file-input').forEach(input => {
            input.addEventListener('change', () => {
                const label = input.parentElement?.querySelector('.rfq-file-label');
                if (!label) return;
                if (input.files && input.files.length > 0) {
                    const safeName = input.files[0].name.replace(/</g, '&lt;').replace(/>/g, '&gt;');
                    label.innerHTML = `<i class="fa-solid fa-circle-check text-emerald-600 shrink-0"></i><span class="truncate">${safeName}</span>`;
                    label.classList.add('border-emerald-300', 'bg-emerald-50', 'text-emerald-800');
                } else {
                    label.innerHTML = `<i class="fa-solid fa-upload text-primary shrink-0"></i><span class="truncate">Pilih File</span>`;
                    label.classList.remove('border-emerald-300', 'bg-emerald-50', 'text-emerald-800');
                }
            });
        });

        document.getElementById('rfq-form')?.addEventListener('submit', (e) => {
            const hasFile = Array.from(document.querySelectorAll('.rfq-file-input')).some(inp => inp.files && inp.files.length > 0);
            if (!hasFile) {
                e.preventDefault();
                alert('Harap unggah minimal 1 berkas spesifikasi (Gambar Desain/CAD, BoQ, atau Moodboard) sebelum mengirim RFQ.');
            }
        });
    </script>
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
</body>
</html>
