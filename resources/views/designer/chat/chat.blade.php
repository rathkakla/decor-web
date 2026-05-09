<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Decor Designer - Chat Center</title>
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
        ::-webkit-scrollbar { width: 4px; }
        ::-webkit-scrollbar-thumb { background: #E5E7EB; border-radius: 10px; }
    </style>
</head>
<body class="text-gray-800">

    <aside id="sidebar" class="w-64 bg-white border-r border-gray-200 flex flex-col fixed h-full z-50 sidebar-transition">
        <div class="p-8">
            <h1 class="text-2xl font-bold tracking-widest text-primary uppercase leading-none">DECOR</h1>
            <p class="text-[10px] text-gray-400 mt-1 uppercase tracking-widest italic font-bold">Designer Portal</p>
        </div>
        
        <nav class="flex-1 px-4 space-y-1">
            <a href="{{ route('designer.dashboard') }}" class="flex items-center px-4 py-3 text-xs font-bold text-gray-400 hover:text-primary transition-all rounded-lg"><i class="fa-solid fa-table-columns mr-3 w-5 text-center"></i> Dashboard</a>
            <a href="{{ route('designer.portfolio.index') }}" class="flex items-center px-4 py-3 text-xs font-bold text-gray-400 hover:text-primary transition-all rounded-lg"><i class="fa-solid fa-briefcase mr-3 w-5 text-center"></i> Kelola Portofolio</a>
            <a href="{{ route('designer.consultations.index') }}" class="flex items-center px-4 py-3 text-xs font-bold text-gray-400 hover:text-primary transition-all rounded-lg"><i class="fa-solid fa-calendar-check mr-3 w-5 text-center"></i>Konsultasi</a>
            <a href="{{ route('designer.chats') }}" class="flex items-center px-4 py-3 text-xs font-bold active-link transition-all rounded-lg"><i class="fa-solid fa-comment-dots mr-3 w-5 text-center"></i>Chat</a>
            <a href="{{ route('designer.reviews') }}" class="flex items-center px-4 py-3 text-xs font-bold text-gray-400 hover:text-primary transition-all rounded-lg"><i class="fa-solid fa-star mr-3 w-5 text-center"></i> Review & Rating</a>
            <a href="{{ route('designer.reports') }}" class="flex items-center px-4 py-3 text-xs font-bold text-gray-400 hover:text-primary transition-all rounded-lg"><i class="fa-solid fa-chart-line mr-3 w-5 text-center"></i> Laporan</a>
        </nav>
        <div class="p-4 border-t border-gray-100 space-y-1">
            <a href="{{ route('designer.settings') }}" class="flex items-center px-4 py-3 text-xs font-bold text-gray-400 hover:text-primary transition-all rounded-lg"><i class="fa-solid fa-gear mr-3 w-5 text-center"></i> Settings</a>
            <a href="{{ route('designer.support') }}" class="flex items-center px-4 py-3 text-xs font-bold text-gray-400 hover:text-primary transition-all rounded-lg"><i class="fa-solid fa-circle-question mr-3 w-5 text-center"></i> Support</a>
        </div>
    </aside>

    <main id="main-content" class="ml-64 flex flex-col h-screen sidebar-transition">
        <header class="h-16 bg-primary flex items-center justify-between px-8 sticky top-0 z-40 shadow-sm text-white flex-shrink-0">
            <div class="flex items-center">
                <i onclick="toggleSidebar()" class="fa-solid fa-bars-staggered text-xl mr-4 cursor-pointer hover:scale-110 transition-transform"></i>
                <h2 class="font-black text-[10px] uppercase tracking-widest leading-none">Message Center</h2>
            </div>
            <div class="flex items-center space-x-6">
                <i class="fa-regular fa-bell text-xl"></i>
                <div class="flex items-center space-x-3 bg-white/10 px-3 py-1.5 rounded-xl border border-white/20">
                    <span class="text-[10px] font-black uppercase tracking-widest">Elena Vance</span>
                    <div class="w-8 h-8 rounded-lg bg-white text-primary flex items-center justify-center font-bold">EV</div>
                </div>
            </div>
        </header>

        <div class="flex-1 flex overflow-hidden">
            <aside class="w-80 bg-white border-r border-gray-100 flex flex-col flex-shrink-0">
                <div class="p-6">
                    <input type="text" placeholder="Search conversations..." class="w-full bg-gray-50 border-none rounded-xl py-3 px-4 text-[10px] font-black uppercase tracking-widest outline-none focus:ring-1 focus:ring-primary/20">
                </div>
                <div class="flex-1 overflow-y-auto">
                    <div class="px-6 py-5 bg-primary/5 border-r-4 border-primary flex items-center space-x-4 cursor-pointer">
                        <div class="w-12 h-12 rounded-2xl bg-gray-900 text-white flex items-center justify-center font-bold">ER</div>
                        <div class="flex-1 min-w-0">
                            <h4 class="text-xs font-black text-gray-900 uppercase">Elena Rodriguez</h4>
                            <p class="text-[10px] text-primary font-bold truncate">Sure, let's update the sofa color...</p>
                        </div>
                    </div>
                </div>
            </aside>

            <section class="flex-1 flex flex-col bg-[#FDFDFD]">
                <div class="px-8 py-4 bg-white border-b border-gray-50 flex justify-between items-center">
                    <div>
                        <h4 class="text-[11px] font-black text-gray-900 uppercase tracking-widest">Elena Rodriguez</h4>
                        <p class="text-[8px] font-black text-green-500 uppercase tracking-widest italic">● Online Now</p>
                    </div>
                    <div class="flex items-center bg-gray-50 px-4 py-2 rounded-2xl border border-gray-100">
                        <div class="mr-4">
                            <p class="text-[7px] font-black text-gray-300 uppercase italic leading-none mb-1">Active Project</p>
                            <p class="text-[10px] font-black text-gray-900 uppercase">Penthouse Interior</p>
                        </div>
                        <!-- FIX: Rute diarahkan ke Workspace Tunggal -->
                        <a href="{{ route('designer.consultations.show') }}" class="text-[9px] font-black text-primary border-l border-gray-200 pl-4 uppercase hover:underline tracking-widest">View Detail</a>
                    </div>
                </div>

                <div class="flex-1 overflow-y-auto p-10 space-y-8">
                    <div class="max-w-2xl bg-white border border-gray-100 p-6 rounded-2xl shadow-sm text-xs font-semibold italic text-gray-600 leading-relaxed">
                        Hello! I'm interested in the Sienna Oak Table. Is it currently in stock?
                    </div>
                    <div class="max-w-2xl ml-auto bg-primary text-white p-6 rounded-2xl shadow-xl shadow-primary/10 text-xs font-semibold leading-relaxed">
                        Hello Elena! Yes, we have 3 units remaining in our warehouse. ✨
                    </div>
                </div>

                <div class="p-8 bg-white border-t border-gray-50 flex items-center space-x-4">
                    <button class="text-gray-300 hover:text-primary"><i class="fa-solid fa-paperclip"></i></button>
                    <input type="text" placeholder="Type message..." class="flex-1 bg-gray-50 border-none py-4 px-6 rounded-2xl text-[11px] font-bold outline-none">
                    <button class="bg-primary text-white w-12 h-12 rounded-[22px] flex items-center justify-center shadow-lg"><i class="fa-solid fa-paper-plane"></i></button>
                </div>
            </section>
        </div>
        <footer class="p-8 border-t border-gray-100 text-[9px] font-black text-gray-400 uppercase tracking-widest bg-white mt-auto text-center">
            © 2026 DECOR DESIGNER PORTAL. ALL RIGHTS RESERVED.
        </footer>
    </main>

    <script>function toggleSidebar() { document.getElementById('sidebar').classList.toggle('sidebar-closed'); document.getElementById('main-content').classList.toggle('main-full'); }</script>
</body>
</html>