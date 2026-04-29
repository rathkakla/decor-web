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

    <aside id="sidebar" class="w-64 bg-white border-r border-gray-200 flex flex-col fixed h-full z-50 sidebar-transition">
        <div class="p-8">
            <h1 class="text-2xl font-bold tracking-widest text-primary uppercase leading-none">DECOR</h1>
            <p class="text-[10px] text-gray-400 mt-1 uppercase tracking-widest">Seller Portal</p>
        </div>

        <nav class="flex-1 px-4 space-y-1">
            <a href="{{ route('seller.dashboard') }}" class="flex items-center px-4 py-3 text-xs font-bold text-gray-400 hover:text-primary transition-all rounded-lg">
                <i class="fa-solid fa-table-columns mr-3 w-5 text-center"></i> Dashboard
            </a>
            <a href="{{ route('seller.products.index') }}" class="flex items-center px-4 py-3 text-xs font-bold text-gray-400 hover:text-primary transition-all rounded-lg">
                <i class="fa-solid fa-couch mr-3 w-5 text-center"></i> Kelola Produk
            </a>
            <a href="{{ route('seller.orders') }}" class="flex items-center px-4 py-3 text-xs font-bold text-gray-400 hover:text-primary transition-all rounded-lg">
                <i class="fa-solid fa-bag-shopping mr-3 w-5 text-center"></i> Daftar Pesanan
            </a>
            <a href="{{ route('seller.chats') }}" class="flex items-center px-4 py-3 text-xs font-bold text-gray-400 hover:text-primary transition-all rounded-lg">
                <i class="fa-solid fa-message mr-3 w-5 text-center"></i> Seller Chat
            </a>
            <a href="{{ route('seller.complaint.index') }}" class="flex items-center px-4 py-3 text-xs font-bold active-link transition-all rounded-lg">
                <i class="fa-solid fa-circle-exclamation mr-3 w-5 text-center"></i> Komplain
            </a>
            <a href="{{ route('seller.reviews') }}" class="flex items-center px-4 py-3 text-xs font-bold text-gray-400 hover:text-primary transition-all rounded-lg">
                <i class="fa-solid fa-star mr-3 w-5 text-center"></i> Review & Rating
            </a>
            <a href="{{ route('seller.reports') }}" class="flex items-center px-4 py-3 text-xs font-bold text-gray-400 hover:text-primary transition-all rounded-lg">
                <i class="fa-solid fa-chart-line mr-3 w-5 text-center"></i> Laporan
            </a>
        </nav>

        <div class="p-4 border-t border-gray-100 space-y-1">
            <a href="{{ route('seller.settings') }}" class="flex items-center px-4 py-3 text-xs font-bold text-gray-400 hover:text-primary transition-all rounded-lg">
                <i class="fa-solid fa-gear mr-3 w-5 text-center"></i> Settings
            </a>
            <a href="{{ route('seller.support') }}" class="flex items-center px-4 py-3 text-xs font-bold transition-all rounded-lg {{ Request::routeIs('seller.support') ? 'active-link' : 'text-gray-400 hover:text-primary' }}">
                <i class="fa-solid fa-gear mr-3 w-5 text-center"></i> Support
            </a>
        </div>
    </aside>

    <main id="main-content" class="flex-1 flex flex-col ml-64 sidebar-transition min-h-screen">
        
        <header class="h-16 bg-primary flex items-center justify-between px-8 sticky top-0 z-40 shadow-sm">
            <div class="flex items-center">
                <button id="toggle-sidebar" class="text-white hover:opacity-80 mr-4 transition-transform active:scale-95">
                    <i class="fa-solid fa-bars-staggered text-xl"></i>
                </button>
                <div class="relative ml-4">
                    <i class="fa-solid fa-magnifying-glass absolute left-3 top-1/2 -translate-y-1/2 text-white/50 text-xs"></i>
                    <input type="text" placeholder="Search Case IDs..." class="bg-white/10 border border-white/20 rounded-full py-1.5 pl-9 pr-4 text-xs text-white placeholder-white/50 outline-none focus:bg-white/20 w-64 transition-all">
                </div>
            </div>
            <div class="flex items-center space-x-6 text-white">
                <i class="fa-regular fa-bell text-xl cursor-pointer"></i>
                <img src="https://ui-avatars.com/api/?name=Audri&background=fff&color=B5733A" class="w-9 h-9 rounded-lg border-2 border-white/20">
            </div>
        </header>

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
                        <h3 class="text-5xl font-black leading-none">12</h3>
                        <span class="text-xs font-bold text-orange-500 mb-1">+4% vs last week</span>
                    </div>
                </div>
                <div class="bg-white p-8 rounded-3xl border border-gray-100 shadow-sm relative overflow-hidden">
                    <div class="flex justify-between items-start mb-4">
                        <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">In Investigation</p>
                        <i class="fa-solid fa-chart-line text-gray-300"></i>
                    </div>
                    <div class="flex items-end space-x-3 text-gray-800">
                        <h3 class="text-5xl font-black leading-none">08</h3>
                        <span class="text-xs font-bold text-gray-400 mb-1 tracking-widest">Steady</span>
                    </div>
                </div>
                <div class="bg-white p-8 rounded-3xl border border-gray-100 shadow-sm relative overflow-hidden">
                    <div class="flex justify-between items-start mb-4">
                        <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Resolved</p>
                        <i class="fa-regular fa-circle-check text-gray-300"></i>
                    </div>
                    <div class="flex items-end space-x-3 text-gray-800">
                        <h3 class="text-5xl font-black leading-none">142</h3>
                        <span class="text-xs font-bold text-green-500 mb-1">98% Success</span>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-[32px] border border-gray-100 shadow-sm overflow-hidden p-4">
                <div class="flex space-x-8 border-b border-gray-50 px-6 pt-4 text-[10px] font-black uppercase tracking-widest text-gray-400">
                    <button class="pb-4 border-b-2 border-primary text-primary">All (162)</button>
                    <button class="pb-4 hover:text-primary transition-colors">Pending (20)</button>
                    <button class="pb-4 hover:text-primary transition-colors">Resolved (142)</button>
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
                        @php
                        $complaints = [
                            ['id' => '#CS-9842', 'tag' => 'NEW', 'tag_col' => 'bg-orange-400', 'name' => 'Elena Rosales', 'tier' => 'PREMIUM MEMBER', 'issue' => 'Damaged Product: Velvet Chaise', 'date' => 'Oct 24, 2023', 'action' => 'OPEN DETAILS', 'act_col' => 'text-primary'],
                            ['id' => '#CS-9840', 'tag' => 'INVESTIGATING', 'tag_col' => 'bg-orange-800', 'name' => 'Marcus Vane', 'tier' => 'REPEAT BUYER', 'issue' => 'Wrong Item: Received \'Oak...\'', 'date' => 'Oct 23, 2023', 'action' => 'OPEN DETAILS', 'act_col' => 'text-primary'],
                            ['id' => '#CS-9838', 'tag' => 'RESOLVED', 'tag_col' => 'bg-gray-400', 'name' => 'Sarah Jenkins', 'tier' => 'STANDARD ACCOUNT', 'issue' => 'Missing Hardware: Assembly kit...', 'date' => 'Oct 22, 2023', 'action' => 'VIEW ARCHIVE', 'act_col' => 'text-gray-400'],
                        ];
                        @endphp

                        @foreach($complaints as $c)
                        <tr class="hover:bg-gray-50 transition-colors group">
                            <td class="px-8 py-6">
                                <span class="text-xs font-bold text-gray-300 block mb-1">{{ $c['id'] }}</span>
                                <span class="{{ $c['tag_col'] }} text-white text-[8px] font-black px-2 py-0.5 rounded uppercase">{{ $c['tag'] }}</span>
                            </td>
                            <td class="px-8 py-6">
                                <h4 class="text-sm font-black leading-none">{{ $c['name'] }}</h4>
                                <span class="text-[9px] font-bold text-gray-300 uppercase">{{ $c['tier'] }}</span>
                            </td>
                            <td class="px-8 py-6 text-xs font-bold text-gray-600 tracking-tight">{{ $c['issue'] }}</td>
                            <td class="px-8 py-6 text-xs font-bold text-gray-500">{{ $c['date'] }}</td>
                            <td class="px-8 py-6 text-right">
                                <a href="{{ route('seller.complaint.detail') }}" class="{{ $c['act_col'] }} text-[10px] font-black uppercase tracking-widest flex items-center justify-end ml-auto group-hover:scale-105 transition-transform">
                                    {{ $c['action'] }} <i class="fa-solid fa-arrow-right ml-2 text-[8px]"></i>
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

           
        </div>

        <footer class="px-8 py-6 border-t border-gray-100 text-[10px] font-bold text-gray-400 uppercase tracking-widest text-left">
            © 2026 DECOR MERCHANT SERVICE CENTER. ALL RIGHTS RESERVED.
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