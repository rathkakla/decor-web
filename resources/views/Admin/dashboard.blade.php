@php
$admin_name = Auth::user()->full_name;
$admin_role = "ADMIN";

$customerSupportCount = \App\Models\Support::whereHas('user', fn($q) => $q->where('role', 'customer'))->where('status', 'pending')->count();
$sellerSupportCount = \App\Models\Support::whereHas('user', fn($q) => $q->where('role', 'seller'))->where('status', 'pending')->count();
$designerSupportCount = \App\Models\Support::whereHas('user', fn($q) => $q->where('role', 'designer'))->where('status', 'pending')->count();

$menu_items = [
    ["label" => "Dashboard",              "path" => route('admin.dashboard'),              "icon" => "grid"],
    ["label" => "User Management",        "path" => route('admin.user-management'),        "icon" => "users"],
    ["label" => "Account Validation",     "path" => route('admin.account.validation'),     "icon" => "shield"],
    ["label" => "Seller Monitor",         "path" => route('admin.seller-monitor'),         "icon" => "shopping-bag"],
    ["label" => "Designer Monitor",       "path" => route('admin.designer-monitor'),       "icon" => "pen-tool"],
    ["label" => "Seller Support",         "path" => route('admin.seller-support'),         "icon" => "headphones", "badge" => $sellerSupportCount],
    ["label" => "Designer Support",       "path" => route('admin.designer-support'),       "icon" => "pen-tool", "badge" => $designerSupportCount],
    ["label" => "Customer Support",       "path" => route('admin.customer-support'),       "icon" => "message-circle", "badge" => $customerSupportCount],
    ["label" => "Product Validation",     "path" => route('admin.product.validation'),     "icon" => "check-circle"],
    ["label" => "Portofolio Validation",  "path" => route('admin.portfolio-validation'),  "icon" => "image"],
];
@endphp
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard — DECOR</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=DM+Serif+Display:ital@0;1&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://unpkg.com/feather-icons"></script>
    <style>
        :root {
            --primary: #B5733A;
            --primary-hover: #8A5229;
            --primary-light: #F7F0E8;
            --primary-muted: #E8D4BC;
            --bg: #F5F2EE;
            --surface: #FFFFFF;
            --surface-2: #FAF8F5;
            --border: #E8E2DB;
            --border-soft: #EFE9E3;
            --text: #1C1410;
            --text-soft: #6B5F55;
            --text-muted: #A8998D;
            --danger: #C0392B;
            --danger-bg: #FEF1F0;
            --success: #1A7A4A;
            --success-bg: #EDF7F1;
            --warning: #B45309;
            --warning-bg: #FFFBEB;
            --sidebar-w: 256px;
            --radius: 14px;
            --radius-sm: 9px;
            --shadow: 0 1px 4px rgba(28,20,16,.05), 0 4px 18px rgba(28,20,16,.04);
            --shadow-card: 0 2px 8px rgba(28,20,16,.06), 0 8px 32px rgba(28,20,16,.05);
        }

        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: 'Plus Jakarta Sans', sans-serif; background: var(--bg); color: var(--text); display: flex; min-height: 100vh; font-size: 13px; }

        /* ─── SIDEBAR ─── */
        aside { width: var(--sidebar-w); background: var(--surface); border-right: 1px solid var(--border); display: flex; flex-direction: column; position: fixed; height: 100vh; z-index: 1000; }
        .brand { padding: 26px 22px 22px; border-bottom: 1px solid var(--border-soft); }
        .brand-row { display: flex; align-items: center; gap: 10px; }
        .brand-icon { width: 36px; height: 36px; background: var(--primary); border-radius: 10px; display: flex; align-items: center; justify-content: center; }
        .brand-icon svg { color: #fff; width: 16px; height: 16px; stroke: #fff; }
        .brand-name { font-size: 16px; font-weight: 800; letter-spacing: 3px; color: var(--primary); text-transform: uppercase; }
        
        .nav-section { padding: 18px 14px 8px; flex: 1; overflow-y: auto; }
        .nav-label { font-size: 9px; font-weight: 800; text-transform: uppercase; letter-spacing: 1.2px; color: var(--text-muted); padding: 0 8px; margin-bottom: 6px; display: block; }
        .menu-link { text-decoration: none; display: block; margin-bottom: 2px; }
        .menu-item { padding: 9px 10px; display: flex; align-items: center; gap: 10px; border-radius: var(--radius-sm); font-size: 12.5px; font-weight: 500; color: var(--text-soft); transition: all .15s ease; border: 1px solid transparent; }
        .menu-item svg { width: 15px; height: 15px; flex-shrink: 0; }
        .menu-item:hover { background: var(--surface-2); color: var(--text); }
        .menu-link.active .menu-item { background: var(--primary-light); color: var(--primary); font-weight: 700; border-color: var(--primary-muted); }
        
        .sidebar-footer { padding: 16px 22px; border-top: 1px solid var(--border-soft); display: flex; align-items: center; gap: 12px; }
        .s-avatar { width: 36px; height: 36px; border-radius: 10px; border: 2px solid var(--border-soft); object-fit: cover; }
        .s-name { font-size: 12px; font-weight: 700; }
        .s-role { font-size: 9px; color: var(--text-muted); text-transform: uppercase; font-weight: 600; }
        .s-dot { margin-left: auto; width: 8px; height: 8px; border-radius: 50%; background: var(--success); }

        /* ─── MAIN ─── */
        main { margin-left: var(--sidebar-w); flex: 1; padding: 30px 38px 60px; }

        /* ─── PAGE HEADER ─── */
        .page-header { display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 28px; }
        .page-eyebrow { font-size: 10px; font-weight: 800; text-transform: uppercase; letter-spacing: .16em; color: var(--primary); margin-bottom: 5px; }
        .page-title { font-size: 22px; font-weight: 800; letter-spacing: -.02em; color: var(--text); }
        .page-sub { font-size: 12px; color: var(--text-muted); margin-top: 4px; }
        .date-pill { display: flex; align-items: center; gap: 7px; background: var(--surface); border: 1px solid var(--border); padding: 8px 16px; border-radius: 99px; font-size: 11px; font-weight: 600; color: var(--text-soft); }
        .date-pill svg { width: 12px; height: 12px; stroke: var(--primary); }

        /* ─── STATS ─── */
        .stats-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 16px; margin-bottom: 22px; }
        .stat-card { background: var(--surface); border: 1px solid var(--border); border-radius: var(--radius); padding: 20px 22px; box-shadow: var(--shadow); position: relative; overflow: hidden; transition: 0.2s ease; }
        .stat-card:hover { box-shadow: var(--shadow-card); transform: translateY(-2px); }
        .stat-card::after { content: ''; position: absolute; top: 0; left: 0; right: 0; height: 3px; background: linear-gradient(90deg, var(--primary), #D4956A); }
        .stat-label { font-size: 10px; font-weight: 800; text-transform: uppercase; letter-spacing: .12em; color: var(--text-muted); }
        .stat-value { font-size: 22px; font-weight: 800; margin: 8px 0 6px; letter-spacing: -.02em; color: var(--text); }
        .stat-trend { display: inline-flex; align-items: center; gap: 4px; font-size: 10px; font-weight: 700; background: var(--success-bg); color: var(--success); padding: 3px 8px; border-radius: 6px; }
        .stat-icon { position: absolute; right: 18px; top: 18px; width: 34px; height: 34px; border-radius: 9px; background: var(--primary-light); display: flex; align-items: center; justify-content: center; }
        .stat-icon svg { width: 15px; height: 15px; stroke: var(--primary); }

        /* ─── CHART ─── */
        .panel { background: var(--surface); border: 1px solid var(--border); border-radius: var(--radius); padding: 22px 24px; box-shadow: var(--shadow); }
        .panel-header { display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 20px; }
        .panel-title { font-size: 13px; font-weight: 800; color: var(--text); }
        .panel-sub { font-size: 11px; color: var(--text-muted); margin-top: 2px; }
        .panel-badge { font-size: 10px; font-weight: 800; background: var(--primary-light); color: var(--primary); border: 1px solid var(--primary-muted); padding: 4px 12px; border-radius: 99px; }
        .chart-wrap { height: 220px; position: relative; }

        /* ─── TABLES ─── */
        .tx-row { display: grid; grid-template-columns: 1fr 1fr; gap: 18px; }
        table { width: 100%; border-collapse: collapse; }
        thead th { font-size: 9px; font-weight: 800; text-transform: uppercase; letter-spacing: .12em; color: var(--text-muted); padding: 0 0 12px; border-bottom: 1.5px solid var(--border-soft); text-align: left; }
        tbody td { padding: 12px 0; font-size: 12px; border-bottom: 1px solid var(--border-soft); vertical-align: middle; }
        .tx-id { font-size: 10px; font-weight: 700; color: var(--text-muted); font-family: monospace; }
        .tx-name { font-weight: 700; font-size: 12.5px; color: var(--text); }
        .tx-amt { font-weight: 700; color: var(--text); }
        .tx-fee { font-weight: 800; color: var(--primary); }
        .view-all { display: inline-flex; align-items: center; gap: 5px; font-size: 11px; color: var(--primary); font-weight: 700; text-decoration: none; margin-top: 14px; padding: 6px 12px; background: var(--primary-light); border: 1px solid var(--primary-muted); border-radius: 8px; }

        @keyframes fadeUp { from { opacity:0; transform:translateY(12px); } to { opacity:1; transform:none; } }
        .fade-up { animation: fadeUp .45s ease both; }
    </style>
</head>
<body>

<aside>
    <div class="brand">
        <div class="brand-row">
            <div class="brand-icon"><i data-feather="layout"></i></div>
            <div class="brand-name">DECOR</div>
        </div>
    </div>

    <div class="nav-section">
        <span class="nav-label">Navigation</span>
        @foreach ($menu_items as $item)
        @php $active = (request()->url() === $item['path']) ? 'active' : ''; @endphp
        <a href="{{ $item['path'] }}" class="menu-link {{ $active }}">
            <div class="menu-item" style="position: relative;">
                <i data-feather="{{ $item['icon'] }}"></i>
                <span>{{ $item['label'] }}</span>
                @if(isset($item['badge']) && $item['badge'] > 0)
                <span style="position: absolute; right: 10px; top: 50%; transform: translateY(-50%); background: var(--danger); color: white; border-radius: 99px; font-size: 8px; font-weight: 800; padding: 2px 6px; box-shadow: 0 2px 8px rgba(192,57,43,0.3);">{{ $item['badge'] }}</span>
                @endif
            </div>
        </a>
        @endforeach

        <span class="nav-label" style="margin-top:20px;">System</span>
        <a href="{{ route('admin.settings') }}" class="menu-link"><div class="menu-item"><i data-feather="settings"></i><span>Settings</span></div></a>
        <form method="POST" action="{{ route('logout') }}" id="logout-form">
            @csrf
            <a href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" class="menu-link">
                <div class="menu-item">
                    <i data-feather="log-out"></i>
                    <span>Logout</span>
                </div>
            </a>
        </form>
    </div>

    <div class="sidebar-footer">
        <img src="https://ui-avatars.com/api/?name={{ urlencode($admin_name) }}&background=B5733A&color=fff&size=80" class="s-avatar">
        <div>
            <div class="s-name">{{ $admin_name }}</div>
            <div class="s-role">{{ $admin_role }}</div>
        </div>
        <span class="s-dot"></span>
    </div>
</aside>

<main>
    <div class="page-header fade-up">
        <div>
            <div class="page-eyebrow">DECOR Marketplace</div>
            <div class="page-title">Executive Overview</div>
            <div class="page-sub">Admin fees, seller & designer performance monitoring</div>
        </div>
        <div class="date-pill">
            <i data-feather="calendar"></i>
            <span id="live-date"></span>
        </div>
    </div>

    <div class="stats-grid">
        <div class="stat-card fade-up">
            <div class="stat-icon"><i data-feather="dollar-sign"></i></div>
            <div class="stat-label">Total Admin Fees</div>
            <div class="stat-value">Rp {{ number_format($stats['total_fees'], 0, ',', '.') }}</div>
            <span class="stat-trend">↑ 15.2%</span>
        </div>
        <div class="stat-card fade-up">
            <div class="stat-icon"><i data-feather="store"></i></div>
            <div class="stat-label">Active Sellers</div>
            <div class="stat-value">{{ $stats['active_sellers'] }}</div>
            <span class="stat-trend">↑ 4</span>
        </div>
        <div class="stat-card fade-up">
            <div class="stat-icon"><i data-feather="palette"></i></div>
            <div class="stat-label">Active Designers</div>
            <div class="stat-value">{{ $stats['active_designers'] }}</div>
            <span class="stat-trend">↑ 2</span>
        </div>
        <div class="stat-card fade-up">
            <div class="stat-icon"><i data-feather="repeat"></i></div>
            <div class="stat-label">Total Transactions</div>
            <div class="stat-value">{{ number_format($stats['total_transactions']) }}</div>
            <span class="stat-trend">Today</span>
        </div>
    </div>

    <div class="panel fade-up" style="margin-bottom: 22px;">
        <div class="panel-header">
            <div>
                <div class="panel-title">Admin Fee Revenue</div>
                <div class="panel-sub">Monthly performance — 5% per transaction</div>
            </div>
            <span class="panel-badge">2026</span>
        </div>
        <div class="chart-wrap">
            <canvas id="revenueChart"></canvas>
        </div>
    </div>

    <div class="tx-row fade-up">
        <!-- Seller Transactions -->
        <div class="panel">
            <div class="panel-header">
                <div>
                    <div class="panel-title">Recent Seller Transactions</div>
                    <div class="panel-sub">Monitor fee masuk dari produk</div>
                </div>
            </div>
            <table>
                <thead><tr><th>Order ID</th><th>Seller</th><th>Amount</th><th>Fee</th></tr></thead>
                <tbody>
                    @foreach($recentSellerTransactions as $tx)
                    <tr>
                        <td><span class="tx-id">#DC-{{ str_pad($tx->order->id, 4, '0', STR_PAD_LEFT) }}</span></td>
                        <td><span class="tx-name">{{ $tx->product->seller->user->full_name }}</span></td>
                        <td><span class="tx-amt">Rp {{ number_format($tx->price * $tx->quantity, 0, ',', '.') }}</span></td>
                        <td><span class="tx-fee">Rp {{ number_format($tx->price * $tx->quantity * 0.05, 0, ',', '.') }}</span></td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            <a href="{{ route('admin.seller-monitor') }}" class="view-all">View All Transactions</a>
        </div>

        <!-- Designer Transactions -->
        <div class="panel">
            <div class="panel-header">
                <div>
                    <div class="panel-title">Recent Designer Transactions</div>
                    <div class="panel-sub">Monitor fee dari jasa desain</div>
                </div>
            </div>
            <table>
                <thead><tr><th>Quote ID</th><th>Designer</th><th>Amount</th><th>Fee</th></tr></thead>
                <tbody>
                    @foreach($recentDesignerTransactions as $tx)
                    <tr>
                        <td><span class="tx-id">#DD-{{ str_pad($tx->id, 4, '0', STR_PAD_LEFT) }}</span></td>
                        <td><span class="tx-name">{{ $tx->consultation->designer->user->full_name }}</span></td>
                        <td><span class="tx-amt">Rp {{ number_format($tx->amount, 0, ',', '.') }}</span></td>
                        <td><span class="tx-fee">Rp {{ number_format($tx->amount * 0.05, 0, ',', '.') }}</span></td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            <a href="{{ route('admin.designer-monitor') }}" class="view-all">View All Transactions</a>
        </div>
    </div>
</main>

<script>
    feather.replace({ 'stroke-width': 1.75 });
    document.getElementById('live-date').textContent = new Date().toLocaleDateString('en-GB', {
        day: 'numeric', month: 'short', year: 'numeric'
    });

    const ctx = document.getElementById('revenueChart').getContext('2d');
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug'],
            datasets: [{
                data: @json($revenueData).map(v => v * 1000000),
                borderColor: '#B5733A',
                backgroundColor: 'rgba(181,115,58,0.1)',
                fill: true, tension: 0.4, borderWidth: 3, pointRadius: 4
            }]
        },
        options: {
            responsive: true, maintainAspectRatio: false,
            plugins: { legend: { display: false } },
            scales: { y: { ticks: { callback: v => 'Rp ' + (v/1000000).toFixed(1) + 'M' } } }
        }
    });
</script>
</body>
</html>