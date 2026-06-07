<aside class="w-72 border-r border-gray-50 p-10 bg-gray-50/20 shrink-0 sticky top-[73px] self-start h-[calc(100vh-73px)] overflow-y-auto no-scrollbar">
    <div class="text-center mb-10 group">
        <div class="relative inline-block">
            <img src="{{ Auth::user()->avatar_url }}" 
                 class="w-20 h-20 rounded-2xl mx-auto mb-4 bg-white shadow-sm border border-gray-100 object-cover">
            
            <button onclick="document.getElementById('avatar-input').click()" 
                    class="absolute -bottom-1 -right-1 w-7 h-7 bg-primary text-white rounded-lg shadow-lg flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity">
                <i class="fa-solid fa-pencil text-[10px]"></i>
            </button>
        </div>
        <h3 class="font-bold text-lg">{{ Auth::user()->full_name }}</h3>
        <p class="text-[9px] text-gray-400 uppercase tracking-widest mt-1">Member since {{ Auth::user()->created_at->format('Y') }}</p>

        {{-- Form Tersembunyi untuk Upload --}}
        <form id="avatar-form" action="{{ route('customer.profile.update-avatar') }}" method="POST" enctype="multipart/form-data" class="hidden">
            @csrf
            <input type="file" name="profile_image" id="avatar-input" accept="image/*" onchange="document.getElementById('avatar-form').submit()">
        </form>
    </div>

    <nav class="space-y-1">
        <a href="{{ route('customer.profile') }}" class="flex items-center space-x-4 px-4 py-3 {{ request()->routeIs('customer.profile') ? 'bg-white text-primary font-bold rounded-xl shadow-sm border border-gray-100' : 'text-gray-400 hover:text-primary transition-colors' }}">
            <i class="fa-regular fa-user text-xs"></i> <span class="text-[11px] uppercase tracking-widest">Profile</span>
        </a>
        <a href="{{ route('customer.orders') }}" class="flex items-center space-x-4 px-4 py-3 {{ request()->routeIs('customer.orders') ? 'bg-white text-primary font-bold rounded-xl shadow-sm border border-gray-100' : 'text-gray-400 hover:text-primary transition-colors' }}">
            <i class="fa-solid fa-box-archive text-xs"></i> <span class="text-[11px] uppercase tracking-widest">Orders</span>
        </a>
        <a href="{{ route('customer.my-consultations') }}" class="flex items-center space-x-4 px-4 py-3 {{ request()->routeIs('customer.my-consultations') ? 'bg-white text-primary font-bold rounded-xl shadow-sm border border-gray-100' : 'text-gray-400 hover:text-primary transition-colors' }}">
            <i class="fa-solid fa-wand-magic-sparkles text-xs"></i> <span class="text-[11px] uppercase tracking-widest">Consultations</span>
        </a>
        <a href="{{ route('customer.track-consultation.list') }}" class="flex items-center space-x-4 px-4 py-3 {{ request()->routeIs('customer.track-consultation.list') ? 'bg-white text-primary font-bold rounded-xl shadow-sm border border-gray-100' : 'text-gray-400 hover:text-primary transition-colors' }}">
            <i class="fa-solid fa-list-check"></i> <span class="text-[11px] uppercase tracking-widest">Track Consultation</span>
        </a>
        <a href="{{ route('customer.return-request') }}" class="flex items-center space-x-4 px-4 py-3 {{ request()->routeIs('customer.return-request') || request()->routeIs('customer.return-detail') ? 'bg-white text-primary font-bold rounded-xl shadow-sm border border-gray-100' : 'text-gray-400 hover:text-primary transition-colors' }}">
            <i class="fa-solid fa-rotate-left text-xs"></i> <span class="text-[11px] uppercase tracking-widest">Returns</span>
        </a>
        <a href="{{ route('customer.product-favorite') }}" class="flex items-center space-x-4 px-4 py-3 {{ request()->routeIs('customer.product-favorite') ? 'bg-white text-primary font-bold rounded-xl shadow-sm border border-gray-100' : 'text-gray-400 hover:text-primary transition-colors' }}">
            <i class="fa-regular fa-heart text-xs"></i>
            <span class="text-[11px] uppercase tracking-widest">Product Favorite</span>
        </a>
        <div class="pt-6 mt-6 border-t border-gray-100">
            <p class="px-4 text-[9px] font-black text-gray-300 uppercase tracking-widest mb-2">Chat History</p>
            <a href="{{ route('customer.chat-seller.with') }}" class="flex items-center space-x-4 px-4 py-3 rounded-xl {{ request()->is('customer/chat-seller*') || request()->is('customer/riwayat-chat*') ? 'bg-white text-primary font-bold shadow-sm border border-gray-100' : 'text-gray-400 hover:text-primary' }}">
                <i class="fa-solid fa-shop text-xs"></i> 
                <span class="text-[11px] uppercase tracking-widest">Seller</span>
            </a>
        </div>
    </nav>
</aside>


