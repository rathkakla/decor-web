<?php
$current_page = basename($_SERVER['PHP_SELF']);
$current_path = str_replace('.php', '', $current_page);
if ($current_path == "" || $current_path == "index") {
    $current_path = "designer-monitor"; 
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
    ["label" => "Product Validation",  "path" => "product-validation","icon" => "check-circle"],
    ["label" => "Portofolio Validation", "path" => "portofolio-validation","icon" => "image"],
];
$designers = [
    [
        "id"       => "DES-001",
        "name"     => "Julian Thorne",
        "spec"     => "Mid-Century Modern",
        "projects" => 12,
        "rate"     => "94",
        "rating"   => 4.9,
        "status"   => "Verified",
        "revenue"  => "Rp 18.4M",
        "joined"   => "Mar 2023",
    ],
    [
        "id"       => "DES-002",
        "name"     => "Marcus Webb",
        "spec"     => "Industrial Loft",
        "projects" => 2,
        "rate"     => "40",
        "rating"   => 2.5,
        "status"   => "Suspended",
        "revenue"  => "Rp 2.1M",
        "joined"   => "Aug 2023",
    ],
    [
        "id"       => "DES-003",
        "name"     => "Ahmad Zaki",
        "spec"     => "Scandinavian",
        "projects" => 0,
        "rate"     => "0",
        "rating"   => 1.2,
        "status"   => "Banned",
        "revenue"  => "Rp 0",
        "joined"   => "Jan 2024",
    ],
];

$stats = [
    ["label" => "Total Designers", "value" => count($designers), "icon" => "pen-tool",    "note" => "registered"],
    ["label" => "Verified",        "value" => 1,                 "icon" => "check-circle","note" => "active partners"],
    ["label" => "Suspended",       "value" => 1,                 "icon" => "pause-circle","note" => "under review"],
    ["label" => "Banned",          "value" => 1,                 "icon" => "x-circle",    "note" => "removed"],
];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Designer Monitor — DECOR Admin</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=DM+Mono:wght@400;500&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/feather-icons"></script>
    <style>
        :root {
            --primary:        #B5733A;
            --primary-hover:  #8A5229;
            --primary-light:  #F7F0E8;
            --primary-muted:  #E8D4BC;
            --bg:             #F5F2EE;
            --surface:        #FFFFFF;
            --surface-2:      #FAF8F5;
            --border:         #E8E2DB;
            --border-soft:    #EFE9E3;
            --text:           #1C1410;
            --text-soft:      #6B5F55;
            --text-muted:     #A8998D;
            --danger:         #C0392B;
            --danger-bg:      #FEF1F0;
            --success:        #1A7A4A;
            --success-bg:     #EDF7F1;
            --warning:        #B45309;
            --warning-bg:     #FFFBEB;
            --sidebar-w:      256px;
            --radius:         14px;
            --radius-sm:      9px;
            --shadow:         0 1px 4px rgba(28,20,16,.05), 0 4px 18px rgba(28,20,16,.04);
            --shadow-card:    0 2px 8px rgba(28,20,16,.06), 0 8px 32px rgba(28,20,16,.05);
        }

        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background: var(--bg);
            color: var(--text);
            display: flex;
            min-height: 100vh;
            font-size: 13px;
        }

        /* ─── SIDEBAR (unchanged) ─── */
        aside {
            width: var(--sidebar-w); background: var(--surface);
            border-right: 1px solid var(--border);
            display: flex; flex-direction: column;
            position: fixed; height: 100vh; z-index: 1000;
        }
        .brand { padding: 26px 22px 22px; border-bottom: 1px solid var(--border-soft); }
        .brand-row { display: flex; align-items: center; gap: 10px; }
        .brand-icon { width: 36px; height: 36px; background: var(--primary); border-radius: 10px; display: flex; align-items: center; justify-content: center; }
        .brand-icon svg { width: 16px; height: 16px; stroke: #fff; }
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

        /* ─── MAIN ─── */
        main { margin-left: var(--sidebar-w); flex: 1; padding: 36px 40px 72px; }

        /* ─── PAGE HEADER ─── */
        .page-header { display: flex; justify-content: space-between; align-items: flex-end; margin-bottom: 28px; }
        .page-eyebrow { font-size: 10px; font-weight: 800; text-transform: uppercase; letter-spacing: .16em; color: var(--primary); margin-bottom: 5px; }
        .page-title { font-size: 24px; font-weight: 800; color: var(--text); }
        .page-desc { font-size: 12px; color: var(--text-muted); margin-top: 4px; }

        .header-actions { display: flex; align-items: center; gap: 10px; }
        .btn-outline {
            display: flex; align-items: center; gap: 6px;
            padding: 9px 16px; border-radius: 10px;
            font-size: 11px; font-weight: 700;
            border: 1.5px solid var(--border); background: var(--surface);
            color: var(--text-soft); cursor: pointer; transition: 0.15s;
            text-decoration: none;
        }
        .btn-outline:hover { border-color: var(--primary-muted); color: var(--primary); background: var(--primary-light); }
        .btn-outline svg { width: 13px; height: 13px; }

        .btn-primary {
            display: flex; align-items: center; gap: 6px;
            padding: 9px 18px; border-radius: 10px;
            font-size: 11px; font-weight: 700;
            background: var(--primary); color: #fff;
            border: none; cursor: pointer; transition: 0.15s;
            text-decoration: none;
        }
        .btn-primary:hover { background: var(--primary-hover); }
        .btn-primary svg { width: 13px; height: 13px; }

        /* ─── STAT MINI CARDS ─── */
        .stats-row { display: grid; grid-template-columns: repeat(4,1fr); gap: 14px; margin-bottom: 28px; }
        .stat-mini {
            background: var(--surface); border: 1px solid var(--border);
            border-radius: var(--radius); padding: 18px 20px;
            box-shadow: var(--shadow); display: flex; align-items: center; gap: 14px;
            position: relative; overflow: hidden;
            animation: fadeUp 0.4s ease both;
        }
        .stat-mini:nth-child(1){ animation-delay:.05s }
        .stat-mini:nth-child(2){ animation-delay:.1s }
        .stat-mini:nth-child(3){ animation-delay:.15s }
        .stat-mini:nth-child(4){ animation-delay:.2s }
        .stat-mini::after {
            content:''; position:absolute; bottom:0; left:0; right:0;
            height:3px; background: var(--primary);
        }
        .stat-icon {
            width: 40px; height: 40px; border-radius: 10px;
            background: var(--primary-light);
            display: flex; align-items: center; justify-content: center; flex-shrink: 0;
        }
        .stat-icon svg { width: 16px; height: 16px; stroke: var(--primary); }
        .stat-val { font-size: 22px; font-weight: 800; color: var(--text); line-height: 1; }
        .stat-lbl { font-size: 10px; font-weight: 700; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.05em; margin-top: 3px; }
        .stat-note { font-size: 9px; color: var(--text-muted); margin-top: 1px; }

        /* ─── SEARCH + FILTER BAR ─── */
        .toolbar {
            display: flex; align-items: center; gap: 10px; margin-bottom: 20px;
            animation: fadeUp 0.4s ease 0.22s both;
        }
        .search-wrap { position: relative; flex: 1; max-width: 320px; }
        .search-wrap svg { position: absolute; left: 12px; top: 50%; transform: translateY(-50%); width: 14px; height: 14px; stroke: var(--text-muted); pointer-events: none; }
        .search-input {
            width: 100%; padding: 9px 14px 9px 36px;
            border: 1.5px solid var(--border); border-radius: 10px;
            background: var(--surface); font-family: inherit;
            font-size: 12px; font-weight: 500; color: var(--text);
            outline: none; transition: 0.15s;
        }
        .search-input:focus { border-color: var(--primary-muted); background: var(--primary-light); }
        .search-input::placeholder { color: var(--text-muted); }

        .filter-tabs { display: flex; gap: 4px; background: var(--surface); border: 1.5px solid var(--border); border-radius: 10px; padding: 4px; }
        .ftab {
            padding: 6px 14px; border-radius: 7px;
            font-size: 11px; font-weight: 700; cursor: pointer;
            color: var(--text-muted); transition: 0.15s; border: none; background: none;
            font-family: inherit;
        }
        .ftab.active, .ftab:hover { background: var(--primary-light); color: var(--primary); }

        .sort-select {
            padding: 8px 12px; border: 1.5px solid var(--border); border-radius: 10px;
            font-family: inherit; font-size: 11px; font-weight: 600;
            color: var(--text-soft); background: var(--surface); outline: none; cursor: pointer;
        }

        /* ─── DESIGNER CARDS GRID ─── */
        .cards-grid { display: grid; grid-template-columns: repeat(3,1fr); gap: 18px; }

        .designer-card {
            background: var(--surface); border: 1px solid var(--border);
            border-radius: var(--radius); box-shadow: var(--shadow-card);
            overflow: hidden; transition: transform 0.2s, box-shadow 0.2s;
            animation: fadeUp 0.4s ease both;
            position: relative;
        }
        .designer-card:nth-child(1){ animation-delay:.28s }
        .designer-card:nth-child(2){ animation-delay:.34s }
        .designer-card:nth-child(3){ animation-delay:.40s }
        .designer-card:hover { transform: translateY(-3px); box-shadow: 0 8px 40px rgba(28,20,16,0.10); }
        .designer-card.is-banned { opacity: 0.65; }

        /* top colored bar per status */
        .card-bar { height: 4px; width: 100%; }
        .bar-verified  { background: var(--success); }
        .bar-suspended { background: var(--warning); }
        .bar-banned    { background: var(--danger); }

        .card-body { padding: 20px 22px 0; }
        .card-head { display: flex; align-items: flex-start; justify-content: space-between; margin-bottom: 16px; }
        .designer-avatar {
            width: 52px; height: 52px; border-radius: 14px;
            border: 2px solid var(--border-soft); object-fit: cover;
        }
        .designer-avatar.grayed { filter: grayscale(1); }

        .pill { display: inline-flex; align-items: center; gap: 4px; padding: 4px 10px; border-radius: 6px; font-size: 9px; font-weight: 800; text-transform: uppercase; letter-spacing: 0.05em; }
        .pill svg { width: 9px; height: 9px; }
        .pill-verified  { background: var(--success-bg); color: var(--success); }
        .pill-suspended { background: var(--warning-bg); color: var(--warning); }
        .pill-banned    { background: var(--danger-bg);  color: var(--danger); }

        .card-name { font-size: 15px; font-weight: 800; color: var(--text); margin-bottom: 2px; }
        .card-name.name-banned { text-decoration: line-through; color: var(--danger); }
        .card-spec { font-size: 11px; color: var(--text-muted); font-weight: 500; display: flex; align-items: center; gap: 4px; }
        .card-spec svg { width: 10px; height: 10px; }
        .designer-id { font-family: 'DM Mono', monospace; font-size: 9px; color: var(--text-muted); margin-top: 4px; letter-spacing: 0.05em; }

        /* metrics row */
        .card-metrics { display: grid; grid-template-columns: repeat(3,1fr); gap: 1px; background: var(--border-soft); border: 1px solid var(--border-soft); border-radius: 10px; margin: 16px 0; overflow: hidden; }
        .metric { background: var(--surface-2); padding: 12px 0; text-align: center; }
        .metric-val { font-size: 15px; font-weight: 800; color: var(--text); }
        .metric-lbl { font-size: 9px; font-weight: 700; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.05em; margin-top: 2px; }

        /* rating bar */
        .rating-row { display: flex; align-items: center; gap: 8px; margin-bottom: 16px; }
        .stars { display: flex; gap: 2px; }
        .star { width: 11px; height: 11px; }
        .rating-num { font-size: 12px; font-weight: 800; color: var(--text); }
        .rating-count { font-size: 10px; color: var(--text-muted); }
        .rate-bar-wrap { flex: 1; }
        .rate-bar-bg { height: 5px; background: var(--border-soft); border-radius: 99px; }
        .rate-bar-fill { height: 100%; border-radius: 99px; background: var(--primary); transition: width 0.6s ease; }

        /* card footer */
        .card-footer {
            border-top: 1px solid var(--border-soft);
            padding: 12px 22px; margin-top: 0;
            display: flex; align-items: center; justify-content: space-between;
            background: var(--surface-2);
        }
        .joined-txt { font-size: 10px; color: var(--text-muted); font-weight: 500; display: flex; align-items: center; gap: 4px; }
        .joined-txt svg { width: 10px; }
        .revenue-txt { font-size: 11px; font-weight: 800; color: var(--primary); }

        .btn-view {
            display: inline-flex; align-items: center; gap: 6px;
            padding: 7px 14px; border-radius: 8px;
            font-size: 11px; font-weight: 700;
            border: 1.5px solid var(--border); background: var(--surface);
            color: var(--text-soft); cursor: pointer; transition: 0.15s;
            text-decoration: none;
        }
        .btn-view:hover { border-color: var(--primary-muted); color: var(--primary); background: var(--primary-light); }
        .btn-view svg { width: 12px; height: 12px; }

        /* ─── ANIMATION ─── */
        @keyframes fadeUp {
            from { opacity: 0; transform: translateY(14px); }
            to   { opacity: 1; transform: none; }
        }
    </style>
</head>
<body>

<!-- ══════════ SIDEBAR ══════════ -->
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

<!-- ══════════ MAIN ══════════ -->
<main>

    <!-- Page Header -->
    <div class="page-header" style="animation: fadeUp 0.4s ease both;">
        <div>
            <div class="page-eyebrow">Monitoring System</div>
            <div class="page-title">Designer Partnerships</div>
            <div class="page-desc">Audit performa dan kelola status kemitraan desainer.</div>
        </div>
        
    </div>

    <!-- Mini Stats -->
    <div class="stats-row">
        <?php foreach ($stats as $s): ?>
        <div class="stat-mini">
            <div class="stat-icon"><i data-feather="<?= $s['icon'] ?>"></i></div>
            <div>
                <div class="stat-val"><?= $s['value'] ?></div>
                <div class="stat-lbl"><?= $s['label'] ?></div>
                <div class="stat-note"><?= $s['note'] ?></div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>

    <!-- Toolbar -->
    <div class="toolbar">
        <div class="search-wrap">
            <i data-feather="search"></i>
            <input class="search-input" type="text" placeholder="Search designer name or specialty…">
        </div>
        <div class="filter-tabs">
            <button class="ftab active">All</button>
            <button class="ftab">Verified</button>
            <button class="ftab">Suspended</button>
            <button class="ftab">Banned</button>
        </div>
        
    </div>

    <!-- Cards Grid -->
    <div class="cards-grid">
        <?php foreach ($designers as $d):
            $sl = strtolower($d['status']);
            $isBanned     = $d['status'] === 'Banned';
            $isSuspended  = $d['status'] === 'Suspended';
            $starFull  = floor($d['rating']);
            $starEmpty = 5 - $starFull;
            $rateWidth = $d['rate'] . '%';
        ?>
        <div class="designer-card <?= $isBanned ? 'is-banned' : '' ?>">
            <div class="card-bar bar-<?= $sl ?>"></div>
            <div class="card-body">

                <div class="card-head">
                    <img src="https://ui-avatars.com/api/?name=<?= urlencode($d['name']) ?>&background=random&size=104"
                         class="designer-avatar <?= ($isBanned || $isSuspended) ? 'grayed' : '' ?>">
                    <span class="pill pill-<?= $sl ?>">
                        <i data-feather="<?= $sl === 'verified' ? 'check' : ($sl === 'suspended' ? 'pause' : 'x') ?>"></i>
                        <?= $d['status'] ?>
                    </span>
                </div>

                <div class="card-name <?= $isBanned ? 'name-banned' : '' ?>"><?= $d['name'] ?></div>
                <div class="card-spec"><i data-feather="tag"></i><?= $d['spec'] ?></div>
                <div class="designer-id"><?= $d['id'] ?></div>

                <!-- Metrics -->
                <div class="card-metrics">
                    <div class="metric">
                        <div class="metric-val"><?= $d['projects'] ?></div>
                        <div class="metric-lbl">Projects</div>
                    </div>
                    <div class="metric">
                        <div class="metric-val"><?= $d['rate'] ?>%</div>
                        <div class="metric-lbl">Conv. Rate</div>
                    </div>
                    <div class="metric">
                        <div class="metric-val"><?= $d['rating'] ?></div>
                        <div class="metric-lbl">Rating</div>
                    </div>
                </div>

                <!-- Rating + bar -->
                <div class="rating-row">
                    <div class="stars">
                        <?php for($i=0;$i<$starFull;$i++): ?>
                        <svg class="star" viewBox="0 0 16 16" fill="#FFA000"><path d="M8 1l1.85 3.75L14 5.5l-3 2.92.71 4.13L8 10.4l-3.71 2.15.71-4.13L2 5.5l4.15-.75z"/></svg>
                        <?php endfor; ?>
                        <?php for($i=0;$i<$starEmpty;$i++): ?>
                        <svg class="star" viewBox="0 0 16 16" fill="#E8E2DB"><path d="M8 1l1.85 3.75L14 5.5l-3 2.92.71 4.13L8 10.4l-3.71 2.15.71-4.13L2 5.5l4.15-.75z"/></svg>
                        <?php endfor; ?>
                    </div>
                    <span class="rating-num"><?= $d['rating'] ?></span>
                    <div class="rate-bar-wrap">
                        <div class="rate-bar-bg"><div class="rate-bar-fill" style="width:<?= $rateWidth ?>"></div></div>
                    </div>
                    <span class="rating-count"><?= $d['rate'] ?>%</span>
                </div>

            </div><!-- /card-body -->

            <div class="card-footer">
                <div>
                    <div class="joined-txt"><i data-feather="calendar"></i> Joined <?= $d['joined'] ?></div>
                    <div class="revenue-txt"><?= $d['revenue'] ?> earned</div>
                </div>
                <a href="designer-detail?id=<?= $d['id'] ?>" class="btn-view">
                    View Profile <i data-feather="arrow-right"></i>
                </a>
            </div>
        </div>
        <?php endforeach; ?>
    </div>

</main>

<script>
    feather.replace({ 'stroke-width': 2 });
    // Filter tabs (visual only, wire to PHP/AJAX as needed)
    document.querySelectorAll('.ftab').forEach(btn => {
        btn.addEventListener('click', function() {
            document.querySelectorAll('.ftab').forEach(b => b.classList.remove('active'));
            this.classList.add('active');
        });
    });
</script>
</body>
</html>