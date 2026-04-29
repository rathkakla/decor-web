<?php $site_name = "DECOR"; ?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Consultation Workspace — <?= $site_name ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: { primary: '#B5733A', secondary: '#E3DCD6' }
                }
            }
        }
    </script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;600;700&display=swap');
        body { font-family: 'Plus Jakarta Sans', sans-serif; overflow: hidden; }
        .chat-scroll::-webkit-scrollbar { width: 4px; }
        .chat-scroll::-webkit-scrollbar-thumb { background: #f1f1f1; border-radius: 10px; }
        .unlock-animation { transition: all 0.8s cubic-bezier(0.4, 0, 0.2, 1); }
    </style>
</head>
<body class="text-gray-800 flex flex-col h-screen bg-white">

    <header class="bg-white border-b border-gray-100 sticky top-0 z-50">
        <div class="content-container flex justify-between items-center py-4 px-6">
            <div class="flex items-center space-x-8 flex-1">
                <a href="{{ route('homepage') }}" class="text-2xl font-black tracking-tighter uppercase text-primary hover:opacity-80 transition-all">
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
                <a href="{{ route('customer.cart') }}" class="text-primary hover:scale-110 transition-transform">
                    <i class="fa-solid fa-bag-shopping text-lg"></i>
                </a>
                <button class="text-primary hover:scale-110 transition-transform">
                    <i class="fa-regular fa-bell text-lg"></i>
                </button>
                <div class="w-9 h-9 rounded-md overflow-hidden border border-gray-200 cursor-pointer">
<a href="{{ route('customer.profile') }}" class="block">
    <div class="w-9 h-9 rounded-md overflow-hidden border border-gray-200 cursor-pointer hover:border-primary transition-all">
        <img src="https://api.dicebear.com/7.x/avataaars/svg?seed=Felix" class="w-full h-full bg-slate-100">
    </div>
</a>
                </div>
            </div>
        </div>
    </header>
    <main class="flex-grow flex overflow-hidden">
        <aside class="w-1/4 border-r border-gray-100 flex flex-col bg-white">
            <div class="p-6 border-b border-gray-50">
                <h3 class="text-xs font-black uppercase tracking-widest text-gray-400 mb-4 italic">Workspace</h3>
                <input type="text" placeholder="Search..." class="w-full bg-gray-50 border-none rounded-xl py-3 px-4 text-xs outline-none">
            </div>
            <div class="flex-grow overflow-y-auto chat-scroll">
                <div class="p-6 flex items-center gap-4 bg-gray-50/50 border-r-4 border-primary cursor-pointer transition-all">
                    <div class="relative w-12 h-12 rounded-2xl bg-secondary flex items-center justify-center text-primary font-bold text-sm shadow-sm">JT</div>
                    <div class="flex-grow min-w-0">
                        <div class="flex justify-between items-baseline">
                            <h4 class="text-xs font-bold truncate italic">Julian Thorne</h4>
                            <span class="text-[9px] text-gray-400 uppercase font-bold">Active</span>
                        </div>
                        <p id="sidebar-status" class="text-[11px] text-primary font-bold mt-1 tracking-tight italic">Menunggu Pembayaran...</p>
                    </div>
                </div>
            </div>
        </aside>

        <section class="flex-grow flex flex-col bg-white relative">
            <div class="px-8 py-5 border-b border-gray-50 flex justify-between items-center">
                <div class="flex items-center gap-4">
                    <div class="w-10 h-10 rounded-xl bg-secondary flex items-center justify-center text-primary font-bold text-xs">JT</div>
                    <div>
                        <h3 class="text-sm font-black uppercase tracking-tighter italic">Julian Thorne</h3>
                        <p id="online-status" class="text-[10px] font-bold text-gray-300 uppercase tracking-widest italic">Locked Mode</p>
                    </div>
                </div>
            </div>

            <div class="relative flex-grow flex flex-col overflow-hidden">
                <div id="paywall-overlay" class="absolute inset-0 z-40 bg-white/40 backdrop-blur-md flex items-center justify-center p-6 unlock-animation">
                    <div class="max-w-sm w-full bg-white border border-gray-100 rounded-[3rem] p-12 shadow-[0_20px_50px_rgba(181,115,58,0.15)] text-center">
                        <div class="w-20 h-20 bg-secondary/30 rounded-[2rem] flex items-center justify-center text-primary mx-auto mb-8">
                            <i class="fa-solid fa-lock text-3xl"></i>
                        </div>
                        <h3 class="text-2xl font-bold italic mb-3 italic">Buka Konsultasi</h3>
                        <p class="text-[11px] text-gray-400 leading-relaxed mb-10 px-4 italic">Bayar jasa konsultasi untuk mulai merancang ruang impian Anda.</p>
                        <button onclick="openModal()" class="w-full bg-primary text-white py-5 rounded-2xl text-[11px] font-black uppercase tracking-[0.3em] shadow-xl shadow-primary/20 hover:scale-[1.03] transition-all">
                            Bayar Rp 50.000
                        </button>
                    </div>
                </div>

                <div id="chat-content" class="flex-grow p-10 space-y-10 overflow-y-auto blur-[10px] opacity-20 pointer-events-none unlock-animation">
                    <div class="flex justify-start">
                        <div class="max-w-[70%] bg-gray-50 px-8 py-5 rounded-[2.5rem] rounded-tl-none text-[13px] leading-relaxed">
                            Halo Anisa! Saya Julian. Senang bisa membantu proyek interior Anda. Apa konsep utama yang Anda inginkan?
                        </div>
                    </div>
                    <div class="flex justify-end">
                        <div class="max-w-[70%] bg-primary text-white px-8 py-5 rounded-[2.5rem] rounded-tr-none text-[13px] leading-relaxed shadow-lg shadow-primary/10">
                            Saya ingin gaya Mid-Century Modern tapi tetap terasa hangat dengan banyak material kayu Walnut.
                        </div>
                    </div>
                </div>

                <div id="input-area" class="p-8 bg-white opacity-20 pointer-events-none unlock-animation">
                    <div class="bg-gray-50 rounded-full flex items-center px-6 py-3 border border-gray-100 gap-4">
                        <button class="text-gray-400 hover:text-primary transition-colors text-lg">
                            <i class="fa-solid fa-paperclip"></i>
                        </button>
                        <input type="text" placeholder="Tulis pesan..." class="flex-grow bg-transparent border-none outline-none text-sm">
                        <button class="w-10 h-10 bg-primary text-white rounded-full flex items-center justify-center hover:scale-105 transition-all shadow-md">
                            <i class="fa-solid fa-paper-plane text-xs"></i>
                        </button>
                    </div>
                </div>
            </div>
        </section>
    </main>

    <div id="payment-modal" class="hidden fixed inset-0 z-[60] bg-black/60 backdrop-blur-sm flex items-center justify-center p-6">
        <div class="bg-white w-full max-w-md rounded-[3rem] p-10 shadow-2xl scale-95 opacity-0 transition-all duration-300" id="modal-content">
            <h4 class="text-center text-[10px] font-black uppercase tracking-[0.3em] text-primary mb-8 italic italic">Payment Method</h4>
            <div class="space-y-4 mb-10">
                <div class="flex items-center justify-between p-5 border-2 border-primary rounded-2xl bg-primary/5 cursor-pointer">
                    <div class="flex items-center gap-4"><span class="text-xl">🏦</span><b class="text-xs italic">Bank Transfer (BCA)</b></div>
                    <i class="fa-solid fa-circle-check text-primary"></i>
                </div>
                <div class="flex items-center justify-between p-5 border border-gray-100 rounded-2xl hover:bg-gray-50 cursor-pointer">
                    <div class="flex items-center gap-4 text-gray-400"><span class="text-xl opacity-50">📱</span><b class="text-xs italic">E-Wallet (GoPay/Dana)</b></div>
                </div>
            </div>
            <button onclick="finishPayment()" class="w-full bg-gray-900 text-white py-5 rounded-2xl text-[11px] font-black uppercase tracking-[0.3em] hover:bg-black transition-all">
                Selesai Melakukan Pembayaran
            </button>
        </div>
    </div>

    <script>
        function openModal() {
            const modal = document.getElementById('payment-modal');
            const content = document.getElementById('modal-content');
            modal.classList.remove('hidden');
            setTimeout(() => {
                content.classList.remove('scale-95', 'opacity-0');
                content.classList.add('scale-100', 'opacity-100');
            }, 10);
        }

        function finishPayment() {
            const modal = document.getElementById('payment-modal');
            modal.classList.add('hidden');

            const overlay = document.getElementById('paywall-overlay');
            const chatContent = document.getElementById('chat-content');
            const inputArea = document.getElementById('input-area');
            const sidebarStatus = document.getElementById('sidebar-status');
            const onlineStatus = document.getElementById('online-status');

            overlay.style.opacity = '0';
            overlay.style.pointerEvents = 'none';
            
            setTimeout(() => {
                overlay.style.display = 'none';
                chatContent.classList.remove('blur-[10px]', 'opacity-20', 'pointer-events-none');
                inputArea.classList.remove('opacity-20', 'pointer-events-none');
                sidebarStatus.innerText = "Mari kita mulai...";
                sidebarStatus.classList.replace('text-primary', 'text-gray-400');
                onlineStatus.innerText = "Online Now";
                onlineStatus.classList.replace('text-gray-300', 'text-green-500');
            }, 500);
        }
    </script>
</body>
</html>