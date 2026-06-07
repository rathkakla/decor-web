<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Decor - My Notifications</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&display=swap');
        body { background-color: #F8F6F4; font-family: 'Plus Jakarta Sans', sans-serif; }
        .bg-primary { background-color: #B5733A; }
        .text-primary { color: #B5733A; }
    </style>
</head>
<body class="text-gray-800">

    <nav class="bg-white border-b border-gray-100 sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-8 h-20 flex items-center justify-between">
            <a href="{{ route('customer.homepage') }}" class="text-2xl font-black text-primary tracking-widest uppercase">DECOR</a>
            <div class="flex items-center space-x-6">
                <a href="{{ route('customer.cart') }}" class="text-gray-400 hover:text-primary transition-colors"><i class="fa-solid fa-cart-shopping"></i></a>
                <a href="{{ route('customer.profile') }}" class="w-10 h-10 rounded-full bg-gray-100 flex items-center justify-center overflow-hidden border border-gray-100">
                    <img src="{{ Auth::user()->avatar_url }}" class="w-full h-full object-cover">
                </a>
            </div>
        </div>
    </nav>

    <main class="max-w-4xl mx-auto px-8 py-12 min-h-screen">
        <div class="mb-10">
            <h1 class="text-4xl font-extrabold text-gray-900 tracking-tight">Pemberitahuan</h1>
            <p class="text-gray-400 mt-2 font-medium">Informasi terbaru mengenai pesanan dan aktivitas Anda.</p>
        </div>

        <div class="bg-white rounded-[40px] border border-gray-100 shadow-sm overflow-hidden">
            <div class="divide-y divide-gray-50">
                @forelse($notifications as $notification)
                    <div class="p-8 hover:bg-gray-50/50 transition-all flex items-start space-x-6 {{ $notification->read_at ? 'opacity-60' : '' }}">
                        <div class="w-12 h-12 rounded-2xl bg-primary/10 flex items-center justify-center shrink-0">
                            <i class="fa-solid {{ $notification->data['icon'] ?? 'fa-bell' }} text-primary text-xl"></i>
                        </div>
                        <div class="flex-1">
                            <div class="flex justify-between items-start">
                                <h4 class="text-sm font-black text-gray-800 uppercase tracking-tight">
                                    {{ $notification->data['type'] ?? 'Aktivitas' }}
                                </h4>
                                <span class="text-[10px] font-bold text-gray-300 uppercase">{{ $notification->created_at->diffForHumans() }}</span>
                            </div>
                            <p class="text-xs font-bold text-gray-500 mt-1 leading-relaxed">
                                {{ $notification->data['message'] ?? 'Ada update baru untuk Anda.' }}
                            </p>
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
    </main>

    <footer class="bg-white border-t border-gray-100 py-12 mt-20">
        <div class="max-w-7xl mx-auto px-8 text-center">
            <p class="text-[10px] font-bold text-gray-400 uppercase tracking-[0.3em]">Â© 2026 DECOR LUXURY FURNITURE. ALL RIGHTS RESERVED.</p>
        </div>
    </footer>

</body>
</html>