@php $site_name = "DECOR"; @endphp
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Tracking — {{ $site_name }}</title>
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
<body class="text-gray-800 flex flex-col min-h-screen">

    <!-- HEADER (Sama seperti halaman profil) -->
    <header class="bg-white border-b border-gray-100 sticky top-0 z-50">
        <div class="content-container flex justify-between items-center py-4 px-6 mx-auto max-w-[1200px]">
            <div class="flex items-center space-x-8 flex-1">
                <a href="{{ route('customer.homepage') }}" class="text-2xl font-black tracking-tighter uppercase text-primary hover:opacity-80 transition-all">
                    {{ $site_name }}
                </a>
                <div class="hidden lg:flex items-center bg-gray-50 border border-gray-100 rounded-md px-4 py-2 w-full max-w-[180px] group focus-within:bg-white focus-within:border-primary/30 transition-all">
                    <i class="fa-solid fa-magnifying-glass text-gray-400 text-[10px] mr-2"></i>
                    <input type="text" placeholder="Search..." class="bg-transparent border-none outline-none text-[10px] w-full placeholder:text-gray-400">
                </div>
            </div>
            <nav class="hidden md:flex items-center space-x-10 text-[13px] font-medium text-gray-500 tracking-wide">
                <a href="{{ route('customer.catalog') }}" class="hover:text-primary transition-all">Collections</a>
                <a href="{{ route('customer.designers') }}" class="hover:text-primary transition-all">Designers</a>
                <a href="{{ route('customer.design-lab') }}" class="hover:text-primary transition-all">AI Studio</a>
            </nav>
            <div class="flex items-center space-x-6 flex-1 justify-end">
                <a href="{{ route('customer.cart') }}" class="text-primary hover:scale-110 transition-transform">
                    <i class="fa-solid fa-bag-shopping text-lg"></i>
                </a>
                <button class="text-primary hover:scale-110 transition-transform">
                    <i class="fa-regular fa-bell text-lg"></i>
                </button>
                <div class="w-9 h-9 rounded-md overflow-hidden border border-gray-200 cursor-pointer">
                    <a href="{{ route('customer.profile') }}">
                        <img src="https://api.dicebear.com/7.x/avataaars/svg?seed={{ urlencode($user->username) }}" alt="Profile" class="w-full h-full object-cover bg-slate-100">
                    </a>
                </div>
            </div>
        </div>
    </header>

    <main class="flex-grow flex content-container w-full bg-white">
        <!-- SIDEBAR (TETAP SAMA) -->
        <aside class="w-72 border-r border-gray-50 p-10 bg-gray-50/20 shrink-0">
            <div class="text-center mb-10">
                <img src="https://api.dicebear.com/7.x/avataaars/svg?seed={{ urlencode($user->username) }}" class="w-20 h-20 rounded-2xl mx-auto mb-4 bg-white shadow-sm border border-gray-100">
                <h3 class="font-bold text-lg">{{ $user->full_name }}</h3>
                <p class="text-[9px] text-gray-400 uppercase tracking-widest mt-1">Pro Member</p>
            </div>

            <nav class="space-y-1">
                <a href="{{ route('customer.profile') }}" class="flex items-center space-x-4 px-4 py-3 text-gray-400 hover:text-primary transition-colors">
                    <i class="fa-regular fa-user text-xs"></i> <span class="text-[11px] uppercase tracking-widest">Profile</span>
                </a>
                <a href="{{ route('customer.orders') ?? '#' }}" class="flex items-center space-x-4 px-4 py-3 bg-white text-primary font-bold rounded-xl shadow-sm border border-gray-100">
                    <i class="fa-solid fa-box-archive text-xs"></i> <span class="text-[11px] uppercase tracking-widest">Orders</span>
                </a>
                <a href="#" class="flex items-center space-x-4 px-4 py-3 text-gray-400 hover:text-primary transition-colors">
                    <i class="fa-solid fa-rotate-left text-xs"></i> <span class="text-[11px] uppercase tracking-widest">Returns</span>
                </a>
                <a href="#" class="flex items-center space-x-4 px-4 py-3 bg-white text-gray-400 font-medium rounded-xl hover:text-primary transition-colors">
                    <i class="fa-regular fa-heart text-xs"></i>
                    <span class="text-[11px] uppercase tracking-widest">Product Favorite</span>
                </a>
            </nav>
        </aside>

        <!-- MAIN CONTENT: NEW LAYOUT -->
        <div class="flex-grow p-12 bg-[#fafafa]/30">
            <div class="max-w-5xl mx-auto">
                <h1 class="text-3xl font-bold tracking-tighter mb-1 text-gray-900">My Orders</h1>
                <p class="text-sm text-gray-500 mb-10">Manage your active shipments and view past purchases.</p>

                @forelse($orders as $order)
                
                @php 
                    $isCompleted = $order->status == 'completed'; 
                    $isShipped = in_array($order->status, ['shipped', 'completed']);
                    $isProcessed = in_array($order->status, ['paid', 'shipped', 'completed']);
                @endphp

                <!-- MAIN CARD UNTUK SETIAP PESANAN -->
                <div class="bg-white rounded-2xl border border-gray-100 shadow-[0_2px_10px_-4px_rgba(0,0,0,0.05)] mb-8 overflow-hidden">
                    
                    <!-- HEADER CARD -->
                    <div class="p-8 border-b border-gray-100 flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
                        <div>
                            <span class="text-[10px] font-black text-primary uppercase tracking-widest">
                                {{ $order->status == 'shipped' ? 'In Transit' : ($order->status == 'completed' ? 'Delivered' : $order->status) }}
                            </span>
                            <h2 class="text-xl font-bold text-gray-900 mt-1">Order #DEC-{{ $order->id }}</h2>
                            <p class="text-xs text-gray-500 mt-1 font-medium">
                                Ordered on {{ $order->created_at->format('M d, Y') }} <span class="mx-2">&bull;</span> Total: <span class="font-bold text-gray-900">Rp {{ number_format($order->total_price, 0, ',', '.') }}</span>
                            </p>
                        </div>
                        
                        <!-- TOMBOL TRACK LIVE LOCATION -->
                        <button class="bg-[#1a1a1a] text-white px-5 py-2.5 rounded-lg text-xs font-bold tracking-wide hover:bg-black transition-colors shadow-sm">
                            Track Live Location
                        </button>
                    </div>

                    <!-- BODY CARD (2 KOLOM) -->
                    <div class="p-8 grid grid-cols-1 lg:grid-cols-12 gap-12">
                        
                        <!-- KOLOM KIRI: DELIVERY STATUS -->
                        <div class="lg:col-span-4">
                            <h3 class="text-[10px] font-black text-gray-400 tracking-widest uppercase mb-8">Delivery Status</h3>
                            
                            <div class="relative pl-3 space-y-8">
                                <!-- Garis Vertikal Timeline -->
                                <div class="absolute left-[17px] top-2 bottom-2 w-[2px] {{ $isCompleted ? 'bg-primary' : 'bg-gray-100' }}"></div>

                                <!-- Step 1: Ordered -->
                                <div class="relative flex items-start gap-5">
                                    <div class="z-10 w-5 h-5 rounded-full bg-primary flex items-center justify-center ring-[6px] ring-white">
                                        <i class="fa-solid fa-check text-[9px] text-white"></i>
                                    </div>
                                    <div>
                                        <h4 class="font-bold text-sm text-gray-900">Ordered</h4>
                                        <p class="text-xs text-gray-400 mt-0.5">{{ $order->created_at->format('M d, h:i A') }}</p>
                                    </div>
                                </div>

                                <!-- Step 2: Processed -->
                                <div class="relative flex items-start gap-5 {{ !$isProcessed ? 'opacity-40' : '' }}">
                                    <div class="z-10 w-5 h-5 rounded-full {{ $isProcessed ? 'bg-primary' : 'bg-gray-200' }} flex items-center justify-center ring-[6px] ring-white">
                                        <i class="fa-solid fa-check text-[9px] {{ $isProcessed ? 'text-white' : 'text-transparent' }}"></i>
                                    </div>
                                    <div>
                                        <h4 class="font-bold text-sm text-gray-900">Processed</h4>
                                        <p class="text-xs text-gray-400 mt-0.5">Payment confirmed</p>
                                    </div>
                                </div>

                                <!-- Step 3: Shipped -->
                                <div class="relative flex items-start gap-5 {{ !$isShipped ? 'opacity-40' : '' }}">
                                    <!-- Lingkaran Outlined khusus status ini seperti di gambar -->
                                    <div class="z-10 w-5 h-5 rounded-full {{ $isShipped && !$isCompleted ? 'border-2 border-primary bg-white' : ($isShipped ? 'bg-primary' : 'bg-gray-200') }} flex items-center justify-center ring-[6px] ring-white">
                                        @if($isCompleted)
                                            <i class="fa-solid fa-check text-[9px] text-white"></i>
                                        @elseif($isShipped)
                                            <div class="w-1.5 h-1.5 rounded-full bg-primary"></div>
                                        @endif
                                    </div>
                                    <div>
                                        <h4 class="font-bold text-sm {{ $isShipped && !$isCompleted ? 'text-primary' : 'text-gray-900' }}">Shipped</h4>
                                        <p class="text-xs {{ $isShipped && !$isCompleted ? 'text-primary' : 'text-gray-400' }} mt-0.5">In transit via {{ $order->shipping_courier }}</p>
                                    </div>
                                </div>

                                <!-- Step 4: Delivered -->
                                <div class="relative flex items-start gap-5 {{ !$isCompleted ? 'opacity-40' : '' }}">
                                    <div class="z-10 w-5 h-5 rounded-full {{ $isCompleted ? 'bg-primary' : 'bg-gray-100' }} flex items-center justify-center ring-[6px] ring-white">
                                        <i class="fa-solid fa-check text-[9px] {{ $isCompleted ? 'text-white' : 'text-transparent' }}"></i>
                                    </div>
                                    <div>
                                        <h4 class="font-bold text-sm text-gray-900">Delivered</h4>
                                        <p class="text-xs text-gray-400 mt-0.5">Estimated arrival</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- KOLOM KANAN: ORDER SUMMARY -->
                        <div class="lg:col-span-8">
                            <h3 class="text-[10px] font-black text-gray-400 tracking-widest uppercase mb-6">Order Summary</h3>
                            
                            <!-- Looping Produk -->
                            <div class="space-y-6">
                                @foreach($order->orderItems as $item)
                                <div class="flex items-center gap-5">
                                    <div class="w-16 h-16 bg-gray-50 rounded-lg overflow-hidden shrink-0 border border-gray-100">
                                        <img src="{{ $item->product->images->first()->img_url ?? 'https://via.placeholder.com/200' }}" class="w-full h-full object-cover">
                                    </div>
                                    <div class="flex-grow">
                                        <div class="flex justify-between items-start">
                                            <h4 class="font-bold text-sm text-gray-900">{{ $item->product->name }}</h4>
                                            <p class="font-bold text-sm text-gray-900">Rp {{ number_format($item->price, 0, ',', '.') }}</p>
                                        </div>
                                        <p class="text-xs text-gray-500 mt-1">Material: Wood / Default</p>
                                        <p class="text-xs text-gray-500 mt-0.5">Qty: {{ $item->quantity }}</p>
                                    </div>
                                </div>
                                @endforeach
                            </div>

                            <!-- Kotak Alamat & Payment -->
                            <div class="mt-8 p-6 bg-[#fafafa] rounded-xl flex flex-col md:flex-row gap-8">
                                <div class="flex-1">
                                    <h4 class="text-[9px] font-black text-gray-400 uppercase tracking-widest mb-2">Shipping Address</h4>
                                    <p class="text-xs text-gray-600 leading-relaxed font-medium">
                                        {{ $user->full_name }}<br>
                                        {{ $customer->address ?? 'Alamat belum diatur' }}<br>
                                        {{ $customer->city ?? 'Indonesia' }}
                                    </p>
                                </div>
                                <div class="flex-1">
                                    <h4 class="text-[9px] font-black text-gray-400 uppercase tracking-widest mb-2">Payment</h4>
                                    <p class="text-xs text-gray-600 leading-relaxed font-medium capitalize">
                                        {{ str_replace('_', ' ', $order->payment_method) }}
                                    </p>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
                @empty
                <!-- TAMPILAN KOSONG JIKA BELUM ADA ORDER -->
                <div class="bg-white rounded-2xl border border-gray-100 p-12 text-center">
                    <p class="text-gray-500">You don't have any orders yet.</p>
                </div>
                @endforelse

            </div>
        </div>
    </main>

    <!-- FOOTER -->
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
                         <a href="{{ route('customer.help-center') ?? '#' }}" class="hover:text-white/70 transition-colors">Help Center</a>
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

</body>
</html>