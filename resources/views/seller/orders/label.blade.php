<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shipping Label - DECOR</title>
    <script src="https://cdn.tailwindcss.com"></script>
    @include('seller.partials.print-head')
    <style>
        @media print {
            .no-print { display: none !important; }
            body { padding: 0 !important; background: white !important; }
            .label-card { box-shadow: none !important; }
            * { -webkit-print-color-adjust: exact !important; print-color-adjust: exact !important; }
        }
        .dashed-border { border: 3px dashed #000; }
    </style>
</head>
<body class="bg-gray-100 p-4 sm:p-8">

    @php
        $orderNumber = $order->return_code
            ? '#DEC-' . str_replace('RET-', '', $order->return_code) . '-RET'
            : '#DEC-' . $order->id;
        $recipient = $order->shipping_recipient ?? ($order->customer->user->full_name ?? 'Customer');
        $phone = $order->shipping_phone ?? ($order->customer->phone ?? '-');
        $address = $order->shipping_address ?? ($order->customer->address ?? 'Alamat belum diatur');
        $region = collect([
            $order->shipping_city ?? $order->customer->city ?? null,
            $order->shipping_province ?? null,
            $order->shipping_postal_code ?? null,
        ])->filter()->implode(', ');
        $storeName = $order->orderItems->first()->product->seller->store_name ?? 'DECOR Official Store';
        $totalItems = $order->orderItems->sum('quantity');
    @endphp

    <div class="no-print mb-6 flex flex-col sm:flex-row items-center justify-center gap-3">
        <a href="{{ route('seller.orders.show', $order->id) }}" class="inline-flex items-center justify-center h-10 px-4 border border-gray-200 bg-white text-gray-600 rounded-lg text-[10px] font-bold uppercase tracking-widest hover:border-primary hover:text-primary transition-all">
            <i class="fa-solid fa-arrow-left mr-2"></i> Kembali ke Detail
        </a>
        <button onclick="window.print()" class="bg-black text-white h-10 px-6 rounded-lg text-[10px] font-bold uppercase tracking-widest hover:opacity-90 transition-all">Print Resi</button>
    </div>

    <div class="label-card w-full max-w-[550px] mx-auto bg-white dashed-border p-6 sm:p-8">
        <div class="flex justify-between items-center border-b-2 border-black pb-4 mb-6">
            <h1 class="text-3xl font-black italic tracking-tighter text-black">DECOR.</h1>
            <div class="text-right">
                <p class="text-[9px] font-bold uppercase text-gray-600 tracking-widest">Order ID</p>
                <p class="text-lg font-black leading-none text-black">{{ $orderNumber }}</p>
            </div>
        </div>

        <div class="space-y-6">
            <div class="border-l-4 border-black pl-4">
                <p class="text-[8px] font-bold uppercase tracking-widest text-gray-600">Sender (Pengirim):</p>
                <p class="text-sm font-black uppercase mt-1 text-black">{{ $storeName }}</p>
                <p class="text-[10px] font-semibold text-black">DECOR Marketplace &bull; {{ $order->shipping_courier ?? 'Standard Delivery' }}</p>
            </div>

            <div class="bg-black text-white p-6">
                <p class="text-[8px] font-bold uppercase tracking-[0.3em] opacity-80">To (Penerima):</p>
                <p class="text-xl font-black mt-2 uppercase tracking-tight text-white">{{ $recipient }}</p>
                <p class="text-xs mt-3 leading-snug font-semibold text-white">
                    {{ $address }}@if($region), {{ $region }}@endif
                </p>
                <p class="text-sm font-black mt-3 text-white">{{ $phone }}</p>
            </div>

            <div class="border-2 border-black h-24 flex flex-col items-center justify-center space-y-2">
                <div class="flex space-x-1">
                    @for($i=0; $i<45; $i++)
                        <div class="h-12 bg-black" style="width: {{ rand(1, 4) }}px"></div>
                    @endfor
                </div>
                <p class="text-[10px] font-black tracking-[0.6em] text-black uppercase">ORD{{ $order->id }}{{ \Illuminate\Support\Str::upper(\Illuminate\Support\Str::substr($recipient, 0, 4)) }}</p>
            </div>

            <div class="flex justify-between items-center pt-4 border-t border-black">
                <div>
                    <p class="text-[8px] font-bold text-gray-600 uppercase">Logistic</p>
                    <p class="text-lg font-black italic tracking-tighter uppercase text-black">{{ $order->shipping_courier ?? 'JNE Trucking' }}</p>
                    <p class="text-[9px] font-bold text-gray-600 uppercase mt-1">{{ $totalItems }} item</p>
                </div>
                <div class="text-right">
                    <p class="text-[10px] font-bold uppercase border-2 border-black px-3 py-1 text-black">FRAGILE</p>
                </div>
            </div>
        </div>
    </div>

</body>
</html>
