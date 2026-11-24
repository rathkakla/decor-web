<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Decor Seller Portal - Sales Report</title>
    <script src="https://cdn.tailwindcss.com"></script>
    @include('seller.partials.head')
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body class="text-gray-800">

    @include('seller.partials.sidebar')

    <main id="main-content" class="flex-1 flex flex-col ml-0 md:ml-64 sidebar-transition min-h-screen">
        @php
            $pdfParams = array_filter([
                'start_date' => $startDate,
                'end_date' => $endDate,
                'search' => $search ?? null,
                'category_id' => $categoryId ?? null,
            ], fn($v) => $v !== null && $v !== '');
            $selectedCategory = $categories->firstWhere('id', (int) ($categoryId ?? 0));
        @endphp
        @include('seller.partials.header', ['title' => 'Business Analytics'])

        <div class="p-4 sm:p-6 lg:p-8 space-y-6 flex-1">
            @if(session('warning'))
            <div class="bg-amber-50 border border-amber-200 text-amber-800 p-4 rounded-xl text-xs font-semibold flex items-center gap-3 shadow-sm">
                <i class="fa-solid fa-triangle-exclamation text-amber-600 text-base"></i>
                <span>{{ session('warning') }}</span>
            </div>
            @endif
            @if($errors->any())
            <div class="bg-red-50 text-red-600 p-4 rounded-xl text-xs font-semibold border border-red-100 flex items-center gap-3 shadow-sm">
                <i class="fa-solid fa-triangle-exclamation text-base"></i>
                <span>{{ $errors->first() }}</span>
            </div>
            @endif

            <div>
                <div class="text-[10px] font-bold text-primary uppercase tracking-widest mb-1">Business Analytics</div>
                <h2 class="text-2xl font-bold text-gray-900 tracking-tight">Laporan Penjualan</h2>
                <p class="text-xs text-gray-500 mt-1">Riwayat transaksi dan ringkasan performa toko.</p>
            </div>

            <form action="{{ route('seller.reports') }}" method="GET" class="flex flex-col lg:flex-row justify-between items-start lg:items-end gap-4 bg-white p-5 rounded-xl border border-gray-100 shadow-sm">
                @if(!empty($search))
                <input type="hidden" name="search" value="{{ $search }}">
                @endif
                @if(!empty($categoryId))
                <input type="hidden" name="category_id" value="{{ $categoryId }}">
                @endif
                <div class="flex flex-wrap items-end gap-3 w-full lg:w-auto">
                    <div class="flex flex-col w-full sm:w-44 space-y-1">
                        <label for="sellerReportStartDate" class="text-[11px] font-semibold text-gray-500">Dari Tanggal</label>
                        <input id="sellerReportStartDate" type="date" name="start_date" value="{{ $startDate }}" max="{{ $endDate }}" class="h-10 bg-white border border-gray-200 rounded-lg px-3 text-xs font-medium text-gray-700 outline-none focus:border-primary focus:ring-2 focus:ring-primary/10">
                    </div>
                    <div class="flex flex-col w-full sm:w-44 space-y-1">
                        <label for="sellerReportEndDate" class="text-[11px] font-semibold text-gray-500">Sampai Tanggal</label>
                        <input id="sellerReportEndDate" type="date" name="end_date" value="{{ $endDate }}" min="{{ $startDate }}" class="h-10 bg-white border border-gray-200 rounded-lg px-3 text-xs font-medium text-gray-700 outline-none focus:border-primary focus:ring-2 focus:ring-primary/10">
                    </div>
                    <button type="submit" class="h-10 bg-primary text-white px-4 rounded-lg text-xs font-semibold hover:bg-opacity-90 transition-all whitespace-nowrap">
                        Terapkan
                    </button>
                </div>

                <a href="{{ route('seller.reports.download', $pdfParams) }}" class="w-full lg:w-auto h-10 inline-flex items-center justify-center gap-2 bg-white border border-primary/40 text-primary hover:bg-primary hover:text-white px-4 rounded-lg text-xs font-semibold transition-all whitespace-nowrap">
                    <i class="fa-solid fa-file-pdf"></i>
                    <span>Download PDF</span>
                </a>
            </form>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- Total Pendapatan -->
                <div class="bg-white p-6 rounded-xl border border-gray-100 shadow-sm relative overflow-hidden">
                    <div class="absolute bottom-0 left-0 right-0 h-1 bg-green-600"></div>
                    <div class="w-10 h-10 rounded-lg bg-green-50 text-green-600 flex items-center justify-center mb-4"><i class="fa-solid fa-wallet"></i></div>
                    <p class="text-[10px] text-gray-400 font-bold uppercase tracking-wider mb-2">Total Pendapatan</p>
                    <h3 class="text-2xl font-bold text-gray-900">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</h3>
                    <div class="mt-4 flex items-center text-green-500 space-x-1">
                        <i class="fa-solid fa-arrow-trend-up text-xs"></i>
                        <span class="text-[10px] font-bold">+12.5% vs last month</span>
                    </div>
                </div>

                <!-- Total Pesanan Selesai -->
                <div class="bg-white p-6 rounded-xl border border-gray-100 shadow-sm relative overflow-hidden">
                    <div class="absolute bottom-0 left-0 right-0 h-1 bg-blue-600"></div>
                    <div class="w-10 h-10 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center mb-4"><i class="fa-solid fa-cart-shopping"></i></div>
                    <p class="text-[10px] text-gray-400 font-bold uppercase tracking-wider mb-2">Total Pesanan Selesai</p>
                    <h3 class="text-2xl font-bold text-gray-900">{{ $orderCount }}</h3>
                    <div class="mt-4 flex items-center text-blue-500 space-x-1">
                        <i class="fa-solid fa-circle-check text-xs"></i>
                        <span class="text-[10px] font-bold">Based on selected period</span>
                    </div>
                </div>

                <!-- Rata-rata Order -->
                <div class="bg-white p-6 rounded-xl border border-gray-100 shadow-sm relative overflow-hidden">
                    <div class="absolute bottom-0 left-0 right-0 h-1 bg-amber-600"></div>
                    <div class="w-10 h-10 rounded-lg bg-amber-50 text-amber-600 flex items-center justify-center mb-4"><i class="fa-solid fa-star"></i></div>
                    <p class="text-[10px] text-gray-400 font-bold uppercase tracking-wider mb-2">Rata-rata Order</p>
                    <h3 class="text-2xl font-bold text-gray-900">Rp {{ number_format($averageOrder, 0, ',', '.') }}</h3>
                    <div class="mt-4 flex items-center text-amber-500 space-x-1">
                        <i class="fa-solid fa-bolt text-xs"></i>
                        <span class="text-[10px] font-bold">Stable performance</span>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="px-6 py-5 border-b border-gray-100 flex flex-col lg:flex-row lg:items-center justify-between gap-4">
                    <div>
                        <div class="flex flex-wrap items-center gap-2.5">
                            <h3 class="font-bold text-gray-900 uppercase tracking-widest text-sm">Rincian Transaksi Penjualan</h3>
                            <span class="px-2.5 py-0.5 bg-gray-100 text-gray-600 rounded-full text-[10px] font-bold uppercase tracking-wider">
                                {{ $transactions->total() }} Item
                            </span>
                            @if($selectedCategory)
                            <span class="px-2.5 py-0.5 bg-amber-50 text-primary border border-amber-200 rounded-full text-[10px] font-bold">
                                Kategori: {{ $selectedCategory->name }}
                            </span>
                            @endif
                        </div>
                        <p class="text-[11px] text-gray-400 font-medium mt-0.5">Periode {{ date('d M Y', strtotime($startDate)) }} – {{ date('d M Y', strtotime($endDate)) }}</p>
                    </div>

                    <form action="{{ route('seller.reports') }}" method="GET" class="flex flex-wrap items-center gap-2.5">
                        <input type="hidden" name="start_date" value="{{ $startDate }}">
                        <input type="hidden" name="end_date" value="{{ $endDate }}">
                        <div class="relative">
                            <i class="fa-solid fa-magnifying-glass absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-xs pointer-events-none"></i>
                            <input type="text" name="search" value="{{ $search ?? '' }}" placeholder="Cari order, produk, customer..." class="h-10 pl-9 pr-3 w-56 sm:w-64 text-xs font-medium text-gray-700 bg-white border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary/10 focus:border-primary transition-all">
                        </div>
                        <div class="relative">
                            <i class="fa-solid fa-filter absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-xs pointer-events-none"></i>
                            <select name="category_id" onchange="this.form.submit()" aria-label="Filter Kategori" class="h-10 appearance-none bg-white text-xs font-medium text-gray-600 border border-gray-200 rounded-lg pl-9 pr-9 focus:outline-none focus:border-primary focus:ring-2 focus:ring-primary/10 cursor-pointer">
                                <option value="">Semua Kategori</option>
                                @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ (string) ($categoryId ?? '') === (string) $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                                @endforeach
                            </select>
                            <i class="fa-solid fa-chevron-down absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 text-[10px] pointer-events-none"></i>
                        </div>
                        <button type="submit" class="h-10 px-4 bg-primary text-white rounded-lg text-xs font-semibold hover:bg-opacity-90 transition-all">
                            Cari
                        </button>
                        @if(request()->filled('search') || request()->filled('category_id'))
                        <a href="{{ route('seller.reports', ['start_date' => $startDate, 'end_date' => $endDate]) }}" class="h-10 px-3 inline-flex items-center justify-center border border-gray-200 text-gray-600 hover:border-primary hover:text-primary rounded-lg text-xs font-semibold transition-all">
                            Reset
                        </a>
                        @endif
                    </form>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-gray-50/70 border-b border-gray-100">
                                <th class="px-6 py-3.5 text-[10px] font-bold text-gray-400 uppercase tracking-widest whitespace-nowrap">Tanggal</th>
                                <th class="px-6 py-3.5 text-[10px] font-bold text-gray-400 uppercase tracking-widest whitespace-nowrap">Order ID</th>
                                <th class="px-6 py-3.5 text-[10px] font-bold text-gray-400 uppercase tracking-widest">Produk & Customer</th>
                                <th class="px-6 py-3.5 text-[10px] font-bold text-gray-400 uppercase tracking-widest text-center whitespace-nowrap">Kategori</th>
                                <th class="px-6 py-3.5 text-[10px] font-bold text-gray-400 uppercase tracking-widest text-right whitespace-nowrap">Qty</th>
                                <th class="px-6 py-3.5 text-[10px] font-bold text-gray-400 uppercase tracking-widest text-right whitespace-nowrap">Total Harga</th>
                                <th class="px-6 py-3.5 text-[10px] font-bold text-gray-400 uppercase tracking-widest text-center whitespace-nowrap">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse($transactions as $item)
                            <tr class="hover:bg-gray-50/60 transition-colors">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="text-xs font-semibold text-gray-500">{{ $item->order->created_at->format('d M Y') }}</span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <a href="{{ route('seller.orders.show', $item->order->id) }}" class="text-xs font-bold text-primary hover:underline uppercase">
                                        @if($item->order->return_code)
                                            #DEC-{{ str_replace('RET-', '', $item->order->return_code) }}-RET
                                        @else
                                            #ORD-{{ $item->order->id }}
                                        @endif
                                    </a>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-xs font-bold text-gray-800">{{ $item->product->name }}</div>
                                    <div class="text-[11px] text-gray-400 font-medium mt-0.5">{{ $item->order->customer->user->full_name ?? 'Customer' }}</div>
                                </td>
                                <td class="px-6 py-4 text-center whitespace-nowrap">
                                    <span class="inline-flex items-center px-2.5 py-1 bg-amber-50 text-primary border border-amber-100 text-[10px] font-bold rounded-full">
                                        {{ $item->product->category->name ?? 'N/A' }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-right font-bold text-xs text-gray-700 tabular-nums whitespace-nowrap">{{ $item->quantity }}</td>
                                <td class="px-6 py-4 text-right font-bold text-xs text-gray-900 tabular-nums whitespace-nowrap">Rp {{ number_format($item->price * $item->quantity, 0, ',', '.') }}</td>
                                <td class="px-6 py-4 text-center whitespace-nowrap">
                                    @php
                                        $statusClasses = [
                                            'pending' => 'bg-amber-50 text-amber-600',
                                            'processing' => 'bg-blue-50 text-blue-600',
                                            'shipped' => 'bg-purple-50 text-purple-600',
                                            'delivered' => 'bg-cyan-50 text-cyan-600',
                                            'completed' => 'bg-green-50 text-green-600',
                                            'cancelled' => 'bg-red-50 text-red-600',
                                        ];
                                        $class = $statusClasses[$item->order->status] ?? 'bg-gray-50 text-gray-600';
                                    @endphp
                                    <span class="inline-flex items-center px-2.5 py-1 {{ $class }} text-[9px] font-bold rounded-full uppercase tracking-widest">
                                        {{ $item->order->status == 'completed' ? 'SELESAI' : ($item->order->status == 'shipped' ? 'DIKIRIM' : strtoupper($item->order->status)) }}
                                    </span>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="px-6 py-16 text-center">
                                    <div class="w-12 h-12 rounded-full bg-gray-50 text-gray-300 flex items-center justify-center mx-auto mb-3">
                                        <i class="fa-solid fa-receipt text-lg"></i>
                                    </div>
                                    <p class="text-sm font-bold text-gray-500">Tidak ada transaksi ditemukan pada filter atau rentang tanggal ini.</p>
                                    @if(request()->filled('search') || request()->filled('category_id'))
                                    <a href="{{ route('seller.reports', ['start_date' => $startDate, 'end_date' => $endDate]) }}" class="inline-block mt-3 text-xs font-bold text-primary hover:underline">Reset pencarian & kategori</a>
                                    @endif
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                @include('partials.pagination', ['paginator' => $transactions, 'label' => 'transaksi'])
            </div>
        </div>

        @include('partials.footer')
    </main>

    <script>
        (function() {
            const startInput = document.getElementById('sellerReportStartDate');
            const endInput = document.getElementById('sellerReportEndDate');
            if (startInput && endInput) {
                startInput.addEventListener('change', function() {
                    if (this.value) {
                        endInput.min = this.value;
                        if (endInput.value && endInput.value < this.value) {
                            endInput.value = this.value;
                        }
                    }
                });
                endInput.addEventListener('change', function() {
                    if (this.value) {
                        startInput.max = this.value;
                        if (startInput.value && startInput.value > this.value) {
                            startInput.value = this.value;
                        }
                    }
                });
            }
        })();
    </script>
</body>
</html>