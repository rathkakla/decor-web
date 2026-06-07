<aside id="sidebar" class="w-64 bg-white border-r border-gray-200 flex flex-col fixed h-full z-50 sidebar-transition">
    <div class="p-8">
        <h1 class="text-2xl font-bold tracking-widest text-primary uppercase leading-none">DECOR</h1>
        <p class="text-[10px] text-gray-400 mt-1 uppercase tracking-widest">Seller Portal</p>
    </div>
    
    <!-- MENU UTAMA (ATAS) -->
    <nav class="flex-1 px-4 space-y-1 overflow-y-auto">
        <a href="{{ route('seller.dashboard') }}" class="flex items-center px-4 py-3 text-xs font-bold transition-all rounded-lg {{ Request::routeIs('seller.dashboard') ? 'active-link' : 'text-gray-400 hover:text-primary hover:bg-gray-50' }}">
            <i class="fa-solid fa-table-columns mr-3 w-5 text-center"></i> Dashboard
        </a>
        <a href="{{ route('seller.products.index') }}" class="flex items-center px-4 py-3 text-xs font-bold transition-all rounded-lg {{ Request::routeIs('seller.products.*') ? 'active-link' : 'text-gray-400 hover:text-primary hover:bg-gray-50' }}">
            <i class="fa-solid fa-couch mr-3 w-5 text-center"></i> Kelola Produk
        </a>
        <a href="{{ route('seller.orders') }}" class="flex items-center justify-between px-4 py-3 text-xs font-bold transition-all rounded-lg {{ Request::routeIs('seller.orders*') || Request::routeIs('orders*') ? 'active-link' : 'text-gray-400 hover:text-primary hover:bg-gray-50' }}">
            <div class="flex items-center">
                <i class="fa-solid fa-bag-shopping mr-3 w-5 text-center"></i> Daftar Pesanan
            </div>
            @php
                $seller = \App\Models\Seller::where('user_id', Auth::id())->first();
                $newOrdersCount = 0;
                if ($seller) {
                    $newOrdersCount = \App\Models\Order::whereHas('orderItems.product', function ($q) use ($seller) {
                        $q->where('seller_id', $seller->id);
                    })->whereIn('status', ['pending', 'waiting_verification'])->count();
                }
            @endphp
            @if($newOrdersCount > 0)
                <span class="bg-primary text-white text-[9px] px-2 py-0.5 rounded-full">{{ $newOrdersCount }}</span>
            @endif
        </a>
        <a href="{{ route('seller.chats') }}" class="flex items-center px-4 py-3 text-xs font-bold transition-all rounded-lg {{ Request::routeIs('seller.chats*') ? 'active-link' : 'text-gray-400 hover:text-primary hover:bg-gray-50' }}">
            <i class="fa-solid fa-message mr-3 w-5 text-center"></i> Seller Chat
        </a>
        <a href="{{ route('seller.complaint.index') }}" class="flex items-center px-4 py-3 text-xs font-bold transition-all rounded-lg {{ Request::routeIs('seller.complaint.*') ? 'active-link' : 'text-gray-400 hover:text-primary hover:bg-gray-50' }}">
            <i class="fa-solid fa-circle-exclamation mr-3 w-5 text-center"></i> Komplain
        </a>
         <!-- Review & Rating -->
        <a href="{{ route('seller.reviews') }}" class="flex items-center px-4 py-3 text-xs font-bold transition-all rounded-lg {{ Request::is('*review*') ? 'active-link' : 'text-gray-400 hover:text-primary hover:bg-gray-50' }}">
            <i class="fa-solid fa-star mr-3 w-5 text-center"></i> Review & Rating
        </a>
        
        <!-- Laporan -->
        <a href="{{ route('seller.reports') }}" class="flex items-center px-4 py-3 text-xs font-bold transition-all rounded-lg {{ Request::is('*report*') ? 'active-link' : 'text-gray-400 hover:text-primary hover:bg-gray-50' }}">
            <i class="fa-solid fa-chart-line mr-3 w-5 text-center"></i> Laporan
        </a>

        <!-- Voucher -->
        <a href="{{ route('seller.vouchers.index') }}" class="flex items-center px-4 py-3 text-xs font-bold transition-all rounded-lg {{ Request::routeIs('seller.vouchers.*') ? 'active-link' : 'text-gray-400 hover:text-primary hover:bg-gray-50' }}">
            <i class="fa-solid fa-ticket mr-3 w-5 text-center"></i> Kelola Voucher
        </a>
    </nav>
    
    <!-- MENU PENGATURAN (BAWAH) -->
    <div class="p-4 border-t border-gray-100 space-y-1 bg-white">
        <!-- Settings -->
        <a href="{{ route('seller.settings') }}" class="flex items-center px-4 py-3 text-xs font-bold transition-all rounded-lg {{ Request::routeIs('seller.settings*') ? 'active-link' : 'text-gray-400 hover:text-primary hover:bg-gray-50' }}">
            <i class="fa-solid fa-gear mr-3 w-5 text-center"></i> Settings
        </a>
        
        <!-- Support -->
        <a href="{{ route('seller.support') }}" class="flex items-center px-4 py-3 text-xs font-bold transition-all rounded-lg {{ Request::routeIs('seller.support*') ? 'active-link' : 'text-gray-400 hover:text-primary hover:bg-gray-50' }}">
            <i class="fa-solid fa-headset mr-3 w-5 text-center"></i> Support
        </a>
        
        <!-- Logout -->
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="w-full flex items-center px-4 py-3 text-xs font-bold text-red-400 hover:text-red-500 hover:bg-red-50 transition-all rounded-lg">
                <i class="fa-solid fa-right-from-bracket mr-3 w-5 text-center"></i> Logout
            </button>
        </form>
    </div>
</aside>