<?php
$current_page = basename($_SERVER['PHP_SELF']);
$current_path = str_replace('.php', '', $current_page);
if ($current_path == "" || $current_path == "index") {
    $current_path = "settings";
}

$admin_name = "Alex Rivera";
$admin_role = "SUPER ADMIN";

$menu_items = [
    ["label" => "Dashboard",              "path" => "dashboard",              "icon" => "grid"],
    ["label" => "User Management",        "path" => "user-management",        "icon" => "users"],
    ["label" => "Seller Monitor",         "path" => "seller-monitor",         "icon" => "shopping-bag"],
    ["label" => "Designer Monitor",       "path" => "designer-monitor",       "icon" => "pen-tool"],
    ["label" => "Seller Support",         "path" => "seller-support",         "icon" => "headphones"],
    ["label" => "Designer Support",       "path" => "designer-support",       "icon" => "pen-tool"],
    ["label" => "Customer Support",       "path" => "customer-support",       "icon" => "message-circle"],
    ["label" => "Product Validation",     "path" => "product-validation",     "icon" => "check-circle"],
    ["label" => "Portofolio Validation",  "path" => "portofolio-validation",  "icon" => "image"],
];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Settings — DECOR Admin</title>
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
            --sidebar-w:     256px;
            --radius:        14px;
            --radius-sm:     9px;
            --shadow:        0 1px 4px rgba(28,20,16,.05), 0 4px 18px rgba(28,20,16,.04);
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
        .page-header { max-width: 680px; margin: 0 auto 28px; animation: fadeUp 0.4s ease both; }
        .page-eyebrow { font-size: 10px; font-weight: 800; text-transform: uppercase; letter-spacing: .16em; color: var(--primary); margin-bottom: 5px; }
        .page-title { font-size: 24px; font-weight: 800; color: var(--text); }
        .page-desc { font-size: 12px; color: var(--text-muted); margin-top: 4px; }

        /* ─── SETTINGS WRAP ─── */
        .settings-wrap { max-width: 680px; margin: 0 auto; display: flex; flex-direction: column; gap: 20px; animation: fadeUp 0.4s ease 0.1s both; }

        /* ─── CARD ─── */
        .s-card { background: var(--surface); border: 1px solid var(--border); border-radius: var(--radius); box-shadow: var(--shadow); overflow: hidden; }
        .s-card-header { padding: 20px 24px; border-bottom: 1px solid var(--border-soft); display: flex; align-items: center; gap: 12px; }
        .s-card-icon { width: 36px; height: 36px; border-radius: 10px; background: var(--primary-light); display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
        .s-card-icon svg { width: 15px; height: 15px; stroke: var(--primary); }
        .s-card-title { font-size: 14px; font-weight: 800; color: var(--text); }
        .s-card-sub { font-size: 11px; color: var(--text-muted); margin-top: 2px; }
        .s-card-body { padding: 24px; }
        .s-card-footer { padding: 16px 24px; border-top: 1px solid var(--border-soft); background: var(--surface-2); display: flex; align-items: center; justify-content: space-between; }
        .footer-hint { font-size: 11px; color: var(--text-muted); display: flex; align-items: center; gap: 5px; }
        .footer-hint svg { width: 12px; height: 12px; flex-shrink: 0; }

        /* ─── AVATAR ROW ─── */
        .avatar-row { display: flex; align-items: center; gap: 20px; margin-bottom: 24px; padding-bottom: 24px; border-bottom: 1px solid var(--border-soft); }
        .avatar-lg { width: 68px; height: 68px; border-radius: 14px; border: 3px solid var(--primary-muted); object-fit: cover; flex-shrink: 0; }
        .avatar-name { font-size: 15px; font-weight: 800; color: var(--text); }
        .avatar-id { font-size: 11px; color: var(--text-muted); margin-top: 3px; font-family: 'DM Mono', monospace; }
        .role-badge { display: inline-flex; margin-top: 8px; padding: 4px 10px; border-radius: 6px; font-size: 9px; font-weight: 800; text-transform: uppercase; background: var(--primary-light); color: var(--primary); border: 1px solid var(--primary-muted); }
        .btn-photo { display: inline-flex; align-items: center; gap: 6px; padding: 8px 14px; border-radius: 9px; font-size: 11px; font-weight: 700; background: var(--surface-2); color: var(--text-soft); border: 1.5px solid var(--border); cursor: pointer; transition: 0.15s; white-space: nowrap; }
        .btn-photo:hover { border-color: var(--primary-muted); color: var(--primary); background: var(--primary-light); }
        .btn-photo svg { width: 12px; height: 12px; }

        /* ─── FORM ─── */
        .form-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 16px; }
        .form-group { display: flex; flex-direction: column; gap: 7px; }
        .form-group.full { grid-column: span 2; }
        .form-label { font-size: 10px; font-weight: 800; text-transform: uppercase; letter-spacing: 0.08em; color: var(--text-muted); }
        .form-input { width: 100%; padding: 10px 14px; border: 1.5px solid var(--border); border-radius: 10px; font-family: inherit; font-size: 13px; color: var(--text); outline: none; transition: 0.15s; background: var(--surface); }
        .form-input:focus { border-color: var(--primary-muted); background: var(--primary-light); }
        .form-input:disabled { background: var(--surface-2); color: var(--text-muted); cursor: not-allowed; }
        .form-input.mono { font-family: 'DM Mono', monospace; font-size: 12px; }
        .form-hint { font-size: 10px; color: var(--text-muted); }

        /* ─── STRENGTH BAR ─── */
        .strength-wrap { margin-top: 6px; }
        .strength-bar-bg { height: 4px; border-radius: 2px; background: var(--border); overflow: hidden; }
        .strength-bar-fill { height: 100%; width: 0%; border-radius: 2px; transition: width 0.3s, background 0.3s; }
        .strength-label { font-size: 10px; margin-top: 4px; font-weight: 700; }

        /* ─── BTN SAVE ─── */
        .btn-save { display: inline-flex; align-items: center; gap: 7px; padding: 10px 22px; background: var(--primary); color: #fff; border: none; border-radius: 10px; font-size: 12px; font-weight: 800; cursor: pointer; transition: 0.15s; font-family: inherit; }
        .btn-save:hover { background: var(--primary-hover); }
        .btn-save svg { width: 13px; height: 13px; }

        /* ─── TOAST ─── */
        .toast { position: fixed; bottom: 32px; right: 32px; z-index: 9999; display: flex; align-items: center; gap: 12px; background: var(--text); color: #fff; padding: 14px 20px; border-radius: 12px; font-size: 13px; font-weight: 600; box-shadow: 0 8px 30px rgba(0,0,0,0.2); transform: translateY(80px); opacity: 0; transition: all 0.35s cubic-bezier(0.16,1,0.3,1); pointer-events: none; min-width: 240px; }
        .toast.show { transform: translateY(0); opacity: 1; }
        .toast-icon { width: 28px; height: 28px; border-radius: 8px; background: var(--success-bg); display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
        .toast-icon svg { width: 14px; height: 14px; stroke: var(--success); }

        @keyframes fadeUp { from { opacity: 0; transform: translateY(14px); } to { opacity: 1; transform: none; } }
    </style>
</head>
<body>

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
        <a href="settings" class="menu-link active"><div class="menu-item"><i data-feather="settings"></i><span>Settings</span></div></a>
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

<main>
    <div class="page-header">
        <div class="page-eyebrow">System</div>
        <div class="page-title">Settings</div>
        <div class="page-desc">Kelola profil dan keamanan akun admin.</div>
    </div>

    <div class="settings-wrap">

        <div class="s-card">
            <div class="s-card-header">
                <div class="s-card-icon"><i data-feather="user"></i></div>
                <div>
                    <div class="s-card-title">Personal Profile</div>
                    <div class="s-card-sub">Nama tampilan, email, dan info akun publik</div>
                </div>
            </div>
            <div class="s-card-body">
                <div class="avatar-row">
                    <img src="https://ui-avatars.com/api/?name=Alex+Rivera&background=B5733A&color=fff&size=160" class="avatar-lg">
                    <div style="flex:1;">
                        <div class="avatar-name"><?= $admin_name ?></div>
                        <div class="avatar-id">ADM-99210-AX</div>
                        <span class="role-badge"><?= $admin_role ?></span>
                    </div>
                    <button class="btn-photo"><i data-feather="upload"></i> Ganti Foto</button>
                </div>
                <div class="form-grid">
                    <div class="form-group">
                        <label class="form-label">Admin ID</label>
                        <input type="text" class="form-input mono" value="ADM-99210-AX" disabled>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Role</label>
                        <input type="text" class="form-input" value="Super Administrator" disabled>
                    </div>
                    <div class="form-group full">
                        <label class="form-label">Nama Lengkap</label>
                        <input type="text" class="form-input" value="Alex Rivera">
                    </div>
                    <div class="form-group full">
                        <label class="form-label">Email Address</label>
                        <input type="email" class="form-input" value="alex.rivera@decor.com">
                        <span class="form-hint">Email ini digunakan untuk login dan notifikasi sistem.</span>
                    </div>
                </div>
            </div>
            <div class="s-card-footer">
                <div class="footer-hint"><i data-feather="info"></i> Perubahan diterapkan setelah disimpan.</div>
                <button class="btn-save" onclick="showToast('Profil berhasil diperbarui')">
                    <i data-feather="save"></i> Simpan Perubahan
                </button>
            </div>
        </div>

        <div class="s-card">
            <div class="s-card-header">
                <div class="s-card-icon"><i data-feather="lock"></i></div>
                <div>
                    <div class="s-card-title">Password & Security</div>
                    <div class="s-card-sub">Ubah kata sandi akun admin</div>
                </div>
            </div>
            <div class="s-card-body">
                <div class="form-grid">
                    <div class="form-group full">
                        <label class="form-label">Password Saat Ini</label>
                        <input type="password" class="form-input" id="currentPass" placeholder="Masukkan password saat ini">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Password Baru</label>
                        <input type="password" class="form-input" id="newPass" placeholder="Min. 8 karakter" oninput="checkStrength(this.value)">
                        <div class="strength-wrap">
                            <div class="strength-bar-bg"><div class="strength-bar-fill" id="strengthBar"></div></div>
                            <div class="strength-label" id="strengthLabel"></div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Konfirmasi Password Baru</label>
                        <input type="password" class="form-input" id="confirmPass" placeholder="Ulangi password baru">
                    </div>
                </div>
            </div>
            <div class="s-card-footer">
                <div class="footer-hint"><i data-feather="shield"></i> Gunakan kombinasi huruf, angka & simbol.</div>
                <button class="btn-save" onclick="savePassword()">
                    <i data-feather="lock"></i> Update Password
                </button>
            </div>
        </div>

    </div>
</main>

<div class="toast" id="toast">
    <div class="toast-icon"><i data-feather="check"></i></div>
    <span id="toastMsg">Perubahan berhasil disimpan</span>
</div>

<script>
feather.replace({ 'stroke-width': 2 });

let toastTimer = null;
function showToast(msg) {
    document.getElementById('toastMsg').textContent = msg;
    const t = document.getElementById('toast');
    t.classList.add('show');
    clearTimeout(toastTimer);
    toastTimer = setTimeout(() => t.classList.remove('show'), 3000);
}

function checkStrength(val) {
    const bar = document.getElementById('strengthBar');
    const lbl = document.getElementById('strengthLabel');
    let score = 0;
    if (val.length >= 8)           score++;
    if (/[A-Z]/.test(val))         score++;
    if (/[0-9]/.test(val))         score++;
    if (/[^A-Za-z0-9]/.test(val))  score++;

    const map = [
        { pct: '0%',   bg: 'var(--border)',  label: '',            color: 'var(--text-muted)' },
        { pct: '25%',  bg: 'var(--danger)',  label: 'Lemah',       color: 'var(--danger)' },
        { pct: '50%',  bg: 'var(--warning)', label: 'Sedang',      color: 'var(--warning)' },
        { pct: '75%',  bg: 'var(--primary)', label: 'Kuat',        color: 'var(--primary)' },
        { pct: '100%', bg: 'var(--success)', label: 'Sangat Kuat', color: 'var(--success)' },
    ];
    bar.style.width      = map[score].pct;
    bar.style.background = map[score].bg;
    lbl.textContent      = map[score].label;
    lbl.style.color      = map[score].color;
}

function savePassword() {
    const cur  = document.getElementById('currentPass').value;
    const nw   = document.getElementById('newPass').value;
    const conf = document.getElementById('confirmPass').value;

    if (!cur)          { showToast('Masukkan password saat ini terlebih dahulu'); return; }
    if (nw.length < 8) { showToast('Password baru minimal 8 karakter'); return; }
    if (nw !== conf)   { showToast('Konfirmasi password tidak cocok'); return; }

    document.getElementById('currentPass').value = '';
    document.getElementById('newPass').value     = '';
    document.getElementById('confirmPass').value = '';
    checkStrength('');
    showToast('Password berhasil diperbarui');
}
</script>
</body>
</html>