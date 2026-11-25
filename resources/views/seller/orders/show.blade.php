<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Decor Seller - Detail Pesanan @if($order->return_code) #DEC-{{ str_replace('RET-', '', $order->return_code) }}-RET @else #DEC-{{ $order->id }} @endif</title>
    <script src="https://cdn.tailwindcss.com"></script>
    @include('seller.partials.head')
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body class="text-gray-800">

    @include('seller.partials.sidebar')

    <main id="main-content" class="flex-1 flex flex-col ml-0 md:ml-64 sidebar-transition min-h-screen">
        @php
            $orderNumber = $order->return_code
                ? '#DEC-' . str_replace('RET-', '', $order->return_code) . '-RET'
                : '#DEC-' . $order->id;
            $statusLabel = match($order->status) {
                'pending' => 'Pending',
                'waiting_verification' => 'Menunggu Verifikasi',
                'paid' => 'Dibayar',
                'shipped' => 'Dikirim',
                'completed' => 'Selesai',
                'cancelled' => 'Dibatalkan',
                'return approved' => 'Retur Disetujui',
                'returning' => 'Retur Diproses',
                'return rejected' => 'Retur Ditolak',
                default => ucfirst(str_replace('_', ' ', $order->status)),
            };
            $badgeClass = match($order->status) {
                'pending' => 'bg-yellow-100 text-yellow-700',
                'waiting_verification' => 'bg-orange-100 text-orange-700',
                'paid' => 'bg-blue-100 text-blue-700',
                'shipped' => 'bg-indigo-100 text-indigo-700',
                'completed' => 'bg-green-100 text-green-700',
                'cancelled', 'return rejected' => 'bg-red-100 text-red-600',
                'return approved' => 'bg-purple-100 text-purple-700',
                'returning' => 'bg-purple-100 text-purple-700',
                default => 'bg-gray-100 text-gray-600',
            };
            $extraAction = '<a href="'.route('seller.orders').'" class="hidden sm:inline-block hover:opacity-70 transition-colors text-[10px] font-bold uppercase tracking-widest">Back to List</a>';

            $itemsSubtotal = (float) $order->orderItems->sum(fn($item) => (float) $item->price * (int) $item->quantity);
            $discountAmount = (float) ($order->discount_amount ?? 0);
            $grandTotal = (float) $order->total_price;
            $standardShipping = 150000;
            $standardTax = round($itemsSubtotal * 0.11);
            $expectedWithStandardFees = $itemsSubtotal + $standardShipping + $standardTax - $discountAmount;
            $expectedNoFees = $itemsSubtotal - $discountAmount;

            if (abs($grandTotal - $expectedWithStandardFees) < 1) {
                $shippingFee = $standardShipping;
                $taxAmount = $standardTax;
                $adjustmentAmount = 0;
            } elseif (abs($grandTotal - $expectedNoFees) < 1) {
                $shippingFee = 0;
                $taxAmount = 0;
                $adjustmentAmount = 0;
            } else {
                $shippingFee = $standardShipping;
                $taxAmount = $standardTax;
                $adjustmentAmount = $grandTotal - $expectedWithStandardFees;
            }

            $isPaidOrBeyond = in_array($order->status, ['paid', 'shipped', 'completed', 'return approved', 'returning']);
            $isShippedOrBeyond = in_array($order->status, ['shipped', 'completed', 'return approved', 'returning']);
            $isCompletedOrReturn = in_array($order->status, ['completed', 'return approved', 'returning']);
            $isReturnFlow = in_array($order->status, ['returning', 'return approved']);
        @endphp
        @include('seller.partials.header', ['title' => 'Order Details ' . $orderNumber, 'extra_action' => $extraAction])

        <div class="p-4 sm:p-6 lg:p-8 space-y-6 flex-1 pb-24">
            <!-- STATUS BANNER + STEPPER -->
            <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
                <div class="p-5 sm:p-6 border-b border-gray-100 flex flex-col lg:flex-row lg:items-center justify-between gap-4">
                    <div class="flex items-start sm:items-center gap-3.5">
                        <div class="w-11 h-11 rounded-xl bg-primary/10 text-primary flex items-center justify-center shrink-0 text-lg">
                            <i class="fa-solid {{ $order->status === 'return approved' ? 'fa-rotate-left' : ($order->status === 'completed' ? 'fa-circle-check' : ($order->status === 'shipped' ? 'fa-truck-fast' : 'fa-receipt')) }}"></i>
                        </div>
                        <div>
                            <div class="flex flex-wrap items-center gap-2.5">
                                <h2 class="text-lg sm:text-xl font-bold text-gray-900">Pesanan {{ $orderNumber }}</h2>
                                <span class="{{ $badgeClass }} text-[10px] px-3 py-1 rounded-full uppercase tracking-widest font-bold">{{ $statusLabel }}</span>
                            </div>
                            <p class="text-xs text-gray-500 mt-1">
                                Dipesan pada {{ $order->created_at->format('d M Y, H:i') }} WIB &bull; Kurir: <span class="font-semibold text-gray-700">{{ $order->shipping_courier ?? 'Standard Delivery' }}</span>
                            </p>
                        </div>
                    </div>

                    <div class="flex flex-wrap items-center gap-2.5">
                        <a href="{{ route('seller.orders.invoice', $order->id) }}" target="_blank" class="inline-flex items-center justify-center px-4 py-2.5 bg-primary text-white rounded-lg text-[10px] font-bold uppercase tracking-widest hover:bg-opacity-90 transition-all">
                            <i class="fa-solid fa-file-invoice mr-2"></i> Cetak Invoice
                        </a>
                        <a href="{{ route('seller.orders.label', $order->id) }}" target="_blank" class="inline-flex items-center justify-center px-4 py-2.5 border border-gray-200 text-gray-700 rounded-lg text-[10px] font-bold uppercase tracking-widest hover:bg-gray-50 transition-all">
                            <i class="fa-solid fa-tag mr-2"></i> Cetak Label
                        </a>
                    </div>
                </div>

                @if(!in_array($order->status, ['cancelled', 'return rejected']))
                <div class="px-5 sm:px-6 py-4 bg-gray-50/50 grid grid-cols-2 sm:grid-cols-4 gap-4">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 rounded-full bg-green-100 text-green-700 flex items-center justify-center text-xs shrink-0"><i class="fa-solid fa-check"></i></div>
                        <div>
                            <p class="text-[11px] font-bold text-gray-800">Pesanan Dibuat</p>
                            <p class="text-[10px] text-gray-400">{{ $order->created_at->format('d M Y') }}</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 rounded-full {{ $isPaidOrBeyond ? 'bg-green-100 text-green-700' : 'bg-amber-100 text-amber-700' }} flex items-center justify-center text-xs shrink-0">
                            <i class="fa-solid {{ $isPaidOrBeyond ? 'fa-check' : 'fa-clock' }}"></i>
                        </div>
                        <div>
                            <p class="text-[11px] font-bold text-gray-800">Pembayaran</p>
                            <p class="text-[10px] text-gray-400">{{ $isPaidOrBeyond ? 'Terverifikasi' : ($order->status === 'waiting_verification' ? 'Menunggu Verifikasi' : 'Belum Dibayar') }}</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 rounded-full {{ $isShippedOrBeyond ? 'bg-green-100 text-green-700' : 'bg-gray-200 text-gray-400' }} flex items-center justify-center text-xs shrink-0">
                            <i class="fa-solid {{ $isShippedOrBeyond ? 'fa-truck-fast' : 'fa-box' }}"></i>
                        </div>
                        <div>
                            <p class="text-[11px] font-bold {{ $isShippedOrBeyond ? 'text-gray-800' : 'text-gray-400' }}">Pengiriman</p>
                            <p class="text-[10px] text-gray-400">{{ $isShippedOrBeyond ? 'Dalam / Sudah Dikirim' : 'Menunggu Kirim' }}</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 rounded-full {{ $isReturnFlow ? 'bg-purple-100 text-purple-700' : ($isCompletedOrReturn ? 'bg-green-100 text-green-700' : 'bg-gray-200 text-gray-400') }} flex items-center justify-center text-xs shrink-0">
                            <i class="fa-solid {{ $isReturnFlow ? 'fa-rotate-left' : 'fa-flag-checkered' }}"></i>
                        </div>
                        <div>
                            <p class="text-[11px] font-bold {{ $isCompletedOrReturn ? 'text-gray-800' : 'text-gray-400' }}">{{ $isReturnFlow ? 'Retur Diproses' : 'Pesanan Selesai' }}</p>
                            <p class="text-[10px] text-gray-400">{{ $order->status === 'return approved' ? 'Retur Disetujui' : ($order->status === 'returning' ? 'Menunggu Keputusan' : ($order->status === 'completed' ? 'Transaksi Selesai' : 'Menunggu Selesai')) }}</p>
                        </div>
                    </div>
                </div>
                @endif
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">
                <div class="lg:col-span-8 space-y-6">
                    <!-- PEMBELI & ALAMAT -->
                    <div class="bg-white p-6 rounded-xl border border-gray-100 shadow-sm grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-3">
                            <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Informasi Pembeli</p>
                            <div class="flex items-center gap-3.5">
                                <img src="https://ui-avatars.com/api/?name={{ urlencode($order->customer->user->full_name) }}&background=f3f4f6&color=B5733A" class="w-11 h-11 rounded-full shrink-0 border border-gray-100">
                                <div class="min-w-0">
                                    <h3 class="text-base font-bold text-gray-900 truncate">{{ $order->customer->user->full_name }}</h3>
                                    <p class="text-xs text-gray-500">{{ $order->customer->phone ?? 'Nomor HP tidak tersedia' }}</p>
                                    <p class="text-[11px] text-gray-400 mt-0.5">Member sejak {{ $order->customer->user->created_at->format('M Y') }}</p>
                                </div>
                            </div>
                        </div>

                        <div class="md:border-l md:border-gray-100 md:pl-6 space-y-2">
                            <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Alamat Pengiriman</p>
                            <div class="text-xs space-y-1">
                                <p class="font-bold text-gray-800">
                                    {{ $order->shipping_recipient ?? $order->customer->user->full_name }}
                                    @if($order->shipping_phone)
                                    <span class="text-gray-400 font-normal">&bull; {{ $order->shipping_phone }}</span>
                                    @endif
                                </p>
                                <p class="text-gray-600 leading-relaxed">
                                    {{ $order->shipping_address ?? $order->customer->address ?? 'Alamat belum diatur' }}<br>
                                    {{ collect([$order->shipping_city ?? $order->customer->city, $order->shipping_province, $order->shipping_postal_code])->filter()->implode(', ') ?: 'Indonesia' }}
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- RINCIAN PRODUK + RINGKASAN -->
                    <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
                        <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
                            <h3 class="text-sm font-bold text-gray-800 uppercase tracking-widest flex items-center gap-2">
                                <i class="fa-solid fa-box-open text-primary"></i> Rincian Produk
                            </h3>
                            <span class="text-xs font-semibold text-gray-400">{{ $order->orderItems->sum('quantity') }} item</span>
                        </div>

                        <div class="divide-y divide-gray-100 px-6">
                            @foreach($order->orderItems as $item)
                            <div class="py-4 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                                <div class="flex items-center gap-4 min-w-0">
                                    <img src="{{ $item->product->images->first()->img_url ?? 'https://via.placeholder.com/200' }}" class="w-16 h-16 rounded-lg object-cover border border-gray-100 shrink-0">
                                    <div class="min-w-0">
                                        <h4 class="text-sm font-bold text-gray-900">{{ $item->product->name }}</h4>
                                        <p class="text-[11px] text-gray-400 font-bold uppercase tracking-widest mt-0.5">SKU: PROD-{{ $item->product_id }}</p>
                                    </div>
                                </div>
                                <div class="text-left sm:text-right shrink-0 tabular-nums">
                                    <p class="text-xs text-gray-400 font-semibold">{{ $item->quantity }} &times; Rp {{ number_format($item->price, 0, ',', '.') }}</p>
                                    <p class="text-sm font-bold text-gray-900 mt-0.5">Rp {{ number_format($item->price * $item->quantity, 0, ',', '.') }}</p>
                                </div>
                            </div>
                            @endforeach
                        </div>

                        <div class="bg-gray-50/70 border-t border-gray-100 px-6 py-5">
                            <div class="max-w-md ml-auto space-y-2.5 text-xs tabular-nums">
                                <div class="flex justify-between text-gray-500">
                                    <span>Subtotal Produk</span>
                                    <span class="font-semibold text-gray-800">Rp {{ number_format($itemsSubtotal, 0, ',', '.') }}</span>
                                </div>
                                <div class="flex justify-between text-gray-500">
                                    <span>Ongkos Kirim</span>
                                    <span class="font-semibold text-gray-800">Rp {{ number_format($shippingFee, 0, ',', '.') }}</span>
                                </div>
                                <div class="flex justify-between text-gray-500">
                                    <span>PPN (11%)</span>
                                    <span class="font-semibold text-gray-800">Rp {{ number_format($taxAmount, 0, ',', '.') }}</span>
                                </div>
                                @if($discountAmount > 0)
                                <div class="flex justify-between text-primary">
                                    <span class="font-semibold">Diskon Promo</span>
                                    <span class="font-bold">- Rp {{ number_format($discountAmount, 0, ',', '.') }}</span>
                                </div>
                                @endif
                                @if(abs($adjustmentAmount) >= 1)
                                <div class="flex justify-between text-amber-700">
                                    <span class="font-semibold">Penyesuaian Total</span>
                                    <span class="font-bold">{{ $adjustmentAmount > 0 ? '+' : '-' }} Rp {{ number_format(abs($adjustmentAmount), 0, ',', '.') }}</span>
                                </div>
                                @endif
                                <div class="pt-3 border-t border-gray-200 flex justify-between items-center">
                                    <span class="text-xs font-bold text-gray-700 uppercase tracking-wider">Total Pembayaran</span>
                                    <span class="text-xl font-bold text-primary">Rp {{ number_format($grandTotal, 0, ',', '.') }}</span>
                                </div>
                            </div>

                            @if(abs($adjustmentAmount) >= 1)
                            <div class="mt-4 p-3 rounded-lg bg-amber-50 border border-amber-200 text-[11px] text-amber-800 flex items-start gap-2">
                                <i class="fa-solid fa-triangle-exclamation mt-0.5 shrink-0"></i>
                                <span>Total tersimpan pada pesanan lama ini berbeda dari perhitungan standar (subtotal + ongkir + PPN 11% &minus; diskon).</span>
                            </div>
                            @endif
                        </div>
                    </div>

                    @if($order->productReturn)
                    @php
                        $ret = $order->productReturn;
                        $retBadge = match($ret->status) {
                            'approved' => 'bg-green-100 text-green-700',
                            'rejected' => 'bg-red-100 text-red-700',
                            default => 'bg-amber-100 text-amber-700',
                        };
                    @endphp
                    <div class="bg-white p-6 rounded-xl border border-purple-100 shadow-sm space-y-4">
                        <div class="flex flex-wrap items-center justify-between gap-3 border-b border-gray-100 pb-3">
                            <div class="flex items-center gap-2.5">
                                <i class="fa-solid fa-rotate-left text-purple-600"></i>
                                <h3 class="text-xs font-bold text-gray-800 uppercase tracking-widest">Informasi Retur</h3>
                            </div>
                            <span class="{{ $retBadge }} text-[10px] px-3 py-1 rounded-full uppercase tracking-widest font-bold">{{ $ret->status }}</span>
                        </div>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-xs">
                            <div>
                                <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Jenis Pengajuan</p>
                                <p class="font-bold text-gray-800 mt-1 uppercase">{{ $ret->return_type ?? '-' }}</p>
                            </div>
                            <div>
                                <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Tanggal Pengajuan</p>
                                <p class="font-bold text-gray-800 mt-1">{{ $ret->return_date ? \Carbon\Carbon::parse($ret->return_date)->format('d M Y') : $ret->created_at->format('d M Y') }}</p>
                            </div>
                            <div class="sm:col-span-2">
                                <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Alasan Retur</p>
                                <p class="text-gray-600 mt-1 leading-relaxed">{{ $ret->reason ?? '-' }}</p>
                            </div>
                        </div>
                        <div>
                            <a href="{{ route('seller.complaint.detail', $ret->id) }}" class="inline-flex items-center text-xs font-bold text-primary hover:underline">
                                <span>Lihat Detail Komplain / Retur</span>
                                <i class="fa-solid fa-arrow-right ml-1.5 text-[10px]"></i>
                            </a>
                        </div>
                    </div>
                    @endif
                </div>

                <div class="lg:col-span-4 space-y-6 lg:sticky lg:top-6">
                    <div class="bg-white p-6 rounded-xl border border-gray-100 shadow-sm space-y-3">
                        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Status Pembayaran</p>
                        <div class="flex flex-wrap items-center justify-between gap-3">
                            <span class="text-sm font-bold uppercase text-gray-800">{{ str_replace('_', ' ', $order->payment_method) }}</span>
                            @if($order->status == 'waiting_verification')
                            <span class="inline-flex items-center px-2.5 py-1 rounded-full bg-orange-50 text-orange-600 font-bold uppercase text-[10px] tracking-wider"><i class="fa-solid fa-clock mr-1"></i> WAITING VERIFICATION</span>
                            @elseif(in_array($order->status, ['paid', 'shipped', 'completed', 'return approved', 'returning']))
                            <span class="inline-flex items-center px-2.5 py-1 rounded-full bg-green-50 text-green-600 font-bold uppercase text-[10px] tracking-wider"><i class="fa-solid fa-check mr-1"></i> PAID SUCCESSFUL</span>
                            @elseif(in_array($order->status, ['cancelled', 'return rejected']))
                            <span class="inline-flex items-center px-2.5 py-1 rounded-full bg-red-50 text-red-600 font-bold uppercase text-[10px] tracking-wider">{{ strtoupper(str_replace('_', ' ', $order->status)) }}</span>
                            @else
                            <span class="inline-flex items-center px-2.5 py-1 rounded-full bg-yellow-50 text-yellow-600 font-bold uppercase text-[10px] tracking-wider">PENDING PAYMENT</span>
                            @endif
                        </div>
                    </div>

                    @if($order->payment_proof)
                    <div class="bg-white p-6 rounded-xl border border-gray-100 shadow-sm space-y-4">
                        <div class="flex items-center justify-between">
                            <h3 class="text-xs font-bold text-gray-400 uppercase tracking-widest">Bukti Pembayaran</h3>
                            <a href="{{ asset('storage/' . $order->payment_proof) }}" target="_blank" class="text-[10px] font-bold text-primary hover:underline uppercase tracking-wider">Buka Penuh</a>
                        </div>
                        <div class="rounded-xl overflow-hidden border border-gray-100 bg-gray-50">
                            <img src="{{ asset('storage/' . $order->payment_proof) }}" class="w-full max-h-52 object-contain cursor-pointer hover:opacity-90 transition-opacity" onclick="window.open(this.src)">
                        </div>

                        @if($order->status == 'waiting_verification')
                        <div class="grid grid-cols-2 gap-3 pt-1">
                            <form action="{{ route('seller.orders.validate-payment', $order->id) }}" method="POST">
                                @csrf
                                <input type="hidden" name="action" value="approve">
                                <button type="submit" class="w-full bg-green-500 text-white py-2.5 rounded-xl text-[10px] font-bold uppercase tracking-widest shadow-lg shadow-green-500/20 hover:opacity-90 transition-all">
                                    Setujui
                                </button>
                            </form>
                            <form action="{{ route('seller.orders.validate-payment', $order->id) }}" method="POST">
                                @csrf
                                <input type="hidden" name="action" value="reject">
                                <button type="submit" class="w-full bg-red-500 text-white py-2.5 rounded-xl text-[10px] font-bold uppercase tracking-widest shadow-lg shadow-red-500/20 hover:opacity-90 transition-all">
                                    Tolak
                                </button>
                            </form>
                        </div>
                        @endif
                    </div>
                    @endif

                    <div class="bg-white p-6 rounded-xl border border-gray-100 shadow-sm space-y-3">
                        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Dokumen Pesanan</p>
                        <div class="flex flex-col gap-2">
                            <a href="{{ route('seller.orders.invoice', $order->id) }}" target="_blank" class="inline-flex items-center justify-center bg-primary text-white py-2.5 rounded-xl text-[10px] font-bold uppercase tracking-widest hover:opacity-90 transition-all">
                                <i class="fa-solid fa-file-invoice mr-2"></i> Cetak Invoice
                            </a>
                            <a href="{{ route('seller.orders.label', $order->id) }}" target="_blank" class="inline-flex items-center justify-center border border-gray-200 text-gray-700 py-2.5 rounded-xl text-[10px] font-bold uppercase tracking-widest hover:bg-gray-50 transition-all">
                                <i class="fa-solid fa-tag mr-2"></i> Cetak Label Pengiriman
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        @include('partials.footer')
    </main>
</body>
</html>
