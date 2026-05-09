<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Decor Designer - Reviews & Ratings</title>
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
        ::-webkit-scrollbar { width: 5px; }
        ::-webkit-scrollbar-thumb { background: #B5733A; border-radius: 10px; }
    </style>
</head>
<body class="text-gray-800 flex h-screen">

    <!-- SIDEBAR (8 Menus Consistent) -->
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
                <i class="fa-solid fa-calendar-check mr-3 w-5 text-center"></i> Jadwal Konsultasi
            </a>
            <a href="{{ route('designer.chats') }}" class="flex items-center px-4 py-3 text-xs font-bold text-gray-400 hover:text-primary transition-all rounded-lg">
                <i class="fa-solid fa-comment-dots mr-3 w-5 text-center"></i> Designer Chat
            </a>
            <a href="{{ route('designer.reviews') }}" class="flex items-center px-4 py-3 text-xs font-bold active-link transition-all rounded-lg">
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
            <a href="{{ route('designer.support') }}" class="flex items-center px-4 py-3 text-xs font-bold text-gray-400 hover:text-primary transition-all rounded-lg">
                <i class="fa-solid fa-circle-question mr-3 w-5 text-center"></i> Support
            </a>
        </div>
    </aside>

    <!-- MAIN AREA -->
    <div id="main-content" class="flex-1 flex flex-col min-w-0 overflow-hidden ml-64 sidebar-transition">
        
        <!-- HEADER (CONSISTENT) -->
        <header class="h-16 bg-primary flex items-center justify-between px-8 sticky top-0 z-40 shadow-sm text-white">
            <div class="flex items-center">
                <i onclick="toggleSidebar()" class="fa-solid fa-bars-staggered text-xl mr-4 cursor-pointer hover:scale-110 transition-transform"></i>
                <h2 class="font-black text-[10px] uppercase tracking-widest leading-none">Review & Rating</h2>
            </div>
            <div class="flex items-center space-x-6">
                <i class="fa-regular fa-bell text-xl"></i>
                <div class="flex items-center space-x-3 bg-white/10 px-3 py-1.5 rounded-xl border border-white/20">
                    <span class="text-[10px] font-black uppercase tracking-widest">Elena Vance</span>
                    <div class="w-8 h-8 rounded-lg bg-white text-primary flex items-center justify-center font-bold">EV</div>
                </div>
            </div>
        </header>

        <main class="flex-1 overflow-y-auto p-10 space-y-10 custom-scroll pb-24">
            
            <div class="flex justify-between items-end">
                <div>
                    <h3 class="text-2xl font-black text-gray-900 tracking-tight leading-none">Customer Insights</h3>
                    <p class="text-xs text-gray-400 font-bold italic mt-2">Insights into your customer's furniture experiences.</p>
                </div>
                
                <!-- NEW: Kiyowo Search Bar (Consistent with other pages) -->
                <div class="relative group">
                    <i class="fa-solid fa-magnifying-glass absolute left-4 top-1/2 -translate-y-1/2 text-gray-300 group-focus-within:text-primary transition-colors text-xs"></i>
                    <input type="text" placeholder="Search reviews..." class="bg-white border-none rounded-2xl py-3 pl-10 pr-6 text-[10px] font-bold text-gray-900 focus:ring-2 focus:ring-primary/20 outline-none w-64 shadow-sm transition-all">
                </div>
            </div>

            <!-- OVERALL RATING SECTION -->
            <div class="flex flex-col lg:flex-row gap-6">
                <div class="flex-1 bg-white p-10 rounded-[40px] shadow-sm border border-gray-100 flex flex-col md:flex-row items-center gap-10">
                    <div class="text-center md:text-left">
                        <p class="text-[9px] font-black text-gray-300 uppercase tracking-widest mb-2">Overall Rating</p>
                        <h3 class="text-6xl font-black text-gray-900 tracking-tighter">4.9 <span class="text-xl text-gray-300">/ 5.0</span></h3>
                        <div class="flex text-orange-400 text-sm mt-3 justify-center md:justify-start">
                            <i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i>
                        </div>
                        <p class="text-[9px] font-bold text-gray-400 uppercase mt-4 tracking-widest">Based on 1,248 Reviews</p>
                    </div>

                    <div class="flex-1 w-full space-y-3">
                        <div class="flex items-center space-x-4">
                            <span class="text-[10px] font-black text-gray-400 w-4">5</span>
                            <div class="flex-1 h-2 bg-gray-50 rounded-full overflow-hidden"><div class="bg-primary h-full w-[88%]"></div></div>
                            <span class="text-[10px] font-black text-gray-400 w-8">88%</span>
                        </div>
                        <div class="flex items-center space-x-4">
                            <span class="text-[10px] font-black text-gray-400 w-4">4</span>
                            <div class="flex-1 h-2 bg-gray-50 rounded-full overflow-hidden"><div class="bg-primary h-full w-[9%]"></div></div>
                            <span class="text-[10px] font-black text-gray-400 w-8">9%</span>
                        </div>
                        <div class="flex items-center space-x-4">
                            <span class="text-[10px] font-black text-gray-400 w-4">3</span>
                            <div class="flex-1 h-2 bg-gray-50 rounded-full overflow-hidden"><div class="bg-primary h-full w-[2%]"></div></div>
                            <span class="text-[10px] font-black text-gray-400 w-8">2%</span>
                        </div>
                    </div>
                </div>

                <div class="w-full lg:w-96 bg-primary p-10 rounded-[40px] text-white flex flex-col justify-between relative overflow-hidden shadow-xl shadow-primary/20">
                    <div class="relative z-10">
                        <p class="text-[9px] font-black text-white/50 uppercase tracking-widest mb-1">Sentiment Score</p>
                        <h3 class="text-4xl font-black tracking-tight">Excellent</h3>
                        
                        <div class="mt-10">
                            <p class="text-[9px] font-black text-white/50 uppercase tracking-widest mb-1">Response Rate</p>
                            <h3 class="text-4xl font-black tracking-tight">99.4%</h3>
                        </div>
                    </div>
                    <div class="mt-8 flex -space-x-3 relative z-10">
                        <div class="w-10 h-10 rounded-full border-4 border-primary bg-white text-primary flex items-center justify-center font-bold text-xs">A</div>
                        <div class="w-10 h-10 rounded-full border-4 border-primary bg-white text-primary flex items-center justify-center font-bold text-xs">B</div>
                        <div class="w-10 h-10 rounded-full border-4 border-primary bg-gray-200 text-gray-400 flex items-center justify-center font-bold text-[8px]">+12</div>
                    </div>
                    <div class="absolute -bottom-10 -right-10 w-40 h-40 bg-white/10 rounded-full"></div>
                </div>
            </div>

            <!-- REVIEW CARD -->
            <div class="bg-white p-10 rounded-[48px] border border-gray-100 shadow-sm space-y-10">
                <div class="flex flex-col lg:flex-row gap-10">
                    <div class="flex-1 space-y-6">
                        <div class="flex items-center space-x-4">
                            <div class="w-12 h-12 rounded-2xl bg-gray-50 flex items-center justify-center text-gray-200"><i class="fa-solid fa-user"></i></div>
                            <div>
                                <div class="flex items-center space-x-3">
                                    <h4 class="font-black text-sm text-gray-900 tracking-tight leading-none">Julianne Moore</h4>
                                    <div class="flex text-orange-400 text-[8px]">
                                        <i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i>
                                    </div>
                                </div>
                                <p class="text-[9px] font-bold text-gray-400 uppercase tracking-widest mt-2 leading-none">Verified Purchase • 2 Days Ago</p>
                            </div>
                        </div>

                        <p class="text-xs font-medium text-gray-600 leading-relaxed italic">
                            "The Archi-Curve lounge chair is even more stunning in person. The velvet texture is exquisite and the ergonomic support exceeded my expectations. It fits perfectly into my minimalist reading corner. Fast delivery and premium packaging!"
                        </p>

                        <div class="flex items-center space-x-3">
                            <div class="w-20 h-20 bg-gray-100 rounded-2xl overflow-hidden border border-gray-50"><img src="https://images.unsplash.com/photo-1598300042247-d088f8ab3a91?q=80&w=400" class="w-full h-full object-cover"></div>
                            <div class="w-20 h-20 bg-gray-100 rounded-2xl overflow-hidden border border-gray-50"><img src="https://images.unsplash.com/photo-1555041469-a586c61ea9bc?q=80&w=400" class="w-full h-full object-cover"></div>
                        </div>
                    </div>

                    <div class="w-full lg:w-48 flex-shrink-0 flex flex-col items-center text-center space-y-4">
                        <div class="w-full aspect-square rounded-[32px] overflow-hidden shadow-sm border border-gray-100">
                            <img src="https://images.unsplash.com/photo-1598300042247-d088f8ab3a91?q=80&w=400" class="w-full h-full object-cover">
                        </div>
                        <div>
                            <p class="text-[8px] font-black text-gray-300 uppercase tracking-widest">Reviewed Item</p>
                            <h5 class="text-xs font-black text-gray-900 mt-1">Archi-Curve Velvet Lounge</h5>
                            <p class="text-sm font-black text-primary mt-1">$1,240</p>
                        </div>
                        <a href="#" class="text-[8px] font-black text-gray-300 uppercase tracking-widest hover:text-primary transition-all">
                            <i class="fa-solid fa-eye mr-1"></i> View Product Page
                        </a>
                    </div>
                </div>

                <!-- DESIGNER RESPONSE AREA -->
                <div class="bg-gray-50/50 p-8 rounded-[40px] border border-gray-100 space-y-6">
                    <h5 class="text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] italic">Designer Response</h5>
                    <textarea placeholder="Write your reply to Julianne..." class="w-full bg-white border border-gray-100 rounded-3xl p-6 text-sm font-medium text-gray-600 focus:ring-2 focus:ring-primary/10 outline-none transition-all resize-none h-24 shadow-sm" spellcheck="false"></textarea>
                    <div class="flex justify-end">
                        <button class="bg-primary text-white px-10 py-3.5 rounded-2xl text-[10px] font-black uppercase tracking-widest shadow-xl shadow-primary/20 hover:scale-105 transition-all">
                            Post Reply
                        </button>
                    </div>
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