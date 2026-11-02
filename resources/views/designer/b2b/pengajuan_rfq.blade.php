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
                    <a href="{{ b2b_route('designer.b2b.katalog') }}" class="text-white/80 hover:text-white">Katalog Pabrik</a>
                    <span class="text-white/50">/</span>
                    <span class="font-bold text-white">Formulir Pengajuan RFQ Proyek</span>
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
        <div class="p-8 space-y-6 max-w-5xl">
            <!-- Header Bar -->
            <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <div>
                    <span class="bg-primary/10 text-primary text-[10px] font-extrabold px-3 py-1 rounded-full uppercase tracking-wider">
                        Tahap 1: Pengajuan Kebutuhan Kustom
                    </span>
                    <h2 class="text-xl font-extrabold text-gray-900 mt-2">Request for Quotation (RFQ) Furnitur Komersil</h2>
                    <p class="text-xs text-gray-500 mt-0.5">Lengkapi gambar kerja CAD, rincian BoQ, dan spesifikasi teknis untuk pabrik mitra</p>
                </div>
                <div class="flex items-center space-x-3 bg-amber-50 p-3 rounded-xl border border-amber-200">
                    <img src="{{ $seller->store_image_url }}" class="w-10 h-10 rounded-xl object-cover" alt="{{ $seller->store_name }}">
                    <div class="text-xs">
                        <p class="text-[10px] text-gray-400 uppercase font-bold">Pabrik Tujuan:</p>
                        <p class="font-bold text-gray-900">{{ $seller->store_name }}</p>
                        <p class="text-[10px] {{ $seller->svlk_verified ? 'text-emerald-700' : 'text-gray-500' }} font-bold">
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

            <form action="{{ b2b_route('designer.b2b.rfq.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
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
                        <div class="space-y-1">
                            <label class="font-bold text-gray-700">Pagu Anggaran Maksimal (Rp)</label>
                            <input type="number" name="budget_ceiling" value="{{ old('budget_ceiling') }}" min="0" step="1000" placeholder="cth: 90000000" class="w-full border border-gray-200 rounded-xl px-4 py-2.5 focus:outline-none focus:border-primary font-medium">
                        </div>
                        <div class="space-y-1">
                            <label class="font-bold text-gray-700">Jenis Kayu Utama *</label>
                            <select name="wood_type" class="w-full border border-gray-200 rounded-xl px-4 py-2.5 focus:outline-none focus:border-primary font-medium" required>
                                <option value="">-- Pilih material --</option>
                                @foreach(\App\Models\B2bRfq::WOOD_TYPES as $wood)
                                    <option value="{{ $wood }}" @selected(old('wood_type') === $wood)>{{ $wood }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="space-y-1 md:col-span-2">
                            <label class="font-bold text-gray-700">Metode Pembayaran Termin</label>
                            <input type="text" value="Escrow Platform (DP sesuai penawaran + Pelunasan pasca QC)" class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-2.5 text-gray-600 font-medium" readonly>
                        </div>
                    </div>
                </div>

                <!-- Section 2: Upload File CAD, BoQ, dan Moodboard -->
                <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm space-y-4">
                    <h3 class="text-xs font-bold text-gray-900 uppercase tracking-wider border-b border-gray-100 pb-2">
                        <i class="fa-solid fa-file-arrow-up text-primary mr-2"></i> 2. Berkas Spesifikasi Teknis & Gambar Kerja
                    </h3>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <label class="p-4 bg-gray-50 border-2 border-dashed border-gray-200 rounded-2xl text-center space-y-2 hover:border-primary transition-all cursor-pointer block">
                            <i class="fa-solid fa-compass-drafting text-2xl text-primary"></i>
                            <div>
                                <p class="text-xs font-bold text-gray-800">Gambar Kerja CAD / 3D</p>
                                <p class="text-[10px] text-gray-400">DWG, DXF, atau PDF (maks 20 MB)</p>
                            </div>
                            <input type="file" name="cad_file" accept=".dwg,.dxf,.pdf" class="w-full text-[10px] text-gray-500">
                        </label>

                        <label class="p-4 bg-gray-50 border-2 border-dashed border-gray-200 rounded-2xl text-center space-y-2 hover:border-primary transition-all cursor-pointer block">
                            <i class="fa-solid fa-file-excel text-2xl text-emerald-600"></i>
                            <div>
                                <p class="text-xs font-bold text-gray-800">Bill of Quantities (BoQ)</p>
                                <p class="text-[10px] text-gray-400">XLSX, XLS, CSV (maks 10 MB)</p>
                            </div>
                            <input type="file" name="boq_file" accept=".xlsx,.xls,.csv" class="w-full text-[10px] text-gray-500">
                        </label>

                        <label class="p-4 bg-gray-50 border-2 border-dashed border-gray-200 rounded-2xl text-center space-y-2 hover:border-primary transition-all cursor-pointer block">
                            <i class="fa-solid fa-palette text-2xl text-amber-600"></i>
                            <div>
                                <p class="text-xs font-bold text-gray-800">Moodboard & Skema Finishing</p>
                                <p class="text-[10px] text-gray-400">PDF / JPG (maks 10 MB)</p>
                            </div>
                            <input type="file" name="moodboard_file" accept=".pdf,.jpg,.jpeg,.png" class="w-full text-[10px] text-gray-500">
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
                        <table class="w-full text-left text-xs">
                            <thead class="bg-gray-50 text-gray-600 font-bold border-b border-gray-200">
                                <tr>
                                    <th class="p-3">Item Furnitur</th>
                                    <th class="p-3">Spesifikasi / Dimensi</th>
                                    <th class="p-3 w-20 text-center">Qty</th>
                                    <th class="p-3 w-24">Satuan</th>
                                    <th class="p-3 w-10"></th>
                                </tr>
                            </thead>
                            <tbody id="items-body" class="divide-y divide-gray-100">
                                <tr>
                                    <td class="p-2"><input type="text" name="items[0][name]" class="w-full border-0 focus:ring-0 bg-transparent font-bold text-gray-900" placeholder="cth: Meja Kopi Resto Jati Solid"></td>
                                    <td class="p-2"><input type="text" name="items[0][note]" class="w-full border-0 focus:ring-0 bg-transparent text-gray-600" placeholder="120 x 70 x 75 cm, PU Matte"></td>
                                    <td class="p-2"><input type="number" name="items[0][qty]" value="1" min="1" class="w-full border-0 focus:ring-0 bg-transparent text-center font-bold text-primary"></td>
                                    <td class="p-2">
                                        <select name="items[0][unit]" class="w-full border-0 focus:ring-0 bg-transparent text-gray-600">
                                            <option value="unit">Unit</option>
                                            <option value="set">Set</option>
                                            <option value="m2">m²</option>
                                            <option value="meter">Meter</option>
                                        </select>
                                    </td>
                                    <td class="p-2 text-center">
                                        <button type="button" class="remove-item text-gray-300 hover:text-red-500"><i class="fa-solid fa-trash"></i></button>
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
                <div class="flex items-center justify-between pt-2">
                    <a href="{{ b2b_route('designer.b2b.katalog') }}" class="px-5 py-3 border border-gray-200 rounded-xl text-xs font-bold text-gray-600 hover:bg-gray-50 transition-all flex items-center">
                        <i class="fa-solid fa-arrow-left mr-2"></i> Kembali ke Katalog Pabrik
                    </a>
                    <button type="submit" class="bg-primary hover:bg-[#8C4E1E] text-white px-8 py-3.5 rounded-xl font-bold text-xs transition-all shadow-md flex items-center">
                        Kirim RFQ ke Pabrik <i class="fa-solid fa-paper-plane ml-2"></i>
                    </button>
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

        // ---- Rincian item dinamis ----
        let itemIndex = 1;
        const itemsBody = document.getElementById('items-body');

        document.getElementById('add-item').addEventListener('click', () => {
            const row = document.createElement('tr');
            row.innerHTML = `
                <td class="p-2"><input type="text" name="items[${itemIndex}][name]" class="w-full border-0 focus:ring-0 bg-transparent font-bold text-gray-900" placeholder="Nama item furnitur"></td>
                <td class="p-2"><input type="text" name="items[${itemIndex}][note]" class="w-full border-0 focus:ring-0 bg-transparent text-gray-600" placeholder="Dimensi & finishing"></td>
                <td class="p-2"><input type="number" name="items[${itemIndex}][qty]" value="1" min="1" class="w-full border-0 focus:ring-0 bg-transparent text-center font-bold text-primary"></td>
                <td class="p-2">
                    <select name="items[${itemIndex}][unit]" class="w-full border-0 focus:ring-0 bg-transparent text-gray-600">
                        <option value="unit">Unit</option>
                        <option value="set">Set</option>
                        <option value="m2">m²</option>
                        <option value="meter">Meter</option>
                    </select>
                </td>
                <td class="p-2 text-center">
                    <button type="button" class="remove-item text-gray-300 hover:text-red-500"><i class="fa-solid fa-trash"></i></button>
                </td>`;
            itemsBody.appendChild(row);
            itemIndex++;
        });

        itemsBody.addEventListener('click', (e) => {
            const btn = e.target.closest('.remove-item');
            if (!btn) return;
            if (itemsBody.querySelectorAll('tr').length > 1) {
                btn.closest('tr').remove();
            } else {
                btn.closest('tr').querySelectorAll('input').forEach((input) => {
                    input.value = input.type === 'number' ? 1 : '';
                });
            }
        });
    </script>
</body>
</html>
