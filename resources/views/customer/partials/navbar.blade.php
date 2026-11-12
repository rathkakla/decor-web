<header class="bg-white border-b border-gray-100 sticky top-0 z-50">
    <div class="content-container flex justify-between items-center py-4 px-6 mx-auto max-w-[1200px]">
        <div class="flex items-center space-x-8 flex-1">
            <a href="{{ route('homepage') }}" class="text-2xl font-black tracking-tighter uppercase text-primary hover:opacity-80 transition-all">
                DECOR
            </a>
            <form action="{{ route('customer.catalog') }}" method="GET" class="hidden lg:flex items-center bg-gray-50 border border-gray-100 rounded-md px-4 py-2 w-full max-w-[180px] group focus-within:bg-white focus-within:border-primary/30 transition-all">
                <i class="fa-solid fa-magnifying-glass text-gray-400 text-[10px] mr-2"></i>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search..." class="bg-transparent border-none outline-none text-[10px] w-full placeholder:text-gray-400">
            </form>
        </div>

        <nav class="hidden md:flex items-center space-x-10 text-[13px] font-medium text-gray-500 tracking-wide">
            <a href="{{ route('customer.catalog') }}" class="hover:text-primary transition-all">Collections</a>
            <a href="{{ route('customer.designers') }}" class="hover:text-primary transition-all">Designers</a>
            <a href="{{ route('customer.design-lab') }}" class="hover:text-primary transition-all">AI Studio</a>
        </nav>

        <div class="flex items-center space-x-6 flex-1 justify-end">
            @auth
                <div class="flex items-center gap-4 border-r pr-6 border-gray-100">
                    <div class="text-right hidden sm:block">
                        <p class="text-[9px] uppercase tracking-widest text-gray-400 font-bold leading-none mb-1">Welcome back</p>
                        <p class="text-xs font-bold text-primary capitalize">{{ Auth::user()->full_name }}</p>
                    </div>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="text-gray-400 hover:text-red-500 transition-colors" title="Logout">
                            <i class="fa-solid fa-power-off text-sm"></i>
                        </button>
                    </form>
                </div>
                <a href="{{ route('customer.cart') }}" class="text-primary hover:scale-110 transition-transform">
                    <i class="fa-solid fa-bag-shopping text-lg"></i>
                </a>
                <div class="w-9 h-9 rounded-md overflow-hidden border border-gray-200 cursor-pointer hover:border-primary transition-all">
                    <a href="{{ route('customer.profile') }}" class="block w-full h-full">
                        <img src="{{ Auth::user()->avatar_url }}" class="w-full h-full bg-slate-100 object-cover">
                    </a>
                </div>
            @endauth
            @guest
                <a href="{{ route('login') }}" class="text-[11px] font-bold text-gray-600 uppercase tracking-widest hover:text-primary transition-colors">Login</a>
                <a href="{{ route('role.selection') }}" class="text-[11px] font-bold text-white uppercase tracking-widest bg-primary px-5 py-2.5 rounded-full hover:bg-opacity-90 transition-all shadow-md shadow-primary/20">Join Us</a>
            @endguest
        </div>
    </div>
</header>