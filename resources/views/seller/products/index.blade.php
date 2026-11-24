<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Decor Seller - Kelola Produk</title>
    <script src="https://cdn.tailwindcss.com"></script>
    @include('seller.partials.head')
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

        <div class="p-4 sm:p-6 lg:p-8 space-y-6 sm:space-y-8 flex-1">
            @if(session('success'))
            <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-xl relative text-sm font-medium" role="alert">
                <i class="fa-solid fa-circle-check mr-2"></i>
                <span class="block sm:inline">{{ session('success') }}</span>
            </div>
            @endif

            <div class="flex flex-col sm:flex-row sm:items-end justify-between gap-4">
                <div>
                    <div class="text-[10px] font-bold text-primary uppercase tracking-widest mb-1">Inventory Gallery</div>
                    <h2 class="text-2xl font-bold text-gray-900 tracking-tight">Kelola Produk</h2>
                    <p class="text-xs text-gray-500 mt-1">Kelola, atur, dan pantau katalog produk toko Anda.</p>
                </div>
                <a href="{{ route('seller.products.create') }}" class="h-10 px-4 bg-primary text-white rounded-lg text-xs font-semibold inline-flex items-center gap-2 shadow-sm hover:bg-opacity-90 transition-all">
                    <i class="fa-solid fa-plus"></i><span>Tambah Produk</span>
                </a>
            </div>

            <form action="{{ route('seller.products.index') }}" method="GET" class="flex items-center gap-2.5">
                <div class="flex-1 relative">
                    <i class="fa-solid fa-magnifying-glass absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-xs pointer-events-none"></i>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama produk..." class="w-full h-10 bg-white border border-gray-200 rounded-lg pl-9 pr-3 text-xs font-medium text-gray-700 focus:outline-none focus:border-primary focus:ring-2 focus:ring-primary/10 transition-all">
                </div>
                <button type="submit" class="h-10 px-4 bg-primary text-white rounded-lg text-xs font-semibold hover:bg-opacity-90 transition-all">Cari</button>
                @if(request('search'))
                <a href="{{ route('seller.products.index') }}" class="h-10 px-3 inline-flex items-center justify-center border border-gray-200 text-gray-600 hover:border-primary hover:text-primary rounded-lg text-xs font-semibold transition-all">Reset</a>
                @endif
            </form>

            <div class="bg-white rounded-xl border border-gray-100 overflow-hidden shadow-sm">
                <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead class="bg-gray-50 border-b border-gray-100">
                        <tr>
                            <th class="px-4 sm:px-6 py-4 text-[10px] font-bold text-gray-400 uppercase tracking-widest">Produk</th>
                            <th class="px-6 py-4 text-[10px] font-bold text-gray-400 uppercase tracking-widest hidden lg:table-cell">SKU</th>
                            <th class="px-6 py-4 text-[10px] font-bold text-gray-400 uppercase tracking-widest text-center hidden lg:table-cell">Validation</th>
                            <th class="px-6 py-4 text-[10px] font-bold text-gray-400 uppercase tracking-widest hidden lg:table-cell">Harga</th>
                            <th class="px-6 py-4 text-[10px] font-bold text-gray-400 uppercase tracking-widest hidden lg:table-cell">Stock</th>
                            <th class="px-4 sm:px-6 py-4 text-[10px] font-bold text-gray-400 uppercase tracking-widest text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50 text-gray-800">
    @forelse($products as $p)
    @php
        $statusMap = [
            'pending'  => ['bg-yellow-50 text-yellow-600', 'PENDING'],
            'approved' => ['bg-green-50 text-green-600', 'APPROVED'],
            'rejected' => ['bg-red-50 text-red-600', 'REJECTED'],
        ];
        $sData = $statusMap[$p->status] ?? ['bg-gray-50 text-gray-600', strtoupper($p->status)];
        if($p->stock > 10) {
            $stockBadge = 'bg-green-50 text-green-600';
        } elseif($p->stock > 0) {
            $stockBadge = 'bg-orange-50 text-orange-600';
        } else {
            $stockBadge = 'bg-red-50 text-red-600';
        }
    @endphp
    <tr class="hover:bg-gray-50 transition-colors group">
        <td class="px-4 sm:px-6 py-4">
            <div class="flex items-start sm:items-center space-x-3 sm:space-x-4 min-w-0">
                <img src="{{ $p->images->first()->img_url ?? 'https://via.placeholder.com/100' }}" class="w-12 h-12 rounded-lg object-cover shrink-0 mt-0.5 sm:mt-0">
                <div class="min-w-0">
                    <h4 class="text-sm font-bold text-gray-800">{{ $p->name }}</h4>
                    <p class="text-[10px] text-gray-400 font-medium flex flex-wrap items-center gap-2 mt-0.5">
                        {{ $p->category->name ?? 'Uncategorized' }}
                        <span class="text-[9px] font-bold text-gray-500 bg-gray-100 px-1.5 py-0.5 rounded-md whitespace-nowrap">{{ $p->images_count ?? $p->images->count() }} foto</span>
                    </p>
                    <div class="lg:hidden mt-2 flex flex-wrap items-center gap-2">
                        <span class="{{ $sData[0] }} text-[9px] font-bold px-2 py-0.5 rounded-md uppercase tracking-wider border border-current opacity-80">{{ $sData[1] }}</span>
                        <span class="text-xs font-bold text-primary">Rp {{ number_format($p->price, 0, ',', '.') }}</span>
                        <span class="{{ $stockBadge }} text-[9px] font-bold px-2 py-0.5 rounded-full whitespace-nowrap">Stock: {{ $p->stock }}</span>
                    </div>
                </div>
            </div>
        </td>

        <td class="px-6 py-4 text-xs font-bold text-primary uppercase tracking-tighter hidden lg:table-cell">
            {{ $p->style ?? 'N/A' }}
        </td>

        <td class="px-6 py-4 text-center hidden lg:table-cell">
            <span class="{{ $sData[0] }} text-[9px] font-black px-2.5 py-1 rounded-md uppercase tracking-widest border border-current opacity-80">
                {{ $sData[1] }}
            </span>
        </td>

        <td class="px-6 py-4 text-sm font-bold text-primary whitespace-nowrap hidden lg:table-cell">Rp {{ number_format($p->price, 0, ',', '.') }}</td>

        <td class="px-6 py-4 hidden lg:table-cell">
            <span class="{{ $stockBadge }} text-[10px] font-bold px-3 py-1 rounded-full flex items-center w-fit whitespace-nowrap">
                <span class="w-1 h-1 rounded-full bg-current mr-2"></span> Stock: {{ $p->stock }}
            </span>
        </td>

        <td class="px-4 sm:px-6 py-4 text-right">
            <div class="flex justify-end items-center space-x-2">
                <div class="relative group/action">
                    <a href="{{ route('seller.products.edit', $p->id) }}" class="w-9 h-9 rounded-lg border border-gray-200 bg-gray-50 text-gray-500 hover:bg-primary hover:text-white inline-flex items-center justify-center transition-all" aria-label="Edit Produk" title="Edit Produk">
                        <i class="fa-solid fa-pen-to-square text-xs"></i>
                    </a>
                    <span class="pointer-events-none absolute right-full top-1/2 -translate-y-1/2 mr-2 whitespace-nowrap rounded-md bg-gray-900 px-2 py-1 text-[10px] font-semibold text-white opacity-0 transition-opacity group-hover/action:opacity-100 group-focus-within/action:opacity-100 z-20 shadow-sm">Edit Produk</span>
                </div>

                <form action="{{ route('seller.products.delete', $p->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus {{ $p->name }}?');" class="relative group/action inline-block">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="w-9 h-9 rounded-lg border border-red-200 bg-red-50 text-red-500 hover:bg-red-500 hover:text-white inline-flex items-center justify-center transition-all" aria-label="Hapus Produk" title="Hapus Produk">
                        <i class="fa-solid fa-trash-can text-xs"></i>
                    </button>
                    <span class="pointer-events-none absolute right-full top-1/2 -translate-y-1/2 mr-2 whitespace-nowrap rounded-md bg-gray-900 px-2 py-1 text-[10px] font-semibold text-white opacity-0 transition-opacity group-hover/action:opacity-100 group-focus-within/action:opacity-100 z-20 shadow-sm">Hapus Produk</span>
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
                </div>
                @include('partials.pagination', ['paginator' => $products, 'label' => 'produk'])
            </div>
        </div>
        
        @include('partials.footer')
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