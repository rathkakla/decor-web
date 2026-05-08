<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Decor Seller - Detail Pesanan #DEC-{{ $order->id }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&display=swap');
        body { background-color: #F8F6F4; font-family: 'Plus Jakarta Sans', sans-serif; overflow-x: hidden; }
        .bg-primary { background-color: #B5733A; }
        .text-primary { color: #B5733A; }
        .active-link { background-color: rgba(181, 115, 58, 0.1); color: #B5733A; border-right: 4px solid #B5733A; }
        .sidebar-transition { transition: transform 0.3s ease-in-out, margin 0.3s ease-in-out; }
        .sidebar-hidden { transform: translateX(-100%); }
        .main-expanded { margin-left: 0 !important; left: 0 !important; }
    </style>
</head>
<body class="text-gray-800">

    <!-- SIDEBAR -->
    <aside id="sidebar" class="w-64 bg-white border-r border-gray-200 flex flex-col fixed h-full z-50 sidebar-transition">
        <div class="p-8">
            <h1 class="text-2xl font-bold tracking-widest text-primary uppercase leading-none">DECOR</h1>
            <p class="text-[10px] text-gray-400 mt-1 uppercase tracking-widest">Seller Portal</p>
        </div>

        <nav class="flex-1 px-4 space-y-1 overflow-y-auto">
            <a href="{{ route('seller.dashboard') }}" class="flex items-center px-4 py-3 text-xs font-bold transition-all rounded-lg {{ Request::routeIs('seller.dashboard') ? 'active-link' : 'text-gray-400 hover:text-primary hover:bg-gray-50' }}">
                <i class="fa-solid fa-table-columns mr-3 w-5 text-center"></i> Dashboard
            </a>
            <a href="{{ route('seller.products.index') }}" class="flex items-center px-4 py-3 text-xs font-bold transition-all rounded-lg {{ Request::routeIs('seller.products.*') || Request::is('*product*') ? 'active-link' : 'text-gray-400 hover:text-primary hover:bg-gray-50' }}">
                <i class="fa-solid fa-couch mr-3 w-5 text-center"></i> Kelola Produk
            </a>
            <a href="{{ route('seller.orders') ?? route('seller.orders') }}" class="flex items-center px-4 py-3 text-xs font-bold transition-all rounded-lg {{ Request::is('*order*') ? 'active-link' : 'text-gray-400 hover:text-primary hover:bg-gray-50' }}">
                <i class="fa-solid fa-bag-shopping mr-3 w-5 text-center"></i> Daftar Pesanan
            </a>
            <a href="{{ route('seller.chats') }}" class="flex items-center px-4 py-3 text-xs font-bold transition-all rounded-lg {{ Request::is('*chat*') ? 'active-link' : 'text-gray-400 hover:text-primary hover:bg-gray-50' }}">
                <i class="fa-solid fa-message mr-3 w-5 text-center"></i> Seller Chat
            </a>
            <a href="{{ route('seller.complaint.index') }}" class="flex items-center px-4 py-3 text-xs font-bold transition-all rounded-lg {{ Request::is('*complaint*') ? 'active-link' : 'text-gray-400 hover:text-primary hover:bg-gray-50' }}">
                <i class="fa-solid fa-circle-exclamation mr-3 w-5 text-center"></i> Komplain
            </a>
            <a href="{{ route('seller.reviews') }}" class="flex items-center px-4 py-3 text-xs font-bold transition-all rounded-lg {{ Request::is('*review*') ? 'active-link' : 'text-gray-400 hover:text-primary hover:bg-gray-50' }}">
                <i class="fa-solid fa-star mr-3 w-5 text-center"></i> Review & Rating
            </a>
            <a href="{{ route('seller.reports') }}" class="flex items-center px-4 py-3 text-xs font-bold transition-all rounded-lg {{ Request::is('*report*') ? 'active-link' : 'text-gray-400 hover:text-primary hover:bg-gray-50' }}">
                <i class="fa-solid fa-chart-line mr-3 w-5 text-center"></i> Laporan
            </a>
        </nav>

        <div class="p-4 border-t border-gray-100 space-y-1 bg-white">
            <a href="{{ route('seller.settings') }}" class="flex items-center px-4 py-3 text-xs font-bold transition-all rounded-lg {{ Request::is('*setting*') ? 'active-link' : 'text-gray-400 hover:text-primary hover:bg-gray-50' }}">
                <i class="fa-solid fa-gear mr-3 w-5 text-center"></i> Settings
            </a>
            <a href="{{ route('seller.support') }}" class="flex items-center px-4 py-3 text-xs font-bold transition-all rounded-lg {{ Request::is('*support*') ? 'active-link' : 'text-gray-400 hover:text-primary hover:bg-gray-50' }}">
                <i class="fa-solid fa-headset mr-3 w-5 text-center"></i> Support
            </a>
            <div class="pt-2 mt-2 border-t border-gray-50">
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="flex items-center w-full px-4 py-3 text-xs font-bold text-red-400 hover:text-red-500 hover:bg-red-50 transition-all rounded-lg">
                        <i class="fa-solid fa-arrow-right-from-bracket mr-3 w-5 text-center"></i> Logout
                    </button>
                </form>
            </div>
        </div>
    </aside>

    <main id="main-content" class="flex-1 flex flex-col ml-64 sidebar-transition min-h-screen">
        
        <header class="h-16 bg-primary flex items-center justify-between px-8 sticky top-0 z-40">
            <div class="flex items-center">
                <button id="toggle-sidebar" class="text-white hover:opacity-80 mr-4 transition-transform active:scale-95">
                    <i class="fa-solid fa-bars-staggered text-xl"></i>
                </button>
                <!-- Menampilkan ID Pesanan Dinamis -->
                <h2 class="font-bold text-xs uppercase tracking-widest text-white leading-none">Order Details #DEC-{{ $order->id }}</h2>
            </div>
            <div class="flex items-center space-x-6 text-white text-[10px] font-bold uppercase tracking-widest">
                <a href="{{ route('seller.orders') ?? route('seller.orders') }}" class="hover:opacity-70 transition-colors">Back to List</a>
            </div>
        </header>

        <div class="p-8 grid grid-cols-1 md:grid-cols-12 gap-8 flex-1 pb-32">
            
            <div class="md:col-span-8 space-y-8">
                
                <!-- CUSTOMER DETAILS -->
                <div class="bg-white p-10 rounded-[32px] border border-gray-100 shadow-sm flex justify-between items-center">
                    <div>
                        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Customer Details</p>
                        <h2 class="text-3xl font-black text-gray-800 mt-2">{{ $order->customer->user->full_name }}</h2>
                        <p class="text-sm font-medium text-gray-400">{{ $order->customer->phone ?? 'Nomor HP tidak tersedia' }}</p>
                    </div>
                    <div class="text-right flex flex-col items-end">
                        <img src="https://ui-avatars.com/api/?name={{ urlencode($order->customer->user->full_name) }}&background=f3f4f6&color=B5733A" class="w-12 h-12 rounded-full mb-3">
                        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Member Since</p>
                        <p class="text-sm font-black text-gray-800 mt-1">{{ $order->customer->user->created_at->format('M Y') }}</p>
                    </div>
                </div>

                <!-- ITEMS PURCHASED -->
                <div class="bg-white p-10 rounded-[32px] border border-gray-100 shadow-sm space-y-6">
                    <h3 class="text-xl font-black text-gray-800 border-b border-gray-50 pb-4 flex items-center justify-between">
                        <span><i class="fa-solid fa-box-open mr-3 text-primary"></i> Items Purchased</span>
                        <!-- Status Badge -->
                        @php
                            $badgeClass = match($order->status) {
                                'pending' => 'bg-yellow-100 text-yellow-600',
                                'paid' => 'bg-blue-100 text-blue-600',
                                'shipped' => 'bg-indigo-100 text-indigo-600',
                                'completed' => 'bg-green-100 text-green-600',
                                'cancelled' => 'bg-red-100 text-red-600',
                                default => 'bg-gray-100 text-gray-600',
                            };
                        @endphp
                        <span class="{{ $badgeClass }} text-[10px] px-3 py-1 rounded-full uppercase tracking-widest font-black">{{ $order->status }}</span>
                    </h3>
                    
                    <!-- Looping Produk yang dibeli -->
                    <div class="space-y-6">
                        @foreach($order->orderItems as $item)
                        <div class="flex items-center justify-between group border-b border-gray-50 pb-6 last:border-0 last:pb-0">
                            <div class="flex items-center space-x-6">
                                <img src="{{ $item->product->images->first()->img_url ?? 'https://via.placeholder.com/200' }}" class="w-20 h-20 rounded-2xl object-cover grayscale group-hover:grayscale-0 transition-all border border-gray-100">
                                <div>
                                    <h4 class="text-lg font-bold">{{ $item->product->name }}</h4>
                                    <p class="text-xs text-gray-400 font-bold uppercase tracking-widest mt-1">SKU: PROD-{{ $item->product_id }}</p>
                                </div>
                            </div>
                            <div class="text-right">
                                <p class="text-xs text-gray-400 font-bold">{{ $item->quantity }} x Rp {{ number_format($item->price, 0, ',', '.') }}</p>
                                <p class="text-lg font-black text-primary mt-1">Rp {{ number_format($item->price * $item->quantity, 0, ',', '.') }}</p>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>

                <!-- SHIPPING & PAYMENT INFO -->
                <div class="bg-white p-10 rounded-[32px] border border-gray-100 shadow-sm grid grid-cols-2 gap-10">
                    <div class="space-y-4">
                        <h3 class="text-[10px] font-bold text-gray-400 uppercase tracking-[0.2em]">Shipping Information</h3>
                        <div class="flex space-x-3 italic">
                            <i class="fa-solid fa-truck-fast text-primary mt-1"></i>
                            <div class="text-sm font-medium">
                                <p class="font-bold uppercase tracking-tighter">{{ $order->shipping_courier ?? 'Standard Delivery' }}</p>
                                <p class="text-gray-400 mt-2 leading-relaxed">
                                    {{ $order->customer->address ?? 'Alamat belum diatur' }}<br>
                                    {{ $order->customer->city ?? 'Indonesia' }}
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="space-y-4 border-l border-gray-50 pl-10">
                        <h3 class="text-[10px] font-bold text-gray-400 uppercase tracking-[0.2em]">Payment Method</h3>
                        <div class="flex space-x-3 italic">
                            <i class="fa-solid fa-credit-card text-primary mt-1"></i>
                            <div class="text-sm font-medium">
                                <p class="font-bold uppercase tracking-tighter">{{ str_replace('_', ' ', $order->payment_method) }}</p>
                                @if(in_array($order->status, ['paid', 'shipped', 'completed']))
                                    <p class="text-green-500 font-bold mt-1 uppercase text-[10px] tracking-widest"><i class="fa-solid fa-check mr-1"></i> PAID SUCCESSFUL</p>
                                @else
                                    <p class="text-yellow-500 font-bold mt-1 uppercase text-[10px] tracking-widest">PENDING PAYMENT</p>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- PAYMENT SUMMARY & ACTION BUTTONS -->
            <div class="md:col-span-4 space-y-8">
                
                <div class="bg-[#1a1a1a] p-10 rounded-[32px] text-white shadow-2xl">
                    <h3 class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-6">Payment Summary</h3>
                    <div class="space-y-4 border-b border-white/10 pb-6 mb-6 text-xs font-medium">
                        <div class="flex justify-between">
                            <span class="opacity-60 italic">Subtotal</span>
                            <span>Rp {{ number_format($order->total_price, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="opacity-60 italic">Shipping Fee</span>
                            <span>Rp 0</span> <!-- Bisa diganti dinamis kalau ada kolom ongkir di DB -->
                        </div>
                        <div class="flex justify-between text-primary">
                            <span class="font-bold italic">Promo Discount</span>
                            <span class="font-bold">- Rp 0</span>
                        </div>
                    </div>
                    <div class="flex justify-between items-end">
                        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-[0.2em] leading-none">Grand Total</p>
                        <p class="text-3xl font-black text-white leading-none">Rp {{ number_format($order->total_price, 0, ',', '.') }}</p>
                    </div>
                </div>

                <div class="bg-white p-10 rounded-[32px] border border-gray-100 shadow-sm space-y-4 text-center">
                    <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Need to Print?</p>
                    <div class="flex flex-col space-y-2">
                        <!-- Link ke Invoice dan Label Pengiriman -->
                        <a href="{{ route('seller.orders.invoice', $order->id) }}" target="_blank" class="bg-primary text-white py-3 rounded-xl text-[10px] font-black uppercase tracking-widest shadow-lg shadow-primary/20 hover:opacity-90 transition-all">
                            Print Invoice
                        </a>
                        <a href="{{ route('seller.orders.label', $order->id) }}" target="_blank" class="border border-gray-200 text-gray-700 py-3 rounded-xl text-[10px] font-black uppercase tracking-widest hover:bg-gray-50 transition-all">
                            Print Shipping Label
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <footer class="px-8 py-6 border-t border-gray-100 text-[10px] font-bold text-gray-400 uppercase tracking-widest text-left mt-auto">
            © 2026 DECOR MARKETPLACE SELLER PORTAL. ALL RIGHTS RESERVED.
        </footer>
    </main>

    <script>
        const btn = document.getElementById('toggle-sidebar');
        const sidebar = document.getElementById('sidebar');
        const main = document.getElementById('main-content');
        btn.addEventListener('click', () => { 
            sidebar.classList.toggle('sidebar-hidden'); 
            main.classList.toggle('main-expanded'); 
        });
    </script>
</body>
</html>