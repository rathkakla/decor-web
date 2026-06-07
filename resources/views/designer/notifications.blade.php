<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Decor Designer - Notifikasi</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&display=swap');
        body { background-color: #F8F6F4; font-family: 'Plus Jakarta Sans', sans-serif; overflow-x: hidden; }
        .bg-primary { background-color: #B5733A; }
        .text-primary { color: #B5733A; }
        .active-link { background-color: rgba(181, 115, 58, 0.1); color: #B5733A; border-right: 4px solid #B5733A; }
    </style>
</head>
<body class="text-gray-800">

    @include('designer.partials.sidebar')

    <main class="ml-64 p-12">
        <div class="max-w-4xl">
            <div class="mb-12">
                <h2 class="text-3xl font-black text-gray-900 tracking-tight uppercase italic">Notifications Center</h2>
                <p class="text-xs text-gray-400 font-bold uppercase tracking-[0.2em] mt-2">Daftar peringatan dan informasi sistem</p>
            </div>

            <div class="space-y-4">
                @forelse($notifications as $notification)
                <div class="bg-white p-8 rounded-[32px] border {{ $notification->read_at ? 'border-gray-100 opacity-60' : 'border-primary/20 shadow-xl shadow-primary/5' }} transition-all">
                    <div class="flex items-start gap-6">
                        <div class="w-12 h-12 rounded-2xl {{ ($notification->data['type'] ?? '') == 'warning' ? 'bg-red-50 text-red-500' : 'bg-primary/10 text-primary' }} flex items-center justify-center flex-shrink-0">
                            <i class="fa-solid fa-{{ $notification->data['icon'] ?? 'bell' }} text-xl"></i>
                        </div>
                        <div class="flex-1">
                            <div class="flex justify-between items-start">
                                <h3 class="text-lg font-black text-gray-900 tracking-tight">{{ $notification->data['title'] }}</h3>
                                <span class="text-[10px] text-gray-400 font-bold uppercase tracking-widest">{{ $notification->created_at->diffForHumans() }}</span>
                            </div>
                            <p class="text-sm text-gray-500 mt-2 leading-relaxed">{{ $notification->data['message'] }}</p>
                        </div>
                    </div>
                </div>
                @empty
                <div class="text-center py-24 bg-white rounded-[48px] border border-dashed border-gray-200">
                    <p class="text-gray-300 font-black uppercase tracking-[0.3em] italic">No Notifications Yet</p>
                </div>
                @endforelse

                <div class="mt-8">
                    {{ $notifications->links() }}
                </div>
            </div>
        </div>
    </main>

</body>
</html>