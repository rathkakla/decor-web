<?php $site_name = "DECOR"; ?>
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
    @include('customer.partials.navbar')

    <main class="flex-grow flex content-container w-full bg-white">
        <!-- SIDEBAR (TETAP SAMA) -->
        @include('customer.partials.sidebar')

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
                                @if($order->productReturn)
                                    @if($order->productReturn->status == 'approved')
                                        <span class="text-green-600">Returned</span>
                                    @elseif($order->productReturn->status == 'rejected')
                                        <span class="text-red-500">Return Rejected</span>
                                    @else
                                        <span class="text-yellow-600">Return Pending</span>
                                    @endif
                                @else
                                    {{ $order->status == 'shipped' ? 'In Transit' : ($order->status == 'completed' ? 'Delivered' : $order->status) }}
                                @endif
                            </span>
                            <h2 class="text-xl font-bold text-gray-900 mt-1">Order @if($order->return_code) #DEC-{{ str_replace('RET-', '', $order->return_code) }}-RET @else #DEC-{{ $order->id }} @endif</h2>
                            <p class="text-xs text-gray-500 mt-1 font-medium">
                                Ordered on {{ $order->created_at->format('M d, Y') }} <span class="mx-2">&bull;</span> Total: <span class="font-bold text-gray-900">Rp {{ number_format($order->total_price, 0, ',', '.') }}</span>
                            </p>
                        </div>
                        
                        <!-- TOMBOL AKSI (TRACKING / RETURN) -->
<div class="flex gap-3 items-center">
    <a href="{{ route('invoice.download', $order->id) }}" target="_blank" class="border border-[#B5733A] text-[#B5733A] bg-amber-50/30 px-5 py-2.5 rounded-lg text-xs font-bold tracking-wide hover:bg-amber-50 transition-colors shadow-sm inline-flex items-center">
        <i class="fa-solid fa-file-invoice mr-2"></i> Invoice
    </a>
    @if($order->status == 'pending')
        <div class="flex items-center gap-3">
            <span class="text-[11px] font-bold text-red-500 countdown-timer" data-expires="{{ $order->created_at->copy()->addDay()->toIso8601String() }}">
                Menghitung...
            </span>
            <a href="{{ route('customer.payment', $order->id) }}" class="bg-primary text-white px-5 py-2.5 rounded-lg text-xs font-bold tracking-wide hover:bg-opacity-90 transition-colors shadow-sm inline-block">
                Pay Now
            </a>
            <button class="border border-gray-200 text-gray-500 px-5 py-2.5 rounded-lg text-xs font-bold tracking-wide hover:bg-gray-50 transition-colors shadow-sm">
                Cancel
            </button>
        </div>
    @elseif($order->status == 'shipped')
        <div class="flex gap-2">
            <button class="bg-[#1a1a1a] text-white px-5 py-2.5 rounded-lg text-xs font-bold tracking-wide hover:bg-black transition-colors shadow-sm">
                Track Live Location
            </button>
            <form action="{{ route('customer.orders.complete', $order->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin pesanan sudah diterima dengan baik?')">
                @csrf
                <button type="submit" class="bg-green-600 text-white px-5 py-2.5 rounded-lg text-xs font-bold tracking-wide hover:bg-green-700 transition-colors shadow-sm">
                    Pesanan Diterima
                </button>
            </form>
        </div>
    @elseif($order->status == 'completed')
        @if(!$order->productReturn)
            <a href="{{ route('customer.return-request', ['order_id' => $order->id]) }}" class="bg-primary text-white px-5 py-2.5 rounded-lg text-xs font-bold tracking-wide hover:bg-opacity-90 transition-colors shadow-sm inline-block">
                Return Request
            </a>
        @else
            <a href="{{ route('customer.return-request') }}" class="border border-primary text-primary px-5 py-2.5 rounded-lg text-xs font-bold tracking-wide hover:bg-primary hover:text-white transition-colors shadow-sm inline-block">
                View Return Status
            </a>
        @endif
    @else
        <button class="border border-gray-200 text-gray-500 px-5 py-2.5 rounded-lg text-xs font-bold tracking-wide hover:bg-gray-50 transition-colors shadow-sm">
            Cancel Order
        </button>
    @endif
</div>
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
                                        
                                        @if($order->status == 'completed')
                                        <div class="mt-2 flex gap-2">
                                            <a href="{{ route('customer.review', $item->product_id) }}" class="inline-block text-[10px] font-bold text-primary uppercase tracking-widest border border-primary px-3 py-1 rounded hover:bg-primary hover:text-white transition-colors">
                                                Write a Review
                                            </a>
                                            @if(!$order->productReturn)
                                                <a href="{{ route('customer.return-request', $order->id) }}" class="inline-block text-[10px] font-bold text-gray-500 uppercase tracking-widest border border-gray-300 px-3 py-1 rounded hover:bg-gray-100 transition-colors">
                                                    Return Request
                                                </a>
                                            @endif
                                        </div>
                                        @endif
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

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const countdownElements = document.querySelectorAll('.countdown-timer');
            
            setInterval(() => {
                const now = new Date().getTime();
                
                countdownElements.forEach(el => {
                    const expiresStr = el.getAttribute('data-expires');
                    if (!expiresStr) return;
                    
                    const expires = new Date(expiresStr).getTime();
                    const distance = expires - now;
                    
                    if (distance < 0) {
                        el.innerHTML = '<i class="fa-solid fa-circle-exclamation mr-1"></i> Expired';
                        return;
                    }
                    
                    const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                    const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                    const seconds = Math.floor((distance % (1000 * 60)) / 1000);
                    
                    el.innerHTML = `<i class="fa-regular fa-clock mr-1"></i> ${hours.toString().padStart(2, '0')}:${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')}`;
                });
            }, 1000);
        });
    </script>
    <!-- REVIEW MODAL -->
    @if(session('show_review_modal') && session('completed_order_id'))
        @php
            $completedOrder = \App\Models\Order::with('orderItems.product.images')->find(session('completed_order_id'));
        @endphp
        @if($completedOrder)
            <div id="reviewModal" class="fixed inset-0 z-50 flex items-center justify-center hidden">
                <!-- Overlay with backdrop blur -->
                <div class="absolute inset-0 bg-black/60 backdrop-blur-sm transition-opacity duration-300" onclick="closeReviewModal()"></div>
                
                <!-- Modal container -->
                <div class="relative bg-white rounded-2xl w-full max-w-lg mx-4 p-8 shadow-2xl transition-all transform scale-95 opacity-0 duration-300 z-10 border border-gray-100" id="reviewModalContainer">
                    <button onclick="closeReviewModal()" class="absolute top-4 right-4 text-gray-400 hover:text-gray-600 transition-colors">
                        <i class="fa-solid fa-xmark text-lg"></i>
                    </button>

                    <div class="text-center mb-6">
                        <div class="w-16 h-16 bg-amber-50 rounded-full flex items-center justify-center mx-auto mb-4 border border-amber-100">
                            <i class="fa-solid fa-star text-2xl text-primary animate-pulse"></i>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 tracking-tight">Bagaimana Kualitas Produk Kami?</h3>
                        <p class="text-xs text-gray-500 mt-1">Ulasan Anda sangat berharga untuk penjual & pelanggan lain.</p>
                    </div>

                    <form action="{{ route('customer.orders.submit-reviews') }}" method="POST" class="space-y-6">
                        @csrf
                        <input type="hidden" name="order_id" value="{{ $completedOrder->id }}">
                        
                        <div class="max-h-[320px] overflow-y-auto pr-2 space-y-6">
                            @foreach($completedOrder->orderItems as $index => $item)
                                <div class="border-b border-gray-100 pb-5 last:border-0 last:pb-0">
                                    <div class="flex items-center gap-4 mb-4">
                                        <img src="{{ $item->product->images->first()->img_url ?? 'https://via.placeholder.com/200' }}" class="w-14 h-14 object-cover rounded-xl border border-gray-100 shadow-sm shrink-0">
                                        <div class="flex-grow">
                                            <h4 class="font-bold text-xs text-gray-900 line-clamp-1">{{ $item->product->name }}</h4>
                                            <p class="text-[10px] text-gray-400 mt-0.5">Rp {{ number_format($item->price, 0, ',', '.') }}</p>
                                        </div>
                                    </div>

                                    <!-- Rating inputs & Star selector -->
                                    <input type="hidden" name="reviews[{{ $index }}][product_id]" value="{{ $item->product_id }}">
                                    <input type="hidden" name="reviews[{{ $index }}][rating]" id="rating-{{ $index }}" value="">

                                    <div class="flex flex-col items-center mb-4" id="stars-container-{{ $index }}">
                                        <div class="flex gap-2" id="stars-wrapper-{{ $index }}">
                                            @for($i = 1; $i <= 5; $i++)
                                                <i class="fa-solid fa-star text-3xl cursor-pointer text-gray-200 hover:scale-110 hover:text-primary transition-all duration-100" id="star-{{ $index }}-{{ $i }}" onclick="setStarRating({{ $index }}, {{ $i }})"></i>
                                            @endfor
                                        </div>
                                        <p class="text-[10px] text-gray-400 mt-2 font-medium" id="rating-text-{{ $index }}">Sentuh bintang untuk menilai</p>
                                    </div>

                                    <!-- Comment Textarea -->
                                    <textarea name="reviews[{{ $index }}][comment]" placeholder="Tulis komentar ulasan Anda (opsional)..." class="w-full bg-gray-50 border border-gray-100 rounded-xl p-3 text-xs outline-none focus:border-primary focus:bg-white transition-all resize-none h-20 placeholder:text-gray-400"></textarea>
                                </div>
                            @endforeach
                        </div>

                        <!-- Footer Actions -->
                        <div class="flex flex-col gap-2 border-t border-gray-100 pt-4 mt-4">
                            <div class="flex items-center justify-between gap-3">
                                <button type="button" onclick="closeReviewModal()" class="px-5 py-3 text-[10px] font-bold text-gray-400 hover:text-gray-600 transition-colors uppercase tracking-wider">
                                    Ulas Nanti
                                </button>
                                <button type="submit" id="submitReviewsBtn" disabled class="flex-grow py-3 bg-primary text-white text-[10px] font-bold rounded-xl shadow-lg shadow-primary/20 hover:opacity-90 transition-all disabled:opacity-40 disabled:cursor-not-allowed uppercase tracking-wider text-center">
                                    Kirim Ulasan
                                </button>
                            </div>
                            <a href="{{ route('customer.review', $completedOrder->orderItems->first()->product_id) }}" class="w-full py-2.5 border border-primary text-primary text-[10px] font-bold rounded-xl hover:bg-primary hover:text-white transition-all uppercase tracking-wider text-center block">
                                Ulas Selengkapnya di Halaman Review
                            </a>
                        </div>
                    </form>
                </div>
            </div>

            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    showReviewModal();
                });

                function showReviewModal() {
                    const modal = document.getElementById('reviewModal');
                    const container = document.getElementById('reviewModalContainer');
                    if (modal && container) {
                        modal.classList.remove('hidden');
                        setTimeout(() => {
                            container.classList.remove('scale-95', 'opacity-0');
                            container.classList.add('scale-100', 'opacity-100');
                        }, 50);
                    }
                }

                function closeReviewModal() {
                    const modal = document.getElementById('reviewModal');
                    const container = document.getElementById('reviewModalContainer');
                    if (modal && container) {
                        container.classList.remove('scale-100', 'opacity-100');
                        container.classList.add('scale-95', 'opacity-0');
                        setTimeout(() => {
                            modal.classList.add('hidden');
                        }, 300);
                    }
                }

                function setStarRating(index, rating) {
                    const ratingInput = document.getElementById(`rating-${index}`);
                    ratingInput.value = rating;

                    const ratingText = document.getElementById(`rating-text-${index}`);
                    const labels = {
                        1: "Sangat Buruk 😠",
                        2: "Buruk 😞",
                        3: "Cukup 😐",
                        4: "Baik 😊",
                        5: "Sangat Baik 😍"
                    };
                    ratingText.innerText = labels[rating] || "";
                    ratingText.classList.remove('text-gray-400');
                    ratingText.classList.add('text-primary', 'font-bold');

                    updateStarsVisual(index, rating);
                    validateForm();
                }

                function updateStarsVisual(index, rating) {
                    for (let i = 1; i <= 5; i++) {
                        const star = document.getElementById(`star-${index}-${i}`);
                        if (i <= rating) {
                            star.classList.remove('text-gray-200');
                            star.classList.add('text-primary');
                        } else {
                            star.classList.remove('text-primary');
                            star.classList.add('text-gray-200');
                        }
                    }
                }

                function validateForm() {
                    const ratingInputs = document.querySelectorAll('input[id^="rating-"]');
                    let allRated = true;
                    ratingInputs.forEach(input => {
                        if (!input.value) {
                            allRated = false;
                        }
                    });

                    const submitBtn = document.getElementById('submitReviewsBtn');
                    if (submitBtn) {
                        submitBtn.disabled = !allRated;
                    }
                }
            </script>
        @endif
    @endif
</body>
</html>