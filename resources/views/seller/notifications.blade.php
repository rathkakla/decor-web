<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Decor Seller - Notifications</title>
    <script src="https://cdn.tailwindcss.com"></script>
    @include('seller.partials.head')
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&display=swap');
        body { background-color: #F8F6F4; font-family: 'Plus Jakarta Sans', sans-serif; overflow-x: hidden; }
        .bg-primary { background-color: #B5733A; }
        .text-primary { color: #B5733A; }
        .active-link { background-color: rgba(181, 115, 58, 0.1); color: #B5733A; border-right: 4px solid #B5733A; }
        .sidebar-transition { transition: transform 0.3s ease-in-out, margin 0.3s ease-in-out; }
        .sidebar-hidden { transform: translateX(-100%); }
        .main-expanded { margin-left: 0 !important; }
    </style>
</head>
<body class="text-gray-800">

    @include('seller.partials.sidebar')

    <main id="main-content" class="flex-1 flex flex-col ml-0 md:ml-64 sidebar-transition min-h-screen">
        @include('seller.partials.header', ['title' => 'Notifications Center'])

        <div class="p-4 sm:p-6 lg:p-8 space-y-8 flex-1">
            <div>
                <div class="text-[10px] font-bold text-primary uppercase tracking-widest mb-1">Activity Feed</div>
                <h2 class="text-2xl font-bold text-gray-800 tracking-tight">Pemberitahuan</h2>
                <p class="text-xs text-gray-500 mt-1">Pantau aktivitas terbaru toko dan pembaruan pesanan Anda.</p>
            </div>

            <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
                <div class="divide-y divide-gray-50">
                    @forelse($notifications as $notification)
                        <div class="p-5 sm:p-6 hover:bg-gray-50/50 transition-all flex items-start gap-4 sm:gap-6 {{ $notification->read_at ? 'opacity-60' : '' }}">
                            <div class="w-11 h-11 sm:w-12 sm:h-12 rounded-xl bg-primary/10 flex items-center justify-center shrink-0">
                                <i class="fa-solid {{ $notification->data['icon'] ?? 'fa-bell' }} text-primary text-lg"></i>
                            </div>
                            <div class="flex-1 min-w-0">
                                <div class="flex flex-col sm:flex-row sm:justify-between sm:items-start gap-1 sm:gap-4">
                                    <h4 class="text-sm font-bold text-gray-800 uppercase tracking-tight">
                                        {{ ($notification->data['type'] ?? null) == 'new_order' ? 'Pesanan Pending Masuk' : 'Notification' }}
                                    </h4>
                                    <span class="text-[10px] font-bold text-gray-300 uppercase whitespace-nowrap">{{ $notification->created_at->diffForHumans() }}</span>
                                </div>
                                <p class="text-xs font-medium text-gray-500 mt-1 leading-relaxed">
                                    {{ $notification->data['message'] ?? 'Ada aktivitas baru di akun Anda.' }}
                                </p>

                                @if(isset($notification->data['order_id']))
                                    <div class="mt-4">
                                        <a href="{{ route('seller.orders.show', $notification->data['order_id']) }}" class="inline-flex items-center text-[10px] font-bold text-primary uppercase tracking-widest hover:opacity-70 transition-all">
                                            Lihat Detail Pesanan <i class="fa-solid fa-arrow-right ml-2 text-[8px]"></i>
                                        </a>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @empty
                        <div class="p-16 text-center">
                            <div class="w-16 h-16 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-5">
                                <i class="fa-regular fa-bell text-2xl text-gray-200"></i>
                            </div>
                            <p class="text-gray-400 font-bold text-sm">Belum ada notifikasi baru untuk Anda.</p>
                        </div>
                    @endforelse
                </div>

                @if($notifications->hasPages())
                    @include('partials.pagination', ['paginator' => $notifications, 'label' => 'notifikasi'])
                @endif
            </div>
        </div>

        @include('partials.footer')
    </main>

    <script>
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