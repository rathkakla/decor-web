@php
$admin_name = Auth::user()->full_name;
$admin_role = "ADMIN";

$stats_view = [
    ["label" => "Pending Seller",    "value" => sprintf("%02d", $stats['pending_sellers']),   "icon" => "shopping-bag", "note" => "menunggu review",  "color" => "warning"],
    ["label" => "Pending Designer",  "value" => sprintf("%02d", $stats['pending_designers']), "icon" => "pen-tool",     "note" => "menunggu review",  "color" => "info"],
    ["label" => "Total Verified",    "value" => sprintf("%02d", $stats['total_verified'] ?? 0),"icon" => "check-circle","note" => "mitra disetujui",  "color" => "success"],
    ["label" => "Total Rejected",    "value" => sprintf("%02d", $stats['total_rejected'] ?? 0),"icon" => "x-circle",    "note" => "pengajuan ditolak","color" => "danger"],
];
@endphp
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Account Validation — DECOR Admin</title>
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
        .confirm-modal { position: fixed; inset: 0; background: rgba(28, 20, 16, 0.4); backdrop-filter: blur(4px); display: flex; align-items: center; justify-content: center; z-index: 3000; opacity: 0; pointer-events: none; transition: 0.2s; }
        .confirm-modal.show { opacity: 1; pointer-events: auto; }
    </style>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body class="text-gray-800 bg-[#F8F6F4]">

@include("Admin.partials.sidebar")

<main id="main-content" class="flex-1 flex flex-col ml-64 sidebar-transition min-h-screen bg-[#F8F6F4]">
    @include("Admin.partials.header", ["title" => "Account Validation"])
    <div class="p-8 space-y-6 flex-1">

    @if(session('success'))
        <div class="bg-green-50 border border-green-200 text-green-600 px-6 py-4 rounded-xl text-xs font-bold flex items-center">
            <i data-feather="check-circle" class="w-4 h-4 mr-3"></i>
            {{ session('success') }}
        </div>
    @endif

    <div>
        <div class="text-[10px] font-black text-primary uppercase tracking-widest mb-1">Onboarding Control</div>
        <div class="text-2xl font-bold text-gray-900">Account Validation</div>
        <div class="text-xs text-gray-500 mt-1">Review pendaftaran {{ $type }} baru sebelum mereka dapat berjualan atau menawarkan jasa.</div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        @foreach ($stats_view as $s)
        <div class="bg-white p-6 rounded-xl border border-gray-100 shadow-sm transition-transform hover:-translate-y-1 relative overflow-hidden">
            @php
                $borderColor = match($s['color']) {
                    'warning' => 'bg-amber-600',
                    'info' => 'bg-blue-600',
                    'success' => 'bg-green-600',
                    'danger' => 'bg-red-600',
                    default => 'bg-primary'
                };
                $iconBg = match($s['color']) {
                    'warning' => 'bg-amber-50 text-amber-600',
                    'info' => 'bg-blue-50 text-blue-600',
                    'success' => 'bg-green-50 text-green-600',
                    'danger' => 'bg-red-50 text-red-600',
                    default => 'bg-orange-50 text-primary'
                };
            @endphp
            <div class="absolute bottom-0 left-0 right-0 h-1 {{ $borderColor }}"></div>
            <div class="w-10 h-10 rounded-lg {{ $iconBg }} flex items-center justify-center mb-4">
                <i data-feather="{{ $s['icon'] }}" class="w-5 h-5"></i>
            </div>
            <h3 class="text-2xl font-bold text-gray-900">{{ $s['value'] }}</h3>
            <p class="text-[10px] text-gray-400 font-bold uppercase tracking-wider mt-1">{{ $s['label'] }}</p>
            <p class="text-[10px] font-black text-gray-500 mt-1">{{ $s['note'] }}</p>
        </div>
        @endforeach
    </div>

    <!-- Table Card with Filter & Pagination -->
    <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
        <!-- Toolbar -->
        <div class="p-5 border-b border-gray-100 flex flex-col lg:flex-row lg:items-center justify-between gap-4">
            <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-3 flex-1">
                <div class="flex gap-1 bg-gray-50 border border-gray-200 rounded-xl p-1 shrink-0">
                    <a href="{{ route('admin.account.validation', ['type' => 'seller', 'status' => $status]) }}" class="px-4 py-1.5 rounded-lg text-xs font-bold transition-colors {{ $type == 'seller' ? 'bg-white text-primary shadow-sm' : 'text-gray-500 hover:text-primary' }}">Sellers</a>
                    <a href="{{ route('admin.account.validation', ['type' => 'designer', 'status' => $status]) }}" class="px-4 py-1.5 rounded-lg text-xs font-bold transition-colors {{ $type == 'designer' ? 'bg-white text-primary shadow-sm' : 'text-gray-500 hover:text-primary' }}">Designers</a>
                </div>
                <div class="relative flex-1 max-w-sm">
                    <i data-feather="search" class="w-4 h-4 absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-400"></i>
                    <input type="text" id="accountSearch" class="w-full pl-10 pr-4 py-2 bg-gray-50 border border-gray-200 rounded-xl text-xs font-semibold focus:outline-none focus:border-primary focus:bg-white transition-colors" placeholder="Cari nama, email, atau lokasi/spesialisasi…">
                </div>
            </div>

            <div class="flex gap-1 bg-gray-50 border border-gray-200 rounded-xl p-1 self-start lg:self-auto">
                <a href="{{ route('admin.account.validation', ['type' => $type, 'status' => 'semua']) }}" class="px-3.5 py-1.5 rounded-lg text-[10px] font-bold uppercase tracking-widest transition-colors {{ $status == 'semua' ? 'bg-white text-primary shadow-sm' : 'text-gray-500 hover:text-primary' }}">Semua</a>
                <a href="{{ route('admin.account.validation', ['type' => $type, 'status' => 'pending']) }}" class="px-3.5 py-1.5 rounded-lg text-[10px] font-bold uppercase tracking-widest transition-colors {{ $status == 'pending' ? 'bg-white text-primary shadow-sm' : 'text-gray-500 hover:text-primary' }}">Pending</a>
                <a href="{{ route('admin.account.validation', ['type' => $type, 'status' => 'approved']) }}" class="px-3.5 py-1.5 rounded-lg text-[10px] font-bold uppercase tracking-widest transition-colors {{ $status == 'approved' ? 'bg-white text-primary shadow-sm' : 'text-gray-500 hover:text-primary' }}">Approved</a>
                <a href="{{ route('admin.account.validation', ['type' => $type, 'status' => 'rejected']) }}" class="px-3.5 py-1.5 rounded-lg text-[10px] font-bold uppercase tracking-widest transition-colors {{ $status == 'rejected' ? 'bg-white text-primary shadow-sm' : 'text-gray-500 hover:text-primary' }}">Rejected</a>
            </div>
        </div>

        <!-- Data Table -->
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50/70 border-b border-gray-100 text-[9px] font-black text-gray-400 uppercase tracking-widest">
                        <th class="py-3.5 px-6">{{ $type == 'seller' ? 'Toko / Seller' : 'Desainer' }}</th>
                        <th class="py-3.5 px-6">{{ $type == 'seller' ? 'Pemilik & Email' : 'Studio & Email' }}</th>
                        <th class="py-3.5 px-6">{{ $type == 'seller' ? 'Alamat & Kota' : 'Spesialisasi' }}</th>
                        <th class="py-3.5 px-6">Tanggal Daftar</th>
                        <th class="py-3.5 px-6">Status</th>
                        <th class="py-3.5 px-6 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 text-xs" id="accountTableBody">
                    @forelse ($accounts as $acc)
                    @php
                        $avatarUrl = $type == 'seller'
                            ? $acc->store_image_url
                            : ($acc->designer_image ? asset('storage/'.$acc->designer_image) : 'https://ui-avatars.com/api/?name='.urlencode($acc->user->full_name).'&background=B5733A&color=fff');
                        $displayName = $type == 'seller' ? $acc->store_name : $acc->user->full_name;
                        $subInfo = $type == 'seller' ? ($acc->store_address ?? '-') : ($acc->specialty ?? '-');
                        $statusCls = match($acc->status) {
                            'pending' => 'bg-amber-50 text-amber-600 border-amber-200',
                            'approved' => 'bg-green-50 text-green-600 border-green-200',
                            'rejected' => 'bg-red-50 text-red-600 border-red-200',
                            default => 'bg-gray-50 text-gray-600 border-gray-200'
                        };
                    @endphp
                    <tr class="account-row hover:bg-gray-50/70 transition-colors"
                        data-search="{{ strtolower($displayName . ' ' . $acc->user->full_name . ' ' . $acc->user->email . ' ' . $subInfo) }}">
                        <td class="py-3.5 px-6">
                            <div class="flex items-center gap-3.5">
                                <img src="{{ $avatarUrl }}" class="w-11 h-11 rounded-xl object-cover border border-gray-100 shrink-0">
                                <div>
                                    <span class="inline-block px-2 py-0.5 bg-orange-50 text-primary text-[9px] font-black uppercase tracking-widest rounded font-mono mb-0.5">{{ $type == 'seller' ? 'SEL' : 'DES' }}-{{ str_pad($acc->id, 3, '0', STR_PAD_LEFT) }}</span>
                                    <div class="font-bold text-gray-900">{{ $displayName }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="py-3.5 px-6">
                            <div class="font-bold text-gray-800">{{ $type == 'seller' ? $acc->user->full_name : ($acc->studio_name ?: 'Independent Studio') }}</div>
                            <div class="text-[10px] font-mono text-gray-400 mt-0.5">{{ $acc->user->email }}</div>
                        </td>
                        <td class="py-3.5 px-6 max-w-[240px]">
                            <div class="font-semibold text-gray-800 truncate">{{ $subInfo }}</div>
                            @if($type == 'seller' && $acc->b2b_enabled)
                                <span class="inline-flex items-center gap-1 mt-1 px-2 py-0.5 rounded text-[8px] font-black uppercase tracking-widest bg-emerald-50 text-emerald-600">B2B Ready</span>
                            @elseif($type == 'designer')
                                <div class="text-[10px] text-gray-400 mt-0.5">{{ $acc->experience_years ?? 0 }} Tahun Pengalaman</div>
                            @endif
                        </td>
                        <td class="py-3.5 px-6 font-semibold text-gray-500">
                            {{ $acc->created_at->format('d M Y') }}
                        </td>
                        <td class="py-3.5 px-6">
                            <span class="inline-flex items-center px-2.5 py-1 rounded-md text-[9px] font-black uppercase tracking-widest border {{ $statusCls }}">
                                {{ strtoupper($acc->status) }}
                            </span>
                            @if($acc->status == 'rejected' && $acc->rejection_reason)
                                <div class="text-[10px] text-red-500 font-semibold mt-1 max-w-[160px] truncate" title="{{ $acc->rejection_reason }}">
                                    Alasan: {{ $acc->rejection_reason }}
                                </div>
                            @endif
                        </td>
                        <td class="py-3.5 px-6 text-right">
                            <div class="flex items-center justify-end gap-1.5">
                                <a href="{{ $type == 'seller' ? route('admin.seller-detail', $acc->id) : route('admin.designer-detail', $acc->id) }}"
                                   class="inline-flex items-center px-3 py-1.5 rounded-lg text-[10px] font-bold bg-orange-50 text-primary hover:bg-orange-100 transition-colors">
                                    <i data-feather="eye" class="w-3.5 h-3.5 mr-1"></i> Detail
                                </a>
                                @if($acc->status == 'pending')
                                <form action="{{ route('admin.account.approve', $acc->id) }}" method="POST" class="inline">
                                    @csrf
                                    <input type="hidden" name="type" value="{{ $type }}">
                                    <button type="submit" class="w-8 h-8 flex items-center justify-center rounded-lg bg-green-50 text-green-600 hover:bg-green-100 transition-colors" title="Approve">
                                        <i data-feather="check" class="w-3.5 h-3.5"></i>
                                    </button>
                                </form>
                                <button type="button" class="w-8 h-8 flex items-center justify-center rounded-lg bg-red-50 text-red-600 hover:bg-red-100 transition-colors" title="Reject"
                                        onclick="openRejectModal('{{ $acc->id }}', '{{ addslashes($displayName) }}')">
                                    <i data-feather="x" class="w-3.5 h-3.5"></i>
                                </button>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center py-12 text-gray-400">
                            <i data-feather="inbox" class="w-10 h-10 mx-auto mb-2 opacity-50"></i>
                            <p class="text-xs font-semibold">Tidak ada pengajuan akun untuk status ini.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination Bar -->
        <div class="px-6 py-4 border-t border-gray-100 bg-gray-50/50 flex flex-col sm:flex-row items-center justify-between gap-3">
            <div class="text-xs font-semibold text-gray-500" id="pageInfo">Menampilkan data akun</div>
            <div class="flex items-center gap-1.5" id="paginationControls"></div>
        </div>
    </div>

    </div>
    @include("Admin.partials.footer")
</main>

<div id="rejectModal" class="confirm-modal">
    <div class="bg-white w-[400px] p-8 rounded-2xl text-center shadow-2xl transform scale-95 transition-transform duration-200" id="rejectBox">
        <form id="rejectForm" method="POST">
            @csrf
            <input type="hidden" name="type" value="{{ $type }}">
            <div class="mb-6">
                <div class="w-14 h-14 rounded-2xl bg-red-50 text-red-500 flex items-center justify-center mx-auto mb-4">
                    <i data-feather="alert-triangle" class="w-6 h-6"></i>
                </div>
                <div class="text-lg font-bold text-gray-900">Reject Account</div>
                <div class="text-xs text-gray-500 mt-2">Berikan alasan kenapa akun <strong id="targetName" class="text-gray-900"></strong> ditolak.</div>
                <textarea name="reason" required class="w-full mt-6 h-24 p-3 bg-gray-50 border border-gray-200 rounded-xl text-xs focus:outline-none focus:border-red-300 focus:ring-1 focus:ring-red-300 transition-colors" placeholder="Contoh: Portfolio tidak valid atau Nama toko mengandung kata terlarang..."></textarea>
            </div>
            <div class="flex gap-3 mt-4">
                <button type="button" class="flex-1 bg-gray-100 hover:bg-gray-200 text-gray-600 font-bold py-3 rounded-xl text-xs transition-colors" onclick="closeRejectModal()">Batal</button>
                <button type="submit" class="flex-1 bg-red-500 hover:bg-red-600 text-white font-bold py-3 rounded-xl text-xs transition-colors">Reject Account</button>
            </div>
        </form>
    </div>
</div>

<script>
    feather.replace({ 'stroke-width': 2 });
    function openRejectModal(id, name) {
        document.getElementById('rejectForm').action = `/admin/account-validation/${id}/reject`;
        document.getElementById('targetName').textContent = name;
        document.getElementById('rejectModal').classList.add('show');
    }
    function closeRejectModal() {
        document.getElementById('rejectModal').classList.remove('show');
    }

    const allRows = Array.from(document.querySelectorAll('#accountTableBody .account-row'));
    const searchInput = document.getElementById('accountSearch');
    const pageInfo = document.getElementById('pageInfo');
    const paginationControls = document.getElementById('paginationControls');
    const perPage = 5;
    let currentPage = 1;

    function renderTable() {
        const term = (searchInput?.value || '').toLowerCase().trim();

        const filtered = allRows.filter(row => {
            const s = row.getAttribute('data-search') || '';
            return !term || s.includes(term);
        });

        const total = filtered.length;
        const totalPages = Math.max(1, Math.ceil(total / perPage));
        if (currentPage > totalPages) currentPage = totalPages;

        allRows.forEach(r => r.style.display = 'none');
        const start = (currentPage - 1) * perPage;
        const end = Math.min(start + perPage, total);
        filtered.slice(start, end).forEach(r => r.style.display = '');

        pageInfo.textContent = total === 0
            ? 'Tidak ada akun yang cocok dengan filter'
            : `Halaman ${currentPage} dari ${totalPages} — Menampilkan ${start + 1}–${end} dari ${total} akun`;

        paginationControls.innerHTML = '';
        if (totalPages > 1) {
            for (let i = 1; i <= totalPages; i++) {
                const btn = document.createElement('button');
                btn.textContent = i;
                btn.className = `w-8 h-8 rounded-lg text-xs font-bold transition-colors ${i === currentPage ? 'bg-primary text-white shadow-sm' : 'bg-white border border-gray-200 text-gray-600 hover:border-primary hover:text-primary'}`;
                btn.onclick = () => { currentPage = i; renderTable(); };
                paginationControls.appendChild(btn);
            }
        }
    }

    searchInput?.addEventListener('input', () => { currentPage = 1; renderTable(); });
    renderTable();
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
