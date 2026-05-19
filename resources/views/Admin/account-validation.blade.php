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

$stats_view = [
    ["label" => "Pending Seller",    "value" => number_format($stats['pending_sellers']), "icon" => "shopping-cart", "note" => "menunggu review",  "color" => "warning"],
    ["label" => "Pending Designer",  "value" => number_format($stats['pending_designers']), "icon" => "pen-tool", "note" => "menunggu review", "color" => "info"],
    ["label" => "Total Verified",    "value" => "42", "icon" => "check-circle", "note" => "bulan ini",     "color" => "success"],
    ["label" => "Avg. Response",     "value" => "2j", "icon" => "zap",          "note" => "waktu validasi",   "color" => "primary"],
];
@endphp
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Account Validation — DECOR Admin</title>
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
        .clr-info    { background: var(--info-bg); color: var(--info); }

        /* ─── TOOLBAR ─── */
        .toolbar { background: var(--surface); padding: 12px; border-radius: var(--radius); border: 1px solid var(--border); display: flex; align-items: center; justify-content: space-between; margin-bottom: 24px; box-shadow: var(--shadow); }
        .search-wrap { display: flex; align-items: center; gap: 10px; padding-left: 10px; flex: 1; }
        .search-wrap svg { width: 14px; height: 14px; color: var(--text-muted); }
        .search-input { border: none; background: transparent; font-size: 13px; font-weight: 500; width: 100%; outline: none; color: var(--text); }
        .filter-tabs { display: flex; background: var(--bg); padding: 4px; border-radius: 11px; gap: 2px; }
        .ftab { padding: 7px 16px; border: none; background: transparent; border-radius: 8px; font-size: 12px; font-weight: 700; color: var(--text-soft); cursor: pointer; transition: 0.2s; text-decoration: none; }
        .ftab:hover { color: var(--text); }
        .ftab.active { background: var(--surface); color: var(--primary); box-shadow: 0 2px 6px rgba(28,20,16,.08); }

        /* ─── LIST ─── */
        .account-list { display: flex; flex-direction: column; gap: 8px; }
        .account-row { background: var(--surface); border-radius: var(--radius); border: 1px solid var(--border); display: grid; grid-template-columns: 70px 2fr 1.5fr 1fr 1fr 1.5fr; align-items: center; transition: 0.2s ease; position: relative; overflow: hidden; box-shadow: var(--shadow); }
        .account-row:hover { border-color: var(--primary-muted); box-shadow: var(--shadow-card); }

        .tcell { padding: 12px 16px; }
        .account-thumb { width: 50px; height: 50px; border-radius: 10px; object-fit: cover; border: 1px solid var(--border-soft); }

        .acc-name { font-size: 14px; font-weight: 700; color: var(--text); }
        .acc-email { font-size: 11px; color: var(--text-muted); margin-top: 1px; font-weight: 500; }

        .info-label { font-size: 9px; font-weight: 800; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.5px; }
        .info-value { font-size: 12px; font-weight: 600; color: var(--text); }

        .status-pill { font-size: 9px; font-weight: 800; padding: 5px 12px; border-radius: 7px; letter-spacing: 0.5px; text-transform: uppercase; }
        .st-pending  { background: var(--warning-bg); color: #B45309; }
        .st-approved { background: var(--success-bg); color: var(--success); }
        .st-rejected { background: var(--danger-bg);  color: var(--danger); }

        .action-cell { display: flex; gap: 6px; justify-content: flex-end; }
        .btn-approve { background: var(--success-bg); color: var(--success); border: 1px solid transparent; padding: 7px 14px; border-radius: 9px; font-size: 11px; font-weight: 700; cursor: pointer; transition: 0.15s; display: flex; align-items: center; gap: 5px; }
        .btn-approve:hover { background: var(--success); color: white; }
        .btn-reject { background: var(--danger-bg); color: var(--danger); border: 1px solid transparent; padding: 7px 14px; border-radius: 9px; font-size: 11px; font-weight: 700; cursor: pointer; transition: 0.15s; display: flex; align-items: center; gap: 5px; }
        .btn-reject:hover { background: var(--danger); color: white; }

        /* Modal Reason */
        .confirm-modal { position: fixed; inset: 0; background: rgba(28, 20, 16, 0.4); backdrop-filter: blur(4px); display: flex; align-items: center; justify-content: center; z-index: 3000; opacity: 0; pointer-events: none; transition: 0.2s; }
        .confirm-modal.show { opacity: 1; pointer-events: auto; }
        .confirm-box { background: var(--surface); width: 400px; padding: 28px; border-radius: 20px; text-align: center; box-shadow: 0 10px 40px rgba(0,0,0,0.1); }
        .confirm-header { margin-bottom: 24px; }
        .confirm-icon { width: 56px; height: 56px; border-radius: 16px; display: flex; align-items: center; justify-content: center; margin: 0 auto 16px; background: var(--danger-bg); color: var(--danger); }
        .confirm-title { font-size: 18px; font-weight: 800; color: var(--text); }
        .confirm-desc { font-size: 12px; color: var(--text-muted); margin-top: 6px; line-height: 1.5; }
        .confirm-footer { display: flex; gap: 10px; margin-top: 20px; }
        .btn-confirm-cancel { flex: 1; background: var(--bg); border: none; padding: 12px; border-radius: 10px; font-weight: 700; cursor: pointer; font-size: 12px; color: var(--text-soft); }
        .btn-confirm-ok { flex: 1; background: var(--danger); color: white; border: none; padding: 12px; border-radius: 10px; font-weight: 700; cursor: pointer; font-size: 12px; }
        
        .w-full { width: 100%; }
        .mt-4 { margin-top: 1rem; }
    </style>
</head>
<body>

<aside>
    <div class="brand">
        <div class="brand-row">
            <div class="brand-icon"><i data-feather="shield"></i></div>
            <div class="brand-name">DECOR</div>
        </div>
    </div>
    <div class="nav-section">
        <span class="nav-label">Main Menu</span>
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
    </div>
    <div class="sidebar-footer">
        <img src="https://ui-avatars.com/api/?name={{ urlencode($admin_name) }}&background=B5733A&color=fff&size=80" class="s-avatar">
        <div>
            <div class="s-name">{{ $admin_name }}</div>
            <div class="s-role">{{ $admin_role }}</div>
        </div>
    </div>
</aside>

<main>
    <div class="page-header">
        <div>
            <div class="page-eyebrow">Onboarding Control</div>
            <div class="page-title">Account Validation</div>
            <div class="page-desc">Review pendaftaran {{ $type }} baru sebelum mereka dapat berjualan atau menawarkan jasa.</div>
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
        <div class="filter-tabs">
            <a href="{{ route('admin.account.validation', ['type' => 'seller', 'status' => $status]) }}" class="ftab {{ $type == 'seller' ? 'active' : '' }}">Sellers</a>
            <a href="{{ route('admin.account.validation', ['type' => 'designer', 'status' => $status]) }}" class="ftab {{ $type == 'designer' ? 'active' : '' }}">Designers</a>
        </div>
        <div class="filter-tabs">
            <a href="{{ route('admin.account.validation', ['type' => $type, 'status' => 'pending']) }}" class="ftab {{ $status == 'pending' ? 'active' : '' }}">Pending</a>
            <a href="{{ route('admin.account.validation', ['type' => $type, 'status' => 'approved']) }}" class="ftab {{ $status == 'approved' ? 'active' : '' }}">Approved</a>
            <a href="{{ route('admin.account.validation', ['type' => $type, 'status' => 'rejected']) }}" class="ftab {{ $status == 'rejected' ? 'active' : '' }}">Rejected</a>
        </div>
    </div>

    <div class="account-list">
        @if($accounts->isEmpty())
            <div style="text-align: center; padding: 40px; background: var(--surface); border-radius: var(--radius); color: var(--text-muted);">
                <i data-feather="inbox" style="width: 48px; height: 48px; margin-bottom: 12px; opacity: 0.5;"></i>
                <p>Tidak ada pengajuan akun untuk saat ini.</p>
            </div>
        @endif

        @foreach ($accounts as $acc)
        <div class="account-row">
            <div class="tcell">
                <img src="{{ $type == 'seller' ? ($acc->store_image_url) : ($acc->designer_image ? asset('storage/'.$acc->designer_image) : 'https://ui-avatars.com/api/?name='.urlencode($acc->user->full_name)) }}" class="account-thumb">
            </div>
            <div class="tcell">
                <div class="acc-name">{{ $type == 'seller' ? $acc->store_name : $acc->user->full_name }}</div>
                <div class="acc-email">{{ $acc->user->email }}</div>
            </div>
            <div class="tcell">
                <div class="info-label">{{ $type == 'seller' ? 'Address' : 'Specialty' }}</div>
                <div class="info-value">{{ $type == 'seller' ? ($acc->store_address ?? '-') : ($acc->specialty ?? '-') }}</div>
            </div>
            <div class="tcell">
                <div class="info-label">Joined</div>
                <div class="info-value">{{ $acc->created_at->format('d M Y') }}</div>
            </div>
            <div class="tcell">
                <span class="status-pill st-{{ $acc->status }}">{{ strtoupper($acc->status) }}</span>
            </div>
            <div class="tcell">
                <div class="action-cell">
                    @if($acc->status == 'pending')
                    <a href="{{ $type == 'seller' ? route('admin.seller-detail', $acc->id) : route('admin.designer-detail', $acc->id) }}" class="btn-approve" style="background: var(--primary-light); color: var(--primary);">
                        <i data-feather="eye"></i> Detail
                    </a>
                    <form action="{{ route('admin.account.approve', $acc->id) }}" method="POST">
                        @csrf
                        <input type="hidden" name="type" value="{{ $type }}">
                        <button type="submit" class="btn-approve"><i data-feather="check"></i> Approve</button>
                    </form>
                    <button class="btn-reject" onclick="openRejectModal('{{ $acc->id }}', '{{ $type == 'seller' ? $acc->store_name : $acc->user->full_name }}')">
                        <i data-feather="x"></i> Reject
                    </button>
                    @elseif($acc->status == 'rejected')
                        <div class="info-label">Reason:</div>
                        <div style="font-size: 10px; color: var(--danger);">{{ $acc->rejection_reason }}</div>
                    @else
                    <a href="{{ $type == 'seller' ? route('admin.seller-detail', $acc->id) : route('admin.designer-detail', $acc->id) }}" class="btn-approve" style="background: var(--primary-light); color: var(--primary);">
                        <i data-feather="eye"></i> View Profile
                    </a>
                    @endif
                </div>
            </div>
        </div>
        @endforeach
    </div>
</main>

<div id="rejectModal" class="confirm-modal">
    <div class="confirm-box">
        <form id="rejectForm" method="POST">
            @csrf
            <input type="hidden" name="type" value="{{ $type }}">
            <div class="confirm-header">
                <div class="confirm-icon"><i data-feather="alert-triangle"></i></div>
                <div class="confirm-title">Reject Account</div>
                <div class="confirm-desc">Berikan alasan kenapa akun <strong id="targetName"></strong> ditolak.</div>
                <textarea name="reason" class="search-input w-full mt-4" style="height:100px; padding:12px; background: var(--bg); border: 1px solid var(--border); border-radius: 10px;" placeholder="Contoh: Portfolio tidak valid atau Nama toko mengandung kata terlarang..."></textarea>
            </div>
            <div class="confirm-footer">
                <button type="button" class="btn-confirm-cancel" onclick="closeRejectModal()">Batal</button>
                <button type="submit" class="btn-confirm-ok">Reject Account</button>
            </div>
        </form>
    </div>
</div>

<script>
    feather.replace();
    function openRejectModal(id, name) {
        document.getElementById('rejectForm').action = `/admin/account-validation/${id}/reject`;
        document.getElementById('targetName').textContent = name;
        document.getElementById('rejectModal').classList.add('show');
    }
    function closeRejectModal() {
        document.getElementById('rejectModal').classList.remove('show');
    }
</script>
</body>
</html>