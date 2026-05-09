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

    <!-- 1. SIDEBAR (8 Menus Consistent) -->
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
        </div>
    </aside>

    <div id="main-content" class="flex-1 flex flex-col min-w-0 overflow-hidden ml-64 sidebar-transition">
        
        <!-- HEADER (CONSISTENT LUXURY) -->
        <header class="h-16 bg-primary flex items-center justify-between px-8 sticky top-0 z-40 shadow-sm text-white">
            <div class="flex items-center">
                <i onclick="toggleSidebar()" class="fa-solid fa-bars-staggered text-xl mr-4 cursor-pointer hover:scale-110 transition-transform"></i>
                <h2 class="font-black text-[10px] uppercase tracking-[0.3em] leading-none">General Settings</h2>
            </div>
            <div class="flex items-center space-x-6">
                <button class="bg-white/10 border border-white/20 text-white px-8 py-2.5 rounded-xl text-[10px] font-black uppercase shadow-lg hover:bg-white hover:text-primary transition-all">Save Profile</button>
                <div class="flex items-center space-x-3 bg-white/10 px-3 py-1.5 rounded-xl border border-white/20">
                    <span class="text-[10px] font-black uppercase tracking-widest">Elena Vance</span>
                    <div class="w-8 h-8 rounded-lg bg-white text-primary flex items-center justify-center font-bold">EV</div>
                </div>
            </div>
        </header>

        <main class="flex-1 overflow-y-auto p-10 space-y-8 custom-scroll pb-24">
            <div class="max-w-7xl mx-auto space-y-8">
                
                <!-- TAB NAVIGATION -->
                <div class="flex border-b border-gray-200">
                    <a href="{{ route('designer.settings') }}" class="px-8 py-4 text-[10px] font-black uppercase tracking-widest tab-active transition-all">Studio Info</a>
                    <a href="{{ route('designer.settings.bank') }}" class="px-8 py-4 text-[10px] font-black uppercase tracking-widest text-gray-400 hover:text-primary transition-all">Bank & Payout</a>
                </div>

                <!-- BANNER & AVATAR -->
                <section class="relative mt-6">
                    <div class="w-full h-64 bg-gray-100 rounded-[48px] overflow-hidden group relative border border-gray-100 shadow-sm">
                        <img src="https://images.unsplash.com/photo-1618221195710-dd6b41faaea6?q=80&w=1200" class="w-full h-full object-cover">
                        <div class="absolute inset-0 bg-black/20 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-all cursor-pointer">
                            <span class="bg-white/20 backdrop-blur-md text-white text-[9px] font-black uppercase px-8 py-3 rounded-2xl border border-white/30">Update Studio Cover</span>
                        </div>
                    </div>
                    <div class="absolute -bottom-12 left-16 group">
                        <div class="w-40 h-40 rounded-[40px] bg-white p-2 shadow-2xl relative overflow-hidden">
                            <img src="https://ui-avatars.com/api/?name=Elena+Vance&background=B5733A&color=fff" class="w-full h-full rounded-[34px] object-cover">
                            <div class="absolute inset-0 bg-black/40 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-all cursor-pointer">
                                <i class="fa-solid fa-camera text-white text-2xl"></i>
                            </div>
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
                                    <input type="text" value="Elena Vance Design" class="w-full bg-gray-50 border-none rounded-2xl py-4 px-6 text-sm font-bold outline-none focus:ring-2 focus:ring-primary/10">
                                </div>
                                <div class="space-y-3">
                                    <label class="text-[9px] font-black uppercase text-gray-400 ml-2">Specialization</label>
                                    <select class="w-full bg-gray-50 border-none rounded-2xl py-4 px-6 text-sm font-bold outline-none appearance-none cursor-pointer">
                                        <option>Interior Specialist</option><option>Furniture Curator</option>
                                    </select>
                                </div>
                            </div>
                            <div class="space-y-3">
                                <label class="text-[9px] font-black uppercase text-gray-400 ml-2">Design Philosophy & Bio</label>
                                <textarea class="w-full bg-gray-50 border-none rounded-[32px] py-4 px-6 text-xs font-medium leading-relaxed h-40 outline-none focus:ring-2 focus:ring-primary/10 transition-all">Focused on creating timeless spaces with organic textures and natural lighting.</textarea>
                            </div>
                            <div class="space-y-4">
                                <label class="text-[9px] font-black uppercase text-gray-400 ml-2">Skills & Software</label>
                                <div class="flex flex-wrap gap-2">
                                    <span class="bg-blue-50 border border-blue-100 px-4 py-2.5 rounded-xl text-[9px] font-black uppercase text-blue-400 flex items-center">AutoCAD <i class="fa-solid fa-xmark ml-3 cursor-pointer"></i></span>
                                    <span class="bg-red-50 border border-red-100 px-4 py-2.5 rounded-xl text-[9px] font-black uppercase text-red-400 flex items-center">SketchUp <i class="fa-solid fa-xmark ml-3 cursor-pointer"></i></span>
                                    <button onclick="toggleSkillModal()" class="bg-primary text-white px-5 py-2.5 rounded-xl text-[9px] font-black uppercase shadow-lg shadow-primary/10 hover:scale-105 transition-all">+ Add Skill</button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- RIGHT COLUMN -->
                    <div class="col-span-12 lg:col-span-5 space-y-8 flex flex-col">
                        <div class="bg-white p-10 rounded-[48px] border border-gray-100 shadow-sm space-y-8 flex-1">
                            <div class="flex justify-between items-center">
                                <h3 class="text-xs font-black uppercase tracking-widest text-gray-300 italic">Studio Status</h3>
                                <label class="switch"><input type="checkbox" checked><span class="slider"></span></label>
                            </div>
                            <div class="p-8 bg-green-50/50 rounded-[32px] border border-green-100">
                                <p class="text-[10px] font-black text-green-700 uppercase leading-none">Currently: Open for Projects</p>
                                <p class="text-[9px] text-green-600 font-medium mt-3 italic tracking-tight">Klien bisa langsung kirim permintaan konsultasi.</p>
                            </div>
                        </div>

                        <div class="bg-white p-10 rounded-[48px] border border-gray-100 shadow-sm space-y-6 text-center flex-1">
                            <div class="flex justify-between items-center mb-2">
                                <h3 class="text-xs font-black uppercase tracking-widest text-gray-300 italic">Operating Hours</h3>
                                <button onclick="toggleTimeModal()" class="text-[9px] font-black uppercase text-primary hover:scale-105 transition-all">Change</button>
                            </div>
                            <div class="grid grid-cols-2 gap-4">
                                <div class="bg-gray-50 p-6 rounded-[32px] border border-gray-100"><p class="text-[9px] font-black text-gray-400 uppercase mb-1">From</p><p class="text-lg font-black text-gray-900">09:00 AM</p></div>
                                <div class="bg-gray-50 p-6 rounded-[32px] border border-gray-100"><p class="text-[9px] font-black text-gray-400 uppercase mb-1">Until</p><p class="text-lg font-black text-gray-900">06:00 PM</p></div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- ROW 2: DIGITAL PRESENCE FULL WIDTH -->
                <div class="col-span-12">
                    <div class="bg-white p-10 rounded-[48px] border border-gray-100 shadow-sm space-y-8">
                        <h3 class="text-xs font-black uppercase tracking-widest text-gray-300 italic leading-none">Digital Presence</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                            <div class="relative">
                                <i class="fa-brands fa-behance absolute left-6 top-1/2 -translate-y-1/2 text-gray-300 text-lg"></i>
                                <input type="text" placeholder="Behance URL" class="w-full bg-gray-50 border-none rounded-2xl py-5 pl-14 pr-8 text-[11px] font-bold outline-none focus:ring-2 focus:ring-primary/10">
                            </div>
                            <div class="relative">
                                <i class="fa-brands fa-instagram absolute left-6 top-1/2 -translate-y-1/2 text-gray-300 text-lg"></i>
                                <input type="text" placeholder="Instagram Username" class="w-full bg-gray-50 border-none rounded-2xl py-5 pl-14 pr-8 text-[11px] font-bold outline-none focus:ring-2 focus:ring-primary/10">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- ROW 3: SERVICES & AWARDS SIDE-BY-SIDE -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 pb-10">
                    <!-- Services -->
                    <div class="bg-white p-10 rounded-[48px] border border-gray-100 shadow-sm space-y-8">
                        <div class="flex justify-between items-center">
                            <h3 class="text-xs font-black uppercase tracking-widest text-gray-300 italic">Professional Services</h3>
                            <button onclick="toggleServiceModal()" class="text-primary text-[9px] font-black uppercase tracking-widest hover:scale-110 transition-all">+ Add</button>
                        </div>
                        <div class="space-y-4">
                            <div class="p-6 bg-gray-50 border border-gray-100 rounded-[32px] group relative hover:bg-white transition-all duration-300">
                                <div class="flex justify-between items-start">
                                    <div class="w-12 h-12 bg-white rounded-2xl flex items-center justify-center text-primary shadow-sm mb-4"><i class="fa-solid fa-house-laptop"></i></div>
                                    <div class="flex space-x-2 opacity-0 group-hover:opacity-100 transition-all">
                                        <button onclick="toggleServiceModal()" class="w-8 h-8 bg-white rounded-lg text-gray-300 hover:text-primary shadow-sm"><i class="fa-solid fa-pen text-[10px]"></i></button>
                                        <button onclick="toggleDeleteModal('Service', 'Interior Consultation')" class="w-8 h-8 bg-white rounded-lg text-gray-300 hover:text-red-400 shadow-sm"><i class="fa-solid fa-trash text-[10px]"></i></button>
                                    </div>
                                </div>
                                <h4 class="text-[10px] font-black uppercase text-gray-800 tracking-widest">Interior Consultation</h4>
                                <p class="text-[9px] text-gray-400 mt-2 italic leading-relaxed">"1-on-1 consultation to analyze your space."</p>
                            </div>
                        </div>
                    </div>

                    <!-- Awards -->
                    <div class="bg-white p-10 rounded-[48px] border border-gray-100 shadow-sm space-y-8">
                        <div class="flex justify-between items-center">
                            <h3 class="text-xs font-black uppercase tracking-widest text-gray-300 italic">Awards & Recognition</h3>
                            <button onclick="toggleAwardModal()" class="text-primary text-[9px] font-black uppercase tracking-widest hover:scale-110 transition-all">+ Add</button>
                        </div>
                        <div class="space-y-4">
                            <div class="p-6 bg-gray-50 border border-gray-100 rounded-[32px] group hover:bg-white transition-all duration-300">
                                <div class="flex justify-between items-center">
                                    <div class="flex items-center space-x-5">
                                        <div class="text-[10px] font-black text-primary bg-white px-4 py-3 rounded-2xl shadow-sm">2024</div>
                                        <div><h4 class="text-[10px] font-black uppercase text-gray-800 tracking-widest leading-none">Designer of the Year</h4><p class="text-[8px] text-gray-400 font-bold uppercase mt-1 tracking-tighter">ELLE Decoration</p></div>
                                    </div>
                                    <div class="flex space-x-2 opacity-0 group-hover:opacity-100 transition-all">
                                        <button onclick="toggleAwardModal()" class="w-8 h-8 bg-white rounded-lg text-gray-300 hover:text-primary shadow-sm"><i class="fa-solid fa-pen text-[10px]"></i></button>
                                        <button onclick="toggleDeleteModal('Award', 'Designer of the Year')" class="w-8 h-8 bg-white rounded-lg text-gray-300 hover:text-red-400 shadow-sm"><i class="fa-solid fa-trash text-[10px]"></i></button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>

       <footer class="p-8 border-t border-gray-100 text-[9px] font-black text-gray-400 uppercase tracking-widest bg-white mt-auto text-center">
            © 2026 DECOR DESIGNER PORTAL. ALL RIGHTS RESERVED.
        </footer>
    </div>

    <!-- MODALS -->
    <div id="deleteModal" class="fixed inset-0 z-[200] flex items-center justify-center opacity-0 pointer-events-none modal-overlay"><div onclick="toggleDeleteModal()" class="absolute inset-0 bg-black/40 backdrop-blur-sm"></div><div class="relative bg-white w-full max-w-sm rounded-[48px] p-10 shadow-2xl scale-90 opacity-0 modal-content text-center"><div class="w-20 h-20 bg-red-50 text-red-500 rounded-full flex items-center justify-center mx-auto mb-6 text-3xl"><i class="fa-solid fa-trash-can"></i></div><h3 class="text-xl font-black uppercase">Are you sure, bebs?</h3><p class="text-[11px] text-gray-400 mt-3 italic leading-relaxed"><span id="deleteItemName" class="text-red-500 not-italic uppercase tracking-widest">this item</span> will be deleted forever.</p><div class="flex flex-col space-y-3 mt-8"><button onclick="toggleDeleteModal()" class="w-full py-4 bg-red-500 text-white rounded-2xl text-[10px] font-black uppercase shadow-xl hover:scale-105 transition-all">Yes, Delete It</button><button onclick="toggleDeleteModal()" class="w-full py-4 bg-gray-50 text-gray-400 rounded-2xl text-[10px] font-black uppercase hover:bg-gray-100 transition-all">Cancel</button></div></div></div>
    <div id="awardModal" class="fixed inset-0 z-[100] flex items-center justify-center opacity-0 pointer-events-none modal-overlay"><div onclick="toggleAwardModal()" class="absolute inset-0 bg-black/40 backdrop-blur-sm"></div><div class="relative bg-white w-full max-w-xl rounded-[48px] p-10 shadow-2xl scale-90 opacity-0 modal-content"><div class="flex justify-between items-center mb-8"><div><h3 class="text-xl font-black text-gray-900 tracking-tight leading-none uppercase">Achievement</h3><p class="text-[10px] text-gray-400 font-bold uppercase tracking-widest mt-2">Professional recognition details</p></div><button onclick="toggleAwardModal()" class="w-10 h-10 rounded-full bg-gray-50 text-gray-400 hover:text-red-500 transition-colors"><i class="fa-solid fa-xmark"></i></button></div><div class="grid grid-cols-3 gap-4 mb-4"><input type="number" value="2026" class="col-span-1 bg-gray-50 border-none rounded-2xl py-4 px-6 text-sm font-bold outline-none"><input type="text" placeholder="Award Title" class="col-span-2 bg-gray-50 border-none rounded-2xl py-4 px-6 text-sm font-bold outline-none"></div><input type="text" placeholder="Issuer" class="w-full bg-gray-50 border-none rounded-2xl py-4 px-6 mb-4 text-sm font-bold outline-none"><label class="w-full h-32 bg-gray-50 border-2 border-dashed border-gray-200 rounded-[32px] flex flex-col items-center justify-center cursor-pointer hover:border-primary transition-all"><input type="file" class="hidden"><i class="fa-solid fa-cloud-arrow-up text-gray-300 text-2xl mb-2"></i><span class="text-[9px] font-black text-gray-400 uppercase tracking-widest">Upload Proof</span></label><button onclick="toggleAwardModal()" class="w-full py-4 mt-6 bg-primary text-white rounded-2xl text-[10px] font-black uppercase shadow-xl">Save Achievement</button></div></div>
    <div id="skillModal" class="fixed inset-0 z-[100] flex items-center justify-center opacity-0 pointer-events-none modal-overlay"><div onclick="toggleSkillModal()" class="absolute inset-0 bg-black/40 backdrop-blur-sm"></div><div class="relative bg-white w-full max-w-xl rounded-[48px] p-10 shadow-2xl scale-90 opacity-0 modal-content"><h3 class="text-xl font-black mb-8 uppercase text-center">Add Skills</h3><input type="text" placeholder="Search tools..." class="w-full bg-gray-50 border-none rounded-2xl py-4 px-6 mb-6 text-sm outline-none focus:ring-2 focus:ring-primary/10"><button onclick="toggleSkillModal()" class="w-full py-4 bg-primary text-white rounded-2xl text-[10px] font-black uppercase">Save Skills</button></div></div>
    <div id="serviceModal" class="fixed inset-0 z-[100] flex items-center justify-center opacity-0 pointer-events-none modal-overlay"><div onclick="toggleServiceModal()" class="absolute inset-0 bg-black/40 backdrop-blur-sm"></div><div class="relative bg-white w-full max-w-xl rounded-[48px] p-10 shadow-2xl scale-90 opacity-0 modal-content text-center"><h3 class="text-xl font-black mb-6 uppercase">Manage Service</h3><input type="text" placeholder="Icon" class="w-full bg-gray-50 border-none rounded-2xl py-4 px-6 mb-4 outline-none"><input type="text" placeholder="Title" class="w-full bg-gray-50 border-none rounded-2xl py-4 px-6 mb-4 outline-none"><textarea placeholder="Description" class="w-full bg-gray-50 border-none rounded-[32px] py-4 px-6 mb-6 h-32 outline-none"></textarea><button onclick="toggleServiceModal()" class="w-full py-4 bg-primary text-white rounded-2xl text-[10px] font-black uppercase">Save</button></div></div>
    <div id="timeModal" class="fixed inset-0 z-[100] flex items-center justify-center opacity-0 pointer-events-none modal-overlay"><div onclick="toggleTimeModal()" class="absolute inset-0 bg-black/40 backdrop-blur-sm"></div><div class="relative bg-white w-full max-w-lg rounded-[48px] p-10 shadow-2xl scale-90 opacity-0 modal-content text-center"><h3 class="text-xl font-black mb-6 uppercase">Operating Hours</h3><div class="grid grid-cols-2 gap-4 mb-6"><input type="time" value="09:00" class="w-full bg-gray-50 border-none rounded-2xl py-4 px-6"><input type="time" value="18:00" class="w-full bg-gray-50 border-none rounded-2xl py-4 px-6"></div><button onclick="toggleTimeModal()" class="w-full py-4 bg-primary text-white rounded-2xl text-[10px] font-black uppercase">Save</button></div></div>

    <script>
        function toggleSidebar() { document.getElementById('sidebar').classList.toggle('sidebar-closed'); document.getElementById('main-content').classList.toggle('main-full'); }
        function openModal(modalId) { const modal = document.getElementById(modalId); const content = modal.querySelector('.modal-content'); modal.classList.remove('opacity-0', 'pointer-events-none'); setTimeout(() => { content.classList.remove('scale-90', 'opacity-0'); content.classList.add('scale-100', 'opacity-100'); }, 10); }
        function closeModal(modalId) { const modal = document.getElementById(modalId); const content = modal.querySelector('.modal-content'); content.classList.remove('scale-100', 'opacity-100'); content.classList.add('scale-90', 'opacity-0'); setTimeout(() => { modal.classList.add('opacity-0', 'pointer-events-none'); }, 300); }
        function toggleSkillModal() { const m = document.getElementById('skillModal'); m.classList.contains('opacity-0') ? openModal('skillModal') : closeModal('skillModal'); }
        function toggleTimeModal() { const m = document.getElementById('timeModal'); m.classList.contains('opacity-0') ? openModal('timeModal') : closeModal('timeModal'); }
        function toggleServiceModal() { const m = document.getElementById('serviceModal'); m.classList.contains('opacity-0') ? openModal('serviceModal') : closeModal('serviceModal'); }
        function toggleAwardModal() { const m = document.getElementById('awardModal'); m.classList.contains('opacity-0') ? openModal('awardModal') : closeModal('awardModal'); }
        function toggleDeleteModal(type = '', name = '') { const m = document.getElementById('deleteModal'); if (name) document.getElementById('deleteItemName').innerText = `${type}: ${name}`; m.classList.contains('opacity-0') ? openModal('deleteModal') : closeModal('deleteModal'); }
    </script>
</body>
</html>