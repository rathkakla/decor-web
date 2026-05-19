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
@endphp
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Seller Detail — {{ $seller->store_name }} | DECOR Admin</title>
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

        /* ─── BREADCRUMB ─── */
        .breadcrumb { display: flex; align-items: center; gap: 6px; margin-bottom: 24px; }
        .breadcrumb a { text-decoration: none; font-size: 11px; font-weight: 700; color: var(--text-muted); display: flex; align-items: center; gap: 4px; transition: color 0.15s; }
        .breadcrumb a:hover { color: var(--primary); }
        .breadcrumb a svg { width: 11px; height: 11px; }
        .breadcrumb-sep { color: var(--border); font-size: 14px; }
        .breadcrumb-current { font-size: 11px; font-weight: 700; color: var(--primary); }

        /* ─── HERO CARD ─── */
        .hero-card {
            background: var(--surface); border: 1px solid var(--border);
            border-radius: var(--radius); box-shadow: var(--shadow-card);
            padding: 28px 32px; margin-bottom: 20px;
            position: relative; overflow: hidden;
        }
        .hero-card::before { content: ''; position: absolute; top: 0; left: 0; right: 0; height: 4px; background: {{ $seller->status == 'pending' ? 'var(--warning)' : 'var(--success)' }}; }
        .hero-top { display: flex; align-items: flex-start; justify-content: space-between; gap: 20px; }
        .hero-left { display: flex; align-items: center; gap: 18px; }
        .shop-icon { width: 64px; height: 64px; border-radius: 16px; background: var(--primary-light); border: 2px solid var(--primary-muted); display: flex; align-items: center; justify-content: center; flex-shrink: 0; overflow: hidden; }
        .shop-icon img { width: 100%; height: 100%; object-fit: cover; }
        .hero-name { font-size: 22px; font-weight: 800; color: var(--text); margin-bottom: 4px; }
        .hero-meta { display: flex; align-items: center; gap: 10px; flex-wrap: wrap; }
        .hero-id { font-family: 'DM Mono', monospace; font-size: 10px; color: var(--text-muted); background: var(--surface-2); border: 1px solid var(--border-soft); padding: 3px 8px; border-radius: 5px; }
        .hero-spec { font-size: 11px; color: var(--text-soft); font-weight: 500; display: flex; align-items: center; gap: 4px; }
        .hero-spec svg { width: 10px; }
        .hero-joined { font-size: 11px; color: var(--text-muted); font-weight: 500; display: flex; align-items: center; gap: 4px; }
        .hero-joined svg { width: 10px; }
        .hero-right { display: flex; flex-direction: column; align-items: flex-end; gap: 8px; }
        .pill { display: inline-flex; align-items: center; gap: 4px; padding: 5px 12px; border-radius: 7px; font-size: 10px; font-weight: 800; text-transform: uppercase; letter-spacing: 0.05em; }
        .pill svg { width: 10px; }
        .pill-pending { background: var(--warning-bg); color: var(--warning); border: 1px solid #F5CBA7; }
        .pill-approved { background: var(--success-bg); color: var(--success); border: 1px solid #B8DEC8; }
        .pill-rejected { background: var(--danger-bg); color: var(--danger); border: 1px solid #F5C6C1; }

        /* ─── REVIEW BOX ─── */
        .review-box { background: var(--warning-bg); border: 1px solid #F5CBA7; border-radius: var(--radius); padding: 24px; margin-bottom: 24px; display: flex; gap: 20px; align-items: center; }
        .review-info { flex: 1; }
        .review-title { font-size: 16px; font-weight: 800; color: var(--warning); margin-bottom: 4px; }
        .review-desc { font-size: 12px; color: var(--text-soft); line-height: 1.5; }
        .review-actions { display: flex; gap: 10px; }
        .btn-approve { background: var(--success); color: #fff; border: none; padding: 10px 20px; border-radius: 10px; font-size: 12px; font-weight: 700; cursor: pointer; transition: 0.2s; display: flex; align-items: center; gap: 8px; }
        .btn-approve:hover { background: #125A35; }
        .btn-reject { background: var(--danger); color: #fff; border: none; padding: 10px 20px; border-radius: 10px; font-size: 12px; font-weight: 700; cursor: pointer; transition: 0.2s; display: flex; align-items: center; gap: 8px; }
        .btn-reject:hover { background: #9B2335; }

        /* ─── LAYOUT ─── */
        .layout { display: grid; grid-template-columns: 1fr 320px; gap: 24px; }
        .panel { background: var(--surface); border: 1px solid var(--border); border-radius: var(--radius); box-shadow: var(--shadow); overflow: hidden; margin-bottom: 20px; }
        .panel-header { padding: 16px 22px; border-bottom: 1px solid var(--border-soft); display: flex; align-items: center; justify-content: space-between; }
        .panel-title { font-size: 13px; font-weight: 800; color: var(--text); display: flex; align-items: center; gap: 8px; }
        .panel-title svg { width: 14px; height: 14px; stroke: var(--primary); }
        .panel-body { padding: 22px; }

        /* ─── PRODUCTS GRID ─── */
        .products-grid { display: grid; grid-template-columns: repeat(2, 1fr); gap: 16px; }
        .prod-card { border: 1px solid var(--border-soft); border-radius: 12px; overflow: hidden; background: var(--surface); position: relative; }
        .prod-img-wrap { height: 160px; overflow: hidden; }
        .prod-img { width: 100%; height: 100%; object-fit: cover; }
        .prod-body { padding: 14px; }
        .prod-name { font-size: 13px; font-weight: 800; color: var(--text); margin-bottom: 4px; }
        .prod-price { font-size: 12px; font-weight: 700; color: var(--primary); }
        .prod-desc { font-size: 11px; color: var(--text-soft); margin-top: 8px; line-height: 1.5; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; }

        /* Modal Reason */
        .modal { position: fixed; inset: 0; background: rgba(28, 20, 16, 0.4); backdrop-filter: blur(4px); display: none; align-items: center; justify-content: center; z-index: 3000; }
        .modal.show { display: flex; }
        .modal-box { background: var(--surface); width: 400px; padding: 28px; border-radius: 20px; box-shadow: 0 10px 40px rgba(0,0,0,0.1); }
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
            <div class="menu-item">
                <i data-feather="{{ $item['icon'] }}"></i>
                <span>{{ $item['label'] }}</span>
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
    <div class="breadcrumb">
        <a href="{{ route('admin.dashboard') }}"><i data-feather="home"></i> Dashboard</a>
        <span class="breadcrumb-sep">/</span>
        <a href="{{ route('admin.account.validation') }}"><i data-feather="shield"></i> Account Validation</a>
        <span class="breadcrumb-sep">/</span>
        <span class="breadcrumb-current">{{ $seller->store_name }}</span>
    </div>

    @if($seller->status == 'pending')
    <div class="review-box">
        <div class="review-info">
            <div class="review-title">Account Review Pending</div>
            <div class="review-desc">Mohon tinjau informasi toko dan produk yang diupload oleh seller ini sebelum memberikan persetujuan aktivasi akun.</div>
        </div>
        <div class="review-actions">
            <form action="{{ route('admin.account.approve', $seller->id) }}" method="POST">
                @csrf
                <input type="hidden" name="type" value="seller">
                <button type="submit" class="btn-approve"><i data-feather="check"></i> Approve Account</button>
            </form>
            <button type="button" class="btn-reject" onclick="openRejectModal()"><i data-feather="x"></i> Reject Account</button>
        </div>
    </div>
    @endif

    <div class="hero-card">
        <div class="hero-top">
            <div class="hero-left">
                <div class="shop-icon">
                    @if($seller->store_image_url)
                        <img src="{{ $seller->store_image_url }}">
                    @else
                        <i data-feather="shopping-bag"></i>
                    @endif
                </div>
                <div>
                    <div class="hero-name">{{ $seller->store_name }}</div>
                    <div class="hero-meta">
                        <span class="hero-id">ID: {{ str_pad($seller->id, 4, '0', STR_PAD_LEFT) }}</span>
                        <span class="hero-spec"><i data-feather="tag"></i>Seller</span>
                        <span class="hero-joined"><i data-feather="calendar"></i>Joined {{ $seller->created_at->format('d M Y') }}</span>
                    </div>
                </div>
            </div>
            <div class="hero-right">
                <span class="pill pill-{{ $seller->status }}"><i data-feather="info"></i>{{ strtoupper($seller->status) }}</span>
            </div>
        </div>
    </div>

    <div class="layout">
        <div class="left-col">
            <div class="panel">
                <div class="panel-header">
                    <div class="panel-title"><i data-feather="package"></i> Produk yang diupload</div>
                </div>
                <div class="panel-body">
                    @if($seller->products->isEmpty())
                        <p style="text-align: center; color: var(--text-muted); padding: 20px;">Belum ada produk yang diupload.</p>
                    @else
                        <div class="products-grid">
                            @foreach($seller->products as $product)
                            <div class="prod-card">
                                <div class="prod-img-wrap">
                                    @if($product->images->isNotEmpty())
                                        @php
                                            $imgUrl = $product->images->first()->img_url;
                                            if (\Illuminate\Support\Str::startsWith($imgUrl, ['/storage', 'storage', 'http'])) {
                                                $imgUrl = $imgUrl; // Already has path
                                            } else {
                                                $imgUrl = 'storage/' . $imgUrl;
                                            }
                                        @endphp
                                        <img src="{{ asset($imgUrl) }}" class="prod-img">
                                    @else
                                        <img src="https://placehold.co/400" class="prod-img">
                                    @endif
                                </div>
                                <div class="prod-body">
                                    <div class="prod-name">{{ $product->name }}</div>
                                    <div class="prod-price">Rp {{ number_format($product->price, 0, ',', '.') }}</div>
                                    <div class="prod-desc">{{ $product->description }}</div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="right-col">
            <div class="panel">
                <div class="panel-header">
                    <div class="panel-title"><i data-feather="info"></i> Informasi Toko</div>
                </div>
                <div class="panel-body">
                    <div style="margin-bottom: 16px;">
                        <label style="font-size: 10px; font-weight: 800; color: var(--text-muted); text-transform: uppercase;">Owner Name</label>
                        <div style="font-size: 13px; font-weight: 700;">{{ $seller->user->full_name }}</div>
                    </div>
                    <div style="margin-bottom: 16px;">
                        <label style="font-size: 10px; font-weight: 800; color: var(--text-muted); text-transform: uppercase;">Store Address</label>
                        <div style="font-size: 12px; line-height: 1.5;">{{ $seller->store_address ?? 'Tidak ada data' }}</div>
                    </div>
                    <div style="margin-bottom: 16px;">
                        <label style="font-size: 10px; font-weight: 800; color: var(--text-muted); text-transform: uppercase;">Description</label>
                        <div style="font-size: 12px; line-height: 1.5;">{{ $seller->store_description ?? 'Tidak ada data' }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

<div id="rejectModal" class="modal">
    <div class="modal-box">
        <form action="{{ route('admin.account.reject', $seller->id) }}" method="POST">
            @csrf
            <input type="hidden" name="type" value="seller">
            <h3 style="font-size: 18px; font-weight: 800; margin-bottom: 16px;">Reject Account</h3>
            <p style="font-size: 12px; color: var(--text-soft); margin-bottom: 16px;">Berikan alasan penolakan agar seller dapat memperbaiki datanya.</p>
            <textarea name="reason" required style="width: 100%; height: 100px; padding: 12px; border: 1px solid var(--border); border-radius: 10px; margin-bottom: 16px;" placeholder="Contoh: Nama toko mengandung kata terlarang, foto produk tidak jelas..."></textarea>
            <div style="display: flex; gap: 10px; justify-content: flex-end;">
                <button type="button" onclick="closeRejectModal()" style="padding: 10px 20px; border-radius: 10px; background: var(--bg); border: none; font-weight: 700;">Batal</button>
                <button type="submit" class="btn-reject">Reject Account</button>
            </div>
        </form>
    </div>
</div>

<script>
    feather.replace();
    function openRejectModal() { document.getElementById('rejectModal').classList.add('show'); }
    function closeRejectModal() { document.getElementById('rejectModal').classList.remove('show'); }
</script>
</body>
</html>