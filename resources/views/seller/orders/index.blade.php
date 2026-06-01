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

    @include('seller.partials.sidebar')

  <main id="main-content" class="flex-1 flex flex-col ml-64 sidebar-transition min-h-screen">
    @include('seller.partials.header', ['title' => 'Order Management'])

    <div class="p-8 space-y-8 flex-1">
        <!-- ALERT PESAN SUKSES -->
        @if(session('success'))
            <div class="bg-green-50 text-green-600 p-4 rounded-xl text-sm font-bold border border-green-100 flex items-center">
                <i class="fa-solid fa-circle-check mr-3 text-lg"></i> {{ session('success') }}
            </div>
        @endif

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

        <!-- NAVIGASI FILTER -->
        <div class="flex space-x-8 border-b border-gray-200 text-[10px] font-black uppercase tracking-widest text-gray-400 mb-8">
            <a href="#" class="pb-4 border-b-2 border-primary text-primary">Semua</a>
            <a href="#" class="pb-4 hover:text-gray-800 transition-colors">pending</a>
            <a href="#" class="pb-4 hover:text-gray-800 transition-colors">paid</a>
            <a href="#" class="pb-4 hover:text-gray-800 transition-colors">shipped</a>
            <a href="#" class="pb-4 hover:text-gray-800 transition-colors">completed</a>
            <a href="#" class="pb-4 hover:text-gray-800 transition-colors">cancelled</a>
        </div>

        <div class="space-y-4">
            @forelse($orders as $order)
            @php
                $badgeClass = match($order->status) {
                    'pending' => 'bg-yellow-100 text-yellow-600',
                    'waiting_verification' => 'bg-orange-100 text-orange-600',
                    'paid' => 'bg-blue-100 text-blue-600',
                    'shipped' => 'bg-indigo-100 text-indigo-600',
                    'completed' => 'bg-green-100 text-green-600',
                    'cancelled' => 'bg-red-100 text-red-600',
                    default => 'bg-gray-100 text-gray-600',
                };
            @endphp

            <div class="bg-white p-6 rounded-2xl border border-gray-100 flex items-center justify-between hover:shadow-md transition-shadow group">
                <div class="flex items-center space-x-6">
                    <div class="relative">
                        <img src="{{ $order->orderItems->first()->product->images->first()->img_url ?? 'https://via.placeholder.com/150' }}" class="w-16 h-16 rounded-xl object-cover grayscale group-hover:grayscale-0 transition-all border border-gray-100">
                        @if($order->status == 'completed')
                        <div class="absolute -bottom-2 -right-2 bg-white p-1 rounded-lg shadow-sm">
                            <i class="fa-solid fa-circle-check text-[10px] text-green-500"></i>
                        </div>
                        @endif
                    </div>
                    
                    <div class="space-y-1">
                        <div class="flex items-center space-x-3">
                            <span class="text-sm font-bold text-gray-700">
                                @if($order->return_code)
                                    #DEC-{{ str_replace('RET-', '', $order->return_code) }}-RET
                                @else
                                    #ORD-{{ $order->id }}
                                @endif
                            </span>
                            <span class="{{ $badgeClass }} text-[8px] font-black px-2 py-0.5 rounded tracking-widest uppercase">{{ $order->status }}</span>
                        </div>
                        <h3 class="text-lg font-black text-gray-800">{{ $order->customer->user->full_name ?? 'Customer' }}</h3>
                        <div class="flex items-center space-x-4 text-[10px] text-gray-400 font-bold uppercase tracking-tighter">
                            <span><i class="fa-regular fa-calendar mr-1"></i> {{ $order->created_at->format('d M Y') }}</span>
                            <span><i class="fa-solid fa-box mr-1"></i> {{ $order->orderItems->sum('quantity') }} Items</span>
                        </div>
                    </div>
                </div>

                <div class="text-right space-y-4">
                    <div>
                        <p class="text-[8px] text-gray-400 font-bold uppercase tracking-widest text-right">Total Amount</p>
                        <p class="text-xl font-black leading-none mt-1 text-gray-800">Rp {{ number_format($order->total_price, 0, ',', '.') }}</p>
                    </div>
                    <div class="flex space-x-3 justify-end">
                        @if($order->status == 'waiting_verification')
                            <a href="{{ route('seller.orders.show', $order->id) }}" 
                                class="px-5 py-2.5 bg-orange-500 text-white rounded-lg text-[10px] font-black uppercase tracking-widest shadow-lg shadow-orange-500/20 hover:opacity-90 transition-all">
                                <i class="fa-solid fa-file-invoice-dollar mr-1"></i> Validasi Pembayaran
                            </a>
                        @elseif($order->status == 'paid')
                            <!-- FIX: Gunakan route name dan kirim URL penuh ke fungsi JS -->
                            <button onclick="openStatusModal('{{ route('seller.orders.update-status', $order->id) }}')" 
                                class="px-5 py-2.5 bg-[#B5733A] text-white rounded-lg text-[10px] font-black uppercase tracking-widest shadow-lg shadow-primary/20 hover:opacity-90 transition-all">
                                Proses & Kirim
                            </button>
                        @elseif($order->status == 'shipped')
                            <!-- FIX: Gunakan route name yang sama -->
                            <button onclick="openStatusModal('{{ route('seller.orders.update-status', $order->id) }}')" 
                                class="px-5 py-2.5 bg-indigo-500 text-white rounded-lg text-[10px] font-black uppercase tracking-widest shadow-lg shadow-indigo-500/20">
                                Update Status
                            </button>
                        @endif
                        <a href="{{ route('invoice.download', $order->id) }}" target="_blank" class="px-5 py-2.5 border border-[#B5733A] text-[#B5733A] bg-amber-50/30 rounded-lg text-[10px] font-black uppercase tracking-widest hover:bg-amber-50 transition-all flex items-center">
                            <i class="fa-solid fa-file-invoice mr-2"></i> Invoice
                        </a>
                        <a href="{{ route('seller.orders.show', $order->id) }}" class="px-5 py-2.5 border border-gray-200 text-gray-800 rounded-lg text-[10px] font-black uppercase tracking-widest hover:bg-gray-50 transition-all">Detail</a>
                    </div>
                </div>
            </div>
            @empty
                <div class="text-center py-20 bg-white rounded-3xl border-2 border-dashed border-gray-100">
                    <p class="text-gray-400 font-bold italic">Belum ada pesanan masuk.</p>
                </div>
            @endforelse
        </div>
    </div>
</main>

    <!-- MODAL UPDATE STATUS -->
    <div id="statusModal" class="fixed inset-0 bg-black/50 z-[60] hidden items-center justify-center backdrop-blur-sm">
        <div class="bg-white w-96 rounded-[32px] p-8 space-y-6 shadow-2xl">
            <div class="text-center">
                <div class="w-16 h-16 bg-primary/10 text-primary rounded-full flex items-center justify-center mx-auto mb-4 text-2xl">
                    <i class="fa-solid fa-truck-fast"></i>
                </div>
                <h3 class="text-xl font-bold">Update Status Pesanan</h3>
                <p class="text-xs text-gray-400 font-medium">Ubah status pengiriman barang ke customer.</p>
            </div>
            
            <!-- FORM DINAMIS -->
            <form id="updateStatusForm" method="POST" action="">
                @csrf
                @method('PATCH')
                <div class="space-y-2">
                    <!-- Tombol Set Status Shipped -->
                    <button type="submit" name="status" value="shipped" class="w-full text-left p-4 rounded-xl border border-gray-100 hover:border-indigo-500 hover:bg-indigo-50 transition-all text-xs font-bold text-gray-700">
                        <i class="fa-solid fa-box mr-2"></i> Pesanan Dikirim (Shipped)
                    </button>
                </div>
            </form>

            <button onclick="closeStatusModal()" class="w-full py-2 text-[10px] font-black uppercase tracking-widest text-gray-400 hover:text-gray-600 transition-colors">Batal</button>
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

        function openStatusModal(url) {
    const form = document.getElementById('updateStatusForm');
    
    // Langsung masukkan URL yang kita ambil dari tombol tadi
    form.action = url; 
    
    document.getElementById('statusModal').classList.remove('hidden');
    document.getElementById('statusModal').classList.add('flex');
}

        function closeStatusModal() {
            document.getElementById('statusModal').classList.add('hidden');
            document.getElementById('statusModal').classList.remove('flex');
        }

        // Close modal when clicking outside
        window.onclick = function(event) {
            const modal = document.getElementById('statusModal');
            if (event.target == modal) closeStatusModal();
        }
    </script>
</body>
</html>