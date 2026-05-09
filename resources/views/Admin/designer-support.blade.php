<?php
$current_page = basename($_SERVER['PHP_SELF']);
$current_path = str_replace('.php', '', $current_page);
if ($current_path == "" || $current_path == "index") {
    $current_path = "designer-support";
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

$tickets = [
    [
        "id"       => "TKT-D01",
        "sender"   => "Julian Thorne",
        "subject"  => "Gagal Upload Render 3D",
        "category" => "Technical",
        "status"   => "Open",
        
        "date"     => "15 Mins Ago",
    ],
    [
        "id"       => "TKT-D02",
        "sender"   => "Elena Rodriguez",
        "subject"  => "Pencairan Komisi Tertunda",
        "category" => "Finance",
        "status"   => "Diproses",
       
        "date"     => "2 Hours Ago",
    ],
    [
        "id"       => "TKT-D03",
        "sender"   => "Marcus Webb",
        "subject"  => "Fitur AI Lab Tidak Akurat",
        "category" => "Feedback",
        "status"   => "Selesai",
       
        "date"     => "Yesterday",
    ],
];

$stats = [
    ["label" => "Open",          "value" => "09", "icon" => "alert-circle", "note" => "butuh respon",    "color" => "danger"],
    ["label" => "Diproses",      "value" => "05", "icon" => "refresh-cw",   "note" => "sedang berjalan", "color" => "info"],
    ["label" => "Selesai",       "value" => "32", "icon" => "check-circle", "note" => "minggu ini",      "color" => "success"],
    ["label" => "Avg. Response", "value" => "22m","icon" => "clock",        "note" => "response time",   "color" => "primary"],
];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Designer Support — DECOR Admin</title>
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
        .stat-val.clr-danger  { color: var(--danger); }
        .stat-val.clr-info    { color: var(--info); }
        .stat-val.clr-success { color: var(--success); }
        .stat-val.clr-primary { color: var(--primary); }

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

        /* ─── TICKET LIST ─── */
        .ticket-list { display: flex; flex-direction: column; gap: 10px; animation: fadeUp 0.4s ease 0.28s both; }

        .ticket-row {
            background: var(--surface); border: 1px solid var(--border);
            border-radius: var(--radius); box-shadow: var(--shadow);
            display: grid; grid-template-columns: 130px 1fr 120px 110px 110px 130px;
            align-items: center; gap: 0;
            transition: transform 0.15s, box-shadow 0.15s;
            overflow: hidden; position: relative;
        }
        .ticket-row:hover { transform: translateY(-2px); box-shadow: var(--shadow-card); }

        .ticket-row::before {
            content: ''; position: absolute; left: 0; top: 0; bottom: 0; width: 4px;
        }
        .ticket-row.st-open::before     { background: var(--danger); }
        .ticket-row.st-diproses::before { background: var(--info); }
        .ticket-row.st-selesai::before  { background: var(--success); }

        .tcell { padding: 16px 18px; border-right: 1px solid var(--border-soft); }
        .tcell:last-child { border-right: none; }
        .tcell:first-child { padding-left: 22px; }

        .tkt-id { font-family: 'DM Mono', monospace; font-size: 11px; font-weight: 500; color: var(--primary); display: block; }
        .tkt-time { font-size: 9px; color: var(--text-muted); font-weight: 500; margin-top: 4px; display: flex; align-items: center; gap: 3px; }
        .tkt-time svg { width: 9px; height: 9px; }

        .sender-name { font-size: 12.5px; font-weight: 700; color: var(--text); }
        .sender-cat { display: inline-flex; align-items: center; gap: 3px; margin-top: 3px; font-size: 9px; font-weight: 800; text-transform: uppercase; letter-spacing: 0.05em; padding: 2px 7px; border-radius: 4px; }
        .cat-technical { background: var(--info-bg);     color: var(--info); }
        .cat-finance   { background: var(--primary-light); color: var(--primary); }
        .cat-feedback  { background: var(--warning-bg);  color: var(--warning); }

        .tkt-subject { font-size: 12.5px; font-weight: 500; color: var(--text-soft); }

        .priority { display: inline-flex; align-items: center; gap: 4px; padding: 4px 10px; border-radius: 6px; font-size: 9px; font-weight: 800; text-transform: uppercase; }
        .priority-high   { background: var(--danger-bg);  color: var(--danger); }
        .priority-medium { background: var(--warning-bg); color: var(--warning); }
        .priority-low    { background: var(--success-bg); color: var(--success); }

        .status-select {
            padding: 6px 10px; border-radius: 8px; font-size: 10px; font-weight: 800;
            border: 1.5px solid; outline: none; cursor: pointer;
            text-transform: uppercase; transition: 0.2s; font-family: inherit; width: 100%;
        }
        .status-open     { background: var(--danger-bg);  color: var(--danger);  border-color: #F5C6C1; }
        .status-diproses { background: var(--info-bg);    color: var(--info);    border-color: #BBDEFB; }
        .status-selesai  { background: var(--success-bg); color: var(--success); border-color: #B8DEC8; }

        .btn-reply {
            display: inline-flex; align-items: center; gap: 6px;
            padding: 8px 14px; border-radius: 9px; font-size: 11px; font-weight: 700;
            background: var(--primary); color: #fff; border: none; cursor: pointer;
            transition: 0.15s; white-space: nowrap;
        }
        .btn-reply:hover { background: var(--primary-hover); }
        .btn-reply svg { width: 12px; height: 12px; }

        /* ─── CHAT MODAL ─── */
        .chat-modal { display: none; position: fixed; z-index: 2000; inset: 0; background: rgba(28,20,16,0.55); backdrop-filter: blur(5px); align-items: center; justify-content: center; }
        .chat-modal.show { display: flex; }

        .chat-content {
            background: var(--surface); width: 480px; border-radius: 20px;
            overflow: hidden; box-shadow: 0 24px 60px rgba(0,0,0,0.2);
            animation: slideUp 0.3s cubic-bezier(0.16,1,0.3,1) both;
            display: flex; flex-direction: column; max-height: 90vh;
        }
        @keyframes slideUp { from { transform: translateY(24px); opacity: 0; } to { transform: none; opacity: 1; } }

        .chat-header {
            padding: 18px 22px; background: var(--primary);
            display: flex; align-items: center; justify-content: space-between; flex-shrink: 0;
        }
        .chat-header-left { display: flex; align-items: center; gap: 10px; }
        .chat-header-icon { width: 34px; height: 34px; background: rgba(255,255,255,0.2); border-radius: 9px; display: flex; align-items: center; justify-content: center; }
        .chat-header-icon svg { width: 15px; height: 15px; stroke: #fff; }
        .chat-title { font-size: 13px; font-weight: 800; color: #fff; }
        .chat-sub { font-size: 10px; color: rgba(255,255,255,0.65); margin-top: 1px; }
        .chat-close { background: rgba(255,255,255,0.15); border: none; color: #fff; width: 28px; height: 28px; border-radius: 8px; cursor: pointer; font-size: 16px; display: flex; align-items: center; justify-content: center; transition: 0.15s; }
        .chat-close:hover { background: rgba(255,255,255,0.3); }

        .chat-body { flex: 1; padding: 20px; overflow-y: auto; background: var(--surface-2); display: flex; flex-direction: column; gap: 12px; min-height: 300px; max-height: 380px; }

        .bubble-wrap { display: flex; flex-direction: column; }
        .bubble-wrap.admin-wrap { align-items: flex-end; }
        .bubble-meta { font-size: 9px; color: var(--text-muted); font-weight: 600; margin-bottom: 4px; padding: 0 4px; }

        .bubble { max-width: 82%; padding: 11px 15px; border-radius: 14px; font-size: 12.5px; line-height: 1.55; }
        .bubble.designer { background: var(--surface); border: 1px solid var(--border-soft); color: var(--text); border-bottom-left-radius: 4px; }
        .bubble.admin    { background: var(--primary); color: #fff; border-bottom-right-radius: 4px; }

        .chat-footer { padding: 16px 18px; border-top: 1px solid var(--border-soft); background: var(--surface); display: flex; gap: 8px; flex-shrink: 0; }
        .chat-input { flex: 1; padding: 10px 16px; border: 1.5px solid var(--border); border-radius: 10px; font-family: inherit; font-size: 12.5px; color: var(--text); outline: none; transition: 0.15s; background: var(--surface-2); }
        .chat-input:focus { border-color: var(--primary-muted); background: var(--primary-light); }
        .btn-send { padding: 10px 16px; background: var(--primary); color: #fff; border: none; border-radius: 10px; font-size: 11px; font-weight: 700; cursor: pointer; display: flex; align-items: center; gap: 5px; transition: 0.15s; font-family: inherit; }
        .btn-send:hover { background: var(--primary-hover); }
        .btn-send svg { width: 13px; height: 13px; }

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
            <div class="page-eyebrow">Communication Hub</div>
            <div class="page-title">Designer Support Center</div>
            <div class="page-desc">Tanggapi kendala teknis, pencairan komisi, dan feedback fitur dari para designer.</div>
        </div>
    </div>

    <!-- Stats -->
    <div class="stats-row">
        <?php
        $clr_map = ['danger' => 'clr-danger', 'info' => 'clr-info', 'success' => 'clr-success', 'primary' => 'clr-primary'];
        foreach ($stats as $s): ?>
        <div class="stat-mini">
            <div class="stat-icon"><i data-feather="<?= $s['icon'] ?>"></i></div>
            <div>
                <div class="stat-val <?= $clr_map[$s['color']] ?>"><?= $s['value'] ?></div>
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
            <input class="search-input" type="text" placeholder="Cari tiket, designer, atau subjek…">
        </div>
        <div class="filter-tabs">
            <button class="ftab active">Semua</button>
            <button class="ftab">Open</button>
            <button class="ftab">Diproses</button>
            <button class="ftab">Selesai</button>
        </div>
    </div>

    <!-- Ticket List -->
    <div class="ticket-list">
        <?php foreach ($tickets as $t):
            $sl     = strtolower(str_replace(' ', '', $t['status']));
            
            $cat_cls = 'cat-' . strtolower($t['category']);
        ?>
        <div class="ticket-row st-<?= $sl ?>">

            <!-- ID + Time -->
            <div class="tcell">
                <span class="tkt-id"><?= $t['id'] ?></span>
                <div class="tkt-time"><i data-feather="clock"></i><?= $t['date'] ?></div>
            </div>

            <!-- Sender -->
            <div class="tcell">
                <div class="sender-name"><?= $t['sender'] ?></div>
                <span class="sender-cat <?= $cat_cls ?>"><?= $t['category'] ?></span>
            </div>

            <!-- Subject -->
            <div class="tcell">
                <div class="tkt-subject"><?= $t['subject'] ?></div>
            </div>

            <!-- Priority -->
            
            <!-- Status -->
            <div class="tcell">
                <select class="status-select status-<?= $sl ?>" onchange="updateStatus(this)">
                    <option value="open"     <?= $sl === 'open'     ? 'selected' : '' ?>>Open</option>
                    <option value="diproses" <?= $sl === 'diproses' ? 'selected' : '' ?>>Diproses</option>
                    <option value="selesai"  <?= $sl === 'selesai'  ? 'selected' : '' ?>>Selesai</option>
                </select>
            </div>

            <!-- Action -->
            <div class="tcell">
                <button class="btn-reply"
                        onclick="openChat('<?= $t['id'] ?>', '<?= $t['sender'] ?>', '<?= $t['subject'] ?>')">
                    <i data-feather="message-circle"></i> Balas
                </button>
            </div>

        </div>
        <?php endforeach; ?>
    </div>

</main>

<!-- ══════════ CHAT MODAL ══════════ -->
<div id="chatModal" class="chat-modal">
    <div class="chat-content">
        <div class="chat-header">
            <div class="chat-header-left">
                <div class="chat-header-icon"><i data-feather="pen-tool"></i></div>
                <div>
                    <div class="chat-title" id="chatTitle">—</div>
                    <div class="chat-sub" id="chatSub">—</div>
                </div>
            </div>
            <button class="chat-close" onclick="closeChat()">×</button>
        </div>

        <div class="chat-body" id="chatWindow">
            <div class="bubble-wrap">
                <div class="bubble-meta">Designer · Just now</div>
                <div class="bubble designer">Halo Admin, ada kendala pada akun saya. Mohon bantuannya segera.</div>
            </div>
            <div class="bubble-wrap admin-wrap">
                <div class="bubble-meta">Alex Rivera · Just now</div>
                <div class="bubble admin">Halo, tim support sudah menerima laporan Anda. Kami akan segera membantu pengecekan.</div>
            </div>
        </div>

        <div class="chat-footer">
            <input type="text" id="chatInput" class="chat-input" placeholder="Tulis balasan…"
                   onkeydown="if(event.key==='Enter') sendMessage()">
            <button class="btn-send" onclick="sendMessage()">
                <i data-feather="send"></i> Kirim
            </button>
        </div>
    </div>
</div>

<script>
    feather.replace({ 'stroke-width': 2 });

    document.querySelectorAll('.ftab').forEach(btn => {
        btn.addEventListener('click', function() {
            document.querySelectorAll('.ftab').forEach(b => b.classList.remove('active'));
            this.classList.add('active');
        });
    });

    function updateStatus(sel) {
        sel.className = 'status-select status-' + sel.value;
        const row = sel.closest('.ticket-row');
        row.className = 'ticket-row st-' + sel.value;
    }

    function openChat(id, name, subject) {
        document.getElementById('chatTitle').textContent = id + ' · ' + name;
        document.getElementById('chatSub').textContent = subject;
        document.getElementById('chatModal').classList.add('show');
    }
    function closeChat() {
        document.getElementById('chatModal').classList.remove('show');
    }
    function sendMessage() {
        const input = document.getElementById('chatInput');
        const win   = document.getElementById('chatWindow');
        if (!input.value.trim()) return;
        const wrap = document.createElement('div');
        wrap.className = 'bubble-wrap admin-wrap';
        wrap.innerHTML = `<div class="bubble-meta">Alex Rivera · Now</div><div class="bubble admin">${input.value}</div>`;
        win.appendChild(wrap);
        input.value = '';
        win.scrollTop = win.scrollHeight;
        feather.replace({ 'stroke-width': 2 });
    }
    window.addEventListener('click', e => {
        if (e.target === document.getElementById('chatModal')) closeChat();
    });
</script>
</body>
</html>