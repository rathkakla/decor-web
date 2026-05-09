<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Decor Designer - Admin Support Chat</title>
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
        
        .bubble-admin { border-radius: 2px 18px 18px 18px; }
        .bubble-designer { border-radius: 18px 2px 18px 18px; }
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
            <div class="flex items-center space-x-6">
                <i onclick="toggleSidebar()" class="fa-solid fa-bars-staggered text-xl mr-4 cursor-pointer hover:scale-110 transition-transform"></i>
                <div class="flex items-center space-x-6">
                    <h2 class="font-black text-[10px] uppercase tracking-[0.3em] leading-none">Admin Support Chat</h2>
                    <div class="h-4 w-px bg-white/20"></div>
                    <a href="{{ route('designer.support') }}" class="text-[9px] font-black uppercase tracking-widest text-white/70 hover:text-white transition-all flex items-center">
                        <i class="fa-solid fa-chevron-left mr-2"></i> Back to Center
                    </a>
                </div>
            </div>
            <div class="flex items-center space-x-6">
                <i class="fa-regular fa-bell text-xl"></i>
                <div class="flex items-center space-x-3 bg-white/10 px-3 py-1.5 rounded-xl border border-white/20">
                    <span class="text-[10px] font-black uppercase tracking-widest">Elena Vance</span>
                    <div class="w-8 h-8 rounded-lg bg-white text-primary flex items-center justify-center font-bold">EV</div>
                </div>
            </div>
        </header>

        <!-- CHAT INTERFACE -->
        <main class="flex-1 flex flex-col p-10 overflow-hidden">
            <div class="mb-8">
                <h2 class="text-4xl font-black tracking-tight text-gray-800 italic">Chat with Admin</h2>
                <p class="text-xs text-gray-400 mt-2 font-bold uppercase tracking-widest">Direct line to DECOR's official support team</p>
            </div>

            <div class="bg-white rounded-[48px] border border-gray-100 shadow-sm flex flex-1 overflow-hidden flex-col">
                
                <div class="px-10 py-6 border-b border-gray-50 bg-white flex justify-between items-center">
                    <div class="flex items-center space-x-4">
                        <div class="w-12 h-12 rounded-2xl bg-primary/10 flex items-center justify-center font-black text-primary text-xs">
                            AD
                        </div>
                        <div>
                            <h4 class="text-[10px] font-black text-gray-800 uppercase tracking-widest leading-none">Official Admin Support</h4>
                            <p class="text-[8px] font-bold text-green-500 uppercase mt-1 tracking-tighter">Online Response</p>
                        </div>
                    </div>
                </div>

                <!-- Messages -->
                <div class="flex-1 overflow-y-auto p-12 space-y-8 bg-[#FCFBFB]">
                    <div class="flex items-start space-x-4 max-w-[70%]">
                        <div class="bg-white bubble-admin p-6 text-xs font-semibold leading-relaxed border border-gray-50 shadow-sm text-gray-600 italic">
                            Halo Audri! Ada yang bisa kami bantu terkait akun desainer atau proyek kamu hari ini? 😊
                        </div>
                    </div>

                    <div class="flex items-end space-x-4 max-w-[70%] ml-auto text-right flex-row-reverse">
                        <div class="bg-primary text-white bubble-designer p-6 text-xs font-semibold leading-relaxed mr-4 shadow-xl shadow-primary/20">
                            Halo Admin! Saya ingin bertanya terkait proses verifikasi portofolio baru saya yang masih pending.
                        </div>
                    </div>
                </div>

                <!-- Input -->
                <div class="p-10 bg-white border-t border-gray-50">
                    <div class="flex items-center space-x-6">
                        <button class="text-gray-300 hover:text-primary transition-all"><i class="fa-solid fa-paperclip text-xl"></i></button>
                        <div class="flex-1">
                            <input type="text" placeholder="Write a message to Admin Support..." class="w-full bg-gray-50 rounded-2xl py-5 px-8 text-xs font-bold outline-none border-2 border-transparent focus:border-primary/10 transition-all shadow-inner">
                        </div>
                        <button class="bg-primary text-white w-14 h-14 rounded-2xl flex items-center justify-center shadow-xl shadow-primary/20 hover:scale-105 transition-all">
                            <i class="fa-solid fa-paper-plane text-sm"></i>
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