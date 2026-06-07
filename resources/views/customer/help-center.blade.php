<?php $site_name = "DECOR"; ?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pusat Bantuan — <?= $site_name ?></title>
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
        body { font-family: 'Plus Jakarta Sans', sans-serif; background-color: #fff; }
    </style>
</head>
<body class="text-gray-800">

 @include('customer.partials.navbar')

    <header class="py-10 px-6">
        <div class="max-w-5xl mx-auto text-center">
            <h1 class="text-[10px] font-black uppercase tracking-[0.5em] text-primary mb-4">Support System</h1>
            <h2 class="text-4xl md:text-5xl font-bold tracking-tighter italic">Pusat Bantuan Pelanggan</h2>
        </div>
    </header>

    <main class="max-w-5xl mx-auto px-6 pb-24">
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-20">
            <div class="p-8 border border-gray-100 rounded-[2.5rem] hover:border-primary transition-all cursor-pointer bg-gray-50/30 group text-center">
                <div class="w-14 h-14 bg-white rounded-2xl shadow-sm flex items-center justify-center text-primary mx-auto mb-6 group-hover:bg-primary group-hover:text-white transition-all">
                    <i class="fa-solid fa-box-open text-xl"></i>
                </div>
                <h4 class="font-bold mb-2">Masalah Pesanan</h4>
                <p class="text-[11px] text-gray-400 leading-relaxed">Status pengiriman, barang rusak, atau pembatalan order.</p>
            </div>

            <div class="p-8 border border-gray-100 rounded-[2.5rem] hover:border-primary transition-all cursor-pointer bg-gray-50/30 group text-center">
                <div class="w-14 h-14 bg-white rounded-2xl shadow-sm flex items-center justify-center text-primary mx-auto mb-6 group-hover:bg-primary group-hover:text-white transition-all">
                    <i class="fa-solid fa-wand-magic-sparkles text-xl"></i>
                </div>
                <h4 class="font-bold mb-2">Desainer & AI Lab</h4>
                <p class="text-[11px] text-gray-400 leading-relaxed">Kendala saat konsultasi atau penggunaan fitur AI Moodboard.</p>
            </div>

            <div class="p-8 border border-gray-100 rounded-[2.5rem] hover:border-primary transition-all cursor-pointer bg-gray-50/30 group text-center">
                <div class="w-14 h-14 bg-white rounded-2xl shadow-sm flex items-center justify-center text-primary mx-auto mb-6 group-hover:bg-primary group-hover:text-white transition-all">
                    <i class="fa-solid fa-credit-card text-xl"></i>
                </div>
                <h4 class="font-bold mb-2">Pembayaran</h4>
                <p class="text-[11px] text-gray-400 leading-relaxed">Konfirmasi transaksi, refund dana, dan metode pembayaran.</p>
            </div>
        </div>

        <div class="bg-gray-50 rounded-[3rem] p-8 md:p-16 border border-gray-100">
            <div class="max-w-2xl mx-auto">
                <div class="text-center mb-12">
                    <h3 class="text-3xl font-bold italic mb-3 text-gray-900">Masih butuh bantuan?</h3>
                    <p class="text-sm text-gray-400">Silakan isi formulir di bawah ini, tim kurasi kami akan merespon dalam 1x24 jam.</p>
                </div>

                @if(session('success'))
                    <div class="mb-6 bg-green-50 text-green-700 border border-green-200 p-4 rounded-xl text-sm font-bold text-center">
                        {{ session('success') }}
                    </div>
                @endif
                <form action="{{ route('customer.support.submit') }}" method="POST" class="space-y-6">
                    @csrf
                    @auth
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-2">
                            <label class="text-[10px] font-black uppercase tracking-widest text-gray-400 ml-2">Nama Lengkap</label>
                            <input type="text" value="{{ Auth::user()->full_name }}" readonly class="w-full bg-gray-50 border border-gray-100 p-4 rounded-2xl outline-none text-gray-500 cursor-not-allowed text-sm">
                        </div>
                        <div class="space-y-2">
                            <label class="text-[10px] font-black uppercase tracking-widest text-gray-400 ml-2">Email Aktif</label>
                            <input type="email" value="{{ Auth::user()->email }}" readonly class="w-full bg-gray-50 border border-gray-100 p-4 rounded-2xl outline-none text-gray-500 cursor-not-allowed text-sm">
                        </div>
                    </div>
                    @else
                    <div class="bg-amber-50 text-amber-700 border border-amber-200 p-4 rounded-xl text-sm text-center mb-6">
                        Silakan <a href="{{ route('login') }}" class="font-bold underline hover:text-amber-800">Login</a> terlebih dahulu untuk mengirim pesan bantuan.
                    </div>
                    @endauth

                    <div class="space-y-2">
                        <label class="text-[10px] font-black uppercase tracking-widest text-gray-400 ml-2">Subjek Masalah</label>
                        <select name="subject" required class="w-full bg-white border border-gray-100 p-4 rounded-2xl outline-none focus:border-primary transition-all text-sm appearance-none @error('subject') border-red-500 @enderror">
                            <option value="Keluhan Pembayaran">Keluhan Pembayaran</option>
                            <option value="Masalah Pesanan">Masalah Pesanan</option>
                            <option value="Kendala Fitur Konsultasi & AI">Kendala Fitur Konsultasi & AI</option>
                            <option value="Masalah Teknis Lainnya">Masalah Teknis Lainnya</option>
                        </select>
                        @error('subject') <span class="text-xs text-red-500 ml-2">{{ $message }}</span> @enderror
                    </div>

                    <div class="space-y-2">
                        <label class="text-[10px] font-black uppercase tracking-widest text-gray-400 ml-2">Detail Pesan</label>
                        <textarea name="message" rows="5" required placeholder="Ceritakan kendala Anda secara detail..." class="w-full bg-white border border-gray-100 p-4 rounded-2xl outline-none focus:border-primary transition-all text-sm resize-none @error('message') border-red-500 @enderror">{{ old('message') }}</textarea>
                        @error('message') <span class="text-xs text-red-500 ml-2">{{ $message }}</span> @enderror
                    </div>

                    <button type="submit" @guest disabled @endguest class="w-full {{ auth()->check() ? 'bg-primary hover:scale-[1.01] active:scale-[0.99] shadow-xl shadow-primary/20' : 'bg-gray-300 cursor-not-allowed' }} text-white py-5 rounded-2xl text-[11px] font-black uppercase tracking-[0.2em] transition-all">
                        Kirim Pesan Sekarang
                    </button>
                </form>

                @auth
                @if(isset($supports) && $supports->count() > 0)
                <div class="mt-16 pt-12 border-t border-gray-200">
                    <h3 class="text-2xl font-bold italic mb-8 text-gray-900 text-center">Riwayat Bantuan Anda</h3>
                    <div class="space-y-4">
                        @foreach($supports as $support)
                        <div class="bg-white border border-gray-100 p-6 rounded-2xl shadow-sm hover:border-primary transition-all">
                            <div class="flex justify-between items-start mb-4">
                                <div>
                                    <span class="text-[9px] font-black uppercase tracking-widest px-3 py-1 rounded-full {{ $support->status == 'resolved' ? 'bg-green-50 text-green-600' : ($support->status == 'replied' ? 'bg-blue-50 text-blue-600' : 'bg-amber-50 text-amber-600') }}">
                                        {{ $support->status }}
                                    </span>
                                    <p class="text-[10px] text-gray-400 mt-2 font-medium">{{ $support->created_at->format('d M Y, H:i') }}</p>
                                </div>
                                <h4 class="font-bold text-sm text-gray-900 text-right max-w-[50%]">{{ $support->subject }}</h4>
                            </div>
                            <p class="text-xs text-gray-600 leading-relaxed bg-gray-50 p-4 rounded-xl border border-gray-100">{{ $support->message }}</p>
                            
                            @if($support->admin_reply)
                            <div class="mt-4 pl-4 border-l-2 border-primary">
                                <p class="text-[10px] font-black uppercase tracking-widest text-primary mb-1">Balasan Admin:</p>
                                <p class="text-xs text-gray-700 leading-relaxed">{{ $support->admin_reply }}</p>
                            </div>
                            @endif
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif
                @endauth

                <div class="mt-12 flex flex-col md:flex-row items-center justify-center gap-8 border-t border-gray-200 pt-10">
                    <div class="text-center">
                        <p class="text-[9px] font-black text-gray-300 uppercase tracking-widest mb-1">Email Support</p>
                        <p class="text-xs font-bold text-primary">care@decor.com</p>
                    </div>
                    <div class="hidden md:block w-px h-8 bg-gray-200"></div>
                    <div class="text-center">
                        <p class="text-[9px] font-black text-gray-300 uppercase tracking-widest mb-1">Working Hours</p>
                        <p class="text-xs font-bold text-primary">Mon — Fri, 09:00 - 18:00</p>
                    </div>
                </div>
            </div>
        </div>
    </main>

  <footer class="bg-primary text-white py-12 px-6">
        <div class="max-w-6xl mx-auto"> 
            <div class="flex flex-col md:flex-row justify-between items-start border-b border-white/20 pb-10 gap-10 text-left">
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
                        <a href="#" class="hover:text-white/70 transition-colors">Terms of Service</a>
                        <a href="#" class="hover:text-white/70 transition-colors">Privacy Policy</a>
                    </div>
                    <div class="flex flex-col space-y-3">
                        <a href="#" class="hover:text-white/70 transition-colors">Help Center</a>
                        <a href="#" class="hover:text-white/70 transition-colors">FAQ</a>
                    </div>
                </div>

                <div class="flex flex-col items-end space-y-6">
                    <span class="text-[10px] font-bold tracking-widest uppercase text-white/40">Portal Version 1.0</span>
                    <div class="flex space-x-4">
                        <a href="#" class="w-10 h-10 border border-white/40 flex items-center justify-center rounded-full hover:bg-white hover:text-primary transition-all duration-300">
                            <i class="fa-brands fa-instagram"></i>
                        </a>
                        <a href="#" class="w-10 h-10 border border-white/40 flex items-center justify-center rounded-full hover:bg-white hover:text-primary transition-all duration-300">
                            <i class="fa-regular fa-paper-plane"></i>
                        </a>
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