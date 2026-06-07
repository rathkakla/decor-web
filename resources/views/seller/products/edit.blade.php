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
                        <div class="flex items-center space-x-3 border-b border-gray-50 pb-4">
                            <i class="fa-solid fa-image text-primary"></i>
                            <h3 class="font-bold text-gray-700 text-sm uppercase tracking-wide">Media Gallery</h3>
                        </div>
                        
                        <input type="file" name="image" id="file-upload" class="hidden" accept="image/png, image/jpeg, image/jpg" onchange="previewImage(event)">

                        <div class="flex space-x-4">
                            <div class="w-24 h-24 rounded-xl border-2 border-primary p-1 relative">
                                <img id="image-preview" src="{{ $product->images->first()->img_url ?? 'https://via.placeholder.com/200' }}" class="w-full h-full object-cover rounded-lg">
                            </div>
                            
                            <div onclick="document.getElementById('file-upload').click()" class="w-24 h-24 rounded-xl border border-gray-100 bg-gray-50 flex flex-col items-center justify-center text-gray-300 hover:text-primary cursor-pointer border-dashed transition-all">
                                <i class="fa-solid fa-camera text-lg"></i>
                                <span class="text-[8px] font-bold uppercase mt-1">Change</span>
                            </div>
                        </div>
                        <p class="text-[10px] text-gray-400 font-medium mt-2">*Upload gambar baru hanya jika ingin mengganti gambar yang sudah ada.</p>
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
        // Fungsi untuk preview gambar jika user mengupload gambar baru
        function previewImage(event) {
            const input = event.target;
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById('image-preview').src = e.target.result;
                }
                reader.readAsDataURL(input.files[0]);
            }
        }

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