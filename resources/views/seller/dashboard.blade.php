<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Decor Seller Portal - Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&display=swap');
        body { background-color: #F8F6F4; font-family: 'Plus Jakarta Sans', sans-serif; overflow-x: hidden; }
        .bg-primary { background-color: #B5733A; }
        .text-primary { color: #B5733A; }
        .active-link { background-color: rgba(181, 115, 58, 0.1); color: #B5733A; border-right: 4px solid #B5733A; }
        .sidebar-transition { transition: transform 0.3s ease-in-out, margin 0.3s ease-in-out; }
        .sidebar-hidden { transform: translateX(-100%); }
        .main-expanded { margin-left: 0 !important; }
    </style>
</head>
<body class="text-gray-800">

    <aside id="sidebar" class="w-64 bg-white border-r border-gray-200 flex flex-col fixed h-full z-50 sidebar-transition">
        <div class="p-8">
            <h1 class="text-2xl font-bold tracking-widest text-primary uppercase">DECOR</h1>
            <p class="text-[10px] text-gray-400 mt-1 uppercase tracking-widest">Seller Portal</p>
        </div>
        <nav class="flex-1 px-4 space-y-1">
            <a href="{{ route('seller.dashboard') }}" class="flex items-center px-4 py-3 text-xs font-bold transition-all rounded-lg {{ Request::routeIs('seller.dashboard') ? 'active-link' : 'text-gray-400 hover:text-primary' }}">
                <i class="fa-solid fa-table-columns mr-3 w-5 text-center"></i> Dashboard
            </a>
            <a href="{{ route('seller.products.index') }}" class="flex items-center px-4 py-3 text-xs font-bold transition-all rounded-lg {{ Request::routeIs('seller.products.*') ? 'active-link' : 'text-gray-400 hover:text-primary' }}">
                <i class="fa-solid fa-couch mr-3 w-5 text-center"></i> Kelola Produk
            </a>
            <a href="{{ route('seller.orders') }}" class="flex items-center px-4 py-3 text-xs font-bold transition-all rounded-lg {{ Request::routeIs('seller.orders*') ? 'active-link' : 'text-gray-400 hover:text-primary' }}">
                <i class="fa-solid fa-bag-shopping mr-3 w-5 text-center"></i> Daftar Pesanan
            </a>
            <a href="{{ route('seller.chats') }}" class="flex items-center px-4 py-3 text-xs font-bold text-gray-400 hover:text-primary transition-all rounded-lg">
                <i class="fa-solid fa-message mr-3 w-5 text-center"></i> Seller Chat
            </a>
            <a href="{{ route('seller.complaint.index') }}" class="flex items-center px-4 py-3 text-xs font-bold text-gray-400 hover:text-primary transition-all rounded-lg">
                <i class="fa-solid fa-circle-exclamation mr-3 w-5 text-center"></i> Komplain
            </a>
        </nav>
        
        <div class="p-4 border-t border-gray-100">
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="flex items-center w-full px-4 py-3 text-xs font-bold text-red-400 hover:bg-red-50 transition-all rounded-lg">
                    <i class="fa-solid fa-arrow-right-from-bracket mr-3 w-5 text-center"></i> Logout
                </button>
            </form>
        </div>
    </aside>

    <main id="main-content" class="flex-1 flex flex-col ml-64 sidebar-transition min-h-screen">
        <header class="h-16 bg-primary flex items-center justify-between px-8 sticky top-0 z-40 shadow-sm">
            <div class="flex items-center">
                <button id="toggle-sidebar" class="text-white hover:opacity-80 mr-4">
                    <i class="fa-solid fa-bars-staggered text-xl"></i>
                </button>
                <h2 class="font-bold text-xs uppercase tracking-widest text-white leading-none">Overview</h2>
            </div>
            <div class="flex items-center space-x-6 text-white">
                <p class="text-[10px] font-bold uppercase tracking-widest">{{ Auth::user()->full_name }}</p>
                <img src="https://ui-avatars.com/api/?name={{ Auth::user()->full_name }}&background=fff&color=B5733A" class="w-9 h-9 rounded-lg border-2 border-white/20">
            </div>
        </header>

        <div class="p-8 space-y-8 flex-1">
            <h2 class="text-2xl font-bold">Welcome back, <span class="text-primary">{{ Auth::user()->full_name }}!</span></h2>
            
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                <div class="bg-white p-6 rounded-xl border border-gray-100 shadow-sm">
                    <div class="p-2 bg-amber-50 rounded-lg text-primary text-sm w-fit mb-4"><i class="fa-solid fa-wallet"></i></div>
                    <p class="text-[10px] text-gray-400 font-bold uppercase tracking-wider">Estimated Revenue</p>
                    <h3 class="text-2xl font-bold mt-1">Rp 0</h3>
                </div>

                <div class="bg-white p-6 rounded-xl border border-gray-100 shadow-sm">
                    <div class="p-2 bg-amber-50 rounded-lg text-primary text-sm w-fit mb-4"><i class="fa-solid fa-cart-shopping"></i></div>
                    <p class="text-[10px] text-gray-400 font-bold uppercase tracking-wider">New Orders</p>
                    <h3 class="text-2xl font-bold mt-1">0</h3>
                </div>

                <div class="bg-white p-6 rounded-xl border border-gray-100 shadow-sm">
                    <div class="p-2 bg-amber-50 rounded-lg text-primary text-sm w-fit mb-4"><i class="fa-solid fa-box-archive"></i></div>
                    <p class="text-[10px] text-gray-400 font-bold uppercase tracking-wider">Active Products</p>
                    <h3 class="text-2xl font-bold mt-1">{{ $totalProducts }}</h3>
                </div>

                <div class="bg-white p-6 rounded-xl border border-gray-100 shadow-sm">
                    <div class="p-2 bg-amber-50 rounded-lg text-primary text-sm w-fit mb-4"><i class="fa-solid fa-star"></i></div>
                    <p class="text-[10px] text-gray-400 font-bold uppercase tracking-wider">Store Rating</p>
                    <h3 class="text-2xl font-bold mt-1">{{ $seller->rating ?? '0.0' }}</h3>
                </div>
            </div>

            <div class="bg-white p-8 rounded-xl shadow-sm border border-gray-100">
                <div class="flex justify-between items-center mb-10">
                    <h3 class="text-lg font-bold">Monthly Revenue</h3>
                    <span class="text-[10px] font-black text-primary bg-amber-50 px-3 py-1 rounded-full uppercase tracking-widest">Live Data</span>
                </div>
                <div class="h-48 flex items-end justify-between border-b border-gray-100 pb-2">
                    @foreach(['JAN','FEB','MAR','APR','MAY','JUN'] as $month)
                    <div class="flex flex-col items-center group">
                        <div class="w-12 bg-primary/10 hover:bg-primary transition-all rounded-t-sm" style="height: {{ rand(20, 150) }}px"></div>
                        <span class="text-[8px] font-bold text-gray-300 mt-2">{{ $month }}</span>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>

        <footer class="p-8 border-t border-gray-100 text-[10px] font-bold text-gray-400 uppercase tracking-widest mt-auto">
            © 2026 DECOR SELLER PORTAL. ALL RIGHTS RESERVED.
        </footer>
    </main>

    <script>
        const btn = document.getElementById('toggle-sidebar');
        const sidebar = document.getElementById('sidebar');
        const main = document.getElementById('main-content');
        btn.addEventListener('click', () => { 
            sidebar.classList.toggle('sidebar-hidden'); 
            main.classList.toggle('main-expanded'); 
        });
    </script>
</body>
</html>