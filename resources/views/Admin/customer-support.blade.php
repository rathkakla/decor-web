<?php
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
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customer Support — DECOR Admin</title>
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

        .ticket-row::before { content: ''; position: absolute; left: 0; top: 0; bottom: 0; width: 4px; }
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
        .cat-account   { background: var(--warning-bg);   color: var(--warning); }
        .cat-technical { background: var(--info-bg);      color: var(--info); }
        .cat-billing   { background: var(--primary-light); color: var(--primary); }

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
        .bubble.customer { background: var(--surface); border: 1px solid var(--border-soft); color: var(--text); border-bottom-left-radius: 4px; }
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
            $active = (request()->url() === $item['path']) ? 'active' : '';
        ?>
        <a href="<?= $item['path'] ?>" class="menu-link <?= $active ?>">
            <div class="menu-item" style="position: relative;">
                <i data-feather="<?= $item['icon'] ?>"></i>
                <span><?= $item['label'] ?></span>
                @if(isset($item['badge']) && $item['badge'] > 0)
                <span style="position: absolute; right: 10px; top: 50%; transform: translateY(-50%); background: var(--danger); color: white; border-radius: 99px; font-size: 8px; font-weight: 800; padding: 2px 6px; box-shadow: 0 2px 8px rgba(192,57,43,0.3);"><?= $item['badge'] ?></span>
                @endif
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
            <div class="page-title">Customer Support Center</div>
            <div class="page-desc">Tanggapi keluhan akses akun, verifikasi email, dan pertanyaan umum dari pelanggan.</div>
        </div>
    </div>

    <!-- Stats -->
    <div class="stats-row">
        <div class="stat-mini">
            <div class="stat-icon"><i data-feather="alert-circle"></i></div>
            <div>
                <div class="stat-val clr-danger"><?= str_pad($stats['open'], 2, '0', STR_PAD_LEFT) ?></div>
                <div class="stat-lbl">Open</div>
                <div class="stat-note">butuh respon</div>
            </div>
        </div>
        <div class="stat-mini">
            <div class="stat-icon"><i data-feather="message-square"></i></div>
            <div>
                <div class="stat-val clr-info"><?= str_pad($stats['replied'], 2, '0', STR_PAD_LEFT) ?></div>
                <div class="stat-lbl">Replied</div>
                <div class="stat-note">sudah dibalas</div>
            </div>
        </div>
        <div class="stat-mini">
            <div class="stat-icon"><i data-feather="check-circle"></i></div>
            <div>
                <div class="stat-val clr-success"><?= str_pad($stats['resolved'], 2, '0', STR_PAD_LEFT) ?></div>
                <div class="stat-lbl">Resolved</div>
                <div class="stat-note">selesai</div>
            </div>
        </div>
    </div>

    <!-- Toolbar -->
    <div class="toolbar">
        <div class="search-wrap">
            <i data-feather="search"></i>
            <input class="search-input" type="text" placeholder="Cari tiket, customer, atau subjek…">
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
        @forelse ($tickets as $t)
        @php
            $sl = $t->status;
            $cat_cls = 'cat-account'; // Default
        @endphp
        <div class="ticket-row st-{{ $sl }}">

            <!-- ID + Time -->
            <div class="tcell">
                <span class="tkt-id">#{{ str_pad($t->id, 4, '0', STR_PAD_LEFT) }}</span>
                <div class="tkt-time"><i data-feather="clock"></i>{{ $t->created_at->diffForHumans() }}</div>
            </div>

            <!-- Sender -->
            <div class="tcell">
                <div class="sender-name">{{ $t->user->full_name }}</div>
                <span class="sender-cat {{ $cat_cls }}">Support</span>
            </div>

            <!-- Subject -->
            <div class="tcell">
                <div class="tkt-subject">{{ $t->subject }}</div>
            </div>

            <!-- Status -->
            <div class="tcell">
                <select class="status-select status-{{ $sl }}" onchange="updateTicketStatus(this, {{ $t->id }})">
                    <option value="pending" {{ $sl === 'pending' ? 'selected' : '' }}>Open</option>
                    <option value="replied" {{ $sl === 'replied' ? 'selected' : '' }}>Replied</option>
                    <option value="resolved" {{ $sl === 'resolved' ? 'selected' : '' }}>Resolved</option>
                </select>
            </div>

            <!-- Action -->
            <div class="tcell">
                <button class="btn-reply"
                        onclick="openChat('{{ $t->id }}', '{{ $t->user->full_name }}', '{{ $t->subject }}', '{{ addslashes($t->message) }}', '{{ addslashes($t->admin_reply) }}')">
                    <i data-feather="message-circle"></i> View & Reply
                </button>
            </div>

        </div>
        @empty
        <div class="panel" style="padding: 40px; text-align: center; color: var(--text-muted);">
            <i data-feather="inbox" style="width: 48px; height: 48px; margin-bottom: 16px; opacity: 0.5;"></i>
            <p>Belum ada tiket bantuan dari pelanggan.</p>
        </div>
        @endforelse
    </div>

</main>

<!-- ══════════ CHAT MODAL ══════════ -->
<div id="chatModal" class="chat-modal">
    <div class="chat-content">
        <div class="chat-header">
            <div class="chat-header-left">
                <div class="chat-header-icon"><i data-feather="message-circle"></i></div>
                <div>
                    <div class="chat-title" id="chatTitle">—</div>
                    <div class="chat-sub" id="chatSub">—</div>
                </div>
            </div>
            <button class="chat-close" onclick="closeChat()">×</button>
        </div>

        <div class="chat-body" id="chatWindow">
            <!-- Messages will be injected here -->
        </div>

        <form id="replyForm" action="" method="POST" class="chat-footer">
            @csrf
            <input type="text" name="reply" id="chatInput" class="chat-input" placeholder="Tulis balasan…" required>
            <button type="submit" class="btn-send">
                <i data-feather="send"></i> Kirim
            </button>
        </form>
    </div>
</div>

<script>
    feather.replace({ 'stroke-width': 2 });

    function updateTicketStatus(sel, id) {
        sel.className = 'status-select status-' + sel.value;
        const row = sel.closest('.ticket-row');
        row.className = 'ticket-row st-' + sel.value;

        fetch(`{{ url('admin/support') }}/${id}/status`, {
            method: 'PATCH',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ status: sel.value })
        });
    }

    function openChat(id, name, subject, message, reply) {
        document.getElementById('chatTitle').textContent = '#' + id.toString().padStart(4, '0') + ' · ' + name;
        document.getElementById('chatSub').textContent = subject;
        
        const win = document.getElementById('chatWindow');
        win.innerHTML = `
            <div class="bubble-wrap">
                <div class="bubble-meta">${name} · Customer</div>
                <div class="bubble customer">${message}</div>
            </div>
        `;

        if(reply && reply !== '') {
            win.innerHTML += `
                <div class="bubble-wrap admin-wrap">
                    <div class="bubble-meta">Admin · Reply</div>
                    <div class="bubble admin">${reply}</div>
                </div>
            `;
        }

        document.getElementById('replyForm').action = `{{ url('admin/support') }}/${id}/reply`;
        document.getElementById('chatModal').classList.add('show');
        win.scrollTop = win.scrollHeight;
    }

    function closeChat() {
        document.getElementById('chatModal').classList.remove('show');
    }

    window.addEventListener('click', e => {
        if (e.target === document.getElementById('chatModal')) closeChat();
    });
</script>
</body>
</html>