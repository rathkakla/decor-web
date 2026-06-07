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
    <title>Notifikasi — DECOR Admin</title>
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
    @include("Admin.partials.header", ["title" => "Notifikasi"])
    <div class="p-8 space-y-8 flex-1">

    <div class="mb-8">
        <h2 class="text-3xl font-bold uppercase tracking-tight text-gray-900">System Logs & Notifications</h2>
        <p class="text-gray-500 text-sm mt-1">Admin system alerts.</p>
    </div>

    <div class="space-y-4 max-w-4xl">
        @forelse($notifications as $notification)
        <div class="bg-white p-6 rounded-xl border {{ $notification->read_at ? 'border-gray-100 opacity-60' : 'border-primary/20 shadow-lg shadow-primary/5' }} transition-all">
            <div class="flex items-start gap-4">
                <div class="w-10 h-10 rounded-lg {{ ($notification->data['type'] ?? '') == 'warning' ? 'bg-red-50 text-red-500' : 'bg-orange-50 text-primary' }} flex items-center justify-center flex-shrink-0">
                    <i class="fa-solid fa-{{ $notification->data['icon'] ?? 'bell' }}"></i>
                </div>
                <div class="flex-1">
                    <div class="flex justify-between items-start">
                        <h3 class="font-bold text-gray-900">{{ $notification->data['title'] }}</h3>
                        <span class="text-[10px] text-gray-400 font-bold uppercase tracking-widest">{{ $notification->created_at->diffForHumans() }}</span>
                    </div>
                    <p class="text-sm text-gray-600 mt-1 leading-relaxed">{{ $notification->data['message'] }}</p>
                </div>
            </div>
        </div>
        @empty
        <div class="text-center py-20 bg-white rounded-xl border border-dashed border-gray-200">
            <p class="text-gray-400 font-bold uppercase tracking-widest text-xs">No notifications</p>
        </div>
        @endforelse
    </div>

    </div>
</main>

<script>
    feather.replace({ 'stroke-width': 2 });
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