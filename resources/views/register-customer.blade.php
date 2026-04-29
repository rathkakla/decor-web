<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Decor - Begin Your Collection</title>
    
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
<body class="bg-white min-h-screen flex font-sans antialiased overflow-x-hidden">

    <section class="hidden lg:flex lg:w-1/2 relative bg-gray-900 items-end p-20 overflow-hidden">
        <img src="https://images.unsplash.com/photo-1593455483017-ed620ec55640?ixlib=rb-4.0.3&auto=format&fit=crop&w=1200&q=80" 
             class="absolute inset-0 w-full h-full object-cover opacity-70" alt="Interior">
        
        <div class="relative z-10 w-full">
            <div class="flex items-center gap-4 mb-24 absolute top-[-30rem]">
                <span class="text-white font-extrabold text-2xl tracking-widest uppercase">DECOR</span>
                <div class="h-[2px] w-12 bg-white mt-1"></div>
            </div>

            <div class="space-y-6">
                <p class="text-gray-300 text-xs font-bold uppercase tracking-[0.2em]">The Digital Curator</p>
                <h1 class="text-white text-6xl font-extrabold leading-tight">
                    Space is the breath <br> of art.
                </h1>
                <p class="text-gray-300 text-sm italic font-medium max-w-md leading-relaxed border-l-2 border-primary pl-4">
                    "Join a community where every piece of furniture tells a story of craftsmanship and curated intent."
                </p>
            </div>
        </div>
    </section>

    <section class="w-full lg:w-1/2 flex flex-col p-8 md:p-16 lg:p-20 relative">
        
        <div class="absolute top-10 right-10">
            <a href="/" class="text-[10px] font-bold text-gray-500 uppercase tracking-widest flex items-center gap-2 hover:text-primary transition">
                Back to gallery <i class="fa-solid fa-xmark text-sm"></i>
            </a>
        </div>

        <div class="max-w-md mx-auto w-full flex-grow flex flex-col justify-center">
            <div class="mb-10">
                <h2 class="text-3xl font-extrabold text-gray-900 mb-2">Begin your collection.</h2>
                <p class="text-gray-500 text-sm">Enter your details to create an account.</p>
            </div>
    @if ($errors->any())
    <div class="bg-red-100 text-red-700 p-3 rounded mb-5 text-xs font-bold uppercase">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
    <form action="{{ route('register') }}" method="POST">
    @csrf
    
    <div class="mb-6">
        <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-2">Username</label>
        <input type="text" name="username" required class="w-full border-b border-gray-200 py-2 focus:outline-none focus:border-primary text-sm">
    </div>

    <div class="mb-6">
        <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-2">Full Name</label>
        <input type="text" name="name" required class="w-full border-b border-gray-200 py-2 focus:outline-none focus:border-primary text-sm">
    </div>

    <div class="mb-6">
        <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-2">Email Address</label>
        <input type="email" name="email" required class="w-full border-b border-gray-200 py-2 focus:outline-none focus:border-primary text-sm">
    </div>

    <div class="mb-6">
        <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-2">Password</label>
        <input type="password" name="password" required class="w-full border-b border-gray-200 py-2 focus:outline-none focus:border-primary text-sm">
    </div>

    <div class="mb-10">
        <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-2">Confirm Password</label>
        <input type="password" name="password_confirmation" required class="w-full border-b border-gray-200 py-2 focus:outline-none focus:border-primary text-sm">
    </div>

    <button type="submit" class="w-full bg-primary text-white py-4 rounded font-bold text-sm shadow-xl shadow-primary/20">
        Create Account
    </button>
</form>

            <div class="mt-10">
                <div class="relative flex items-center justify-center mb-8">
                    <div class="border-t border-gray-100 w-full"></div>
                    <span class="bg-white px-4 text-[9px] font-bold text-gray-300 uppercase tracking-[0.2em] absolute">Or join with</span>
                </div>

                <div class="flex gap-4">
                    <button class="flex-1 border border-gray-100 py-3 rounded flex items-center justify-center gap-3 hover:bg-gray-50 transition text-[11px] font-bold text-gray-600 shadow-sm">
                        <img src="https://www.svgrepo.com/show/475656/google-color.svg" class="w-4 h-4" alt="Google"> Google
                    </button>
                    <button class="flex-1 border border-gray-100 py-3 rounded flex items-center justify-center gap-3 hover:bg-gray-50 transition text-[11px] font-bold text-gray-600 shadow-sm">
                        <i class="fa-brands fa-apple text-lg"></i> Apple
                    </button>
                </div>
            </div>

            <div class="mt-auto pt-10 text-center">
                <p class="text-[11px] text-gray-400 font-bold tracking-wide">
                    Already part of the studio? <a href="/login" class="text-primary hover:underline underline-offset-4">Sign in here.</a>
                </p>
            </div>
        </div>

        <div class="absolute bottom-0 left-0 right-0 pointer-events-none -mb-10 text-center">
            <h3 class="text-[150px] font-extrabold text-gray-50 tracking-tighter leading-none opacity-50">DECOR</h3>
        </div>
    </section>

</body>
</html>