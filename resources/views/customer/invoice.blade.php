<?php 
$site_name = "DECOR"; 
$seller_name = "Luxury Living Official"; // Nama Toko Seller
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice #DC-9920184 — <?= $site_name ?></title>
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
        @media print {
            .no-print { display: none; }
            aside { display: none; }
            header { display: none; }
            footer { display: none; }
            main { margin: 0; padding: 0; border: none; }
            .content-container { max-width: 100%; }
        }
    </style>
</head>
<body class="text-gray-800 flex flex-col min-h-screen">

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
            <a href="#" class="hover:text-primary transition-all">Collections</a>
            <a href="#" class="hover:text-primary transition-all">Designers</a>
             <a href="#" class="hover:text-primary transition-all">AI Studio</a>
        </nav>

        <div class="flex items-center space-x-6 flex-1 justify-end">
            <a href="#" class="text-primary hover:scale-110 transition-transform">
                <i class="fa-solid fa-bag-shopping text-lg"></i>
            </a>
            <button class="text-primary hover:scale-110 transition-transform">
                <i class="fa-regular fa-bell text-lg"></i>
            </button>
            <div class="w-9 h-9 rounded-md overflow-hidden border border-gray-200 cursor-pointer">
                <img src="https://api.dicebear.com/7.x/avataaars/svg?seed=Felix" alt="Profile" class="w-full h-full object-cover bg-slate-100">
            </div>
        </div>
    </div>
</header>

    <main class="flex-grow flex content-container w-full bg-white">
        <aside class="w-72 border-r border-gray-50 p-10 bg-gray-50/20 no-print">
            <div class="text-center mb-10">
                <img src="https://api.dicebear.com/7.x/avataaars/svg?seed=Felix" class="w-20 h-20 rounded-2xl mx-auto mb-4 bg-white shadow-sm border border-gray-100">
                <h3 class="font-bold text-lg">Julian Voss</h3>
                <p class="text-[9px] text-gray-400 uppercase tracking-widest mt-1">Pro Member</p>
            </div>

            <nav class="space-y-1">
                <a href="{{ route('customer.profile') }}" class="flex items-center space-x-4 px-4 py-3 text-gray-400 hover:text-primary transition-colors">
                <i class="fa-regular fa-user text-xs"></i>
                <span class="text-[11px] uppercase tracking-widest">Profile</span>
            </a>
                <a href="{{ route('order') }}" class="flex items-center space-x-4 px-4 py-3 bg-white text-primary font-bold rounded-xl shadow-sm border border-gray-100">
                    <i class="fa-solid fa-box-archive text-xs"></i> <span class="text-[11px] uppercase tracking-widest">Orders</span>
                </a>
                <a href="{{ route('customer.returns') }}" class="flex items-center space-x-4 px-4 py-3 text-gray-400 hover:text-primary transition-colors">
                    <i class="fa-solid fa-rotate-left text-xs"></i> <span class="text-[11px] uppercase tracking-widest">Returns</span>
                </a>
                <a href="{{ route('customer.product-favorite') }}" class="flex items-center space-x-4 px-4 py-3 text-gray-400 hover:text-primary transition-colors">
                    <i class="fa-regular fa-heart text-xs"></i> <span class="text-[11px] uppercase tracking-widest"> Product Favorite</span>
                </a>
                <div class="pt-6 mt-6 border-t border-gray-100">
                    <p class="px-4 text-[9px] font-black text-gray-300 uppercase tracking-widest mb-2">Chat History</p>
                    
                    <a href="{{ route('customer.riwayat-chat') }}" class="flex items-center space-x-4 px-4 py-3 rounded-xl {{ (request()->is('messages/designer')) ? 'bg-white text-primary font-bold shadow-sm border border-gray-100' : 'text-gray-400 hover:text-primary' }}">
                        <i class="fa-solid fa-wand-magic-sparkles text-xs"></i> 
                        <span class="text-[11px] uppercase tracking-widest">Designer</span>
                    </a>

                    <a href="{{ route('customer.riwayat-chat') }}" class="flex items-center space-x-4 px-4 py-3 rounded-xl {{ (request()->is('messages/seller')) ? 'bg-white text-primary font-bold shadow-sm border border-gray-100' : 'text-gray-400 hover:text-primary' }}">
                        <i class="fa-solid fa-shop text-xs"></i> 
                        <span class="text-[11px] uppercase tracking-widest">Seller</span>
                    </a>
                </div>

            </nav>
        </aside>

        <div class="flex-grow p-12">
            <div class="max-w-3xl mx-auto bg-white border border-gray-100 rounded-[2.5rem] p-12 shadow-sm relative overflow-hidden">
                <div class="absolute top-0 left-0 right-0 h-2 bg-primary"></div>

                <div class="flex justify-between items-start mb-16">
                    <div>
                        <h1 class="text-4xl font-black tracking-tighter text-primary uppercase"><?= $site_name ?></h1>
                        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-[0.3em] mt-2">Official Invoice</p>
                    </div>
                    <div class="text-right">
                        <button onclick="window.print()" class="no-print mb-4 text-[10px] font-bold bg-gray-50 px-4 py-2 rounded-lg hover:bg-primary hover:text-white transition-all uppercase tracking-widest">
                            <i class="fa-solid fa-print mr-2"></i> Print PDF
                        </button>
                        <p class="text-sm font-bold">#DC-9920184</p>
                        <p class="text-[11px] text-gray-400">Issued on Oct 23, 2026</p>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-12 mb-16">
                    <div>
                        <p class="text-[9px] font-black text-gray-300 uppercase tracking-widest mb-3">Billed To</p>
                        <p class="text-sm font-bold">Julian Voss</p>
                        <p class="text-[11px] text-gray-400 leading-relaxed mt-1">
                            Architectural Studio<br>
                            Kantstraße 152, 10623 Berlin<br>
                            j.voss@curatorial.studio
                        </p>
                    </div>
                    <div class="text-right">
                        <p class="text-[9px] font-black text-gray-300 uppercase tracking-widest mb-3">Payment Method</p>
                        <p class="text-sm font-bold italic">Mastercard •••• 9012</p>
                        <p class="text-[11px] text-green-500 font-bold uppercase tracking-widest mt-1">Paid in Full</p>
                    </div>
                </div>

                <div class="border-t border-gray-100 pt-8 mb-12">
                    <table class="w-full text-left">
                        <thead>
                            <tr class="text-[9px] font-black text-gray-300 uppercase tracking-widest">
                                <th class="pb-6">Item Description</th>
                                <th class="pb-6 text-center">Qty</th>
                                <th class="pb-6 text-right">Price</th>
                            </tr>
                        </thead>
                        <tbody class="text-sm">
                            <tr class="border-b border-gray-50">
                                <td class="py-6">
                                    <p class="font-bold">Hans Wegner Shell Chair</p>
                                    <p class="text-[10px] text-gray-400 mt-1 uppercase tracking-wider">Walnut / Black Leather</p>
                                    <p class="text-[9px] mt-2 flex items-center text-primary font-semibold italic">
                                        <i class="fa-solid fa-store mr-1 text-[8px]"></i> Seller: <?= $seller_name ?>
                                    </p>
                                </td>
                                <td class="py-6 text-center">01</td>
                                <td class="py-6 text-right font-bold">€1.240,00</td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div class="flex justify-end">
                    <div class="w-64 space-y-3">
                        <div class="flex justify-between text-xs">
                            <span class="text-gray-400">Subtotal</span>
                            <span class="font-bold">€1.240,00</span>
                        </div>
                        <div class="flex justify-between text-xs">
                            <span class="text-gray-400">Shipping</span>
                            <span class="font-bold">€0,00</span>
                        </div>
                        <div class="flex justify-between text-lg border-t border-gray-100 pt-4">
                            <span class="font-black tracking-tighter uppercase">Total</span>
                            <span class="font-black text-primary">€1.240,00</span>
                        </div>
                    </div>
                </div>

                <div class="mt-20 text-center border-t border-gray-50 pt-8">
                    <p class="text-[10px] text-gray-300 font-medium tracking-widest uppercase italic">Thank you for curating with <?= $site_name ?></p>
                </div>
            </div>
        </div>
    </main>

    <footer class="bg-primary text-white py-12 px-6 mt-12">
        <div class="max-w-6xl mx-auto text-center md:text-left"> 
            <p class="text-[10px] text-white/60 font-medium tracking-widest uppercase">
                ©️ 2026 <?= $site_name ?> Marketplace. All Rights Reserved.
            </p>
        </div>
    </footer>

</body>
</html>