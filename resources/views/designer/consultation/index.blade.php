<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Decor Designer - Riwayat Konsultasi</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&display=swap');
        body { background-color: #F8F6F4; font-family: 'Plus Jakarta Sans', sans-serif; overflow-x: hidden; }
        .bg-primary { background-color: #B5733A; }
        .text-primary { color: #B5733A; }
        .active-link { background-color: rgba(181, 115, 58, 0.1); color: #B5733A; border-right: 4px solid #B5733A; }
        ::-webkit-scrollbar { width: 5px; }
        ::-webkit-scrollbar-thumb { background: #B5733A; border-radius: 10px; }
        .sidebar-transition { transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1); }
        .sidebar-closed { transform: translateX(-100%); }
        .main-full { margin-left: 0 !important; }
    </style>
</head>
<body class="text-gray-800">

    @include('designer.partials.sidebar')

    <main id="main-content" class="ml-64 flex flex-col min-h-screen sidebar-transition">
        <header class="h-16 bg-primary flex items-center justify-between px-8 sticky top-0 z-40 shadow-sm text-white">
            <div class="flex items-center">
                <i onclick="toggleSidebar()" class="fa-solid fa-bars-staggered text-xl mr-4 cursor-pointer hover:scale-110 transition-transform"></i>
                <h2 class="font-bold text-[10px] uppercase tracking-widest leading-none">Consultations</h2>
            </div>
            <div class="flex items-center space-x-6">
                <i class="fa-regular fa-bell text-xl"></i>
                <div class="flex items-center space-x-3 bg-white/10 px-3 py-1.5 rounded-xl border border-white/20">
                    <span class="text-[10px] font-black uppercase tracking-widest">{{ Auth::user()->full_name }}</span>
                    <div class="w-8 h-8 rounded-lg bg-white text-primary flex items-center justify-center font-bold font-sans">
                        {{ strtoupper(substr(Auth::user()->full_name, 0, 2)) }}
                    </div>
                </div>
            </div>
        </header>

        <div class="p-10 space-y-10 flex-1">
            <div class="flex flex-col md:flex-row justify-between items-start md:items-end gap-6">
                <div>
                    <h2 class="text-4xl font-black tracking-tight text-gray-900 uppercase leading-none">Riwayat Konsultasi</h2>
                    <p class="text-xs text-gray-400 mt-2 font-medium italic">Your curated archive of intellectual luxury projects.</p>
                </div>
                <div class="relative w-full md:w-72">
                    <i class="fa-solid fa-magnifying-glass absolute left-4 top-1/2 -translate-y-1/2 text-gray-300 text-xs"></i>
                    <input type="text" placeholder="Search..." class="w-full bg-white border border-gray-100 rounded-2xl py-3.5 pl-11 pr-6 text-[10px] font-black uppercase tracking-widest outline-none focus:ring-2 focus:ring-primary/10 shadow-sm transition-all">
                </div>
            </div>

            @php
                $tab = request('tab', 'needs_action');
                
                $needsActionCount = $consultations->whereIn('status', [0, 3, 5])->count();
                $onProgressCount = $consultations->whereIn('status', [1, 2, 7, 8, 9])->count();
                $completedCount = $consultations->where('status', 4)->count();

                $filteredConsultations = $consultations->filter(function($item) use ($tab) {
                    if ($tab == 'needs_action') return in_array($item->status, [0, 3, 5]);
                    if ($tab == 'on_progress') return in_array($item->status, [1, 2, 7, 8, 9]);
                    if ($tab == 'completed') return $item->status == 4;
                    return true;
                });
            @endphp

            <div class="flex items-center space-x-4 border-b border-gray-100 pb-4">
                <a href="{{ route('designer.consultations.index', ['tab' => 'needs_action']) }}" 
                   class="px-6 py-3 rounded-2xl text-[10px] font-black uppercase tracking-widest transition-all flex items-center gap-2 {{ $tab == 'needs_action' ? 'bg-primary text-white shadow-lg shadow-primary/20' : 'bg-white text-gray-400 border border-gray-50' }}">
                    Needs Action
                    @if($needsActionCount > 0)
                        <span class="{{ $tab == 'needs_action' ? 'bg-white text-primary' : 'bg-primary text-white' }} px-2 py-0.5 rounded-full text-[9px]">{{ $needsActionCount }}</span>
                    @endif
                </a>
                <a href="{{ route('designer.consultations.index', ['tab' => 'on_progress']) }}" 
                   class="px-6 py-3 rounded-2xl text-[10px] font-black uppercase tracking-widest transition-all flex items-center gap-2 {{ $tab == 'on_progress' ? 'bg-primary text-white shadow-lg shadow-primary/20' : 'bg-white text-gray-400 border border-gray-50' }}">
                    On-Progress
                    @if($onProgressCount > 0)
                        <span class="{{ $tab == 'on_progress' ? 'bg-white text-primary' : 'bg-primary text-white' }} px-2 py-0.5 rounded-full text-[9px]">{{ $onProgressCount }}</span>
                    @endif
                </a>
                <a href="{{ route('designer.consultations.index', ['tab' => 'completed']) }}" 
                   class="px-6 py-3 rounded-2xl text-[10px] font-black uppercase tracking-widest transition-all flex items-center gap-2 {{ $tab == 'completed' ? 'bg-primary text-white shadow-lg shadow-primary/20' : 'bg-white text-gray-400 border border-gray-50' }}">
                    Completed
                    @if($completedCount > 0)
                        <span class="{{ $tab == 'completed' ? 'bg-white text-primary' : 'bg-primary text-white' }} px-2 py-0.5 rounded-full text-[9px]">{{ $completedCount }}</span>
                    @endif
                </a>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 pb-20">
                @forelse($filteredConsultations as $consultation)
                <div class="bg-white rounded-[48px] border border-gray-100 overflow-hidden shadow-sm group hover:shadow-2xl transition-all duration-700 flex flex-col">
                    <div class="h-64 relative overflow-hidden">
                        <img src="{{ $consultation->cover_image ?? 'https://images.unsplash.com/photo-1616486338812-3dadae4b4ace?q=80&w=1200' }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-1000">
                        <div class="absolute top-6 left-6 flex space-x-2">
                            <span class="bg-white/90 backdrop-blur-md text-gray-900 px-4 py-1.5 rounded-full text-[9px] font-black uppercase tracking-widest shadow-sm">Consultation</span>
                            @php
                                $statusColors = [
                                    0 => 'bg-yellow-50 text-yellow-600', // Waiting for Brief
                                    1 => 'bg-blue-50 text-blue-600',   // Drafting
                                    2 => 'bg-purple-50 text-purple-600', // Under Review
                                    3 => 'bg-red-50 text-red-600',    // Revision Requested
                                    4 => 'bg-green-50 text-green-600',  // Completed
                                    5 => 'bg-gray-100 text-gray-600',   // Pending Approval
                                    6 => 'bg-red-100 text-red-700',     // Rejected
                                    7 => 'bg-amber-100 text-amber-700',  // Waiting Payment
                                    8 => 'bg-indigo-50 text-indigo-600', // Offer Received
                                    9 => 'bg-emerald-50 text-emerald-600', // Waiting Final Payment
                                ];
                                $color = $statusColors[$consultation->status] ?? 'bg-gray-50 text-gray-600';
                            @endphp
                            <span class="{{ $color }} px-4 py-1.5 rounded-full text-[9px] font-black uppercase tracking-widest shadow-sm">
                                {{ strtoupper(App\Models\Consultation::getStatusLabel($consultation->status)) }}
                            </span>
                        </div>
                    </div>
                    <div class="p-10 flex flex-col flex-1 justify-between">
                        <div class="flex justify-between items-start">
                            <div>
                                <h3 class="text-2xl font-black text-gray-900 tracking-tight leading-none">{{ $consultation->customer->user->full_name }}</h3>
                                <p class="text-[11px] font-bold text-gray-400 uppercase tracking-widest mt-2">{{ $consultation->title }}</p>
                            </div>
                            <div class="text-right">
                                <p class="text-sm font-black text-primary">{{ $consultation->budget_range }}</p>
                            </div>
                        </div>
                        <div class="flex justify-between items-center pt-8 mt-8 border-t border-gray-50">
                            <p class="text-[9px] font-black text-gray-300 uppercase tracking-widest italic">Order ID: #DEC-{{ str_pad($consultation->id, 5, '0', STR_PAD_LEFT) }}</p>
                            <a href="{{ route('designer.consultations.show', $consultation->id) }}" class="text-[10px] font-black uppercase tracking-[0.2em] text-primary hover:mr-2 transition-all flex items-center">View Project Details <i class="fa-solid fa-arrow-right ml-2"></i></a>
                        </div>
                    </div>
                </div>
                @empty
                <div class="col-span-full py-20 text-center">
                    <i class="fa-solid fa-folder-open text-6xl text-gray-100 mb-4"></i>
                    <p class="text-xs font-black text-gray-300 uppercase tracking-widest">No consultations found in this category.</p>
                </div>
                @endforelse
            </div>
        </div>

        <footer class="p-8 border-t border-gray-100 text-[9px] font-black text-gray-400 uppercase tracking-widest bg-white mt-auto text-center">
            Â© 2026 DECOR DESIGNER PORTAL. ALL RIGHTS RESERVED.
        </footer>
    </main>

    <script>
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            const mainContent = document.getElementById('main-content');
            sidebar.classList.toggle('sidebar-closed');
            mainContent.classList.toggle('main-full');
        }
    </script>
</body>
</html>