<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice {{ $order->return_code ? '#DEC-' . str_replace('RET-', '', $order->return_code) . '-RET' : '#DEC-' . $order->id }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    @include('seller.partials.print-head')
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        @media print {
            .no-print { display: none !important; }
            body { background: white !important; padding: 0 !important; }
            .invoice-card { border: none !important; box-shadow: none !important; border-radius: 0 !important; }
            * { -webkit-print-color-adjust: exact !important; print-color-adjust: exact !important; }
        }
    </style>
</head>
<body class="bg-[#F8F6F4] p-4 sm:p-8">

    @php
        $orderNumber = $order->return_code
            ? '#DEC-' . str_replace('RET-', '', $order->return_code) . '-RET'
            : '#DEC-' . $order->id;
        $customerName = $order->customer->user->full_name ?? 'Customer';
        $customerEmail = $order->customer->user->email ?? '-';
        $customerPhone = $order->shipping_phone ?? $order->customer->phone ?? '-';
        $customerAddress = $order->shipping_address ?? $order->customer->address ?? 'Alamat belum diatur';
        $customerCity = collect([
            $order->shipping_city ?? $order->customer->city ?? null,
            $order->shipping_province ?? null,
            $order->shipping_postal_code ?? null,
        ])->filter()->implode(', ');
        $storeName = $order->orderItems->first()->product->seller->store_name ?? 'DECOR Official Store';
        $itemsSubtotal = (float) $order->orderItems->sum(fn($item) => (float) $item->price * (int) $item->quantity);
        $discountAmount = (float) ($order->discount_amount ?? 0);
        $others = (float) $order->total_price - $itemsSubtotal + $discountAmount;
        $statusLabel = match($order->status) {
            'pending' => 'Pending',
            'waiting_verification' => 'Menunggu Verifikasi',
            'paid' => 'Dibayar',
            'shipped' => 'Dikirim',
            'completed' => 'Selesai',
            'cancelled' => 'Dibatalkan',
            'return approved' => 'Retur Disetujui',
            'return rejected' => 'Retur Ditolak',
            default => ucfirst(str_replace('_', ' ', $order->status)),
        };
        $statusBadge = match($order->status) {
            'pending' => 'bg-yellow-100 text-yellow-700',
            'waiting_verification' => 'bg-orange-100 text-orange-700',
            'paid' => 'bg-blue-100 text-blue-700',
            'shipped' => 'bg-indigo-100 text-indigo-700',
            'completed', 'return approved' => 'bg-green-100 text-green-700',
            'cancelled', 'return rejected' => 'bg-red-100 text-red-700',
            default => 'bg-gray-100 text-gray-600',
        };
    @endphp

    <div class="no-print max-w-4xl mx-auto mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
        <a href="{{ route('seller.orders.show', $order->id) }}" class="inline-flex items-center justify-center h-10 px-4 border border-gray-200 bg-white text-gray-600 rounded-lg text-[10px] font-black uppercase tracking-widest hover:border-primary hover:text-primary transition-all">
            <i class="fa-solid fa-arrow-left mr-2"></i> Kembali ke Detail
        </a>
        <button onclick="window.print()" class="inline-flex items-center justify-center h-10 px-5 bg-primary text-white rounded-lg text-[10px] font-black uppercase tracking-widest shadow-lg shadow-primary/20 hover:opacity-90 transition-all">
            <i class="fa-solid fa-print mr-2"></i> Cetak Invoice
        </button>
    </div>

    <div class="invoice-card max-w-4xl mx-auto bg-white p-6 sm:p-10 lg:p-12 border border-gray-100 shadow-sm rounded-xl">
        <div class="flex flex-col sm:flex-row sm:justify-between sm:items-start gap-6 border-b-4 border-primary pb-8">
            <div>
                <h1 class="text-3xl sm:text-4xl font-black tracking-tighter text-gray-900">DECOR.</h1>
                <p class="text-[10px] font-bold uppercase tracking-[0.4em] text-gray-400 mt-2">Premium Furniture Marketplace</p>
            </div>
            <div class="sm:text-right">
                <h2 class="text-lg font-black uppercase tracking-widest text-gray-900">Official Invoice</h2>
                <p class="text-sm font-bold text-gray-500 mt-1">{{ $orderNumber }}</p>
                <p class="text-xs text-gray-400 mt-1">{{ $order->created_at->format('d M Y') }}</p>
                <span class="{{ $statusBadge }} inline-flex items-center px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-widest mt-3">{{ $statusLabel }}</span>
            </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-8 py-10">
            <div>
                <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-3">Sold By</p>
                <h3 class="text-lg font-black text-primary uppercase">{{ $storeName }}</h3>
                <p class="text-xs text-gray-500 mt-1 font-semibold">Verified Merchant</p>
                <p class="text-xs text-gray-500 mt-1">DECOR Marketplace</p>
            </div>
            <div class="sm:text-right">
                <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-3">Billed To</p>
                <p class="font-bold text-lg text-gray-900">{{ $customerName }}</p>
                <p class="text-xs text-gray-500 mt-1">{{ $customerEmail }}</p>
                <p class="text-xs text-gray-500 mt-1">{{ $customerPhone }}</p>
                <p class="text-xs text-gray-500 leading-relaxed mt-2">{{ $customerAddress }}<br>{{ $customerCity ?: 'Indonesia' }}</p>
            </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 border-y border-gray-100 py-5 mb-8">
            <div>
                <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Kurir</p>
                <p class="text-xs font-bold text-gray-800 mt-1">{{ strtoupper($order->shipping_courier ?? 'Standard Delivery') }}</p>
            </div>
            <div>
                <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Pembayaran</p>
                <p class="text-xs font-bold text-gray-800 mt-1">{{ strtoupper(str_replace('_', ' ', $order->payment_method ?? '-')) }}</p>
            </div>
            <div>
                <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">No. Pesanan</p>
                <p class="text-xs font-bold text-gray-800 mt-1">{{ $orderNumber }}</p>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="border-b-2 border-gray-900">
                        <th class="py-4 text-[10px] font-black uppercase tracking-widest text-gray-500">Produk</th>
                        <th class="py-4 text-[10px] font-black uppercase tracking-widest text-gray-500 text-center">Qty</th>
                        <th class="py-4 text-[10px] font-black uppercase tracking-widest text-gray-500 text-right hidden sm:table-cell">Harga</th>
                        <th class="py-4 text-[10px] font-black uppercase tracking-widest text-gray-500 text-right">Subtotal</th>
                    </tr>
                </thead>
                <tbody class="text-sm font-semibold text-gray-800">
                    @foreach($order->orderItems as $item)
                    <tr class="border-b border-gray-100">
                        <td class="py-5 pr-3">
                            <p class="font-bold text-gray-900">{{ $item->product->name ?? 'Produk' }}</p>
                            <p class="text-[10px] text-gray-400 font-bold uppercase mt-1">SKU: PROD-{{ $item->product_id }}</p>
                            <p class="sm:hidden text-[10px] text-gray-500 font-semibold mt-1">Rp {{ number_format($item->price, 0, ',', '.') }} / item</p>
                        </td>
                        <td class="py-5 text-center">{{ $item->quantity }}</td>
                        <td class="py-5 text-right hidden sm:table-cell">Rp {{ number_format($item->price, 0, ',', '.') }}</td>
                        <td class="py-5 text-right font-black whitespace-nowrap">Rp {{ number_format($item->price * $item->quantity, 0, ',', '.') }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="flex justify-end pt-8">
            <div class="w-full sm:w-80 space-y-4">
                <div class="flex justify-between text-xs text-gray-500 font-bold uppercase tracking-widest">
                    <span>Subtotal Produk</span>
                    <span class="text-gray-900">Rp {{ number_format($itemsSubtotal, 0, ',', '.') }}</span>
                </div>
                @if(abs($others) >= 1)
                <div class="flex justify-between text-xs text-gray-500 font-bold uppercase tracking-widest">
                    <span>Ongkir &amp; PPN</span>
                    <span class="text-gray-900">{{ $others > 0 ? '' : '- ' }}Rp {{ number_format(abs($others), 0, ',', '.') }}</span>
                </div>
                @endif
                @if($discountAmount > 0)
                <div class="flex justify-between text-xs text-primary font-bold uppercase tracking-widest border-b border-gray-100 pb-4">
                    <span>Diskon</span>
                    <span>- Rp {{ number_format($discountAmount, 0, ',', '.') }}</span>
                </div>
                @endif
                <div class="flex justify-between items-center pt-2">
                    <p class="text-xs font-black uppercase tracking-[0.2em] text-gray-900">Grand Total</p>
                    <p class="text-2xl sm:text-3xl font-black text-primary tracking-tighter">Rp {{ number_format($order->total_price, 0, ',', '.') }}</p>
                </div>
            </div>
        </div>

        <div class="mt-16 pt-8 border-t border-gray-100 flex flex-col sm:flex-row sm:justify-between sm:items-center gap-3">
            <div>
                <p class="text-[10px] font-bold text-gray-900 uppercase tracking-widest">Terima kasih atas pembelian Anda.</p>
                <p class="text-[10px] font-semibold text-gray-400 mt-1">Invoice ini merupakan bukti transaksi yang sah di DECOR Marketplace.</p>
            </div>
            <p class="text-[10px] font-bold text-gray-900">&copy; {{ now()->year }} DECOR.</p>
        </div>
    </div>
</body>
</html>
