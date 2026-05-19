<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Decor Admin - Notifikasi</title>
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

    <main class="p-12 max-w-5xl mx-auto">
        <div class="mb-12">
            <h2 class="text-3xl font-bold uppercase tracking-tight">System Logs & Notifications</h2>
            <p class="text-gray-400 text-sm mt-1">Admin system alerts.</p>
        </div>

        <div class="space-y-4">
            @forelse($notifications as $notification)
            <div class="bg-white p-6 rounded-xl border {{ $notification->read_at ? 'border-gray-100 opacity-60' : 'border-primary/20 shadow-lg shadow-primary/5' }} transition-all">
                <div class="flex items-start gap-4">
                    <div class="w-10 h-10 rounded-lg {{ ($notification->data['type'] ?? '') == 'warning' ? 'bg-red-50 text-red-500' : 'bg-primary/10 text-primary' }} flex items-center justify-center flex-shrink-0">
                        <i class="fa-solid fa-{{ $notification->data['icon'] ?? 'bell' }}"></i>
                    </div>
                    <div class="flex-1">
                        <div class="flex justify-between items-start">
                            <h3 class="font-bold text-gray-900">{{ $notification->data['title'] }}</h3>
                            <span class="text-[10px] text-gray-400 font-bold uppercase tracking-widest">{{ $notification->created_at->diffForHumans() }}</span>
                        </div>
                        <p class="text-sm text-gray-600 mt-1 leading-relaxed">{{ $notification->data['message'] }}</p>
                    </div>
                </div>
            </div>
            @empty
            <div class="text-center py-20 bg-white rounded-xl border border-dashed border-gray-200">
                <p class="text-gray-400 font-bold uppercase tracking-widest text-xs">No notifications</p>
            </div>
            @endforelse
        </div>
    </main>

</body>
</html>