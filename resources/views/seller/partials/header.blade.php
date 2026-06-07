<header class="h-16 bg-primary flex items-center justify-between px-8 sticky top-0 z-40 shadow-sm">
    <div class="flex items-center">
        <button id="toggle-sidebar" class="text-white hover:opacity-80 mr-4 flex items-center justify-center transition-transform active:scale-95">
            <i class="fa-solid fa-bars-staggered text-xl"></i>
        </button>
        <h2 class="font-bold text-xs uppercase tracking-widest text-white leading-none">{{ $title ?? 'Seller Portal' }}</h2>
    </div>

    <div class="flex items-center space-x-6 text-white">
        @isset($extra_action)
            {!! $extra_action !!}
        @endisset

        <a href="{{ route('notifications') }}" class="relative hover:opacity-80 transition-all">
            <i class="fa-solid fa-bell text-xl"></i>
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
                <span class="absolute -top-1 -right-1 bg-white text-primary text-[8px] font-bold px-1 rounded-full border border-primary">
                    {{ $newOrdersCount }}
                </span>
            @endif
        </a>
        <p class="text-[10px] font-bold uppercase tracking-widest hidden md:block">{{ Auth::user()->full_name }}</p>
        <img src="{{ Auth::user()->avatar_url ?? 'https://ui-avatars.com/api/?name='.urlencode(Auth::user()->full_name).'&background=fff&color=B5733A' }}" class="w-9 h-9 rounded-lg border-2 border-white/20 object-cover">
    </div>
</header>