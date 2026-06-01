<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Success — {{ env('APP_NAME', 'DECOR') }}</title>
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
    </style>
</head>
<body class="text-gray-800">

    @include('customer.partials.navbar')

    <main class="py-20 content-container px-6 text-center">
        <div class="mb-8 flex justify-center">
            <div class="w-20 h-20 bg-gray-50 rounded-2xl flex items-center justify-center border border-gray-100">
                <i class="fa-solid fa-check text-2xl text-primary"></i>
            </div>
        </div>

        <h1 class="text-6xl font-bold tracking-tighter mb-4">Payment Successful!</h1>
        <p class="text-gray-400 text-sm max-w-lg mx-auto leading-relaxed mb-12">
            Your order <span class="font-bold text-gray-600">#{{ $order->order_number ?? 'DEC-9921' }}</span> is being processed and curated by our logistical team.
        </p>

        <div class="max-w-3xl mx-auto bg-gray-50 rounded-[2rem] p-10 grid grid-cols-1 md:grid-cols-2 gap-10 text-left mb-16 border border-gray-100">
            <div>
                <h4 class="text-[10px] font-bold uppercase tracking-[0.2em] text-primary mb-4">Delivery Address</h4>
                <p class="text-sm font-bold mb-1">{{ Auth::user()->name }}</p>
                <p class="text-xs text-gray-500 leading-relaxed">
                    {!! nl2br(e($order->customer->address ?? 'Alamat pengiriman belum diatur di profil.')) !!}
                </p>
            </div>
            <div>
                <h4 class="text-[10px] font-bold uppercase tracking-[0.2em] text-primary mb-4">Estimated Arrival</h4>
                <div class="flex items-start space-x-3 mb-4">
                    <i class="fa-regular fa-calendar text-primary mt-1"></i>
                    <p class="text-sm font-bold">
                        {{ \Carbon\Carbon::parse($order->created_at)->addDays(3)->translatedFormat('l, d M Y') }} - <br>
                        {{ \Carbon\Carbon::parse($order->created_at)->addDays(5)->translatedFormat('l, d M Y') }}
                    </p>
                </div>
                <p class="text-[11px] text-gray-400 italic">A tracking link will be sent to your email once the courier departs.</p>
            </div>
        </div>

        <div class="flex flex-col md:flex-row justify-center gap-4 mb-24">
            <a href="{{ route('customer.orders') }}" class="inline-block text-center bg-primary text-white px-12 py-5 rounded-sm text-xs font-bold uppercase tracking-widest hover:bg-opacity-90 transition shadow-lg shadow-primary/20">
                Track My Order
            </a>
            <a href="{{ route('homepage') }}" class="border border-gray-200 px-12 py-5 rounded-sm text-xs font-bold uppercase tracking-widest hover:bg-gray-50 transition">
                Back to Home
            </a>
        </div>

        <section class="border-t border-gray-100 pt-20 mb-20">
            <div class="flex justify-between items-end mb-12 text-left">
                <h2 class="text-3xl font-bold tracking-tight">Complete your space</h2>
                <a href="{{ route('customer.catalog') }}" class="text-[10px] font-black uppercase tracking-widest text-gray-400 border-b-2 border-gray-100 pb-1 hover:text-primary hover:border-primary transition-all">View Curated Suggestions</a>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8 text-left">
                @forelse ($suggestions as $product)
                <div class="group cursor-pointer">
                    <a href="{{ route('customer.product-detail', $product->id) }}" class="block">
                        <div class="aspect-square rounded-2xl overflow-hidden bg-gray-50 mb-4">
    @php
        // 1. Ambil data gambar pertama
        $image = $product->images->first();
        // 2. Ambil nilai dari kolom img_url (sesuai database kamu)
        $path = $image ? $image->img_url : null;
    @endphp

    @if($path)
        <img src="{{ Str::startsWith($path, ['http://', 'https://']) ? $path : asset($path) }}" 
             onerror="this.src='https://via.placeholder.com/600?text=Image+Not+Found'"
             class="w-full h-full object-cover group-hover:scale-110 transition duration-700">
    @else
        <img src="https://via.placeholder.com/600?text=No+Image" class="w-full h-full object-cover">
    @endif
</div>
                        <h3 class="font-bold text-sm group-hover:text-primary transition-colors">{{ $product->name }}</h3>
                        <p class="text-xs text-gray-400 mt-1">Rp {{ number_format($product->price, 0, ',', '.') }}</p>
                    </a>
                </div>
                @empty
                    <p class="text-xs text-gray-400 italic col-span-3 text-center">Belum ada saran produk untuk saat ini.</p>
                @endforelse
            </div>
        </section>
    </main>

    <footer class="bg-primary text-white py-12 px-6">
        <div class="max-w-6xl mx-auto">
            <div class="pt-8 text-[10px] text-white/60 font-medium tracking-widest text-center">
                <p>©️ 2026 DECOR MARKETPLACE. ALL RIGHTS RESERVED.</p>
            </div>
        </div>
    </footer>
</body>
</html>