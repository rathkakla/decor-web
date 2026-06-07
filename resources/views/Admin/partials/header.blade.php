<header class="h-16 bg-primary flex items-center justify-between px-8 sticky top-0 z-40 shadow-sm">
    <div class="flex items-center">
        <button id="toggle-sidebar" class="text-white hover:opacity-80 mr-4 flex items-center justify-center transition-transform active:scale-95">
            <i class="fa-solid fa-bars-staggered text-xl"></i>
        </button>
        <h2 class="font-bold text-xs uppercase tracking-widest text-white leading-none">{{ $title ?? 'Admin Portal' }}</h2>
    </div>

    <div class="flex items-center space-x-6 text-white">
        @isset($extra_action)
            {!! $extra_action !!}
        @endisset

        <a href="{{ route('admin.dashboard') }}" class="relative hover:opacity-80 transition-all">
            <i class="fa-solid fa-bell text-xl"></i>
        </a>
        <p class="text-[10px] font-bold uppercase tracking-widest hidden md:block">{{ Auth::user()->full_name }}</p>
        <img src="{{ Auth::user()->avatar_url ?? 'https://ui-avatars.com/api/?name='.urlencode(Auth::user()->full_name).'&background=fff&color=B5733A' }}" class="w-9 h-9 rounded-lg border-2 border-white/20 object-cover">
    </div>
</header>