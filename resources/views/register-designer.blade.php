<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Decor - Create Professional Profile</title>
    
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#B5733A', /* Cokelat Tan/Kayu */
                        secondary: '#E3DCD6', /* Warm Grey */
                    },
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                    }
                }
            }
        }
    </script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-white min-h-screen flex font-sans antialiased overflow-x-hidden">

    <section class="hidden lg:flex lg:w-1/2 relative bg-gray-900 items-end p-20 overflow-hidden">
        <img src="https://images.unsplash.com/photo-1618221195710-dd6b41faaea6?ixlib=rb-4.0.3&auto=format&fit=crop&w=1200&q=80" 
             class="absolute inset-0 w-full h-full object-cover opacity-60" alt="Designer Interior">
        
        <div class="relative z-10 w-full">
            <div class="flex items-center gap-4 mb-24 absolute top-[-30rem]">
                <span class="text-white font-extrabold text-2xl tracking-widest uppercase">DECOR</span>
                <div class="h-[2px] w-12 bg-white mt-1"></div>
            </div>

            <div class="space-y-6">
                <p class="text-gray-300 text-xs font-bold uppercase tracking-[0.2em]">The Digital Curator</p>
                <h1 class="text-white text-6xl font-extrabold leading-tight">
                    Curating the space <br> between <span class="italic font-light">vision</span> and <br> reality.
                </h1>
                <p class="text-gray-300 text-sm italic font-medium max-w-md leading-relaxed border-l-2 border-primary pl-4">
                    "Join a community where every piece of furniture tells a story of craftsmanship and curated intent."
                </p>
            </div>
        </div>
    </section>

    <section class="w-full lg:w-1/2 flex flex-col p-8 md:p-16 lg:p-20 relative bg-white">
        
        <div class="max-w-md mx-auto w-full flex-grow flex flex-col justify-center">
            <div class="mb-10">
                <h2 class="text-3xl font-extrabold text-gray-900 mb-2">Create Professional Profile</h2>
                <p class="text-gray-500 text-sm">Join our elite community of architectural visionaries.</p>
            </div>

            @if ($errors->any())
                <div class="mb-6 p-4 bg-red-50 border-l-4 border-red-500 text-red-700 text-xs uppercase font-bold">
                    @foreach ($errors->all() as $error) <p>{{ $error }}</p> @endforeach
                </div>
            @endif

            <form action="{{ route('register') }}" method="POST">
                @csrf
                
                <div class="mb-8">
                    <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-4">I AM A...</label>
                    <div class="flex gap-3">
                        <a href="{{ route('register.role', 'customer') }}" class="flex-1 bg-secondary/30 text-gray-500 py-2 rounded text-center text-[11px] font-bold border border-secondary transition">Customer</a>
                        <a href="{{ route('register.role', 'seller') }}" class="flex-1 bg-secondary/30 text-gray-500 py-2 rounded text-center text-[11px] font-bold border border-secondary transition">Seller</a>
                        <button type="button" class="flex-1 bg-primary text-white py-2 rounded text-[11px] font-bold shadow-lg shadow-primary/20">Designer</button>
                    </div>
                </div>

                <div class="space-y-6 mb-10">
                    <div>
                        <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1">Full Name</label>
                        <input type="text" name="name" placeholder="Julianne Moore" required
                               class="w-full border-b border-gray-200 py-2 focus:outline-none focus:border-primary text-sm text-gray-800">
                    </div>

                    <div>
                        <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1">Email Address</label>
                        <input type="email" name="email" placeholder="julianne@studio.design" required
                               class="w-full border-b border-gray-200 py-2 focus:outline-none focus:border-primary text-sm text-gray-800">
                    </div>

                    <div>
                        <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1">Professional Category</label>
                        <select name="category" class="w-full border-b border-gray-200 py-2 focus:outline-none focus:border-primary text-sm text-gray-800 bg-transparent cursor-pointer">
                            <option value="interior_designer">Interior Designer</option>
                            <option value="architect">Architect</option>
                            <option value="landscape_designer">Landscape Designer</option>
                        </select>
                    </div>

                    <div class="flex gap-4">
                        <div class="flex-1">
                            <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1">Password</label>
                            <input type="password" name="password" placeholder="••••••••" required
                                   class="w-full border-b border-gray-200 py-2 focus:outline-none focus:border-primary text-sm">
                        </div>
                        <div class="flex-1">
                            <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1">Confirm Password</label>
                            <input type="password" name="password_confirmation" placeholder="••••••••" required
                                   class="w-full border-b border-gray-200 py-2 focus:outline-none focus:border-primary text-sm">
                        </div>
                    </div>
                </div>

                <button type="submit" class="w-full bg-primary hover:bg-[#9a6130] text-white py-4 rounded font-bold text-sm transition flex justify-center items-center gap-4 shadow-xl shadow-primary/20">
                    Register As Designer <i class="fa-solid fa-arrow-right-long text-xs"></i>
                </button>
            </form>

            <div class="mt-auto pt-10 text-center">
                <p class="text-[11px] text-gray-400 font-bold tracking-wide">
                    Already have an account? <a href="{{ route('login') }}" class="text-primary hover:underline underline-offset-4">Sign in</a>
                </p>
            </div>
        </div>

        <div class="absolute bottom-0 left-0 right-0 pointer-events-none -mb-10 text-center">
            <h3 class="text-[150px] font-extrabold text-gray-50 tracking-tighter leading-none opacity-50 uppercase">Decor</h3>
        </div>
    </section>

</body>
</html>