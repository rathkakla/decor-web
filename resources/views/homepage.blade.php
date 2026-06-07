<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Decor - The Future of Living</title>
    
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#B5733A', /* Cokelat Tan/Kayu */
                        secondary: '#E3DCD6', /* Warm Grey / Off-White */
                        background: '#FAFAF9', /* Latar belakang utama yang sangat terang */
                        dark: '#1C1C1C'
                    },
                    fontFamily: {
                        sans: ['Inter', 'ui-sans-serif', 'system-ui', 'sans-serif'],
                    }
                }
            }
        }
    </script>
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-background text-gray-900 font-sans antialiased">

   <nav class="w-full px-8 md:px-16 py-6 flex justify-between items-center bg-transparent absolute top-0 z-50">
    <div class="text-xl font-extrabold tracking-widest uppercase text-primary">Decor</div>
    
    <div class="hidden md:flex gap-10 text-sm font-bold uppercase tracking-widest">
        <a href="#" class="hover:text-primary transition">Collections</a>
        <a href="#" class="hover:text-primary transition">Magazine</a>
        <a href="#" class="hover:text-primary transition">Architects</a>
    </div>

    <div class="flex items-center gap-6">
        @auth
            <div class="flex items-center gap-4">
                <span class="text-sm font-bold text-primary italic font-serif">Halo, {{ Auth::user()->name }}</span>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="text-[10px] font-bold uppercase tracking-widest text-gray-400 hover:text-red-500">Logout</button>
                </form>
            </div>
        @else
            <a href="{{ route('login') }}" class="text-sm font-bold hover:text-primary transition">Sign In</a>
            <a href="/choose-role" class="bg-primary text-white px-6 py-2 rounded text-xs font-bold shadow-lg shadow-primary/20">Join Us</a>
        @endauth
    </div>
</nav>

    <section class="relative pt-32 pb-20 px-8 md:px-16 max-w-7xl mx-auto flex flex-col-reverse md:flex-row items-center gap-12">
        <div class="flex-1 space-y-6">
            <h1 class="text-5xl md:text-7xl font-extrabold tracking-tight leading-[1.1]">
                The <br>
                Future <br>
                of <span class="text-primary italic font-serif">Living</span>.
            </h1>
            <p class="text-gray-500 max-w-sm text-sm leading-relaxed">
                Curating architectural excellence and sustainable craftsmanship for the modern digital sanctuary. Designed by AI - perfected by artisans.
            </p>
            <div class="flex items-center gap-6 pt-4">
                <a href="#" class="bg-primary hover:bg-[#9a6130] text-white px-8 py-3.5 rounded shadow-lg shadow-primary/30 transition-all font-semibold text-sm">
                    Shop Collections
                </a>
                <a href="#" class="text-sm font-semibold uppercase tracking-wider hover:text-primary transition-colors flex items-center gap-2">
                    View Lookbook <i class="fa-solid fa-arrow-right"></i>
                </a>
            </div>
        </div>
        
        <div class="flex-1 relative">
            <img src="https://images.unsplash.com/photo-1600210492486-724fe5c67fb0?ixlib=rb-4.0.3&auto=format&fit=crop&w=1000&q=80" alt="Modern Living Room" class="rounded-xl shadow-2xl object-cover w-full h-[500px]">
            
            <div class="absolute bottom-8 left-[-2rem] bg-white/90 backdrop-blur-md p-5 rounded-lg shadow-xl border border-white/40 max-w-xs">
                <span class="text-[10px] font-bold tracking-widest text-primary uppercase">Premium Series</span>
                <h3 class="font-bold text-lg mt-1 mb-2">The Ochre Lounge</h3>
                <p class="text-xs text-gray-500 leading-relaxed">A study in imperfect <span class="font-bold text-gray-700">geometry and sustainable</span> oak.</p>
            </div>
        </div>
    </section>

    <section class="py-20 px-8 md:px-16 max-w-7xl mx-auto">
        <div class="flex justify-between items-end mb-10">
            <div>
                <span class="text-[10px] font-bold tracking-widest text-gray-400 uppercase">Categories</span>
                <h2 class="text-3xl font-bold mt-2">Shop by Aesthetic</h2>
            </div>
            <a href="#" class="text-primary text-sm font-semibold hover:underline underline-offset-4">Browse all spaces</a>
        </div>

        <div class="grid grid-cols-2 md:grid-cols-6 gap-6">
            <a href="#" class="flex flex-col items-center justify-center p-6 bg-secondary/20 hover:bg-secondary/50 rounded-xl transition cursor-pointer group">
                <i class="fa-solid fa-couch text-2xl text-gray-600 group-hover:text-primary transition mb-4"></i>
                <span class="text-xs font-bold uppercase tracking-wide">Seating</span>
            </a>
            <a href="#" class="flex flex-col items-center justify-center p-6 bg-secondary/20 hover:bg-secondary/50 rounded-xl transition cursor-pointer group">
                <i class="fa-solid fa-utensils text-2xl text-gray-600 group-hover:text-primary transition mb-4"></i>
                <span class="text-xs font-bold uppercase tracking-wide">Dining</span>
            </a>
            <a href="#" class="flex flex-col items-center justify-center p-6 bg-secondary/20 hover:bg-secondary/50 rounded-xl transition cursor-pointer group">
                <i class="fa-regular fa-lightbulb text-2xl text-gray-600 group-hover:text-primary transition mb-4"></i>
                <span class="text-xs font-bold uppercase tracking-wide">Lighting</span>
            </a>
            <a href="#" class="flex flex-col items-center justify-center p-6 bg-secondary/20 hover:bg-secondary/50 rounded-xl transition cursor-pointer group">
                <i class="fa-solid fa-bed text-2xl text-gray-600 group-hover:text-primary transition mb-4"></i>
                <span class="text-xs font-bold uppercase tracking-wide">Bedroom</span>
            </a>
            <a href="#" class="flex flex-col items-center justify-center p-6 bg-secondary/20 hover:bg-secondary/50 rounded-xl transition cursor-pointer group">
                <i class="fa-solid fa-box-open text-2xl text-gray-600 group-hover:text-primary transition mb-4"></i>
                <span class="text-xs font-bold uppercase tracking-wide">Storage</span>
            </a>
            <a href="#" class="flex flex-col items-center justify-center p-6 bg-secondary/20 hover:bg-secondary/50 rounded-xl transition cursor-pointer group">
                <i class="fa-solid fa-vase text-2xl text-gray-600 group-hover:text-primary transition mb-4"></i>
                <span class="text-xs font-bold uppercase tracking-wide">Decor</span>
            </a>
        </div>
    </section>

    <section class="py-12 px-8 md:px-16 max-w-7xl mx-auto">
        <div class="mb-10">
            <h2 class="text-3xl font-bold mb-3">Curated Arrivals</h2>
            <p class="text-gray-500 text-sm max-w-md">Each piece is hand-selected for its architectural resonance and material honesty.</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-10">
            <div class="group cursor-pointer">
                <div class="relative overflow-hidden rounded-xl bg-gray-100 aspect-[4/5] mb-4">
                    <img src="https://unsplash.com/photos/a-white-vase-with-a-plant-inside-of-it-MbdDaLvIkOk" alt="Vase" class="object-cover w-full h-full group-hover:scale-105 transition duration-500">
                </div>
                <div class="flex justify-between items-start">
                    <div>
                        <h3 class="font-bold text-lg">Terraform Vase</h3>
                        <p class="text-xs text-gray-400 mt-1">Hand-thrown ceramic</p>
                    </div>
                    <span class="font-bold text-primary">$265</span>
                </div>
            </div>

            <div class="group cursor-pointer">
                <div class="relative overflow-hidden rounded-xl bg-gray-100 aspect-[4/5] mb-4">
                    <span class="absolute top-4 left-4 z-10 bg-primary text-white text-[9px] font-bold px-2 py-1 uppercase tracking-wider rounded">Limited Edition</span>
                    <img src="https://images.unsplash.com/photo-1505843490538-5133c6c7d0e1?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80" alt="Armchair" class="object-cover w-full h-full group-hover:scale-105 transition duration-500">
                </div>
                <div class="flex justify-between items-start">
                    <div>
                        <h3 class="font-bold text-lg">Monolith Armchair</h3>
                        <p class="text-xs text-gray-400 mt-1">Aniline tanned leather</p>
                    </div>
                    <span class="font-bold text-primary">$1,590</span>
                </div>
            </div>

            <div class="group cursor-pointer">
                <div class="relative overflow-hidden rounded-xl bg-gray-100 aspect-[4/5] mb-4">
                    <img src="https://images.unsplash.com/photo-1513506003901-1e6a229e9d15?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80" alt="Pendant Light" class="object-cover w-full h-full group-hover:scale-105 transition duration-500">
                </div>
                <div class="flex justify-between items-start">
                    <div>
                        <h3 class="font-bold text-lg">Solstice Pendant</h3>
                        <p class="text-xs text-gray-400 mt-1">Brass & frosted glass</p>
                    </div>
                    <span class="font-bold text-primary">$340</span>
                </div>
            </div>
        </div>
    </section>

    <section class="py-20">
        <div class="relative w-full h-[500px] bg-dark bg-[url('https://www.transparenttextures.com/patterns/carbon-fibre.png')] flex items-center justify-center px-4">
            
            <div class="relative z-10 w-full max-w-4xl bg-white/80 backdrop-blur-xl border border-white/40 rounded-3xl p-10 md:p-16 shadow-[0_20px_50px_rgba(0,0,0,0.3)] flex flex-col md:flex-row items-center gap-10">
                
                <div class="flex-1">
                    <span class="inline-block bg-primary/10 text-primary text-[10px] font-bold px-3 py-1 rounded uppercase tracking-widest mb-4">Generative Design</span>
                    <h2 class="text-4xl font-extrabold mb-4 leading-tight">
                        Your room, <br>
                        <span class="text-primary italic font-serif">reimagined</span> <br>
                        by AI.
                    </h2>
                    <p class="text-sm text-gray-600 mb-8 max-w-xs">
                        Upload a photo of your space and let our proprietary AI curate a collection that perfectly balances your architecture and lifestyle.
                    </p>
                    <button class="bg-primary hover:bg-[#9a6130] text-white px-6 py-3 rounded flex items-center gap-2 text-sm font-semibold transition shadow-lg">
                        Launch Designer <i class="fa-solid fa-wand-magic-sparkles"></i>
                    </button>
                </div>

                <div class="flex-1 relative w-full h-48 md:h-full flex justify-center items-center">
                    <div class="absolute right-10 top-0 w-40 h-40 bg-gray-200 rounded-lg shadow-lg overflow-hidden border-2 border-white">
                        <img src="https://images.unsplash.com/photo-1493809842364-78817add7ffb?ixlib=rb-4.0.3&auto=format&fit=crop&w=400&q=80" alt="Empty Room" class="object-cover w-full h-full opacity-60">
                    </div>
                    <div class="absolute left-0 bottom-[-2rem] w-48 h-48 bg-gray-200 rounded-lg shadow-2xl overflow-hidden border-4 border-white z-20">
                        <img src="https://images.unsplash.com/photo-1600210491369-e753d80a41f3?ixlib=rb-4.0.3&auto=format&fit=crop&w=400&q=80" alt="Furnished Room" class="object-cover w-full h-full">
                    </div>
                    <div class="absolute bottom-6 left-1/2 transform -translate-x-1/2 z-30 bg-white shadow-lg rounded-full py-2 px-4 flex items-center gap-2 text-[10px] font-bold text-gray-700 whitespace-nowrap border border-gray-100">
                        <i class="fa-solid fa-circle-notch fa-spin text-primary"></i>
                        Applying 'Scandinavian Minimalist' palette
                    </div>
                </div>

            </div>
        </div>
    </section>

    <section class="py-24 px-8 md:px-16 max-w-7xl mx-auto flex flex-col md:flex-row items-center gap-16">
        <div class="flex-1 relative">
            <div class="absolute -bottom-6 -right-6 w-full h-full bg-secondary rounded-xl -z-10"></div>
            <img src="https://images.unsplash.com/photo-1622359416550-93bc71d531a7?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80" alt="Craftsman working" class="rounded-xl shadow-xl w-full object-cover h-[450px]">
        </div>

        <div class="flex-1">
            <span class="text-[10px] font-bold tracking-widest text-gray-400 uppercase mb-4 block">Our Heritage</span>
            <h2 class="text-4xl md:text-5xl font-extrabold mb-6 leading-tight">
                Where technology meets <span class="text-primary italic font-serif">material honesty</span>.
            </h2>
            <p class="text-gray-500 text-sm leading-relaxed mb-8 max-w-md">
                We believe that the finest furniture is born from the tension between digital precision and human soul. Our workshop combines robotic carving with hand-rubbed finishes, ensuring each CURA piece is a future heirloom.
            </p>
            <a href="#" class="text-sm font-bold text-gray-800 hover:text-primary transition border-b-2 border-primary pb-1 inline-block">
                Learn about our process
            </a>
        </div>
    </section>

</body>
</html>