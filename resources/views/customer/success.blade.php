<?php $site_name = "DECOR"; ?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Success — <?= $site_name ?></title>
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
    </style>
</head>
<body class="text-gray-800">

    <header class="bg-white border-b border-gray-100 sticky top-0 z-50">
    <div class="content-container flex justify-between items-center py-4 px-6 mx-auto max-w-[1200px]">
        
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
</header>>

    <main class="py-20 content-container px-6 text-center">
        <div class="mb-8 flex justify-center">
            <div class="w-20 h-20 bg-gray-50 rounded-2xl flex items-center justify-center border border-gray-100">
                <i class="fa-solid fa-check text-2xl text-primary"></i>
            </div>
        </div>

        <h1 class="text-6xl font-bold tracking-tighter mb-4">Payment Successful!</h1>
        <p class="text-gray-400 text-sm max-w-lg mx-auto leading-relaxed mb-12">
            Your order #DEC-9921 is being processed and curated by our logistical team.
        </p>

        <div class="max-w-3xl mx-auto bg-gray-50 rounded-[2rem] p-10 grid grid-cols-1 md:grid-cols-2 gap-10 text-left mb-16 border border-gray-100">
            <div>
                <h4 class="text-[10px] font-bold uppercase tracking-[0.2em] text-primary mb-4">Delivery Address</h4>
                <p class="text-sm font-bold mb-1">Julianne Moore</p>
                <p class="text-xs text-gray-500 leading-relaxed">
                    4822 Architecture Way, Suite 400<br>
                    Creative District, Copenhagen<br>
                    2100, Denmark
                </p>
            </div>
            <div>
                <h4 class="text-[10px] font-bold uppercase tracking-[0.2em] text-primary mb-4">Estimated Arrival</h4>
                <div class="flex items-start space-x-3 mb-4">
                    <i class="fa-regular fa-calendar text-primary mt-1"></i>
                    <p class="text-sm font-bold">Thursday, Oct 24 -<br>Saturday, Oct 26</p>
                </div>
                <p class="text-[11px] text-gray-400 italic">A tracking link will be sent to your email once the courier departs.</p>
            </div>
        </div>

        <div class="flex flex-col md:flex-row justify-center gap-4 mb-24">
            <a href="{{ route('order') }}" class="inline-block text-center bg-primary text-white px-12 py-5 rounded-sm text-xs font-bold uppercase tracking-widest hover:bg-opacity-90 transition shadow-lg shadow-primary/20">
    Track My Order
</a>
            <a href="{{ route('homepage') }}" class="border border-gray-200 px-12 py-5 rounded-sm text-xs font-bold uppercase tracking-widest hover:bg-gray-50 transition">
                Back to Home
            </a>
        </div>

        <section class="border-t border-gray-100 pt-20 mb-20">
            <div class="flex justify-between items-end mb-12 text-left">
                <h2 class="text-3xl font-bold tracking-tight">Complete your space</h2>
                <a href="{{ route('customer.catalog') }}" class="text-[10px] font-black uppercase tracking-widest text-gray-400 border-b-2 border-gray-100 pb-1 hover:text-primary hover:border-primary transition-all">View Curated Suggestions</a>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8 text-left">
                <div class="group cursor-pointer">
                    <div class="aspect-square rounded-2xl overflow-hidden bg-gray-50 mb-4">
                        <img src="https://images.unsplash.com/photo-1567538096630-e0c55bd6374c?w=600" class="w-full h-full object-cover group-hover:scale-110 transition duration-700">
                    </div>
                    <h3 class="font-bold text-sm">Sculptura Lounge</h3>
                    <p class="text-xs text-gray-400 mt-1">$1,840.00</p>
                </div>
                <div class="group cursor-pointer">
                    <div class="aspect-square rounded-2xl overflow-hidden bg-gray-50 mb-4">
                        <img src="https://images.unsplash.com/photo-1513506003901-1e6a35082161?w=600" class="w-full h-full object-cover group-hover:scale-110 transition duration-700">
                    </div>
                    <h3 class="font-bold text-sm">Mora Stone Lamp</h3>
                    <p class="text-xs text-gray-400 mt-1">$420.00</p>
                </div>
                <div class="group cursor-pointer">
                    <div class="aspect-square rounded-2xl overflow-hidden bg-gray-50 mb-4">
                        <img src="https://images.unsplash.com/photo-1582555172866-f73bb12a2ab3?w=600" class="w-full h-full object-cover group-hover:scale-110 transition duration-700">
                    </div>
                    <h3 class="font-bold text-sm">Abstract No. 4</h3>
                    <p class="text-xs text-gray-400 mt-1">$2,100.00</p>
                </div>
            </div>
        </section>
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