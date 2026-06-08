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
                                <input type="text" name="material" required placeholder="e.g. Solid Teak Wood, Leather" class="w-full bg-gray-50 rounded-lg p-3 text-sm border-transparent border-2 transition-all">
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
                        <div class="flex items-center space-x-3 border-b border-gray-50 pb-4">
                            <i class="fa-solid fa-image text-primary"></i>
                            <h3 class="font-bold text-gray-700 text-sm uppercase tracking-wide">Media</h3>
                        </div>
                        
                        <input type="file" name="image" id="file-upload" class="hidden" accept="image/png, image/jpeg, image/jpg" onchange="previewImage(event)">

                        <div onclick="document.getElementById('file-upload').click()" class="border-2 border-dashed border-secondary rounded-2xl p-12 text-center space-y-4 hover:border-primary transition-colors cursor-pointer group">
                            <div class="bg-secondary/30 w-12 h-12 rounded-lg flex items-center justify-center mx-auto text-primary group-hover:bg-primary/20 transition-all">
                                <i class="fa-solid fa-cloud-arrow-up"></i>
                            </div>
                            <div>
                                <h4 class="text-sm font-bold">Click to Upload Product Image</h4>
                                <p class="text-[10px] text-gray-400 font-medium">Upload high-resolution editorial photography. PNG or JPG.</p>
                            </div>
                        </div>

                        <div class="flex space-x-4 pt-4 hidden" id="preview-container">
                            <div class="w-24 h-24 rounded-xl border-2 border-primary p-1 relative">
                                <img id="image-preview" src="" class="w-full h-full object-cover rounded-lg">
                            </div>
                        </div>
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
        // FUNGSI PREVIEW GAMBAR
        function previewImage(event) {
            const input = event.target;
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById('image-preview').src = e.target.result;
                    document.getElementById('preview-container').classList.remove('hidden');
                }
                reader.readAsDataURL(input.files[0]);
            }
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