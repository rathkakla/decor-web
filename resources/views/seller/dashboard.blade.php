<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Decor Seller Portal - Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
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

    @include('seller.partials.sidebar')

    <main id="main-content" class="flex-1 flex flex-col ml-64 sidebar-transition min-h-screen">
    @include('seller.partials.header', ['title' => 'Overview'])

        <div class="p-8 space-y-8 flex-1">
            <h2 class="text-2xl font-bold">Welcome back, <span class="text-primary">{{ Auth::user()->full_name }}!</span></h2>

            @if($seller->status === 'pending')
            <div class="bg-amber-50 border-l-4 border-amber-400 p-6 rounded-xl shadow-sm mb-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <i class="fa-solid fa-clock text-amber-400 text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-bold text-amber-800 uppercase tracking-wider">Account Pending Approval</p>
                        <p class="text-xs text-amber-700 mt-1">Akun Anda sedang dalam tahap peninjauan oleh Admin. Produk Anda tidak akan tampil di katalog customer sampai akun Anda disetujui. Mohon tunggu 1-2 hari kerja.</p>
                    </div>
                </div>
            </div>
            @elseif($seller->status === 'rejected')
            <div class="bg-red-50 border-l-4 border-red-400 p-6 rounded-xl shadow-sm mb-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <i class="fa-solid fa-circle-xmark text-red-400 text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-bold text-red-800 uppercase tracking-wider">Account Activation Rejected</p>
                        <p class="text-xs text-red-700 mt-1">Maaf, pengajuan akun Anda ditolak dengan alasan: <strong class="italic">{{ $seller->rejection_reason }}</strong>. Silakan perbarui informasi profil atau produk Anda sesuai dengan ketentuan.</p>
                    </div>
                </div>
            </div>
            @endif
            
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                <div class="bg-white p-6 rounded-xl border border-gray-100 shadow-sm">
                    <div class="p-2 bg-amber-50 rounded-lg text-primary text-sm w-fit mb-4"><i class="fa-solid fa-wallet"></i></div>
                    <p class="text-[10px] text-gray-400 font-bold uppercase tracking-wider">Estimated Revenue</p>
                    <h3 class="text-2xl font-bold mt-1">Rp {{ number_format($estimatedRevenue, 0, ',', '.') }}</h3>
                </div>

                <div class="bg-white p-6 rounded-xl border border-gray-100 shadow-sm">
                    <div class="p-2 bg-amber-50 rounded-lg text-primary text-sm w-fit mb-4"><i class="fa-solid fa-cart-shopping"></i></div>
                    <p class="text-[10px] text-gray-400 font-bold uppercase tracking-wider">Pending Orders</p>
                    <h3 class="text-2xl font-bold mt-1">{{ $newOrdersCount }}</h3>
                </div>

                <div class="bg-white p-6 rounded-xl border border-gray-100 shadow-sm">
                    <div class="p-2 bg-amber-50 rounded-lg text-primary text-sm w-fit mb-4"><i class="fa-solid fa-box-archive"></i></div>
                    <p class="text-[10px] text-gray-400 font-bold uppercase tracking-wider">Active Products</p>
                    <h3 class="text-2xl font-bold mt-1">{{ $totalProducts }}</h3>
                </div>

                <div class="bg-white p-6 rounded-xl border border-gray-100 shadow-sm">
                    <div class="p-2 bg-amber-50 rounded-lg text-primary text-sm w-fit mb-4"><i class="fa-solid fa-star"></i></div>
                    <p class="text-[10px] text-gray-400 font-bold uppercase tracking-wider">Store Rating</p>
                    <h3 class="text-2xl font-bold mt-1">{{ number_format($averageRating, 1) }}</h3>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <div class="lg:col-span-2 bg-white p-8 rounded-xl shadow-sm border border-gray-100">
                    <div class="flex justify-between items-center mb-10">
                        <div>
                            <h3 class="text-lg font-bold">Annual Revenue Overview</h3>
                            <p class="text-[10px] text-gray-400 font-bold uppercase tracking-wider mt-1">Performance in {{ $selectedYear }}</p>
                        </div>
                        <div class="flex items-center space-x-4">
                            <form action="{{ route('seller.dashboard') }}" method="GET" id="yearFilterForm">
                                <select name="year" onchange="document.getElementById('yearFilterForm').submit()" class="bg-gray-50 border-none text-[10px] font-black uppercase tracking-widest text-primary px-4 py-2 rounded-lg focus:ring-2 focus:ring-primary/20 cursor-pointer">
                                    @foreach($availableYears as $year)
                                        <option value="{{ $year }}" {{ $selectedYear == $year ? 'selected' : '' }}>Year {{ $year }}</option>
                                    @endforeach
                                </select>
                            </form>
                            <span class="text-[10px] font-black text-primary bg-amber-50 px-3 py-1 rounded-full uppercase tracking-widest">Live Data</span>
                        </div>
                    </div>
                    <div class="h-64">
                        <canvas id="revenueChart"></canvas>
                    </div>
                </div>

                <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
                <script>
                    document.addEventListener('DOMContentLoaded', function() {
                        const ctx = document.getElementById('revenueChart').getContext('2d');
                        const gradient = ctx.createLinearGradient(0, 0, 0, 400);
                        gradient.addColorStop(0, 'rgba(181, 115, 58, 0.2)');
                        gradient.addColorStop(1, 'rgba(181, 115, 58, 0)');

                        new Chart(ctx, {
                            type: 'line',
                            data: {
                                labels: @json($months),
                                datasets: [{
                                    label: 'Revenue',
                                    data: @json($revenueData),
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
                                                return 'Rp ' + new Intl.NumberFormat('id-ID').format(value);
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

                <div class="bg-white p-8 rounded-xl shadow-sm border border-gray-100">
                    <h3 class="text-lg font-bold mb-8">Recent Orders</h3>
                    <div class="space-y-6">
                        @forelse($recentOrders as $order)
                        <div class="flex items-center justify-between group cursor-pointer">
                            <div class="flex items-center space-x-4">
                                <div class="w-10 h-10 rounded-lg bg-gray-50 flex items-center justify-center text-primary group-hover:bg-primary group-hover:text-white transition-all">
                                    <i class="fa-solid fa-bag-shopping"></i>
                                </div>
                                <div>
                                    <p class="text-xs font-bold">{{ $order->customer->user->full_name }}</p>
                                    <p class="text-[9px] text-gray-400 uppercase tracking-widest">{{ $order->created_at->format('d M, H:i') }}</p>
                                </div>
                            </div>
                            <div class="text-right">
                                <p class="text-xs font-bold text-gray-900">Rp {{ number_format($order->total_price, 0, ',', '.') }}</p>
                                <span class="text-[8px] font-black uppercase px-2 py-0.5 rounded-full {{ $order->status == 'completed' ? 'bg-green-50 text-green-600' : 'bg-amber-50 text-amber-600' }}">
                                    {{ $order->status }}
                                </span>
                            </div>
                        </div>
                        @empty
                        <p class="text-xs text-gray-400 text-center py-10">No recent orders yet.</p>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>

        <footer class="p-8 border-t border-gray-100 text-[10px] font-bold text-gray-400 uppercase tracking-widest mt-auto">
            © 2026 DECOR SELLER PORTAL. ALL RIGHTS RESERVED.
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