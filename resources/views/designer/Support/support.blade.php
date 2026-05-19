<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Decor Designer - Support Center</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap');
        body { background-color: #F8F6F4; font-family: 'Plus Jakarta Sans', sans-serif; overflow-x: hidden; }
        .bg-primary { background-color: #B5733A; }
        .text-primary { color: #B5733A; }
        .active-link { background-color: rgba(181, 115, 58, 0.1); color: #B5733A; border-right: 4px solid #B5733A; }
        .sidebar-transition { transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1); }
        .sidebar-closed { transform: translateX(-100%); }
        .main-full { margin-left: 0 !important; }
        ::-webkit-scrollbar { width: 5px; }
        ::-webkit-scrollbar-thumb { background: #B5733A; border-radius: 10px; }
    </style>
</head>
<body class="text-gray-800 flex h-screen">

    <!-- 1. SIDEBAR (8 Menus Consistent) -->
    <aside id="sidebar" class="w-64 bg-white border-r border-gray-200 flex flex-col h-full z-50 flex-shrink-0 sidebar-transition fixed">
        <div class="p-8">
            <h1 class="text-2xl font-bold tracking-widest text-primary uppercase leading-none">DECOR</h1>
            <p class="text-[10px] text-gray-400 mt-1 uppercase tracking-widest italic font-bold">Designer Portal</p>
        </div>

        <nav class="flex-1 px-4 space-y-1">
            <a href="{{ route('designer.dashboard') }}" class="flex items-center px-4 py-3 text-xs font-bold text-gray-400 hover:text-primary transition-all rounded-lg">
                <i class="fa-solid fa-table-columns mr-3 w-5 text-center"></i> Dashboard
            </a>
            <a href="{{ route('designer.portfolio.index') }}" class="flex items-center px-4 py-3 text-xs font-bold text-gray-400 hover:text-primary transition-all rounded-lg">
                <i class="fa-solid fa-briefcase mr-3 w-5 text-center"></i> Kelola Portofolio
            </a>
            <a href="{{ route('designer.consultations.index') }}" class="flex items-center px-4 py-3 text-xs font-bold text-gray-400 hover:text-primary transition-all rounded-lg">
                <i class="fa-solid fa-calendar-check mr-3 w-5 text-center"></i>Konsultasi
            </a>
            <a href="{{ route('designer.chats') }}" class="flex items-center px-4 py-3 text-xs font-bold text-gray-400 hover:text-primary transition-all rounded-lg">
                <i class="fa-solid fa-comment-dots mr-3 w-5 text-center"></i>Chat
            </a>
            <a href="{{ route('designer.reviews') }}" class="flex items-center px-4 py-3 text-xs font-bold text-gray-400 hover:text-primary transition-all rounded-lg">
                <i class="fa-solid fa-star mr-3 w-5 text-center"></i> Review & Rating
            </a>
            <a href="{{ route('designer.reports') }}" class="flex items-center px-4 py-3 text-xs font-bold text-gray-400 hover:text-primary transition-all rounded-lg">
                <i class="fa-solid fa-chart-line mr-3 w-5 text-center"></i> Laporan
            </a>
        </nav>

        <div class="p-4 border-t border-gray-100 space-y-1">
            <a href="{{ route('designer.settings') }}" class="flex items-center px-4 py-3 text-xs font-bold text-gray-400 hover:text-primary transition-all rounded-lg">
                <i class="fa-solid fa-gear mr-3 w-5 text-center"></i> Settings
            </a>
            <a href="{{ route('designer.support') }}" class="flex items-center px-4 py-3 text-xs font-bold active-link transition-all rounded-lg">
                <i class="fa-solid fa-circle-question mr-3 w-5 text-center"></i> Support
            </a>
        </div>
    </aside>

    <!-- MAIN AREA -->
    <div id="main-content" class="flex-1 flex flex-col min-w-0 overflow-hidden ml-64 sidebar-transition">
        
        <!-- HEADER (CONSISTENT LUXURY) -->
        <header class="h-16 bg-primary flex items-center justify-between px-8 sticky top-0 z-40 shadow-sm text-white">
            <div class="flex items-center">
                <i onclick="toggleSidebar()" class="fa-solid fa-bars-staggered text-xl mr-4 cursor-pointer hover:scale-110 transition-transform"></i>
                <h2 class="font-black text-[10px] uppercase tracking-[0.3em] leading-none">Support Center</h2>
            </div>
            <div class="flex items-center space-x-6">
                <i class="fa-regular fa-bell text-xl"></i>
                <div class="flex items-center space-x-3 bg-white/10 px-3 py-1.5 rounded-xl border border-white/20">
                    <span class="text-[10px] font-black uppercase tracking-widest">Elena Vance</span>
                    <div class="w-8 h-8 rounded-lg bg-white text-primary flex items-center justify-center font-bold text-xs">EV</div>
                </div>
            </div>
        </header>

        <main class="flex-1 overflow-y-auto p-12 space-y-12 custom-scroll pb-24">
            
            <!-- TITLE & SEARCH AREA -->
            <div class="flex flex-col md:flex-row justify-between items-end gap-6">
                <div class="max-w-xl">
                    <h2 class="text-5xl font-black text-gray-800 tracking-tight italic">Designer Support</h2>
                    <p class="text-sm text-gray-400 mt-4 font-medium leading-relaxed">How can we help you today? Our specialized support team is here to ensure your studio remains exceptional.</p>
                </div>
                
                <!-- NEW: Kiyowo Search Bar (Consistent with other pages) -->
                <div class="relative group">
                    <i class="fa-solid fa-magnifying-glass absolute left-4 top-1/2 -translate-y-1/2 text-gray-300 group-focus-within:text-primary transition-colors text-xs"></i>
                    <input type="text" placeholder="Search help articles..." class="bg-white border-none rounded-2xl py-3.5 pl-10 pr-6 text-[10px] font-bold text-gray-900 focus:ring-2 focus:ring-primary/20 outline-none w-64 shadow-sm transition-all">
                </div>
            </div>

            <!-- GRID CARDS -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div class="bg-white p-10 rounded-[40px] border border-gray-100 shadow-sm space-y-6 hover:shadow-xl transition-all duration-500">
                    <div class="w-12 h-12 bg-gray-50 rounded-2xl flex items-center justify-center text-primary"><i class="fa-solid fa-book-open"></i></div>
                    <div>
                        <h3 class="text-xl font-black text-gray-800 uppercase tracking-tight">Help Center</h3>
                        <p class="text-[11px] text-gray-400 font-bold leading-relaxed mt-4">Browse our curated guides on project management and design standards.</p>
                    </div>
                </div>

                <div class="bg-white p-10 rounded-[40px] border border-gray-100 shadow-sm space-y-6 hover:shadow-xl transition-all duration-500">
                    <div class="w-12 h-12 bg-gray-50 rounded-2xl flex items-center justify-center text-primary"><i class="fa-solid fa-envelope"></i></div>
                    <div>
                        <h3 class="text-xl font-black text-gray-800 uppercase tracking-tight">Contact Us</h3>
                        <p class="text-[11px] text-gray-400 font-bold leading-relaxed mt-4">Submit a formal inquiry regarding your partnership or payment fulfillment.</p>
                    </div>
                </div>

                <div class="bg-white p-10 rounded-[40px] border border-gray-100 shadow-sm space-y-6 hover:shadow-xl transition-all duration-500">
                    <div class="w-12 h-12 bg-gray-50 rounded-2xl flex items-center justify-center text-primary"><i class="fa-solid fa-comments"></i></div>
                    <div>
                        <h3 class="text-xl font-black text-gray-800 uppercase tracking-tight">Live Chat</h3>
                        <p class="text-[11px] text-gray-400 font-bold leading-relaxed mt-4">Connect instantly with a dedicated Designer Success Manager for real-time help.</p>
                    </div>
                </div>
            </div>

            <!-- COMPLAINT FORM -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <div class="bg-white p-10 rounded-[40px] border border-gray-100 shadow-sm space-y-6">
                    <h3 class="text-2xl font-black text-gray-800 italic uppercase">Kirim Komplain</h3>
                    
                    @if(session('success'))
                        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-xl relative" role="alert">
                            <span class="block sm:inline">{{ session('success') }}</span>
                        </div>
                    @endif

                    <form action="{{ route('designer.support.submit') }}" method="POST" class="space-y-4">
                        @csrf
                        <div>
                            <label class="text-[10px] font-black uppercase tracking-widest text-gray-400">Subjek</label>
                            <input type="text" name="subject" required class="w-full bg-gray-50 border-none rounded-xl py-3 px-4 text-xs font-bold outline-none focus:ring-2 focus:ring-primary/20 transition-all mt-1" placeholder="Contoh: Masalah Pembayaran">
                        </div>
                        <div>
                            <label class="text-[10px] font-black uppercase tracking-widest text-gray-400">Pesan Detail</label>
                            <textarea name="message" required rows="4" class="w-full bg-gray-50 border-none rounded-xl py-3 px-4 text-xs font-bold outline-none focus:ring-2 focus:ring-primary/20 transition-all mt-1" placeholder="Jelaskan kendala Anda..."></textarea>
                        </div>
                        <button type="submit" class="bg-primary text-white px-8 py-3 rounded-xl text-[10px] font-black uppercase tracking-widest hover:scale-105 transition-all shadow-lg shadow-primary/20">
                            Kirim Sekarang
                        </button>
                    </form>
                </div>

                <div class="bg-white p-10 rounded-[40px] border border-gray-100 shadow-sm overflow-hidden flex flex-col">
                    <h3 class="text-2xl font-black text-gray-800 italic uppercase mb-6">Riwayat Komplain</h3>
                    <div class="flex-1 overflow-y-auto space-y-4 pr-2 custom-scroll">
                        @php
                            $supports = \App\Models\Support::where('user_id', Auth::id())->latest()->get();
                        @endphp
                        @forelse($supports as $s)
                        <div class="p-6 rounded-2xl border border-gray-50 bg-gray-50/50">
                            <div class="flex justify-between items-start mb-2">
                                <h4 class="text-xs font-black uppercase tracking-tight text-gray-800">{{ $s->subject }}</h4>
                                <span class="text-[8px] font-black uppercase px-2 py-1 rounded bg-{{ $s->status === 'pending' ? 'yellow-100 text-yellow-700' : ($s->status === 'replied' ? 'blue-100 text-blue-700' : 'green-100 text-green-700') }}">
                                    {{ $s->status }}
                                </span>
                            </div>
                            <p class="text-[10px] text-gray-500 font-medium mb-3">{{ $s->message }}</p>
                            @if($s->admin_reply)
                            <div class="mt-3 p-4 rounded-xl bg-primary/5 border-l-4 border-primary">
                                <p class="text-[9px] font-black uppercase text-primary mb-1">Admin Reply:</p>
                                <p class="text-[10px] text-gray-600 font-bold italic">{{ $s->admin_reply }}</p>
                            </div>
                            @endif
                            <div class="mt-2 text-[8px] text-gray-400 font-bold uppercase">{{ $s->created_at->diffForHumans() }}</div>
                        </div>
                        @empty
                        <div class="text-center py-10">
                            <i class="fa-solid fa-inbox text-3xl text-gray-200 mb-4 block"></i>
                            <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Belum ada riwayat</p>
                        </div>
                        @endforelse
                    </div>
                </div>
            </div>

            <!-- CHAT BANNER -->
            <div class="bg-gray-100/60 p-12 rounded-[48px] flex flex-col md:flex-row items-center justify-between border border-white">
                <div class="max-w-xl space-y-4">
                    <h3 class="text-3xl font-black text-gray-800 italic">Dedicated Support for Curators</h3>
                    <p class="text-xs font-bold text-gray-400 leading-relaxed uppercase tracking-widest">Our premium support tier ensures that high-end designers receive priority response times.</p>
                </div>
                <a href="{{ route('designer.support.chat') }}" class="mt-8 md:mt-0 bg-primary text-white px-10 py-5 rounded-2xl text-[11px] font-black uppercase tracking-[0.2em] shadow-xl shadow-primary/20 hover:scale-105 transition-all flex items-center">
                    <i class="fa-solid fa-headset mr-3"></i> CHAT WITH ADMIN
                </a>
            </div>

            <!-- VERIFIED SECTION -->
            <div class="grid grid-cols-1 md:grid-cols-12 gap-8">
                <div class="md:col-span-8 rounded-[48px] overflow-hidden shadow-2xl h-80">
                    <img src="https://images.unsplash.com/photo-1497366216548-37526070297c?q=80&w=1200" class="w-full h-full object-cover grayscale opacity-90">
                </div>
                <div class="md:col-span-4 bg-[#1A1A1A] p-12 rounded-[48px] flex flex-col justify-between text-white relative overflow-hidden">
                    <div class="relative z-10">
                        <div class="bg-primary/20 w-10 h-10 rounded-xl flex items-center justify-center mb-8 text-primary">
                            <i class="fa-solid fa-certificate"></i>
                        </div>
                        <h4 class="text-2xl font-black leading-tight italic uppercase">Verified Designer Program</h4>
                        <p class="text-[10px] font-bold text-gray-400 mt-6 leading-relaxed uppercase tracking-widest">Access to 24/7 technical oversight and curated marketing insights.</p>
                    </div>
                    <div class="absolute -right-10 -bottom-10 w-40 h-40 bg-white/5 rounded-full"></div>
                </div>
            </div>
        </main>

        <footer class="p-8 border-t border-gray-100 text-[9px] font-black text-gray-400 uppercase tracking-widest bg-white mt-auto text-center">
            © 2026 DECOR DESIGNER PORTAL. ALL RIGHTS RESERVED.
        </footer>
    </div>

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