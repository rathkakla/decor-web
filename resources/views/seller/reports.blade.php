<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Decor Seller Portal - Sales Report</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&display=swap');
        body { background-color: #F8F6F4; font-family: 'Plus Jakarta Sans', sans-serif; }
        .bg-primary { background-color: #B5733A; }
        .text-primary { color: #B5733A; }
        .active-link { background-color: rgba(181, 115, 58, 0.1); color: #B5733A; border-right: 4px solid #B5733A; }
    </style>
</head>
<body class="text-gray-800">

    <!-- Sidebar (Same as dashboard) -->
    @include('seller.partials.sidebar')

    <main id="main-content" class="flex-1 flex flex-col ml-64 sidebar-transition min-h-screen">
        @php
            $extraAction = '<a href="'.route('seller.reports.download', ['start_date' => $startDate, 'end_date' => $endDate]).'" class="bg-white text-primary px-6 py-2 rounded-lg text-[10px] font-black uppercase tracking-widest hover:bg-amber-50 transition-all flex items-center space-x-2"><i class="fa-solid fa-file-pdf"></i><span>Download PDF Report</span></a>';
        @endphp
        @include('seller.partials.header', ['title' => 'Business Analytics', 'extra_action' => $extraAction])

        <div class="p-8 space-y-8 flex-1">
            @if($errors->any())
                <div class="bg-red-50 text-red-600 p-4 rounded-xl text-sm font-bold border border-red-100 flex items-center shadow-sm">
                    <i class="fa-solid fa-triangle-exclamation mr-3 text-lg"></i> 
                    {{ $errors->first() }}
                </div>
            @endif
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
                <div>
                    <h2 class="text-3xl font-bold">Laporan Penjualan</h2>
                    <p class="text-[10px] text-gray-400 font-bold uppercase tracking-[0.2em] mt-1">Transaction History & Performance Summary</p>
                </div>
                
                <form action="{{ route('seller.reports') }}" method="GET" class="bg-white p-4 rounded-2xl shadow-sm border border-gray-100 flex items-end space-x-4">
                    <div class="space-y-1">
                        <label class="text-[9px] font-black text-gray-400 uppercase tracking-widest">Dari Tanggal</label>
                        <input type="date" name="start_date" value="{{ $startDate }}" class="block w-full text-xs font-bold border-gray-100 rounded-lg focus:ring-primary focus:border-primary">
                    </div>
                    <div class="space-y-1">
                        <label class="text-[9px] font-black text-gray-400 uppercase tracking-widest">Sampai Tanggal</label>
                        <input type="date" name="end_date" value="{{ $endDate }}" class="block w-full text-xs font-bold border-gray-100 rounded-lg focus:ring-primary focus:border-primary">
                    </div>
                    <button type="submit" class="bg-primary text-white px-6 py-2.5 rounded-xl text-[10px] font-black uppercase tracking-widest hover:bg-opacity-90 transition-all">
                        Terapkan
                    </button>
                </form>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- Total Pendapatan -->
                <div class="bg-white p-8 rounded-3xl border border-gray-100 shadow-sm relative overflow-hidden group">
                    <div class="relative z-10">
                        <p class="text-[10px] text-gray-400 font-bold uppercase tracking-widest mb-2">Total Pendapatan</p>
                        <h3 class="text-3xl font-black text-gray-900">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</h3>
                        <div class="mt-4 flex items-center text-green-500 space-x-1">
                            <i class="fa-solid fa-arrow-trend-up text-xs"></i>
                            <span class="text-[10px] font-bold">+12.5% vs last month</span>
                        </div>
                    </div>
                    <div class="absolute -right-4 -bottom-4 opacity-[0.03] group-hover:opacity-[0.05] transition-all">
                        <i class="fa-solid fa-wallet text-9xl"></i>
                    </div>
                </div>

                <!-- Total Pesanan Selesai -->
                <div class="bg-white p-8 rounded-3xl border border-gray-100 shadow-sm relative overflow-hidden group">
                    <div class="relative z-10">
                        <p class="text-[10px] text-gray-400 font-bold uppercase tracking-widest mb-2">Total Pesanan Selesai</p>
                        <h3 class="text-3xl font-black text-gray-900">{{ $transactions->groupBy('order_id')->count() }}</h3>
                        <div class="mt-4 flex items-center text-blue-500 space-x-1">
                            <i class="fa-solid fa-circle-check text-xs"></i>
                            <span class="text-[10px] font-bold">Based on selected period</span>
                        </div>
                    </div>
                    <div class="absolute -right-4 -bottom-4 opacity-[0.03] group-hover:opacity-[0.05] transition-all">
                        <i class="fa-solid fa-cart-shopping text-9xl"></i>
                    </div>
                </div>

                <!-- Rata-rata Order -->
                <div class="bg-white p-8 rounded-3xl border border-gray-100 shadow-sm relative overflow-hidden group">
                    <div class="relative z-10">
                        <p class="text-[10px] text-gray-400 font-bold uppercase tracking-widest mb-2">Rata-rata Order</p>
                        <h3 class="text-3xl font-black text-gray-900">Rp {{ number_format($averageOrder, 0, ',', '.') }}</h3>
                        <div class="mt-4 flex items-center text-amber-500 space-x-1">
                            <i class="fa-solid fa-bolt text-xs"></i>
                            <span class="text-[10px] font-bold">Stable performance</span>
                        </div>
                    </div>
                    <div class="absolute -right-4 -bottom-4 opacity-[0.03] group-hover:opacity-[0.05] transition-all">
                        <i class="fa-solid fa-star text-9xl"></i>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="p-8 border-b border-gray-50 flex justify-between items-center">
                    <h3 class="font-bold text-gray-900 uppercase tracking-widest text-sm">Rincian Transaksi Penjualan</h3>
                    <div class="relative">
                        <i class="fa-solid fa-magnifying-glass absolute left-3 top-1/2 -translate-y-1/2 text-gray-300 text-xs"></i>
                        <input type="text" placeholder="Cari transaksi..." class="pl-10 pr-4 py-2 bg-gray-50 border-none rounded-xl text-xs focus:ring-2 focus:ring-primary/20 w-64">
                    </div>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left">
                        <thead>
                            <tr class="bg-gray-50/50">
                                <th class="px-8 py-4 text-[10px] font-black text-gray-400 uppercase tracking-widest">Tanggal</th>
                                <th class="px-8 py-4 text-[10px] font-black text-gray-400 uppercase tracking-widest">Order ID</th>
                                <th class="px-8 py-4 text-[10px] font-black text-gray-400 uppercase tracking-widest">Produk</th>
                                <th class="px-8 py-4 text-[10px] font-black text-gray-400 uppercase tracking-widest text-center">Kategori</th>
                                <th class="px-8 py-4 text-[10px] font-black text-gray-400 uppercase tracking-widest text-center">Qty</th>
                                <th class="px-8 py-4 text-[10px] font-black text-gray-400 uppercase tracking-widest text-right">Total Harga</th>
                                <th class="px-8 py-4 text-[10px] font-black text-gray-400 uppercase tracking-widest text-center">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50">
                            @forelse($transactions as $item)
                            <tr class="hover:bg-gray-50/50 transition-all">
                                <td class="px-8 py-5">
                                    <span class="text-xs font-bold text-gray-400 italic">{{ $item->order->created_at->format('d F Y') }}</span>
                                </td>
                                <td class="px-8 py-5">
                                    <span class="text-xs font-black text-primary uppercase">
                                        @if($item->order->return_code)
                                            #DEC-{{ str_replace('RET-', '', $item->order->return_code) }}-RET
                                        @else
                                            #ORD-{{ $item->order->id }}
                                        @endif
                                    </span>
                                </td>
                                <td class="px-8 py-5">
                                    <span class="text-xs font-bold text-gray-700">{{ $item->product->name }}</span>
                                </td>
                                <td class="px-8 py-5 text-center">
                                    <span class="px-3 py-1 bg-gray-100 text-[9px] font-black text-gray-500 rounded-full uppercase tracking-tighter">
                                        {{ $item->product->category->name ?? 'N/A' }}
                                    </span>
                                </td>
                                <td class="px-8 py-5 text-center font-bold text-xs">{{ $item->quantity }}</td>
                                <td class="px-8 py-5 text-right font-black text-xs">Rp {{ number_format($item->price * $item->quantity, 0, ',', '.') }}</td>
                                <td class="px-8 py-5 text-center">
                                    @php
                                        $statusClasses = [
                                            'pending' => 'bg-amber-50 text-amber-600',
                                            'processing' => 'bg-blue-50 text-blue-600',
                                            'shipped' => 'bg-purple-50 text-purple-600',
                                            'delivered' => 'bg-cyan-50 text-cyan-600',
                                            'completed' => 'bg-green-50 text-green-600',
                                            'cancelled' => 'bg-red-50 text-red-600',
                                        ];
                                        $class = $statusClasses[$item->order->status] ?? 'bg-gray-50 text-gray-600';
                                    @endphp
                                    <span class="px-3 py-1 {{ $class }} text-[9px] font-black rounded-full uppercase tracking-widest">
                                        {{ $item->order->status == 'completed' ? 'SELESAI' : ($item->order->status == 'shipped' ? 'DIKIRIM' : strtoupper($item->order->status)) }}
                                    </span>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="px-8 py-20 text-center text-gray-400 font-bold italic text-sm">
                                    Tidak ada transaksi ditemukan pada rentang tanggal ini.
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
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