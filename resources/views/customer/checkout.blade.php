@php
    $site_name = "DECOR";
    
    // Hitung Subtotal berdasarkan item di keranjang
    $subtotal = 0;
    if($cart && $cart->cartItems) {
        foreach ($cart->cartItems as $item) {
            // Kita asumsikan semua barang di keranjang yang dicentang akan dicheckout
            if($item->is_selected) {
                $subtotal += ($item->product->price * $item->quantity);
            }
        }
    }

    // Biaya tambahan dalam Rupiah
    $shipping = $subtotal > 0 ? 150000 : 0; // Ongkir Rp 150.000
    $taxes = $subtotal * 0.11;              // PPN 11%
    $total_amount = $subtotal + $shipping + $taxes;
@endphp

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout — {{ $site_name }}</title>
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

    <header class="bg-white border-b border-gray-100 sticky top-0 z-50">
    <div class="content-container flex justify-between items-center py-4 px-6 mx-auto max-w-[1200px]">
        
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
            <a href="{{ route('customer.design-lab') }}" class="hover:text-primary transition-all">AI Studio</a>
        </nav>

       <div class="flex items-center space-x-6 flex-1 justify-end">
    @auth
        <div class="flex items-center gap-4 border-r pr-6 border-gray-100">
            <div class="text-right hidden sm:block">
                <p class="text-[9px] uppercase tracking-widest text-gray-400 font-bold leading-none mb-1">Welcome back</p>
                <p class="text-xs font-bold text-primary capitalize">{{ Auth::user()->full_name }}</p>
            </div>
            
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="text-gray-400 hover:text-red-500 transition-colors" title="Logout">
                    <i class="fa-solid fa-power-off text-sm"></i>
                </button>
            </form>
        </div>

        <a href="{{ route('customer.cart') }}" class="text-primary hover:scale-110 transition-transform">
            <i class="fa-solid fa-bag-shopping text-lg"></i>
        </a>

        <div class="w-9 h-9 rounded-md overflow-hidden border border-gray-200 cursor-pointer hover:border-primary transition-all">
            <a href="{{ route('customer.profile') }}" class="block w-full h-full">
                <img src="https://api.dicebear.com/7.x/avataaars/svg?seed={{ Auth::user()->username }}" class="w-full h-full bg-slate-100">
            </a>
        </div>
    @else
        <a href="{{ route('login') }}" class="text-[11px] font-bold uppercase tracking-widest text-gray-500 hover:text-primary transition-all">
            Sign In
        </a>
        
        <a href="{{ route('role.selection') }}">
            <button class="bg-primary text-white px-6 py-2.5 rounded-sm text-[10px] font-black uppercase tracking-[0.2em] shadow-lg shadow-primary/20 hover:bg-opacity-90 transition-all">
                Join Us
            </button>
        </a>
    @endauth
</div>
    </div>
</header>

   <main class="py-16 content-container px-6">
        <form action="{{ route('customer.place-order') }}" method="POST">
            @csrf
            
            <div class="grid grid-cols-1 lg:grid-cols-[1fr_400px] gap-20">
                
                <div class="space-y-12">
                    <section>
                        <div class="flex justify-between items-center mb-6">
                            <h2 class="text-2xl font-bold tracking-tight">Shipping Address</h2>
                            <a href="{{ route('customer.profile') }}" class="text-[10px] font-bold uppercase tracking-widest text-primary border-b border-primary hover:opacity-80">Edit Profile</a>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="p-6 border-2 border-primary rounded-xl relative bg-orange-50/30">
                                <i class="fa-solid fa-circle-check absolute top-4 right-4 text-primary"></i>
                                <span class="text-[9px] font-bold uppercase tracking-widest text-primary mb-2 block">Primary Address</span>
                                <p class="font-bold text-sm">{{ Auth::user()->full_name }}</p>
                                <p class="text-xs text-gray-500 leading-relaxed mt-2">
                                    {{ $customer->address ?? 'Alamat belum diisi. Silakan edit di menu Profil.' }}<br>
                                    {{ $customer->city ?? 'Indonesia' }}
                                </p>
                            </div>
                        </div>
                    </section>

                    <section>
                        <h2 class="text-2xl font-bold tracking-tight mb-6">Payment Method</h2>
                        <div class="space-y-3">
                            
                            <label class="block relative cursor-pointer group">
                                <input type="radio" name="payment_method" value="bank_transfer" class="peer sr-only" required>
                                <div class="flex items-center justify-between p-5 border border-gray-200 rounded-xl bg-white peer-checked:border-2 peer-checked:border-primary peer-checked:bg-orange-50/30 transition-all">
                                    <div class="flex items-center space-x-4">
                                        <div class="w-10 h-10 bg-white border border-gray-100 rounded flex items-center justify-center text-gray-400 group-hover:text-primary transition-colors">
                                            <i class="fa-solid fa-building-columns"></i>
                                        </div>
                                        <div>
                                            <p class="text-sm font-bold text-gray-800">Bank Transfer (Manual)</p>
                                            <p class="text-[10px] text-gray-400 uppercase tracking-widest">BCA, Mandiri, BNI</p>
                                        </div>
                                    </div>
                                    <div class="w-4 h-4 rounded-full border-2 border-gray-300 flex items-center justify-center peer-checked:border-primary">
                                        <div class="w-2 h-2 rounded-full bg-primary opacity-0 peer-checked:opacity-100 transition-opacity"></div>
                                    </div>
                                </div>
                            </label>

                            <label class="block relative cursor-pointer group">
                                <input type="radio" name="payment_method" value="cod" class="peer sr-only" required>
                                <div class="flex items-center justify-between p-5 border border-gray-200 rounded-xl bg-white peer-checked:border-2 peer-checked:border-primary peer-checked:bg-orange-50/30 transition-all">
                                    <div class="flex items-center space-x-4">
                                        <div class="w-10 h-10 bg-white border border-gray-100 rounded flex items-center justify-center text-gray-400 group-hover:text-primary transition-colors">
                                            <i class="fa-solid fa-wallet"></i>
                                        </div>
                                        <div>
                                            <p class="text-sm font-bold text-gray-800">Cash on Delivery (COD)</p>
                                            <p class="text-[10px] text-gray-400 uppercase tracking-widest">Pay when it arrives</p>
                                        </div>
                                    </div>
                                    <div class="w-4 h-4 rounded-full border-2 border-gray-300 flex items-center justify-center peer-checked:border-primary">
                                        <div class="w-2 h-2 rounded-full bg-primary opacity-0 peer-checked:opacity-100 transition-opacity"></div>
                                    </div>
                                </div>
                            </label>

                        </div>
                    </section>

                    <section>
                        <h2 class="text-2xl font-bold tracking-tight mb-6">Shipping Method</h2>
                        <div class="flex items-center justify-between p-6 bg-gray-50 rounded-xl border border-gray-100">
                            <div class="flex items-center space-x-4">
                                <i class="fa-solid fa-truck-fast text-primary text-xl"></i>
                                <div>
                                    <p class="text-sm font-bold">Curated White-Glove Delivery</p>
                                    <p class="text-[10px] text-gray-400">Professional assembly and packaging removal included.</p>
                                </div>
                            </div>
                            <span class="font-bold text-sm text-primary">Rp {{ number_format($shipping, 0, ',', '.') }}</span>
                        </div>
                    </section>
                </div>

                <aside>
                    <div class="bg-gray-50/80 p-8 rounded-3xl sticky top-28 border border-gray-100">
                        <h3 class="text-xl font-bold mb-8">Order Summary</h3>
                        
                        <div class="space-y-6 mb-8 border-b border-gray-200 pb-8">
                            @if($cart && $cart->cartItems)
                                @foreach($cart->cartItems as $item)
                                    @if($item->is_selected)
                                    <div class="flex space-x-4">
                                        <img src="{{ $item->product->images->first()->img_url ?? 'https://via.placeholder.com/200' }}" class="w-16 h-16 rounded-lg object-cover bg-white border border-gray-100">
                                        <div class="flex-1">
                                            <p class="text-sm font-bold">{{ $item->product->name }}</p>
                                            <p class="text-[9px] text-gray-400 uppercase tracking-widest mt-1">Qty: {{ $item->quantity }}x</p>
                                            <p class="text-xs font-bold mt-1 text-primary">Rp {{ number_format($item->product->price * $item->quantity, 0, ',', '.') }}</p>
                                        </div>
                                    </div>
                                    @endif
                                @endforeach
                            @endif
                        </div>

                        <div class="space-y-3 mb-8">
                            <div class="flex justify-between text-[10px] uppercase font-bold tracking-widest text-gray-400">
                                <span>Subtotal</span>
                                <span class="text-gray-900">Rp {{ number_format($subtotal, 0, ',', '.') }}</span>
                            </div>
                            <div class="flex justify-between text-[10px] uppercase font-bold tracking-widest text-gray-400">
                                <span>Shipping</span>
                                <span class="text-gray-900">Rp {{ number_format($shipping, 0, ',', '.') }}</span>
                            </div>
                            <div class="flex justify-between text-[10px] uppercase font-bold tracking-widest text-gray-400">
                                <span>Estimated VAT (11%)</span>
                                <span class="text-gray-900">Rp {{ number_format($taxes, 0, ',', '.') }}</span>
                            </div>
                        </div>

                        <div class="flex justify-between items-end mb-8 pt-4 border-t border-gray-200">
                            <span class="text-lg font-bold">Total</span>
                            <span class="text-2xl font-black text-primary italic">Rp {{ number_format($total_amount, 0, ',', '.') }}</span>
                        </div>

                        <button type="submit" class="w-full bg-primary text-white py-5 rounded-sm text-xs font-bold uppercase tracking-widest hover:bg-opacity-90 transition shadow-xl shadow-primary/30 {{ $subtotal == 0 ? 'opacity-50 cursor-not-allowed' : '' }}" {{ $subtotal == 0 ? 'disabled' : '' }}>
                            Pay Now & Place Order
                        </button>
                        
                    </div>
                </aside>
            </div>
        </form> </main>

    <footer class="bg-primary text-white py-12 px-6 mt-12">
        <div class="max-w-6xl mx-auto"> 
            <div class="flex flex-col md:flex-row justify-between items-start border-b border-white/20 pb-10 gap-10">
                <div class="space-y-4">
                    <div class="flex items-center space-x-2 text-white">
                        <div class="w-8 h-8 bg-white/20 rounded flex items-center justify-center">
                            <i class="fa-solid fa-couch text-sm"></i>
                        </div>
                        <span class="text-xl font-bold tracking-widest uppercase">{{ $site_name }}</span>
                    </div>
                </div>
            </div>
            <div class="pt-8 text-[10px] text-white/60 font-medium tracking-widest">
                <p>©️ 2026 {{ $site_name }} MARKETPLACE. ALL RIGHTS RESERVED.</p>
            </div>
        </div>
    </footer>

</body>
</html>