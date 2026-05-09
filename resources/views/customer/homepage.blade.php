<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Decor - The Future of Living</title>
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
<?php $site_name = "DECOR"; ?>

<header class="bg-white border-b border-gray-100 sticky top-0 z-50">
    <div class="content-container flex justify-between items-center py-4 px-6 mx-auto max-w-[1200px]">
        
        <div class="flex items-center space-x-8 flex-1">
            <a href="{{ route('homepage') }}" class="text-2xl font-black tracking-tighter uppercase text-primary hover:opacity-80 transition-all">
                <?= $site_name ?>
            </a>
            
            <!-- Tambahkan tag form yang mengarah ke route katalog -->
<form action="{{ route('customer.catalog') }}" method="GET" class="hidden lg:flex items-center bg-gray-50 border border-gray-100 rounded-md px-4 py-2 w-full max-w-[180px] group focus-within:bg-white focus-within:border-primary/30 transition-all">
    <i class="fa-solid fa-magnifying-glass text-gray-400 text-[10px] mr-2"></i>
    
    <!-- Tambahkan name="search" agar bisa dibaca $request->search di Controller -->
    <!-- Tambahkan value="{{ request('search') }}" agar teks pencarian tidak hilang setelah enter -->
    <input 
        type="text" 
        name="search" 
        value="{{ request('search') }}" 
        placeholder="Search..." 
        class="bg-transparent border-none outline-none text-[10px] w-full placeholder:text-gray-400"
    >
</form>
        </div>

        <nav class="hidden md:flex items-center space-x-10 text-[13px] font-medium text-gray-500 tracking-wide">
            <a href="{{ route('customer.catalog') }}" class="hover:text-primary transition-all">Collections</a>
            <a href="{{ route('customer.designers') }}" class="hover:text-primary transition-all">Designers</a>
            <a href="{{ route('customer.design-lab') }}" class="hover:text-primary transition-all">AI Studio</a>
        </nav>

       <div class="flex items-center space-x-6 flex-1 justify-end">
    @auth
        <div class="flex items-center gap-4 border-r pr-6 border-gray-100">
            <div class="text-right hidden sm:block">
                <p class="text-[9px] uppercase tracking-widest text-gray-400 font-bold leading-none mb-1">Welcome back</p>
                <p class="text-xs font-bold text-primary capitalize">{{ Auth::user()->full_name }}</p>
            </div>
            
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="text-gray-400 hover:text-red-500 transition-colors" title="Logout">
                    <i class="fa-solid fa-power-off text-sm"></i>
                </button>
            </form>
        </div>

        <a href="{{ route('customer.cart') }}" class="text-primary hover:scale-110 transition-transform">
            <i class="fa-solid fa-bag-shopping text-lg"></i>
        </a>

        <div class="w-9 h-9 rounded-md overflow-hidden border border-gray-200 cursor-pointer hover:border-primary transition-all">
            <a href="{{ route('customer.profile') }}" class="block w-full h-full">
                <img src="{{ Auth::user()->avatar_url }}" class="w-full h-full bg-slate-100 object-cover">
            </a>
        </div>
    @else
        <a href="{{ route('login') }}" class="text-[11px] font-bold uppercase tracking-widest text-gray-500 hover:text-primary transition-all">
            Sign In
        </a>
        
        <a href="{{ route('role.selection') }}">
            <button class="bg-primary text-white px-6 py-2.5 rounded-sm text-[10px] font-black uppercase tracking-[0.2em] shadow-lg shadow-primary/20 hover:bg-opacity-90 transition-all">
                Join Us
            </button>
        </a>
    @endauth
</div>
    </div>
</header>

<main class="py-12">
    <section class="content-container px-6 grid md:grid-cols-2 gap-12 items-center mb-24">
        <div class="space-y-6">
            <h1 class="text-7xl font-bold leading-[1.1] tracking-tight">The<br>Future<br>of <span class="text-primary italic">Living.</span></h1>
            <p class="text-gray-400 text-sm max-w-sm leading-relaxed">Curating architectural excellence and sustainable craftsmanship for the modern world. Designed by innovators, for visionaries.</p>
            
            <div class="flex items-center space-x-6 pt-4">
                <a href="{{ route('customer.catalog') }}">
                    <button class="bg-primary text-white px-8 py-4 rounded-sm text-xs font-bold uppercase tracking-widest hover:bg-opacity-90 transition shadow-lg shadow-primary/20">
                        Shop Collection
                    </button>
                </a>
                <a href="{{ route('customer.about') }}" class="text-[11px] font-black border-b-2 border-gray-800 pb-1 uppercase tracking-[0.2em] hover:text-primary hover:border-primary transition-all">About Decor →</a>
            </div>
        </div>
        <div class="relative max-w-md mx-auto md:mr-0">
            <img src="https://images.unsplash.com/photo-1616486338812-3dadae4b4ace?auto=format&fit=crop&q=80&w=800" alt="Hero" class="rounded-lg shadow-2xl w-full h-[450px] object-cover">
            <div class="absolute bottom-6 left-6 bg-white/95 backdrop-blur-md p-6 max-w-[240px] rounded-sm shadow-xl">
                <span class="text-[9px] uppercase tracking-[0.3em] text-primary font-bold mb-1 block">New Release</span>
                <h3 class="font-bold text-base mb-1">The Ochre Lounge</h3>
                <p class="text-[11px] text-gray-500 leading-relaxed italic">"A fusion of Japanese minimalism and mid-century soul."</p>
            </div>
        </div>
    </section>

    <section class="content-container px-6 py-16 mb-16 bg-secondary/20 rounded-3xl">
        <div class="flex justify-between items-end mb-10">
            <div>
                <h2 class="text-2xl font-bold">Shop by Aesthetic</h2>
                <p class="text-gray-400 text-xs mt-1">Explore our collections by room and style.</p>
            </div>
            <a href="{{ route('customer.catalog') }}" class="text-[10px] font-bold uppercase border-b border-gray-400 pb-1 hover:text-primary hover:border-primary transition-all">Browse All Styles</a>
        </div>
        <div class="grid grid-cols-2 md:grid-cols-6 gap-4">
            <?php
            $cats = [
                ['name' => 'Seating', 'icon' => 'fa-couch'],
                ['name' => 'Living', 'icon' => 'fa-house-chimney-window'],
                ['name' => 'Lighting', 'icon' => 'fa-lightbulb'],
                ['name' => 'Bedroom', 'icon' => 'fa-bed'],
                ['name' => 'Storage', 'icon' => 'fa-box-archive'],
                ['name' => 'Decor', 'icon' => 'fa-leaf']
            ];
            foreach ($cats as $c) {
                echo "<a href='".route('customer.catalog')."' class='bg-white p-6 flex flex-col items-center justify-center space-y-4 rounded-xl hover:shadow-xl hover:-translate-y-1 transition-all cursor-pointer border border-transparent hover:border-primary/10 group'>
                        <div class='w-12 h-12 bg-gray-50 flex items-center justify-center group-hover:bg-primary/10 transition-colors rounded-full'>
                            <i class='fa-solid {$c['icon']} text-sm text-gray-400 group-hover:text-primary'></i>
                        </div>
                        <span class='text-[10px] font-bold uppercase tracking-widest text-gray-600 group-hover:text-primary'>{$c['name']}</span>
                      </a>";
            }
            ?>
        </div>
    </section>

    <section class="content-container px-6 mb-24">
        <div class="bg-zinc-900 rounded-[2rem] p-10 md:p-16 text-center text-white relative overflow-hidden shadow-2xl">
            <div class="relative z-10 space-y-6 flex flex-col items-center">
                <span class="text-primary text-[10px] uppercase tracking-[0.4em] font-black bg-white/5 px-4 py-2 rounded-full border border-white/10">Next-Gen Interface</span>
                <h2 class="text-4xl md:text-5xl font-bold leading-tight">Your room, <br><span class="text-primary italic">reimagined</span> by AI.</h2>
                <p class="text-gray-400 max-w-md mx-auto text-sm leading-relaxed">Unggah foto ruangan Anda dan biarkan teknologi AI kami mengkurasi furnitur yang paling sesuai dengan struktur arsitektur rumah Anda secara otomatis.</p>
                <a href="{{ route('customer.design-lab') }}">
                    <button class="bg-primary text-white px-8 py-3.5 rounded-sm font-bold text-xs flex items-center space-x-3 hover:bg-white hover:text-primary transition-all duration-300">
                        <span>LAUNCH AI DESIGNER</span>
                        <i class="fa-solid fa-wand-magic-sparkles"></i>
                    </button>
                </a>
            </div>
            <div class="absolute top-0 right-0 w-80 h-80 bg-primary/20 blur-[120px] rounded-full -mr-32 -mt-32"></div>
            <div class="absolute bottom-0 left-0 w-80 h-80 bg-primary/10 blur-[100px] rounded-full -ml-32 -mb-32"></div>
        </div>
    </section>

    <section class="content-container px-6 mb-24">
        <h2 class="text-2xl font-bold mb-1">Curated Arrivals</h2>
        <p class="text-gray-400 text-xs mb-10 tracking-wide">Freshly sourced pieces for the discerning collector.</p>
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-10">
            <div class="group cursor-pointer">
                <div class="bg-gray-100 aspect-[4/5] rounded-2xl overflow-hidden mb-5">
                    <img src="https://images.unsplash.com/photo-1581783898377-1c85bf937427?auto=format&fit=crop&q=80&w=600" class="w-full h-full object-cover group-hover:scale-110 transition duration-700">
                </div>
                <div class="flex justify-between items-center px-1">
                    <div>
                        <h4 class="font-bold text-gray-900 group-hover:text-primary transition-colors">Terraform Vase</h4>
                        <p class="text-[10px] text-gray-400 uppercase font-bold tracking-widest mt-0.5">Ceramic • Limited</p>
                    </div>
                    <span class="font-bold text-sm bg-secondary/40 px-3 py-1 rounded-full">$180</span>
                </div>
            </div>
        </div>
    </section>
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
        </div>
        <div class="pt-8 text-[10px] text-white/60 font-medium tracking-widest">
            <p>©️ 2026 DECOR MARKETPLACE. ALL RIGHTS RESERVED.</p>
        </div>
    </div>
</footer>
</body>
</html>