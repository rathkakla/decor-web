<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Decor Seller - Detail Komplain</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap');
        body { background-color: #F8F6F4; font-family: 'Plus Jakarta Sans', sans-serif; overflow-x: hidden; }
        .bg-primary { background-color: #B5733A; }
        .text-primary { color: #B5733A; }
        .active-link { background-color: rgba(181, 115, 58, 0.1); color: #B5733A; border-right: 4px solid #B5733A; }
        .sidebar-transition { transition: transform 0.3s ease-in-out, margin 0.3s ease-in-out; }
        .sidebar-hidden { transform: translateX(-100%); }
        .main-expanded { margin-left: 0 !important; }
        
        /* Badge Status Khusus */
        .badge-investigating { background-color: #8D592C; color: white; }
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
            <a href="{{ route('seller.chats') }}" class="flex items-center px-4 py-3 text-xs font-bold text-gray-400 hover:text-primary transition-all rounded-lg">
                <i class="fa-solid fa-message mr-3 w-5 text-center"></i> Seller Chat
            </a>
            <a href="{{ route('seller.complaint.index') }}" class="flex items-center px-4 py-3 text-xs font-bold active-link transition-all rounded-lg">
                <i class="fa-solid fa-circle-exclamation mr-3 w-5 text-center"></i> Komplain
            </a>
            <a href="{{ route('seller.reviews') }}" class="flex items-center px-4 py-3 text-xs font-bold text-gray-400 hover:text-primary transition-all rounded-lg">
                <i class="fa-solid fa-star mr-3 w-5 text-center"></i> Review & Rating
            </a>
            <a href="{{ route('seller.reports') }}" class="flex items-center px-4 py-3 text-xs font-bold text-gray-400 hover:text-primary transition-all rounded-lg">
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
                <div class="flex items-center space-x-3">
                    <h2 class="font-bold text-sm uppercase tracking-widest text-white leading-none">Case ID #CS-8842</h2>
                    <span class="badge-investigating text-[8px] font-black px-2 py-0.5 rounded tracking-widest uppercase border border-white/20">INVESTIGATING</span>
                </div>
            </div>
            <div class="flex items-center space-x-6 text-white">
                <i class="fa-regular fa-bell text-xl cursor-pointer"></i>
                <i class="fa-regular fa-envelope text-xl cursor-pointer"></i>
                <img src="https://ui-avatars.com/api/?name=Audri&background=fff&color=B5733A" class="w-9 h-9 rounded-lg border-2 border-white/20">
            </div>
        </header>

        <div class="p-8 grid grid-cols-12 gap-8">
            
            <div class="col-span-12 lg:col-span-8 space-y-8">
                
                <div class="bg-white p-8 rounded-3xl border border-gray-100 shadow-sm space-y-8">
                    <div class="flex justify-between items-start">
                        <div>
                            <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">Customer Details</p>
                            <h3 class="text-3xl font-black text-gray-800 tracking-tight">Julian Voss</h3>
                            <p class="text-sm font-bold text-gray-400">+49 172 884 9201</p>
                        </div>
                        <div class="text-right">
                            <p class="text-[9px] font-black text-gray-300 uppercase tracking-widest">Member Since</p>
                            <p class="text-sm font-bold text-gray-800">May 2021</p>
                        </div>
                    </div>

                    <div class="space-y-4">
                        <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Recent Order History</p>
                        <div class="overflow-hidden border border-gray-50 rounded-2xl">
                            <table class="w-full text-left text-[10px] font-bold">
                                <thead class="bg-gray-50/50 text-gray-300 uppercase tracking-widest">
                                    <tr>
                                        <th class="px-6 py-3">Order ID</th>
                                        <th class="px-6 py-3">Date</th>
                                        <th class="px-6 py-3">Status</th>
                                        <th class="px-6 py-3 text-right">Total</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-50">
                                    <tr>
                                        <td class="px-6 py-4 font-black">#DEC-8829</td>
                                        <td class="px-6 py-4 text-gray-400">Oct 14, 2023</td>
                                        <td class="px-6 py-4">
                                            <span class="bg-gray-100 text-gray-500 px-2 py-0.5 rounded text-[8px]">DELIVERED</span>
                                        </td>
                                        <td class="px-6 py-4 text-right font-black text-sm">$2,490.00</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="bg-white p-8 rounded-3xl border border-gray-100 shadow-sm space-y-6">
                    <div>
                        <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2">Issue Summary</p>
                        <h4 class="text-2xl font-black text-gray-800 leading-tight">Structural Damage: Bastille Lounge Chair</h4>
                    </div>
                    <div class="border-l-4 border-primary pl-6 py-2">
                        <p class="text-sm font-bold text-gray-500 leading-relaxed italic">
                            "The item arrived today with significant structural compromises. Upon unboxing, we discovered multiple stress cracks running along the walnut frame's left joint. The wood appears splintered, and the structural integrity is severely weakened. Additionally, there are deep scuffs on the leather seat and a noticeable tear on the rear upholstery."
                        </p>
                    </div>
                </div>

                <div class="space-y-4">
                    <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Damage Evidence Gallery</p>
                    <div class="grid grid-cols-4 gap-4">
                        <div class="aspect-square rounded-2xl overflow-hidden shadow-sm border border-gray-200">
                            <img src="https://images.unsplash.com/photo-1592078615290-033ee584e267?q=80&w=400" class="w-full h-full object-cover">
                        </div>
                        <div class="aspect-square rounded-2xl overflow-hidden shadow-sm border border-gray-200">
                            <img src="https://images.unsplash.com/photo-1567016432779-094069958ea5?q=80&w=400" class="w-full h-full object-cover">
                        </div>
                        <div class="aspect-square rounded-2xl overflow-hidden shadow-sm border border-gray-200">
                            <img src="https://images.unsplash.com/photo-1594026112284-02bb6f3352fe?q=80&w=400" class="w-full h-full object-cover">
                        </div>
                        <div class="aspect-square rounded-2xl overflow-hidden shadow-sm border border-gray-200 bg-gray-200 flex items-center justify-center text-gray-400">
                            <i class="fa-solid fa-plus text-2xl"></i>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-span-12 lg:col-span-4 space-y-8">
                
                <div class="bg-white p-8 rounded-3xl border border-gray-100 shadow-sm space-y-6">
                    <h4 class="text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] mb-4">Resolution Center</h4>
                    <button class="w-full bg-primary text-white py-4 rounded-xl text-[10px] font-black uppercase tracking-[0.1em] shadow-lg shadow-primary/20 hover:opacity-90 transition-all">
                        <i class="fa-solid fa-check-circle mr-2"></i> Accept Return
                    </button>
                    <button class="w-full bg-white border border-gray-200 text-gray-800 py-4 rounded-xl text-[10px] font-black uppercase tracking-[0.1em] hover:bg-gray-50 transition-all">
                        <i class="fa-solid fa-xmark-circle mr-2"></i> Reject Claim
                    </button>
                    <div class="pt-4 flex justify-center">
                        <button class="flex items-center text-[9px] font-black text-gray-400 hover:text-primary transition-colors uppercase tracking-widest">
                            <i class="fa-solid fa-message mr-2"></i> Chat with Customer
                        </button>
                    </div>
                </div>

                <div class="bg-white p-8 rounded-3xl border border-gray-100 shadow-sm space-y-6">
                    <h4 class="text-[10px] font-black text-gray-400 uppercase tracking-[0.2em]">Internal Notes</h4>
                    <div class="space-y-4">
                        <div class="bg-gray-50 p-4 rounded-2xl border border-gray-100 flex items-start space-x-3">
                            <i class="fa-solid fa-circle-info text-gray-300 mt-1 text-xs"></i>
                            <p class="text-[10px] font-bold text-gray-500 leading-relaxed">Inventory check shows 2 units remaining in Berlin warehouse for possible replacement.</p>
                        </div>
                        <div class="bg-gray-50 p-4 rounded-2xl border border-gray-100 flex items-start space-x-3">
                            <i class="fa-solid fa-truck text-gray-300 mt-1 text-xs"></i>
                            <p class="text-[10px] font-bold text-gray-500 leading-relaxed">DHL Express courier flagged 'Heavy Load' during initial delivery. Potential handling error.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <footer class="p-8 border-t border-gray-100 flex justify-between items-center text-[10px] font-bold text-gray-400 uppercase tracking-widest mt-auto">
            <p>© 2026 DECOR MERCHANT SERVICE CENTER. ALL RIGHTS RESERVED.</p>
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