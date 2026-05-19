<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Decor - Join the Curator Community</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;600;700;800&family=Playfair+Display:italic,wght@700&display=swap');
        body { font-family: 'Plus Jakarta Sans', sans-serif; overflow-x: hidden; }
        .font-playfair { font-family: 'Playfair Display', serif; }
        .bg-primary { background-color: #B5733A; }
        .text-primary { color: #B5733A; }
        
        .input-underline {
            border: none; border-bottom: 1.5px solid #E5E7EB; border-radius: 0;
            padding: 0.75rem 0; background: transparent; transition: all 0.4s ease;
        }
        .input-underline:focus { outline: none; border-bottom: 1.5px solid #B5733A; }
        
        .step-dot { width: 8px; height: 8px; border-radius: 50%; background: #E5E7EB; transition: all 0.5s ease; }
        .step-dot.active { background: #B5733A; box-shadow: 0 0 10px rgba(181, 115, 58, 0.4); width: 24px; border-radius: 10px; }
        
        .terms-container {
            height: 300px;
            overflow-y: scroll;
            padding: 20px;
            background: #F9FAFB;
            border: 1px solid #E5E7EB;
            border-radius: 8px;
            font-size: 0.875rem;
            line-height: 1.6;
            color: #374151;
        }
        .terms-container::-webkit-scrollbar { width: 6px; }
        .terms-container::-webkit-scrollbar-track { background: #F1F1F1; }
        .terms-container::-webkit-scrollbar-thumb { background: #B5733A; border-radius: 10px; }
    </style>
</head>
<body class="bg-white antialiased">

    <div class="flex flex-col lg:flex-row min-h-screen">
        <div class="hidden lg:flex lg:w-5/12 relative overflow-hidden bg-[#2D3E35]">
            <img src="https://images.unsplash.com/photo-1586023492125-27b2c045efd7?q=80&w=1200" class="absolute inset-0 w-full h-full object-cover opacity-70 scale-110">
            <div class="relative z-10 p-16 flex flex-col justify-between h-full w-full text-white">
                <h1 class="text-2xl font-bold tracking-[0.4em]">DECOR.</h1>
                <div class="max-w-xs">
                    <p class="text-2xl font-playfair leading-relaxed mb-6">"Curating space is an act of intellectual luxury."</p>
                    <div class="w-16 h-[1.5px] bg-white/40"></div>
                </div>
            </div>
        </div>

        <div class="w-full lg:w-7/12 flex flex-col p-8 md:p-16 lg:p-20">
            <div class="max-w-xl mx-auto w-full">
                
                <div class="flex items-center space-x-3 mb-12">
                    <div id="dot-1" class="step-dot active"></div>
                    <div id="dot-2" class="step-dot"></div>
                    <div id="dot-3" class="step-dot"></div>
                    <span id="progress-text" class="text-[9px] font-black uppercase tracking-[0.3em] text-gray-300 ml-4">Progress 33%</span>
                </div>

                <div class="mb-10">
                    <h2 class="text-4xl lg:text-5xl font-light text-gray-800 leading-tight">Join the</h2>
                    <h2 class="text-4xl lg:text-5xl font-black text-gray-800 italic font-playfair">Curator Community</h2>
                </div>

                @if ($errors->any())
                    <div class="mb-6 p-4 bg-red-50 border-l-4 border-red-500 text-red-700 text-xs font-bold rounded">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('register') }}" method="POST" class="space-y-12">
                    @csrf
                    
                    <div id="section-1" class="space-y-8">
>
                        <span class="text-[10px] font-black uppercase text-primary tracking-[0.4em]">01 — Identity</span>
                        <div class="grid gap-6">
                            <div class="grid grid-cols-2 gap-6">
                                <div class="flex flex-col"><label class="text-[9px] font-black tracking-widest text-gray-400 uppercase">Username</label><input type="text" name="username" value="{{ old('username') }}" placeholder="atelier_luxe" required class="input-underline text-sm font-bold"></div>
                                <div class="flex flex-col"><label class="text-[9px] font-black tracking-widest text-gray-400 uppercase">Store Name</label><input type="text" name="store_name" value="{{ old('store_name') }}" placeholder="Atelier de Luxe" required class="input-underline text-sm font-bold"></div>
                            </div>
                            <div class="flex flex-col"><label class="text-[9px] font-black tracking-widest text-gray-400 uppercase">Business Email</label><input type="email" name="email" value="{{ old('email') }}" placeholder="curate@studio.com" required class="input-underline text-sm font-bold"></div>
                            <div class="grid grid-cols-2 gap-6">
                                <div class="flex flex-col relative"><label class="text-[9px] font-black tracking-widest text-gray-400 uppercase">Password</label><input type="password" name="password" placeholder="••••••••••••" required class="input-underline text-sm font-bold"></div>
                                <div class="flex flex-col relative"><label class="text-[9px] font-black tracking-widest text-gray-400 uppercase">Confirm Password</label><input type="password" name="password_confirmation" placeholder="••••••••••••" required class="input-underline text-sm font-bold"></div>
                            </div>
                        </div>
                        <button type="button" onclick="nextStep(2)" class="w-full bg-primary text-white py-5 rounded-sm text-[10px] font-black uppercase tracking-[0.4em] shadow-2xl shadow-primary/30 flex items-center justify-center group">
                            Continue Professional Info <i class="fa-solid fa-arrow-right ml-4 group-hover:translate-x-2 transition-transform"></i>
                        </button>
                    </div>

                    <div id="section-2" class="hidden space-y-8">
                        <span class="text-[10px] font-black uppercase text-primary tracking-[0.4em]">02 — Professional</span>
                        <div class="grid gap-6">
                            <div class="flex flex-col"><label class="text-[9px] font-black tracking-widest text-gray-400 uppercase">Owner's Full Name</label><input type="text" name="name" value="{{ old('name') }}" placeholder="Julian Vane" required class="input-underline text-sm font-bold"></div>
                            <div class="grid grid-cols-2 gap-8">
                                <div class="flex flex-col"><label class="text-[9px] font-black tracking-widest text-gray-400 uppercase">Phone Number</label><input type="text" name="phone" value="{{ old('phone') }}" placeholder="+62 812..." class="input-underline text-sm font-bold"></div>
                                <div class="flex flex-col"><label class="text-[9px] font-black tracking-widest text-gray-400 uppercase">Bank Name</label><input type="text" name="bank_name" placeholder="BCA / Mandiri / BNI / Others" class="input-underline text-sm font-bold"></div>
                            </div>
                            <div class="flex flex-col"><label class="text-[9px] font-black tracking-widest text-gray-400 uppercase">Account Number</label><input type="text" id="accNumber" name="account_number" placeholder="0000 0000 0000" class="input-underline text-sm font-black tracking-widest"></div>
                        </div>
                        <div class="flex items-center space-x-4">
                            <button type="button" onclick="nextStep(1)" class="w-1/3 py-5 text-[9px] font-black uppercase text-gray-400 tracking-widest">Back</button>
                            <button type="button" onclick="nextStep(3)" class="w-2/3 bg-primary text-white py-5 rounded-sm text-[10px] font-black uppercase tracking-[0.4em] shadow-2xl shadow-primary/30 flex items-center justify-center group">
                                Continue Logistics <i class="fa-solid fa-arrow-right ml-4 group-hover:translate-x-2 transition-transform"></i>
                            </button>
                        </div>
                    </div>

                    <div id="section-3" class="hidden space-y-8">
                        <span class="text-[10px] font-black uppercase text-primary tracking-[0.4em]">03 — Logistics</span>
                        <div class="space-y-6">
                            <div class="flex flex-col">
                                <div class="flex justify-between items-center mb-1">
                                    <label class="text-[9px] font-black tracking-widest text-gray-400 uppercase">Warehouse Address</label>
                                    <button type="button" class="text-[8px] font-black text-primary uppercase"><i class="fa-solid fa-location-dot"></i> Pin Location</button>
                                </div>
                                <textarea name="store_address" required placeholder="Street name, Building No, City..." class="input-underline text-sm font-medium h-24 resize-none">{{ old('store_address') }}</textarea>
                            </div>

                            <div class="flex items-start space-x-3 py-4">
                                <input type="checkbox" id="terms-checkbox" disabled class="mt-1 w-4 h-4 text-primary border-gray-300 rounded focus:ring-primary cursor-not-allowed">
                                <label for="terms-checkbox" class="text-[10px] text-gray-500 leading-relaxed uppercase font-bold tracking-wider">
                                    Saya menyetujui <button type="button" onclick="openTerms()" class="text-primary underline">Syarat & Ketentuan</button> yang berlaku pada platform DECOR.
                                </label>
                            </div>
                        </div>
                        <div class="flex items-center space-x-4">
                            <button type="button" onclick="nextStep(2)" class="w-1/3 py-5 text-[9px] font-black uppercase text-gray-400 tracking-widest">Back</button>
                            <button type="submit" id="submit-reg" disabled class="w-2/3 bg-primary text-white py-5 rounded-sm text-[10px] font-black uppercase tracking-[0.4em] shadow-2xl shadow-primary/30 flex items-center justify-center opacity-50 cursor-not-allowed">
                                Complete Registration
                            </button>
                        </div>
                    </div>
                  <!-- Terms Modal -->
    <div id="terms-modal" class="fixed inset-0 z-50 hidden overflow-y-auto bg-black/60 backdrop-blur-sm">
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="bg-white rounded-2xl max-w-2xl w-full p-8 shadow-2xl">
                <div class="flex justify-between items-center mb-6">
                    <h3 class="text-2xl font-bold text-gray-800">Syarat & Ketentuan Seller</h3>
                    <button type="button" onclick="closeTerms()" class="text-gray-400 hover:text-gray-600 transition-colors"><i class="fa-solid fa-xmark text-xl"></i></button>
                </div>
                <div id="modal-terms-box" class="terms-container h-[400px] custom-scrollbar">
                    <p class="mb-4">Dengan mendaftarkan akun sebagai Seller, pengguna dianggap telah membaca, memahami, dan menyetujui seluruh syarat dan ketentuan yang berlaku pada platform marketplace.</p>
                    
                    <h4 class="font-bold text-gray-800 mt-6 mb-2 uppercase text-[11px] tracking-wider">Kewajiban Seller</h4>
                    <ul class="list-disc pl-5 space-y-2 text-gray-600">
                        <li>Seller wajib memberikan informasi produk secara lengkap, jelas, dan sesuai dengan kondisi asli produk, termasuk nama produk, deskripsi, harga, stok, ukuran, warna, dan foto produk.</li>
                        <li>Seller dilarang menjual produk yang melanggar hukum, produk ilegal, barang terlarang, produk palsu, maupun barang yang tidak sesuai dengan peraturan yang berlaku.</li>
                        <li>Seller wajib menjaga kualitas produk dan memastikan barang yang dikirim kepada customer dalam kondisi baik, layak, dan sesuai dengan deskripsi produk yang ditampilkan.</li>
                        <li>Seller wajib memproses dan mengirimkan pesanan customer tepat waktu sesuai dengan ketentuan pengiriman yang berlaku pada platform.</li>
                        <li>Seller wajib memberikan tanggapan yang baik terhadap pertanyaan, komplain, maupun permintaan pengembalian barang dari customer sesuai prosedur yang berlaku.</li>
                    </ul>

                    <h4 class="font-bold text-gray-800 mt-6 mb-2 uppercase text-[11px] tracking-wider">Larangan Bagi Seller</h4>
                    <ul class="list-disc pl-5 space-y-2 text-gray-600">
                        <li>Seller dilarang melakukan tindakan penipuan dalam bentuk apa pun, termasuk memberikan informasi palsu, manipulasi transaksi, maupun tindakan yang merugikan customer and platform.</li>
                        <li>Seller dilarang melakukan manipulasi harga produk, termasuk menaikkan harga secara tidak wajar untuk kepentingan tertentu atau membuat promo yang menyesatkan.</li>
                        <li>Seller dilarang mengunggah atau menjual produk palsu, tiruan, atau produk yang melanggar hak kekayaan intelektual pihak lain.</li>
                        <li>Seller dilarang melakukan spam produk, duplikasi produk berlebihan, maupun aktivitas lain yang dapat mengganggu kenyamanan pengguna platform.</li>
                    </ul>

                    <h4 class="font-bold text-gray-800 mt-6 mb-2 uppercase text-[11px] tracking-wider">Hak Platform</h4>
                    <ul class="list-disc pl-5 space-y-2 text-gray-600">
                        <li>Platform berhak melakukan peninjauan, pembatasan, penghapusan produk, maupun penonaktifan toko apabila ditemukan pelanggaran terhadap syarat dan ketentuan yang berlaku.</li>
                        <li>Platform berhak memberikan peringatan, suspend sementara, maupun suspend permanen terhadap akun seller yang terbukti melakukan pelanggaran.</li>
                        <li>Platform berhak menghapus produk atau konten yang dianggap tidak sesuai, menyesatkan, melanggar hukum, atau merugikan pihak lain.</li>
                    </ul>
                    
                    <div class="mt-8 p-4 bg-primary/5 border border-primary/20 rounded-lg">
                        <p class="text-primary font-bold text-center italic text-xs">Silakan scroll hingga akhir untuk mengaktifkan persetujuan.</p>
                    </div>
                </div>
                <div class="mt-6 flex justify-end">
                    <button type="button" id="btn-modal-close" disabled onclick="acceptTerms()" class="bg-gray-200 text-gray-500 px-8 py-3 rounded-lg font-bold text-sm uppercase tracking-widest cursor-not-allowed transition-all duration-300">Close & Accept</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        function openTerms() {
            document.getElementById('terms-modal').classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }

        function closeTerms() {
            document.getElementById('terms-modal').classList.add('hidden');
            document.body.style.overflow = 'auto';
        }

        function acceptTerms() {
            termsCheckbox.checked = true;
            submitBtn.disabled = false;
            submitBtn.classList.remove('opacity-50', 'cursor-not-allowed');
            closeTerms();
        }

        const modalTermsBox = document.getElementById('modal-terms-box');
        const modalCloseBtn = document.getElementById('btn-modal-close');
        const termsCheckbox = document.getElementById('terms-checkbox');
        const submitBtn = document.getElementById('submit-reg');

        modalTermsBox.addEventListener('scroll', function() {
            const scrollTotal = modalTermsBox.scrollHeight - modalTermsBox.clientHeight;
            if (modalTermsBox.scrollTop / scrollTotal > 0.95) {
                modalCloseBtn.disabled = false;
                modalCloseBtn.classList.remove('bg-gray-200', 'text-gray-500', 'cursor-not-allowed');
                modalCloseBtn.classList.add('bg-primary', 'text-white');
                
                termsCheckbox.disabled = false;
                termsCheckbox.classList.remove('cursor-not-allowed');
            }
        });

        termsCheckbox.addEventListener('change', function() {
            if (this.checked) {
                submitBtn.disabled = false;
                submitBtn.classList.remove('opacity-50', 'cursor-not-allowed');
            } else {
                submitBtn.disabled = true;
                submitBtn.classList.add('opacity-50', 'cursor-not-allowed');
            }
        });

        function nextStep(step) {
            document.getElementById('section-1').classList.add('hidden');
            document.getElementById('section-2').classList.add('hidden');
            document.getElementById('section-3').classList.add('hidden');
            
            document.getElementById('section-' + step).classList.remove('hidden');
            
            for(let i=1; i<=3; i++) {
                document.getElementById('dot-' + i).classList.remove('active');
            }
            document.getElementById('dot-' + step).classList.add('active');
            
            const progressTexts = ["Progress 33%", "Progress 66%", "Progress 100%"];
            document.getElementById('progress-text').innerText = progressTexts[step-1];
        }

        document.getElementById('accNumber').addEventListener('input', function (e) {
            e.target.value = e.target.value.replace(/[^\d]/g, '').replace(/(.{4})/g, '$1 ').trim();
        });
    </script>
</body>
</html>