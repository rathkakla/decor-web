<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Decor - Choose Your Role</title>
    
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#B5733A', /* Cokelat Tan/Kayu */
                        secondary: '#E3DCD6', /* Warm Grey / Off-White */
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
<body class="bg-secondary min-h-screen flex flex-col font-sans antialiased">

    <header class="w-full px-8 md:px-16 py-6 flex justify-between items-center bg-transparent">
        <div class="text-primary font-bold text-xl tracking-widest uppercase">DECOR</div>
       
        <div class="text-sm font-semibold text-gray-600">
            <a href="" class="hover:text-primary transition">Sign In</a>
        </div>
    </header>

    <main class="flex-grow flex flex-col items-center justify-center px-6 py-12">
        <div class="text-center mb-16">
            <h1 class="text-4xl md:text-5xl font-extrabold text-gray-900 mb-4">Which describes you best?</h1>
            <p class="text-gray-500 font-medium tracking-wide">Please Choose Your Role</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8 w-full max-w-6xl mb-20">
            
            <div class="bg-white rounded-xl shadow-xl p-8 flex flex-col items-center text-center transition-transform hover:scale-105">
                <div class="w-14 h-14 bg-gray-100 rounded-lg flex items-center justify-center mb-6">
                    <i class="fa-solid fa-house-chimney text-gray-700 text-xl"></i>
                </div>
                <h2 class="text-lg font-bold tracking-widest uppercase mb-4">Customer</h2>
                <div class="space-y-2 mb-10">
                    <p class="text-xs text-gray-500 leading-relaxed">I am looking for services and products for my dream project.</p>
                    <p class="text-[10px] text-gray-300 italic">Saya mencari jasa dan produk untuk proyek impian saya.</p>
                </div>
                <a href="{{ route('register.role', 'customer') }}" class="w-full bg-primary hover:bg-[#9a6130] text-white py-3 rounded-md font-bold text-sm text-center block shadow-md">
    Create Your Project
</a>
            </div>

            <div class="bg-white rounded-xl shadow-xl p-8 flex flex-col items-center text-center transition-transform hover:scale-105">
                <div class="w-14 h-14 bg-gray-100 rounded-lg flex items-center justify-center mb-6">
                    <i class="fa-solid fa-box-archive text-gray-700 text-xl"></i>
                </div>
                <h2 class="text-lg font-bold tracking-widest uppercase mb-4">Seller</h2>
                <div class="space-y-2 mb-10">
                    <p class="text-xs text-gray-500 leading-relaxed">I offer products, manufacture goods, or own a brand.</p>
                    <p class="text-[10px] text-gray-300 italic">Saya menawarkan produk, manufaktur barang atau pemilik merk/brand.</p>
                </div>
                <a href="{{ route('register.role', 'seller') }}" class="w-full bg-primary hover:bg-[#9a6130] text-white py-3 rounded-md font-bold text-sm text-center block shadow-md">
    List Your Products
</a>
            </div>

            <div class="bg-white rounded-xl shadow-xl p-8 flex flex-col items-center text-center transition-transform hover:scale-105">
                <div class="w-14 h-14 bg-gray-100 rounded-lg flex items-center justify-center mb-6">
                    <i class="fa-solid fa-compass-drafting text-gray-700 text-xl"></i>
                </div>
                <h2 class="text-lg font-bold tracking-widest uppercase mb-4">Designer</h2>
                <div class="space-y-2 mb-10">
                    <p class="text-xs text-gray-500 leading-relaxed">I offer architectural, interior, and landscape design services.</p>
                    <p class="text-[10px] text-gray-300 italic">Saya menawarkan jasa desain arsitektur, interior, dan lanskap.</p>
                </div>
                <a href="{{ route('register.role', 'designer') }}" class="w-full bg-primary hover:bg-[#9a6130] text-white py-3 rounded-md font-bold text-sm text-center block shadow-md">
    Showcase Portfolio
</a>
            </div>

        </div>

        <div class="w-full max-w-6xl relative h-64 rounded-xl overflow-hidden shadow-2xl group">
            <img src="https://images.unsplash.com/photo-1540518614846-7eded433c457?ixlib=rb-4.0.3&auto=format&fit=crop&w=1200&q=80" 
                 class="w-full h-full object-cover brightness-[0.4] group-hover:scale-105 transition duration-700" alt="Furniture Banner">
            <div class="absolute bottom-8 left-10 text-white">
                <h3 class="text-2xl font-bold mb-2">Join the Architecture Movement</h3>
                <p class="text-sm text-gray-300">Thousands of professionals are already collaborating on DECOR.</p>
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
        </div>
        <div class="pt-8 text-[10px] text-white/60 font-medium tracking-widest">
            <p>©️ 2026 DECOR MARKETPLACE. ALL RIGHTS RESERVED.</p>
        </div>
    </div>
</footer>

</body>
</html>