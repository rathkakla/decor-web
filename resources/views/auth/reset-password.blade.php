<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password - DECOR</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&display=swap');
        body { background-color: #F3E9E0; font-family: 'Plus Jakarta Sans', sans-serif; }
    </style>
</head>
<body class="min-h-screen flex items-center justify-center p-6">

    <div class="bg-white rounded-[32px] shadow-xl p-10 w-full max-w-md text-center">
        <!-- Icon Lock -->
        <div class="w-16 h-16 bg-[#FDF0E5] rounded-xl flex items-center justify-center mx-auto mb-6">
            <i class="fa-solid fa-lock-open text-[#B5733A] text-2xl"></i>
        </div>

        <h1 class="text-2xl font-black text-gray-800 mb-2 tracking-tight">Set New Password</h1>
        <p class="text-sm text-gray-500 mb-8 leading-relaxed px-4">
            Almost done! Please enter your new password to regain access to your account.
        </p>

        <form method="POST" action="{{ route('password.store') }}" class="text-left space-y-5">
            @csrf

            <!-- Password Reset Token (Wajib dari Breeze) -->
            <input type="hidden" name="token" value="{{ $request->route('token') }}">

            <!-- Email Address (Biasanya otomatis terisi dari link email) -->
            <div>
                <label for="email" class="text-[10px] font-black uppercase tracking-widest text-gray-400 mb-2 block">Email Address</label>
                <input id="email" type="email" name="email" value="{{ old('email', $request->email) }}" required readonly
                    class="w-full bg-gray-50 border border-gray-100 rounded-xl py-4 px-6 text-sm text-gray-400 outline-none cursor-not-allowed">
                @error('email')
                    <p class="text-[10px] text-red-500 font-bold mt-1 uppercase">{{ $message }}</p>
                @enderror
            </div>

            <!-- New Password -->
            <div>
                <label for="password" class="text-[10px] font-black uppercase tracking-widest text-gray-400 mb-2 block">New Password</label>
                <input id="password" type="password" name="password" required autofocus autocomplete="new-password"
                    class="w-full bg-[#F8F6F4] border border-gray-100 rounded-xl py-4 px-6 text-sm outline-none focus:border-[#B5733A] transition-all">
                @error('password')
                    <p class="text-[10px] text-red-500 font-bold mt-1 uppercase">{{ $message }}</p>
                @enderror
            </div>

            <!-- Confirm Password -->
            <div>
                <label for="password_confirmation" class="text-[10px] font-black uppercase tracking-widest text-gray-400 mb-2 block">Confirm Password</label>
                <input id="password_confirmation" type="password" name="password_confirmation" required autocomplete="new-password"
                    class="w-full bg-[#F8F6F4] border border-gray-100 rounded-xl py-4 px-6 text-sm outline-none focus:border-[#B5733A] transition-all">
            </div>

            <button type="submit" 
                class="w-full bg-[#8D5426] text-white py-4 rounded-xl text-sm font-bold shadow-lg shadow-amber-900/20 hover:opacity-90 transition-all">
                Reset Password
            </button>
        </form>
    </div>

</body>
</html>