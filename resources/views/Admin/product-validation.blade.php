@php
$admin_name = Auth::user()->full_name;
$admin_role = "ADMIN";

$stats_view = [
    ["label" => "Pending",   "value" => sprintf("%02d", $stats['pending']),             "icon" => "clock",        "note" => "menunggu kurasi", "color" => "warning"],
    ["label" => "Published", "value" => sprintf("%02d", $stats['approved']),            "icon" => "check-circle", "note" => "produk live",     "color" => "success"],
    ["label" => "Rejected",  "value" => sprintf("%02d", $stats['rejected']),            "icon" => "x-circle",     "note" => "tidak layak",     "color" => "danger"],
    ["label" => "Total",     "value" => sprintf("%02d", $stats['total'] ?? ($stats['pending'] + $stats['approved'] + $stats['rejected'])), "icon" => "zap", "note" => "semua produk", "color" => "primary"],
];

$categoriesList = $products->pluck('category.name')->filter()->unique()->sort()->values();
$sellersList = $products->map(fn($p) => $p->seller->store_name ?? ($p->seller->user->full_name ?? 'Official Store'))->filter()->unique()->sort()->values();
$groupedBySeller = $products->groupBy(fn($p) => $p->seller_id ?? 0);
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
    <div class="p-8 space-y-6 flex-1">
    
    @if(session('success'))
        <div class="bg-green-50 border border-green-200 text-green-600 px-6 py-4 rounded-xl text-xs font-bold flex items-center">
            <i data-feather="check-circle" class="w-4 h-4 mr-3"></i>
            {{ session('success') }}
        </div>
    @endif
    
    @if($errors->any())
        <div class="bg-red-50 border border-red-200 text-red-600 px-6 py-4 rounded-xl text-xs font-bold flex flex-col justify-center">
            <div class="flex items-center mb-2">
                <i data-feather="alert-circle" class="w-4 h-4 mr-3"></i>
                <span>Terdapat kesalahan:</span>
            </div>
            <ul class="list-disc list-inside ml-7">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div>
        <div class="text-[10px] font-black text-primary uppercase tracking-widest mb-1">Quality Control</div>
        <div class="text-2xl font-bold text-gray-900">Product Validation</div>
        <div class="text-xs text-gray-500 mt-1">Review dan setujui produk baru yang dikelompokkan per toko sebelum tampil di katalog marketplace.</div>
    </div>

    <!-- Top Stat Cards -->
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
                <input type="text" id="productSearch" class="w-full pl-10 pr-4 py-2 bg-gray-50 border border-gray-200 rounded-xl text-xs font-semibold focus:outline-none focus:border-primary focus:bg-white transition-colors" placeholder="Cari nama produk, ID, atau toko…">
            </div>
            <select id="categoryFilter" class="px-3.5 py-2 bg-gray-50 border border-gray-200 rounded-xl text-xs font-bold text-gray-600 focus:outline-none focus:border-primary">
                <option value="">Semua Kategori</option>
                @foreach($categoriesList as $cat)
                    <option value="{{ strtolower($cat) }}">{{ $cat }}</option>
                @endforeach
            </select>
        </div>

        <div class="flex gap-1 bg-gray-50 border border-gray-200 rounded-xl p-1 self-start lg:self-auto">
            <a href="{{ route('admin.product.validation', ['status' => 'semua']) }}" class="px-3.5 py-1.5 rounded-lg text-[10px] font-bold uppercase tracking-widest transition-colors {{ $statusFilter == 'semua' ? 'bg-white text-primary shadow-sm' : 'text-gray-500 hover:text-primary' }}">Semua</a>
            <a href="{{ route('admin.product.validation', ['status' => 'pending']) }}" class="px-3.5 py-1.5 rounded-lg text-[10px] font-bold uppercase tracking-widest transition-colors {{ $statusFilter == 'pending' ? 'bg-white text-primary shadow-sm' : 'text-gray-500 hover:text-primary' }}">Pending</a>
            <a href="{{ route('admin.product.validation', ['status' => 'approved']) }}" class="px-3.5 py-1.5 rounded-lg text-[10px] font-bold uppercase tracking-widest transition-colors {{ $statusFilter == 'approved' ? 'bg-white text-primary shadow-sm' : 'text-gray-500 hover:text-primary' }}">Published</a>
            <a href="{{ route('admin.product.validation', ['status' => 'rejected']) }}" class="px-3.5 py-1.5 rounded-lg text-[10px] font-bold uppercase tracking-widest transition-colors {{ $statusFilter == 'rejected' ? 'bg-white text-primary shadow-sm' : 'text-gray-500 hover:text-primary' }}">Rejected</a>
        </div>
    </div>

    <!-- Grouped Tables Per Seller/Store -->
    <div id="sellerTablesContainer" class="space-y-6">
        @forelse ($groupedBySeller as $sellerId => $sellerProducts)
            @php
                $firstProd = $sellerProducts->first();
                $sellerObj = $firstProd->seller;
                $storeName = $sellerObj->store_name ?? 'Official Store';
                $ownerName = $sellerObj->user->full_name ?? 'Unknown Owner';
                $pendingCount = $sellerProducts->where('status', 'pending')->count();
                $approvedCount = $sellerProducts->where('status', 'approved')->count();
            @endphp
            <div class="seller-group-card bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden"
                 data-seller="{{ strtolower($storeName) }}">
                <!-- Store Header -->
                <div class="px-6 py-4 bg-gray-50/70 border-b border-gray-100 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                    <div class="flex items-center gap-3.5">
                        <div class="w-10 h-10 rounded-xl bg-orange-50 border border-orange-100 text-primary flex items-center justify-center shrink-0">
                            <i data-feather="shopping-bag" class="w-4 h-4"></i>
                        </div>
                        <div>
                            <div class="flex items-center gap-2">
                                <h3 class="text-sm font-bold text-gray-900">{{ $storeName }}</h3>
                                <span class="font-mono text-[9px] text-gray-500 bg-white border border-gray-200 px-2 py-0.5 rounded">SEL-{{ str_pad($sellerId, 3, '0', STR_PAD_LEFT) }}</span>
                            </div>
                            <p class="text-[11px] text-gray-500 font-semibold mt-0.5">Owner: {{ $ownerName }} &bull; {{ $sellerProducts->count() }} Produk</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-2">
                        @if($pendingCount > 0)
                            <span class="px-2.5 py-1 rounded-md text-[9px] font-black uppercase tracking-widest bg-amber-50 text-amber-600 border border-amber-200">{{ $pendingCount }} Pending</span>
                        @endif
                        @if($approvedCount > 0)
                            <span class="px-2.5 py-1 rounded-md text-[9px] font-black uppercase tracking-widest bg-green-50 text-green-600 border border-green-200">{{ $approvedCount }} Published</span>
                        @endif
                        @if($sellerObj)
                            <a href="{{ route('admin.seller-detail', $sellerObj->id) }}" class="inline-flex items-center gap-1 px-3 py-1.5 rounded-lg text-[10px] font-bold bg-white border border-gray-200 text-gray-700 hover:border-primary hover:text-primary transition-colors">
                                Profil Toko <i data-feather="arrow-up-right" class="w-3 h-3"></i>
                            </a>
                        @endif
                    </div>
                </div>

                <!-- Store Products Table -->
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
                                <th class="py-3 px-6">Produk</th>
                                <th class="py-3 px-6">Kategori & Style</th>
                                <th class="py-3 px-6">Harga & Stok</th>
                                <th class="py-3 px-6">Diajukan</th>
                                <th class="py-3 px-6">Status</th>
                                <th class="py-3 px-6 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 text-xs">
                            @foreach ($sellerProducts as $p)
                            @php
                                $sl = strtolower($p->status);
                                $imgUrl = $p->images->first()?->img_url ?? 'https://images.unsplash.com/photo-1555041469-a586c61ea9bc?w=600';
                                if (!\Illuminate\Support\Str::startsWith($imgUrl, ['/storage', 'storage', 'http'])) {
                                    $imgUrl = asset('storage/' . $imgUrl);
                                }
                                $statusCls = match($sl) {
                                    'pending' => 'bg-amber-50 text-amber-600 border-amber-200',
                                    'approved' => 'bg-green-50 text-green-600 border-green-200',
                                    'rejected' => 'bg-red-50 text-red-600 border-red-200',
                                    default => 'bg-gray-50 text-gray-600 border-gray-200'
                                };
                                $detailPayload = [
                                    'id' => $p->id,
                                    'name' => $p->name,
                                    'price' => $p->price,
                                    'stock' => $p->stock,
                                    'style' => $p->style ?? '-',
                                    'material' => $p->material ?? '-',
                                    'description' => $p->description ?? '-',
                                    'status' => $p->status,
                                    'image' => $imgUrl,
                                    'shop' => $storeName,
                                    'category' => $p->category->name ?? 'Uncategorized',
                                ];
                            @endphp
                            <tr class="product-row hover:bg-gray-50/70 transition-colors {{ $sl == 'rejected' ? 'opacity-75' : '' }}"
                                data-search="{{ strtolower('PRD-' . str_pad($p->id, 3, '0', STR_PAD_LEFT) . ' ' . $p->name . ' ' . $storeName . ' ' . $ownerName) }}"
                                data-category="{{ strtolower($p->category->name ?? '') }}">
                                <td class="py-3.5 px-6">
                                    <div class="flex items-center gap-3.5 overflow-hidden">
                                        <img src="{{ $imgUrl }}"
                                             class="w-12 h-12 rounded-xl object-cover border border-gray-100 cursor-pointer hover:opacity-80 transition-opacity shrink-0"
                                             onclick='openDetail(@json($detailPayload))'>
                                        <div class="min-w-0 flex-1">
                                            <span class="inline-block px-2 py-0.5 bg-orange-50 text-primary text-[9px] font-black uppercase tracking-widest rounded font-mono mb-0.5">PRD-{{ str_pad($p->id, 3, '0', STR_PAD_LEFT) }}</span>
                                            <div class="font-bold text-gray-900 truncate cursor-pointer hover:text-primary transition-colors" onclick='openDetail(@json($detailPayload))'>{{ $p->name }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="py-3.5 px-6">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-md text-[9px] font-black text-gray-600 bg-gray-100 uppercase tracking-widest">
                                        {{ $p->category->name ?? 'Uncategorized' }}
                                    </span>
                                    <div class="text-[10px] text-gray-400 font-semibold mt-1">{{ $p->style ?: 'Standard' }}</div>
                                </td>
                                <td class="py-3.5 px-6">
                                    <div class="font-bold text-gray-900">Rp {{ number_format($p->price, 0, ',', '.') }}</div>
                                    <div class="text-[10px] font-semibold text-gray-400 mt-0.5">Stok: {{ $p->stock }} unit</div>
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
                                        <form action="{{ route('admin.product.approve', $p->id) }}" method="POST" class="inline">
                                            @csrf
                                            <button type="submit" class="w-8 h-8 flex items-center justify-center rounded-lg bg-green-50 text-green-600 hover:bg-green-100 transition-colors" title="Approve">
                                                <i data-feather="check" class="w-3.5 h-3.5"></i>
                                            </button>
                                        </form>
                                        <button type="button" class="w-8 h-8 flex items-center justify-center rounded-lg bg-red-50 text-red-600 hover:bg-red-100 transition-colors" title="Reject"
                                            onclick="askConfirm('reject','{{ $p->id }}','{{ addslashes($p->name) }}')">
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

                <!-- Inner Pagination Per 5 Produk dalam Toko -->
                <div class="px-6 py-3 border-t border-gray-100 bg-gray-50/40 flex flex-col sm:flex-row items-center justify-between gap-2 inner-pagination-bar">
                    <div class="text-[11px] font-semibold text-gray-500 inner-page-info"></div>
                    <div class="flex items-center gap-1 inner-page-controls"></div>
                </div>
            </div>
        @empty
            <div class="text-center py-12 bg-white rounded-xl border border-gray-100 text-gray-400 shadow-sm">
                <i data-feather="box" class="w-10 h-10 mx-auto mb-2 opacity-50"></i>
                <p class="text-xs font-semibold">Tidak ada produk untuk saat ini.</p>
            </div>
        @endforelse
    </div>

    <!-- Pagination Bar (Per Toko) -->
    <div class="bg-white px-6 py-4 rounded-xl border border-gray-100 shadow-sm flex flex-col sm:flex-row items-center justify-between gap-3">
        <div class="text-xs font-semibold text-gray-500" id="pageInfo">Menampilkan daftar toko</div>
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
                <div class="text-sm font-bold opacity-90"><i data-feather="shopping-bag" class="w-3.5 h-3.5 inline mr-1.5"></i><span id="modalShop"></span></div>
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
                <textarea name="message" required class="w-full mt-4 h-24 p-3 bg-gray-50 border border-gray-200 rounded-xl text-xs focus:outline-none focus:border-red-300 focus:ring-1 focus:ring-red-300 transition-colors" placeholder="Tulis alasan penolakan di sini..."></textarea>
            </div>
            <div class="flex gap-3">
                <button type="button" class="flex-1 bg-gray-100 hover:bg-gray-200 text-gray-600 font-bold py-3 rounded-xl text-xs transition-colors" onclick="closeConfirm()">Batal</button>
                <button type="submit" class="flex-1 bg-red-500 hover:bg-red-600 text-white font-bold py-3 rounded-xl text-xs transition-colors">Tolak Produk</button>
            </div>
        </form>
    </div>
</div>

<script>
feather.replace({ 'stroke-width': 2 });

/* ── Grouped Store Table Filter & Pagination ── */
const groupCards = Array.from(document.querySelectorAll('.seller-group-card'));
const searchInput = document.getElementById('productSearch');
const categorySelect = document.getElementById('categoryFilter');
const pageInfo = document.getElementById('pageInfo');
const paginationControls = document.getElementById('paginationControls');
const storesPerPage = 2; // Tampilkan 2 tabel toko per halaman agar tidak merembet ke bawah
let currentPage = 1;

const rowsPerStore = 5;

function renderInnerStorePagination(group, matchedRows) {
    const allGroupRows = Array.from(group.querySelectorAll('.product-row'));
    const bar = group.querySelector('.inner-pagination-bar');
    const info = group.querySelector('.inner-page-info');
    const controls = group.querySelector('.inner-page-controls');

    let storePage = parseInt(group.getAttribute('data-page') || '1', 10);
    const total = matchedRows.length;
    const totalPages = Math.max(1, Math.ceil(total / rowsPerStore));
    if (storePage > totalPages) storePage = totalPages;
    group.setAttribute('data-page', storePage);

    allGroupRows.forEach(r => r.style.display = 'none');
    const start = (storePage - 1) * rowsPerStore;
    const end = Math.min(start + rowsPerStore, total);
    matchedRows.slice(start, end).forEach(r => r.style.display = '');

    if (bar) {
        bar.style.display = total > rowsPerStore ? 'flex' : 'none';
    }
    if (info) {
        info.textContent = `Produk ${start + 1}–${end} dari ${total} (Hal ${storePage}/${totalPages})`;
    }
    if (controls) {
        controls.innerHTML = '';
        if (totalPages > 1) {
            const prevBtn = document.createElement('button');
            prevBtn.innerHTML = '&laquo;';
            prevBtn.disabled = storePage === 1;
            prevBtn.className = `px-2.5 h-7 rounded-lg text-[11px] font-bold border border-gray-200 bg-white text-gray-600 disabled:opacity-40 hover:border-primary hover:text-primary`;
            prevBtn.onclick = () => { group.setAttribute('data-page', storePage - 1); renderInnerStorePagination(group, matchedRows); };
            controls.appendChild(prevBtn);

            for (let i = 1; i <= totalPages; i++) {
                const btn = document.createElement('button');
                btn.textContent = i;
                btn.className = `w-7 h-7 rounded-lg text-[11px] font-bold transition-colors ${i === storePage ? 'bg-primary text-white shadow-sm' : 'bg-white border border-gray-200 text-gray-600 hover:border-primary hover:text-primary'}`;
                btn.onclick = () => { group.setAttribute('data-page', i); renderInnerStorePagination(group, matchedRows); };
                controls.appendChild(btn);
            }

            const nextBtn = document.createElement('button');
            nextBtn.innerHTML = '&raquo;';
            nextBtn.disabled = storePage === totalPages;
            nextBtn.className = `px-2.5 h-7 rounded-lg text-[11px] font-bold border border-gray-200 bg-white text-gray-600 disabled:opacity-40 hover:border-primary hover:text-primary`;
            nextBtn.onclick = () => { group.setAttribute('data-page', storePage + 1); renderInnerStorePagination(group, matchedRows); };
            controls.appendChild(nextBtn);
        }
    }
}

function renderGroupedTables() {
    const term = (searchInput?.value || '').toLowerCase().trim();
    const cat = (categorySelect?.value || '').toLowerCase().trim();

    const matchedGroups = [];

    groupCards.forEach(group => {
        const rows = Array.from(group.querySelectorAll('.product-row'));
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
            renderInnerStorePagination(group, matchedRows);
            matchedGroups.push(group);
        }
        group.style.display = 'none';
    });

    const total = matchedGroups.length;
    const totalPages = Math.max(1, Math.ceil(total / storesPerPage));
    if (currentPage > totalPages) currentPage = totalPages;

    const start = (currentPage - 1) * storesPerPage;
    const end = Math.min(start + storesPerPage, total);
    matchedGroups.slice(start, end).forEach(g => g.style.display = '');

    pageInfo.textContent = total === 0
        ? 'Tidak ada produk/toko yang cocok dengan filter'
        : `Halaman ${currentPage} dari ${totalPages} — Menampilkan ${start + 1}–${end} dari ${total} toko`;

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
    document.getElementById('modalImg').src       = p.image || 'https://images.unsplash.com/photo-1555041469-a586c61ea9bc?w=600';
    document.getElementById('modalId').textContent    = 'PRD-' + String(p.id).padStart(3, '0');
    document.getElementById('modalShop').textContent  = p.shop;
    document.getElementById('modalName').textContent  = p.name;
    document.getElementById('modalPrice').textContent = 'Rp ' + new Intl.NumberFormat('id-ID').format(p.price);
    document.getElementById('modalDesc').textContent  = '"' + p.description + '"';
    document.getElementById('modalStyle').textContent = p.style || '-';
    document.getElementById('modalCat').textContent   = p.category;
    document.getElementById('modalStock').textContent = p.stock + ' unit';

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
    feather.replace({ 'stroke-width': 2 });
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
    feather.replace({ 'stroke-width': 2 });
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
