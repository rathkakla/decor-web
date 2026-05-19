<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Decor Designer - Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&display=swap');
        body { background-color: #F8F6F4; font-family: 'Plus Jakarta Sans', sans-serif; overflow-x: hidden; }
        .bg-primary { background-color: #B5733A; }
        .text-primary { color: #B5733A; }
        .active-link { background-color: rgba(181, 115, 58, 0.1); color: #B5733A; border-right: 4px solid #B5733A; }
        .sidebar-transition { transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1); }
        .sidebar-closed { transform: translateX(-100%); }
        .main-full { margin-left: 0 !important; }
        ::-webkit-scrollbar { width: 5px; }
        ::-webkit-scrollbar-thumb { background: #B5733A; border-radius: 10px; }
    </style>
</head>
<body class="text-gray-800">

    <aside id="sidebar" class="w-64 bg-white border-r border-gray-200 flex flex-col fixed h-full z-50 sidebar-transition">
        <div class="p-8">
            <h1 class="text-2xl font-bold tracking-widest text-primary uppercase leading-none">DECOR</h1>
            <p class="text-[10px] text-gray-400 mt-1 uppercase tracking-widest italic font-bold">Designer Portal</p>
        </div>

        <nav class="flex-1 px-4 space-y-1">
            <a href="{{ route('designer.dashboard') }}" class="flex items-center px-4 py-3 text-xs font-bold transition-all rounded-lg {{ request()->routeIs('designer.dashboard') ? 'active-link' : 'text-gray-400 hover:text-primary' }}">
                <i class="fa-solid fa-table-columns mr-3 w-5 text-center"></i> Dashboard
            </a>
            <a href="{{ route('designer.portfolio.index') }}" class="flex items-center px-4 py-3 text-xs font-bold transition-all rounded-lg {{ request()->routeIs('designer.portfolio.*') ? 'active-link' : 'text-gray-400 hover:text-primary' }}">
                <i class="fa-solid fa-briefcase mr-3 w-5 text-center"></i> Kelola Portofolio
            </a>
            <a href="{{ route('designer.consultations.index') }}" class="flex items-center px-4 py-3 text-xs font-bold transition-all rounded-lg {{ request()->routeIs('designer.consultations.*') ? 'active-link' : 'text-gray-400 hover:text-primary' }}">
                <i class="fa-solid fa-calendar-check mr-3 w-5 text-center"></i> Konsultasi
            </a>
            <a href="{{ route('designer.chats') }}" class="flex items-center px-4 py-3 text-xs font-bold transition-all rounded-lg {{ request()->routeIs('designer.chats') ? 'active-link' : 'text-gray-400 hover:text-primary' }}">
                <i class="fa-solid fa-comment-dots mr-3 w-5 text-center"></i> Chat
            </a>
            <a href="{{ route('designer.reviews') }}" class="flex items-center px-4 py-3 text-xs font-bold transition-all rounded-lg {{ request()->routeIs('designer.reviews') ? 'active-link' : 'text-gray-400 hover:text-primary' }}">
                <i class="fa-solid fa-star mr-3 w-5 text-center"></i> Review & Rating
            </a>
            <a href="{{ route('designer.reports') }}" class="flex items-center px-4 py-3 text-xs font-bold transition-all rounded-lg {{ request()->routeIs('designer.reports') ? 'active-link' : 'text-gray-400 hover:text-primary' }}">
                <i class="fa-solid fa-chart-line mr-3 w-5 text-center"></i> Laporan
            </a>
        </nav>

        <div class="p-4 border-t border-gray-100 space-y-1">
            <a href="{{ route('designer.settings') }}" class="flex items-center px-4 py-3 text-xs font-bold text-gray-400 hover:text-primary transition-all rounded-lg"><i class="fa-solid fa-gear mr-3 w-5 text-center"></i> Settings</a>
            <a href="{{ route('designer.support') }}" class="flex items-center px-4 py-3 text-xs font-bold text-gray-400 hover:text-primary transition-all rounded-lg"><i class="fa-solid fa-circle-question mr-3 w-5 text-center"></i> Support</a>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="w-full flex items-center px-4 py-3 text-xs font-bold text-gray-400 hover:text-red-500 transition-all rounded-lg">
                    <i class="fa-solid fa-right-from-bracket mr-3 w-5 text-center"></i> Logout
                </button>
            </form>
        </div>
    </aside>

    <main id="main-content" class="ml-64 flex flex-col min-h-screen sidebar-transition">
        <header class="h-16 bg-primary flex items-center justify-between px-8 sticky top-0 z-40 shadow-sm text-white">
            <div class="flex items-center">
                <i onclick="toggleSidebar()" class="fa-solid fa-bars-staggered text-xl mr-4 cursor-pointer hover:scale-110 transition-transform"></i>
                <h2 class="font-bold text-[10px] uppercase tracking-widest leading-none">Overview</h2>
            </div>
            <div class="flex items-center space-x-6">
                <i class="fa-regular fa-bell text-xl"></i>
                <div class="flex items-center space-x-3 bg-white/10 px-3 py-1.5 rounded-xl border border-white/20">
                    <span class="text-[10px] font-black uppercase tracking-widest">{{ Auth::user()->full_name }}</span>
                    <div class="w-8 h-8 rounded-lg bg-white text-primary flex items-center justify-center font-bold">
                        {{ strtoupper(substr(Auth::user()->full_name, 0, 1)) }}
                    </div>
                </div>
            </div>
        </header>

        <div class="p-10 space-y-10 flex-1">
            <div>
                <h2 class="text-3xl font-extrabold tracking-tight text-gray-800">Welcome back, {{ explode(' ', Auth::user()->full_name)[0] }}</h2>
                <p class="text-xs text-gray-400 mt-1 font-medium italic">Curating excellence for your amazing projects.</p>
            </div>

            @if($designer->status === 'pending')
            <div class="bg-amber-50 border-l-4 border-amber-400 p-6 rounded-[32px] shadow-sm">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <i class="fa-solid fa-clock text-amber-400 text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-bold text-amber-800 uppercase tracking-wider">Account Pending Approval</p>
                        <p class="text-xs text-amber-700 mt-1">Profil desainer Anda sedang ditinjau. Portfolio Anda tidak akan tampil di halaman utama sampai akun disetujui Admin. Mohon tunggu 1-2 hari kerja.</p>
                    </div>
                </div>
            </div>
            @elseif($designer->status === 'rejected')
            <div class="bg-red-50 border-l-4 border-red-400 p-6 rounded-[32px] shadow-sm">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <i class="fa-solid fa-circle-xmark text-red-400 text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-bold text-red-800 uppercase tracking-wider">Account Activation Rejected</p>
                        <p class="text-xs text-red-700 mt-1">Maaf, profil desainer Anda ditolak: <strong class="italic">{{ $designer->rejection_reason }}</strong>. Silakan perbarui info spesialisasi atau portfolio Anda.</p>
                    </div>
                </div>
            </div>
            @endif

            <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                <div class="bg-white p-8 rounded-[32px] border border-gray-100 shadow-sm">
                    <div class="w-10 h-10 bg-orange-50 rounded-xl flex items-center justify-center text-orange-500 mb-4"><i class="fa-solid fa-palette"></i></div>
                    <p class="text-[9px] font-black text-gray-400 uppercase tracking-widest">Active Consultations</p>
                    <h3 class="text-3xl font-black mt-2">{{ $activeConsultations }}</h3>
                </div>
                <div class="bg-white p-8 rounded-[32px] border border-gray-100 shadow-sm">
                    <div class="w-10 h-10 bg-blue-50 rounded-xl flex items-center justify-center text-blue-500 mb-4"><i class="fa-solid fa-calendar-day"></i></div>
                    <p class="text-[9px] font-black text-gray-400 uppercase tracking-widest">Pending Requests</p>
                    <h3 class="text-3xl font-black mt-2">{{ $pendingRequests }}</h3>
                </div>
                <div class="bg-white p-8 rounded-[32px] border border-gray-100 shadow-sm">
                    <div class="w-10 h-10 bg-green-50 rounded-xl flex items-center justify-center text-green-500 mb-4"><i class="fa-solid fa-wallet"></i></div>
                    <p class="text-[9px] font-black text-gray-400 uppercase tracking-widest">Total Earnings</p>
                    <h3 class="text-3xl font-black mt-2">Rp {{ number_format($monthlyEarnings, 0, ',', '.') }}</h3>
                </div>
                <div class="bg-white p-8 rounded-[32px] border border-gray-100 shadow-sm">
                    <div class="w-10 h-10 bg-yellow-50 rounded-xl flex items-center justify-center text-yellow-500 mb-4"><i class="fa-solid fa-star"></i></div>
                    <p class="text-[9px] font-black text-gray-400 uppercase tracking-widest">Avg. Rating</p>
                    <h3 class="text-3xl font-black mt-2">{{ number_format($avgRating, 1) }}<span class="text-xs text-gray-300">/5</span></h3>
                </div>
            </div>

            <div class="bg-white p-10 rounded-[48px] border border-gray-100 shadow-sm">
                <div class="flex justify-between items-center mb-8">
                    <h3 class="text-lg font-black text-gray-800 uppercase tracking-tight italic">Monthly Revenue Overview</h3>
                </div>
                <div class="h-80">
                    <canvas id="revenueChart"></canvas>
                </div>
            </div>

            <!-- DETAILED TRANSACTION LOG -->
            <div class="bg-white p-10 rounded-[48px] border border-gray-100 shadow-sm">
                <div class="flex justify-between items-center mb-8">
                    <h3 class="text-[10px] font-black text-gray-800 uppercase tracking-widest">Recent Consultation Quotes</h3>
                    <div class="relative group">
                        <i class="fa-solid fa-magnifying-glass absolute left-4 top-1/2 -translate-y-1/2 text-gray-300 group-focus-within:text-primary transition-colors text-xs"></i>
                        <input type="text" id="logSearch" placeholder="Search client..." class="bg-gray-50 border-none rounded-2xl py-2.5 pl-10 pr-6 text-[10px] font-bold text-gray-900 focus:ring-2 focus:ring-primary/20 outline-none w-64 transition-all" onkeyup="filterTransactions()">
                    </div>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse" id="transactionTable">
                        <thead>
                            <tr>
                                <th class="text-[9px] font-black text-gray-400 uppercase tracking-widest pb-4">Date</th>
                                <th class="text-[9px] font-black text-gray-400 uppercase tracking-widest pb-4">Quote ID</th>
                                <th class="text-[9px] font-black text-gray-400 uppercase tracking-widest pb-4">Client Name</th>
                                <th class="text-[9px] font-black text-gray-400 uppercase tracking-widest pb-4 text-right">Amount</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50">
                            @forelse($recentTransactions as $tx)
                            <tr class="group hover:bg-gray-50/50 transition-colors">
                                <td class="py-6 text-[10px] font-black text-gray-400 uppercase italic">{{ $tx->created_at->format('M d, Y') }}</td>
                                <td class="py-6 text-[10px] font-black text-primary uppercase tracking-tight">#QT-{{ str_pad($tx->id, 5, '0', STR_PAD_LEFT) }}</td>
                                <td class="py-6 text-[10px] font-black text-gray-800 uppercase tracking-widest">{{ $tx->consultation->customer->user->full_name }}</td>
                                <td class="py-6 text-[10px] font-black text-gray-800 italic text-right">Rp {{ number_format($tx->amount, 0, ',', '.') }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="py-10 text-center text-gray-400 text-[10px] font-black uppercase">No transactions yet.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <footer class="p-8 border-t border-gray-100 text-[9px] font-black text-gray-400 uppercase tracking-widest bg-white mt-auto text-center">
            © 2026 DECOR DESIGNER PORTAL. ALL RIGHTS RESERVED.
        </footer>
    </main>

    <script>
        // SIDEBAR TOGGLE
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            const mainContent = document.getElementById('main-content');
            sidebar.classList.toggle('sidebar-closed');
            mainContent.classList.toggle('main-full');
        }

        // SEARCH FILTER LOGIC
        function filterTransactions() {
            const input = document.getElementById("logSearch");
            const filter = input.value.toUpperCase();
            const table = document.getElementById("transactionTable");
            const tr = table.getElementsByTagName("tr");

            for (let i = 1; i < tr.length; i++) {
                let textContent = tr[i].textContent || tr[i].innerText;
                if (textContent.toUpperCase().indexOf(filter) > -1) {
                    tr[i].style.display = "";
                } else {
                    tr[i].style.display = "none";
                }
            }
        }

        // CHART LOGIC
        const ctx = document.getElementById('revenueChart').getContext('2d');
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
                datasets: [{
                    label: 'Monthly Earnings (Rp)',
                    data: @json($revenueData),
                    borderColor: '#B5733A',
                    backgroundColor: 'rgba(181, 115, 58, 0.1)',
                    fill: true,
                    tension: 0.4,
                    borderWidth: 3,
                    pointRadius: 4,
                    pointBackgroundColor: '#B5733A'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: {
                    y: { beginAtZero: true, grid: { color: 'rgba(0,0,0,0.03)' }, ticks: { font: { size: 10 } } },
                    x: { grid: { display: false }, ticks: { font: { size: 10 } } }
                }
            }
        });
    </script>

</body>
</html>