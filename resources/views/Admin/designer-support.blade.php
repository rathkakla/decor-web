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
        .stats-row { display: grid; grid-template-columns: repeat(3,1fr); gap: 14px; margin-bottom: 28px; }
        .stat-mini { background: var(--surface); border: 1px solid var(--border); border-radius: var(--radius); padding: 18px 20px; box-shadow: var(--shadow); display: flex; align-items: center; gap: 14px; position: relative; overflow: hidden; animation: fadeUp 0.4s ease both; }
        .stat-mini::after { content:''; position:absolute; bottom:0; left:0; right:0; height:3px; background: var(--primary); }
        .stat-icon { width: 40px; height: 40px; border-radius: 10px; background: var(--primary-light); display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
        .stat-icon svg { width: 16px; height: 16px; stroke: var(--primary); }
        .stat-val { font-size: 22px; font-weight: 800; color: var(--text); line-height: 1; }
        .stat-lbl { font-size: 10px; font-weight: 700; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.05em; margin-top: 3px; }
        .stat-note { font-size: 9px; color: var(--text-muted); margin-top: 1px; }
        .stat-val.clr-danger { color: var(--danger); }
        .stat-val.clr-info { color: var(--info); }
        .stat-val.clr-success { color: var(--success); }

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
        .ticket-row::before { content: ''; position: absolute; left: 0; top: 0; bottom: 0; width: 4px; }
        .ticket-row.st-pending::before  { background: var(--danger); }
        .ticket-row.st-replied::before  { background: var(--info); }
        .ticket-row.st-resolved::before { background: var(--success); }
        .tcell { padding: 16px 18px; border-right: 1px solid var(--border-soft); }
        .tkt-id { font-family: 'DM Mono', monospace; font-size: 11px; font-weight: 500; color: var(--primary); display: block; }
        .tkt-time { font-size: 9px; color: var(--text-muted); font-weight: 500; margin-top: 4px; display: flex; align-items: center; gap: 3px; }
        .sender-name { font-size: 12.5px; font-weight: 700; color: var(--text); }
        .tkt-subject { font-size: 12.5px; font-weight: 500; color: var(--text-soft); }
        .status-select {
            padding: 6px 10px; border-radius: 8px; font-size: 10px; font-weight: 800;
            border: 1.5px solid; outline: none; cursor: pointer;
            text-transform: uppercase; transition: 0.2s; font-family: inherit; width: 100%;
        }
        .status-pending  { background: var(--danger-bg);  color: var(--danger);  border-color: #F5C6C1; }
        .status-replied  { background: var(--info-bg);    color: var(--info);    border-color: #BBDEFB; }
        .status-resolved { background: var(--success-bg); color: var(--success); border-color: #B8DEC8; }
        .btn-reply {
            display: inline-flex; align-items: center; gap: 6px;
            padding: 8px 14px; border-radius: 9px; font-size: 11px; font-weight: 700;
            background: var(--primary); color: #fff; border: none; cursor: pointer;
            transition: 0.15s; white-space: nowrap;
        }

        /* ─── CHAT MODAL ─── */
        .chat-modal { display: none; position: fixed; z-index: 2000; inset: 0; background: rgba(28,20,16,0.55); backdrop-filter: blur(5px); align-items: center; justify-content: center; }
        .chat-modal.show { display: flex; }
        .chat-content { background: var(--surface); width: 480px; border-radius: 20px; overflow: hidden; display: flex; flex-direction: column; max-height: 90vh; }
        .chat-header { padding: 18px 22px; background: var(--primary); display: flex; align-items: center; justify-content: space-between; }
        .chat-title { font-size: 13px; font-weight: 800; color: #fff; }
        .chat-body { flex: 1; padding: 20px; overflow-y: auto; background: var(--surface-2); display: flex; flex-direction: column; gap: 12px; min-height: 300px; }
        .bubble-wrap { display: flex; flex-direction: column; }
        .bubble-wrap.admin-wrap { align-items: flex-end; }
        .bubble { max-width: 82%; padding: 11px 15px; border-radius: 14px; font-size: 12.5px; }
        .bubble.user { background: var(--surface); border: 1px solid var(--border-soft); border-bottom-left-radius: 4px; }
        .bubble.admin { background: var(--primary); color: #fff; border-bottom-right-radius: 4px; }
        .chat-footer { padding: 16px 18px; border-top: 1px solid var(--border-soft); background: var(--surface); display: flex; gap: 8px; }
        .chat-input { flex: 1; padding: 10px 16px; border: 1.5px solid var(--border); border-radius: 10px; outline: none; }
        .btn-send { padding: 10px 16px; background: var(--primary); color: #fff; border-radius: 10px; font-weight: 700; border: none; cursor: pointer; }

        @keyframes fadeUp { from { opacity: 0; transform: translateY(14px); } to { opacity: 1; transform: none; } }
    </style>
</head>
<body>

<aside>
    <div class="brand">
        <div class="brand-row"><div class="brand-icon"><i data-feather="layout"></i></div><div class="brand-name">DECOR</div></div>
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
                <span style="position: absolute; right: 10px; top: 50%; transform: translateY(-50%); background: var(--danger); color: white; border-radius: 99px; font-size: 8px; font-weight: 800; padding: 2px 6px;">{{ $item['badge'] }}</span>
                @endif
            </div>
        </a>
        @endforeach
    </div>
</aside>

<main>
    <div class="page-header">
        <div>
            <div class="page-eyebrow">Communication Hub</div>
            <div class="page-title">Designer Support Center</div>
            <div class="page-desc">Tanggapi keluhan dan bantuan teknis dari para desainer interior.</div>
        </div>
    </div>

    <div class="stats-row">
        <div class="stat-mini">
            <div class="stat-icon"><i data-feather="alert-circle"></i></div>
            <div>
                <div class="stat-val clr-danger">{{ str_pad($stats['open'], 2, '0', STR_PAD_LEFT) }}</div>
                <div class="stat-lbl">Open</div>
            </div>
        </div>
        <div class="stat-mini">
            <div class="stat-icon"><i data-feather="message-square"></i></div>
            <div>
                <div class="stat-val clr-info">{{ str_pad($stats['replied'], 2, '0', STR_PAD_LEFT) }}</div>
                <div class="stat-lbl">Replied</div>
            </div>
        </div>
        <div class="stat-mini">
            <div class="stat-icon"><i data-feather="check-circle"></i></div>
            <div>
                <div class="stat-val clr-success">{{ str_pad($stats['resolved'], 2, '0', STR_PAD_LEFT) }}</div>
                <div class="stat-lbl">Resolved</div>
            </div>
        </div>
    </div>

    <div class="ticket-list">
        @forelse ($tickets as $t)
        <div class="ticket-row st-{{ $t->status }}">
            <div class="tcell"><span class="tkt-id">#{{ str_pad($t->id, 4, '0', STR_PAD_LEFT) }}</span><div class="tkt-time">{{ $t->created_at->diffForHumans() }}</div></div>
            <div class="tcell"><div class="sender-name">{{ $t->user->full_name }}</div><span class="nav-label">Designer</span></div>
            <div class="tcell"><div class="tkt-subject">{{ $t->subject }}</div></div>
            <div class="tcell">
                <select class="status-select status-{{ $t->status }}" onchange="updateTicketStatus(this, {{ $t->id }})">
                    <option value="pending" {{ $t->status === 'pending' ? 'selected' : '' }}>Open</option>
                    <option value="replied" {{ $t->status === 'replied' ? 'selected' : '' }}>Replied</option>
                    <option value="resolved" {{ $t->status === 'resolved' ? 'selected' : '' }}>Resolved</option>
                </select>
            </div>
            <div class="tcell">
                <button class="btn-reply" onclick="openChat('{{ $t->id }}', '{{ $t->user->full_name }}', '{{ $t->subject }}', '{{ addslashes($t->message) }}', '{{ addslashes($t->admin_reply) }}')">View & Reply</button>
            </div>
        </div>
        @empty
        <p class="text-center py-10 text-muted">Belum ada tiket bantuan.</p>
        @endforelse
    </div>
</main>

<div id="chatModal" class="chat-modal">
    <div class="chat-content">
        <div class="chat-header"><div class="chat-title" id="chatTitle">—</div><button onclick="closeChat()" style="background:none; border:none; color:white; font-size:20px; cursor:pointer;">×</button></div>
        <div class="chat-body" id="chatWindow"></div>
        <form id="replyForm" action="" method="POST" class="chat-footer">
            @csrf
            <input type="text" name="reply" id="chatInput" class="chat-input" placeholder="Tulis balasan…" required>
            <button type="submit" class="btn-send">Kirim</button>
        </form>
    </div>
</div>

<script>
    feather.replace();
    function updateTicketStatus(sel, id) {
        fetch(`{{ url('admin/support') }}/${id}/status`, {
            method: 'PATCH',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
            body: JSON.stringify({ status: sel.value })
        });
    }
    function openChat(id, name, subject, message, reply) {
        document.getElementById('chatTitle').textContent = '#' + id + ' · ' + name;
        const win = document.getElementById('chatWindow');
        win.innerHTML = `<div class="bubble-wrap"><div class="bubble user">${message}</div></div>`;
        if(reply) win.innerHTML += `<div class="bubble-wrap admin-wrap"><div class="bubble admin">${reply}</div></div>`;
        document.getElementById('replyForm').action = `{{ url('admin/support') }}/${id}/reply`;
        document.getElementById('chatModal').classList.add('show');
    }
    function closeChat() { document.getElementById('chatModal').classList.remove('show'); }
</script>
</body>
</html>