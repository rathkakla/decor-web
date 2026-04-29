<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Designer Portal - Decor</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#B5733A',
                        sidebar: '#F8F7F6',
                    },
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                    }
                }
            }
        }
    </script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-white flex min-h-screen font-sans text-gray-800">

    <aside class="w-64 bg-sidebar flex flex-col border-r border-gray-100">
        <div class="p-8">
            <div class="flex items-center gap-3 mb-10">
                <div class="bg-primary text-white w-10 h-10 flex items-center justify-center rounded-lg font-bold text-xl">D</div>
                <div>
                    <h1 class="font-extrabold text-sm tracking-widest uppercase">DECOR</h1>
                    <p class="text-[10px] text-gray-400 font-bold uppercase tracking-tighter">Designer Portal</p>
                </div>
            </div>

            <nav class="space-y-2">
                <a href="#" class="flex items-center gap-3 px-4 py-3 bg-white text-primary rounded-xl shadow-sm border border-gray-100 font-bold text-sm">
                    <i class="fa-solid fa-table-cells-large"></i> Dashboard
                </a>
                <a href="#" class="flex items-center gap-3 px-4 py-3 text-gray-400 hover:text-primary transition font-bold text-sm">
                    <i class="fa-solid fa-calendar-check"></i> Incoming Consultations
                </a>
                <a href="#" class="flex items-center gap-3 px-4 py-3 text-gray-400 hover:text-primary transition font-bold text-sm">
                    <i class="fa-solid fa-file-invoice"></i> Session Details
                </a>
                <a href="#" class="flex items-center gap-3 px-4 py-3 text-gray-400 hover:text-primary transition font-bold text-sm">
                    <i class="fa-solid fa-clock-rotate-left"></i> History
                </a>
            </nav>
        </div>

        <div class="mt-auto p-8 space-y-4">
            <button class="w-full bg-primary text-white py-3 rounded-xl font-bold text-sm shadow-lg shadow-primary/20 hover:bg-[#9a6130] transition">
                New Project
            </button>
            <a href="#" class="flex items-center gap-3 px-4 text-gray-400 text-xs font-bold uppercase tracking-widest">
                <i class="fa-solid fa-circle-question text-base"></i> Help Center
            </a>
        </div>
        <div class="px-8 mb-6">
    <form method="POST" action="{{ route('logout') }}">
        @csrf
        <button type="submit" class="w-full flex items-center gap-3 px-4 py-3 text-red-400 hover:bg-red-50 hover:text-red-600 rounded-xl transition font-bold text-sm group">
            <i class="fa-solid fa-right-from-bracket transition-transform group-hover:translate-x-1"></i> 
            Sign Out
        </button>
    </form>
</div>
    </aside>

    <main class="flex-1 p-12 overflow-y-auto">
        <header class="flex justify-between items-start mb-12">
            <div>
                <h2 class="text-4xl font-extrabold text-gray-900 mb-2">Welcome back, {{ Auth::user()->name }}</h2>
                <p class="text-gray-400 text-sm font-medium">Curating excellence for 12 ongoing projects.</p>
            </div>
            <div class="flex items-center gap-4 text-right">
    <div>
        <p class="text-[10px] font-bold text-gray-900 uppercase tracking-widest">
            {{ Auth::user()->name }}
        </p>
        <p class="text-[10px] font-bold text-primary uppercase">Online & Available</p>
    </div>
    
    <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&background=B5733A&color=fff" 
         class="w-12 h-12 rounded-2xl shadow-md" 
         alt="Avatar">
</div>


        </header>

        <div class="flex gap-8">
            <div class="flex-1 space-y-12">
                <div class="grid grid-cols-4 gap-6">
                    <div class="bg-white p-6 rounded-3xl border border-gray-100 shadow-sm">
                        <i class="fa-solid fa-palette text-primary mb-4 block"></i>
                        <p class="text-[10px] font-bold text-gray-400 uppercase mb-1">Active Consultations</p>
                        <h3 class="text-3xl font-extrabold">12</h3>
                    </div>
                    <div class="bg-white p-6 rounded-3xl border border-gray-100 shadow-sm">
                        <i class="fa-solid fa-clipboard-list text-primary mb-4 block"></i>
                        <p class="text-[10px] font-bold text-gray-400 uppercase mb-1">Pending Requests</p>
                        <h3 class="text-3xl font-extrabold">04</h3>
                    </div>
                    <div class="bg-white p-6 rounded-3xl border border-gray-100 shadow-sm">
                        <i class="fa-solid fa-wallet text-primary mb-4 block"></i>
                        <p class="text-[10px] font-bold text-gray-400 uppercase mb-1">Monthly Earnings</p>
                        <h3 class="text-3xl font-extrabold">$4,250</h3>
                    </div>
                    <div class="bg-white p-6 rounded-3xl border border-gray-100 shadow-sm">
                        <i class="fa-solid fa-star text-primary mb-4 block"></i>
                        <p class="text-[10px] font-bold text-gray-400 uppercase mb-1">Avg. Rating</p>
                        <h3 class="text-3xl font-extrabold">4.9<span class="text-gray-300 text-sm font-bold">/5</span></h3>
                    </div>
                </div>

                <section>
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-xl font-bold">Upcoming Sessions</h3>
                        <a href="#" class="text-[10px] font-bold text-primary uppercase border-b-2 border-primary pb-1">View Full Calendar</a>
                    </div>
                    <div class="space-y-4">
                        <div class="bg-white p-6 rounded-3xl border border-gray-100 shadow-sm flex items-center justify-between">
                            <div class="flex items-center gap-6">
                                <img src="https://ui-avatars.com/api/?name=Marcus+Horne&background=E3DCD6&color=B5733A" class="w-16 h-16 rounded-2xl" alt="Client">
                                <div>
                                    <h4 class="font-bold text-lg">Marcus Horne</h4>
                                    <p class="text-gray-400 text-xs mb-3">Penthouse Living Room Redesign</p>
                                    <div class="flex gap-2">
                                        <span class="bg-orange-50 text-orange-600 text-[10px] px-3 py-1 rounded-full font-bold uppercase tracking-tighter">Premium</span>
                                        <span class="bg-gray-50 text-gray-400 text-[10px] px-3 py-1 rounded-full font-bold uppercase tracking-tighter">Concept Phase</span>
                                    </div>
                                </div>
                            </div>
                            <div class="text-right">
                                <p class="text-xl font-extrabold mb-1">14:00 PM</p>
                                <p class="text-[10px] font-bold text-gray-400 uppercase mb-4">Today, 24 Oct</p>
                                <button class="bg-white border-2 border-gray-100 px-6 py-2 rounded-xl text-xs font-bold hover:border-primary transition">Join Call</button>
                            </div>
                        </div>
                        </div>
                </section>
            </div>

            <div class="w-80 space-y-8">
                <div class="bg-gray-50 p-8 rounded-[2rem] border border-gray-100">
                    <h4 class="font-bold text-lg mb-4">AI Design Assistant</h4>
                    <p class="text-gray-500 text-sm leading-relaxed mb-8 italic">
                        Generated 4 new moodboard options for the <span class="font-bold text-gray-800">Thorne Project</span> based on your latest sketches.
                    </p>
                    <button class="w-full bg-white border border-gray-200 py-3 rounded-xl text-xs font-bold flex items-center justify-center gap-2 hover:bg-gray-50 transition">
                        <i class="fa-solid fa-wand-magic-sparkles text-primary"></i> Review Moodboards
                    </button>
                </div>

                <div>
                    <h4 class="font-bold text-sm uppercase tracking-widest mb-6">Recent Moodboards</h4>
                    <div class="grid grid-cols-2 gap-4">
                        <div class="aspect-square bg-gray-100 rounded-2xl overflow-hidden group relative">
                            <img src="https://images.unsplash.com/photo-1616486338812-3dadae4b4ace?auto=format&fit=crop&w=300&q=80" class="w-full h-full object-cover group-hover:scale-110 transition duration-500">
                            <div class="absolute inset-0 bg-black/20 flex items-end p-4">
                                <span class="text-white text-[10px] font-bold uppercase">Minimalist</span>
                            </div>
                        </div>
                        <div class="aspect-square bg-gray-100 rounded-2xl overflow-hidden group relative">
                            <img src="https://images.unsplash.com/photo-1615873968403-89e068629275?auto=format&fit=crop&w=300&q=80" class="w-full h-full object-cover group-hover:scale-110 transition duration-500">
                            <div class="absolute inset-0 bg-black/20 flex items-end p-4">
                                <span class="text-white text-[10px] font-bold uppercase">Art Deco</span>
                            </div>
                        </div>
                        </div>
                </div>
            </div>
        </div>
    </main>

</body>
</html>