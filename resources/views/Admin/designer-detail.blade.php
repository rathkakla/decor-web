<?php
$current_page = basename($_SERVER['PHP_SELF']);
$current_path = str_replace('.php', '', $current_page);
if ($current_path == "designer-detail") {
    $current_path = "designer-monitor";
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
];

$designer = [
    "id"                  => "DES-009",
    "name"                => "Julian Thorne",
    "specialization"      => "Mid-Century Modern",
    "status"              => "Verified",
    "rating"              => 4.9,
    "joined"              => "20 Nov 2025",
    "total_earnings"      => "Rp 18.500.000",
    "admin_fee"           => "Rp 925.000",
    "active_projects"     => 12,
    "completed_projects"  => 45,
    "consultation_rate"   => "94%",
    "response_time"       => "2 Hours",
    "owner"               => "Julian Thorne",
];

$portfolio = [
    [
        "title"     => "The Walnut Residence",
        "category"  => "Living Room",
        "client"    => "Private Client, Jakarta",
        "desc"      => "Ruang keluarga bertema mid-century modern dengan palet earthy tone, furnitur kayu walnut, dan pencahayaan ambient yang hangat.",
        "tags"      => ["Mid-Century", "Earthy Tone", "Wood"],
        "img"       => "https://images.unsplash.com/photo-1555041469-a586c61ea9bc?w=600&q=80",
        "status"    => "Featured",
    ],
    [
        "title"     => "Studio Noire",
        "category"  => "Home Office",
        "client"    => "Startup Founder, Bandung",
        "desc"      => "Ruang kerja produktif dengan nuansa gelap elegan, rak buku built-in, dan zona pencahayaan task yang terencana.",
        "tags"      => ["Dark Palette", "Minimalist", "Functional"],
        "img"       => "https://images.unsplash.com/photo-1593642632559-0c6d3fc62b89?w=600&q=80",
        "status"    => "Published",
    ],
    [
        "title"     => "Terracotta Loft",
        "category"  => "Bedroom",
        "client"    => "Young Professional, Surabaya",
        "desc"      => "Kamar tidur bohemian modern dengan aksen terracotta, tekstur rattan, dan tanaman hijau sebagai elemen hidup.",
        "tags"      => ["Bohemian", "Terracotta", "Natural"],
        "img"       => "https://images.unsplash.com/photo-1616594039964-ae9021a400a0?w=600&q=80",
        "status"    => "Published",
    ],
    [
        "title"     => "Scandinavian Kitchen",
        "category"  => "Kitchen",
        "client"    => "Family Home, Bali",
        "desc"      => "Dapur bersih bergaya Scandinavian dengan kabinet putih matte, countertop marmer, dan pencahayaan natural maksimal.",
        "tags"      => ["Scandinavian", "Clean", "Marble"],
        "img"       => "https://images.unsplash.com/photo-1556909114-f6e7ad7d3136?w=600&q=80",
        "status"    => "Published",
    ],
    [
        "title"     => "Urban Jungle Balcony",
        "category"  => "Outdoor",
        "client"    => "Apartment Owner, Jakarta",
        "desc"      => "Transformasi balkon apartemen kecil menjadi oasis hijau urban dengan tanaman tropis, furniture rattan ringan, dan pencahayaan fairy lights.",
        "tags"      => ["Urban Jungle", "Outdoor", "Plants"],
        "img"       => "https://images.unsplash.com/photo-1600566752355-35792bedcfea?w=600&q=80",
        "status"    => "Draft",
    ],
    [
        "title"     => "Monochrome Dining",
        "category"  => "Dining Room",
        "client"    => "Restaurant Owner, Yogyakarta",
        "desc"      => "Ruang makan eksklusif dengan palet hitam-putih, meja marmer oval, dan kursi velvet sebagai statement piece.",
        "tags"      => ["Monochrome", "Luxury", "Velvet"],
        "img"       => "https://images.unsplash.com/photo-1617806118233-18e1de247200?w=600&q=80",
        "status"    => "Featured",
    ],
];

$reviews = [
    [
        "name"   => "Adriana Voss",
        "rating" => 5,
        "text"   => "Julian transformed our family home into something extraordinary. Highly recommended.",
        "date"   => "March 2024",
        "avatar" => "https://i.pravatar.cc/150?u=adriana",
    ],
    [
        "name"   => "Marco Pellegrini",
        "rating" => 5,
        "text"   => "Professional, creative, and incredibly patient throughout the whole project.",
        "date"   => "Feb 2024",
        "avatar" => "https://i.pravatar.cc/150?u=marco",
    ],
];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Designer Detail — <?= $designer['name'] ?> | DECOR Admin</title>
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
        .hero-card { background: var(--surface); border: 1px solid var(--border); border-radius: var(--radius); box-shadow: var(--shadow-card); padding: 28px 32px; margin-bottom: 20px; animation: fadeUp 0.4s ease 0.05s both; position: relative; overflow: hidden; }
        .hero-card::before { content: ''; position: absolute; top: 0; left: 0; right: 0; height: 4px; background: var(--success); }
        .hero-top { display: flex; align-items: flex-start; justify-content: space-between; gap: 20px; }
        .hero-left { display: flex; align-items: center; gap: 18px; }
        .designer-avatar-lg { width: 64px; height: 64px; border-radius: 16px; object-fit: cover; border: 2px solid var(--primary-muted); flex-shrink: 0; }
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
        .pill-verified  { background: var(--success-bg); color: var(--success); border: 1px solid #B8DEC8; }
        .pill-suspended { background: var(--warning-bg); color: var(--warning); border: 1px solid #F5CBA7; }
        .pill-banned    { background: var(--danger-bg);  color: var(--danger);  border: 1px solid #F5C6C1; }
        .hero-actions { display: flex; gap: 8px; margin-top: 4px; }
        .btn-sm { display: inline-flex; align-items: center; gap: 5px; padding: 7px 14px; border-radius: 8px; font-size: 11px; font-weight: 700; border: 1.5px solid var(--border); background: var(--surface); color: var(--text-soft); cursor: pointer; transition: 0.15s; font-family: inherit; }
        .btn-sm:hover { border-color: var(--primary-muted); color: var(--primary); background: var(--primary-light); }
        .btn-sm svg { width: 12px; height: 12px; }

        /* ─── METRICS STRIP ─── */
        .metrics-strip { display: grid; grid-template-columns: repeat(5, 1fr); gap: 1px; background: var(--border-soft); border: 1px solid var(--border-soft); border-radius: 10px; overflow: hidden; margin-top: 24px; }
        .metric-cell { background: var(--surface-2); padding: 14px 18px; }
        .metric-val { font-size: 16px; font-weight: 800; color: var(--text); }
        .metric-val.good    { color: var(--success); }
        .metric-val.primary { color: var(--primary); }
        .metric-lbl { font-size: 9px; font-weight: 700; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.05em; margin-top: 3px; }

        /* ─── LAYOUT ─── */
        .layout { display: grid; grid-template-columns: 1fr 300px; gap: 20px; animation: fadeUp 0.4s ease 0.12s both; }

        /* ─── PANEL ─── */
        .panel { background: var(--surface); border: 1px solid var(--border); border-radius: var(--radius); box-shadow: var(--shadow); overflow: hidden; margin-bottom: 20px; }
        .panel-header { padding: 18px 22px; border-bottom: 1px solid var(--border-soft); display: flex; align-items: center; justify-content: space-between; }
        .panel-title { font-size: 13px; font-weight: 800; color: var(--text); display: flex; align-items: center; gap: 8px; }
        .panel-title svg { width: 14px; height: 14px; stroke: var(--primary); }
        .panel-body { padding: 22px; }
        .panel-badge-good { font-size: 9px; font-weight: 800; padding: 3px 8px; border-radius: 5px; background: var(--success-bg); color: var(--success); text-transform: uppercase; }

        /* ─── PORTFOLIO ─── */
        .portfolio-filter { display: flex; gap: 6px; flex-wrap: wrap; }
        .pf-tab { padding: 5px 12px; border-radius: 7px; font-size: 10px; font-weight: 700; cursor: pointer; color: var(--text-muted); background: var(--surface-2); border: 1.5px solid var(--border); transition: 0.15s; font-family: inherit; }
        .pf-tab.active, .pf-tab:hover { background: var(--primary-light); color: var(--primary); border-color: var(--primary-muted); }

        .portfolio-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 16px; padding: 22px; }
        .port-card { border: 1.5px solid var(--border); border-radius: 12px; overflow: hidden; background: var(--surface-2); transition: box-shadow 0.2s, transform 0.2s; cursor: pointer; }
        .port-card:hover { box-shadow: 0 8px 28px rgba(28,20,16,.10); transform: translateY(-3px); }
        .port-img-wrap { position: relative; height: 160px; overflow: hidden; }
        .port-img-wrap img { width: 100%; height: 100%; object-fit: cover; transition: transform 0.4s; display: block; }
        .port-card:hover .port-img-wrap img { transform: scale(1.06); }
        .port-cat { position: absolute; bottom: 10px; right: 10px; background: rgba(28,20,16,.55); backdrop-filter: blur(4px); color: #fff; padding: 3px 8px; border-radius: 5px; font-size: 9px; font-weight: 700; }
        .port-info { padding: 14px; }
        .port-title { font-size: 13px; font-weight: 800; color: var(--text); margin-bottom: 3px; }
        .port-client { font-size: 10px; color: var(--text-muted); font-weight: 500; display: flex; align-items: center; gap: 4px; margin-bottom: 8px; }
        .port-client svg { width: 9px; }
        .port-desc { font-size: 11px; color: var(--text-soft); line-height: 1.6; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; }
        .port-footer { padding: 10px 14px; border-top: 1px solid var(--border-soft); display: flex; align-items: center; justify-content: flex-end; }
        .port-actions { display: flex; gap: 5px; }
        .port-btn { width: 26px; height: 26px; border-radius: 7px; border: 1.5px solid var(--border); background: var(--surface); cursor: pointer; color: var(--text-muted); display: inline-flex; align-items: center; justify-content: center; transition: 0.15s; }
        .port-btn svg { width: 11px; height: 11px; }
        .port-btn:hover { border-color: var(--primary-muted); color: var(--primary); background: var(--primary-light); }

        /* ─── REVIEWS ─── */
        .review-item { display: flex; gap: 12px; padding: 16px 0; border-bottom: 1px solid var(--border-soft); }
        .review-item:last-child { border-bottom: none; padding-bottom: 0; }
        .review-avatar { width: 36px; height: 36px; border-radius: 10px; object-fit: cover; border: 1px solid var(--border-soft); flex-shrink: 0; }
        .review-name { font-size: 12px; font-weight: 700; color: var(--text); }
        .review-date { font-size: 9px; color: var(--text-muted); font-weight: 600; margin-left: 6px; }
        .star-row { display: flex; gap: 2px; margin: 4px 0 8px; }
        .star { width: 10px; height: 10px; }
        .review-text { font-size: 12px; color: var(--text-soft); line-height: 1.65; background: var(--surface-2); padding: 10px 14px; border-radius: 10px; border-left: 3px solid var(--primary-muted); font-style: italic; }

        /* ─── MODERATION ─── */
        .mod-section { margin-bottom: 18px; }
        .mod-label { font-size: 9px; font-weight: 800; text-transform: uppercase; letter-spacing: 0.12em; color: var(--text-muted); margin-bottom: 7px; display: block; }
        .mod-textarea { width: 100%; height: 90px; border: 1.5px solid var(--border); border-radius: 10px; padding: 12px 14px; font-family: inherit; font-size: 12px; color: var(--text); background: var(--surface-2); resize: none; outline: none; transition: 0.15s; }
        .mod-textarea:focus { border-color: var(--primary-muted); background: var(--primary-light); }
        .mod-textarea::placeholder { color: var(--text-muted); }
        .dur-grid { display: grid; grid-template-columns: repeat(3,1fr); gap: 8px; margin-top: 4px; }
        .dur-btn { padding: 10px 6px; border-radius: 10px; border: 1.5px solid var(--border); background: var(--surface-2); font-family: inherit; font-size: 12px; font-weight: 700; color: var(--text-soft); cursor: pointer; transition: 0.15s; text-align: center; }
        .dur-btn:hover { border-color: var(--warning); color: var(--warning); background: var(--warning-bg); }
        .dur-btn.selected { border-color: var(--warning); background: var(--warning-bg); color: var(--warning); }
        .dur-days { font-size: 18px; font-weight: 800; display: block; }
        .dur-label { font-size: 9px; font-weight: 600; color: inherit; opacity: 0.8; text-transform: uppercase; letter-spacing: 0.05em; }
        .action-btns { display: flex; flex-direction: column; gap: 8px; }
        .btn-action-full { width: 100%; padding: 11px 16px; border-radius: 10px; font-size: 12px; font-weight: 700; cursor: pointer; display: flex; align-items: center; gap: 8px; border: 1.5px solid; transition: 0.15s; font-family: inherit; }
        .btn-action-full svg { width: 13px; height: 13px; }
        .btn-action-full.warning { background: var(--warning-bg); color: var(--warning); border-color: #F5CBA7; }
        .btn-action-full.warning:hover { background: var(--warning); color: #fff; }
        .btn-action-full.danger  { background: var(--danger-bg);  color: var(--danger);  border-color: #F5C6C1; }
        .btn-action-full.danger:hover  { background: var(--danger); color: #fff; }
        .btn-action-full.success { background: var(--success-bg); color: var(--success); border-color: #B8DEC8; }
        .btn-action-full.success:hover { background: var(--success); color: #fff; }

        /* ─── PORTFOLIO LIGHTBOX ─── */
        .overlay { display: none; position: fixed; inset: 0; z-index: 2000; background: rgba(28,20,16,0.55); backdrop-filter: blur(6px); align-items: center; justify-content: center; }
        .overlay.show { display: flex; }
        .modal-box { background: var(--surface); border-radius: 18px; padding: 28px 30px; width: 380px; box-shadow: 0 24px 60px rgba(0,0,0,0.18); animation: slideUp 0.25s cubic-bezier(0.16,1,0.3,1) both; }
        @keyframes slideUp { from { transform: translateY(20px); opacity: 0; } to { transform: none; opacity: 1; } }

        /* lightbox */
        .lightbox-box { background: var(--surface); border-radius: 18px; width: 780px; max-width: 95vw; max-height: 90vh; overflow: hidden; box-shadow: 0 32px 80px rgba(0,0,0,0.22); animation: slideUp 0.25s cubic-bezier(0.16,1,0.3,1) both; display: flex; flex-direction: column; }
        .lb-img { width: 100%; height: 340px; object-fit: cover; display: block; }
        .lb-body { padding: 24px 28px; overflow-y: auto; }
        .lb-meta { display: flex; align-items: center; gap: 8px; margin-bottom: 10px; }
        .lb-title { font-size: 20px; font-weight: 800; color: var(--text); margin-bottom: 4px; }
        .lb-client { font-size: 11px; color: var(--text-muted); font-weight: 600; display: flex; align-items: center; gap: 4px; margin-bottom: 14px; }
        .lb-client svg { width: 10px; }
        .lb-desc { font-size: 13px; color: var(--text-soft); line-height: 1.7; }
        .lb-actions { display: flex; gap: 8px; padding-top: 16px; border-top: 1px solid var(--border-soft); margin-top: 16px; }
        .lb-close { margin-left: auto; width: 32px; height: 32px; border-radius: 9px; border: 1.5px solid var(--border); background: var(--surface); cursor: pointer; color: var(--text-muted); display: flex; align-items: center; justify-content: center; transition: 0.15s; }
        .lb-close:hover { border-color: var(--border); background: var(--surface-2); }
        .lb-close svg { width: 14px; height: 14px; }

        /* confirm */
        .confirm-icon { width: 48px; height: 48px; border-radius: 14px; display: flex; align-items: center; justify-content: center; margin-bottom: 16px; }
        .confirm-icon svg { width: 22px; height: 22px; }
        .confirm-icon.warning { background: var(--warning-bg); } .confirm-icon.warning svg { stroke: var(--warning); }
        .confirm-icon.danger  { background: var(--danger-bg);  } .confirm-icon.danger  svg { stroke: var(--danger); }
        .confirm-icon.success { background: var(--success-bg); } .confirm-icon.success svg { stroke: var(--success); }
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
        .btn-ok.success { background: var(--success); } .btn-ok.success:hover { background: #125A35; }

        /* chat modal */
        .chat-box { background: var(--surface); border-radius: 20px; width: 460px; overflow: hidden; box-shadow: 0 24px 60px rgba(0,0,0,0.18); animation: slideUp 0.25s cubic-bezier(0.16,1,0.3,1) both; }

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

    <!-- Breadcrumb -->
    <div class="breadcrumb">
        <a href="dashboard"><i data-feather="home"></i> Dashboard</a>
        <span class="breadcrumb-sep">/</span>
        <a href="designer-monitor"><i data-feather="pen-tool"></i> Designer Monitor</a>
        <span class="breadcrumb-sep">/</span>
        <span class="breadcrumb-current"><?= $designer['name'] ?></span>
    </div>

    <!-- Hero Card -->
    <div class="hero-card">
        <div class="hero-top">
            <div class="hero-left">
                <img src="https://ui-avatars.com/api/?name=Julian+Thorne&background=B5733A&color=fff&size=128" class="designer-avatar-lg">
                <div>
                    <div class="hero-name"><?= $designer['name'] ?></div>
                    <div class="hero-meta">
                        <span class="hero-id"><?= $designer['id'] ?></span>
                        <span class="hero-spec"><i data-feather="award"></i><?= $designer['specialization'] ?></span>
                        <span class="hero-joined"><i data-feather="calendar"></i>Joined <?= $designer['joined'] ?></span>
                    </div>
                </div>
            </div>
            <div class="hero-right">
                <?php $sl = strtolower($designer['status']); ?>
                <span class="pill pill-<?= $sl ?>">
                    <i data-feather="<?= $sl === 'verified' ? 'check' : ($sl === 'suspended' ? 'pause' : 'x') ?>"></i>
                    <?= $designer['status'] ?>
                </span>
                <div class="hero-actions">
                    <button class="btn-sm" onclick="openChat()"><i data-feather="message-circle"></i> Kirim Pesan</button>
                </div>
            </div>
        </div>
        <div class="metrics-strip">
            <div class="metric-cell">
                <div class="metric-val primary"><?= $designer['total_earnings'] ?></div>
                <div class="metric-lbl">Total Earnings</div>
            </div>
            <div class="metric-cell">
                <div class="metric-val primary"><?= $designer['admin_fee'] ?></div>
                <div class="metric-lbl">Admin Fee</div>
            </div>
            <div class="metric-cell">
                <div class="metric-val"><?= $designer['active_projects'] ?></div>
                <div class="metric-lbl">Active Projects</div>
            </div>
            <div class="metric-cell">
                <div class="metric-val"><?= $designer['completed_projects'] ?></div>
                <div class="metric-lbl">Completed</div>
            </div>
            <div class="metric-cell">
                <div class="metric-val good">⭐ <?= $designer['rating'] ?></div>
                <div class="metric-lbl">Rating</div>
            </div>
        </div>
    </div>

    <!-- Main Layout -->
    <div class="layout">

        <!-- LEFT COLUMN -->
        <div>

            <!-- ══ PORTFOLIO ══ -->
            <div class="panel">
                <div class="panel-header">
                    <div class="panel-title"><i data-feather="image"></i> Portfolio Desainer</div>
                    <div style="display:flex;align-items:center;gap:10px;">
                        <div class="portfolio-filter" id="pfFilter">
                            <button class="pf-tab active" data-cat="all">Semua</button>
                            <button class="pf-tab" data-cat="Living Room">Living Room</button>
                            <button class="pf-tab" data-cat="Bedroom">Bedroom</button>
                            <button class="pf-tab" data-cat="Kitchen">Kitchen</button>
                            <button class="pf-tab" data-cat="Outdoor">Outdoor</button>
                        </div>
                        <span class="panel-badge-good"><?= count($portfolio) ?> Karya</span>
                    </div>
                </div>
                <div class="portfolio-grid" id="portfolioGrid">
                    <?php foreach ($portfolio as $pi => $p): ?>
                    <div class="port-card" data-cat="<?= $p['category'] ?>" onclick="openLightbox(<?= $pi ?>)">
                        <div class="port-img-wrap">
                            <img src="<?= $p['img'] ?>" alt="<?= htmlspecialchars($p['title']) ?>" loading="lazy">
                            <span class="port-cat"><?= $p['category'] ?></span>
                        </div>
                        <div class="port-info">
                            <div class="port-title"><?= htmlspecialchars($p['title']) ?></div>
                            <div class="port-client"><i data-feather="map-pin"></i><?= htmlspecialchars($p['client']) ?></div>
                            <div class="port-desc"><?= htmlspecialchars($p['desc']) ?></div>
                        </div>
                        <div class="port-footer" onclick="event.stopPropagation()">
                            <div class="port-actions">
                                <button class="port-btn" title="Lihat" onclick="openLightbox(<?= $pi ?>)"><i data-feather="eye"></i></button>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <!-- ══ REVIEWS ══ -->
            <div class="panel">
                <div class="panel-header">
                    <div class="panel-title"><i data-feather="message-square"></i> Client Testimonials</div>
                    <span class="panel-badge-good"><?= count($reviews) ?> Reviews</span>
                </div>
                <div class="panel-body">
                    <?php foreach ($reviews as $r):
                        $sf = $r['rating']; $se = 5 - $sf;
                    ?>
                    <div class="review-item">
                        <img src="<?= $r['avatar'] ?>" class="review-avatar">
                        <div style="flex:1;">
                            <div>
                                <span class="review-name"><?= $r['name'] ?></span>
                                <span class="review-date"><?= $r['date'] ?></span>
                            </div>
                            <div class="star-row">
                                <?php for($i=0;$i<$sf;$i++): ?><svg class="star" viewBox="0 0 16 16" fill="#FFA000"><path d="M8 1l1.85 3.75L14 5.5l-3 2.92.71 4.13L8 10.4l-3.71 2.15.71-4.13L2 5.5l4.15-.75z"/></svg><?php endfor; ?>
                                <?php for($i=0;$i<$se;$i++): ?><svg class="star" viewBox="0 0 16 16" fill="#E8E2DB"><path d="M8 1l1.85 3.75L14 5.5l-3 2.92.71 4.13L8 10.4l-3.71 2.15.71-4.13L2 5.5l4.15-.75z"/></svg><?php endfor; ?>
                            </div>
                            <div class="review-text">"<?= $r['text'] ?>"</div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>

        </div>

        <!-- RIGHT COLUMN -->
        <div>
            <div class="panel" style="position: sticky; top: 24px;">
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
                                <span id="suspendBtnLabel">Suspend Akun</span>
                            </button>
                            <button class="btn-action-full danger" onclick="openConfirm('ban')">
                                <i data-feather="slash"></i> Ban Permanen
                            </button>
                            <button class="btn-action-full success" onclick="openConfirm('clear')">
                                <i data-feather="check-circle"></i> Clear Warning
                            </button>
                        </div>
                    </div>

                </div>
            </div>
        </div>

    </div>
</main>

<!-- ══════════ PORTFOLIO LIGHTBOX ══════════ -->
<div id="lightboxOverlay" class="overlay">
    <div class="lightbox-box">
        <img id="lbImg" src="" alt="" class="lb-img">
        <div class="lb-body">
            <div class="lb-meta">
                <span id="lbCat" style="font-size:10px;color:var(--text-muted);font-weight:700;text-transform:uppercase;letter-spacing:.08em;"></span>
                <button class="lb-close" onclick="closeLightbox()" style="margin-left:auto;"><i data-feather="x"></i></button>
            </div>
            <div id="lbTitle" class="lb-title"></div>
            <div id="lbClient" class="lb-client"><i data-feather="map-pin"></i><span id="lbClientText"></span></div>
            <div id="lbDesc" class="lb-desc"></div>
        </div>
    </div>
</div>

<!-- ══════════ CHAT MODAL ══════════ -->
<div id="chatOverlay" class="overlay">
    <div class="chat-box">
        <div style="padding: 18px 22px; background: var(--primary); display: flex; align-items: center; justify-content: space-between;">
            <div style="display:flex;align-items:center;gap:10px;">
                <div style="width:34px;height:34px;background:rgba(255,255,255,0.2);border-radius:9px;display:flex;align-items:center;justify-content:center;">
                    <i data-feather="message-circle" style="width:15px;height:15px;stroke:#fff;"></i>
                </div>
                <div>
                    <div style="font-size:13px;font-weight:800;color:#fff;"><?= $designer['name'] ?></div>
                    <div style="font-size:10px;color:rgba(255,255,255,0.65);">Kirim pesan ke desainer</div>
                </div>
            </div>
            <button onclick="closeChat()" style="background:rgba(255,255,255,0.15);border:none;color:#fff;width:28px;height:28px;border-radius:8px;cursor:pointer;font-size:16px;display:flex;align-items:center;justify-content:center;">×</button>
        </div>
        <div id="chatWindow" style="height:300px;padding:18px;overflow-y:auto;background:var(--surface-2);display:flex;flex-direction:column;gap:10px;">
            <div style="display:flex;flex-direction:column;">
                <div style="font-size:9px;color:var(--text-muted);font-weight:600;margin-bottom:3px;padding:0 3px;"><?= $designer['name'] ?> · Yesterday</div>
                <div style="max-width:82%;padding:10px 14px;border-radius:14px;border-bottom-left-radius:4px;background:var(--surface);border:1px solid var(--border-soft);font-size:12px;line-height:1.55;color:var(--text);">Halo Admin, ada yang ingin saya tanyakan terkait proyek aktif saya.</div>
            </div>
            <div style="display:flex;flex-direction:column;align-items:flex-end;">
                <div style="font-size:9px;color:var(--text-muted);font-weight:600;margin-bottom:3px;padding:0 3px;">Alex Rivera · Yesterday</div>
                <div style="max-width:82%;padding:10px 14px;border-radius:14px;border-bottom-right-radius:4px;background:var(--primary);font-size:12px;line-height:1.55;color:#fff;">Halo, silakan sampaikan pertanyaannya.</div>
            </div>
        </div>
        <div style="padding:14px 16px;border-top:1px solid var(--border-soft);background:var(--surface);display:flex;gap:8px;">
            <input type="text" id="chatInput" placeholder="Tulis pesan ke desainer…"
                   onkeydown="if(event.key==='Enter') sendChat()"
                   style="flex:1;padding:10px 14px;border:1.5px solid var(--border);border-radius:10px;font-family:inherit;font-size:12px;color:var(--text);background:var(--surface-2);outline:none;">
            <button onclick="sendChat()" style="padding:10px 16px;background:var(--primary);color:#fff;border:none;border-radius:10px;font-size:11px;font-weight:700;cursor:pointer;display:flex;align-items:center;gap:5px;font-family:inherit;">
                <i data-feather="send" style="width:13px;height:13px;"></i> Kirim
            </button>
        </div>
    </div>
</div>

<!-- ══════════ CONFIRM MODAL ══════════ -->
<div id="confirmOverlay" class="overlay">
    <div class="modal-box">
        <div id="confirmIcon" class="confirm-icon warning">
            <i id="confirmIconSvg" data-feather="pause-circle"></i>
        </div>
        <div id="confirmTitle" class="confirm-title">Konfirmasi Tindakan</div>
        <div id="confirmDesc"  class="confirm-desc">Apakah kamu yakin ingin melanjutkan aksi ini?</div>
        <div class="confirm-chip">
            <div class="chip-icon"><i data-feather="pen-tool"></i></div>
            <span><?= $designer['name'] ?> (<?= $designer['id'] ?>)</span>
        </div>
        <div class="confirm-btns">
            <button class="btn-cancel" onclick="closeConfirm()">Batal</button>
            <button id="btnOk" class="btn-ok warning" onclick="closeConfirm()">Konfirmasi</button>
        </div>
    </div>
</div>

<!-- Portfolio data for JS -->
<script>
const portfolioData = <?= json_encode($portfolio) ?>;
</script>

<script>
feather.replace({ 'stroke-width': 2 });

/* ── Portfolio filter ── */
document.getElementById('pfFilter').addEventListener('click', function(e) {
    if (!e.target.classList.contains('pf-tab')) return;
    document.querySelectorAll('.pf-tab').forEach(b => b.classList.remove('active'));
    e.target.classList.add('active');
    const cat = e.target.dataset.cat;
    document.querySelectorAll('.port-card').forEach(card => {
        card.style.display = (cat === 'all' || card.dataset.cat === cat) ? '' : 'none';
    });
});

/* ── Portfolio Lightbox ── */
function openLightbox(idx) {
    const p = portfolioData[idx];
    document.getElementById('lbImg').src = p.img;
    document.getElementById('lbImg').alt = p.title;
    document.getElementById('lbTitle').textContent  = p.title;
    document.getElementById('lbClientText').textContent = p.client;
    document.getElementById('lbDesc').textContent   = p.desc;
    document.getElementById('lbCat').textContent    = p.category;
    document.getElementById('lightboxOverlay').classList.add('show');
    feather.replace({ 'stroke-width': 2 });
}
function closeLightbox() { document.getElementById('lightboxOverlay').classList.remove('show'); }
document.getElementById('lightboxOverlay').addEventListener('click', e => {
    if (e.target === document.getElementById('lightboxOverlay')) closeLightbox();
});

/* ── Suspend duration ── */
let selectedDays = null;
function selectDur(btn, days) {
    document.querySelectorAll('.dur-btn').forEach(b => b.classList.remove('selected'));
    btn.classList.add('selected');
    selectedDays = days;
    document.getElementById('suspendBtnLabel').textContent = 'Suspend ' + days + ' Hari';
}

/* ── Confirm modal ── */
const modalConfigs = {
    suspend: { icon: 'pause-circle', type: 'warning', title: 'Suspend Akun',  desc: '' },
    ban:     { icon: 'slash',        type: 'danger',  title: 'Ban Permanen',  desc: 'Aksi ini permanen. Akun desainer akan diblokir dan semua portofolio disembunyikan.' },
    clear:   { icon: 'check-circle', type: 'success', title: 'Clear Warning', desc: 'Status warning pada akun desainer ini akan dihapus dan dikembalikan ke status Verified.' },
};
function openConfirm(action) {
    if (action === 'suspend' && !selectedDays) {
        alert('Pilih durasi suspend terlebih dahulu (7, 14, atau 30 hari).');
        return;
    }
    const cfg = modalConfigs[action];
    document.getElementById('confirmIcon').className  = 'confirm-icon ' + cfg.type;
    document.getElementById('confirmIconSvg').setAttribute('data-feather', cfg.icon);
    document.getElementById('confirmTitle').textContent = cfg.title;
    document.getElementById('confirmDesc').textContent  = action === 'suspend'
        ? 'Akun desainer ini akan dinonaktifkan selama ' + selectedDays + ' hari. Seluruh proyek aktif akan ditangguhkan sementara.'
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