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
    <title>Seller Support — DECOR Admin</title>
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
        .chat-modal { display: none; position: fixed; z-index: 2000; inset: 0; background: rgba(28,20,16,0.55); backdrop-filter: blur(5px); align-items: center; justify-content: center; }
        .chat-modal.show { display: flex; }
        .chat-content { transform: scale(0.95); transition: 0.3s; }
        .chat-modal.show .chat-content { transform: scale(1); }
        .chat-body { flex: 1; padding: 20px; overflow-y: auto; display: flex; flex-direction: column; gap: 16px; background: #FAF8F5; }
    </style>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body class="text-gray-800 bg-[#F8F6F4]">

@include("Admin.partials.sidebar")

<main id="main-content" class="flex-1 flex flex-col ml-64 sidebar-transition min-h-screen bg-[#F8F6F4]">
    @include("Admin.partials.header", ["title" => "Seller Support"])
    <div class="p-8 space-y-8 flex-1">

    <div class="mb-8">
        <div class="text-[10px] font-black text-primary uppercase tracking-widest mb-1">Partnership Care</div>
        <div class="text-2xl font-bold text-gray-900">Seller Support Hub</div>
        <div class="text-xs text-gray-500 mt-1">Bantu penjual mengatasi kendala upload produk, pembayaran, dan komplain pelanggan.</div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="bg-white p-6 rounded-xl border border-gray-100 shadow-sm transition-transform hover:-translate-y-1 relative overflow-hidden">
            <div class="absolute bottom-0 left-0 right-0 h-1 bg-red-500"></div>
            <div class="w-10 h-10 rounded-lg bg-red-50 text-red-500 flex items-center justify-center mb-4">
                <i data-feather="alert-circle" class="w-5 h-5"></i>
            </div>
            <h3 class="text-2xl font-bold text-gray-900 text-red-500"><?= str_pad($stats['open'], 2, '0', STR_PAD_LEFT) ?></h3>
            <p class="text-[10px] text-gray-400 font-bold uppercase tracking-wider mt-1">Open</p>
            <p class="text-[10px] font-black text-gray-500 mt-1">butuh respon cepat</p>
        </div>
        <div class="bg-white p-6 rounded-xl border border-gray-100 shadow-sm transition-transform hover:-translate-y-1 relative overflow-hidden">
            <div class="absolute bottom-0 left-0 right-0 h-1 bg-blue-500"></div>
            <div class="w-10 h-10 rounded-lg bg-blue-50 text-blue-500 flex items-center justify-center mb-4">
                <i data-feather="message-square" class="w-5 h-5"></i>
            </div>
            <h3 class="text-2xl font-bold text-gray-900 text-blue-500"><?= str_pad($stats['replied'], 2, '0', STR_PAD_LEFT) ?></h3>
            <p class="text-[10px] text-gray-400 font-bold uppercase tracking-wider mt-1">Replied</p>
            <p class="text-[10px] font-black text-gray-500 mt-1">menunggu respon balik</p>
        </div>
        <div class="bg-white p-6 rounded-xl border border-gray-100 shadow-sm transition-transform hover:-translate-y-1 relative overflow-hidden">
            <div class="absolute bottom-0 left-0 right-0 h-1 bg-green-500"></div>
            <div class="w-10 h-10 rounded-lg bg-green-50 text-green-500 flex items-center justify-center mb-4">
                <i data-feather="check-circle" class="w-5 h-5"></i>
            </div>
            <h3 class="text-2xl font-bold text-gray-900 text-green-500"><?= str_pad($stats['resolved'], 2, '0', STR_PAD_LEFT) ?></h3>
            <p class="text-[10px] text-gray-400 font-bold uppercase tracking-wider mt-1">Resolved</p>
            <p class="text-[10px] font-black text-gray-500 mt-1">kasus selesai</p>
        </div>
    </div>

    <div class="flex items-center gap-4 mb-6">
        <div class="flex-1 relative">
            <i data-feather="search" class="w-4 h-4 absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
            <input type="text" class="w-full pl-10 pr-4 py-3 bg-white border border-gray-100 rounded-xl text-xs font-semibold focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary shadow-sm" placeholder="Cari ID tiket atau nama seller…">
        </div>
        <div class="flex gap-1 bg-white border border-gray-100 shadow-sm rounded-xl p-1">
            <button class="px-4 py-2 rounded-lg text-[10px] font-bold uppercase tracking-widest transition-colors bg-orange-50 text-primary">Semua</button>
            <button class="px-4 py-2 rounded-lg text-[10px] font-bold uppercase tracking-widest transition-colors text-gray-500 hover:bg-gray-50">Open</button>
            <button class="px-4 py-2 rounded-lg text-[10px] font-bold uppercase tracking-widest transition-colors text-gray-500 hover:bg-gray-50">Diproses</button>
            <button class="px-4 py-2 rounded-lg text-[10px] font-bold uppercase tracking-widest transition-colors text-gray-500 hover:bg-gray-50">Selesai</button>
        </div>
    </div>

    <!-- Ticket List -->
    <div class="space-y-4">
        @forelse ($tickets as $t)
        @php
            $sl = strtolower($t->status);
            $leftBorder = match($sl) {
                'pending' => 'border-l-4 border-red-500',
                'replied' => 'border-l-4 border-blue-500',
                'resolved' => 'border-l-4 border-green-500',
                default => 'border-l-4 border-gray-500'
            };
        @endphp
        <div class="bg-white rounded-xl border border-gray-100 p-4 flex items-center gap-6 shadow-sm hover:shadow-md transition-shadow group {{ $leftBorder }} ticket-row" id="row-{{ $t->id }}">

            <!-- ID + Time -->
            <div class="w-[130px]">
                <span class="inline-block px-2 py-1 bg-orange-50 text-primary text-[9px] font-black uppercase tracking-widest rounded-md mb-1.5 font-mono">#{{ str_pad($t->id, 4, '0', STR_PAD_LEFT) }}</span>
                <div class="flex items-center text-[10px] font-bold text-gray-500 uppercase tracking-widest mt-1">
                    <i data-feather="clock" class="w-3 h-3 mr-1.5"></i> {{ $t->created_at->diffForHumans() }}
                </div>
            </div>

            <!-- Sender -->
            <div class="w-1/5">
                <div class="text-sm font-bold text-gray-900 truncate">{{ $t->user->full_name }}</div>
                <span class="inline-flex items-center mt-1 px-2 py-0.5 rounded-md text-[8px] font-black uppercase tracking-widest bg-emerald-50 text-emerald-600">
                    Seller
                </span>
            </div>

            <!-- Subject -->
            <div class="flex-1">
                <div class="text-xs font-semibold text-gray-600 line-clamp-2">{{ $t->subject }}</div>
            </div>

            <!-- Status -->
            <div class="w-[140px]">
                @php
                    $selectCls = match($sl) {
                        'pending' => 'bg-red-50 text-red-600 border-red-200',
                        'replied' => 'bg-blue-50 text-blue-600 border-blue-200',
                        'resolved' => 'bg-green-50 text-green-600 border-green-200',
                        default => 'bg-gray-50 text-gray-600 border-gray-200'
                    };
                @endphp
                <select class="w-full px-3 py-2 rounded-lg text-[10px] font-black uppercase tracking-widest border focus:outline-none transition-colors appearance-none cursor-pointer {{ $selectCls }}" onchange="updateTicketStatus(this, {{ $t->id }})">
                    <option value="pending" {{ $sl === 'pending' ? 'selected' : '' }}>Open</option>
                    <option value="replied" {{ $sl === 'replied' ? 'selected' : '' }}>Replied</option>
                    <option value="resolved" {{ $sl === 'resolved' ? 'selected' : '' }}>Resolved</option>
                </select>
            </div>

            <!-- Action -->
            <div class="w-[140px] flex justify-end">
                <button class="inline-flex items-center justify-center px-4 py-2 rounded-lg text-[10px] font-bold bg-primary text-white hover:bg-[#8A5229] transition-colors whitespace-nowrap w-full"
                        onclick="openChat('{{ $t->id }}', '{{ addslashes($t->user->full_name) }}', '{{ addslashes($t->subject) }}', '{{ addslashes($t->message) }}', '{{ addslashes($t->admin_reply) }}')">
                    <i data-feather="message-circle" class="w-3.5 h-3.5 mr-1.5"></i> View & Reply
                </button>
            </div>

        </div>
        @empty
        <div class="text-center py-12 bg-white rounded-xl border border-gray-100 text-gray-400 shadow-sm">
            <i data-feather="inbox" class="w-12 h-12 mx-auto mb-3 opacity-50"></i>
            <p class="text-sm font-semibold">Belum ada tiket bantuan dari seller.</p>
        </div>
        @endforelse
    </div>

    </div>
</main>

<!-- ══════════ CHAT MODAL ══════════ -->
<div id="chatModal" class="chat-modal">
    <div class="chat-content bg-white w-full max-w-lg rounded-2xl overflow-hidden shadow-2xl flex flex-col max-h-[90vh]">
        <div class="p-5 bg-primary flex items-center justify-between shrink-0">
            <div class="flex items-center gap-3 text-white">
                <div class="w-10 h-10 bg-white/20 rounded-xl flex items-center justify-center backdrop-blur-sm">
                    <i data-feather="message-circle" class="w-5 h-5"></i>
                </div>
                <div>
                    <div class="text-sm font-bold" id="chatTitle">—</div>
                    <div class="text-[10px] text-white/80 mt-0.5" id="chatSub">—</div>
                </div>
            </div>
            <button class="w-8 h-8 flex items-center justify-center rounded-lg bg-white/10 text-white hover:bg-white/20 transition-colors" onclick="closeChat()">
                <i data-feather="x" class="w-4 h-4"></i>
            </button>
        </div>

        <div class="chat-body" id="chatWindow">
            <!-- Messages will be injected here -->
        </div>

        <form id="replyForm" action="" method="POST" class="p-4 bg-white border-t border-gray-100 flex gap-2 shrink-0">
            @csrf
            <input type="text" name="reply" id="chatInput" class="flex-1 px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-xs focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary transition-colors" placeholder="Tulis balasan…" required>
            <button type="submit" class="px-5 py-3 bg-primary hover:bg-[#8A5229] text-white rounded-xl text-xs font-bold flex items-center gap-2 transition-colors">
                <i data-feather="send" class="w-4 h-4"></i> Kirim
            </button>
        </form>
    </div>
</div>

<script>
    feather.replace({ 'stroke-width': 2 });

    function updateTicketStatus(sel, id) {
        const val = sel.value;
        const clsMap = {
            'pending': 'bg-red-50 text-red-600 border-red-200',
            'replied': 'bg-blue-50 text-blue-600 border-blue-200',
            'resolved': 'bg-green-50 text-green-600 border-green-200'
        };
        const borderMap = {
            'pending': 'border-l-4 border-red-500',
            'replied': 'border-l-4 border-blue-500',
            'resolved': 'border-l-4 border-green-500'
        };
        
        sel.className = `w-full px-3 py-2 rounded-lg text-[10px] font-black uppercase tracking-widest border focus:outline-none transition-colors appearance-none cursor-pointer ${clsMap[val]}`;
        
        const row = sel.closest('.ticket-row');
        row.className = row.className.replace(/border-l-4 border-\w+-500/, borderMap[val]);

        fetch(`{{ url('admin/support') }}/${id}/status`, {
            method: 'PATCH',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ status: val })
        });
    }

    function openChat(id, name, subject, message, reply) {
        document.getElementById('chatTitle').textContent = '#' + id.toString().padStart(4, '0') + ' · ' + name;
        document.getElementById('chatSub').textContent = subject;
        
        const win = document.getElementById('chatWindow');
        win.innerHTML = `
            <div class="flex flex-col items-start w-full mb-2">
                <div class="text-[9px] text-gray-500 font-bold uppercase tracking-widest mb-1 pl-1">${name} · Seller</div>
                <div class="max-w-[85%] px-4 py-3 bg-white border border-gray-200 text-gray-800 text-xs rounded-2xl rounded-tl-sm shadow-sm leading-relaxed">${message}</div>
            </div>
        `;

        if(reply && reply !== '') {
            win.innerHTML += `
                <div class="flex flex-col items-end w-full mt-4">
                    <div class="text-[9px] text-gray-500 font-bold uppercase tracking-widest mb-1 pr-1">Admin · Reply</div>
                    <div class="max-w-[85%] px-4 py-3 bg-primary text-white text-xs rounded-2xl rounded-tr-sm shadow-sm leading-relaxed">${reply}</div>
                </div>
            `;
        }

        document.getElementById('replyForm').action = `{{ url('admin/support') }}/${id}/reply`;
        document.getElementById('chatModal').classList.add('show');
        win.scrollTop = win.scrollHeight;
    }

    function closeChat() {
        document.getElementById('chatModal').classList.remove('show');
    }

    window.addEventListener('click', e => {
        if (e.target === document.getElementById('chatModal')) closeChat();
    });
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