<?php $site_name = "DECOR"; ?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $designer->studio_name ?? $designer->user->full_name }} — Portfolio</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#B5733A',
                        secondary: '#E3DCD6',
                    }
                }
            }
        }
    </script>
    <!-- Pannellum 360 Viewer -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/pannellum@2.5.6/build/pannellum.css"/>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/pannellum@2.5.6/build/pannellum.js"></script>

    <style>
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;600;700&display=swap');
        body { font-family: 'Plus Jakarta Sans', sans-serif; background-color: #ffffff; }
        .content-container { max-width: 1200px; margin: 0 auto; }
        
        /* Modal Animation */
        @keyframes modalFadeIn {
            from { opacity: 0; transform: scale(0.95) translateY(10px); }
            to { opacity: 1; transform: scale(1) translateY(0); }
        }
        .modal-animate {
            animation: modalFadeIn 0.3s cubic-bezier(0.16, 1, 0.3, 1) forwards;
        }
        
        /* Custom Pannellum UI Adjustments */
        .pnlm-container { background: #111 !important; }
        .pnlm-load-box { background: rgba(28,20,16,0.8) !important; border: 1px solid rgba(181, 115, 58, 0.2); border-radius: 16px; color: #fff; }
        .pnlm-lbox { border-color: #B5733A !important; }
    </style>
</head>
<body class="text-gray-800">

     <header class="bg-white border-b border-gray-100 sticky top-0 z-50">
        <div class="content-container flex justify-between items-center py-4 px-6">
            <div class="flex items-center space-x-8 flex-1">
                <a href="{{ route('homepage') }}" class="text-2xl font-black tracking-tighter uppercase text-primary hover:opacity-80 transition-all">
                    <?= $site_name ?>
                </a>
            </div>
            <nav class="hidden md:flex items-center space-x-10 text-[13px] font-medium text-gray-500 tracking-wide">
                <a href="{{ route('customer.catalog') }}" class="hover:text-primary transition-all">Collections</a>
                <a href="{{ route('customer.designers') }}" class="hover:text-primary transition-all">Designers</a>
                 <a href="{{ route('customer.design-lab') }}" class="hover:text-primary transition-all">AI Studio</a>
            </nav>
            <div class="flex items-center space-x-6 flex-1 justify-end">
                @auth
                    <div class="w-9 h-9 rounded-md overflow-hidden border border-gray-200 cursor-pointer hover:border-primary transition-all">
                        <a href="{{ route('customer.profile') }}" class="block w-full h-full">
                            <img src="{{ Auth::user()->avatar_url }}" class="w-full h-full bg-slate-100 object-cover">
                        </a>
                    </div>
                @else
                    <a href="{{ route('login') }}" class="text-[11px] font-bold uppercase tracking-widest text-gray-500 hover:text-primary transition-all">Sign In</a>
                @endauth
            </div>
        </div>
    </header>

    <!-- Studio Cover Banner -->
    <div class="content-container px-6 mt-10">
        <div class="w-full h-64 md:h-80 rounded-[40px] overflow-hidden bg-gray-100 border border-gray-100 shadow-sm relative group">
            <img src="{{ $designer->banner_image ? asset('storage/' . $designer->banner_image) : 'https://images.unsplash.com/photo-1618221195710-dd6b41faaea6?q=80&w=1200' }}" class="w-full h-full object-cover">
        </div>
    </div>

    <main class="py-12 content-container px-6">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-20">
            <!-- Sidebar: Designer Info -->
            <div class="lg:col-span-1 space-y-10">
                <div class="aspect-square rounded-[40px] overflow-hidden bg-gray-100 shadow-2xl border-8 border-gray-50">
                    @php
                        $image = $designer->designer_image 
                            ? asset('storage/' . $designer->designer_image) 
                            : 'https://ui-avatars.com/api/?name='.urlencode($designer->user->full_name).'&background=B5733A&color=fff';
                    @endphp
                    <img src="{{ $image }}" class="w-full h-full object-cover">
                </div>
                
                <div class="space-y-4">
                    <div class="flex items-center gap-2">
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[9px] font-black uppercase tracking-wider {{ $designer->is_open ? 'bg-green-50 text-green-700 border border-green-200/50' : 'bg-red-50 text-red-700 border border-red-200/50' }}">
                            <span class="w-1.5 h-1.5 rounded-full {{ $designer->is_open ? 'bg-green-500' : 'bg-red-500' }}"></span>
                            {{ $designer->is_open ? 'Open for Projects' : 'Closed for Projects' }}
                        </span>
                    </div>
                    <h1 class="text-4xl font-bold tracking-tighter">{{ $designer->studio_name ?? $designer->user->full_name }}</h1>
                    <p class="text-[10px] font-black uppercase tracking-[0.3em] text-primary">{{ $designer->specialty }}</p>
                    <p class="text-sm text-gray-400 leading-relaxed">{{ $designer->bio ?? 'No bio provided.' }}</p>
                </div>

                <div class="pt-10 border-t border-gray-100 space-y-6">
                    <div class="grid grid-cols-3 gap-3">
                        <div class="bg-gray-50 p-4 rounded-2xl text-center">
                            <p class="text-xl font-bold text-primary">{{ $designer->consultations->where('status', 4)->count() }}</p>
                            <p class="text-[7px] font-black uppercase tracking-widest text-gray-400 mt-1 leading-tight">Completed Projects</p>
                        </div>
                        <div class="bg-gray-50 p-4 rounded-2xl text-center">
                            <p class="text-xl font-bold text-primary">4.9</p>
                            <p class="text-[7px] font-black uppercase tracking-widest text-gray-400 mt-1 leading-tight">Overall Rating</p>
                        </div>
                        <div class="bg-gray-50 p-4 rounded-2xl text-center">
                            <p class="text-xl font-bold text-primary">{{ $designer->average_project_duration }}</p>
                            <p class="text-[7px] font-black uppercase tracking-widest text-gray-400 mt-1 leading-tight">Avg. Duration</p>
                        </div>
                    </div>

                    @if($designer->education)
                    <div class="pt-6 border-t border-gray-100 space-y-2">
                        <h4 class="text-[9px] font-black uppercase tracking-wider text-gray-400"><i class="fa-solid fa-graduation-cap mr-1 text-primary"></i> Education</h4>
                        <p class="text-xs text-gray-600 font-medium leading-relaxed">{!! nl2br(e($designer->education)) !!}</p>
                    </div>
                    @endif

                    @if($designer->awards)
                    <div class="pt-6 border-t border-gray-100 space-y-2">
                        <h4 class="text-[9px] font-black uppercase tracking-wider text-gray-400"><i class="fa-solid fa-trophy mr-1 text-primary"></i> Awards & Achievements</h4>
                        <p class="text-xs text-gray-600 font-medium leading-relaxed">{!! nl2br(e($designer->awards)) !!}</p>
                    </div>
                    @endif

                    @if($designer->instagram_url || $designer->linkedin_url)
                    <div class="flex space-x-4">
                        @if($designer->instagram_url)
                        <a href="{{ $designer->instagram_url }}" target="_blank" class="w-12 h-12 bg-gray-50 flex items-center justify-center rounded-xl text-primary hover:bg-primary hover:text-white transition-all">
                            <i class="fa-brands fa-instagram text-lg"></i>
                        </a>
                        @endif
                        @if($designer->linkedin_url)
                        <a href="{{ $designer->linkedin_url }}" target="_blank" class="w-12 h-12 bg-gray-50 flex items-center justify-center rounded-xl text-primary hover:bg-primary hover:text-white transition-all">
                            <i class="fa-brands fa-linkedin text-lg"></i>
                        </a>
                        @endif
                    </div>
                    @endif

                    <a href="{{ route('customer.designers.free-chat', $designer->id) }}" class="block w-full">
                        <button class="w-full bg-primary text-white py-5 rounded-2xl text-xs font-black uppercase tracking-widest shadow-xl shadow-primary/20 hover:scale-[1.02] transition-all">
                            Start Consultation
                        </button>
                    </a>
                </div>
            </div>

            <!-- Main Content: Portfolios -->
            <div class="lg:col-span-2">
                <header class="mb-12 flex flex-col md:flex-row md:items-end md:justify-between gap-4">
                    <div>
                        <h2 class="text-2xl font-bold mb-1">Portfolio Highlights</h2>
                        <p class="text-gray-400 text-xs">Curated masterpieces by {{ $designer->user->full_name }}.</p>
                    </div>
                    @if($designer->instagram_url || $designer->linkedin_url)
                    <div class="flex items-center space-x-2 text-[10px] font-black uppercase tracking-wider text-primary bg-primary/5 px-4 py-2.5 rounded-xl border border-primary/10">
                        <i class="fa-solid fa-circle-info text-xs"></i>
                        <span>Lihat Portofolio Lengkap di Media Sosial</span>
                    </div>
                    @endif
                </header>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    @forelse($designer->portfolios as $portfolio)
                    <div class="group cursor-pointer" onclick="openPortfolioModal('{{ addslashes($portfolio->title) }}', '{{ asset('storage/' . $portfolio->image_url) }}', {{ $portfolio->is_360 ? 'true' : 'false' }}, '{{ $portfolio->category ?? 'Interior' }}', '{{ $portfolio->area ?? '-' }}', '{{ $portfolio->duration ?? '-' }}', '{{ addslashes(str_replace(["\r", "\n"], " ", $portfolio->description ?? 'No description.')) }}')">
                        <div class="aspect-[4/3] overflow-hidden rounded-[32px] bg-gray-50 mb-6 relative shadow-sm border border-gray-100">
                            <img src="{{ asset('storage/' . $portfolio->image_url) }}" class="w-full h-full object-cover group-hover:scale-110 transition duration-700">
                            <div class="absolute bottom-6 left-6">
                                <span class="bg-white/90 backdrop-blur text-[8px] font-black uppercase tracking-widest text-gray-900 px-4 py-2 rounded-full shadow-sm">
                                    {{ $portfolio->category ?? 'Interior' }}
                                </span>
                            </div>
                            @if($portfolio->is_360)
                            <div class="absolute top-6 right-6">
                                <span class="bg-primary text-white text-[8px] font-black uppercase tracking-widest px-4 py-2 rounded-full shadow-md flex items-center gap-1.5">
                                    <i class="fa-solid fa-vr-cardboard"></i> 360° VIEW
                                </span>
                            </div>
                            @endif
                        </div>
                        <h4 class="text-lg font-bold text-gray-900 group-hover:text-primary transition-colors">{{ $portfolio->title }}</h4>
                        <div class="flex items-center space-x-4 mt-2">
                            @if($portfolio->area)
                            <span class="text-[9px] font-bold text-gray-400 uppercase tracking-widest"><i class="fa-solid fa-vector-square mr-1"></i> {{ $portfolio->area }}</span>
                            @endif
                            @if($portfolio->duration)
                            <span class="text-[9px] font-bold text-gray-400 uppercase tracking-widest"><i class="fa-solid fa-clock mr-1"></i> {{ $portfolio->duration }}</span>
                            @endif
                        </div>
                    </div>
                    @empty
                    <div class="col-span-full py-20 bg-gray-50 rounded-[40px] text-center border-2 border-dashed border-gray-200">
                        <i class="fa-solid fa-images text-gray-200 text-4xl mb-4"></i>
                        <p class="text-xs font-black text-gray-300 uppercase tracking-widest">No portfolio works uploaded yet.</p>
                    </div>
                    @endforelse
                </div>
            </div>
        </div>
    </main>

    <!-- PORTFOLIO DETAIL & 360 VIEW MODAL -->
    <div id="portfolioModal" class="fixed inset-0 z-[100] hidden items-center justify-center p-4 bg-black/80 backdrop-blur-md transition-opacity duration-300">
        <div class="bg-white w-full max-w-5xl rounded-[32px] overflow-hidden shadow-2xl relative flex flex-col md:flex-row h-[90vh] md:h-[70vh] border border-gray-100 modal-animate">
            <!-- Close Button -->
            <button onclick="closePortfolioModal()" class="absolute top-6 right-6 z-[110] w-10 h-10 bg-white/95 backdrop-blur rounded-full flex items-center justify-center text-gray-500 hover:text-gray-900 transition-all shadow-md">
                <i class="fa-solid fa-xmark text-lg"></i>
            </button>

            <!-- Left Column: Visual Area -->
            <div class="w-full md:w-3/5 h-[45%] md:h-full bg-neutral-900 relative">
                <!-- Static Image Viewer -->
                <img id="modalStaticImage" src="" class="w-full h-full object-cover hidden">
                
                <!-- 360 Panorama Viewer Container -->
                <div id="modal360Viewer" class="w-full h-full hidden"></div>
                
                <!-- 360 Control Indicator Overlay -->
                <div id="modal360Indicator" class="absolute bottom-6 left-6 bg-black/75 backdrop-blur text-white px-4 py-2.5 rounded-xl text-[10px] font-black uppercase tracking-widest pointer-events-none flex items-center gap-2 hidden">
                    <i class="fa-solid fa-arrows-spin animate-spin text-primary"></i> Klik & seret untuk memutar ruangan 360°
                </div>
            </div>

            <!-- Right Column: Info Area -->
            <div class="w-full md:w-2/5 h-[55%] md:h-full p-8 md:p-10 flex flex-col justify-between bg-white overflow-y-auto">
                <div class="space-y-6">
                    <div>
                        <span id="modalCategory" class="text-[8px] bg-primary/10 text-primary px-3 py-1.5 rounded-lg font-black uppercase tracking-widest border border-primary/10">
                            Interior
                        </span>
                        <h3 id="modalTitle" class="text-2xl font-black text-gray-900 uppercase tracking-tight mt-4 leading-tight">Project Title</h3>
                    </div>

                    <!-- Specs Grid -->
                    <div class="grid grid-cols-2 gap-4">
                        <div class="bg-gray-50 p-4 rounded-2xl border border-gray-100/50">
                            <p class="text-[8px] font-black uppercase tracking-widest text-gray-400">Luas Area</p>
                            <p id="modalArea" class="text-xs font-bold text-gray-800 mt-1"><i class="fa-solid fa-vector-square text-primary mr-1"></i> -</p>
                        </div>
                        <div class="bg-gray-50 p-4 rounded-2xl border border-gray-100/50">
                            <p class="text-[8px] font-black uppercase tracking-widest text-gray-400">Durasi Pengerjaan</p>
                            <p id="modalDuration" class="text-xs font-bold text-gray-800 mt-1"><i class="fa-solid fa-clock text-primary mr-1"></i> -</p>
                        </div>
                    </div>

                    <!-- Description -->
                    <div class="space-y-2">
                        <h4 class="text-[9px] font-black uppercase tracking-widest text-gray-400">Concept & Detail</h4>
                        <p id="modalDescription" class="text-xs text-gray-500 leading-relaxed font-medium">
                            Description goes here...
                        </p>
                    </div>
                </div>

                <!-- Action Button -->
                <div class="pt-6 border-t border-gray-100 mt-8">
                    <a href="{{ route('customer.designers.free-chat', $designer->id) }}" class="block w-full">
                        <button class="w-full bg-primary text-white py-4 rounded-2xl text-[10px] font-black uppercase tracking-widest shadow-xl shadow-primary/20 hover:scale-[1.02] transition-all">
                            Tanyakan Tentang Desain Ini
                        </button>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <footer class="bg-primary text-white py-12 px-6 mt-12">
        <div class="max-w-6xl mx-auto text-center"> 
            <p class="text-[10px] text-white/60 font-medium tracking-widest uppercase">
                ©️ 2026 DECOR MARKETPLACE. ALL RIGHTS RESERVED.
            </p>
        </div>
    </footer>

    <script>
        let activeViewer = null;

        function openPortfolioModal(title, imageUrl, is360, category, area, duration, description) {
            // Destroy existing viewer if any to prevent memory leaks and issues
            if (activeViewer) {
                try {
                    activeViewer.destroy();
                } catch(e) {}
                activeViewer = null;
            }

            // Set text values
            document.getElementById('modalTitle').innerText = title;
            document.getElementById('modalCategory').innerText = category;
            document.getElementById('modalArea').innerHTML = `<i class="fa-solid fa-vector-square text-primary mr-1 text-[10px]"></i> ${area}`;
            document.getElementById('modalDuration').innerHTML = `<i class="fa-solid fa-clock text-primary mr-1 text-[10px]"></i> ${duration}`;
            document.getElementById('modalDescription').innerText = description;

            const staticImg = document.getElementById('modalStaticImage');
            const viewer360 = document.getElementById('modal360Viewer');
            const indicator360 = document.getElementById('modal360Indicator');

            if (is360) {
                staticImg.classList.add('hidden');
                viewer360.classList.remove('hidden');
                indicator360.classList.remove('hidden');

                // Bypass CORS by converting absolute same-origin URLs to relative pathnames
                let panoramaUrl = imageUrl;
                try {
                    const urlObj = new URL(imageUrl, window.location.origin);
                    if (urlObj.origin === window.location.origin) {
                        panoramaUrl = urlObj.pathname + urlObj.search + urlObj.hash;
                    }
                } catch (e) {
                    console.error("Failed to parse panorama URL, using original: ", e);
                }

                // Initialize Pannellum Equirectangular Viewer
                setTimeout(() => {
                    activeViewer = pannellum.viewer('modal360Viewer', {
                        "type": "equirectangular",
                        "panorama": panoramaUrl,
                        "autoLoad": true,
                        "compass": false,
                        "showZoomCtrl": true,
                        "mouseZoom": false,
                    });
                    
                    // Force resize after modal transition/animation completes (300ms)
                    setTimeout(() => {
                        if (activeViewer) {
                            activeViewer.resize();
                        }
                    }, 300);
                }, 50); // slight delay to ensure modal container size is fully rendered
            } else {
                staticImg.src = imageUrl;
                staticImg.classList.remove('hidden');
                viewer360.classList.add('hidden');
                indicator360.classList.add('hidden');
            }

            // Open Modal
            const modal = document.getElementById('portfolioModal');
            modal.classList.remove('hidden');
            modal.classList.add('flex');
            document.body.style.overflow = 'hidden';
        }

        function closePortfolioModal() {
            if (activeViewer) {
                try {
                    activeViewer.destroy();
                } catch(e) {}
                activeViewer = null;
            }

            const modal = document.getElementById('portfolioModal');
            modal.classList.add('hidden');
            modal.classList.remove('flex');
            document.body.style.overflow = '';
        }

        // Close when clicking outside of the modal dialog box
        window.addEventListener('click', function(e) {
            const modal = document.getElementById('portfolioModal');
            if (e.target === modal) {
                closePortfolioModal();
            }
        });
    </script>
</body>
</html>
