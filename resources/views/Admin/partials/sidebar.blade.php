<aside id="sidebar" class="w-64 bg-white border-r border-gray-200 flex flex-col fixed h-full z-50 sidebar-transition">
    <div class="p-8">
        <h1 class="text-2xl font-bold tracking-widest text-primary uppercase leading-none">DECOR</h1>
        <p class="text-[10px] text-gray-400 mt-1 uppercase tracking-widest">Admin Portal</p>
    </div>
    
    @php
        $customerSupportCount = \App\Models\Support::whereHas('user', fn($q) => $q->where('role', 'customer'))->where('status', 'pending')->count();
        $sellerSupportCount = \App\Models\Support::whereHas('user', fn($q) => $q->where('role', 'seller'))->where('status', 'pending')->count();
        $designerSupportCount = \App\Models\Support::whereHas('user', fn($q) => $q->where('role', 'designer'))->where('status', 'pending')->count();
    @endphp

    <!-- MENU UTAMA (ATAS) -->
    <nav class="flex-1 px-4 space-y-1 overflow-y-auto">
        <a href="{{ route('admin.dashboard') }}" class="flex items-center px-4 py-3 text-xs font-bold transition-all rounded-lg {{ Request::routeIs('admin.dashboard') ? 'active-link' : 'text-gray-400 hover:text-primary hover:bg-gray-50' }}">
            <i class="fa-solid fa-table-columns mr-3 w-5 text-center"></i> Dashboard
        </a>
        <a href="{{ route('admin.user-management') }}" class="flex items-center px-4 py-3 text-xs font-bold transition-all rounded-lg {{ Request::routeIs('admin.user-management') ? 'active-link' : 'text-gray-400 hover:text-primary hover:bg-gray-50' }}">
            <i class="fa-solid fa-users mr-3 w-5 text-center"></i> User Management
        </a>
        <a href="{{ route('admin.account.validation') }}" class="flex items-center px-4 py-3 text-xs font-bold transition-all rounded-lg {{ Request::routeIs('admin.account.validation') ? 'active-link' : 'text-gray-400 hover:text-primary hover:bg-gray-50' }}">
            <i class="fa-solid fa-shield-halved mr-3 w-5 text-center"></i> Account Validation
        </a>
        <a href="{{ route('admin.seller-monitor') }}" class="flex items-center px-4 py-3 text-xs font-bold transition-all rounded-lg {{ Request::routeIs('admin.seller-monitor') ? 'active-link' : 'text-gray-400 hover:text-primary hover:bg-gray-50' }}">
            <i class="fa-solid fa-store mr-3 w-5 text-center"></i> Seller Monitor
        </a>
        <a href="{{ route('admin.designer-monitor') }}" class="flex items-center px-4 py-3 text-xs font-bold transition-all rounded-lg {{ Request::routeIs('admin.designer-monitor') ? 'active-link' : 'text-gray-400 hover:text-primary hover:bg-gray-50' }}">
            <i class="fa-solid fa-palette mr-3 w-5 text-center"></i> Designer Monitor
        </a>
        <a href="{{ route('admin.product.validation') }}" class="flex items-center px-4 py-3 text-xs font-bold transition-all rounded-lg {{ Request::routeIs('admin.product.validation') ? 'active-link' : 'text-gray-400 hover:text-primary hover:bg-gray-50' }}">
            <i class="fa-solid fa-circle-check mr-3 w-5 text-center"></i> Product Validation
        </a>
        <a href="{{ route('admin.portfolio-validation') }}" class="flex items-center px-4 py-3 text-xs font-bold transition-all rounded-lg {{ Request::routeIs('admin.portfolio-validation') ? 'active-link' : 'text-gray-400 hover:text-primary hover:bg-gray-50' }}">
            <i class="fa-solid fa-image mr-3 w-5 text-center"></i> Portfolio Validation
        </a>

        <!-- Support Sections -->
        <span class="block px-4 py-2 mt-4 text-[9px] font-black tracking-widest text-gray-400 uppercase">Support</span>
        
        <a href="{{ route('admin.customer-support') }}" class="flex items-center justify-between px-4 py-3 text-xs font-bold transition-all rounded-lg {{ Request::routeIs('admin.customer-support') ? 'active-link' : 'text-gray-400 hover:text-primary hover:bg-gray-50' }}">
            <div class="flex items-center">
                <i class="fa-solid fa-comments mr-3 w-5 text-center"></i> Customer Support
            </div>
            @if($customerSupportCount > 0)
                <span class="bg-primary text-white text-[9px] px-2 py-0.5 rounded-full">{{ $customerSupportCount }}</span>
            @endif
        </a>
        <a href="{{ route('admin.seller-support') }}" class="flex items-center justify-between px-4 py-3 text-xs font-bold transition-all rounded-lg {{ Request::routeIs('admin.seller-support') ? 'active-link' : 'text-gray-400 hover:text-primary hover:bg-gray-50' }}">
            <div class="flex items-center">
                <i class="fa-solid fa-headset mr-3 w-5 text-center"></i> Seller Support
            </div>
            @if($sellerSupportCount > 0)
                <span class="bg-primary text-white text-[9px] px-2 py-0.5 rounded-full">{{ $sellerSupportCount }}</span>
            @endif
        </a>
        <a href="{{ route('admin.designer-support') }}" class="flex items-center justify-between px-4 py-3 text-xs font-bold transition-all rounded-lg {{ Request::routeIs('admin.designer-support') ? 'active-link' : 'text-gray-400 hover:text-primary hover:bg-gray-50' }}">
            <div class="flex items-center">
                <i class="fa-solid fa-pen-ruler mr-3 w-5 text-center"></i> Designer Support
            </div>
            @if($designerSupportCount > 0)
                <span class="bg-primary text-white text-[9px] px-2 py-0.5 rounded-full">{{ $designerSupportCount }}</span>
            @endif
        </a>
    </nav>
    
    <!-- MENU PENGATURAN (BAWAH) -->
    <div class="p-4 border-t border-gray-100 space-y-1 bg-white">
        <!-- Settings -->
        <a href="{{ route('admin.settings') }}" class="flex items-center px-4 py-3 text-xs font-bold transition-all rounded-lg {{ Request::routeIs('admin.settings*') ? 'active-link' : 'text-gray-400 hover:text-primary hover:bg-gray-50' }}">
            <i class="fa-solid fa-gear mr-3 w-5 text-center"></i> Settings
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