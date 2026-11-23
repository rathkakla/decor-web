@php
$admin_name = Auth::user()->full_name;
$admin_role = "ADMIN";

$stats_view = [
    ["label" => "Pending",   "value" => sprintf("%02d", $stats['pending']),  "icon" => "clock",        "note" => "menunggu kurasi",   "color" => "warning"],
    ["label" => "Published", "value" => sprintf("%02d", $stats['approved']), "icon" => "check-circle", "note" => "karya live",        "color" => "success"],
    ["label" => "Rejected",  "value" => sprintf("%02d", $stats['rejected']), "icon" => "x-circle",     "note" => "tidak layak",       "color" => "danger"],
    ["label" => "Total",     "value" => sprintf("%02d", $stats['total']),    "icon" => "zap",          "note" => "semua portofolio",  "color" => "primary"],
];

$categoriesList = $portfolios->pluck('category')->filter()->unique()->sort()->values();
$designersList = $portfolios->map(fn($p) => $p->designer->user->full_name ?? 'Independent Designer')->filter()->unique()->sort()->values();
$groupedByDesigner = $portfolios->groupBy(fn($p) => $p->designer_id ?? 0);
@endphp
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Portfolio Validation — DECOR Admin</title>
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
        .detail-modal, .confirm-modal { display: none; position: fixed; inset: 0; background: rgba(28, 20, 16, 0.4); backdrop-filter: blur(8px); align-items: center; justify-content: center; z-index: 2000; }
        .detail-modal.show, .confirm-modal.show { display: flex; }
        .detail-content { transform: scale(0.95); transition: 0.3s; }
        .detail-modal.show .detail-content { transform: scale(1); }
    </style>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body class="text-gray-800 bg-[#F8F6F4]">

@include("Admin.partials.sidebar")

<main id="main-content" class="flex-1 flex flex-col ml-64 sidebar-transition min-h-screen bg-[#F8F6F4]">
    @include("Admin.partials.header", ["title" => "Portfolio Validation"])
    <div class="p-8 space-y-6 flex-1">

    @if(session('success'))
        <div class="bg-green-50 border border-green-200 text-green-600 px-6 py-4 rounded-xl text-xs font-bold flex items-center">
            <i data-feather="check-circle" class="w-4 h-4 mr-3"></i>
            {{ session('success') }}
        </div>
    @endif

    <div>
        <div class="text-[10px] font-black text-primary uppercase tracking-widest mb-1">Aesthetic Curation</div>
        <div class="text-2xl font-bold text-gray-900">Portfolio Validation</div>
        <div class="text-xs text-gray-500 mt-1">Kurasi dan publikasikan portofolio karya yang dikelompokkan per desainer sebelum tampil di platform.</div>
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

    <!-- Filter Toolbar -->
    <div class="bg-white p-4 rounded-xl border border-gray-100 shadow-sm flex flex-col lg:flex-row lg:items-center justify-between gap-4">
        <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-3 flex-1">
            <div class="relative flex-1 max-w-sm">
                <i data-feather="search" class="w-4 h-4 absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-400"></i>
                <input type="text" id="portfolioSearch" class="w-full pl-10 pr-4 py-2 bg-gray-50 border border-gray-200 rounded-xl text-xs font-semibold focus:outline-none focus:border-primary focus:bg-white transition-colors" placeholder="Cari judul portofolio, ID, atau desainer…">
            </div>
            <select id="categoryFilter" class="px-3.5 py-2 bg-gray-50 border border-gray-200 rounded-xl text-xs font-bold text-gray-600 focus:outline-none focus:border-primary">
                <option value="">Semua Kategori</option>
                @foreach($categoriesList as $cat)
                    <option value="{{ strtolower($cat) }}">{{ $cat }}</option>
                @endforeach
            </select>
        </div>

        <div class="flex gap-1 bg-gray-50 border border-gray-200 rounded-xl p-1 self-start lg:self-auto">
            <a href="{{ route('admin.portfolio-validation', ['status' => 'semua']) }}" class="px-3.5 py-1.5 rounded-lg text-[10px] font-bold uppercase tracking-widest transition-colors {{ $statusFilter == 'semua' ? 'bg-white text-primary shadow-sm' : 'text-gray-500 hover:text-primary' }}">Semua</a>
            <a href="{{ route('admin.portfolio-validation', ['status' => 'pending']) }}" class="px-3.5 py-1.5 rounded-lg text-[10px] font-bold uppercase tracking-widest transition-colors {{ $statusFilter == 'pending' ? 'bg-white text-primary shadow-sm' : 'text-gray-500 hover:text-primary' }}">Pending</a>
            <a href="{{ route('admin.portfolio-validation', ['status' => 'approved']) }}" class="px-3.5 py-1.5 rounded-lg text-[10px] font-bold uppercase tracking-widest transition-colors {{ $statusFilter == 'approved' ? 'bg-white text-primary shadow-sm' : 'text-gray-500 hover:text-primary' }}">Published</a>
            <a href="{{ route('admin.portfolio-validation', ['status' => 'rejected']) }}" class="px-3.5 py-1.5 rounded-lg text-[10px] font-bold uppercase tracking-widest transition-colors {{ $statusFilter == 'rejected' ? 'bg-white text-primary shadow-sm' : 'text-gray-500 hover:text-primary' }}">Rejected</a>
        </div>
    </div>

    <!-- Grouped Tables Per Designer -->
    <div id="designerTablesContainer" class="space-y-6">
        @forelse ($groupedByDesigner as $designerId => $designerPortfolios)
            @php
                $firstPort = $designerPortfolios->first();
                $designerObj = $firstPort->designer;
                $designerName = $designerObj->user->full_name ?? 'Independent Designer';
                $studioName = $designerObj->studio_name ?? ($designerObj->specialty ?? 'Interior Designer');
                $pendingCount = $designerPortfolios->where('status', 'pending')->count();
                $approvedCount = $designerPortfolios->where('status', 'approved')->count();
            @endphp
            <div class="designer-group-card bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden"
                 data-designer="{{ strtolower($designerName) }}">
                <!-- Designer Header -->
                <div class="px-6 py-4 bg-gray-50/70 border-b border-gray-100 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                    <div class="flex items-center gap-3.5">
                        <div class="w-10 h-10 rounded-xl bg-orange-50 border border-orange-100 text-primary flex items-center justify-center shrink-0">
                            <i data-feather="pen-tool" class="w-4 h-4"></i>
                        </div>
                        <div>
                            <div class="flex items-center gap-2">
                                <h3 class="text-sm font-bold text-gray-900">{{ $designerName }}</h3>
                                <span class="font-mono text-[9px] text-gray-500 bg-white border border-gray-200 px-2 py-0.5 rounded">DES-{{ str_pad($designerId, 3, '0', STR_PAD_LEFT) }}</span>
                            </div>
                            <p class="text-[11px] text-gray-500 font-semibold mt-0.5">Studio: {{ $studioName }} &bull; {{ $designerPortfolios->count() }} Karya Portofolio</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-2">
                        @if($pendingCount > 0)
                            <span class="px-2.5 py-1 rounded-md text-[9px] font-black uppercase tracking-widest bg-amber-50 text-amber-600 border border-amber-200">{{ $pendingCount }} Pending</span>
                        @endif
                        @if($approvedCount > 0)
                            <span class="px-2.5 py-1 rounded-md text-[9px] font-black uppercase tracking-widest bg-green-50 text-green-600 border border-green-200">{{ $approvedCount }} Published</span>
                        @endif
                        @if($designerObj)
                            <a href="{{ route('admin.designer-detail', $designerObj->id) }}" class="inline-flex items-center gap-1 px-3 py-1.5 rounded-lg text-[10px] font-bold bg-white border border-gray-200 text-gray-700 hover:border-primary hover:text-primary transition-colors">
                                Profil Desainer <i data-feather="arrow-up-right" class="w-3 h-3"></i>
                            </a>
                        @endif
                    </div>
                </div>

                <!-- Designer Portfolios Table -->
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse table-fixed">
                        <colgroup>
                            <col class="w-[34%]">
                            <col class="w-[18%]">
                            <col class="w-[16%]">
                            <col class="w-[12%]">
                            <col class="w-[10%]">
                            <col class="w-[10%]">
                        </colgroup>
                        <thead>
                            <tr class="border-b border-gray-100 text-[9px] font-black text-gray-400 uppercase tracking-widest bg-white">
                                <th class="py-3 px-6">Karya Portofolio</th>
                                <th class="py-3 px-6">Kategori</th>
                                <th class="py-3 px-6">Area & Durasi</th>
                                <th class="py-3 px-6">Diajukan</th>
                                <th class="py-3 px-6">Status</th>
                                <th class="py-3 px-6 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 text-xs">
                            @foreach ($designerPortfolios as $p)
                            @php
                                $sl = strtolower($p->status);
                                $imgUrl = storage_url($p->image_url, 'https://images.unsplash.com/photo-1600210492486-724fe5c67fb0?w=600');
                                $statusCls = match($sl) {
                                    'pending' => 'bg-amber-50 text-amber-600 border-amber-200',
                                    'approved' => 'bg-green-50 text-green-600 border-green-200',
                                    'rejected' => 'bg-red-50 text-red-600 border-red-200',
                                    default => 'bg-gray-50 text-gray-600 border-gray-200'
                                };
                                $detailPayload = [
                                    'id' => $p->id,
                                    'title' => $p->title,
                                    'designer' => $designerName,
                                    'style' => $p->category ?? '-',
                                    'image' => $imgUrl,
                                    'description' => $p->description ?? '',
                                    'dimensions' => $p->area ?? '-',
                                    'duration' => $p->duration ?? '-',
                                    'status' => $p->status
                                ];
                            @endphp
                            <tr class="portfolio-row hover:bg-gray-50/70 transition-colors {{ $sl == 'rejected' ? 'opacity-75' : '' }}"
                                data-search="{{ strtolower('PF-' . str_pad($p->id, 3, '0', STR_PAD_LEFT) . ' ' . $p->title . ' ' . $designerName . ' ' . $p->category) }}"
                                data-category="{{ strtolower($p->category ?? '') }}">
                                <td class="py-3.5 px-6">
                                    <div class="flex items-center gap-3.5 overflow-hidden">
                                        <img src="{{ $imgUrl }}"
                                             class="w-12 h-12 rounded-xl object-cover border border-gray-100 cursor-pointer hover:opacity-80 transition-opacity shrink-0"
                                             onclick='openDetail(@json($detailPayload))'>
                                        <div class="min-w-0 flex-1">
                                            <span class="inline-block px-2 py-0.5 bg-orange-50 text-primary text-[9px] font-black uppercase tracking-widest rounded font-mono mb-0.5">PF-{{ str_pad($p->id, 3, '0', STR_PAD_LEFT) }}</span>
                                            <div class="font-bold text-gray-900 truncate cursor-pointer hover:text-primary transition-colors" onclick='openDetail(@json($detailPayload))'>{{ $p->title }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="py-3.5 px-6">
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-md text-[9px] font-black text-gray-600 bg-gray-100 uppercase tracking-widest">
                                        {{ $p->category ?? '-' }}
                                    </span>
                                </td>
                                <td class="py-3.5 px-6">
                                    <div class="font-bold text-gray-900">{{ $p->area ?? '-' }}</div>
                                    <div class="text-[10px] font-semibold text-gray-400 mt-0.5">Durasi: {{ $p->duration ?? '-' }}</div>
                                </td>
                                <td class="py-3.5 px-6 text-[11px] font-semibold text-gray-500">
                                    {{ $p->created_at->diffForHumans() }}
                                </td>
                                <td class="py-3.5 px-6">
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-md text-[9px] font-black uppercase tracking-widest border {{ $statusCls }}">
                                        {{ $sl === 'approved' ? 'PUBLISHED' : strtoupper($p->status) }}
                                    </span>
                                </td>
                                <td class="py-3.5 px-6 text-right">
                                    <div class="flex items-center justify-end gap-1.5">
                                        <button type="button" class="w-8 h-8 flex items-center justify-center rounded-lg bg-orange-50 text-primary hover:bg-orange-100 transition-colors" title="Lihat Detail" onclick='openDetail(@json($detailPayload))'>
                                            <i data-feather="eye" class="w-3.5 h-3.5"></i>
                                        </button>
                                        @if($p->status == 'pending')
                                        <button type="button" class="w-8 h-8 flex items-center justify-center rounded-lg bg-green-50 text-green-600 hover:bg-green-100 transition-colors" title="Approve"
                                            onclick="askConfirm('approve','{{ $p->id }}','{{ addslashes($p->title) }}')">
                                            <i data-feather="check" class="w-3.5 h-3.5"></i>
                                        </button>
                                        <button type="button" class="w-8 h-8 flex items-center justify-center rounded-lg bg-red-50 text-red-600 hover:bg-red-100 transition-colors" title="Reject"
                                            onclick="askConfirm('reject','{{ $p->id }}','{{ addslashes($p->title) }}')">
                                            <i data-feather="x" class="w-3.5 h-3.5"></i>
                                        </button>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Inner Pagination Per 5 Karya dalam Desainer -->
                <div class="px-6 py-3 border-t border-gray-100 bg-gray-50/40 flex flex-col sm:flex-row items-center justify-between gap-2 inner-pagination-bar">
                    <div class="text-[11px] font-semibold text-gray-500 inner-page-info"></div>
                    <div class="flex items-center gap-1 inner-page-controls"></div>
                </div>
            </div>
        @empty
            <div class="text-center py-12 bg-white rounded-xl border border-gray-100 text-gray-400 shadow-sm">
                <i data-feather="image" class="w-10 h-10 mx-auto mb-2 opacity-50"></i>
                <p class="text-xs font-semibold">Tidak ada portofolio dengan status "{{ ucfirst($statusFilter) }}"</p>
            </div>
        @endforelse
    </div>

    <!-- Pagination Bar (Per Desainer) -->
    <div class="bg-white px-6 py-4 rounded-xl border border-gray-100 shadow-sm flex flex-col sm:flex-row items-center justify-between gap-3">
        <div class="text-xs font-semibold text-gray-500" id="pageInfo">Menampilkan daftar desainer</div>
        <div class="flex items-center gap-1.5" id="paginationControls"></div>
    </div>

    </div>
    @include("Admin.partials.footer")
</main>

<!-- ══════════ DETAIL MODAL ══════════ -->
<div id="detailModal" class="detail-modal">
    <div class="detail-content bg-white w-full max-w-4xl rounded-3xl overflow-hidden shadow-2xl flex flex-col md:flex-row max-h-[90vh]">
        <div class="w-full md:w-1/2 relative bg-gray-100 min-h-[280px]">
            <img id="modalImg" src="" alt="" class="w-full h-full object-cover">
            <div class="absolute bottom-0 left-0 right-0 p-8 bg-gradient-to-t from-black/80 to-transparent text-white">
                <div class="inline-block px-2 py-1 bg-primary/90 text-white text-[10px] font-black uppercase tracking-widest rounded-md mb-2 font-mono" id="modalId"></div>
                <div class="text-sm font-bold opacity-90"><i data-feather="user" class="w-3.5 h-3.5 inline mr-1.5"></i><span id="modalDesigner"></span></div>
            </div>
        </div>
        <div class="w-full md:w-1/2 p-8 flex flex-col relative overflow-y-auto">
            <button class="absolute top-6 right-6 w-8 h-8 flex items-center justify-center rounded-full bg-gray-100 text-gray-500 hover:bg-gray-200 transition-colors" onclick="closeDetail()">
                <i data-feather="x" class="w-4 h-4"></i>
            </button>
            <div class="text-2xl font-bold text-gray-900 mb-2 pr-8" id="modalTitle"></div>
            <div class="text-xs font-black text-primary uppercase tracking-widest mb-6" id="modalStyle"></div>
            
            <div class="bg-gray-50 p-4 rounded-2xl border border-gray-100 mb-6">
                <div class="text-[9px] font-black text-gray-400 uppercase tracking-widest mb-2">Description</div>
                <div class="text-xs text-gray-600 leading-relaxed italic" id="modalDesc"></div>
            </div>
            
            <div class="grid grid-cols-2 gap-4 mb-8 mt-auto">
                <div class="bg-gray-50 p-4 rounded-2xl border border-gray-100">
                    <div class="text-[9px] font-black text-gray-400 uppercase tracking-widest mb-1">Area</div>
                    <div class="text-sm font-bold text-gray-900" id="modalDim"></div>
                </div>
                <div class="bg-gray-50 p-4 rounded-2xl border border-gray-100">
                    <div class="text-[9px] font-black text-gray-400 uppercase tracking-widest mb-1">Duration</div>
                    <div class="text-sm font-bold text-gray-900" id="modalDuration"></div>
                </div>
                <div class="bg-gray-50 p-4 rounded-2xl border border-gray-100 col-span-2">
                    <div class="text-[9px] font-black text-gray-400 uppercase tracking-widest mb-1">Designer</div>
                    <div class="text-sm font-bold text-gray-900" id="modalDesignerSpec"></div>
                </div>
            </div>
            
            <div class="flex gap-4" id="detailActionArea">
                <button class="flex-1 flex items-center justify-center py-4 bg-green-500 hover:bg-green-600 text-white rounded-xl text-sm font-bold transition-colors" id="modalApproveBtn">
                    <i data-feather="check-circle" class="w-4 h-4 mr-2"></i> Approve & Publish
                </button>
                <button class="flex items-center justify-center px-6 py-4 bg-red-50 hover:bg-red-100 text-red-600 rounded-xl text-sm font-bold transition-colors" id="modalRejectBtn">
                    <i data-feather="x-circle" class="w-4 h-4 mr-2"></i> Tolak
                </button>
            </div>
        </div>
    </div>
</div>

<!-- ══════════ CONFIRM MODAL ══════════ -->
<div id="confirmModal" class="confirm-modal">
    <div class="bg-white w-[400px] p-8 rounded-3xl text-center shadow-2xl transform scale-95 transition-transform duration-200" id="confirmBox">
        <div class="mb-6">
            <div class="w-16 h-16 rounded-2xl flex items-center justify-center mx-auto mb-4" id="confirmIcon">
            </div>
            <div class="text-xl font-bold text-gray-900" id="confirmTitle"></div>
            <div class="text-xs text-gray-500 mt-2" id="confirmDesc"></div>
            <div class="text-sm font-bold text-gray-900 mt-4 px-4 py-2 bg-gray-50 rounded-lg truncate border border-gray-100" id="confirmTarget"></div>
        </div>
        <div class="flex gap-3">
            <button class="flex-1 bg-gray-100 hover:bg-gray-200 text-gray-600 font-bold py-3 rounded-xl text-xs transition-colors" onclick="closeConfirm()">Batal</button>
            <button class="flex-1 text-white font-bold py-3 rounded-xl text-xs transition-colors" id="confirmOkBtn" onclick="executeAction()">
                <div class="flex items-center justify-center gap-2"><i data-feather="check" class="w-4 h-4"></i> <span id="confirmOkLabel">Ya, Lanjutkan</span></div>
            </button>
        </div>
    </div>
</div>

<!-- Hidden Action Form -->
<form id="actionForm" method="POST" style="display: none;">
    @csrf
</form>

<script>
feather.replace({ 'stroke-width': 2 });

/* ── Grouped Designer Table Filter & Pagination ── */
const groupCards = Array.from(document.querySelectorAll('.designer-group-card'));
const searchInput = document.getElementById('portfolioSearch');
const categorySelect = document.getElementById('categoryFilter');
const pageInfo = document.getElementById('pageInfo');
const paginationControls = document.getElementById('paginationControls');
const designersPerPage = 2; // Tampilkan 2 tabel desainer per halaman
let currentPage = 1;

const rowsPerDesigner = 5;

function renderInnerDesignerPagination(group, matchedRows) {
    const allGroupRows = Array.from(group.querySelectorAll('.portfolio-row'));
    const bar = group.querySelector('.inner-pagination-bar');
    const info = group.querySelector('.inner-page-info');
    const controls = group.querySelector('.inner-page-controls');

    let desPage = parseInt(group.getAttribute('data-page') || '1', 10);
    const total = matchedRows.length;
    const totalPages = Math.max(1, Math.ceil(total / rowsPerDesigner));
    if (desPage > totalPages) desPage = totalPages;
    group.setAttribute('data-page', desPage);

    allGroupRows.forEach(r => r.style.display = 'none');
    const start = (desPage - 1) * rowsPerDesigner;
    const end = Math.min(start + rowsPerDesigner, total);
    matchedRows.slice(start, end).forEach(r => r.style.display = '');

    if (bar) {
        bar.style.display = total > rowsPerDesigner ? 'flex' : 'none';
    }
    if (info) {
        info.textContent = `Karya ${start + 1}–${end} dari ${total} (Hal ${desPage}/${totalPages})`;
    }
    if (controls) {
        controls.innerHTML = '';
        if (totalPages > 1) {
            const prevBtn = document.createElement('button');
            prevBtn.innerHTML = '&laquo;';
            prevBtn.disabled = desPage === 1;
            prevBtn.className = `px-2.5 h-7 rounded-lg text-[11px] font-bold border border-gray-200 bg-white text-gray-600 disabled:opacity-40 hover:border-primary hover:text-primary`;
            prevBtn.onclick = () => { group.setAttribute('data-page', desPage - 1); renderInnerDesignerPagination(group, matchedRows); };
            controls.appendChild(prevBtn);

            for (let i = 1; i <= totalPages; i++) {
                const btn = document.createElement('button');
                btn.textContent = i;
                btn.className = `w-7 h-7 rounded-lg text-[11px] font-bold transition-colors ${i === desPage ? 'bg-primary text-white shadow-sm' : 'bg-white border border-gray-200 text-gray-600 hover:border-primary hover:text-primary'}`;
                btn.onclick = () => { group.setAttribute('data-page', i); renderInnerDesignerPagination(group, matchedRows); };
                controls.appendChild(btn);
            }

            const nextBtn = document.createElement('button');
            nextBtn.innerHTML = '&raquo;';
            nextBtn.disabled = desPage === totalPages;
            nextBtn.className = `px-2.5 h-7 rounded-lg text-[11px] font-bold border border-gray-200 bg-white text-gray-600 disabled:opacity-40 hover:border-primary hover:text-primary`;
            nextBtn.onclick = () => { group.setAttribute('data-page', desPage + 1); renderInnerDesignerPagination(group, matchedRows); };
            controls.appendChild(nextBtn);
        }
    }
}

function renderGroupedTables() {
    const term = (searchInput?.value || '').toLowerCase().trim();
    const cat = (categorySelect?.value || '').toLowerCase().trim();

    const matchedGroups = [];

    groupCards.forEach(group => {
        const rows = Array.from(group.querySelectorAll('.portfolio-row'));
        const matchedRows = [];

        rows.forEach(row => {
            const s = row.getAttribute('data-search') || '';
            const c = row.getAttribute('data-category') || '';
            const matchSearch = !term || s.includes(term);
            const matchCat = !cat || c === cat;

            if (matchSearch && matchCat) {
                matchedRows.push(row);
            } else {
                row.style.display = 'none';
            }
        });

        if (matchedRows.length > 0) {
            renderInnerDesignerPagination(group, matchedRows);
            matchedGroups.push(group);
        }
        group.style.display = 'none';
    });

    const total = matchedGroups.length;
    const totalPages = Math.max(1, Math.ceil(total / designersPerPage));
    if (currentPage > totalPages) currentPage = totalPages;

    const start = (currentPage - 1) * designersPerPage;
    const end = Math.min(start + designersPerPage, total);
    matchedGroups.slice(start, end).forEach(g => g.style.display = '');

    pageInfo.textContent = total === 0
        ? 'Tidak ada portofolio/desainer yang cocok dengan filter'
        : `Halaman ${currentPage} dari ${totalPages} — Menampilkan ${start + 1}–${end} dari ${total} desainer`;

    paginationControls.innerHTML = '';
    if (totalPages > 1) {
        const prevBtn = document.createElement('button');
        prevBtn.innerHTML = '&laquo;';
        prevBtn.disabled = currentPage === 1;
        prevBtn.className = `px-3 h-8 rounded-lg text-xs font-bold border border-gray-200 bg-white text-gray-600 disabled:opacity-40 hover:border-primary hover:text-primary`;
        prevBtn.onclick = () => { if (currentPage > 1) { currentPage--; renderGroupedTables(); } };
        paginationControls.appendChild(prevBtn);

        for (let i = 1; i <= totalPages; i++) {
            const btn = document.createElement('button');
            btn.textContent = i;
            btn.className = `w-8 h-8 rounded-lg text-xs font-bold transition-colors ${i === currentPage ? 'bg-primary text-white shadow-sm' : 'bg-white border border-gray-200 text-gray-600 hover:border-primary hover:text-primary'}`;
            btn.onclick = () => { currentPage = i; renderGroupedTables(); };
            paginationControls.appendChild(btn);
        }

        const nextBtn = document.createElement('button');
        nextBtn.innerHTML = '&raquo;';
        nextBtn.disabled = currentPage === totalPages;
        nextBtn.className = `px-3 h-8 rounded-lg text-xs font-bold border border-gray-200 bg-white text-gray-600 disabled:opacity-40 hover:border-primary hover:text-primary`;
        nextBtn.onclick = () => { if (currentPage < totalPages) { currentPage++; renderGroupedTables(); } };
        paginationControls.appendChild(nextBtn);
    }
}

searchInput?.addEventListener('input', () => { currentPage = 1; renderGroupedTables(); });
categorySelect?.addEventListener('change', () => { currentPage = 1; renderGroupedTables(); });
renderGroupedTables();

/* ── detail modal ── */
function openDetail(p) {
    document.getElementById('modalImg').src            = p.image || 'https://images.unsplash.com/photo-1600210492486-724fe5c67fb0?w=600';
    document.getElementById('modalId').textContent     = 'PF-' + String(p.id).padStart(3, '0');
    document.getElementById('modalDesigner').textContent = p.designer;
    document.getElementById('modalTitle').textContent  = p.title;
    document.getElementById('modalStyle').textContent  = p.style;
    document.getElementById('modalDesc').textContent   = p.description ? '"' + p.description + '"' : '-';
    document.getElementById('modalDim').textContent    = p.dimensions;
    document.getElementById('modalDuration').textContent = p.duration;
    document.getElementById('modalDesignerSpec').textContent = p.designer;

    document.getElementById('modalApproveBtn').onclick = () => { closeDetail(); askConfirm('approve', p.id, p.title); };
    document.getElementById('modalRejectBtn').onclick  = () => { closeDetail(); askConfirm('reject',  p.id, p.title); };

    if (p.status !== 'pending') {
        document.getElementById('detailActionArea').style.display = 'none';
    } else {
        document.getElementById('detailActionArea').style.display = 'flex';
    }

    document.getElementById('detailModal').classList.add('show');
    feather.replace({ 'stroke-width': 2 });
}
function closeDetail() { document.getElementById('detailModal').classList.remove('show'); }
window.addEventListener('click', e => {
    if (e.target === document.getElementById('detailModal')) closeDetail();
    if (e.target === document.getElementById('confirmModal')) closeConfirm();
});

/* ── confirm modal ── */
let pendingAction = null;
let pendingId     = null;

function askConfirm(type, id, title) {
    pendingAction = type;
    pendingId     = id;

    const iconWrap  = document.getElementById('confirmIcon');
    const okBtn = document.getElementById('confirmOkBtn');

    if (type === 'approve') {
        iconWrap.className = 'w-16 h-16 rounded-2xl flex items-center justify-center mx-auto mb-4 bg-green-50 text-green-500';
        iconWrap.innerHTML = '<i data-feather="check-circle" class="w-8 h-8"></i>';
        document.getElementById('confirmTitle').textContent = 'Approve Portofolio?';
        document.getElementById('confirmDesc').textContent  = 'Portofolio ini akan dipublikasikan dan dapat dilihat oleh semua pengguna platform.';
        okBtn.className = 'flex-1 font-bold py-3 rounded-xl text-xs transition-colors bg-green-500 hover:bg-green-600 text-white';
        document.getElementById('confirmOkLabel').textContent = 'Ya, Approve';
    } else {
        iconWrap.className = 'w-16 h-16 rounded-2xl flex items-center justify-center mx-auto mb-4 bg-red-50 text-red-500';
        iconWrap.innerHTML = '<i data-feather="x-circle" class="w-8 h-8"></i>';
        document.getElementById('confirmTitle').textContent = 'Tolak Portofolio?';
        document.getElementById('confirmDesc').textContent  = 'Portofolio ini akan ditolak dan tidak akan ditampilkan ke customer.';
        okBtn.className = 'flex-1 font-bold py-3 rounded-xl text-xs transition-colors bg-red-500 hover:bg-red-600 text-white';
        document.getElementById('confirmOkLabel').textContent = 'Ya, Tolak';
    }

    document.getElementById('confirmTarget').textContent = title;
    document.getElementById('confirmModal').classList.add('show');
    feather.replace({ 'stroke-width': 2 });
}

function closeConfirm() {
    document.getElementById('confirmModal').classList.remove('show');
    pendingAction = null;
    pendingId     = null;
}

function executeAction() {
    if (!pendingAction || !pendingId) return;

    const form = document.getElementById('actionForm');
    if (pendingAction === 'approve') {
        form.action = `/admin/portofolio-validation/${pendingId}/approve`;
    } else {
        form.action = `/admin/portofolio-validation/${pendingId}/reject`;
    }
    form.submit();
    closeConfirm();
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
