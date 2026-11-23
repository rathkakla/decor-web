@php
$admin_name = Auth::user()->full_name;
$admin_role = "ADMIN";
@endphp
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Designer Detail — {{ $designer->user->full_name }} | DECOR Admin</title>
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
    @include("Admin.partials.header", ["title" => "Designer Detail"])
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

    <!-- Breadcrumb (DESIGNER) -->
    <div class="flex items-center justify-between">
        <div class="flex items-center gap-2 text-xs font-bold text-gray-500">
            <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-1 hover:text-primary transition-colors"><i data-feather="home" class="w-3.5 h-3.5"></i> Dashboard</a>
            <span class="text-gray-300">/</span>
            @if($designer->status === 'approved')
                <a href="{{ route('admin.designer-monitor') }}" class="flex items-center gap-1 hover:text-primary transition-colors"><i data-feather="pen-tool" class="w-3.5 h-3.5"></i> Designer Monitor</a>
            @else
                <a href="{{ route('admin.account.validation', ['type' => 'designer']) }}" class="flex items-center gap-1 hover:text-primary transition-colors"><i data-feather="shield" class="w-3.5 h-3.5"></i> Account Validation</a>
            @endif
            <span class="text-gray-300">/</span>
            <span class="text-primary">{{ $designer->user->full_name }}</span>
        </div>
        <a href="{{ $designer->status === 'approved' ? route('admin.designer-monitor') : route('admin.account.validation', ['type' => 'designer']) }}" class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-lg bg-white border border-gray-200 text-xs font-bold text-gray-600 hover:text-primary hover:border-primary/40 transition-colors shadow-sm">
            <i data-feather="arrow-left" class="w-3.5 h-3.5"></i> Kembali
        </a>
    </div>

    @if(isset($isWarning) && $isWarning)
    <!-- Banner Audit & Suspensi (Muncul Otomatis saat Designer Status Warning) -->
    <div class="bg-amber-50/90 border border-amber-200 rounded-xl p-6 flex flex-col md:flex-row gap-6 items-center shadow-sm">
        <div class="flex items-start gap-4 flex-1">
            <div class="w-11 h-11 rounded-xl bg-amber-100 text-amber-600 flex items-center justify-center shrink-0">
                <i data-feather="alert-triangle" class="w-5 h-5"></i>
            </div>
            <div>
                <div class="flex items-center gap-2 mb-0.5">
                    <h3 class="text-base font-bold text-amber-900">Status Desainer: Warning (Perlu Tindakan Audit)</h3>
                    <span class="px-2 py-0.5 rounded text-[9px] font-black uppercase tracking-widest bg-amber-200/70 text-amber-800">Audit Required</span>
                </div>
                <p class="text-xs text-amber-700 leading-relaxed">Desainer ini belum memiliki proyek selesai atau membutuhkan evaluasi kepatuhan layanan. Anda dapat menjatuhkan sanksi Audit (Suspensi Sementara 7 / 14 / 30 Hari).</p>
            </div>
        </div>
        <div class="shrink-0">
            <button type="button" onclick="openAuditModal()" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl text-xs font-bold bg-amber-600 hover:bg-amber-700 text-white transition-colors shadow-sm">
                <i data-feather="shield" class="w-4 h-4"></i> Tindakan Audit
            </button>
        </div>
    </div>
    @endif

    @if($designer->status == 'pending')
    <div class="bg-amber-50 border border-amber-200 rounded-xl p-6 flex flex-col md:flex-row gap-6 items-center shadow-sm">
        <div class="flex items-start gap-4 flex-1">
            <div class="w-10 h-10 rounded-xl bg-amber-100 text-amber-600 flex items-center justify-center shrink-0">
                <i data-feather="alert-circle" class="w-5 h-5"></i>
            </div>
            <div>
                <h3 class="text-base font-bold text-amber-800 mb-0.5">Designer Profile Review Pending</h3>
                <p class="text-xs text-amber-700 leading-relaxed">Mohon tinjau portofolio, latar belakang pendidikan, dan informasi keahlian desainer ini sebelum memberikan persetujuan aktivasi akun.</p>
            </div>
        </div>
        <div class="flex gap-3 shrink-0">
            <form action="{{ route('admin.account.approve', $designer->id) }}" method="POST">
                @csrf
                <input type="hidden" name="type" value="designer">
                <button type="submit" class="flex items-center gap-2 px-5 py-2.5 rounded-lg text-xs font-bold bg-green-600 hover:bg-green-700 text-white transition-colors shadow-sm">
                    <i data-feather="check" class="w-4 h-4"></i> Approve Profile
                </button>
            </form>
            <button type="button" onclick="openRejectModal()" class="flex items-center gap-2 px-5 py-2.5 rounded-lg text-xs font-bold bg-red-500 hover:bg-red-600 text-white transition-colors shadow-sm">
                <i data-feather="x" class="w-4 h-4"></i> Reject Profile
            </button>
        </div>
    </div>
    @endif

    <!-- Hero Profile Card -->
    <div class="bg-white border border-gray-100 rounded-xl p-7 shadow-sm relative overflow-hidden">
        <div class="absolute top-0 left-0 right-0 h-1 {{ $designer->status == 'pending' ? 'bg-amber-500' : ($designer->status == 'approved' ? 'bg-green-500' : 'bg-red-500') }}"></div>
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
            <div class="flex items-center gap-5">
                <img src="{{ $designer->designer_image ? asset('storage/'.$designer->designer_image) : 'https://ui-avatars.com/api/?name='.urlencode($designer->user->full_name).'&background=B5733A&color=fff' }}" class="w-16 h-16 rounded-2xl object-cover border border-gray-200 shrink-0 shadow-sm">
                <div>
                    <h2 class="text-2xl font-bold text-gray-900 mb-1.5">{{ $designer->user->full_name }}</h2>
                    <div class="flex items-center gap-3 flex-wrap">
                        <span class="font-mono text-[10px] text-gray-500 bg-gray-50 border border-gray-200 px-2.5 py-1 rounded-md">ID: DES-{{ str_pad($designer->id, 4, '0', STR_PAD_LEFT) }}</span>
                        <span class="flex items-center gap-1.5 text-[11px] font-semibold text-gray-500"><i data-feather="briefcase" class="w-3.5 h-3.5 text-primary"></i> {{ $designer->studio_name ?? 'Independent Studio' }}</span>
                        <span class="flex items-center gap-1.5 text-[11px] font-semibold text-gray-500"><i data-feather="award" class="w-3.5 h-3.5 text-primary"></i> {{ $designer->specialty ?? 'General Designer' }}</span>
                        <span class="flex items-center gap-1.5 text-[11px] font-semibold text-gray-400"><i data-feather="calendar" class="w-3.5 h-3.5"></i> Joined {{ $designer->created_at->format('d M Y') }}</span>
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
                    $statusCls = match(strtolower($designer->status)) {
                        'pending' => 'bg-amber-50 text-amber-600 border border-amber-200',
                        'approved' => 'bg-green-50 text-green-600 border border-green-200',
                        'rejected' => 'bg-red-50 text-red-600 border border-red-200',
                        default => 'bg-gray-50 text-gray-600 border border-gray-200'
                    };
                @endphp
                <span class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-lg text-[10px] font-black uppercase tracking-widest {{ $statusCls }}">
                    <i data-feather="info" class="w-3.5 h-3.5"></i> {{ strtoupper($designer->status) }}
                </span>
            </div>
        </div>
    </div>

    <!-- Top Grid: Informasi Studio & Kualifikasi Profesional Side-by-Side -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 items-stretch">
        <!-- Card 1: Profil & Biografi -->
        <div class="bg-white border border-gray-100 rounded-xl shadow-sm overflow-hidden flex flex-col">
            <div class="p-5 border-b border-gray-100 flex items-center justify-between bg-gray-50/40">
                <h3 class="text-sm font-bold text-gray-900 flex items-center gap-2"><i data-feather="user" class="w-4 h-4 text-primary"></i> Profil & Biografi Desainer</h3>
                <span class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">{{ $designer->experience_years ?? 0 }} Tahun Pengalaman</span>
            </div>
            <div class="p-6 space-y-4 flex-1 flex flex-col justify-between">
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div class="bg-gray-50 p-3.5 rounded-xl border border-gray-100">
                        <label class="block text-[9px] font-black text-gray-400 uppercase tracking-widest mb-1">Nama Studio</label>
                        <div class="text-xs font-bold text-gray-900">{{ $designer->studio_name ?: 'Independent Designer' }}</div>
                    </div>
                    <div class="bg-gray-50 p-3.5 rounded-xl border border-gray-100">
                        <label class="block text-[9px] font-black text-gray-400 uppercase tracking-widest mb-1">Email</label>
                        <div class="text-xs font-bold text-gray-900 truncate">{{ $designer->user->email }}</div>
                    </div>
                </div>
                <div>
                    <label class="block text-[9px] font-black text-gray-400 uppercase tracking-widest mb-1.5">Specialty / Gaya Desain</label>
                    <div class="text-xs font-bold text-primary bg-orange-50/60 p-3.5 rounded-xl border border-orange-100">{{ $designer->specialty ?? 'Tidak ada data' }}</div>
                </div>
                <div>
                    <label class="block text-[9px] font-black text-gray-400 uppercase tracking-widest mb-1.5">Bio Singkat</label>
                    <div class="text-xs text-gray-600 leading-relaxed bg-gray-50 p-3.5 rounded-xl border border-gray-100">{{ $designer->bio ?? 'Tidak ada data' }}</div>
                </div>
            </div>
        </div>

        <!-- Card 2: Pendidikan, Penghargaan & Informasi Bank -->
        <div class="bg-white border border-gray-100 rounded-xl shadow-sm overflow-hidden flex flex-col">
            <div class="p-5 border-b border-gray-100 flex items-center justify-between bg-gray-50/40">
                <h3 class="text-sm font-bold text-gray-900 flex items-center gap-2"><i data-feather="award" class="w-4 h-4 text-primary"></i> Kredensial & Akun Pencairan</h3>
                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-md text-[9px] font-black uppercase tracking-widest {{ $designer->is_open ? 'bg-green-50 text-green-600' : 'bg-gray-100 text-gray-500' }}">
                    {{ $designer->is_open ? 'Open for Project' : 'Closed' }}
                </span>
            </div>
            <div class="p-6 space-y-4 flex-1 flex flex-col justify-between">
                <div>
                    <label class="block text-[9px] font-black text-gray-400 uppercase tracking-widest mb-1.5">Riwayat Pendidikan</label>
                    <div class="text-xs text-gray-700 font-semibold leading-relaxed bg-gray-50 p-3.5 rounded-xl border border-gray-100">{{ $designer->education ?? 'Tidak ada data' }}</div>
                </div>
                <div>
                    <label class="block text-[9px] font-black text-gray-400 uppercase tracking-widest mb-1.5">Penghargaan & Sertifikasi</label>
                    <div class="text-xs text-gray-700 font-semibold leading-relaxed bg-gray-50 p-3.5 rounded-xl border border-gray-100">{{ $designer->awards ?? 'Tidak ada data' }}</div>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div class="bg-gray-50 p-3.5 rounded-xl border border-gray-100">
                        <label class="block text-[9px] font-black text-gray-400 uppercase tracking-widest mb-1">Bank Pencairan</label>
                        <div class="text-xs font-bold text-gray-900">{{ $designer->bank_name ?: '-' }}</div>
                    </div>
                    <div class="bg-gray-50 p-3.5 rounded-xl border border-gray-100">
                        <label class="block text-[9px] font-black text-gray-400 uppercase tracking-widest mb-1">Nomor Rekening</label>
                        <div class="text-xs font-mono font-bold text-gray-900">{{ $designer->account_number ?: '-' }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bottom Card: Daftar Portofolio Desainer (Tabel + Filter) -->
    <div class="bg-white border border-gray-100 rounded-xl shadow-sm overflow-hidden">
        <div class="p-5 border-b border-gray-100 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <h3 class="text-sm font-bold text-gray-900 flex items-center gap-2"><i data-feather="image" class="w-4 h-4 text-primary"></i> Karya Portofolio ({{ $designer->portfolios->count() }})</h3>
                <p class="text-[11px] text-gray-500 mt-0.5">Daftar karya desain yang diunggah oleh {{ $designer->user->full_name }}.</p>
            </div>
            <div class="relative w-full sm:w-64">
                <i data-feather="search" class="w-3.5 h-3.5 absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"></i>
                <input type="text" id="designerPortSearch" placeholder="Cari judul portofolio..." class="w-full pl-9 pr-3 py-2 bg-gray-50 border border-gray-200 rounded-lg text-xs font-semibold focus:outline-none focus:border-primary focus:bg-white">
            </div>
        </div>

        @if($designer->portfolios->isEmpty())
            <div class="text-center py-12 text-gray-400">
                <i data-feather="image" class="w-10 h-10 mx-auto mb-3 opacity-50"></i>
                <p class="text-xs font-semibold">Belum ada portofolio yang diupload.</p>
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-gray-50/70 border-b border-gray-100 text-[9px] font-black text-gray-400 uppercase tracking-widest">
                            <th class="py-3.5 px-6">Karya Portofolio</th>
                            <th class="py-3.5 px-6">Kategori</th>
                            <th class="py-3.5 px-6">Area & Durasi</th>
                            <th class="py-3.5 px-6">Estimasi Budget</th>
                            <th class="py-3.5 px-6">Status Kurasi</th>
                            <th class="py-3.5 px-6 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 text-xs">
                        @foreach($designer->portfolios as $port)
                            @php
                                $portImg = storage_url($port->image_url, 'https://images.unsplash.com/photo-1600210492486-724fe5c67fb0?w=600');
                                $pfSt = strtolower($port->status ?? 'pending');
                                $pfBadge = match($pfSt) {
                                    'pending' => 'bg-amber-50 text-amber-600 border-amber-200',
                                    'approved' => 'bg-green-50 text-green-600 border-green-200',
                                    'rejected' => 'bg-red-50 text-red-600 border-red-200',
                                    default => 'bg-gray-50 text-gray-600 border-gray-200'
                                };
                                $portPayload = [
                                    'id' => $port->id,
                                    'title' => $port->title,
                                    'image' => $portImg,
                                    'category' => $port->category ?: '-',
                                    'budget' => $port->budget ?: '-',
                                    'area' => $port->area ?: '-',
                                    'duration' => $port->duration ?: '-',
                                    'description' => $port->description ?: '-',
                                    'status' => strtoupper($port->status ?? 'pending'),
                                    'created' => optional($port->created_at)->format('d M Y H:i') ?? '-',
                                    'from_consultation' => (bool) ($port->consultation_id ?? false),
                                ];
                            @endphp
                            <tr class="hover:bg-gray-50/60 transition-colors designer-port-row" data-search="{{ strtolower($port->title . ' ' . $port->category) }}">
                                <td class="py-3.5 px-6">
                                    <div class="flex items-center gap-3.5">
                                        <img src="{{ $portImg }}" class="w-12 h-12 rounded-xl object-cover border border-gray-100 shrink-0 cursor-pointer hover:opacity-80 transition-opacity js-portfolio-detail" data-detail="{{ json_encode($portPayload) }}">
                                        <div class="max-w-xs">
                                            <div class="font-bold text-gray-900 truncate cursor-pointer hover:text-primary transition-colors js-portfolio-detail" data-detail="{{ json_encode($portPayload) }}">{{ $port->title }}</div>
                                            <div class="text-[10px] text-gray-500 line-clamp-1 mt-0.5">{{ $port->description }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="py-3.5 px-6">
                                    <span class="px-2.5 py-1 rounded-md text-[9px] font-black text-gray-600 bg-gray-100 uppercase tracking-widest">{{ $port->category ?: '-' }}</span>
                                </td>
                                <td class="py-3.5 px-6">
                                    <div class="font-bold text-gray-800">{{ $port->area ?: '-' }}</div>
                                    <div class="text-[10px] text-gray-400">Durasi: {{ $port->duration ?: '-' }}</div>
                                </td>
                                <td class="py-3.5 px-6 font-bold text-primary">
                                    {{ $port->budget ?: '-' }}
                                </td>
                                <td class="py-3.5 px-6">
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-md text-[9px] font-black uppercase tracking-widest border {{ $pfBadge }}">
                                        {{ strtoupper($port->status ?? 'PENDING') }}
                                    </span>
                                </td>
                                <td class="py-3.5 px-6">
                                    <div class="flex items-center justify-end gap-1.5">
                                        <button type="button" class="w-8 h-8 flex items-center justify-center rounded-lg bg-orange-50 text-primary hover:bg-orange-100 transition-colors js-portfolio-detail" data-detail="{{ json_encode($portPayload) }}" title="Lihat detail karya">
                                            <i data-feather="eye" class="w-3.5 h-3.5"></i>
                                        </button>
                                        <form action="{{ route('admin.portfolio.delete', $port->id) }}" method="POST" class="inline" onsubmit="return confirm('Hapus karya &quot;{{ addslashes($port->title) }}&quot; dari portofolio desainer? Tindakan ini permanen.')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="w-8 h-8 flex items-center justify-center rounded-lg bg-red-50 text-red-600 hover:bg-red-100 transition-colors" title="Hapus karya">
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

            <!-- Pagination Bar (Per 5 Portofolio) -->
            <div class="px-6 py-4 border-t border-gray-100 bg-gray-50/50 flex flex-col sm:flex-row items-center justify-between gap-3">
                <div class="text-xs font-semibold text-gray-500" id="designerPortPageInfo">Menampilkan karya portofolio</div>
                <div class="flex items-center gap-1.5" id="designerPortPagination"></div>
            </div>
        @endif
    </div>

    </div>
    @include("Admin.partials.footer")
</main>

<div id="rejectModal" class="modal">
    <div class="bg-white w-full max-w-md p-7 rounded-2xl shadow-2xl scale-95 transition-transform duration-300 transform" id="rejectModalContent">
        <form action="{{ route('admin.account.reject', $designer->id) }}" method="POST">
            @csrf
            <input type="hidden" name="type" value="designer">
            <h3 class="text-lg font-bold text-gray-900 mb-2">Reject Profile</h3>
            <p class="text-xs text-gray-500 mb-5">Berikan alasan penolakan agar desainer dapat memperbaiki datanya.</p>
            <textarea name="reason" required class="w-full h-32 p-4 border border-gray-200 rounded-xl mb-6 text-sm focus:outline-none focus:border-red-500 focus:ring-1 focus:ring-red-500 transition-shadow bg-gray-50" placeholder="Contoh: Portofolio kurang berkualitas, Bio mengandung kata terlarang..."></textarea>
            <div class="flex gap-3 justify-end">
                <button type="button" onclick="closeRejectModal()" class="px-5 py-2.5 rounded-xl bg-gray-100 text-gray-700 font-bold text-xs hover:bg-gray-200 transition-colors">Batal</button>
                <button type="submit" class="px-5 py-2.5 rounded-xl bg-red-500 text-white font-bold text-xs hover:bg-red-600 transition-colors shadow-sm">Reject Profile</button>
            </div>
        </form>
    </div>
</div>

<!-- ══════════ AUDIT / TEMPORARY BAN MODAL ══════════ -->
<div id="auditModal" class="modal">
    <div class="bg-white w-full max-w-md p-7 rounded-2xl shadow-2xl scale-95 transition-transform duration-300 transform" id="auditModalContent">
        <form action="{{ route('admin.user.warn', $designer->user_id) }}" method="POST">
            @csrf
            <div class="flex items-center gap-3 mb-4">
                <div class="w-11 h-11 rounded-xl bg-amber-100 text-amber-600 flex items-center justify-center shrink-0">
                    <i data-feather="shield" class="w-5 h-5"></i>
                </div>
                <div>
                    <h3 class="text-base font-bold text-gray-900">Tindakan Audit & Suspensi</h3>
                    <p class="text-[11px] text-gray-500">{{ $designer->user->full_name }} ({{ $designer->studio_name ?? 'Interior Designer' }})</p>
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
                <textarea name="reason" required class="w-full h-28 p-3.5 border border-gray-200 rounded-xl text-xs focus:outline-none focus:border-amber-500 focus:ring-1 focus:ring-amber-500 bg-gray-50" placeholder="Contoh: Desainer tidak merespon konsultasi klien atau sedang dalam peninjauan kualitas layanan..."></textarea>
            </div>

            <div class="flex gap-3 justify-end">
                <button type="button" onclick="closeAuditModal()" class="px-5 py-2.5 rounded-xl bg-gray-100 text-gray-700 font-bold text-xs hover:bg-gray-200 transition-colors">Batal</button>
                <button type="submit" class="px-5 py-2.5 rounded-xl bg-amber-600 text-white font-bold text-xs hover:bg-amber-700 transition-colors shadow-sm">Terapkan Sanksi Audit</button>
            </div>
        </form>
    </div>
</div>

<!-- ══════════ DETAIL KARYA PORTOFOLIO MODAL ══════════ -->
<div id="portfolioDetailModal" class="modal">
    <div class="bg-white w-full max-w-3xl rounded-2xl shadow-2xl overflow-hidden flex flex-col md:flex-row max-h-[90vh] scale-95 transition-transform duration-300" id="portfolioDetailContent">
        <div class="w-full md:w-1/2 bg-gray-100 relative min-h-[240px]">
            <img id="pfImage" src="" alt="" class="w-full h-full object-cover">
            <div class="absolute top-4 left-4">
                <span id="pfStatus" class="inline-flex px-2.5 py-1 rounded-md text-[9px] font-black uppercase tracking-widest bg-white/90 text-gray-700 shadow-sm"></span>
            </div>
        </div>
        <div class="w-full md:w-1/2 p-6 overflow-y-auto relative">
            <button type="button" onclick="closePortfolioDetail()" class="absolute top-4 right-4 w-8 h-8 flex items-center justify-center rounded-full bg-gray-100 text-gray-500 hover:bg-gray-200 transition-colors">
                <i data-feather="x" class="w-4 h-4"></i>
            </button>
            <div class="text-[10px] font-mono font-bold text-primary mb-1" id="pfId"></div>
            <h3 class="text-lg font-bold text-gray-900 pr-8 leading-snug" id="pfTitle"></h3>
            <div class="text-xl font-black text-primary mt-1.5" id="pfBudget"></div>

            <div class="grid grid-cols-2 gap-3 mt-5">
                <div class="bg-gray-50 p-3 rounded-xl border border-gray-100">
                    <div class="text-[9px] font-black text-gray-400 uppercase tracking-widest mb-1">Kategori</div>
                    <div class="text-xs font-bold text-gray-900" id="pfCategory"></div>
                </div>
                <div class="bg-gray-50 p-3 rounded-xl border border-gray-100">
                    <div class="text-[9px] font-black text-gray-400 uppercase tracking-widest mb-1">Area</div>
                    <div class="text-xs font-bold text-gray-900" id="pfArea"></div>
                </div>
                <div class="bg-gray-50 p-3 rounded-xl border border-gray-100">
                    <div class="text-[9px] font-black text-gray-400 uppercase tracking-widest mb-1">Durasi</div>
                    <div class="text-xs font-bold text-gray-900" id="pfDuration"></div>
                </div>
                <div class="bg-gray-50 p-3 rounded-xl border border-gray-100">
                    <div class="text-[9px] font-black text-gray-400 uppercase tracking-widest mb-1">Sumber</div>
                    <div class="text-xs font-bold text-gray-900" id="pfSource"></div>
                </div>
            </div>

            <div class="mt-4 bg-gray-50 border border-gray-100 rounded-xl p-4">
                <div class="text-[9px] font-black text-gray-400 uppercase tracking-widest mb-1.5">Deskripsi Karya</div>
                <p class="text-xs text-gray-600 leading-relaxed" id="pfDesc"></p>
            </div>

            <div class="mt-3 text-[10px] font-semibold text-gray-400" id="pfCreated"></div>

            <form id="pfDeleteForm" method="POST" class="mt-5" onsubmit="return confirm('Hapus karya ini dari portofolio desainer? Tindakan ini permanen.')">
                @csrf
                @method('DELETE')
                <button type="submit" class="w-full flex items-center justify-center gap-2 py-3 bg-red-50 hover:bg-red-100 text-red-600 rounded-xl text-xs font-bold transition-colors">
                    <i data-feather="trash-2" class="w-3.5 h-3.5"></i> Hapus Karya Ini
                </button>
            </form>
        </div>
    </div>
</div>

<script>
    feather.replace({ 'stroke-width': 2 });

    function openPortfolioDetail(data) {
        document.getElementById('pfId').textContent = 'PTF-' + String(data.id).padStart(3, '0');
        document.getElementById('pfTitle').textContent = data.title || '-';
        document.getElementById('pfBudget').textContent = data.budget && data.budget !== '-' ? data.budget : 'Budget belum diisi';
        document.getElementById('pfCategory').textContent = data.category || '-';
        document.getElementById('pfArea').textContent = data.area || '-';
        document.getElementById('pfDuration').textContent = data.duration || '-';
        document.getElementById('pfSource').textContent = data.from_consultation ? 'Proyek konsultasi' : 'Upload manual';
        document.getElementById('pfDesc').textContent = data.description || '-';
        document.getElementById('pfCreated').textContent = 'Diunggah: ' + (data.created || '-');
        document.getElementById('pfStatus').textContent = data.status || '-';
        document.getElementById('pfImage').src = data.image || '';
        document.getElementById('pfDeleteForm').action = '{{ url('/admin/portfolio') }}/' + data.id;

        const modal = document.getElementById('portfolioDetailModal');
        const content = document.getElementById('portfolioDetailContent');
        modal.classList.add('show');
        setTimeout(() => content.classList.remove('scale-95'), 10);
    }

    function closePortfolioDetail() {
        const modal = document.getElementById('portfolioDetailModal');
        const content = document.getElementById('portfolioDetailContent');
        content.classList.add('scale-95');
        setTimeout(() => modal.classList.remove('show'), 200);
    }

    document.querySelectorAll('.js-portfolio-detail').forEach(function (el) {
        el.addEventListener('click', function () {
            try {
                openPortfolioDetail(JSON.parse(el.dataset.detail));
            } catch (e) {
                console.error('payload portofolio tidak valid', e);
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

    const portRows = Array.from(document.querySelectorAll('.designer-port-row'));
    const portSearch = document.getElementById('designerPortSearch');
    const portPageInfo = document.getElementById('designerPortPageInfo');
    const portPagination = document.getElementById('designerPortPagination');
    const portPerPage = 5;
    let portCurrentPage = 1;

    function renderDesignerPortfolios() {
        if (!portRows.length) return;
        const term = (portSearch?.value || '').toLowerCase().trim();
        const filtered = portRows.filter(row => {
            const s = row.getAttribute('data-search') || '';
            return !term || s.includes(term);
        });

        const total = filtered.length;
        const totalPages = Math.max(1, Math.ceil(total / portPerPage));
        if (portCurrentPage > totalPages) portCurrentPage = totalPages;

        portRows.forEach(r => r.style.display = 'none');
        const start = (portCurrentPage - 1) * portPerPage;
        const end = Math.min(start + portPerPage, total);
        filtered.slice(start, end).forEach(r => r.style.display = '');

        if (portPageInfo) {
            portPageInfo.textContent = total === 0
                ? 'Tidak ada portofolio yang cocok dengan pencarian'
                : `Halaman ${portCurrentPage} dari ${totalPages} — Menampilkan ${start + 1}–${end} dari ${total} karya`;
        }

        if (portPagination) {
            portPagination.innerHTML = '';
            if (totalPages > 1) {
                const prevBtn = document.createElement('button');
                prevBtn.innerHTML = '&laquo;';
                prevBtn.disabled = portCurrentPage === 1;
                prevBtn.className = `px-3 h-8 rounded-lg text-xs font-bold border border-gray-200 bg-white text-gray-600 disabled:opacity-40 hover:border-primary hover:text-primary`;
                prevBtn.onclick = () => { if (portCurrentPage > 1) { portCurrentPage--; renderDesignerPortfolios(); } };
                portPagination.appendChild(prevBtn);

                for (let i = 1; i <= totalPages; i++) {
                    const btn = document.createElement('button');
                    btn.textContent = i;
                    btn.className = `w-8 h-8 rounded-lg text-xs font-bold transition-colors ${i === portCurrentPage ? 'bg-primary text-white shadow-sm' : 'bg-white border border-gray-200 text-gray-600 hover:border-primary hover:text-primary'}`;
                    btn.onclick = () => { portCurrentPage = i; renderDesignerPortfolios(); };
                    portPagination.appendChild(btn);
                }

                const nextBtn = document.createElement('button');
                nextBtn.innerHTML = '&raquo;';
                nextBtn.disabled = portCurrentPage === totalPages;
                nextBtn.className = `px-3 h-8 rounded-lg text-xs font-bold border border-gray-200 bg-white text-gray-600 disabled:opacity-40 hover:border-primary hover:text-primary`;
                nextBtn.onclick = () => { if (portCurrentPage < totalPages) { portCurrentPage++; renderDesignerPortfolios(); } };
                portPagination.appendChild(nextBtn);
            }
        }
    }

    portSearch?.addEventListener('input', () => { portCurrentPage = 1; renderDesignerPortfolios(); });
    renderDesignerPortfolios();
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
</body>
</html>
