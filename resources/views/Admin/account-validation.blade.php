@php
$admin_name = Auth::user()->full_name;
$admin_role = "ADMIN";

$customerSupportCount = \App\Models\Support::whereHas('user', fn($q) => $q->where('role', 'customer'))->where('status', 'pending')->count();
$sellerSupportCount = \App\Models\Support::whereHas('user', fn($q) => $q->where('role', 'seller'))->where('status', 'pending')->count();
$designerSupportCount = \App\Models\Support::whereHas('user', fn($q) => $q->where('role', 'designer'))->where('status', 'pending')->count();

$menu_items = [
    ["label" => "Dashboard",              "path" => route('admin.dashboard'),              "icon" => "grid"],
    ["label" => "User Management",        "path" => route('admin.user-management'),        "icon" => "users"],
    ["label" => "Account Validation",     "path" => route('admin.account.validation'),     "icon" => "shield"],
    ["label" => "Seller Monitor",         "path" => route('admin.seller-monitor'),         "icon" => "shopping-bag"],
    ["label" => "Designer Monitor",       "path" => route('admin.designer-monitor'),       "icon" => "pen-tool"],
    ["label" => "Seller Support",         "path" => route('admin.seller-support'),         "icon" => "headphones", "badge" => $sellerSupportCount],
    ["label" => "Designer Support",       "path" => route('admin.designer-support'),       "icon" => "pen-tool", "badge" => $designerSupportCount],
    ["label" => "Customer Support",       "path" => route('admin.customer-support'),       "icon" => "message-circle", "badge" => $customerSupportCount],
    ["label" => "Product Validation",     "path" => route('admin.product.validation'),     "icon" => "check-circle"],
    ["label" => "Portofolio Validation",  "path" => route('admin.portfolio-validation'),  "icon" => "image"],
];

$stats_view = [
    ["label" => "Pending Seller",    "value" => number_format($stats['pending_sellers']), "icon" => "shopping-cart", "note" => "menunggu review",  "color" => "warning"],
    ["label" => "Pending Designer",  "value" => number_format($stats['pending_designers']), "icon" => "pen-tool", "note" => "menunggu review", "color" => "info"],
    ["label" => "Total Verified",    "value" => "42", "icon" => "check-circle", "note" => "bulan ini",     "color" => "success"],
    ["label" => "Avg. Response",     "value" => "2j", "icon" => "zap",          "note" => "waktu validasi",   "color" => "primary"],
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
    <div class="p-8 space-y-8 flex-1">

    <div class="mb-8">
        <div class="text-[10px] font-black text-primary uppercase tracking-widest mb-1">Onboarding Control</div>
        <div class="text-2xl font-bold text-gray-900">Account Validation</div>
        <div class="text-xs text-gray-500 mt-1">Review pendaftaran {{ $type }} baru sebelum mereka dapat berjualan atau menawarkan jasa.</div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
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

    <div class="flex items-center gap-4 mb-6 justify-between bg-white p-4 rounded-xl shadow-sm border border-gray-100">
        <div class="flex gap-1 bg-gray-50 border border-gray-200 rounded-xl p-1">
            <a href="{{ route('admin.account.validation', ['type' => 'seller', 'status' => $status]) }}" class="px-4 py-2 rounded-lg text-xs font-bold transition-colors {{ $type == 'seller' ? 'bg-white text-primary shadow-sm' : 'text-gray-500 hover:bg-gray-100' }}">Sellers</a>
            <a href="{{ route('admin.account.validation', ['type' => 'designer', 'status' => $status]) }}" class="px-4 py-2 rounded-lg text-xs font-bold transition-colors {{ $type == 'designer' ? 'bg-white text-primary shadow-sm' : 'text-gray-500 hover:bg-gray-100' }}">Designers</a>
        </div>
        <div class="flex gap-1 bg-gray-50 border border-gray-200 rounded-xl p-1">
            <a href="{{ route('admin.account.validation', ['type' => $type, 'status' => 'pending']) }}" class="px-4 py-2 rounded-lg text-xs font-bold transition-colors {{ $status == 'pending' ? 'bg-white text-primary shadow-sm' : 'text-gray-500 hover:bg-gray-100' }}">Pending</a>
            <a href="{{ route('admin.account.validation', ['type' => $type, 'status' => 'approved']) }}" class="px-4 py-2 rounded-lg text-xs font-bold transition-colors {{ $status == 'approved' ? 'bg-white text-primary shadow-sm' : 'text-gray-500 hover:bg-gray-100' }}">Approved</a>
            <a href="{{ route('admin.account.validation', ['type' => $type, 'status' => 'rejected']) }}" class="px-4 py-2 rounded-lg text-xs font-bold transition-colors {{ $status == 'rejected' ? 'bg-white text-primary shadow-sm' : 'text-gray-500 hover:bg-gray-100' }}">Rejected</a>
        </div>
    </div>

    <div class="space-y-4">
        @if($accounts->isEmpty())
            <div class="text-center py-12 bg-white rounded-xl border border-gray-100 text-gray-400">
                <i data-feather="inbox" class="w-12 h-12 mx-auto mb-3 opacity-50"></i>
                <p class="text-sm font-semibold">Tidak ada pengajuan akun untuk saat ini.</p>
            </div>
        @endif

        @foreach ($accounts as $acc)
        <div class="bg-white rounded-xl border border-gray-100 p-4 flex items-center gap-6 shadow-sm hover:border-primary/30 transition-colors group">
            <img src="{{ $type == 'seller' ? ($acc->store_image_url) : ($acc->designer_image ? asset('storage/'.$acc->designer_image) : 'https://ui-avatars.com/api/?name='.urlencode($acc->user->full_name)) }}" class="w-16 h-16 rounded-xl object-cover border border-gray-100">
            
            <div class="w-1/4">
                <div class="text-sm font-bold text-gray-900">{{ $type == 'seller' ? $acc->store_name : $acc->user->full_name }}</div>
                <div class="text-[10px] text-gray-500 font-mono mt-0.5">{{ $acc->user->email }}</div>
            </div>

            <div class="w-1/4">
                <div class="text-[9px] font-black text-gray-400 uppercase tracking-widest">{{ $type == 'seller' ? 'Address' : 'Specialty' }}</div>
                <div class="text-xs font-semibold text-gray-900 mt-1 truncate">{{ $type == 'seller' ? ($acc->store_address ?? '-') : ($acc->specialty ?? '-') }}</div>
            </div>

            <div class="w-1/6">
                <div class="text-[9px] font-black text-gray-400 uppercase tracking-widest">Joined</div>
                <div class="text-xs font-semibold text-gray-900 mt-1">{{ $acc->created_at->format('d M Y') }}</div>
            </div>

            <div class="w-1/6">
                @php
                    $statusCls = match($acc->status) {
                        'pending' => 'bg-amber-50 text-amber-600',
                        'approved' => 'bg-green-50 text-green-600',
                        'rejected' => 'bg-red-50 text-red-600',
                        default => 'bg-gray-50 text-gray-600'
                    };
                @endphp
                <span class="inline-flex items-center px-2.5 py-1 rounded-md text-[9px] font-black uppercase tracking-widest {{ $statusCls }}">
                    {{ strtoupper($acc->status) }}
                </span>
            </div>

            <div class="flex-1 flex justify-end gap-2">
                @if($acc->status == 'pending')
                <a href="{{ $type == 'seller' ? route('admin.seller-detail', $acc->id) : route('admin.designer-detail', $acc->id) }}" class="flex items-center px-3 py-1.5 rounded-lg text-[10px] font-bold bg-orange-50 text-primary hover:bg-orange-100 transition-colors">
                    <i data-feather="eye" class="w-3.5 h-3.5 mr-1.5"></i> Detail
                </a>
                <form action="{{ route('admin.account.approve', $acc->id) }}" method="POST">
                    @csrf
                    <input type="hidden" name="type" value="{{ $type }}">
                    <button type="submit" class="flex items-center px-3 py-1.5 rounded-lg text-[10px] font-bold bg-green-50 text-green-600 hover:bg-green-100 transition-colors"><i data-feather="check" class="w-3.5 h-3.5 mr-1.5"></i> Approve</button>
                </form>
                <button class="flex items-center px-3 py-1.5 rounded-lg text-[10px] font-bold bg-red-50 text-red-600 hover:bg-red-100 transition-colors" onclick="openRejectModal('{{ $acc->id }}', '{{ $type == 'seller' ? $acc->store_name : $acc->user->full_name }}')">
                    <i data-feather="x" class="w-3.5 h-3.5 mr-1.5"></i> Reject
                </button>
                @elseif($acc->status == 'rejected')
                    <div class="text-right">
                        <div class="text-[9px] font-black text-gray-400 uppercase tracking-widest">Reason</div>
                        <div class="text-[10px] font-semibold text-red-500 mt-1 truncate max-w-[150px]">{{ $acc->rejection_reason }}</div>
                    </div>
                @else
                <a href="{{ $type == 'seller' ? route('admin.seller-detail', $acc->id) : route('admin.designer-detail', $acc->id) }}" class="flex items-center px-4 py-2 rounded-lg text-[10px] font-bold bg-orange-50 text-primary hover:bg-orange-100 transition-colors">
                    <i data-feather="eye" class="w-3.5 h-3.5 mr-1.5"></i> View Profile
                </a>
                @endif
            </div>
        </div>
        @endforeach
    </div>
    </div>
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
                <textarea name="reason" class="w-full mt-6 h-24 p-3 bg-gray-50 border border-gray-200 rounded-xl text-xs focus:outline-none focus:border-red-300 focus:ring-1 focus:ring-red-300 transition-colors" placeholder="Contoh: Portfolio tidak valid atau Nama toko mengandung kata terlarang..."></textarea>
            </div>
            <div class="flex gap-3 mt-4">
                <button type="button" class="flex-1 bg-gray-100 hover:bg-gray-200 text-gray-600 font-bold py-3 rounded-xl text-xs transition-colors" onclick="closeRejectModal()">Batal</button>
                <button type="submit" class="flex-1 bg-red-500 hover:bg-red-600 text-white font-bold py-3 rounded-xl text-xs transition-colors">Reject Account</button>
            </div>
        </form>
    </div>
</div>

<script>
    feather.replace();
    function openRejectModal(id, name) {
        document.getElementById('rejectForm').action = `/admin/account-validation/${id}/reject`;
        document.getElementById('targetName').textContent = name;
        document.getElementById('rejectModal').classList.add('show');
    }
    function closeRejectModal() {
        document.getElementById('rejectModal').classList.remove('show');
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