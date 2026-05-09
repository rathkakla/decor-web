<?php $site_name = "DECOR"; ?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About — <?= $site_name ?></title>
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
        .content-container { max-width: 1200px; margin: 0 auto; }
        .editorial-text { line-height: 1.1; letter-spacing: -0.05em; }
    </style>
</head>
<body class="text-gray-800 bg-white">

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
                <img src="{{ Auth::user()->avatar_url }}" alt="Profile" class="w-full h-full object-cover bg-slate-100">
            </div>
        </div>
    </div>
</header>

    <main>
        <section class="py-24 px-6 content-container">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-20 items-center">
                <div>
                    <h1 class="text-7xl md:text-8xl font-bold editorial-text mb-8">
                        Elevating <br> <span class="text-primary italic">Atmosphere.</span>
                    </h1>
                    <p class="text-lg text-gray-500 leading-relaxed max-w-md">
                        DECOR presents a premium furniture collection that blends the art of interior design with future technology. We believe every corner of your home is a reflection of your identity.
                    </p>
                </div>
                <div class="relative">
                    <img src="https://images.unsplash.com/photo-1616486341353-c5833ad8f01f?w=800" class="rounded-[3rem] shadow-2xl">
                    <div class="absolute -bottom-10 -left-10 bg-primary text-white p-10 rounded-3xl hidden md:block max-w-[240px]">
                        <p class="text-3xl font-bold italic">2026</p>
                        <p class="text-[10px] uppercase tracking-widest mt-2">The New Era of Curatorial Living</p>
                    </div>
                </div>
            </div>
        </section>

        <section class="bg-gray-50 py-32 px-6">
            <div class="content-container">
                <div class="text-center max-w-3xl mx-auto mb-20">
                    <span class="text-primary text-[10px] font-black uppercase tracking-[0.4em]">Future Intelligence</span>
                    <h2 class="text-4xl font-bold mt-4 mb-6">Generative AI Assistance</h2>
                    <p class="text-gray-400 text-sm leading-relaxed">
                        Kami mengintegrasikan kecerdasan buatan untuk membantu Anda memvisualisasikan hunian impian secara instan.
                    </p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    <div class="bg-white p-10 rounded-[2.5rem] border border-gray-100 hover:shadow-xl transition-all group">
                        <div class="w-12 h-12 bg-secondary/30 rounded-2xl flex items-center justify-center text-primary mb-8 group-hover:bg-primary group-hover:text-white transition-all">
                            <i class="fa-solid fa-wand-magic-sparkles text-xl"></i>
                        </div>
                        <h4 class="font-bold text-lg mb-4">Prompt to Design</h4>
                        <p class="text-xs text-gray-400 leading-relaxed">Ketikkan visi Anda, dan biarkan Generative AI kami menyusun moodboard furnitur yang paling sesuai dalam hitungan detik.</p>
                    </div>

                    <div class="bg-white p-10 rounded-[2.5rem] border border-gray-100 hover:shadow-xl transition-all group">
                        <div class="w-12 h-12 bg-secondary/30 rounded-2xl flex items-center justify-center text-primary mb-8 group-hover:bg-primary group-hover:text-white transition-all">
                            <i class="fa-solid fa-brain text-xl"></i>
                        </div>
                        <h4 class="font-bold text-lg mb-4">Smart Curation</h4>
                        <p class="text-xs text-gray-400 leading-relaxed">AI kami mempelajari preferensi material dan warna Anda untuk menyarankan furnitur dari katalog yang melengkapi koleksi Anda.</p>
                    </div>

                    <div class="bg-white p-10 rounded-[2.5rem] border border-gray-100 hover:shadow-xl transition-all group">
                        <div class="w-12 h-12 bg-secondary/30 rounded-2xl flex items-center justify-center text-primary mb-8 group-hover:bg-primary group-hover:text-white transition-all">
                            <i class="fa-solid fa-cube text-xl"></i>
                        </div>
                        <h4 class="font-bold text-lg mb-4">Virtual Staging</h4>
                        <p class="text-xs text-gray-400 leading-relaxed">Ubah floor plan Anda menjadi visualisasi yang nyata menggunakan teknologi yang kami sediakan.</p>
                    </div>
                </div>
            </div>
        </section>

        <section class="py-32 px-6 content-container text-center">
            <h2 class="text-[10px] font-black uppercase tracking-[0.5em] text-gray-300 mb-12">Our Core Mission</h2>
            <p class="text-3xl md:text-5xl font-medium tracking-tight italic text-gray-400 leading-tight">
                "Empowering every individual to be the  <span class="text-gray-900 not-italic font-bold underline decoration-primary decoration-4">creator</span> of their own home, supported by the best designers and the most advanced technology."
            </p>
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