<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Decor Seller - Detail Komplain</title>
    <script src="https://cdn.tailwindcss.com"></script>
    @include('seller.partials.head')
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        .badge-investigating { background-color: #8D592C; color: white; }
    </style>
</head>
<body class="text-gray-800">

    @include('seller.partials.sidebar')

    @php
        $statusLabel = ucfirst(str_replace('_', ' ', $return->status));
        $statusBadge = match($return->status) {
            'approved' => 'bg-green-100 text-green-700',
            'rejected' => 'bg-red-100 text-red-600',
            default => 'bg-amber-100 text-amber-700',
        };
        $extraAction = '<span class="'.$statusBadge.' text-[9px] px-2.5 py-1 rounded-full uppercase tracking-widest font-bold">'.$statusLabel.'</span>';
    @endphp

    <main id="main-content" class="flex-1 flex flex-col ml-0 md:ml-64 sidebar-transition min-h-screen">
        @include('seller.partials.header', ['title' => 'Komplain #DEC-' . $return->order_id, 'extra_action' => $extraAction])

        <div class="p-4 sm:p-6 lg:p-8 grid grid-cols-1 lg:grid-cols-12 gap-6 flex-1">
            <div class="lg:col-span-8 space-y-6">
                <div class="bg-white p-5 sm:p-8 rounded-xl border border-gray-100 shadow-sm space-y-6">
                    <div class="flex flex-col sm:flex-row sm:justify-between sm:items-start gap-4">
                        <div>
                            <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1">Customer Details</p>
                            <h3 class="text-xl sm:text-2xl font-bold text-gray-900 tracking-tight">{{ $return->order->customer->user->full_name ?? 'Customer' }}</h3>
                            <p class="text-sm font-semibold text-gray-400">{{ $return->order->customer->phone ?? 'No Phone Number' }}</p>
                        </div>
                        <div class="sm:text-right">
                            <p class="text-[9px] font-bold text-gray-300 uppercase tracking-widest">Member Since</p>
                            <p class="text-sm font-bold text-gray-800">{{ optional($return->order->customer->user->created_at)->format('M Y') ?? '-' }}</p>
                        </div>
                    </div>

                    <div class="space-y-3">
                        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Order Information</p>
                        <div class="overflow-x-auto border border-gray-100 rounded-xl">
                            <table class="w-full sm:min-w-[480px] text-left text-[10px] font-bold">
                                <thead class="bg-gray-50/70 text-gray-400 uppercase tracking-widest">
                                    <tr>
                                        <th class="px-4 sm:px-6 py-3">Order ID</th>
                                        <th class="px-4 sm:px-6 py-3 hidden sm:table-cell">Date</th>
                                        <th class="px-4 sm:px-6 py-3">Status</th>
                                        <th class="px-4 sm:px-6 py-3 text-right">Total</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-100">
                                    <tr>
                                        <td class="px-4 sm:px-6 py-4 font-bold text-gray-800 whitespace-nowrap">#DEC-{{ $return->order->id }}</td>
                                        <td class="px-4 sm:px-6 py-4 text-gray-400 hidden sm:table-cell">{{ $return->order->created_at->format('M d, Y') }}</td>
                                        <td class="px-4 sm:px-6 py-4">
                                            <span class="bg-gray-100 text-gray-500 px-2 py-0.5 rounded text-[8px] uppercase">{{ $return->order->status }}</span>
                                        </td>
                                        <td class="px-4 sm:px-6 py-4 text-right font-bold text-sm text-gray-900">Rp {{ number_format($return->order->total_price, 0, ',', '.') }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="bg-white p-5 sm:p-8 rounded-xl border border-gray-100 shadow-sm space-y-6">
                    <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-4">
                        <div>
                            <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-2">Issue Summary</p>
                            <h4 class="text-xl sm:text-2xl font-bold text-gray-900 leading-tight">Return Reason</h4>
                        </div>
                        <div class="sm:text-right">
                            <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1">Return Type</p>
                            <span class="inline-block px-3 py-1 bg-primary/10 text-primary rounded-full text-[10px] font-bold uppercase tracking-widest">
                                {{ $return->return_type == 'refund' ? 'Refund' : 'Ganti Barang Baru' }}
                            </span>
                        </div>
                    </div>
                    <div class="border-l-4 border-primary pl-5 sm:pl-6 py-2">
                        <p class="text-sm font-semibold text-gray-600 leading-relaxed italic">
                            "{{ $return->reason }}"
                        </p>
                    </div>

                    @if($return->return_type == 'refund')
                    <div class="p-4 bg-gray-50 rounded-xl border border-gray-100">
                        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-2">Bank Account for Refund</p>
                        <p class="text-sm font-bold text-gray-800">{{ $return->bank_account_number }}</p>
                        <p class="text-[9px] text-gray-400 mt-1 italic">Note: Refund harus diproses manual oleh seller ke rekening tersebut.</p>
                    </div>
                    @endif
                </div>

                <div class="space-y-3">
                    <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Customer Evidence</p>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 sm:gap-6">
                        <div class="space-y-2">
                            <p class="text-[9px] font-bold text-gray-300 uppercase tracking-widest">Video Proof</p>
                            <div class="aspect-video rounded-xl overflow-hidden shadow-sm border border-gray-200 bg-black">
                                @if($return->video_proof)
                                    <video controls class="w-full h-full">
                                        <source src="{{ asset('storage/' . $return->video_proof) }}" type="video/mp4">
                                        Your browser does not support the video tag.
                                    </video>
                                @else
                                    <div class="w-full h-full flex items-center justify-center">
                                        <i class="fa-solid fa-video-slash text-gray-500"></i>
                                    </div>
                                @endif
                            </div>
                        </div>
                        <div class="space-y-2">
                            <p class="text-[9px] font-bold text-gray-300 uppercase tracking-widest">Photo Proof</p>
                            <div class="aspect-video rounded-xl overflow-hidden shadow-sm border border-gray-200 bg-gray-50">
                                @if($return->photo_proof)
                                    <a href="{{ asset('storage/' . $return->photo_proof) }}" target="_blank">
                                        <img src="{{ asset('storage/' . $return->photo_proof) }}" class="w-full h-full object-cover hover:scale-105 transition-transform">
                                    </a>
                                @else
                                    <div class="w-full h-full flex items-center justify-center">
                                        <i class="fa-solid fa-image text-gray-500"></i>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <div class="space-y-3">
                    <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Original Product Images</p>
                    <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 sm:gap-4">
                        @if($return->order->orderItems->first() && $return->order->orderItems->first()->product->images->isNotEmpty())
                            @foreach($return->order->orderItems->first()->product->images as $img)
                            <div class="aspect-square rounded-xl overflow-hidden shadow-sm border border-gray-200">
                                <img src="{{ $img->img_url }}" class="w-full h-full object-cover">
                            </div>
                            @endforeach
                        @else
                        <div class="col-span-2 sm:col-span-4 p-8 text-center text-gray-400 font-bold border border-gray-200 border-dashed rounded-xl">
                            No product images available.
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            <div class="lg:col-span-4 space-y-6">
                <div class="bg-white p-5 sm:p-8 rounded-xl border border-gray-100 shadow-sm space-y-5">
                    <h4 class="text-[10px] font-bold text-gray-400 uppercase tracking-[0.2em]">Resolution Center</h4>
                    @if($return->status == 'pending')
                    <form action="{{ route('seller.returns.approve', $return->id) }}" method="POST">
                        @csrf
                        <button type="submit" class="w-full bg-primary text-white py-3.5 rounded-xl text-[10px] font-bold uppercase tracking-[0.1em] shadow-lg shadow-primary/20 hover:opacity-90 transition-all mb-3">
                            <i class="fa-solid fa-check-circle mr-2"></i> Accept Return
                        </button>
                    </form>
                    <form action="{{ route('seller.returns.reject', $return->id) }}" method="POST">
                        @csrf
                        <button type="submit" class="w-full bg-white border border-gray-200 text-gray-800 py-3.5 rounded-xl text-[10px] font-bold uppercase tracking-[0.1em] hover:bg-gray-50 transition-all">
                            <i class="fa-solid fa-xmark-circle mr-2"></i> Reject Claim
                        </button>
                    </form>
                    @else
                    <div class="text-center p-4 border border-gray-100 bg-gray-50 rounded-xl">
                        <p class="text-xs font-bold text-gray-400">Status: <span class="uppercase text-gray-800">{{ $return->status }}</span></p>
                    </div>
                    @endif
                    <div class="pt-1 flex justify-center">
                        <a href="{{ route('seller.chats', $return->order->customer->user_id) }}" class="flex items-center text-[9px] font-bold text-gray-400 hover:text-primary transition-colors uppercase tracking-widest">
                            <i class="fa-solid fa-message mr-2"></i> Chat with Customer
                        </a>
                    </div>
                </div>

                <div class="bg-white p-5 sm:p-8 rounded-xl border border-gray-100 shadow-sm space-y-4">
                    <h4 class="text-[10px] font-bold text-gray-400 uppercase tracking-[0.2em]">Return Details</h4>
                    <div class="bg-gray-50 p-4 rounded-xl border border-gray-100 flex items-start space-x-3">
                        <i class="fa-solid fa-calendar text-gray-300 mt-1 text-xs"></i>
                        <p class="text-[10px] font-bold text-gray-500 leading-relaxed">Requested on {{ \Carbon\Carbon::parse($return->return_date)->format('M d, Y') }}</p>
                    </div>
                    <a href="{{ route('seller.orders.show', $return->order_id) }}" class="inline-flex items-center text-xs font-bold text-primary hover:underline">
                        <span>Lihat Detail Pesanan</span>
                        <i class="fa-solid fa-arrow-right ml-1.5 text-[10px]"></i>
                    </a>
                </div>
            </div>
        </div>

        @include('partials.footer')
    </main>
</body>
</html>
