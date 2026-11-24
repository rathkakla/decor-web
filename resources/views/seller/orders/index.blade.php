<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Decor Seller - Daftar Pesanan</title>
    <script src="https://cdn.tailwindcss.com"></script>
    @include('seller.partials.head')
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

    <div class="p-4 sm:p-6 lg:p-8 space-y-6 sm:space-y-8 flex-1">
        <!-- ALERT PESAN SUKSES -->
        @if(session('success'))
            <div class="bg-green-50 text-green-600 p-4 rounded-xl text-sm font-bold border border-green-100 flex items-center">
                <i class="fa-solid fa-circle-check mr-3 text-lg"></i> {{ session('success') }}
            </div>
        @endif

        <div class="flex flex-col sm:flex-row sm:items-end justify-between gap-4">
            <div>
                <div class="text-[10px] font-bold text-primary uppercase tracking-widest mb-1">Order Management</div>
                <h2 class="text-2xl font-bold text-gray-900 tracking-tight">Daftar Pesanan</h2>
                <p class="text-xs text-gray-500 mt-1">Kelola dan pantau pesanan customer dengan rapi.</p>
            </div>
            <form action="{{ route('seller.orders') }}" method="GET" class="relative w-full sm:w-64 lg:w-72">
                @if($currentStatus !== 'all')
                    <input type="hidden" name="status" value="{{ $currentStatus }}">
                @endif
                <i class="fa-solid fa-magnifying-glass absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-xs pointer-events-none"></i>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari pesanan..." class="w-full h-10 bg-white border border-gray-200 rounded-lg pl-9 pr-8 text-xs font-medium text-gray-700 focus:outline-none focus:border-primary focus:ring-2 focus:ring-primary/10 transition-all">
                @if(request('search'))
                    <a href="{{ route('seller.orders', ['status' => $currentStatus]) }}" class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-red-500">
                        <i class="fa-solid fa-xmark text-xs"></i>
                    </a>
                @endif
            </form>
        </div>

        <!-- NAVIGASI FILTER -->
        <div class="flex space-x-8 border-b border-gray-200 text-[10px] font-bold uppercase tracking-widest text-gray-400 overflow-x-auto whitespace-nowrap hide-scrollbar">
            <a href="{{ route('seller.orders', ['status' => 'all']) }}" class="pb-4 transition-colors {{ $currentStatus == 'all' ? 'border-b-2 border-primary text-primary' : 'hover:text-gray-800' }}">Semua</a>
            <a href="{{ route('seller.orders', ['status' => 'pending']) }}" class="pb-4 transition-colors {{ $currentStatus == 'pending' ? 'border-b-2 border-primary text-primary' : 'hover:text-gray-800' }}">Pending</a>
            <a href="{{ route('seller.orders', ['status' => 'waiting_verification']) }}" class="pb-4 transition-colors {{ $currentStatus == 'waiting_verification' ? 'border-b-2 border-primary text-primary' : 'hover:text-gray-800' }}">Menunggu Verifikasi</a>
            <a href="{{ route('seller.orders', ['status' => 'paid']) }}" class="pb-4 transition-colors {{ $currentStatus == 'paid' ? 'border-b-2 border-primary text-primary' : 'hover:text-gray-800' }}">Paid</a>
            <a href="{{ route('seller.orders', ['status' => 'shipped']) }}" class="pb-4 transition-colors {{ $currentStatus == 'shipped' ? 'border-b-2 border-primary text-primary' : 'hover:text-gray-800' }}">Shipped</a>
            <a href="{{ route('seller.orders', ['status' => 'completed']) }}" class="pb-4 transition-colors {{ $currentStatus == 'completed' ? 'border-b-2 border-primary text-primary' : 'hover:text-gray-800' }}">Completed</a>
            <a href="{{ route('seller.orders', ['status' => 'cancelled']) }}" class="pb-4 transition-colors {{ $currentStatus == 'cancelled' ? 'border-b-2 border-primary text-primary' : 'hover:text-gray-800' }}">Cancelled</a>
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
                $statusLabel = match($order->status) {
                    'pending' => 'Pending',
                    'waiting_verification' => 'Menunggu Verifikasi',
                    'paid' => 'Dibayar',
                    'shipped' => 'Dikirim',
                    'completed' => 'Selesai',
                    'cancelled' => 'Dibatalkan',
                    default => ucfirst(str_replace('_', ' ', $order->status)),
                };
                $statusAccent = match($order->status) {
                    'pending' => 'border-l-yellow-400',
                    'waiting_verification' => 'border-l-orange-400',
                    'paid' => 'border-l-blue-400',
                    'shipped' => 'border-l-indigo-400',
                    'completed' => 'border-l-green-500',
                    'cancelled' => 'border-l-red-400',
                    default => 'border-l-gray-300',
                };
                $orderNumber = $order->return_code
                    ? '#DEC-' . str_replace('RET-', '', $order->return_code) . '-RET'
                    : '#ORD-' . $order->id;
                $neutralIconBtn = 'w-9 h-9 rounded-lg border border-gray-200 bg-gray-50 text-gray-500 hover:bg-gray-100 hover:text-gray-800 inline-flex items-center justify-center transition-all';
                $primaryIconBtn = 'w-9 h-9 rounded-lg border border-primary/20 bg-primary/10 text-primary hover:bg-primary hover:text-white inline-flex items-center justify-center transition-all';
                $orangeIconBtn = 'w-9 h-9 rounded-lg border border-orange-200 bg-orange-50 text-orange-500 hover:bg-orange-500 hover:text-white inline-flex items-center justify-center transition-all';
                $tooltipClass = 'pointer-events-none absolute right-full top-1/2 -translate-y-1/2 mr-2 whitespace-nowrap rounded-md bg-gray-900 px-2 py-1 text-[10px] font-semibold text-white opacity-0 transition-opacity group-hover/action:opacity-100 group-focus-within/action:opacity-100 z-20 shadow-sm';
            @endphp

            <div class="bg-white p-5 sm:p-6 rounded-xl border border-gray-100 border-l-4 {{ $statusAccent }} shadow-sm hover:shadow-md transition-shadow flex flex-col lg:flex-row lg:items-center lg:justify-between gap-5 group">
                <div class="flex items-start gap-4 sm:gap-6 min-w-0">
                    <div class="relative shrink-0">
                        <img src="{{ $order->orderItems->first()->product->images->first()->img_url ?? 'https://via.placeholder.com/150' }}" class="w-14 h-14 sm:w-16 sm:h-16 rounded-xl object-cover grayscale group-hover:grayscale-0 transition-all border border-gray-100">
                        @if($order->status == 'completed')
                        <div class="absolute -bottom-2 -right-2 bg-white p-1 rounded-lg shadow-sm">
                            <i class="fa-solid fa-circle-check text-[10px] text-green-500"></i>
                        </div>
                        @endif
                    </div>

                    <div class="space-y-1 min-w-0">
                        <div class="flex flex-wrap items-center gap-2 sm:gap-3">
                            <span class="text-sm font-bold text-gray-700">{{ $orderNumber }}</span>
                            <span class="{{ $badgeClass }} text-[8px] font-bold px-2 py-0.5 rounded tracking-widest uppercase whitespace-nowrap">{{ $statusLabel }}</span>
                        </div>
                        <h3 class="text-base sm:text-lg font-bold text-gray-800 truncate">{{ $order->customer->user->full_name ?? 'Customer' }}</h3>
                        <div class="flex flex-wrap items-center gap-x-4 gap-y-1 text-[10px] text-gray-400 font-bold uppercase tracking-tight">
                            <span><i class="fa-regular fa-calendar mr-1"></i> {{ $order->created_at->format('d M Y') }}</span>
                            <span><i class="fa-solid fa-box mr-1"></i> {{ $order->orderItems->sum('quantity') }} Items</span>
                        </div>
                    </div>
                </div>

                <div class="flex flex-col sm:flex-row sm:items-center lg:flex-col lg:items-end justify-between gap-4 lg:gap-3">
                    <div class="sm:text-right">
                        <p class="text-[8px] text-gray-400 font-bold uppercase tracking-widest sm:text-right">Total Amount</p>
                        <p class="text-xl font-bold leading-none mt-1 text-gray-800">Rp {{ number_format($order->total_price, 0, ',', '.') }}</p>
                    </div>
                    <div class="flex flex-wrap items-center gap-2 lg:justify-end">
                        @if($order->status == 'waiting_verification')
                            <div class="relative group/action">
                                <a href="{{ route('seller.orders.show', $order->id) }}" class="{{ $orangeIconBtn }}" aria-label="Validasi Pembayaran" title="Validasi Pembayaran">
                                    <i class="fa-solid fa-file-invoice-dollar text-xs"></i>
                                </a>
                                <span class="{{ $tooltipClass }}">Validasi Pembayaran</span>
                            </div>
                        @elseif($order->status == 'paid')
                            <div class="relative group/action">
                                <button type="button" onclick="openStatusModal('{{ route('seller.orders.update-status', $order->id) }}')" class="{{ $primaryIconBtn }}" aria-label="Proses & Kirim" title="Proses & Kirim">
                                    <i class="fa-solid fa-truck-fast text-xs"></i>
                                </button>
                                <span class="{{ $tooltipClass }}">Proses & Kirim</span>
                            </div>
                        @elseif($order->status == 'shipped')
                            <div class="relative group/action">
                                <button type="button" onclick="openStatusModal('{{ route('seller.orders.update-status', $order->id) }}')" class="{{ $primaryIconBtn }}" aria-label="Update Status" title="Update Status">
                                    <i class="fa-solid fa-rotate text-xs"></i>
                                </button>
                                <span class="{{ $tooltipClass }}">Update Status</span>
                            </div>
                        @endif
                        <div class="relative group/action">
                            <a href="{{ route('invoice.download', $order->id) }}" target="_blank" class="{{ $neutralIconBtn }}" aria-label="Invoice" title="Invoice">
                                <i class="fa-solid fa-file-invoice text-xs"></i>
                            </a>
                            <span class="{{ $tooltipClass }}">Invoice</span>
                        </div>
                        <div class="relative group/action">
                            <a href="{{ route('seller.orders.show', $order->id) }}" class="{{ $neutralIconBtn }}" aria-label="Detail" title="Detail">
                                <i class="fa-solid fa-eye text-xs"></i>
                            </a>
                            <span class="{{ $tooltipClass }}">Detail</span>
                        </div>
                    </div>
                </div>
            </div>
            @empty
                <div class="text-center py-20 bg-white rounded-xl border-2 border-dashed border-gray-100">
                    <p class="text-gray-400 font-bold">Belum ada pesanan masuk.</p>
                </div>
            @endforelse
            @include('partials.pagination', ['paginator' => $orders, 'label' => 'pesanan', 'standalone' => true])
        </div>
    </div>
</main>

    <!-- MODAL UPDATE STATUS -->
    <div id="statusModal" class="fixed inset-0 bg-black/50 z-[60] hidden items-center justify-center backdrop-blur-sm">
        <div class="bg-white w-96 rounded-xl p-8 space-y-6 shadow-2xl">
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