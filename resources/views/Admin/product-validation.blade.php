@php
$admin_name = Auth::user()->full_name;
$admin_role = "ADMIN";

$menu_items = [
    ["label" => "Dashboard",              "path" => route('admin.dashboard'),              "icon" => "grid"],
    ["label" => "User Management",        "path" => route('admin.user-management'),        "icon" => "users"],
    ["label" => "Account Validation",     "path" => route('admin.account.validation'),     "icon" => "shield"],
    ["label" => "Seller Monitor",         "path" => route('admin.seller-monitor'),         "icon" => "shopping-bag"],
    ["label" => "Designer Monitor",       "path" => route('admin.designer-monitor'),       "icon" => "pen-tool"],
    ["label" => "Seller Support",         "path" => route('admin.seller-support'),         "icon" => "headphones"],
    ["label" => "Designer Support",       "path" => route('admin.designer-support'),       "icon" => "pen-tool"],
    ["label" => "Customer Support",       "path" => route('admin.customer-support'),       "icon" => "message-circle"],
    ["label" => "Product Validation",     "path" => route('admin.product.validation'),     "icon" => "check-circle"],
    ["label" => "Portofolio Validation",  "path" => route('admin.portfolio-validation'),  "icon" => "image"],
];

$stats_view = [
    ["label" => "Pending",    "value" => number_format($stats['pending']), "icon" => "clock",        "note" => "menunggu review",  "color" => "warning"],
    ["label" => "Disetujui",  "value" => number_format($stats['approved']), "icon" => "check-circle", "note" => "total disetujui", "color" => "success"],
    ["label" => "Ditolak",    "value" => number_format($stats['rejected']), "icon" => "x-circle",     "note" => "total ditolak",     "color" => "danger"],
    ["label" => "Avg. Review","value" => "3j", "icon" => "zap",          "note" => "waktu validasi",   "color" => "primary"],
];
@endphp
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product Validation — DECOR Admin</title>
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
        body { font-family: 'Plus Jakarta Sans', sans-serif; background: var(--bg); color: var(--text); display: flex; min-height: 100vh; font-size: 13px; overflow-x: hidden; }

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
        .page-header { display: flex; justify-content: space-between; align-items: flex-end; margin-bottom: 28px; }
        .page-eyebrow { font-size: 10px; font-weight: 800; text-transform: uppercase; letter-spacing: .16em; color: var(--primary); margin-bottom: 5px; }
        .page-title { font-size: 24px; font-weight: 800; color: var(--text); }
        .page-desc { font-size: 12px; color: var(--text-muted); margin-top: 4px; }

        /* ─── STATS ─── */
        .stats-row { display: grid; grid-template-columns: repeat(4,1fr); gap: 14px; margin-bottom: 28px; }
        .stat-card { background: var(--surface); padding: 20px; border-radius: var(--radius); border: 1px solid var(--border); box-shadow: var(--shadow); transition: 0.2s ease; display: flex; align-items: center; gap: 16px; }
        .stat-card:hover { transform: translateY(-2px); box-shadow: var(--shadow-card); }
        .stat-icon { width: 44px; height: 44px; border-radius: 11px; display: flex; align-items: center; justify-content: center; }
        .stat-icon svg { width: 20px; height: 20px; }
        .stat-val { font-size: 20px; font-weight: 800; color: var(--text); }
        .stat-lbl { font-size: 11px; font-weight: 700; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.5px; }
        .stat-note { font-size: 10px; color: var(--text-soft); margin-top: 1px; }

        .clr-warning { background: var(--warning-bg); color: var(--warning); }
        .clr-success { background: var(--success-bg); color: var(--success); }
        .clr-danger  { background: var(--danger-bg); color: var(--danger); }
        .clr-primary { background: var(--primary-light); color: var(--primary); }

        /* ─── TOOLBAR ─── */
        .toolbar { background: var(--surface); padding: 12px; border-radius: var(--radius); border: 1px solid var(--border); display: flex; align-items: center; justify-content: space-between; margin-bottom: 24px; box-shadow: var(--shadow); }
        .search-wrap { display: flex; align-items: center; gap: 10px; padding-left: 10px; flex: 1; }
        .search-wrap svg { width: 14px; height: 14px; color: var(--text-muted); }
        .search-input { border: none; background: transparent; font-size: 13px; font-weight: 500; width: 100%; outline: none; color: var(--text); }
        .filter-tabs { display: flex; background: var(--bg); padding: 4px; border-radius: 11px; gap: 2px; }
        .ftab { padding: 7px 16px; border: none; background: transparent; border-radius: 8px; font-size: 12px; font-weight: 700; color: var(--text-soft); cursor: pointer; transition: 0.2s; text-decoration: none; }
        .ftab:hover { color: var(--text); }
        .ftab.active { background: var(--surface); color: var(--primary); box-shadow: 0 2px 6px rgba(28,20,16,.08); }

        /* ─── PRODUCT LIST ─── */
        .product-list { display: flex; flex-direction: column; gap: 8px; }
        .product-row { background: var(--surface); border-radius: var(--radius); border: 1px solid var(--border); display: grid; grid-template-columns: 70px 2fr 1fr 1.2fr 1.2fr 1fr 1.5fr; align-items: center; transition: 0.2s ease; position: relative; overflow: hidden; box-shadow: var(--shadow); }
        .product-row:hover { border-color: var(--primary-muted); box-shadow: var(--shadow-card); }
        .product-row.decided { opacity: 0.6; filter: grayscale(0.6); pointer-events: none; }

        .tcell { padding: 12px 16px; }
        .prod-thumb { width: 64px; height: 64px; border-radius: 10px; object-fit: cover; cursor: pointer; transition: 0.2s; border: 1px solid var(--border-soft); }
        .prod-thumb:hover { filter: brightness(0.9); transform: scale(1.05); }

        .prod-id { font-family: 'DM Mono', monospace; font-size: 10px; font-weight: 700; color: var(--primary); background: var(--primary-light); padding: 2px 6px; border-radius: 5px; width: fit-content; margin-bottom: 4px; }
        .prod-name { font-size: 14px; font-weight: 700; color: var(--text); }
        .prod-shop { font-size: 11px; color: var(--text-muted); margin-top: 1px; font-weight: 500; }

        .cat-badge { font-size: 10px; font-weight: 800; color: var(--text-muted); background: var(--bg); padding: 4px 12px; border-radius: 20px; text-transform: uppercase; letter-spacing: 0.5px; }

        .prod-price { font-size: 15px; font-weight: 800; color: var(--text); }
        .prod-stock { font-size: 11px; color: var(--text-muted); margin-top: 2px; }

        .tkt-time { display: flex; align-items: center; gap: 6px; font-size: 11px; color: var(--text-muted); font-weight: 500; }
        .tkt-time svg { width: 13px; height: 13px; stroke-width: 2.5px; }

        .status-pill { font-size: 9px; font-weight: 800; padding: 5px 12px; border-radius: 7px; letter-spacing: 0.5px; text-transform: uppercase; }
        .st-pending  { background: var(--warning-bg); color: #B45309; }
        .st-approved { background: var(--success-bg); color: var(--success); }
        .st-rejected { background: var(--danger-bg);  color: var(--danger); }

        .action-cell { display: flex; gap: 6px; justify-content: flex-end; }
        .btn-approve { background: var(--success-bg); color: var(--success); border: 1px solid transparent; padding: 7px 14px; border-radius: 9px; font-size: 11px; font-weight: 700; cursor: pointer; transition: 0.15s; display: flex; align-items: center; gap: 5px; }
        .btn-approve:hover { background: var(--success); color: white; }
        .btn-reject { background: var(--danger-bg); color: var(--danger); border: 1px solid transparent; padding: 7px 14px; border-radius: 9px; font-size: 11px; font-weight: 700; cursor: pointer; transition: 0.15s; display: flex; align-items: center; gap: 5px; }
        .btn-reject:hover { background: var(--danger); color: white; }

        /* ─── MODALS ─── */
        .detail-modal { position: fixed; inset: 0; background: rgba(28, 20, 16, 0.4); backdrop-filter: blur(8px); display: flex; align-items: center; justify-content: center; z-index: 2000; opacity: 0; pointer-events: none; transition: 0.3s; }
        .detail-modal.show { opacity: 1; pointer-events: auto; }
        .detail-content { background: var(--surface); width: 860px; border-radius: 24px; display: grid; grid-template-columns: 1fr 1.2fr; overflow: hidden; transform: scale(0.95); transition: 0.3s; box-shadow: 0 20px 60px rgba(0,0,0,0.2); }
        .detail-modal.show .detail-content { transform: scale(1); }

        .detail-img-wrap { position: relative; height: 100%; }
        .detail-img-wrap img { width: 100%; height: 100%; object-fit: cover; }
        .detail-img-overlay { position: absolute; bottom: 0; left: 0; right: 0; padding: 32px; background: linear-gradient(to top, rgba(0,0,0,0.7), transparent); color: white; }

        .detail-body { padding: 40px; position: relative; display: flex; flex-direction: column; }
        .detail-close { position: absolute; top: 20px; right: 20px; border: none; background: var(--bg); width: 32px; height: 32px; border-radius: 50%; cursor: pointer; display: flex; align-items: center; justify-content: center; color: var(--text-muted); transition: 0.2s; }
        .detail-close:hover { background: var(--border-soft); color: var(--text); }
        .detail-title { font-size: 24px; font-weight: 800; margin-bottom: 6px; color: var(--text); }
        .detail-price { font-size: 20px; font-weight: 800; color: var(--primary); margin-bottom: 24px; }
        .detail-desc { font-size: 13.5px; color: var(--text-soft); line-height: 1.7; margin-bottom: 32px; font-style: italic; background: var(--surface-2); padding: 16px; border-radius: 12px; border: 1px solid var(--border-soft); }

        .spec-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 12px; margin-bottom: auto; }
        .spec-box { background: var(--bg); padding: 14px; border-radius: 12px; border: 1px solid var(--border-soft); }
        .spec-label { font-size: 9px; font-weight: 800; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 3px; }
        .spec-value { font-size: 13px; font-weight: 700; color: var(--text); }

        .detail-actions { display: flex; gap: 12px; margin-top: 32px; }
        .btn-approve-lg { flex: 1; background: var(--success); color: white; border: none; padding: 14px; border-radius: 12px; font-weight: 800; cursor: pointer; font-size: 13px; transition: 0.2s; }
        .btn-approve-lg:hover { transform: translateY(-2px); box-shadow: 0 8px 20px rgba(26, 122, 74, 0.25); }
        .btn-reject-lg { background: var(--danger-bg); color: var(--danger); border: none; padding: 14px; border-radius: 12px; font-weight: 800; cursor: pointer; font-size: 13px; transition: 0.2s; }
        .btn-reject-lg:hover { background: var(--danger); color: white; }

        /* Confirm Modal */
        .confirm-modal { position: fixed; inset: 0; background: rgba(28, 20, 16, 0.4); backdrop-filter: blur(4px); display: flex; align-items: center; justify-content: center; z-index: 3000; opacity: 0; pointer-events: none; transition: 0.2s; }
        .confirm-modal.show { opacity: 1; pointer-events: auto; }
        .confirm-box { background: var(--surface); width: 380px; padding: 28px; border-radius: 20px; text-align: center; box-shadow: 0 10px 40px rgba(0,0,0,0.1); }
        .confirm-header { margin-bottom: 24px; }
        .confirm-icon { width: 56px; height: 56px; border-radius: 16px; display: flex; align-items: center; justify-content: center; margin: 0 auto 16px; background: var(--danger-bg); color: var(--danger); }
        .confirm-title { font-size: 18px; font-weight: 800; color: var(--text); }
        .confirm-desc { font-size: 12px; color: var(--text-muted); margin-top: 6px; line-height: 1.5; }
        .confirm-target { font-weight: 700; color: var(--text); display: block; margin-top: 4px; }
        .confirm-footer { display: flex; gap: 10px; }
        .btn-confirm-cancel { flex: 1; background: var(--bg); border: none; padding: 12px; border-radius: 10px; font-weight: 700; cursor: pointer; font-size: 12px; color: var(--text-soft); }
        .btn-confirm-ok { flex: 1; background: var(--danger); color: white; border: none; padding: 12px; border-radius: 10px; font-weight: 700; cursor: pointer; font-size: 12px; }

        .w-full { width: 100%; }
        .mt-4 { margin-top: 1rem; }
        .flex-1 { flex: 1; }
    </style>
</head>
<body>

<!-- ══════════ SIDEBAR ══════════ -->
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
        @php
            $active = (request()->url() === $item['path']) ? 'active' : '';
        @endphp
        <a href="{{ $item['path'] }}" class="menu-link {{ $active }}">
            <div class="menu-item">
                <i data-feather="{{ $item['icon'] }}"></i>
                <span>{{ $item['label'] }}</span>
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

<!-- ══════════ MAIN ══════════ -->
<main>

    <!-- Page Header -->
    <div class="page-header">
        <div>
            <div class="page-eyebrow">Quality Control</div>
            <div class="page-title">Product Validation</div>
            <div class="page-desc">Review dan setujui produk baru sebelum tampil di katalog marketplace.</div>
        </div>
    </div>

    <div class="stats-row">
        @foreach ($stats_view as $s)
        <div class="stat-card">
            <div class="stat-icon clr-{{ $s['color'] }}"><i data-feather="{{ $s['icon'] }}"></i></div>
            <div>
                <div class="stat-val">{{ $s['value'] }}</div>
                <div class="stat-lbl">{{ $s['label'] }}</div>
                <div class="stat-note">{{ $s['note'] }}</div>
            </div>
        </div>
        @endforeach
    </div>

    <div class="toolbar">
        <div class="search-wrap">
            <i data-feather="search"></i>
            <input class="search-input" type="text" placeholder="Cari produk, toko, atau kategori…">
        </div>
        <div class="filter-tabs">
            <a href="{{ route('admin.product.validation', ['status' => 'semua']) }}" class="ftab {{ $statusFilter == 'semua' ? 'active' : '' }}">Semua</a>
            <a href="{{ route('admin.product.validation', ['status' => 'pending']) }}" class="ftab {{ $statusFilter == 'pending' ? 'active' : '' }}">Pending</a>
            <a href="{{ route('admin.product.validation', ['status' => 'approved']) }}" class="ftab {{ $statusFilter == 'approved' ? 'active' : '' }}">Approved</a>
            <a href="{{ route('admin.product.validation', ['status' => 'rejected']) }}" class="ftab {{ $statusFilter == 'rejected' ? 'active' : '' }}">Rejected</a>
        </div>
    </div>

    <!-- Product List -->
    <div class="product-list">
        @foreach ($products as $p)
        @php
            $sl = strtolower($p->status);
        @endphp
        <div class="product-row" id="row-{{ $p->id }}">

            <!-- Thumbnail -->
            <div class="tcell" style="padding:10px;">
                <img src="{{ $p->images->first()->img_url ?? 'https://images.unsplash.com/photo-1555041469-a586c61ea9bc?w=600' }}" class="prod-thumb"
                     onclick="openDetail({{ json_encode($p) }}, '{{ $p->seller->user->full_name }}', '{{ $p->category->name }}')">
            </div>

            <!-- Product Info -->
            <div class="tcell">
                <div class="prod-id">PRD-{{ str_pad($p->id, 3, '0', STR_PAD_LEFT) }}</div>
                <div class="prod-name">{{ $p->name }}</div>
                <div class="prod-shop">{{ $p->seller->user->full_name }}</div>
            </div>

            <!-- Category -->
            <div class="tcell">
                <span class="cat-badge">{{ $p->category->name }}</span>
            </div>

            <!-- Price + Stock -->
            <div class="tcell">
                <div class="prod-price">Rp {{ number_format($p->price, 0, ',', '.') }}</div>
                <div class="prod-stock">Stok: {{ $p->stock }} unit</div>
            </div>

            <!-- Submitted -->
            <div class="tcell">
                <div class="tkt-time"><i data-feather="clock"></i>{{ $p->created_at->diffForHumans() }}</div>
            </div>

            <!-- Status -->
            <div class="tcell">
                <span class="status-pill st-{{ $sl }}" id="pill-{{ $p->id }}">{{ strtoupper($p->status) }}</span>
            </div>

            <!-- Actions -->
            <div class="tcell">
                <div class="action-cell">
                    @if($p->status == 'pending')
                    <form action="{{ route('admin.product.approve', $p->id) }}" method="POST">
                        @csrf
                        <button type="submit" class="btn-approve">
                            <i data-feather="check"></i>
                        </button>
                    </form>
                    <button class="btn-reject"
                        onclick="askConfirm('reject','{{ $p->id }}','{{ addslashes($p->name) }}')">
                        <i data-feather="x"></i>
                    </button>
                    @endif
                </div>
            </div>

        </div>
        @endforeach
    </div>

</main>

<!-- ══════════ DETAIL MODAL ══════════ -->
<div id="detailModal" class="detail-modal">
    <div class="detail-content">
        <div class="detail-img-wrap">
            <img id="modalImg" src="" alt="">
            <div class="detail-img-overlay">
                <div class="prod-id-lg" id="modalId"></div>
                <div class="prod-shop-lg" id="modalShop"></div>
            </div>
        </div>
        <div class="detail-body">
            <button class="detail-close" onclick="closeDetail()">×</button>
            <div class="detail-title" id="modalName"></div>
            <div class="detail-price" id="modalPrice"></div>
            <div class="detail-desc"  id="modalDesc"></div>
            <div class="spec-grid">
                <div class="spec-box"><div class="spec-label">Style</div><div class="spec-value" id="modalStyle"></div></div>
                <div class="spec-box"><div class="spec-label">Kategori</div><div class="spec-value" id="modalCat"></div></div>
                <div class="spec-box"><div class="spec-label">Stok Tersedia</div><div class="spec-value" id="modalStock"></div></div>
            </div>
            <div class="detail-actions" id="modalActionArea">
                <form id="modalApproveForm" method="POST" class="flex-1">
                    @csrf
                    <button type="submit" class="btn-approve-lg w-full">
                        <i data-feather="check-circle"></i> Approve Produk
                    </button>
                </form>
                <button class="btn-reject-lg" id="modalRejectBtn">
                    <i data-feather="x-circle"></i> Tolak Produk
                </button>
            </div>
        </div>
    </div>
</div>

<!-- ══════════ REJECT REASON MODAL ══════════ -->
<div id="confirmModal" class="confirm-modal">
    <div class="confirm-box">
        <form id="rejectForm" method="POST">
            @csrf
            <div class="confirm-header">
                <div class="confirm-icon">
                    <i data-feather="x-circle"></i>
                </div>
                <div class="confirm-title">Tolak Produk</div>
                <div class="confirm-desc">Berikan alasan penolakan agar seller dapat memperbaiki produk mereka.</div>
                <div class="confirm-target" id="confirmTarget"></div>
                <textarea name="message" class="search-input w-full mt-4" style="height:100px; padding:12px; background: var(--bg); border: 1px solid var(--border); border-radius: 10px;" placeholder="Tulis alasan penolakan di sini..."></textarea>
            </div>
            <div class="confirm-footer">
                <button type="button" class="btn-confirm-cancel" onclick="closeConfirm()">Batal</button>
                <button type="submit" class="btn-confirm-ok">Tolak Produk</button>
            </div>
        </form>
    </div>
</div>

<script>
feather.replace();

/* ── detail modal ── */
function openDetail(p, shopName, catName) {
    const images = p.images || [];
    document.getElementById('modalImg').src      = images.length > 0 ? images[0].img_url : 'https://images.unsplash.com/photo-1555041469-a586c61ea9bc?w=600';
    document.getElementById('modalId').textContent   = 'PRD-' + String(p.id).padStart(3, '0');
    document.getElementById('modalShop').textContent = shopName;
    document.getElementById('modalName').textContent = p.name;
    document.getElementById('modalPrice').textContent = 'Rp ' + new Intl.NumberFormat('id-ID').format(p.price);
    document.getElementById('modalDesc').textContent = '"' + p.description + '"';
    document.getElementById('modalStyle').textContent = p.style || '-';
    document.getElementById('modalCat').textContent     = catName;
    document.getElementById('modalStock').textContent   = p.stock + ' unit';

    /* wire modal buttons */
    const approveForm = document.getElementById('modalApproveForm');
    approveForm.action = `/admin/product-validation/${p.id}/approve`;
    
    const rejectBtn = document.getElementById('modalRejectBtn');
    rejectBtn.onclick = () => { closeDetail(); askConfirm('reject', p.id, p.name); };

    if (p.status !== 'pending') {
        document.getElementById('modalActionArea').style.display = 'none';
    } else {
        document.getElementById('modalActionArea').style.display = 'flex';
    }

    document.getElementById('detailModal').classList.add('show');
    feather.replace();
}
function closeDetail() { document.getElementById('detailModal').classList.remove('show'); }

window.addEventListener('click', e => {
    if (e.target === document.getElementById('detailModal')) closeDetail();
    if (e.target === document.getElementById('confirmModal')) closeConfirm();
});

/* ── confirm modal ── */
function askConfirm(type, id, name) {
    const form = document.getElementById('rejectForm');
    form.action = `/admin/product-validation/${id}/reject`;
    document.getElementById('confirmTarget').textContent = name;
    document.getElementById('confirmModal').classList.add('show');
    feather.replace();
}

function closeConfirm() {
    document.getElementById('confirmModal').classList.remove('show');
}
</script>
</body>
</html>