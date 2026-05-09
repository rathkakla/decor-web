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

    <!-- SIDEBAR (8 Menus Consistent) -->
    <aside id="sidebar" class="w-64 bg-white border-r border-gray-200 flex flex-col fixed h-full z-50 sidebar-transition">
        <div class="p-8">
            <h1 class="text-2xl font-bold tracking-widest text-primary uppercase leading-none">DECOR</h1>
            <p class="text-[10px] text-gray-400 mt-1 uppercase tracking-widest italic font-bold">Designer Portal</p>
        </div>
        
        <nav class="flex-1 px-4 space-y-1">
            <a href="{{ route('designer.dashboard') }}" class="flex items-center px-4 py-3 text-xs font-bold text-gray-400 hover:text-primary transition-all rounded-lg"><i class="fa-solid fa-table-columns mr-3 w-5 text-center"></i> Dashboard</a>
            <a href="{{ route('designer.portfolio.index') }}" class="flex items-center px-4 py-3 text-xs font-bold text-gray-400 hover:text-primary transition-all rounded-lg"><i class="fa-solid fa-briefcase mr-3 w-5 text-center"></i> Kelola Portofolio</a>
            <a href="{{ route('designer.consultations.index') }}" class="flex items-center px-4 py-3 text-xs font-bold text-gray-400 hover:text-primary transition-all rounded-lg"><i class="fa-solid fa-calendar-check mr-3 w-5 text-center"></i>Konsultasi</a>
            <a href="{{ route('designer.chats') }}" class="flex items-center px-4 py-3 text-xs font-bold text-gray-400 hover:text-primary transition-all rounded-lg"><i class="fa-solid fa-comment-dots mr-3 w-5 text-center"></i>Chat</a>
            <a href="{{ route('designer.reviews') }}" class="flex items-center px-4 py-3 text-xs font-bold text-gray-400 hover:text-primary transition-all rounded-lg"><i class="fa-solid fa-star mr-3 w-5 text-center"></i> Review & Rating</a>
            <a href="{{ route('designer.reports') }}" class="flex items-center px-4 py-3 text-xs font-bold active-link transition-all rounded-lg"><i class="fa-solid fa-chart-line mr-3 w-5 text-center"></i> Laporan</a>
        </nav>
        <div class="p-4 border-t border-gray-100 space-y-1">
            <a href="{{ route('designer.settings') }}" class="flex items-center px-4 py-3 text-xs font-bold text-gray-400 hover:text-primary transition-all rounded-lg"><i class="fa-solid fa-gear mr-3 w-5 text-center"></i> Settings</a>
            <a href="{{ route('designer.support') }}" class="flex items-center px-4 py-3 text-xs font-bold text-gray-400 hover:text-primary transition-all rounded-lg"><i class="fa-solid fa-circle-question mr-3 w-5 text-center"></i> Support</a>
        </div>
    </aside>

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
                    <span class="text-[10px] font-black uppercase tracking-widest">Elena Vance</span>
                    <div class="w-8 h-8 rounded-lg bg-white text-primary flex items-center justify-center font-bold">EV</div>
                </div>
            </div>
        </header>

        <!-- CONTENT AREA -->
        <div class="p-10 space-y-10 flex-1">
            
            <!-- FILTER TOOLBAR -->
            <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center gap-6 bg-white p-6 rounded-[32px] border border-gray-100 shadow-sm">
                <div class="flex flex-col md:flex-row items-center gap-4 w-full lg:w-auto">
                    <div class="flex flex-col w-full md:w-44">
                        <label class="text-[9px] font-black text-gray-300 uppercase mb-1 ml-1">Start Date</label>
                        <input type="date" class="bg-gray-50 border-none rounded-xl py-2 px-4 text-xs font-bold outline-none focus:ring-1 focus:ring-primary/20">
                    </div>
                    <div class="flex flex-col w-full md:w-44">
                        <label class="text-[9px] font-black text-gray-300 uppercase mb-1 ml-1">End Date</label>
                        <input type="date" class="bg-gray-50 border-none rounded-xl py-2 px-4 text-xs font-bold outline-none focus:ring-1 focus:ring-primary/20">
                    </div>
                    <button class="mt-5 md:mt-4 bg-gray-900 text-white px-6 py-2.5 rounded-xl text-[10px] font-black uppercase tracking-widest hover:bg-primary transition-all">Filter</button>
                </div>
                
                <a href="{{ route('designer.report.export') }}" class="w-full lg:w-auto flex items-center justify-center space-x-3 bg-primary text-white px-8 py-3 rounded-2xl text-[10px] font-black uppercase tracking-widest shadow-xl shadow-primary/20 hover:scale-105 transition-all">
                    <i class="fa-solid fa-file-pdf"></i>
                    <span>Download PDF Report</span>
                </a>
            </div>

            <!-- ANALYTICS SUMMARY -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <div class="bg-white p-8 rounded-[32px] border border-gray-100 shadow-sm">
                    <p class="text-[9px] font-black text-gray-300 uppercase tracking-widest mb-2">Period Revenue</p>
                    <h3 class="text-3xl font-black text-gray-900 tracking-tighter">$8,450.00</h3>
                    <p class="text-[10px] text-green-500 font-bold mt-2"><i class="fa-solid fa-arrow-trend-up mr-1"></i> +12.5% from last period</p>
                </div>
                <div class="bg-white p-8 rounded-[32px] border border-gray-100 shadow-sm">
                    <p class="text-[9px] font-black text-gray-300 uppercase tracking-widest mb-2">Projects Completed</p>
                    <h3 class="text-3xl font-black text-gray-900 tracking-tighter">24</h3>
                    <p class="text-[10px] text-gray-400 font-bold mt-2 italic">Target: 30 Projects</p>
                </div>
                <div class="bg-white p-8 rounded-[32px] border border-gray-100 shadow-sm">
                    <p class="text-[9px] font-black text-gray-300 uppercase tracking-widest mb-2">Avg. Project Value</p>
                    <h3 class="text-3xl font-black text-gray-900 tracking-tighter">$352.00</h3>
                    <p class="text-[10px] text-primary font-bold mt-2 uppercase tracking-tighter italic">Top Tier Designer</p>
                </div>
                <div class="bg-white p-8 rounded-[32px] border border-gray-100 shadow-sm">
                    <p class="text-[9px] font-black text-gray-300 uppercase tracking-widest mb-2">Lead Conversion</p>
                    <h3 class="text-3xl font-black text-gray-900 tracking-tighter">68%</h3>
                    <p class="text-[10px] text-green-500 font-bold mt-2"><i class="fa-solid fa-circle-check mr-1"></i> Excellent Performance</p>
                </div>
            </div>

            <!-- IMPROVED: FULL-WIDTH HIGH-RESOLUTION CHART -->
            <div class="bg-white p-12 rounded-[48px] border border-gray-100 shadow-sm">
                <div class="flex justify-between items-center mb-12">
                    <h3 class="text-xs font-black text-gray-900 uppercase tracking-[0.3em]">Revenue Analytics</h3>
                    <div class="flex items-center space-x-2">
                        <span class="w-3 h-3 rounded-full bg-primary"></span>
                        <span class="text-[9px] font-black uppercase text-gray-400">Total Earnings</span>
                    </div>
                </div>
                <!-- Fixed Height Container for better verticality -->
                <div class="w-full h-[450px]">
                    <canvas id="revenueReportChart"></canvas>
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
                            <tr class="group hover:bg-gray-50/50 transition-colors">
                                <td class="py-6 tracking-tighter">Apr 28, 2026</td>
                                <td class="py-6 text-primary">#DEC-88219</td>
                                <td class="py-6 text-gray-900">Elena Rodriguez</td>
                                <td class="py-6">Residential</td>
                                <td class="py-6 text-right text-gray-900">$1,250.00</td>
                            </tr>
                            <tr class="group hover:bg-gray-50/50 transition-colors">
                                <td class="py-6 tracking-tighter">Apr 25, 2026</td>
                                <td class="py-6 text-primary">#DEC-88102</td>
                                <td class="py-6 text-gray-900">Marcus Thorne</td>
                                <td class="py-6">Residential</td>
                                <td class="py-6 text-right text-gray-900">$2,400.00</td>
                            </tr>
                            <tr class="group hover:bg-gray-50/50 transition-colors">
                                <td class="py-6 tracking-tighter">Apr 20, 2026</td>
                                <td class="py-6 text-primary">#DEC-87944</td>
                                <td class="py-6 text-gray-900">Luminary Studios</td>
                                <td class="py-6">Commercial</td>
                                <td class="py-6 text-right text-gray-900">$3,100.00</td>
                            </tr>
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

        // REVENUE REPORT CHART (IMPROVED VISUALS)
        const ctxRevenue = document.getElementById('revenueReportChart').getContext('2d');
        
        // Creating a beautiful gradient
        const gradient = ctxRevenue.createLinearGradient(0, 0, 0, 400);
        gradient.addColorStop(0, 'rgba(181, 115, 58, 0.2)');
        gradient.addColorStop(1, 'rgba(181, 115, 58, 0.0)');

        new Chart(ctxRevenue, {
            type: 'line',
            data: {
                labels: ['Week 1', 'Week 2', 'Week 3', 'Week 4'],
                datasets: [{
                    label: 'Revenue',
                    data: [1500, 2800, 2100, 4250],
                    borderColor: '#B5733A',
                    backgroundColor: gradient,
                    borderWidth: 5,
                    fill: true,
                    tension: 0.4,
                    pointRadius: 8,
                    pointBackgroundColor: '#fff',
                    pointBorderColor: '#B5733A',
                    pointBorderWidth: 3,
                    pointHoverRadius: 10,
                    pointHoverBackgroundColor: '#B5733A',
                    pointHoverBorderColor: '#fff'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { 
                    legend: { display: false },
                    tooltip: {
                        backgroundColor: '#1a1a1a',
                        titleFont: { size: 10, weight: 'bold' },
                        bodyFont: { size: 12, weight: 'bold' },
                        padding: 12,
                        displayColors: false,
                        callbacks: {
                            label: function(context) { return '$ ' + context.parsed.y.toLocaleString(); }
                        }
                    }
                },
                scales: { 
                    y: { 
                        beginAtZero: true, 
                        suggestedMax: 5000, 
                        grid: { color: 'rgba(0,0,0,0.03)', drawBorder: false },
                        ticks: { 
                            font: { size: 10, weight: '800' },
                            color: '#cbd5e1',
                            callback: function(value) { return '$' + value; }
                        }
                    },
                    x: { 
                        grid: { display: false },
                        ticks: { 
                            font: { size: 10, weight: '800' },
                            color: '#cbd5e1',
                            padding: 20
                        }
                    }
                }
            }
        });
    </script>
</body>
</html>