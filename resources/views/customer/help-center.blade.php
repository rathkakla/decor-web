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

                <form class="space-y-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-2">
                            <label class="text-[10px] font-black uppercase tracking-widest text-gray-400 ml-2">Nama Lengkap</label>
                            <input type="text" placeholder="Keisya Azahra" class="w-full bg-white border border-gray-100 p-4 rounded-2xl outline-none focus:border-primary transition-all text-sm">
                        </div>
                        <div class="space-y-2">
                            <label class="text-[10px] font-black uppercase tracking-widest text-gray-400 ml-2">Email Aktif</label>
                            <input type="email" placeholder="example@mail.com" class="w-full bg-white border border-gray-100 p-4 rounded-2xl outline-none focus:border-primary transition-all text-sm">
                        </div>
                    </div>

                    <div class="space-y-2">
                        <label class="text-[10px] font-black uppercase tracking-widest text-gray-400 ml-2">Subjek Masalah</label>
                        <select class="w-full bg-white border border-gray-100 p-4 rounded-2xl outline-none focus:border-primary transition-all text-sm appearance-none">
                            <option>Keluhan Pembayaran</option>
                            <option>Kendala Fitur AI</option>
                            <option>Pertanyaan Kemitraan</option>
                            <option>Masalah Teknis Lainnya</option>
                        </select>
                    </div>

                    <div class="space-y-2">
                        <label class="text-[10px] font-black uppercase tracking-widest text-gray-400 ml-2">Detail Pesan</label>
                        <textarea rows="5" placeholder="Ceritakan kendala Anda secara detail..." class="w-full bg-white border border-gray-100 p-4 rounded-2xl outline-none focus:border-primary transition-all text-sm resize-none"></textarea>
                    </div>

                    <div class="space-y-2">
                        <label class="text-[10px] font-black uppercase tracking-widest text-gray-400 ml-2">Lampiran File (Opsional)</label>
                        <div class="relative group">
                            <input type="file" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10">
                            <div class="w-full bg-white border border-dashed border-gray-200 p-6 rounded-2xl flex flex-col items-center justify-center group-hover:border-primary transition-all">
                                <i class="fa-solid fa-cloud-arrow-up text-gray-300 text-2xl mb-2 group-hover:text-primary transition-all"></i>
                                <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest group-hover:text-primary transition-all">Klik untuk upload foto atau dokumen</p>
                                <p class="text-[9px] text-gray-300 mt-1">PNG, JPG, PDF (Max 5MB)</p>
                            </div>
                        </div>
                    </div>

                    <button type="submit" class="w-full bg-primary text-white py-5 rounded-2xl text-[11px] font-black uppercase tracking-[0.2em] shadow-xl shadow-primary/20 hover:scale-[1.01] active:scale-[0.99] transition-all">
                        Kirim Pesan Sekarang
                    </button>
                </form>

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