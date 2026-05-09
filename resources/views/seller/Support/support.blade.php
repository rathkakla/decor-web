<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Decor Seller - Merchant Support</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap');
        body { background-color: #F8F6F4; font-family: 'Plus Jakarta Sans', sans-serif; overflow-x: hidden; }
        .bg-primary { background-color: #B5733A; }
        .text-primary { color: #B5733A; }
        
        /* Active Link Style */
        .active-link { background-color: rgba(181, 115, 58, 0.1); color: #B5733A; border-right: 4px solid #B5733A; }
        
        /* Sidebar Animations */
        .sidebar-transition { transition: transform 0.3s ease-in-out, margin 0.3s ease-in-out; }
        .sidebar-hidden { transform: translateX(-100%); }
        .main-expanded { margin-left: 0 !important; }
    </style>
</head>
<body class="text-gray-800">

    @include('seller.partials.sidebar')

    <main id="main-content" class="flex-1 flex flex-col ml-64 sidebar-transition min-h-screen relative">
        
        <header class="h-16 bg-primary flex items-center justify-between px-8 sticky top-0 z-40 shadow-sm">
            <div class="flex items-center">
                <button id="toggle-sidebar" class="text-white hover:opacity-80 mr-4 flex items-center justify-center transition-transform active:scale-95">
                    <i class="fa-solid fa-bars-staggered text-xl"></i>
                </button>
                <h2 class="font-bold text-xs uppercase tracking-widest text-white leading-none">Support Center</h2>
            </div>
            <div class="flex items-center space-x-6 text-white">
                <i class="fa-regular fa-bell text-xl cursor-pointer"></i>
                <img src="https://ui-avatars.com/api/?name=Audri&background=fff&color=B5733A" class="w-9 h-9 rounded-lg border-2 border-white/20">
            </div>
        </header>

        <div class="p-12 space-y-12 flex-1">
            
            <div class="max-w-4xl">
                <h2 class="text-5xl font-black text-gray-800 tracking-tight">Merchant Support</h2>
                <p class="text-sm text-gray-400 mt-4 max-w-xl font-medium leading-relaxed">How can we help you today? Our specialized support team is here to ensure your boutique remains exceptional.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div class="bg-white p-10 rounded-[32px] border border-gray-100 shadow-sm space-y-6">
                    <div class="w-12 h-12 bg-gray-50 rounded-xl flex items-center justify-center text-primary"><i class="fa-solid fa-book-open"></i></div>
                    <div>
                        <h3 class="text-xl font-black text-gray-800">Help Center</h3>
                        <p class="text-[11px] text-gray-400 font-bold leading-relaxed mt-4">Browse our curated guides on logistics, inventory management, and photography standards.</p>
                    </div>
                    <div class="w-8 h-[2px] bg-primary/20"></div>
                </div>

                <div class="bg-white p-10 rounded-[32px] border border-gray-100 shadow-sm space-y-6">
                    <div class="w-12 h-12 bg-gray-50 rounded-xl flex items-center justify-center text-primary"><i class="fa-solid fa-envelope"></i></div>
                    <div>
                        <h3 class="text-xl font-black text-gray-800">Contact Us</h3>
                        <p class="text-[11px] text-gray-400 font-bold leading-relaxed mt-4">Submit a formal inquiry regarding your partnership or bespoke order fulfillment.</p>
                    </div>
                    <div class="w-8 h-[2px] bg-primary/20"></div>
                </div>

                <div class="bg-white p-10 rounded-[32px] border border-gray-100 shadow-sm space-y-6">
                    <div class="w-12 h-12 bg-gray-50 rounded-xl flex items-center justify-center text-primary"><i class="fa-solid fa-comments"></i></div>
                    <div>
                        <h3 class="text-xl font-black text-gray-800">Live Chat</h3>
                        <p class="text-[11px] text-gray-400 font-bold leading-relaxed mt-4">Connect instantly with a dedicated Merchant Success Manager for real-time assistance.</p>
                    </div>
                    <div class="w-8 h-[2px] bg-primary/20"></div>
                </div>
            </div>

            <div class="bg-gray-100/60 p-12 rounded-[40px] flex flex-col md:flex-row items-center justify-between border border-white">
                <div class="max-w-xl space-y-4">
                    <h3 class="text-3xl font-black text-gray-800">Dedicated Support for Curators</h3>
                    <p class="text-xs font-bold text-gray-400 leading-relaxed uppercase tracking-widest">Our premium support tier ensures that high-end merchants receive priority response times and strategic consultation for business growth.</p>
                </div>
                <button class="mt-8 md:mt-0 bg-primary text-white px-10 py-5 rounded-2xl text-[11px] font-black uppercase tracking-[0.2em] shadow-xl shadow-primary/20 hover:scale-105 transition-all">
                    <a href="{{ route('seller.support.chat') }}" class="bg-[#B5733A] text-white px-8 py-3 rounded-xl ...">
                        <i class="fa-solid fa-headset mr-2"></i> CHAT WITH ADMIN
                    </a>
                </button>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-12 gap-8">
                <div class="md:col-span-8 rounded-[40px] overflow-hidden shadow-2xl h-80">
                    <img src="https://images.unsplash.com/photo-1497366216548-37526070297c?q=80&w=1200" class="w-full h-full object-cover grayscale opacity-90">
                </div>
                <div class="md:col-span-4 bg-[#1A1A1A] p-12 rounded-[40px] flex flex-col justify-between text-white relative overflow-hidden">
                    <div class="relative z-10">
                        <div class="bg-primary/20 w-10 h-10 rounded-lg flex items-center justify-center mb-8 text-primary">
                            <i class="fa-solid fa-certificate"></i>
                        </div>
                        <h4 class="text-2xl font-black leading-tight">Verified Merchant Program</h4>
                        <p class="text-[10px] font-bold text-gray-400 mt-6 leading-relaxed uppercase tracking-widest">As a premium member of The Curator, you have access to 24/7 technical oversight and curated marketing insights.</p>
                    </div>
                    <div class="absolute -right-10 -bottom-10 w-40 h-40 bg-white/5 rounded-full"></div>
                </div>
            </div>
        </div>

        <footer class="p-8 border-t border-gray-100 text-[10px] font-bold text-gray-400 uppercase tracking-widest mt-auto text-left">
            Â© 2026 DECOR MERCHANT SERVICE CENTER. ALL RIGHTS RESERVED.
        </footer>
    </main>

    <script>
        const btn = document.getElementById('toggle-sidebar');
        const sidebar = document.getElementById('sidebar');
        const main = document.getElementById('main-content');
        btn.addEventListener('click', () => { 
            sidebar.classList.toggle('sidebar-hidden'); 
            main.classList.toggle('main-expanded'); 
        });
    </script>
</body>
</html>