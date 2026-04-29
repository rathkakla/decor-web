<?php $site_name = "DECOR"; ?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Messages — <?= $site_name ?></title>
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
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;600;700&display=swap');
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        .content-container { max-width: 1400px; margin: 0 auto; }
        .chat-scroll::-webkit-scrollbar { width: 4px; }
        .chat-scroll::-webkit-scrollbar-thumb { background: #f1f1f1; border-radius: 10px; }
    </style>
</head>
<body class="text-gray-800 flex flex-col min-h-screen bg-white">

   <header class="bg-white border-b border-gray-100 sticky top-0 z-50">
    <div class="content-container flex justify-between items-center py-4 px-6 mx-auto max-w-[1200px]">
        <div class="flex items-center space-x-8 flex-1">
            <a href="#" class="text-2xl font-black tracking-tighter uppercase text-primary hover:opacity-80 transition-all">
                <?= $site_name ?>
            </a>
            <div class="hidden lg:flex items-center bg-gray-50 border border-gray-100 rounded-md px-4 py-2 w-full max-w-[180px] group focus-within:bg-white focus-within:border-primary/30 transition-all">
                <i class="fa-solid fa-magnifying-glass text-gray-400 text-[10px] mr-2"></i>
                <input type="text" placeholder="Search..." class="bg-transparent border-none outline-none text-[10px] w-full placeholder:text-gray-400">
            </div>
        </div>
        <nav class="hidden md:flex items-center space-x-10 text-[13px] font-medium text-gray-500 tracking-wide">
            <a href="{{ route('customer.catalog') }}" class="hover:text-primary transition-all">Collections</a>
            <a href="{{ route('customer.designers') }}" class="hover:text-primary transition-all">Designers</a>
            <a href="{{ route('customer.design-lab') }}" class="hover:text-primary transition-all">AI Studio</a>
           
        </nav>
        <div class="flex items-center space-x-6 flex-1 justify-end">
            <a href="#" class="text-primary hover:scale-110 transition-transform">
                <i class="fa-solid fa-bag-shopping text-lg"></i>
            </a>
            <button class="text-primary hover:scale-110 transition-transform">
                <i class="fa-regular fa-bell text-lg"></i>
            </button>
            <a href="#" class="block">
                <div class="w-9 h-9 rounded-md overflow-hidden border border-gray-200 cursor-pointer hover:border-primary transition-all">
                    <img src="https://api.dicebear.com/7.x/avataaars/svg?seed=Felix" class="w-full h-full bg-slate-100">
                </div>
            </a>
        </div>
    </div>
</header>


    <main class="flex-grow flex justify-center bg-gray-50/30">
        <div class="content-container w-full flex bg-white border-x border-gray-100 overflow-hidden" style="height: calc(100vh - 140px);">
            
            <aside class="w-1/4 border-r border-gray-100 flex flex-col bg-white">
                <div class="p-4">
                    <div class="relative group">
                        <input type="text" placeholder="Cari percakapan..." 
                               class="w-full bg-gray-50 border-none rounded-lg py-2 px-4 text-xs outline-none focus:ring-1 focus:ring-primary/20 transition-all">
                    </div>
                </div>
                
                <div class="flex-grow overflow-y-auto chat-scroll">
                    <div class="p-4 flex items-center gap-3 bg-gray-50/80 border-r-2 border-primary cursor-pointer transition-all">
                        <div class="relative">
                            <div class="w-11 h-11 rounded-full bg-orange-100 flex items-center justify-center text-primary font-bold text-xs">EV</div>
                            <div class="absolute bottom-0 right-0 w-3 h-3 bg-green-500 border-2 border-white rounded-full"></div>
                        </div>
                        <div class="flex-grow min-w-0">
                            <div class="flex justify-between items-center mb-1">
                                <h4 class="text-xs font-bold truncate">Julian Thorne</h4>
                                <span class="text-[10px] text-gray-400">10:24 AM</span>
                            </div>
                            <p class="text-[11px] text-gray-500 truncate">What are the dimensions for the...</p>
                        </div>
                    </div>

                    <div class="p-4 flex items-center gap-3 hover:bg-gray-50 cursor-pointer transition-all">
                        <div class="w-11 h-11 rounded-full bg-blue-100 flex items-center justify-center text-blue-500 font-bold text-xs">JT</div>
                        <div class="flex-grow min-w-0">
                            <div class="flex justify-between items-center mb-1">
                                <h4 class="text-xs font-bold truncate">Taehyung</h4>
                                <span class="text-[10px] text-gray-400">Yesterday</span>
                            </div>
                            <p class="text-[11px] text-gray-400 truncate">Saran saya gunakan kayu jati...</p>
                        </div>
                    </div>
                </div>
            </aside>

            <section class="flex-grow flex flex-col bg-white">
                <div class="px-8 py-4 border-b border-gray-50 flex justify-between items-center bg-white/80 backdrop-blur-sm">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 rounded-full bg-orange-100 flex items-center justify-center text-primary font-bold text-[10px]">EV</div>
                        <div>
                            <h3 class="text-xs font-black uppercase tracking-wider">Julian Thorne</h3>
                            <p class="text-[9px] font-bold text-green-500 uppercase tracking-widest">Online Now</p>
                        </div>
                    </div>
                    <button class="text-gray-300 hover:text-gray-600"><i class="fa-solid fa-ellipsis-vertical"></i></button>
                </div>

                <div class="flex-grow p-10 overflow-y-auto space-y-10 chat-scroll">
                    <div class="flex justify-start">
                        <div class="max-w-[70%] bg-white border border-gray-100 px-6 py-4 rounded-[2rem] rounded-tl-none shadow-sm text-[13px] leading-relaxed text-gray-700">
                           Kami menyediakan warna coklat
                        </div>
                    </div>

                    <div class="flex justify-end">
                        <div class="max-w-[70%] bg-primary text-white px-6 py-4 rounded-[2rem] rounded-tr-none shadow-lg shadow-primary/10 text-[13px] leading-relaxed">
                            Apakah Anda menyediakan opsi warna lain untuk kayu jati, seperti warna pink?
                        </div>
                    </div>
                </div>

                <div class="p-6 bg-white">
                    <div class="bg-gray-50 rounded-full flex items-center px-4 py-2 border border-transparent focus-within:border-gray-100 focus-within:bg-white transition-all group">
                        <button class="p-2 text-gray-400 hover:text-primary transition-colors">
                            <i class="fa-solid fa-paperclip"></i>
                        </button>
                        <input type="text" placeholder="Tulis pesan untuk Elena..." 
                               class="flex-grow bg-transparent border-none outline-none px-4 py-3 text-[13px] text-gray-600">
                        <button class="w-10 h-10 bg-primary text-white rounded-full flex items-center justify-center shadow-md hover:scale-105 transition-all">
                            <i class="fa-solid fa-paper-plane text-xs"></i>
                        </button>
                    </div>
                </div>
            </section>
        </div>
    </main>

    <footer class="bg-primary text-white py-12 px-6">
        <div class="max-w-6xl mx-auto"> 
            <div class="flex flex-col md:flex-row justify-between items-start border-b border-white/20 pb-10 gap-10 text-left">
                <div class="space-y-4">
                    <div class="flex items-center space-x-2 text-white">
                        <div class="w-8 h-8 bg-white/20 rounded flex items-center justify-center">
                            <i class="fa-solid fa-couch text-sm"></i>
                        </div>
                        <span class="text-xl font-bold tracking-widest uppercase">DECOR</span>
                    </div>
                    <div class="text-sm text-white/90 space-y-2">
                        <p class="flex items-center"><i class="fa-regular fa-envelope mr-3 text-xs"></i> decorofficial@gmail.com</p>
                        <p class="flex items-center"><i class="fa-brands fa-instagram mr-3 text-xs"></i> @decor_official</p>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-x-16 gap-y-4 text-[10px] font-bold tracking-widest uppercase text-white/90">
                    <div class="flex flex-col space-y-3">
                        <a href="#" class="hover:text-white/70 transition-colors">Terms of Service</a>
                        <a href="#" class="hover:text-white/70 transition-colors">Privacy Policy</a>
                    </div>
                    <div class="flex flex-col space-y-3">
                        <a href="{{ route('customer.help-center') }}" class="hover:text-white/70 transition-colors">Help Center</a>
                        <a href="#" class="hover:text-white/70 transition-colors">FAQ</a>
                    </div>
                </div>

                <div class="flex flex-col items-end space-y-6">
                    <span class="text-[10px] font-bold tracking-widest uppercase text-white/40">Portal Version 1.0</span>
                    <div class="flex space-x-4">
                        <a href="#" class="w-10 h-10 border border-white/40 flex items-center justify-center rounded-full hover:bg-white hover:text-primary transition-all duration-300">
                            <i class="fa-brands fa-instagram"></i>
                        </a>
                        <a href="#" class="w-10 h-10 border border-white/40 flex items-center justify-center rounded-full hover:bg-white hover:text-primary transition-all duration-300">
                            <i class="fa-regular fa-paper-plane"></i>
                        </a>
                    </div>
                </div>
            </div>
            <div class="pt-8 text-[10px] text-white/60 font-medium tracking-widest">
                <p>©️ 2026 DECOR MARKETPLACE. ALL RIGHTS RESERVED.</p>
            </div>
        </div>
    </footer>

</body>
</html>