<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Decor Seller Portal - Voucher Management</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&display=swap');
        body { background-color: #F8F6F4; font-family: 'Plus Jakarta Sans', sans-serif; }
        .bg-primary { background-color: #B5733A; }
        .text-primary { color: #B5733A; }
    </style>
</head>
<body class="text-gray-800">

    @include('seller.partials.sidebar')

    <main id="main-content" class="flex-1 flex flex-col ml-64 sidebar-transition min-h-screen">
        @php
            $extraAction = '<a href="'.route('seller.vouchers.create').'" class="bg-primary text-white px-6 py-2 rounded-lg text-[10px] font-black uppercase tracking-widest hover:bg-opacity-90 transition-all flex items-center space-x-2"><i class="fa-solid fa-plus"></i><span>Create New Voucher</span></a>';
        @endphp
        @include('seller.partials.header', ['title' => 'Voucher Management', 'extra_action' => $extraAction])

        <div class="p-8 space-y-8 flex-1">
            @if(session('success'))
                <div class="bg-green-50 border border-green-200 text-green-600 px-4 py-3 rounded-xl text-sm font-bold flex items-center">
                    <i class="fa-solid fa-circle-check mr-2"></i>
                    {{ session('success') }}
                </div>
            @endif

            <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="p-8 border-b border-gray-50 flex justify-between items-center">
                    <h3 class="font-bold text-gray-900 uppercase tracking-widest text-sm">Active & Scheduled Vouchers</h3>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left">
                        <thead>
                            <tr class="bg-gray-50/50">
                                <th class="px-8 py-4 text-[10px] font-black text-gray-400 uppercase tracking-widest">Code</th>
                                <th class="px-8 py-4 text-[10px] font-black text-gray-400 uppercase tracking-widest">Name</th>
                                <th class="px-8 py-4 text-[10px] font-black text-gray-400 uppercase tracking-widest">Discount</th>
                                <th class="px-8 py-4 text-[10px] font-black text-gray-400 uppercase tracking-widest">Period</th>
                                <th class="px-8 py-4 text-[10px] font-black text-gray-400 uppercase tracking-widest">Quota</th>
                                <th class="px-8 py-4 text-[10px] font-black text-gray-400 uppercase tracking-widest text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50">
                            @forelse($vouchers as $voucher)
                            <tr class="hover:bg-gray-50/50 transition-all">
                                <td class="px-8 py-5">
                                    <span class="px-3 py-1 bg-amber-50 text-amber-700 text-[10px] font-black rounded-lg border border-amber-200 uppercase tracking-widest">
                                        {{ $voucher->code }}
                                    </span>
                                </td>
                                <td class="px-8 py-5">
                                    <span class="text-xs font-bold text-gray-700">{{ $voucher->name }}</span>
                                </td>
                                <td class="px-8 py-5">
                                    <div class="flex flex-col">
                                        <span class="text-xs font-black text-gray-900">
                                            @if($voucher->discount_type == 'percentage')
                                                {{ $voucher->discount_value }}% OFF
                                            @else
                                                Rp {{ number_format($voucher->discount_value, 0, ',', '.') }} OFF
                                            @endif
                                        </span>
                                        <span class="text-[9px] text-gray-400 font-bold uppercase tracking-tighter">Min. Rp {{ number_format($voucher->min_purchase, 0, ',', '.') }}</span>
                                    </div>
                                </td>
                                <td class="px-8 py-5">
                                    <div class="flex flex-col">
                                        <span class="text-[10px] font-bold text-gray-600">{{ date('d M Y', strtotime($voucher->start_date)) }}</span>
                                        <span class="text-[9px] text-gray-400 font-medium">Until {{ date('d M Y', strtotime($voucher->end_date)) }}</span>
                                    </div>
                                </td>
                                <td class="px-8 py-5">
                                    <span class="text-xs font-bold text-gray-600">{{ $voucher->quota }} Left</span>
                                </td>
                                <td class="px-8 py-5 text-center">
                                    <div class="flex items-center justify-center space-x-3">
                                        <a href="{{ route('seller.vouchers.edit', $voucher->id) }}" class="p-2 bg-blue-50 text-blue-600 rounded-lg hover:bg-blue-100 transition-all">
                                            <i class="fa-solid fa-pen-to-square"></i>
                                        </a>
                                        <form action="{{ route('seller.vouchers.delete', $voucher->id) }}" method="POST" onsubmit="return confirm('Hapus voucher ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="p-2 bg-red-50 text-red-600 rounded-lg hover:bg-red-100 transition-all">
                                                <i class="fa-solid fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="px-8 py-20 text-center text-gray-400 font-bold italic text-sm">
                                    No vouchers created yet.
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </main>

</body>
</html>