<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Decor Seller - Pengaturan Toko</title>
    <script src="https://cdn.tailwindcss.com"></script>
    @include('seller.partials.head')
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        input:focus, textarea:focus { border-color: #B5733A !important; outline: none; background: white !important; }
    </style>
</head>
<body class="text-gray-800">

    @include('seller.partials.sidebar')

    <main id="main-content" class="flex-1 flex flex-col ml-0 md:ml-64 sidebar-transition min-h-screen relative">
        @include('seller.partials.header', ['title' => 'Store Settings'])

        <form action="{{ route('seller.settings.update') }}" method="POST" enctype="multipart/form-data" id="settingsForm" class="flex-1 flex flex-col">
            @csrf

            <div class="p-4 sm:p-6 lg:p-8 space-y-6 flex-1">
                @if(session('success'))
                    <div class="bg-green-50 border border-green-200 text-green-700 px-5 py-3.5 rounded-xl text-xs font-semibold flex items-center gap-2.5">
                        <i class="fa-solid fa-circle-check text-green-600"></i>
                        <span>{{ session('success') }}</span>
                    </div>
                @endif

                @if($errors->any())
                    <div class="bg-red-50 border border-red-200 text-red-700 px-5 py-3.5 rounded-xl text-xs font-semibold space-y-1.5">
                        <div class="flex items-center gap-2">
                            <i class="fa-solid fa-circle-exclamation"></i>
                            <span>Terdapat beberapa kesalahan:</span>
                        </div>
                        <ul class="list-disc list-inside ml-5 font-medium">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div>
                    <div class="text-[10px] font-bold text-primary uppercase tracking-widest mb-1">Store Settings</div>
                    <h2 class="text-2xl font-bold tracking-tight text-gray-900">Pengaturan Toko</h2>
                    <p class="text-xs text-gray-500 mt-1">Kelola identitas toko, informasi rekening, dan keamanan akun Anda.</p>
                </div>

                <!-- CARD 1: IDENTITAS VISUAL & PROFIL TOKO -->
                <div class="bg-white p-5 sm:p-6 rounded-xl border border-gray-100 shadow-sm space-y-6">
                    <div>
                        <h3 class="text-sm font-bold text-gray-900">Profil &amp; Identitas Toko</h3>
                        <p class="text-xs text-gray-400 mt-0.5">Logo, banner toko, dan informasi publik yang dilihat pelanggan.</p>
                    </div>

                    <div class="flex flex-col md:flex-row gap-6 items-start">
                        <div class="space-y-2 shrink-0">
                            <label class="block text-xs font-semibold text-gray-700">Logo Toko</label>
                            <input type="file" name="store_image" id="logoInput" class="hidden" onchange="previewImage(this, 'logoPreview')">
                            <div onclick="document.getElementById('logoInput').click()" class="w-40 h-40 sm:w-44 sm:h-44 bg-secondary/30 rounded-xl border-2 border-dashed border-primary/25 flex flex-col items-center justify-center text-primary/40 group relative overflow-hidden cursor-pointer">
                                <img src="{{ $seller->store_image ? asset('storage/' . $seller->store_image) : $seller->user->avatar_url }}" id="logoPreview" class="w-full h-full object-cover">
                                <div class="absolute inset-0 bg-primary/80 opacity-0 group-hover:opacity-100 transition-all flex flex-col items-center justify-center text-white gap-1">
                                    <i class="fa-solid fa-camera text-base"></i>
                                    <span class="text-[11px] font-semibold">Ganti Logo</span>
                                </div>
                            </div>
                            <p class="text-[11px] text-gray-400">Rasio 1:1 (disarankan 500&times;500px)</p>
                        </div>

                        <div class="space-y-2 flex-1 w-full">
                            <label class="block text-xs font-semibold text-gray-700">Banner Toko</label>
                            <input type="file" name="store_banner" id="bannerInput" class="hidden" onchange="previewImage(this, 'bannerPreview')">
                            <div onclick="document.getElementById('bannerInput').click()" class="h-44 w-full bg-gray-50 rounded-xl border-2 border-dashed border-gray-200 flex flex-col items-center justify-center relative overflow-hidden group cursor-pointer">
                                @if($seller->store_banner)
                                    <img src="{{ asset('storage/' . $seller->store_banner) }}" id="bannerPreview" class="w-full h-full object-cover">
                                @else
                                    <div id="bannerPlaceholder" class="flex flex-col items-center">
                                        <i class="fa-solid fa-image text-3xl text-gray-300 mb-2"></i>
                                        <span class="text-xs font-medium text-gray-400">Klik untuk unggah banner toko</span>
                                    </div>
                                    <img id="bannerPreview" class="w-full h-full object-cover hidden">
                                @endif
                                <div class="absolute inset-0 bg-black/45 opacity-0 group-hover:opacity-100 transition-all flex items-center justify-center">
                                    <span class="bg-white text-gray-800 px-3.5 py-2 rounded-lg text-xs font-semibold shadow-sm">Ganti Banner</span>
                                </div>
                            </div>
                            <p class="text-[11px] text-gray-400">Disarankan ukuran 1200&times;400px (JPG/PNG)</p>
                        </div>
                    </div>

                    <div class="border-t border-gray-100 pt-5 space-y-4">
                        <div class="space-y-1.5">
                            <label for="store_name" class="block text-xs font-semibold text-gray-700">Nama Toko</label>
                            <input id="store_name" type="text" name="store_name" value="{{ $seller->store_name }}" class="w-full h-10 bg-white border border-gray-200 rounded-lg px-3.5 text-xs font-medium text-gray-800 focus:outline-none focus:border-primary focus:ring-2 focus:ring-primary/10 transition-all">
                        </div>
                        <div class="space-y-1.5">
                            <label for="store_description" class="block text-xs font-semibold text-gray-700">Deskripsi Toko</label>
                            <textarea id="store_description" name="store_description" rows="3" class="w-full bg-white border border-gray-200 rounded-lg p-3.5 text-xs font-medium text-gray-700 leading-relaxed focus:outline-none focus:border-primary focus:ring-2 focus:ring-primary/10 transition-all">{{ $seller->store_description }}</textarea>
                        </div>
                        <div class="space-y-1.5">
                            <label for="store_address" class="block text-xs font-semibold text-gray-700">Alamat Toko</label>
                            <textarea id="store_address" name="store_address" rows="2" class="w-full bg-white border border-gray-200 rounded-lg p-3.5 text-xs font-medium text-gray-700 leading-relaxed focus:outline-none focus:border-primary focus:ring-2 focus:ring-primary/10 transition-all" placeholder="Masukkan alamat lengkap toko...">{{ $seller->store_address }}</textarea>
                        </div>
                    </div>
                </div>

                <!-- LAYANAN PESANAN PROYEK (B2B) -->
                <div class="bg-white p-5 sm:p-6 rounded-xl border border-gray-100 shadow-sm space-y-4">
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                        <div class="flex items-start gap-4">
                            <div class="w-11 h-11 rounded-xl bg-primary/10 text-primary flex items-center justify-center shrink-0">
                                <i class="fa-solid fa-industry"></i>
                            </div>
                            <div>
                                <div class="flex items-center gap-2 flex-wrap">
                                    <h3 class="text-sm font-bold text-gray-900">Layanan Pesanan Proyek (B2B)</h3>
                                    @if(($seller->b2b_status ?? 'not_registered') === 'pending')
                                        <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-amber-100 text-amber-800">Menunggu Verifikasi</span>
                                    @elseif($seller->b2b_status === 'approved' && $seller->b2b_enabled)
                                        <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-emerald-100 text-emerald-800">Aktif</span>
                                    @elseif(($seller->b2b_status ?? 'not_registered') === 'rejected')
                                        <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-red-100 text-red-800">Ditolak</span>
                                    @else
                                        <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-gray-100 text-gray-600">Belum Terdaftar</span>
                                    @endif
                                </div>
                                <p class="text-xs text-gray-500 mt-1 leading-relaxed max-w-2xl">
                                    @if(($seller->b2b_status ?? 'not_registered') === 'pending')
                                        Pengajuan Anda sedang ditinjau Admin DECOR (maks. 1&times;24 jam kerja).
                                    @elseif($seller->b2b_status === 'approved' && $seller->b2b_enabled)
                                        Toko Anda terdaftar sebagai Mitra Pabrik dan tampil di katalog vendor desainer interior.
                                    @elseif(($seller->b2b_status ?? 'not_registered') === 'rejected')
                                        Pengajuan ditolak: {{ $seller->b2b_rejection_reason ?: 'silakan lengkapi data workshop Anda.' }}
                                    @elseif($seller->b2b_status === 'approved')
                                        Status mitra aktif, namun layanan pesanan proyek sedang dinonaktifkan oleh Admin.
                                    @else
                                        Punya workshop / tukang sendiri? Daftarkan toko Anda untuk menerima order mebel kustom dari Desainer Interior DECOR.
                                    @endif
                                </p>
                            </div>
                        </div>
                        <a href="{{ route('seller.b2b.register') }}" class="inline-flex items-center justify-center px-4 py-2.5 bg-primary/10 text-primary hover:bg-primary hover:text-white rounded-lg text-xs font-bold transition-all shrink-0">
                            <i class="fa-solid fa-arrow-right mr-2"></i>
                            {{ ($seller->b2b_status ?? 'not_registered') === 'pending' ? 'Cek Status Pengajuan' : ($seller->b2b_status === 'approved' ? 'Kelola Layanan B2B' : 'Daftar Layanan Proyek') }}
                        </a>
                    </div>
                </div>

                <!-- BARIS BAWAH: REKENING BANK & KEAMANAN AKUN -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <div class="bg-white p-5 sm:p-6 rounded-xl border border-gray-100 shadow-sm space-y-4">
                        <div>
                            <h3 class="text-sm font-bold text-gray-900">Informasi Rekening Bank</h3>
                            <p class="text-xs text-gray-400 mt-0.5">Rekening tujuan untuk pencairan dana penjualan.</p>
                        </div>
                        <div class="space-y-4 pt-1">
                            <div class="space-y-1.5">
                                <label for="bank_name" class="block text-xs font-semibold text-gray-700">Nama Bank</label>
                                <input id="bank_name" type="text" name="bank_name" value="{{ $seller->bank_name }}" placeholder="Contoh: BCA, Mandiri, BNI" class="w-full h-10 bg-white border border-gray-200 rounded-lg px-3.5 text-xs font-medium text-gray-800 focus:outline-none focus:border-primary focus:ring-2 focus:ring-primary/10 transition-all">
                            </div>
                            <div class="space-y-1.5">
                                <label for="account_number" class="block text-xs font-semibold text-gray-700">Nomor Rekening</label>
                                <input id="account_number" type="text" name="account_number" value="{{ $seller->account_number }}" placeholder="Contoh: 8820xxxxxx" class="w-full h-10 bg-white border border-gray-200 rounded-lg px-3.5 text-xs font-medium text-gray-800 focus:outline-none focus:border-primary focus:ring-2 focus:ring-primary/10 transition-all">
                            </div>
                        </div>
                    </div>

                    <div class="bg-white p-5 sm:p-6 rounded-xl border border-gray-100 shadow-sm space-y-4">
                        <div>
                            <h3 class="text-sm font-bold text-gray-900">Keamanan Akun</h3>
                            <p class="text-xs text-gray-400 mt-0.5">Kosongkan kolom di bawah jika tidak ingin mengubah password.</p>
                        </div>
                        <div class="space-y-4 pt-1">
                            <div class="space-y-1.5">
                                <label for="current_password" class="block text-xs font-semibold text-gray-700">Password Saat Ini</label>
                                <input id="current_password" type="password" name="current_password" class="w-full h-10 bg-white @error('current_password') border-red-500 @else border-gray-200 @enderror border rounded-lg px-3.5 text-xs font-medium text-gray-800 focus:outline-none focus:border-primary focus:ring-2 focus:ring-primary/10 transition-all" placeholder="Masukkan password saat ini">
                                @error('current_password')
                                    <p class="text-red-500 text-xs font-medium mt-1"><i class="fa-solid fa-circle-exclamation mr-1"></i> {{ $message }}</p>
                                @enderror
                            </div>
                            <div class="space-y-1.5">
                                <label for="new_password" class="block text-xs font-semibold text-gray-700">Password Baru</label>
                                <input id="new_password" type="password" name="new_password" class="w-full h-10 bg-white @error('new_password') border-red-500 @else border-gray-200 @enderror border rounded-lg px-3.5 text-xs font-medium text-gray-800 focus:outline-none focus:border-primary focus:ring-2 focus:ring-primary/10 transition-all" placeholder="Masukkan password baru">
                                @error('new_password')
                                    <p class="text-red-500 text-xs font-medium mt-1"><i class="fa-solid fa-circle-exclamation mr-1"></i> {{ $message }}</p>
                                @enderror
                            </div>
                            <div class="space-y-1.5">
                                <label for="new_password_confirmation" class="block text-xs font-semibold text-gray-700">Konfirmasi Password Baru</label>
                                <input id="new_password_confirmation" type="password" name="new_password_confirmation" class="w-full h-10 bg-white border border-gray-200 rounded-lg px-3.5 text-xs font-medium text-gray-800 focus:outline-none focus:border-primary focus:ring-2 focus:ring-primary/10 transition-all" placeholder="Konfirmasi password baru">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-xl border border-gray-100 shadow-sm px-5 sm:px-6 py-4 flex flex-wrap items-center justify-between gap-4">
                    <div class="flex items-center gap-2 text-xs font-medium text-gray-500">
                        <i class="fa-solid fa-circle-info text-primary"></i>
                        <span>Perubahan akan langsung berlaku setelah disimpan.</span>
                    </div>
                    <div class="flex items-center gap-2.5 ml-auto">
                        <button type="reset" class="h-10 px-4 border border-gray-200 text-gray-600 hover:border-primary hover:text-primary rounded-lg text-xs font-semibold transition-all">Batal</button>
                        <button type="submit" class="h-10 px-5 bg-primary text-white rounded-lg text-xs font-semibold hover:bg-opacity-90 transition-all">Simpan Perubahan</button>
                    </div>
                </div>
            </div>
        </form>

        @include('partials.footer')
    </main>

    <script>
        function previewImage(input, previewId) {
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const preview = document.getElementById(previewId);
                    preview.src = e.target.result;
                    preview.classList.remove('hidden');

                    const placeholder = document.getElementById(previewId.replace('Preview', 'Placeholder'));
                    if (placeholder) placeholder.classList.add('hidden');
                }
                reader.readAsDataURL(input.files[0]);
            }
        }
    </script>
</body>
</html>
