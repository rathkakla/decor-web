<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Decor Seller - Tambah Produk Baru</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&display=swap');
        body { background-color: #F8F6F4; font-family: 'Plus Jakarta Sans', sans-serif; overflow-x: hidden; }
        .bg-primary { background-color: #B5733A; }
        .text-primary { color: #B5733A; }
        .bg-secondary { background-color: #E3DCD6; }
        .active-link { background-color: rgba(181, 115, 58, 0.1); color: #B5733A; border-right: 4px solid #B5733A; }
        .sidebar-transition { transition: transform 0.3s ease-in-out, margin 0.3s ease-in-out; }
        .sidebar-hidden { transform: translateX(-100%); }
        .main-expanded { margin-left: 0 !important; left: 0 !important; }
        input:focus, select:focus, textarea:focus { border-color: #B5733A !important; ring: 0 !important; outline: none !important; }
    </style>
</head>
<body class="text-gray-800">

    @include('seller.partials.sidebar')

    <main id="main-content" class="flex-1 flex flex-col ml-64 sidebar-transition min-h-screen">
        
        <header class="h-16 bg-primary flex items-center justify-between px-8 sticky top-0 z-40 shadow-sm">
            <div class="flex items-center">
                <button id="toggle-sidebar" class="text-white hover:opacity-80 mr-4 flex items-center justify-center transition-transform active:scale-95">
                    <i class="fa-solid fa-bars-staggered text-xl"></i>
                </button>
                <h2 class="font-bold text-xs uppercase tracking-widest text-white leading-none">Add New Product</h2>
            </div>
        </header>

        <form action="{{ route('seller.products.store') }}" method="POST" enctype="multipart/form-data" class="flex-1 flex flex-col">
            @csrf

            <div class="p-8 space-y-8 flex-1">
                <div>
                    <h2 class="text-3xl font-bold text-gray-800 tracking-tight">Add New Product</h2>
                    <p class="text-[10px] font-bold text-gray-400 mt-1 uppercase tracking-widest">Fill in the details to curate your new collection item.</p>
                </div>

                @if ($errors->any())
                    <div class="bg-red-50 text-red-500 p-4 rounded-lg text-sm font-bold">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>- {{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div class="space-y-8">
                    <div class="bg-white p-8 rounded-2xl border border-gray-100 shadow-sm space-y-6">
                        <div class="flex items-center space-x-3 border-b border-gray-50 pb-4">
                            <i class="fa-solid fa-circle-info text-primary"></i>
                            <h3 class="font-bold text-gray-700 text-sm uppercase tracking-wide">Basic Information</h3>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                            
                            <div class="space-y-2 md:col-span-2">
                                <label class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Product Name</label>
                                <input type="text" name="name" required placeholder="e.g. Minimalist Oak Dining Chair" class="w-full bg-gray-50 rounded-lg p-3 text-sm border-transparent border-2 transition-all">
                            </div>

                            <div class="space-y-2">
                                <label class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Category</label>
                                <select name="category_id" required class="w-full bg-gray-50 rounded-lg p-3 text-sm border-transparent border-2">
                                    <option value="" disabled selected>Pilih Kategori</option>
                                    @foreach($categories as $cat)
                                        <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="space-y-2">
                                <label class="text-[10px] font-bold text-primary uppercase tracking-widest">Design Style (For AI Match)</label>
                                <select name="style" required class="w-full bg-orange-50 rounded-lg p-3 text-sm border-orange-200 border text-primary">
                                    <option value="" disabled selected>Pilih Gaya Desain</option>
                                    @foreach($styles as $style)
                                        <option value="{{ $style }}">{{ $style }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="space-y-2">
                                <label class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Material</label>
                                <select name="material" required class="w-full bg-gray-50 rounded-lg p-3 text-sm border-transparent border-2">
                                    <option value="" disabled selected>Pilih Material</option>
                                    @foreach($materials as $material)
                                        <option value="{{ $material }}">{{ $material }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="space-y-2">
                                <label class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Price (IDR)</label>
                                <div class="relative">
                                    <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm font-bold">Rp</span>
                                    <input type="number" name="price" required min="0" placeholder="0" class="w-full bg-gray-50 rounded-lg p-3 pl-10 text-sm border-2 @error('price') border-red-500 @else border-transparent @enderror">
                                </div>
                                @error('price')
                                    <p class="text-red-500 text-xs font-semibold mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="space-y-2">
                                <label class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Stock Quantity</label>
                                <input type="number" name="stock" required placeholder="10" class="w-full bg-gray-50 rounded-lg p-3 text-sm border-transparent border-2">
                            </div>
                        </div>
                    </div>

                    <div class="bg-white p-8 rounded-2xl border border-gray-100 shadow-sm space-y-6">
                        <div class="flex items-center space-x-3 border-b border-gray-50 pb-4">
                            <i class="fa-solid fa-file-lines text-primary"></i>
                            <h3 class="font-bold text-gray-700 text-sm uppercase tracking-wide">Description</h3>
                        </div>
                        <div class="space-y-2">
                            <label class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Product Details</label>
                            <textarea name="description" required placeholder="Describe the craftsmanship, materials, and design philosophy of this piece..." class="w-full bg-gray-50 rounded-lg p-4 text-sm min-h-[150px] border-transparent border-2 transition-all"></textarea>
                        </div>
                    </div>

                    <div class="bg-white p-8 rounded-2xl border border-gray-100 shadow-sm space-y-6">
                        <div class="flex items-center justify-between border-b border-gray-50 pb-4">
                            <div class="flex items-center space-x-3">
                                <i class="fa-solid fa-image text-primary"></i>
                                <h3 class="font-bold text-gray-700 text-sm uppercase tracking-wide">Media</h3>
                            </div>
                            <span id="photo-counter" class="text-[10px] font-black uppercase tracking-widest text-gray-400">0 / 8 foto</span>
                        </div>

                        <input type="file" name="images[]" id="file-upload" class="hidden" accept="image/png, image/jpeg, image/jpg, image/webp" multiple>

                        <div id="dropzone" class="border-2 border-dashed border-secondary rounded-2xl p-10 text-center space-y-3 hover:border-primary transition-colors cursor-pointer group">
                            <div class="bg-secondary/30 w-12 h-12 rounded-lg flex items-center justify-center mx-auto text-primary group-hover:bg-primary/20 transition-all">
                                <i class="fa-solid fa-cloud-arrow-up"></i>
                            </div>
                            <div>
                                <h4 class="text-sm font-bold">Klik atau seret foto ke sini</h4>
                                <p class="text-[10px] text-gray-400 font-medium">Pilih beberapa foto sekaligus (maks. 8). JPG, PNG, atau WEBP — otomatis dikompres agar upload tetap cepat.</p>
                            </div>
                        </div>

                        <p id="image-error" class="hidden text-xs font-semibold text-red-500"></p>

                        <div class="flex gap-3 pt-2 overflow-x-auto pb-2 hidden" id="preview-container">
                            <!-- Previews go here -->
                        </div>
                        <p class="text-[10px] text-gray-400 font-medium">Foto pertama ditandai MAIN dan menjadi cover produk. Gunakan panah untuk mengubah urutan, ikon silang untuk menghapus.</p>
                    </div>
                </div>
            </div>

            <div class="h-32"></div>

            <div id="fixed-bottom-bar" class="fixed bottom-0 left-64 right-0 bg-white border-t border-gray-100 px-8 py-4 flex justify-between items-center z-50 sidebar-transition">
                <div class="flex items-center space-x-2 text-[10px] font-bold text-orange-500 uppercase tracking-widest italic">
                    <i class="fa-solid fa-circle-exclamation"></i>
                    <span>Produk akan tayang setelah disubmit.</span>
                </div>
                
                <div class="flex items-center space-x-6">
                    <a href="{{ route('seller.products.index') }}" class="text-[10px] font-bold text-gray-400 uppercase tracking-widest hover:text-gray-600 transition-colors">Cancel</a>
                    
                    <button type="submit" class="bg-primary text-white px-8 py-2.5 rounded-lg text-sm font-bold shadow-lg shadow-primary/30 hover:opacity-90 transition-all">
                        Submit & Publish
                    </button>
                </div>
            </div>
        </form>
    </main>

    <script>
        // ====== MEDIA UPLOADER ======
        const MAX_PHOTOS = 8;
        const fileInput = document.getElementById('file-upload');
        const dropzone = document.getElementById('dropzone');
        const previewContainer = document.getElementById('preview-container');
        const photoCounter = document.getElementById('photo-counter');
        const imageError = document.getElementById('image-error');
        const productForm = document.querySelector('form[enctype="multipart/form-data"]');

        let selectedFiles = [];
        let previewUrls = [];

        function showImageError(message) {
            if (!imageError) return;
            if (!message) {
                imageError.textContent = '';
                imageError.classList.add('hidden');
                return;
            }
            imageError.textContent = message;
            imageError.classList.remove('hidden');
        }

        function syncFileInput() {
            const dataTransfer = new DataTransfer();
            selectedFiles.forEach(file => dataTransfer.items.add(file));
            fileInput.files = dataTransfer.files;
        }

        function loadImageElement(file) {
            return new Promise((resolve, reject) => {
                const url = URL.createObjectURL(file);
                const img = new Image();
                img.onload = () => { URL.revokeObjectURL(url); resolve(img); };
                img.onerror = () => { URL.revokeObjectURL(url); reject(new Error('image-load-failed')); };
                img.src = url;
            });
        }

        // Perkecil foto besar di browser supaya tidak ditolak limit upload server (2 MB per file).
        async function compressImage(file) {
            const targetSize = 700 * 1024;
            const needsCompression = file.size > targetSize || /image\/png/i.test(file.type);

            if (!needsCompression) {
                return file;
            }

            try {
                const image = await loadImageElement(file);
                let maxDimension = 1600;
                let quality = 0.82;
                let blob = null;

                for (let attempt = 0; attempt < 4; attempt++) {
                    const scale = Math.min(1, maxDimension / Math.max(image.width, image.height));
                    const width = Math.max(1, Math.round(image.width * scale));
                    const height = Math.max(1, Math.round(image.height * scale));

                    const canvas = document.createElement('canvas');
                    canvas.width = width;
                    canvas.height = height;

                    const ctx = canvas.getContext('2d');
                    ctx.fillStyle = '#ffffff';
                    ctx.fillRect(0, 0, width, height);
                    ctx.drawImage(image, 0, 0, width, height);

                    blob = await new Promise(resolve => canvas.toBlob(resolve, 'image/jpeg', quality));

                    if (blob && blob.size <= targetSize) {
                        break;
                    }

                    quality = Math.max(0.55, quality - 0.1);

                    if (attempt >= 2) {
                        maxDimension = 1200;
                    }
                }

                if (!blob || blob.size >= file.size) {
                    return file;
                }

                const name = file.name.replace(/\.[^.]+$/, '') + '.jpg';

                return new File([blob], name, { type: 'image/jpeg', lastModified: Date.now() });
            } catch (error) {
                return file;
            }
        }

        async function addFiles(fileList) {
            const incoming = Array.from(fileList || []);

            if (!incoming.length) {
                return;
            }

            showImageError('');

            for (const file of incoming) {
                if (selectedFiles.length >= MAX_PHOTOS) {
                    showImageError('Maksimal ' + MAX_PHOTOS + ' foto untuk setiap produk.');
                    break;
                }

                if (!file.type.startsWith('image/')) {
                    showImageError('Hanya file gambar (JPG, PNG, WEBP) yang diperbolehkan.');
                    continue;
                }

                const optimized = await compressImage(file);
                selectedFiles.push(optimized);
            }

            syncFileInput();
            renderPreviews();
        }

        function renderPreviews() {
            previewUrls.forEach(url => URL.revokeObjectURL(url));
            previewUrls = [];
            previewContainer.innerHTML = '';

            photoCounter.textContent = selectedFiles.length + ' / ' + MAX_PHOTOS + ' foto';

            if (!selectedFiles.length) {
                previewContainer.classList.add('hidden');
                return;
            }

            previewContainer.classList.remove('hidden');

            selectedFiles.forEach((file, index) => {
                const url = URL.createObjectURL(file);
                previewUrls.push(url);

                const card = document.createElement('div');
                card.className = 'w-24 h-24 rounded-xl border-2 ' + (index === 0 ? 'border-primary' : 'border-gray-200') + ' p-1 relative shrink-0 bg-white';

                const img = document.createElement('img');
                img.src = url;
                img.className = 'w-full h-full object-cover rounded-lg';
                card.appendChild(img);

                if (index === 0) {
                    const badge = document.createElement('div');
                    badge.className = 'absolute top-1.5 right-1.5 bg-primary text-white text-[8px] font-bold px-1.5 py-0.5 rounded-md shadow-sm';
                    badge.innerText = 'MAIN';
                    card.appendChild(badge);
                }

                const controls = document.createElement('div');
                controls.className = 'absolute bottom-1.5 left-1.5 right-1.5 flex items-center justify-between gap-1';

                const left = document.createElement('button');
                left.type = 'button';
                left.className = 'w-5 h-5 rounded-md bg-white/90 text-gray-600 text-[9px] flex items-center justify-center shadow-sm hover:bg-primary hover:text-white disabled:opacity-30';
                left.innerHTML = '<i class="fa-solid fa-chevron-left"></i>';
                left.disabled = index === 0;
                left.addEventListener('click', () => moveFile(index, -1));

                const right = document.createElement('button');
                right.type = 'button';
                right.className = 'w-5 h-5 rounded-md bg-white/90 text-gray-600 text-[9px] flex items-center justify-center shadow-sm hover:bg-primary hover:text-white disabled:opacity-30';
                right.innerHTML = '<i class="fa-solid fa-chevron-right"></i>';
                right.disabled = index === selectedFiles.length - 1;
                right.addEventListener('click', () => moveFile(index, 1));

                const remove = document.createElement('button');
                remove.type = 'button';
                remove.className = 'w-5 h-5 rounded-md bg-red-500 text-white text-[9px] flex items-center justify-center shadow-sm hover:bg-red-600';
                remove.innerHTML = '<i class="fa-solid fa-xmark"></i>';
                remove.addEventListener('click', () => removeFile(index));

                controls.appendChild(left);
                controls.appendChild(right);
                controls.appendChild(remove);
                card.appendChild(controls);

                previewContainer.appendChild(card);
            });
        }

        function moveFile(index, direction) {
            const target = index + direction;

            if (target < 0 || target >= selectedFiles.length) {
                return;
            }

            const [moved] = selectedFiles.splice(index, 1);
            selectedFiles.splice(target, 0, moved);
            syncFileInput();
            renderPreviews();
        }

        function removeFile(index) {
            selectedFiles.splice(index, 1);
            syncFileInput();
            renderPreviews();
        }

        dropzone.addEventListener('click', () => fileInput.click());

        ['dragenter', 'dragover'].forEach(eventName => {
            dropzone.addEventListener(eventName, event => {
                event.preventDefault();
                dropzone.classList.add('border-primary', 'bg-orange-50/40');
            });
        });

        ['dragleave', 'drop'].forEach(eventName => {
            dropzone.addEventListener(eventName, event => {
                event.preventDefault();
                dropzone.classList.remove('border-primary', 'bg-orange-50/40');
            });
        });

        dropzone.addEventListener('drop', event => {
            addFiles(event.dataTransfer.files);
        });

        fileInput.addEventListener('change', event => {
            addFiles(event.target.files);
        });

        if (productForm) {
            productForm.addEventListener('submit', event => {
                if (!selectedFiles.length) {
                    event.preventDefault();
                    showImageError('Minimal satu foto produk wajib diunggah.');
                    dropzone.scrollIntoView({ behavior: 'smooth', block: 'center' });
                    dropzone.classList.add('border-red-400');
                }
            });
        }

        // SIDEBAR TOGGLE
        const btn = document.getElementById('toggle-sidebar');
        const sidebar = document.getElementById('sidebar');
        const main = document.getElementById('main-content');
        const bottomBar = document.getElementById('fixed-bottom-bar');

        btn.addEventListener('click', () => { 
            sidebar.classList.toggle('sidebar-hidden'); 
            main.classList.toggle('main-expanded'); 
            if(bottomBar) bottomBar.classList.toggle('main-expanded');
        });
    </script>
</body>
</html>