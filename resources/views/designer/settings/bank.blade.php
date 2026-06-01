<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Decor Designer - Bank & Payout</title>
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
        
        .modal-overlay { transition: opacity 0.3s ease; }
        .modal-content { transition: transform 0.3s ease, opacity 0.3s ease; }
    </style>
</head>
<body class="text-gray-800 flex h-screen">

    @include('designer.partials.sidebar')

    <div id="main-content" class="flex-1 flex flex-col min-w-0 overflow-hidden ml-64 sidebar-transition">
        
        <!-- HEADER (CONSISTENT LUXURY) -->
        <header class="h-16 bg-primary flex items-center justify-between px-8 sticky top-0 z-40 shadow-sm text-white">
            <div class="flex items-center">
                <i onclick="toggleSidebar()" class="fa-solid fa-bars-staggered text-xl mr-4 cursor-pointer hover:scale-110 transition-transform"></i>
                <h2 class="font-black text-[10px] uppercase tracking-[0.3em] leading-none">Financial Settings</h2>
            </div>
            <div class="flex items-center space-x-6">
                <button class="bg-white/10 border border-white/20 text-white px-8 py-2.5 rounded-xl text-[10px] font-black uppercase tracking-widest hover:bg-white hover:text-primary transition-all">Save Changes</button>
                <div class="flex items-center space-x-3 bg-white/10 px-3 py-1.5 rounded-xl border border-white/20">
                    <span class="text-[10px] font-black uppercase tracking-widest">Elena Vance</span>
                    <div class="w-8 h-8 rounded-lg bg-white text-primary flex items-center justify-center font-bold">EV</div>
                </div>
            </div>
        </header>

        <main class="flex-1 overflow-y-auto p-10 space-y-8 custom-scroll pb-24">
            <div class="max-w-5xl mx-auto space-y-8">
                
                <!-- TAB NAVIGATION -->
                <div class="flex border-b border-gray-200">
                    <a href="{{ route('designer.settings') }}" class="px-8 py-4 text-[10px] font-black uppercase tracking-widest text-gray-400 hover:text-primary transition-all">Studio Info</a>
                    <a href="{{ route('designer.settings.bank') }}" class="px-8 py-4 text-[10px] font-black uppercase tracking-widest tab-active transition-all">Bank & Payout</a>
                </div>

                <!-- BANK ACCOUNT SECTION -->
                <section class="bg-white p-10 rounded-[48px] border border-gray-100 shadow-sm space-y-8 mt-6">
                    <h3 class="text-xs font-black uppercase tracking-widest text-gray-300 italic leading-none">Primary Bank Account</h3>
                    <div class="bg-gray-50 p-8 rounded-[32px] border border-gray-100 flex items-center justify-between group hover:border-primary/20 transition-all duration-300">
                        <div class="flex items-center space-x-6">
                            <div class="w-14 h-14 rounded-2xl bg-white border border-gray-100 flex items-center justify-center text-primary text-xl shadow-sm"><i class="fa-solid fa-university"></i></div>
                            <div>
                                <h4 class="text-sm font-black text-gray-900">Bank Central Asia (BCA)</h4>
                                <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mt-1">Elena Vance • **** 8819</p>
                            </div>
                        </div>
                        <div class="flex items-center space-x-4">
                            <button onclick="toggleBankModal()" class="text-[9px] font-black uppercase text-primary hover:tracking-widest transition-all">Change Account</button>
                            <div class="h-4 w-px bg-gray-200"></div>
                            <button onclick="toggleDeleteBankModal('BCA - **** 8819')" class="w-10 h-10 flex items-center justify-center bg-white border border-gray-100 rounded-xl text-gray-300 hover:text-red-500 hover:border-red-100 transition-all shadow-sm">
                                <i class="fa-solid fa-trash-can text-xs"></i>
                            </button>
                        </div>
                    </div>
                </section>

                <!-- SIGNATURE SECTION (FIXED & CLICKABLE) -->
                <section class="bg-white p-10 rounded-[48px] border border-gray-100 shadow-sm space-y-8">
                    <h3 class="text-xs font-black uppercase tracking-widest text-gray-300 italic leading-none">Digital Signature</h3>
                    
                    <label class="h-48 border-2 border-dashed border-gray-100 rounded-[32px] flex flex-col items-center justify-center space-y-4 hover:border-primary transition-all cursor-pointer group bg-gray-50/30 relative">
                        <!-- Hidden Input File -->
                        <input type="file" class="hidden" accept="image/*">
                        
                        <div class="w-16 h-16 bg-white rounded-full flex items-center justify-center text-gray-300 group-hover:text-primary shadow-sm transition-all">
                            <i class="fa-solid fa-signature text-2xl"></i>
                        </div>
                        
                        <div class="text-center">
                            <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest italic">Upload studio signature for official invoices</p>
                            <p class="text-[8px] text-gray-300 font-bold uppercase mt-1">PNG, JPG up to 2MB</p>
                        </div>
                    </label>
                </section>
            </div>
        </main>

        <footer class="p-8 border-t border-gray-100 text-[9px] font-black text-gray-400 uppercase tracking-widest bg-white mt-auto text-center">
            © 2026 DECOR DESIGNER PORTAL. ALL RIGHTS RESERVED.
        </footer>
    </div>

    <!-- BANK DELETE ALERT MODAL -->
    <div id="deleteBankModal" class="fixed inset-0 z-[200] flex items-center justify-center opacity-0 pointer-events-none modal-overlay">
        <div onclick="toggleDeleteBankModal()" class="absolute inset-0 bg-black/40 backdrop-blur-sm"></div>
        <div class="relative bg-white w-full max-w-sm rounded-[48px] p-10 shadow-2xl scale-90 opacity-0 modal-content text-center">
            <div class="w-20 h-20 bg-red-50 text-red-500 rounded-full flex items-center justify-center mx-auto mb-6 text-3xl"><i class="fa-solid fa-bank"></i></div>
            <h3 class="text-xl font-black text-gray-900 tracking-tight leading-none uppercase">Remove Bank Account?</h3>
            <p class="text-[11px] text-gray-400 font-bold italic mt-3 px-4 leading-relaxed"><span id="deleteBankName" class="text-red-500 not-italic uppercase tracking-widest">this account</span> will be removed from your payout settings.</p>
            <div class="flex flex-col space-y-3 mt-8">
                <button onclick="toggleDeleteBankModal()" class="w-full py-4 bg-red-500 text-white rounded-2xl text-[10px] font-black uppercase shadow-xl hover:scale-105 transition-all">Yes, Remove It</button>
                <button onclick="toggleDeleteBankModal()" class="w-full py-4 bg-gray-50 text-gray-400 rounded-2xl text-[10px] font-black uppercase hover:bg-gray-100 transition-all">Cancel</button>
            </div>
        </div>
    </div>

    <!-- UPDATE BANK MODAL -->
    <div id="bankModal" class="fixed inset-0 z-[100] flex items-center justify-center opacity-0 pointer-events-none modal-overlay">
        <div onclick="toggleBankModal()" class="absolute inset-0 bg-black/40 backdrop-blur-sm"></div>
        <div class="relative bg-white w-full max-w-lg rounded-[48px] p-10 shadow-2xl scale-90 opacity-0 modal-content">
            <div class="flex justify-between items-center mb-8">
                <div><h3 class="text-xl font-black text-gray-900 tracking-tight leading-none uppercase">Update Bank Info</h3><p class="text-[10px] text-gray-400 font-bold uppercase tracking-widest mt-2">Change payout account</p></div>
                <button onclick="toggleBankModal()" class="w-10 h-10 rounded-full bg-gray-50 text-gray-400 hover:text-red-500 transition-colors"><i class="fa-solid fa-xmark"></i></button>
            </div>
            <form class="space-y-6">
                <div class="space-y-2"><label class="text-[9px] font-black uppercase text-gray-400 ml-2 tracking-widest">Select Bank</label><select class="w-full bg-gray-50 border-none rounded-2xl py-4 px-6 text-xs font-bold outline-none cursor-pointer"><option>BCA</option><option>Mandiri</option></select></div>
                <div class="space-y-2"><label class="text-[9px] font-black uppercase text-gray-400 ml-2 tracking-widest">Account Number</label><input type="text" class="w-full bg-gray-50 border-none rounded-2xl py-4 px-6 text-xs font-bold outline-none focus:ring-2 focus:ring-primary/10"></div>
                <button type="button" class="w-full py-4 bg-primary text-white rounded-2xl text-[10px] font-black uppercase shadow-xl">Update Account</button>
            </form>
        </div>
    </div>

    <script>
        function toggleSidebar() { document.getElementById('sidebar').classList.toggle('sidebar-closed'); document.getElementById('main-content').classList.toggle('main-full'); }
        
        function openModal(id) { 
            const m = document.getElementById(id); 
            const c = m.querySelector('.modal-content'); 
            m.classList.remove('opacity-0', 'pointer-events-none'); 
            setTimeout(() => { c.classList.remove('scale-90', 'opacity-0'); c.classList.add('scale-100', 'opacity-100'); }, 10); 
        }
        
        function closeModal(id) { 
            const m = document.getElementById(id); 
            const c = m.querySelector('.modal-content'); 
            c.classList.remove('scale-100', 'opacity-100'); 
            c.classList.add('scale-90', 'opacity-0'); 
            setTimeout(() => { m.classList.add('opacity-0', 'pointer-events-none'); }, 300); 
        }
        
        function toggleBankModal() { const m = document.getElementById('bankModal'); m.classList.contains('opacity-0') ? openModal('bankModal') : closeModal('bankModal'); }
        
        function toggleDeleteBankModal(name = '') { 
            const m = document.getElementById('deleteBankModal'); 
            if (name) document.getElementById('deleteBankName').innerText = name;
            m.classList.contains('opacity-0') ? openModal('deleteBankModal') : closeModal('deleteBankModal'); 
        }
    </script>
</body>
</html>