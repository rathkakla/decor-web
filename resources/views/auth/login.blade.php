<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Decor - Login</title>
    
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#B5733A',
                        secondary: '#E3DCD6',
                        lightbg: '#F8F6F4'
                    },
                    fontFamily: {
                        sans: ['Inter', 'ui-sans-serif', 'system-ui', '-apple-system', 'BlinkMacSystemFont', 'Segoe UI', 'Roboto', 'Helvetica Neue', 'Arial', 'sans-serif'],
                    }
                }
            }
        }
    </script>
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        body {
            background: radial-gradient(circle at center, #ffffff 0%, var(--tw-color-lightbg) 100%);
        }
    </style>
</head>
<body class="min-h-screen flex flex-col text-gray-800 antialiased">

    <header class="w-full bg-white px-8 py-4 flex justify-between items-center shadow-sm relative z-10">
        <div class="text-primary font-bold text-xl tracking-widest uppercase">
            Decor
        </div>
        <div class="text-gray-400">
            <i class="fa-solid fa-shield-halved"></i>
        </div>
    </header>

    <main class="flex-grow flex items-center justify-center p-4 bg-[#D7C3B5]">
        <div class="bg-white rounded-lg shadow-[0_10px_40px_rgba(0,0,0,0.05)] p-10 w-full max-w-md">
            
            <div class="text-center mb-8">
                <h1 class="text-3xl font-bold text-primary mb-2">Login</h1>
                <p class="text-[10px] text-gray-500 uppercase tracking-widest font-semibold">Secure Identity Verification</p>
            </div>

            @if ($errors->any())
                <div class="mb-6 p-4 bg-red-50 border-l-4 border-red-500 text-red-700 text-[11px] font-bold rounded tracking-wide">
                    <ul class="list-disc pl-4">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}">
                @csrf 
                <div class="mb-5">
                    <label for="email" class="block text-[11px] font-bold text-gray-600 mb-2 uppercase tracking-wide">Email</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <span class="text-gray-400 text-sm font-semibold">@</span>
                        </div>
                        <input type="email" id="email" name="email" value="{{ old('email') }}"
                            class="bg-secondary/40 w-full pl-10 pr-4 py-3 rounded-md border-none focus:ring-2 focus:ring-primary/50 text-sm transition-all" 
                            placeholder="nama@email.com" required autofocus>
                    </div>
                </div>

                <div class="mb-8">
                    <label for="password" class="block text-[11px] font-bold text-gray-600 mb-2 uppercase tracking-wide">Security Key</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fa-solid fa-key text-gray-400 text-sm"></i>
                        </div>
                        <input type="password" id="password" name="password" 
                            class="bg-secondary/40 w-full pl-10 pr-10 py-3 rounded-md border-none focus:ring-2 focus:ring-primary/50 text-sm tracking-[0.2em] transition-all" 
                            placeholder="••••••••••" required>
                        <div class="absolute inset-y-0 right-0 pr-3 flex items-center cursor-pointer text-gray-400 hover:text-primary transition-colors" onclick="togglePassword()">
                            <i class="fa-solid fa-eye-slash" id="togglePasswordIcon"></i>
                        </div>
                    </div>
                </div>

                <button type="submit" class="w-full bg-primary hover:bg-[#9a6130] text-white font-semibold py-3.5 rounded-md transition duration-300 flex justify-center items-center gap-2 shadow-md hover:shadow-lg">
                    Secure Login 
                    <i class="fa-solid fa-shield-halved text-sm"></i>
                </button>
            </form>

            <div class="text-center mt-8">
                <a href="{{ route('password.request') }}" class="text-[11px] text-gray-500 hover:text-primary uppercase tracking-wider underline font-semibold transition-colors border-gray-400 decoration-gray-400 hover:decoration-primary underline-offset-4">
                    Forgot Password
                </a>
            </div>

            <!-- Demo Access Section -->
            <div class="mt-10 p-6 bg-gray-50/50 rounded-xl border border-gray-100 shadow-inner">
                <div class="flex items-center justify-center gap-2 mb-4">
                    <div class="h-[1px] w-8 bg-gray-200"></div>
                    <h3 class="text-[10px] font-bold text-gray-400 uppercase tracking-[0.2em]">Demo Credentials</h3>
                    <div class="h-[1px] w-8 bg-gray-200"></div>
                </div>
                
                <div class="space-y-3">
                    <div class="flex flex-col gap-1">
                        <span class="text-[9px] font-black text-primary/70 uppercase tracking-wider ml-1">Administrator</span>
                        <div class="flex items-center justify-between bg-white px-3 py-2 rounded-lg border border-gray-100 shadow-sm group hover:border-primary/30 transition-all cursor-pointer">
                            <code class="text-[11px] font-mono text-gray-600">admin@decor.com</code>
                            <span class="text-[10px] font-bold text-gray-300 group-hover:text-primary transition-colors">admin123</span>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 gap-3 pt-2 border-t border-gray-100">
                        <div class="flex flex-col gap-1">
                            <span class="text-[9px] font-black text-gray-400 uppercase tracking-wider ml-1">Customer Access</span>
                            <div class="flex items-center justify-between bg-white/60 px-3 py-2 rounded-lg border border-gray-100 shadow-sm">
                                <code class="text-[11px] font-mono text-gray-500">customer@decor.com</code>
                                <span class="text-[10px] font-bold text-gray-300">password</span>
                            </div>
                        </div>

                        <div class="flex flex-col gap-1">
                            <span class="text-[9px] font-black text-gray-400 uppercase tracking-wider ml-1">Seller Partner</span>
                            <div class="flex items-center justify-between bg-white/60 px-3 py-2 rounded-lg border border-gray-100 shadow-sm">
                                <code class="text-[11px] font-mono text-gray-500">seller@decor.com</code>
                                <span class="text-[10px] font-bold text-gray-300">password</span>
                            </div>
                        </div>

                        <div class="flex flex-col gap-1">
                            <span class="text-[9px] font-black text-gray-400 uppercase tracking-wider ml-1">Interior Designer</span>
                            <div class="flex items-center justify-between bg-white/60 px-3 py-2 rounded-lg border border-gray-100 shadow-sm">
                                <code class="text-[11px] font-mono text-gray-500">designer@decor.com</code>
                                <span class="text-[10px] font-bold text-gray-300">password</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="text-center mt-10 border-t border-gray-100 pt-8">
                <p class="text-[10px] text-gray-400 uppercase tracking-widest font-bold mb-3">New to Decor?</p>
                <a href="{{ route('role.selection') }}" class="text-[10px] text-primary hover:scale-105 inline-block uppercase tracking-[0.2em] font-black transition-all">
                    Register Account <i class="fa-solid fa-arrow-right ml-1"></i>
                </a>
            </div>
        </div>
    </main>

    <footer class="w-full px-8 py-6 flex flex-col md:flex-row justify-between items-center text-[10px] text-gray-400 uppercase tracking-widest font-medium relative z-10">
        <div class="mb-4 md:mb-0">
            © 2024 Decor Admin Portal. Secure System Entry.
        </div>
        <div class="flex gap-6">
            <a href="#" class="hover:text-gray-600 transition-colors">Privacy Policy</a>
            <a href="#" class="hover:text-gray-600 transition-colors">System Status</a>
            <a href="#" class="hover:text-gray-600 transition-colors">Contact Support</a>
        </div>
    </footer>

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
    </script>
</body>
</html>