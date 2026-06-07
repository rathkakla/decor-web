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

$stats_view = [
    ["label" => "Pending",   "value" => sprintf("%02d", $stats['pending']),  "icon" => "clock",        "note" => "menunggu kurasi",   "color" => "warning"],
    ["label" => "Published", "value" => sprintf("%02d", $stats['approved']), "icon" => "check-circle", "note" => "karya live",        "color" => "success"],
    ["label" => "Rejected",  "value" => sprintf("%02d", $stats['rejected']), "icon" => "x-circle",     "note" => "tidak layak",       "color" => "danger"],
    ["label" => "Total",     "value" => sprintf("%02d", $stats['total']),    "icon" => "zap",          "note" => "semua portofolio",  "color" => "primary"],
];
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
    <div class="p-8 space-y-8 flex-1">

    <div class="mb-8">
        <div class="text-[10px] font-black text-primary uppercase tracking-widest mb-1">Aesthetic Curation</div>
        <div class="text-2xl font-bold text-gray-900">Portfolio Validation</div>
        <div class="text-xs text-gray-500 mt-1">Kurasi dan publikasikan portofolio karya desainer sebelum tampil di platform.</div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
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

    <div class="flex items-center gap-4 mb-6">
        <div class="flex-1 relative">
            <i data-feather="search" class="w-4 h-4 absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
            <input type="text" id="portfolioSearch" class="w-full pl-10 pr-4 py-3 bg-white border border-gray-100 rounded-xl text-xs font-semibold focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary shadow-sm" placeholder="Cari portofolio, desainer, atau style…">
        </div>
        <div class="flex gap-1 bg-white border border-gray-100 shadow-sm rounded-xl p-1">
            <a href="{{ route('admin.portfolio-validation', ['status' => 'semua']) }}" class="px-4 py-2 rounded-lg text-[10px] font-bold uppercase tracking-widest transition-colors {{ $statusFilter == 'semua' ? 'bg-orange-50 text-primary' : 'text-gray-500 hover:bg-gray-50' }}">Semua</a>
            <a href="{{ route('admin.portfolio-validation', ['status' => 'pending']) }}" class="px-4 py-2 rounded-lg text-[10px] font-bold uppercase tracking-widest transition-colors {{ $statusFilter == 'pending' ? 'bg-orange-50 text-primary' : 'text-gray-500 hover:bg-gray-50' }}">Pending</a>
            <a href="{{ route('admin.portfolio-validation', ['status' => 'approved']) }}" class="px-4 py-2 rounded-lg text-[10px] font-bold uppercase tracking-widest transition-colors {{ $statusFilter == 'approved' ? 'bg-orange-50 text-primary' : 'text-gray-500 hover:bg-gray-50' }}">Published</a>
            <a href="{{ route('admin.portfolio-validation', ['status' => 'rejected']) }}" class="px-4 py-2 rounded-lg text-[10px] font-bold uppercase tracking-widest transition-colors {{ $statusFilter == 'rejected' ? 'bg-orange-50 text-primary' : 'text-gray-500 hover:bg-gray-50' }}">Rejected</a>
        </div>
    </div>

    <!-- Portfolio List -->
    <div class="space-y-4" id="portfolioList">
        @forelse ($portfolios as $p)
        @php
            $sl = strtolower($p->status);
            $leftBorder = match($sl) {
                'pending' => 'border-l-4 border-amber-500',
                'approved' => 'border-l-4 border-green-500',
                'rejected' => 'border-l-4 border-red-500',
                default => 'border-l-4 border-gray-500'
            };
        @endphp
        <div class="product-row bg-white rounded-xl border border-gray-100 p-4 flex items-center gap-6 shadow-sm hover:shadow-md transition-shadow group {{ $leftBorder }} {{ $sl == 'rejected' ? 'opacity-75' : '' }}" id="row-{{ $p->id }}" data-search="{{ strtolower($p->title . ' ' . ($p->designer->user->full_name ?? '') . ' ' . $p->category) }}">

            <!-- Thumbnail -->
            <div class="shrink-0">
                <img src="{{ $p->image_url ? asset('storage/' . $p->image_url) : 'https://images.unsplash.com/photo-1600210492486-724fe5c67fb0?w=600' }}" 
                     class="w-20 h-20 rounded-xl object-cover border border-gray-100 cursor-pointer hover:opacity-80 transition-opacity"
                     onclick="openDetail({{ json_encode([
                          'id' => $p->id,
                          'title' => $p->title,
                          'designer' => $p->designer->user->full_name ?? 'N/A',
                          'style' => $p->category ?? '-',
                          'image' => $p->image_url ? asset('storage/' . $p->image_url) : '',
                          'description' => $p->description ?? '',
                          'dimensions' => $p->area ?? '-',
                          'duration' => $p->duration ?? '-',
                          'status' => $p->status
                     ]) }})">
            </div>

            <!-- Info -->
            <div class="w-1/4 cursor-pointer" onclick="openDetail({{ json_encode([
                  'id' => $p->id,
                  'title' => $p->title,
                  'designer' => $p->designer->user->full_name ?? 'N/A',
                  'style' => $p->category ?? '-',
                  'image' => $p->image_url ? asset('storage/' . $p->image_url) : '',
                  'description' => $p->description ?? '',
                  'dimensions' => $p->area ?? '-',
                  'duration' => $p->duration ?? '-',
                  'status' => $p->status
             ]) }})">
                <div class="inline-block px-2 py-1 bg-orange-50 text-primary text-[9px] font-black uppercase tracking-widest rounded-md mb-1.5 font-mono">PF-{{ str_pad($p->id, 3, '0', STR_PAD_LEFT) }}</div>
                <div class="text-sm font-bold text-gray-900 hover:text-primary transition-colors truncate">{{ $p->title }}</div>
                <div class="text-[10px] text-gray-500 font-semibold mt-1"><i data-feather="user" class="w-3 h-3 inline mr-1"></i>{{ $p->designer->user->full_name ?? 'N/A' }}</div>
            </div>

            <!-- Style -->
            <div class="w-1/6">
                <span class="inline-flex items-center px-2.5 py-1 rounded-md text-[9px] font-black text-gray-600 bg-gray-100 uppercase tracking-widest">
                    {{ $p->category ?? '-' }}
                </span>
            </div>

            <!-- Dim + Duration -->
            <div class="w-1/6">
                <div class="text-sm font-bold text-gray-900">{{ $p->area ?? '-' }}</div>
                <div class="text-[10px] font-semibold text-gray-500 mt-1">Durasi: {{ $p->duration ?? '-' }}</div>
            </div>

            <!-- Submitted -->
            <div class="w-1/6">
                <div class="flex items-center text-[10px] font-bold text-gray-500 uppercase tracking-widest">
                    <i data-feather="clock" class="w-3 h-3 mr-1.5"></i> {{ $p->created_at->diffForHumans() }}
                </div>
            </div>

            <!-- Status -->
            <div class="w-1/6">
                @php
                    $statusCls = match($sl) {
                        'pending' => 'bg-amber-50 text-amber-600',
                        'approved' => 'bg-green-50 text-green-600',
                        'rejected' => 'bg-red-50 text-red-600',
                        default => 'bg-gray-50 text-gray-600'
                    };
                @endphp
                <span class="inline-flex items-center px-2.5 py-1 rounded-md text-[9px] font-black uppercase tracking-widest {{ $statusCls }}" id="pill-{{ $p->id }}">
                    {{ strtoupper($p->status) }}
                </span>
            </div>

            <!-- Actions -->
            <div class="flex-1 flex justify-end gap-2">
                @if($p->status == 'pending')
                <button class="w-9 h-9 flex items-center justify-center rounded-lg bg-green-50 text-green-600 hover:bg-green-100 transition-colors" title="Approve"
                    onclick="askConfirm('approve','{{ $p->id }}','{{ addslashes($p->title) }}')">
                    <i data-feather="check" class="w-4 h-4"></i>
                </button>
                <button class="w-9 h-9 flex items-center justify-center rounded-lg bg-red-50 text-red-600 hover:bg-red-100 transition-colors" title="Reject"
                    onclick="askConfirm('reject','{{ $p->id }}','{{ addslashes($p->title) }}')">
                    <i data-feather="x" class="w-4 h-4"></i>
                </button>
                @endif
            </div>

        </div>
        @empty
        <div class="text-center py-12 bg-white rounded-xl border border-gray-100 text-gray-400 shadow-sm">
            <i data-feather="image" class="w-12 h-12 mx-auto mb-3 opacity-50"></i>
            <p class="text-sm font-semibold">Tidak ada portofolio dengan status "{{ ucfirst($statusFilter) }}"</p>
        </div>
        @endforelse
    </div>
    </div>
</main>

<!-- ══════════ DETAIL MODAL ══════════ -->
<div id="detailModal" class="detail-modal">
    <div class="detail-content bg-white w-full max-w-4xl rounded-3xl overflow-hidden shadow-2xl flex flex-col md:flex-row max-h-[90vh]">
        <div class="w-full md:w-1/2 relative bg-gray-100">
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

/* ── search functionality ── */
document.getElementById('portfolioSearch').addEventListener('input', function(e) {
    const term = e.target.value.toLowerCase().trim();
    document.querySelectorAll('#portfolioList .product-row').forEach(row => {
        const searchText = row.getAttribute('data-search');
        if (!searchText || searchText.includes(term)) {
            row.style.display = 'flex';
        } else {
            row.style.display = 'none';
        }
    });
});

/* ── current detail product ── */
let currentDetail = null;

/* ── detail modal ── */
function openDetail(p) {
    currentDetail = p;
    document.getElementById('modalImg').src            = p.image || 'https://images.unsplash.com/photo-1600210492486-724fe5c67fb0?w=600';
    document.getElementById('modalId').textContent     = 'PF-' + String(p.id).padStart(3, '0');
    document.getElementById('modalDesigner').textContent = p.designer;
    document.getElementById('modalTitle').textContent  = p.title;
    document.getElementById('modalStyle').textContent  = p.style;
    document.getElementById('modalDesc').textContent   = p.description ? '"' + p.description + '"' : '-';
    document.getElementById('modalDim').textContent    = p.dimensions;
    document.getElementById('modalDuration').textContent = p.duration;
    document.getElementById('modalDesignerSpec').textContent = p.designer;

    /* wire modal buttons */
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