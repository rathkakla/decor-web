<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Decor Designer - Reports & Analytics</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
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

    @include('designer.partials.sidebar')

    <main id="main-content" class="ml-64 flex flex-col min-h-screen sidebar-transition">
        <!-- HEADER -->
        <header class="h-16 bg-primary flex items-center justify-between px-8 sticky top-0 z-40 shadow-sm text-white">
            <div class="flex items-center">
                <i onclick="toggleSidebar()" class="fa-solid fa-bars-staggered text-xl mr-4 cursor-pointer hover:scale-110 transition-transform"></i>
                <h2 class="font-black text-[10px] uppercase tracking-widest leading-none">Reports & Analytics</h2>
            </div>
            <div class="flex items-center space-x-6">
                <i class="fa-regular fa-bell text-xl"></i>
                <div class="flex items-center space-x-3 bg-white/10 px-3 py-1.5 rounded-xl border border-white/20">
                    <span class="text-[10px] font-black uppercase tracking-widest">{{ Auth::user()->full_name }}</span>
                    <div class="w-8 h-8 rounded-lg bg-white text-primary flex items-center justify-center font-bold">{{ strtoupper(substr(Auth::user()->full_name, 0, 2)) }}</div>
                </div>
            </div>
        </header>

        <!-- CONTENT AREA -->
        <div class="p-10 space-y-10 flex-1">
            
            <!-- FILTER TOOLBAR -->
            <form method="GET" action="{{ route('designer.reports') }}" class="flex flex-col lg:flex-row justify-between items-start lg:items-center gap-6 bg-white p-6 rounded-[32px] border border-gray-100 shadow-sm">
                <div class="flex flex-col md:flex-row items-center gap-4 w-full lg:w-auto">
                    <div class="flex flex-col w-full md:w-44">
                        <label class="text-[9px] font-black text-gray-300 uppercase mb-1 ml-1">Start Date</label>
                        <input type="date" name="start_date" value="{{ $startDate }}" class="bg-gray-50 border-none rounded-xl py-2 px-4 text-xs font-bold outline-none focus:ring-1 focus:ring-primary/20">
                    </div>
                    <div class="flex flex-col w-full md:w-44">
                        <label class="text-[9px] font-black text-gray-300 uppercase mb-1 ml-1">End Date</label>
                        <input type="date" name="end_date" value="{{ $endDate }}" class="bg-gray-50 border-none rounded-xl py-2 px-4 text-xs font-bold outline-none focus:ring-1 focus:ring-primary/20">
                    </div>
                    <button type="submit" class="mt-5 md:mt-4 bg-gray-900 text-white px-6 py-2.5 rounded-xl text-[10px] font-black uppercase tracking-widest hover:bg-primary transition-all">Filter</button>
                </div>
                
                <a href="{{ route('designer.report.export', ['start_date' => $startDate, 'end_date' => $endDate]) }}" class="w-full lg:w-auto flex items-center justify-center space-x-3 bg-primary text-white px-8 py-3 rounded-2xl text-[10px] font-black uppercase tracking-widest shadow-xl shadow-primary/20 hover:scale-105 transition-all">
                    <i class="fa-solid fa-file-pdf"></i>
                    <span>Download PDF Report</span>
                </a>
            </form>

            @if(session('warning'))
                <div class="bg-amber-50 border border-amber-100 text-amber-600 px-6 py-4 rounded-2xl text-[10px] font-black uppercase tracking-widest shadow-sm flex items-center">
                    <i class="fa-solid fa-triangle-exclamation mr-3 text-lg"></i>
                    {{ session('warning') }}
                </div>
            @endif

            <!-- ANALYTICS SUMMARY -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <div class="bg-white p-8 rounded-[32px] border border-gray-100 shadow-sm">
                    <p class="text-[9px] font-black text-gray-300 uppercase tracking-widest mb-2">Period Revenue</p>
                    <h3 class="text-3xl font-black text-gray-900 tracking-tighter">Rp{{ number_format($periodRevenue, 0, ',', '.') }}</h3>
                    <p class="text-[10px] text-green-500 font-bold mt-2"><i class="fa-solid fa-money-bill-wave mr-1"></i> Total paid projects</p>
                </div>
                <div class="bg-white p-8 rounded-[32px] border border-gray-100 shadow-sm">
                    <p class="text-[9px] font-black text-gray-300 uppercase tracking-widest mb-2">Projects Completed</p>
                    <h3 class="text-3xl font-black text-gray-900 tracking-tighter">{{ $projectsCompleted }}</h3>
                    <p class="text-[10px] text-gray-400 font-bold mt-2 italic">Finished in this period</p>
                </div>
                <div class="bg-white p-8 rounded-[32px] border border-gray-100 shadow-sm">
                    <p class="text-[9px] font-black text-gray-300 uppercase tracking-widest mb-2">Avg. Project Value</p>
                    <h3 class="text-3xl font-black text-gray-900 tracking-tighter">Rp{{ number_format($avgProjectValue, 0, ',', '.') }}</h3>
                    <p class="text-[10px] text-primary font-bold mt-2 uppercase tracking-tighter italic">Top Tier Designer</p>
                </div>
                <div class="bg-white p-8 rounded-[32px] border border-gray-100 shadow-sm">
                    <p class="text-[9px] font-black text-gray-300 uppercase tracking-widest mb-2">Lead Conversion</p>
                    <h3 class="text-3xl font-black text-gray-900 tracking-tighter">{{ $leadConversion }}%</h3>
                    <p class="text-[10px] text-green-500 font-bold mt-2"><i class="fa-solid fa-circle-check mr-1"></i> Excellent Performance</p>
                </div>
            </div>


            <!-- UPDATED: DETAILED TRANSACTION LOG WITH SEARCH BAR -->
            <div class="bg-white rounded-[48px] border border-gray-100 shadow-sm overflow-hidden pb-10">
                <div class="p-10 flex justify-between items-center">
                    <h3 class="text-xs font-black text-gray-900 uppercase tracking-[0.2em]">Detailed Transaction Log</h3>
                    
                    <!-- NEW: Kiyowo Search Box -->
                    <div class="relative group">
                        <i class="fa-solid fa-magnifying-glass absolute left-4 top-1/2 -translate-y-1/2 text-gray-300 group-focus-within:text-primary transition-colors text-xs"></i>
                        <input type="text" id="reportLogSearch" placeholder="Search client or ID..." class="bg-gray-50 border-none rounded-2xl py-2.5 pl-10 pr-6 text-[10px] font-bold text-gray-900 focus:ring-2 focus:ring-primary/20 outline-none w-64 transition-all" onkeyup="filterReportTransactions()">
                    </div>

                </div>
                <div class="overflow-x-auto px-10">
                    <table class="w-full text-left" id="reportTransactionTable">
                        <thead>
                            <tr class="border-b border-gray-50">
                                <th class="pb-6 text-[9px] font-black text-gray-300 uppercase tracking-widest">Date</th>
                                <th class="pb-6 text-[9px] font-black text-gray-300 uppercase tracking-widest">Project ID</th>
                                <th class="pb-6 text-[9px] font-black text-gray-300 uppercase tracking-widest">Client Name</th>
                                <th class="pb-6 text-[9px] font-black text-gray-300 uppercase tracking-widest">Category</th>
                                <th class="pb-6 text-right text-[9px] font-black text-gray-300 uppercase tracking-widest">Amount</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50 text-[11px] font-bold text-gray-700 uppercase italic">
                            @forelse($consultations as $consult)
                            <tr class="group hover:bg-gray-50/50 transition-colors">
                                <td class="py-6 tracking-tighter">{{ $consult->updated_at->format('M d, Y') }}</td>
                                <td class="py-6 text-primary">#DEC-{{ str_pad($consult->id, 5, '0', STR_PAD_LEFT) }}</td>
                                <td class="py-6 text-gray-900">{{ $consult->customer->user->full_name ?? 'Client' }}</td>
                                <td class="py-6">Consultation & Quote</td>
                                <td class="py-6 text-right text-gray-900">Rp{{ number_format($consult->quotes->first()->amount ?? 0, 0, ',', '.') }}</td>
                            </tr>
                            @empty
                            <tr><td colspan="5" class="text-center py-6">No completed projects found for this period.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

       <footer class="p-8 border-t border-gray-100 text-[9px] font-black text-gray-400 uppercase tracking-widest bg-white mt-auto text-center">
            Â© 2026 DECOR DESIGNER PORTAL. ALL RIGHTS RESERVED.
        </footer>
    </main>

    <script>
        function toggleSidebar() {
            document.getElementById('sidebar').classList.toggle('sidebar-closed');
            document.getElementById('main-content').classList.toggle('main-full');
        }

        // NEW: SEARCH FILTER LOGIC FOR REPORT
        function filterReportTransactions() {
            const input = document.getElementById("reportLogSearch");
            const filter = input.value.toUpperCase();
            const table = document.getElementById("reportTransactionTable");
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


    </script>
</body>
</html>