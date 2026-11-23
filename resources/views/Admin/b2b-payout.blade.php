<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pencairan B2B — DECOR Admin</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=DM+Mono:wght@400;500&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/feather-icons"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800;900&display=swap');
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        .bg-primary { background-color: #B5733A; }
        .text-primary { color: #B5733A; }
        .sidebar-transition { transition: transform 0.3s ease-in-out, margin 0.3s ease-in-out; }
        .sidebar-hidden { transform: translateX(-100%); }
        .main-expanded { margin-left: 0 !important; }
    </style>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body class="text-gray-800 bg-[#F8F6F4]">

@include("Admin.partials.sidebar")

<main id="main-content" class="flex-1 flex flex-col ml-64 sidebar-transition min-h-screen bg-[#F8F6F4]">
    @include("Admin.partials.header", ["title" => "Pencairan Dana B2B"])

    <div class="p-8 space-y-8 flex-1">

        <div>
            <div class="text-[10px] font-black text-primary uppercase tracking-widest mb-1">Keuangan B2B</div>
            <div class="text-2xl font-bold text-gray-900">Pencairan Dana Escrow</div>
            <div class="text-xs text-gray-500 mt-1">Setujui permintaan pencairan dana dari seller setelah order B2B selesai dan BAST ditandatangani.</div>
        </div>

        @if(session('success'))
            <div class="bg-green-50 border border-green-200 text-green-700 px-6 py-4 rounded-xl text-xs font-bold flex items-center">
                <i data-feather="check-circle" class="w-4 h-4 mr-3"></i>
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="bg-red-50 border border-red-200 text-red-700 px-6 py-4 rounded-xl text-xs font-bold flex items-center">
                <i data-feather="alert-triangle" class="w-4 h-4 mr-3"></i>
                {{ session('error') }}
            </div>
        @endif

        <!-- Statistik -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="bg-white p-6 rounded-xl border border-gray-100 shadow-sm relative overflow-hidden">
                <div class="absolute bottom-0 left-0 right-0 h-1 bg-amber-500"></div>
                <div class="w-10 h-10 rounded-lg bg-amber-50 text-amber-600 flex items-center justify-center mb-4">
                    <i data-feather="clock" class="w-5 h-5"></i>
                </div>
                <h3 class="text-2xl font-bold text-gray-900">{{ $stats['requested'] }}</h3>
                <p class="text-[10px] text-gray-400 font-bold uppercase tracking-wider mt-1">Menunggu Persetujuan</p>
                <p class="text-[11px] font-black text-amber-600 mt-1">Rp {{ number_format((float) $stats['requested_amount'], 0, ',', '.') }}</p>
            </div>

            <div class="bg-white p-6 rounded-xl border border-gray-100 shadow-sm relative overflow-hidden">
                <div class="absolute bottom-0 left-0 right-0 h-1 bg-green-500"></div>
                <div class="w-10 h-10 rounded-lg bg-green-50 text-green-600 flex items-center justify-center mb-4">
                    <i data-feather="check-circle" class="w-5 h-5"></i>
                </div>
                <h3 class="text-2xl font-bold text-gray-900">{{ $stats['paid'] }}</h3>
                <p class="text-[10px] text-gray-400 font-bold uppercase tracking-wider mt-1">Sudah Dibayar</p>
                <p class="text-[11px] font-black text-green-600 mt-1">Rp {{ number_format((float) $stats['paid_amount'], 0, ',', '.') }}</p>
            </div>
        </div>

        <!-- Filter -->
        <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
            <div class="p-5 border-b border-gray-100 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                <h3 class="font-bold text-sm text-gray-900">Daftar Pengajuan Pencairan</h3>
                <div class="inline-flex items-center bg-gray-50 border border-gray-200 rounded-lg p-1 text-[10px] font-bold">
                    <a href="{{ route('admin.b2b-payout', ['status' => 'REQUESTED']) }}"
                       class="px-3 py-1.5 rounded-md transition-all {{ $statusFilter === 'REQUESTED' ? 'bg-white text-primary shadow-sm' : 'text-gray-500 hover:text-primary' }}">
                        Menunggu
                    </a>
                    <a href="{{ route('admin.b2b-payout', ['status' => 'PAID']) }}"
                       class="px-3 py-1.5 rounded-md transition-all {{ $statusFilter === 'PAID' ? 'bg-white text-primary shadow-sm' : 'text-gray-500 hover:text-primary' }}">
                        Sudah Dibayar
                    </a>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead class="bg-gray-50 text-[9px] uppercase tracking-widest text-gray-400 font-black">
                        <tr>
                            <th class="px-6 py-3">Order</th>
                            <th class="px-6 py-3">Proyek</th>
                            <th class="px-6 py-3">Seller</th>
                            <th class="px-6 py-3 text-right">Nilai Escrow</th>
                            <th class="px-6 py-3">Diajukan</th>
                            <th class="px-6 py-3">Status</th>
                            <th class="px-6 py-3 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 text-xs">
                        @forelse($orders as $order)
                            <tr class="hover:bg-gray-50/60 transition-colors">
                                <td class="px-6 py-4">
                                    <span class="font-mono font-bold text-gray-800">{{ $order->order_number }}</span>
                                    <p class="text-[10px] text-gray-400 mt-0.5">{{ $order->rfq?->rfq_number ?? '-' }}</p>
                                </td>
                                <td class="px-6 py-4 max-w-[240px]">
                                    <p class="font-bold text-gray-800 truncate">{{ $order->rfq?->project_name ?? '-' }}</p>
                                    <p class="text-[10px] text-gray-400 mt-0.5">Designer: {{ $order->rfq?->designer?->user?->full_name ?? '-' }}</p>
                                </td>
                                <td class="px-6 py-4">
                                    <p class="font-bold text-gray-800">{{ $order->rfq?->seller?->store_name ?? '-' }}</p>
                                    <p class="text-[10px] text-gray-400 mt-0.5">{{ $order->rfq?->seller?->user?->email ?? '-' }}</p>
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <span class="font-bold text-gray-900">Rp {{ number_format((float) $order->total_amount, 0, ',', '.') }}</span>
                                </td>
                                <td class="px-6 py-4 text-gray-600">
                                    {{ $order->payout_requested_at?->format('d M Y H:i') ?? '-' }}
                                </td>
                                <td class="px-6 py-4">
                                    @if($order->payout_status === 'REQUESTED')
                                        <span class="bg-amber-100 text-amber-700 px-2.5 py-1 rounded-full text-[10px] font-bold">MENUNGGU</span>
                                    @else
                                        <span class="bg-emerald-100 text-emerald-700 px-2.5 py-1 rounded-full text-[10px] font-bold">DIBAYAR</span>
                                        <p class="text-[10px] text-gray-400 mt-1">{{ $order->payout_paid_at?->format('d M Y H:i') }}</p>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-right">
                                    @if($order->payout_status === 'REQUESTED')
                                        <form method="POST" action="{{ route('admin.b2b-payout.approve', $order->id) }}" onsubmit="return confirm('Tandai pencairan {{ $order->order_number }} sudah dibayar ke seller?')">
                                            @csrf
                                            <button type="submit" class="inline-flex items-center px-3.5 py-2 rounded-lg text-[10px] font-bold bg-primary text-white hover:bg-[#8A5229] transition-colors">
                                                <i data-feather="check" class="w-3.5 h-3.5 mr-1.5"></i> Setujui & Tandai Dibayar
                                            </button>
                                        </form>
                                    @else
                                        <span class="text-[10px] font-bold text-gray-400 inline-flex items-center">
                                            <i data-feather="check-circle" class="w-3.5 h-3.5 mr-1.5 text-emerald-500"></i> Selesai
                                        </span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-6 py-12 text-center text-gray-400 text-xs">
                                    <i data-feather="inbox" class="w-8 h-8 mx-auto mb-2 text-gray-300"></i>
                                    {{ $statusFilter === 'PAID' ? 'Belum ada pencairan yang dibayar.' : 'Tidak ada pengajuan pencairan yang menunggu persetujuan.' }}
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($orders->hasPages())
                <div class="p-5 border-t border-gray-100">
                    {{ $orders->links() }}
                </div>
            @endif
        </div>
    </div>
    @include("Admin.partials.footer")
</main>

<script>
    feather.replace({ 'stroke-width': 2 });
</script>

<script>
    const btn = document.getElementById("toggle-sidebar");
    const sidebar = document.getElementById("sidebar");
    const main = document.getElementById("main-content");
    if (btn) {
        btn.addEventListener("click", () => {
            sidebar.classList.toggle("sidebar-hidden");
            main.classList.toggle("main-expanded");
        });
    }
</script>
<script async defer data-website-id="cd56ccb0-617d-4bec-8e77-0ba7ea566e9a" data-host-url="/umami-analytics" src="/umami-analytics/script.js"></script>
</body>
</html>
