<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Decor Seller - Laporan Penjualan</title>
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
        
        @media print {
            .no-print { display: none !important; }
            main { margin-left: 0 !important; }
            .print-area { border: none !important; shadow: none !important; }
        }
    </style>
</head>
<body class="text-gray-800">

    <aside id="sidebar" class="w-64 bg-white border-r border-gray-200 flex flex-col fixed h-full z-50 sidebar-transition no-print">
        <div class="p-8">
            <h1 class="text-2xl font-bold tracking-widest text-primary uppercase leading-none">DECOR</h1>
            <p class="text-[10px] text-gray-400 mt-1 uppercase tracking-widest">Seller Portal</p>
        </div>

        <nav class="flex-1 px-4 space-y-1">
            <a href="{{ route('seller.dashboard') }}" class="flex items-center px-4 py-3 text-xs font-bold text-gray-400 hover:text-primary rounded-lg">
                <i class="fa-solid fa-table-columns mr-3 w-5 text-center"></i> Dashboard
            </a>
            <a href="{{ route('seller.products.index') }}" class="flex items-center px-4 py-3 text-xs font-bold text-gray-400 hover:text-primary rounded-lg">
                <i class="fa-solid fa-couch mr-3 w-5 text-center"></i> Kelola Produk
            </a>
            <a href="{{ route('seller.orders') }}" class="flex items-center px-4 py-3 text-xs font-bold text-gray-400 hover:text-primary rounded-lg">
                <i class="fa-solid fa-bag-shopping mr-3 w-5 text-center"></i> Daftar Pesanan
            </a>
            <a href="{{ route('seller.chats') }}" class="flex items-center px-4 py-3 text-xs font-bold text-gray-400 hover:text-primary rounded-lg">
                <i class="fa-solid fa-message mr-3 w-5 text-center"></i> Seller Chat
            </a>
            <a href="{{ route('seller.complaint.index') }}" class="flex items-center px-4 py-3 text-xs font-bold text-gray-400 hover:text-primary rounded-lg">
                <i class="fa-solid fa-circle-exclamation mr-3 w-5 text-center"></i> Komplain
            </a>
            <a href="{{ route('seller.reviews') }}" class="flex items-center px-4 py-3 text-xs font-bold text-gray-400 hover:text-primary rounded-lg">
                <i class="fa-solid fa-star mr-3 w-5 text-center"></i> Review & Rating
            </a>
            <a href="{{ route('seller.reports') }}" class="flex items-center px-4 py-3 text-xs font-bold active-link transition-all rounded-lg">
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
        
        <header class="h-16 bg-primary flex items-center justify-between px-8 sticky top-0 z-40 shadow-sm no-print">
            <div class="flex items-center">
                <button id="toggle-sidebar" class="text-white hover:opacity-80 mr-4 transition-transform active:scale-95">
                    <i class="fa-solid fa-bars-staggered text-xl"></i>
                </button>
                <h2 class="font-bold text-xs uppercase tracking-widest text-white leading-none">Business Analytics</h2>
            </div>
            <button onclick="window.print()" class="bg-white text-primary px-6 py-2 rounded-lg text-[10px] font-black uppercase tracking-widest shadow-md hover:bg-gray-100 transition-all">
                <i class="fa-solid fa-file-pdf mr-2"></i> Download PDF Report
            </button>
        </header>

        <div class="p-8 space-y-8 flex-1 print-area">
            <div class="flex flex-col md:flex-row md:items-end justify-between gap-6">
                <div>
                    <h2 class="text-3xl font-black tracking-tight text-gray-800">Laporan Penjualan</h2>
                    <p class="text-[10px] font-bold text-gray-400 mt-1 uppercase tracking-[0.2em] italic">Transaction history & performance summary</p>
                </div>
                
                <div class="bg-white p-4 rounded-[24px] shadow-sm border border-gray-100 flex flex-wrap items-center gap-4 no-print">
                    <div class="space-y-1">
                        <label class="text-[8px] font-black uppercase text-gray-400 ml-1">Dari Tanggal</label>
                        <input type="date" class="block w-full bg-gray-50 border-none rounded-xl text-[10px] font-bold p-2 outline-none focus:ring-2 focus:ring-primary/20">
                    </div>
                    <div class="space-y-1">
                        <label class="text-[8px] font-black uppercase text-gray-400 ml-1">Sampai Tanggal</label>
                        <input type="date" class="block w-full bg-gray-50 border-none rounded-xl text-[10px] font-bold p-2 outline-none focus:ring-2 focus:ring-primary/20">
                    </div>
                    
                    <button class="bg-primary text-white px-6 py-3 rounded-xl hover:opacity-90 mt-3 md:mt-0 shadow-lg shadow-primary/20 transition-all text-[10px] font-black uppercase tracking-widest flex items-center">
                        <i class="fa-solid fa-check-circle mr-2"></i> Terapkan
                    </button>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="bg-white p-8 rounded-[32px] border border-gray-100 shadow-sm relative overflow-hidden group">
                    <i class="fa-solid fa-wallet absolute -right-4 -bottom-4 text-gray-50 text-8xl group-hover:text-primary/5 transition-colors"></i>
                    <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Total Pendapatan</p>
                    <h3 class="text-3xl font-black text-gray-800 mt-2">Rp 124.5M</h3>
                    <div class="mt-4 flex items-center text-[9px] font-bold text-green-500">
                        <i class="fa-solid fa-arrow-trend-up mr-1"></i> +12.5% vs last month
                    </div>
                </div>
                <div class="bg-white p-8 rounded-[32px] border border-gray-100 shadow-sm relative overflow-hidden group">
                    <i class="fa-solid fa-bag-shopping absolute -right-4 -bottom-4 text-gray-50 text-8xl group-hover:text-primary/5 transition-colors"></i>
                    <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Pesanan Baru</p>
                    <h3 class="text-3xl font-black text-gray-800 mt-2">342</h3>
                    <div class="mt-4 flex items-center text-[9px] font-bold text-blue-500">
                        <i class="fa-solid fa-circle-check mr-1"></i> +5.2% vs last month
                    </div>
                </div>
                <div class="bg-white p-8 rounded-[32px] border border-gray-100 shadow-sm relative overflow-hidden group">
                    <i class="fa-solid fa-chart-line absolute -right-4 -bottom-4 text-gray-50 text-8xl group-hover:text-primary/5 transition-colors"></i>
                    <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Rata-Rata Order</p>
                    <h3 class="text-3xl font-black text-gray-800 mt-2">Rp 2.4M</h3>
                    <div class="mt-4 flex items-center text-[9px] font-bold text-orange-500">
                        <i class="fa-solid fa-bolt mr-1"></i> Stable performance
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-[40px] border border-gray-100 shadow-sm overflow-hidden">
                <div class="p-8 border-b border-gray-50 flex justify-between items-center">
                    <h3 class="text-sm font-black text-gray-800 uppercase tracking-widest">Rincian Transaksi Penjualan</h3>
                    <div class="flex space-x-2 no-print">
                        <button class="w-8 h-8 rounded-full bg-gray-50 text-gray-400 flex items-center justify-center hover:text-primary transition-colors">
                            <i class="fa-solid fa-magnifying-glass text-[10px]"></i>
                        </button>
                    </div>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left">
                        <thead>
                            <tr class="bg-gray-50/50 text-[10px] font-black text-gray-400 uppercase tracking-[0.2em]">
                                <th class="py-5 pl-8">Tanggal</th>
                                <th class="py-5">Order ID</th>
                                <th class="py-5">Produk</th>
                                <th class="py-5">Kategori</th>
                                <th class="py-5 text-center">Qty</th>
                                <th class="py-5 text-right">Total Harga</th>
                                <th class="py-5 pr-8 text-center">Status</th>
                            </tr>
                        </thead>
                        <tbody class="text-xs font-bold text-gray-600">
                            @php
                                $reports = [
                                    ['date' => '25 April 2026', 'id' => 'ORD-8821', 'product' => 'Sienna Oak Dining Table', 'cat' => 'Meja', 'qty' => 1, 'price' => 'Rp 4.500.000', 'status' => 'Selesai'],
                                    ['date' => '24 April 2026', 'id' => 'ORD-8819', 'product' => 'Bastille Lounge Chair', 'cat' => 'Kursi', 'qty' => 2, 'price' => 'Rp 8.900.000', 'status' => 'Dikirim'],
                                    ['date' => '24 April 2026', 'id' => 'ORD-8815', 'product' => 'Minimalist Floor Lamp', 'cat' => 'Lampu', 'qty' => 3, 'price' => 'Rp 1.250.000', 'status' => 'Selesai'],
                                    ['date' => '23 April 2026', 'id' => 'ORD-8810', 'product' => 'Velvet Tufted Sofa', 'cat' => 'Sofa', 'qty' => 1, 'price' => 'Rp 12.400.000', 'status' => 'Selesai'],
                                    ['date' => '22 April 2026', 'id' => 'ORD-8798', 'product' => 'Modern Oak Bookshelf', 'cat' => 'Lemari', 'qty' => 1, 'price' => 'Rp 3.200.000', 'status' => 'Selesai'],
                                ];
                            @endphp

                            @foreach($reports as $item)
                            <tr class="hover:bg-gray-50/50 transition-colors group">
                                <td class="py-5 pl-8 border-b border-gray-50 italic text-gray-400">{{ $item['date'] }}</td>
                                <td class="py-5 border-b border-gray-50 text-primary">#{{ $item['id'] }}</td>
                                <td class="py-5 border-b border-gray-50 text-gray-800">{{ $item['product'] }}</td>
                                <td class="py-5 border-b border-gray-50">
                                    <span class="text-[9px] bg-gray-100 px-2 py-1 rounded-lg uppercase tracking-tighter">{{ $item['cat'] }}</span>
                                </td>
                                <td class="py-5 border-b border-gray-50 text-center">{{ $item['qty'] }}</td>
                                <td class="py-5 border-b border-gray-50 text-right font-black text-gray-800">{{ $item['price'] }}</td>
                                <td class="py-5 pr-8 border-b border-gray-50 text-center">
                                    <span class="{{ $item['status'] == 'Selesai' ? 'text-green-500' : 'text-blue-500' }} text-[9px] font-black uppercase tracking-widest">
                                        {{ $item['status'] }}
                                    </span>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <footer class="px-8 py-6 border-t border-gray-100 text-[10px] font-bold text-gray-400 uppercase tracking-widest text-left mt-auto no-print">
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