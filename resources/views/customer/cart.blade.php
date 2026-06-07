@php
    $site_name = "DECOR";
    
    // Inisialisasi hitungan
    $subtotal = 0;
    if($cart && $cart->cartItems) {
        foreach ($cart->cartItems as $item) {
            // Hanya hitung yang dicentang (is_selected)
            if($item->is_selected) {
                $subtotal += ($item->product->price * $item->quantity);
            }
        }
    }

    // Biaya tambahan (Contoh Rp 150.000)
    $shipping = $subtotal > 0 ? 150000 : 0; 
    $taxes = $subtotal * 0.11; // PPN 11%
    $total_amount = $subtotal + $shipping + $taxes;
@endphp

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Curation — {{ $site_name }}</title>
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
        .custom-checkbox { accent-color: #B5733A; width: 18px; height: 18px; cursor: pointer; }
    </style>
</head>
<body class="text-gray-800">

    @include('customer.partials.navbar')

    <main class="py-12 content-container px-6">
        <header class="mb-12">
            <h1 class="text-5xl font-bold tracking-tight mb-2">Your Cart</h1>
            <span class="text-[10px] uppercase tracking-[0.2em] text-gray-400 font-bold">
                Selected Pieces ({{ $cart ? $cart->cartItems->count() : 0 }})
            </span>
        </header>

        <div class="grid grid-cols-1 lg:grid-cols-[1fr_380px] gap-16">
            <section class="space-y-10">
                @if($cart && $cart->cartItems->count() > 0)
                    @foreach ($cart->cartItems as $item)
                    <div class="flex flex-col md:flex-row items-start md:items-center gap-6 border-b border-gray-100 pb-10">
                        <div class="flex items-center h-full pt-1">
    <form action="{{ route('customer.cart.toggle', $item->id) }}" method="POST" class="m-0">
        @csrf
        @method('PATCH')
        <!-- Trik rahasia: Jika checkbox tidak dicentang, form akan mengirim nilai 0 -->
        <input type="hidden" name="is_selected" value="0">
        <!-- Jika dicentang, akan mengirim nilai 1 -->
        <input type="checkbox" name="is_selected" value="1" class="custom-checkbox" 
               onchange="this.form.submit()" 
               {{ $item->is_selected ? 'checked' : '' }}>
    </form>
</div>
                        

                        <div class="flex flex-1 flex-col md:flex-row gap-8 w-full">
                            <img src="{{ $item->product->images->first()->img_url ?? 'https://via.placeholder.com/400' }}" 
                                 class="w-full md:w-44 h-44 object-cover rounded-lg bg-gray-50" alt="{{ $item->product->name }}">
                            
                            <div class="flex-1 flex flex-col justify-between">
                                <div>
                                    <h2 class="text-xl font-bold mb-1">{{ $item->product->name }}</h2>
                                    <p class="text-sm text-gray-400">{{ $item->product->category->name ?? 'Furniture' }}</p>
                                </div>
                                
                                <div class="flex items-center gap-6 mt-4">
                                    <div class="flex items-center gap-4 bg-gray-50 px-4 py-2 rounded">
                                        <form action="{{ route('customer.cart.decrement', $item->id) }}" method="POST" class="m-0">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="hover:text-primary transition-colors focus:outline-none">−</button>
                                        </form>

                                        <span class="text-sm font-medium">{{ sprintf("%02d", $item->quantity) }}</span>

                                        <form action="{{ route('customer.cart.increment', $item->id) }}" method="POST" class="m-0">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="hover:text-primary transition-colors focus:outline-none">+</button>
                                        </form>
                                    </div>
                                    
                                    <form action="{{ route('customer.cart.remove', $item->id) }}" method="POST" class="m-0">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-[10px] uppercase tracking-widest font-bold text-gray-300 hover:text-red-500 transition-colors focus:outline-none">✕ Remove</button>
                                    </form>
                                </div>
                            </div>
                            <div class="text-right">
                                <span class="text-lg font-bold text-primary">Rp {{ number_format($item->product->price * $item->quantity, 0, ',', '.') }}</span>
                            </div>
                        </div>
                    </div>
                    @endforeach
                @else
                    <div class="text-center py-20 bg-gray-50 rounded-2xl">
                        <p class="text-gray-400 mb-4">Your curation is empty.</p>
                        <a href="{{ route('customer.catalog') }}" class="text-primary font-bold text-sm underline underline-offset-4 hover:opacity-80 transition-opacity">Explore Collections</a>
                    </div>
                @endif
            </section>

            <aside>
                <div class="bg-gray-50 p-8 rounded-2xl sticky top-28">
                    <h3 class="text-lg font-bold mb-8">Order Summary</h3>
                    <div class="space-y-4 mb-8">
                        <div class="flex justify-between text-xs uppercase tracking-wider text-gray-500">
                            <span>Subtotal</span>
                            <!-- Ubah ke format Rupiah -->
                            <span class="font-bold text-gray-900">Rp {{ number_format($subtotal, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between text-xs uppercase tracking-wider text-gray-500">
                            <span>Shipping</span>
                            <!-- Ubah ke format Rupiah -->
                            <span class="font-bold text-gray-900">Rp {{ number_format($shipping, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between text-xs uppercase tracking-wider text-gray-500">
                            <span>PPN (11%)</span>
                            <!-- Ubah ke format Rupiah -->
                            <span class="font-bold text-gray-900">Rp {{ number_format($taxes, 0, ',', '.') }}</span>
                        </div>
                    </div>
                    <div class="border-t border-gray-200 pt-8 flex justify-between items-end mb-8">
                        <span class="text-sm uppercase font-bold tracking-widest">Total Amount</span>
                        <!-- Ubah ke format Rupiah -->
                        <span class="text-2xl font-bold text-primary">Rp {{ number_format($total_amount, 0, ',', '.') }}</span>
                    </div>

                    <a href="{{ route('customer.checkout') }}" class="block w-full">
                        <button class="w-full bg-primary text-white py-5 rounded-sm text-xs font-bold uppercase tracking-widest hover:bg-opacity-90 transition shadow-lg shadow-primary/20 {{ $subtotal == 0 ? 'opacity-50 cursor-not-allowed' : '' }}" {{ $subtotal == 0 ? 'disabled' : '' }}>
                            Proceed to Checkout
                        </button>
                    </a>
                </div>
            </aside>
        </div>
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