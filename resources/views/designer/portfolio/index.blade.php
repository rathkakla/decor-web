<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Decor Designer - Kelola Portofolio</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&display=swap');
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
    </style>
</head>
<body class="text-gray-800">

    <!-- SIDEBAR (8 Menus Consistent) -->
    <aside id="sidebar" class="w-64 bg-white border-r border-gray-200 flex flex-col fixed h-full z-50 sidebar-transition">
        <div class="p-8">
            <h1 class="text-2xl font-bold tracking-widest text-primary uppercase leading-none">DECOR</h1>
            <p class="text-[10px] text-gray-400 mt-1 uppercase tracking-widest italic font-bold">Designer Portal</p>
        </div>
        
        <nav class="flex-1 px-4 space-y-1">
            <a href="{{ route('designer.dashboard') }}" class="flex items-center px-4 py-3 text-xs font-bold text-gray-400 hover:text-primary transition-all rounded-lg"><i class="fa-solid fa-table-columns mr-3 w-5 text-center"></i> Dashboard</a>
            <a href="{{ route('designer.portfolio.index') }}" class="flex items-center px-4 py-3 text-xs font-bold active-link transition-all rounded-lg"><i class="fa-solid fa-briefcase mr-3 w-5 text-center"></i> Kelola Portofolio</a>
            <a href="{{ route('designer.consultations.index') }}" class="flex items-center px-4 py-3 text-xs font-bold text-gray-400 hover:text-primary transition-all rounded-lg"><i class="fa-solid fa-calendar-check mr-3 w-5 text-center"></i>Konsultasi</a>
            <a href="{{ route('designer.chats') }}" class="flex items-center px-4 py-3 text-xs font-bold text-gray-400 hover:text-primary transition-all rounded-lg"><i class="fa-solid fa-comment-dots mr-3 w-5 text-center"></i>Chat</a>
            <a href="{{ route('designer.reviews') }}" class="flex items-center px-4 py-3 text-xs font-bold text-gray-400 hover:text-primary transition-all rounded-lg"><i class="fa-solid fa-star mr-3 w-5 text-center"></i> Review & Rating</a>
            <a href="{{ route('designer.reports') }}" class="flex items-center px-4 py-3 text-xs font-bold text-gray-400 hover:text-primary transition-all rounded-lg"><i class="fa-solid fa-chart-line mr-3 w-5 text-center"></i> Laporan</a>
        </nav>

        <div class="p-4 border-t border-gray-100 space-y-1">
            <a href="{{ route('designer.settings') }}" class="flex items-center px-4 py-3 text-xs font-bold text-gray-400 hover:text-primary transition-all rounded-lg"><i class="fa-solid fa-gear mr-3 w-5 text-center"></i> Settings</a>
            <a href="{{ route('designer.support') }}" class="flex items-center px-4 py-3 text-xs font-bold text-gray-400 hover:text-primary transition-all rounded-lg"><i class="fa-solid fa-circle-question mr-3 w-5 text-center"></i> Support</a>
        </div>
    </aside>

    <main id="main-content" class="ml-64 flex flex-col min-h-screen sidebar-transition">
        <!-- HEADER -->
        <header class="h-16 bg-primary flex items-center justify-between px-8 sticky top-0 z-40 text-white">
            <div class="flex items-center">
                <i onclick="toggleSidebar()" class="fa-solid fa-bars-staggered text-xl mr-4 cursor-pointer hover:scale-110 transition-transform"></i>
                <h2 class="font-black text-[10px] uppercase tracking-widest leading-none">Portfolio Management</h2>
            </div>
            <div class="flex items-center space-x-6">
                <i class="fa-regular fa-bell text-xl"></i>
                <div class="flex items-center space-x-3 bg-white/10 px-3 py-1.5 rounded-xl border border-white/20">
                    <span class="text-[10px] font-black uppercase tracking-widest">Elena Vance</span>
                    <div class="w-8 h-8 rounded-lg bg-white text-primary flex items-center justify-center font-bold">EV</div>
                </div>
            </div>
        </header>

        <!-- CONTENT -->
        <div class="p-10 space-y-10 flex-1">
            <div class="flex justify-between items-end">
                <div>
                    <h3 class="text-2xl font-black text-gray-900 tracking-tight">Your Masterpieces</h3>
                    <p class="text-xs text-gray-400 font-bold italic mt-1">Manage and showcase your best interior designs.</p>
                </div>
                
                <div class="flex items-center space-x-4">
                    <!-- NEW: Kiyowo Search Bar -->
                    <div class="relative group">
                        <i class="fa-solid fa-magnifying-glass absolute left-4 top-1/2 -translate-y-1/2 text-gray-300 group-focus-within:text-primary transition-colors text-xs"></i>
                        <input type="text" id="portfolioSearch" placeholder="Search by title..." class="bg-white border-none rounded-2xl py-3 pl-10 pr-6 text-[10px] font-bold text-gray-900 focus:ring-2 focus:ring-primary/20 outline-none w-64 shadow-sm transition-all" onkeyup="filterPortfolio()">
                    </div>

                    <a href="{{ route('designer.portfolio.create') }}" class="bg-primary text-white px-8 py-3.5 rounded-2xl text-[10px] font-black uppercase tracking-widest shadow-2xl shadow-primary/30 hover:scale-105 transition-all">
                        + Add New Work
                    </a>
                </div>
            </div>

            <!-- TAB SYSTEM -->
            <div class="flex border-b border-gray-200">
                <button onclick="switchTab('interior')" id="tab-interior" class="px-8 py-4 text-[10px] font-black uppercase tracking-widest tab-active transition-all">Interior Consultation</button>
                <button onclick="switchTab('product')" id="tab-product" class="px-8 py-4 text-[10px] font-black uppercase tracking-widest text-gray-400 transition-all">Product Design Service</button>
            </div>

            <!-- PORTFOLIO GRID -->
            <div id="grid-content" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                
                <!-- EXAMPLE CARD: INTERIOR CONSULTATION (Approved) -->
                <div class="interior-card bg-white rounded-[40px] border border-gray-100 shadow-sm overflow-hidden group hover:shadow-2xl transition-all duration-500">
                    <div class="aspect-[4/3] bg-gray-100 relative overflow-hidden">
                        <img src="https://images.unsplash.com/photo-1616486338812-3dadae4b4ace?q=80&w=1000" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700">
                        <div class="absolute top-6 left-6">
                            <span class="text-[8px] bg-white/90 backdrop-blur text-gray-900 px-3 py-1.5 rounded-lg font-black uppercase tracking-widest shadow-sm">Residential · 2024</span>
                        </div>
                        <div class="absolute top-6 right-6">
                            <span class="text-[8px] bg-green-500 text-white px-3 py-1.5 rounded-lg font-black uppercase tracking-widest shadow-lg shadow-green-200">Approved</span>
                        </div>
                    </div>
                    <div class="p-8">
                        <div class="flex justify-between items-start mb-4">
                            <h4 class="text-sm font-black text-gray-900 uppercase tracking-tight leading-none">Modern Scandi Living</h4>
                            <span class="text-[9px] font-black text-primary uppercase">45jt</span>
                        </div>
                        
                        <div class="flex items-center space-x-4 mb-6">
                            <div class="flex items-center text-gray-400 bg-gray-50 px-3 py-1.5 rounded-xl border border-gray-100">
                                <i class="fa-solid fa-vector-square text-[10px] mr-2"></i>
                                <span class="text-[9px] font-bold">210 sqm</span>
                            </div>
                            <div class="flex items-center text-gray-400 bg-gray-50 px-3 py-1.5 rounded-xl border border-gray-100">
                                <i class="fa-solid fa-stopwatch text-[10px] mr-2"></i>
                                <span class="text-[9px] font-bold">5 Months</span>
                            </div>
                        </div>

                        <div class="pt-6 border-t border-gray-50 flex justify-between items-center">
                            <div class="flex items-center">
                                <div class="relative inline-block w-8 h-4 mr-2 align-middle select-none transition duration-200 ease-in">
                                    <input type="checkbox" name="toggle" id="toggle1" checked class="toggle-checkbox absolute block w-4 h-4 rounded-full bg-white border-2 border-green-500 appearance-none cursor-pointer translate-x-4 transition-transform"/>
                                    <label for="toggle1" class="toggle-label block overflow-hidden h-4 rounded-full bg-green-500 cursor-pointer"></label>
                                </div>
                                <span class="text-[9px] font-black text-gray-400 uppercase tracking-widest">Live</span>
                            </div>
                            <div class="flex items-center space-x-3">
                                <button onclick="openEditModal(1)" class="w-9 h-9 flex items-center justify-center bg-primary/5 border border-primary/10 rounded-xl text-primary hover:bg-primary hover:text-white transition-all">
                                    <i class="fa-solid fa-pen-to-square text-xs"></i>
                                </button>
                                <button onclick="openDeleteModal(1)" class="w-9 h-9 flex items-center justify-center bg-red-50 border border-red-100 rounded-xl text-red-500 hover:bg-red-500 hover:text-white transition-all">
                                    <i class="fa-solid fa-trash-can text-xs"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- EXAMPLE CARD: PRODUCT DESIGN (Rejected) -->
                <div class="product-card hidden bg-white rounded-[40px] border border-red-100 shadow-sm overflow-hidden group hover:shadow-2xl transition-all duration-500">
                    <div class="aspect-[4/3] bg-gray-100 relative overflow-hidden">
                        <img src="https://images.unsplash.com/photo-1592078615290-033ee584e267?q=80&w=1000" class="w-full h-full object-cover grayscale opacity-80 group-hover:scale-110 transition-transform duration-700">
                        <div class="absolute top-6 right-6">
                            <span class="text-[8px] bg-red-500 text-white px-3 py-1.5 rounded-lg font-black uppercase tracking-widest shadow-lg shadow-red-200">Rejected</span>
                        </div>
                    </div>
                    <div class="p-8">
                        <div class="flex justify-between items-start mb-4">
                            <h4 class="text-sm font-black text-gray-900 uppercase tracking-tight leading-none">Mid-Century Armchair</h4>
                            <span class="text-[9px] font-black text-primary uppercase">4.5jt</span>
                        </div>
                        
                        <div class="bg-red-50 p-4 rounded-2xl mb-6">
                            <p class="text-[10px] text-red-500 font-bold leading-relaxed italic">
                                <i class="fa-solid fa-circle-info mr-1"></i> "Admin: Foto produk terlalu gelap."
                            </p>
                        </div>

                        <div class="pt-6 border-t border-gray-50 flex justify-between items-center">
                            <span class="text-[9px] font-black text-gray-400 uppercase tracking-widest italic text-red-400">Needs Revision</span>
                            <div class="flex items-center space-x-3">
                                <button onclick="openEditModal(2)" class="w-12 h-9 flex items-center justify-center bg-red-500 border border-red-600 rounded-xl text-white hover:bg-red-600 transition-all shadow-lg shadow-red-100">
                                    <i class="fa-solid fa-rotate-right text-xs mr-2"></i> <span class="text-[8px] font-black uppercase">Fix</span>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- EXAMPLE CARD: PRODUCT DESIGN (Pending) -->
                <div class="product-card hidden bg-white rounded-[40px] border border-gray-100 shadow-sm overflow-hidden group hover:shadow-2xl transition-all duration-500">
                    <div class="aspect-[4/3] bg-gray-100 relative overflow-hidden">
                        <img src="https://images.unsplash.com/photo-1594026112284-02bb6f3352fe?q=80&w=1000" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700">
                        <div class="absolute top-6 right-6">
                            <span class="text-[8px] bg-amber-500 text-white px-3 py-1.5 rounded-lg font-black uppercase tracking-widest shadow-lg shadow-amber-200">Pending Review</span>
                        </div>
                    </div>
                    <div class="p-8">
                        <div class="flex justify-between items-start mb-4">
                            <h4 class="text-sm font-black text-gray-900 uppercase tracking-tight leading-none">Minimalist Bamboo Lamp</h4>
                            <span class="text-[9px] font-black text-primary uppercase">1.2jt</span>
                        </div>
                        
                        <div class="flex items-center space-x-4 mb-6">
                            <div class="flex items-center text-gray-400 bg-gray-50 px-3 py-1.5 rounded-xl border border-gray-100">
                                <i class="fa-solid fa-ruler-combined text-[10px] mr-2"></i>
                                <span class="text-[9px] font-bold">30x30x45cm</span>
                            </div>
                        </div>

                        <div class="pt-6 border-t border-gray-50 flex justify-between items-center text-gray-300">
                            <span class="text-[9px] font-black uppercase italic">Awaiting Admin...</span>
                            <div class="flex items-center space-x-3">
                                <button onclick="openEditModal(3)" class="w-9 h-9 flex items-center justify-center bg-gray-50 border border-gray-100 rounded-xl text-gray-300 hover:bg-primary hover:text-white transition-all">
                                    <i class="fa-solid fa-pen-to-square text-xs"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <footer class="p-8 border-t border-gray-100 text-[9px] font-black text-gray-400 uppercase tracking-widest bg-white mt-auto text-center">
            © 2026 DECOR DESIGNER PORTAL. ALL RIGHTS RESERVED.
        </footer>
    </main>

    <!-- MODALS (Edit & Delete remain same) -->
    <div id="editModal" class="hidden fixed inset-0 z-[100] flex items-center justify-center bg-black/20 backdrop-blur-sm p-4">
        <div class="bg-white rounded-[48px] p-10 max-w-sm w-full shadow-2xl text-center space-y-6">
            <div class="w-20 h-20 bg-primary/10 text-primary rounded-full flex items-center justify-center mx-auto text-3xl"><i class="fa-solid fa-pen-nib"></i></div>
            <h4 class="text-lg font-black text-gray-900 uppercase">Modify Masterpiece?</h4>
            <div class="flex flex-col space-y-3 pt-4">
                <a id="editConfirmBtn" href="#" class="w-full bg-primary text-white py-4 rounded-2xl text-[10px] font-black uppercase tracking-widest shadow-xl shadow-primary/20 hover:scale-105 transition-all text-center">Yes, Go to Editor</a>
                <button onclick="closeEditModal()" class="text-[10px] font-black uppercase text-gray-300 tracking-widest py-2 hover:text-gray-500 transition-all">Cancel</button>
            </div>
        </div>
    </div>

    <div id="deleteModal" class="hidden fixed inset-0 z-[100] flex items-center justify-center bg-black/20 backdrop-blur-sm p-4">
        <div class="bg-white rounded-[48px] p-10 max-w-sm w-full shadow-2xl text-center space-y-6">
            <div class="w-20 h-20 bg-red-50 text-red-500 rounded-full flex items-center justify-center mx-auto text-3xl"><i class="fa-solid fa-triangle-exclamation"></i></div>
            <h4 class="text-lg font-black text-gray-900 uppercase">Are you sure?</h4>
            <div class="flex flex-col space-y-3 pt-4">
                <form id="deleteForm" method="POST">
                    <button type="submit" class="w-full bg-red-500 text-white py-4 rounded-2xl text-[10px] font-black uppercase tracking-widest shadow-xl shadow-red-200 hover:scale-105 transition-all">Yes, Delete Forever</button>
                </form>
                <button onclick="closeDeleteModal()" class="text-[10px] font-black uppercase text-gray-300 tracking-widest py-2 hover:text-gray-500 transition-all">Cancel</button>
            </div>
        </div>
    </div>

    <script>
        function toggleSidebar() {
            document.getElementById('sidebar').classList.toggle('sidebar-closed');
            document.getElementById('main-content').classList.toggle('main-full');
        }

        // TAB SWITCH LOGIC
        let currentTab = 'interior';
        function switchTab(type) {
            currentTab = type;
            const intTab = document.getElementById('tab-interior');
            const prodTab = document.getElementById('tab-product');
            const interiorCards = document.querySelectorAll('.interior-card');
            const productCards = document.querySelectorAll('.product-card');

            if(type === 'interior') {
                intTab.classList.add('tab-active'); intTab.classList.remove('text-gray-400');
                prodTab.classList.remove('tab-active'); prodTab.classList.add('text-gray-400');
                interiorCards.forEach(c => c.classList.remove('hidden'));
                productCards.forEach(c => c.classList.add('hidden'));
            } else {
                prodTab.classList.add('tab-active'); prodTab.classList.remove('text-gray-400');
                intTab.classList.remove('tab-active'); intTab.classList.add('text-gray-400');
                productCards.forEach(c => c.classList.remove('hidden'));
                interiorCards.forEach(c => c.classList.add('hidden'));
            }
            filterPortfolio(); // Re-apply search when switching tabs
        }

        // NEW: SEARCH FILTER LOGIC FOR PORTFOLIO
        function filterPortfolio() {
            const input = document.getElementById("portfolioSearch");
            const filter = input.value.toUpperCase();
            const cards = document.querySelectorAll(currentTab === 'interior' ? '.interior-card' : '.product-card');
            const otherCards = document.querySelectorAll(currentTab === 'interior' ? '.product-card' : '.interior-card');

            // Hide cards from the other tab regardless of search
            otherCards.forEach(card => card.classList.add('hidden'));

            cards.forEach(card => {
                const title = card.querySelector('h4').innerText;
                if (title.toUpperCase().indexOf(filter) > -1) {
                    card.classList.remove('hidden');
                } else {
                    card.classList.add('hidden');
                }
            });
        }

        function openEditModal(id) {
            const editBtn = document.getElementById('editConfirmBtn');
            editBtn.href = `/designer/portfolio/${id}/edit`;
            document.getElementById('editModal').classList.remove('hidden');
        }
        function closeEditModal() { document.getElementById('editModal').classList.add('hidden'); }
        function openDeleteModal(id) {
            const form = document.getElementById('deleteForm');
            form.action = `/designer/portfolio/${id}/destroy`; 
            document.getElementById('deleteModal').classList.remove('hidden');
        }
        function closeDeleteModal() { document.getElementById('deleteModal').classList.add('hidden'); }

        window.onclick = function(event) {
            if (event.target.id == 'deleteModal') closeDeleteModal();
            if (event.target.id == 'editModal') closeEditModal();
        }
    </script>
</body>
</html>