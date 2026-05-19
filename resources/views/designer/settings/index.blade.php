<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Decor Designer - Studio Identity</title>
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
        
        .tab-active { color: #B5733A; border-bottom: 3px solid #B5733A; }
        
        ::-webkit-scrollbar { width: 5px; }
        ::-webkit-scrollbar-thumb { background: #B5733A; border-radius: 10px; }
        
        .switch { position: relative; display: inline-block; width: 44px; height: 22px; }
        .switch input { opacity: 0; width: 0; height: 0; }
        .slider { position: absolute; cursor: pointer; top: 0; left: 0; right: 0; bottom: 0; background-color: #E5E7EB; transition: .4s; border-radius: 20px; }
        .slider:before { position: absolute; content: ""; height: 16px; width: 16px; left: 3px; bottom: 3px; background-color: white; transition: .4s; border-radius: 50%; }
        input:checked + .slider { background-color: #B5733A; }
        input:checked + .slider:before { transform: translateX(22px); }

        .modal-overlay { transition: opacity 0.3s ease; }
        .modal-content { transition: transform 0.3s ease, opacity 0.3s ease; }
        .custom-scroll::-webkit-scrollbar { width: 4px; }
    </style>
</head>
<body class="text-gray-800 flex h-screen">

    <!-- 1. SIDEBAR -->
    <aside id="sidebar" class="w-64 bg-white border-r border-gray-200 flex flex-col h-full z-50 flex-shrink-0 sidebar-transition fixed">
        <div class="p-8">
            <h1 class="text-2xl font-bold tracking-widest text-primary uppercase leading-none">DECOR</h1>
            <p class="text-[10px] text-gray-400 mt-1 uppercase tracking-widest italic font-bold">Designer Portal</p>
        </div>

        <nav class="flex-1 px-4 space-y-1">
            <a href="{{ route('designer.dashboard') }}" class="flex items-center px-4 py-3 text-xs font-bold text-gray-400 hover:text-primary transition-all rounded-lg"><i class="fa-solid fa-table-columns mr-3 w-5 text-center"></i> Dashboard</a>
            <a href="{{ route('designer.portfolio.index') }}" class="flex items-center px-4 py-3 text-xs font-bold text-gray-400 hover:text-primary transition-all rounded-lg"><i class="fa-solid fa-briefcase mr-3 w-5 text-center"></i> Kelola Portofolio</a>
            <a href="{{ route('designer.consultations.index') }}" class="flex items-center px-4 py-3 text-xs font-bold text-gray-400 hover:text-primary transition-all rounded-lg"><i class="fa-solid fa-calendar-check mr-3 w-5 text-center"></i>Konsultasi</a>
            <a href="{{ route('designer.chats') }}" class="flex items-center px-4 py-3 text-xs font-bold text-gray-400 hover:text-primary transition-all rounded-lg"><i class="fa-solid fa-comment-dots mr-3 w-5 text-center"></i>Chat</a>
            <a href="{{ route('designer.reviews') }}" class="flex items-center px-4 py-3 text-xs font-bold text-gray-400 hover:text-primary transition-all rounded-lg"><i class="fa-solid fa-star mr-3 w-5 text-center"></i> Review & Rating</a>
            <a href="{{ route('designer.reports') }}" class="flex items-center px-4 py-3 text-xs font-bold text-gray-400 hover:text-primary transition-all rounded-lg"><i class="fa-solid fa-chart-line mr-3 w-5 text-center"></i> Laporan</a>
        </nav>

        <div class="p-4 border-t border-gray-100 space-y-1">
            <a href="{{ route('designer.settings') }}" class="flex items-center px-4 py-3 text-xs font-bold active-link transition-all rounded-lg"><i class="fa-solid fa-gear mr-3 w-5 text-center"></i> Settings</a>
            <a href="{{ route('designer.support') }}" class="flex items-center px-4 py-3 text-xs font-bold text-gray-400 hover:text-primary transition-all rounded-lg"><i class="fa-solid fa-circle-question mr-3 w-5 text-center"></i> Support</a>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="w-full flex items-center px-4 py-3 text-xs font-bold text-red-400 hover:text-red-500 hover:bg-red-50 transition-all rounded-lg">
                    <i class="fa-solid fa-right-from-bracket mr-3 w-5 text-center"></i> Logout
                </button>
            </form>
        </div>
    </aside>

    <div id="main-content" class="flex-1 flex flex-col min-w-0 overflow-hidden ml-64 sidebar-transition">
        
        <form action="{{ route('designer.settings.update') }}" method="POST" enctype="multipart/form-data" class="flex-1 flex flex-col min-w-0 overflow-hidden">
            @csrf
            <!-- HEADER -->
            <header class="h-16 bg-primary flex items-center justify-between px-8 sticky top-0 z-40 shadow-sm text-white">
                <div class="flex items-center">
                    <i onclick="toggleSidebar()" class="fa-solid fa-bars-staggered text-xl mr-4 cursor-pointer hover:scale-110 transition-transform"></i>
                    <h2 class="font-black text-[10px] uppercase tracking-[0.3em] leading-none">General Settings</h2>
                </div>
                <div class="flex items-center space-x-6">
                    <button type="submit" class="bg-white text-primary px-8 py-2.5 rounded-xl text-[10px] font-black uppercase shadow-lg hover:bg-white/90 transition-all">Save Profile</button>
                    <div class="flex items-center space-x-3 bg-white/10 px-3 py-1.5 rounded-xl border border-white/20">
                        <span class="text-[10px] font-black uppercase tracking-widest">{{ Auth::user()->full_name }}</span>
                        <div class="w-8 h-8 rounded-lg bg-white text-primary flex items-center justify-center font-bold">
                            {{ strtoupper(substr(Auth::user()->full_name, 0, 2)) }}
                        </div>
                    </div>
                </div>
            </header>

            <main class="flex-1 overflow-y-auto p-10 space-y-8 custom-scroll pb-24">
                <div class="max-w-7xl mx-auto space-y-8">
                    
                    @if(session('success'))
                    <div class="bg-green-50 border border-green-100 text-green-600 px-6 py-4 rounded-2xl text-[10px] font-black uppercase tracking-widest shadow-sm">
                        {{ session('success') }}
                    </div>
                    @endif

                    @if($errors->any())
                    <div class="bg-red-50 border border-red-100 text-red-600 px-6 py-4 rounded-2xl text-[10px] font-black uppercase tracking-widest shadow-sm">
                        <ul>
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                    @endif

                    <!-- TAB NAVIGATION -->
                    <div class="flex border-b border-gray-200">
                        <a href="{{ route('designer.settings') }}" class="px-8 py-4 text-[10px] font-black uppercase tracking-widest tab-active transition-all">Studio Info</a>
                        <a href="{{ route('designer.settings.bank') }}" class="px-8 py-4 text-[10px] font-black uppercase tracking-widest text-gray-400 hover:text-primary transition-all">Bank & Payout</a>
                    </div>

                    <!-- BANNER & AVATAR -->
                    <section class="relative mt-6">
                        <div class="w-full h-64 bg-gray-100 rounded-[48px] overflow-hidden group relative border border-gray-100 shadow-sm">
                            <img id="banner-preview" src="{{ $designer->banner_image ? asset('storage/' . $designer->banner_image) : 'https://images.unsplash.com/photo-1618221195710-dd6b41faaea6?q=80&w=1200' }}" class="w-full h-full object-cover">
                            <label class="absolute inset-0 bg-black/20 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-all cursor-pointer">
                                <input type="file" name="banner_image" class="hidden" onchange="previewImage(this, 'banner-preview')">
                                <span class="bg-white/20 backdrop-blur-md text-white text-[9px] font-black uppercase px-8 py-3 rounded-2xl border border-white/30">Update Studio Cover</span>
                            </label>
                        </div>
                        <div class="absolute -bottom-12 left-16 group">
                            <div class="w-40 h-40 rounded-[40px] bg-white p-2 shadow-2xl relative overflow-hidden">
                                <img id="avatar-preview" src="{{ $designer->designer_image ? asset('storage/' . $designer->designer_image) : 'https://ui-avatars.com/api/?name='.urlencode($designer->studio_name ?? Auth::user()->full_name).'&background=B5733A&color=fff' }}" class="w-full h-full rounded-[34px] object-cover">
                                <label class="absolute inset-0 bg-black/40 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-all cursor-pointer">
                                    <input type="file" name="designer_image" class="hidden" onchange="previewImage(this, 'avatar-preview')">
                                    <i class="fa-solid fa-camera text-white text-2xl"></i>
                                </label>
                            </div>
                        </div>
                    </section>

                    <!-- GRID ROW 1: IDENTITY & STATUS/HOURS -->
                    <div class="pt-12 grid grid-cols-12 gap-8">
                        <!-- LEFT COLUMN -->
                        <div class="col-span-12 lg:col-span-7">
                            <div class="bg-white p-10 rounded-[48px] border border-gray-100 shadow-sm space-y-8 h-full">
                                <h3 class="text-xs font-black uppercase tracking-widest text-gray-300 italic leading-none">Studio Identity</h3>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div class="space-y-3">
                                        <label class="text-[9px] font-black uppercase text-gray-400 ml-2">Display Name</label>
                                        <input type="text" name="studio_name" value="{{ old('studio_name', $designer->studio_name) }}" class="w-full bg-gray-50 border-none rounded-2xl py-4 px-6 text-sm font-bold outline-none focus:ring-2 focus:ring-primary/10">
                                        @error('studio_name') <p class="text-[9px] text-red-500 font-bold ml-2">{{ $message }}</p> @enderror
                                    </div>
                                    <div class="space-y-3">
                                        <label class="text-[9px] font-black uppercase text-gray-400 ml-2">Specialization</label>
                                        <input type="text" name="specialty" value="{{ old('specialty', $designer->specialty) }}" placeholder="e.g. Expert in Living Room Design, Modern Minimalist" class="w-full bg-gray-50 border-none rounded-2xl py-4 px-6 text-sm font-bold outline-none focus:ring-2 focus:ring-primary/10">
                                        @error('specialty') <p class="text-[9px] text-red-500 font-bold ml-2">{{ $message }}</p> @enderror
                                    </div>
                                </div>
                                <div class="space-y-3">
                                    <label class="text-[9px] font-black uppercase text-gray-400 ml-2">Design Philosophy & Bio</label>
                                    <textarea name="bio" class="w-full bg-gray-50 border-none rounded-[32px] py-4 px-6 text-xs font-medium leading-relaxed h-32 outline-none focus:ring-2 focus:ring-primary/10 transition-all">{{ old('bio', $designer->bio) }}</textarea>
                                    @error('bio') <p class="text-[9px] text-red-500 font-bold ml-2">{{ $message }}</p> @enderror
                                </div>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div class="space-y-3">
                                        <label class="text-[9px] font-black uppercase text-gray-400 ml-2">Education History</label>
                                        <textarea name="education" placeholder="e.g. B.Arch from University of Indonesia" class="w-full bg-gray-50 border-none rounded-[24px] py-4 px-6 text-xs font-medium leading-relaxed h-28 outline-none focus:ring-2 focus:ring-primary/10 transition-all">{{ old('education', $designer->education) }}</textarea>
                                        @error('education') <p class="text-[9px] text-red-500 font-bold ml-2">{{ $message }}</p> @enderror
                                    </div>
                                    <div class="space-y-3">
                                        <label class="text-[9px] font-black uppercase text-gray-400 ml-2">Awards & Achievements</label>
                                        <textarea name="awards" placeholder="e.g. Best Interior Designer 2023" class="w-full bg-gray-50 border-none rounded-[24px] py-4 px-6 text-xs font-medium leading-relaxed h-28 outline-none focus:ring-2 focus:ring-primary/10 transition-all">{{ old('awards', $designer->awards) }}</textarea>
                                        @error('awards') <p class="text-[9px] text-red-500 font-bold ml-2">{{ $message }}</p> @enderror
                                    </div>
                                </div>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">
                                    <div class="space-y-3">
                                        <label class="text-[9px] font-black uppercase text-gray-400 ml-2"><i class="fa-brands fa-instagram mr-1"></i> Instagram URL</label>
                                        <input type="url" name="instagram_url" value="{{ old('instagram_url', $designer->instagram_url) }}" placeholder="https://instagram.com/yourprofile" class="w-full bg-gray-50 border-none rounded-2xl py-4 px-6 text-sm font-bold outline-none focus:ring-2 focus:ring-primary/10">
                                        @error('instagram_url') <p class="text-[9px] text-red-500 font-bold ml-2">{{ $message }}</p> @enderror
                                    </div>
                                    <div class="space-y-3">
                                        <label class="text-[9px] font-black uppercase text-gray-400 ml-2"><i class="fa-brands fa-linkedin mr-1"></i> LinkedIn URL</label>
                                        <input type="url" name="linkedin_url" value="{{ old('linkedin_url', $designer->linkedin_url) }}" placeholder="https://linkedin.com/in/yourprofile" class="w-full bg-gray-50 border-none rounded-2xl py-4 px-6 text-sm font-bold outline-none focus:ring-2 focus:ring-primary/10">
                                        @error('linkedin_url') <p class="text-[9px] text-red-500 font-bold ml-2">{{ $message }}</p> @enderror
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- RIGHT COLUMN -->
                        <div class="col-span-12 lg:col-span-5 space-y-8 flex flex-col">
                            <div class="bg-white p-10 rounded-[48px] border border-gray-100 shadow-sm space-y-8 flex-1">
                                <div class="flex justify-between items-center">
                                    <h3 class="text-xs font-black uppercase tracking-widest text-gray-300 italic">Studio Status</h3>
                                    <label class="switch">
                                        <input type="checkbox" name="is_open" {{ $designer->is_open ? 'checked' : '' }}>
                                        <span class="slider"></span>
                                    </label>
                                </div>
                                <div id="status-box" class="p-8 {{ $designer->is_open ? 'bg-green-50/50 border-green-100 text-green-700' : 'bg-red-50/50 border-red-100 text-red-700' }} rounded-[32px] border transition-colors">
                                    <p id="status-text" class="text-[10px] font-black uppercase leading-none">
                                        Currently: {{ $designer->is_open ? 'Open for Projects' : 'Closed for Projects' }}
                                    </p>
                                    <p id="status-subtext" class="text-[9px] font-medium mt-3 italic tracking-tight">
                                        {{ $designer->is_open ? 'Klien bisa langsung kirim permintaan konsultasi.' : 'Maaf, untuk saat ini kami tidak menerima proyek baru.' }}
                                    </p>
                                </div>
                            </div>

                            <div class="bg-white p-10 rounded-[48px] border border-gray-100 shadow-sm space-y-6 text-center flex-1">
                                <div class="flex justify-between items-center mb-2">
                                    <h3 class="text-xs font-black uppercase tracking-widest text-gray-300 italic">Operating Hours</h3>
                                    <button type="button" onclick="toggleTimeModal()" class="text-[9px] font-black uppercase text-primary hover:scale-105 transition-all">Change</button>
                                </div>
                                <div class="grid grid-cols-2 gap-4">
                                    <div class="bg-gray-50 p-6 rounded-[32px] border border-gray-100"><p class="text-[9px] font-black text-gray-400 uppercase mb-1">From</p><p class="text-lg font-black text-gray-900">09:00 AM</p></div>
                                    <div class="bg-gray-50 p-6 rounded-[32px] border border-gray-100"><p class="text-[9px] font-black text-gray-400 uppercase mb-1">Until</p><p class="text-lg font-black text-gray-900">06:00 PM</p></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </main>

            <footer class="p-8 border-t border-gray-100 text-[9px] font-black text-gray-400 uppercase tracking-widest bg-white mt-auto text-center">
                © 2026 DECOR DESIGNER PORTAL. ALL RIGHTS RESERVED.
            </footer>
        </form>
    </div>

    <!-- MODALS (Placeholder/Optional logic) -->
    <!-- ... same modals as before ... -->

    <script>
        function toggleSidebar() { 
            document.getElementById('sidebar').classList.toggle('sidebar-closed'); 
            document.getElementById('main-content').classList.toggle('main-full'); 
        }

        function previewImage(input, previewId) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById(previewId).src = e.target.result;
                }
                reader.readAsDataURL(input.files[0]);
            }
        }

        // Real-time status update for UI
        document.querySelector('input[name="is_open"]').addEventListener('change', function() {
            const box = document.getElementById('status-box');
            const text = document.getElementById('status-text');
            const subtext = document.getElementById('status-subtext');
            
            if (this.checked) {
                box.classList.remove('bg-red-50/50', 'border-red-100', 'text-red-700');
                box.classList.add('bg-green-50/50', 'border-green-100', 'text-green-700');
                text.innerText = 'Currently: Open for Projects';
                subtext.innerText = 'Klien bisa langsung kirim permintaan konsultasi.';
            } else {
                box.classList.remove('bg-green-50/50', 'border-green-100', 'text-green-700');
                box.classList.add('bg-red-50/50', 'border-red-100', 'text-red-700');
                text.innerText = 'Currently: Closed for Projects';
                subtext.innerText = 'Maaf, untuk saat ini kami tidak menerima proyek baru.';
            }
        });
    </script>
</body>
</html>