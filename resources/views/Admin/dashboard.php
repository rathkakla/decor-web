<?php
// Deteksi halaman aktif untuk class 'active' di sidebar
$current_page = basename($_SERVER['PHP_SELF']);
$current_path = str_replace('.php', '', $current_page);

if ($current_path == "" || $current_path == "index") {
    $current_path = "dashboard"; 
}

$admin_name = "Alex Rivera";
$admin_role = "SUPER ADMIN";

$menu_items = [
    ["label" => "Dashboard",          "path" => "dashboard",         "icon" => "grid"],
    ["label" => "User Management",     "path" => "user-management",   "icon" => "users"],
    ["label" => "Seller Monitor",      "path" => "seller-monitor",    "icon" => "shopping-bag"],
    ["label" => "Designer Monitor",    "path" => "designer-monitor",  "icon" => "pen-tool"],
    ["label" => "Seller Support",      "path" => "seller-support",    "icon" => "headphones"],
    ["label" => "Designer Support",    "path" => "designer-support",  "icon" => "pen-tool"],
    ["label" => "Customer Support",    "path" => "customer-support",  "icon" => "message-circle"],
    ["label" => "Product Validation",  "path" => "product-validation", "icon" => "check-circle"],
    ["label" => "Portofolio Validation", "path" => "portfolio-validation", "icon" => "image"],
];

$stats = [
    ["label" => "Total Admin Fees",     "value" => "Rp 12.450.000", "trend" => "+15.2%",  "up" => true],
    ["label" => "Active Sellers",       "value" => "148",           "trend" => "+4",       "up" => true],
    ["label" => "Active Designers",     "value" => "93",            "trend" => "+2",       "up" => true],
    ["label" => "Total Transactions",   "value" => "1,204",         "trend" => "Today",    "up" => true],
];
?>
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
            --secondary: #E3DCD6;
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
        }

        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: 'Plus Jakarta Sans', sans-serif; background: var(--bg); color: var(--text); display: flex; min-height: 100vh; font-size: 13px; }

        /* SIDEBAR */
        aside {
            width: var(--sidebar-w); background: var(--surface);
            border-right: 1px solid var(--border);
            display: flex; flex-direction: column;
            position: fixed; height: 100vh; z-index: 1000;
        }
        .brand { padding: 26px 22px 22px; border-bottom: 1px solid var(--border-soft); }
        .brand-row { display: flex; align-items: center; gap: 10px; }
        .brand-icon { width: 36px; height: 36px; background: var(--primary); border-radius: 10px; display: flex; align-items: center; justify-content: center; }
        .brand-icon svg { color: #fff; width: 16px; height: 16px; stroke: #fff; }
        .brand-name { font-size: 16px; font-weight: 800; letter-spacing: 3px; color: var(--primary); text-transform: uppercase; }
        .brand-sub { font-size: 9px; color: var(--text-muted); letter-spacing: 1.5px; text-transform: uppercase; margin-top: 2px; font-weight: 600; }
        
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

        /* MAIN CONTENT */
        main { margin-left: var(--sidebar-w); flex: 1; padding: 30px 38px 60px; }
        .page-header { display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 28px; }
        .page-eyebrow { font-size: 10px; font-weight: 800; text-transform: uppercase; letter-spacing: .16em; color: var(--primary); margin-bottom: 5px; }
        .page-title { font-size: 22px; font-weight: 800; letter-spacing: -.02em; color: var(--text); }
        .page-sub { font-size: 12px; color: var(--text-muted); margin-top: 4px; }
        .date-pill { display: flex; align-items: center; gap: 7px; background: var(--surface); border: 1px solid var(--border); padding: 8px 16px; border-radius: 99px; font-size: 11px; font-weight: 600; color: var(--text-soft); }
        .date-pill svg { width: 12px; height: 12px; stroke: var(--primary); }

        .stats-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 16px; margin-bottom: 22px; }
        .stat-card { background: var(--surface); border: 1px solid var(--border); border-radius: var(--radius); padding: 20px 22px; box-shadow: var(--shadow); position: relative; overflow: hidden; transition: box-shadow .2s, transform .2s; }
        .stat-card:hover { box-shadow: 0 6px 24px rgba(181,115,58,.1); transform: translateY(-2px); }
        .stat-card::after { content: ''; position: absolute; top: 0; left: 0; right: 0; height: 3px; background: linear-gradient(90deg, var(--primary), #D4956A); }
        .stat-label { font-size: 10px; font-weight: 800; text-transform: uppercase; letter-spacing: .12em; color: var(--text-muted); }
        .stat-value { font-size: 22px; font-weight: 800; margin: 8px 0 6px; letter-spacing: -.02em; color: var(--text); }
        .stat-trend { display: inline-flex; align-items: center; gap: 4px; font-size: 10px; font-weight: 700; background: var(--success-bg); color: var(--success); padding: 3px 8px; border-radius: 6px; }
        .stat-icon { position: absolute; right: 18px; top: 18px; width: 34px; height: 34px; border-radius: 9px; background: var(--primary-light); display: flex; align-items: center; justify-content: center; }
        .stat-icon svg { width: 15px; height: 15px; stroke: var(--primary); }

        .chart-row { display: grid; grid-template-columns: 2fr 1fr; gap: 18px; margin-bottom: 22px; }
        .panel { background: var(--surface); border: 1px solid var(--border); border-radius: var(--radius); padding: 22px 24px; box-shadow: var(--shadow); }
        .panel-header { display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 20px; }
        .panel-title { font-size: 13px; font-weight: 800; color: var(--text); }
        .panel-sub { font-size: 11px; color: var(--text-muted); margin-top: 2px; }
        .panel-badge { font-size: 10px; font-weight: 800; background: var(--primary-light); color: var(--primary); border: 1px solid var(--primary-muted); padding: 4px 12px; border-radius: 99px; }
        .chart-wrap { height: 220px; position: relative; }

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
        .d1{animation-delay:.05s} .d2{animation-delay:.12s} .d3{animation-delay:.2s} .d4{animation-delay:.28s}
    </style>
</head>
<body>

<aside>
    <div class="brand">
        <div class="brand-row">
            <div class="brand-icon"><i data-feather="layout"></i></div>
            <div>
                <div class="brand-name">DECOR</div>
                
            </div>
        </div>
    </div>

    <div class="nav-section">
        <span class="nav-label">Navigation</span>
        <?php foreach ($menu_items as $item):
            $active = ($item['path'] === $current_path) ? 'active' : '';
        ?>
        <a href="<?= $item['path'] ?>" class="menu-link <?= $active ?>">
            <div class="menu-item">
                <i data-feather="<?= $item['icon'] ?>"></i>
                <span><?= $item['label'] ?></span>
            </div>
        </a>
        <?php endforeach; ?>

        <span class="nav-label" style="margin-top:20px;">System</span>
        <a href="settings" class="menu-link"><div class="menu-item"><i data-feather="settings"></i><span>Settings</span></div></a>
        <a href="logout"   class="menu-link"><div class="menu-item"><i data-feather="log-out"></i><span>Logout</span></div></a>
    </div>

    <div class="sidebar-footer">
        <img src="https://ui-avatars.com/api/?name=Alex+Rivera&background=B5733A&color=fff&size=80" class="s-avatar">
        <div>
            <div class="s-name"><?= $admin_name ?></div>
            <div class="s-role"><?= $admin_role ?></div>
        </div>
        <span class="s-dot"></span>
    </div>
</aside>

<main>
    <div class="page-header fade-up d1">
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
        <?php 
        $stat_icons = ['dollar-sign','store','palette','repeat'];
        foreach ($stats as $i => $s): ?>
        <div class="stat-card fade-up d2">
            <div class="stat-icon"><i data-feather="<?= $stat_icons[$i] ?>"></i></div>
            <div class="stat-label"><?= $s['label'] ?></div>
            <div class="stat-value"><?= $s['value'] ?></div>
            <span class="stat-trend">↑ <?= $s['trend'] ?></span>
        </div>
        <?php endforeach; ?>
    </div>

    <div class="panel fade-up d3" style="margin-bottom: 22px;">
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

    <div class="tx-row fade-up d4">
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
                    <tr>
                        <td><span class="tx-id">#DC-8812</span></td>
                        <td><span class="tx-name">Studio Archi</span></td>
                        <td><span class="tx-amt">Rp 2.5M</span></td>
                        <td><span class="tx-fee">Rp 125K</span></td>
                    </tr>
                    <tr>
                        <td><span class="tx-id">#DC-8813</span></td>
                        <td><span class="tx-name">Modern Craft</span></td>
                        <td><span class="tx-amt">Rp 1.2M</span></td>
                        <td><span class="tx-fee">Rp 60K</span></td>
                    </tr>
                </tbody>
            </table>
            <a href="seller-monitor" class="view-all">View All Transactions</a>
        </div>

        <div class="panel">
            <div class="panel-header">
                <div>
                    <div class="panel-title">Recent Designer Transactions</div>
                    <div class="panel-sub">Monitor fee dari jasa desain</div>
                </div>
            </div>
            <table>
                <thead><tr><th>Order ID</th><th>Designer</th><th>Amount</th><th>Fee</th></tr></thead>
                <tbody>
                    <tr>
                        <td><span class="tx-id">#DD-2201</span></td>
                        <td><span class="tx-name">Reza Atelier</span></td>
                        <td><span class="tx-amt">Rp 4.5M</span></td>
                        <td><span class="tx-fee">Rp 225K</span></td>
                    </tr>
                    <tr>
                        <td><span class="tx-id">#DD-2202</span></td>
                        <td><span class="tx-name">Hana Studio</span></td>
                        <td><span class="tx-amt">Rp 2.2M</span></td>
                        <td><span class="tx-fee">Rp 110K</span></td>
                    </tr>
                </tbody>
            </table>
            <a href="designer-monitor" class="view-all">View All Transactions</a>
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
                data: [1200000, 2100000, 1800000, 2800000, 2200000, 3500000, 3100000, 4500000],
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