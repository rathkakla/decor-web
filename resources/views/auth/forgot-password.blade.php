<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password - DECOR</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&display=swap');
        body { background-color: #F3E9E0; font-family: 'Plus Jakarta Sans', sans-serif; }
        .bg-card { background-color: #FFFFFF; }
        .btn-decor { background-color: #8D5426; }
    </style>
</head>

<body class="min-h-screen flex items-center justify-center p-6">
    

    <div class="bg-card rounded-[32px] shadow-xl p-10 w-full max-w-md text-center">
        <!-- Icon sesuai gambar -->
        <div class="w-16 h-16 bg-[#FDF0E5] rounded-xl flex items-center justify-center mx-auto mb-6">
            <i class="fa-solid fa-rotate-left text-[#B5733A] text-2xl"></i>
        </div>

        <h1 class="text-2xl font-black text-gray-800 mb-2 tracking-tight">Forgot Password?</h1>
        <p class="text-sm text-gray-500 mb-8 leading-relaxed px-4">
            Enter your email address and we will send you a link to reset your password.
        </p>

        <!-- Session Status (Pesan sukses dari Breeze) -->
        @if (session('status'))
            <div class="bg-green-50 text-green-600 p-4 rounded-xl text-xs font-bold mb-6 border border-green-100">
                {{ session('status') }}
            </div>
        @endif

        <form method="POST" action="{{ route('password.email') }}" class="text-left space-y-6">
            @csrf
            <div>
                <label for="email" class="text-[10px] font-black uppercase tracking-widest text-gray-400 mb-2 block">Email Address</label>
                <input id="email" type="email" name="email" value="{{ old('email') }}" placeholder="e.g. curator@decor.com" required autofocus
                    class="w-full bg-[#F8F6F4] border border-gray-100 rounded-xl py-4 px-6 text-sm outline-none focus:border-[#B5733A] transition-all @error('email') border-red-400 @enderror">
                
                @error('email')
                    <p class="text-[10px] text-red-500 font-bold mt-1 uppercase">{{ $message }}</p>
                @enderror
            </div>

            <button type="submit" 
                class="w-full btn-decor text-white py-4 rounded-xl text-sm font-bold shadow-lg shadow-amber-900/20 hover:opacity-90 transition-all flex items-center justify-center gap-2">
                Send Reset Link <i class="fa-solid fa-arrow-right text-xs"></i>
            </button>
        </form>

        <div class="mt-8">
            <a href="{{ route('login') }}" class="text-xs font-bold text-gray-400 hover:text-gray-800 transition-colors flex items-center justify-center gap-2">
                <i class="fa-solid fa-arrow-left text-[10px]"></i> Back to Login
            </a>
        </div>
    </div>

</body>
</html>