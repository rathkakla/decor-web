<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Decor Seller - Customer Complaints</title>
    <script src="https://cdn.tailwindcss.com"></script>
    @include('seller.partials.head')
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap');
        body { background-color: #F8F6F4; font-family: 'Plus Jakarta Sans', sans-serif; overflow-x: hidden; }
        .bg-primary { background-color: #B5733A; }
        .text-primary { color: #B5733A; }
        .active-link { background-color: rgba(181, 115, 58, 0.1); color: #B5733A; border-right: 4px solid #B5733A; }
        .sidebar-transition { transition: transform 0.3s ease-in-out, margin 0.3s ease-in-out; }
        .sidebar-hidden { transform: translateX(-100%); }
        .main-expanded { margin-left: 0 !important; }
    </style>
</head>
<body class="text-gray-800">

    @include('seller.partials.sidebar')

    <main id="main-content" class="flex-1 flex flex-col ml-0 md:ml-64 sidebar-transition min-h-screen">
        @include('seller.partials.header', ['title' => 'Customer Complaints'])

        <div class="p-4 sm:p-6 lg:p-8 space-y-6 sm:space-y-8 flex-1">
            <div>
                <div class="text-[10px] font-bold text-primary uppercase tracking-widest mb-1">Resolution Center</div>
                <h2 class="text-2xl font-bold text-gray-900 tracking-tight">Customer Complaints</h2>
                <p class="text-xs text-gray-500 mt-1">Pusat penanganan komplain dan pengajuan retur pelanggan.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 sm:gap-6">
                <div class="bg-white p-6 rounded-xl border border-gray-100 shadow-sm relative overflow-hidden">
                    <div class="absolute bottom-0 left-0 right-0 h-1 bg-orange-500"></div>
                    <div class="flex justify-between items-start mb-4">
                        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">New Complaints</p>
                        <i class="fa-regular fa-copy text-gray-300"></i>
                    </div>
                    <div class="flex items-end space-x-3 text-gray-800">
                        <h3 class="text-2xl font-bold leading-none">{{ sprintf('%02d', $counts['pending']) }}</h3>
                        <span class="text-xs font-bold text-orange-500 mb-1">Pending approval</span>
                    </div>
                </div>
                <div class="bg-white p-6 rounded-xl border border-gray-100 shadow-sm relative overflow-hidden">
                    <div class="absolute bottom-0 left-0 right-0 h-1 bg-blue-500"></div>
                    <div class="flex justify-between items-start mb-4">
                        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">In Investigation</p>
                        <i class="fa-solid fa-chart-line text-gray-300"></i>
                    </div>
                    <div class="flex items-end space-x-3 text-gray-800">
                        <h3 class="text-2xl font-bold leading-none">00</h3>
                        <span class="text-xs font-bold text-gray-400 mb-1 tracking-widest">Steady</span>
                    </div>
                </div>
                <div class="bg-white p-6 rounded-xl border border-gray-100 shadow-sm relative overflow-hidden">
                    <div class="absolute bottom-0 left-0 right-0 h-1 bg-green-600"></div>
                    <div class="flex justify-between items-start mb-4">
                        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Resolved</p>
                        <i class="fa-regular fa-circle-check text-gray-300"></i>
                    </div>
                    <div class="flex items-end space-x-3 text-gray-800">
                        <h3 class="text-2xl font-bold leading-none">{{ sprintf('%02d', $counts['resolved']) }}</h3>
                        <span class="text-xs font-bold text-green-500 mb-1">Resolved cases</span>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100 flex flex-col lg:flex-row lg:items-center justify-between gap-4">
                    <div class="flex space-x-8 text-[10px] font-bold uppercase tracking-widest text-gray-400 overflow-x-auto overflow-y-hidden whitespace-nowrap hide-scrollbar">
                        <a href="{{ route('seller.complaint.index', ['search' => request('search')]) }}" class="pb-3 -mb-3.5 {{ $currentStatus == 'all' ? 'border-b-2 border-primary text-primary' : 'hover:text-primary transition-colors' }}">All ({{ $counts['all'] }})</a>
                        <a href="{{ route('seller.complaint.index', ['status' => 'pending', 'search' => request('search')]) }}" class="pb-3 -mb-3.5 {{ $currentStatus == 'pending' ? 'border-b-2 border-primary text-primary' : 'hover:text-primary transition-colors' }}">Pending ({{ $counts['pending'] }})</a>
                        <a href="{{ route('seller.complaint.index', ['status' => 'resolved', 'search' => request('search')]) }}" class="pb-3 -mb-3.5 {{ $currentStatus == 'resolved' ? 'border-b-2 border-primary text-primary' : 'hover:text-primary transition-colors' }}">Resolved ({{ $counts['resolved'] }})</a>
                    </div>
                    <form action="{{ route('seller.complaint.index') }}" method="GET" class="flex items-center gap-2 w-full lg:w-auto">
                        @if($currentStatus !== 'all')
                        <input type="hidden" name="status" value="{{ $currentStatus }}">
                        @endif
                        <div class="relative w-full lg:w-64">
                            <i class="fa-solid fa-magnifying-glass absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-xs pointer-events-none"></i>
                            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari Case ID, customer, alasan..." class="w-full h-10 bg-white border border-gray-200 rounded-lg pl-9 pr-3 text-xs font-medium text-gray-700 focus:outline-none focus:border-primary focus:ring-2 focus:ring-primary/10">
                        </div>
                        <button type="submit" class="h-10 px-4 bg-primary text-white rounded-lg text-xs font-semibold hover:bg-opacity-90 transition-all whitespace-nowrap">Cari</button>
                        @if(request('search'))
                        <a href="{{ route('seller.complaint.index', $currentStatus !== 'all' ? ['status' => $currentStatus] : []) }}" class="h-10 px-3 inline-flex items-center justify-center border border-gray-200 text-gray-600 hover:border-primary hover:text-primary rounded-lg text-xs font-semibold transition-all">Reset</a>
                        @endif
                    </form>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full min-w-[760px] text-left">
                        <thead class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">
                            <tr class="bg-gray-50/70 border-b border-gray-100">
                                <th class="px-6 py-3.5 whitespace-nowrap">Case ID</th>
                                <th class="px-6 py-3.5 whitespace-nowrap">Customer</th>
                                <th class="px-6 py-3.5">Issue Description</th>
                                <th class="px-6 py-3.5 whitespace-nowrap">Date</th>
                                <th class="px-6 py-3.5 text-right whitespace-nowrap">Action</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 text-gray-800">
                            @forelse($returns as $return)
                            @php
                                $tag = strtoupper($return->status);
                                $tag_col = match($return->status) {
                                    'pending' => 'bg-orange-400',
                                    'approved' => 'bg-green-500',
                                    'rejected' => 'bg-red-500',
                                    default => 'bg-gray-400'
                                };
                                $actionLabel = $return->status == 'pending' ? 'Lihat Detail' : 'Lihat Arsip';
                                $actionBtn = $return->status == 'pending'
                                    ? 'w-9 h-9 rounded-lg border border-primary/20 bg-primary/10 text-primary hover:bg-primary hover:text-white inline-flex items-center justify-center transition-all'
                                    : 'w-9 h-9 rounded-lg border border-gray-200 bg-gray-50 text-gray-500 hover:bg-gray-100 hover:text-gray-800 inline-flex items-center justify-center transition-all';
                                $tooltipClass = 'pointer-events-none absolute right-full top-1/2 -translate-y-1/2 mr-2 whitespace-nowrap rounded-md bg-gray-900 px-2 py-1 text-[10px] font-semibold text-white opacity-0 transition-opacity group-hover/action:opacity-100 group-focus-within/action:opacity-100 z-20 shadow-sm';
                            @endphp
                            <tr class="hover:bg-gray-50 transition-colors group">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="text-xs font-semibold text-gray-500 block mb-1">#DEC-{{ $return->order_id }}</span>
                                    <span class="{{ $tag_col }} text-white text-[8px] font-bold px-2 py-0.5 rounded uppercase">{{ $tag }}</span>
                                </td>
                                <td class="px-6 py-4">
                                    <h4 class="text-sm font-bold leading-none text-gray-800">{{ $return->order->customer->user->full_name ?? 'Customer' }}</h4>
                                    <span class="text-[9px] font-bold text-gray-400 uppercase">Order #{{ $return->order_id }}</span>
                                </td>
                                <td class="px-6 py-4 text-xs font-semibold text-gray-600">{{ $return->reason }}</td>
                                <td class="px-6 py-4 text-xs font-semibold text-gray-500 whitespace-nowrap">{{ \Carbon\Carbon::parse($return->return_date)->format('d M Y') }}</td>
                                <td class="px-6 py-4 text-right">
                                    <div class="relative group/action inline-flex justify-end">
                                        <a href="{{ route('seller.complaint.detail', $return->id) }}" class="{{ $actionBtn }}" aria-label="{{ $actionLabel }}" title="{{ $actionLabel }}">
                                            <i class="fa-solid fa-eye text-xs"></i>
                                        </a>
                                        <span class="{{ $tooltipClass }}">{{ $actionLabel }}</span>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="px-6 py-16 text-center">
                                    <div class="w-12 h-12 rounded-full bg-gray-50 text-gray-300 flex items-center justify-center mx-auto mb-3">
                                        <i class="fa-regular fa-circle-check text-lg"></i>
                                    </div>
                                    <p class="text-sm font-bold text-gray-500">Belum ada komplain atau pengembalian.</p>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                @include('partials.pagination', ['paginator' => $returns, 'label' => 'komplain'])
            </div>
        </div>

        @include('partials.footer')
    </main>

    <script>
        const btn = document.getElementById('toggle-sidebar');
        const sidebar = document.getElementById('sidebar');
        const main = document.getElementById('main-content');
        btn.addEventListener('click', () => { 
            sidebar.classList.toggle('sidebar-hidden'); 
            main.classList.toggle('main-expanded'); 
        });
    </script>
</body>
</html>