<?php $site_name = "DECOR"; ?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Julian Thorne — Portfolio · <?= $site_name ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=DM+Serif+Display:ital@0;1&display=swap" rel="stylesheet">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#B5733A',
                        secondary: '#E3DCD6',
                    }
                }
            }
        }
    </script>
    <style>
        :root {
            --primary: #B5733A;
            --secondary: #E3DCD6;
            --warm: #F7F3EF;
            --bark: #8A5228;
            --ink: #1C1410;
            --border: #EDE8E2;
            --muted: #A89E97;
        }
        * { box-sizing: border-box; }
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background: #fff;
            color: var(--ink);
        }
        .serif { font-family: 'DM Serif Display', serif; }

        /* ── HERO ── */
        .hero-section {
            background: var(--warm);
            border-bottom: 1px solid var(--border);
            padding: 64px 24px 0;
            overflow: hidden;
            position: relative;
        }
        .hero-section::before {
            content: '';
            position: absolute;
            inset: 0;
            background: radial-gradient(ellipse 60% 80% at 80% 100%, rgba(181,115,58,.08) 0%, transparent 70%);
            pointer-events: none;
        }

        /* Fade-up animation */
        .fade-up {
            opacity: 0;
            transform: translateY(24px);
            animation: fadeUp .6s ease forwards;
        }
        @keyframes fadeUp {
            to { opacity: 1; transform: translateY(0); }
        }
        .delay-1 { animation-delay: .1s; }
        .delay-2 { animation-delay: .2s; }
        .delay-3 { animation-delay: .3s; }
        .delay-4 { animation-delay: .4s; }
        .delay-5 { animation-delay: .55s; }

        /* Stat pill */
        .stat-pill {
            background: #fff;
            border: 1px solid var(--border);
            border-radius: 12px;
            padding: 14px 20px;
            text-align: center;
            transition: box-shadow .2s, transform .2s;
        }
        .stat-pill:hover { box-shadow: 0 8px 24px rgba(0,0,0,.07); transform: translateY(-2px); }

        /* Tab nav */
        .tab-nav {
            display: flex;
            gap: 0;
            border-bottom: 1px solid var(--border);
            background: #fff;
            position: sticky;
            top: 65px;
            z-index: 20;
            padding: 0 24px;
            max-width: 1200px;
            margin: 0 auto;
        }
        .tab-btn {
            padding: 16px 20px;
            font-size: 12px;
            font-weight: 700;
            letter-spacing: .06em;
            text-transform: uppercase;
            color: var(--muted);
            border: none;
            background: transparent;
            cursor: pointer;
            border-bottom: 2px solid transparent;
            margin-bottom: -1px;
            transition: color .2s, border-color .2s;
            font-family: inherit;
        }
        .tab-btn.active { color: var(--primary); border-bottom-color: var(--primary); }
        .tab-btn:hover:not(.active) { color: var(--ink); }

        /* Section heading */
        .section-label {
            font-size: 10px;
            font-weight: 800;
            letter-spacing: .14em;
            text-transform: uppercase;
            color: var(--muted);
            margin-bottom: 20px;
        }

        /* Portfolio grid */
        .port-grid {
            display: grid;
            grid-template-columns: repeat(12, 1fr);
            gap: 16px;
        }
        .port-card {
            border-radius: 16px;
            overflow: hidden;
            position: relative;
            cursor: pointer;
            background: var(--warm);
        }
        .port-card img {
            width: 100%; height: 100%; object-fit: cover;
            transition: transform .5s ease;
            display: block;
        }
        .port-card:hover img { transform: scale(1.04); }
        .port-card-overlay {
            position: absolute; inset: 0;
            background: linear-gradient(to top, rgba(28,20,16,.72) 0%, transparent 55%);
            opacity: 0;
            transition: opacity .3s;
            display: flex; align-items: flex-end;
            padding: 20px;
        }
        .port-card:hover .port-card-overlay { opacity: 1; }
        .port-card-tag {
            position: absolute; top: 14px; left: 14px;
            background: rgba(255,255,255,.92);
            border-radius: 99px;
            padding: 5px 12px;
            font-size: 10px; font-weight: 700;
            color: var(--ink);
            letter-spacing: .05em;
        }

        /* Tall card */
        .col-span-5 { grid-column: span 5; }
        .col-span-4 { grid-column: span 4; }
        .col-span-7 { grid-column: span 7; }
        .col-span-3 { grid-column: span 3; }
        .col-span-8 { grid-column: span 8; }
        .col-span-6 { grid-column: span 6; }
        .h-64  { height: 260px; }
        .h-80  { height: 320px; }
        .h-96  { height: 380px; }
        .h-48  { height: 192px; }

        /* Review card */
        .review-card {
            background: var(--warm);
            border: 1px solid var(--border);
            border-radius: 16px;
            padding: 24px;
            transition: box-shadow .2s;
        }
        .review-card:hover { box-shadow: 0 8px 28px rgba(0,0,0,.06); }

        /* Stars */
        .stars { color: var(--primary); font-size: 11px; letter-spacing: 2px; }

        /* Service card */
        .service-card {
            border: 1px solid var(--border);
            border-radius: 16px;
            padding: 28px;
            background: #fff;
            transition: border-color .2s, box-shadow .2s, transform .2s;
            cursor: pointer;
        }
        .service-card:hover {
            border-color: var(--primary);
            box-shadow: 0 8px 28px rgba(181,115,58,.1);
            transform: translateY(-3px);
        }
        .service-icon {
            width: 48px; height: 48px;
            border-radius: 14px;
            background: var(--warm);
            display: flex; align-items: center; justify-content: center;
            margin-bottom: 16px;
            color: var(--primary);
            font-size: 18px;
        }

        /* CTA banner */
        .cta-banner {
            background: var(--ink);
            border-radius: 20px;
            padding: 52px 48px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 32px;
            position: relative;
            overflow: hidden;
        }
        .cta-banner::before {
            content: '';
            position: absolute;
            width: 400px; height: 400px;
            border-radius: 50%;
            background: radial-gradient(circle, rgba(181,115,58,.25) 0%, transparent 70%);
            right: -80px; top: -120px;
            pointer-events: none;
        }

        /* Btn */
        .btn-primary {
            display: inline-flex; align-items: center; gap: 8px;
            background: var(--primary);
            color: #fff;
            border: none;
            border-radius: 12px;
            padding: 14px 24px;
            font-size: 13px;
            font-weight: 700;
            cursor: pointer;
            font-family: inherit;
            transition: background .2s, transform .15s;
            text-decoration: none;
        }
        .btn-primary:hover { background: var(--bark); transform: translateY(-1px); }

        .btn-outline {
            display: inline-flex; align-items: center; gap: 8px;
            background: transparent;
            color: var(--primary);
            border: 1.5px solid var(--primary);
            border-radius: 12px;
            padding: 13px 24px;
            font-size: 13px;
            font-weight: 700;
            cursor: pointer;
            font-family: inherit;
            transition: background .2s, color .2s;
            text-decoration: none;
        }
        .btn-outline:hover { background: var(--primary); color: #fff; }

        /* Avatar badge */
        .badge {
            display: inline-flex; align-items: center; gap: 6px;
            background: #fff;
            border: 1px solid var(--border);
            border-radius: 99px;
            padding: 5px 12px 5px 6px;
            font-size: 11px; font-weight: 600;
            color: var(--ink);
        }
        .badge img { width: 22px; height: 22px; border-radius: 50%; object-fit: cover; }

        /* Availability chip */
        .avail-chip {
            display: inline-flex; align-items: center; gap: 7px;
            background: #F0FDF4;
            border: 1px solid #BBF7D0;
            border-radius: 99px;
            padding: 5px 14px;
            font-size: 11px; font-weight: 700;
            color: #16A34A;
        }
        .avail-dot { width: 7px; height: 7px; border-radius: 50%; background: #22C55E; animation: pulse 2s infinite; }
        @keyframes pulse { 0%,100%{opacity:1;transform:scale(1)} 50%{opacity:.6;transform:scale(.85)} }

        /* Social btn */
        .social-btn {
            width: 38px; height: 38px;
            border-radius: 10px;
            border: 1px solid var(--border);
            display: flex; align-items: center; justify-content: center;
            color: var(--muted);
            font-size: 13px;
            cursor: pointer;
            transition: border-color .15s, color .15s, background .15s;
            text-decoration: none;
        }
        .social-btn:hover { border-color: var(--primary); color: var(--primary); background: #fff7f1; }

        /* Award badge */
        .award-item {
            display: flex; align-items: center; gap: 14px;
            padding: 16px 20px;
            background: var(--warm);
            border: 1px solid var(--border);
            border-radius: 14px;
        }
        .award-icon {
            width: 44px; height: 44px; border-radius: 12px;
            background: linear-gradient(135deg,#B5733A,#E8A96A);
            display: flex; align-items: center; justify-content: center;
            color: #fff; font-size: 16px; flex-shrink: 0;
        }

        /* Specialty tag */
        .spec-tag {
            display: inline-flex; align-items: center; gap: 6px;
            background: var(--warm);
            border: 1px solid var(--border);
            border-radius: 99px;
            padding: 6px 14px;
            font-size: 11px; font-weight: 600;
            color: var(--ink);
        }
    </style>
</head>
<body>

<!-- ══════════ NAVBAR ══════════ -->
<header class="bg-white border-b border-gray-100 sticky top-0 z-50">
    <div class="content-container flex justify-between items-center py-4 px-6 mx-auto max-w-[1200px]">
        <div class="flex items-center space-x-8 flex-1">
            <a href="#" class="text-2xl font-black tracking-tighter uppercase text-primary hover:opacity-80 transition-all">
                <?= $site_name ?>
            </a>
            <div class="hidden lg:flex items-center bg-gray-50 border border-gray-100 rounded-md px-4 py-2 w-full max-w-[180px] group focus-within:bg-white focus-within:border-primary/30 transition-all">
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
            <a href="#" class="text-primary hover:scale-110 transition-transform">
                <i class="fa-solid fa-bag-shopping text-lg"></i>
            </a>
            <button class="text-primary hover:scale-110 transition-transform">
                <i class="fa-regular fa-bell text-lg"></i>
            </button>
            <a href="#" class="block">
                <div class="w-9 h-9 rounded-md overflow-hidden border border-gray-200 cursor-pointer hover:border-primary transition-all">
                    <img src="https://api.dicebear.com/7.x/avataaars/svg?seed=Felix" class="w-full h-full bg-slate-100">
                </div>
            </a>
        </div>
    </div>
</header>

<!-- ══════════ HERO ══════════ -->
<div class="hero-section">
    <div style="max-width:1200px; margin:0 auto; padding:0 24px;">

        <!-- Breadcrumb -->
        <div class="fade-up flex items-center gap-2 text-[11px] text-gray-400 font-medium mb-8">
            <a href="#" class="hover:text-primary transition-colors">Designers</a>
            <i class="fa-solid fa-chevron-right text-[8px]"></i>
            <span class="text-primary font-semibold">Julian Thorne</span>
        </div>

        <div class="flex flex-col lg:flex-row gap-12 pb-0 items-end">

            <!-- Left: Info -->
            <div class="flex-1 pb-12">
                <div class="fade-up delay-1 flex flex-wrap items-center gap-3 mb-6">
                    <span class="avail-chip"><span class="avail-dot"></span> Available for projects</span>
                    <span class="spec-tag"><i class="fa-solid fa-leaf text-[10px] text-primary"></i> Sustainable Design</span>
                    <span class="spec-tag"><i class="fa-solid fa-star text-[10px] text-primary"></i> Verified Pro</span>
                </div>

                <h1 class="fade-up delay-2 serif text-5xl lg:text-6xl leading-none tracking-tight mb-3" style="color:var(--ink);">
                    Julian<br><em>Thorne</em>
                </h1>
                <p class="fade-up delay-2 text-[11px] font-black tracking-[.2em] uppercase text-primary mb-5">Principal Creative Lead</p>

                <p class="fade-up delay-3 text-[14px] text-gray-500 leading-relaxed max-w-lg mb-8">
                    Focused on high-end residential estates where sustainability meets timeless editorial grandeur. Crafting spaces that tell stories through material, light, and proportion.
                </p>

                <!-- Specialties -->
                <div class="fade-up delay-3 flex flex-wrap gap-2 mb-8">
                  
                </div>

                <div class="fade-up delay-4 flex flex-wrap items-center gap-3">
                    
                    <a href="{{ route('customer.chat') }}" class="btn-outline">
                        <i class="fa-regular fa-envelope text-[12px]"></i> Send Message
                    </a>
                    <a href="#" class="social-btn"><i class="fa-brands fa-instagram"></i></a>
                    <a href="#" class="social-btn"><i class="fa-brands fa-pinterest"></i></a>
                    <a href="#" class="social-btn"><i class="fa-brands fa-linkedin"></i></a>
                </div>
            </div>

            <!-- Right: Photo + Stats -->
            <div class="fade-up delay-5 flex flex-col items-end gap-6 lg:w-[420px]">
                <!-- Stats row -->
                <div class="grid grid-cols-3 gap-3 w-full">
                    <div class="stat-pill">
                        <p class="text-2xl font-black" style="color:var(--primary);">82</p>
                        <p class="text-[9px] font-bold uppercase tracking-wider text-gray-400 mt-0.5">Reviews</p>
                    </div>
                    <div class="stat-pill">
                        <p class="text-2xl font-black" style="color:var(--primary);">5.0</p>
                        <p class="text-[9px] font-bold uppercase tracking-wider text-gray-400 mt-0.5">Rating</p>
                    </div>
                    <div class="stat-pill">
                        <p class="text-2xl font-black" style="color:var(--primary);">140+</p>
                        <p class="text-[9px] font-bold uppercase tracking-wider text-gray-400 mt-0.5">Projects</p>
                    </div>
                </div>

                <!-- Profile photo -->
                <div style="position:relative; width:100%;">
                    <div style="border-radius:20px 20px 0 0; overflow:hidden; background:var(--secondary); aspect-ratio:4/4.2;">
                        <img src="/mnt/user-data/uploads/1776998093137_image.png"
                             onerror="this.src='https://images.unsplash.com/photo-1560250097-0b93528c311a?w=600'"
                             style="width:100%; height:100%; object-fit:cover; object-position:top;">
                    </div>
                    <!-- Rating badge overlay -->
                    <div style="position:absolute; bottom:16px; left:16px; background:rgba(28,20,16,.85); backdrop-filter:blur(8px); border-radius:99px; padding:8px 14px; display:flex; align-items:center; gap:6px;">
                        <i class="fa-solid fa-star" style="color:#F59E0B; font-size:11px;"></i>
                        <span style="color:#fff; font-size:12px; font-weight:800;">5.0</span>
                        <span style="color:rgba(255,255,255,.5); font-size:11px; font-weight:600;">(82 reviews)</span>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

<!-- ══════════ TABS ══════════ -->
<div style="background:#fff; border-bottom:1px solid var(--border); position:sticky; top:65px; z-index:20;">
    <div class="tab-nav" style="position:static; border-bottom:none; padding:0;">
        <button class="tab-btn active" onclick="switchTab(this,'portfolio')">Portfolio</button>
        <button class="tab-btn" onclick="switchTab(this,'services')">Services</button>
        <button class="tab-btn" onclick="switchTab(this,'awards')">Awards</button>
        <button class="tab-btn" onclick="switchTab(this,'reviews')">Reviews</button>
    </div>
</div>

<!-- ══════════ CONTENT ══════════ -->
<div style="max-width:1200px; margin:0 auto; padding:52px 24px 80px;">

    <!-- ── PORTFOLIO TAB ── -->
    <div id="tab-portfolio">
        <div class="flex items-center justify-between mb-8">
            <div>
                <p class="section-label">Selected Works</p>
                <h2 class="serif text-3xl" style="color:var(--ink);">Projects Completed</h2>
            </div>
           
        </div>

        <!-- Masonry-style grid -->
        <div class="port-grid">

            <!-- Big card -->
            <div class="port-card col-span-7 h-96">
                <img src="https://images.unsplash.com/photo-1586023492125-27b2c045efd7?w=900" alt="">
                <span class="port-card-tag">Residential · 2024</span>
                <div class="port-card-overlay">
                    <div>
                        <p style="color:#fff; font-size:16px; font-weight:800;" class="serif">Villa Nusa Living Room</p>
                        <p style="color:rgba(255,255,255,.65); font-size:11px; margin-top:3px;">Travertine · Walnut · Mediterranean</p>
                    </div>
                </div>
            </div>

            <!-- Stack right -->
            <div class="col-span-5" style="display:flex; flex-direction:column; gap:16px;">
                <div class="port-card" style="height:185px;">
                    <img src="https://images.unsplash.com/photo-1555041469-a586c61ea9bc?w=700" alt="">
                    <span class="port-card-tag">Lounge · 2024</span>
                    <div class="port-card-overlay">
                        <div>
                            <p style="color:#fff; font-size:14px; font-weight:800;" class="serif">The Velvet Lounge</p>
                            <p style="color:rgba(255,255,255,.65); font-size:10px; margin-top:2px;">Boucle · Brass · Moody Warm</p>
                        </div>
                    </div>
                </div>
                <div class="port-card" style="height:185px;">
                    <img src="https://images.unsplash.com/photo-1600210492493-0946911123ea?w=700" alt="">
                    <span class="port-card-tag">Kitchen · 2023</span>
                    <div class="port-card-overlay">
                        <div>
                            <p style="color:#fff; font-size:14px; font-weight:800;" class="serif">Monolith Kitchen</p>
                            <p style="color:rgba(255,255,255,.65); font-size:10px; margin-top:2px;">Nero Marquina · Oak · Minimal</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Bottom row -->
            <div class="port-card col-span-4 h-64">
                <img src="https://images.unsplash.com/photo-1617104551722-3b2d51366400?w=700" alt="">
                <span class="port-card-tag">Bedroom · 2023</span>
                <div class="port-card-overlay">
                    <div>
                        <p style="color:#fff; font-size:14px; font-weight:800;" class="serif">Sanctuary Suite</p>
                        <p style="color:rgba(255,255,255,.65); font-size:10px; margin-top:2px;">Linen · Walnut · Biophilic</p>
                    </div>
                </div>
            </div>

            <div class="port-card col-span-4 h-64">
                <img src="https://images.unsplash.com/photo-1616594039964-ae9021a400a0?w=700" alt="">
                <span class="port-card-tag">Office · 2024</span>
                <div class="port-card-overlay">
                    <div>
                        <p style="color:#fff; font-size:14px; font-weight:800;" class="serif">Editorial Study</p>
                        <p style="color:rgba(255,255,255,.65); font-size:10px; margin-top:2px;">Leather · Steel · Library</p>
                    </div>
                </div>
            </div>

            <div class="port-card col-span-4 h-64">
                <img src="https://images.unsplash.com/photo-1613977257363-707ba9348227?w=700" alt="">
                <span class="port-card-tag">Outdoor · 2024</span>
                <div class="port-card-overlay">
                    <div>
                        <p style="color:#fff; font-size:14px; font-weight:800;" class="serif">Garden Terrace</p>
                        <p style="color:rgba(255,255,255,.65); font-size:10px; margin-top:2px;">Natural Stone · Greenery</p>
                    </div>
                </div>
            </div>

        </div>

        
    </div>

    <!-- ── SERVICES TAB ── -->
    <div id="tab-services" style="display:none;">
        <p class="section-label">What I Offer</p>
        <h2 class="serif text-3xl mb-10" style="color:var(--ink);">Design Services</h2>
        <div style="display:grid; grid-template-columns:repeat(auto-fill, minmax(280px, 1fr)); gap:20px;">
            <?php
            $services = [
                ['icon'=>'fa-house','title'=>'Full Interior Design','desc'=>'Complete design solution from concept to completion — space planning, material selection, furniture curation, and project management.'],
                ['icon'=>'fa-palette','title'=>'Design Consultation','desc'=>'1-on-1 consultation to analyze your space, discuss style direction, and create an actionable design roadmap.'],
                ['icon'=>'fa-couch','title'=>'Furniture Curation','desc'=>'Handpicked furniture and decor selection sourced from premium artisan brands and exclusive suppliers.'],
                ['icon'=>'fa-lightbulb','title'=>'Lighting Design','desc'=>'Layered lighting strategy — ambient, task, and accent — to elevate mood and functionality in every room.'],
                ['icon'=>'fa-seedling','title'=>'Biophilic Design','desc'=>'Integrating natural elements, plants, materials, and daylight optimization to create wellness-focused spaces.'],
                ['icon'=>'fa-vr-cardboard','title'=>'3D Visualization','desc'=>'Photorealistic renders and walkthrough animations to visualize your space before a single item is purchased.'],
            ];
            foreach($services as $s): ?>
            <div class="service-card">
                <div class="service-icon"><i class="fa-solid <?= $s['icon'] ?>"></i></div>
                <h3 style="font-size:15px; font-weight:800; margin-bottom:6px;"><?= $s['title'] ?></h3>

                <p style="font-size:12.5px; color:#7A6E68; line-height:1.65;"><?= $s['desc'] ?></p>
            </div>
            <?php endforeach; ?>
        </div>
    </div>

    <!-- ── AWARDS TAB ── -->
    <div id="tab-awards" style="display:none;">
        <p class="section-label">Recognition</p>
        <h2 class="serif text-3xl mb-10" style="color:var(--ink);">Awards & Credentials</h2>
        <div style="display:grid; grid-template-columns:repeat(auto-fill, minmax(320px, 1fr)); gap:14px; max-width:800px;">
            <?php
            $awards = [
                ['year'=>'2024','title'=>'ELLE Decoration — Designer of the Year','body'=>'ELLE Decoration International'],
                ['year'=>'2024','title'=>'Top 40 Residential Designers','body'=>'Architectural Digest Indonesia'],
                ['year'=>'2023','title'=>'Sustainable Interiors Award','body'=>'Green Design Council'],
                ['year'=>'2023','title'=>'Best Residential Project','body'=>'Asia Design Awards'],
                ['year'=>'2022','title'=>'Editorial Excellence in Design','body'=>'Wallpaper* Magazine'],
                ['year'=>'2021','title'=>'Rising Design Leader','body'=>'Interior Design Asia Forum'],
            ];
            foreach($awards as $a): ?>
            <div class="award-item">
                <div class="award-icon"><i class="fa-solid fa-trophy"></i></div>
                <div>
                    <p style="font-size:10px; font-weight:800; color:var(--primary); letter-spacing:.08em; text-transform:uppercase;"><?= $a['year'] ?></p>
                    <p style="font-size:13px; font-weight:700; margin-top:2px;"><?= $a['title'] ?></p>
                    <p style="font-size:11px; color:var(--muted); margin-top:2px;"><?= $a['body'] ?></p>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>

  <div id="tab-reviews" style="display:none;">
    
    <div style="background:var(--white); border:1px solid var(--border); border-radius:24px; padding:40px; margin-bottom:48px;">
        <div style="text-align:center; max-w:500px; margin:0 auto;">
            <h3 class="serif" style="font-size:24px; color:var(--primary); margin-bottom:8px;">Share Your Experience</h3>
            <p style="font-size:12px; color:var(--muted); margin-bottom:32px; font-weight:500; text-transform:uppercase; tracking-widest:0.1em;">Berikan ulasan untuk sesi konsultasi Anda</p>
            
            <form action="#" method="POST" style="text-align:left;">
                <div style="margin-bottom:24px; text-align:center;">
                    <div class="star-rating" style="display:flex; justify-content:center; gap:8px; flex-direction:row-reverse;">
                        <?php for($i=5; $i>=1; $i--): ?>
                            <input type="radio" id="star<?= $i ?>" name="rating" value="<?= $i ?>" style="display:none;" />
                            <label for="star<?= $i ?>" class="fa-solid fa-star" style="font-size:24px; color:#E5E7EB; cursor:pointer; transition:0.2s;"></label>
                        <?php endfor; ?>
                    </div>
                    <style>
                        /* Efek hover untuk bintang input */
                        .star-rating label:hover,
                        .star-rating label:hover ~ label,
                        .star-rating input:checked ~ label { color: #F59E0B !important; }
                    </style>
                </div>

                <div style="margin-bottom:20px;">
                    <label style="font-size:10px; font-weight:800; text-transform:uppercase; color:var(--muted); letter-spacing:1px; display:block; margin-bottom:8px; margin-left:4px;">Detail Proyek</label>
                    <input type="text" placeholder="Contoh: Living Room Modern · Jakarta" 
                           style="width:100%; padding:14px 20px; border-radius:12px; border:1px solid var(--border); background:var(--warm); font-size:13px; outline:none; font-family:inherit;">
                </div>

                <div style="margin-bottom:24px;">
                    <label style="font-size:10px; font-weight:800; text-transform:uppercase; color:var(--muted); letter-spacing:1px; display:block; margin-bottom:8px; margin-left:4px;">Review Anda</label>
                    <textarea placeholder="Ceritakan pengalaman konsultasi desain Anda..." 
                              style="width:100%; height:100px; padding:16px 20px; border-radius:12px; border:1px solid var(--border); background:var(--warm); font-size:13px; outline:none; font-family:inherit; resize:none;"></textarea>
                </div>

                <button type="submit" style="width:100%; padding:16px; background:var(--primary); color:white; border:none; border-radius:12px; font-size:11px; font-weight:800; text-transform:uppercase; letter-spacing:2px; cursor:pointer; box-shadow:0 10px 20px rgba(181, 115, 58, 0.2); transition:0.3s;">
                    Publish Review
                </button>
            </form>
        </div>
    </div>

    <div style="display:flex; align-items:center; gap:48px; margin-bottom:40px; padding:32px; background:var(--warm); border-radius:24px; border:1px solid var(--border);">
        <div style="text-align:center; flex-shrink:0;">
            <p class="serif" style="font-size:64px; line-height:1; color:var(--primary); font-weight:400;">5.0</p>
            <div class="stars" style="font-size:16px; margin:6px 0; color:#F59E0B;">★★★★★</div>
            <p style="font-size:11px; color:var(--muted); font-weight:600;">82 reviews</p>
        </div>
        <div style="flex:1;">
            <?php foreach([5=>94,4=>4,3=>1,2=>1,1=>0] as $star=>$pct): ?>
            <div style="display:flex; align-items:center; gap:10px; margin-bottom:8px;">
                <span style="font-size:11px; font-weight:700; color:var(--muted); width:14px;"><?= $star ?></span>
                <i class="fa-solid fa-star" style="color:#F59E0B; font-size:10px;"></i>
                <div style="flex:1; height:6px; background:#E5E7EB; border-radius:99px; overflow:hidden;">
                    <div style="width:<?= $pct ?>%; height:100%; background:var(--primary); border-radius:99px;"></div>
                </div>
                <span style="font-size:11px; font-weight:600; color:var(--muted); width:28px;"><?= $pct ?>%</span>
            </div>
            <?php endforeach; ?>
        </div>
    </div>

    <p class="section-label">Client Testimonials</p>
    <div style="display:grid; grid-template-columns:repeat(auto-fill, minmax(300px, 1fr)); gap:16px;">
        <?php
        $reviews = [
            ['name'=>'Adriana Voss','seed'=>'Adriana','date'=>'March 2024','rating'=>5,'text'=>'Julian transformed our family home into something I see in design magazines. His attention to material quality and the way he balances warmth with elegance is extraordinary.','project'=>'Full Residential · Jakarta'],
            ['name'=>'Marco Pellegrini','seed'=>'Marco','date'=>'Feb 2024','rating'=>5,'text'=>'Professional, creative, and incredibly patient throughout the whole process. The walnut and travertine combination he suggested turned out better than I ever imagined.','project'=>'Living Room · Bali'],
            ['name'=>'Sana Kimura','seed'=>'Sana','date'=>'Jan 2024','rating'=>5,'text'=>'Working with Julian was seamless. He has a rare talent for understanding what a client actually wants, even when they can\'t articulate it themselves.','project'=>'Studio Apartment · Bandung'],
        ];
        foreach($reviews as $r): ?>
        <div class="review-card" style="background:white; padding:24px; border-radius:20px; border:1px solid var(--border);">
            <div class="stars" style="margin-bottom:12px; color:#F59E0B;"><?= str_repeat('★',$r['rating']) ?></div>
            <p style="font-size:13px; color:#4B4440; line-height:1.7; margin-bottom:16px;">"<?= $r['text'] ?>"</p>
            <div style="display:flex; align-items:center; gap:10px; border-top:1px solid var(--border); padding-top:14px;">
                <img src="https://api.dicebear.com/7.x/avataaars/svg?seed=<?= $r['seed'] ?>" style="width:36px; height:36px; border-radius:10px; background:var(--secondary);">
                <div>
                    <p style="font-size:12px; font-weight:700;"><?= $r['name'] ?></p>
                    <p style="font-size:10px; color:var(--muted);"><?= $r['project'] ?> · <?= $r['date'] ?></p>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
</div>

    <!-- ══════════ CTA ══════════ -->
    

</div><!-- /content -->

<!-- ══════════ FOOTER ══════════ -->
<footer class="bg-primary text-white py-12 px-6 mt-12">
    <div class="max-w-6xl mx-auto">
        <div class="flex flex-col md:flex-row justify-between items-start border-b border-white/20 pb-10 gap-10">
            <div class="space-y-4">
                <div class="flex items-center space-x-2 text-white">
                    <div class="w-8 h-8 bg-white/20 rounded flex items-center justify-center">
                        <i class="fa-solid fa-couch text-sm"></i>
                    </div>
                    <span class="text-xl font-bold tracking-widest uppercase">DECOR</span>
                </div>
                <div class="text-sm text-white/90 space-y-2">
                    <p class="flex items-center"><i class="fa-regular fa-envelope mr-3 text-xs"></i> decorofficial@gmail.com</p>
                    <p class="flex items-center"><i class="fa-brands fa-instagram mr-3 text-xs"></i> @decor_official</p>
                </div>
            </div>
            <div class="grid grid-cols-2 gap-x-16 gap-y-4 text-[10px] font-bold tracking-widest uppercase text-white/90">
                <div class="flex flex-col space-y-3">
                    <a href="#" class="hover:text-white/70">Terms of Service</a>
                    <a href="#" class="hover:text-white/70">Privacy Policy</a>
                </div>
                <div class="flex flex-col space-y-3">
                     <a href="{{ route('customer.help-center') }}" class="hover:text-white/70 transition-colors">Help Center</a>
                    <a href="#" class="hover:text-white/70">FAQ</a>
                </div>
            </div>
            <div class="flex flex-col items-end space-y-6">
                <span class="text-[10px] font-bold tracking-widest uppercase text-white/40">Portal Version 1.0</span>
                <div class="flex space-x-4">
                    <a href="#" class="w-10 h-10 border border-white/40 flex items-center justify-center rounded-full hover:bg-white hover:text-primary transition-all"><i class="fa-brands fa-instagram"></i></a>
                    <a href="#" class="w-10 h-10 border border-white/40 flex items-center justify-center rounded-full hover:bg-white hover:text-primary transition-all"><i class="fa-regular fa-paper-plane"></i></a>
                </div>
            </div>
        </div>
        <div class="pt-8 text-[10px] text-white/60 font-medium tracking-widest">
            <p>©️ 2026 DECOR MARKETPLACE. ALL RIGHTS RESERVED.</p>
        </div>
    </div>
</footer>

<script>
function switchTab(btn, tabId) {
    // hide all
    ['portfolio','services','awards','reviews'].forEach(id => {
        document.getElementById('tab-' + id).style.display = 'none';
    });
    // show target
    document.getElementById('tab-' + tabId).style.display = 'block';
    // update tab buttons
    document.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('active'));
    btn.classList.add('active');
}
</script>

</body>
</html>