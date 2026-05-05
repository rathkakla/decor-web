@php $site_name = "DECOR"; @endphp
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $seller->store_name ?? $seller->user->full_name }} — {{ $site_name }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=DM+Serif+Display:ital@0;1&display=swap" rel="stylesheet">
    <script>
        tailwind.config = {
            theme: { extend: { colors: { primary:'#B5733A', secondary:'#E3DCD6' } } }
        }
    </script>
    <style>
        :root {
            --primary:  #B5733A;
            --secondary:#E3DCD6;
            --warm:     #F7F3EF;
            --bark:     #8A5228;
            --ink:      #1C1410;
            --border:   #EDE8E2;
            --muted:    #A89E97;
        }
        * { box-sizing: border-box; }
        body { font-family: 'Plus Jakarta Sans', sans-serif; background: #fff; color: var(--ink); }
        .serif { font-family: 'DM Serif Display', serif; }

        /* ── COVER ── */
        .cover-wrap { height: 340px; position: relative; overflow: hidden; }
        .cover-img { width: 100%; height: 100%; object-fit: cover; filter: brightness(.72); transform: scale(1.03); transition: transform 8s ease; }
        .cover-wrap:hover .cover-img { transform: scale(1); }
        .cover-overlay { position: absolute; inset: 0; background: linear-gradient(to bottom, transparent 40%, rgba(28,20,16,.65) 100%); }
        .cover-grain { position: absolute; inset: 0; background-image: url("data:image/svg+xml,%3Csvg viewBox='0 0 256 256' xmlns='http://www.w3.org/2000/svg'%3E%3Cfilter id='noise'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.9' numOctaves='4' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23noise)' opacity='0.04'/%3E%3C/svg%3E"); opacity: .3; pointer-events: none; }

        /* ── SELLER IDENTITY CARD ── */
        .identity-wrap { max-width: 1200px; margin: 0 auto; padding: 0 24px; position: relative; }
        .identity-card { background: #fff; border: 1px solid var(--border); border-radius: 20px; padding: 28px 32px; display: flex; align-items: flex-start; gap: 24px; margin-top: -72px; position: relative; z-index: 10; box-shadow: 0 8px 40px rgba(28,20,16,.10); }
        .seller-avatar-wrap { position: relative; flex-shrink: 0; }
        .seller-avatar { width: 88px; height: 88px; border-radius: 18px; background: var(--secondary); border: 3px solid #fff; box-shadow: 0 4px 16px rgba(0,0,0,.12); object-fit: cover; }
        .verified-ring { position: absolute; bottom: -4px; right: -4px; width: 26px; height: 26px; border-radius: 50%; background: var(--primary); border: 2px solid #fff; display: flex; align-items: center; justify-content: center; font-size: 11px; color: #fff; }
        .seller-meta { flex: 1; min-width: 0; }
        .seller-badge { display: inline-flex; align-items: center; gap: 5px; background: var(--warm); border: 1px solid var(--secondary); border-radius: 99px; padding: 4px 12px; font-size: 10px; font-weight: 800; color: var(--primary); letter-spacing: .06em; text-transform: uppercase; margin-bottom: 8px; }
        .seller-name { font-size: 24px; font-weight: 800; letter-spacing: -.02em; line-height: 1.1; }
        .seller-tagline { font-size: 13px; color: var(--muted); margin-top: 4px; font-style: italic; }
        .seller-loc { display: flex; align-items: center; gap: 5px; font-size: 12px; color: var(--muted); font-weight: 600; margin-top: 6px; }

        /* stats inline */
        .seller-stats { display: flex; gap: 24px; margin-top: 16px; flex-wrap: wrap; }
        .stat-item { text-align: center; }
        .stat-num  { font-size: 20px; font-weight: 800; color: var(--ink); }
        .stat-lbl  { font-size: 9px; font-weight: 800; text-transform: uppercase; letter-spacing: .12em; color: var(--muted); margin-top: 1px; }
        .stat-divider { width: 1px; background: var(--border); align-self: stretch; }

        /* CTA buttons */
        .cta-group { display: flex; flex-direction: column; gap: 10px; flex-shrink: 0; }
        .btn-primary { display: inline-flex; align-items: center; justify-content: center; gap: 8px; background: var(--primary); color: #fff; border: none; border-radius: 12px; padding: 13px 22px; font-size: 12px; font-weight: 800; cursor: pointer; transition: background .2s, transform .15s; text-decoration: none; }
        .btn-primary:hover { background: var(--bark); transform: translateY(-1px); }
        .btn-outline { display: inline-flex; align-items: center; justify-content: center; gap: 8px; background: transparent; color: var(--primary); border: 1.5px solid var(--primary); border-radius: 12px; padding: 12px 22px; font-size: 12px; font-weight: 800; cursor: pointer; transition: background .2s, color .2s; text-decoration: none; }
        .btn-outline:hover { background: var(--primary); color: #fff; }

        /* ── TAB NAV ── */
        .tab-bar { max-width: 1200px; margin: 0 auto; padding: 0 24px; display: flex; gap: 0; border-bottom: 1px solid var(--border); margin-top: 24px; }
        .tab-btn { padding: 14px 20px; font-size: 12px; font-weight: 700; letter-spacing: .06em; text-transform: uppercase; color: var(--muted); background: none; border: none; border-bottom: 2px solid transparent; margin-bottom: -1px; cursor: pointer; transition: color .15s, border-color .15s; }
        .tab-btn.active  { color: var(--primary); border-bottom-color: var(--primary); }
        .tab-btn:hover:not(.active) { color: var(--ink); }

        /* ── CONTENT ── */
        .content-wrap { max-width: 1200px; margin: 0 auto; padding: 40px 24px 80px; }
        .section-label { font-size: 10px; font-weight: 800; letter-spacing: .16em; text-transform: uppercase; color: var(--primary); margin-bottom: 6px; }

        /* ── PRODUCT GRID ── */
        .product-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 20px; }
        .product-card { border-radius: 16px; overflow: hidden; border: 1px solid var(--border); background: #fff; transition: box-shadow .25s, transform .25s; cursor: pointer; text-decoration: none; color: var(--ink); }
        .product-card:hover { box-shadow: 0 12px 36px rgba(181,115,58,.12); transform: translateY(-3px); }
        .product-img-wrap { position: relative; aspect-ratio: 1/1; overflow: hidden; background: var(--warm); }
        .product-img-wrap img { width: 100%; height: 100%; object-fit: cover; transition: transform .5s ease; }
        .product-card:hover .product-img-wrap img { transform: scale(1.06); }
        .prod-info { padding: 14px 16px; }
        .prod-name { font-size: 13.5px; font-weight: 700; margin-bottom: 6px; line-height: 1.3; }
        .prod-price-row { display: flex; align-items: center; gap: 7px; margin-bottom: 8px; }
        .prod-price { font-size: 15px; font-weight: 800; color: var(--primary); }

        /* ── STORE BANNER ── */
        .store-banner { background: var(--ink); border-radius: 18px; padding: 36px 44px; display: flex; align-items: center; justify-content: space-between; gap: 28px; margin-bottom: 32px; position: relative; overflow: hidden; }
        .store-banner::before { content: ''; position: absolute; inset: 0; background: radial-gradient(ellipse 60% 90% at 90% 50%, rgba(181,115,58,.25), transparent 70%); pointer-events: none; }
        .store-banner h2 { font-size: 26px; font-weight: 800; color: #fff; line-height: 1.2; }
        .store-banner p  { font-size: 13px; color: rgba(255,255,255,.55); margin-top: 6px; max-width: 380px; }

        /* filter row */
        .filter-row { display: flex; align-items: center; justify-content: space-between; margin-bottom: 24px; flex-wrap: wrap; gap: 12px; }
        .cat-chips { display: flex; gap: 8px; flex-wrap: wrap; }
        .cat-chip { border: 1.5px solid var(--border); border-radius: 99px; padding: 6px 14px; font-size: 11px; font-weight: 700; color: var(--muted); cursor: pointer; background: #fff; transition: border-color .15s, color .15s, background .15s; }
        .cat-chip.active, .cat-chip:hover { border-color: var(--primary); color: var(--primary); }
        .cat-chip.active { background: var(--primary); color: #fff; }

        /* fade up animation */
        @keyframes fadeUp { from{opacity:0;transform:translateY(16px)} to{opacity:1;transform:none} }
        .fade-up { animation: fadeUp .5s ease both; }
        .d1{animation-delay:.06s}.d2{animation-delay:.12s}.d3{animation-delay:.18s}.d4{animation-delay:.24s}
    </style>
</head>
<body>

<!-- ══════════════ NAVBAR (Minimalist Version) ══════════════ -->
<header class="bg-white border-b border-gray-100 sticky top-0 z-50">
    <div style="max-width:1200px; margin:0 auto;" class="flex justify-between items-center py-4 px-6">
        <div class="flex items-center space-x-8 flex-1">
            <a href="{{ route('customer.homepage') }}" class="text-2xl font-black tracking-tighter uppercase text-primary hover:opacity-80 transition-all">
                {{ $site_name }}
            </a>
            <div class="hidden lg:flex items-center bg-gray-50 border border-gray-100 rounded-md px-4 py-2 w-full max-w-[180px] focus-within:bg-white focus-within:border-primary/30 transition-all">
                <i class="fa-solid fa-magnifying-glass text-gray-400 text-[10px] mr-2"></i>
                <input type="text" placeholder="Search..." class="bg-transparent border-none outline-none text-[10px] w-full placeholder:text-gray-400">
            </div>
        </div>
        <nav class="hidden md:flex items-center space-x-10 text-[13px] font-medium text-gray-500 tracking-wide">
            <a href="{{ route('customer.catalog') }}" class="hover:text-primary transition-all">Collections</a>
            <a href="{{ route('customer.designers') }}" class="hover:text-primary transition-all">Designers</a>
            <a href="{{ route('customer.design-lab') }}" class="hover:text-primary transition-all">AI Studio</a>
        </nav>
        <div class="flex items-center space-x-6 flex-1 justify-end">
            <a href="{{ route('customer.cart') }}" class="text-primary hover:scale-110 transition-transform">
                <i class="fa-solid fa-bag-shopping text-lg"></i>
            </a>
        </div>
    </div>
</header>

<!-- ══════════════ COVER ══════════════ -->
<div class="cover-wrap">
    <!-- Gambar Cover Dinamis (Ganti dengan default jika belum punya kolom cover) -->
    <img src="https://images.unsplash.com/photo-1586023492125-27b2c045efd7?w=1400" class="cover-img" alt="Store Cover">
    <div class="cover-overlay"></div>
    <div class="cover-grain"></div>
    <!-- Breadcrumb over cover -->
    <div style="position:absolute; top:20px; left:50%; transform:translateX(-50%); max-width:1200px; width:100%; padding:0 24px;">
        <div style="display:flex; align-items:center; gap:8px; font-size:11px; font-weight:600; color:rgba(255,255,255,.6);">
            <a href="{{ route('customer.homepage') }}" style="color:rgba(255,255,255,.6); text-decoration:none;">Home</a>
            <i class="fa-solid fa-chevron-right" style="font-size:8px;"></i>
            <a href="{{ route('customer.designers') }}" style="color:rgba(255,255,255,.6); text-decoration:none;">Sellers</a>
            <i class="fa-solid fa-chevron-right" style="font-size:8px;"></i>
            <span style="color:#fff;">{{ $seller->store_name ?? $seller->user->full_name }}</span>
        </div>
    </div>
</div>

<!-- ══════════════ IDENTITY CARD ══════════════ -->
<div class="identity-wrap fade-up d1">
    <div class="identity-card">

        <!-- Avatar -->
        <div class="seller-avatar-wrap">
            <img src="https://api.dicebear.com/7.x/avataaars/svg?seed={{ urlencode($seller->user->username) }}" class="seller-avatar" alt="Avatar">
            <div class="verified-ring" title="Verified Artisan">
                <i class="fa-solid fa-check" style="font-size:9px;"></i>
            </div>
        </div>

        <!-- Meta -->
        <div class="seller-meta">
            <div class="seller-badge">
                <i class="fa-solid fa-medal" style="font-size:9px;"></i> Verified Artisan
            </div>
            <h1 class="seller-name">{{ $seller->store_name ?? $seller->user->full_name }}</h1>
            <p class="seller-tagline">"Crafting spaces where material meets memory."</p>
            <div class="seller-loc">
                <i class="fa-solid fa-location-dot" style="font-size:10px; color:var(--primary);"></i>
                Indonesia
                &nbsp;·&nbsp;
                <i class="fa-regular fa-calendar" style="font-size:10px;"></i>
                Since {{ $seller->created_at->format('Y') }}
                &nbsp;·&nbsp;
                <span style="color:#22C55E; font-weight:800;">
                    <span style="display:inline-block;width:7px;height:7px;border-radius:50%;background:#22C55E;margin-right:4px;vertical-align:middle;"></span>
                    Online Now
                </span>
            </div>

            <!-- Inline stats -->
            <div class="seller-stats">
                <div class="stat-item">
                    <div class="stat-num" style="color:var(--primary);">4.9 ★</div>
                    <div class="stat-lbl">Store Rating</div>
                </div>
                <div class="stat-divider"></div>
                <div class="stat-item">
                    <div class="stat-num">{{ $totalProducts }}</div>
                    <div class="stat-lbl">Products</div>
                </div>
                <div class="stat-divider"></div>
                <div class="stat-item">
                    <div class="stat-num">< 1 hour</div>
                    <div class="stat-lbl">Response Time</div>
                </div>
            </div>
        </div>

        <!-- CTA -->
        <div class="cta-group">
            <a href="{{ route('customer.riwayat-chat') }}" class="btn-primary">
                <i class="fa-regular fa-message" style="font-size:12px;"></i>
                Message Seller
            </a>
        </div>

    </div>
</div>

<!-- ══════════════ TABS ══════════════ -->
<div class="tab-bar fade-up d2">
    <button class="tab-btn active" onclick="switchTab(this,'products')">
        <i class="fa-solid fa-grid-2" style="font-size:11px; margin-right:6px;"></i>Products
    </button>
</div>

<!-- ══════════════ CONTENT ══════════════ -->
<div class="content-wrap">

    <!-- ── PRODUCTS TAB ── -->
    <div id="tab-products">

        <!-- Store banner -->
        <div class="store-banner fade-up d3">
            <div style="position:relative;z-index:1;">
                <p style="font-size:10px;font-weight:800;letter-spacing:.14em;text-transform:uppercase;color:rgba(255,255,255,.4);margin-bottom:8px;">{{ $seller->store_name ?? $seller->user->full_name }}</p>
                <h2 class="serif">Objects of enduring<br><em>character.</em></h2>
                <p>{{ $totalProducts }} handcrafted pieces. Sustainably sourced. Made to order.</p>
            </div>
        </div>

        <!-- Product grid Dinamis -->
        <div class="product-grid">
            @forelse($products as $pi => $p)
            @php $delay = 300 + ($pi * 60); @endphp
            <a href="{{ route('customer.product-detail', $p->id) }}" class="product-card fade-up" style="animation-delay:{{ $delay }}ms;">
                <div class="product-img-wrap">
                    <img src="{{ $p->images->first()->img_url ?? 'https://via.placeholder.com/600' }}" alt="{{ $p->name }}">
                </div>
                <div class="prod-info">
                    <div class="prod-name">{{ $p->name }}</div>
                    <div class="prod-price-row">
                        <span class="prod-price">Rp {{ number_format($p->price, 0, ',', '.') }}</span>
                    </div>
                </div>
            </a>
            @empty
            <div class="col-span-4 text-center py-10 text-gray-400">
                Toko ini belum mengunggah produk apapun.
            </div>
            @endforelse
        </div>

    </div>

</div><!-- /content-wrap -->

<!-- ══════════════ FOOTER ══════════════ -->
<footer class="bg-primary text-white py-12 px-6 mt-12">
    <div class="max-w-6xl mx-auto text-center">
        <p class="text-sm">©️ 2026 DECOR MARKETPLACE. ALL RIGHTS RESERVED.</p>
    </div>
</footer>

<script>
function switchTab(btn, id) {
    ['products'].forEach(t => {
        let el = document.getElementById('tab-' + t);
        if(el) el.style.display = 'none';
    });
    document.getElementById('tab-' + id).style.display = 'block';
    document.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('active'));
    btn.classList.add('active');
}
</script>
</body>
</html>