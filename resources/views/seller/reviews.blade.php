<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Decor Seller - Reviews & Ratings</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap');
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

    <aside id="sidebar" class="w-64 bg-white border-r border-gray-200 flex flex-col fixed h-full z-50 sidebar-transition">
        <div class="p-8">
            <h1 class="text-2xl font-bold tracking-widest text-primary uppercase leading-none">DECOR</h1>
            <p class="text-[10px] text-gray-400 mt-1 uppercase tracking-widest">Seller Portal</p>
        </div>

        <nav class="flex-1 px-4 space-y-1">
            <a href="{{ route('seller.dashboard') }}" class="flex items-center px-4 py-3 text-xs font-bold text-gray-400 hover:text-primary transition-all rounded-lg">
                <i class="fa-solid fa-table-columns mr-3 w-5 text-center"></i> Dashboard
            </a>
            <a href="{{ route('seller.products.index') }}" class="flex items-center px-4 py-3 text-xs font-bold text-gray-400 hover:text-primary transition-all rounded-lg">
                <i class="fa-solid fa-couch mr-3 w-5 text-center"></i> Kelola Produk
            </a>
            <a href="{{ route('seller.orders') }}" class="flex items-center px-4 py-3 text-xs font-bold text-gray-400 hover:text-primary transition-all rounded-lg">
                <i class="fa-solid fa-bag-shopping mr-3 w-5 text-center"></i> Daftar Pesanan
            </a>
            <a href="{{ route('seller.chats') }}" class="flex items-center px-4 py-3 text-xs font-bold text-gray-400 hover:text-primary transition-all rounded-lg">
                <i class="fa-solid fa-message mr-3 w-5 text-center"></i> Seller Chat
            </a>
            <a href="{{ route('seller.complaint.index') }}" class="flex items-center px-4 py-3 text-xs font-bold text-gray-400 hover:text-primary transition-all rounded-lg">
                <i class="fa-solid fa-circle-exclamation mr-3 w-5 text-center"></i> Komplain
            </a>
            <a href="{{ route('seller.reviews') }}" class="flex items-center px-4 py-3 text-xs font-bold active-link transition-all rounded-lg">
                <i class="fa-solid fa-star mr-3 w-5 text-center"></i> Review & Rating
            </a>
            <a href="{{ route('seller.reports') }}" class="flex items-center px-4 py-3 text-xs font-bold text-gray-400 hover:text-primary transition-all rounded-lg">
                <i class="fa-solid fa-chart-line mr-3 w-5 text-center"></i> Laporan
            </a>
        </nav>

        <div class="p-4 border-t border-gray-100 space-y-1">
            <a href="{{ route('seller.settings') }}" class="flex items-center px-4 py-3 text-xs font-bold text-gray-400 hover:text-primary transition-all rounded-lg">
                <i class="fa-solid fa-gear mr-3 w-5 text-center"></i> Settings
            </a>
            <a href="{{ route('seller.support') }}" class="flex items-center px-4 py-3 text-xs font-bold transition-all rounded-lg {{ Request::routeIs('seller.support') ? 'active-link' : 'text-gray-400 hover:text-primary' }}">
                <i class="fa-solid fa-gear mr-3 w-5 text-center"></i> Support
            </a>
        </div>
    </aside>

    <main id="main-content" class="flex-1 flex flex-col ml-64 sidebar-transition min-h-screen relative">
        
        <header class="h-16 bg-primary flex items-center justify-between px-8 sticky top-0 z-40 shadow-sm">
            <div class="flex items-center">
                <button id="toggle-sidebar" class="text-white hover:opacity-80 mr-4 flex items-center justify-center transition-transform active:scale-95">
                    <i class="fa-solid fa-bars-staggered text-xl"></i>
                </button>
                <h2 class="font-bold text-xs uppercase tracking-widest text-white leading-none">Reviews Management</h2>
            </div>
            <div class="flex items-center space-x-6 text-white">
                <i class="fa-regular fa-bell text-xl cursor-pointer"></i>
                <img src="https://ui-avatars.com/api/?name=Adrian&background=fff&color=B5733A" class="w-9 h-9 rounded-lg border-2 border-white/20">
            </div>
        </header>

        <div class="p-10 space-y-8 flex-1">
            
            <div class="flex justify-between items-end">
                <div>
                    <h2 class="text-4xl font-extrabold text-gray-800 tracking-tight">Reviews & Ratings</h2>
                    <p class="text-sm text-gray-400 mt-2 font-medium">Insights into your customer's furniture experiences.</p>
                </div>
                <div class="flex space-x-4">
                    <div class="relative">
                        <i class="fa-solid fa-magnifying-glass absolute left-3 top-1/2 -translate-y-1/2 text-gray-300 text-xs"></i>
                        <input type="text" placeholder="Search reviews..." class="bg-white border border-gray-100 rounded-xl py-2 pl-9 pr-4 text-xs w-64 focus:outline-none focus:border-primary transition-all">
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-12 gap-8">
                <div class="col-span-8 bg-white p-10 rounded-[32px] border border-gray-100 shadow-sm flex items-center space-x-12">
                    <div class="text-center space-y-2 border-r border-gray-50 pr-12">
                        <p class="text-[10px] font-black text-gray-300 uppercase tracking-widest">Overall Rating</p>
                        <h3 class="text-6xl font-black text-gray-800">4.9 <span class="text-2xl text-gray-300">/ 5.0</span></h3>
                        <div class="flex text-orange-400 text-lg justify-center">
                            <i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i>
                        </div>
                        <p class="text-[9px] font-bold text-gray-400 uppercase tracking-tighter">Based on 1,248 reviews</p>
                    </div>
                    <div class="flex-1 space-y-3">
                        @foreach([['5', '88%'], ['4', '9%'], ['3', '2%'], ['2', '1%'], ['1', '0%']] as $bar)
                        <div class="flex items-center space-x-4">
                            <span class="text-[10px] font-bold text-gray-400 w-2">{{ $bar[0] }}</span>
                            <div class="flex-1 h-1.5 bg-gray-50 rounded-full overflow-hidden">
                                <div class="h-full bg-primary" style="width: {{ $bar[1] }}"></div>
                            </div>
                            <span class="text-[10px] font-bold text-gray-300 w-8">{{ $bar[1] }}</span>
                        </div>
                        @endforeach
                    </div>
                </div>

                <div class="col-span-4 bg-primary p-10 rounded-[32px] shadow-lg shadow-primary/20 text-white flex flex-col justify-between relative overflow-hidden">
                    <div class="relative z-10">
                        <p class="text-[10px] font-black opacity-60 uppercase tracking-widest mb-1">Sentiment Score</p>
                        <h3 class="text-4xl font-black">Excellent</h3>
                    </div>
                    <div class="relative z-10 mt-8">
                        <p class="text-[10px] font-black opacity-60 uppercase tracking-widest">Response Rate</p>
                        <p class="text-3xl font-black">99.4%</p>
                    </div>
                    <div class="mt-4 flex -space-x-2 relative z-10">
                        <img class="w-8 h-8 rounded-full border-2 border-primary" src="https://ui-avatars.com/api/?name=A&background=fff&color=B5733A">
                        <img class="w-8 h-8 rounded-full border-2 border-primary" src="https://ui-avatars.com/api/?name=B&background=fff&color=B5733A">
                        <div class="w-8 h-8 rounded-full border-2 border-primary bg-white/20 flex items-center justify-center text-[8px] font-bold">+12</div>
                    </div>
                    <div class="absolute -right-10 -bottom-10 w-40 h-40 bg-white/10 rounded-full"></div>
                </div>
            </div>

            <div class="space-y-8">
                
                @php
                $reviews = [
                    [
                        'name' => 'Julianne Moore', 'date' => '2 days ago', 'rating' => 5,
                        'text' => 'The "Archi-Curve" lounge chair is even more stunning in person. The velvet texture is exquisite and the ergonomic support exceeded my expectations. It fits perfectly into my minimalist reading corner. Fast delivery and premium packaging!',
                        'imgs' => ['https://images.unsplash.com/photo-1592078615290-033ee584e267?q=80&w=200', 'https://images.unsplash.com/photo-1567016432779-094069958ea5?q=80&w=200', 'https://images.unsplash.com/photo-1594026112284-02bb6f3352fe?q=80&w=200'],
                        'prod_img' => 'https://images.unsplash.com/photo-1592078615290-033ee584e267?q=80&w=200', 'prod_title' => 'Archi-Curve Velvet Lounge', 'prod_price' => '$1,240',
                        'responded' => false
                    ],
                    [
                        'name' => 'Mark Sanderson', 'date' => '1 week ago', 'rating' => 4,
                        'text' => 'Solid build quality and the wood grain is beautiful. Assembly was straightforward but I\'d suggest using your own tools for a tighter finish. Overall, a great piece that looks expensive for its price point.',
                        'imgs' => ['https://images.unsplash.com/photo-1533090161767-e6ffed986c88?q=80&w=200', 'https://images.unsplash.com/photo-1538688525198-9b88f6f53126?q=80&w=200'],
                        'prod_img' => 'https://images.unsplash.com/photo-1533090161767-e6ffed986c88?q=80&w=200', 'prod_title' => 'Nordic Solid Oak Dining Table', 'prod_price' => '$2,100',
                        'responded' => true, 'response_text' => '"Thank you for the kind words, Mark! We love the detail about the wood grain. Your suggestion about tools is noted—we\'re actually looking into including a higher-quality hex key in the future."'
                    ]
                ];
                @endphp

                @foreach($reviews as $r)
                <div class="bg-white p-10 rounded-[40px] border border-gray-100 shadow-sm flex space-x-12">
                    <div class="flex-1 space-y-6">
                        <div class="flex items-center space-x-4">
                            <div class="w-12 h-12 rounded-2xl bg-gray-50 flex items-center justify-center text-gray-300"><i class="fa-solid fa-user"></i></div>
                            <div>
                                <h4 class="text-sm font-black text-gray-800">{{ $r['name'] }}</h4>
                                <p class="text-[10px] font-bold text-gray-300 uppercase tracking-widest">Verified Purchase • {{ $r['date'] }}</p>
                            </div>
                            <div class="flex text-orange-400 text-xs ml-auto">
                                @for($i=0; $i<$r['rating']; $i++) <i class="fa-solid fa-star"></i> @endfor
                            </div>
                        </div>
                        <p class="text-xs font-bold text-gray-500 leading-relaxed">{{ $r['text'] }}</p>
                        <div class="flex space-x-4">
                            @foreach($r['imgs'] as $img)
                            <img src="{{ $img }}" class="w-20 h-20 rounded-2xl object-cover grayscale opacity-80 hover:grayscale-0 transition-all cursor-pointer">
                            @endforeach
                        </div>

                        <div class="bg-gray-50/50 p-8 rounded-[32px] space-y-4">
                            @if($r['responded'])
                                <div class="flex justify-between items-center mb-2">
                                    <p class="text-[9px] font-black text-primary uppercase tracking-[0.2em]">You Responded</p>
                                    <span class="text-[8px] font-bold text-gray-300">Oct 24, 2023</span>
                                </div>
                                <p class="text-[10px] font-bold text-gray-500 italic leading-relaxed">{{ $r['response_text'] }}</p>
                            @else
                                <p class="text-[9px] font-black text-gray-400 uppercase tracking-[0.2em] mb-2">Seller Response</p>
                                <textarea placeholder="Write your reply to {{ explode(' ', $r['name'])[0] }}..." class="w-full bg-white border border-gray-100 rounded-2xl p-4 text-[10px] font-bold placeholder-gray-300 outline-none focus:border-primary min-h-[80px]"></textarea>
                                <div class="flex justify-end pt-2">
                                    <button class="bg-primary text-white px-8 py-2.5 rounded-xl text-[10px] font-black uppercase tracking-widest shadow-lg shadow-primary/20 hover:opacity-90">Post Reply</button>
                                </div>
                            @endif
                        </div>
                    </div>

                    <div class="w-48 flex-shrink-0 text-center space-y-4 border-l border-gray-50 pl-12">
                        <img src="{{ $r['prod_img'] }}" class="w-full aspect-square rounded-3xl object-cover shadow-sm">
                        <div>
                            <p class="text-[8px] font-black text-gray-300 uppercase tracking-widest mb-1">Reviewed Item</p>
                            <h5 class="text-[11px] font-black text-gray-800 leading-tight">{{ $r['prod_title'] }}</h5>
                            <p class="text-sm font-black text-primary mt-1">{{ $r['prod_price'] }}</p>
                        </div>
                        <button class="text-[8px] font-black text-gray-300 hover:text-primary uppercase tracking-widest flex items-center justify-center w-full group">
                            <i class="fa-regular fa-eye mr-2 group-hover:scale-110 transition-transform"></i> View Product Page
                        </button>
                    </div>
                </div>
                @endforeach
            </div>

            <div class="flex justify-center pt-8">
                <button class="text-[10px] font-black text-gray-400 hover:text-primary uppercase tracking-[0.3em] flex flex-col items-center">
                    Load More Reviews
                    <i class="fa-solid fa-chevron-down mt-2 animate-bounce"></i>
                </button>
            </div>
        </div>

        <footer class="p-8 border-t border-gray-100 text-[10px] font-bold text-gray-400 uppercase tracking-widest mt-auto text-left">
            © 2026 DECOR MERCHANT SERVICE CENTER. ALL RIGHTS RESERVED.
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