@php
$admin_name = Auth::user()->full_name;
$admin_role = "ADMIN";

$stats_view = [
    ["label" => "Total Users",      "value" => sprintf("%02d", $stats['total']),     "note" => "terdaftar di sistem", "icon" => "users",       "color" => "primary"],
    ["label" => "Designers",        "value" => sprintf("%02d", $stats['designers']), "note" => "mitra desainer",      "icon" => "pen-tool",    "color" => "info"],
    ["label" => "Platform Sellers", "value" => sprintf("%02d", $stats['sellers']),   "note" => "mitra toko & pabrik", "icon" => "shopping-bag","color" => "warning"],
    ["label" => "Customers",        "value" => sprintf("%02d", $stats['customers']), "note" => "pembeli aktif",       "icon" => "user-check",  "color" => "success"],
];
@endphp
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Management — DECOR Admin</title>
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
    </style>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body class="text-gray-800 bg-[#F8F6F4]">

@include("Admin.partials.sidebar")

<main id="main-content" class="flex-1 flex flex-col ml-64 sidebar-transition min-h-screen bg-[#F8F6F4]">
    @include("Admin.partials.header", ["title" => "User Management"])
    <div class="p-8 space-y-6 flex-1">

    <!-- Page Header -->
    <div>
        <div class="text-[10px] font-black text-primary uppercase tracking-widest mb-1">Database Management</div>
        <div class="text-2xl font-bold text-gray-900">User Management</div>
        <div class="text-xs text-gray-500 mt-1">Kelola akun pengguna, role, dan status di seluruh platform.</div>
    </div>

    @if(session('success'))
    <div class="bg-green-50 border border-green-200 text-green-700 px-6 py-4 rounded-xl flex items-center gap-3 shadow-sm">
        <i data-feather="check-circle" class="w-5 h-5 text-green-500"></i>
        <div class="flex-1">
            <p class="text-xs font-bold">{{ session('success') }}</p>
        </div>
        <button onclick="this.parentElement.style.display='none'" class="text-green-500 hover:text-green-700 transition-colors focus:outline-none">
            <i data-feather="x" class="w-4 h-4"></i>
        </button>
    </div>
    @endif

    <!-- Stats Cards (Unified Style) -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        @foreach ($stats_view as $s)
        <div class="bg-white p-6 rounded-xl border border-gray-100 shadow-sm transition-transform hover:-translate-y-1 relative overflow-hidden">
            @php
                $borderColor = match($s['color']) {
                    'warning' => 'bg-amber-600',
                    'info' => 'bg-blue-600',
                    'success' => 'bg-green-600',
                    default => 'bg-primary'
                };
                $iconBg = match($s['color']) {
                    'warning' => 'bg-amber-50 text-amber-600',
                    'info' => 'bg-blue-50 text-blue-600',
                    'success' => 'bg-green-50 text-green-600',
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

    <!-- User Table Card with Toolbar & Pagination -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <!-- Toolbar -->
        <div class="p-5 border-b border-gray-100 flex flex-col lg:flex-row lg:items-center justify-between gap-4">
            <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-3 flex-1">
                <div class="relative flex-1 max-w-md">
                    <i data-feather="search" class="w-4 h-4 absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-400"></i>
                    <input id="userSearch" type="text" class="w-full pl-10 pr-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-xs font-semibold focus:outline-none focus:border-primary focus:bg-white transition-colors" placeholder="Cari nama, username, email, atau role…" value="{{ request('search') }}">
                </div>
            </div>
            <div class="flex gap-1 bg-gray-50 border border-gray-200 rounded-xl p-1 self-start lg:self-auto">
                <a href="{{ route('admin.user-management', ['role' => 'semua']) }}" class="px-3.5 py-1.5 rounded-lg text-[10px] font-bold uppercase tracking-widest transition-colors {{ !request('role') || request('role') == 'semua' ? 'bg-white text-primary shadow-sm' : 'text-gray-500 hover:text-primary' }}">Semua</a>
                <a href="{{ route('admin.user-management', ['role' => 'designer']) }}" class="px-3.5 py-1.5 rounded-lg text-[10px] font-bold uppercase tracking-widest transition-colors {{ request('role') == 'designer' ? 'bg-white text-primary shadow-sm' : 'text-gray-500 hover:text-primary' }}">Designer</a>
                <a href="{{ route('admin.user-management', ['role' => 'seller']) }}" class="px-3.5 py-1.5 rounded-lg text-[10px] font-bold uppercase tracking-widest transition-colors {{ request('role') == 'seller' ? 'bg-white text-primary shadow-sm' : 'text-gray-500 hover:text-primary' }}">Seller</a>
                <a href="{{ route('admin.user-management', ['role' => 'customer']) }}" class="px-3.5 py-1.5 rounded-lg text-[10px] font-bold uppercase tracking-widest transition-colors {{ request('role') == 'customer' ? 'bg-white text-primary shadow-sm' : 'text-gray-500 hover:text-primary' }}">Customer</a>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50/70 border-b border-gray-100 text-[9px] font-black text-gray-400 uppercase tracking-widest">
                        <th class="py-3.5 px-6">Pengguna</th>
                        <th class="py-3.5 px-6">Username</th>
                        <th class="py-3.5 px-6">Role</th>
                        <th class="py-3.5 px-6">Bergabung</th>
                        <th class="py-3.5 px-6">Status</th>
                        <th class="py-3.5 px-6 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 text-xs" id="userTableBody">
                    @foreach ($users as $u)
                    @php
                        $role_cls = match(strtolower($u->role)) {
                            'designer' => 'bg-blue-50 text-blue-600 border-blue-200',
                            'seller' => 'bg-amber-50 text-amber-600 border-amber-200',
                            'admin' => 'bg-orange-50 text-primary border-orange-200',
                            'customer' => 'bg-gray-50 text-gray-600 border-gray-200',
                            default => 'bg-gray-50 text-gray-600 border-gray-200'
                        };
                    @endphp
                    <tr class="user-row hover:bg-gray-50/70 transition-colors"
                        data-search="{{ strtolower($u->full_name . ' ' . $u->username . ' ' . $u->email . ' ' . $u->role) }}">
                        <td class="py-3.5 px-6">
                            <div class="flex items-center gap-3.5">
                                <img src="{{ $u->avatar_url ?? 'https://ui-avatars.com/api/?name='.urlencode($u->full_name).'&background=B5733A&color=fff' }}" class="w-10 h-10 rounded-xl object-cover border border-gray-100 flex-shrink-0">
                                <div>
                                    <span class="block font-bold text-gray-900">{{ $u->full_name }}</span>
                                    <span class="block text-[10px] text-gray-400 font-mono mt-0.5">{{ $u->email }}</span>
                                </div>
                            </div>
                        </td>
                        <td class="py-3.5 px-6 font-mono text-[11px] text-gray-600">
                            {{ '@' . $u->username }}
                        </td>
                        <td class="py-3.5 px-6">
                            <span class="inline-flex items-center px-2.5 py-1 rounded-md text-[9px] font-black uppercase tracking-widest border {{ $role_cls }}">
                                {{ strtoupper($u->role) }}
                            </span>
                        </td>
                        <td class="py-3.5 px-6">
                            <span class="font-semibold text-gray-600">{{ $u->created_at->format('d M Y') }}</span>
                        </td>
                        <td class="py-3.5 px-6">
                            <span class="inline-flex items-center gap-1.5 text-[10px] font-black text-green-600 uppercase tracking-widest">
                                <span class="w-1.5 h-1.5 rounded-full bg-green-600"></span> Active
                            </span>
                        </td>
                        <td class="py-3.5 px-6">
                            <div class="flex items-center justify-end gap-1.5">
                                <form action="{{ route('admin.user.warn', $u->id) }}" method="POST" onsubmit="return confirm('Kirim peringatan pelanggaran ke user ini?')">
                                    @csrf
                                    <button type="submit" class="w-8 h-8 flex items-center justify-center rounded-lg border border-amber-200 bg-amber-50 text-amber-600 hover:bg-amber-100 transition-colors" title="Kirim Peringatan">
                                        <i data-feather="alert-triangle" class="w-3.5 h-3.5"></i>
                                    </button>
                                </form>
                                @if($u->role !== 'admin')
                                <form action="{{ route('admin.user.delete', $u->id) }}" method="POST" onsubmit="return confirm('BANNED: Anda yakin ingin menghapus user ini secara permanen?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="w-8 h-8 flex items-center justify-center rounded-lg border border-red-200 bg-red-50 text-red-600 hover:bg-red-100 transition-colors" title="Suspend & Delete">
                                        <i data-feather="slash" class="w-3.5 h-3.5"></i>
                                    </button>
                                </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Pagination Bar -->
        <div class="px-6 py-4 border-t border-gray-100 bg-gray-50/50 flex flex-col sm:flex-row items-center justify-between gap-3">
            <div class="text-xs font-semibold text-gray-500" id="pageInfo">Menampilkan data pengguna</div>
            <div class="flex items-center gap-1.5" id="paginationControls"></div>
        </div>
    </div>

    </div>
    @include("Admin.partials.footer")
</main>

<script>
    feather.replace({ 'stroke-width': 2 });

    const allRows = Array.from(document.querySelectorAll('#userTableBody .user-row'));
    const searchInput = document.getElementById('userSearch');
    const pageInfo = document.getElementById('pageInfo');
    const paginationControls = document.getElementById('paginationControls');
    const perPage = 6;
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
            ? 'Tidak ada pengguna yang cocok dengan filter'
            : `Halaman ${currentPage} dari ${totalPages} — Menampilkan ${start + 1}–${end} dari ${total} pengguna`;

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
