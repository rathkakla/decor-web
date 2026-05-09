<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Decor Seller - Pengaturan Toko</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&display=swap');
        body { background-color: #F8F6F4; font-family: 'Plus Jakarta Sans', sans-serif; overflow-x: hidden; }
        .bg-primary { background-color: #B5733A; }
        .text-primary { color: #B5733A; }
        
        /* Active Link: Cokelat Pastel */
        .active-link { background-color: rgba(181, 115, 58, 0.1); color: #B5733A; border-right: 4px solid #B5733A; }
        
        /* Animasi Sidebar */
        .sidebar-transition { transition: transform 0.3s ease-in-out, margin 0.3s ease-in-out; }
        .sidebar-hidden { transform: translateX(-100%); }
        .main-expanded { margin-left: 0 !important; }

        input:focus, textarea:focus { border-color: #B5733A !important; outline: none; background: white !important; }

        /* Modal Blur */
        .modal-active { overflow: hidden; }
    </style>
</head>
<body class="text-gray-800">

   <aside id="sidebar" class="w-64 bg-white border-r border-gray-200 flex flex-col fixed h-full z-50 sidebar-transition">
    <div class="p-8">
        <h1 class="text-2xl font-bold tracking-widest text-primary uppercase leading-none">DECOR</h1>
        <p class="text-[10px] text-gray-400 mt-1 uppercase tracking-widest">Seller Portal</p>
    </div>

    <nav class="flex-1 px-4 space-y-1 overflow-y-auto">
        <a href="{{ route('seller.dashboard') }}" class="flex items-center px-4 py-3 text-xs font-bold transition-all rounded-lg {{ Request::routeIs('seller.dashboard') ? 'active-link' : 'text-gray-400 hover:text-primary hover:bg-gray-50' }}">
            <i class="fa-solid fa-table-columns mr-3 w-5 text-center"></i> Dashboard
        </a>
        <a href="{{ route('seller.products.index') }}" class="flex items-center px-4 py-3 text-xs font-bold transition-all rounded-lg {{ Request::is('*product*') ? 'active-link' : 'text-gray-400 hover:text-primary hover:bg-gray-50' }}">
            <i class="fa-solid fa-couch mr-3 w-5 text-center"></i> Kelola Produk
        </a>
        <a href="{{ route('seller.orders') }}" class="flex items-center px-4 py-3 text-xs font-bold transition-all rounded-lg {{ Request::is('*order*') ? 'active-link' : 'text-gray-400 hover:text-primary hover:bg-gray-50' }}">
            <i class="fa-solid fa-bag-shopping mr-3 w-5 text-center"></i> Daftar Pesanan
        </a>
        <a href="{{ route('seller.chats') }}" class="flex items-center px-4 py-3 text-xs font-bold transition-all rounded-lg {{ Request::is('*chat*') ? 'active-link' : 'text-gray-400 hover:text-primary hover:bg-gray-50' }}">
            <i class="fa-solid fa-message mr-3 w-5 text-center"></i> Seller Chat
        </a>
        <a href="{{ route('seller.complaint.index') }}" class="flex items-center px-4 py-3 text-xs font-bold transition-all rounded-lg {{ Request::is('*complaint*') ? 'active-link' : 'text-gray-400 hover:text-primary hover:bg-gray-50' }}">
            <i class="fa-solid fa-circle-exclamation mr-3 w-5 text-center"></i> Komplain
        </a>
        <a href="{{ route('seller.reviews') }}" class="flex items-center px-4 py-3 text-xs font-bold transition-all rounded-lg {{ Request::is('*review*') ? 'active-link' : 'text-gray-400 hover:text-primary hover:bg-gray-50' }}">
            <i class="fa-solid fa-star mr-3 w-5 text-center"></i> Review & Rating
        </a>
        <a href="{{ route('seller.reports') }}" class="flex items-center px-4 py-3 text-xs font-bold transition-all rounded-lg {{ Request::is('*report*') ? 'active-link' : 'text-gray-400 hover:text-primary hover:bg-gray-50' }}">
            <i class="fa-solid fa-chart-line mr-3 w-5 text-center"></i> Laporan
        </a>
    </nav>

    <!-- Settings, Support & Logout -->
    <div class="p-4 border-t border-gray-100 space-y-1 bg-white">
        <a href="{{ route('seller.settings') }}" class="flex items-center px-4 py-3 text-xs font-bold transition-all rounded-lg {{ Request::is('*setting*') ? 'active-link' : 'text-gray-400 hover:text-primary hover:bg-gray-50' }}">
            <i class="fa-solid fa-gear mr-3 w-5 text-center"></i> Settings
        </a>
        <a href="{{ route('seller.support') }}" class="flex items-center px-4 py-3 text-xs font-bold transition-all rounded-lg {{ Request::is('*support*') ? 'active-link' : 'text-gray-400 hover:text-primary hover:bg-gray-50' }}">
            <i class="fa-solid fa-headset mr-3 w-5 text-center"></i> Support
        </a>
        
        <!-- Logout -->
        <div class="pt-2 mt-2 border-t border-gray-50">
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="flex items-center w-full px-4 py-3 text-xs font-bold text-red-400 hover:text-red-500 hover:bg-red-50 transition-all rounded-lg">
                    <i class="fa-solid fa-arrow-right-from-bracket mr-3 w-5 text-center"></i> Logout
                </button>
            </form>
        </div>
    </div>
</aside>

    <main id="main-content" class="flex-1 flex flex-col ml-64 sidebar-transition min-h-screen relative">
        <form action="{{ route('seller.settings.update') }}" method="POST" enctype="multipart/form-data" id="settingsForm">
            @csrf
            <header class="h-16 bg-primary flex items-center justify-between px-8 sticky top-0 z-40 shadow-sm">
                <div class="flex items-center">
                    <button type="button" id="toggle-sidebar" class="text-white hover:opacity-80 mr-4 flex items-center justify-center transition-transform active:scale-95">
                        <i class="fa-solid fa-bars-staggered text-xl"></i>
                    </button>
                    <h2 class="font-bold text-xs uppercase tracking-widest text-white leading-none">Store Settings</h2>
                </div>
                <div class="flex items-center space-x-6 text-white">
                    <i class="fa-regular fa-bell text-xl cursor-pointer"></i>
                    <div class="w-9 h-9 rounded-lg bg-white/20 flex items-center justify-center overflow-hidden border border-white/30">
                        <img src="{{ $seller->user->avatar_url }}" alt="User" class="w-full h-full object-cover">
                    </div>
                </div>
            </header>

            <div class="p-10 space-y-10 flex-1 pb-32">
                @if(session('success'))
                    <div class="bg-green-50 border border-green-200 text-green-600 px-6 py-4 rounded-2xl text-xs font-bold flex items-center">
                        <i class="fa-solid fa-circle-check mr-3"></i>
                        {{ session('success') }}
                    </div>
                @endif

                <div>
                    <h2 class="text-3xl font-extrabold tracking-tight text-gray-800">Pengaturan Toko</h2>
                    <p class="text-xs text-gray-400 mt-1 font-medium italic">Manage your gallery's digital identity and operational workflow.</p>
                </div>

                <div class="space-y-6">
                    <h3 class="text-[10px] font-black text-gray-400 uppercase tracking-[0.3em] flex items-center">Visual Identity <span class="ml-4 h-[1px] flex-1 bg-gray-200"></span></h3>
                    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                        <div class="lg:col-span-2 space-y-3">
                            <label class="text-[9px] font-black uppercase tracking-widest text-gray-400">Store Banner</label>
                            <input type="file" name="store_banner" id="bannerInput" class="hidden" onchange="previewImage(this, 'bannerPreview')">
                            <div onclick="document.getElementById('bannerInput').click()" class="h-48 bg-gray-100 rounded-2xl border-2 border-dashed border-gray-200 flex flex-col items-center justify-center relative overflow-hidden group cursor-pointer">
                                @if($seller->store_banner)
                                    <img src="{{ asset('storage/' . $seller->store_banner) }}" id="bannerPreview" class="w-full h-full object-cover">
                                @else
                                    <div id="bannerPlaceholder" class="flex flex-col items-center">
                                        <i class="fa-solid fa-image text-3xl text-gray-200 mb-2"></i>
                                        <span class="text-[10px] font-bold text-gray-300 uppercase tracking-widest">Recommended: 1200x400px</span>
                                    </div>
                                    <img id="bannerPreview" class="w-full h-full object-cover hidden">
                                @endif
                                <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-all flex items-center justify-center">
                                    <span class="bg-white px-4 py-2 rounded-lg text-[9px] font-black uppercase tracking-widest">Replace Banner</span>
                                </div>
                            </div>
                        </div>
                        <div class="space-y-3">
                            <label class="text-[9px] font-black uppercase tracking-widest text-gray-400">Store Logo</label>
                            <input type="file" name="store_image" id="logoInput" class="hidden" onchange="previewImage(this, 'logoPreview')">
                            <div onclick="document.getElementById('logoInput').click()" class="aspect-square bg-secondary/30 rounded-2xl border-2 border-dashed border-primary/20 flex flex-col items-center justify-center text-primary/40 group relative overflow-hidden cursor-pointer">
                                <img src="{{ $seller->store_image ? asset('storage/' . $seller->store_image) : $seller->user->avatar_url }}" id="logoPreview" class="w-full h-full object-cover">
                                <div class="absolute inset-0 bg-primary/80 opacity-0 group-hover:opacity-100 transition-all flex items-center justify-center text-white">
                                    <i class="fa-solid fa-camera"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="space-y-6">
                    <h3 class="text-[10px] font-black text-gray-400 uppercase tracking-[0.3em] flex items-center">General Information <span class="ml-4 h-[1px] flex-1 bg-gray-100"></span></h3>
                    <div class="bg-white p-8 rounded-3xl border border-gray-100 space-y-6 shadow-sm">
                        <div class="space-y-2">
                            <label class="text-[9px] font-black uppercase tracking-widest text-gray-400">Nama Toko</label>
                            <input type="text" name="store_name" value="{{ $seller->store_name }}" class="w-full bg-gray-50/50 border border-gray-100 rounded-xl p-3 text-xs font-bold text-gray-800 transition-all">
                        </div>
                        <div class="space-y-2">
                            <label class="text-[9px] font-black uppercase tracking-widest text-gray-400">Deskripsi Toko</label>
                            <textarea name="store_description" rows="4" class="w-full bg-gray-50/50 border border-gray-100 rounded-xl p-4 text-xs font-medium text-gray-600 leading-relaxed transition-all">{{ $seller->store_description }}</textarea>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                    <div class="space-y-6">
                        <h3 class="text-[10px] font-black text-gray-400 uppercase tracking-[0.3em]">Informasi Rekening Bank</h3>
                        <div class="bg-white p-8 rounded-3xl border border-gray-100 shadow-sm space-y-6">
                            <div class="space-y-2">
                                <label class="text-[9px] font-black uppercase tracking-widest text-gray-400">Bank Name</label>
                                <input type="text" name="bank_name" value="{{ $seller->bank_name }}" placeholder="e.g. BCA, Mandiri, BNI" class="w-full bg-gray-50/50 border border-gray-100 rounded-xl p-3 text-xs font-bold text-gray-800 transition-all">
                            </div>
                            <div class="space-y-2">
                                <label class="text-[9px] font-black uppercase tracking-widest text-gray-400">Account Number</label>
                                <input type="text" name="account_number" value="{{ $seller->account_number }}" placeholder="e.g. 8820xxxxxx" class="w-full bg-gray-50/50 border border-gray-100 rounded-xl p-3 text-xs font-bold text-gray-800 transition-all">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <footer class="p-8 border-t border-gray-100 text-[10px] font-bold text-gray-400 uppercase tracking-widest mt-auto text-left">
                © 2026 DECOR MARKETPLACE SELLER PORTAL. ALL RIGHTS RESERVED.
            </footer>

            <div id="fixed-action-bar" class="fixed bottom-0 left-64 right-0 bg-white/80 backdrop-blur-md border-t border-gray-100 px-10 py-5 flex items-center justify-between z-50 sidebar-transition">
                <div class="flex items-center space-x-3 text-[10px] font-bold text-gray-400 uppercase tracking-widest">
                    <i class="fa-solid fa-circle-info text-primary"></i>
                    <span>Changes will go live immediately after saving.</span>
                </div>
                <div class="flex items-center space-x-6">
                    <button type="reset" class="text-[10px] font-black uppercase tracking-widest text-gray-400 hover:text-gray-800 transition-colors">Batal</button>
                    <button type="submit" class="bg-primary text-white px-10 py-3 rounded-2xl text-[10px] font-black uppercase tracking-widest shadow-xl shadow-primary/30 hover:scale-105 transition-all">Simpan Perubahan</button>
                </div>
            </div>
        </form>
    </main>

    <script>
        const btn = document.getElementById('toggle-sidebar');
        const sidebar = document.getElementById('sidebar');
        const main = document.getElementById('main-content');
        const actionBar = document.getElementById('fixed-action-bar');

        btn.addEventListener('click', () => { 
            sidebar.classList.toggle('sidebar-hidden'); 
            main.classList.toggle('main-expanded'); 
            if(actionBar) actionBar.classList.toggle('main-expanded');
        });

        function previewImage(input, previewId) {
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const preview = document.getElementById(previewId);
                    preview.src = e.target.result;
                    preview.classList.remove('hidden');
                    
                    const placeholder = document.getElementById(previewId.replace('Preview', 'Placeholder'));
                    if (placeholder) placeholder.classList.add('hidden');
                }
                reader.readAsDataURL(input.files[0]);
            }
        }
    </script>
</body>
</html>