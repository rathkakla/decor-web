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
    <div class="p-8 space-y-8 flex-1">

    <div class="flex items-center gap-2 mb-6 text-xs font-bold text-gray-500">
        <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-1 hover:text-primary transition-colors"><i data-feather="home" class="w-3.5 h-3.5"></i> Dashboard</a>
        <span class="text-gray-300">/</span>
        <a href="{{ route('admin.account.validation') }}" class="flex items-center gap-1 hover:text-primary transition-colors"><i data-feather="shield" class="w-3.5 h-3.5"></i> Account Validation</a>
        <span class="text-gray-300">/</span>
        <span class="text-primary">{{ $seller->store_name }}</span>
    </div>

    @if($seller->status == 'pending')
    <div class="bg-amber-50 border border-amber-200 rounded-xl p-6 mb-6 flex flex-col md:flex-row gap-6 items-center shadow-sm">
        <div class="flex-1">
            <h3 class="text-lg font-bold text-amber-600 mb-1">Account Review Pending</h3>
            <p class="text-xs text-amber-700 leading-relaxed">Mohon tinjau informasi toko dan produk yang diupload oleh seller ini sebelum memberikan persetujuan aktivasi akun.</p>
        </div>
        <div class="flex gap-3 shrink-0">
            <form action="{{ route('admin.account.approve', $seller->id) }}" method="POST">
                @csrf
                <input type="hidden" name="type" value="seller">
                <button type="submit" class="flex items-center gap-2 px-5 py-2.5 rounded-lg text-xs font-bold bg-green-500 hover:bg-green-600 text-white transition-colors shadow-sm">
                    <i data-feather="check" class="w-4 h-4"></i> Approve Account
                </button>
            </form>
            <button type="button" onclick="openRejectModal()" class="flex items-center gap-2 px-5 py-2.5 rounded-lg text-xs font-bold bg-red-500 hover:bg-red-600 text-white transition-colors shadow-sm">
                <i data-feather="x" class="w-4 h-4"></i> Reject Account
            </button>
        </div>
    </div>
    @endif

    <div class="bg-white border border-gray-100 rounded-xl p-8 mb-6 shadow-sm relative overflow-hidden">
        <div class="absolute top-0 left-0 right-0 h-1 {{ $seller->status == 'pending' ? 'bg-amber-500' : 'bg-green-500' }}"></div>
        <div class="flex flex-col md:flex-row md:items-start justify-between gap-6">
            <div class="flex items-center gap-5">
                <div class="w-16 h-16 rounded-2xl bg-orange-50 border border-orange-100 flex items-center justify-center shrink-0 overflow-hidden text-primary">
                    @if($seller->store_image_url)
                        <img src="{{ $seller->store_image_url }}" class="w-full h-full object-cover">
                    @else
                        <i data-feather="shopping-bag" class="w-6 h-6"></i>
                    @endif
                </div>
                <div>
                    <h2 class="text-2xl font-bold text-gray-900 mb-2">{{ $seller->store_name }}</h2>
                    <div class="flex items-center gap-3 flex-wrap">
                        <span class="font-mono text-[10px] text-gray-500 bg-gray-50 border border-gray-200 px-2.5 py-1 rounded-md">ID: {{ str_pad($seller->id, 4, '0', STR_PAD_LEFT) }}</span>
                        <span class="flex items-center gap-1.5 text-[11px] font-semibold text-gray-500"><i data-feather="tag" class="w-3.5 h-3.5"></i> Seller</span>
                        <span class="flex items-center gap-1.5 text-[11px] font-semibold text-gray-400"><i data-feather="calendar" class="w-3.5 h-3.5"></i> Joined {{ $seller->created_at->format('d M Y') }}</span>
                    </div>
                </div>
            </div>
            <div class="flex flex-col items-end gap-2">
                @php
                    $statusCls = match(strtolower($seller->status)) {
                        'pending' => 'bg-amber-50 text-amber-600 border border-amber-200',
                        'approved' => 'bg-green-50 text-green-600 border border-green-200',
                        'rejected' => 'bg-red-50 text-red-600 border border-red-200',
                        default => 'bg-gray-50 text-gray-600 border border-gray-200'
                    };
                @endphp
                <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-[10px] font-black uppercase tracking-widest {{ $statusCls }}">
                    <i data-feather="info" class="w-3 h-3"></i> {{ strtoupper($seller->status) }}
                </span>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2 space-y-6">
            <div class="bg-white border border-gray-100 rounded-xl shadow-sm overflow-hidden">
                <div class="p-5 border-b border-gray-100 flex items-center justify-between">
                    <h3 class="text-sm font-bold text-gray-900 flex items-center gap-2"><i data-feather="package" class="w-4 h-4 text-primary"></i> Produk yang diupload</h3>
                </div>
                <div class="p-6 bg-gray-50/50">
                    @if($seller->products->isEmpty())
                        <div class="text-center py-8 text-gray-400">
                            <i data-feather="package" class="w-10 h-10 mx-auto mb-3 opacity-50"></i>
                            <p class="text-xs font-semibold">Belum ada produk yang diupload.</p>
                        </div>
                    @else
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            @foreach($seller->products as $product)
                            <div class="bg-white border border-gray-200 rounded-xl overflow-hidden hover:shadow-md transition-shadow">
                                <div class="h-40 overflow-hidden bg-gray-100">
                                    @if($product->images->isNotEmpty())
                                        @php
                                            $imgUrl = $product->images->first()->img_url;
                                            if (\Illuminate\Support\Str::startsWith($imgUrl, ['/storage', 'storage', 'http'])) {
                                                $imgUrl = $imgUrl; // Already has path
                                            } else {
                                                $imgUrl = 'storage/' . $imgUrl;
                                            }
                                        @endphp
                                        <img src="{{ asset($imgUrl) }}" class="w-full h-full object-cover">
                                    @else
                                        <img src="https://placehold.co/400" class="w-full h-full object-cover">
                                    @endif
                                </div>
                                <div class="p-4">
                                    <h4 class="text-sm font-bold text-gray-900 mb-1 truncate">{{ $product->name }}</h4>
                                    <div class="text-xs font-bold text-primary mb-2">Rp {{ number_format($product->price, 0, ',', '.') }}</div>
                                    <p class="text-[10px] text-gray-500 leading-relaxed line-clamp-2">{{ $product->description }}</p>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="space-y-6">
            <div class="bg-white border border-gray-100 rounded-xl shadow-sm overflow-hidden">
                <div class="p-5 border-b border-gray-100 flex items-center justify-between">
                    <h3 class="text-sm font-bold text-gray-900 flex items-center gap-2"><i data-feather="info" class="w-4 h-4 text-primary"></i> Informasi Toko</h3>
                </div>
                <div class="p-6 space-y-5">
                    <div>
                        <label class="block text-[9px] font-black text-gray-400 uppercase tracking-widest mb-1.5">Owner Name</label>
                        <div class="text-sm font-bold text-gray-900">{{ $seller->user->full_name }}</div>
                    </div>
                    <div>
                        <label class="block text-[9px] font-black text-gray-400 uppercase tracking-widest mb-1.5">Store Address</label>
                        <div class="text-xs text-gray-600 leading-relaxed bg-gray-50 p-3 rounded-lg border border-gray-100">{{ $seller->store_address ?? 'Tidak ada data' }}</div>
                    </div>
                    <div>
                        <label class="block text-[9px] font-black text-gray-400 uppercase tracking-widest mb-1.5">Description</label>
                        <div class="text-xs text-gray-600 leading-relaxed bg-gray-50 p-3 rounded-lg border border-gray-100">{{ $seller->store_description ?? 'Tidak ada data' }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    </div>
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