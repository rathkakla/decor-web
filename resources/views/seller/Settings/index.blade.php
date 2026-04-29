<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Decor Seller - Pengaturan Toko</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&display=swap');
        body { background-color: #F8F6F4; font-family: 'Plus Jakarta Sans', sans-serif; overflow-x: hidden; }
        .bg-primary { background-color: #B5733A; }
        .text-primary { color: #B5733A; }
        
        /* Active Link: Cokelat Pastel */
        .active-link { background-color: rgba(181, 115, 58, 0.1); color: #B5733A; border-right: 4px solid #B5733A; }
        
        /* Animasi Sidebar */
        .sidebar-transition { transition: transform 0.3s ease-in-out, margin 0.3s ease-in-out; }
        .sidebar-hidden { transform: translateX(-100%); }
        .main-expanded { margin-left: 0 !important; }

        input:focus, textarea:focus { border-color: #B5733A !important; outline: none; background: white !important; }

        /* Modal Blur */
        .modal-active { overflow: hidden; }
    </style>
</head>
<body class="text-gray-800">

    <aside id="sidebar" class="w-64 bg-white border-r border-gray-200 flex flex-col fixed h-full z-50 sidebar-transition">
        <div class="p-8">
            <h1 class="text-2xl font-bold tracking-widest text-primary uppercase leading-none">DECOR</h1>
            <p class="text-[10px] text-gray-400 mt-1 uppercase tracking-widest">Seller Portal</p>
        </div>

         <nav class="flex-1 px-4 space-y-1">
            <a href="{{ route('seller.dashboard') }}" class="flex items-center px-4 py-3 text-xs font-bold transition-all rounded-lg {{ Request::routeIs('seller.dashboard') ? 'active-link' : 'text-gray-400 hover:text-primary' }}">
                <i class="fa-solid fa-table-columns mr-3 w-5 text-center"></i> Dashboard
            </a>
            <a href="{{ route('seller.products.index') }}" class="flex items-center px-4 py-3 text-xs font-bold transition-all rounded-lg {{ Request::routeIs('products.*') ? 'active-link' : 'text-gray-400 hover:text-primary' }}">
                <i class="fa-solid fa-couch mr-3 w-5 text-center"></i> Kelola Produk
            </a>
            <a href="{{ route('seller.orders') }}" class="flex items-center px-4 py-3 text-xs font-bold transition-all rounded-lg {{ Request::routeIs('seller.orders') ? 'active-link' : 'text-gray-400 hover:text-primary' }}">
                <i class="fa-solid fa-bag-shopping mr-3 w-5 text-center"></i> Daftar Pesanan
            </a>
            <a href="{{ route('seller.chats') }}" class="flex items-center px-4 py-3 text-xs font-bold transition-all rounded-lg {{ Request::routeIs('seller.chats') ? 'active-link' : 'text-gray-400 hover:text-primary' }}">
                <i class="fa-solid fa-message mr-3 w-5 text-center"></i> Seller Chat
            </a>
            <a href="{{ route('seller.complaint.index') }}" class="flex items-center px-4 py-3 text-xs font-bold transition-all rounded-lg {{ Request::routeIs('seller.complaint.*') ? 'active-link' : 'text-gray-400 hover:text-primary' }}">
                <i class="fa-solid fa-circle-exclamation mr-3 w-5 text-center"></i> Komplain
            </a>
            <a href="{{ route('seller.reviews') }}" class="flex items-center px-4 py-3 text-xs font-bold transition-all rounded-lg {{ Request::routeIs('seller.reviews') ? 'active-link' : 'text-gray-400 hover:text-primary' }}">
                <i class="fa-solid fa-star mr-3 w-5 text-center"></i> Review & Rating
            </a>
            <a href="{{ route('seller.reports') }}" class="flex items-center px-4 py-3 text-xs font-bold transition-all rounded-lg {{ Request::routeIs('seller.reports') ? 'active-link' : 'text-gray-400 hover:text-primary' }}">
                <i class="fa-solid fa-chart-line mr-3 w-5 text-center"></i> Laporan
            </a>
        </nav>

        <div class="p-4 border-t border-gray-100 space-y-1">
            <a href="{{ route('seller.settings') }}" class="flex items-center px-4 py-3 text-xs font-bold active-link transition-all rounded-lg">
                <i class="fa-solid fa-gear mr-3 w-5 text-center"></i> Settings
            </a>
           <a href="{{ route('seller.support') }}" class="flex items-center px-4 py-3 text-xs font-bold transition-all rounded-lg {{ Request::routeIs('seller.support') ? 'active-link' : 'text-gray-400 hover:text-primary' }}">
                <i class="fa-solid fa-gear mr-3 w-5 text-center"></i> Support
            </a>
        </div>
    </aside>

    <main id="main-content" class="flex-1 flex flex-col ml-64 sidebar-transition min-h-screen relative">
        
        <header class="h-16 bg-primary flex items-center justify-between px-8 sticky top-0 z-40 shadow-sm">
            <div class="flex items-center">
                <button id="toggle-sidebar" class="text-white hover:opacity-80 mr-4 flex items-center justify-center transition-transform active:scale-95">
                    <i class="fa-solid fa-bars-staggered text-xl"></i>
                </button>
                <h2 class="font-bold text-xs uppercase tracking-widest text-white leading-none">Store Settings</h2>
            </div>
            <div class="flex items-center space-x-6 text-white">
                <i class="fa-regular fa-bell text-xl cursor-pointer"></i>
                <div class="w-9 h-9 rounded-lg bg-white/20 flex items-center justify-center overflow-hidden border border-white/30">
                     <img src="https://ui-avatars.com/api/?name=Adrian+Putra&background=fff&color=B5733A" alt="User">
                </div>
            </div>
        </header>

        <div class="p-10 space-y-10 flex-1 pb-32">
            <div>
                <h2 class="text-3xl font-extrabold tracking-tight text-gray-800">Pengaturan Toko</h2>
                <p class="text-xs text-gray-400 mt-1 font-medium italic">Manage your gallery's digital identity and operational workflow.</p>
            </div>

            <div class="space-y-6">
                <h3 class="text-[10px] font-black text-gray-400 uppercase tracking-[0.3em] flex items-center">Visual Identity <span class="ml-4 h-[1px] flex-1 bg-gray-200"></span></h3>
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                    <div class="lg:col-span-2 space-y-3">
                        <label class="text-[9px] font-black uppercase tracking-widest text-gray-400">Store Banner</label>
                        <div class="h-48 bg-gray-100 rounded-2xl border-2 border-dashed border-gray-200 flex flex-col items-center justify-center relative overflow-hidden group">
                            <i class="fa-solid fa-image text-3xl text-gray-200 mb-2"></i>
                            <span class="text-[10px] font-bold text-gray-300 uppercase tracking-widest">Recommended: 1200x400px</span>
                            <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-all flex items-center justify-center">
                                <button class="bg-white px-4 py-2 rounded-lg text-[9px] font-black uppercase tracking-widest">Replace Banner</button>
                            </div>
                        </div>
                    </div>
                    <div class="space-y-3">
                        <label class="text-[9px] font-black uppercase tracking-widest text-gray-400">Store Logo</label>
                        <div class="aspect-square bg-secondary/30 rounded-2xl border-2 border-dashed border-primary/20 flex flex-col items-center justify-center text-primary/40 group relative overflow-hidden">
                            <span class="text-6xl font-black">D</span>
                            <div class="absolute inset-0 bg-primary/80 opacity-0 group-hover:opacity-100 transition-all flex items-center justify-center text-white">
                                <i class="fa-solid fa-camera"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="space-y-6">
                <h3 class="text-[10px] font-black text-gray-400 uppercase tracking-[0.3em] flex items-center">General Information <span class="ml-4 h-[1px] flex-1 bg-gray-100"></span></h3>
                <div class="bg-white p-8 rounded-3xl border border-gray-100 space-y-6 shadow-sm">
                    <div class="space-y-2">
                        <label class="text-[9px] font-black uppercase tracking-widest text-gray-400">Nama Toko</label>
                        <input type="text" value="DECOR Premium Gallery" class="w-full bg-gray-50/50 border border-gray-100 rounded-xl p-3 text-xs font-bold text-gray-800 transition-all">
                    </div>
                    <div class="space-y-2">
                        <label class="text-[9px] font-black uppercase tracking-widest text-gray-400">Deskripsi Toko</label>
                        <textarea rows="4" class="w-full bg-gray-50/50 border border-gray-100 rounded-xl p-4 text-xs font-medium text-gray-600 leading-relaxed transition-all">DECOR is a curated marketplace for artisanal furniture and high-end editorial interior pieces. Our collection focuses on the intersection of architectural heritage and modern living functionality, ensuring every piece tells a story of craftsmanship.</textarea>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                <div class="space-y-6">
                    <h3 class="text-[10px] font-black text-gray-400 uppercase tracking-[0.3em]">Jam Operasional</h3>
                    <div class="bg-white p-6 rounded-3xl border border-gray-100 space-y-4 shadow-sm text-[10px] font-bold text-gray-600">
                        <div class="flex justify-between items-center pb-3 border-b border-gray-50"><span>Senin - Jumat</span> <span class="text-primary font-black">09:00 — 18:00</span></div>
                        <div class="flex justify-between items-center pb-3 border-b border-gray-50"><span>Sabtu</span> <span class="text-primary font-black">10:00 — 15:00</span></div>
                        <div class="flex justify-between items-center text-red-400 uppercase tracking-widest"><span>Minggu</span> <span class="bg-red-50 px-2 py-1 rounded">Tutup</span></div>
                        <button onclick="toggleModal('modal-hours')" class="w-full mt-4 py-3 border border-gray-100 rounded-xl hover:bg-gray-50 transition-all uppercase tracking-[0.2em] font-black text-gray-400">Edit Hours</button>
                    </div>
                </div>

                <div class="space-y-6">
                    <h3 class="text-[10px] font-black text-gray-400 uppercase tracking-[0.3em]">Informasi Rekening Bank</h3>
                    <div class="bg-white p-6 rounded-3xl border border-gray-100 shadow-sm space-y-4">
                        <div class="flex items-center space-x-4">
                            <div class="w-12 h-12 bg-gray-50 rounded-xl flex items-center justify-center border border-gray-100 text-primary"><i class="fa-solid fa-building-columns"></i></div>
                            <div><p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Bank Name</p><p class="text-xs font-black uppercase text-gray-800">Bank Central Asia (BCA)</p></div>
                        </div>
                        <div class="pt-4 border-t border-gray-50">
                            <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Account Number</p>
                            <p class="text-sm font-black tracking-tighter text-gray-800">8820 ● ● ● ● 992</p>
                            <p class="text-[10px] font-bold text-primary mt-1">PT. DECOR LIFESTYLE INDONESIA</p>
                        </div>
                        <a href="{{ route('seller.settings.bank') }}" class="block w-full py-3 border border-primary/20 text-primary rounded-xl hover:bg-primary/5 transition-all uppercase tracking-widest font-black text-center text-[10px]">Update Withdrawal Info</a>
                    </div>
                </div>
            </div>
        </div>

        <footer class="p-8 border-t border-gray-100 text-[10px] font-bold text-gray-400 uppercase tracking-widest mt-auto text-left">
            © 2026 DECOR MARKETPLACE SELLER PORTAL. ALL RIGHTS RESERVED.
        </footer>

        <div id="fixed-action-bar" class="fixed bottom-0 left-64 right-0 bg-white/80 backdrop-blur-md border-t border-gray-100 px-10 py-5 flex items-center justify-between z-50 sidebar-transition">
            <div class="flex items-center space-x-3 text-[10px] font-bold text-gray-400 uppercase tracking-widest">
                <i class="fa-solid fa-circle-info text-primary"></i>
                <span>Changes will go live immediately after approval.</span>
            </div>
            <div class="flex items-center space-x-6">
                <button class="text-[10px] font-black uppercase tracking-widest text-gray-400 hover:text-gray-800 transition-colors">Batal</button>
                <button class="bg-primary text-white px-10 py-3 rounded-2xl text-[10px] font-black uppercase tracking-widest shadow-xl shadow-primary/30 hover:scale-105 transition-all">Simpan Perubahan</button>
            </div>
        </div>

        <div id="modal-hours" class="hidden fixed inset-0 bg-black/40 backdrop-blur-sm z-[100] flex items-center justify-center p-6">
            <div class="bg-white w-full max-w-md rounded-[40px] shadow-2xl overflow-hidden p-10 space-y-8 animate-in fade-in zoom-in duration-300">
                <div class="text-center">
                    <h3 class="text-2xl font-black tracking-tight">Edit Operational Hours</h3>
                    <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mt-1">Select your digital gallery active time</p>
                </div>
                
                <div class="space-y-6">
                    @foreach(['Senin - Jumat', 'Sabtu'] as $day)
                    <div class="space-y-2">
                        <label class="text-[9px] font-black uppercase text-gray-400 ml-2 tracking-widest">{{ $day }}</label>
                        <div class="flex items-center space-x-3">
                            <select class="flex-1 bg-gray-50 border-none rounded-2xl p-3 text-xs font-bold outline-none focus:ring-2 focus:ring-primary/20">
                                @for($i=7; $i<=12; $i++) <option>{{ sprintf('%02d:00', $i) }}</option> @endfor
                            </select>
                            <span class="text-gray-300">—</span>
                            <select class="flex-1 bg-gray-50 border-none rounded-2xl p-3 text-xs font-bold outline-none focus:ring-2 focus:ring-primary/20">
                                @for($i=15; $i<=21; $i++) <option>{{ sprintf('%02d:00', $i) }}</option> @endfor
                            </select>
                        </div>
                    </div>
                    @endforeach
                    <div class="flex items-center justify-between px-2 pt-2">
                        <span class="text-xs font-bold text-gray-600">Buka Hari Minggu?</span>
                        <div class="w-10 h-5 bg-gray-100 rounded-full relative cursor-pointer"><div class="absolute left-1 top-1 w-3 h-3 bg-white shadow-sm rounded-full"></div></div>
                    </div>
                </div>

                <div class="flex space-x-4 pt-4">
                    <button onclick="toggleModal('modal-hours')" class="flex-1 text-[10px] font-black uppercase tracking-widest text-gray-400">Cancel</button>
                    <button onclick="toggleModal('modal-hours')" class="flex-1 bg-primary text-white py-4 rounded-2xl text-[10px] font-black uppercase tracking-widest shadow-xl shadow-primary/20">Save Settings</button>
                </div>
            </div>
        </div>
    </main>

    <script>
        const btn = document.getElementById('toggle-sidebar');
        const sidebar = document.getElementById('sidebar');
        const main = document.getElementById('main-content');
        const actionBar = document.getElementById('fixed-action-bar');

        btn.addEventListener('click', () => { 
            sidebar.classList.toggle('sidebar-hidden'); 
            main.classList.toggle('main-expanded'); 
            if(actionBar) actionBar.classList.toggle('main-expanded');
        });

        // REVISI: FUNGSI TOGGLE MODAL
        function toggleModal(id) {
            const modal = document.getElementById(id);
            modal.classList.toggle('hidden');
            document.body.classList.toggle('modal-active');
        }
    </script>
</body>
</html>