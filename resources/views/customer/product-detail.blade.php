<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $product->name }} — DECOR</title>
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
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;600;700;800&display=swap');

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: #ffffff;
        }

        .content-container {
            max-width: 1200px;
            margin: 0 auto;
        }

        .thumb.active {
            border-color: #B5733A;
        }

        .thumb img {
            transition: transform 0.4s ease;
        }

        .thumb:hover img {
            transform: scale(1.06);
        }

        #main-img {
            transition: opacity 0.25s ease;
        }

        #main-img.fade {
            opacity: 0;
        }

        .fade-up {
            animation: fadeUp .45s ease both;
        }

        @keyframes fadeUp {
            from {
                opacity: 0;
                transform: translateY(14px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .review-card {
            border: 1px solid #EDE8E2;
            border-radius: 16px;
            padding: 20px;
            background: #fff;
            transition: box-shadow .2s, transform .2s;
            position: relative;
            overflow: hidden;
        }

        .review-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 2px;
            background: linear-gradient(90deg, #B5733A, #E8A96A);
            opacity: 0;
            transition: opacity .2s;
        }

        .review-card:hover {
            box-shadow: 0 8px 28px rgba(0, 0, 0, .07);
            transform: translateY(-2px);
        }

        .review-card:hover::before {
            opacity: 1;
        }

        .review-photo {
            width: 60px;
            height: 60px;
            border-radius: 9px;
            object-fit: cover;
            border: 2px solid #EDE8E2;
            cursor: pointer;
            transition: border-color .15s, transform .15s;
        }

        .review-photo:hover {
            border-color: #B5733A;
            transform: scale(1.06);
        }
    </style>
</head>

<body class="text-gray-800">

    @include('customer.partials.navbar')

    <div class="content-container px-6 py-4">
        <nav class="flex items-center gap-2 text-[11px] text-gray-400 font-semibold uppercase tracking-wider">
            <a href="/" class="hover:text-primary transition-colors">Home</a>
            <i class="fa-solid fa-chevron-right text-[8px]"></i>
            <a href="#" class="hover:text-primary transition-colors">{{ $product->category->name ?? 'Collection' }}</a>
            <i class="fa-solid fa-chevron-right text-[8px]"></i>
            <span class="text-gray-600">{{ $product->name }}</span>
        </nav>
    </div>

    <main>
        <div class="content-container px-6 grid grid-cols-1 lg:grid-cols-[1fr_420px] gap-14 pb-20 fade-up">

            <div class="flex flex-col gap-10">
                <div class="flex gap-3 max-h-[480px]">
                    <div class="hidden md:flex flex-col gap-2 w-14 shrink-0">
                        @forelse ($product->images as $img)
                            <div class="thumb aspect-square rounded-md overflow-hidden border-2 cursor-pointer transition-all {{ $loop->first ? 'active border-primary' : 'border-transparent' }}"
                                onclick="switchImg('{{ asset($img->img_url) }}', this)">
                                <img src="{{ asset($img->img_url) }}" alt="Thumb" class="w-full h-full object-cover">
                            </div>
                        @empty
                            <div
                                class="thumb aspect-square rounded-md overflow-hidden border-2 cursor-pointer active border-primary">
                                <img src="https://via.placeholder.com/100" class="w-full h-full object-cover">
                            </div>
                        @endforelse
                    </div>

                    <div class="flex-1 aspect-[3/4] max-h-[480px] bg-gray-50 rounded-xl overflow-hidden relative group">
                        <img id="main-img"
                            src="{{ $product->images->isNotEmpty() ? asset($product->images->first()->img_url) : 'https://via.placeholder.com/800' }}"
                            alt="{{ $product->name }}" class="w-full h-full object-cover">
                    </div>
                </div>

                <div id="reviews">
                    <div
                        style="display:flex; align-items:center; justify-content:space-between; margin-bottom:20px; padding-bottom:16px; border-bottom:1px solid #EDE8E2;">
                        <div>
                            <p
                                style="font-size:10px; font-weight:800; letter-spacing:.18em; text-transform:uppercase; color:#B5733A; margin-bottom:4px;">
                                Customer Reviews</p>
                            <h3 style="font-size:22px; font-weight:800; color:#1C1410;">{{ $product->name }}</h3>
                        </div>
                    </div>
                    <div style="display:flex; flex-direction:column; gap:14px;">
                        @forelse($product->reviews ?? [] as $r)
                            <div class="review-card">
                                <div
                                    style="display:flex; align-items:flex-start; justify-content:space-between; gap:12px; margin-bottom:12px;">
                                    <div style="display:flex; align-items:center; gap:10px;">
                                        <img src="{{ $r->customer->user->avatar_url }}"
                                            style="width:38px; height:38px; border-radius:10px; background:#E3DCD6; flex-shrink:0; object-cover;">
                                        <div>
                                            <span
                                                style="font-size:13px; font-weight:800;">{{ $r->customer->user->full_name ?? 'Anonymous' }}</span>
                                            <p style="font-size:9px; color:#A89E97;">{{ $r->created_at->format('M d, Y') }}
                                            </p>
                                        </div>
                                    </div>
                                    <div style="flex-shrink:0;">
                                        @for($s = 1; $s <= 5; $s++)
                                            <span
                                                style="color:{{ $s <= $r->rating ? '#F59E0B' : '#E5E7EB' }}; font-size:11px;">★</span>
                                        @endfor
                                    </div>
                                </div>
                                <p style="font-size:13px; color:#4B4440; line-height:1.72; margin-bottom:12px;">
                                    {{ $r->comment }}</p>
                                
                                @if($r->reply)
                                    <div style="margin-left: 20px; padding: 12px 16px; background: #F8F6F4; border-radius: 12px; border-left: 3px solid #B5733A;">
                                        <div style="display: flex; align-items: center; gap: 8px; margin-bottom: 6px;">
                                            <i class="fa-solid fa-reply" style="color: #B5733A; font-size: 10px;"></i>
                                            <span style="font-size: 10px; font-weight: 800; color: #B5733A; text-transform: uppercase; letter-spacing: 0.05em;">Seller's Response</span>
                                        </div>
                                        <p style="font-size: 12px; color: #6B7280; font-style: italic; line-height: 1.5;">
                                            "{{ $r->reply }}"
                                        </p>
                                    </div>
                                @endif
                            </div>
                        @empty
                            <div class="text-center py-8 border border-dashed border-gray-200 rounded-2xl">
                                <p class="text-[13px] text-gray-500 font-medium">Belum ada review untuk produk ini.</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>

            <div class="lg:sticky lg:top-24 self-start space-y-7">

                <div class="space-y-2 pb-6 border-b border-gray-100">
                    <span
                        class="text-[9px] font-black uppercase tracking-[0.3em] text-primary">{{ $product->style ?? 'Exclusive' }}
                        Collection</span>
                    <h1 class="text-4xl font-bold tracking-tight leading-tight text-gray-900">{{ $product->name }}</h1>
                    <div class="flex items-baseline gap-3 mt-2">
                        <span class="text-3xl font-light text-primary">Rp
                            {{ number_format($product->price, 0, ',', '.') }}</span>
                        @if($product->stock < 5)
                            <span
                                class="text-[10px] font-black uppercase tracking-wider text-red-600 bg-red-50 px-2 py-1 rounded-full">Sisa
                                {{ $product->stock }}!</span>
                        @endif
                    </div>
                </div>

                <p class="text-[13px] text-gray-500 leading-relaxed">{{ $product->description }}</p>

                <div class="flex gap-3 items-center">
                    <form action="{{ route('customer.cart.store') }}" method="POST"
                        class="flex-1 flex gap-3 items-center">
                        @csrf
                        <input type="hidden" name="product_id" value="{{ $product->id }}">

                        <div class="flex items-center border border-gray-200 rounded-lg overflow-hidden bg-white">
                            <button type="button"
                                class="w-10 h-12 flex items-center justify-center text-gray-500 hover:bg-gray-50 hover:text-primary transition-colors text-lg font-light"
                                onclick="changeQty(-1)">−</button>
                            <input type="number" id="qty-input" name="quantity" value="1" min="1"
                                max="{{ $product->stock }}"
                                class="w-10 text-center text-sm font-bold text-gray-800 border-none outline-none focus:ring-0 bg-transparent"
                                readonly>
                            <button type="button"
                                class="w-10 h-12 flex items-center justify-center text-gray-500 hover:bg-gray-50 hover:text-primary transition-colors text-lg font-light"
                                onclick="changeQty(1, {{ $product->stock }})">+</button>
                        </div>

                        <button type="submit"
                            class="flex-1 text-center bg-primary text-white py-3.5 rounded-xl text-[11px] font-black uppercase tracking-widest hover:bg-primary/90 transition-all shadow-md shadow-primary/20 active:scale-[0.98]">
                            Add to Cart
                        </button>
                    </form>

                    <form action="{{ route('customer.favorite.toggle', $product->id) }}" method="POST">
                        @csrf
                        <button type="submit"
                            class="w-12 h-12 border border-gray-200 rounded-xl flex items-center justify-center text-gray-500 hover:border-primary hover:text-primary transition-all">
                            <i class="fa-{{ $isFavorite ? 'solid text-red-500' : 'regular' }} fa-heart"></i>
                        </button>
                    </form>
                </div>

                <div class="border-t border-gray-100 pt-6 space-y-4">
                    <h4 class="text-[10px] font-black uppercase tracking-[0.25em] text-gray-400">Specifications</h4>
                    <div class="flex gap-4">
                        <span
                            class="text-[10px] font-black uppercase tracking-wider text-gray-400 w-24 shrink-0 pt-0.5">Category</span>
                        <p class="text-[13px] text-gray-700 leading-relaxed">{{ $product->category->name ?? '-' }}</p>
                    </div>
                    <div class="flex gap-4">
                        <span
                            class="text-[10px] font-black uppercase tracking-wider text-gray-400 w-24 shrink-0 pt-0.5">Design
                            Style</span>
                        <p class="text-[13px] text-gray-700 leading-relaxed">{{ $product->style ?? '-' }}</p>
                    </div>
                </div>

                <div class="border border-gray-100 rounded-2xl p-5 space-y-4">
                    <div class="flex items-center justify-between">
                        <h4 class="text-[10px] font-black uppercase tracking-[0.25em] text-gray-400">Sold by</h4>
                        <span
                            class="text-[9px] font-black uppercase tracking-wider text-primary bg-primary/10 px-2.5 py-1 rounded-full">Verified
                            Partner</span>
                    </div>

                    <!-- UBAH DIV INI MENJADI TAG <a> -->
                    <a href="{{ route('customer.store', $product->seller_id) }}"
                        class="flex items-center gap-4 group cursor-pointer hover:bg-gray-50 p-2 -ml-2 rounded-xl transition-all">
                        <div
                            class="w-12 h-12 rounded-xl overflow-hidden border border-gray-100 shrink-0 bg-gray-50 group-hover:border-primary transition-colors">
                            <img src="{{ $product->seller->store_image_url }}"
                                class="w-full h-full object-cover">
                        </div>
                        <div class="flex-1 min-w-0">
                            <!-- Tambahkan group-hover:text-primary agar nama berubah warna saat disorot -->
                            <p
                                class="font-bold text-gray-900 text-sm truncate group-hover:text-primary transition-colors">
                                {{ $product->seller->store_name }}</p>
                            <p class="text-[11px] text-gray-400 flex items-center gap-1 mt-0.5 line-clamp-1">
                                <i class="fa-solid fa-location-dot text-[9px]"></i>
                                {{ $product->seller->store_address ?? 'Indonesia' }}
                            </p>
                        </div>
                    </a>

                    <!-- Tombol Chat tetap dipertahankan -->
                    <a href="{{ route('customer.chat-seller.with', $product->seller_id) }}"
                        class="w-full flex items-center justify-center gap-2.5 border border-primary text-primary py-3 rounded-xl text-[11px] font-black uppercase tracking-widest hover:bg-primary hover:text-white transition-all duration-200 active:scale-[0.98]">
                        <i class="fa-regular fa-message"></i> Chat with Seller
                    </a>
                </div>
            </div>

            <section class="bg-gray-50/60 py-16 border-t border-gray-100">
                <div class="content-container px-6">
                    <div class="flex items-end justify-between mb-10">
                        <div>
                            <p class="text-[10px] font-black uppercase tracking-[0.3em] text-primary mb-1">Curated
                                Pairings</p>
                            <h2 class="text-2xl font-bold text-gray-900">Complete the Atmosphere</h2>
                        </div>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                        @forelse ($relatedProducts as $rec)
                            <a href="{{ route('customer.product-detail', $rec->id) }}" class="group">
                                <div class="aspect-[4/3] rounded-xl overflow-hidden bg-gray-100 mb-4 relative">
                                    <img src="{{ asset($rec->images->first()->img_url ?? 'https://via.placeholder.com/500') }}"
                                        alt="{{ $rec->name }}"
                                        class="w-full h-full object-cover group-hover:scale-105 transition duration-700">
                                    <div
                                        class="absolute inset-0 bg-black/0 group-hover:bg-black/10 transition-all duration-500 rounded-2xl">
                                    </div>
                                </div>
                                <div class="flex items-center justify-between px-1">
                                    <h4
                                        class="font-semibold text-[14px] text-gray-900 group-hover:text-primary transition-colors">
                                        {{ $rec->name }}</h4>
                                    <span class="text-[13px] font-bold text-gray-700">Rp
                                        {{ number_format($rec->price, 0, ',', '.') }}</span>
                                </div>
                            </a>
                        @empty
                            <p class="text-gray-400 text-sm">Belum ada produk serupa di kategori ini.</p>
                        @endforelse
                    </div>
                </div>
            </section>
    </main>

    <script>
        function switchImg(src, el) {
            const main = document.getElementById('main-img');
            main.classList.add('fade');
            setTimeout(() => { main.src = src; main.classList.remove('fade'); }, 220);
            document.querySelectorAll('.thumb').forEach(t => t.classList.remove('active', 'border-primary'));
            el.classList.add('active', 'border-primary');
        }

        function changeQty(delta, maxStock) {
            const inputEl = document.getElementById('qty-input');
            let v = parseInt(inputEl.value) + delta;
            if (v < 1) v = 1;
            if (v > maxStock) {
                alert('Maksimal pembelian adalah ' + maxStock + ' barang!');
                v = maxStock;
            }
            inputEl.value = v;
        }
    </script>
</body>

</html>