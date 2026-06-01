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

    @include('customer.partials.navbar')

   <main class="py-16 content-container px-6">
        <form action="{{ route('customer.place-order') }}" method="POST">
            @csrf
            
            <div class="grid grid-cols-1 lg:grid-cols-[1fr_400px] gap-20">
                
                <div class="space-y-12">
                    <section>
                        <div class="flex justify-between items-center mb-6">
                            <h2 class="text-2xl font-bold tracking-tight">Shipping Destination</h2>
                            <a href="{{ route('customer.profile') }}" class="text-[10px] font-bold uppercase tracking-widest text-primary border-b border-primary hover:opacity-80">Manage Addresses</a>
                        </div>
                        
                        @if($customer->addresses->count() > 0)
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                @foreach($customer->addresses as $address)
                                <label class="block cursor-pointer relative">
                                    <input type="radio" name="address_id" value="{{ $address->id }}" class="peer sr-only" {{ $address->is_main ? 'checked' : '' }} required>
                                    <div class="p-6 border-2 border-gray-100 rounded-xl bg-white peer-checked:border-primary peer-checked:bg-orange-50/30 transition-all hover:border-primary/50 h-full">
                                        <div class="flex justify-between items-start mb-2">
                                            <span class="text-[9px] font-bold uppercase tracking-widest text-gray-400 peer-checked:text-primary">{{ $address->label }}</span>
                                            <i class="fa-solid fa-circle-check text-primary opacity-0 peer-checked:opacity-100 transition-opacity"></i>
                                        </div>
                                        <p class="font-bold text-sm text-gray-800">{{ $address->recipient_name }}</p>
                                        <p class="text-[10px] text-gray-500 font-medium">{{ $address->phone_number }}</p>
                                        <p class="text-xs text-gray-500 leading-relaxed mt-2 line-clamp-2">
                                            {{ $address->full_address }}<br>
                                            <span class="font-bold">{{ $address->city }}</span>
                                        </p>
                                    </div>
                                </label>
                                @endforeach
                            </div>
                        @else
                            <div class="p-10 border-2 border-dashed border-gray-200 rounded-2xl text-center bg-gray-50">
                                <i class="fa-solid fa-map-location-dot text-3xl text-gray-300 mb-4 block"></i>
                                <p class="text-sm text-gray-500 mb-4 font-medium">You haven't added any shipping addresses yet.</p>
                                <a href="{{ route('customer.profile') }}">
                                    <button type="button" class="bg-primary text-white px-8 py-3 rounded-sm text-[10px] font-black uppercase tracking-widest shadow-lg shadow-primary/20">Add Address First</button>
                                </a>
                            </div>
                        @endif
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
                                            <input type="hidden" class="seller-id" value="{{ $item->product->seller_id }}">
                                        </div>
                                    </div>
                                    @endif
                                @endforeach
                            @endif
                        </div>

                        <!-- Voucher Section -->
                        <div class="mb-8 bg-white p-4 rounded-xl border border-gray-100 shadow-sm">
                            <label class="text-[9px] font-black text-gray-400 uppercase tracking-widest block mb-2">Voucher Toko</label>
                            <button type="button" id="open-voucher-modal" class="w-full flex items-center justify-between bg-gray-50/50 hover:bg-gray-50 border border-gray-100 hover:border-primary/20 rounded-xl p-3.5 transition-all group">
                                <div class="flex items-center space-x-3 text-left">
                                    <div class="w-8 h-8 rounded-lg bg-primary/10 flex items-center justify-center text-primary group-hover:scale-110 transition-transform">
                                        <i class="fa-solid fa-ticket"></i>
                                    </div>
                                    <div>
                                        <p class="text-xs font-bold text-gray-800" id="selected-voucher-title">Pilih Voucher Toko</p>
                                        <p class="text-[9px] text-gray-400" id="selected-voucher-desc">Hemat lebih banyak dengan voucher terklaim Anda</p>
                                    </div>
                                </div>
                                <div class="flex items-center space-x-2">
                                    @if(count($claimedVouchers) > 0)
                                        <span class="bg-primary text-white text-[9px] font-black px-2 py-0.5 rounded-full uppercase tracking-wider">{{ count($claimedVouchers) }} Voucher</span>
                                    @endif
                                    <i class="fa-solid fa-chevron-right text-gray-400 text-xs group-hover:translate-x-0.5 transition-transform"></i>
                                </div>
                            </button>

                            <div id="applied-voucher" class="hidden mt-3 p-3.5 bg-orange-50/50 rounded-xl border border-primary/20 flex justify-between items-center">
                                <div class="flex items-center space-x-3">
                                    <div class="w-8 h-8 rounded-lg bg-primary flex items-center justify-center text-white">
                                        <i class="fa-solid fa-check"></i>
                                    </div>
                                    <div>
                                        <p class="text-[9px] font-black text-primary uppercase tracking-widest" id="applied-code"></p>
                                        <p class="text-[11px] font-bold text-gray-700 leading-tight" id="applied-discount"></p>
                                    </div>
                                </div>
                                <button type="button" id="remove-voucher" class="text-red-400 hover:text-red-500 transition-colors p-1"><i class="fa-solid fa-circle-xmark text-lg"></i></button>
                            </div>
                            <input type="hidden" name="voucher_id" id="voucher-id-input">
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
                            <div class="flex justify-between text-[10px] uppercase font-bold tracking-widest text-primary hidden" id="discount-row">
                                <span>Voucher Discount</span>
                                <span id="discount-amount">- Rp 0</span>
                            </div>
                        </div>

                        <div class="flex justify-between items-end mb-8 pt-4 border-t border-gray-200">
                            <span class="text-lg font-bold">Total</span>
                            <span class="text-2xl font-black text-primary italic" id="final-total">Rp {{ number_format($total_amount, 0, ',', '.') }}</span>
                        </div>

                        <button type="submit" class="w-full bg-primary text-white py-5 rounded-sm text-xs font-bold uppercase tracking-widest hover:bg-opacity-90 transition shadow-xl shadow-primary/30 {{ $subtotal == 0 ? 'opacity-50 cursor-not-allowed' : '' }}" {{ $subtotal == 0 ? 'disabled' : '' }}>
                            Pay Now & Place Order
                        </button>
                        
                    </div>
                </aside>
            </div>
        </form>
    </main>

    <!-- Voucher Selection Modal (Shopee-Style) -->
    <div id="voucher-modal" class="fixed inset-0 z-50 hidden flex items-center justify-center p-4">
        <!-- Backdrop -->
        <div class="absolute inset-0 bg-black/60 backdrop-blur-sm transition-opacity" id="close-voucher-backdrop"></div>
        
        <!-- Modal Content -->
        <div class="bg-white rounded-3xl w-full max-w-lg shadow-2xl relative z-10 flex flex-col max-h-[85vh] overflow-hidden transform scale-95 opacity-0 transition-all duration-300" id="voucher-modal-container">
            <!-- Style for Peer checking -->
            <style>
                input[type="radio"]:checked + div {
                    border-color: #B5733A !important;
                    background-color: rgb(254 243 199 / 0.15) !important;
                }
                input[type="radio"]:checked + div .w-5 {
                    border-color: #B5733A !important;
                }
                input[type="radio"]:checked + div .scale-0 {
                    transform: scale(1) !important;
                }
            </style>

            <!-- Header -->
            <div class="p-6 border-b border-gray-100 flex items-center justify-between">
                <div class="flex items-center space-x-2">
                    <i class="fa-solid fa-ticket text-primary text-lg"></i>
                    <h3 class="text-lg font-bold text-gray-800">Pilih Voucher Toko</h3>
                </div>
                <button type="button" id="close-voucher-modal" class="text-gray-400 hover:text-gray-600 p-1 rounded-full hover:bg-gray-50 transition-colors">
                    <i class="fa-solid fa-xmark text-lg"></i>
                </button>
            </div>
            


            <!-- Scrollable Voucher List -->
            <div class="flex-1 overflow-y-auto p-6 space-y-4 max-h-[50vh]">
                @if(count($claimedVouchers) > 0)
                    @php
                        // Get all seller ids in the cart to check eligibility
                        $cartSellerIds = [];
                        if($cart && $cart->cartItems) {
                            foreach($cart->cartItems as $item) {
                                if($item->is_selected) {
                                    $cartSellerIds[] = $item->product->seller_id;
                                }
                            }
                        }
                        $cartSellerIds = array_unique($cartSellerIds);
                    @endphp

                    <!-- Eligible Vouchers -->
                    <div class="space-y-3">
                        <h4 class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Voucher Tersedia</h4>
                        
                        @php $hasVoucher = false; @endphp
                        @foreach($claimedVouchers as $voucher)
                            @php
                                $inCart = in_array($voucher->seller_id, $cartSellerIds);
                                // Get subtotal for this specific seller in the cart
                                $sellerSubtotal = 0;
                                if($cart && $cart->cartItems) {
                                    foreach($cart->cartItems as $item) {
                                        if($item->is_selected && $item->product->seller_id == $voucher->seller_id) {
                                            $sellerSubtotal += ($item->product->price * $item->quantity);
                                        }
                                    }
                                }
                                $minPurchaseMet = $sellerSubtotal >= $voucher->min_purchase;
                                $isEligible = $inCart && $minPurchaseMet;
                            @endphp

                            @if($isEligible)
                                @php $hasVoucher = true; @endphp
                                <!-- Voucher Card (Eligible) -->
                                <label class="block relative cursor-pointer group">
                                    <input type="radio" name="selected_modal_voucher" 
                                           value="{{ $voucher->id }}" 
                                           data-code="{{ $voucher->code }}" 
                                           data-seller="{{ $voucher->seller_id }}"
                                           class="peer sr-only">
                                    
                                    <div class="flex border border-gray-200 rounded-2xl overflow-hidden bg-white hover:border-primary/50 transition-all shadow-sm">
                                        <!-- Left Accent Panel (Ticket shape) -->
                                        <div class="bg-primary/5 text-primary w-24 flex flex-col items-center justify-center p-4 border-r border-dashed border-gray-200 relative">
                                            <!-- Half circle notch top -->
                                            <div class="absolute -top-2 -right-2 w-4 h-4 rounded-full bg-white border border-gray-200"></div>
                                            <!-- Half circle notch bottom -->
                                            <div class="absolute -bottom-2 -right-2 w-4 h-4 rounded-full bg-white border border-gray-200"></div>
                                            
                                            <i class="fa-solid fa-ticket text-xl mb-1.5 opacity-80"></i>
                                            <span class="text-[9px] font-black uppercase tracking-widest text-center line-clamp-2 leading-tight">{{ $voucher->seller->user->full_name ?? 'Seller' }}</span>
                                        </div>
                                        
                                        <!-- Voucher Details -->
                                        <div class="flex-1 p-5 flex flex-col justify-between">
                                            <div>
                                                <div class="flex justify-between items-start">
                                                    <h5 class="text-sm font-bold text-gray-800 group-hover:text-primary transition-colors">
                                                        @if($voucher->discount_type === 'percentage')
                                                            Diskon {{ number_format($voucher->discount_value, 0) }}%
                                                        @else
                                                            Potongan Rp {{ number_format($voucher->discount_value, 0, ',', '.') }}
                                                        @endif
                                                    </h5>
                                                    <!-- Custom Radio Circle -->
                                                    <div class="w-5 h-5 rounded-full border-2 border-gray-300 flex items-center justify-center transition-all">
                                                        <div class="w-2.5 h-2.5 rounded-full bg-primary scale-0 transition-transform"></div>
                                                    </div>
                                                </div>
                                                <p class="text-[10px] text-gray-500 font-medium mt-1">Min. Belanja: Rp {{ number_format($voucher->min_purchase, 0, ',', '.') }}</p>
                                                @if($voucher->max_discount)
                                                    <p class="text-[9px] text-gray-400 font-medium">Maks. Potongan: Rp {{ number_format($voucher->max_discount, 0, ',', '.') }}</p>
                                                @endif
                                            </div>
                                            <div class="flex justify-between items-center mt-3 pt-3 border-t border-gray-50">
                                                <span class="text-[9px] font-bold uppercase tracking-wider text-primary bg-primary/10 px-2 py-0.5 rounded">{{ $voucher->code }}</span>
                                                <span class="text-[9px] text-gray-400 font-medium">Berlaku s/d {{ \Carbon\Carbon::parse($voucher->end_date)->translatedFormat('d M Y') }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </label>
                            @endif
                        @endforeach

                        @if(!$hasVoucher)
                            <p class="text-xs text-gray-400 font-medium text-center py-4 bg-gray-50 rounded-2xl border border-dashed border-gray-150">Tidak ada voucher yang dapat langsung digunakan.</p>
                        @endif
                    </div>

                    <!-- Ineligible Vouchers -->
                    <div class="space-y-3 pt-4 border-t border-gray-100">
                        <h4 class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Voucher Tidak Memenuhi Syarat</h4>
                        
                        @php $hasIneligible = false; @endphp
                        @foreach($claimedVouchers as $voucher)
                            @php
                                $inCart = in_array($voucher->seller_id, $cartSellerIds);
                                // Get subtotal for this specific seller in the cart
                                $sellerSubtotal = 0;
                                if($cart && $cart->cartItems) {
                                    foreach($cart->cartItems as $item) {
                                        if($item->is_selected && $item->product->seller_id == $voucher->seller_id) {
                                            $sellerSubtotal += ($item->product->price * $item->quantity);
                                        }
                                    }
                                }
                                $minPurchaseMet = $sellerSubtotal >= $voucher->min_purchase;
                                $isEligible = $inCart && $minPurchaseMet;
                            @endphp

                            @if(!$isEligible)
                                @php $hasIneligible = true; @endphp
                                <!-- Voucher Card (Ineligible) -->
                                <div class="flex border border-gray-100 rounded-2xl overflow-hidden bg-gray-50/50 opacity-60">
                                    <!-- Left Accent Panel (Ticket shape) -->
                                    <div class="bg-gray-100 text-gray-400 w-24 flex flex-col items-center justify-center p-4 border-r border-dashed border-gray-200 relative">
                                        <!-- Half circle notch top -->
                                        <div class="absolute -top-2 -right-2 w-4 h-4 rounded-full bg-white border border-gray-100"></div>
                                        <!-- Half circle notch bottom -->
                                        <div class="absolute -bottom-2 -right-2 w-4 h-4 rounded-full bg-white border border-gray-100"></div>
                                        
                                        <i class="fa-solid fa-ticket text-xl mb-1.5"></i>
                                        <span class="text-[9px] font-bold uppercase tracking-widest text-center line-clamp-2 leading-tight">{{ $voucher->seller->user->full_name ?? 'Seller' }}</span>
                                    </div>
                                    
                                    <!-- Voucher Details -->
                                    <div class="flex-1 p-5 flex flex-col justify-between">
                                        <div>
                                            <div class="flex justify-between items-start">
                                                <h5 class="text-sm font-bold text-gray-400">
                                                    @if($voucher->discount_type === 'percentage')
                                                        Diskon {{ number_format($voucher->discount_value, 0) }}%
                                                    @else
                                                        Potongan Rp {{ number_format($voucher->discount_value, 0, ',', '.') }}
                                                    @endif
                                                </h5>
                                                <span class="text-[8px] font-black uppercase bg-gray-200 text-gray-500 px-2 py-0.5 rounded tracking-wide leading-none">
                                                    @if(!$inCart)
                                                        Beda Toko
                                                    @elseif(!$minPurchaseMet)
                                                        Kurang Rp {{ number_format($voucher->min_purchase - $sellerSubtotal, 0, ',', '.') }}
                                                    @endif
                                                </span>
                                            </div>
                                            <p class="text-[10px] text-gray-400 font-medium mt-1">Min. Belanja: Rp {{ number_format($voucher->min_purchase, 0, ',', '.') }}</p>
                                        </div>
                                        <div class="flex justify-between items-center mt-3 pt-3 border-t border-gray-150">
                                            <span class="text-[9px] font-bold uppercase tracking-wider text-gray-400 bg-gray-200 px-2 py-0.5 rounded">{{ $voucher->code }}</span>
                                            <span class="text-[9px] text-gray-400 font-medium">Berlaku s/d {{ \Carbon\Carbon::parse($voucher->end_date)->translatedFormat('d M Y') }}</span>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        @endforeach

                        @if(!$hasIneligible)
                            <p class="text-xs text-gray-400 font-medium text-center py-4 bg-gray-50 rounded-2xl border border-dashed border-gray-150">Semua voucher memenuhi syarat belanja!</p>
                        @endif
                    </div>
                @else
                    <div class="p-10 text-center">
                        <i class="fa-solid fa-ticket text-5xl text-gray-200 mb-4 block"></i>
                        <p class="text-sm text-gray-500 font-medium mb-1">Anda belum mengklaim voucher apa pun.</p>
                        <p class="text-[10px] text-gray-400">Klaim voucher di profil toko desainer/seller kesayangan Anda!</p>
                    </div>
                @endif
            </div>

            <!-- Footer Action -->
            <div class="p-6 border-t border-gray-100 bg-gray-50/50 flex space-x-3">
                <button type="button" id="cancel-voucher-selection" class="flex-1 bg-white hover:bg-gray-50 border border-gray-200 text-gray-700 py-3.5 rounded-xl text-xs font-bold uppercase tracking-wider transition-all">Batal</button>
                <button type="button" id="confirm-voucher-selection" class="flex-1 bg-primary text-white py-3.5 rounded-xl text-xs font-bold uppercase tracking-wider hover:bg-primary/95 transition-all shadow-lg shadow-primary/20">Gunakan Voucher</button>
            </div>
        </div>
    </div>

    <script>
        // Modal Event Listeners
        const voucherModal = document.getElementById('voucher-modal');
        const voucherModalContainer = document.getElementById('voucher-modal-container');
        
        document.getElementById('open-voucher-modal').addEventListener('click', function() {
            voucherModal.classList.remove('hidden');
            setTimeout(() => {
                voucherModalContainer.classList.remove('scale-95', 'opacity-0');
                voucherModalContainer.classList.add('scale-100', 'opacity-100');
            }, 10);
        });

        function hideVoucherModal() {
            voucherModalContainer.classList.remove('scale-100', 'opacity-100');
            voucherModalContainer.classList.add('scale-95', 'opacity-0');
            setTimeout(() => {
                voucherModal.classList.add('hidden');
            }, 300);
        }

        document.getElementById('close-voucher-modal').addEventListener('click', hideVoucherModal);
        document.getElementById('close-voucher-backdrop').addEventListener('click', hideVoucherModal);
        document.getElementById('cancel-voucher-selection').addEventListener('click', hideVoucherModal);

        // Confirm Selection
        document.getElementById('confirm-voucher-selection').addEventListener('click', async function() {
            const selectedRadio = document.querySelector('input[name="selected_modal_voucher"]:checked');
            if (!selectedRadio) {
                hideVoucherModal();
                return;
            }
            
            const code = selectedRadio.getAttribute('data-code');
            const sellerId = selectedRadio.getAttribute('data-seller');
            
            await applySelectedVoucher(code, sellerId);
            hideVoucherModal();
        });



        async function applySelectedVoucher(code, sellerId) {
            const response = await fetch("{{ route('customer.vouchers.apply') }}", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ code: code, seller_id: sellerId })
            });
            const data = await response.json();
            
            if (data.success) {
                applyDiscount(data, code);
            } else {
                alert(data.message || "Gagal menerapkan voucher.");
            }
        }

        function applyDiscount(data, code) {
            const subtotal = {{ $subtotal }};
            const shipping = {{ $shipping }};
            const taxes = {{ $taxes }};
            let discount = 0;

            if (data.discount_type === 'percentage') {
                discount = subtotal * (data.discount_value / 100);
                if (data.max_discount && discount > data.max_discount) {
                    discount = data.max_discount;
                }
            } else {
                discount = data.discount_value;
            }

            if (subtotal < data.min_purchase) {
                alert("Minimal belanja Rp " + new Intl.NumberFormat('id-ID').format(data.min_purchase) + " tidak terpenuhi.");
                return;
            }

            // UI Update
            document.getElementById('discount-row').classList.remove('hidden');
            document.getElementById('discount-amount').textContent = "- Rp " + new Intl.NumberFormat('id-ID').format(discount);
            document.getElementById('voucher-id-input').value = data.voucher_id;
            
            const newTotal = subtotal + shipping + taxes - discount;
            document.getElementById('final-total').textContent = "Rp " + new Intl.NumberFormat('id-ID').format(newTotal);

            document.getElementById('applied-voucher').classList.remove('hidden');
            document.getElementById('applied-code').textContent = code.toUpperCase();
            document.getElementById('applied-discount').textContent = data.discount_type === 'percentage' ? data.discount_value + "% OFF" : "Rp " + new Intl.NumberFormat('id-ID').format(data.discount_value) + " OFF";
            
            // Update trigger card text
            document.getElementById('selected-voucher-title').textContent = code.toUpperCase() + " Terpasang";
            document.getElementById('selected-voucher-desc').textContent = "Diskon Rp " + new Intl.NumberFormat('id-ID').format(discount) + " berhasil dipasang";
        }

        document.getElementById('remove-voucher').addEventListener('click', function() {
            document.getElementById('discount-row').classList.add('hidden');
            document.getElementById('voucher-id-input').value = '';
            document.getElementById('final-total').textContent = "Rp {{ number_format($total_amount, 0, ',', '.') }}";
            document.getElementById('applied-voucher').classList.add('hidden');
            
            // Reset trigger card text
            document.getElementById('selected-voucher-title').textContent = "Pilih Voucher Toko";
            document.getElementById('selected-voucher-desc').textContent = "Hemat lebih banyak dengan voucher terklaim Anda";
            
            // Uncheck all radios in modal
            const checkedRadio = document.querySelector('input[name="selected_modal_voucher"]:checked');
            if (checkedRadio) checkedRadio.checked = false;
        });
    </script>

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