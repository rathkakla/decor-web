<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Decor Seller - Pusat Pesan</title>
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

        .bubble-customer { border-radius: 2px 18px 18px 18px; }
        .bubble-seller { border-radius: 18px 2px 18px 18px; }
    </style>
</head>
<body class="text-gray-800">

    <aside id="sidebar" class="w-64 bg-white border-r border-gray-200 flex flex-col fixed h-full z-50 sidebar-transition">
        <div class="p-8">
            <h1 class="text-2xl font-bold tracking-widest text-primary uppercase leading-none">DECOR</h1>
            <p class="text-[10px] text-gray-400 mt-1 uppercase tracking-widest">Seller Portal</p>
        </div>

        <nav class="flex-1 px-4 space-y-1">
            <a href="{{ route('seller.dashboard') }}" class="flex items-center px-4 py-3 text-xs font-bold text-gray-400 hover:text-primary transition-all rounded-lg">
                <i class="fa-solid fa-table-columns mr-3 w-5 text-center"></i> Dashboard
            </a>
            <a href="{{ route('seller.products.index') }}" class="flex items-center px-4 py-3 text-xs font-bold text-gray-400 hover:text-primary transition-all rounded-lg">
                <i class="fa-solid fa-couch mr-3 w-5 text-center"></i> Kelola Produk
            </a>
            <a href="{{ route('seller.orders') }}" class="flex items-center px-4 py-3 text-xs font-bold text-gray-400 hover:text-primary transition-all rounded-lg">
                <i class="fa-solid fa-bag-shopping mr-3 w-5 text-center"></i> Daftar Pesanan
            </a>
            <a href="{{ route('seller.chats') }}" class="flex items-center px-4 py-3 text-xs font-bold active-link transition-all rounded-lg">
                <i class="fa-solid fa-message mr-3 w-5 text-center"></i> Seller Chat
            </a>
            <a href="{{ route('seller.complaint.index') }}" class="flex items-center px-4 py-3 text-xs font-bold text-gray-400 hover:text-primary transition-all rounded-lg">
                <i class="fa-solid fa-circle-exclamation mr-3 w-5 text-center"></i> Komplain
            </a>
            <a href="{{ route('seller.reviews') }}" class="flex items-center px-4 py-3 text-xs font-bold text-gray-400 hover:text-primary transition-all rounded-lg">
                <i class="fa-solid fa-star mr-3 w-5 text-center"></i> Review & Rating
            </a>
            <a href="{{ route('seller.reports') }}" class="flex items-center px-4 py-3 text-xs font-bold transition-all rounded-lg {{ Request::routeIs('seller.reports') ? 'active-link' : 'text-gray-400 hover:text-primary' }}">
                <i class="fa-solid fa-chart-line mr-3 w-5 text-center"></i> Laporan
            </a>
        </nav>

        <div class="p-4 border-t border-gray-100 space-y-1">
            <a href="{{ route('seller.settings') }}" class="flex items-center px-4 py-3 text-xs font-bold text-gray-400 hover:text-primary transition-all rounded-lg">
                <i class="fa-solid fa-gear mr-3 w-5 text-center"></i> Settings
            </a>
            <a href="{{ route('seller.support') }}" class="flex items-center px-4 py-3 text-xs font-bold transition-all rounded-lg {{ Request::routeIs('seller.support') ? 'active-link' : 'text-gray-400 hover:text-primary' }}">
                <i class="fa-solid fa-gear mr-3 w-5 text-center"></i> Support
            </a>
        </div>
    </aside>

    <main id="main-content" class="flex-1 flex flex-col ml-64 sidebar-transition min-h-screen">
        
        <header class="h-16 bg-primary flex items-center justify-between px-8 sticky top-0 z-40 shadow-sm">
            <div class="flex items-center">
                <button id="toggle-sidebar" class="text-white hover:opacity-80 mr-4 flex items-center justify-center transition-transform active:scale-95">
                    <i class="fa-solid fa-bars-staggered text-xl"></i>
                </button>
                <h2 class="font-bold text-xs uppercase tracking-widest text-white leading-none">Message Center</h2>
            </div>
            <div class="flex items-center space-x-6 text-white">
                <i class="fa-regular fa-bell text-xl cursor-pointer"></i>
                <div class="w-9 h-9 rounded-lg bg-white/20 flex items-center justify-center overflow-hidden border border-white/30">
                     <img src="https://ui-avatars.com/api/?name=Adrian&background=fff&color=B5733A" alt="User">
                </div>
            </div>
        </header>

        <div class="p-8 flex-1 flex flex-col overflow-hidden">
            <div class="mb-6">
                <h2 class="text-3xl font-bold tracking-tight text-gray-800">Pusat Pesan</h2>
                <p class="text-xs text-gray-400 mt-1 font-medium italic">Direct marketplace communication by Decor</p>
            </div>

            <div class="bg-white rounded-3xl border border-gray-100 shadow-sm flex flex-1 overflow-hidden min-h-[500px]">
                <div class="w-80 border-r border-gray-100 flex flex-col bg-white">
                    <div class="p-4 border-b border-gray-50">
                        <input type="text" placeholder="Cari percakapan..." class="bg-gray-50 w-full rounded-lg py-2 px-3 text-xs border-none outline-none focus:ring-1 focus:ring-primary transition-all">
                    </div>
                    <div class="flex-1 overflow-y-auto">
                        <div class="p-6 flex items-center space-x-4 bg-primary/[0.03] border-r-4 border-primary cursor-pointer transition-all">
                            <div class="relative flex-shrink-0">
                                <div class="w-12 h-12 rounded-2xl bg-secondary flex items-center justify-center font-bold text-primary">EV</div>
                                <span class="absolute -bottom-1 -right-1 w-3 h-3 bg-green-500 border-2 border-white rounded-full"></span>
                            </div>
                            <div class="flex-1 min-w-0">
                                <div class="flex justify-between items-center mb-1">
                                    <h4 class="text-xs font-extrabold truncate text-gray-800">Elena Vance</h4>
                                    <span class="text-[9px] font-bold text-gray-400 uppercase">10:24 AM</span>
                                </div>
                                <p class="text-[11px] text-gray-500 truncate font-medium">What are the dimensions for the...</p>
                            </div>
                        </div>
                        </div>
                </div>

                <div class="flex-1 flex flex-col bg-[#FCFBFB]">
                    <div class="px-8 py-4 border-b border-gray-100 bg-white flex justify-between items-center">
                        <div class="flex items-center space-x-4">
                            <div class="w-10 h-10 rounded-xl bg-secondary flex items-center justify-center font-bold text-primary text-xs">EV</div>
                            <div>
                                <h4 class="text-xs font-extrabold text-gray-800">Elena Vance</h4>
                                <p class="text-[10px] text-green-500 font-bold uppercase tracking-widest">Online Now</p>
                            </div>
                        </div>
                        <button class="text-gray-300 hover:text-primary transition-colors"><i class="fa-solid fa-ellipsis-vertical"></i></button>
                    </div>

                    <div class="flex-1 overflow-y-auto p-8 space-y-6">
                        <div class="flex items-end space-x-4 max-w-[70%]">
                            <div class="bg-white bubble-customer p-4 text-xs font-medium leading-relaxed border border-gray-100 shadow-sm text-gray-600">
                                Hello! I'm interested in the Sienna Oak Table. Is it currently in stock?
                            </div>
                        </div>
                        <div class="flex items-end space-x-4 max-w-[70%] ml-auto text-right flex-row-reverse">
                            <div class="bg-primary text-white bubble-seller p-4 text-xs font-medium leading-relaxed mr-4 shadow-lg shadow-primary/10">
                                Hello Elena! Yes, we have 3 units remaining in our warehouse. ✨
                            </div>
                        </div>
                    </div>

                    <div class="p-6 bg-white border-t border-gray-100">
                        <div class="flex items-center space-x-4">
                            <button class="text-gray-400 hover:text-primary transition-colors"><i class="fa-solid fa-paperclip text-lg"></i></button>
                            <div class="flex-1 relative">
                                <input type="text" placeholder="Tulis pesan untuk Elena..." class="w-full bg-gray-50 rounded-2xl py-3 px-6 text-xs outline-none focus:bg-white border border-transparent focus:border-primary/20 transition-all">
                                <button class="absolute right-2 top-1/2 -translate-y-1/2 bg-primary text-white w-9 h-9 rounded-xl flex items-center justify-center shadow-lg shadow-primary/20 hover:opacity-90 transition-all">
                                    <i class="fa-solid fa-paper-plane text-xs"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <footer class="p-8 border-t border-gray-100 text-[10px] font-bold text-gray-400 uppercase tracking-widest mt-auto text-left">
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