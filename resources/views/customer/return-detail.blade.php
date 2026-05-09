@php
    $site_name = "DECOR"; 
    $user = Auth::user(); 
@endphp
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Return Details #{{ $return->id }} — {{ $site_name }}</title>
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

    <header class="bg-white border-b border-gray-100 sticky top-0 z-50">
        <div class="content-container flex justify-between items-center py-4 px-6 mx-auto max-w-[1200px]">
            <div class="flex items-center space-x-8 flex-1">
                <a href="{{ route('customer.homepage') }}" class="text-2xl font-black tracking-tighter uppercase text-primary hover:opacity-80 transition-all">
                    {{ $site_name }}
                </a>
            </div>

            <nav class="hidden md:flex items-center space-x-10 text-[13px] font-medium text-gray-500 tracking-wide">
                <a href="{{ route('customer.catalog') }}" class="hover:text-primary transition-all">Collections</a>
                <a href="{{ route('customer.designers') }}" class="hover:text-primary transition-all">Designers</a>
                <a href="{{ route('customer.design-lab') }}" class="hover:text-primary transition-all">AI Studio</a>
            </nav>

            <div class="flex items-center space-x-6 flex-1 justify-end">
                <a href="{{ route('customer.cart') }}" class="text-primary">
                    <i class="fa-solid fa-bag-shopping text-lg"></i>
                </a>
                <div class="w-9 h-9 rounded-md overflow-hidden border border-gray-200">
                    <a href="{{ route('customer.profile') }}" class="block w-full h-full">
                        <img src="https://api.dicebear.com/7.x/avataaars/svg?seed={{ urlencode($user->username) }}" alt="Profile" class="w-full h-full object-cover bg-slate-100">
                    </a>
                </div>
            </div>
        </div>
    </header>

    <main class="flex-grow flex content-container w-full bg-white">
        <!-- SIDEBAR -->
        <aside class="w-72 border-r border-gray-50 p-10 bg-gray-50/20">
            <div class="text-center mb-10">
                <img src="https://api.dicebear.com/7.x/avataaars/svg?seed={{ urlencode($user->username) }}" class="w-20 h-20 rounded-2xl mx-auto mb-4 bg-white shadow-sm border border-gray-100">
                <h3 class="font-bold text-lg">{{ $user->full_name }}</h3>
                <p class="text-[9px] text-gray-400 uppercase tracking-widest mt-1">Member since {{ $user->created_at->format('Y') }}</p>
            </div>

            <nav class="space-y-1">
                <a href="{{ route('customer.profile') }}" class="flex items-center space-x-4 px-4 py-3 text-gray-400 hover:text-primary transition-colors">
                    <i class="fa-regular fa-user text-xs"></i> <span class="text-[11px] uppercase tracking-widest">Profile</span>
                </a>
                <a href="{{ route('customer.orders') }}" class="flex items-center space-x-4 px-4 py-3 text-gray-400 hover:text-primary transition-colors">
                    <i class="fa-solid fa-box-archive text-xs"></i> <span class="text-[11px] uppercase tracking-widest">Orders</span>
                </a>
                <a href="{{ route('customer.return-request') }}" class="flex items-center space-x-4 px-4 py-3 bg-white text-primary font-bold rounded-xl shadow-sm border border-gray-100">
                    <i class="fa-solid fa-rotate-left text-xs"></i> <span class="text-[11px] uppercase tracking-widest">Returns</span>
                </a>
                <a href="{{ route('customer.product-favorite') }}" class="flex items-center space-x-4 px-4 py-3 text-gray-400 hover:text-primary transition-colors">
                    <i class="fa-regular fa-heart text-xs"></i> <span class="text-[11px] uppercase tracking-widest">Product Favorite</span>
                </a>
                <div class="pt-6 mt-6 border-t border-gray-100">
                    <p class="px-4 text-[9px] font-black text-gray-300 uppercase tracking-widest mb-2">Chat History</p>
                    
                    <a href="{{ route('customer.riwayat-chat') }}" class="flex items-center space-x-4 px-4 py-3 rounded-xl text-gray-400 hover:text-primary">
                        <i class="fa-solid fa-wand-magic-sparkles text-xs"></i> 
                        <span class="text-[11px] uppercase tracking-widest">Designer</span>
                    </a>

                    <a href="{{ route('customer.riwayat-chat') }}" class="flex items-center space-x-4 px-4 py-3 rounded-xl text-gray-400 hover:text-primary">
                        <i class="fa-solid fa-shop text-xs"></i> 
                        <span class="text-[11px] uppercase tracking-widest">Seller</span>
                    </a>
                </div>
            </nav>
        </aside>

        <!-- CONTENT -->
        <section class="flex-1 p-10 bg-white">
            <div class="max-w-4xl mx-auto">
                <!-- Header: Kembali & Judul -->
                <div class="flex items-center justify-between mb-8">
                    <div class="flex items-center gap-4">
                        <a href="{{ route('customer.return-request') }}" class="w-10 h-10 bg-white border border-gray-100 rounded-xl flex items-center justify-center text-gray-400 hover:text-primary hover:border-primary transition-all">
                            <i class="fa-solid fa-arrow-left"></i>
                        </a>
                        <div>
                            <h2 class="text-2xl font-bold text-gray-900">Return Details</h2>
                            <p class="text-xs text-gray-400 mt-0.5 font-medium uppercase tracking-widest">Case ID: #RET-{{ $return->id }}</p>
                        </div>
                    </div>
                    
                    <!-- Status Badge -->
                    <div class="px-6 py-3 rounded-2xl flex items-center gap-3
                        @if($return->status == 'pending') bg-yellow-50 text-yellow-600 border border-yellow-100
                        @elseif($return->status == 'approved') bg-green-50 text-green-600 border border-green-100
                        @elseif($return->status == 'rejected') bg-red-50 text-red-600 border border-red-100
                        @endif">
                        <div class="w-2.5 h-2.5 rounded-full animate-pulse
                            @if($return->status == 'pending') bg-yellow-500
                            @elseif($return->status == 'approved') bg-green-500
                            @elseif($return->status == 'rejected') bg-red-500
                            @endif"></div>
                        <span class="text-xs font-black uppercase tracking-widest">{{ $return->status }}</span>
                    </div>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                    <!-- Kolom Kiri -->
                    <div class="lg:col-span-2 space-y-8">
                        <!-- Informasi Produk -->
                        <div class="bg-white rounded-3xl border border-gray-100 overflow-hidden shadow-sm">
                            <div class="p-6 border-b border-gray-50 bg-gray-50/30">
                                <h3 class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Returned Items</h3>
                            </div>
                            <div class="p-6">
                                @foreach($return->order->orderItems as $item)
                                <div class="flex items-center gap-6">
                                    <div class="w-24 h-24 bg-gray-50 rounded-2xl overflow-hidden border border-gray-100 shrink-0">
                                        <img src="{{ $item->product->images->first()->img_url ?? 'https://via.placeholder.com/200' }}" class="w-full h-full object-cover">
                                    </div>
                                    <div class="flex-grow">
                                        <h4 class="font-bold text-lg text-gray-900">{{ $item->product->name }}</h4>
                                        <p class="text-xs text-gray-400 mt-1">Qty: <span class="text-gray-900 font-bold">{{ $item->quantity }}</span></p>
                                        <p class="text-primary font-bold mt-2">Rp {{ number_format($item->price, 0, ',', '.') }}</p>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>

                        <!-- Detail Pengajuan -->
                        <div class="bg-white rounded-3xl border border-gray-100 overflow-hidden shadow-sm">
                            <div class="p-6 border-b border-gray-50">
                                <h3 class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Reason & Evidence</h3>
                            </div>
                            <div class="p-8 space-y-8">
                                <div>
                                    <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest block mb-2">Main Reason</label>
                                    <div class="p-4 bg-gray-50 rounded-xl border border-gray-100 font-bold text-gray-800">
                                        {{ $return->reason }}
                                    </div>
                                </div>

                                @if($return->notes)
                                <div>
                                    <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest block mb-2">Detailed Problem</label>
                                    <p class="text-sm text-gray-600 leading-relaxed italic">"{{ $return->notes }}"</p>
                                </div>
                                @endif
                            </div>
                        </div>

                        <!-- Refund Summary -->
                        <div class="bg-white rounded-3xl border border-gray-100 overflow-hidden shadow-sm">
                            <div class="p-6 border-b border-gray-50">
                                <h3 class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Refund Summary</h3>
                            </div>
                            <div class="p-8 flex items-center justify-between">
                                <div>
                                    <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Total Refund Estimate</p>
                                    <h2 class="text-3xl font-black text-primary mt-1">Rp {{ number_format($return->order->total_price, 0, ',', '.') }}</h2>
                                </div>
                                <div class="text-right">
                                    <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Refund Method</p>
                                    <p class="text-sm font-bold text-gray-800 mt-1 uppercase tracking-wider">{{ $return->order->payment_method == 'bank_transfer' ? 'Bank Transfer' : 'Balance' }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Kolom Kanan -->
                    <div class="space-y-8">
                        <div class="bg-white rounded-3xl border border-gray-100 p-8 shadow-sm">
                            <h3 class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-8">Resolution Timeline</h3>
                            <div class="relative space-y-8 pl-8">
                                <div class="absolute left-[11px] top-2 bottom-2 w-[2px] bg-gray-100"></div>
                                <div class="relative">
                                    <div class="absolute -left-[27px] top-0 w-4 h-4 rounded-full bg-primary ring-4 ring-white shadow-sm flex items-center justify-center">
                                        <i class="fa-solid fa-check text-[8px] text-white"></i>
                                    </div>
                                    <h4 class="text-sm font-bold text-gray-900">Request Submitted</h4>
                                    <p class="text-[10px] text-gray-400 mt-1">{{ \Carbon\Carbon::parse($return->return_date)->format('d M Y, h:i A') }}</p>
                                </div>

                                @if($return->status != 'pending')
                                <div class="relative">
                                    <div class="absolute -left-[27px] top-0 w-4 h-4 rounded-full 
                                        {{ $return->status == 'approved' ? 'bg-green-500' : 'bg-red-500' }} ring-4 ring-white shadow-sm flex items-center justify-center">
                                        <i class="fa-solid fa-{{ $return->status == 'approved' ? 'check' : 'xmark' }} text-[8px] text-white"></i>
                                    </div>
                                    <h4 class="text-sm font-bold text-gray-900">{{ ucfirst($return->status) }} by Seller</h4>
                                    <p class="text-[10px] text-gray-400 mt-1">{{ $return->updated_at->format('d M Y, h:i A') }}</p>
                                </div>
                                @endif
                            </div>
                        </div>

                        <!-- Buttons -->
                        <div class="space-y-4">
                            <a href="#" class="w-full py-4 bg-white border border-gray-100 flex items-center justify-center gap-3 text-[10px] font-black uppercase tracking-widest text-gray-900 rounded-2xl hover:bg-gray-50 transition-all shadow-sm">
                                <i class="fa-solid fa-comment-dots text-primary text-sm"></i>
                                Chat Seller
                            </a>
                            <a href="#" class="block text-center text-[10px] font-black uppercase tracking-widest text-gray-400 hover:text-primary transition-all">
                                Return Policy Help Center
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>

    <footer class="bg-white border-t border-gray-50 py-10">
        <div class="content-container px-6 text-center">
            <p class="text-gray-400 text-[10px] font-bold uppercase tracking-[0.2em]">&copy; 2026 DECOR Premium Furniture. All Rights Reserved.</p>
        </div>
    </footer>

</body>
</html>