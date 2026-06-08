<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Decor Designer - Kelola Portofolio</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <!-- Pannellum 360 Viewer -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/pannellum@2.5.6/build/pannellum.css"/>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/pannellum@2.5.6/build/pannellum.js"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&display=swap');
        body { background-color: #F8F6F4; font-family: 'Plus Jakarta Sans', sans-serif; overflow-x: hidden; }
        .bg-primary { background-color: #B5733A; }
        .text-primary { color: #B5733A; }
        .active-link { background-color: rgba(181, 115, 58, 0.1); color: #B5733A; border-right: 4px solid #B5733A; }
        .sidebar-transition { transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1); }
        .sidebar-closed { transform: translateX(-100%); }
        .main-full { margin-left: 0 !important; }
        ::-webkit-scrollbar { width: 5px; }
        ::-webkit-scrollbar-thumb { background: #B5733A; border-radius: 10px; }
        
        /* Pannellum overrides */
        .pnlm-container { background: #111 !important; }
        .pnlm-load-box { background: rgba(28,20,16,0.8) !important; border: 1px solid rgba(181, 115, 58, 0.2); border-radius: 16px; color: #fff; }
        .pnlm-lbox { border-color: #B5733A !important; }
        
        /* Modal Animation */
        @keyframes modalFadeIn {
            from { opacity: 0; transform: scale(0.95) translateY(10px); }
            to { opacity: 1; transform: scale(1) translateY(0); }
        }
        .modal-animate {
            animation: modalFadeIn 0.3s cubic-bezier(0.16, 1, 0.3, 1) forwards;
        }
    </style>
</head>
<body class="text-gray-800">

    <!-- SIDEBAR -->
    @include('designer.partials.sidebar')

    <main id="main-content" class="ml-64 flex flex-col min-h-screen sidebar-transition">
        <!-- HEADER -->
        <header class="h-16 bg-primary flex items-center justify-between px-8 sticky top-0 z-40 text-white">
            <div class="flex items-center">
                <i onclick="toggleSidebar()" class="fa-solid fa-bars-staggered text-xl mr-4 cursor-pointer hover:scale-110 transition-transform"></i>
                <h2 class="font-black text-[10px] uppercase tracking-widest leading-none">Portfolio Management</h2>
            </div>
            <div class="flex items-center space-x-6">
                <div class="flex items-center space-x-3 bg-white/10 px-3 py-1.5 rounded-xl border border-white/20">
                    <span class="text-[10px] font-black uppercase tracking-widest">{{ Auth::user()->full_name }}</span>
                    <div class="w-8 h-8 rounded-lg bg-white text-primary flex items-center justify-center font-bold">
                        {{ strtoupper(substr(Auth::user()->full_name, 0, 2)) }}
                    </div>
                </div>
            </div>
        </header>

        <!-- CONTENT -->
        <div class="p-10 space-y-10 flex-1">
            <div class="flex justify-between items-end">
                <div>
                    <h3 class="text-2xl font-black text-gray-900 tracking-tight">Your Masterpieces</h3>
                    <p class="text-xs text-gray-400 font-bold italic mt-1">Manage and showcase your interior design works.</p>
                    <div class="flex items-center mt-3 space-x-2">
                        <span class="text-[9px] bg-primary/10 text-primary px-3 py-1.5 rounded-lg font-black uppercase tracking-widest">
                            Portofolio Manual: {{ $manualPortfoliosCount }} / {{ $maxManualPortfolios }}
                        </span>
                        <span class="text-[8px] text-gray-400 font-bold">
                            (Batas upload manual adalah 5. Menyelesaikan proyek dari customer akan otomatis menambah portofolio baru)
                        </span>
                    </div>
                </div>
                
                <div class="flex items-center space-x-4">
                    <div class="relative group">
                        <i class="fa-solid fa-magnifying-glass absolute left-4 top-1/2 -translate-y-1/2 text-gray-300 group-focus-within:text-primary transition-colors text-xs"></i>
                        <input type="text" id="portfolioSearch" placeholder="Search by title..." class="bg-white border-none rounded-2xl py-3 pl-10 pr-6 text-[10px] font-bold text-gray-900 focus:ring-2 focus:ring-primary/20 outline-none w-64 shadow-sm transition-all" onkeyup="filterPortfolio()">
                    </div>
 
                    @if($manualPortfoliosCount < $maxManualPortfolios)
                        <a href="{{ route('designer.portfolio.create') }}" class="bg-primary text-white px-8 py-3.5 rounded-2xl text-[10px] font-black uppercase tracking-widest shadow-2xl shadow-primary/30 hover:scale-105 transition-all">
                            + Add New Work
                        </a>
                    @else
                        <button disabled class="bg-gray-300 text-gray-500 cursor-not-allowed px-8 py-3.5 rounded-2xl text-[10px] font-black uppercase tracking-widest shadow-none">
                            + Add New Work
                        </button>
                    @endif
                </div>
            </div>
 
            @if(session('success'))
                <div class="bg-green-50 border border-green-100 text-green-600 px-6 py-4 rounded-2xl text-[10px] font-black uppercase tracking-widest shadow-sm">
                    {{ session('success') }}
                </div>
            @endif
 
            @if(session('error'))
                <div class="bg-red-50 border border-red-100 text-red-600 px-6 py-4 rounded-2xl text-[10px] font-black uppercase tracking-widest shadow-sm">
                    {{ session('error') }}
                </div>
            @endif
 
            @if($manualPortfoliosCount >= $maxManualPortfolios)
                <div class="bg-amber-50 border border-amber-100 text-amber-800 p-6 rounded-[32px] shadow-sm flex items-start space-x-4">
                    <div class="w-10 h-10 bg-amber-100 text-amber-700 rounded-full flex items-center justify-center flex-shrink-0 text-lg">
                        <i class="fa-solid fa-triangle-exclamation"></i>
                    </div>
                    <div>
                        <h4 class="text-xs font-black uppercase tracking-wider text-amber-900">Batas Upload Portofolio Manual Tercapai</h4>
                        <p class="text-[11px] font-medium text-amber-700/90 mt-1 leading-relaxed">
                            Batas upload portofolio secara manual adalah 5. Anda tidak dapat mengupload portofolio baru secara manual kecuali Anda menghapus portofolio manual yang ada, atau Anda menyelesaikan proyek/konsultasi dari customer di platform DECOR untuk menambahkan portofolio secara otomatis.
                        </p>
                    </div>
                </div>
            @endif

            <!-- PORTFOLIO GRID -->
            <div id="grid-content" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @forelse($portfolios as $portfolio)
                <div class="portfolio-card bg-white rounded-[40px] border border-gray-100 shadow-sm overflow-hidden group hover:shadow-2xl transition-all duration-500" 
                     id="portfolio-card-{{ $portfolio->id }}"
                     data-id="{{ $portfolio->id }}"
                     data-title="{{ addslashes($portfolio->title) }}"
                     data-image-url="{{ asset('storage/' . $portfolio->image_url) }}"
                     data-is-360="{{ $portfolio->is_360 ? 'true' : 'false' }}"
                     data-category="{{ $portfolio->category ?? 'Interior' }}"
                     data-area="{{ $portfolio->area ?? '-' }}"
                     data-duration="{{ $portfolio->duration ?? '-' }}"
                     data-description="{{ addslashes(str_replace(["\r", "\n"], " ", $portfolio->description ?? 'No description.')) }}">
                    <div class="aspect-[4/3] bg-gray-100 relative overflow-hidden cursor-pointer" onclick="openPortfolioModalFromCard({{ $portfolio->id }})">
                        <img src="{{ asset('storage/' . $portfolio->image_url) }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700">
                        <div class="absolute top-6 left-6 flex flex-col gap-1.5">
                            <span class="text-[8px] bg-white/90 backdrop-blur text-gray-900 px-3 py-1.5 rounded-lg font-black uppercase tracking-widest shadow-sm w-fit">
                                {{ $portfolio->category ?? 'Interior' }}
                            </span>
                            @php
                                $statusColors = [
                                    'pending' => 'bg-amber-500/90 text-white',
                                    'approved' => 'bg-emerald-500/90 text-white',
                                    'rejected' => 'bg-rose-500/90 text-white',
                                ];
                                $statusLabels = [
                                    'pending' => 'PENDING',
                                    'approved' => 'APPROVED',
                                    'rejected' => 'REJECTED',
                                ];
                                $color = $statusColors[$portfolio->status] ?? 'bg-gray-500/90 text-white';
                                $label = $statusLabels[$portfolio->status] ?? strtoupper($portfolio->status);
                            @endphp
                            <span class="text-[8px] {{ $color }} px-3 py-1.5 rounded-lg font-black uppercase tracking-widest shadow-sm w-fit">
                                {{ $label }}
                            </span>
                        </div>
                        @if($portfolio->is_360)
                        <div class="absolute top-6 right-6">
                            <span class="text-[8px] bg-primary text-white px-3 py-1.5 rounded-lg font-black uppercase tracking-widest shadow-sm flex items-center gap-1.5">
                                <i class="fa-solid fa-vr-cardboard"></i> 360° VIEW
                            </span>
                        </div>
                        @endif
                    </div>
                    <div class="p-8">
                        <div class="flex justify-between items-start mb-4">
                            <h4 class="text-sm font-black text-gray-900 uppercase tracking-tight leading-none cursor-pointer hover:text-primary transition-colors" onclick="openPortfolioModalFromCard({{ $portfolio->id }})">{{ $portfolio->title }}</h4>
                            @if($portfolio->budget)
                            <span class="text-[9px] font-black text-primary uppercase">{{ $portfolio->budget }}</span>
                            @endif
                        </div>
                        
                        <div class="flex items-center space-x-4 mb-6">
                            <div class="flex items-center text-gray-400 bg-gray-50 px-3 py-1.5 rounded-xl border border-gray-100 {{ $portfolio->area ? '' : 'hidden' }}" id="area-badge-{{ $portfolio->id }}">
                                <i class="fa-solid fa-vector-square text-[10px] mr-2"></i>
                                <span class="text-[9px] font-bold" id="area-val-{{ $portfolio->id }}">{{ $portfolio->area }}</span>
                            </div>
                            @if($portfolio->duration)
                            <div class="flex items-center text-gray-400 bg-gray-50 px-3 py-1.5 rounded-xl border border-gray-100">
                                <i class="fa-solid fa-stopwatch text-[10px] mr-2"></i>
                                <span class="text-[9px] font-bold">{{ $portfolio->duration }}</span>
                            </div>
                            @endif
                        </div>

                        <div class="pt-6 border-t border-gray-50 flex justify-end items-center">
                            <div class="flex items-center space-x-3">
                                @if(is_null($portfolio->consultation_id))
                                <a href="{{ route('designer.portfolio.edit', $portfolio->id) }}" class="w-9 h-9 flex items-center justify-center bg-amber-50 border border-amber-100 rounded-xl text-amber-500 hover:bg-amber-500 hover:text-white transition-all" title="Edit Portofolio">
                                    <i class="fa-solid fa-pen text-xs"></i>
                                </a>
                                @endif
                                <button onclick="openPortfolioModalFromCard({{ $portfolio->id }})" class="w-9 h-9 flex items-center justify-center bg-primary/5 border border-primary/10 rounded-xl text-primary hover:bg-primary hover:text-white transition-all" title="Lihat Detail">
                                    <i class="fa-solid fa-eye text-xs"></i>
                                </button>
                                <button onclick="openDeleteModal({{ $portfolio->id }})" class="w-9 h-9 flex items-center justify-center bg-red-50 border border-red-100 rounded-xl text-red-500 hover:bg-red-500 hover:text-white transition-all" title="Hapus Portofolio">
                                    <i class="fa-solid fa-trash-can text-xs"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                @empty
                <div class="col-span-full py-20 text-center">
                    <div class="w-20 h-20 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fa-solid fa-briefcase text-gray-300 text-2xl"></i>
                    </div>
                    <p class="text-xs font-black text-gray-300 uppercase tracking-widest">No works uploaded yet.</p>
                </div>
                @endforelse
            </div>

            <!-- Pesan Tidak Ditemukan (Search) -->
            <div id="no-result-msg" class="hidden py-20 text-center w-full">
                <div class="w-20 h-20 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fa-solid fa-magnifying-glass text-gray-300 text-2xl"></i>
                </div>
                <p class="text-xs font-black text-gray-400 uppercase tracking-widest">Portofolio tidak ditemukan.</p>
            </div>
        </div>

        <footer class="p-8 border-t border-gray-100 text-[9px] font-black text-gray-400 uppercase tracking-widest bg-white mt-auto text-center">
            © 2026 DECOR DESIGNER PORTAL. ALL RIGHTS RESERVED.
        </footer>
    </main>

    <!-- MODALS -->
    <!-- DELETE CONFIRMATION MODAL -->
    <div id="deleteModal" class="hidden fixed inset-0 z-[100] flex items-center justify-center bg-black/20 backdrop-blur-sm p-4">
        <div class="bg-white rounded-[48px] p-10 max-w-sm w-full shadow-2xl text-center space-y-6">
            <div class="w-20 h-20 bg-red-50 text-red-500 rounded-full flex items-center justify-center mx-auto text-3xl"><i class="fa-solid fa-triangle-exclamation"></i></div>
            <h4 class="text-lg font-black text-gray-900 uppercase">Are you sure?</h4>
            <div class="flex flex-col space-y-3 pt-4">
                <form id="deleteForm" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="w-full bg-red-500 text-white py-4 rounded-2xl text-[10px] font-black uppercase tracking-widest shadow-xl shadow-red-200 hover:scale-105 transition-all">Yes, Delete Forever</button>
                </form>
                <button onclick="closeDeleteModal()" class="text-[10px] font-black uppercase text-gray-300 tracking-widest py-2 hover:text-gray-500 transition-all">Cancel</button>
            </div>
        </div>
    </div>

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
                            <p class="text-[8px] font-black uppercase tracking-widest text-gray-400 mb-1">Luas Area</p>
                            <div class="flex items-center gap-2">
                                <input type="text" id="modalAreaInput" class="w-full bg-white border border-gray-200 rounded-xl px-3 py-2 text-xs font-bold text-gray-800 focus:outline-none focus:ring-2 focus:ring-primary/20 outline-none" placeholder="e.g. 50 sqm">
                                <button onclick="savePortfolioArea()" class="bg-primary text-white text-[10px] font-black uppercase px-3 py-2.5 rounded-xl hover:scale-105 transition-all shadow-md shadow-primary/20 flex items-center justify-center flex-shrink-0 animate-save-btn" title="Simpan Luas Area">
                                    <i class="fa-solid fa-floppy-disk"></i>
                                </button>
                            </div>
                            <span id="saveStatus" class="text-[8px] font-bold text-green-500 mt-1 hidden block">Tersimpan!</span>
                        </div>
                        <div class="bg-gray-50 p-4 rounded-2xl border border-gray-100/50">
                            <p class="text-[8px] font-black uppercase tracking-widest text-gray-400">Durasi Pengerjaan</p>
                            <p id="modalDuration" class="text-xs font-bold text-gray-800 mt-2.5"><i class="fa-solid fa-clock text-primary mr-1 text-[10px]"></i> -</p>
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
            </div>
        </div>
    </div>

    <script>
        let activeViewer = null;
        let openPortfolioId = null;

        function toggleSidebar() {
            document.getElementById('sidebar').classList.toggle('sidebar-closed');
            document.getElementById('main-content').classList.toggle('main-full');
        }

        function filterPortfolio() {
            const input = document.getElementById("portfolioSearch");
            const filter = input.value.toUpperCase();
            const cards = document.querySelectorAll('.portfolio-card');
            let visibleCount = 0;

            cards.forEach(card => {
                const title = card.querySelector('h4').innerText;
                if (title.toUpperCase().indexOf(filter) > -1) {
                    card.classList.remove('hidden');
                    visibleCount++;
                } else {
                    card.classList.add('hidden');
                }
            });

            const noResultMsg = document.getElementById('no-result-msg');
            if (cards.length > 0) {
                if (visibleCount === 0) {
                    noResultMsg.classList.remove('hidden');
                } else {
                    noResultMsg.classList.add('hidden');
                }
            }
        }

        function openDeleteModal(id) {
            const form = document.getElementById('deleteForm');
            form.action = `/designer/portfolio/${id}/destroy`; 
            document.getElementById('deleteModal').classList.remove('hidden');
        }
        function closeDeleteModal() { document.getElementById('deleteModal').classList.add('hidden'); }

        function openPortfolioModalFromCard(id) {
            const card = document.getElementById(`portfolio-card-${id}`);
            if (!card) return;
            
            const title = card.getAttribute('data-title');
            const imageUrl = card.getAttribute('data-image-url');
            const is360 = card.getAttribute('data-is-360') === 'true';
            const category = card.getAttribute('data-category');
            const area = card.getAttribute('data-area');
            const duration = card.getAttribute('data-duration');
            const description = card.getAttribute('data-description');
            
            openPortfolioModal(id, title, imageUrl, is360, category, area, duration, description);
        }

        function openPortfolioModal(id, title, imageUrl, is360, category, area, duration, description) {
            openPortfolioId = id;
            
            // Destroy existing viewer if any to prevent memory leaks and issues
            if (activeViewer) {
                try {
                    activeViewer.destroy();
                } catch(e) {}
                activeViewer = null;
            }

            // Reset save status label
            document.getElementById('saveStatus').classList.add('hidden');

            // Set text & input values
            document.getElementById('modalTitle').innerText = title;
            document.getElementById('modalCategory').innerText = category;
            document.getElementById('modalAreaInput').value = area === '-' ? '' : area;
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
            openPortfolioId = null;
        }

        function savePortfolioArea() {
            if (!openPortfolioId) return;
            const areaValue = document.getElementById('modalAreaInput').value;
            const saveBtn = document.querySelector('.animate-save-btn');
            const statusText = document.getElementById('saveStatus');
            
            saveBtn.disabled = true;
            saveBtn.innerHTML = '<i class="fa-solid fa-spinner animate-spin"></i>';
            
            fetch(`/designer/portfolio/${openPortfolioId}/update-area`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    area: areaValue
                })
            })
            .then(res => res.json())
            .then(data => {
                saveBtn.disabled = false;
                saveBtn.innerHTML = '<i class="fa-solid fa-floppy-disk"></i>';
                if (data.success) {
                    statusText.innerText = 'Tersimpan!';
                    statusText.classList.remove('hidden', 'text-red-500');
                    statusText.classList.add('text-green-500');
                    
                    // Update dataset in-page card attributes
                    const card = document.getElementById(`portfolio-card-${openPortfolioId}`);
                    if (card) {
                        card.setAttribute('data-area', data.area);
                    }
                    
                    // Update in-page card badges as well
                    const cardBadge = document.getElementById(`area-badge-${openPortfolioId}`);
                    const cardVal = document.getElementById(`area-val-${openPortfolioId}`);
                    
                    if (data.area && data.area !== '-') {
                        if (cardBadge) cardBadge.classList.remove('hidden');
                        if (cardVal) cardVal.innerText = data.area;
                    } else {
                        if (cardBadge) cardBadge.classList.add('hidden');
                    }
                    
                    setTimeout(() => statusText.classList.add('hidden'), 2000);
                } else {
                    statusText.innerText = 'Gagal menyimpan!';
                    statusText.classList.remove('hidden', 'text-green-500');
                    statusText.classList.add('text-red-500');
                }
            })
            .catch(err => {
                console.error(err);
                saveBtn.disabled = false;
                saveBtn.innerHTML = '<i class="fa-solid fa-floppy-disk"></i>';
                statusText.innerText = 'Koneksi error!';
                statusText.classList.remove('hidden', 'text-green-500');
                statusText.classList.add('text-red-500');
            });
        }

        // Close when clicking outside of the modal dialog boxes
        window.addEventListener('click', function(e) {
            const deleteModal = document.getElementById('deleteModal');
            const portfolioModal = document.getElementById('portfolioModal');
            
            if (e.target === deleteModal) {
                closeDeleteModal();
            }
            if (e.target === portfolioModal) {
                closePortfolioModal();
            }
        });
    </script>
</body>
</html>