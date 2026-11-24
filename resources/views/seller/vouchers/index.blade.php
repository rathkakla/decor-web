<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Decor Seller Portal - Voucher Management</title>
    <script src="https://cdn.tailwindcss.com"></script>
    @include('seller.partials.head')
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

    <main id="main-content" class="flex-1 flex flex-col ml-0 md:ml-64 sidebar-transition min-h-screen">
        @include('seller.partials.header', ['title' => 'Voucher Management'])

        <div class="p-4 sm:p-6 lg:p-8 space-y-6 flex-1">
            <div class="flex flex-col sm:flex-row sm:items-end justify-between gap-4">
                <div>
                    <div class="text-[10px] font-bold text-primary uppercase tracking-widest mb-1">Promotions</div>
                    <h2 class="text-2xl font-bold text-gray-900 tracking-tight">Voucher Management</h2>
                    <p class="text-xs text-gray-500 mt-1">Kelola kode promo dan diskon untuk pelanggan toko Anda.</p>
                </div>
                <a href="{{ route('seller.vouchers.create') }}" class="h-10 px-4 bg-primary text-white rounded-lg text-xs font-semibold hover:bg-opacity-90 transition-all inline-flex items-center justify-center gap-2 whitespace-nowrap shrink-0">
                    <i class="fa-solid fa-plus"></i>
                    <span>Create New Voucher</span>
                </a>
            </div>

            @if(session('success'))
                <div class="bg-green-50 border border-green-200 text-green-600 px-4 py-3 rounded-xl text-sm font-bold flex items-center">
                    <i class="fa-solid fa-circle-check mr-2"></i>
                    {{ session('success') }}
                </div>
            @endif

            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="px-6 py-5 border-b border-gray-100 flex flex-col lg:flex-row lg:items-center justify-between gap-4">
                    <div class="flex items-center gap-2.5 shrink-0">
                        <h3 class="font-bold text-gray-900 text-sm whitespace-nowrap">Active &amp; Scheduled Vouchers</h3>
                        <span class="px-2.5 py-0.5 bg-gray-100 text-gray-600 rounded-full text-[10px] font-bold uppercase tracking-wider whitespace-nowrap shrink-0">
                            {{ $vouchers->total() }} Voucher
                        </span>
                    </div>

                    <form action="{{ route('seller.vouchers.index') }}" method="GET" class="flex flex-wrap sm:flex-nowrap items-center gap-2.5">
                        <div class="relative w-full sm:w-56">
                            <i class="fa-solid fa-magnifying-glass absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-xs pointer-events-none"></i>
                            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari kode atau nama..." class="w-full h-10 bg-white border border-gray-200 rounded-lg pl-9 pr-3 text-xs font-medium text-gray-700 focus:outline-none focus:border-primary focus:ring-2 focus:ring-primary/10 transition-all">
                        </div>
                        <div class="relative w-full sm:w-auto shrink-0">
                            <i class="fa-solid fa-filter absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-xs pointer-events-none"></i>
                            <select name="status" onchange="this.form.submit()" aria-label="Filter Status Voucher" class="w-full sm:w-auto h-10 appearance-none bg-white text-xs font-medium text-gray-600 border border-gray-200 rounded-lg pl-9 pr-9 focus:outline-none focus:border-primary focus:ring-2 focus:ring-primary/10 cursor-pointer">
                                <option value="">Semua Status</option>
                                <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Aktif</option>
                                <option value="scheduled" {{ request('status') === 'scheduled' ? 'selected' : '' }}>Terjadwal</option>
                                <option value="ended" {{ request('status') === 'ended' ? 'selected' : '' }}>Berakhir / Habis</option>
                            </select>
                            <i class="fa-solid fa-chevron-down absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 text-[10px] pointer-events-none"></i>
                        </div>
                        <button type="submit" class="h-10 px-4 bg-primary text-white rounded-lg text-xs font-semibold hover:bg-opacity-90 transition-all whitespace-nowrap shrink-0">Cari</button>
                        @if(request()->filled('search') || request()->filled('status'))
                            <a href="{{ route('seller.vouchers.index') }}" class="h-10 px-3 inline-flex items-center justify-center border border-gray-200 text-gray-600 hover:border-primary hover:text-primary rounded-lg text-xs font-semibold transition-all whitespace-nowrap shrink-0">Reset</a>
                        @endif
                    </form>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left">
                        <thead>
                            <tr class="bg-gray-50/50">
                                <th class="px-6 py-3.5 text-[10px] font-bold text-gray-400 uppercase tracking-widest">Code</th>
                                <th class="px-6 py-3.5 text-[10px] font-bold text-gray-400 uppercase tracking-widest">Name</th>
                                <th class="px-6 py-3.5 text-[10px] font-bold text-gray-400 uppercase tracking-widest">Discount</th>
                                <th class="px-6 py-3.5 text-[10px] font-bold text-gray-400 uppercase tracking-widest">Period</th>
                                <th class="px-6 py-3.5 text-[10px] font-bold text-gray-400 uppercase tracking-widest">Quota</th>
                                <th class="px-6 py-3.5 text-[10px] font-bold text-gray-400 uppercase tracking-widest text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50">
                            @forelse($vouchers as $voucher)
                            <tr class="hover:bg-gray-50/50 transition-all">
                                <td class="px-6 py-4">
                                    <span class="px-3 py-1 bg-amber-50 text-amber-700 text-[10px] font-bold rounded-lg border border-amber-200 uppercase tracking-widest">
                                        {{ $voucher->code }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="text-xs font-bold text-gray-700">{{ $voucher->name }}</span>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex flex-col">
                                        <span class="text-xs font-bold text-gray-900">
                                            @if($voucher->discount_type == 'percentage')
                                                {{ $voucher->discount_value }}% OFF
                                            @else
                                                Rp {{ number_format($voucher->discount_value, 0, ',', '.') }} OFF
                                            @endif
                                        </span>
                                        <span class="text-[9px] text-gray-400 font-bold uppercase tracking-tight">Min. Rp {{ number_format($voucher->min_purchase, 0, ',', '.') }}</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex flex-col">
                                        <span class="text-[10px] font-bold text-gray-600">{{ date('d M Y', strtotime($voucher->start_date)) }}</span>
                                        <span class="text-[9px] text-gray-400 font-medium">Until {{ date('d M Y', strtotime($voucher->end_date)) }}</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="text-xs font-bold text-gray-600">{{ $voucher->quota }} Left</span>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <div class="flex items-center justify-center space-x-2">
                                        <div class="relative group/action">
                                            <a href="{{ route('seller.vouchers.edit', $voucher->id) }}" class="w-9 h-9 rounded-lg bg-blue-50 text-blue-600 hover:bg-blue-100 inline-flex items-center justify-center transition-all" aria-label="Edit Voucher" title="Edit Voucher">
                                                <i class="fa-solid fa-pen-to-square text-xs"></i>
                                            </a>
                                            <span class="pointer-events-none absolute right-full top-1/2 -translate-y-1/2 mr-2 whitespace-nowrap rounded-md bg-gray-900 px-2 py-1 text-[10px] font-semibold text-white opacity-0 transition-opacity group-hover/action:opacity-100 group-focus-within/action:opacity-100 z-20 shadow-sm">Edit Voucher</span>
                                        </div>
                                        <form action="{{ route('seller.vouchers.delete', $voucher->id) }}" method="POST" onsubmit="return confirm('Hapus voucher ini?')" class="relative group/action inline-block">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="w-9 h-9 rounded-lg bg-red-50 text-red-600 hover:bg-red-100 inline-flex items-center justify-center transition-all" aria-label="Hapus Voucher" title="Hapus Voucher">
                                                <i class="fa-solid fa-trash text-xs"></i>
                                            </button>
                                            <span class="pointer-events-none absolute right-full top-1/2 -translate-y-1/2 mr-2 whitespace-nowrap rounded-md bg-gray-900 px-2 py-1 text-[10px] font-semibold text-white opacity-0 transition-opacity group-hover/action:opacity-100 group-focus-within/action:opacity-100 z-20 shadow-sm">Hapus Voucher</span>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="px-6 py-16 text-center">
                                    <p class="text-sm font-semibold text-gray-500">
                                        @if(request()->filled('search') || request()->filled('status'))
                                            Voucher tidak ditemukan untuk filter atau pencarian ini.
                                        @else
                                            Belum ada voucher yang dibuat.
                                        @endif
                                    </p>
                                    @if(request()->filled('search') || request()->filled('status'))
                                        <a href="{{ route('seller.vouchers.index') }}" class="inline-block mt-2 text-xs font-semibold text-primary hover:underline">Reset filter &amp; pencarian</a>
                                    @endif
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                @include('partials.pagination', ['paginator' => $vouchers, 'label' => 'voucher'])
            </div>
        </div>

        @include('partials.footer')
    </main>

</body>
</html>