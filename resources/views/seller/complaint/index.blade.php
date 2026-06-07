<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Decor Seller - Customer Complaints</title>
    <script src="https://cdn.tailwindcss.com"></script>
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

    <main id="main-content" class="flex-1 flex flex-col ml-64 sidebar-transition min-h-screen">
        
        @php
            $extraAction = '<div class="relative ml-4"><i class="fa-solid fa-magnifying-glass absolute left-3 top-1/2 -translate-y-1/2 text-white/50 text-xs"></i><input type="text" placeholder="Search Case IDs..." class="bg-white/10 border border-white/20 rounded-full py-1.5 pl-9 pr-4 text-xs text-white placeholder-white/50 outline-none focus:bg-white/20 w-64 transition-all"></div>';
        @endphp
        @include('seller.partials.header', ['title' => 'Customer Complaints', 'extra_action' => $extraAction])

        <div class="p-10 space-y-10 flex-1">
            
            <div>
                <h2 class="text-4xl font-extrabold text-gray-800 tracking-tight">Customer Complaints</h2>
                <p class="text-sm text-gray-400 mt-2 max-w-2xl font-medium">Resolution center for high-end furniture inquiries.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div class="bg-white p-8 rounded-3xl border border-gray-100 shadow-sm relative overflow-hidden">
                    <div class="flex justify-between items-start mb-4">
                        <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">New Complaints</p>
                        <i class="fa-regular fa-copy text-gray-300"></i>
                    </div>
                    <div class="flex items-end space-x-3 text-gray-800">
                        <h3 class="text-5xl font-black leading-none">{{ sprintf('%02d', $counts['pending']) }}</h3>
                        <span class="text-xs font-bold text-orange-500 mb-1">Pending approval</span>
                    </div>
                </div>
                <div class="bg-white p-8 rounded-3xl border border-gray-100 shadow-sm relative overflow-hidden">
                    <div class="flex justify-between items-start mb-4">
                        <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">In Investigation</p>
                        <i class="fa-solid fa-chart-line text-gray-300"></i>
                    </div>
                    <div class="flex items-end space-x-3 text-gray-800">
                        <h3 class="text-5xl font-black leading-none">00</h3>
                        <span class="text-xs font-bold text-gray-400 mb-1 tracking-widest">Steady</span>
                    </div>
                </div>
                <div class="bg-white p-8 rounded-3xl border border-gray-100 shadow-sm relative overflow-hidden">
                    <div class="flex justify-between items-start mb-4">
                        <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Resolved</p>
                        <i class="fa-regular fa-circle-check text-gray-300"></i>
                    </div>
                    <div class="flex items-end space-x-3 text-gray-800">
                        <h3 class="text-5xl font-black leading-none">{{ sprintf('%02d', $counts['resolved']) }}</h3>
                        <span class="text-xs font-bold text-green-500 mb-1">Resolved cases</span>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-[32px] border border-gray-100 shadow-sm overflow-hidden p-4">
                <div class="flex space-x-8 border-b border-gray-50 px-6 pt-4 text-[10px] font-black uppercase tracking-widest text-gray-400">
                    <a href="{{ route('seller.complaint.index') }}" class="pb-4 {{ $currentStatus == 'all' ? 'border-b-2 border-primary text-primary' : 'hover:text-primary transition-colors' }}">All ({{ $counts['all'] }})</a>
                    <a href="{{ route('seller.complaint.index', ['status' => 'pending']) }}" class="pb-4 {{ $currentStatus == 'pending' ? 'border-b-2 border-primary text-primary' : 'hover:text-primary transition-colors' }}">Pending ({{ $counts['pending'] }})</a>
                    <a href="{{ route('seller.complaint.index', ['status' => 'resolved']) }}" class="pb-4 {{ $currentStatus == 'resolved' ? 'border-b-2 border-primary text-primary' : 'hover:text-primary transition-colors' }}">Resolved ({{ $counts['resolved'] }})</a>
                </div>

                <table class="w-full text-left">
                    <thead class="text-[9px] font-black text-gray-300 uppercase tracking-[0.2em]">
                        <tr>
                            <th class="px-8 py-6">Case ID</th>
                            <th class="px-8 py-6">Customer</th>
                            <th class="px-8 py-6">Issue Description</th>
                            <th class="px-8 py-6">Date</th>
                            <th class="px-8 py-6 text-right">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50 text-gray-800">
                        @forelse($returns as $return)
                        @php
                            $tag = strtoupper($return->status);
                            $tag_col = match($return->status) {
                                'pending' => 'bg-orange-400',
                                'approved' => 'bg-green-500',
                                'rejected' => 'bg-red-500',
                                default => 'bg-gray-400'
                            };
                            $act_col = $return->status == 'pending' ? 'text-primary' : 'text-gray-400';
                            $action = $return->status == 'pending' ? 'OPEN DETAILS' : 'VIEW ARCHIVE';
                        @endphp
                        <tr class="hover:bg-gray-50 transition-colors group">
                            <td class="px-8 py-6">
                                <span class="text-xs font-bold text-gray-300 block mb-1">#DEC-{{ $return->order_id }}</span>
                                <span class="{{ $tag_col }} text-white text-[8px] font-black px-2 py-0.5 rounded uppercase">{{ $tag }}</span>
                            </td>
                            <td class="px-8 py-6">
                                <h4 class="text-sm font-black leading-none">{{ $return->order->customer->user->full_name ?? 'Customer' }}</h4>
                                <span class="text-[9px] font-bold text-gray-300 uppercase">Order #{{ $return->order_id }}</span>
                            </td>
                            <td class="px-8 py-6 text-xs font-bold text-gray-600 tracking-tight">{{ $return->reason }}</td>
                            <td class="px-8 py-6 text-xs font-bold text-gray-500">{{ \Carbon\Carbon::parse($return->return_date)->format('M d, Y') }}</td>
                            <td class="px-8 py-6 text-right">
                                <a href="{{ route('seller.complaint.detail', $return->id) }}" class="{{ $act_col }} text-[10px] font-black uppercase tracking-widest flex items-center justify-end ml-auto group-hover:scale-105 transition-transform">
                                    {{ $action }} <i class="fa-solid fa-arrow-right ml-2 text-[8px]"></i>
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="px-8 py-6 text-center text-xs font-bold text-gray-400">Belum ada komplain atau pengembalian.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

           
        </div>

        <footer class="px-8 py-6 border-t border-gray-100 text-[10px] font-bold text-gray-400 uppercase tracking-widest text-left">
            Â© 2026 DECOR MERCHANT SERVICE CENTER. ALL RIGHTS RESERVED.
        </footer>
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