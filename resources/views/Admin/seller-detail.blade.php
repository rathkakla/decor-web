<?php
$current_page = basename($_SERVER['PHP_SELF']);
$current_path = str_replace('.php', '', $current_page);
if ($current_path == "seller-detail") {
    $current_path = "seller-monitor";
}

$admin_name = "Alex Rivera";
$admin_role = "SUPER ADMIN";

$menu_items = [
    ["label" => "Dashboard",          "path" => "dashboard",          "icon" => "grid"],
    ["label" => "User Management",    "path" => "user-management",    "icon" => "users"],
    ["label" => "Seller Monitor",     "path" => "seller-monitor",     "icon" => "shopping-bag"],
    ["label" => "Designer Monitor",   "path" => "designer-monitor",   "icon" => "pen-tool"],
    ["label" => "Seller Support",     "path" => "seller-support",     "icon" => "headphones"],
    ["label" => "Designer Support",   "path" => "designer-support",   "icon" => "pen-tool"],
    ["label" => "Customer Support",   "path" => "customer-support",   "icon" => "message-circle"],
    ["label" => "Product Validation", "path" => "product-validation", "icon" => "check-circle"],
    ["label" => "Portofolio Validation", "path" => "portofolio-validation", "icon" => "image"],
];

$seller = [
    "id"             => "SEL-042",
    "name"           => "Studio Archi",
    "owner"          => "Marco Verratti",
    "join_date"      => "12 Jan 2024",
    "rating"         => 2.1,
    "total_sales"    => "Rp 45.200.000",
    "admin_fee_paid" => "Rp 2.260.000",
    "return_rate"    => "24%",
    "response_time"  => "14 Hours",
    "transactions"   => 124,
    "status"         => "Warning",
    "spec"           => "Furniture & Home Décor",
];

$recent_reviews = [
    [
        "user"    => "Anisa R.",
        "rating"  => 1,
        "comment" => "Barang sampai dalam keadaan lecet, seller sangat lambat merespon chat di workspace.",
        "date"    => "Yesterday",
        "avatar"  => "https://i.pravatar.cc/150?u=anisa",
    ],
    [
        "user"    => "Budi H.",
        "rating"  => 2,
        "comment" => "Kualitas kayu tidak sesuai dengan foto katalog. Kecewa dengan pelayanan.",
        "date"    => "3 days ago",
        "avatar"  => "https://i.pravatar.cc/150?u=budi",
    ],
];

$seller_products = [
    [
        "id"       => "PRD-101",
        "name"     => "Mid-Century Sofa",
        "category" => "Furniture",
        "price"    => "Rp 4.500.000",
        "stock"    => 5,
        "sold"     => 38,
        "rating"   => 2.3,
        "status"   => "Active",
        "image"    => "https://images.unsplash.com/photo-1555041469-a586c61ea9bc?w=800&q=80",
        "desc"     => "Sofa bergaya mid-century modern dengan rangka kayu walnut solid dan bantal seat busa high-density. Kain pelapis tersedia dalam pilihan warna earthy tone. Cocok untuk ruang keluarga minimalis.",
        "weight"   => "35 kg",
        "dimension"=> "220 × 85 × 78 cm",
        "material" => "Kayu Walnut, Busa HD, Kain Linen",
    ],
    [
        "id"       => "PRD-102",
        "name"     => "Walnut Bookshelf",
        "category" => "Storage",
        "price"    => "Rp 3.200.000",
        "stock"    => 2,
        "sold"     => 15,
        "rating"   => 3.1,
        "status"   => "Active",
        "image"    => "https://images.unsplash.com/photo-1594938298603-c8148c4b4d93?w=800&q=80",
        "desc"     => "Rak buku minimalis dengan material kayu walnut asli berfinishing natural oil. Memiliki 5 tingkat rak dengan kapasitas beban hingga 20 kg per rak. Desain terbuka yang elegan.",
        "weight"   => "22 kg",
        "dimension"=> "90 × 30 × 180 cm",
        "material" => "Kayu Walnut Solid, Natural Oil Finish",
    ],
    [
        "id"       => "PRD-103",
        "name"     => "Rattan Side Table",
        "category" => "Table",
        "price"    => "Rp 1.850.000",
        "stock"    => 0,
        "sold"     => 22,
        "rating"   => 1.8,
        "status"   => "Out of Stock",
        "image"    => "https://images.unsplash.com/photo-1530018607912-eff2df114fbe?w=800&q=80",
        "desc"     => "Meja samping berbahan rotan alami dengan kaki besi matte black. Kombinasi bahan organik dan metal modern memberikan nuansa bohemian kontemporer. Tersedia dalam 2 ukuran.",
        "weight"   => "4.5 kg",
        "dimension"=> "45 × 45 × 55 cm",
        "material" => "Rotan Alami, Besi Powder Coat",
    ],
    [
        "id"       => "PRD-104",
        "name"     => "Teak Dining Chair",
        "category" => "Chair",
        "price"    => "Rp 2.100.000",
        "stock"    => 12,
        "sold"     => 49,
        "rating"   => 2.0,
        "status"   => "Active",
        "image"    => "https://images.unsplash.com/photo-1567538096630-e0c55bd6374c?w=800&q=80",
        "desc"     => "Kursi makan dari kayu jati grade A dengan finishing natural. Desain ergonomis dengan sandaran yang nyaman untuk penggunaan sehari-hari. Dapat menopang berat hingga 120 kg.",
        "weight"   => "8 kg",
        "dimension"=> "48 × 52 × 88 cm",
        "material" => "Kayu Jati Grade A, Finishing Natural",
    ],
    [
        "id"       => "PRD-105",
        "name"     => "Marble Coffee Table",
        "category" => "Table",
        "price"    => "Rp 6.750.000",
        "stock"    => 1,
        "sold"     => 8,
        "rating"   => 4.2,
        "status"   => "Active",
        "image"    => "https://images.unsplash.com/photo-1618220179428-22790b461013?w=800&q=80",
        "desc"     => "Meja kopi mewah dengan top permukaan marmer putih Carrara asli dan kaki stainless steel gold-plated. Statement piece yang sempurna untuk ruang tamu premium. Setiap unit memiliki pola marmer yang unik.",
        "weight"   => "28 kg",
        "dimension"=> "120 × 60 × 42 cm",
        "material" => "Marmer Carrara, Stainless Steel Gold-Plated",
    ],
    [
        "id"       => "PRD-106",
        "name"     => "Linen Throw Pillow Set",
        "category" => "Decor",
        "price"    => "Rp 480.000",
        "stock"    => 30,
        "sold"     => 61,
        "rating"   => 3.5,
        "status"   => "Active",
        "image"    => "https://images.unsplash.com/photo-1540574163026-643ea20ade25?w=800&q=80",
        "desc"     => "Set 2 bantal dekorasi berbahan linen premium dengan isian fiber dakron. Hadir dalam berbagai pilihan warna earth tone yang cocok untuk berbagai gaya interior. Sarung bantal dapat dilepas dan dicuci.",
        "weight"   => "0.8 kg",
        "dimension"=> "45 × 45 cm (per bantal)",
        "material" => "Linen Premium, Dakron Fill",
    ],
];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Seller Detail — <?= $seller['name'] ?> | DECOR Admin</title>
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
        .breadcrumb { display: flex; align-items: center; gap: 6px; margin-bottom: 24px; animation: fadeUp 0.3s ease both; }
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
            animation: fadeUp 0.4s ease 0.05s both;
            position: relative; overflow: hidden;
        }
        .hero-card::before { content: ''; position: absolute; top: 0; left: 0; right: 0; height: 4px; background: var(--warning); }
        .hero-top { display: flex; align-items: flex-start; justify-content: space-between; gap: 20px; }
        .hero-left { display: flex; align-items: center; gap: 18px; }
        .shop-icon { width: 64px; height: 64px; border-radius: 16px; background: var(--primary-light); border: 2px solid var(--primary-muted); display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
        .shop-icon svg { width: 28px; height: 28px; stroke: var(--primary); }
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
        .pill-warning { background: var(--warning-bg); color: var(--warning); border: 1px solid #F5CBA7; }
        .hero-actions { display: flex; gap: 8px; margin-top: 4px; }
        .btn-sm { display: inline-flex; align-items: center; gap: 5px; padding: 7px 14px; border-radius: 8px; font-size: 11px; font-weight: 700; border: 1.5px solid var(--border); background: var(--surface); color: var(--text-soft); cursor: pointer; transition: 0.15s; font-family: inherit; }
        .btn-sm:hover { border-color: var(--primary-muted); color: var(--primary); background: var(--primary-light); }
        .btn-sm svg { width: 12px; height: 12px; }

        /* ─── METRICS STRIP ─── */
        .metrics-strip { display: grid; grid-template-columns: repeat(5, 1fr); gap: 1px; background: var(--border-soft); border: 1px solid var(--border-soft); border-radius: 10px; overflow: hidden; margin-top: 24px; }
        .metric-cell { background: var(--surface-2); padding: 14px 18px; }
        .metric-val { font-size: 16px; font-weight: 800; color: var(--text); }
        .metric-val.bad { color: var(--danger); }
        .metric-val.good { color: var(--success); }
        .metric-val.primary { color: var(--primary); }
        .metric-lbl { font-size: 9px; font-weight: 700; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.05em; margin-top: 3px; }

        /* ─── LAYOUT ─── */
        .layout { display: grid; grid-template-columns: 1fr 300px; gap: 20px; animation: fadeUp 0.4s ease 0.12s both; }

        /* ─── PANEL ─── */
        .panel { background: var(--surface); border: 1px solid var(--border); border-radius: var(--radius); box-shadow: var(--shadow); overflow: hidden; margin-bottom: 20px; }
        .panel-header { padding: 16px 22px; border-bottom: 1px solid var(--border-soft); display: flex; align-items: center; justify-content: space-between; }
        .panel-title { font-size: 13px; font-weight: 800; color: var(--text); display: flex; align-items: center; gap: 8px; }
        .panel-title svg { width: 14px; height: 14px; stroke: var(--primary); }
        .panel-body { padding: 22px; }
        .panel-badge { font-size: 9px; font-weight: 800; padding: 3px 8px; border-radius: 5px; background: var(--danger-bg); color: var(--danger); text-transform: uppercase; }
        .panel-badge-neutral { font-size: 9px; font-weight: 800; padding: 3px 8px; border-radius: 5px; background: var(--primary-light); color: var(--primary); text-transform: uppercase; }

        /* ─── REVIEWS ─── */
        .review-item { display: flex; gap: 12px; padding: 16px 0; border-bottom: 1px solid var(--border-soft); }
        .review-item:last-child { border-bottom: none; padding-bottom: 0; }
        .review-avatar { width: 36px; height: 36px; border-radius: 10px; object-fit: cover; border: 1px solid var(--border-soft); flex-shrink: 0; }
        .review-name { font-size: 12px; font-weight: 700; color: var(--text); }
        .review-date { font-size: 9px; color: var(--text-muted); font-weight: 600; margin-left: 6px; }
        .star-row { display: flex; gap: 2px; margin: 4px 0 8px; }
        .star { width: 10px; height: 10px; }
        .review-text { font-size: 12px; color: var(--text-soft); line-height: 1.65; background: var(--surface-2); padding: 10px 14px; border-radius: 10px; border-left: 3px solid var(--primary-muted); font-style: italic; }

        /* ─── PRODUCTS GRID ─── */
        .products-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 12px; }
        .prod-card { border: 1px solid var(--border-soft); border-radius: 12px; overflow: hidden; background: var(--surface); transition: transform 0.15s, box-shadow 0.15s; position: relative; cursor: pointer; }
        .prod-card:hover { transform: translateY(-2px); box-shadow: var(--shadow-card); }
        .prod-card-img-wrap { position: relative; height: 130px; overflow: hidden; }
        .prod-card-img { width: 100%; height: 100%; object-fit: cover; display: block; transition: transform 0.4s; }
        .prod-card:hover .prod-card-img { transform: scale(1.05); }
        .prod-card-hover-overlay { position: absolute; inset: 0; background: rgba(28,20,16,0.4); display: flex; align-items: center; justify-content: center; opacity: 0; transition: opacity 0.2s; }
        .prod-card:hover .prod-card-hover-overlay { opacity: 1; }
        .prod-card-hover-overlay span { color: #fff; font-size: 11px; font-weight: 700; display: flex; align-items: center; gap: 5px; background: rgba(255,255,255,0.15); backdrop-filter: blur(4px); padding: 6px 12px; border-radius: 8px; border: 1px solid rgba(255,255,255,0.3); }
        .prod-card-hover-overlay span svg { width: 12px; height: 12px; stroke: #fff; }
        .prod-card-body { padding: 12px 14px; }
        .prod-card-id { font-family: 'DM Mono', monospace; font-size: 9px; color: var(--primary); font-weight: 500; }
        .prod-card-name { font-size: 12px; font-weight: 800; color: var(--text); margin: 3px 0 2px; line-height: 1.35; }
        .prod-card-cat { display: inline-flex; padding: 2px 7px; border-radius: 4px; font-size: 8px; font-weight: 800; text-transform: uppercase; background: var(--primary-light); color: var(--primary); margin-bottom: 8px; }
        .prod-card-price { font-size: 13px; font-weight: 800; color: var(--text); }
        .prod-card-meta { display: flex; justify-content: space-between; align-items: center; margin-top: 8px; padding-top: 8px; border-top: 1px solid var(--border-soft); }
        .prod-card-stat { font-size: 9px; color: var(--text-muted); font-weight: 600; display: flex; align-items: center; gap: 3px; }
        .prod-card-stat svg { width: 9px; height: 9px; }
        .prod-card-stat.bad { color: var(--danger); }
        .prod-card-status { position: absolute; top: 8px; right: 8px; font-size: 8px; font-weight: 800; text-transform: uppercase; padding: 3px 7px; border-radius: 5px; }
        .prod-card-status.active { background: var(--success-bg); color: var(--success); }
        .prod-card-status.out { background: var(--danger-bg); color: var(--danger); }
        .prod-star-mini { display: flex; align-items: center; gap: 2px; }
        .prod-star-mini svg { width: 9px; height: 9px; }

        /* ─── SUSPEND / MODERATION ─── */
        .dur-grid { display: grid; grid-template-columns: repeat(3,1fr); gap: 8px; margin-top: 4px; }
        .dur-btn { padding: 10px 6px; border-radius: 10px; border: 1.5px solid var(--border); background: var(--surface-2); font-family: inherit; font-size: 12px; font-weight: 700; color: var(--text-soft); cursor: pointer; transition: 0.15s; text-align: center; }
        .dur-btn:hover { border-color: var(--warning); color: var(--warning); background: var(--warning-bg); }
        .dur-btn.selected { border-color: var(--warning); background: var(--warning-bg); color: var(--warning); }
        .dur-days { font-size: 18px; font-weight: 800; display: block; }
        .dur-label { font-size: 9px; font-weight: 600; color: inherit; opacity: 0.8; text-transform: uppercase; letter-spacing: 0.05em; }
        .mod-section { margin-bottom: 18px; }
        .mod-label { font-size: 9px; font-weight: 800; text-transform: uppercase; letter-spacing: 0.12em; color: var(--text-muted); margin-bottom: 7px; display: block; }
        .mod-textarea { width: 100%; height: 90px; border: 1.5px solid var(--border); border-radius: 10px; padding: 12px 14px; font-family: inherit; font-size: 12px; color: var(--text); background: var(--surface-2); resize: none; outline: none; transition: 0.15s; }
        .mod-textarea:focus { border-color: var(--primary-muted); background: var(--primary-light); }
        .mod-textarea::placeholder { color: var(--text-muted); }
        .action-btns { display: flex; flex-direction: column; gap: 8px; }
        .btn-action-full { width: 100%; padding: 11px 16px; border-radius: 10px; font-size: 12px; font-weight: 700; cursor: pointer; display: flex; align-items: center; gap: 8px; border: 1.5px solid; transition: 0.15s; font-family: inherit; }
        .btn-action-full svg { width: 13px; height: 13px; }
        .btn-action-full.warning { background: var(--warning-bg); color: var(--warning); border-color: #F5CBA7; }
        .btn-action-full.warning:hover { background: var(--warning); color: #fff; }
        .btn-action-full.danger { background: var(--danger-bg); color: var(--danger); border-color: #F5C6C1; }
        .btn-action-full.danger:hover { background: var(--danger); color: #fff; }
        .btn-action-full.success { background: var(--success-bg); color: var(--success); border-color: #B8DEC8; }
        .btn-action-full.success:hover { background: var(--success); color: #fff; }

        /* ─── PRODUCT LIGHTBOX ─── */
        .overlay { display: none; position: fixed; inset: 0; z-index: 2000; background: rgba(28,20,16,0.55); backdrop-filter: blur(6px); align-items: center; justify-content: center; }
        .overlay.show { display: flex; }

        .product-lightbox-box {
            background: var(--surface);
            border-radius: 20px;
            width: 820px;
            max-width: 95vw;
            max-height: 92vh;
            overflow: hidden;
            box-shadow: 0 32px 80px rgba(0,0,0,0.25);
            animation: slideUp 0.25s cubic-bezier(0.16,1,0.3,1) both;
            display: flex;
        }

        /* Left: image */
        .plb-left {
            width: 360px;
            flex-shrink: 0;
            position: relative;
            background: var(--surface-2);
        }
        .plb-left img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: block;
        }
        .plb-img-overlay {
            position: absolute;
            inset: 0;
            background: linear-gradient(to top, rgba(28,20,16,0.5) 0%, transparent 50%);
        }
        .plb-status-badge {
            position: absolute;
            top: 14px;
            left: 14px;
            font-size: 9px;
            font-weight: 800;
            text-transform: uppercase;
            padding: 4px 10px;
            border-radius: 6px;
            letter-spacing: 0.05em;
        }
        .plb-status-badge.active { background: var(--success-bg); color: var(--success); }
        .plb-status-badge.out    { background: var(--danger-bg);  color: var(--danger);  }
        .plb-bottom-info {
            position: absolute;
            bottom: 0; left: 0; right: 0;
            padding: 18px;
        }
        .plb-price-big {
            font-size: 22px;
            font-weight: 800;
            color: #fff;
            text-shadow: 0 1px 4px rgba(0,0,0,0.4);
            margin-bottom: 6px;
        }
        .plb-rating-row {
            display: flex;
            align-items: center;
            gap: 6px;
        }
        .plb-stars { display: flex; gap: 2px; }
        .plb-stars svg { width: 11px; height: 11px; }
        .plb-rating-num {
            font-size: 11px;
            font-weight: 700;
            color: rgba(255,255,255,0.85);
        }

        /* Right: details */
        .plb-right {
            flex: 1;
            overflow-y: auto;
            padding: 28px 28px 24px;
            display: flex;
            flex-direction: column;
        }
        .plb-top-row {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            margin-bottom: 16px;
        }
        .plb-id {
            font-family: 'DM Mono', monospace;
            font-size: 10px;
            color: var(--primary);
            font-weight: 500;
            background: var(--primary-light);
            border: 1px solid var(--primary-muted);
            padding: 3px 9px;
            border-radius: 5px;
            margin-bottom: 8px;
            display: inline-block;
        }
        .plb-title {
            font-size: 20px;
            font-weight: 800;
            color: var(--text);
            line-height: 1.25;
            margin-bottom: 6px;
        }
        .plb-cat {
            display: inline-flex;
            padding: 3px 9px;
            border-radius: 5px;
            font-size: 9px;
            font-weight: 800;
            text-transform: uppercase;
            background: var(--primary-light);
            color: var(--primary);
            letter-spacing: 0.05em;
        }
        .plb-close-btn {
            width: 32px;
            height: 32px;
            border-radius: 9px;
            border: 1.5px solid var(--border);
            background: var(--surface-2);
            cursor: pointer;
            color: var(--text-muted);
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            transition: 0.15s;
        }
        .plb-close-btn:hover { border-color: var(--border); background: var(--bg); color: var(--text); }
        .plb-close-btn svg { width: 14px; height: 14px; }

        .plb-divider { height: 1px; background: var(--border-soft); margin: 16px 0; }

        .plb-desc-label { font-size: 9px; font-weight: 800; text-transform: uppercase; letter-spacing: 0.12em; color: var(--text-muted); margin-bottom: 8px; display: block; }
        .plb-desc { font-size: 12.5px; color: var(--text-soft); line-height: 1.7; }

        .plb-specs {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 8px;
            margin-top: 16px;
        }
        .plb-spec-item {
            background: var(--surface-2);
            border: 1px solid var(--border-soft);
            border-radius: 10px;
            padding: 10px 12px;
        }
        .plb-spec-label { font-size: 9px; font-weight: 700; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 3px; }
        .plb-spec-val { font-size: 11.5px; font-weight: 700; color: var(--text); }

        .plb-stats-row {
            display: flex;
            gap: 10px;
            margin-top: 16px;
        }
        .plb-stat-pill {
            flex: 1;
            background: var(--surface-2);
            border: 1px solid var(--border-soft);
            border-radius: 10px;
            padding: 10px 12px;
            text-align: center;
        }
        .plb-stat-pill .val { font-size: 15px; font-weight: 800; }
        .plb-stat-pill .val.bad { color: var(--danger); }
        .plb-stat-pill .val.good { color: var(--success); }
        .plb-stat-pill .val.warn { color: var(--warning); }
        .plb-stat-pill .lbl { font-size: 9px; font-weight: 700; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.05em; margin-top: 2px; }

        .plb-actions {
            margin-top: auto;
            padding-top: 18px;
            border-top: 1px solid var(--border-soft);
            display: flex;
            gap: 8px;
        }
        .plb-btn { flex: 1; padding: 10px 14px; border-radius: 10px; font-size: 11px; font-weight: 700; cursor: pointer; display: flex; align-items: center; justify-content: center; gap: 6px; border: 1.5px solid; transition: 0.15s; font-family: inherit; }
        .plb-btn svg { width: 12px; height: 12px; }
        .plb-btn.danger  { background: var(--danger-bg);  color: var(--danger);  border-color: #F5C6C1; }
        .plb-btn.danger:hover  { background: var(--danger); color: #fff; }
        .plb-btn.warning { background: var(--warning-bg); color: var(--warning); border-color: #F5CBA7; }
        .plb-btn.warning:hover { background: var(--warning); color: #fff; }
        .plb-btn.neutral { background: var(--surface-2); color: var(--text-soft); border-color: var(--border); }
        .plb-btn.neutral:hover { background: var(--bg); color: var(--text); }

        /* ─── CONFIRM MODAL ─── */
        .confirm-overlay { display: none; position: fixed; inset: 0; z-index: 3000; background: rgba(28,20,16,0.5); backdrop-filter: blur(4px); align-items: center; justify-content: center; }
        .confirm-overlay.show { display: flex; }
        .confirm-box { background: var(--surface); border-radius: 18px; padding: 28px 30px; width: 380px; box-shadow: 0 24px 60px rgba(0,0,0,0.18); animation: slideUp 0.25s cubic-bezier(0.16,1,0.3,1) both; }
        .confirm-icon { width: 48px; height: 48px; border-radius: 14px; display: flex; align-items: center; justify-content: center; margin-bottom: 16px; }
        .confirm-icon svg { width: 22px; height: 22px; }
        .confirm-icon.warning { background: var(--warning-bg); } .confirm-icon.warning svg { stroke: var(--warning); }
        .confirm-icon.danger  { background: var(--danger-bg);  } .confirm-icon.danger  svg { stroke: var(--danger); }
        .confirm-title { font-size: 16px; font-weight: 800; color: var(--text); margin-bottom: 6px; }
        .confirm-desc  { font-size: 12px; color: var(--text-muted); line-height: 1.6; margin-bottom: 20px; }
        .confirm-chip { display: inline-flex; align-items: center; gap: 8px; background: var(--surface-2); border: 1px solid var(--border); border-radius: 8px; padding: 7px 12px; margin-bottom: 20px; }
        .confirm-chip .chip-icon { width: 28px; height: 28px; border-radius: 8px; background: var(--primary-light); display: flex; align-items: center; justify-content: center; }
        .confirm-chip .chip-icon svg { width: 13px; height: 13px; stroke: var(--primary); }
        .confirm-chip span { font-size: 12px; font-weight: 700; color: var(--text); }
        .confirm-btns { display: flex; gap: 8px; justify-content: flex-end; }
        .btn-cancel { padding: 9px 18px; border-radius: 9px; border: 1.5px solid var(--border); background: var(--surface); font-size: 11px; font-weight: 700; color: var(--text-soft); cursor: pointer; font-family: inherit; transition: 0.15s; }
        .btn-cancel:hover { background: var(--surface-2); }
        .btn-ok { padding: 9px 18px; border-radius: 9px; border: none; font-size: 11px; font-weight: 700; color: #fff; cursor: pointer; font-family: inherit; transition: 0.15s; }
        .btn-ok.warning { background: var(--warning); } .btn-ok.warning:hover { background: #8A3F05; }
        .btn-ok.danger  { background: var(--danger);  } .btn-ok.danger:hover  { background: #9B2335; }

        @keyframes fadeUp  { from { opacity: 0; transform: translateY(14px); } to { opacity: 1; transform: none; } }
        @keyframes slideUp { from { transform: translateY(20px); opacity: 0; } to { transform: none; opacity: 1; } }
    </style>
</head>
<body>

<!-- ══════════ SIDEBAR ══════════ -->
<aside>
    <div class="brand">
        <div class="brand-row">
            <div class="brand-icon"><i data-feather="layout"></i></div>
            <div><div class="brand-name">DECOR</div></div>
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

    <!-- Breadcrumb -->
    <div class="breadcrumb">
        <a href="dashboard"><i data-feather="home"></i> Dashboard</a>
        <span class="breadcrumb-sep">/</span>
        <a href="seller-monitor"><i data-feather="shopping-bag"></i> Seller Monitor</a>
        <span class="breadcrumb-sep">/</span>
        <span class="breadcrumb-current"><?= $seller['name'] ?></span>
    </div>

    <!-- Hero Card -->
    <div class="hero-card">
        <div class="hero-top">
            <div class="hero-left">
                <div class="shop-icon"><i data-feather="shopping-bag"></i></div>
                <div>
                    <div class="hero-name"><?= $seller['name'] ?></div>
                    <div class="hero-meta">
                        <span class="hero-id"><?= $seller['id'] ?></span>
                        <span class="hero-spec"><i data-feather="tag"></i><?= $seller['spec'] ?></span>
                        <span class="hero-joined"><i data-feather="calendar"></i>Joined <?= $seller['join_date'] ?></span>
                    </div>
                    <div style="margin-top: 6px; font-size: 11px; color: var(--text-muted); font-weight: 500;">
                        Owner: <strong style="color: var(--text-soft);"><?= $seller['owner'] ?></strong>
                    </div>
                </div>
            </div>
            <div class="hero-right">
                <span class="pill pill-warning"><i data-feather="alert-triangle"></i><?= $seller['status'] ?></span>
                <div class="hero-actions">
                    <button class="btn-sm" onclick="openChat()"><i data-feather="message-circle"></i> Kirim Pesan</button>
                </div>
            </div>
        </div>
        <div class="metrics-strip">
            <div class="metric-cell">
                <div class="metric-val primary"><?= $seller['total_sales'] ?></div>
                <div class="metric-lbl">Total Revenue</div>
            </div>
            <div class="metric-cell">
                <div class="metric-val primary"><?= $seller['admin_fee_paid'] ?></div>
                <div class="metric-lbl">Admin Fee</div>
            </div>
            <div class="metric-cell">
                <div class="metric-val"><?= $seller['transactions'] ?></div>
                <div class="metric-lbl">Transactions</div>
            </div>
            <div class="metric-cell">
                <div class="metric-val bad"><?= $seller['return_rate'] ?></div>
                <div class="metric-lbl">Return Rate</div>
            </div>
            <div class="metric-cell">
                <div class="metric-val bad">⭐ <?= $seller['rating'] ?></div>
                <div class="metric-lbl">Avg. Rating</div>
            </div>
        </div>
    </div>

    <!-- Layout -->
    <div class="layout">

        <!-- LEFT COLUMN -->
        <div>

            <!-- Products by Seller -->
            <div class="panel" style="animation: fadeUp 0.4s ease 0.16s both;">
                <div class="panel-header">
                    <div class="panel-title"><i data-feather="package"></i> Produk Dijual</div>
                    <span class="panel-badge-neutral"><?= count($seller_products) ?> Produk</span>
                </div>
                <div class="panel-body">
                    <div class="products-grid">
                        <?php foreach ($seller_products as $pi => $p):
                            $is_out = $p['stock'] === 0;
                            $rating_bad = $p['rating'] < 3.0;
                        ?>
                        <div class="prod-card" onclick="openProductDetail(<?= $pi ?>)">
                            <div class="prod-card-img-wrap">
                                <img src="<?= $p['image'] ?>" class="prod-card-img" alt="<?= $p['name'] ?>">
                                <div class="prod-card-hover-overlay">
                                    <span><i data-feather="eye"></i> Lihat Detail</span>
                                </div>
                                <span class="prod-card-status <?= $is_out ? 'out' : 'active' ?>">
                                    <?= $is_out ? 'Habis' : 'Aktif' ?>
                                </span>
                            </div>
                            <div class="prod-card-body">
                                <div class="prod-card-id"><?= $p['id'] ?></div>
                                <div class="prod-card-name"><?= $p['name'] ?></div>
                                <span class="prod-card-cat"><?= $p['category'] ?></span>
                                <div class="prod-card-price"><?= $p['price'] ?></div>
                                <div class="prod-card-meta">
                                    <div class="prod-card-stat <?= $rating_bad ? 'bad' : '' ?>">
                                        <div class="prod-star-mini">
                                            <?php
                                            $full  = floor($p['rating']);
                                            $empty = 5 - $full;
                                            for($i=0;$i<$full;$i++): ?>
                                            <svg viewBox="0 0 16 16" fill="<?= $rating_bad ? '#C0392B' : '#FFA000' ?>"><path d="M8 1l1.85 3.75L14 5.5l-3 2.92.71 4.13L8 10.4l-3.71 2.15.71-4.13L2 5.5l4.15-.75z"/></svg>
                                            <?php endfor; for($i=0;$i<$empty;$i++): ?>
                                            <svg viewBox="0 0 16 16" fill="#E8E2DB"><path d="M8 1l1.85 3.75L14 5.5l-3 2.92.71 4.13L8 10.4l-3.71 2.15.71-4.13L2 5.5l4.15-.75z"/></svg>
                                            <?php endfor; ?>
                                        </div>
                                        <?= $p['rating'] ?>
                                    </div>
                                    <div style="display: flex; gap: 10px;">
                                        <div class="prod-card-stat" title="Terjual">
                                            <i data-feather="shopping-cart"></i> <?= $p['sold'] ?>
                                        </div>
                                        <div class="prod-card-stat <?= $is_out ? 'bad' : '' ?>" title="Stok">
                                            <i data-feather="layers"></i> <?= $p['stock'] ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>

            <!-- Reviews -->
            <div class="panel" style="animation: fadeUp 0.4s ease 0.20s both;">
                <div class="panel-header">
                    <div class="panel-title"><i data-feather="message-square"></i> Complaints & Reviews</div>
                    <span class="panel-badge"><?= count($recent_reviews) ?> Keluhan</span>
                </div>
                <div class="panel-body">
                    <?php foreach ($recent_reviews as $r):
                        $stars_full  = $r['rating'];
                        $stars_empty = 5 - $stars_full;
                    ?>
                    <div class="review-item">
                        <img src="<?= $r['avatar'] ?>" class="review-avatar">
                        <div style="flex:1;">
                            <div>
                                <span class="review-name"><?= $r['user'] ?></span>
                                <span class="review-date"><?= $r['date'] ?></span>
                            </div>
                            <div class="star-row">
                                <?php for($i=0;$i<$stars_full;$i++): ?>
                                <svg class="star" viewBox="0 0 16 16" fill="#FFA000"><path d="M8 1l1.85 3.75L14 5.5l-3 2.92.71 4.13L8 10.4l-3.71 2.15.71-4.13L2 5.5l4.15-.75z"/></svg>
                                <?php endfor; for($i=0;$i<$stars_empty;$i++): ?>
                                <svg class="star" viewBox="0 0 16 16" fill="#E8E2DB"><path d="M8 1l1.85 3.75L14 5.5l-3 2.92.71 4.13L8 10.4l-3.71 2.15.71-4.13L2 5.5l4.15-.75z"/></svg>
                                <?php endfor; ?>
                            </div>
                            <div class="review-text">"<?= $r['comment'] ?>"</div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>

        </div>

        <!-- RIGHT COLUMN -->
        <div>
            <div class="panel" style="position: sticky; top: 24px; animation: fadeUp 0.4s ease 0.18s both;">
                <div class="panel-header">
                    <div class="panel-title"><i data-feather="shield"></i> Moderation Center</div>
                </div>
                <div class="panel-body">

                    <div class="mod-section">
                        <span class="mod-label">Audit Note</span>
                        <textarea class="mod-textarea" placeholder="Tulis catatan audit atau alasan tindakan…"></textarea>
                    </div>

                    <div class="mod-section">
                        <span class="mod-label">Durasi Suspend</span>
                        <div class="dur-grid">
                            <button class="dur-btn" onclick="selectDur(this, 7)">
                                <span class="dur-days">7</span>
                                <span class="dur-label">Hari</span>
                            </button>
                            <button class="dur-btn" onclick="selectDur(this, 14)">
                                <span class="dur-days">14</span>
                                <span class="dur-label">Hari</span>
                            </button>
                            <button class="dur-btn" onclick="selectDur(this, 30)">
                                <span class="dur-days">30</span>
                                <span class="dur-label">Hari</span>
                            </button>
                        </div>
                    </div>

                    <div class="mod-section">
                        <span class="mod-label">Tindakan</span>
                        <div class="action-btns">
                            <button class="btn-action-full warning" onclick="openConfirm('suspend')">
                                <i data-feather="pause-circle"></i>
                                <span id="suspendBtnLabel">Suspend Toko</span>
                            </button>
                            <button class="btn-action-full danger" onclick="openConfirm('ban')">
                                <i data-feather="slash"></i> Ban Permanen
                            </button>
                            <button class="btn-action-full success" onclick="openConfirm('clear')">
                                <i data-feather="check-circle"></i> Clear Warning
                            </button>
                        </div>
                    </div>

                    <div style="margin-top: 20px; padding-top: 16px; border-top: 1px solid var(--border-soft);">
                        <span class="mod-label">Info Toko</span>
                        <div style="display: flex; flex-direction: column; gap: 8px;">
                            <div style="display: flex; justify-content: space-between; font-size: 11px;">
                                <span style="color: var(--text-muted); font-weight: 600;">Response Time</span>
                                <span style="font-weight: 700; color: var(--danger);"><?= $seller['response_time'] ?></span>
                            </div>
                            <div style="display: flex; justify-content: space-between; font-size: 11px;">
                                <span style="color: var(--text-muted); font-weight: 600;">Return Rate</span>
                                <span style="font-weight: 700; color: var(--danger);"><?= $seller['return_rate'] ?></span>
                            </div>
                            <div style="display: flex; justify-content: space-between; font-size: 11px;">
                                <span style="color: var(--text-muted); font-weight: 600;">Rating</span>
                                <span style="font-weight: 700; color: var(--danger);">⭐ <?= $seller['rating'] ?></span>
                            </div>
                            <div style="display: flex; justify-content: space-between; font-size: 11px;">
                                <span style="color: var(--text-muted); font-weight: 600;">Total Produk</span>
                                <span style="font-weight: 700; color: var(--primary);"><?= count($seller_products) ?> item</span>
                            </div>
                            <div style="display: flex; justify-content: space-between; font-size: 11px;">
                                <span style="color: var(--text-muted); font-weight: 600;">Admin Fee</span>
                                <span style="font-weight: 700; color: var(--primary);"><?= $seller['admin_fee_paid'] ?></span>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>

    </div>
</main>

<!-- ══════════ PRODUCT LIGHTBOX ══════════ -->
<div id="productLightbox" class="overlay">
    <div class="product-lightbox-box">
        <!-- Left: Image -->
        <div class="plb-left">
            <img id="plbImg" src="" alt="">
            <div class="plb-img-overlay"></div>
            <span id="plbStatusBadge" class="plb-status-badge active">Aktif</span>
            <div class="plb-bottom-info">
                <div id="plbPrice" class="plb-price-big"></div>
                <div class="plb-rating-row">
                    <div id="plbStars" class="plb-stars"></div>
                    <span id="plbRatingNum" class="plb-rating-num"></span>
                </div>
            </div>
        </div>
        <!-- Right: Details -->
        <div class="plb-right">
            <div class="plb-top-row">
                <div>
                    <div id="plbId" class="plb-id"></div>
                    <div id="plbTitle" class="plb-title"></div>
                    <span id="plbCat" class="plb-cat"></span>
                </div>
                <button class="plb-close-btn" onclick="closeProductDetail()"><i data-feather="x"></i></button>
            </div>

            <div class="plb-divider"></div>

            <span class="plb-desc-label">Deskripsi Produk</span>
            <div id="plbDesc" class="plb-desc"></div>

            <div class="plb-specs" id="plbSpecs">
                <!-- injected by JS -->
            </div>

            <div class="plb-stats-row" id="plbStats">
                <!-- injected by JS -->
            </div>

            
               
                
            </div>
        </div>
    </div>
</div>

<!-- ══════════ CHAT MODAL ══════════ -->
<div id="chatOverlay" class="confirm-overlay">
    <div style="background: var(--surface); width: 460px; border-radius: 20px; overflow: hidden; box-shadow: 0 24px 60px rgba(0,0,0,0.2); animation: slideUp 0.25s cubic-bezier(0.16,1,0.3,1) both;">
        <div style="padding: 18px 22px; background: var(--primary); display: flex; align-items: center; justify-content: space-between;">
            <div style="display: flex; align-items: center; gap: 10px;">
                <div style="width: 34px; height: 34px; background: rgba(255,255,255,0.2); border-radius: 9px; display: flex; align-items: center; justify-content: center;">
                    <i data-feather="message-circle" style="width:15px;height:15px;stroke:#fff;"></i>
                </div>
                <div>
                    <div style="font-size: 13px; font-weight: 800; color: #fff;"><?= $seller['name'] ?></div>
                    <div style="font-size: 10px; color: rgba(255,255,255,0.65);">Kirim pesan ke seller</div>
                </div>
            </div>
            <button onclick="closeChat()" style="background: rgba(255,255,255,0.15); border: none; color: #fff; width: 28px; height: 28px; border-radius: 8px; cursor: pointer; font-size: 16px; display: flex; align-items: center; justify-content: center;">×</button>
        </div>
        <div id="chatWindow" style="height: 300px; padding: 18px; overflow-y: auto; background: var(--surface-2); display: flex; flex-direction: column; gap: 10px;">
            <div style="display: flex; flex-direction: column;">
                <div style="font-size: 9px; color: var(--text-muted); font-weight: 600; margin-bottom: 3px; padding: 0 3px;"><?= $seller['name'] ?> · Yesterday</div>
                <div style="max-width: 82%; padding: 10px 14px; border-radius: 14px; border-bottom-left-radius: 4px; background: var(--surface); border: 1px solid var(--border-soft); font-size: 12px; line-height: 1.55; color: var(--text);">Halo Admin, ada yang bisa saya tanyakan terkait akun kami?</div>
            </div>
            <div style="display: flex; flex-direction: column; align-items: flex-end;">
                <div style="font-size: 9px; color: var(--text-muted); font-weight: 600; margin-bottom: 3px; padding: 0 3px;">Alex Rivera · Yesterday</div>
                <div style="max-width: 82%; padding: 10px 14px; border-radius: 14px; border-bottom-right-radius: 4px; background: var(--primary); font-size: 12px; line-height: 1.55; color: #fff;">Halo, silakan sampaikan pertanyaannya.</div>
            </div>
        </div>
        <div style="padding: 14px 16px; border-top: 1px solid var(--border-soft); background: var(--surface); display: flex; gap: 8px;">
            <input type="text" id="chatInput" placeholder="Tulis pesan ke seller…"
                   onkeydown="if(event.key==='Enter') sendChat()"
                   style="flex:1; padding: 10px 14px; border: 1.5px solid var(--border); border-radius: 10px; font-family: inherit; font-size: 12px; color: var(--text); background: var(--surface-2); outline: none;">
            <button onclick="sendChat()" style="padding: 10px 16px; background: var(--primary); color: #fff; border: none; border-radius: 10px; font-size: 11px; font-weight: 700; cursor: pointer; display: flex; align-items: center; gap: 5px; font-family: inherit;">
                <i data-feather="send" style="width:13px;height:13px;"></i> Kirim
            </button>
        </div>
    </div>
</div>

<!-- ══════════ CONFIRM MODAL ══════════ -->
<div id="confirmOverlay" class="confirm-overlay">
    <div class="confirm-box">
        <div id="confirmIcon" class="confirm-icon warning">
            <i id="confirmIconSvg" data-feather="pause-circle"></i>
        </div>
        <div id="confirmTitle" class="confirm-title">Konfirmasi Tindakan</div>
        <div id="confirmDesc"  class="confirm-desc">Apakah kamu yakin ingin melanjutkan aksi ini?</div>
        <div class="confirm-chip">
            <div class="chip-icon"><i data-feather="shopping-bag"></i></div>
            <span><?= $seller['name'] ?> (<?= $seller['id'] ?>)</span>
        </div>
        <div class="confirm-btns">
            <button class="btn-cancel" onclick="closeConfirm()">Batal</button>
            <button id="btnOk" class="btn-ok warning" onclick="closeConfirm()">Konfirmasi</button>
        </div>
    </div>
</div>

<!-- Product data for JS -->
<script>
const productsData = <?= json_encode($seller_products) ?>;
</script>

<script>
feather.replace({ 'stroke-width': 2 });

/* ── Product Lightbox ── */
function openProductDetail(idx) {
    const p = productsData[idx];
    const isOut = p.stock === 0;
    const ratingBad = p.rating < 3.0;

    document.getElementById('plbImg').src = p.image;
    document.getElementById('plbImg').alt = p.name;
    document.getElementById('plbId').textContent    = p.id;
    document.getElementById('plbTitle').textContent = p.name;
    document.getElementById('plbCat').textContent   = p.category;
    document.getElementById('plbPrice').textContent = p.price;
    document.getElementById('plbDesc').textContent  = p.desc;

    // Status badge
    const badge = document.getElementById('plbStatusBadge');
    badge.className = 'plb-status-badge ' + (isOut ? 'out' : 'active');
    badge.textContent = isOut ? 'Stok Habis' : 'Aktif';

    // Stars
    const starsEl = document.getElementById('plbStars');
    starsEl.innerHTML = '';
    const full = Math.floor(p.rating);
    const empty = 5 - full;
    const starColor = ratingBad ? '#ef9a9a' : '#FFA000';
    for (let i = 0; i < full; i++) {
        starsEl.innerHTML += `<svg viewBox="0 0 16 16" fill="${starColor}"><path d="M8 1l1.85 3.75L14 5.5l-3 2.92.71 4.13L8 10.4l-3.71 2.15.71-4.13L2 5.5l4.15-.75z"/></svg>`;
    }
    for (let i = 0; i < empty; i++) {
        starsEl.innerHTML += `<svg viewBox="0 0 16 16" fill="rgba(255,255,255,0.3)"><path d="M8 1l1.85 3.75L14 5.5l-3 2.92.71 4.13L8 10.4l-3.71 2.15.71-4.13L2 5.5l4.15-.75z"/></svg>`;
    }
    document.getElementById('plbRatingNum').textContent = p.rating + ' / 5.0';

    // Specs grid
    document.getElementById('plbSpecs').innerHTML = `
        <div class="plb-spec-item">
            <div class="plb-spec-label">Material</div>
            <div class="plb-spec-val">${p.material}</div>
        </div>
        <div class="plb-spec-item">
            <div class="plb-spec-label">Dimensi</div>
            <div class="plb-spec-val">${p.dimension}</div>
        </div>
        <div class="plb-spec-item">
            <div class="plb-spec-label">Berat</div>
            <div class="plb-spec-val">${p.weight}</div>
        </div>
        <div class="plb-spec-item">
            <div class="plb-spec-label">Kategori</div>
            <div class="plb-spec-val">${p.category}</div>
        </div>
    `;

    // Stats row
    const soldClass  = 'good';
    const stockClass = isOut ? 'bad' : 'good';
    const ratingClass = ratingBad ? 'bad' : 'good';
    document.getElementById('plbStats').innerHTML = `
        <div class="plb-stat-pill">
            <div class="val ${soldClass}">${p.sold}</div>
            <div class="lbl">Terjual</div>
        </div>
        <div class="plb-stat-pill">
            <div class="val ${stockClass}">${p.stock}</div>
            <div class="lbl">Stok</div>
        </div>
        <div class="plb-stat-pill">
            <div class="val ${ratingClass}">⭐ ${p.rating}</div>
            <div class="lbl">Rating</div>
        </div>
    `;

    document.getElementById('productLightbox').classList.add('show');
    feather.replace({ 'stroke-width': 2 });
}
function closeProductDetail() {
    document.getElementById('productLightbox').classList.remove('show');
}
document.getElementById('productLightbox').addEventListener('click', e => {
    if (e.target === document.getElementById('productLightbox')) closeProductDetail();
});

/* ── Suspend duration picker ── */
let selectedDays = null;
function selectDur(btn, days) {
    document.querySelectorAll('.dur-btn').forEach(b => b.classList.remove('selected'));
    btn.classList.add('selected');
    selectedDays = days;
    document.getElementById('suspendBtnLabel').textContent = 'Suspend ' + days + ' Hari';
}

/* ── Confirm modal ── */
const modalConfigs = {
    suspend: { icon: 'pause-circle', type: 'warning', title: 'Suspend Toko',  desc: '' },
    ban:     { icon: 'slash',        type: 'danger',  title: 'Ban Permanen',  desc: 'Aksi ini permanen. Toko dan akun seller akan diblokir dari seluruh platform.' },
    clear:   { icon: 'check-circle', type: 'warning', title: 'Clear Warning', desc: 'Status warning pada toko ini akan dihapus dan dikembalikan ke status Active.' },
};

function openConfirm(action) {
    if (action === 'suspend' && !selectedDays) {
        alert('Pilih durasi suspend terlebih dahulu (7, 14, atau 30 hari).');
        return;
    }
    const cfg = modalConfigs[action];
    document.getElementById('confirmIcon').className    = 'confirm-icon ' + cfg.type;
    document.getElementById('confirmIconSvg').setAttribute('data-feather', cfg.icon);
    document.getElementById('confirmTitle').textContent = cfg.title;
    document.getElementById('confirmDesc').textContent  = action === 'suspend'
        ? 'Toko ini akan dinonaktifkan selama ' + selectedDays + ' hari. Seller tidak dapat berjualan selama periode tersebut.'
        : cfg.desc;
    document.getElementById('btnOk').className = 'btn-ok ' + cfg.type;
    feather.replace({ 'stroke-width': 2 });
    document.getElementById('confirmOverlay').classList.add('show');
}
function closeConfirm() { document.getElementById('confirmOverlay').classList.remove('show'); }
document.getElementById('confirmOverlay').addEventListener('click', e => {
    if (e.target === document.getElementById('confirmOverlay')) closeConfirm();
});

/* ── Chat modal ── */
function openChat() {
    document.getElementById('chatOverlay').classList.add('show');
    feather.replace({ 'stroke-width': 2 });
    const win = document.getElementById('chatWindow');
    win.scrollTop = win.scrollHeight;
}
function closeChat() { document.getElementById('chatOverlay').classList.remove('show'); }
function sendChat() {
    const input = document.getElementById('chatInput');
    const win   = document.getElementById('chatWindow');
    if (!input.value.trim()) return;
    const wrap = document.createElement('div');
    wrap.style.cssText = 'display:flex;flex-direction:column;align-items:flex-end;';
    wrap.innerHTML = `
        <div style="font-size:9px;color:var(--text-muted);font-weight:600;margin-bottom:3px;padding:0 3px;">Alex Rivera · Now</div>
        <div style="max-width:82%;padding:10px 14px;border-radius:14px;border-bottom-right-radius:4px;background:var(--primary);font-size:12px;line-height:1.55;color:#fff;">${input.value}</div>`;
    win.appendChild(wrap);
    input.value = '';
    win.scrollTop = win.scrollHeight;
}
document.getElementById('chatOverlay').addEventListener('click', e => {
    if (e.target === document.getElementById('chatOverlay')) closeChat();
});
</script>
</body>
</html>