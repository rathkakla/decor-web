<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Decor Seller - Notifications</title>
    <script src="https://cdn.tailwindcss.com"></script>
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

    <main id="main-content" class="flex-1 flex flex-col ml-64 sidebar-transition min-h-screen">
        @include('seller.partials.header', ['title' => 'Notifications Center'])

        <div class="p-10 space-y-8 flex-1">
            <div class="flex justify-between items-end">
                <div>
                    <h2 class="text-4xl font-extrabold text-gray-800 tracking-tight">Pemberitahuan</h2>
                    <p class="text-sm text-gray-400 mt-2 font-medium">Keep track of your store's latest activities and order updates.</p>
                </div>
            </div>

            <div class="bg-white rounded-[32px] border border-gray-100 shadow-sm overflow-hidden">
                <div class="divide-y divide-gray-50">
                    @forelse($notifications as $notification)
                        <div class="p-8 hover:bg-gray-50/50 transition-all flex items-start space-x-6 {{ $notification->read_at ? 'opacity-60' : '' }}">
                            <div class="w-12 h-12 rounded-2xl bg-primary/10 flex items-center justify-center shrink-0">
                                <i class="fa-solid {{ $notification->data['icon'] ?? 'fa-bell' }} text-primary text-xl"></i>
                            </div>
                            <div class="flex-1">
                                <div class="flex justify-between items-start">
                                    <h4 class="text-sm font-black text-gray-800 uppercase tracking-tight">
                                        {{ $notification->data['type'] == 'new_order' ? 'Pesanan Pending Masuk' : 'Notification' }}
                                    </h4>
                                    <span class="text-[10px] font-bold text-gray-300 uppercase">{{ $notification->created_at->diffForHumans() }}</span>
                                </div>
                                <p class="text-xs font-bold text-gray-500 mt-1 leading-relaxed">
                                    {{ $notification->data['message'] ?? 'Ada aktivitas baru di akun Anda.' }}
                                </p>
                                
                                @if(isset($notification->data['order_id']))
                                    <div class="mt-4">
                                        <a href="{{ route('seller.orders.show', $notification->data['order_id']) }}" class="inline-flex items-center text-[10px] font-black text-primary uppercase tracking-widest hover:opacity-70 transition-all">
                                            Lihat Detail Pesanan <i class="fa-solid fa-arrow-right ml-2 text-[8px]"></i>
                                        </a>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @empty
                        <div class="p-20 text-center">
                            <div class="w-20 h-20 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-6">
                                <i class="fa-regular fa-bell text-3xl text-gray-200"></i>
                            </div>
                            <p class="text-gray-400 font-bold">Belum ada notifikasi baru untuk Anda.</p>
                        </div>
                    @endforelse
                </div>

                @if($notifications->hasPages())
                    <div class="p-8 border-t border-gray-50">
                        {{ $notifications->links() }}
                    </div>
                @endif
            </div>
        </div>

        <footer class="p-8 border-t border-gray-100 text-[10px] font-bold text-gray-400 uppercase tracking-widest mt-auto">
            Â© 2026 DECOR MERCHANT SERVICE CENTER. ALL RIGHTS RESERVED.
        </footer>
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