<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Layanan Pesanan Proyek — DECOR Seller</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#B5733A',
                        'primary-dark': '#8A5229',
                        secondary: '#E3DCD6',
                    },
                    fontFamily: {
                        sans: ['"Plus Jakarta Sans"', 'sans-serif'],
                        heading: ['"Poppins"', 'sans-serif'],
                    },
                    letterSpacing: {
                        tight: '-0.02em',
                    }
                }
            }
        }
    </script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&family=Poppins:wght@600;700&display=swap');
        body { background-color: #F8F6F4; font-family: 'Plus Jakarta Sans', sans-serif; color: #1F2937; }
        h1, h2, h3, h4, h5, h6, .font-heading { font-family: 'Poppins', sans-serif; letter-spacing: -0.02em; }
        p, label, input, select, textarea { font-family: 'Plus Jakarta Sans', sans-serif; }
        p { line-height: 1.6; }
        .bg-primary { background-color: #B5733A; }
        .text-primary { color: #B5733A; }
        .active-link { background-color: rgba(181, 115, 58, 0.1); color: #B5733A; border-right: 4px solid #B5733A; }
        .sidebar-transition { transition: transform 0.3s ease-in-out, margin 0.3s ease-in-out; }
        @media (max-width: 767px) {
            #sidebar { transform: translateX(-100%); }
            #sidebar.sidebar-open { transform: translateX(0); }
            #main-content { margin-left: 0 !important; }
        }
        @media (min-width: 768px) {
            #sidebar.sidebar-hidden { transform: translateX(-100%); }
            #main-content.main-expanded { margin-left: 0 !important; }
        }
    </style>
</head>
<body class="text-gray-800 bg-[#F8F6F4]">

    @include('seller.partials.sidebar')

    <main id="main-content" class="flex-1 flex flex-col ml-0 md:ml-64 sidebar-transition min-h-screen">
        @include('seller.partials.header', ['title' => 'Layanan Pesanan Proyek'])

        <div class="p-6 sm:p-8 space-y-6 flex-1 max-w-5xl">

            <!-- Flash Alert -->
            @if(session('success'))
                <div class="bg-emerald-50 border border-emerald-200 text-emerald-800 px-5 py-4 rounded-xl text-xs font-semibold flex items-center gap-3">
                    <i class="fa-solid fa-circle-check text-emerald-600 text-base"></i>
                    <span>{{ session('success') }}</span>
                </div>
            @endif

            @if($errors->any())
                <div class="bg-red-50 border border-red-200 text-red-700 px-5 py-4 rounded-xl text-xs font-semibold space-y-1">
                    <div class="flex items-center gap-2">
                        <i class="fa-solid fa-circle-exclamation text-red-600"></i>
                        <span>Mohon periksa kembali formulir Anda:</span>
                    </div>
                    <ul class="list-disc list-inside ml-5 font-medium">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- Breadcrumb / Header Title -->
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <div class="text-[10px] font-bold text-primary uppercase tracking-widest mb-1">Kemitraan Fabrikasi</div>
                    <h1 class="text-2xl font-bold tracking-tight text-gray-900">Layanan Pesanan Proyek (Custom)</h1>
                    <p class="text-xs text-gray-500 mt-1">Terima pesanan kustom partai besar langsung dari Interior Desainer terdaftar di DECOR.</p>
                </div>
                <a href="{{ route('seller.settings') }}" class="inline-flex items-center text-xs font-semibold text-gray-500 hover:text-primary transition-colors">
                    <i class="fa-solid fa-arrow-left mr-2"></i> Kembali ke Pengaturan Toko
                </a>
            </div>

            <!-- KONDISI 1: STATUS PENDING (MENUNGGU VERIFIKASI) -->
            @if($seller->b2b_status === 'pending')
                <div class="bg-white p-8 rounded-xl border border-amber-200 shadow-sm space-y-6">
                    <div class="flex items-start gap-4">
                        <div class="w-12 h-12 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center text-xl shrink-0">
                            <i class="fa-solid fa-hourglass-half"></i>
                        </div>
                        <div class="space-y-1">
                            <span class="inline-block px-2.5 py-1 rounded-full text-[10px] font-bold bg-amber-100 text-amber-800 uppercase tracking-wider mb-1">
                                Menunggu Verifikasi Admin
                            </span>
                            <h2 class="text-lg font-bold text-gray-900">Pendaftaran Workshop Anda Sedang Ditinjau</h2>
                            <p class="text-xs text-gray-500 leading-relaxed">
                                Formulir spesifikasi dan kesepakatan S&K telah kami terima pada <strong>{{ $seller->b2b_terms_agreed_at?->format('d M Y, H:i') ?? 'hari ini' }}</strong>. 
                                Tim kurator DECOR sedang memeriksa data workshop Anda. Proses verifikasi biasanya memakan waktu maksimal 1x24 jam kerja.
                            </p>
                        </div>
                    </div>

                    <div class="p-5 bg-gray-50 rounded-xl border border-gray-100 space-y-3 text-xs">
                        <div class="font-bold text-gray-800 uppercase tracking-wider text-[10px]">Ringkasan Data yang Diajukan:</div>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 text-gray-600">
                            <div><span class="font-semibold text-gray-800">Kota Workshop:</span> {{ $seller->city ?? '-' }}</div>
                            <div><span class="font-semibold text-gray-800">Kapasitas Produksi:</span> {{ $seller->monthly_capacity ?? 0 }} unit / bulan</div>
                            <div class="sm:col-span-2">
                                <span class="font-semibold text-gray-800">Kategori Material:</span>
                                <div class="flex flex-wrap gap-1.5 mt-1">
                                    @foreach($seller->wood_specialties ?? [] as $mat)
                                        <span class="px-2 py-0.5 bg-white border border-gray-200 rounded text-[11px] font-medium text-gray-700">{{ $mat }}</span>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            <!-- KONDISI 2: STATUS APPROVED (SUDAH AKTIF) -->
            @elseif($seller->b2b_status === 'approved')
                <div class="bg-white p-8 rounded-xl border border-emerald-200 shadow-sm space-y-6">
                    <div class="flex items-start gap-4">
                        <div class="w-12 h-12 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-xl shrink-0">
                            <i class="fa-solid fa-circle-check"></i>
                        </div>
                        <div class="space-y-1">
                            <span class="inline-block px-2.5 py-1 rounded-full text-[10px] font-bold bg-emerald-100 text-emerald-800 uppercase tracking-wider mb-1">
                                Terverifikasi & Aktif
                            </span>
                            <h2 class="text-lg font-bold text-gray-900">Toko Anda Resmi Terdaftar Sebagai Mitra Pesanan Proyek</h2>
                            <p class="text-xs text-gray-500 leading-relaxed">
                                Profil workshop Anda sudah tayang di Katalog Desainer Interior DECOR. Anda siap menerima permintaan proyek masuk, mengirim penawaran harga, dan memproses pesanan kustom.
                            </p>
                        </div>
                    </div>

                    <div class="pt-4 border-t border-gray-100 flex flex-wrap items-center gap-3">
                        <a href="{{ route('seller.b2b.inbox') }}" class="px-5 py-2.5 bg-primary text-white text-xs font-bold rounded-lg hover:bg-[#8A5229] transition-all inline-flex items-center gap-2">
                            <i class="fa-solid fa-inbox"></i> Buka Inbox Pesanan Proyek
                        </a>
                        <span class="text-xs text-gray-400">Menu ini juga sudah aktif di bilah navigasi (sidebar) Anda.</span>
                    </div>
                </div>

            <!-- KONDISI 3: STATUS NOT REGISTERED ATAU REJECTED (TAMPILKAN FORM LENGKAP & S&K) -->
            @else
                @if($seller->b2b_status === 'rejected')
                    <div class="bg-red-50 border border-red-200 text-red-800 p-5 rounded-xl text-xs space-y-1.5">
                        <div class="font-bold flex items-center gap-2">
                            <i class="fa-solid fa-triangle-exclamation text-red-600"></i>
                            <span>Pengajuan Sebelumnya Ditolak Admin:</span>
                        </div>
                        <p class="text-gray-700 pl-6">{{ $seller->b2b_rejection_reason ?? 'Dokumen atau foto kelayakan workshop belum memenuhi kriteria.' }}</p>
                        <p class="text-gray-500 pl-6 text-[11px]">Silakan lengkapi atau perbaiki data di bawah untuk mengajukan ulang.</p>
                    </div>
                @endif

                <!-- Hero Info Banner -->
                <div class="bg-gradient-to-r from-[#B5733A] to-[#8A5229] text-white p-6 sm:p-8 rounded-xl shadow-sm relative overflow-hidden">
                    <div class="relative z-10 space-y-2 max-w-2xl">
                        <span class="inline-block px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-white/20 uppercase tracking-widest">Peluang Omzet Baru</span>
                        <h2 class="text-xl sm:text-2xl font-bold tracking-tight">Kembangkan Toko Anda Jadi Rekanan Desainer Interior</h2>
                        <p class="text-xs text-white/90 leading-relaxed">
                            Bukan cuma jual produk retail satuan di etalase, Anda kini bisa memproduksi mebel kustom volume besar (kafe, kantor, hunian) dengan sistem Pembayaran Bertahap via Decor yang aman.
                        </p>
                    </div>
                    <i class="fa-solid fa-couch absolute -right-6 -bottom-6 text-9xl text-white/10 pointer-events-none"></i>
                </div>

                <!-- Form Pendaftaran -->
                <form action="{{ route('seller.b2b.register.submit') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                    @csrf

                    <!-- BAGIAN 1: KAPABILITAS & WORKSHOP FISIK -->
                    <div class="bg-white p-6 sm:p-8 rounded-xl border border-gray-100 shadow-sm space-y-6">
                        <div class="border-b border-gray-100 pb-4">
                            <h3 class="text-base font-bold text-gray-900">1. Data Kapabilitas & Spesialisasi Workshop</h3>
                            <p class="text-xs text-gray-500 mt-0.5">Informasikan kemampuan fabrikasi toko Anda agar desainer dapat mencocokkan kebutuhan proyek mereka.</p>
                        </div>

                        <!-- Kategori Material (Checkbox Multi-select) -->
                        <div class="space-y-2">
                            <label class="block text-xs font-bold text-gray-700">
                                Kategori Material & Fabrikasi yang Dikuasai <span class="text-red-500">*</span>
                            </label>
                            <p class="text-[11px] text-gray-400">Pilih satu atau lebih kategori yang sanggup dikerjakan oleh tukang/workshop Anda:</p>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-2.5 pt-2">
                                @php
                                    $selectedMaterials = old('materials', $seller->wood_specialties ?? []);
                                @endphp
                                @foreach($materialOptions as $opt)
                                    <label class="flex items-start gap-3 p-3 bg-gray-50 hover:bg-gray-100/80 border border-gray-200 rounded-lg cursor-pointer transition-colors text-xs">
                                        <input type="checkbox" name="materials[]" value="{{ $opt }}" class="mt-0.5 text-primary focus:ring-primary rounded border-gray-300" {{ in_array($opt, $selectedMaterials) ? 'checked' : '' }}>
                                        <span class="text-gray-700 font-medium leading-relaxed">{{ $opt }}</span>
                                    </label>
                                @endforeach
                            </div>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 pt-2">
                            <!-- Kapasitas Produksi -->
                            <div class="space-y-1.5">
                                <label for="monthly_capacity" class="block text-xs font-bold text-gray-700">
                                    Estimasi Kapasitas Produksi Bulanan <span class="text-red-500">*</span>
                                </label>
                                <div class="relative">
                                    <input type="number" id="monthly_capacity" name="monthly_capacity" value="{{ old('monthly_capacity', $seller->monthly_capacity ?? 30) }}" min="1" max="10000" class="w-full h-10 bg-white border border-gray-200 rounded-lg px-3.5 pr-20 text-xs font-medium text-gray-800 focus:outline-none focus:border-primary focus:ring-2 focus:ring-primary/10 transition-all" placeholder="Contoh: 30">
                                    <span class="absolute right-3.5 top-2.5 text-xs text-gray-400 font-medium">unit / bulan</span>
                                </div>
                            </div>

                            <!-- Kota Workshop -->
                            <div class="space-y-1.5">
                                <label for="city" class="block text-xs font-bold text-gray-700">
                                    Kota Workshop / Pabrik <span class="text-red-500">*</span>
                                </label>
                                <input type="text" id="city" name="city" value="{{ old('city', $seller->city ?? '') }}" class="w-full h-10 bg-white border border-gray-200 rounded-lg px-3.5 text-xs font-medium text-gray-800 focus:outline-none focus:border-primary focus:ring-2 focus:ring-primary/10 transition-all" placeholder="Contoh: Jepara, Bandung, Jakarta Timur">
                            </div>
                        </div>

                        <!-- Alamat Workshop Fisik -->
                        <div class="space-y-1.5">
                            <label for="workshop_address" class="block text-xs font-bold text-gray-700">
                                Alamat Lengkap Workshop / Tempat Pengerjaan <span class="text-red-500">*</span>
                            </label>
                            <textarea id="workshop_address" name="workshop_address" rows="3" class="w-full bg-white border border-gray-200 rounded-lg p-3 text-xs font-medium text-gray-800 focus:outline-none focus:border-primary focus:ring-2 focus:ring-primary/10 transition-all" placeholder="Masukkan alamat lengkap bengkel kerja Anda untuk memvalidasi kelayakan fisik...">{{ old('workshop_address', $seller->store_address ?? '') }}</textarea>
                        </div>

                        <!-- Upload Foto Workshop / Hasil Mebel -->
                        <div class="space-y-1.5">
                            <label class="block text-xs font-bold text-gray-700">
                                Foto Workshop Fisik / Portofolio Proyek Terdahulu
                            </label>
                            <input type="file" name="workshop_photo" accept="image/jpeg,image/png,image/jpg" class="w-full text-xs text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-primary/10 file:text-primary hover:file:bg-primary/20 cursor-pointer">
                            <p class="text-[11px] text-gray-400">Format: JPG, PNG maks 5MB. Lampirkan foto workshop atau mebel kustom yang pernah dibuat sebagai bukti kelayakan.</p>
                        </div>
                    </div>

                    <!-- BAGIAN 2: SYARAT & KETENTUAN (S&K) SERTA PERJANJIAN KEMITRAAN -->
                    <div class="bg-white p-6 sm:p-8 rounded-xl border border-gray-100 shadow-sm space-y-4">
                        <div class="border-b border-gray-100 pb-3">
                            <div class="flex items-center gap-2">
                                <i class="fa-solid fa-file-contract text-primary"></i>
                                <h3 class="text-base font-bold text-gray-900">2. Syarat & Ketentuan Kemitraan Proyek DECOR</h3>
                            </div>
                            <p class="text-xs text-gray-500 mt-0.5">Harap baca dengan teliti perjanjian komitmen mutu dan pembayaran berikut:</p>
                        </div>

                        <!-- Scrollable S&K Box -->
                        <div class="p-4 bg-gray-50 border border-gray-200 rounded-xl h-60 overflow-y-auto text-xs text-gray-600 space-y-3 leading-relaxed">
                            <div class="font-bold text-gray-900">PERJANJIAN LAYANAN PENGADAAN MEBEL KUSTOM (B2B DECOR)</div>
                            
                            <p><strong>Pasal 1: Ruang Lingkup & Kepatuhan Spesifikasi</strong><br>
                            Seller yang terdaftar dalam Layanan Pesanan Proyek berkewajiban memproduksi pesanan kustom secara presisi mengacu pada berkas gambar kerja (CAD), Bill of Quantity (BoQ), dan spesifikasi sampel fisik yang disepakati secara tertulis di sistem.</p>

                            <p><strong>Pasal 2: Kendali Mutu (Pengecekan Barang) &amp; Transparansi 5 Tahapan</strong><br>
                            Seller wajib mendokumentasikan dan memperbarui progres pengerjaan pada 5 pos tahapan produksi di sistem DECOR (Persiapan Bahan, Pembentukan Rangka, Perakitan, Finishing, dan Pengecekan Akhir) dengan menyertakan bukti foto aktual sebelum pengiriman dilakukan.</p>

                            <p><strong>Pasal 3: Berita Acara Serah Terima (BAST) &amp; Pengiriman</strong><br>
                            Penyerahan barang di lokasi proyek wajib disertai penerbitan dokumen Surat Jalan resmi dan penandatanganan Berita Acara Serah Terima (BAST) digital oleh Desainer Interior setelah dilakukan pemeriksaan kesesuaian fisik.</p>

                            <p><strong>Pasal 4: Pembayaran Bertahap via Decor &amp; Pencairan Dana</strong><br>
                            Seluruh pembayaran dari Desainer (DP 50% dan Pelunasan 50%) ditahan aman oleh sistem DECOR. Dana pembayaran akan dicairkan ke rekening bank Seller setelah dokumen BAST resmi disetujui oleh Desainer dan divalidasi oleh Admin DECOR.</p>

                            <p><strong>Pasal 5: Komisi Platform & Mediasi Masalah</strong><br>
                            DECOR berhak memotong biaya administrasi/layanan platform dari total nilai proyek yang selesai. Apabila terjadi sengketa ketidaksesuaian barang, Seller dan Desainer bersedia mengikutsertakan Admin DECOR sebagai mediator penyelesaian.</p>
                        </div>

                        <!-- Checkbox Persetujuan -->
                        <div class="pt-2">
                            <label class="flex items-start gap-3 p-3.5 bg-primary/5 border border-primary/20 rounded-xl cursor-pointer">
                                <input type="checkbox" name="terms_agreed" value="1" class="mt-0.5 text-primary focus:ring-primary rounded border-gray-300" {{ old('terms_agreed') ? 'checked' : '' }} required>
                                <span class="text-xs font-semibold text-gray-800 leading-relaxed">
                                    Saya telah membaca, memahami, dan menyetujui seluruh isi Syarat & Ketentuan Layanan Pesanan Proyek DECOR serta berkomitmen mematuhi standar mutu dan jadwal produksi.
                                </span>
                            </label>
                        </div>
                    </div>

                    <!-- Tombol Aksi Submit -->
                    <div class="bg-white p-5 rounded-xl border border-gray-100 shadow-sm flex flex-col sm:flex-row items-center justify-between gap-4">
                        <div class="text-xs text-gray-500 flex items-center gap-2">
                            <i class="fa-solid fa-shield-halved text-emerald-600 text-sm"></i>
                            <span>Data Anda aman dan akan diverifikasi langsung oleh Admin DECOR.</span>
                        </div>
                        <button type="submit" class="w-full sm:w-auto px-8 py-3 bg-primary text-white rounded-lg text-xs font-bold hover:bg-[#8A5229] transition-all shadow-sm flex items-center justify-center gap-2">
                            <i class="fa-solid fa-paper-plane"></i>
                            <span>Kirim Pendaftaran ke Admin</span>
                        </button>
                    </div>
                </form>
            @endif

        </div>

        @include('partials.footer')
    </main>

    <script>
        const btn = document.getElementById('toggle-sidebar');
        const sidebar = document.getElementById('sidebar');
        const main = document.getElementById('main-content');
        if (btn && sidebar && main) {
            btn.addEventListener('click', () => {
                sidebar.classList.toggle('sidebar-hidden');
                main.classList.toggle('main-expanded');
            });
        }
    </script>
</body>
</html>
