<aside id="sidebar" class="w-64 bg-white border-r border-gray-200 flex flex-col fixed h-full z-50 sidebar-transition">
    <div class="p-8">
        <h1 class="text-2xl font-bold tracking-widest text-primary uppercase leading-none">DECOR</h1>
        <p class="text-[10px] text-gray-400 mt-1 uppercase tracking-widest italic font-bold">Designer Portal</p>
    </div>

    <nav class="flex-1 px-4 space-y-1 overflow-y-auto">
        <a href="{{ route('designer.dashboard') }}" class="flex items-center px-4 py-3 text-xs font-bold transition-all rounded-lg {{ request()->routeIs('designer.dashboard') ? 'active-link' : 'text-gray-400 hover:text-primary' }}">
            <i class="fa-solid fa-table-columns mr-3 w-5 text-center"></i> Dashboard
        </a>
        <a href="{{ route('designer.portfolio.index') }}" class="flex items-center px-4 py-3 text-xs font-bold transition-all rounded-lg {{ request()->routeIs('designer.portfolio.*') ? 'active-link' : 'text-gray-400 hover:text-primary' }}">
            <i class="fa-solid fa-briefcase mr-3 w-5 text-center"></i> Kelola Portofolio
        </a>
        <a href="{{ route('designer.consultations.index') }}" class="flex items-center px-4 py-3 text-xs font-bold transition-all rounded-lg {{ request()->routeIs('designer.consultations.*') ? 'active-link' : 'text-gray-400 hover:text-primary' }}">
            <i class="fa-solid fa-calendar-check mr-3 w-5 text-center"></i> 
            <span class="flex-1">Konsultasi</span>
            @php
                $sidebarDesigner = \App\Models\Designer::where('user_id', Auth::id())->first();
                $sidebarConsultationNotif = 0;
                if ($sidebarDesigner) {
                    $sidebarConsultationNotif = \App\Models\Consultation::where('designer_id', $sidebarDesigner->id)
                        ->whereIn('status', [0, 3, 5]) // Needs action statuses
                        ->count();
                }
            @endphp
            @if($sidebarConsultationNotif > 0)
                <span class="bg-primary text-white text-[10px] font-black px-2 py-0.5 rounded-full">{{ $sidebarConsultationNotif }}</span>
            @endif
        </a>
        <a href="{{ route('designer.chats') }}" class="flex items-center px-4 py-3 text-xs font-bold transition-all rounded-lg {{ request()->routeIs('designer.chats') ? 'active-link' : 'text-gray-400 hover:text-primary' }}">
            <i class="fa-solid fa-comment-dots mr-3 w-5 text-center"></i> Chat
        </a>
        <a href="{{ route('designer.reviews') }}" class="flex items-center px-4 py-3 text-xs font-bold transition-all rounded-lg {{ request()->routeIs('designer.reviews') ? 'active-link' : 'text-gray-400 hover:text-primary' }}">
            <i class="fa-solid fa-star mr-3 w-5 text-center"></i> Review & Rating
        </a>
        <a href="{{ route('designer.reports') }}" class="flex items-center px-4 py-3 text-xs font-bold transition-all rounded-lg {{ request()->routeIs('designer.reports') ? 'active-link' : 'text-gray-400 hover:text-primary' }}">
            <i class="fa-solid fa-chart-line mr-3 w-5 text-center"></i> Laporan
        </a>
    </nav>

    <div class="p-4 border-t border-gray-100 space-y-1">
        <a href="{{ route('designer.settings') }}" class="flex items-center px-4 py-3 text-xs font-bold transition-all rounded-lg {{ request()->routeIs('designer.settings') ? 'active-link' : 'text-gray-400 hover:text-primary' }}">
            <i class="fa-solid fa-gear mr-3 w-5 text-center"></i> Settings
        </a>
        <a href="{{ route('designer.support') }}" class="flex items-center px-4 py-3 text-xs font-bold transition-all rounded-lg {{ request()->routeIs('designer.support') ? 'active-link' : 'text-gray-400 hover:text-primary' }}">
            <i class="fa-solid fa-circle-question mr-3 w-5 text-center"></i> Support
        </a>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="w-full flex items-center px-4 py-3 text-xs font-bold text-gray-400 hover:text-red-500 transition-all rounded-lg">
                <i class="fa-solid fa-right-from-bracket mr-3 w-5 text-center"></i> Logout
            </button>
        </form>
    </div>
</aside>