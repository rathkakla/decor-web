<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DECOR — Sign In</title>
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
        .showcase-card.active {
            background: rgba(181, 115, 58, 0.28);
            border-color: rgba(251, 191, 36, 0.55);
        }
    </style>
</head>
<body class="min-h-screen bg-[#1F140D] text-gray-800 antialiased relative flex items-center justify-center p-4 sm:p-6 lg:p-8 overflow-x-hidden">

    <!-- FULL-BLEED BACKGROUND: KEN BURNS FALLBACK + AMBIENT VIDEO -->
    <div id="loginSlideshow" class="fixed inset-0 z-0 overflow-hidden">
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

    <video id="loginBgVideo" autoplay muted loop playsinline
           poster="https://images.unsplash.com/photo-1618221195710-dd6b41faaea6?q=80&w=1600&auto=format&fit=crop"
           class="fixed inset-0 z-0 w-full h-full object-cover opacity-0 transition-opacity duration-700"
           oncanplay="this.classList.remove('opacity-0'); this.classList.add('opacity-100');"
           onerror="this.remove();">
        <source src="{{ asset('videos/decor-showcase.mp4') }}" type="video/mp4">
    </video>

    <div class="fixed inset-0 z-0 bg-gradient-to-br from-[#1A110B]/80 via-[#261810]/70 to-[#1A110B]/85"></div>

    <!-- UNIFIED FLOATING CARD -->
    <div class="relative z-10 w-full max-w-5xl rounded-3xl overflow-hidden shadow-[0_24px_70px_rgba(0,0,0,0.4)] border border-white/15 grid grid-cols-1 lg:grid-cols-12 animate-fade-up">

        <!-- LEFT SIDE: TRANSPARENT FROSTED SHOWCASE -->
        <aside class="order-2 lg:order-1 lg:col-span-6 bg-[#1A110B]/55 backdrop-blur-md text-white p-6 sm:p-9 lg:p-10 flex flex-col justify-between gap-6 lg:gap-8">
            <div class="flex items-center justify-between">
                <a href="{{ url('/') }}" class="inline-flex items-center gap-3">
                    <div class="w-9 h-9 rounded-xl bg-primary text-white flex items-center justify-center shadow-lg shadow-primary/30">
                        <i class="fa-solid fa-couch text-xs"></i>
                    </div>
                    <div>
                        <span class="font-heading text-lg font-bold tracking-[0.22em] text-white block leading-none">DECOR</span>
                        <span class="text-[10px] text-white/65 tracking-widest uppercase">Interior Ecosystem</span>
                    </div>
                </a>
                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-white/10 border border-white/15 text-[11px] font-medium text-white/85">
                    <i class="fa-solid fa-shield-halved text-amber-300 text-[10px]"></i>
                    <span>Secure</span>
                </span>
            </div>

            <div class="space-y-4">
                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-primary/25 border border-primary/40 text-[11px] font-semibold text-amber-200">
                    <span class="w-1.5 h-1.5 rounded-full bg-amber-300 animate-pulse"></span>
                    <span id="showcaseBadge">For Customers</span>
                </div>
                <h2 id="showcaseTitle" class="text-2xl sm:text-3xl font-bold leading-snug tracking-tight text-white">
                    Discover Furniture &amp; Design Your Dream Space.
                </h2>
                <p id="showcaseDesc" class="text-xs text-white/75 leading-relaxed max-w-sm">
                    Shop curated collections, design your room in Design Lab, and consult directly with designers.
                </p>

                <div class="hidden lg:grid grid-cols-3 gap-2.5 pt-2">
                    <button type="button" onclick="setShowcase(0)" data-showcase-idx="0"
                        class="showcase-card active text-left bg-white/10 hover:bg-white/15 border border-white/15 rounded-xl p-3 transition-all cursor-pointer">
                        <i class="fa-solid fa-bag-shopping text-amber-300 text-xs mb-1.5 block"></i>
                        <p class="text-[11px] font-semibold text-white">Customer</p>
                        <p class="text-[10px] text-white/65 mt-0.5 truncate">Shop &amp; Lab</p>
                    </button>
                    <button type="button" onclick="setShowcase(1)" data-showcase-idx="1"
                        class="showcase-card text-left bg-white/10 hover:bg-white/15 border border-white/15 rounded-xl p-3 transition-all cursor-pointer">
                        <i class="fa-solid fa-store text-amber-300 text-xs mb-1.5 block"></i>
                        <p class="text-[11px] font-semibold text-white">Seller</p>
                        <p class="text-[10px] text-white/65 mt-0.5 truncate">Furniture Catalog</p>
                    </button>
                    <button type="button" onclick="setShowcase(2)" data-showcase-idx="2"
                        class="showcase-card text-left bg-white/10 hover:bg-white/15 border border-white/15 rounded-xl p-3 transition-all cursor-pointer">
                        <i class="fa-solid fa-pen-ruler text-amber-300 text-xs mb-1.5 block"></i>
                        <p class="text-[11px] font-semibold text-white">Designer</p>
                        <p class="text-[10px] text-white/65 mt-0.5 truncate">360° Consultation</p>
                    </button>
                </div>
            </div>

            <div class="hidden lg:flex items-center justify-between text-[11px] text-white/55 pt-4 border-t border-white/10">
                <span>&copy; 2026 DECOR</span>
                <div class="flex items-center gap-1.5" aria-label="Showcase progress">
                    <span class="showcase-dot w-4 h-1.5 rounded-full bg-amber-300 transition-all"></span>
                    <span class="showcase-dot w-1.5 h-1.5 rounded-full bg-white/30 transition-all"></span>
                    <span class="showcase-dot w-1.5 h-1.5 rounded-full bg-white/30 transition-all"></span>
                </div>
            </div>
        </aside>

        <!-- RIGHT SIDE: LOGIN FORM (IN THE SAME FRAME) -->
        <main class="order-1 lg:order-2 lg:col-span-6 bg-white p-7 sm:p-9 lg:p-10 flex flex-col justify-center">
            <div class="space-y-5">
                <div class="flex items-center gap-2.5 lg:hidden mb-1">
                    <div class="w-9 h-9 rounded-xl bg-primary text-white flex items-center justify-center shadow-md shadow-primary/25">
                        <i class="fa-solid fa-couch text-xs"></i>
                    </div>
                    <div>
                        <span class="font-heading text-base font-bold tracking-[0.22em] text-gray-900 block leading-none">DECOR</span>
                        <span class="text-[9px] text-gray-400 tracking-widest uppercase">Interior Ecosystem</span>
                    </div>
                </div>
                <div class="space-y-1">
                    <p class="text-[10px] font-bold text-primary uppercase tracking-widest">Welcome Back</p>
                    <h1 class="text-2xl font-bold text-gray-900 tracking-tight">Sign In to Your Account</h1>
                    <p class="text-xs text-gray-500">Enter your email and password to continue.</p>
                </div>

                @if ($errors->any())
                    <div class="p-3.5 bg-red-50 border border-red-200 text-red-700 text-xs font-medium rounded-xl space-y-1">
                        @foreach ($errors->all() as $error)
                            <div class="flex items-center gap-2">
                                <i class="fa-solid fa-circle-exclamation shrink-0"></i>
                                <span>{{ $error }}</span>
                            </div>
                        @endforeach
                    </div>
                @endif

                <form method="POST" action="{{ route('login') }}" class="space-y-4">
                    @csrf
                    <div class="space-y-1.5">
                        <label for="email" class="block text-xs font-semibold text-gray-700">Email</label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-gray-400">
                                <i class="fa-regular fa-envelope text-xs"></i>
                            </span>
                            <input type="email" id="email" name="email" value="{{ old('email') }}"
                                class="w-full h-11 bg-white border border-gray-200 rounded-xl pl-10 pr-4 text-xs font-medium text-gray-800 placeholder-gray-400 focus:outline-none focus:border-primary focus:ring-2 focus:ring-primary/15 transition-all"
                                placeholder="name@email.com" required autofocus>
                        </div>
                    </div>

                    <div class="space-y-1.5">
                        <div class="flex items-center justify-between">
                            <label for="password" class="block text-xs font-semibold text-gray-700">Password</label>
                            <a href="{{ route('password.request') }}" class="text-[11px] font-semibold text-primary hover:underline">
                                Forgot password?
                            </a>
                        </div>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-gray-400">
                                <i class="fa-solid fa-lock text-xs"></i>
                            </span>
                            <input type="password" id="password" name="password"
                                class="w-full h-11 bg-white border border-gray-200 rounded-xl pl-10 pr-10 text-xs font-medium text-gray-800 placeholder-gray-400 tracking-[0.2em] focus:outline-none focus:border-primary focus:ring-2 focus:ring-primary/15 transition-all"
                                placeholder="••••••••••" required>
                            <button type="button" onclick="togglePassword()" aria-label="Show password"
                                class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-gray-400 hover:text-primary transition-colors">
                                <i class="fa-solid fa-eye-slash text-xs" id="togglePasswordIcon"></i>
                            </button>
                        </div>
                    </div>

                    <button type="submit" id="loginSubmitBtn" class="w-full h-11 bg-primary hover:bg-primary-dark text-white font-semibold text-xs rounded-xl transition-all flex justify-center items-center gap-2 shadow-md shadow-primary/20 cursor-pointer">
                        <span id="loginBtnText">Sign In</span>
                        <i class="fa-solid fa-arrow-right text-[11px]" id="loginBtnIcon"></i>
                    </button>
                </form>

                <!-- Demo Credentials (Compact 2x2 Click-to-Fill) -->
                <div class="pt-4 border-t border-gray-100 space-y-2.5">
                    <div class="flex items-center justify-between">
                        <span class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Quick Demo Access</span>
                        <span id="demoFillStatus" class="text-[10px] text-gray-400 transition-colors">Click to autofill</span>
                    </div>
                    <div class="grid grid-cols-2 gap-2">
                        <button type="button" onclick="fillDemo('admin@decor.com', 'admin123', this)"
                            class="demo-card text-left p-2.5 rounded-xl bg-gray-50 hover:bg-amber-50/70 border border-gray-100 hover:border-primary/30 transition-all group cursor-pointer">
                            <span class="block text-[10px] font-bold text-primary">Administrator</span>
                            <span class="block text-[11px] font-mono text-gray-600 truncate mt-0.5">admin@decor.com</span>
                        </button>
                        <button type="button" onclick="fillDemo('customer@decor.com', 'password', this)"
                            class="demo-card text-left p-2.5 rounded-xl bg-gray-50 hover:bg-amber-50/70 border border-gray-100 hover:border-primary/30 transition-all group cursor-pointer">
                            <span class="block text-[10px] font-bold text-gray-700 group-hover:text-primary">Customer</span>
                            <span class="block text-[11px] font-mono text-gray-600 truncate mt-0.5">customer@decor.com</span>
                        </button>
                        <button type="button" onclick="fillDemo('seller@decor.com', 'password', this)"
                            class="demo-card text-left p-2.5 rounded-xl bg-gray-50 hover:bg-amber-50/70 border border-gray-100 hover:border-primary/30 transition-all group cursor-pointer">
                            <span class="block text-[10px] font-bold text-gray-700 group-hover:text-primary">Seller Partner</span>
                            <span class="block text-[11px] font-mono text-gray-600 truncate mt-0.5">seller@decor.com</span>
                        </button>
                        <button type="button" onclick="fillDemo('designer@decor.com', 'password', this)"
                            class="demo-card text-left p-2.5 rounded-xl bg-gray-50 hover:bg-amber-50/70 border border-gray-100 hover:border-primary/30 transition-all group cursor-pointer">
                            <span class="block text-[10px] font-bold text-gray-700 group-hover:text-primary">Interior Designer</span>
                            <span class="block text-[11px] font-mono text-gray-600 truncate mt-0.5">designer@decor.com</span>
                        </button>
                    </div>
                </div>

                <div class="pt-3 border-t border-gray-100 text-center">
                    <p class="text-xs text-gray-500">
                        Don't have an account?
                        <a href="{{ route('role.selection') }}" class="font-semibold text-primary hover:underline ml-1">
                            Sign Up Now <i class="fa-solid fa-arrow-right text-[10px] ml-0.5"></i>
                        </a>
                    </p>
                </div>
            </div>
        </main>
    </div>

    <script>
        function togglePassword() {
            const passwordInput = document.getElementById('password');
            const toggleIcon = document.getElementById('togglePasswordIcon');

            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                passwordInput.classList.remove('tracking-[0.2em]');
                toggleIcon.classList.remove('fa-eye-slash');
                toggleIcon.classList.add('fa-eye');
            } else {
                passwordInput.type = 'password';
                passwordInput.classList.add('tracking-[0.2em]');
                toggleIcon.classList.remove('fa-eye');
                toggleIcon.classList.add('fa-eye-slash');
            }
        }

        function fillDemo(email, password, cardEl) {
            const emailInput = document.getElementById('email');
            const passwordInput = document.getElementById('password');
            const statusEl = document.getElementById('demoFillStatus');
            if (emailInput && passwordInput) {
                emailInput.value = email;
                passwordInput.value = password;
                emailInput.focus();
            }
            document.querySelectorAll('.demo-card').forEach(el => {
                el.classList.remove('border-primary', 'bg-amber-50/70');
            });
            if (cardEl) {
                cardEl.classList.add('border-primary', 'bg-amber-50/70');
            }
            if (statusEl) {
                statusEl.textContent = 'Account ready to sign in!';
                statusEl.classList.remove('text-gray-400');
                statusEl.classList.add('text-primary', 'font-semibold');
            }
        }

        const showcaseItems = [
            {
                badge: 'For Customers',
                title: 'Discover Furniture & Design Your Dream Space.',
                desc: 'Shop curated collections, design your room in Design Lab, and consult directly with designers.'
            },
            {
                badge: 'For Seller Partners',
                title: 'Manage Your Catalog & Sales in One Place.',
                desc: 'Track orders, set up promo vouchers, and reach customers as well as interior projects.'
            },
            {
                badge: 'For Interior Designers',
                title: 'Showcase 360° Portfolios & Manage Clients.',
                desc: 'Receive consultations, build project budgets, and collaborate directly with clients.'
            }
        ];

        let currentShowcase = 0;
        function setShowcase(idx) {
            currentShowcase = idx;
            const item = showcaseItems[idx];
            const badgeEl = document.getElementById('showcaseBadge');
            const titleEl = document.getElementById('showcaseTitle');
            const descEl = document.getElementById('showcaseDesc');
            if (badgeEl && titleEl && descEl && item) {
                badgeEl.textContent = item.badge;
                titleEl.textContent = item.title;
                descEl.textContent = item.desc;
            }
            document.querySelectorAll('.showcase-card').forEach((card, i) => {
                card.classList.toggle('active', i === idx);
            });
            document.querySelectorAll('.showcase-dot').forEach((dot, i) => {
                dot.classList.toggle('w-4', i === idx);
                dot.classList.toggle('w-1.5', i !== idx);
                dot.classList.toggle('bg-amber-300', i === idx);
                dot.classList.toggle('bg-white/30', i !== idx);
            });
            const slides = document.querySelectorAll('#loginSlideshow img');
            slides.forEach((img, i) => {
                img.classList.toggle('opacity-100', i === idx);
                img.classList.toggle('opacity-0', i !== idx);
            });
        }

        setInterval(() => {
            setShowcase((currentShowcase + 1) % showcaseItems.length);
        }, 5000);

        document.querySelector('form[action="{{ route('login') }}"]')?.addEventListener('submit', function() {
            const btn = document.getElementById('loginSubmitBtn');
            const txt = document.getElementById('loginBtnText');
            const icon = document.getElementById('loginBtnIcon');
            if (btn && txt && icon) {
                btn.disabled = true;
                btn.classList.add('opacity-80', 'cursor-wait');
                txt.textContent = 'Processing...';
                icon.className = 'fa-solid fa-circle-notch fa-spin text-xs';
            }
        });
    </script>
</body>
</html>