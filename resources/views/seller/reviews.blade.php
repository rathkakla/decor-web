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

    @include('seller.partials.sidebar')

    <main id="main-content" class="flex-1 flex flex-col ml-64 sidebar-transition min-h-screen relative">
        
        @include('seller.partials.header', ['title' => 'Reviews Management'])

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
                        <h3 class="text-6xl font-black text-gray-800">{{ number_format($averageRating, 1) }} <span class="text-2xl text-gray-300">/ 5.0</span></h3>
                        <div class="flex text-orange-400 text-lg justify-center">
                            @for($i=1; $i<=5; $i++)
                                <i class="fa-{{ $i <= round($averageRating) ? 'solid' : 'regular' }} fa-star"></i>
                            @endfor
                        </div>
                        <p class="text-[9px] font-bold text-gray-400 uppercase tracking-tighter">Based on {{ number_format($totalReviews) }} reviews</p>
                    </div>
                    <div class="flex-1 space-y-3">
                        @foreach([5, 4, 3, 2, 1] as $star)
                        @php $percentage = $totalReviews > 0 ? ($ratingCounts[$star] / $totalReviews) * 100 : 0; @endphp
                        <div class="flex items-center space-x-4">
                            <span class="text-[10px] font-bold text-gray-400 w-2">{{ $star }}</span>
                            <div class="flex-1 h-1.5 bg-gray-50 rounded-full overflow-hidden">
                                <div class="h-full bg-primary" style="width: {{ $percentage }}%"></div>
                            </div>
                            <span class="text-[10px] font-bold text-gray-300 w-8">{{ round($percentage) }}%</span>
                        </div>
                        @endforeach
                    </div>
                </div>

                <div class="col-span-4 bg-primary p-10 rounded-[32px] shadow-lg shadow-primary/20 text-white flex flex-col justify-between relative overflow-hidden">
                    <div class="relative z-10">
                        <p class="text-[10px] font-black opacity-60 uppercase tracking-widest mb-1">Sentiment Score</p>
                        <h3 class="text-4xl font-black">{{ $sentimentScore }}</h3>
                    </div>
                    <div class="relative z-10 mt-8">
                        <p class="text-[10px] font-black opacity-60 uppercase tracking-widest">Response Rate</p>
                        <p class="text-3xl font-black">{{ number_format($responseRate, 1) }}%</p>
                    </div>
                    <div class="mt-4 flex -space-x-2 relative z-10">
                        <img class="w-8 h-8 rounded-full border-2 border-primary" src="https://ui-avatars.com/api/?name=A&background=fff&color=B5733A">
                        <img class="w-8 h-8 rounded-full border-2 border-primary" src="https://ui-avatars.com/api/?name=B&background=fff&color=B5733A">
                        @if($totalReviews > 2)
                        <div class="w-8 h-8 rounded-full border-2 border-primary bg-white/20 flex items-center justify-center text-[8px] font-bold">+{{ $totalReviews - 2 }}</div>
                        @endif
                    </div>
                    <div class="absolute -right-10 -bottom-10 w-40 h-40 bg-white/10 rounded-full"></div>
                </div>
            </div>

            <div class="space-y-8">
                
                @forelse($reviews as $r)
                <div class="bg-white p-10 rounded-[40px] border border-gray-100 shadow-sm flex space-x-12">
                    <div class="flex-1 space-y-6">
                        <div class="flex items-center space-x-4">
                            <div class="w-12 h-12 rounded-2xl bg-gray-50 flex items-center justify-center text-gray-300 overflow-hidden">
                                @if($r->customer->profile_image)
                                    <img src="{{ asset('storage/' . $r->customer->profile_image) }}" class="w-full h-full object-cover">
                                @else
                                    <i class="fa-solid fa-user"></i>
                                @endif
                            </div>
                            <div>
                                <h4 class="text-sm font-black text-gray-800">{{ $r->customer->user->full_name }}</h4>
                                <p class="text-[10px] font-bold text-gray-300 uppercase tracking-widest">Verified Purchase • {{ $r->created_at->diffForHumans() }}</p>
                            </div>
                            <div class="flex text-orange-400 text-xs ml-auto">
                                @for($i=0; $i<$r->rating; $i++) <i class="fa-solid fa-star"></i> @endfor
                                @for($i=$r->rating; $i<5; $i++) <i class="fa-regular fa-star text-gray-200"></i> @endfor
                            </div>
                        </div>
                        <p class="text-xs font-bold text-gray-500 leading-relaxed">{{ $r->comment }}</p>

                        <div class="bg-gray-50/50 p-8 rounded-[32px] space-y-4">
                            @if($r->reply)
                                <div class="flex justify-between items-center mb-2">
                                    <p class="text-[9px] font-black text-primary uppercase tracking-[0.2em]">You Responded</p>
                                    <span class="text-[8px] font-bold text-gray-300">{{ $r->updated_at->format('M d, Y') }}</span>
                                </div>
                                <p class="text-[10px] font-bold text-gray-500 italic leading-relaxed">{{ $r->reply }}</p>
                            @else
                                <p class="text-[9px] font-black text-gray-400 uppercase tracking-[0.2em] mb-2">Seller Response</p>
                                <form action="{{ route('seller.reviews.reply', $r->id) }}" method="POST">
                                    @csrf
                                    <textarea name="reply" placeholder="Write your reply to {{ explode(' ', $r->customer->user->full_name)[0] }}..." class="w-full bg-white border border-gray-100 rounded-2xl p-4 text-[10px] font-bold placeholder-gray-300 outline-none focus:border-primary min-h-[80px]" required></textarea>
                                    <div class="flex justify-end pt-2">
                                        <button type="submit" class="bg-primary text-white px-8 py-2.5 rounded-xl text-[10px] font-black uppercase tracking-widest shadow-lg shadow-primary/20 hover:opacity-90">Post Reply</button>
                                    </div>
                                </form>
                            @endif
                        </div>
                    </div>

                    <div class="w-48 flex-shrink-0 text-center space-y-4 border-l border-gray-50 pl-12">
                        <img src="{{ $r->product->images->first()->img_url ?? 'https://via.placeholder.com/200' }}" class="w-full aspect-square rounded-3xl object-cover shadow-sm">
                        <div>
                            <p class="text-[8px] font-black text-gray-300 uppercase tracking-widest mb-1">Reviewed Item</p>
                            <h5 class="text-[11px] font-black text-gray-800 leading-tight">{{ $r->product->name }}</h5>
                            <p class="text-sm font-black text-primary mt-1">Rp {{ number_format($r->product->price, 0, ',', '.') }}</p>
                        </div>
                        <a href="{{ route('customer.product-detail', $r->product_id) }}" target="_blank" class="text-[8px] font-black text-gray-300 hover:text-primary uppercase tracking-widest flex items-center justify-center w-full group">
                            <i class="fa-regular fa-eye mr-2 group-hover:scale-110 transition-transform"></i> View Product Page
                        </a>
                    </div>
                </div>
                @empty
                <div class="bg-white p-20 rounded-[40px] border border-dashed border-gray-200 text-center">
                    <i class="fa-regular fa-star text-5xl text-gray-200 mb-4 block"></i>
                    <p class="text-gray-400 font-bold">No reviews yet for your products.</p>
                </div>
                @endforelse
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