@php
$admin_name = Auth::user()->full_name;
$admin_role = "ADMIN";
$current_path = 'user-management';

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
    ["label" => "Total Users",      "value" => number_format($stats['total']), "trend" => "+5.2%", "icon" => "users",      "color" => "primary"],
    ["label" => "Active Designers", "value" => number_format($stats['designers']),    "trend" => "+12%",  "icon" => "pen-tool",   "color" => "info"],
    ["label" => "Platform Sellers", "value" => number_format($stats['sellers']),  "trend" => "+2.4%", "icon" => "shopping-bag","color" => "warning"],
    ["label" => "Customers",        "value" => number_format($stats['customers']),  "trend" => "+3.1%", "icon" => "user-check", "color" => "success"],
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
    <div class="p-8 space-y-8 flex-1">


    <!-- Page Header -->
    <div class="mb-8">
        <div class="text-[10px] font-black text-primary uppercase tracking-widest mb-1">Database Management</div>
        <div class="text-2xl font-bold text-gray-900">User Management</div>
        <div class="text-xs text-gray-500 mt-1">Kelola akun pengguna, role, dan status di seluruh platform.</div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        @foreach ($stats_view as $s)
        <div class="bg-white p-6 rounded-xl border border-gray-100 shadow-sm transition-transform hover:-translate-y-1 relative overflow-hidden">
            <div class="absolute bottom-0 left-0 right-0 h-1 bg-primary"></div>
            <div class="w-10 h-10 rounded-lg bg-orange-50 text-primary flex items-center justify-center mb-4">
                <i data-feather="{{ $s['icon'] }}" class="w-5 h-5"></i>
            </div>
            <h3 class="text-2xl font-bold text-gray-900">{{ $s['value'] }}</h3>
            <p class="text-[10px] text-gray-400 font-bold uppercase tracking-wider mt-1">{{ $s['label'] }}</p>
            <p class="text-[10px] font-black text-green-600 mt-1">{{ $s['trend'] }} bulan ini</p>
        </div>
        @endforeach
    </div>

    <div class="flex items-center gap-4 mb-6">
        <form action="{{ route('admin.user-management') }}" method="GET" class="flex-1 max-w-md relative">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <i data-feather="search" class="w-4 h-4 text-gray-400"></i>
            </div>
            <input name="search" type="text" class="w-full pl-10 pr-4 py-2 border border-gray-200 rounded-xl text-xs font-semibold focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary bg-white text-gray-800" placeholder="Cari nama, email, atau role…" value="{{ request('search') }}">
            <input type="hidden" name="role" value="{{ request('role', 'semua') }}">
        </form>
        <div class="flex gap-1 bg-white border border-gray-200 rounded-xl p-1">
            <a href="{{ route('admin.user-management', ['role' => 'semua']) }}" class="px-4 py-1.5 rounded-lg text-[10px] font-bold transition-colors {{ !request('role') || request('role') == 'semua' ? 'bg-orange-50 text-primary' : 'text-gray-500 hover:bg-gray-50' }}">Semua</a>
            <a href="{{ route('admin.user-management', ['role' => 'designer']) }}" class="px-4 py-1.5 rounded-lg text-[10px] font-bold transition-colors {{ request('role') == 'designer' ? 'bg-orange-50 text-primary' : 'text-gray-500 hover:bg-gray-50' }}">Designer</a>
            <a href="{{ route('admin.user-management', ['role' => 'seller']) }}" class="px-4 py-1.5 rounded-lg text-[10px] font-bold transition-colors {{ request('role') == 'seller' ? 'bg-orange-50 text-primary' : 'text-gray-500 hover:bg-gray-50' }}">Seller</a>
            <a href="{{ route('admin.user-management', ['role' => 'customer']) }}" class="px-4 py-1.5 rounded-lg text-[10px] font-bold transition-colors {{ request('role') == 'customer' ? 'bg-orange-50 text-primary' : 'text-gray-500 hover:bg-gray-50' }}">Customer</a>
        </div>
    </div>

    <!-- User Table -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50/50 border-b border-gray-100">
                        <th class="py-4 px-6 text-[9px] font-black text-gray-400 uppercase tracking-widest">User</th>
                        <th class="py-4 px-6 text-[9px] font-black text-gray-400 uppercase tracking-widest">Role</th>
                        <th class="py-4 px-6 text-[9px] font-black text-gray-400 uppercase tracking-widest">Bergabung</th>
                        <th class="py-4 px-6 text-[9px] font-black text-gray-400 uppercase tracking-widest">Status</th>
                        <th class="py-4 px-6 text-[9px] font-black text-gray-400 uppercase tracking-widest text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($users as $u)
                    @php
                        $role_cls = match(strtolower($u->role)) {
                            'designer' => 'bg-blue-50 text-blue-600',
                            'seller' => 'bg-amber-50 text-amber-600',
                            'customer' => 'bg-gray-50 text-gray-600 border border-gray-200',
                            default => 'bg-gray-50 text-gray-600'
                        };
                    @endphp
                    <tr class="border-b border-gray-50 hover:bg-gray-50/50 transition-colors">
                        <td class="py-4 px-6">
                            <div class="flex items-center gap-4">
                                <img src="{{ $u->avatar_url ?? 'https://ui-avatars.com/api/?name='.urlencode($u->full_name).'&background=B5733A&color=fff' }}" class="w-10 h-10 rounded-xl object-cover border border-gray-100 flex-shrink-0">
                                <div>
                                    <span class="block text-sm font-bold text-gray-900">{{ $u->full_name }}</span>
                                    <span class="block text-[10px] text-gray-500 font-mono mt-0.5">{{ $u->email }}</span>
                                </div>
                            </div>
                        </td>
                        <td class="py-4 px-6">
                            <span class="inline-flex items-center px-2.5 py-1 rounded-md text-[9px] font-black uppercase tracking-widest {{ $role_cls }}">
                                {{ strtoupper($u->role) }}
                            </span>
                        </td>
                        <td class="py-4 px-6">
                            <span class="text-xs font-semibold text-gray-600">{{ $u->created_at->format('M d, Y') }}</span>
                        </td>
                        <td class="py-4 px-6">
                            <span class="inline-flex items-center gap-1.5 text-[10px] font-black text-green-600 uppercase tracking-widest">
                                <span class="w-1.5 h-1.5 rounded-full bg-green-600"></span> Active
                            </span>
                        </td>
                        <td class="py-4 px-6">
                            <div class="flex items-center justify-end gap-2">
                                <form action="{{ route('admin.user.warn', $u->id) }}" method="POST" onsubmit="return confirm('Kirim peringatan pelanggaran ke user ini?')">
                                    @csrf
                                    <button type="submit" class="w-8 h-8 flex items-center justify-center rounded-lg border border-amber-200 bg-amber-50 text-amber-600 hover:bg-amber-100 hover:border-amber-300 transition-colors" title="Kirim Peringatan">
                                        <i data-feather="alert-triangle" class="w-3.5 h-3.5"></i>
                                    </button>
                                </form>
                                <form action="{{ route('admin.user.delete', $u->id) }}" method="POST" onsubmit="return confirm('BANNED: Anda yakin ingin menghapus user ini secara permanen?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="w-8 h-8 flex items-center justify-center rounded-lg border border-red-200 bg-red-50 text-red-600 hover:bg-red-100 hover:border-red-300 transition-colors" title="Suspend & Delete">
                                        <i data-feather="slash" class="w-3.5 h-3.5"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    </div>
</main>

<script>
    feather.replace({ 'stroke-width': 2 });

    document.querySelectorAll('.ftab').forEach(btn => {
        btn.addEventListener('click', function() {
            document.querySelectorAll('.ftab').forEach(b => b.classList.remove('active'));
            this.classList.add('active');
        });
    });
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