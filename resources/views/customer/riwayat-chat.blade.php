<?php 
$site_name = "DECOR"; 

// Logika Routing Sederhana dalam satu file
$page = isset($_GET['page']) ? $_GET['page'] : 'profile';
$chat_type = isset($_GET['type']) ? $_GET['type'] : 'designer';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= ucfirst($page) ?> — <?= $site_name ?></title>
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
        body { font-family: 'Plus Jakarta Sans', sans-serif; background-color: #ffffff; }
        .content-container { max-width: 1200px; margin: 0 auto; }
        .chat-scroll::-webkit-scrollbar { width: 4px; }
        .chat-scroll::-webkit-scrollbar-thumb { background: #f1f1f1; border-radius: 10px; }
    </style>
</head>
<body class="text-gray-800 flex flex-col min-h-screen">

    @include('customer.partials.navbar')

    <main class="flex-grow flex content-container w-full bg-white">
        @include('customer.partials.sidebar')

        

        
                <div class="flex-grow flex overflow-hidden" style="height: calc(100vh - 100px);">
                    <aside class="w-80 border-r border-gray-100 flex flex-col bg-white">
                        <div class="p-6">
                            <h2 class="text-lg font-bold tracking-tighter mb-4"><?= ucfirst($chat_type) ?> Messages</h2>
                            <div class="relative group">
                                <input type="text" placeholder="Search conversation..." class="w-full bg-gray-50 border-none rounded-lg py-2 px-4 text-[10px] outline-none focus:ring-1 focus:ring-primary/20 transition-all">
                            </div>
                        </div>
                        <div class="flex-grow overflow-y-auto chat-scroll p-2">
                            <div class="p-4 flex items-center gap-3 bg-gray-50/80 border-r-2 border-primary cursor-pointer rounded-xl">
                                <div class="w-10 h-10 rounded-full bg-orange-100 flex items-center justify-center text-primary font-bold text-xs">EV</div>
                                <div class="flex-grow min-w-0">
                                    <div class="flex justify-between items-center mb-1">
                                        <h4 class="text-xs font-bold truncate">Elena Valery</h4>
                                        <span class="text-[9px] text-gray-400">10:24 AM</span>
                                    </div>
                                    <p class="text-[10px] text-gray-500 truncate font-medium">Saran saya gunakan kayu jati...</p>
                                </div>
                            </div>
                        </div>
                    </aside>

                    <section class="flex-grow flex flex-col bg-white">
                        <div class="px-8 py-4 border-b border-gray-50 flex justify-between items-center bg-white/80 backdrop-blur-sm">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-full bg-orange-100 flex items-center justify-center text-primary font-bold text-[10px]">EV</div>
                                <div>
                                    <h3 class="text-xs font-black uppercase tracking-wider">Elena Valery</h3>
                                    <p class="text-[9px] font-bold text-green-500 uppercase tracking-widest">Online Now</p>
                                </div>
                            </div>
                            <button class="text-gray-300 hover:text-gray-600"><i class="fa-solid fa-ellipsis-vertical"></i></button>
                        </div>

                        <div class="flex-grow p-10 overflow-y-auto space-y-8 chat-scroll">
                            <div class="flex justify-start">
                                <div class="max-w-[70%] bg-gray-50 border border-gray-100 px-6 py-4 rounded-[2rem] rounded-tl-none text-[13px] text-gray-700">
                                    Halo Julian, saran saya gunakan kayu jati untuk bagian kaki meja agar estetika ruang tamu lebih hangat.
                                </div>
                            </div>
                            <div class="flex justify-end">
                                <div class="max-w-[70%] bg-primary text-white px-6 py-4 rounded-[2rem] rounded-tr-none shadow-lg shadow-primary/10 text-[13px]">
                                    Terima kasih Elena, saya setuju. Bagaimana dengan finishing-nya?
                                </div>
                            </div>
                        </div>

                        <div class="p-6 bg-white border-t border-gray-50">
                            <div class="bg-gray-50 rounded-full flex items-center px-4 py-1 focus-within:bg-white focus-within:border-gray-100 transition-all border border-transparent">
                                <button class="p-2 text-gray-400 hover:text-primary transition-colors"><i class="fa-solid fa-paperclip"></i></button>
                                <input type="text" placeholder="Tulis pesan..." class="flex-grow bg-transparent border-none outline-none px-4 py-3 text-xs">
                                <button class="w-9 h-9 bg-primary text-white rounded-full flex items-center justify-center shadow-md hover:scale-105 transition-all"><i class="fa-solid fa-paper-plane text-[10px]"></i></button>
                            </div>
                        </div>
                    </section>
                </div>
          
        </div>
    </main>

    <footer class="bg-primary text-white py-12 px-6 mt-12">
        <div class="max-w-6xl mx-auto"> 
            <div class="flex flex-col md:flex-row justify-between items-start border-b border-white/20 pb-10 gap-10">
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
                        <a href="#" class="hover:text-white/70">Terms of Service</a>
                        <a href="#" class="hover:text-white/70">Privacy Policy</a>
                    </div>
                    <div class="flex flex-col space-y-3">
                         <a href="{{ route('customer.help-center') }}" class="hover:text-white/70 transition-colors">Help Center</a>
                        <a href="#" class="hover:text-white/70">FAQ</a>
                    </div>
                </div>

                <div class="flex flex-col items-end space-y-6">
                    <span class="text-[10px] font-bold tracking-widest uppercase text-white/40">Portal Version 1.0</span>
                    <div class="flex space-x-4">
                        <a href="#" class="w-10 h-10 border border-white/40 flex items-center justify-center rounded-full hover:bg-white hover:text-primary transition-all"><i class="fa-brands fa-instagram"></i></a>
                        <a href="#" class="w-10 h-10 border border-white/40 flex items-center justify-center rounded-full hover:bg-white hover:text-primary transition-all"><i class="fa-regular fa-paper-plane"></i></a>
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