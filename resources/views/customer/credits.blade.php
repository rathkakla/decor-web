<?php $site_name = "DECOR"; ?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Credits & Attributions — {{ $site_name }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: { primary: '#B5733A', secondary: '#E3DCD6' }
                }
            }
        }
    </script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;600;700;800&display=swap');
        body { font-family: 'Plus Jakarta Sans', sans-serif; background-color: #F8F6F4; }
    </style>
</head>
<body class="text-gray-800 flex flex-col min-h-screen">

    @include('customer.partials.navbar')

    <main class="flex-grow pt-24 pb-32">
        <div class="max-w-4xl mx-auto px-6">
            <div class="text-center mb-16">
                <span class="text-primary text-[10px] font-black uppercase tracking-[0.3em]">Legal Information</span>
                <h1 class="text-4xl md:text-5xl font-bold mt-4 mb-4 tracking-tight">Credits & Attributions</h1>
                <p class="text-gray-500 max-w-xl mx-auto">Pengakuan hak cipta untuk seluruh aset visual, gambar, dan elemen pihak ketiga yang digunakan dalam pembuatan dan demonstrasi website DECOR.</p>
            </div>

            <div class="bg-white rounded-3xl p-10 md:p-16 shadow-[0_8px_30px_rgb(0,0,0,0.04)] border border-gray-100">
                <div class="prose max-w-none text-sm text-gray-600 leading-relaxed space-y-8">
                    
                    <section>
                        <h2 class="text-xl font-bold text-gray-900 mb-3">Tujuan Penggunaan (Disclaimer)</h2>
                        <div class="p-6 bg-gray-50 rounded-2xl border border-gray-100">
                            <p class="mb-0 italic text-gray-500">
                                "Beberapa aset visual, gambar produk, dan foto desainer yang digunakan dalam website ini adalah murni untuk keperluan <strong>demonstrasi, edukasi, dan portofolio desain UI/UX</strong>. Kami tidak mengklaim kepemilikan komersial atas gambar-gambar tersebut."
                            </p>
                        </div>
                    </section>

                    <section>
                        <h2 class="text-xl font-bold text-gray-900 mb-3">Sumber Aset Visual Pihak Ketiga</h2>
                        <p class="mb-4">Website ini secara dominan memanfaatkan foto royalty-free dan aset grafis dari platform berikut:</p>
                        <ul class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
                            <li class="flex items-center space-x-3 p-4 border border-gray-100 rounded-xl hover:border-primary/30 transition">
                                <i class="fa-brands fa-unsplash text-2xl text-gray-800"></i>
                                <div>
                                    <strong class="block text-gray-900">Unsplash</strong>
                                    <span class="text-xs text-gray-400">Placeholder foto furnitur & ruangan</span>
                                </div>
                            </li>
                            <li class="flex items-center space-x-3 p-4 border border-gray-100 rounded-xl hover:border-primary/30 transition">
                                <div class="w-6 h-6 bg-green-500 rounded text-white flex items-center justify-center font-bold text-xs">Px</div>
                                <div>
                                    <strong class="block text-gray-900">Pexels</strong>
                                    <span class="text-xs text-gray-400">Gambar gaya hidup & dekorasi</span>
                                </div>
                            </li>
                            <li class="flex items-center space-x-3 p-4 border border-gray-100 rounded-xl hover:border-primary/30 transition">
                                <div class="w-6 h-6 bg-blue-500 rounded flex items-center justify-center"><i class="fa-solid fa-paintbrush text-white text-xs"></i></div>
                                <div>
                                    <strong class="block text-gray-900">Freepik</strong>
                                    <span class="text-xs text-gray-400">Elemen vektor & ikon</span>
                                </div>
                            </li>
                            <li class="flex items-center space-x-3 p-4 border border-gray-100 rounded-xl hover:border-primary/30 transition">
                                <div class="w-6 h-6 bg-blue-600 rounded text-white flex items-center justify-center font-bold text-xs">Ui</div>
                                <div>
                                    <strong class="block text-gray-900">UI Avatars</strong>
                                    <span class="text-xs text-gray-400">Pembuat foto profil otomatis (API)</span>
                                </div>
                            </li>
                        </ul>
                    </section>

                    <section>
                        <h2 class="text-xl font-bold text-gray-900 mb-3">Ikonografi & Tipografi</h2>
                        <ul class="list-disc pl-5 space-y-2">
                            <li><strong>Font:</strong> Menggunakan keluarga font <a href="https://fonts.google.com/specimen/Plus+Jakarta+Sans" class="text-primary hover:underline" target="_blank">Plus Jakarta Sans</a> dari Google Fonts.</li>
                            <li><strong>Ikon:</strong> Menggunakan pustaka ikon <a href="https://fontawesome.com/" class="text-primary hover:underline" target="_blank">FontAwesome 6</a>.</li>
                        </ul>
                    </section>

                    <hr class="border-gray-100 my-8">

                    <section>
                        <h2 class="text-xl font-bold text-gray-900 mb-3">Takedown Request</h2>
                        <p>
                            Jika Anda adalah pemilik sah hak cipta dari gambar atau aset spesifik yang ada di website ini, dan Anda ingin kami menghapusnya atau mencantumkan credit (tautan) ke portfolio asli Anda, mohon hubungi kami melalui halaman <a href="{{ route('customer.help-center') }}" class="text-primary font-bold hover:underline">Help Center</a> atau email resmi kami. Kami akan segera memproses permintaan Anda dalam 1x24 jam.
                        </p>
                    </section>

                </div>
            </div>
        </div>
    </main>

    <!-- FOOTER -->
    <footer class="bg-primary text-white py-12 px-6 mt-auto">
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
                        <a href="{{ route('customer.credits') }}" class="hover:text-white/70">Image Credits</a>
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
