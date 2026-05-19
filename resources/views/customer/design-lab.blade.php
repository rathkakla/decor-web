<?php $site_name = "DECOR"; ?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AI Design Lab — <?= $site_name ?></title>
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
        body { font-family: 'Plus Jakarta Sans', sans-serif; background-color: #ffffff; }
        .content-container { max-width: 1200px; margin: 0 auto; }
        .room-card.active .room-card-inner { border-color: #B5733A; background-color: #fff8f3; }
        .room-card.active .room-icon { background-color: #B5733A; color: white; }
        .room-card.active .room-label { color: #B5733A; }
    </style>
</head>
<body class="text-gray-800">

    {{-- ─── HEADER ─────────────────────────────────────────────────────────── --}}
    <header class="bg-white border-b border-gray-100 sticky top-0 z-50">
        <div class="content-container flex justify-between items-center py-4 px-6">
            <div class="flex items-center space-x-8 flex-1">
                <a href="{{ route('homepage') }}" class="text-2xl font-black tracking-tighter uppercase text-primary hover:opacity-80 transition-all">
                    <?= $site_name ?>
                </a>
                <div class="hidden lg:flex items-center bg-gray-50 border border-gray-100 rounded-md px-4 py-2 w-full max-w-[180px] group focus-within:bg-white focus-within:border-primary/30 transition-all">
                    <i class="fa-solid fa-magnifying-glass text-gray-400 text-[10px] mr-2"></i>
                    <input type="text" placeholder="Search..." class="bg-transparent border-none outline-none text-[10px] w-full placeholder:text-gray-400">
                </div>
            </div>
            <nav class="hidden md:flex items-center space-x-10 text-[13px] font-medium text-gray-500 tracking-wide">
                <a href="{{ route('customer.catalog') }}" class="hover:text-primary transition-all">Collections</a>
                <a href="{{ route('customer.designers') }}" class="hover:text-primary transition-all">Designers</a>
                <a href="{{ route('customer.design-lab') }}" class="hover:text-primary transition-all text-primary font-bold">AI Studio</a>
            </nav>
            <div class="flex items-center space-x-6 flex-1 justify-end">
                <a href="{{ route('customer.cart') }}" class="text-primary hover:scale-110 transition-transform">
                    <i class="fa-solid fa-bag-shopping text-lg"></i>
                </a>
                <button class="text-primary hover:scale-110 transition-transform">
                    <i class="fa-regular fa-bell text-lg"></i>
                </button>
                <div class="w-9 h-9 rounded-md overflow-hidden border border-gray-200 cursor-pointer">
                    <a href="{{ route('customer.profile') }}" class="block">
                        <div class="w-9 h-9 rounded-md overflow-hidden border border-gray-200 cursor-pointer hover:border-primary transition-all">
                            <img src="https://api.dicebear.com/7.x/avataaars/svg?seed=Felix" class="w-full h-full bg-slate-100">
                        </div>
                    </a>
                </div>
            </div>
        </div>
    </header>

    <main class="py-16 content-container px-6">

        {{-- ─── HERO SECTION ───────────────────────────────────────────────── --}}
        <div class="grid grid-cols-1 lg:grid-cols-[420px_1fr] gap-16 items-start mb-16">

            {{-- Kiri: Upload + Pilihan ──────────────────────────────────────── --}}
            <div class="space-y-6">
                <div>
                    <h1 class="text-6xl font-bold tracking-tighter leading-[0.9] mb-4">Reimagine<br><span class="text-primary italic">Your Space</span></h1>
                    <p class="text-gray-400 text-sm leading-relaxed">
                        Upload foto ruanganmu, pilih jenis ruangan & gaya desain, lalu biarkan AI kami mentransformasinya menjadi karya profesional.
                    </p>
                </div>

                {{-- Upload Zone ─────────────────────────────────────────────── --}}
                <div id="upload-zone" class="border-2 border-dashed border-gray-200 rounded-2xl p-8 text-center hover:border-primary transition-colors cursor-pointer group relative">
                    <input type="file" id="room-input" accept="image/*" class="hidden">
                    <div id="upload-preview" class="hidden mb-4 rounded-xl overflow-hidden aspect-video bg-gray-100">
                        <img id="preview-img" src="" class="w-full h-full object-cover">
                    </div>
                    <div id="upload-placeholder">
                        <div class="w-12 h-12 bg-orange-50 rounded-full flex items-center justify-center mx-auto mb-3 group-hover:bg-primary transition-colors">
                            <i class="fa-solid fa-camera-retro text-primary group-hover:text-white"></i>
                        </div>
                        <p class="text-sm font-bold" id="upload-text">Upload Foto Ruangan</p>
                        <p class="text-[10px] text-gray-400 mt-1 uppercase tracking-widest">JPEG, PNG or HEIC up to 20MB</p>
                    </div>
                    <p id="upload-filename" class="hidden text-xs font-semibold text-primary mt-2"></p>
                </div>

                {{-- ─── STEP 1: Pilih Jenis Ruangan ────────────────────────── --}}
                <div>
                    <div class="flex items-center gap-2 mb-3">
                        <span class="w-6 h-6 rounded-full bg-primary text-white text-[10px] font-bold flex items-center justify-center">1</span>
                        <h3 class="text-sm font-bold uppercase tracking-widest">Pilih Jenis Ruangan</h3>
                    </div>
                    <div class="grid grid-cols-4 gap-2">
                        <?php
                        $rooms = [
                            ['value' => 'living-room', 'label' => 'Ruang Tamu',  'icon' => 'fa-couch'],
                            ['value' => 'bedroom',     'label' => 'Kamar Tidur', 'icon' => 'fa-bed'],
                            ['value' => 'kitchen',     'label' => 'Dapur',       'icon' => 'fa-utensils'],
                            ['value' => 'bathroom',    'label' => 'Kamar Mandi', 'icon' => 'fa-shower'],
                            ['value' => 'dining-room', 'label' => 'Ruang Makan', 'icon' => 'fa-champagne-glasses'],
                            ['value' => 'home-office', 'label' => 'Home Office', 'icon' => 'fa-laptop'],
                            ['value' => 'garden',      'label' => 'Taman',       'icon' => 'fa-seedling'],
                            ['value' => 'kids-room',   'label' => 'Kamar Anak',  'icon' => 'fa-star'],
                        ];
                        foreach($rooms as $i => $room):
                            $isDefault = ($room['value'] === 'living-room') ? 'active' : '';
                        ?>
                        <div class="room-card <?= $isDefault ?> cursor-pointer" data-room="<?= $room['value'] ?>">
                            <div class="room-card-inner border-2 border-gray-100 rounded-xl p-3 text-center hover:border-primary/50 transition-all">
                                <div class="room-icon w-8 h-8 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-1.5 transition-all">
                                    <i class="fa-solid <?= $room['icon'] ?> text-xs text-gray-500"></i>
                                </div>
                                <p class="room-label text-[9px] font-bold text-gray-500 leading-tight"><?= $room['label'] ?></p>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>

                {{-- ─── Tombol Generate ─────────────────────────────────────── --}}
                <button id="generate-btn" class="w-full bg-primary text-white font-bold text-sm py-4 rounded-2xl hover:bg-primary/90 transition-all disabled:opacity-40 disabled:cursor-not-allowed flex items-center justify-center gap-2" disabled>
                    <i class="fa-solid fa-wand-magic-sparkles"></i>
                    Generate AI Design
                </button>
                <p class="text-[10px] text-gray-400 text-center">Upload foto untuk mengaktifkan tombol generate</p>
            </div>

            {{-- Kanan: Hasil AI ─────────────────────────────────────────────── --}}
            <div class="sticky top-24">
                <div class="relative aspect-video rounded-[2rem] overflow-hidden shadow-2xl bg-gray-100">
                    <img id="ai-result-image" src="https://images.unsplash.com/photo-1618221195710-dd6b41faaea6?w=1200" class="w-full h-full object-cover transition-all duration-700">
                    <div class="absolute inset-0 bg-black/10"></div>

                    {{-- Loading Overlay --}}
                    <div id="loading-overlay" class="hidden absolute inset-0 bg-black/75 flex-col items-center justify-center text-white space-y-4 backdrop-blur-sm z-10">
                        <div class="relative">
                            <i class="fa-solid fa-circle-notch animate-spin text-5xl text-primary"></i>
                        </div>
                        <p class="text-sm font-semibold tracking-wide">AI sedang meredesain ruanganmu...</p>
                        <p class="text-xs text-white/60" id="loading-status">Mengirim gambar ke AI...</p>
                        <div class="w-48 bg-white/20 rounded-full h-1 overflow-hidden">
                            <div id="progress-bar" class="h-full bg-primary rounded-full transition-all duration-1000" style="width: 0%"></div>
                        </div>
                    </div>

                    {{-- Badge --}}
                    <div id="result-badge" class="absolute top-5 right-5 bg-primary/80 backdrop-blur-md px-4 py-1.5 rounded-full text-[10px] text-white font-bold uppercase tracking-widest">
                        AI Reimagined
                    </div>

                    {{-- Info hasil --}}
                    <div id="result-info" class="hidden absolute bottom-5 left-5 right-5 bg-black/50 backdrop-blur-md rounded-2xl px-4 py-3">
                        <p id="result-label" class="text-white text-xs font-bold"></p>
                        <p class="text-white/60 text-[10px] mt-0.5">Generated by Stability AI</p>
                    </div>
                </div>

                {{-- Download button (muncul setelah generate) --}}
                <div id="download-section" class="hidden mt-4 flex gap-3">
                    <a id="download-btn" href="#" download="decor-ai-design.jpg"
                       class="flex-1 flex items-center justify-center gap-2 border-2 border-primary text-primary font-bold text-xs py-3 rounded-xl hover:bg-primary hover:text-white transition-all">
                        <i class="fa-solid fa-download"></i> Download Hasil
                    </a>
                    <button id="regenerate-btn"
                            class="flex-1 flex items-center justify-center gap-2 bg-gray-100 text-gray-600 font-bold text-xs py-3 rounded-xl hover:bg-gray-200 transition-all">
                        <i class="fa-solid fa-rotate-right"></i> Generate Ulang
                    </button>
                </div>
            </div>
        </div>

        {{-- ─── STEP 2: Pilih Style ─────────────────────────────────────────── --}}
        <section class="mb-24">
            <div class="flex items-center gap-2 mb-6">
                <span class="w-6 h-6 rounded-full bg-primary text-white text-[10px] font-bold flex items-center justify-center">2</span>
                <h2 class="text-2xl font-bold tracking-tight">Pilih Gaya Desain</h2>
            </div>
            <p class="text-sm text-gray-400 mb-8 max-w-lg">AI kami akan mengaplikasikan estetika desain yang kamu pilih ke struktur ruangan aslimu.</p>

            <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-8 gap-3">
                <?php
                $styles = [
                    ['name' => 'Scandinavian', 'desc' => 'Light & Organic',    'img' => 'https://images.unsplash.com/photo-1594026112284-02bb6f3352fe?w=400'],
                    ['name' => 'Industrial',   'desc' => 'Raw & Structural',   'img' => 'https://images.unsplash.com/photo-1505015920881-0f83c2f7c95e?w=400'],
                    ['name' => 'Minimalist',   'desc' => 'Pure & Essential',   'img' => 'https://images.unsplash.com/photo-1512918728675-ed5a9ecdebfd?w=400'],
                    ['name' => 'Modern',       'desc' => 'Sleek & Fluid',      'img' => 'https://images.unsplash.com/photo-1493809842364-78817add7ffb?w=400'],
                    ['name' => 'Classic',      'desc' => 'Timeless & Ornate',  'img' => 'https://images.unsplash.com/photo-1533090161767-e6ffed986c88?w=400'],
                    ['name' => 'Bohemian',     'desc' => 'Free & Eclectic',    'img' => 'https://images.unsplash.com/photo-1522444195799-478538b28823?w=400'],
                    ['name' => 'Japanese',     'desc' => 'Zen & Natural',      'img' => 'https://images.unsplash.com/photo-1558618666-fcd25c85cd64?w=400'],
                    ['name' => 'Mediterranean','desc' => 'Warm & Vibrant',     'img' => 'https://images.unsplash.com/photo-1600585154340-be6161a56a0c?w=400'],
                ];
                $default_style = 'Scandinavian';
                foreach($styles as $s):
                    $isActive = ($s['name'] === $default_style) ? 'border-primary' : 'border-transparent';
                ?>
                <div class="group cursor-pointer style-card" data-style="<?= $s['name'] ?>">
                    <div class="aspect-[3/4] rounded-xl overflow-hidden bg-gray-100 mb-2 border-2 <?= $isActive ?> card-border group-hover:border-primary transition-all">
                        <img src="<?= $s['img'] ?>" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                    </div>
                    <h4 class="text-[10px] font-bold"><?= $s['name'] ?></h4>
                    <p class="text-[9px] text-gray-400 uppercase tracking-widest"><?= $s['desc'] ?></p>
                </div>
                <?php endforeach; ?>
            </div>
        </section>

        {{-- ─── SHOP THE LOOK ──────────────────────────────────────────────── --}}
        <section class="mb-20">
            <div class="flex justify-between items-center mb-10">
                <h2 class="text-3xl font-bold tracking-tight">Shop the Look</h2>
                <a href="#" class="text-[10px] font-bold uppercase tracking-widest text-primary border-b border-primary">View All 12 Items</a>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                <?php
                $looks = [
                    ['name' => 'Aurelius Lounge Chair',   'price' => '2,450', 'img' => 'https://images.unsplash.com/photo-1592078615290-033ee584e267?w=400'],
                    ['name' => 'Zenith Pendant Light',    'price' => '890',   'img' => 'https://images.unsplash.com/photo-1524484485831-a92ffc0de03f?w=400'],
                    ['name' => 'Nordic Floating Credenza','price' => '1,720', 'img' => 'https://images.unsplash.com/photo-1595428774223-ef52624120d2?w=400'],
                    ['name' => 'Handwoven Wool Rug',      'price' => '640',   'img' => 'https://images.unsplash.com/photo-1575414003591-ece8d0416c7a?w=400'],
                ];
                foreach($looks as $l):
                ?>
                <div class="space-y-4">
                    <div class="aspect-square rounded-2xl overflow-hidden bg-gray-50">
                        <img src="<?= $l['img'] ?>" class="w-full h-full object-cover hover:scale-110 transition duration-700">
                    </div>
                    <div class="flex justify-between items-start">
                        <div>
                            <h5 class="text-xs font-bold"><?= $l['name'] ?></h5>
                            <p class="text-[9px] text-gray-400 mt-1 uppercase tracking-widest">Architectural Series</p>
                        </div>
                        <span class="text-xs font-bold text-primary">$<?= $l['price'] ?></span>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </section>
    </main>

    {{-- ─── FOOTER ─────────────────────────────────────────────────────────── --}}
    <footer class="bg-primary text-white py-12 px-6 mt-12">
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
                    </div>
                    <div class="flex flex-col space-y-3">
                        <a href="{{ route('customer.help-center') }}" class="hover:text-white/70 transition-colors">Help Center</a>
                        <a href="#" class="hover:text-white/70">FAQ</a>
                    </div>
                </div>
                <div class="flex flex-col items-end space-y-6">
                    <span class="text-[10px] font-bold tracking-widest uppercase text-white/40">Portal Version 1.0</span>
                    <div class="flex space-x-4">
                        <a href="#" class="w-10 h-10 border border-white/40 flex items-center justify-center rounded-full hover:bg-white hover:text-primary transition-all"><i class="fa-brands fa-instagram"></i></a>
                        <a href="#" class="w-10 h-10 border border-white/40 flex items-center justify-center rounded-full hover:bg-white hover:text-primary transition-all"><i class="fa-regular fa-paper-plane"></i></a>
                    </div>
                </div>
            </div>
            <div class="pt-8 text-[10px] text-white/60 font-medium tracking-widest">
                <p>©️ 2026 DECOR MARKETPLACE. ALL RIGHTS RESERVED.</p>
            </div>
        </div>
    </footer>

    <script>
    document.addEventListener('DOMContentLoaded', function () {
        const uploadZone     = document.getElementById('upload-zone');
        const roomInput      = document.getElementById('room-input');
        const aiResultImage  = document.getElementById('ai-result-image');
        const loadingOverlay = document.getElementById('loading-overlay');
        const loadingStatus  = document.getElementById('loading-status');
        const uploadText     = document.getElementById('upload-text');
        const uploadFilename = document.getElementById('upload-filename');
        const uploadPreview  = document.getElementById('upload-preview');
        const previewImg     = document.getElementById('preview-img');
        const generateBtn    = document.getElementById('generate-btn');
        const downloadSection= document.getElementById('download-section');
        const downloadBtn    = document.getElementById('download-btn');
        const regenerateBtn  = document.getElementById('regenerate-btn');
        const progressBar    = document.getElementById('progress-bar');
        const resultInfo     = document.getElementById('result-info');
        const resultLabel    = document.getElementById('result-label');
        const styleCards     = document.querySelectorAll('.style-card');
        const roomCards      = document.querySelectorAll('.room-card');

        let selectedStyle    = 'Scandinavian';
        let selectedRoom     = 'living-room';
        let selectedFile     = null;
        let loadingMsgTimer  = null;
        let progressTimer    = null;

        const loadingMessages = [
            'Menganalisis struktur ruangan...',
            'Menerapkan gaya desain pilihan...',
            'AI sedang meredesain ruanganmu...',
            'Menambahkan detail interior...',
            'Hampir selesai...',
            'Memfinalisasi hasil akhir...',
        ];

        // ─── Pilih Style ──────────────────────────────────────────────────────
        styleCards.forEach(card => {
            card.addEventListener('click', function () {
                styleCards.forEach(c => c.querySelector('.card-border').classList.replace('border-primary', 'border-transparent'));
                this.querySelector('.card-border').classList.replace('border-transparent', 'border-primary');
                selectedStyle = this.getAttribute('data-style');
            });
        });

        // ─── Pilih Room Type ──────────────────────────────────────────────────
        roomCards.forEach(card => {
            card.addEventListener('click', function () {
                roomCards.forEach(c => c.classList.remove('active'));
                this.classList.add('active');
                selectedRoom = this.getAttribute('data-room');
            });
        });

        // ─── Klik Upload Zone ─────────────────────────────────────────────────
        uploadZone.addEventListener('click', function (e) {
            if (e.target !== roomInput) roomInput.click();
        });

        // ─── Drag & Drop ──────────────────────────────────────────────────────
        uploadZone.addEventListener('dragover',  e => { e.preventDefault(); uploadZone.classList.add('border-primary'); });
        uploadZone.addEventListener('dragleave', ()  => uploadZone.classList.remove('border-primary'));
        uploadZone.addEventListener('drop', function (e) {
            e.preventDefault();
            uploadZone.classList.remove('border-primary');
            const file = e.dataTransfer.files[0];
            if (file && file.type.startsWith('image/')) handleFile(file);
        });

        // ─── File Dipilih ─────────────────────────────────────────────────────
        roomInput.addEventListener('change', function () {
            if (this.files[0]) handleFile(this.files[0]);
        });

        function handleFile(file) {
            if (file.size > 20 * 1024 * 1024) {
                showToast('❌ Ukuran file terlalu besar! Maksimal 20MB.', 'error');
                return;
            }
            selectedFile = file;

            // Preview gambar
            const reader = new FileReader();
            reader.onload = e => {
                previewImg.src = e.target.result;
                uploadPreview.classList.remove('hidden');
            };
            reader.readAsDataURL(file);

            uploadFilename.innerText = '📎 ' + file.name;
            uploadFilename.classList.remove('hidden');
            uploadText.innerText = 'Ganti Foto';

            // Aktifkan tombol generate
            generateBtn.disabled = false;
            generateBtn.parentElement.querySelector('p').classList.add('hidden');
        }

        // ─── Tombol Generate ──────────────────────────────────────────────────
        generateBtn.addEventListener('click', function () {
            if (!selectedFile) return;
            sendToAI(selectedFile);
        });

        // ─── Tombol Generate Ulang ────────────────────────────────────────────
        regenerateBtn.addEventListener('click', function () {
            if (!selectedFile) return;
            sendToAI(selectedFile);
        });

        // ─── Kirim ke Backend ─────────────────────────────────────────────────
        function sendToAI(file) {
            const formData = new FormData();
            formData.append('room_image', file);
            formData.append('style',      selectedStyle);
            formData.append('room_type',  selectedRoom);

            // Tampilkan loading
            loadingOverlay.classList.remove('hidden');
            loadingOverlay.classList.add('flex');
            aiResultImage.classList.add('blur-sm', 'scale-105');
            downloadSection.classList.add('hidden');
            resultInfo.classList.add('hidden');
            generateBtn.disabled = true;

            // Animasi pesan loading
            let msgIdx = 0;
            loadingStatus.innerText = loadingMessages[0];
            loadingMsgTimer = setInterval(() => {
                msgIdx = (msgIdx + 1) % loadingMessages.length;
                loadingStatus.innerText = loadingMessages[msgIdx];
            }, 4000);

            // Animasi progress bar (estimasi 30 detik)
            let progress = 0;
            progressBar.style.width = '0%';
            progressTimer = setInterval(() => {
                progress = Math.min(progress + 2, 90); // max 90% sampai response balik
                progressBar.style.width = progress + '%';
            }, 600);

            fetch('/api/generate-room', {
                method : 'POST',
                body   : formData,
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept'      : 'application/json',
                },
            })
            .then(res => res.json())
            .then(data => {
                clearInterval(loadingMsgTimer);
                clearInterval(progressTimer);
                progressBar.style.width = '100%';

                setTimeout(() => {
                    loadingOverlay.classList.add('hidden');
                    loadingOverlay.classList.remove('flex');
                    aiResultImage.classList.remove('blur-sm', 'scale-105');
                    generateBtn.disabled = false;
                    progressBar.style.width = '0%';
                }, 300);

                if (data.success) {
                    // Fade in gambar hasil
                    aiResultImage.style.opacity = '0';
                    aiResultImage.src = data.output;
                    aiResultImage.onload = () => {
                        aiResultImage.style.transition = 'opacity 0.8s ease';
                        aiResultImage.style.opacity = '1';
                    };

                    // Tampilkan info & download
                    resultLabel.innerText = data.message;
                    resultInfo.classList.remove('hidden');
                    downloadBtn.href = data.output;
                    downloadSection.classList.remove('hidden');

                    showToast('✨ ' + data.message, 'success');
                } else {
                    showToast('❌ ' + (data.message || 'Terjadi kesalahan'), 'error');
                }
            })
            .catch(err => {
                clearInterval(loadingMsgTimer);
                clearInterval(progressTimer);
                loadingOverlay.classList.add('hidden');
                loadingOverlay.classList.remove('flex');
                aiResultImage.classList.remove('blur-sm', 'scale-105');
                generateBtn.disabled = false;
                showToast('❌ Koneksi gagal: ' + err.message, 'error');
            });
        }

        // ─── Toast Notifikasi ─────────────────────────────────────────────────
        function showToast(message, type = 'success') {
            const existing = document.getElementById('ai-toast');
            if (existing) existing.remove();

            const colors = { success: 'bg-green-500', error: 'bg-red-500', warning: 'bg-yellow-500' };
            const toast = document.createElement('div');
            toast.id = 'ai-toast';
            toast.className = `fixed bottom-6 right-6 z-50 ${colors[type]} text-white text-sm font-semibold px-6 py-4 rounded-2xl shadow-2xl max-w-sm transition-all duration-300 opacity-0 translate-y-4`;
            toast.innerText = message;
            document.body.appendChild(toast);

            requestAnimationFrame(() => toast.classList.remove('opacity-0', 'translate-y-4'));
            setTimeout(() => {
                toast.classList.add('opacity-0', 'translate-y-4');
                setTimeout(() => toast.remove(), 300);
            }, 5000);
        }
    });
    </script>
</body>
</html>