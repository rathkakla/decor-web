<?php
$current_page = basename($_SERVER['PHP_SELF']);
$current_path = str_replace('.php', '', $current_page);
if ($current_path == "" || $current_path == "index") {
    $current_path = "portofolio-validation";
}

$admin_name = "Alex Rivera";
$admin_role = "SUPER ADMIN";

$menu_items = [
    ["label" => "Dashboard",              "path" => "dashboard",              "icon" => "grid"],
    ["label" => "User Management",        "path" => "user-management",        "icon" => "users"],
    ["label" => "Seller Monitor",         "path" => "seller-monitor",         "icon" => "shopping-bag"],
    ["label" => "Designer Monitor",       "path" => "designer-monitor",       "icon" => "pen-tool"],
    ["label" => "Seller Support",         "path" => "seller-support",         "icon" => "headphones"],
    ["label" => "Designer Support",       "path" => "designer-support",       "icon" => "pen-tool"],
    ["label" => "Customer Support",       "path" => "customer-support",       "icon" => "message-circle"],
    ["label" => "Product Validation",     "path" => "product-validation",     "icon" => "check-circle"],
    ["label" => "Portofolio Validation",  "path" => "portofolio-validation",  "icon" => "image"],
];

$pending_portfolios = [
    [
        "id"          => "PF-772",
        "designer"    => "Julian Thorne",
        "title"       => "Warm Minimalist Penthouse",
        "style"       => "Mid-Century Modern",
        "image"       => "https://images.unsplash.com/photo-1600210492486-724fe5c67fb0?w=600",
        "description" => "Proyek renovasi apartemen dengan fokus pada pencahayaan alami dan material kayu walnut premium.",
        "material"    => "Solid Walnut, Travertine Stone",
        "dimensions"  => "120 sqm",
        "duration"    => "3 Months",
        "submitted"   => "15 Mins Ago",
        "status"      => "Pending",
    ],
    [
        "id"          => "PF-775",
        "designer"    => "Elena Rodriguez",
        "title"       => "Zen Garden Studio",
        "style"       => "Japanese Minimalist",
        "image"       => "https://images.unsplash.com/photo-1594051663637-99c39a7c6a99?w=600",
        "description" => "Transformasi studio kecil menjadi ruang meditasi yang menenangkan dengan elemen bambu dan batu alam.",
        "material"    => "Bamboo, Slate Tile",
        "dimensions"  => "45 sqm",
        "duration"    => "1.5 Months",
        "submitted"   => "3 Hours Ago",
        "status"      => "Review",
    ],
    [
        "id"          => "PF-780",
        "designer"    => "Marcus Webb",
        "title"       => "Industrial Loft Conversion",
        "style"       => "Industrial Chic",
        "image"       => "https://images.unsplash.com/photo-1618221195710-dd6b41faaea6?w=600",
        "description" => "Konversi gudang menjadi hunian modern dengan ekspos bata merah, besi hitam, dan aksen kayu reclaimed.",
        "material"    => "Reclaimed Wood, Exposed Brick, Iron",
        "dimensions"  => "210 sqm",
        "duration"    => "5 Months",
        "submitted"   => "Yesterday",
        "status"      => "Pending",
    ],
];

$stats = [
    ["label" => "Pending",   "value" => "08",  "icon" => "clock",        "note" => "menunggu kurasi",   "color" => "warning"],
    ["label" => "Published", "value" => "124", "icon" => "check-circle", "note" => "karya live",        "color" => "success"],
    ["label" => "Rejected",  "value" => "12",  "icon" => "x-circle",     "note" => "tidak layak",       "color" => "danger"],
    ["label" => "Total",     "value" => "144", "icon" => "zap",          "note" => "semua portofolio",  "color" => "primary"],
];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Portfolio Validation — DECOR Admin</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=DM+Mono:wght@400;500&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/feather-icons"></script>
    <style>
        :root {
            --primary:       #B5733A;
            --primary-hover: #8A5229;
            --primary-light: #F7F0E8;
            --primary-muted: #E8D4BC;
            --bg:            #F5F2EE;
            --surface:       #FFFFFF;
            --surface-2:     #FAF8F5;
            --border:        #E8E2DB;
            --border-soft:   #EFE9E3;
            --text:          #1C1410;
            --text-soft:     #6B5F55;
            --text-muted:    #A8998D;
            --danger:        #C0392B;
            --danger-bg:     #FEF1F0;
            --success:       #1A7A4A;
            --success-bg:    #EDF7F1;
            --warning:       #B45309;
            --warning-bg:    #FFFBEB;
            --info:          #1565C0;
            --info-bg:       #E3F2FD;
            --sidebar-w:     256px;
            --radius:        14px;
            --radius-sm:     9px;
            --shadow:        0 1px 4px rgba(28,20,16,.05), 0 4px 18px rgba(28,20,16,.04);
            --shadow-card:   0 2px 8px rgba(28,20,16,.06), 0 8px 32px rgba(28,20,16,.05);
        }

        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: 'Plus Jakarta Sans', sans-serif; background: var(--bg); color: var(--text); display: flex; min-height: 100vh; font-size: 13px; }

        /* ─── SIDEBAR ─── */
        aside { width: var(--sidebar-w); background: var(--surface); border-right: 1px solid var(--border); display: flex; flex-direction: column; position: fixed; height: 100vh; z-index: 1000; }
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
        .page-header { display: flex; justify-content: space-between; align-items: flex-end; margin-bottom: 28px; animation: fadeUp 0.4s ease both; }
        .page-eyebrow { font-size: 10px; font-weight: 800; text-transform: uppercase; letter-spacing: .16em; color: var(--primary); margin-bottom: 5px; }
        .page-title { font-size: 24px; font-weight: 800; color: var(--text); }
        .page-desc { font-size: 12px; color: var(--text-muted); margin-top: 4px; }

        /* ─── STATS ─── */
        .stats-row { display: grid; grid-template-columns: repeat(4,1fr); gap: 14px; margin-bottom: 28px; }
        .stat-mini { background: var(--surface); border: 1px solid var(--border); border-radius: var(--radius); padding: 18px 20px; box-shadow: var(--shadow); display: flex; align-items: center; gap: 14px; position: relative; overflow: hidden; animation: fadeUp 0.4s ease both; }
        .stat-mini:nth-child(1){ animation-delay:.05s } .stat-mini:nth-child(2){ animation-delay:.10s }
        .stat-mini:nth-child(3){ animation-delay:.15s } .stat-mini:nth-child(4){ animation-delay:.20s }
        .stat-mini::after { content:''; position:absolute; bottom:0; left:0; right:0; height:3px; background: var(--primary); }
        .stat-icon { width: 40px; height: 40px; border-radius: 10px; background: var(--primary-light); display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
        .stat-icon svg { width: 16px; height: 16px; stroke: var(--primary); }
        .stat-val { font-size: 22px; font-weight: 800; color: var(--text); line-height: 1; }
        .stat-lbl { font-size: 10px; font-weight: 700; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.05em; margin-top: 3px; }
        .stat-note { font-size: 9px; color: var(--text-muted); margin-top: 1px; }
        .clr-warning { color: var(--warning); } .clr-success { color: var(--success); }
        .clr-danger  { color: var(--danger); }  .clr-primary { color: var(--primary); }

        /* ─── TOOLBAR ─── */
        .toolbar { display: flex; align-items: center; gap: 10px; margin-bottom: 20px; animation: fadeUp 0.4s ease 0.22s both; }
        .search-wrap { position: relative; flex: 1; max-width: 320px; }
        .search-wrap svg { position: absolute; left: 12px; top: 50%; transform: translateY(-50%); width: 14px; height: 14px; stroke: var(--text-muted); pointer-events: none; }
        .search-input { width: 100%; padding: 9px 14px 9px 36px; border: 1.5px solid var(--border); border-radius: 10px; background: var(--surface); font-family: inherit; font-size: 12px; font-weight: 500; color: var(--text); outline: none; transition: 0.15s; }
        .search-input:focus { border-color: var(--primary-muted); background: var(--primary-light); }
        .search-input::placeholder { color: var(--text-muted); }
        .filter-tabs { display: flex; gap: 4px; background: var(--surface); border: 1.5px solid var(--border); border-radius: 10px; padding: 4px; }
        .ftab { padding: 6px 14px; border-radius: 7px; font-size: 11px; font-weight: 700; cursor: pointer; color: var(--text-muted); transition: 0.15s; border: none; background: none; font-family: inherit; }
        .ftab.active, .ftab:hover { background: var(--primary-light); color: var(--primary); }

        /* ─── PORTFOLIO LIST ─── */
        .product-list { display: flex; flex-direction: column; gap: 10px; animation: fadeUp 0.4s ease 0.28s both; }

        .product-row {
            background: var(--surface); border: 1px solid var(--border);
            border-radius: var(--radius); box-shadow: var(--shadow);
            display: grid; grid-template-columns: 72px 210px 1fr 130px 110px 90px 180px;
            align-items: center; gap: 0;
            transition: transform 0.15s, box-shadow 0.15s;
            overflow: hidden; position: relative;
        }
        .product-row:hover { transform: translateY(-2px); box-shadow: var(--shadow-card); }
        .product-row::before { content: ''; position: absolute; left: 0; top: 0; bottom: 0; width: 4px; background: var(--warning); }
        .product-row.st-review::before { background: var(--info); }

        .tcell { padding: 14px 16px; border-right: 1px solid var(--border-soft); }
        .tcell:last-child { border-right: none; }
        .tcell:first-child { padding: 0; }

        .prod-thumb { width: 72px; height: 72px; object-fit: cover; display: block; cursor: pointer; transition: opacity 0.15s; }
        .prod-thumb:hover { opacity: 0.85; }

        .prod-id { font-family: 'DM Mono', monospace; font-size: 10px; font-weight: 500; color: var(--primary); }
        .prod-name { font-size: 13px; font-weight: 700; color: var(--text); margin-top: 3px; }
        .prod-shop { font-size: 10px; color: var(--text-muted); margin-top: 2px; font-weight: 600; }

        .cat-badge { display: inline-flex; padding: 3px 9px; border-radius: 5px; font-size: 9px; font-weight: 800; text-transform: uppercase; background: var(--primary-light); color: var(--primary); }

        .prod-dim { font-size: 13px; font-weight: 700; color: var(--text); }
        .prod-dur { font-size: 10px; color: var(--text-muted); margin-top: 3px; }

        .tkt-time { font-size: 10px; color: var(--text-muted); font-weight: 500; display: flex; align-items: center; gap: 3px; }
        .tkt-time svg { width: 10px; height: 10px; }

        .status-pill { display: inline-flex; align-items: center; gap: 5px; padding: 5px 11px; border-radius: 7px; font-size: 9px; font-weight: 800; text-transform: uppercase; }
        .st-pending { background: var(--warning-bg); color: var(--warning); }
        .st-review  { background: var(--info-bg);    color: var(--info); }

        .action-cell { display: flex; gap: 6px; }
        .btn-approve {
            display: inline-flex; align-items: center; gap: 5px;
            padding: 7px 12px; border-radius: 8px; font-size: 10px; font-weight: 700;
            background: var(--success-bg); color: var(--success);
            border: 1.5px solid #B8DEC8; cursor: pointer; transition: 0.15s; white-space: nowrap;
        }
        .btn-approve:hover { background: var(--success); color: #fff; border-color: var(--success); }
        .btn-reject {
            display: inline-flex; align-items: center; gap: 5px;
            padding: 7px 12px; border-radius: 8px; font-size: 10px; font-weight: 700;
            background: var(--danger-bg); color: var(--danger);
            border: 1.5px solid #F5C6C1; cursor: pointer; transition: 0.15s; white-space: nowrap;
        }
        .btn-reject:hover { background: var(--danger); color: #fff; border-color: var(--danger); }
        .btn-approve svg, .btn-reject svg { width: 11px; height: 11px; }

        /* row disabled after decision */
        .product-row.decided { opacity: 0.5; pointer-events: none; }
        .product-row.decided::before { background: var(--text-muted) !important; }

        /* ─── DETAIL MODAL ─── */
        .detail-modal { display: none; position: fixed; z-index: 2000; inset: 0; background: rgba(28,20,16,0.55); backdrop-filter: blur(5px); align-items: center; justify-content: center; padding: 20px; }
        .detail-modal.show { display: flex; }
        .detail-content {
            background: var(--surface); width: 100%; max-width: 820px;
            border-radius: 20px; overflow: hidden;
            box-shadow: 0 24px 60px rgba(0,0,0,0.2);
            animation: slideUp 0.3s cubic-bezier(0.16,1,0.3,1) both;
            display: flex; max-height: 90vh;
        }
        .detail-img-wrap { width: 340px; flex-shrink: 0; position: relative; }
        .detail-img-wrap img { width: 100%; height: 100%; object-fit: cover; display: block; }
        .detail-img-overlay { position: absolute; bottom: 0; left: 0; right: 0; padding: 20px; background: linear-gradient(to top, rgba(28,20,16,0.75), transparent); }
        .detail-img-overlay .prod-id-lg { font-family: 'DM Mono', monospace; font-size: 11px; color: rgba(255,255,255,0.7); }
        .detail-img-overlay .prod-shop-lg { font-size: 13px; font-weight: 800; color: #fff; margin-top: 2px; }
        .detail-body { flex: 1; padding: 36px; overflow-y: auto; display: flex; flex-direction: column; }
        .detail-close { align-self: flex-end; background: var(--surface-2); border: 1px solid var(--border); color: var(--text-muted); width: 28px; height: 28px; border-radius: 8px; cursor: pointer; font-size: 16px; display: flex; align-items: center; justify-content: center; transition: 0.15s; margin-bottom: 20px; }
        .detail-close:hover { background: var(--border); color: var(--text); }
        .detail-title { font-size: 22px; font-weight: 800; color: var(--text); letter-spacing: -0.02em; }
        .detail-style { font-size: 12px; font-weight: 700; color: var(--primary); margin-top: 6px; text-transform: uppercase; letter-spacing: 0.08em; }
        .detail-desc { font-size: 13px; color: var(--text-soft); line-height: 1.7; margin-top: 16px; padding: 16px; background: var(--surface-2); border-radius: 10px; border-left: 3px solid var(--primary-muted); font-style: italic; }
        .spec-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 10px; margin-top: 20px; }
        .spec-box { background: var(--surface-2); border: 1px solid var(--border-soft); border-radius: 10px; padding: 14px 16px; }
        .spec-label { font-size: 9px; font-weight: 800; text-transform: uppercase; letter-spacing: 0.08em; color: var(--text-muted); }
        .spec-value { font-size: 13px; font-weight: 700; color: var(--text); margin-top: 4px; }
        .detail-actions { display: flex; gap: 10px; margin-top: 24px; }
        .btn-approve-lg {
            flex: 1; padding: 13px; border-radius: 10px; font-size: 12px; font-weight: 800;
            background: var(--success); color: #fff; border: none; cursor: pointer; transition: 0.15s;
            display: flex; align-items: center; justify-content: center; gap: 7px;
        }
        .btn-approve-lg:hover { background: #155e3b; }
        .btn-reject-lg {
            flex: 1; padding: 13px; border-radius: 10px; font-size: 12px; font-weight: 800;
            background: var(--danger-bg); color: var(--danger); border: 1.5px solid #F5C6C1; cursor: pointer; transition: 0.15s;
            display: flex; align-items: center; justify-content: center; gap: 7px;
        }
        .btn-reject-lg:hover { background: var(--danger); color: #fff; border-color: var(--danger); }
        .btn-approve-lg svg, .btn-reject-lg svg { width: 14px; height: 14px; }

        /* ─── CONFIRM MODAL ─── */
        .confirm-modal { display: none; position: fixed; z-index: 3000; inset: 0; background: rgba(28,20,16,0.6); backdrop-filter: blur(6px); align-items: center; justify-content: center; }
        .confirm-modal.show { display: flex; }
        .confirm-box {
            background: var(--surface); border-radius: 18px; width: 400px;
            box-shadow: 0 24px 60px rgba(0,0,0,0.22);
            animation: slideUp 0.25s cubic-bezier(0.16,1,0.3,1) both;
            overflow: hidden;
        }
        .confirm-header { padding: 20px 24px 0; }
        .confirm-icon { width: 44px; height: 44px; border-radius: 12px; display: flex; align-items: center; justify-content: center; margin-bottom: 14px; }
        .confirm-icon.approve { background: var(--success-bg); }
        .confirm-icon.reject  { background: var(--danger-bg); }
        .confirm-icon svg { width: 20px; height: 20px; }
        .confirm-icon.approve svg { stroke: var(--success); }
        .confirm-icon.reject  svg { stroke: var(--danger); }
        .confirm-title { font-size: 16px; font-weight: 800; color: var(--text); }
        .confirm-desc  { font-size: 12px; color: var(--text-muted); margin-top: 6px; line-height: 1.5; }
        .confirm-target { font-size: 12px; font-weight: 700; color: var(--text-soft); margin-top: 12px; padding: 10px 14px; background: var(--surface-2); border-radius: 9px; border: 1px solid var(--border-soft); }
        .confirm-footer { padding: 20px 24px 24px; display: flex; gap: 8px; margin-top: 20px; }
        .btn-confirm-cancel { flex: 1; padding: 11px; border-radius: 10px; font-size: 12px; font-weight: 700; background: var(--surface-2); color: var(--text-soft); border: 1.5px solid var(--border); cursor: pointer; font-family: inherit; transition: 0.15s; }
        .btn-confirm-cancel:hover { border-color: var(--primary-muted); color: var(--primary); }
        .btn-confirm-ok { flex: 1; padding: 11px; border-radius: 10px; font-size: 12px; font-weight: 800; border: none; cursor: pointer; font-family: inherit; transition: 0.15s; display: flex; align-items: center; justify-content: center; gap: 6px; }
        .btn-confirm-ok.approve { background: var(--success); color: #fff; }
        .btn-confirm-ok.approve:hover { background: #155e3b; }
        .btn-confirm-ok.reject  { background: var(--danger);  color: #fff; }
        .btn-confirm-ok.reject:hover  { background: #922b21; }
        .btn-confirm-ok svg { width: 13px; height: 13px; }

        @keyframes fadeUp  { from { opacity: 0; transform: translateY(14px); } to { opacity: 1; transform: none; } }
        @keyframes slideUp { from { transform: translateY(24px); opacity: 0; } to { transform: none; opacity: 1; } }
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
    <div class="page-header">
        <div>
            <div class="page-eyebrow">Aesthetic Curation</div>
            <div class="page-title">Portfolio Validation</div>
            <div class="page-desc">Kurasi dan publikasikan portofolio karya desainer sebelum tampil di platform.</div>
        </div>
    </div>

    <!-- Stats -->
    <div class="stats-row">
        <?php foreach ($stats as $s): ?>
        <div class="stat-mini">
            <div class="stat-icon"><i data-feather="<?= $s['icon'] ?>"></i></div>
            <div>
                <div class="stat-val clr-<?= $s['color'] ?>"><?= $s['value'] ?></div>
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
            <input class="search-input" type="text" placeholder="Cari portofolio, desainer, atau style…">
        </div>
        <div class="filter-tabs">
            <button class="ftab active">Semua</button>
            <button class="ftab">Pending</button>
            <button class="ftab">Review</button>
            <button class="ftab">Published</button>
        </div>
    </div>

    <!-- Portfolio List -->
    <div class="product-list">
        <?php foreach ($pending_portfolios as $p):
            $sl = strtolower($p['status']);
        ?>
        <div class="product-row st-<?= $sl ?>" id="row-<?= $p['id'] ?>">

            <!-- Thumbnail -->
            <div class="tcell" style="padding:0;">
                <img src="<?= $p['image'] ?>" class="prod-thumb"
                     onclick="openDetail(<?= htmlspecialchars(json_encode($p)) ?>)">
            </div>

            <!-- Info -->
            <div class="tcell">
                <div class="prod-id"><?= $p['id'] ?></div>
                <div class="prod-name"><?= $p['title'] ?></div>
                <div class="prod-shop"><?= $p['designer'] ?></div>
            </div>

            <!-- Style -->
            <div class="tcell">
                <span class="cat-badge"><?= $p['style'] ?></span>
            </div>

            <!-- Dim + Duration -->
            <div class="tcell">
                <div class="prod-dim"><?= $p['dimensions'] ?></div>
                <div class="prod-dur">Durasi: <?= $p['duration'] ?></div>
            </div>

            <!-- Submitted -->
            <div class="tcell">
                <div class="tkt-time"><i data-feather="clock"></i><?= $p['submitted'] ?></div>
            </div>

            <!-- Status -->
            <div class="tcell">
                <span class="status-pill st-<?= $sl ?>" id="pill-<?= $p['id'] ?>"><?= $p['status'] ?></span>
            </div>

            <!-- Actions -->
            <div class="tcell">
                <div class="action-cell">
                    <button class="btn-approve"
                        onclick="askConfirm('approve','<?= $p['id'] ?>','<?= addslashes($p['title']) ?>')">
                        <i data-feather="check"></i> Approve
                    </button>
                    <button class="btn-reject"
                        onclick="askConfirm('reject','<?= $p['id'] ?>','<?= addslashes($p['title']) ?>')">
                        <i data-feather="x"></i> Tolak
                    </button>
                </div>
            </div>

        </div>
        <?php endforeach; ?>
    </div>
</main>

<!-- ══════════ DETAIL MODAL ══════════ -->
<div id="detailModal" class="detail-modal">
    <div class="detail-content">
        <div class="detail-img-wrap">
            <img id="modalImg" src="" alt="">
            <div class="detail-img-overlay">
                <div class="prod-id-lg" id="modalId"></div>
                <div class="prod-shop-lg" id="modalDesigner"></div>
            </div>
        </div>
        <div class="detail-body">
            <button class="detail-close" onclick="closeDetail()">×</button>
            <div class="detail-title" id="modalTitle"></div>
            <div class="detail-style" id="modalStyle"></div>
            <div class="detail-desc"  id="modalDesc"></div>
            <div class="spec-grid">
                <div class="spec-box"><div class="spec-label">Area</div><div class="spec-value" id="modalDim"></div></div>
                <div class="spec-box"><div class="spec-label">Durasi</div><div class="spec-value" id="modalDuration"></div></div>
                <div class="spec-box"><div class="spec-label">Material</div><div class="spec-value" id="modalMaterial" style="font-size:11px;"></div></div>
                <div class="spec-box"><div class="spec-label">Designer</div><div class="spec-value" id="modalDesignerSpec"></div></div>
            </div>
            <div class="detail-actions">
                <button class="btn-approve-lg" id="modalApproveBtn">
                    <i data-feather="check-circle"></i> Approve & Publish
                </button>
                <button class="btn-reject-lg" id="modalRejectBtn">
                    <i data-feather="x-circle"></i> Tolak & Notifikasi Designer
                </button>
            </div>
        </div>
    </div>
</div>

<!-- ══════════ CONFIRM MODAL ══════════ -->
<div id="confirmModal" class="confirm-modal">
    <div class="confirm-box">
        <div class="confirm-header">
            <div class="confirm-icon" id="confirmIcon">
                <i data-feather="check-circle"></i>
            </div>
            <div class="confirm-title" id="confirmTitle"></div>
            <div class="confirm-desc"  id="confirmDesc"></div>
            <div class="confirm-target" id="confirmTarget"></div>
        </div>
        <div class="confirm-footer">
            <button class="btn-confirm-cancel" onclick="closeConfirm()">Batal</button>
            <button class="btn-confirm-ok" id="confirmOkBtn" onclick="executeAction()">
                <i data-feather="check"></i> <span id="confirmOkLabel">Ya, Lanjutkan</span>
            </button>
        </div>
    </div>
</div>

<script>
feather.replace({ 'stroke-width': 2 });

/* ── filter tabs ── */
document.querySelectorAll('.ftab').forEach(b => b.addEventListener('click', function() {
    document.querySelectorAll('.ftab').forEach(x => x.classList.remove('active'));
    this.classList.add('active');
}));

/* ── current detail product ── */
let currentDetail = null;

/* ── detail modal ── */
function openDetail(p) {
    currentDetail = p;
    document.getElementById('modalImg').src            = p.image;
    document.getElementById('modalId').textContent     = p.id;
    document.getElementById('modalDesigner').textContent = p.designer;
    document.getElementById('modalTitle').textContent  = p.title;
    document.getElementById('modalStyle').textContent  = p.style;
    document.getElementById('modalDesc').textContent   = '"' + p.description + '"';
    document.getElementById('modalDim').textContent    = p.dimensions;
    document.getElementById('modalDuration').textContent = p.duration;
    document.getElementById('modalMaterial').textContent = p.material;
    document.getElementById('modalDesignerSpec').textContent = p.designer;

    /* wire modal buttons */
    document.getElementById('modalApproveBtn').onclick = () => { closeDetail(); askConfirm('approve', p.id, p.title); };
    document.getElementById('modalRejectBtn').onclick  = () => { closeDetail(); askConfirm('reject',  p.id, p.title); };

    document.getElementById('detailModal').classList.add('show');
    feather.replace({ 'stroke-width': 2 });
}
function closeDetail() { document.getElementById('detailModal').classList.remove('show'); }
window.addEventListener('click', e => {
    if (e.target === document.getElementById('detailModal')) closeDetail();
    if (e.target === document.getElementById('confirmModal')) closeConfirm();
});

/* ── confirm modal ── */
let pendingAction = null;
let pendingId     = null;

function askConfirm(type, id, title) {
    pendingAction = type;
    pendingId     = id;

    const icon  = document.getElementById('confirmIcon');
    const okBtn = document.getElementById('confirmOkBtn');

    if (type === 'approve') {
        icon.className = 'confirm-icon approve';
        icon.innerHTML = '<i data-feather="check-circle"></i>';
        document.getElementById('confirmTitle').textContent = 'Approve Portofolio?';
        document.getElementById('confirmDesc').textContent  = 'Portofolio ini akan dipublikasikan dan dapat dilihat oleh semua pengguna platform.';
        okBtn.className = 'btn-confirm-ok approve';
        document.getElementById('confirmOkLabel').textContent = 'Ya, Approve';
    } else {
        icon.className = 'confirm-icon reject';
        icon.innerHTML = '<i data-feather="x-circle"></i>';
        document.getElementById('confirmTitle').textContent = 'Tolak Portofolio?';
        document.getElementById('confirmDesc').textContent  = 'Portofolio ini akan ditolak dan desainer akan menerima notifikasi beserta alasan penolakan.';
        okBtn.className = 'btn-confirm-ok reject';
        document.getElementById('confirmOkLabel').textContent = 'Ya, Tolak';
    }

    document.getElementById('confirmTarget').textContent = title;
    document.getElementById('confirmModal').classList.add('show');
    feather.replace({ 'stroke-width': 2 });
}

function closeConfirm() {
    document.getElementById('confirmModal').classList.remove('show');
    pendingAction = null;
    pendingId     = null;
}

function executeAction() {
    if (!pendingAction || !pendingId) return;

    const row  = document.getElementById('row-' + pendingId);
    const pill = document.getElementById('pill-' + pendingId);

    if (pendingAction === 'approve') {
        /* update accent bar & pill */
        row.classList.remove('st-pending', 'st-review');
        row.style.setProperty('--row-accent', 'var(--success)');
        row.querySelector('::before');
        row.style.cssText += '; --accent: var(--success)';
        row.classList.add('decided');

        pill.className = 'status-pill';
        pill.style.background = 'var(--success-bg)';
        pill.style.color = 'var(--success)';
        pill.textContent = 'Published';

        /* swap accent bar */
        row.style.borderLeft = '4px solid var(--success)';
    } else {
        row.classList.add('decided');
        pill.className = 'status-pill';
        pill.style.background = 'var(--danger-bg)';
        pill.style.color = 'var(--danger)';
        pill.textContent = 'Rejected';
        row.style.borderLeft = '4px solid var(--danger)';
    }

    closeConfirm();
}
</script>
</body>
</html>