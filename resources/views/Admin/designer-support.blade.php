<?php
$admin_name = Auth::user()->full_name;
$admin_role = "ADMIN";
$totalTickets = ($stats['open'] ?? 0) + ($stats['replied'] ?? 0) + ($stats['resolved'] ?? 0);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Designer Support — DECOR Admin</title>
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
    @include("Admin.partials.header", ["title" => "Designer Support"])
    <div class="p-8 space-y-6 flex-1">

    @if(session('success'))
        <div class="bg-green-50 border border-green-200 text-green-600 px-6 py-4 rounded-xl text-xs font-bold flex items-center">
            <i data-feather="check-circle" class="w-4 h-4 mr-3"></i>
            {{ session('success') }}
        </div>
    @endif

    <div class="flex flex-col sm:flex-row sm:items-end justify-between gap-4">
        <div>
            <div class="text-[10px] font-black text-primary uppercase tracking-widest mb-1">Partnership Care</div>
            <div class="text-2xl font-bold text-gray-900">Designer Support Hub</div>
            <div class="text-xs text-gray-500 mt-1">Bantu desainer mengatasi kendala unggah portofolio, pencairan dana, dan sistem proyek.</div>
        </div>
        
        <a href="{{ route('admin.designer-chat') }}" class="bg-primary text-white px-5 py-2.5 rounded-xl text-[10px] font-black uppercase tracking-widest shadow-sm hover:bg-[#8A5229] transition-all inline-flex items-center gap-2 self-start sm:self-auto">
            <i data-feather="message-circle" class="w-4 h-4"></i>
            Live Chats
        </a>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <div class="bg-white p-6 rounded-xl border border-gray-100 shadow-sm transition-transform hover:-translate-y-1 relative overflow-hidden">
            <div class="absolute bottom-0 left-0 right-0 h-1 bg-red-500"></div>
            <div class="w-10 h-10 rounded-lg bg-red-50 text-red-500 flex items-center justify-center mb-4">
                <i data-feather="alert-circle" class="w-5 h-5"></i>
            </div>
            <h3 class="text-2xl font-bold text-gray-900"><?= str_pad($stats['open'], 2, '0', STR_PAD_LEFT) ?></h3>
            <p class="text-[10px] text-gray-400 font-bold uppercase tracking-wider mt-1">Open</p>
            <p class="text-[10px] font-black text-gray-500 mt-1">butuh respon cepat</p>
        </div>
        <div class="bg-white p-6 rounded-xl border border-gray-100 shadow-sm transition-transform hover:-translate-y-1 relative overflow-hidden">
            <div class="absolute bottom-0 left-0 right-0 h-1 bg-blue-500"></div>
            <div class="w-10 h-10 rounded-lg bg-blue-50 text-blue-500 flex items-center justify-center mb-4">
                <i data-feather="message-square" class="w-5 h-5"></i>
            </div>
            <h3 class="text-2xl font-bold text-gray-900"><?= str_pad($stats['replied'], 2, '0', STR_PAD_LEFT) ?></h3>
            <p class="text-[10px] text-gray-400 font-bold uppercase tracking-wider mt-1">Replied</p>
            <p class="text-[10px] font-black text-gray-500 mt-1">menunggu respon balik</p>
        </div>
        <div class="bg-white p-6 rounded-xl border border-gray-100 shadow-sm transition-transform hover:-translate-y-1 relative overflow-hidden">
            <div class="absolute bottom-0 left-0 right-0 h-1 bg-green-500"></div>
            <div class="w-10 h-10 rounded-lg bg-green-50 text-green-500 flex items-center justify-center mb-4">
                <i data-feather="check-circle" class="w-5 h-5"></i>
            </div>
            <h3 class="text-2xl font-bold text-gray-900"><?= str_pad($stats['resolved'], 2, '0', STR_PAD_LEFT) ?></h3>
            <p class="text-[10px] text-gray-400 font-bold uppercase tracking-wider mt-1">Resolved</p>
            <p class="text-[10px] font-black text-gray-500 mt-1">kasus selesai</p>
        </div>
        <div class="bg-white p-6 rounded-xl border border-gray-100 shadow-sm transition-transform hover:-translate-y-1 relative overflow-hidden">
            <div class="absolute bottom-0 left-0 right-0 h-1 bg-primary"></div>
            <div class="w-10 h-10 rounded-lg bg-orange-50 text-primary flex items-center justify-center mb-4">
                <i data-feather="layers" class="w-5 h-5"></i>
            </div>
            <h3 class="text-2xl font-bold text-gray-900"><?= str_pad($totalTickets, 2, '0', STR_PAD_LEFT) ?></h3>
            <p class="text-[10px] text-gray-400 font-bold uppercase tracking-wider mt-1">Total Tiket</p>
            <p class="text-[10px] font-black text-gray-500 mt-1">semua laporan</p>
        </div>
    </div>

    <!-- Table Card with Filter & Pagination -->
    <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
        <div class="p-5 border-b border-gray-100 flex flex-col lg:flex-row lg:items-center justify-between gap-4">
            <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-3 flex-1">
                <div class="relative flex-1 max-w-md">
                    <i data-feather="search" class="w-4 h-4 absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-400"></i>
                    <input type="text" id="ticketSearch" value="{{ request('search') }}" class="w-full pl-10 pr-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-xs font-semibold focus:outline-none focus:border-primary focus:bg-white transition-colors" placeholder="Cari ID tiket, nama desainer, atau subjek…">
                </div>
            </div>
            <div class="flex gap-1 bg-gray-50 border border-gray-200 rounded-xl p-1 self-start lg:self-auto">
                <a href="{{ route('admin.designer-support', ['status' => 'semua']) }}" class="px-3.5 py-1.5 rounded-lg text-[10px] font-bold uppercase tracking-widest transition-colors {{ $statusFilter == 'semua' ? 'bg-white text-primary shadow-sm' : 'text-gray-500 hover:text-primary' }}">Semua</a>
                <a href="{{ route('admin.designer-support', ['status' => 'pending']) }}" class="px-3.5 py-1.5 rounded-lg text-[10px] font-bold uppercase tracking-widest transition-colors {{ $statusFilter == 'pending' ? 'bg-white text-primary shadow-sm' : 'text-gray-500 hover:text-primary' }}">Open</a>
                <a href="{{ route('admin.designer-support', ['status' => 'replied']) }}" class="px-3.5 py-1.5 rounded-lg text-[10px] font-bold uppercase tracking-widest transition-colors {{ $statusFilter == 'replied' ? 'bg-white text-primary shadow-sm' : 'text-gray-500 hover:text-primary' }}">Replied</a>
                <a href="{{ route('admin.designer-support', ['status' => 'resolved']) }}" class="px-3.5 py-1.5 rounded-lg text-[10px] font-bold uppercase tracking-widest transition-colors {{ $statusFilter == 'resolved' ? 'bg-white text-primary shadow-sm' : 'text-gray-500 hover:text-primary' }}">Resolved</a>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50/70 border-b border-gray-100 text-[9px] font-black text-gray-400 uppercase tracking-widest">
                        <th class="py-3.5 px-6">ID Tiket</th>
                        <th class="py-3.5 px-6">Desainer</th>
                        <th class="py-3.5 px-6">Subjek & Pesan</th>
                        <th class="py-3.5 px-6">Waktu</th>
                        <th class="py-3.5 px-6">Status</th>
                        <th class="py-3.5 px-6 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 text-xs" id="ticketTableBody">
                    @forelse ($tickets as $t)
                    @php
                        $sl = strtolower($t->status);
                        $selectCls = match($sl) {
                            'pending' => 'bg-red-50 text-red-600 border-red-200',
                            'replied' => 'bg-blue-50 text-blue-600 border-blue-200',
                            'resolved' => 'bg-green-50 text-green-600 border-green-200',
                            default => 'bg-gray-50 text-gray-600 border-gray-200'
                        };
                    @endphp
                    <tr class="ticket-row hover:bg-gray-50/70 transition-colors"
                        data-search="{{ strtolower('#' . str_pad($t->id, 4, '0', STR_PAD_LEFT) . ' ' . $t->user->full_name . ' ' . $t->subject . ' ' . $t->message) }}">
                        <td class="py-3.5 px-6">
                            <span class="inline-block px-2.5 py-1 bg-orange-50 text-primary text-[10px] font-black uppercase tracking-widest rounded-md font-mono">#{{ str_pad($t->id, 4, '0', STR_PAD_LEFT) }}</span>
                        </td>
                        <td class="py-3.5 px-6">
                            <div class="font-bold text-gray-900">{{ $t->user->full_name }}</div>
                            <div class="text-[10px] font-mono text-gray-400 mt-0.5">{{ $t->user->email }}</div>
                        </td>
                        <td class="py-3.5 px-6 max-w-md">
                            <div class="font-bold text-gray-900 truncate">{{ $t->subject }}</div>
                            <div class="text-[11px] text-gray-500 line-clamp-1 mt-0.5">{{ $t->message }}</div>
                        </td>
                        <td class="py-3.5 px-6 text-[11px] font-semibold text-gray-500">
                            {{ $t->created_at->diffForHumans() }}
                        </td>
                        <td class="py-3.5 px-6 w-[140px]">
                            <select class="w-full px-3 py-1.5 rounded-lg text-[10px] font-black uppercase tracking-widest border focus:outline-none transition-colors cursor-pointer {{ $selectCls }}" onchange="updateTicketStatus(this, {{ $t->id }})">
                                <option value="pending" {{ $sl === 'pending' ? 'selected' : '' }}>Open</option>
                                <option value="replied" {{ $sl === 'replied' ? 'selected' : '' }}>Replied</option>
                                <option value="resolved" {{ $sl === 'resolved' ? 'selected' : '' }}>Resolved</option>
                            </select>
                        </td>
                        <td class="py-3.5 px-6 text-right">
                            <button class="inline-flex items-center justify-center px-3.5 py-2 rounded-lg text-[10px] font-bold bg-primary text-white hover:bg-[#8A5229] transition-colors whitespace-nowrap"
                                    onclick="openChat('{{ $t->id }}', '{{ addslashes($t->user->full_name) }}', '{{ addslashes($t->subject) }}', '{{ addslashes($t->message) }}', '{{ addslashes($t->admin_reply) }}')">
                                <i data-feather="message-circle" class="w-3.5 h-3.5 mr-1.5"></i> View & Reply
                            </button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center py-12 text-gray-400">
                            <i data-feather="inbox" class="w-10 h-10 mx-auto mb-2 opacity-50"></i>
                            <p class="text-xs font-semibold">Belum ada tiket bantuan dari desainer.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="px-6 py-4 border-t border-gray-100 bg-gray-50/50 flex flex-col sm:flex-row items-center justify-between gap-3">
            <div class="text-xs font-semibold text-gray-500" id="pageInfo">Menampilkan tiket bantuan</div>
            <div class="flex items-center gap-1.5" id="paginationControls"></div>
        </div>
    </div>

    </div>
    @include("Admin.partials.footer")
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

        <div class="chat-body" id="chatWindow"></div>

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

    const allRows = Array.from(document.querySelectorAll('#ticketTableBody .ticket-row'));
    const searchInput = document.getElementById('ticketSearch');
    const pageInfo = document.getElementById('pageInfo');
    const paginationControls = document.getElementById('paginationControls');
    const perPage = 5;
    let currentPage = 1;

    function renderTable() {
        const term = (searchInput?.value || '').toLowerCase().trim();

        const filtered = allRows.filter(row => {
            const s = row.getAttribute('data-search') || '';
            return !term || s.includes(term);
        });

        const total = filtered.length;
        const totalPages = Math.max(1, Math.ceil(total / perPage));
        if (currentPage > totalPages) currentPage = totalPages;

        allRows.forEach(r => r.style.display = 'none');
        const start = (currentPage - 1) * perPage;
        const end = Math.min(start + perPage, total);
        filtered.slice(start, end).forEach(r => r.style.display = '');

        pageInfo.textContent = total === 0
            ? 'Tidak ada tiket yang cocok dengan filter'
            : `Halaman ${currentPage} dari ${totalPages} — Menampilkan ${start + 1}–${end} dari ${total} tiket`;

        paginationControls.innerHTML = '';
        if (totalPages > 1) {
            for (let i = 1; i <= totalPages; i++) {
                const btn = document.createElement('button');
                btn.textContent = i;
                btn.className = `w-8 h-8 rounded-lg text-xs font-bold transition-colors ${i === currentPage ? 'bg-primary text-white shadow-sm' : 'bg-white border border-gray-200 text-gray-600 hover:border-primary hover:text-primary'}`;
                btn.onclick = () => { currentPage = i; renderTable(); };
                paginationControls.appendChild(btn);
            }
        }
    }

    searchInput?.addEventListener('input', () => { currentPage = 1; renderTable(); });
    renderTable();

    function updateTicketStatus(sel, id) {
        const val = sel.value;
        const clsMap = {
            'pending': 'bg-red-50 text-red-600 border-red-200',
            'replied': 'bg-blue-50 text-blue-600 border-blue-200',
            'resolved': 'bg-green-50 text-green-600 border-green-200'
        };
        sel.className = `w-full px-3 py-1.5 rounded-lg text-[10px] font-black uppercase tracking-widest border focus:outline-none transition-colors cursor-pointer ${clsMap[val]}`;

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
                <div class="text-[9px] text-gray-500 font-bold uppercase tracking-widest mb-1 pl-1">${name} · Designer</div>
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
