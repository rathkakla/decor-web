<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Decor Seller - Chat Admin Support</title>
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

        /* Custom Scrollbar */
        ::-webkit-scrollbar { width: 5px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: #E3DCD6; border-radius: 10px; }
        ::-webkit-scrollbar-thumb:hover { background: #B5733A; }

        .bubble-admin { border-radius: 2px 18px 18px 18px; }
        .bubble-seller { border-radius: 18px 2px 18px 18px; }
    </style>
</head>
<body class="text-gray-800">

    <aside id="sidebar" class="w-64 bg-white border-r border-gray-200 flex flex-col fixed h-full z-50 sidebar-transition">
    <div class="p-8">
        <h1 class="text-2xl font-bold tracking-widest text-primary uppercase leading-none">DECOR</h1>
        <p class="text-[10px] text-gray-400 mt-1 uppercase tracking-widest">Seller Portal</p>
    </div>

    <nav class="flex-1 px-4 space-y-1 overflow-y-auto">
        <a href="{{ route('seller.dashboard') }}" class="flex items-center px-4 py-3 text-xs font-bold {{ Request::routeIs('seller.dashboard') ? 'active-link' : 'text-gray-400 hover:text-primary hover:bg-gray-50' }} transition-all rounded-lg">
            <i class="fa-solid fa-table-columns mr-3 w-5 text-center"></i> Dashboard
        </a>
        <a href="{{ route('seller.products.index') }}" class="flex items-center px-4 py-3 text-xs font-bold {{ Request::routeIs('seller.products.*') || Request::routeIs('products.*') ? 'active-link' : 'text-gray-400 hover:text-primary hover:bg-gray-50' }} transition-all rounded-lg">
            <i class="fa-solid fa-couch mr-3 w-5 text-center"></i> Kelola Produk
        </a>
        <a href="{{ route('seller.orders') }}" class="flex items-center px-4 py-3 text-xs font-bold {{ Request::routeIs('seller.orders*') || Request::routeIs('orders*') ? 'active-link' : 'text-gray-400 hover:text-primary hover:bg-gray-50' }} transition-all rounded-lg">
            <i class="fa-solid fa-bag-shopping mr-3 w-5 text-center"></i> Daftar Pesanan
        </a>
        <a href="{{ route('seller.chats') }}" class="flex items-center px-4 py-3 text-xs font-bold {{ Request::routeIs('seller.chats*') ? 'active-link' : 'text-gray-400 hover:text-primary hover:bg-gray-50' }} transition-all rounded-lg">
            <i class="fa-solid fa-message mr-3 w-5 text-center"></i> Seller Chat
        </a>
        <a href="{{ route('seller.complaint.index') }}" class="flex items-center px-4 py-3 text-xs font-bold {{ Request::routeIs('seller.complaint.*') ? 'active-link' : 'text-gray-400 hover:text-primary hover:bg-gray-50' }} transition-all rounded-lg">
            <i class="fa-solid fa-circle-exclamation mr-3 w-5 text-center"></i> Komplain
        </a>
        <a href="{{ route('seller.reviews') }}" class="flex items-center px-4 py-3 text-xs font-bold {{ Request::routeIs('seller.reviews*') ? 'active-link' : 'text-gray-400 hover:text-primary hover:bg-gray-50' }} transition-all rounded-lg">
            <i class="fa-solid fa-star mr-3 w-5 text-center"></i> Review & Rating
        </a>
        <a href="{{ route('seller.reports') }}" class="flex items-center px-4 py-3 text-xs font-bold {{ Request::routeIs('seller.reports*') ? 'active-link' : 'text-gray-400 hover:text-primary hover:bg-gray-50' }} transition-all rounded-lg">
            <i class="fa-solid fa-chart-line mr-3 w-5 text-center"></i> Laporan
        </a>
    </nav>

    <!-- Bagian Settings, Support & Logout -->
    <div class="p-4 border-t border-gray-100 space-y-1 bg-white">
        <a href="{{ route('seller.settings') }}" class="flex items-center px-4 py-3 text-xs font-bold {{ Request::routeIs('seller.settings*') ? 'active-link' : 'text-gray-400 hover:text-primary hover:bg-gray-50' }} transition-all rounded-lg">
            <i class="fa-solid fa-gear mr-3 w-5 text-center"></i> Settings
        </a>
        <a href="{{ route('seller.support') }}" class="flex items-center px-4 py-3 text-xs font-bold {{ Request::routeIs('seller.support*') ? 'active-link' : 'text-gray-400 hover:text-primary hover:bg-gray-50' }} transition-all rounded-lg">
            <i class="fa-solid fa-headset mr-3 w-5 text-center"></i> Support
        </a>
        
        <!-- Tombol Logout -->
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

    <main id="main-content" class="flex-1 flex flex-col ml-64 sidebar-transition min-h-screen">
        
        <header class="h-16 bg-primary flex items-center justify-between px-8 sticky top-0 z-40 shadow-sm">
            <div class="flex items-center">
                <button id="toggle-sidebar" class="text-white hover:opacity-80 mr-4 transition-transform active:scale-95">
                    <i class="fa-solid fa-bars-staggered text-xl"></i>
                </button>
                <h2 class="font-bold text-xs uppercase tracking-widest text-white leading-none">Admin Support Chat</h2>
            </div>
            <div class="flex items-center space-x-6 text-white font-bold text-[10px] tracking-widest uppercase">
                <a href="{{ route('seller.support') }}" class="hover:opacity-70 transition-all flex items-center">
                    <i class="fa-solid fa-chevron-left mr-2"></i> Back to Center
                </a>
            </div>
        </header>

        <div class="p-8 flex-1 flex flex-col overflow-hidden">
            <div class="mb-6">
                <h2 class="text-3xl font-bold tracking-tight text-gray-800">Chat with Admin</h2>
                <p class="text-xs text-gray-400 mt-1 font-medium italic">Direct line to DECOR's official support team</p>
            </div>

            <div class="bg-white rounded-[32px] border border-gray-100 shadow-sm flex flex-1 overflow-hidden min-h-[500px] flex-col">
                
                <div class="px-8 py-5 border-b border-gray-50 bg-white flex justify-between items-center">
                    <div class="flex items-center space-x-4">
                        <div class="w-12 h-12 rounded-2xl bg-primary/10 flex items-center justify-center font-black text-primary text-sm tracking-tighter">
                            AD
                        </div>
                        <div>
                            <h4 class="text-sm font-black text-gray-800 uppercase tracking-tight">Official Admin Support</h4>
                        </div>
                    </div>
                </div>

                <div class="flex-1 overflow-y-auto p-10 space-y-8 bg-[#FCFBFB]">
                    <div class="flex items-start space-x-4 max-w-[65%]">
                        <div class="bg-white bubble-admin p-5 text-xs font-semibold leading-relaxed border border-gray-100 shadow-sm text-gray-600">
                            Halo Audri! Ada yang bisa kami bantu terkait akun penjual atau produk kamu hari ini? 😊
                        </div>
                    </div>

                    <div class="flex items-end space-x-4 max-w-[65%] ml-auto text-right flex-row-reverse">
                        <div class="bg-primary text-white bubble-seller p-5 text-xs font-semibold leading-relaxed mr-4 shadow-lg shadow-primary/10">
                            Halo Admin! Saya ingin bertanya terkait proses validasi produk baru saya yang masih pending. Sudah 2 hari belum ada update.
                        </div>
                    </div>
                </div>

                <div class="p-8 bg-white border-t border-gray-100">
                    <div class="flex items-center space-x-6">
                        <button class="text-gray-300 hover:text-primary transition-all"><i class="fa-solid fa-paperclip text-xl"></i></button>
                        <div class="flex-1 relative">
                            <input type="text" placeholder="Tulis pesan untuk Admin Support..." class="w-full bg-gray-50 rounded-2xl py-4 px-6 text-xs font-bold outline-none border-2 border-transparent focus:border-primary/10 focus:bg-white transition-all">
                            <button class="absolute right-2 top-1/2 -translate-y-1/2 bg-primary text-white w-10 h-10 rounded-xl flex items-center justify-center shadow-xl shadow-primary/20 hover:opacity-90 transition-all">
                                <i class="fa-solid fa-paper-plane text-sm"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <footer class="p-8 border-t border-gray-100 text-[10px] font-bold text-gray-400 uppercase tracking-widest text-left">
            © 2026 DECOR MARKETPLACE SELLER PORTAL. ALL RIGHTS RESERVED.
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