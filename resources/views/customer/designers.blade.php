<?php $site_name = "DECOR"; ?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Consult with Visionaries — <?= $site_name ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#B5733A',
                        secondary: '#E3DCD6',
                    }
                }
            }
        }
    </script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;600;700&display=swap');
        body { font-family: 'Plus Jakarta Sans', sans-serif; background-color: #ffffff; }
        .content-container { max-width: 1200px; margin: 0 auto; }
    </style>
</head>
<body class="text-gray-800">

    @include('customer.partials.navbar')

    <main class="py-20 content-container px-6">
        <header class="mb-16">
            <span class="text-[10px] font-bold uppercase tracking-[0.3em] text-primary mb-4 block">Personalized Curation</span>
            <div class="flex flex-col md:flex-row justify-between items-start md:items-end gap-8">
                <h1 class="text-7xl font-bold tracking-tighter leading-[0.9]">Consult with<br><span class="text-primary italic">the</span><br>Visionaries</h1>
                <p class="text-gray-400 text-sm max-w-sm leading-relaxed">
                    Collaborate with world-class interior architects to transform your living spaces into curated masterpieces of form and function.
                </p>
            </div>
        </header>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-10 mb-32">
            @forelse($designers as $designer)
            <div class="group">
                <div class="aspect-[4/5] overflow-hidden rounded-2xl bg-gray-100 mb-6 relative">
                    @php
                        $image = $designer->designer_image 
                            ? asset('storage/' . $designer->designer_image) 
                            : 'https://ui-avatars.com/api/?name='.urlencode($designer->user->full_name).'&background=B5733A&color=fff';
                    @endphp
                    <img src="{{ $image }}" class="w-full h-full object-cover grayscale group-hover:grayscale-0 transition duration-700">
                    <div class="absolute bottom-4 left-4 bg-black/50 backdrop-blur-md text-white text-[8px] px-3 py-1 rounded-full border border-white/20 uppercase font-bold tracking-widest">
                        ★ 4.9 ({{ $designer->consultations->where('status', 4)->count() }} projects)
                    </div>
                </div>
                <div class="flex justify-between items-start mb-2">
                    <div>
                        <h3 class="text-xl font-bold">{{ $designer->studio_name ?? $designer->user->full_name }}</h3>
                        <p class="text-[9px] font-bold uppercase tracking-widest text-gray-400 mt-1">{{ $designer->specialty }}</p>
                    </div>
                </div>
                <p class="text-xs text-gray-500 leading-relaxed mb-6 line-clamp-3">{{ $designer->bio ?? 'No bio provided.' }}</p>
               <a href="{{ route('customer.designer.profile', $designer->id) }}" class="block w-full">
                    <button class="w-full bg-primary text-white py-4 rounded-sm text-[10px] font-bold uppercase tracking-widest hover:bg-opacity-90 transition">
                        View Portofolio
                    </button>
                </a>
            </div>
            @empty
            <div class="col-span-full py-20 text-center">
                <p class="text-gray-400 font-bold uppercase tracking-widest">No designers available yet.</p>
            </div>
            @endforelse
        </div>

        <section class="grid grid-cols-1 md:grid-cols-2 gap-0 rounded-[3rem] overflow-hidden bg-gray-50 border border-gray-100">
            <div class="h-[500px]">
                <img src="https://images.unsplash.com/photo-1600210492486-724fe5c67fb0?w=800" class="w-full h-full object-cover">
            </div>
            <div class="p-20 flex flex-col justify-center">
                <span class="text-[9px] font-bold uppercase tracking-[0.3em] text-primary mb-6">Designer of the Month</span>
                <h2 class="text-5xl font-bold tracking-tighter mb-8 leading-tight">Mastering<br>Minimalist<br>Grandeur</h2>
                <p class="text-sm text-gray-400 leading-relaxed mb-12 max-w-sm">
                    Our featured designers bring years of experience in curating bespoke atmospheres. Experience a deep dive into the philosophy of Studio Curator.
                </p>
                <div class="grid grid-cols-3 gap-10">
                    <div>
                        <p class="text-2xl font-bold text-primary">15+</p>
                        <p class="text-[8px] font-bold uppercase tracking-widest text-gray-400">Years Exp</p>
                    </div>
                    <div>
                        <p class="text-2xl font-bold text-primary">400</p>
                        <p class="text-[8px] font-bold uppercase tracking-widest text-gray-400">Projects</p>
                    </div>
                    <div>
                        <p class="text-2xl font-bold text-primary">12</p>
                        <p class="text-[8px] font-bold uppercase tracking-widest text-gray-400">Awards</p>
                    </div>
                </div>
            </div>
        </section>
    </main>

     @include('customer.partials.footer')

</body>
</html>