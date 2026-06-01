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
    ["label" => "Pending",    "value" => number_format($stats['pending']), "icon" => "clock",        "note" => "menunggu review",  "color" => "warning"],
    ["label" => "Disetujui",  "value" => number_format($stats['approved']), "icon" => "check-circle", "note" => "total disetujui", "color" => "success"],
    ["label" => "Ditolak",    "value" => number_format($stats['rejected']), "icon" => "x-circle",     "note" => "total ditolak",     "color" => "danger"],
    ["label" => "Avg. Review","value" => "3j", "icon" => "zap",          "note" => "waktu validasi",   "color" => "primary"],
];
@endphp
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product Validation — DECOR Admin</title>
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
        .detail-modal, .confirm-modal { position: fixed; inset: 0; background: rgba(28, 20, 16, 0.4); backdrop-filter: blur(8px); display: flex; align-items: center; justify-content: center; z-index: 2000; opacity: 0; pointer-events: none; transition: 0.3s; }
        .detail-modal.show, .confirm-modal.show { opacity: 1; pointer-events: auto; }
        .detail-content { transform: scale(0.95); transition: 0.3s; }
        .detail-modal.show .detail-content { transform: scale(1); }
    </style>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body class="text-gray-800 bg-[#F8F6F4]">

@include("Admin.partials.sidebar")

<main id="main-content" class="flex-1 flex flex-col ml-64 sidebar-transition min-h-screen bg-[#F8F6F4]">
    @include("Admin.partials.header", ["title" => "Product Validation"])
    <div class="p-8 space-y-8 flex-1">

    <div class="mb-8">
        <div class="text-[10px] font-black text-primary uppercase tracking-widest mb-1">Quality Control</div>
        <div class="text-2xl font-bold text-gray-900">Product Validation</div>
        <div class="text-xs text-gray-500 mt-1">Review dan setujui produk baru sebelum tampil di katalog marketplace.</div>
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
            <input type="text" class="w-full pl-10 pr-4 py-3 bg-white border border-gray-100 rounded-xl text-xs font-semibold focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary shadow-sm" placeholder="Cari produk, toko, atau kategori…">
        </div>
        <div class="flex gap-1 bg-white border border-gray-100 shadow-sm rounded-xl p-1">
            <a href="{{ route('admin.product.validation', ['status' => 'semua']) }}" class="px-4 py-2 rounded-lg text-[10px] font-bold uppercase tracking-widest transition-colors {{ $statusFilter == 'semua' ? 'bg-orange-50 text-primary' : 'text-gray-500 hover:bg-gray-50' }}">Semua</a>
            <a href="{{ route('admin.product.validation', ['status' => 'pending']) }}" class="px-4 py-2 rounded-lg text-[10px] font-bold uppercase tracking-widest transition-colors {{ $statusFilter == 'pending' ? 'bg-orange-50 text-primary' : 'text-gray-500 hover:bg-gray-50' }}">Pending</a>
            <a href="{{ route('admin.product.validation', ['status' => 'approved']) }}" class="px-4 py-2 rounded-lg text-[10px] font-bold uppercase tracking-widest transition-colors {{ $statusFilter == 'approved' ? 'bg-orange-50 text-primary' : 'text-gray-500 hover:bg-gray-50' }}">Approved</a>
            <a href="{{ route('admin.product.validation', ['status' => 'rejected']) }}" class="px-4 py-2 rounded-lg text-[10px] font-bold uppercase tracking-widest transition-colors {{ $statusFilter == 'rejected' ? 'bg-orange-50 text-primary' : 'text-gray-500 hover:bg-gray-50' }}">Rejected</a>
        </div>
    </div>

    <!-- Product List -->
    <div class="space-y-4">
        @if($products->isEmpty())
            <div class="text-center py-12 bg-white rounded-xl border border-gray-100 text-gray-400 shadow-sm">
                <i data-feather="box" class="w-12 h-12 mx-auto mb-3 opacity-50"></i>
                <p class="text-sm font-semibold">Tidak ada produk untuk saat ini.</p>
            </div>
        @endif

        @foreach ($products as $p)
        @php
            $sl = strtolower($p->status);
        @endphp
        <div class="bg-white rounded-xl border border-gray-100 p-4 flex items-center gap-6 shadow-sm hover:border-primary/30 transition-colors group {{ $sl == 'rejected' ? 'opacity-75' : '' }}" id="row-{{ $p->id }}">

            <!-- Thumbnail -->
            <div class="shrink-0">
                <img src="{{ $p->images->first()->img_url ?? 'https://images.unsplash.com/photo-1555041469-a586c61ea9bc?w=600' }}" 
                     class="w-20 h-20 rounded-xl object-cover border border-gray-100 cursor-pointer hover:opacity-80 transition-opacity"
                     onclick="openDetail({{ json_encode($p) }}, '{{ addslashes($p->seller->user->full_name) }}', '{{ addslashes($p->category->name) }}')">
            </div>

            <!-- Product Info -->
            <div class="w-1/4">
                <div class="inline-block px-2 py-1 bg-orange-50 text-primary text-[9px] font-black uppercase tracking-widest rounded-md mb-1.5 font-mono">PRD-{{ str_pad($p->id, 3, '0', STR_PAD_LEFT) }}</div>
                <div class="text-sm font-bold text-gray-900 truncate">{{ $p->name }}</div>
                <div class="text-[10px] text-gray-500 font-semibold mt-1"><i data-feather="user" class="w-3 h-3 inline mr-1"></i>{{ $p->seller->user->full_name }}</div>
            </div>

            <!-- Category -->
            <div class="w-1/6">
                <span class="inline-flex items-center px-2.5 py-1 rounded-md text-[9px] font-black text-gray-600 bg-gray-100 uppercase tracking-widest">
                    {{ $p->category->name }}
                </span>
            </div>

            <!-- Price + Stock -->
            <div class="w-1/6">
                <div class="text-sm font-bold text-gray-900">Rp {{ number_format($p->price, 0, ',', '.') }}</div>
                <div class="text-[10px] font-semibold text-gray-500 mt-1">Stok: {{ $p->stock }} unit</div>
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
                <form action="{{ route('admin.product.approve', $p->id) }}" method="POST">
                    @csrf
                    <button type="submit" class="w-9 h-9 flex items-center justify-center rounded-lg bg-green-50 text-green-600 hover:bg-green-100 transition-colors" title="Approve">
                        <i data-feather="check" class="w-4 h-4"></i>
                    </button>
                </form>
                <button class="w-9 h-9 flex items-center justify-center rounded-lg bg-red-50 text-red-600 hover:bg-red-100 transition-colors" title="Reject"
                    onclick="askConfirm('reject','{{ $p->id }}','{{ addslashes($p->name) }}')">
                    <i data-feather="x" class="w-4 h-4"></i>
                </button>
                @else
                <button class="flex items-center px-3 py-1.5 rounded-lg text-[10px] font-bold bg-orange-50 text-primary hover:bg-orange-100 transition-colors" onclick="openDetail({{ json_encode($p) }}, '{{ addslashes($p->seller->user->full_name) }}', '{{ addslashes($p->category->name) }}')">
                    <i data-feather="eye" class="w-3.5 h-3.5 mr-1.5"></i> Detail
                </button>
                @endif
            </div>

        </div>
        @endforeach
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
                <div class="text-sm font-bold opacity-90"><i data-feather="store" class="w-3.5 h-3.5 inline mr-1.5"></i><span id="modalShop"></span></div>
            </div>
        </div>
        <div class="w-full md:w-1/2 p-8 flex flex-col relative overflow-y-auto">
            <button class="absolute top-6 right-6 w-8 h-8 flex items-center justify-center rounded-full bg-gray-100 text-gray-500 hover:bg-gray-200 transition-colors" onclick="closeDetail()">
                <i data-feather="x" class="w-4 h-4"></i>
            </button>
            <div class="text-2xl font-bold text-gray-900 mb-2 pr-8" id="modalName"></div>
            <div class="text-2xl font-black text-primary mb-6" id="modalPrice"></div>
            
            <div class="bg-gray-50 p-4 rounded-2xl border border-gray-100 mb-6">
                <div class="text-[9px] font-black text-gray-400 uppercase tracking-widest mb-2">Description</div>
                <div class="text-xs text-gray-600 leading-relaxed italic" id="modalDesc"></div>
            </div>
            
            <div class="grid grid-cols-2 gap-4 mb-8 mt-auto">
                <div class="bg-gray-50 p-4 rounded-2xl border border-gray-100">
                    <div class="text-[9px] font-black text-gray-400 uppercase tracking-widest mb-1">Style</div>
                    <div class="text-sm font-bold text-gray-900" id="modalStyle"></div>
                </div>
                <div class="bg-gray-50 p-4 rounded-2xl border border-gray-100">
                    <div class="text-[9px] font-black text-gray-400 uppercase tracking-widest mb-1">Category</div>
                    <div class="text-sm font-bold text-gray-900" id="modalCat"></div>
                </div>
                <div class="bg-gray-50 p-4 rounded-2xl border border-gray-100 col-span-2">
                    <div class="text-[9px] font-black text-gray-400 uppercase tracking-widest mb-1">Stock Available</div>
                    <div class="text-sm font-bold text-gray-900" id="modalStock"></div>
                </div>
            </div>
            
            <div class="flex gap-4" id="modalActionArea">
                <form id="modalApproveForm" method="POST" class="flex-1">
                    @csrf
                    <button type="submit" class="w-full flex items-center justify-center py-4 bg-green-500 hover:bg-green-600 text-white rounded-xl text-sm font-bold transition-colors">
                        <i data-feather="check-circle" class="w-4 h-4 mr-2"></i> Approve Produk
                    </button>
                </form>
                <button class="flex items-center justify-center px-6 py-4 bg-red-50 hover:bg-red-100 text-red-600 rounded-xl text-sm font-bold transition-colors" id="modalRejectBtn">
                    <i data-feather="x-circle" class="w-4 h-4 mr-2"></i> Tolak
                </button>
            </div>
        </div>
    </div>
</div>

<!-- ══════════ REJECT REASON MODAL ══════════ -->
<div id="confirmModal" class="confirm-modal">
    <div class="bg-white w-[400px] p-8 rounded-3xl text-center shadow-2xl transform scale-95 transition-transform duration-200" id="confirmBox">
        <form id="rejectForm" method="POST">
            @csrf
            <div class="mb-6">
                <div class="w-16 h-16 rounded-2xl bg-red-50 text-red-500 flex items-center justify-center mx-auto mb-4">
                    <i data-feather="x-circle" class="w-8 h-8"></i>
                </div>
                <div class="text-xl font-bold text-gray-900">Tolak Produk</div>
                <div class="text-xs text-gray-500 mt-2">Berikan alasan penolakan agar seller dapat memperbaiki produk mereka.</div>
                <div class="text-sm font-bold text-gray-900 mt-4 px-4 py-2 bg-gray-50 rounded-lg truncate" id="confirmTarget"></div>
                <textarea name="message" class="w-full mt-4 h-24 p-3 bg-gray-50 border border-gray-200 rounded-xl text-xs focus:outline-none focus:border-red-300 focus:ring-1 focus:ring-red-300 transition-colors" placeholder="Tulis alasan penolakan di sini..."></textarea>
            </div>
            <div class="flex gap-3">
                <button type="button" class="flex-1 bg-gray-100 hover:bg-gray-200 text-gray-600 font-bold py-3 rounded-xl text-xs transition-colors" onclick="closeConfirm()">Batal</button>
                <button type="submit" class="flex-1 bg-red-500 hover:bg-red-600 text-white font-bold py-3 rounded-xl text-xs transition-colors">Tolak Produk</button>
            </div>
        </form>
    </div>
</div>

<script>
feather.replace();

/* ── detail modal ── */
function openDetail(p, shopName, catName) {
    const images = p.images || [];
    document.getElementById('modalImg').src      = images.length > 0 ? images[0].img_url : 'https://images.unsplash.com/photo-1555041469-a586c61ea9bc?w=600';
    document.getElementById('modalId').textContent   = 'PRD-' + String(p.id).padStart(3, '0');
    document.getElementById('modalShop').textContent = shopName;
    document.getElementById('modalName').textContent = p.name;
    document.getElementById('modalPrice').textContent = 'Rp ' + new Intl.NumberFormat('id-ID').format(p.price);
    document.getElementById('modalDesc').textContent = '"' + p.description + '"';
    document.getElementById('modalStyle').textContent = p.style || '-';
    document.getElementById('modalCat').textContent     = catName;
    document.getElementById('modalStock').textContent   = p.stock + ' unit';

    /* wire modal buttons */
    const approveForm = document.getElementById('modalApproveForm');
    approveForm.action = `/admin/product-validation/${p.id}/approve`;
    
    const rejectBtn = document.getElementById('modalRejectBtn');
    rejectBtn.onclick = () => { closeDetail(); askConfirm('reject', p.id, p.name); };

    if (p.status !== 'pending') {
        document.getElementById('modalActionArea').style.display = 'none';
    } else {
        document.getElementById('modalActionArea').style.display = 'flex';
    }

    document.getElementById('detailModal').classList.add('show');
    feather.replace();
}
function closeDetail() { document.getElementById('detailModal').classList.remove('show'); }

window.addEventListener('click', e => {
    if (e.target === document.getElementById('detailModal')) closeDetail();
    if (e.target === document.getElementById('confirmModal')) closeConfirm();
});

/* ── confirm modal ── */
function askConfirm(type, id, name) {
    const form = document.getElementById('rejectForm');
    form.action = `/admin/product-validation/${id}/reject`;
    document.getElementById('confirmTarget').textContent = name;
    document.getElementById('confirmModal').classList.add('show');
    feather.replace();
}

function closeConfirm() {
    document.getElementById('confirmModal').classList.remove('show');
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