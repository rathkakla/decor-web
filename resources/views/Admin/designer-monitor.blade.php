@php
$admin_name = Auth::user()->full_name;
$admin_role = "ADMIN";

$menu_items = [
    ["label" => "Dashboard",              "path" => route('admin.dashboard'),              "icon" => "grid"],
    ["label" => "User Management",        "path" => route('admin.user-management'),        "icon" => "users"],
    ["label" => "Account Validation",     "path" => route('admin.account.validation'),     "icon" => "shield"],
    ["label" => "Seller Monitor",         "path" => route('admin.seller-monitor'),         "icon" => "shopping-bag"],
    ["label" => "Designer Monitor",       "path" => route('admin.designer-monitor'),       "icon" => "pen-tool"],
    ["label" => "Seller Support",         "path" => route('admin.seller-support'),         "icon" => "headphones"],
    ["label" => "Designer Support",       "path" => route('admin.designer-support'),       "icon" => "pen-tool"],
    ["label" => "Customer Support",       "path" => route('admin.customer-support'),       "icon" => "message-circle"],
    ["label" => "Product Validation",     "path" => route('admin.product.validation'),     "icon" => "check-circle"],
    ["label" => "Portofolio Validation",  "path" => route('admin.portfolio-validation'),  "icon" => "image"],
];
@endphp
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Designer Monitor — DECOR Admin</title>
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
    @include("Admin.partials.header", ["title" => "Designer Monitor"])
    <div class="p-8 space-y-8 flex-1">

    @if(session('success'))
        <div class="bg-green-50 border border-green-200 text-green-600 px-6 py-4 rounded-xl text-xs font-bold flex items-center">
            <i data-feather="check-circle" class="w-4 h-4 mr-3"></i>
            {{ session('success') }}
        </div>
    @endif

    <div class="mb-8">
        <div class="text-[10px] font-black text-primary uppercase tracking-widest mb-1">Manajemen Akun</div>
        <div class="text-2xl font-bold text-gray-900">Designer Monitor</div>
        <div class="text-xs text-gray-500 mt-1">Pantau performa dan kelola status kemitraan desainer.</div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <?php foreach ($stats as $index => $s): 
            $colors = [
                'bg-orange-50 text-primary',
                'bg-green-50 text-green-500',
                'bg-amber-50 text-amber-500',
                'bg-red-50 text-red-500'
            ];
            $borderColors = [
                'bg-primary',
                'bg-green-500',
                'bg-amber-500',
                'bg-red-500'
            ];
            $color = $colors[$index % 4];
            $borderColor = $borderColors[$index % 4];
        ?>
        <div class="bg-white p-6 rounded-xl border border-gray-100 shadow-sm transition-transform hover:-translate-y-1 relative overflow-hidden">
            <div class="absolute bottom-0 left-0 right-0 h-1 <?= $borderColor ?>"></div>
            <div class="w-10 h-10 rounded-lg <?= $color ?> flex items-center justify-center mb-4">
                <i data-feather="<?= $s['icon'] ?>" class="w-5 h-5"></i>
            </div>
            <h3 class="text-2xl font-bold text-gray-900"><?= $s['value'] ?></h3>
            <p class="text-[10px] text-gray-400 font-bold uppercase tracking-wider mt-1"><?= $s['label'] ?></p>
            <p class="text-[10px] font-black text-gray-500 mt-1"><?= $s['note'] ?></p>
        </div>
        <?php endforeach; ?>
    </div>

    <div class="flex items-center gap-4 mb-6">
        <div class="flex-1 relative">
            <i data-feather="search" class="w-4 h-4 absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
            <input type="text" id="designerSearch" class="w-full pl-10 pr-4 py-3 bg-white border border-gray-100 rounded-xl text-xs font-semibold focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary shadow-sm" placeholder="Cari nama desainer atau spesialisasi…">
        </div>
        <div class="flex gap-1 bg-white border border-gray-100 shadow-sm rounded-xl p-1" id="designerFilterTabs">
            <button data-filter="all" class="filter-tab px-4 py-2 rounded-lg text-[10px] font-bold uppercase tracking-widest transition-colors bg-orange-50 text-primary">All</button>
            <button data-filter="verified" class="filter-tab px-4 py-2 rounded-lg text-[10px] font-bold uppercase tracking-widest transition-colors text-gray-500 hover:bg-gray-50">Active</button>
            <button data-filter="warning" class="filter-tab px-4 py-2 rounded-lg text-[10px] font-bold uppercase tracking-widest transition-colors text-gray-500 hover:bg-gray-50">Warning</button>
            <button data-filter="banned" class="filter-tab px-4 py-2 rounded-lg text-[10px] font-bold uppercase tracking-widest transition-colors text-gray-500 hover:bg-gray-50">Banned</button>
        </div>
    </div>

    <!-- Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6" id="designerGrid">
        <?php foreach ($designers as $d):
            $sl = strtolower($d->status);
            $filterCat = match($sl) {
                'verified' => 'verified',
                'pending', 'suspended' => 'warning',
                'rejected', 'banned' => 'banned',
                default => 'warning'
            };
            $isBanned     = in_array($d->status, ['Banned', 'Rejected']);
            $isSuspended  = in_array($d->status, ['Warning', 'Suspended', 'Pending']);
            $starFull  = floor($d->rating);
            $starEmpty = 5 - $starFull;
            $rateWidth = $d->rate . '%';
            $topBorder = match($filterCat) {
                'warning' => 'border-t-4 border-amber-500',
                'verified' => 'border-t-4 border-green-500',
                'banned' => 'border-t-4 border-red-500',
                default => 'border-t-4 border-gray-500'
            };
        ?>
        <div class="designer-card bg-white rounded-xl border border-gray-100 shadow-sm hover:shadow-md transition-shadow flex flex-col <?= $topBorder ?> <?= $isBanned ? 'opacity-75' : '' ?>" data-status="<?= $filterCat ?>" data-search="<?= strtolower($d->name . ' ' . $d->spec . ' ' . $d->id) ?>">
            <div class="p-6 flex-1">
                <div class="flex items-start justify-between mb-4">
                    <img src="https://ui-avatars.com/api/?name=<?= urlencode($d->name) ?>&background=random&size=104"
                         class="w-12 h-12 rounded-xl object-cover border border-gray-200 <?= ($isBanned || $isSuspended) ? 'grayscale' : '' ?>">
                    <?php
                        $statusCls = match($sl) {
                            'warning', 'suspended', 'pending' => 'bg-amber-50 text-amber-600',
                            'verified' => 'bg-green-50 text-green-600',
                            'banned', 'rejected' => 'bg-red-50 text-red-600',
                            default => 'bg-gray-50 text-gray-600'
                        };
                        $statusIcon = match($sl) {
                            'verified' => 'check',
                            'warning', 'suspended', 'pending' => 'alert-triangle',
                            'banned', 'rejected' => 'x',
                            default => 'info'
                        };
                    ?>
                    <span class="inline-flex items-center px-2 py-1 rounded-md text-[9px] font-black uppercase tracking-widest <?= $statusCls ?>">
                        <i data-feather="<?= $statusIcon ?>" class="w-3 h-3 mr-1"></i> <?= $d->status ?>
                    </span>
                </div>

                <div class="text-base font-bold text-gray-900 mb-1 <?= $isBanned ? 'line-through text-red-500' : '' ?>"><?= $d->name ?></div>
                <div class="text-[11px] text-gray-500 font-semibold flex items-center gap-1.5"><i data-feather="tag" class="w-3 h-3"></i><?= $d->spec ?></div>
                <div class="font-mono text-[9px] text-gray-400 mt-2"><?= $d->id ?></div>

                <!-- Metrics -->
                <div class="grid grid-cols-3 gap-[1px] bg-gray-100 border border-gray-100 rounded-xl my-5 overflow-hidden">
                    <div class="bg-gray-50 py-3 text-center">
                        <div class="text-sm font-bold text-gray-900"><?= $d->projects ?></div>
                        <div class="text-[9px] font-bold text-gray-400 uppercase tracking-widest mt-1">Projects</div>
                    </div>
                    <div class="bg-gray-50 py-3 text-center">
                        <div class="text-sm font-bold text-gray-900"><?= $d->rate ?>%</div>
                        <div class="text-[9px] font-bold text-gray-400 uppercase tracking-widest mt-1">Conv. Rate</div>
                    </div>
                    <div class="bg-gray-50 py-3 text-center">
                        <div class="text-sm font-bold text-gray-900"><?= $d->rating ?></div>
                        <div class="text-[9px] font-bold text-gray-400 uppercase tracking-widest mt-1">Rating</div>
                    </div>
                </div>

                <!-- Rating -->
                <div class="flex items-center gap-2 mb-2">
                    <div class="flex gap-0.5">
                        <?php for($i=0;$i<$starFull;$i++): ?>
                        <i data-feather="star" class="w-3 h-3 fill-amber-400 text-amber-400"></i>
                        <?php endfor; ?>
                        <?php for($i=0;$i<$starEmpty;$i++): ?>
                        <i data-feather="star" class="w-3 h-3 fill-gray-200 text-gray-200"></i>
                        <?php endfor; ?>
                    </div>
                    <span class="text-xs font-bold text-gray-900"><?= $d->rating ?></span>
                    <div class="flex-1 mx-2 h-1.5 bg-gray-100 rounded-full overflow-hidden">
                        <div class="h-full bg-primary rounded-full" style="width: <?= $rateWidth ?>"></div>
                    </div>
                    <span class="text-[10px] text-gray-500 font-semibold"><?= $d->rate ?>%</span>
                </div>
            </div>

            <div class="border-t border-gray-100 p-4 bg-gray-50 flex items-center justify-between rounded-b-xl">
                <div class="flex flex-col gap-1">
                    <div class="text-[10px] text-gray-500 font-semibold flex items-center gap-1.5"><i data-feather="calendar" class="w-3 h-3"></i> Joined <?= $d->joined ?></div>
                    <div class="text-[11px] font-bold text-primary"><?= $d->revenue ?> earned</div>
                </div>
                <div class="flex gap-2">
                    <a href="{{ route('admin.designer-detail', ['id' => $d->db_id]) }}" class="inline-flex items-center px-3 py-1.5 rounded-lg text-[10px] font-bold bg-primary text-white hover:bg-[#8A5229] transition-colors">
                        View <i data-feather="arrow-right" class="w-3 h-3 ml-1"></i>
                    </a>
                    <?php if ($isSuspended): ?>
                    <button type="button" onclick="openAuditModal('<?= $d->user_id ?>', '<?= addslashes($d->name) ?>')" class="inline-flex items-center gap-1 px-2.5 py-1.5 rounded-lg text-[10px] font-bold bg-amber-50 text-amber-700 border border-amber-200 hover:bg-amber-600 hover:text-white transition-colors" title="Nonaktifkan Akun">
                        <i data-feather="shield" class="w-3.5 h-3.5"></i> Nonaktifkan
                    </button>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>

    <!-- Pagination Bar -->
    <div class="bg-white px-6 py-4 rounded-xl border border-gray-100 shadow-sm flex flex-col sm:flex-row items-center justify-between gap-3">
        <div class="text-xs font-semibold text-gray-500" id="pageInfo">Menampilkan data desainer</div>
        <div class="flex items-center gap-1.5" id="paginationControls"></div>
    </div>

    </div>
    @include("Admin.partials.footer")
</main>

<!-- ══════════ PENONAKTIFAN AKUN MODAL ══════════ -->
<div id="auditModal" class="fixed inset-0 bg-black/40 backdrop-blur-sm hidden items-center justify-center z-50">
    <div class="bg-white w-full max-w-md p-7 rounded-2xl shadow-2xl">
        <form id="auditForm" method="POST">
            @csrf
            <div class="flex items-center gap-3 mb-4">
                <div class="w-11 h-11 rounded-xl bg-amber-100 text-amber-600 flex items-center justify-center shrink-0">
                    <i data-feather="shield" class="w-5 h-5"></i>
                </div>
                <div>
                    <h3 class="text-base font-bold text-gray-900">Penonaktifan Akun & Suspensi</h3>
                    <p class="text-[11px] text-gray-500" id="auditTargetName">—</p>
                </div>
            </div>

            <div class="mb-4">
                <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2">Pilih Jenis Tindakan</label>
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
                <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2">Alasan Penonaktifan</label>
                <textarea name="reason" required class="w-full h-28 p-3.5 border border-gray-200 rounded-xl text-xs focus:outline-none focus:border-amber-500 focus:ring-1 focus:ring-amber-500 bg-gray-50" placeholder="Contoh: Desainer tidak merespon konsultasi klien atau sedang dalam peninjauan kualitas layanan..."></textarea>
            </div>

            <div class="flex gap-3 justify-end">
                <button type="button" onclick="closeAuditModal()" class="px-5 py-2.5 rounded-xl bg-gray-100 text-gray-700 font-bold text-xs hover:bg-gray-200 transition-colors">Batal</button>
                <button type="submit" class="px-5 py-2.5 rounded-xl bg-amber-600 text-white font-bold text-xs hover:bg-amber-700 transition-colors shadow-sm">Terapkan Tindakan</button>
            </div>
        </form>
    </div>
</div>

<script>
    feather.replace({ 'stroke-width': 2 });

    let currentFilter = 'all';
    let currentPage = 1;
    const perPage = 6; // Tampilkan 6 kartu (2 baris) per halaman
    const searchInput = document.getElementById('designerSearch');
    const cards = Array.from(document.querySelectorAll('.designer-card'));
    const tabs = document.querySelectorAll('#designerFilterTabs .filter-tab');
    const pageInfo = document.getElementById('pageInfo');
    const paginationControls = document.getElementById('paginationControls');

    function filterDesigners() {
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
            ? 'Tidak ada desainer yang cocok dengan filter'
            : `Halaman ${currentPage} dari ${totalPages} — Menampilkan ${start + 1}–${end} dari ${total} desainer`;

        paginationControls.innerHTML = '';
        if (totalPages > 1) {
            const prevBtn = document.createElement('button');
            prevBtn.innerHTML = '&laquo;';
            prevBtn.disabled = currentPage === 1;
            prevBtn.className = `px-3 h-8 rounded-lg text-xs font-bold border border-gray-200 bg-white text-gray-600 disabled:opacity-40 hover:border-primary hover:text-primary`;
            prevBtn.onclick = () => { if (currentPage > 1) { currentPage--; filterDesigners(); } };
            paginationControls.appendChild(prevBtn);

            for (let i = 1; i <= totalPages; i++) {
                const btn = document.createElement('button');
                btn.textContent = i;
                btn.className = `w-8 h-8 rounded-lg text-xs font-bold transition-colors ${i === currentPage ? 'bg-primary text-white shadow-sm' : 'bg-white border border-gray-200 text-gray-600 hover:border-primary hover:text-primary'}`;
                btn.onclick = () => { currentPage = i; filterDesigners(); };
                paginationControls.appendChild(btn);
            }

            const nextBtn = document.createElement('button');
            nextBtn.innerHTML = '&raquo;';
            nextBtn.disabled = currentPage === totalPages;
            nextBtn.className = `px-3 h-8 rounded-lg text-xs font-bold border border-gray-200 bg-white text-gray-600 disabled:opacity-40 hover:border-primary hover:text-primary`;
            nextBtn.onclick = () => { if (currentPage < totalPages) { currentPage++; filterDesigners(); } };
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
            filterDesigners();
        });
    });

    searchInput?.addEventListener('input', () => { currentPage = 1; filterDesigners(); });
    filterDesigners();

    function openAuditModal(userId, designerName) {
        document.getElementById('auditForm').action = `/admin/user-management/${userId}/warn`;
        document.getElementById('auditTargetName').textContent = designerName;
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