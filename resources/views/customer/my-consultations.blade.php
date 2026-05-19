@php $site_name = "DECOR"; @endphp
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Consultations — {{ $site_name }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: { primary: '#B5733A', secondary: '#E3DCD6' }
                }
            }
        }
    </script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;600;700;800&display=swap');
        body { font-family: 'Plus Jakarta Sans', sans-serif; background-color: #ffffff; }
        .content-container { max-width: 1200px; margin: 0 auto; }
    </style>
</head>
<body class="text-gray-800">

    <header class="bg-white border-b border-gray-100 sticky top-0 z-50">
        <div class="content-container flex justify-between items-center py-4 px-6">
            <a href="{{ route('homepage') }}" class="text-2xl font-black tracking-tighter uppercase text-primary hover:opacity-80 transition-all">
                <?= $site_name ?>
            </a>
            <nav class="hidden md:flex items-center space-x-10 text-[13px] font-medium text-gray-500 tracking-wide">
                <a href="{{ route('customer.catalog') }}" class="hover:text-primary transition-all">Collections</a>
                <a href="{{ route('customer.designers') }}" class="hover:text-primary transition-all">Designers</a>
                 <a href="{{ route('customer.design-lab') }}" class="hover:text-primary transition-all">AI Studio</a>
            </nav>
            <div class="flex items-center space-x-6">
                <a href="{{ route('customer.profile') }}" class="w-9 h-9 rounded-md overflow-hidden border border-gray-200">
                    <img src="{{ Auth::user()->avatar_url }}" class="w-full h-full object-cover">
                </a>
            </div>
        </div>
    </header>

    <main class="py-16 content-container px-6">
        <div class="mb-12">
            <h1 class="text-4xl font-extrabold tracking-tighter mb-2">My Consultations</h1>
            <p class="text-gray-400 text-xs font-medium uppercase tracking-widest italic">Track your design journeys with our experts.</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @forelse($consultations as $c)
            <div class="bg-white rounded-[32px] border border-gray-100 shadow-sm overflow-hidden group hover:shadow-xl hover:shadow-gray-200/50 transition-all duration-500">
                <div class="h-48 relative overflow-hidden bg-gray-100">
                    <img src="{{ $c->cover_image }}" class="w-full h-full object-cover group-hover:scale-105 transition duration-700">
                    <div class="absolute top-4 right-4">
                        @php
                            $statusLabel = \App\Models\Consultation::getStatusLabel($c->status);
                            $statusColor = match($c->status) {
                                4 => 'bg-green-500',
                                6 => 'bg-red-500',
                                7 => 'bg-orange-500',
                                default => 'bg-primary'
                            };
                        @endphp
                        <span class="{{ $statusColor }} text-white text-[8px] font-black uppercase tracking-widest px-4 py-2 rounded-full shadow-lg">
                            {{ $statusLabel }}
                        </span>
                    </div>
                </div>
                <div class="p-8">
                    <div class="flex items-center space-x-3 mb-6">
                        <img src="{{ $c->designer->designer_image ? asset('storage/'.$c->designer->designer_image) : 'https://ui-avatars.com/api/?name='.urlencode($c->designer->user->full_name) }}" class="w-8 h-8 rounded-lg object-cover">
                        <div>
                            <p class="text-[9px] font-black text-gray-400 uppercase tracking-widest">Designer</p>
                            <p class="text-[11px] font-bold text-gray-800">{{ $c->designer->user->full_name }}</p>
                        </div>
                    </div>
                    <h3 class="text-xl font-bold mb-3 tracking-tight group-hover:text-primary transition-colors line-clamp-1">{{ $c->title }}</h3>
                    <p class="text-xs text-gray-400 line-clamp-2 leading-relaxed mb-8 italic">"{{ $c->description }}"</p>
                    
                    <a href="{{ route('customer.chat', $c->id) }}" class="block">
                        <button class="w-full bg-gray-50 text-gray-900 py-4 rounded-xl text-[10px] font-black uppercase tracking-widest border border-gray-100 group-hover:bg-primary group-hover:text-white group-hover:border-primary transition-all">
                            Workspace & Chat
                        </button>
                    </a>
                </div>
            </div>
            @empty
            <div class="col-span-full py-24 text-center bg-gray-50 rounded-[40px] border-2 border-dashed border-gray-100">
                <i class="fa-solid fa-calendar-xmark text-gray-200 text-5xl mb-6"></i>
                <h3 class="text-xl font-bold text-gray-300 uppercase tracking-widest">No consultations yet</h3>
                <p class="text-xs text-gray-400 mt-2">Start your first design project today!</p>
                <a href="{{ route('customer.designers') }}" class="inline-block mt-8 bg-primary text-white px-8 py-4 rounded-xl text-[10px] font-black uppercase tracking-widest shadow-xl shadow-primary/20">
                    Find a Designer
                </a>
            </div>
            @endforelse
        </div>
    </main>

    <footer class="py-20 text-center border-t border-gray-50">
        <p class="text-[10px] font-black text-gray-300 uppercase tracking-widest italic">© 2026 DECOR MARKETPLACE. THE FUTURE OF INTERIOR DESIGN.</p>
    </footer>

</body>
</html>