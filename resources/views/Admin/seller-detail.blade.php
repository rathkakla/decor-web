@php
$admin_name = Auth::user()->full_name;
$admin_role = "ADMIN";
@endphp
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Seller Detail — {{ $seller->store_name }} | DECOR Admin</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=DM+Mono:wght@400;500&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/feather-icons"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800;900&display=swap');
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        .bg-primary { background-color: #B5733A; }
        .text-primary { color: #B5733A; }
        .sidebar-transition { transition: transform 0.3s ease-in-out, margin 0.3s ease-in-out; }
        .sidebar-hidden { transform: translateX(-100%); }
        .main-expanded { margin-left: 0 !important; }
        
        .modal { position: fixed; inset: 0; background: rgba(28, 20, 16, 0.4); backdrop-filter: blur(4px); display: none; align-items: center; justify-content: center; z-index: 3000; }
        .modal.show { display: flex; }
    </style>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body class="text-gray-800 bg-[#F8F6F4]">

@include("Admin.partials.sidebar")

<main id="main-content" class="flex-1 flex flex-col ml-64 sidebar-transition min-h-screen bg-[#F8F6F4]">
    @include("Admin.partials.header", ["title" => "Seller Detail"])
    <div class="p-8 space-y-6 flex-1">

    @if(session('success'))
        <div class="bg-green-50 border border-green-200 text-green-600 px-6 py-4 rounded-xl text-xs font-bold flex items-center">
            <i data-feather="check-circle" class="w-4 h-4 mr-3"></i>
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="bg-red-50 border border-red-200 text-red-600 px-6 py-4 rounded-xl text-xs font-bold flex items-center">
            <i data-feather="alert-circle" class="w-4 h-4 mr-3"></i>
            {{ session('error') }}
        </div>
    @endif

    @if($errors->any())
        <div class="bg-red-50 border border-red-200 text-red-600 px-6 py-4 rounded-xl text-xs font-bold flex items-start">
            <i data-feather="alert-circle" class="w-4 h-4 mr-3 mt-0.5"></i>
            <div>
                @foreach($errors->all() as $error)
                    <div>{{ $error }}</div>
                @endforeach
            </div>
        </div>
    @endif

    <!-- Breadcrumb (SELLER) -->
    <div class="flex items-center justify-between">
        <div class="flex items-center gap-2 text-xs font-bold text-gray-500">
            <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-1 hover:text-primary transition-colors"><i data-feather="home" class="w-3.5 h-3.5"></i> Dashboard</a>
            <span class="text-gray-300">/</span>
            @if($seller->status === 'approved')
                <a href="{{ route('admin.seller-monitor') }}" class="flex items-center gap-1 hover:text-primary transition-colors"><i data-feather="shopping-bag" class="w-3.5 h-3.5"></i> Seller Monitor</a>
            @else
                <a href="{{ route('admin.account.validation', ['type' => 'seller']) }}" class="flex items-center gap-1 hover:text-primary transition-colors"><i data-feather="shield" class="w-3.5 h-3.5"></i> Account Validation</a>
            @endif
            <span class="text-gray-300">/</span>
            <span class="text-primary">{{ $seller->store_name }}</span>
        </div>
        <a href="{{ $seller->status === 'approved' ? route('admin.seller-monitor') : route('admin.account.validation', ['type' => 'seller']) }}" class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-lg bg-white border border-gray-200 text-xs font-bold text-gray-600 hover:text-primary hover:border-primary/40 transition-colors shadow-sm">
            <i data-feather="arrow-left" class="w-3.5 h-3.5"></i> Kembali
        </a>
    </div>

    @if(isset($isWarning) && $isWarning)
    <!-- Banner Audit & Suspensi (Muncul Otomatis saat Seller Status Warning) -->
    <div class="bg-amber-50/90 border border-amber-200 rounded-xl p-6 flex flex-col md:flex-row gap-6 items-center shadow-sm">
        <div class="flex items-start gap-4 flex-1">
            <div class="w-11 h-11 rounded-xl bg-amber-100 text-amber-600 flex items-center justify-center shrink-0">
                <i data-feather="alert-triangle" class="w-5 h-5"></i>
            </div>
            <div>
                <div class="flex items-center gap-2 mb-0.5">
                    <h3 class="text-base font-bold text-amber-900">Status Toko: Warning (Perlu Tindakan Audit)</h3>
                    <span class="px-2 py-0.5 rounded text-[9px] font-black uppercase tracking-widest bg-amber-200/70 text-amber-800">Inactive &gt; 30 Days</span>
                </div>
                <p class="text-xs text-amber-700 leading-relaxed">Toko ini tidak memiliki aktivitas transaksi dalam 30 hari terakhir atau terindikasi membutuhkan evaluasi kepatuhan. Anda dapat menjatuhkan sanksi Audit (Suspensi Sementara 7 / 14 / 30 Hari).</p>
            </div>
        </div>
        <div class="shrink-0">
            <button type="button" onclick="openAuditModal()" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl text-xs font-bold bg-amber-600 hover:bg-amber-700 text-white transition-colors shadow-sm">
                <i data-feather="shield" class="w-4 h-4"></i> Tindakan Audit
            </button>
        </div>
    </div>
    @endif

    @if($seller->status == 'pending')
    <div class="bg-amber-50 border border-amber-200 rounded-xl p-6 flex flex-col md:flex-row gap-6 items-center shadow-sm">
        <div class="flex items-start gap-4 flex-1">
            <div class="w-10 h-10 rounded-xl bg-amber-100 text-amber-600 flex items-center justify-center shrink-0">
                <i data-feather="alert-circle" class="w-5 h-5"></i>
            </div>
            <div>
                <h3 class="text-base font-bold text-amber-800 mb-0.5">Account Review Pending</h3>
                <p class="text-xs text-amber-700 leading-relaxed">Mohon tinjau informasi toko, kapabilitas B2B, dan produk yang diupload oleh seller ini sebelum memberikan persetujuan aktivasi akun.</p>
            </div>
        </div>
        <div class="flex gap-3 shrink-0">
            <form action="{{ route('admin.account.approve', $seller->id) }}" method="POST">
                @csrf
                <input type="hidden" name="type" value="seller">
                <button type="submit" class="flex items-center gap-2 px-5 py-2.5 rounded-lg text-xs font-bold bg-green-600 hover:bg-green-700 text-white transition-colors shadow-sm">
                    <i data-feather="check" class="w-4 h-4"></i> Approve Account
                </button>
            </form>
            <button type="button" onclick="openRejectModal()" class="flex items-center gap-2 px-5 py-2.5 rounded-lg text-xs font-bold bg-red-500 hover:bg-red-600 text-white transition-colors shadow-sm">
                <i data-feather="x" class="w-4 h-4"></i> Reject Account
            </button>
        </div>
    </div>
    @endif

    <!-- Hero Profile Card -->
    <div class="bg-white border border-gray-100 rounded-xl p-7 shadow-sm relative overflow-hidden">
        <div class="absolute top-0 left-0 right-0 h-1 {{ $seller->status == 'pending' ? 'bg-amber-500' : ($seller->status == 'approved' ? 'bg-green-500' : 'bg-red-500') }}"></div>
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
            <div class="flex items-center gap-5">
                <div class="w-16 h-16 rounded-2xl bg-orange-50 border border-orange-100 flex items-center justify-center shrink-0 overflow-hidden text-primary shadow-sm">
                    @if($seller->store_image_url)
                        <img src="{{ $seller->store_image_url }}" class="w-full h-full object-cover">
                    @else
                        <i data-feather="shopping-bag" class="w-6 h-6"></i>
                    @endif
                </div>
                <div>
                    <div class="flex items-center gap-2.5 flex-wrap mb-1.5">
                        <h2 class="text-2xl font-bold text-gray-900">{{ $seller->store_name }}</h2>
                        @if($seller->svlk_verified)
                            <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[9px] font-black uppercase tracking-widest bg-emerald-50 text-emerald-700 border border-emerald-200">
                                <i data-feather="award" class="w-3 h-3"></i> SVLK Verified
                            </span>
                        @endif
                    </div>
                    <div class="flex items-center gap-3 flex-wrap">
                        <span class="font-mono text-[10px] text-gray-500 bg-gray-50 border border-gray-200 px-2.5 py-1 rounded-md">ID: SEL-{{ str_pad($seller->id, 4, '0', STR_PAD_LEFT) }}</span>
                        <span class="flex items-center gap-1.5 text-[11px] font-semibold text-gray-500"><i data-feather="user" class="w-3.5 h-3.5 text-primary"></i> {{ $seller->user->full_name }}</span>
                        <span class="flex items-center gap-1.5 text-[11px] font-semibold text-gray-500"><i data-feather="map-pin" class="w-3.5 h-3.5 text-primary"></i> {{ $seller->city ?: 'Indonesia' }}</span>
                        <span class="flex items-center gap-1.5 text-[11px] font-semibold text-gray-400"><i data-feather="calendar" class="w-3.5 h-3.5"></i> Joined {{ $seller->created_at->format('d M Y') }}</span>
                    </div>
                </div>
            </div>
            <div class="flex items-center gap-2.5">
                @if(isset($isWarning) && $isWarning)
                    <span class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-lg text-[10px] font-black uppercase tracking-widest bg-amber-50 text-amber-600 border border-amber-200">
                        <i data-feather="alert-triangle" class="w-3.5 h-3.5"></i> WARNING
                    </span>
                @endif
                @php
                    $statusCls = match(strtolower($seller->status)) {
                        'pending' => 'bg-amber-50 text-amber-600 border border-amber-200',
                        'approved' => 'bg-green-50 text-green-600 border border-green-200',
                        'rejected' => 'bg-red-50 text-red-600 border border-red-200',
                        default => 'bg-gray-50 text-gray-600 border border-gray-200'
                    };
                @endphp
                <span class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-lg text-[10px] font-black uppercase tracking-widest {{ $statusCls }}">
                    <i data-feather="info" class="w-3.5 h-3.5"></i> {{ strtoupper($seller->status) }}
                </span>
            </div>
        </div>
    </div>

    <!-- Top Grid: Informasi Toko & Modul B2B Side-by-Side -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 items-stretch">
        <!-- Card 1: Informasi Toko -->
        <div class="bg-white border border-gray-100 rounded-xl shadow-sm overflow-hidden flex flex-col">
            <div class="p-5 border-b border-gray-100 flex items-center justify-between bg-gray-50/40">
                <h3 class="text-sm font-bold text-gray-900 flex items-center gap-2">
                    <i data-feather="info" class="w-4 h-4 text-primary"></i> Informasi Toko
                </h3>
                <span class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Profil Retail</span>
            </div>
            <div class="p-6 space-y-4 flex-1 flex flex-col justify-between">
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div class="bg-gray-50 p-3.5 rounded-xl border border-gray-100">
                        <label class="block text-[9px] font-black text-gray-400 uppercase tracking-widest mb-1">Owner Name</label>
                        <div class="text-xs font-bold text-gray-900">{{ $seller->user->full_name }}</div>
                    </div>
                    <div class="bg-gray-50 p-3.5 rounded-xl border border-gray-100">
                        <label class="block text-[9px] font-black text-gray-400 uppercase tracking-widest mb-1">Email & Kontak</label>
                        <div class="text-xs font-bold text-gray-900 truncate">{{ $seller->user->email }}</div>
                    </div>
                </div>
                <div>
                    <label class="block text-[9px] font-black text-gray-400 uppercase tracking-widest mb-1.5">Store Address</label>
                    <div class="text-xs text-gray-600 leading-relaxed bg-gray-50 p-3.5 rounded-xl border border-gray-100">{{ $seller->store_address ?? 'Tidak ada data' }}</div>
                </div>
                <div>
                    <label class="block text-[9px] font-black text-gray-400 uppercase tracking-widest mb-1.5">Description</label>
                    <div class="text-xs text-gray-600 leading-relaxed bg-gray-50 p-3.5 rounded-xl border border-gray-100">{{ $seller->store_description ?? 'Tidak ada data' }}</div>
                </div>
            </div>
        </div>

        <!-- Card 2: Modul B2B (Sejajar & Rapi) -->
        <div class="bg-white border border-gray-100 rounded-xl shadow-sm overflow-hidden flex flex-col">
            <div class="p-5 border-b border-gray-100 flex items-center justify-between bg-gray-50/40">
                <div class="flex items-center gap-2">
                    <i data-feather="layers" class="w-4 h-4 text-primary"></i>
                    <h3 class="text-sm font-bold text-gray-900">Modul B2B & Kapasitas Pabrik</h3>
                </div>
                @if(($seller->b2b_status ?? 'not_registered') === 'pending')
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-lg text-[9px] font-black uppercase tracking-widest bg-amber-50 text-amber-700 border border-amber-200">
                        <span class="w-1.5 h-1.5 rounded-full bg-amber-500 animate-pulse"></span>
                        Menunggu Persetujuan
                    </span>
                @elseif($seller->b2b_enabled)
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-lg text-[9px] font-black uppercase tracking-widest bg-green-50 text-green-600 border border-green-200">
                        <span class="w-1.5 h-1.5 rounded-full bg-green-500"></span>
                        B2B Aktif
                    </span>
                @else
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-lg text-[9px] font-black uppercase tracking-widest bg-gray-100 text-gray-500 border border-gray-200">
                        <span class="w-1.5 h-1.5 rounded-full bg-gray-400"></span>
                        B2B Nonaktif
                    </span>
                @endif
            </div>
            <div class="p-6 flex-1 flex flex-col justify-between space-y-5">
                <div class="space-y-4">
                    <div class="p-3.5 rounded-xl border {{ ($seller->b2b_status ?? 'not_registered') === 'pending' ? 'bg-amber-50 border-amber-200 text-amber-900' : ($seller->b2b_enabled ? 'bg-orange-50/50 border-orange-100 text-gray-700' : 'bg-gray-50 border-gray-100 text-gray-500') }} text-xs leading-relaxed">
                        @if(($seller->b2b_status ?? 'not_registered') === 'pending')
                            <strong>Pengajuan Baru!</strong> Toko ini mengajukan pendaftaran Layanan Pesanan Proyek pada
                            <strong>{{ $seller->b2b_terms_agreed_at?->format('d M Y, H:i') ?? 'baru saja' }}</strong>
                            dan telah menyetujui S&K Kemitraan Proyek.
                        @elseif($seller->b2b_enabled)
                            Toko ini terdaftar sebagai <strong>Mitra Pabrik B2B</strong>, dapat menerima tender RFQ pengadaan mebel kustom, dan tampil di katalog vendor desainer interior.
                        @elseif(($seller->b2b_status ?? 'not_registered') === 'rejected')
                            Pengajuan Layanan Pesanan Proyek toko ini <strong>ditolak</strong>. Seller dapat mengajukan ulang setelah memperbaiki data workshop.
                        @else
                            Toko ini saat ini hanya melayani penjualan ritel reguler. Aktifkan modul B2B agar toko dapat menerima tender RFQ dari desainer interior.
                        @endif
                    </div>

                    @if(($seller->b2b_status ?? 'not_registered') === 'rejected' && $seller->b2b_rejection_reason)
                        <div class="p-3.5 rounded-xl border bg-red-50 border-red-200 text-red-800 text-xs leading-relaxed">
                            <span class="block font-black uppercase tracking-widest text-[9px] mb-1 text-red-500">Alasan Penolakan</span>
                            {{ $seller->b2b_rejection_reason }}
                        </div>
                    @endif

                    <div class="grid grid-cols-2 sm:grid-cols-3 gap-3">
                        <div class="bg-gray-50 border border-gray-100 rounded-xl p-3.5">
                            <span class="block font-black text-gray-400 uppercase tracking-widest text-[9px] mb-1">Kota Workshop</span>
                            <span class="text-xs font-bold text-gray-900">{{ $seller->city ?: '-' }}</span>
                        </div>
                        <div class="bg-gray-50 border border-gray-100 rounded-xl p-3.5">
                            <span class="block font-black text-gray-400 uppercase tracking-widest text-[9px] mb-1">Kapasitas Produksi</span>
                            <span class="text-xs font-bold text-gray-900">{{ $seller->monthly_capacity ? $seller->monthly_capacity . ' unit / bln' : '-' }}</span>
                        </div>
                        <div class="bg-gray-50 border border-gray-100 rounded-xl p-3.5 col-span-2 sm:col-span-1">
                            <span class="block font-black text-gray-400 uppercase tracking-widest text-[9px] mb-1">Sertifikasi SVLK</span>
                            <span class="text-xs font-bold {{ $seller->svlk_verified ? 'text-emerald-600' : 'text-gray-500' }}">
                                {{ $seller->svlk_verified ? 'Terverifikasi' : 'Belum Ada' }}
                            </span>
                        </div>
                    </div>

                    <div class="bg-gray-50 border border-gray-100 rounded-xl p-3.5">
                        <span class="block font-black text-gray-400 uppercase tracking-widest text-[9px] mb-2">Spesialisasi Material Kayu</span>
                        <div class="flex flex-wrap gap-1.5">
                            @forelse(($seller->wood_specialties ?? []) as $wood)
                                <span class="px-2.5 py-1 rounded-md bg-white border border-gray-200 text-[10px] font-bold text-gray-700 shadow-sm">{{ $wood }}</span>
                            @empty
                                <span class="text-xs text-gray-400 italic">Belum diatur</span>
                            @endforelse
                        </div>
                    </div>

                    @if($seller->portfolio_url)
                        <div class="bg-gray-50 border border-gray-100 rounded-xl p-3.5">
                            <span class="block font-black text-gray-400 uppercase tracking-widest text-[9px] mb-2">Foto Workshop / Bukti Portofolio</span>
                            <a href="{{ asset('storage/' . $seller->portfolio_url) }}" target="_blank" class="flex items-center gap-3 group">
                                <img src="{{ asset('storage/' . $seller->portfolio_url) }}" alt="Bukti Workshop" class="w-20 h-14 object-cover rounded-lg border border-gray-200 bg-white shrink-0 group-hover:opacity-90 transition-opacity">
                                <div>
                                    <span class="inline-flex items-center gap-1.5 text-xs font-bold text-primary group-hover:underline">
                                        <i data-feather="external-link" class="w-3.5 h-3.5"></i> Lihat Foto Ukuran Penuh
                                    </span>
                                    <p class="text-[11px] text-gray-400 mt-0.5">Klik thumbnail untuk membuka foto di tab baru.</p>
                                </div>
                            </a>
                        </div>
                    @endif

                    <div class="bg-gray-50 border border-gray-100 rounded-xl p-3.5 flex items-center justify-between gap-3">
                        <div>
                            <span class="block font-black text-gray-400 uppercase tracking-widest text-[9px] mb-1">Persetujuan S&K</span>
                            <span class="text-xs font-bold {{ $seller->b2b_terms_agreed_at ? 'text-emerald-600' : 'text-gray-500' }}">
                                {{ $seller->b2b_terms_agreed_at ? $seller->b2b_terms_agreed_at->format('d M Y, H:i') : 'Belum disetujui' }}
                            </span>
                        </div>
                        <span class="text-[10px] font-bold px-2 py-1 rounded-md bg-white border border-gray-200 text-gray-600">
                            {{ ($seller->b2b_status ?? 'not_registered') === 'approved' ? 'Disetujui' : (($seller->b2b_status ?? '') === 'pending' ? 'Menunggu' : (($seller->b2b_status ?? '') === 'rejected' ? 'Ditolak' : 'Belum ada')) }}
                        </span>
                    </div>
                </div>

                <div class="pt-2 border-t border-gray-100 space-y-3">
                    @if(($seller->b2b_status ?? 'not_registered') === 'pending')
                        <form action="{{ route('admin.seller-b2b.toggle', $seller->id) }}" method="POST">
                            @csrf
                            <button type="submit" class="w-full flex items-center justify-center gap-2 px-4 py-3 rounded-xl text-xs font-bold transition-colors shadow-sm bg-primary text-white hover:bg-[#8C4E1E]">
                                <i data-feather="check-circle" class="w-4 h-4"></i>
                                Setujui & Aktifkan Layanan Proyek
                            </button>
                        </form>
                        <button type="button" onclick="document.getElementById('b2bRejectBox').classList.toggle('hidden')" class="w-full flex items-center justify-center gap-2 px-4 py-2.5 rounded-xl text-xs font-bold bg-red-50 text-red-600 border border-red-200 hover:bg-red-100 transition-colors">
                            <i data-feather="x-circle" class="w-4 h-4"></i>
                            Tolak Pengajuan
                        </button>
                        <div id="b2bRejectBox" class="hidden pt-1">
                            <form action="{{ route('admin.seller-b2b.reject', $seller->id) }}" method="POST" class="space-y-2">
                                @csrf
                                <textarea name="b2b_rejection_reason" rows="3" required maxlength="500" class="w-full bg-gray-50 border border-gray-200 rounded-lg p-3 text-xs font-medium text-gray-700 focus:outline-none focus:border-primary focus:bg-white" placeholder="Tuliskan alasan penolakan agar seller dapat memperbaiki pengajuannya..."></textarea>
                                <button type="submit" class="w-full px-4 py-2.5 rounded-lg text-xs font-bold bg-red-600 text-white hover:bg-red-700 transition-colors">
                                    Kirim Penolakan
                                </button>
                            </form>
                        </div>
                    @else
                        <form action="{{ route('admin.seller-b2b.toggle', $seller->id) }}" method="POST">
                            @csrf
                            @if($seller->b2b_enabled)
                                <button type="submit" class="w-full flex items-center justify-center gap-2 px-4 py-3 rounded-xl text-xs font-bold transition-colors shadow-sm bg-red-50 text-red-600 border border-red-200 hover:bg-red-100">
                                    <i data-feather="x-circle" class="w-4 h-4"></i>
                                    Nonaktifkan Modul B2B
                                </button>
                            @else
                                <button type="submit" class="w-full flex items-center justify-center gap-2 px-4 py-3 rounded-xl text-xs font-bold transition-colors shadow-sm bg-primary text-white hover:bg-[#8C4E1E]">
                                    <i data-feather="check-circle" class="w-4 h-4"></i>
                                    Aktifkan Modul B2B untuk Toko Ini
                                </button>
                            @endif
                        </form>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Bottom Card: Daftar Produk yang Diupload (Tabel + Filter) -->
    <div class="bg-white border border-gray-100 rounded-xl shadow-sm overflow-hidden">
        <div class="p-5 border-b border-gray-100 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <h3 class="text-sm font-bold text-gray-900 flex items-center gap-2">
                    <i data-feather="package" class="w-4 h-4 text-primary"></i> Katalog Produk Toko ({{ $seller->products->count() }})
                </h3>
                <p class="text-[11px] text-gray-500 mt-0.5">Daftar seluruh produk yang diunggah oleh {{ $seller->store_name }}.</p>
            </div>
            <div class="relative w-full sm:w-64">
                <i data-feather="search" class="w-3.5 h-3.5 absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"></i>
                <input type="text" id="sellerProdSearch" placeholder="Cari nama produk..." class="w-full pl-9 pr-3 py-2 bg-gray-50 border border-gray-200 rounded-lg text-xs font-semibold focus:outline-none focus:border-primary focus:bg-white">
            </div>
        </div>

        @if($seller->products->isEmpty())
            <div class="text-center py-12 text-gray-400">
                <i data-feather="package" class="w-10 h-10 mx-auto mb-3 opacity-50"></i>
                <p class="text-xs font-semibold">Belum ada produk yang diupload oleh toko ini.</p>
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-gray-50/70 border-b border-gray-100 text-[9px] font-black text-gray-400 uppercase tracking-widest">
                            <th class="py-3.5 px-6">Produk</th>
                            <th class="py-3.5 px-6">Style / Material</th>
                            <th class="py-3.5 px-6">Harga</th>
                            <th class="py-3.5 px-6">Stok</th>
                            <th class="py-3.5 px-6">Status Kurasi</th>
                            <th class="py-3.5 px-6 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 text-xs" id="sellerProdBody">
                        @foreach($seller->products as $product)
                            @php
                                $imgUrl = $product->images->first()?->img_url ?? 'https://images.unsplash.com/photo-1555041469-a586c61ea9bc?w=400';
                                if (!\Illuminate\Support\Str::startsWith($imgUrl, ['/storage', 'storage', 'http'])) {
                                    $imgUrl = asset('storage/' . $imgUrl);
                                }
                                $pSt = strtolower($product->status ?? 'approved');
                                $pBadge = match($pSt) {
                                    'pending' => 'bg-amber-50 text-amber-600 border-amber-200',
                                    'approved' => 'bg-green-50 text-green-600 border-green-200',
                                    'rejected' => 'bg-red-50 text-red-600 border-red-200',
                                    default => 'bg-gray-50 text-gray-600 border-gray-200'
                                };
                                $detailPayload = [
                                    'id' => $product->id,
                                    'name' => $product->name,
                                    'price' => $product->price,
                                    'stock' => $product->stock,
                                    'style' => $product->style ?: '-',
                                    'material' => $product->material ?: '-',
                                    'description' => $product->description ?: '-',
                                    'status' => strtoupper($product->status ?? 'approved'),
                                    'image' => $imgUrl,
                                    'category' => $product->category->name ?? 'Uncategorized',
                                    'created' => optional($product->created_at)->format('d M Y H:i') ?? '-',
                                    'images' => $product->images->map(function ($im) {
                                        $u = $im->img_url;
                                        if (!\Illuminate\Support\Str::startsWith($u, ['/storage', 'storage', 'http'])) {
                                            $u = asset('storage/' . $u);
                                        }
                                        return $u;
                                    })->values(),
                                ];
                            @endphp
                            <tr class="hover:bg-gray-50/60 transition-colors seller-prod-row" data-search="{{ strtolower($product->name . ' ' . $product->style . ' ' . $product->material) }}">
                                <td class="py-3.5 px-6">
                                    <div class="flex items-center gap-3.5">
                                        <img src="{{ $imgUrl }}" class="w-12 h-12 rounded-xl object-cover border border-gray-100 shrink-0 cursor-pointer hover:opacity-80 transition-opacity js-product-detail" data-detail="{{ json_encode($detailPayload) }}">
                                        <div class="max-w-xs">
                                            <div class="font-bold text-gray-900 truncate cursor-pointer hover:text-primary transition-colors js-product-detail" data-detail="{{ json_encode($detailPayload) }}">{{ $product->name }}</div>
                                            <div class="text-[10px] text-gray-500 line-clamp-1 mt-0.5">{{ $product->description }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="py-3.5 px-6">
                                    <div class="font-semibold text-gray-800">{{ $product->style ?: '-' }}</div>
                                    <div class="text-[10px] text-gray-400">{{ $product->material ?: '-' }}</div>
                                </td>
                                <td class="py-3.5 px-6 font-bold text-primary">
                                    Rp {{ number_format($product->price, 0, ',', '.') }}
                                </td>
                                <td class="py-3.5 px-6 font-semibold text-gray-700">
                                    {{ $product->stock }} unit
                                </td>
                                <td class="py-3.5 px-6">
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-md text-[9px] font-black uppercase tracking-widest border {{ $pBadge }}">
                                        {{ strtoupper($product->status ?? 'APPROVED') }}
                                    </span>
                                </td>
                                <td class="py-3.5 px-6">
                                    <div class="flex items-center justify-end gap-1.5">
                                        <button type="button" class="w-8 h-8 flex items-center justify-center rounded-lg bg-orange-50 text-primary hover:bg-orange-100 transition-colors js-product-detail" data-detail="{{ json_encode($detailPayload) }}" title="Lihat detail produk">
                                            <i data-feather="eye" class="w-3.5 h-3.5"></i>
                                        </button>
                                        <form action="{{ route('admin.product.delete', $product->id) }}" method="POST" class="inline" onsubmit="return confirm('Hapus produk &quot;{{ addslashes($product->name) }}&quot; dari katalog seller? Tindakan ini permanen.')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="w-8 h-8 flex items-center justify-center rounded-lg bg-red-50 text-red-600 hover:bg-red-100 transition-colors" title="Hapus produk">
                                                <i data-feather="trash-2" class="w-3.5 h-3.5"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Pagination Bar (Per 5 Produk) -->
            <div class="px-6 py-4 border-t border-gray-100 bg-gray-50/50 flex flex-col sm:flex-row items-center justify-between gap-3">
                <div class="text-xs font-semibold text-gray-500" id="sellerProdPageInfo">Menampilkan katalog produk</div>
                <div class="flex items-center gap-1.5" id="sellerProdPagination"></div>
            </div>
        @endif
    </div>

    </div>
    @include("Admin.partials.footer")
</main>

<div id="rejectModal" class="modal">
    <div class="bg-white w-full max-w-md p-7 rounded-2xl shadow-2xl scale-95 transition-transform duration-300 transform" id="rejectModalContent">
        <form action="{{ route('admin.account.reject', $seller->id) }}" method="POST">
            @csrf
            <input type="hidden" name="type" value="seller">
            <h3 class="text-lg font-bold text-gray-900 mb-2">Reject Account</h3>
            <p class="text-xs text-gray-500 mb-5">Berikan alasan penolakan agar seller dapat memperbaiki datanya.</p>
            <textarea name="reason" required class="w-full h-32 p-4 border border-gray-200 rounded-xl mb-6 text-sm focus:outline-none focus:border-red-500 focus:ring-1 focus:ring-red-500 transition-shadow bg-gray-50" placeholder="Contoh: Nama toko mengandung kata terlarang, foto produk tidak jelas..."></textarea>
            <div class="flex gap-3 justify-end">
                <button type="button" onclick="closeRejectModal()" class="px-5 py-2.5 rounded-xl bg-gray-100 text-gray-700 font-bold text-xs hover:bg-gray-200 transition-colors">Batal</button>
                <button type="submit" class="px-5 py-2.5 rounded-xl bg-red-500 text-white font-bold text-xs hover:bg-red-600 transition-colors shadow-sm">Reject Account</button>
            </div>
        </form>
    </div>
</div>

<!-- ══════════ AUDIT / TEMPORARY BAN MODAL ══════════ -->
<div id="auditModal" class="modal">
    <div class="bg-white w-full max-w-md p-7 rounded-2xl shadow-2xl scale-95 transition-transform duration-300 transform" id="auditModalContent">
        <form action="{{ route('admin.user.warn', $seller->user_id) }}" method="POST">
            @csrf
            <div class="flex items-center gap-3 mb-4">
                <div class="w-11 h-11 rounded-xl bg-amber-100 text-amber-600 flex items-center justify-center shrink-0">
                    <i data-feather="shield" class="w-5 h-5"></i>
                </div>
                <div>
                    <h3 class="text-base font-bold text-gray-900">Tindakan Audit & Suspensi</h3>
                    <p class="text-[11px] text-gray-500">{{ $seller->store_name }} ({{ $seller->user->full_name }})</p>
                </div>
            </div>

            <div class="mb-4">
                <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2">Pilih Jenis Tindakan Audit</label>
                <div class="grid grid-cols-2 gap-2.5">
                    <label class="cursor-pointer">
                        <input type="radio" name="duration" value="7" class="peer hidden" checked>
                        <div class="text-center py-2.5 px-3 rounded-xl border border-gray-200 bg-gray-50 text-xs font-bold text-gray-600 peer-checked:bg-amber-50 peer-checked:border-amber-500 peer-checked:text-amber-700 transition-all">
                            Ban 7 Days
                        </div>
                    </label>
                    <label class="cursor-pointer">
                        <input type="radio" name="duration" value="14" class="peer hidden">
                        <div class="text-center py-2.5 px-3 rounded-xl border border-gray-200 bg-gray-50 text-xs font-bold text-gray-600 peer-checked:bg-amber-50 peer-checked:border-amber-500 peer-checked:text-amber-700 transition-all">
                            Ban 14 Days
                        </div>
                    </label>
                    <label class="cursor-pointer">
                        <input type="radio" name="duration" value="30" class="peer hidden">
                        <div class="text-center py-2.5 px-3 rounded-xl border border-gray-200 bg-gray-50 text-xs font-bold text-gray-600 peer-checked:bg-amber-50 peer-checked:border-amber-500 peer-checked:text-amber-700 transition-all">
                            Ban 30 Days
                        </div>
                    </label>
                    <label class="cursor-pointer">
                        <input type="radio" name="duration" value="deactivate" class="peer hidden">
                        <div class="text-center py-2.5 px-3 rounded-xl border border-red-200 bg-red-50/50 text-xs font-bold text-red-600 peer-checked:bg-red-600 peer-checked:border-red-600 peer-checked:text-white transition-all">
                            Nonaktifkan Akun
                        </div>
                    </label>
                </div>
            </div>

            <div class="mb-6">
                <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2">Alasan Audit / Suspensi</label>
                <textarea name="reason" required class="w-full h-28 p-3.5 border border-gray-200 rounded-xl text-xs focus:outline-none focus:border-amber-500 focus:ring-1 focus:ring-amber-500 bg-gray-50" placeholder="Contoh: Toko tidak aktif melakukan transaksi lebih dari 30 hari atau ditemukan ketidaksesuaian standar layanan..."></textarea>
            </div>

            <div class="flex gap-3 justify-end">
                <button type="button" onclick="closeAuditModal()" class="px-5 py-2.5 rounded-xl bg-gray-100 text-gray-700 font-bold text-xs hover:bg-gray-200 transition-colors">Batal</button>
                <button type="submit" class="px-5 py-2.5 rounded-xl bg-amber-600 text-white font-bold text-xs hover:bg-amber-700 transition-colors shadow-sm">Terapkan Sanksi Audit</button>
            </div>
        </form>
    </div>
</div>

<!-- ══════════ DETAIL PRODUK MODAL ══════════ -->
<div id="productDetailModal" class="modal">
    <div class="bg-white w-full max-w-3xl rounded-2xl shadow-2xl overflow-hidden flex flex-col md:flex-row max-h-[90vh] scale-95 transition-transform duration-300" id="productDetailContent">
        <div class="w-full md:w-1/2 bg-gray-100 relative min-h-[240px]">
            <img id="pdImage" src="" alt="" class="w-full h-full object-cover">
            <div class="absolute top-4 left-4">
                <span id="pdStatus" class="inline-flex px-2.5 py-1 rounded-md text-[9px] font-black uppercase tracking-widest bg-white/90 text-gray-700 shadow-sm"></span>
            </div>
        </div>
        <div class="w-full md:w-1/2 p-6 overflow-y-auto relative">
            <button type="button" onclick="closeProductDetail()" class="absolute top-4 right-4 w-8 h-8 flex items-center justify-center rounded-full bg-gray-100 text-gray-500 hover:bg-gray-200 transition-colors">
                <i data-feather="x" class="w-4 h-4"></i>
            </button>
            <div class="text-[10px] font-mono font-bold text-primary mb-1" id="pdId"></div>
            <h3 class="text-lg font-bold text-gray-900 pr-8 leading-snug" id="pdName"></h3>
            <div class="text-xl font-black text-primary mt-1.5" id="pdPrice"></div>

            <div class="grid grid-cols-2 gap-3 mt-5">
                <div class="bg-gray-50 p-3 rounded-xl border border-gray-100">
                    <div class="text-[9px] font-black text-gray-400 uppercase tracking-widest mb-1">Style</div>
                    <div class="text-xs font-bold text-gray-900" id="pdStyle"></div>
                </div>
                <div class="bg-gray-50 p-3 rounded-xl border border-gray-100">
                    <div class="text-[9px] font-black text-gray-400 uppercase tracking-widest mb-1">Material</div>
                    <div class="text-xs font-bold text-gray-900" id="pdMaterial"></div>
                </div>
                <div class="bg-gray-50 p-3 rounded-xl border border-gray-100">
                    <div class="text-[9px] font-black text-gray-400 uppercase tracking-widest mb-1">Stok</div>
                    <div class="text-xs font-bold text-gray-900" id="pdStock"></div>
                </div>
                <div class="bg-gray-50 p-3 rounded-xl border border-gray-100">
                    <div class="text-[9px] font-black text-gray-400 uppercase tracking-widest mb-1">Kategori</div>
                    <div class="text-xs font-bold text-gray-900" id="pdCategory"></div>
                </div>
            </div>

            <div class="mt-4 bg-gray-50 border border-gray-100 rounded-xl p-4">
                <div class="text-[9px] font-black text-gray-400 uppercase tracking-widest mb-1.5">Deskripsi</div>
                <div class="max-h-48 overflow-y-auto pr-1">
                    <p class="text-xs text-gray-600 leading-relaxed whitespace-pre-line" id="pdDesc"></p>
                </div>
            </div>

            <div class="mt-3 text-[10px] font-semibold text-gray-400" id="pdCreated"></div>

            <form id="pdDeleteForm" method="POST" class="mt-5" onsubmit="return confirm('Hapus produk ini dari katalog seller? Tindakan ini permanen.')">
                @csrf
                @method('DELETE')
                <button type="submit" class="w-full flex items-center justify-center gap-2 py-3 bg-red-50 hover:bg-red-100 text-red-600 rounded-xl text-xs font-bold transition-colors">
                    <i data-feather="trash-2" class="w-3.5 h-3.5"></i> Hapus Produk Ini
                </button>
            </form>
        </div>
    </div>
</div>

<script>
    feather.replace({ 'stroke-width': 2 });

    function openProductDetail(data) {
        document.getElementById('pdId').textContent = 'PRD-' + String(data.id).padStart(3, '0');
        document.getElementById('pdName').textContent = data.name || '-';
        document.getElementById('pdPrice').textContent = 'Rp ' + Number(data.price || 0).toLocaleString('id-ID');
        document.getElementById('pdStyle').textContent = data.style || '-';
        document.getElementById('pdMaterial').textContent = data.material || '-';
        document.getElementById('pdStock').textContent = (data.stock ?? 0) + ' unit';
        document.getElementById('pdCategory').textContent = data.category || '-';
        document.getElementById('pdDesc').textContent = data.description || '-';
        document.getElementById('pdCreated').textContent = 'Ditambahkan: ' + (data.created || '-');
        document.getElementById('pdStatus').textContent = data.status || '-';
        document.getElementById('pdImage').src = data.image || '';
        document.getElementById('pdDeleteForm').action = '{{ url('/admin/product') }}/' + data.id;

        const modal = document.getElementById('productDetailModal');
        const content = document.getElementById('productDetailContent');
        modal.classList.add('show');
        setTimeout(() => content.classList.remove('scale-95'), 10);
    }

    function closeProductDetail() {
        const modal = document.getElementById('productDetailModal');
        const content = document.getElementById('productDetailContent');
        content.classList.add('scale-95');
        setTimeout(() => modal.classList.remove('show'), 200);
    }

    document.querySelectorAll('.js-product-detail').forEach(function (el) {
        el.addEventListener('click', function () {
            try {
                openProductDetail(JSON.parse(el.dataset.detail));
            } catch (e) {
                console.error('detail payload tidak valid', e);
            }
        });
    });
</script>

<script>
    feather.replace({ 'stroke-width': 2 });
    function openRejectModal() { 
        const modal = document.getElementById('rejectModal');
        const content = document.getElementById('rejectModalContent');
        modal.classList.add('show'); 
        setTimeout(() => content.classList.remove('scale-95'), 10);
    }
    function closeRejectModal() { 
        const modal = document.getElementById('rejectModal');
        const content = document.getElementById('rejectModalContent');
        content.classList.add('scale-95');
        setTimeout(() => modal.classList.remove('show'), 200);
    }
    function openAuditModal() {
        const modal = document.getElementById('auditModal');
        const content = document.getElementById('auditModalContent');
        modal.classList.add('show');
        setTimeout(() => content.classList.remove('scale-95'), 10);
    }
    function closeAuditModal() {
        const modal = document.getElementById('auditModal');
        const content = document.getElementById('auditModalContent');
        content.classList.add('scale-95');
        setTimeout(() => modal.classList.remove('show'), 200);
    }

    const prodRows = Array.from(document.querySelectorAll('.seller-prod-row'));
    const prodSearch = document.getElementById('sellerProdSearch');
    const prodPageInfo = document.getElementById('sellerProdPageInfo');
    const prodPagination = document.getElementById('sellerProdPagination');
    const prodPerPage = 5;
    let prodCurrentPage = 1;

    function renderSellerProducts() {
        if (!prodRows.length) return;
        const term = (prodSearch?.value || '').toLowerCase().trim();
        const filtered = prodRows.filter(row => {
            const s = row.getAttribute('data-search') || '';
            return !term || s.includes(term);
        });

        const total = filtered.length;
        const totalPages = Math.max(1, Math.ceil(total / prodPerPage));
        if (prodCurrentPage > totalPages) prodCurrentPage = totalPages;

        prodRows.forEach(r => r.style.display = 'none');
        const start = (prodCurrentPage - 1) * prodPerPage;
        const end = Math.min(start + prodPerPage, total);
        filtered.slice(start, end).forEach(r => r.style.display = '');

        if (prodPageInfo) {
            prodPageInfo.textContent = total === 0
                ? 'Tidak ada produk yang cocok dengan pencarian'
                : `Halaman ${prodCurrentPage} dari ${totalPages} — Menampilkan ${start + 1}–${end} dari ${total} produk`;
        }

        if (prodPagination) {
            prodPagination.innerHTML = '';
            if (totalPages > 1) {
                const prevBtn = document.createElement('button');
                prevBtn.innerHTML = '&laquo;';
                prevBtn.disabled = prodCurrentPage === 1;
                prevBtn.className = `px-3 h-8 rounded-lg text-xs font-bold border border-gray-200 bg-white text-gray-600 disabled:opacity-40 hover:border-primary hover:text-primary`;
                prevBtn.onclick = () => { if (prodCurrentPage > 1) { prodCurrentPage--; renderSellerProducts(); } };
                prodPagination.appendChild(prevBtn);

                for (let i = 1; i <= totalPages; i++) {
                    const btn = document.createElement('button');
                    btn.textContent = i;
                    btn.className = `w-8 h-8 rounded-lg text-xs font-bold transition-colors ${i === prodCurrentPage ? 'bg-primary text-white shadow-sm' : 'bg-white border border-gray-200 text-gray-600 hover:border-primary hover:text-primary'}`;
                    btn.onclick = () => { prodCurrentPage = i; renderSellerProducts(); };
                    prodPagination.appendChild(btn);
                }

                const nextBtn = document.createElement('button');
                nextBtn.innerHTML = '&raquo;';
                nextBtn.disabled = prodCurrentPage === totalPages;
                nextBtn.className = `px-3 h-8 rounded-lg text-xs font-bold border border-gray-200 bg-white text-gray-600 disabled:opacity-40 hover:border-primary hover:text-primary`;
                nextBtn.onclick = () => { if (prodCurrentPage < totalPages) { prodCurrentPage++; renderSellerProducts(); } };
                prodPagination.appendChild(nextBtn);
            }
        }
    }

    prodSearch?.addEventListener('input', () => { prodCurrentPage = 1; renderSellerProducts(); });
    renderSellerProducts();
</script>

<script>
    const btn = document.getElementById("toggle-sidebar");
    const sidebar = document.getElementById("sidebar");
    const main = document.getElementById("main-content");
    if(btn) {
        btn.addEventListener("click", () => { 
            sidebar.classList.toggle("sidebar-hidden"); 
            main.classList.toggle("main-expanded"); 
        });
    }
</script>
<script async defer data-website-id="cd56ccb0-617d-4bec-8e77-0ba7ea566e9a" data-host-url="/umami-analytics" src="/umami-analytics/script.js"></script>
</body>
</html>
