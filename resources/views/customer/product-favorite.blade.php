<?php $site_name = "DECOR"; ?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product Favorite — DECOR</title>
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

        /* Card hover actions */
        .fav-card .card-actions {
            opacity: 0;
            transform: translateY(6px);
            transition: opacity 0.25s ease, transform 0.25s ease;
        }
        .fav-card:hover .card-actions { opacity: 1; transform: translateY(0); }
        .fav-card img { transition: transform 0.5s ease; }
        .fav-card:hover img { transform: scale(1.05); }

        /* Remove btn */
        .btn-remove {
            opacity: 0;
            transition: opacity 0.2s ease;
        }
        .fav-card:hover .btn-remove { opacity: 1; }

        /* Fade up */
        @keyframes fadeUp {
            from { opacity:0; transform:translateY(12px); }
            to   { opacity:1; transform:translateY(0); }
        }
        .fade-up { animation: fadeUp .4s ease both; }
        .fade-up:nth-child(1){animation-delay:.05s}
        .fade-up:nth-child(2){animation-delay:.10s}
        .fade-up:nth-child(3){animation-delay:.15s}
        .fade-up:nth-child(4){animation-delay:.20s}
        .fade-up:nth-child(5){animation-delay:.25s}
        .fade-up:nth-child(6){animation-delay:.30s}
    </style>
</head>
<body class="text-gray-800 flex flex-col min-h-screen">

<!-- ══════════════════════════════════════
     NAVBAR
══════════════════════════════════════ -->
@include('customer.partials.navbar')


<!-- ══════════════════════════════════════
     MAIN LAYOUT
══════════════════════════════════════ -->
<main class="flex-grow flex content-container w-full bg-white">

    <!-- ── Sidebar ── -->
    @include('customer.partials.sidebar')


    <!-- ── Content ── -->
    <div class="flex-grow p-12 overflow-y-auto">
        <div class="max-w-4xl">

            <!-- Header -->
            <header class="mb-10">
                <h1 class="text-4xl font-bold tracking-tighter mb-2">My Favourites</h1>
                <p class="text-[11px] text-gray-400 uppercase tracking-widest font-bold">
                    {{ $favorites->count() }} saved items
                </p>
            </header>


            <!-- ── GRID VIEW ── -->
            <div id="view-grid" class="grid grid-cols-2 xl:grid-cols-3 gap-x-5 gap-y-8">
                @forelse ($favorites as $fav)
                <div class="fav-card group cursor-pointer fade-up" data-name="{{ htmlspecialchars($fav->product->name) }}">

                    <!-- Image -->
                    <div class="relative aspect-[4/3] bg-gray-50 rounded-xl overflow-hidden mb-3">
                        <img src="{{ $fav->product->images->isNotEmpty() ? asset($fav->product->images->first()->img_url) : 'https://via.placeholder.com/600' }}" alt="{{ $fav->product->name }}"
                             class="w-full h-full object-cover">

                        <!-- Remove btn -->
                        <form action="{{ route('customer.favorite.toggle', $fav->product_id) }}" method="POST" class="absolute top-3 right-3 z-10">
                            @csrf
                            <button type="submit" class="btn-remove w-8 h-8 bg-white rounded-full flex items-center justify-center text-gray-400 hover:text-red-400 shadow-sm transition-colors">
                                <i class="fa-solid fa-xmark text-xs"></i>
                            </button>
                        </form>

                        <!-- Hover actions -->
                        <div class="card-actions absolute inset-x-3 bottom-3 flex gap-2 z-10">
                            <a href="{{ route('customer.product-detail', $fav->product_id) }}" class="flex-1 flex items-center justify-center bg-white text-gray-900 text-[9px] font-black uppercase tracking-widest py-2.5 rounded-lg hover:bg-primary hover:text-white transition-all text-center">
                                View Product
                            </a>
                            <form action="{{ route('customer.cart.store') }}" method="POST" class="w-10 h-10 shrink-0">
                                @csrf
                                <input type="hidden" name="product_id" value="{{ $fav->product_id }}">
                                <input type="hidden" name="quantity" value="1">
                                <button type="submit" class="w-full h-full bg-white rounded-lg flex items-center justify-center hover:bg-primary hover:text-white transition-all text-gray-600 {{ $fav->product->stock == 0 ? 'opacity-40 cursor-not-allowed' : '' }}" {{ $fav->product->stock == 0 ? 'disabled' : '' }}>
                                    <i class="fa-solid fa-bag-shopping text-xs"></i>
                                </button>
                            </form>
                        </div>
                    </div>

                    <!-- Info -->
                    <div class="px-0.5">
                        <p class="text-[9px] font-black uppercase tracking-[0.18em] text-gray-300 mb-0.5">{{ $fav->product->style ?? 'FURNITURE' }} • {{ $fav->product->category->name ?? 'CATEGORY' }}</p>
                        <div class="flex items-center justify-between gap-2">
                            <h3 class="text-[13px] font-semibold text-gray-900 leading-snug group-hover:text-primary transition-colors truncate">{{ $fav->product->name }}</h3>
                            <span class="text-[12px] font-bold text-gray-700 shrink-0">Rp {{ number_format($fav->product->price, 0, ',', '.') }}</span>
                        </div>
                    </div>

                </div>
                @empty
                @endforelse
            </div>


            <!-- ── LIST VIEW (hidden by default) ── -->
            <div id="view-list" class="hidden space-y-3">
                @forelse ($favorites as $fav)
                <div class="fav-card group flex items-center gap-5 p-4 bg-gray-50/40 rounded-2xl border border-gray-100 hover:border-primary/20 hover:bg-gray-50 transition-all fade-up"
                     data-name="{{ htmlspecialchars($fav->product->name) }}">

                    <!-- Thumbnail -->
                    <div class="w-20 h-20 rounded-xl overflow-hidden bg-gray-100 shrink-0">
                        <img src="{{ $fav->product->images->isNotEmpty() ? asset($fav->product->images->first()->img_url) : 'https://via.placeholder.com/600' }}" alt="{{ $fav->product->name }}" class="w-full h-full object-cover">
                    </div>

                    <!-- Info -->
                    <div class="flex-1 min-w-0">
                        <p class="text-[9px] font-black uppercase tracking-[0.18em] text-gray-300 mb-0.5">{{ $fav->product->style ?? 'FURNITURE' }} • {{ $fav->product->category->name ?? 'CATEGORY' }}</p>
                        <h3 class="text-[14px] font-semibold text-gray-900 group-hover:text-primary transition-colors truncate">{{ $fav->product->name }}</h3>
                    </div>

                    <!-- Price -->
                    <span class="text-[14px] font-bold text-gray-800 shrink-0">Rp {{ number_format($fav->product->price, 0, ',', '.') }}</span>

                    <!-- Actions -->
                    <div class="card-actions flex items-center gap-2 shrink-0">
                        <a href="{{ route('customer.product-detail', $fav->product_id) }}" class="text-[10px] font-black uppercase tracking-widest border border-gray-200 px-4 py-2 rounded-lg hover:border-primary hover:text-primary transition-all text-gray-500">
                            View
                        </a>
                        <form action="{{ route('customer.cart.store') }}" method="POST">
                            @csrf
                            <input type="hidden" name="product_id" value="{{ $fav->product_id }}">
                            <input type="hidden" name="quantity" value="1">
                            <button type="submit" class="w-9 h-9 bg-primary text-white rounded-lg flex items-center justify-center hover:bg-primary/90 transition-all shrink-0 {{ $fav->product->stock == 0 ? 'opacity-40 cursor-not-allowed' : '' }}"
                                    {{ $fav->product->stock == 0 ? 'disabled' : '' }}>
                                <i class="fa-solid fa-bag-shopping text-xs"></i>
                            </button>
                        </form>
                        <form action="{{ route('customer.favorite.toggle', $fav->product_id) }}" method="POST">
                            @csrf
                            <button type="submit" class="btn-remove w-9 h-9 border border-gray-200 rounded-lg flex items-center justify-center text-gray-400 hover:text-red-400 hover:border-red-200 transition-all">
                                <i class="fa-regular fa-trash-can text-xs"></i>
                            </button>
                        </form>
                    </div>

                </div>
                @empty
                @endforelse
            </div>


            @if ($favorites->isEmpty())
            <!-- Empty state -->
            <div id="empty-state" class="text-center py-24">
                <div class="w-20 h-20 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-5">
                    <i class="fa-regular fa-heart text-2xl text-gray-300"></i>
                </div>
                <h3 class="font-bold text-gray-800 mb-2">No favourites yet</h3>
                <p class="text-[12px] text-gray-400 mb-6">Items you save will appear here.</p>
                <a href="{{ route('customer.catalog') }}"
                   class="inline-flex items-center gap-2 bg-primary text-white px-6 py-3 rounded-xl text-[11px] font-black uppercase tracking-widest hover:bg-primary/90 transition-all">
                    <i class="fa-solid fa-arrow-left text-xs"></i>
                    Browse Collections
                </a>
            </div>
            @endif

        </div>
    </div>

</main>


<!-- ══════════════════════════════════════
     FOOTER
══════════════════════════════════════ -->
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
    /* ── View toggle ── */
    function setView(mode) {
        const grid    = document.getElementById('view-grid');
        const list    = document.getElementById('view-list');
        const btnGrid = document.getElementById('btn-grid');
        const btnList = document.getElementById('btn-list');

        if (mode === 'grid') {
            grid.classList.remove('hidden');
            list.classList.add('hidden');
            btnGrid.classList.add('bg-white', 'shadow-sm', 'text-primary');
            btnGrid.classList.remove('text-gray-400');
            btnList.classList.remove('bg-white', 'shadow-sm', 'text-primary');
            btnList.classList.add('text-gray-400');
        } else {
            list.classList.remove('hidden');
            grid.classList.add('hidden');
            btnList.classList.add('bg-white', 'shadow-sm', 'text-primary');
            btnList.classList.remove('text-gray-400');
            btnGrid.classList.remove('bg-white', 'shadow-sm', 'text-primary');
            btnGrid.classList.add('text-gray-400');
        }
    }


</script>

</body>
</html>