<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DECOR — Choose Your Role</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#B5733A',
                        'primary-dark': '#8A5229',
                        secondary: '#E3DCD6',
                        lightbg: '#F8F6F4'
                    },
                    fontFamily: {
                        sans: ['"Plus Jakarta Sans"', 'sans-serif'],
                        heading: ['"Poppins"', 'sans-serif'],
                    }
                }
            }
        }
    </script>
    @include('partials.typography')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        @keyframes kenBurns {
            0% { transform: scale(1) translate3d(0, 0, 0); }
            100% { transform: scale(1.08) translate3d(-1.2%, -0.8%, 0); }
        }
        @keyframes fadeUp {
            0% { opacity: 0; transform: translateY(14px); }
            100% { opacity: 1; transform: translateY(0); }
        }
        .ken-burns-slide {
            animation: kenBurns 18s ease-in-out infinite alternate;
        }
        .animate-fade-up {
            animation: fadeUp 0.6s cubic-bezier(0.16, 1, 0.3, 1) forwards;
        }
    </style>
</head>
<body class="min-h-screen bg-[#1F140D] text-gray-800 antialiased relative flex flex-col overflow-x-hidden">

    <!-- FULL-BLEED BACKGROUND: KEN BURNS FALLBACK + AMBIENT VIDEO -->
    <div id="roleSlideshow" class="fixed inset-0 z-0 overflow-hidden">
        <img src="https://images.unsplash.com/photo-1618221195710-dd6b41faaea6?q=80&w=1600&auto=format&fit=crop"
             alt="DECOR Interior 1"
             class="ken-burns-slide absolute inset-0 w-full h-full object-cover opacity-100 transition-opacity duration-1000">
        <img src="https://images.unsplash.com/photo-1600210492486-724fe5c67fb0?q=80&w=1600&auto=format&fit=crop"
             alt="DECOR Interior 2"
             class="ken-burns-slide absolute inset-0 w-full h-full object-cover opacity-0 transition-opacity duration-1000">
        <img src="https://images.unsplash.com/photo-1616486338812-3dadae4b4ace?q=80&w=1600&auto=format&fit=crop"
             alt="DECOR Interior 3"
             class="ken-burns-slide absolute inset-0 w-full h-full object-cover opacity-0 transition-opacity duration-1000">
    </div>

    <video id="roleBgVideo" autoplay muted loop playsinline
           poster="https://images.unsplash.com/photo-1618221195710-dd6b41faaea6?q=80&w=1600&auto=format&fit=crop"
           class="fixed inset-0 z-0 w-full h-full object-cover opacity-0 transition-opacity duration-700"
           oncanplay="this.classList.remove('opacity-0'); this.classList.add('opacity-100');"
           onerror="this.remove();">
        <source src="{{ asset('videos/decor-showcase.mp4') }}" type="video/mp4">
    </video>

    <div class="fixed inset-0 z-0 bg-gradient-to-br from-[#1A110B]/80 via-[#261810]/70 to-[#1A110B]/85"></div>

    <!-- CONTENT OVER FULL-BLEED VIDEO -->
    <div class="relative z-10 w-full min-h-screen flex flex-col justify-between px-6 sm:px-10 lg:px-14 py-6">

        <!-- TOP BAR (PINNED AT TOP) -->
        <header class="w-full max-w-6xl mx-auto flex items-center justify-between gap-4 text-white">
            <a href="{{ url('/') }}" class="inline-flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-primary text-white flex items-center justify-center shadow-lg shadow-primary/30">
                    <i class="fa-solid fa-couch text-sm"></i>
                </div>
                <div>
                    <span class="font-heading text-lg font-bold tracking-[0.22em] text-white block leading-none">DECOR</span>
                    <span class="text-[10px] text-white/70 tracking-widest uppercase">Interior Ecosystem</span>
                </div>
            </a>

            <div class="flex items-center gap-2.5 text-xs">
                <span class="text-white/75 hidden sm:inline">Already have an account?</span>
                <a href="{{ route('login') }}" class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl bg-white/15 hover:bg-white/25 backdrop-blur-md border border-white/20 text-white font-semibold transition-all">
                    <span>Sign In</span>
                    <i class="fa-solid fa-arrow-right text-[10px]"></i>
                </a>
            </div>
        </header>

        <!-- CENTERED MAIN: HEADING + 3 FLOATING ROLE CARDS -->
        <main class="w-full max-w-5xl mx-auto my-8 space-y-8 animate-fade-up">
            <div class="text-center space-y-2 max-w-xl mx-auto text-white">
                <span class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-primary/25 border border-primary/40 text-[11px] font-semibold text-amber-200">
                    <span class="w-1.5 h-1.5 rounded-full bg-amber-300 animate-pulse"></span>
                    <span>Join DECOR</span>
                </span>
                <h1 class="text-3xl sm:text-4xl font-bold text-white tracking-tight">Choose Your Role</h1>
                <p class="text-xs sm:text-sm text-white/75">Pick the account type that best fits your needs.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
                <!-- CUSTOMER -->
                <div class="group bg-white/95 backdrop-blur-md hover:bg-white rounded-2xl border border-white/40 hover:border-primary/50 shadow-[0_20px_50px_rgba(0,0,0,0.3)] p-7 flex flex-col items-center text-center transition-all duration-300 hover:-translate-y-1">
                    <div class="w-12 h-12 rounded-xl bg-primary/10 text-primary group-hover:bg-primary group-hover:text-white flex items-center justify-center mb-4 transition-colors">
                        <i class="fa-solid fa-house-chimney text-lg"></i>
                    </div>
                    <h2 class="text-base font-bold text-gray-900 tracking-tight mb-2">Customer</h2>
                    <p class="text-xs text-gray-500 leading-relaxed mb-6 flex-1">
                        Shop curated furniture, design your space in Design Lab, and get professional consultation.
                    </p>
                    <a href="{{ route('register.role', 'customer') }}" class="w-full h-10 bg-primary hover:bg-primary-dark text-white rounded-xl font-semibold text-xs inline-flex items-center justify-center gap-2 shadow-sm transition-all">
                        <span>Register as Customer</span>
                        <i class="fa-solid fa-arrow-right text-[10px]"></i>
                    </a>
                </div>

                <!-- SELLER -->
                <div class="group bg-white/95 backdrop-blur-md hover:bg-white rounded-2xl border border-white/40 hover:border-primary/50 shadow-[0_20px_50px_rgba(0,0,0,0.3)] p-7 flex flex-col items-center text-center transition-all duration-300 hover:-translate-y-1">
                    <div class="w-12 h-12 rounded-xl bg-primary/10 text-primary group-hover:bg-primary group-hover:text-white flex items-center justify-center mb-4 transition-colors">
                        <i class="fa-solid fa-store text-lg"></i>
                    </div>
                    <h2 class="text-base font-bold text-gray-900 tracking-tight mb-2">Seller</h2>
                    <p class="text-xs text-gray-500 leading-relaxed mb-6 flex-1">
                        Manage your furniture catalog, track orders, and expand your store's reach.
                    </p>
                    <a href="{{ route('register.role', 'seller') }}" class="w-full h-10 bg-primary hover:bg-primary-dark text-white rounded-xl font-semibold text-xs inline-flex items-center justify-center gap-2 shadow-sm transition-all">
                        <span>Register as Seller</span>
                        <i class="fa-solid fa-arrow-right text-[10px]"></i>
                    </a>
                </div>

                <!-- DESIGNER -->
                <div class="group bg-white/95 backdrop-blur-md hover:bg-white rounded-2xl border border-white/40 hover:border-primary/50 shadow-[0_20px_50px_rgba(0,0,0,0.3)] p-7 flex flex-col items-center text-center transition-all duration-300 hover:-translate-y-1">
                    <div class="w-12 h-12 rounded-xl bg-primary/10 text-primary group-hover:bg-primary group-hover:text-white flex items-center justify-center mb-4 transition-colors">
                        <i class="fa-solid fa-compass-drafting text-lg"></i>
                    </div>
                    <h2 class="text-base font-bold text-gray-900 tracking-tight mb-2">Designer</h2>
                    <p class="text-xs text-gray-500 leading-relaxed mb-6 flex-1">
                        Showcase 360° portfolios, receive consultations, and manage your client projects.
                    </p>
                    <a href="{{ route('register.role', 'designer') }}" class="w-full h-10 bg-primary hover:bg-primary-dark text-white rounded-xl font-semibold text-xs inline-flex items-center justify-center gap-2 shadow-sm transition-all">
                        <span>Register as Designer</span>
                        <i class="fa-solid fa-arrow-right text-[10px]"></i>
                    </a>
                </div>
            </div>
        </main>

        <!-- BOTTOM BAR (PINNED AT BOTTOM) -->
        <footer class="w-full max-w-6xl mx-auto flex flex-col sm:flex-row items-center justify-between gap-2 text-[11px] text-white/60">
            <span>&copy; 2026 DECOR Marketplace. All rights reserved.</span>
            <a href="{{ route('customer.help-center') }}" class="hover:text-white font-medium transition-colors">Need help? Contact Help Center</a>
        </footer>
    </div>

    <script>
        (function() {
            const slides = document.querySelectorAll('#roleSlideshow img');
            if (slides.length > 1) {
                let idx = 0;
                setInterval(() => {
                    slides[idx].classList.remove('opacity-100');
                    slides[idx].classList.add('opacity-0');
                    idx = (idx + 1) % slides.length;
                    slides[idx].classList.remove('opacity-0');
                    slides[idx].classList.add('opacity-100');
                }, 6000);
            }
        })();
    </script>
</body>
</html>