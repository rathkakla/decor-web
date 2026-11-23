<?php
$current_page = basename($_SERVER['PHP_SELF']);
$current_path = str_replace('.php', '', $current_page);
if ($current_path == "" || $current_path == "index") {
    $current_path = "seller-monitor"; 
}

$admin_name = Auth::user()->full_name;
$admin_role = "SUPER ADMIN";

$menu_items = [
    ["label" => "Dashboard",         "path" => route('admin.dashboard'),         "icon" => "grid"],
    ["label" => "User Management",    "path" => route('admin.user-management'),  "icon" => "users"],
    ["label" => "Account Validation", "path" => route('admin.account.validation'),"icon" => "shield"],
    ["label" => "Seller Monitor",     "path" => route('admin.seller-monitor'),    "icon" => "shopping-bag"],
    ["label" => "Designer Monitor",   "path" => route('admin.designer-monitor'),  "icon" => "pen-tool"],
    ["label" => "Seller Support",     "path" => route('admin.seller-support'),    "icon" => "headphones"],
    ["label" => "Designer Support",   "path" => route('admin.designer-support'),  "icon" => "pen-tool"],
    ["label" => "Customer Support",   "path" => route('admin.customer-support'),  "icon" => "message-circle"],
    ["label" => "Product Validation", "path" => route('admin.product.validation'),"icon" => "check-circle"],
    ["label" => "Portofolio Validation", "path" => route('admin.portfolio-validation'),"icon" => "image"],
];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Seller Monitor — DECOR Admin</title>
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
    @include("Admin.partials.header", ["title" => "Seller Monitor"])
    <div class="p-8 space-y-8 flex-1">

    @if(session('success'))
        <div class="bg-green-50 border border-green-200 text-green-600 px-6 py-4 rounded-xl text-xs font-bold flex items-center">
            <i data-feather="check-circle" class="w-4 h-4 mr-3"></i>
            {{ session('success') }}
        </div>
    @endif

    <div class="mb-8">
        <div class="text-[10px] font-black text-primary uppercase tracking-widest mb-1">Audit & Compliance</div>
        <div class="text-2xl font-bold text-gray-900">Seller Monitor</div>
        <div class="text-xs text-gray-500 mt-1">Pantau performa dan kelola status kemitraan seller.</div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <div class="bg-white p-6 rounded-xl border border-gray-100 shadow-sm transition-transform hover:-translate-y-1 relative overflow-hidden">
            <div class="absolute bottom-0 left-0 right-0 h-1 bg-primary"></div>
            <div class="w-10 h-10 rounded-lg bg-orange-50 text-primary flex items-center justify-center mb-4">
                <i data-feather="shopping-bag" class="w-5 h-5"></i>
            </div>
            <h3 class="text-2xl font-bold text-gray-900">{{ $stats['total'] }}</h3>
            <p class="text-[10px] text-gray-400 font-bold uppercase tracking-wider mt-1">Total Sellers</p>
            <p class="text-[10px] font-black text-gray-500 mt-1">registered</p>
        </div>
        <div class="bg-white p-6 rounded-xl border border-gray-100 shadow-sm transition-transform hover:-translate-y-1 relative overflow-hidden">
            <div class="absolute bottom-0 left-0 right-0 h-1 bg-green-500"></div>
            <div class="w-10 h-10 rounded-lg bg-green-50 text-green-500 flex items-center justify-center mb-4">
                <i data-feather="check-circle" class="w-5 h-5"></i>
            </div>
            <h3 class="text-2xl font-bold text-gray-900">{{ $stats['active'] }}</h3>
            <p class="text-[10px] text-gray-400 font-bold uppercase tracking-wider mt-1">Active</p>
            <p class="text-[10px] font-black text-gray-500 mt-1">selling now</p>
        </div>
        <div class="bg-white p-6 rounded-xl border border-gray-100 shadow-sm transition-transform hover:-translate-y-1 relative overflow-hidden">
            <div class="absolute bottom-0 left-0 right-0 h-1 bg-amber-500"></div>
            <div class="w-10 h-10 rounded-lg bg-amber-50 text-amber-500 flex items-center justify-center mb-4">
                <i data-feather="alert-triangle" class="w-5 h-5"></i>
            </div>
            <h3 class="text-2xl font-bold text-gray-900">{{ $stats['warning'] }}</h3>
            <p class="text-[10px] text-gray-400 font-bold uppercase tracking-wider mt-1">Warning</p>
            <p class="text-[10px] font-black text-gray-500 mt-1">inactive > 30d</p>
        </div>
        <div class="bg-white p-6 rounded-xl border border-gray-100 shadow-sm transition-transform hover:-translate-y-1 relative overflow-hidden">
            <div class="absolute bottom-0 left-0 right-0 h-1 bg-red-500"></div>
            <div class="w-10 h-10 rounded-lg bg-red-50 text-red-500 flex items-center justify-center mb-4">
                <i data-feather="x-circle" class="w-5 h-5"></i>
            </div>
            <h3 class="text-2xl font-bold text-gray-900">{{ $stats['banned'] }}</h3>
            <p class="text-[10px] text-gray-400 font-bold uppercase tracking-wider mt-1">Banned</p>
            <p class="text-[10px] font-black text-gray-500 mt-1">removed</p>
        </div>
    </div>

    <div class="flex items-center gap-4 mb-6">
        <div class="flex-1 relative">
            <i data-feather="search" class="w-4 h-4 absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
            <input type="text" id="sellerSearch" class="w-full pl-10 pr-4 py-3 bg-white border border-gray-100 rounded-xl text-xs font-semibold focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary shadow-sm" placeholder="Cari nama toko atau pemilik…">
        </div>
        <div class="flex gap-1 bg-white border border-gray-100 shadow-sm rounded-xl p-1" id="sellerFilterTabs">
            <button data-filter="all" class="filter-tab px-4 py-2 rounded-lg text-[10px] font-bold uppercase tracking-widest transition-colors bg-orange-50 text-primary">All</button>
            <button data-filter="active" class="filter-tab px-4 py-2 rounded-lg text-[10px] font-bold uppercase tracking-widest transition-colors text-gray-500 hover:bg-gray-50">Active</button>
            <button data-filter="warning" class="filter-tab px-4 py-2 rounded-lg text-[10px] font-bold uppercase tracking-widest transition-colors text-gray-500 hover:bg-gray-50">Warning</button>
            <button data-filter="banned" class="filter-tab px-4 py-2 rounded-lg text-[10px] font-bold uppercase tracking-widest transition-colors text-gray-500 hover:bg-gray-50">Banned</button>
        </div>
    </div>

    <!-- Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6" id="sellerGrid">
        @foreach ($sellers as $s)
        @php
            $sl        = strtolower($s->status);
            $isBanned  = $s->status === 'Banned';
            $isWarning = $s->status === 'Warning';
            $lowRating = $s->rating < 3;
            $topBorder = match($sl) {
                'warning' => 'border-t-4 border-amber-500',
                'active' => 'border-t-4 border-green-500',
                'banned' => 'border-t-4 border-red-500',
                default => 'border-t-4 border-gray-500'
            };
        @endphp
        <div class="seller-card bg-white rounded-xl border border-gray-100 shadow-sm hover:shadow-md transition-shadow flex flex-col {{ $topBorder }} {{ $isBanned ? 'opacity-75' : '' }}" data-status="{{ $sl }}" data-search="{{ strtolower($s->name . ' ' . $s->owner . ' ' . $s->id) }}">
            <div class="p-6 flex-1">
                <div class="flex items-start justify-between mb-4">
                    <div class="w-12 h-12 rounded-xl bg-orange-50 text-primary flex items-center justify-center border border-orange-100 {{ ($isBanned || $isWarning) ? 'grayscale' : '' }}">
                        <i data-feather="shopping-bag" class="w-5 h-5"></i>
                    </div>
                    @php
                        $statusCls = match($sl) {
                            'warning' => 'bg-amber-50 text-amber-600',
                            'active' => 'bg-green-50 text-green-600',
                            'banned' => 'bg-red-50 text-red-600',
                            default => 'bg-gray-50 text-gray-600'
                        };
                        $statusIcon = match($sl) {
                            'active' => 'check',
                            'warning' => 'alert-triangle',
                            'banned' => 'x',
                            default => 'info'
                        };
                    @endphp
                    <span class="inline-flex items-center px-2 py-1 rounded-md text-[9px] font-black uppercase tracking-widest {{ $statusCls }}">
                        <i data-feather="{{ $statusIcon }}" class="w-3 h-3 mr-1"></i> {{ $s->status }}
                    </span>
                </div>

                <div class="text-base font-bold text-gray-900 mb-1 {{ $isBanned ? 'line-through text-red-500' : '' }}">{{ $s->name }}</div>
                <div class="text-[11px] text-gray-500 font-semibold flex items-center gap-1.5"><i data-feather="user" class="w-3 h-3"></i>{{ $s->owner }}</div>
                <div class="font-mono text-[9px] text-gray-400 mt-2">{{ $s->id }}</div>

                <!-- Metrics -->
                <div class="grid grid-cols-3 gap-[1px] bg-gray-100 border border-gray-100 rounded-xl my-5 overflow-hidden">
                    <div class="bg-gray-50 py-3 text-center">
                        <div class="text-sm font-bold text-gray-900">{{ $s->transactions }}</div>
                        <div class="text-[9px] font-bold text-gray-400 uppercase tracking-widest mt-1">Orders</div>
                    </div>
                    <div class="bg-gray-50 py-3 text-center">
                        <div class="text-[11px] font-bold text-gray-900 leading-tight">Rp {{ number_format($s->total_sales, 0, ',', '.') }}</div>
                        <div class="text-[9px] font-bold text-gray-400 uppercase tracking-widest mt-1">Revenue</div>
                    </div>
                    <div class="bg-gray-50 py-3 text-center">
                        <div class="text-[11px] font-bold text-gray-900 leading-tight">Rp {{ number_format($s->admin_fee, 0, ',', '.') }}</div>
                        <div class="text-[9px] font-bold text-gray-400 uppercase tracking-widest mt-1">Admin Fee</div>
                    </div>
                </div>

                <!-- Activity + Rating -->
                <div class="flex items-center gap-2 mb-2">
                    <span class="w-2 h-2 rounded-full {{ $sl == 'active' ? 'bg-green-500 shadow-[0_0_0_3px_rgba(34,197,94,0.2)]' : ($sl == 'warning' ? 'bg-amber-500' : 'bg-red-500') }}"></span>
                    <span class="text-[10px] text-gray-500 font-semibold">Last order: {{ $s->last_active }}</span>
                    <div class="ml-auto flex items-center gap-1 text-[11px] font-bold {{ $lowRating ? 'text-red-500' : 'text-amber-500' }}">
                        <i data-feather="star" class="w-3 h-3 {{ $lowRating ? 'fill-red-500' : 'fill-amber-500' }}"></i> {{ number_format($s->rating,1) }}
                    </div>
                </div>
            </div>

            <div class="border-t border-gray-100 p-4 bg-gray-50 flex items-center justify-between rounded-b-xl">
                <div class="flex flex-col gap-1">
                    <div class="text-[10px] text-gray-500 font-semibold flex items-center gap-1.5"><i data-feather="calendar" class="w-3 h-3"></i> Joined {{ $s->joined }}</div>
                    <div class="text-[11px] font-bold text-primary">Rp {{ number_format($s->admin_fee, 0, ',', '.') }} fee</div>
                </div>
                <div class="flex gap-2">
                    <a href="{{ route('admin.seller-detail', ['id' => $s->db_id]) }}" class="inline-flex items-center px-3 py-1.5 rounded-lg text-[10px] font-bold bg-primary text-white hover:bg-[#8A5229] transition-colors">
                        View <i data-feather="arrow-right" class="w-3 h-3 ml-1"></i>
                    </a>
                    @if ($isWarning)
                    <button type="button" onclick="openAuditModal('{{ $s->user_id }}', '{{ addslashes($s->name) }}')" class="inline-flex items-center gap-1 px-2.5 py-1.5 rounded-lg text-[10px] font-bold bg-amber-50 text-amber-700 border border-amber-200 hover:bg-amber-600 hover:text-white transition-colors" title="Audit / Temporary Ban">
                        <i data-feather="shield" class="w-3.5 h-3.5"></i> Audit
                    </button>
                    @endif
                </div>
            </div>
        </div>
        @endforeach
    </div>

    <!-- Pagination Bar -->
    <div class="bg-white px-6 py-4 rounded-xl border border-gray-100 shadow-sm flex flex-col sm:flex-row items-center justify-between gap-3">
        <div class="text-xs font-semibold text-gray-500" id="pageInfo">Menampilkan data seller</div>
        <div class="flex items-center gap-1.5" id="paginationControls"></div>
    </div>

    </div>
    @include("Admin.partials.footer")
</main>

<!-- ══════════ AUDIT / TEMPORARY BAN MODAL ══════════ -->
<div id="auditModal" class="fixed inset-0 bg-black/40 backdrop-blur-sm hidden items-center justify-center z-50">
    <div class="bg-white w-full max-w-md p-7 rounded-2xl shadow-2xl">
        <form id="auditForm" method="POST">
            @csrf
            <div class="flex items-center gap-3 mb-4">
                <div class="w-11 h-11 rounded-xl bg-amber-100 text-amber-600 flex items-center justify-center shrink-0">
                    <i data-feather="shield" class="w-5 h-5"></i>
                </div>
                <div>
                    <h3 class="text-base font-bold text-gray-900">Tindakan Audit & Suspensi</h3>
                    <p class="text-[11px] text-gray-500" id="auditTargetName">—</p>
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

<script>
    feather.replace({ 'stroke-width': 2 });

    let currentFilter = 'all';
    let currentPage = 1;
    const perPage = 6; // Tampilkan 6 kartu (2 baris) per halaman
    const searchInput = document.getElementById('sellerSearch');
    const cards = Array.from(document.querySelectorAll('.seller-card'));
    const tabs = document.querySelectorAll('#sellerFilterTabs .filter-tab');
    const pageInfo = document.getElementById('pageInfo');
    const paginationControls = document.getElementById('paginationControls');

    function filterSellers() {
        const term = (searchInput?.value || '').toLowerCase().trim();
        const filtered = cards.filter(card => {
            const st = card.getAttribute('data-status');
            const srch = card.getAttribute('data-search') || '';
            const matchFilter = currentFilter === 'all' || st === currentFilter;
            const matchSearch = !term || srch.includes(term);
            return matchFilter && matchSearch;
        });

        const total = filtered.length;
        const totalPages = Math.max(1, Math.ceil(total / perPage));
        if (currentPage > totalPages) currentPage = totalPages;

        cards.forEach(c => c.style.display = 'none');

        const start = (currentPage - 1) * perPage;
        const end = Math.min(start + perPage, total);
        filtered.slice(start, end).forEach(c => c.style.display = 'flex');

        pageInfo.textContent = total === 0
            ? 'Tidak ada seller yang cocok dengan filter'
            : `Halaman ${currentPage} dari ${totalPages} — Menampilkan ${start + 1}–${end} dari ${total} seller`;

        paginationControls.innerHTML = '';
        if (totalPages > 1) {
            const prevBtn = document.createElement('button');
            prevBtn.innerHTML = '&laquo;';
            prevBtn.disabled = currentPage === 1;
            prevBtn.className = `px-3 h-8 rounded-lg text-xs font-bold border border-gray-200 bg-white text-gray-600 disabled:opacity-40 hover:border-primary hover:text-primary`;
            prevBtn.onclick = () => { if (currentPage > 1) { currentPage--; filterSellers(); } };
            paginationControls.appendChild(prevBtn);

            for (let i = 1; i <= totalPages; i++) {
                const btn = document.createElement('button');
                btn.textContent = i;
                btn.className = `w-8 h-8 rounded-lg text-xs font-bold transition-colors ${i === currentPage ? 'bg-primary text-white shadow-sm' : 'bg-white border border-gray-200 text-gray-600 hover:border-primary hover:text-primary'}`;
                btn.onclick = () => { currentPage = i; filterSellers(); };
                paginationControls.appendChild(btn);
            }

            const nextBtn = document.createElement('button');
            nextBtn.innerHTML = '&raquo;';
            nextBtn.disabled = currentPage === totalPages;
            nextBtn.className = `px-3 h-8 rounded-lg text-xs font-bold border border-gray-200 bg-white text-gray-600 disabled:opacity-40 hover:border-primary hover:text-primary`;
            nextBtn.onclick = () => { if (currentPage < totalPages) { currentPage++; filterSellers(); } };
            paginationControls.appendChild(nextBtn);
        }
    }

    tabs.forEach(tab => {
        tab.addEventListener('click', () => {
            tabs.forEach(t => {
                t.classList.remove('bg-orange-50', 'text-primary');
                t.classList.add('text-gray-500');
            });
            tab.classList.remove('text-gray-500');
            tab.classList.add('bg-orange-50', 'text-primary');
            currentFilter = tab.getAttribute('data-filter');
            currentPage = 1;
            filterSellers();
        });
    });

    searchInput?.addEventListener('input', () => { currentPage = 1; filterSellers(); });
    filterSellers();

    function openAuditModal(userId, storeName) {
        document.getElementById('auditForm').action = `/admin/user-management/${userId}/warn`;
        document.getElementById('auditTargetName').textContent = storeName;
        const modal = document.getElementById('auditModal');
        modal.classList.remove('hidden');
        modal.classList.add('flex');
    }
    function closeAuditModal() {
        const modal = document.getElementById('auditModal');
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    }
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