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
    @include('seller.partials.sidebar')

    <main id="main-content" class="flex-1 flex flex-col ml-64 sidebar-transition min-h-screen">
        
        @php
            $extraAction = '<a href="'.route('seller.orders').'" class="hover:opacity-70 transition-colors text-[10px] font-bold uppercase tracking-widest">Back to List</a>';
        @endphp
        @include('seller.partials.header', ['title' => 'Order Details #DEC-'.$order->id, 'extra_action' => $extraAction])

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
                                'waiting_verification' => 'bg-orange-100 text-orange-600',
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
                                @if($order->status == 'waiting_verification')
                                    <p class="text-orange-500 font-bold mt-1 uppercase text-[10px] tracking-widest"><i class="fa-solid fa-clock mr-1"></i> WAITING VERIFICATION</p>
                                @elseif(in_array($order->status, ['paid', 'shipped', 'completed']))
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

                @if($order->payment_proof)
                <div class="bg-white p-10 rounded-[32px] border border-gray-100 shadow-sm space-y-6">
                    <h3 class="text-xs font-bold text-gray-400 uppercase tracking-widest">Bukti Pembayaran</h3>
                    <div class="rounded-2xl overflow-hidden border border-gray-100">
                        <img src="{{ asset('storage/' . $order->payment_proof) }}" class="w-full h-auto cursor-pointer hover:scale-105 transition-transform" onclick="window.open(this.src)">
                    </div>
                    
                    @if($order->status == 'waiting_verification')
                    <div class="grid grid-cols-2 gap-4 pt-4">
                        <form action="{{ route('seller.orders.validate-payment', $order->id) }}" method="POST">
                            @csrf
                            <input type="hidden" name="action" value="approve">
                            <button type="submit" class="w-full bg-green-500 text-white py-3 rounded-xl text-[10px] font-black uppercase tracking-widest shadow-lg shadow-green-500/20 hover:opacity-90 transition-all">
                                Setujui
                            </button>
                        </form>
                        <form action="{{ route('seller.orders.validate-payment', $order->id) }}" method="POST">
                            @csrf
                            <input type="hidden" name="action" value="reject">
                            <button type="submit" class="w-full bg-red-500 text-white py-3 rounded-xl text-[10px] font-black uppercase tracking-widest shadow-lg shadow-red-500/20 hover:opacity-90 transition-all">
                                Tolak
                            </button>
                        </form>
                    </div>
                    @endif
                </div>
                @endif

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