@php
$admin_name = Auth::user()->full_name;
$admin_role = "ADMIN";
@endphp
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Decor Admin Portal - Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&display=swap');
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

    @include('Admin.partials.sidebar')

    <main id="main-content" class="flex-1 flex flex-col ml-64 sidebar-transition min-h-screen">
        @include('Admin.partials.header', ['title' => 'Dashboard Overview'])

        <div class="p-8 space-y-8 flex-1">
            <div class="flex justify-between items-end">
                <div>
                    <h2 class="text-2xl font-bold">Executive Overview</h2>
                    <p class="text-xs text-gray-500 font-medium tracking-wide mt-1">Admin fees, seller & designer performance monitoring.</p>
                </div>
                <div class="bg-white border border-gray-100 px-4 py-2 rounded-full shadow-sm flex items-center space-x-2">
                    <i class="fa-regular fa-calendar text-primary text-xs"></i>
                    <span id="live-date" class="text-[10px] font-bold text-gray-500 uppercase tracking-widest"></span>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                <div class="bg-white p-6 rounded-xl border border-gray-100 shadow-sm transition-transform hover:-translate-y-1">
                    <div class="p-2 bg-amber-50 rounded-lg text-primary text-sm w-fit mb-4"><i class="fa-solid fa-money-bill-wave"></i></div>
                    <p class="text-[10px] text-gray-400 font-bold uppercase tracking-wider">Total Admin Fees</p>
                    <h3 class="text-2xl font-bold mt-1">Rp {{ number_format($stats['total_fees'], 0, ',', '.') }}</h3>
                </div>

                <div class="bg-white p-6 rounded-xl border border-gray-100 shadow-sm transition-transform hover:-translate-y-1">
                    <div class="p-2 bg-amber-50 rounded-lg text-primary text-sm w-fit mb-4"><i class="fa-solid fa-store"></i></div>
                    <p class="text-[10px] text-gray-400 font-bold uppercase tracking-wider">Active Sellers</p>
                    <h3 class="text-2xl font-bold mt-1">{{ $stats['active_sellers'] }}</h3>
                </div>

                <div class="bg-white p-6 rounded-xl border border-gray-100 shadow-sm transition-transform hover:-translate-y-1">
                    <div class="p-2 bg-amber-50 rounded-lg text-primary text-sm w-fit mb-4"><i class="fa-solid fa-palette"></i></div>
                    <p class="text-[10px] text-gray-400 font-bold uppercase tracking-wider">Active Designers</p>
                    <h3 class="text-2xl font-bold mt-1">{{ $stats['active_designers'] }}</h3>
                </div>

                <div class="bg-white p-6 rounded-xl border border-gray-100 shadow-sm transition-transform hover:-translate-y-1">
                    <div class="p-2 bg-amber-50 rounded-lg text-primary text-sm w-fit mb-4"><i class="fa-solid fa-repeat"></i></div>
                    <p class="text-[10px] text-gray-400 font-bold uppercase tracking-wider">Total Transactions</p>
                    <h3 class="text-2xl font-bold mt-1">{{ number_format($stats['total_transactions']) }}</h3>
                </div>
            </div>

            <div class="bg-white p-8 rounded-xl shadow-sm border border-gray-100">
                <div class="flex justify-between items-center mb-10">
                    <div>
                        <h3 class="text-lg font-bold">Admin Fee Revenue</h3>
                        <p class="text-[10px] text-gray-400 font-bold uppercase tracking-wider mt-1">Monthly performance — 5% per transaction</p>
                    </div>
                    <span class="text-[10px] font-black text-primary bg-amber-50 px-3 py-1 rounded-full uppercase tracking-widest">2026</span>
                </div>
                <div class="h-64">
                    <canvas id="revenueChart"></canvas>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                <!-- Seller Transactions -->
                <div class="bg-white p-8 rounded-xl shadow-sm border border-gray-100">
                    <h3 class="text-lg font-bold">Recent Seller Transactions</h3>
                    <p class="text-[10px] text-gray-400 font-bold uppercase tracking-wider mt-1 mb-6">Monitor fee masuk dari produk</p>
                    
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr>
                                    <th class="border-b-2 border-gray-100 pb-3 text-[9px] font-black text-gray-400 uppercase tracking-widest">Order ID</th>
                                    <th class="border-b-2 border-gray-100 pb-3 text-[9px] font-black text-gray-400 uppercase tracking-widest">Seller</th>
                                    <th class="border-b-2 border-gray-100 pb-3 text-[9px] font-black text-gray-400 uppercase tracking-widest">Amount</th>
                                    <th class="border-b-2 border-gray-100 pb-3 text-[9px] font-black text-gray-400 uppercase tracking-widest">Fee</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($recentSellerTransactions as $tx)
                                <tr class="border-b border-gray-50 hover:bg-gray-50 transition-colors">
                                    <td class="py-4 text-xs font-bold text-gray-500 font-mono">#DC-{{ str_pad($tx->order->id, 4, '0', STR_PAD_LEFT) }}</td>
                                    <td class="py-4 text-xs font-bold text-gray-900">{{ $tx->product->seller->user->full_name }}</td>
                                    <td class="py-4 text-xs font-bold text-gray-900">Rp {{ number_format($tx->price * $tx->quantity, 0, ',', '.') }}</td>
                                    <td class="py-4 text-xs font-bold text-primary">Rp {{ number_format($tx->price * $tx->quantity * 0.05, 0, ',', '.') }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-6">
                        <a href="{{ route('admin.seller-monitor') }}" class="inline-flex items-center text-[10px] font-bold text-primary uppercase tracking-widest hover:text-white hover:bg-primary border border-primary px-4 py-2 rounded-lg transition-all">
                            View All Transactions <i class="fa-solid fa-arrow-right ml-2"></i>
                        </a>
                    </div>
                </div>

                <!-- Designer Transactions -->
                <div class="bg-white p-8 rounded-xl shadow-sm border border-gray-100">
                    <h3 class="text-lg font-bold">Recent Designer Transactions</h3>
                    <p class="text-[10px] text-gray-400 font-bold uppercase tracking-wider mt-1 mb-6">Monitor fee dari jasa desain</p>
                    
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr>
                                    <th class="border-b-2 border-gray-100 pb-3 text-[9px] font-black text-gray-400 uppercase tracking-widest">Quote ID</th>
                                    <th class="border-b-2 border-gray-100 pb-3 text-[9px] font-black text-gray-400 uppercase tracking-widest">Designer</th>
                                    <th class="border-b-2 border-gray-100 pb-3 text-[9px] font-black text-gray-400 uppercase tracking-widest">Amount</th>
                                    <th class="border-b-2 border-gray-100 pb-3 text-[9px] font-black text-gray-400 uppercase tracking-widest">Fee</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($recentDesignerTransactions as $tx)
                                <tr class="border-b border-gray-50 hover:bg-gray-50 transition-colors">
                                    <td class="py-4 text-xs font-bold text-gray-500 font-mono">#DD-{{ str_pad($tx->id, 4, '0', STR_PAD_LEFT) }}</td>
                                    <td class="py-4 text-xs font-bold text-gray-900">{{ $tx->consultation->designer->user->full_name }}</td>
                                    <td class="py-4 text-xs font-bold text-gray-900">Rp {{ number_format($tx->amount, 0, ',', '.') }}</td>
                                    <td class="py-4 text-xs font-bold text-primary">Rp {{ number_format($tx->amount * 0.05, 0, ',', '.') }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-6">
                        <a href="{{ route('admin.designer-monitor') }}" class="inline-flex items-center text-[10px] font-bold text-primary uppercase tracking-widest hover:text-white hover:bg-primary border border-primary px-4 py-2 rounded-lg transition-all">
                            View All Transactions <i class="fa-solid fa-arrow-right ml-2"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <footer class="p-8 border-t border-gray-100 text-[10px] font-bold text-gray-400 uppercase tracking-widest mt-auto">
            © 2026 DECOR ADMIN PORTAL. ALL RIGHTS RESERVED.
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

        document.getElementById('live-date').textContent = new Date().toLocaleDateString('en-GB', {
            day: 'numeric', month: 'short', year: 'numeric'
        });

        document.addEventListener('DOMContentLoaded', function() {
            const ctx = document.getElementById('revenueChart').getContext('2d');
            const gradient = ctx.createLinearGradient(0, 0, 0, 400);
            gradient.addColorStop(0, 'rgba(181, 115, 58, 0.2)');
            gradient.addColorStop(1, 'rgba(181, 115, 58, 0)');

            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug'],
                    datasets: [{
                        label: 'Revenue',
                        data: @json($revenueData).map(v => v * 1000000),
                        borderColor: '#B5733A',
                        borderWidth: 3,
                        backgroundColor: gradient,
                        fill: true,
                        tension: 0.4,
                        pointBackgroundColor: '#B5733A',
                        pointBorderColor: '#fff',
                        pointBorderWidth: 2,
                        pointRadius: 4,
                        pointHoverRadius: 6
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            backgroundColor: '#1f2937',
                            padding: 12,
                            titleFont: { size: 10, weight: 'bold' },
                            bodyFont: { size: 12 },
                            callbacks: {
                                label: function(context) {
                                    let label = context.dataset.label || '';
                                    if (label) label += ': ';
                                    if (context.parsed.y !== null) {
                                        label += new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0 }).format(context.parsed.y);
                                    }
                                    return label;
                                }
                            }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: { color: '#f3f4f6' },
                            ticks: {
                                font: { size: 10 },
                                callback: function(value) {
                                    return 'Rp ' + (value/1000000).toFixed(1) + 'M';
                                }
                            }
                        },
                        x: {
                            grid: { display: false },
                            ticks: { font: { size: 10, weight: 'bold' } }
                        }
                    }
                }
            });
        });
    </script>
</body>
</html>