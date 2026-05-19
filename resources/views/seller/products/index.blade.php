<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Decor Seller - Kelola Produk</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&display=swap');
        body { background-color: #F8F6F4; font-family: 'Plus Jakarta Sans', sans-serif; overflow-x: hidden; }
        .bg-primary { background-color: #B5733A; }
        .text-primary { color: #B5733A; }
        
        /* Active Link: Cokelat Pastel */
        .active-link { background-color: rgba(181, 115, 58, 0.1); color: #B5733A; border-right: 4px solid #B5733A; }
        
        /* Animasi Sidebar */
        .sidebar-transition { transition: transform 0.3s ease-in-out, margin 0.3s ease-in-out; }
        .sidebar-hidden { transform: translateX(-100%); }
        .main-expanded { margin-left: 0 !important; }
    </style>
</head>
<body class="text-gray-800">

    @include('seller.partials.sidebar')

    <main id="main-content" class="flex-1 flex flex-col ml-64 sidebar-transition min-h-screen">
        
        @include('seller.partials.header', ['title' => 'Inventory Gallery'])

        <div class="p-8 space-y-8 flex-1">
            <div class="flex justify-between items-end">
                <div>
                    <h2 class="text-3xl font-bold mt-1 tracking-tight text-gray-800">Kelola Produk</h2>
                    <p class="text-xs text-gray-400 mt-1 font-medium italic">Review, organize and manage your premium inventory gallery.</p>
                </div>
                <a href="{{ route('seller.products.create') }}" class="bg-primary text-white px-6 py-2.5 rounded-lg text-sm font-bold flex items-center shadow-lg hover:opacity-90 transition-all">
                    <i class="fa-solid fa-plus mr-2"></i> Tambah Produk
                </a>
            </div>

            <div class="flex space-x-4">
                <div class="flex-1 relative">
                    <i class="fa-solid fa-magnifying-glass absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"></i>
                    <input type="text" placeholder="Cari nama produk....." class="w-full bg-white border border-gray-100 rounded-xl py-3 pl-10 pr-4 text-sm focus:outline-none focus:border-primary transition-all">
                </div>
            </div>

            <div class="bg-white rounded-2xl border border-gray-100 overflow-hidden shadow-sm">
                <table class="w-full text-left">
                    <thead class="bg-gray-50 border-b border-gray-100">
                        <tr>
                            <th class="px-6 py-4 text-[10px] font-bold text-gray-400 uppercase tracking-widest">Produk</th>
                            <th class="px-6 py-4 text-[10px] font-bold text-gray-400 uppercase tracking-widest">SKU</th>
                            <th class="px-6 py-4 text-[10px] font-bold text-gray-400 uppercase tracking-widest text-center">Validation</th>
                            <th class="px-6 py-4 text-[10px] font-bold text-gray-400 uppercase tracking-widest">Harga</th>
                            <th class="px-6 py-4 text-[10px] font-bold text-gray-400 uppercase tracking-widest">Stock Status</th>
                            <th class="px-6 py-4 text-[10px] font-bold text-gray-400 uppercase tracking-widest text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50 text-gray-800">
    @forelse($products as $p)
    <tr class="hover:bg-gray-50 transition-colors group">
        <td class="px-6 py-4 flex items-center space-x-4">
            <img src="{{ $p->images->first()->img_url ?? 'https://via.placeholder.com/100' }}" class="w-12 h-12 rounded-lg object-cover">
            <div>
                <h4 class="text-sm font-bold">{{ $p->name }}</h4>
                <p class="text-[10px] text-gray-400 font-medium">{{ $p->category->name ?? 'Uncategorized' }}</p>
            </div>
        </td>
        
        <td class="px-6 py-4 text-xs font-bold text-primary uppercase tracking-tighter">
            {{ $p->style ?? 'N/A' }}
        </td>
        
        <td class="px-6 py-4 text-center">
            @php
                $statusMap = [
                    'pending'  => ['bg-yellow-50 text-yellow-600', 'PENDING'],
                    'approved' => ['bg-green-50 text-green-600', 'APPROVED'],
                    'rejected' => ['bg-red-50 text-red-600', 'REJECTED'],
                ];
                $sData = $statusMap[$p->status] ?? ['bg-gray-50 text-gray-600', strtoupper($p->status)];
            @endphp
            <span class="{{ $sData[0] }} text-[9px] font-black px-2.5 py-1 rounded-md uppercase tracking-widest border border-current opacity-80">
                {{ $sData[1] }}
            </span>
        </td>

        <td class="px-6 py-4 text-sm font-bold text-primary">Rp {{ number_format($p->price, 0, ',', '.') }}</td>
        
        <td class="px-6 py-4">
            @php 
                if($p->stock > 10) {
                    $badge = 'bg-green-50 text-green-600';
                } elseif($p->stock > 0) {
                    $badge = 'bg-orange-50 text-orange-600';
                } else {
                    $badge = 'bg-red-50 text-red-600';
                }
            @endphp
            <span class="{{ $badge }} text-[10px] font-bold px-3 py-1 rounded-full flex items-center w-fit">
                <span class="w-1 h-1 rounded-full bg-current mr-2"></span> Stock: {{ $p->stock }}
            </span>
        </td>

        <td class="px-6 py-4 text-right">
            <div class="flex justify-end space-x-3">
                <a href="{{ route('seller.products.edit', $p->id) }}" class="text-gray-300 hover:text-primary transition-all">
                    <i class="fa-solid fa-pen-to-square text-sm"></i>
                </a>
                
                <form action="{{ route('seller.products.delete', $p->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus {{ $p->name }}?');" class="inline-block">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="text-gray-300 hover:text-red-500 transition-all">
                        <i class="fa-solid fa-trash-can text-sm"></i>
                    </button>
                </form>
            </div>
        </td>
    </tr>
    @empty
    <tr>
        <td colspan="6" class="px-6 py-8 text-center text-gray-400">
            Belum ada produk. Silakan tambah produk baru.
        </td>
    </tr>
    @endforelse
</tbody>
                </table>
                <div class="px-6 py-4 bg-gray-50 flex justify-between items-center text-[10px] font-bold text-gray-400 uppercase tracking-widest border-t border-gray-100">
                    <p>Menampilkan {{ count($products) }} dari 48 produk</p>
                    <div class="flex space-x-2">
                        <button class="w-7 h-7 flex items-center justify-center rounded bg-primary text-white shadow-md">1</button>
                        <button class="w-7 h-7 flex items-center justify-center rounded hover:bg-gray-200 transition-colors">2</button>
                        <button class="w-7 h-7 flex items-center justify-center rounded hover:bg-gray-200 transition-colors"><i class="fa-solid fa-chevron-right text-[8px]"></i></button>
                    </div>
                </div>
            </div>
        </div>
        
        <footer class="p-8 border-t border-gray-100 text-[10px] font-bold text-gray-400 uppercase tracking-widest mt-auto text-left">
            © 2026 DECOR MARKETPLACE SELLER PORTAL. ALL RIGHTS RESERVED.
        </footer>
    </main>

    <script>
        // ALERT ACTIONS
        function confirmAction(action, name) {
            return confirm(`Apakah Anda yakin ingin ${action} "${name}"?`);
        }

        function confirmDelete(name) {
            if (confirm(`⚠️ PERINGATAN: Apakah Anda yakin ingin menghapus "${name}"? Data yang sudah dihapus tidak dapat dikembalikan.`)) {
                alert(`Produk "${name}" telah berhasil dihapus dari galeri.`);
            }
        }

        // SIDEBAR TOGGLE
        const btn = document.getElementById('toggle-sidebar');
        const sidebar = document.getElementById('sidebar');
        const main = document.getElementById('main-content');
        btn.addEventListener('click', () => { 
            sidebar.classList.toggle('sidebar-hidden'); 
            main.classList.toggle('main-expanded'); 
        });
    </script>
</body>
</html>