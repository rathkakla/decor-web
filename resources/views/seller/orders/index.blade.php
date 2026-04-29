<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Decor Seller - Daftar Pesanan</title>
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
        .main-expanded { margin-left: 0 !important; left: 0 !important; }
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
            <a href="{{ route('seller.orders') }}" class="flex items-center px-4 py-3 text-xs font-bold active-link transition-all rounded-lg">
                <i class="fa-solid fa-bag-shopping mr-3 w-5 text-center"></i> Daftar Pesanan
            </a>
            <a href="{{ route('seller.chats') }}" class="flex items-center px-4 py-3 text-xs font-bold text-gray-400 hover:text-primary transition-all rounded-lg">
                <i class="fa-solid fa-message mr-3 w-5 text-center"></i> Seller Chat
            </a>
            <a href="{{ route('seller.complaint.index') }}" class="flex items-center px-4 py-3 text-xs font-bold text-gray-400 hover:text-primary transition-all rounded-lg">
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
            <a href="{{ route('seller.support') }}" class="flex items-center px-4 py-3 text-xs font-bold text-gray-400 hover:text-primary transition-all rounded-lg">
                <i class="fa-solid fa-headset mr-3 w-5 text-center"></i> Support
            </a>
        </div>
    </aside>

    <main id="main-content" class="flex-1 flex flex-col ml-64 sidebar-transition min-h-screen">
        
        <header class="h-16 bg-primary flex items-center justify-between px-8 sticky top-0 z-40 shadow-sm">
            <div class="flex items-center">
                <button id="toggle-sidebar" class="text-white hover:opacity-80 mr-4 flex items-center justify-center transition-transform active:scale-95">
                    <i class="fa-solid fa-bars-staggered text-xl"></i>
                </button>
                <h2 class="font-bold text-xs uppercase tracking-widest text-white leading-none">Order Management</h2>
            </div>
            <div class="flex items-center space-x-6 text-white font-bold text-[10px] tracking-widest uppercase">
                <i class="fa-regular fa-bell text-xl cursor-pointer"></i>
                <div class="w-9 h-9 rounded-lg bg-white/20 flex items-center justify-center overflow-hidden border border-white/30">
                     <img src="https://ui-avatars.com/api/?name=Audri&background=fff&color=B5733A" alt="User">
                </div>
            </div>
        </header>

        <div class="p-8 space-y-8 flex-1">
            <div class="flex justify-between items-center">
                <div>
                    <h2 class="text-3xl font-bold tracking-tight text-gray-800">Daftar Pesanan</h2>
                    <p class="text-xs text-gray-400 mt-1 font-medium italic">Manage and track your customer orders with precision.</p>
                </div>
                <div class="relative w-1/3">
                    <i class="fa-solid fa-magnifying-glass absolute left-3 top-1/2 -translate-y-1/2 text-gray-300 text-sm"></i>
                    <input type="text" placeholder="Search orders..." class="w-full bg-white border border-gray-200 rounded-xl py-2 pl-10 pr-4 text-xs focus:border-primary outline-none transition-all">
                </div>
            </div>

            <div class="flex space-x-8 border-b border-gray-200 text-[10px] font-black uppercase tracking-widest text-gray-400">
                <button class="pb-4 border-b-2 border-primary text-primary">Semua</button>
                <button class="pb-4 hover:text-primary transition-colors">Dalam Proses</button>
                <button class="pb-4 hover:text-primary transition-colors">Dikirim</button>
                <button class="pb-4 hover:text-primary transition-colors">Selesai</button>
            </div>

            <div class="space-y-4">
                @foreach($orders as $order)
                <div class="bg-white p-6 rounded-2xl border border-gray-100 flex items-center justify-between hover:shadow-md transition-shadow group">
                    <div class="flex items-center space-x-6">
                        <div class="relative">
                            <img src="{{ $order['img'] }}" class="w-16 h-16 rounded-xl object-cover grayscale group-hover:grayscale-0 transition-all">
                            <div class="absolute -bottom-2 -right-2 bg-white p-1 rounded-lg shadow-sm">
                                <i class="fa-solid fa-circle-check text-[10px] text-green-500"></i>
                            </div>
                        </div>
                        
                        <div class="space-y-1">
                            <div class="flex items-center space-x-3">
                                <span class="text-xs font-bold text-primary italic">#{{ $order['id'] }}</span>
                                <span class="{{ $order['status_color'] }} text-[8px] font-black px-2 py-0.5 rounded tracking-widest uppercase">{{ $order['status'] }}</span>
                            </div>
                            <h3 class="text-lg font-bold text-gray-800">{{ $order['customer'] }}</h3>
                            <div class="flex items-center space-x-4 text-[10px] text-gray-400 font-bold uppercase tracking-tighter">
                                <span><i class="fa-regular fa-calendar mr-1"></i> {{ $order['date'] }}</span>
                                <span><i class="fa-solid fa-box mr-1"></i> {{ $order['items'] }}</span>
                            </div>
                        </div>
                    </div>

                    <div class="text-right space-y-4">
                        <div>
                            <p class="text-[8px] text-gray-400 font-bold uppercase tracking-widest">Total Amount</p>
                            <p class="text-xl font-black leading-none mt-1 text-gray-800">{{ $order['total'] }}</p>
                        </div>
                        <div class="flex space-x-3 justify-end">
                            @if($order['status'] == 'BARU')
                                <button onclick="confirmOrder('{{ $order['id'] }}')" class="px-4 py-2 border border-gray-200 rounded-lg text-[10px] font-bold uppercase tracking-widest hover:bg-gray-50 transition-colors">Konfirmasi</button>
                                <a href="{{ route('orders.label') }}" target="_blank" class="px-4 py-2 bg-primary text-white rounded-lg text-[10px] font-bold uppercase tracking-widest shadow-lg shadow-primary/20 hover:opacity-90 transition-all">Cetak Label</a>
                            @elseif($order['status'] == 'DALAM PROSES')
                                <a href="{{ route('orders.show') }}" class="px-4 py-2 border border-gray-200 rounded-lg text-[10px] font-bold uppercase tracking-widest hover:bg-gray-50 transition-colors">Detail</a>
                                <button onclick="openStatusModal('{{ $order['id'] }}')" class="px-4 py-2 bg-primary text-white rounded-lg text-[10px] font-bold uppercase tracking-widest shadow-lg shadow-primary/20">Update Status</button>
                            @else
                                <a href="{{ route('orders.invoice') }}" target="_blank" class="px-6 py-2 bg-primary text-white rounded-lg text-[10px] font-bold uppercase tracking-widest shadow-lg shadow-primary/20 hover:opacity-90 transition-all">
                                    <i class="fa-solid fa-file-invoice mr-2"></i> Cetak Invoice
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        <footer class="px-8 py-6 border-t border-gray-100 text-[10px] font-bold text-gray-400 uppercase tracking-widest text-left mt-auto">
            © 2026 DECOR MARKETPLACE SELLER PORTAL. ALL RIGHTS RESERVED.
        </footer>
    </main>

    <div id="statusModal" class="fixed inset-0 bg-black/50 z-[60] hidden items-center justify-center backdrop-blur-sm">
        <div class="bg-white w-96 rounded-[32px] p-8 space-y-6 shadow-2xl">
            <div class="text-center">
                <div class="w-16 h-16 bg-primary/10 text-primary rounded-full flex items-center justify-center mx-auto mb-4 text-2xl">
                    <i class="fa-solid fa-truck-ramp-box"></i>
                </div>
                <h3 class="text-xl font-bold">Update Status</h3>
                <p class="text-xs text-gray-400 font-medium">Pilih tahapan pengiriman terbaru</p>
            </div>
            <div class="space-y-2">
                <button onclick="closeStatusModal()" class="w-full text-left p-4 rounded-xl border border-gray-100 hover:border-primary hover:bg-primary/5 transition-all text-xs font-bold italic">📦 Pesanan Sedang Dikemas</button>
                <button onclick="closeStatusModal()" class="w-full text-left p-4 rounded-xl border border-gray-100 hover:border-primary hover:bg-primary/5 transition-all text-xs font-bold italic">🚚 Pesanan Sedang Dikirim</button>
                <button onclick="closeStatusModal()" class="w-full text-left p-4 rounded-xl border border-gray-100 hover:border-primary hover:bg-primary/5 transition-all text-xs font-bold italic">✅ Pesanan Telah Sampai</button>
            </div>
            <button onclick="closeStatusModal()" class="w-full py-2 text-[10px] font-black uppercase tracking-widest text-gray-400">Batal</button>
        </div>
    </div>

    <script>
        const btn = document.getElementById('toggle-sidebar');
        const sidebar = document.getElementById('sidebar');
        const main = document.getElementById('main-content');
        btn.addEventListener('click', () => { 
            sidebar.classList.toggle('sidebar-hidden'); 
            main.classList.toggle('main-expanded'); 
        });

        function confirmOrder(id) { if(confirm(`Konfirmasi pesanan ${id}?`)) alert('Pesanan diproses!'); }
        function openStatusModal() {
            document.getElementById('statusModal').classList.remove('hidden');
            document.getElementById('statusModal').classList.add('flex');
        }
        function closeStatusModal() {
            document.getElementById('statusModal').classList.add('hidden');
            document.getElementById('statusModal').classList.remove('flex');
        }
    </script>
</body>
</html>