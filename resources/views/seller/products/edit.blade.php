<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Decor Seller - Edit Produk</title>
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
                <h2 class="font-bold text-xs uppercase tracking-widest text-white leading-none">Edit Product</h2>
            </div>
        </header>

        <form action="{{ route('seller.products.update', $product->id) }}" method="POST" enctype="multipart/form-data" class="flex-1 flex flex-col">
            @csrf
            @method('PUT')

            <div class="p-8 space-y-8 flex-1">
                <div>
                    <h2 class="text-3xl font-bold text-gray-800 tracking-tight">Edit Product: <span class="text-primary">{{ $product->name }}</span></h2>
                    <p class="text-[10px] font-bold text-gray-400 mt-1 uppercase tracking-widest">Update your item's information and keep your gallery up to date.</p>
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
                                <input type="text" name="name" required value="{{ old('name', $product->name) }}" class="w-full bg-gray-50 rounded-lg p-3 text-sm border-transparent border-2 transition-all">
                            </div>

                            <div class="space-y-2">
                                <label class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Category</label>
                                <select name="category_id" required class="w-full bg-gray-50 rounded-lg p-3 text-sm border-transparent border-2">
                                    @foreach($categories as $cat)
                                        <option value="{{ $cat->id }}" {{ $cat->id == $product->category_id ? 'selected' : '' }}>
                                            {{ $cat->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="space-y-2">
                                <label class="text-[10px] font-bold text-primary uppercase tracking-widest">Design Style (For AI Match)</label>
                                <select name="style" required class="w-full bg-orange-50 rounded-lg p-3 text-sm border-orange-200 border text-primary">
                                    @foreach($styles as $style)
                                        <option value="{{ $style }}" {{ $style == $product->style ? 'selected' : '' }}>
                                            {{ $style }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="space-y-2">
                                <label class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Material</label>
                                <select name="material" required class="w-full bg-gray-50 rounded-lg p-3 text-sm border-transparent border-2">
                                    <option value="" disabled>Pilih Material</option>
                                    @foreach($materials as $material)
                                        <option value="{{ $material }}" {{ $material == $product->material ? 'selected' : '' }}>
                                            {{ $material }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="space-y-2">
                                <label class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Price (IDR)</label>
                                <div class="relative">
                                    <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm font-bold">Rp</span>
                                    <input type="number" name="price" required min="0" value="{{ old('price', $product->price) }}" class="w-full bg-gray-50 rounded-lg p-3 pl-10 text-sm border-2 @error('price') border-red-500 @else border-transparent @enderror">
                                </div>
                                @error('price')
                                    <p class="text-red-500 text-xs font-semibold mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="space-y-2">
                                <label class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Stock Quantity</label>
                                <input type="number" name="stock" required value="{{ old('stock', $product->stock) }}" class="w-full bg-gray-50 rounded-lg p-3 text-sm border-transparent border-2">
                            </div>
                        </div>
                    </div>

                    <div class="bg-white p-8 rounded-2xl border border-gray-100 shadow-sm space-y-6">
                        <div class="flex items-center space-x-3 border-b border-gray-50 pb-4">
                            <i class="fa-solid fa-file-lines text-primary"></i>
                            <h3 class="font-bold text-gray-700 text-sm uppercase tracking-wide">Description</h3>
                        </div>
                        <textarea name="description" required class="w-full bg-gray-50 rounded-lg p-4 text-sm min-h-[150px] border-transparent border-2 transition-all">{{ old('description', $product->description) }}</textarea>
                    </div>

                    <div class="bg-white p-8 rounded-2xl border border-gray-100 shadow-sm space-y-6">
                        <div class="flex items-center justify-between border-b border-gray-50 pb-4">
                            <div class="flex items-center space-x-3">
                                <i class="fa-solid fa-image text-primary"></i>
                                <h3 class="font-bold text-gray-700 text-sm uppercase tracking-wide">Media Gallery</h3>
                            </div>
                            <span id="photo-counter" class="text-[10px] font-black uppercase tracking-widest text-gray-400">{{ $product->images->count() }} / 8 foto</span>
                        </div>

                        <input type="file" name="images[]" id="file-upload" class="hidden" accept="image/png, image/jpeg, image/jpg, image/webp" multiple>

                        <div id="deleted-images-container"></div>
                        <input type="hidden" name="primary_target" id="primary-target" value="">
                        <div id="image-order-container"></div>

                        <div id="media-grid" class="flex flex-wrap gap-3">
                            @foreach($product->images as $index => $image)
                                <div class="media-card w-24 h-24 rounded-xl border-2 {{ $index === 0 ? 'border-primary' : 'border-gray-200' }} p-1 relative shrink-0 bg-white server-card"
                                     data-kind="server" data-id="{{ $image->id }}">
                                    <img src="{{ asset($image->img_url) }}" class="w-full h-full object-cover rounded-lg" alt="{{ $product->name }} foto {{ $index + 1 }}">
                                    <span class="main-badge absolute top-1.5 right-1.5 bg-primary text-white text-[8px] font-bold px-1.5 py-0.5 rounded-md shadow-sm {{ $index === 0 ? '' : 'hidden' }}">MAIN</span>
                                    <button type="button" onclick="setPrimary(this)" class="main-star absolute -top-2 -left-2 w-5 h-5 rounded-full bg-white border border-gray-200 text-gray-300 text-[8px] flex items-center justify-center shadow-sm hover:text-primary hover:border-primary" title="Jadikan foto utama">
                                        <i class="fa-solid fa-star"></i>
                                    </button>
                                    <div class="absolute bottom-1.5 left-1.5 right-1.5 flex items-center justify-between gap-1">
                                        <button type="button" onclick="moveCard(this, -1)" class="w-5 h-5 rounded-md bg-white/90 text-gray-600 text-[9px] flex items-center justify-center shadow-sm hover:bg-primary hover:text-white">
                                            <i class="fa-solid fa-chevron-left"></i>
                                        </button>
                                        <button type="button" onclick="moveCard(this, 1)" class="w-5 h-5 rounded-md bg-white/90 text-gray-600 text-[9px] flex items-center justify-center shadow-sm hover:bg-primary hover:text-white">
                                            <i class="fa-solid fa-chevron-right"></i>
                                        </button>
                                        <button type="button" onclick="removeServerImage(this)" class="w-5 h-5 rounded-md bg-red-500 text-white text-[9px] flex items-center justify-center shadow-sm hover:bg-red-600">
                                            <i class="fa-solid fa-xmark"></i>
                                        </button>
                                    </div>
                                </div>
                            @endforeach

                            <div onclick="document.getElementById('file-upload').click()" id="upload-button" class="w-24 h-24 rounded-xl border border-gray-100 bg-gray-50 flex flex-col items-center justify-center text-gray-300 hover:text-primary cursor-pointer border-dashed transition-all shrink-0">
                                <i class="fa-solid fa-plus text-lg"></i>
                                <span class="text-[8px] font-bold uppercase mt-1">Add Images</span>
                            </div>
                        </div>

                        <p class="text-[10px] text-gray-400 font-medium">Klik bintang untuk menjadikan foto utama (cover). Panah mengatur urutan; ikon silang menghapus. Maksimal 8 foto, foto baru otomatis dikompres.</p>
                        <p id="image-error" class="hidden text-xs font-semibold text-red-500"></p>
                    </div>
                </div>
            </div>

            <div class="h-24"></div>

            <div id="fixed-bottom-bar" class="fixed bottom-0 left-64 right-0 bg-white border-t border-gray-100 px-8 py-4 flex justify-end items-center z-50 sidebar-transition">
                <div class="flex items-center space-x-6">
                    <a href="{{ route('seller.products.index') }}" class="text-[10px] font-bold text-gray-400 uppercase tracking-widest hover:text-gray-600 transition-colors">Cancel</a>
                    <button type="submit" class="bg-primary text-white px-8 py-2.5 rounded-lg text-sm font-bold shadow-lg shadow-primary/30 hover:opacity-90 transition-all">
                        Update & Save Product
                    </button>
                </div>
            </div>
        </form>
    </main>

    <script>
        // ====== MEDIA GALLERY MANAGER ======
        const MAX_PHOTOS = 8;
        const fileInput = document.getElementById('file-upload');
        const mediaGrid = document.getElementById('media-grid');
        const uploadButton = document.getElementById('upload-button');
        const photoCounter = document.getElementById('photo-counter');
        const imageError = document.getElementById('image-error');
        const primaryTarget = document.getElementById('primary-target');
        const orderContainer = document.getElementById('image-order-container');
        const deletedContainer = document.getElementById('deleted-images-container');
        const productForm = document.querySelector('form[enctype="multipart/form-data"]');

        let primaryCard = mediaGrid.querySelector('.media-card');

        function allCards() {
            return Array.from(mediaGrid.querySelectorAll('.media-card'));
        }

        function serverCards() {
            return allCards().filter(card => card.dataset.kind === 'server');
        }

        function newCards() {
            return allCards().filter(card => card.dataset.kind === 'new');
        }

        function showImageError(message) {
            if (!message) {
                imageError.textContent = '';
                imageError.classList.add('hidden');
                return;
            }
            imageError.textContent = message;
            imageError.classList.remove('hidden');
        }

        function syncNewFiles() {
            const dataTransfer = new DataTransfer();
            newCards().forEach(card => {
                if (card._file) {
                    dataTransfer.items.add(card._file);
                }
            });
            fileInput.files = dataTransfer.files;
        }

        function rebuildOrderInputs() {
            orderContainer.innerHTML = '';

            serverCards().forEach(card => {
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = 'image_order[]';
                input.value = card.dataset.id;
                orderContainer.appendChild(input);
            });
        }

        function updateUI() {
            const cards = allCards();
            photoCounter.textContent = cards.length + ' / ' + MAX_PHOTOS + ' foto';

            if (!primaryCard || !cards.includes(primaryCard)) {
                primaryCard = cards[0] || null;
            }

            cards.forEach(card => {
                const isPrimary = card === primaryCard;
                const badge = card.querySelector('.main-badge');
                const star = card.querySelector('.main-star');

                card.classList.toggle('border-primary', isPrimary);
                card.classList.toggle('border-gray-200', !isPrimary);

                if (badge) {
                    badge.classList.toggle('hidden', !isPrimary);
                }

                if (star) {
                    star.classList.toggle('text-primary', isPrimary);
                    star.classList.toggle('border-primary', isPrimary);
                    star.classList.toggle('text-gray-300', !isPrimary);
                    star.classList.toggle('border-gray-200', !isPrimary);
                }
            });

            syncNewFiles();
            rebuildOrderInputs();

            if (primaryCard) {
                if (primaryCard.dataset.kind === 'server') {
                    primaryTarget.value = 'img:' + primaryCard.dataset.id;
                } else {
                    primaryTarget.value = 'new:' + newCards().indexOf(primaryCard);
                }
            } else {
                primaryTarget.value = '';
            }
        }

        function moveCard(button, direction) {
            const card = button.closest('.media-card');
            const siblings = allCards();
            const index = siblings.indexOf(card);
            const target = siblings[index + direction];

            if (!card || !target) {
                return;
            }

            if (direction < 0) {
                mediaGrid.insertBefore(card, target);
            } else {
                mediaGrid.insertBefore(target, card);
            }

            showImageError('');
            updateUI();
        }

        function setPrimary(button) {
            primaryCard = button.closest('.media-card');
            mediaGrid.insertBefore(primaryCard, mediaGrid.firstElementChild);
            showImageError('');
            updateUI();
        }

        function removeServerImage(button) {
            const card = button.closest('.media-card');

            if (!card) {
                return;
            }

            if (allCards().length <= 1) {
                showImageError('Produk harus memiliki minimal satu foto.');
                return;
            }

            if (!confirm('Hapus foto ini dari galeri?')) {
                return;
            }

            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = 'delete_images[]';
            input.value = card.dataset.id;
            deletedContainer.appendChild(input);

            if (primaryCard === card) {
                primaryCard = null;
            }

            card.remove();
            showImageError('');
            updateUI();
        }

        function removeNewImage(button) {
            const card = button.closest('.media-card');

            if (!card) {
                return;
            }

            if (primaryCard === card) {
                primaryCard = null;
            }

            card.remove();
            showImageError('');
            updateUI();
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

        async function compressImage(file) {
            const needsCompression = file.size > 1500000 || /image\/png/i.test(file.type);

            if (!needsCompression) {
                return file;
            }

            try {
                const image = await loadImageElement(file);
                const maxDimension = 1600;
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

                const blob = await new Promise(resolve => canvas.toBlob(resolve, 'image/jpeg', 0.82));

                if (!blob || blob.size >= file.size) {
                    return file;
                }

                const name = file.name.replace(/\.[^.]+$/, '') + '.jpg';

                return new File([blob], name, { type: 'image/jpeg', lastModified: Date.now() });
            } catch (error) {
                return file;
            }
        }

        function createPreviewCard(file) {
            const card = document.createElement('div');
            card.className = 'media-card w-24 h-24 rounded-xl border-2 border-gray-200 p-1 relative shrink-0 bg-white';
            card.dataset.kind = 'new';
            card._file = file;

            const img = document.createElement('img');
            img.src = URL.createObjectURL(file);
            img.className = 'w-full h-full object-cover rounded-lg';
            img.onload = () => URL.revokeObjectURL(img.src);
            card.appendChild(img);

            const badge = document.createElement('span');
            badge.className = 'main-badge absolute top-1.5 right-1.5 bg-primary text-white text-[8px] font-bold px-1.5 py-0.5 rounded-md shadow-sm hidden';
            badge.textContent = 'MAIN';
            card.appendChild(badge);

            const star = document.createElement('button');
            star.type = 'button';
            star.className = 'main-star absolute -top-2 -left-2 w-5 h-5 rounded-full bg-white border border-gray-200 text-gray-300 text-[8px] flex items-center justify-center shadow-sm hover:text-primary hover:border-primary';
            star.title = 'Jadikan foto utama';
            star.innerHTML = '<i class="fa-solid fa-star"></i>';
            star.addEventListener('click', () => setPrimary(star));
            card.appendChild(star);

            const controls = document.createElement('div');
            controls.className = 'absolute bottom-1.5 left-1.5 right-1.5 flex items-center justify-between gap-1';

            const left = document.createElement('button');
            left.type = 'button';
            left.className = 'w-5 h-5 rounded-md bg-white/90 text-gray-600 text-[9px] flex items-center justify-center shadow-sm hover:bg-primary hover:text-white';
            left.innerHTML = '<i class="fa-solid fa-chevron-left"></i>';
            left.addEventListener('click', () => moveCard(left, -1));

            const right = document.createElement('button');
            right.type = 'button';
            right.className = 'w-5 h-5 rounded-md bg-white/90 text-gray-600 text-[9px] flex items-center justify-center shadow-sm hover:bg-primary hover:text-white';
            right.innerHTML = '<i class="fa-solid fa-chevron-right"></i>';
            right.addEventListener('click', () => moveCard(right, 1));

            const remove = document.createElement('button');
            remove.type = 'button';
            remove.className = 'w-5 h-5 rounded-md bg-red-500 text-white text-[9px] flex items-center justify-center shadow-sm hover:bg-red-600';
            remove.innerHTML = '<i class="fa-solid fa-xmark"></i>';
            remove.addEventListener('click', () => removeNewImage(remove));

            controls.appendChild(left);
            controls.appendChild(right);
            controls.appendChild(remove);
            card.appendChild(controls);

            return card;
        }

        async function addFiles(fileList) {
            const incoming = Array.from(fileList || []);

            if (!incoming.length) {
                return;
            }

            showImageError('');

            for (const file of incoming) {
                if (allCards().length >= MAX_PHOTOS) {
                    showImageError('Maksimal ' + MAX_PHOTOS + ' foto untuk setiap produk.');
                    break;
                }

                if (!file.type.startsWith('image/')) {
                    showImageError('Hanya file gambar (JPG, PNG, WEBP) yang diperbolehkan.');
                    continue;
                }

                const optimized = await compressImage(file);
                const card = createPreviewCard(optimized);
                mediaGrid.insertBefore(card, uploadButton);

                if (!primaryCard) {
                    primaryCard = card;
                }
            }

            updateUI();
        }

        document.getElementById('upload-button').addEventListener('click', () => fileInput.click());
        fileInput.addEventListener('change', event => addFiles(event.target.files));

        if (productForm) {
            productForm.addEventListener('submit', event => {
                if (!allCards().length) {
                    event.preventDefault();
                    showImageError('Minimal satu foto produk wajib diunggah.');
                    mediaGrid.scrollIntoView({ behavior: 'smooth', block: 'center' });
                    return;
                }

                updateUI();
            });
        }

        updateUI();

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