@php
    $site_name = "DECOR";
    // Data ini tetap ada untuk mengisi pilihan di Sidebar Filter
    $materials = ["Oak", "Walnut", "Fabric", "Leather", "Stone"];
    $styles = ["Minimalist", "Modern", "Classic", "Architectural"];
@endphp

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Collections — {{ $site_name }}</title>
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

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: #ffffff;
        }

        .content-container {
            max-width: 1200px;
            margin: 0 auto;
        }

        .product-card .card-actions {
            opacity: 0;
            transform: translateY(8px);
            transition: opacity 0.3s ease, transform 0.3s ease;
        }
        .product-card:hover .card-actions {
            opacity: 1;
            transform: translateY(0);
        }
        .product-card .card-img {
            transition: transform 0.6s ease;
        }
        .product-card:hover .card-img {
            transform: scale(1.04);
        }

        .filter-check {
            appearance: none;
            -webkit-appearance: none;
            width: 16px;
            height: 16px;
            border: 1.5px solid #D1C9C2;
            border-radius: 3px;
            cursor: pointer;
            flex-shrink: 0;
            transition: border-color 0.2s, background 0.2s;
        }
        .filter-check:checked {
            background-color: #B5733A;
            border-color: #B5733A;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 10 8'%3E%3Cpath d='M1 4l3 3 5-6' stroke='white' stroke-width='1.5' fill='none' stroke-linecap='round' stroke-linejoin='round'/%3E%3C/svg%3E");
            background-size: 10px 8px;
            background-repeat: no-repeat;
            background-position: center;
        }

        .sort-select {
            appearance: none;
            -webkit-appearance: none;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='10' height='6' viewBox='0 0 10 6'%3E%3Cpath d='M1 1l4 4 4-4' stroke='%239CA3AF' stroke-width='1.5' fill='none' stroke-linecap='round' stroke-linejoin='round'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 12px center;
        }

        @keyframes fadeUp {
            from { opacity: 0; transform: translateY(16px); }
            to   { opacity: 1; transform: translateY(0); }
        }
        .fade-up {
            animation: fadeUp 0.5s ease both;
        }
    </style>
</head>
<body class="text-gray-800">

    @include('customer.partials.navbar')

    <main>
        <section class="content-container px-6 pt-14 pb-10">
            <div class="flex flex-col md:flex-row md:items-end justify-between gap-6 border-b border-gray-100 pb-10">
                <div>
                    <p class="text-[10px] font-black uppercase tracking-[0.3em] text-primary mb-3">Spring / Summer 2026</p>
                    <h1 class="text-5xl md:text-6xl font-bold tracking-tight text-gray-900 leading-[1.1]">
                        Essential <em class="text-primary not-italic">Forms</em>
                    </h1>
                    <p class="text-gray-400 text-sm mt-4 max-w-sm leading-relaxed">
                        A curation of architectural silhouettes and natural textures, redefined for the contemporary interior.
                    </p>
                </div>
                <div class="flex items-center gap-6 shrink-0">
    <select onchange="location = this.value;" class="sort-select text-[11px] font-semibold uppercase tracking-wider text-gray-500 bg-gray-50 border border-gray-100 rounded-lg px-4 py-2.5 pr-8 outline-none cursor-pointer hover:border-primary/30 transition-all">
        <option value="{{ request()->fullUrlWithQuery(['sort' => 'featured']) }}" {{ request('sort') == 'featured' ? 'selected' : '' }}>
            Sort: Featured
        </option>
        <option value="{{ request()->fullUrlWithQuery(['sort' => 'price_low']) }}" {{ request('sort') == 'price_low' ? 'selected' : '' }}>
            Price: Low to High
        </option>
        <option value="{{ request()->fullUrlWithQuery(['sort' => 'price_high']) }}" {{ request('sort') == 'price_high' ? 'selected' : '' }}>
            Price: High to Low
        </option>
    </select>
    
    <span class="text-[11px] font-bold uppercase tracking-widest text-gray-300">
        {{ $products->count() }} items
    </span>
</div>
            </div>
        </section>

        <div class="content-container px-6 pb-16 grid grid-cols-1 lg:grid-cols-[220px_1fr] gap-12">

           <form action="{{ route('customer.catalog') }}" method="GET" id="filterForm">
    <!-- Tetap simpan pencarian & kategori yang aktif agar tidak hilang -->
    <input type="hidden" name="search" value="{{ request('search') }}">
    <input type="hidden" name="category" value="{{ request('category') }}">

    <aside class="space-y-8 pt-2">
        <!-- CATEGORIES (Tetap Link) -->
        <div>
    <h4 class="text-[10px] font-black uppercase tracking-[0.25em] mb-4 text-gray-400">Collections</h4>
    <div class="space-y-3">
        <!-- TOMBOL ALL PRODUCTS (Tambahkan kembali di sini) -->
        <a href="{{ route('customer.catalog', request()->except('category')) }}" 
           class="flex items-center justify-between group cursor-pointer">
            <span class="text-[13px] {{ !request('category') ? 'font-bold text-primary' : 'text-gray-600' }} group-hover:text-primary transition-all">
                All Products
            </span>
        </a>

        @foreach ($categories as $cat)
        <a href="{{ route('customer.catalog', array_merge(request()->query(), ['category' => $cat->name])) }}" 
           class="flex items-center justify-between group cursor-pointer">
            <span class="text-[13px] {{ request('category') == $cat->name ? 'font-bold text-primary' : 'text-gray-600' }} group-hover:text-primary transition-all">
                {{ $cat->name }}
            </span>
        </a>
        @endforeach
    </div>
</div>

        <div class="w-full h-px bg-gray-100"></div>

        <!-- MATERIAL (Checkbox) -->
        <div>
            <h4 class="text-[10px] font-black uppercase tracking-[0.25em] mb-4 text-gray-400">Material</h4>
            <div class="space-y-3">
                @foreach ($materials as $m)
                <label class="flex items-center gap-3 cursor-pointer group">
                    <input type="checkbox" name="material[]" value="{{ $m }}" 
                           onchange="this.form.submit()"
                           {{ in_array($m, (array)request('material')) ? 'checked' : '' }}
                           class="filter-check w-4 h-4 rounded border-gray-300 text-primary focus:ring-primary">
                    <span class="text-[13px] text-gray-600 group-hover:text-primary transition-colors">{{ $m }}</span>
                </label>
                @endforeach
            </div>
        </div>

        <div class="w-full h-px bg-gray-100"></div>

        <!-- STYLE (Checkbox) -->
        <div>
            <h4 class="text-[10px] font-black uppercase tracking-[0.25em] mb-4 text-gray-400">Style</h4>
            <div class="space-y-3">
                @foreach ($styles as $s)
                <label class="flex items-center gap-3 cursor-pointer group">
                    <input type="checkbox" name="style[]" value="{{ $s }}" 
                           onchange="this.form.submit()"
                           {{ in_array($s, (array)request('style')) ? 'checked' : '' }}
                           class="filter-check w-4 h-4 rounded border-gray-300 text-primary focus:ring-primary">
                    <span class="text-[13px] text-gray-600 group-hover:text-primary transition-colors">{{ $s }}</span>
                </label>
                @endforeach
            </div>
        </div>
    </aside>
</form>
            <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-x-5 gap-y-10 pt-2">
                @foreach ($products as $i => $p)
                <div class="product-card group cursor-pointer fade-up">
                    <div class="relative aspect-[4/5] bg-gray-50 rounded-xl overflow-hidden mb-4">
                        <img src="{{ $p->images->first()->img_url ?? 'https://via.placeholder.com/600x800' }}" 
                             alt="{{ $p->name }}" class="card-img w-full h-full object-cover">
                        
                        <div class="card-actions absolute inset-x-0 bottom-0 p-4 flex gap-2">
                            <a href="{{ route('customer.product-detail', $p->id) }}" class="flex-1">
                                <button class="w-full bg-white text-gray-900 text-[10px] font-black uppercase tracking-widest py-3 rounded-lg hover:bg-primary hover:text-white transition-all duration-200">
                                    View product
                                </button>
                            </a>
                            <form action="{{ route('customer.cart.store') }}" method="POST">
     @csrf
    <input type="hidden" name="product_id" value="{{ $p->id }}">
    <input type="hidden" name="quantity" value="1">
    
    <button type="submit" class="w-11 h-11 bg-white rounded-lg flex items-center justify-center hover:bg-primary hover:text-white transition-all duration-200 text-gray-700 shrink-0" title="Add to Cart">
        <i class="fa-solid fa-plus text-xs"></i>
    </button>
</form>
                        </div>
                    </div>

                    <div class="space-y-1 px-0.5">
                        <p class="text-[9px] font-black uppercase tracking-[0.2em] text-gray-300">
                            {{ $p->category->name ?? 'COLLECTION' }}
                        </p>
                        <div class="flex items-start justify-between gap-2">
                            <h3 class="text-[14px] font-semibold text-gray-900 leading-snug group-hover:text-primary transition-colors">
                                {{ $p->name }}
                            </h3>
                            <span class="text-[13px] font-bold text-gray-800 shrink-0">
                                Rp.{{ number_format($p->price, 0) }}
                            </span>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        <section class="content-container px-6 mb-16">
            <div class="relative rounded-2xl overflow-hidden bg-zinc-900">
                <div class="absolute inset-0 opacity-[0.04]" style="background-image: repeating-linear-gradient(0deg, transparent, transparent 39px, #fff 39px, #fff 40px), repeating-linear-gradient(90deg, transparent, transparent 39px, #fff 39px, #fff 40px);"></div>
                <div class="absolute -top-20 -right-20 w-80 h-80 rounded-full bg-primary/20 blur-3xl pointer-events-none"></div>

                <div class="relative z-10 flex flex-col md:flex-row items-center justify-between gap-8 px-10 py-12">
                    <div class="space-y-3 text-center md:text-left">
                        <span class="text-[9px] font-black uppercase tracking-[0.4em] text-primary">Generative Intelligence</span>
                        <h2 class="text-3xl md:text-4xl font-bold text-white leading-tight">
                            Visualize these pieces in<br><span class="text-primary italic">your</span> space with AI.
                        </h2>
                    </div>
                    <a href="{{ route('customer.design-lab') }}">
                        <button class="shrink-0 flex items-center gap-3 bg-primary text-white px-8 py-4 rounded-xl font-bold text-[11px] uppercase tracking-widest hover:bg-white hover:text-primary transition-all duration-300 group">
                            <span>Launch AI Studio</span>
                            <i class="fa-solid fa-wand-magic-sparkles group-hover:rotate-12 transition-transform"></i>
                        </button>
                    </a>
                </div>
            </div>
        </section>
    </main>

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
        </div>
        <div class="pt-8 text-[10px] text-white/60 font-medium tracking-widest">
            <p>©️ 2026 DECOR MARKETPLACE. ALL RIGHTS RESERVED.</p>
        </div>
    </div>
</footer>

</body>
</html>