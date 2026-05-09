<?php
$current_page = basename($_SERVER['PHP_SELF']);
$current_path = str_replace('.php', '', $current_page);
if ($current_path == "" || $current_path == "index") {
    $current_path = "user-management";
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
$stats = [
    ["label" => "Total Users",      "value" => "12,482", "trend" => "+5.2%", "icon" => "users",      "color" => "primary"],
    ["label" => "Active Designers", "value" => "842",    "trend" => "+12%",  "icon" => "pen-tool",   "color" => "info"],
    ["label" => "Platform Sellers", "value" => "3,120",  "trend" => "+2.4%", "icon" => "shopping-bag","color" => "warning"],
    ["label" => "Customers",        "value" => "5,678",  "trend" => "+3.1%", "icon" => "user-check", "color" => "success"],
];

$users = [
    ["name" => "Julian Voss",     "email" => "voss.creative@studio.com",    "role" => "DESIGNER",  "date" => "Oct 12, 2023", "status" => "Active",    "img" => "https://i.pravatar.cc/150?u=julian"],
    ["name" => "Elena Rodriguez", "email" => "elena.rodriguez@gmail.com",   "role" => "CUSTOMER",  "date" => "Jan 05, 2024", "status" => "Active",    "img" => "https://i.pravatar.cc/150?u=elena"],
    ["name" => "Marcus Chen",     "email" => "m.chen@decor-partners.com",   "role" => "SELLER",    "date" => "Nov 28, 2023", "status" => "Suspended", "img" => "https://i.pravatar.cc/150?u=marcus"],
    ["name" => "Sophia Laurent",  "email" => "sophia.l@interior-vibe.fr",  "role" => "DESIGNER",  "date" => "Feb 02, 2024", "status" => "Active",    "img" => "https://i.pravatar.cc/150?u=sophia"],
    ["name" => "Reza Mahendra",   "email" => "reza.m@woodcraft.id",         "role" => "SELLER",    "date" => "Mar 15, 2024", "status" => "Active",    "img" => "https://i.pravatar.cc/150?u=reza"],
    ["name" => "Anisa Rahma",     "email" => "anisa.r@mail.com",            "role" => "CUSTOMER",  "date" => "Apr 01, 2024", "status" => "Active",    "img" => "https://i.pravatar.cc/150?u=anisa"],
];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Management — DECOR Admin</title>
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
        .header-actions { display: flex; align-items: center; gap: 10px; }
        .btn-primary { display: flex; align-items: center; gap: 6px; padding: 9px 18px; border-radius: 10px; font-size: 11px; font-weight: 700; background: var(--primary); color: #fff; border: none; cursor: pointer; transition: 0.15s; font-family: inherit; }
        .btn-primary:hover { background: var(--primary-hover); }
        .btn-primary svg { width: 13px; height: 13px; }

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
        .stat-trend { font-size: 10px; font-weight: 800; color: var(--success); margin-top: 2px; }

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

        /* ─── USER TABLE ─── */
        .table-wrap { background: var(--surface); border: 1px solid var(--border); border-radius: var(--radius); box-shadow: var(--shadow); overflow: hidden; animation: fadeUp 0.4s ease 0.28s both; }

        table { width: 100%; border-collapse: collapse; }
        thead tr { background: var(--surface-2); border-bottom: 1.5px solid var(--border-soft); }
        th { padding: 11px 20px; font-size: 9px; font-weight: 800; text-transform: uppercase; letter-spacing: 0.1em; color: var(--text-muted); text-align: left; white-space: nowrap; }

        tbody tr { border-bottom: 1px solid var(--border-soft); transition: background 0.12s; }
        tbody tr:last-child { border-bottom: none; }
        tbody tr:hover { background: var(--surface-2); }
        td { padding: 14px 20px; vertical-align: middle; }

        /* user cell */
        .user-cell { display: flex; align-items: center; gap: 12px; }
        .user-avatar { width: 38px; height: 38px; border-radius: 10px; object-fit: cover; border: 2px solid var(--border-soft); flex-shrink: 0; }
        .u-name  { font-size: 13px; font-weight: 700; color: var(--text); display: block; }
        .u-email { font-size: 10px; color: var(--text-muted); font-weight: 500; margin-top: 1px; display: block; font-family: 'DM Mono', monospace; }

        /* role badge */
        .role-badge { display: inline-flex; padding: 4px 10px; border-radius: 6px; font-size: 9px; font-weight: 800; text-transform: uppercase; letter-spacing: 0.05em; }
        .role-designer { background: var(--info-bg);     color: var(--info); }
        .role-seller   { background: var(--warning-bg);  color: var(--warning); }
        .role-customer { background: var(--surface-2);   color: var(--text-soft); border: 1px solid var(--border); }

        /* joined date */
        .join-date { font-size: 11px; font-weight: 600; color: var(--text-soft); }

        /* status */
        .status-pill { display: inline-flex; align-items: center; gap: 6px; font-size: 10px; font-weight: 800; }
        .status-dot { width: 6px; height: 6px; border-radius: 50%; flex-shrink: 0; }
        .st-active    { color: var(--success); }
        .st-active    .status-dot { background: var(--success); }
        .st-suspended { color: var(--text-muted); }
        .st-suspended .status-dot { background: var(--text-muted); }

        /* action buttons */
        .actions { display: flex; align-items: center; gap: 6px; justify-content: flex-end; }
        .btn-icon { width: 30px; height: 30px; border-radius: 8px; border: 1.5px solid var(--border); background: var(--surface); cursor: pointer; color: var(--text-muted); transition: 0.15s; display: inline-flex; align-items: center; justify-content: center; }
        .btn-icon svg { width: 12px; height: 12px; }
        .btn-icon:hover { border-color: var(--primary-muted); color: var(--primary); background: var(--primary-light); }
        .btn-icon.danger:hover { border-color: #F5C6C1; color: var(--danger); background: var(--danger-bg); }

        @keyframes fadeUp { from { opacity: 0; transform: translateY(14px); } to { opacity: 1; transform: none; } }
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
    <div class="page-header">
        <div>
            <div class="page-eyebrow">Database Management</div>
            <div class="page-title">User Management</div>
            <div class="page-desc">Kelola akun pengguna, role, dan status di seluruh platform.</div>
        </div>
        
    </div>

    <!-- Stats -->
    <div class="stats-row">
        <?php foreach ($stats as $s): ?>
        <div class="stat-mini">
            <div class="stat-icon"><i data-feather="<?= $s['icon'] ?>"></i></div>
            <div>
                <div class="stat-val"><?= $s['value'] ?></div>
                <div class="stat-lbl"><?= $s['label'] ?></div>
                <div class="stat-trend"><?= $s['trend'] ?> bulan ini</div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>

    <!-- Toolbar -->
    <div class="toolbar">
        <div class="search-wrap">
            <i data-feather="search"></i>
            <input class="search-input" type="text" placeholder="Cari nama, email, atau role…">
        </div>
        <div class="filter-tabs">
            <button class="ftab active">Semua</button>
            <button class="ftab">Designer</button>
            <button class="ftab">Seller</button>
            <button class="ftab">Customer</button>
        </div>
    </div>

    <!-- User Table -->
    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>User</th>
                    <th>Role</th>
                    <th>Bergabung</th>
                    <th>Status</th>
                    <th style="text-align:right;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($users as $u):
                    $role_cls   = 'role-' . strtolower($u['role']);
                    $status_cls = 'st-' . strtolower($u['status']);
                ?>
                <tr>
                    <td>
                        <div class="user-cell">
                            <img src="<?= $u['img'] ?>" class="user-avatar">
                            <div>
                                <span class="u-name"><?= $u['name'] ?></span>
                                <span class="u-email"><?= $u['email'] ?></span>
                            </div>
                        </div>
                    </td>
                    <td>
                        <span class="role-badge <?= $role_cls ?>"><?= $u['role'] ?></span>
                    </td>
                    <td>
                        <span class="join-date"><?= $u['date'] ?></span>
                    </td>
                    <td>
                        <span class="status-pill <?= $status_cls ?>">
                            <span class="status-dot"></span>
                            <?= $u['status'] ?>
                        </span>
                    </td>
                    <td>
                        <div class="actions">
                          
                            
                            <button class="btn-icon danger" title="Suspend"><i data-feather="slash"></i></button>
                        </div>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

</main>

<script>
    feather.replace({ 'stroke-width': 2 });

    document.querySelectorAll('.ftab').forEach(btn => {
        btn.addEventListener('click', function() {
            document.querySelectorAll('.ftab').forEach(b => b.classList.remove('active'));
            this.classList.add('active');
        });
    });
</script>
</body>
</html>