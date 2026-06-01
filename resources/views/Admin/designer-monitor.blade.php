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
    <title>Designer Monitor — DECOR Admin</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=DM+Mono:wght@400;500&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/feather-icons"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800;900&display=swap');
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        .bg-primary { background-color: #B5733A; }
        .text-primary { color: #B5733A; }
        .sidebar-transition { transition: transform 0.3s ease-in-out, margin 0.3s ease-in-out; }
        .sidebar-hidden { transform: translateX(-100%); }
        .main-expanded { margin-left: 0 !important; }
    </style>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body class="text-gray-800 bg-[#F8F6F4]">

@include("Admin.partials.sidebar")

<main id="main-content" class="flex-1 flex flex-col ml-64 sidebar-transition min-h-screen bg-[#F8F6F4]">
    @include("Admin.partials.header", ["title" => "Designer Monitor"])
    <div class="p-8 space-y-8 flex-1">

    <div class="mb-8">
        <div class="text-[10px] font-black text-primary uppercase tracking-widest mb-1">Monitoring System</div>
        <div class="text-2xl font-bold text-gray-900">Designer Partnerships</div>
        <div class="text-xs text-gray-500 mt-1">Audit performa dan kelola status kemitraan desainer.</div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <?php foreach ($stats as $index => $s): 
            $colors = [
                'bg-orange-50 text-primary',
                'bg-green-50 text-green-500',
                'bg-amber-50 text-amber-500',
                'bg-red-50 text-red-500'
            ];
            $borderColors = [
                'bg-primary',
                'bg-green-500',
                'bg-amber-500',
                'bg-red-500'
            ];
            $color = $colors[$index % 4];
            $borderColor = $borderColors[$index % 4];
        ?>
        <div class="bg-white p-6 rounded-xl border border-gray-100 shadow-sm transition-transform hover:-translate-y-1 relative overflow-hidden">
            <div class="absolute bottom-0 left-0 right-0 h-1 <?= $borderColor ?>"></div>
            <div class="w-10 h-10 rounded-lg <?= $color ?> flex items-center justify-center mb-4">
                <i data-feather="<?= $s['icon'] ?>" class="w-5 h-5"></i>
            </div>
            <h3 class="text-2xl font-bold text-gray-900"><?= $s['value'] ?></h3>
            <p class="text-[10px] text-gray-400 font-bold uppercase tracking-wider mt-1"><?= $s['label'] ?></p>
            <p class="text-[10px] font-black text-gray-500 mt-1"><?= $s['note'] ?></p>
        </div>
        <?php endforeach; ?>
    </div>

    <div class="flex items-center gap-4 mb-6">
        <div class="flex-1 relative">
            <i data-feather="search" class="w-4 h-4 absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
            <input type="text" class="w-full pl-10 pr-4 py-3 bg-white border border-gray-100 rounded-xl text-xs font-semibold focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary shadow-sm" placeholder="Search designer name or specialty…">
        </div>
        <div class="flex gap-1 bg-white border border-gray-100 shadow-sm rounded-xl p-1">
            <button class="px-4 py-2 rounded-lg text-[10px] font-bold uppercase tracking-widest transition-colors bg-orange-50 text-primary">All</button>
            <button class="px-4 py-2 rounded-lg text-[10px] font-bold uppercase tracking-widest transition-colors text-gray-500 hover:bg-gray-50">Verified</button>
            <button class="px-4 py-2 rounded-lg text-[10px] font-bold uppercase tracking-widest transition-colors text-gray-500 hover:bg-gray-50">Suspended</button>
            <button class="px-4 py-2 rounded-lg text-[10px] font-bold uppercase tracking-widest transition-colors text-gray-500 hover:bg-gray-50">Banned</button>
        </div>
    </div>

    <!-- Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <?php foreach ($designers as $d):
            $sl = strtolower($d->status);
            $isBanned     = $d->status === 'Banned';
            $isSuspended  = $d->status === 'Suspended';
            $starFull  = floor($d->rating);
            $starEmpty = 5 - $starFull;
            $rateWidth = $d->rate . '%';
            $topBorder = match($sl) {
                'suspended' => 'border-t-4 border-amber-500',
                'verified' => 'border-t-4 border-green-500',
                'banned' => 'border-t-4 border-red-500',
                default => 'border-t-4 border-gray-500'
            };
        ?>
        <div class="bg-white rounded-xl border border-gray-100 shadow-sm hover:shadow-md transition-shadow flex flex-col <?= $topBorder ?> <?= $isBanned ? 'opacity-75' : '' ?>">
            <div class="p-6 flex-1">
                <div class="flex items-start justify-between mb-4">
                    <img src="https://ui-avatars.com/api/?name=<?= urlencode($d->name) ?>&background=random&size=104"
                         class="w-12 h-12 rounded-xl object-cover border border-gray-200 <?= ($isBanned || $isSuspended) ? 'grayscale' : '' ?>">
                    <?php
                        $statusCls = match($sl) {
                            'suspended' => 'bg-amber-50 text-amber-600',
                            'verified' => 'bg-green-50 text-green-600',
                            'banned' => 'bg-red-50 text-red-600',
                            default => 'bg-gray-50 text-gray-600'
                        };
                        $statusIcon = match($sl) {
                            'verified' => 'check',
                            'suspended' => 'pause',
                            'banned' => 'x',
                            default => 'info'
                        };
                    ?>
                    <span class="inline-flex items-center px-2 py-1 rounded-md text-[9px] font-black uppercase tracking-widest <?= $statusCls ?>">
                        <i data-feather="<?= $statusIcon ?>" class="w-3 h-3 mr-1"></i> <?= $d->status ?>
                    </span>
                </div>

                <div class="text-base font-bold text-gray-900 mb-1 <?= $isBanned ? 'line-through text-red-500' : '' ?>"><?= $d->name ?></div>
                <div class="text-[11px] text-gray-500 font-semibold flex items-center gap-1.5"><i data-feather="tag" class="w-3 h-3"></i><?= $d->spec ?></div>
                <div class="font-mono text-[9px] text-gray-400 mt-2"><?= $d->id ?></div>

                <!-- Metrics -->
                <div class="grid grid-cols-3 gap-[1px] bg-gray-100 border border-gray-100 rounded-xl my-5 overflow-hidden">
                    <div class="bg-gray-50 py-3 text-center">
                        <div class="text-sm font-bold text-gray-900"><?= $d->projects ?></div>
                        <div class="text-[9px] font-bold text-gray-400 uppercase tracking-widest mt-1">Projects</div>
                    </div>
                    <div class="bg-gray-50 py-3 text-center">
                        <div class="text-sm font-bold text-gray-900"><?= $d->rate ?>%</div>
                        <div class="text-[9px] font-bold text-gray-400 uppercase tracking-widest mt-1">Conv. Rate</div>
                    </div>
                    <div class="bg-gray-50 py-3 text-center">
                        <div class="text-sm font-bold text-gray-900"><?= $d->rating ?></div>
                        <div class="text-[9px] font-bold text-gray-400 uppercase tracking-widest mt-1">Rating</div>
                    </div>
                </div>

                <!-- Rating -->
                <div class="flex items-center gap-2 mb-2">
                    <div class="flex gap-0.5">
                        <?php for($i=0;$i<$starFull;$i++): ?>
                        <i data-feather="star" class="w-3 h-3 fill-amber-400 text-amber-400"></i>
                        <?php endfor; ?>
                        <?php for($i=0;$i<$starEmpty;$i++): ?>
                        <i data-feather="star" class="w-3 h-3 fill-gray-200 text-gray-200"></i>
                        <?php endfor; ?>
                    </div>
                    <span class="text-xs font-bold text-gray-900"><?= $d->rating ?></span>
                    <div class="flex-1 mx-2 h-1.5 bg-gray-100 rounded-full overflow-hidden">
                        <div class="h-full bg-primary rounded-full" style="width: <?= $rateWidth ?>"></div>
                    </div>
                    <span class="text-[10px] text-gray-500 font-semibold"><?= $d->rate ?>%</span>
                </div>
            </div>

            <div class="border-t border-gray-100 p-4 bg-gray-50 flex items-center justify-between rounded-b-xl">
                <div class="flex flex-col gap-1">
                    <div class="text-[10px] text-gray-500 font-semibold flex items-center gap-1.5"><i data-feather="calendar" class="w-3 h-3"></i> Joined <?= $d->joined ?></div>
                    <div class="text-[11px] font-bold text-primary"><?= $d->revenue ?> earned</div>
                </div>
                <a href="{{ route('admin.designer-detail', ['id' => $d->db_id]) }}" class="inline-flex items-center px-3 py-1.5 rounded-lg text-[10px] font-bold bg-white border border-gray-200 text-gray-700 hover:bg-primary hover:text-white hover:border-primary transition-colors">
                    View Profile <i data-feather="arrow-right" class="w-3 h-3 ml-1"></i>
                </a>
            </div>
        </div>
        <?php endforeach; ?>
    </div>

    </div>
</main>

<script>
    feather.replace({ 'stroke-width': 2 });
</script>

<script>
    const btn = document.getElementById("toggle-sidebar");
    const sidebar = document.getElementById("sidebar");
    const main = document.getElementById("main-content");
    if(btn) {
        btn.addEventListener("click", () => { 
            sidebar.classList.toggle("sidebar-hidden"); 
            main.classList.toggle("main-expanded"); 
        });
    }
</script>
</body>
</html>