@php
$current_page = basename($_SERVER['PHP_SELF']);
$current_path = str_replace('.php', '', $current_page);
if ($current_path == "" || $current_path == "index") {
    $current_path = "settings";
}

$admin_name = auth()->user()->full_name;
$admin_role = "SUPER ADMIN";

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
    <title>Settings — DECOR Admin</title>
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
        
        .toast { position: fixed; bottom: 32px; right: 32px; z-index: 9999; display: flex; align-items: center; gap: 12px; background: #1C1410; color: #fff; padding: 14px 20px; border-radius: 12px; font-size: 13px; font-weight: 600; box-shadow: 0 8px 30px rgba(0,0,0,0.2); transform: translateY(80px); opacity: 0; transition: all 0.35s cubic-bezier(0.16,1,0.3,1); pointer-events: none; min-width: 240px; }
        .toast.show { transform: translateY(0); opacity: 1; }
    </style>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body class="text-gray-800 bg-[#F8F6F4]">

@include("Admin.partials.sidebar")

<main id="main-content" class="flex-1 flex flex-col ml-64 sidebar-transition min-h-screen bg-[#F8F6F4]">
    @include("Admin.partials.header", ["title" => "Settings"])
    <div class="p-8 space-y-8 flex-1">

    <div class="max-w-2xl mx-auto mb-8">
        <div class="text-[10px] font-black text-primary uppercase tracking-widest mb-1">System</div>
        <div class="text-2xl font-bold text-gray-900">Settings</div>
        <div class="text-xs text-gray-500 mt-1">Kelola profil dan keamanan akun admin.</div>
    </div>

    <div class="max-w-2xl mx-auto space-y-6">

        @if(session('success'))
            <div class="bg-green-50 text-green-700 border border-green-200 p-4 rounded-xl text-sm font-bold flex items-center gap-2">
                <i data-feather="check-circle" class="w-4 h-4"></i> {{ session('success') }}
            </div>
        @endif
        @if($errors->any())
            <div class="bg-red-50 text-red-700 border border-red-200 p-4 rounded-xl text-sm font-bold flex items-center gap-2">
                <i data-feather="alert-circle" class="w-4 h-4"></i> Terdapat kesalahan pada input Anda.
            </div>
        @endif

        <form action="{{ route('admin.settings.update') }}" method="POST">
            @csrf

            <!-- Personal Profile Card -->
            <div class="bg-white border border-gray-100 rounded-xl shadow-sm overflow-hidden mb-6">
                <div class="p-5 border-b border-gray-100 flex items-center gap-3">
                    <div class="w-10 h-10 rounded-lg bg-orange-50 text-primary flex items-center justify-center shrink-0">
                        <i data-feather="user" class="w-4 h-4"></i>
                    </div>
                    <div>
                        <h3 class="text-sm font-bold text-gray-900">Personal Profile</h3>
                        <p class="text-xs text-gray-500 mt-0.5">Nama tampilan dan email sistem</p>
                    </div>
                </div>
                
                <div class="p-6">
                    <div class="flex items-center gap-5 pb-6 border-b border-gray-100 mb-6">
                        <img src="https://ui-avatars.com/api/?name={{ urlencode($admin_name) }}&background=B5733A&color=fff&size=160" class="w-16 h-16 rounded-2xl border border-gray-200 object-cover shrink-0">
                        <div class="flex-1">
                            <div class="text-base font-bold text-gray-900">{{ $admin_name }}</div>
                            <div class="font-mono text-[11px] text-gray-500 mt-1 mb-2">ADM-{{ str_pad(auth()->id(), 5, '0', STR_PAD_LEFT) }}</div>
                            <span class="inline-flex px-2.5 py-1 rounded-md text-[9px] font-black uppercase tracking-widest bg-orange-50 text-primary border border-orange-100">{{ $admin_role }}</span>
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="space-y-1.5 md:col-span-2">
                            <label class="text-[10px] font-black uppercase tracking-widest text-gray-400">Nama Lengkap</label>
                            <input type="text" name="full_name" class="w-full px-4 py-2.5 bg-white border border-gray-200 rounded-lg text-sm text-gray-900 focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary transition-colors @error('full_name') border-red-500 @enderror" value="{{ old('full_name', auth()->user()->full_name) }}">
                            @error('full_name') <p class="text-xs text-red-500">{{ $message }}</p> @enderror
                        </div>
                        <div class="space-y-1.5 md:col-span-2">
                            <label class="text-[10px] font-black uppercase tracking-widest text-gray-400">Email Address</label>
                            <input type="email" name="email" class="w-full px-4 py-2.5 bg-white border border-gray-200 rounded-lg text-sm text-gray-900 focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary transition-colors @error('email') border-red-500 @enderror" value="{{ old('email', auth()->user()->email) }}">
                            <p class="text-[10px] text-gray-500">Email ini digunakan untuk login dan notifikasi sistem.</p>
                            @error('email') <p class="text-xs text-red-500">{{ $message }}</p> @enderror
                        </div>
                    </div>
                </div>
            </div>

            <!-- Password & Security Card -->
            <div class="bg-white border border-gray-100 rounded-xl shadow-sm overflow-hidden">
                <div class="p-5 border-b border-gray-100 flex items-center gap-3">
                    <div class="w-10 h-10 rounded-lg bg-orange-50 text-primary flex items-center justify-center shrink-0">
                        <i data-feather="lock" class="w-4 h-4"></i>
                    </div>
                    <div>
                        <h3 class="text-sm font-bold text-gray-900">Password & Security</h3>
                        <p class="text-xs text-gray-500 mt-0.5">Ubah kata sandi akun admin</p>
                    </div>
                </div>
                
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="space-y-1.5 md:col-span-2">
                            <label class="text-[10px] font-black uppercase tracking-widest text-gray-400">Password Saat Ini</label>
                            <input type="password" name="current_password" class="w-full px-4 py-2.5 bg-white border border-gray-200 rounded-lg text-sm text-gray-900 focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary transition-colors @error('current_password') border-red-500 @enderror" placeholder="Masukkan password saat ini (Wajib jika ingin mengganti password)">
                            @error('current_password') <p class="text-xs text-red-500">{{ $message }}</p> @enderror
                        </div>
                        <div class="space-y-1.5">
                            <label class="text-[10px] font-black uppercase tracking-widest text-gray-400">Password Baru</label>
                            <input type="password" name="new_password" class="w-full px-4 py-2.5 bg-white border border-gray-200 rounded-lg text-sm text-gray-900 focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary transition-colors @error('new_password') border-red-500 @enderror" placeholder="Kosongkan jika tidak ingin mengubah password" oninput="checkStrength(this.value)">
                            @error('new_password') <p class="text-xs text-red-500">{{ $message }}</p> @enderror
                            <div class="mt-1.5">
                                <div class="h-1 bg-gray-100 rounded-full overflow-hidden">
                                    <div id="strengthBar" class="h-full w-0 transition-all duration-300"></div>
                                </div>
                                <div id="strengthLabel" class="text-[10px] font-bold mt-1"></div>
                            </div>
                        </div>
                        <div class="space-y-1.5">
                            <label class="text-[10px] font-black uppercase tracking-widest text-gray-400">Konfirmasi Password Baru</label>
                            <input type="password" name="new_password_confirmation" class="w-full px-4 py-2.5 bg-white border border-gray-200 rounded-lg text-sm text-gray-900 focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary transition-colors" placeholder="Ulangi password baru">
                        </div>
                    </div>
                </div>
                
                <div class="p-4 bg-gray-50 border-t border-gray-100 flex items-center justify-between">
                    <div class="text-[11px] text-gray-500 flex items-center gap-1.5">
                        <i data-feather="shield" class="w-3.5 h-3.5"></i> Perubahan diterapkan setelah disimpan.
                    </div>
                    <button type="submit" class="flex items-center gap-2 px-5 py-2.5 bg-primary hover:bg-[#8A5229] text-white rounded-lg text-xs font-bold transition-colors shadow-sm">
                        <i data-feather="save" class="w-3.5 h-3.5"></i> Simpan Perubahan
                    </button>
                </div>
            </div>
        </form>

    </div>
    </div>
</main>

<script>
feather.replace({ 'stroke-width': 2 });

function checkStrength(val) {
    const bar = document.getElementById('strengthBar');
    const lbl = document.getElementById('strengthLabel');
    if (!bar || !lbl) return;

    let score = 0;
    if (val.length >= 8)           score++;
    if (/[A-Z]/.test(val))         score++;
    if (/[0-9]/.test(val))         score++;
    if (/[^A-Za-z0-9]/.test(val))  score++;

    const map = [
        { pct: '0%',   bg: 'bg-transparent',  label: '',            color: 'text-gray-400' },
        { pct: '25%',  bg: 'bg-red-500',  label: 'Lemah',       color: 'text-red-500' },
        { pct: '50%',  bg: 'bg-amber-500', label: 'Sedang',      color: 'text-amber-500' },
        { pct: '75%',  bg: 'bg-[#B5733A]', label: 'Kuat',        color: 'text-[#B5733A]' },
        { pct: '100%', bg: 'bg-green-500', label: 'Sangat Kuat', color: 'text-green-500' },
    ];
    
    bar.style.width = map[score].pct;
    bar.className = `h-full transition-all duration-300 ${map[score].bg}`;
    lbl.textContent = map[score].label;
    lbl.className = `text-[10px] font-bold mt-1 ${map[score].color}`;
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