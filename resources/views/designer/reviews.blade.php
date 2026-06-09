<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Decor Designer - Reviews & Ratings</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: { primary: '#B5733A' }
                }
            }
        }
    </script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&display=swap');
        body { background-color: #F8F6F4; font-family: 'Plus Jakarta Sans', sans-serif; overflow-x: hidden; }
        .active-link { background-color: rgba(181,115,58,.1); color: #B5733A; border-right: 4px solid #B5733A; }
        .sidebar-transition { transition: all .3s cubic-bezier(.4,0,.2,1); }
        .sidebar-closed { transform: translateX(-100%); }
        .main-full { margin-left: 0 !important; }
        ::-webkit-scrollbar { width: 5px; }
        ::-webkit-scrollbar-thumb { background: #B5733A; border-radius: 10px; }
        .star-filled { color: #f59e0b; }
        .star-empty  { color: #e5e7eb; }
        .reply-box   { display: none; }
        .reply-box.open { display: block; }
    </style>
</head>
<body class="text-gray-800 flex h-screen">

    @include('designer.partials.sidebar')

    <div id="main-content" class="flex-1 flex flex-col min-w-0 overflow-hidden ml-64 sidebar-transition">

        <!-- HEADER -->
        <header class="h-16 bg-primary flex items-center justify-between px-8 sticky top-0 z-40 shadow-sm text-white">
            <div class="flex items-center">
                <i onclick="toggleSidebar()" class="fa-solid fa-bars-staggered text-xl mr-4 cursor-pointer hover:scale-110 transition-transform"></i>
                <h2 class="font-black text-[10px] uppercase tracking-widest leading-none">Review & Rating</h2>
            </div>
            <div class="flex items-center space-x-6">
                <i class="fa-regular fa-bell text-xl"></i>
                <div class="flex items-center space-x-3 bg-white/10 px-3 py-1.5 rounded-xl border border-white/20">
                    <span class="text-[10px] font-black uppercase tracking-widest">{{ $designer->user->full_name }}</span>
                    <div class="w-8 h-8 rounded-lg bg-white text-primary flex items-center justify-center font-bold text-xs">
                        {{ strtoupper(substr($designer->user->full_name, 0, 2)) }}
                    </div>
                </div>
            </div>
        </header>

        <main class="flex-1 overflow-y-auto p-10 space-y-10 pb-24">

            <!-- Flash Messages -->
            @if(session('success'))
                <div class="bg-green-50 border border-green-200 text-green-700 px-6 py-4 rounded-2xl flex items-center gap-3 text-sm font-bold">
                    <i class="fa-solid fa-circle-check text-green-500"></i> {{ session('success') }}
                </div>
            @endif
            @if(session('error'))
                <div class="bg-red-50 border border-red-200 text-red-700 px-6 py-4 rounded-2xl flex items-center gap-3 text-sm font-bold">
                    <i class="fa-solid fa-circle-xmark text-red-500"></i> {{ session('error') }}
                </div>
            @endif
            @if($errors->any())
                <div class="bg-red-50 border border-red-200 text-red-700 px-6 py-4 rounded-2xl flex items-center gap-3 text-sm font-bold">
                    <i class="fa-solid fa-circle-xmark text-red-500"></i> {{ $errors->first() }}
                </div>
            @endif

            <!-- Page Title + Search/Filter -->
            <div class="flex justify-between items-end flex-wrap gap-4">
                <div>
                    <h3 class="text-2xl font-black text-gray-900 tracking-tight leading-none">Customer Insights</h3>
                    <p class="text-xs text-gray-400 font-bold italic mt-2">
                        {{ $totalReviews }} ulasan dari customer Anda.
                    </p>
                </div>

                <form method="GET" action="{{ route('designer.reviews') }}" class="flex items-center gap-3">
                    <!-- Rating Filter -->
                    <select name="rating" onchange="this.form.submit()"
                            class="bg-white border-none rounded-2xl py-3 px-4 text-[10px] font-bold text-gray-900 focus:ring-2 focus:ring-primary/20 outline-none shadow-sm">
                        <option value="">Semua Bintang</option>
                        @for($i = 5; $i >= 1; $i--)
                            <option value="{{ $i }}" {{ request('rating') == $i ? 'selected' : '' }}>{{ $i }} Bintang</option>
                        @endfor
                    </select>

                    <!-- Search -->
                    <div class="relative group">
                        <i class="fa-solid fa-magnifying-glass absolute left-4 top-1/2 -translate-y-1/2 text-gray-300 group-focus-within:text-primary transition-colors text-xs"></i>
                        <input type="text" name="search" value="{{ request('search') }}"
                               placeholder="Cari ulasan..."
                               class="bg-white border-none rounded-2xl py-3 pl-10 pr-6 text-[10px] font-bold text-gray-900 focus:ring-2 focus:ring-primary/20 outline-none w-56 shadow-sm transition-all">
                    </div>
                    <button type="submit" class="bg-primary text-white px-5 py-3 rounded-2xl text-[10px] font-black uppercase tracking-widest shadow-sm">
                        <i class="fa-solid fa-search"></i>
                    </button>
                    @if(request('search') || request('rating'))
                        <a href="{{ route('designer.reviews') }}" class="bg-gray-100 text-gray-500 px-5 py-3 rounded-2xl text-[10px] font-black uppercase tracking-widest shadow-sm hover:bg-gray-200 transition-colors">
                            Reset
                        </a>
                    @endif
                </form>
            </div>

            <!-- STATS SECTION -->
            <div class="flex flex-col lg:flex-row gap-6">

                <!-- Overall Rating Card -->
                <div class="flex-1 bg-white p-10 rounded-[40px] shadow-sm border border-gray-100 flex flex-col md:flex-row items-center gap-10">
                    <div class="text-center md:text-left">
                        <p class="text-[9px] font-black text-gray-300 uppercase tracking-widest mb-2">Overall Rating</p>
                        <h3 class="text-6xl font-black text-gray-900 tracking-tighter">
                            {{ $avgRating > 0 ? number_format($avgRating, 1) : '—' }}
                            <span class="text-xl text-gray-300">/ 5.0</span>
                        </h3>
                        <div class="flex text-amber-400 text-sm mt-3 justify-center md:justify-start gap-0.5">
                            @for($i = 1; $i <= 5; $i++)
                                <i class="fa-{{ $i <= round($avgRating) ? 'solid' : 'regular' }} fa-star"></i>
                            @endfor
                        </div>
                        <p class="text-[9px] font-bold text-gray-400 uppercase mt-4 tracking-widest">
                            Berdasarkan {{ $totalReviews }} Ulasan
                        </p>
                    </div>

                    <div class="flex-1 w-full space-y-3">
                        @for($i = 5; $i >= 1; $i--)
                            <div class="flex items-center space-x-4">
                                <span class="text-[10px] font-black text-gray-400 w-4">{{ $i }}</span>
                                <div class="flex-1 h-2 bg-gray-50 rounded-full overflow-hidden">
                                    <div class="bg-primary h-full rounded-full transition-all duration-500"
                                         style="width: {{ $ratingCounts[$i]['percent'] }}%"></div>
                                </div>
                                <span class="text-[10px] font-black text-gray-400 w-10">
                                    {{ $ratingCounts[$i]['percent'] }}%
                                </span>
                                <span class="text-[9px] text-gray-300 w-6">({{ $ratingCounts[$i]['count'] }})</span>
                            </div>
                        @endfor
                    </div>
                </div>

                <!-- Sentiment Card -->
                <div class="w-full lg:w-80 bg-primary p-10 rounded-[40px] text-white flex flex-col justify-between relative overflow-hidden shadow-xl shadow-primary/20">
                    <div class="relative z-10">
                        <p class="text-[9px] font-black text-white/50 uppercase tracking-widest mb-1">Sentiment Score</p>
                        <h3 class="text-4xl font-black tracking-tight">
                            @if($avgRating >= 4.5) Excellent
                            @elseif($avgRating >= 3.5) Good
                            @elseif($avgRating >= 2.5) Fair
                            @elseif($avgRating > 0) Needs Work
                            @else No Data
                            @endif
                        </h3>

                        <div class="mt-8">
                            <p class="text-[9px] font-black text-white/50 uppercase tracking-widest mb-1">Response Rate</p>
                            <h3 class="text-4xl font-black tracking-tight">{{ $responseRate }}%</h3>
                        </div>

                        <div class="mt-8">
                            <p class="text-[9px] font-black text-white/50 uppercase tracking-widest mb-1">Total Reviews</p>
                            <h3 class="text-4xl font-black tracking-tight">{{ $totalReviews }}</h3>
                        </div>
                    </div>
                    <div class="absolute -bottom-10 -right-10 w-40 h-40 bg-white/10 rounded-full"></div>
                    <div class="absolute -top-6 -left-6 w-24 h-24 bg-white/5 rounded-full"></div>
                </div>
            </div>

            <!-- REVIEW LIST -->
            @if($reviews->isEmpty())
                <div class="bg-white rounded-[40px] border border-gray-100 p-16 text-center shadow-sm">
                    <div class="w-20 h-20 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-6">
                        <i class="fa-regular fa-star text-3xl text-gray-200"></i>
                    </div>
                    <h4 class="text-lg font-black text-gray-300 tracking-tight">Belum ada ulasan</h4>
                    <p class="text-xs text-gray-400 mt-2">
                        @if(request('search') || request('rating'))
                            Tidak ada ulasan yang cocok dengan filter Anda.
                        @else
                            Ulasan dari customer akan muncul di sini setelah konsultasi selesai.
                        @endif
                    </p>
                </div>
            @else
                <div class="space-y-6">
                    @foreach($reviews as $review)
                        @php
                            $customerName = $review->customer->full_name ?? 'Customer';
                            $initials     = strtoupper(substr($customerName, 0, 2));
                            $projectTitle = $review->consultation->title ?? 'Konsultasi';
                        @endphp

                        <div class="bg-white p-8 rounded-[40px] border border-gray-100 shadow-sm space-y-6">

                            <!-- Review Header -->
                            <div class="flex items-start justify-between gap-4">
                                <div class="flex items-center space-x-4">
                                    <div class="w-12 h-12 rounded-2xl bg-primary/10 text-primary flex items-center justify-center font-black text-sm">
                                        {{ $initials }}
                                    </div>
                                    <div>
                                        <div class="flex items-center space-x-3 flex-wrap gap-y-1">
                                            <h4 class="font-black text-sm text-gray-900 tracking-tight leading-none">{{ $customerName }}</h4>
                                            <!-- Stars -->
                                            <div class="flex gap-0.5">
                                                @for($i = 1; $i <= 5; $i++)
                                                    <i class="fa-{{ $i <= $review->rating ? 'solid' : 'regular' }} fa-star text-amber-400 text-[10px]"></i>
                                                @endfor
                                            </div>
                                            <span class="text-[9px] font-black text-white bg-primary px-2 py-0.5 rounded-full">
                                                {{ $review->rating }}/5
                                            </span>
                                        </div>
                                        <p class="text-[9px] font-bold text-gray-400 uppercase tracking-widest mt-1.5 leading-none">
                                            {{ $projectTitle }} &bull; {{ $review->created_at->diffForHumans() }}
                                        </p>
                                        @if($review->project_duration)
                                            <p class="text-[9px] font-bold text-gray-300 uppercase tracking-widest mt-1">
                                                <i class="fa-regular fa-clock mr-1"></i>Durasi: {{ $review->project_duration }}
                                            </p>
                                        @endif
                                    </div>
                                </div>

                                <!-- Badge status balasan -->
                                @if($review->designer_reply)
                                    <span class="text-[9px] font-black text-green-600 bg-green-50 border border-green-200 px-3 py-1.5 rounded-xl shrink-0 flex items-center gap-1.5">
                                        <i class="fa-solid fa-circle-check"></i> Sudah Dibalas
                                    </span>
                                @else
                                    <span class="text-[9px] font-black text-amber-600 bg-amber-50 border border-amber-200 px-3 py-1.5 rounded-xl shrink-0 flex items-center gap-1.5">
                                        <i class="fa-solid fa-clock"></i> Belum Dibalas
                                    </span>
                                @endif
                            </div>

                            <!-- Comment -->
                            @if($review->comment)
                                <div class="bg-gray-50/60 px-6 py-5 rounded-3xl border border-gray-100">
                                    <p class="text-xs font-medium text-gray-600 leading-relaxed italic">
                                        "{{ $review->comment }}"
                                    </p>
                                </div>
                            @else
                                <p class="text-xs text-gray-300 italic pl-2">— Tidak ada komentar —</p>
                            @endif

                            <!-- Existing Reply -->
                            @if($review->designer_reply)
                                <div class="bg-primary/5 border border-primary/10 p-6 rounded-3xl space-y-2">
                                    <p class="text-[9px] font-black text-primary uppercase tracking-[0.2em]">
                                        <i class="fa-solid fa-reply mr-1"></i>Balasan Anda
                                        <span class="text-gray-400 normal-case ml-2">— {{ $review->designer_replied_at?->diffForHumans() }}</span>
                                    </p>
                                    <p class="text-xs font-medium text-gray-700 leading-relaxed">{{ $review->designer_reply }}</p>

                                    <!-- Edit reply button -->
                                    <button onclick="toggleReply('reply-{{ $review->id }}')"
                                            class="text-[9px] font-black text-primary/60 hover:text-primary uppercase tracking-widest mt-2 transition-colors">
                                        <i class="fa-solid fa-pen mr-1"></i>Edit Balasan
                                    </button>
                                </div>
                            @endif

                            <!-- Reply Form (hidden by default unless no reply yet) -->
                            <div id="reply-{{ $review->id }}" class="reply-box {{ !$review->designer_reply ? 'open' : '' }}">
                                <div class="bg-gray-50/50 p-6 rounded-[32px] border border-gray-100 space-y-4">
                                    <h5 class="text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] italic">
                                        {{ $review->designer_reply ? 'Edit Balasan' : 'Tulis Balasan' }}
                                    </h5>
                                    <form action="{{ route('designer.reviews.reply', $review->id) }}" method="POST">
                                        @csrf
                                        <textarea name="reply" rows="3"
                                                  placeholder="Tulis balasan untuk {{ $customerName }}..."
                                                  class="w-full bg-white border border-gray-100 rounded-2xl p-5 text-sm font-medium text-gray-600 focus:ring-2 focus:ring-primary/10 outline-none transition-all resize-none shadow-sm"
                                                  spellcheck="false">{{ $review->designer_reply }}</textarea>
                                        @error('reply')
                                            <p class="text-xs text-red-500 font-bold mt-2"><i class="fa-solid fa-circle-exclamation mr-1"></i>{{ $message }}</p>
                                        @enderror
                                        <div class="flex justify-between items-center mt-4">
                                            @if($review->designer_reply)
                                                <button type="button" onclick="toggleReply('reply-{{ $review->id }}')"
                                                        class="text-[10px] font-black text-gray-400 uppercase tracking-widest hover:text-gray-600 transition-colors">
                                                    Batal
                                                </button>
                                            @else
                                                <span></span>
                                            @endif
                                            <button type="submit"
                                                    class="bg-primary text-white px-8 py-3 rounded-2xl text-[10px] font-black uppercase tracking-widest shadow-xl shadow-primary/20 hover:scale-105 transition-all">
                                                <i class="fa-solid fa-paper-plane mr-2"></i>
                                                {{ $review->designer_reply ? 'Update Balasan' : 'Kirim Balasan' }}
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>

                        </div>
                    @endforeach
                </div>
            @endif

        </main>

        <footer class="p-8 border-t border-gray-100 text-[9px] font-black text-gray-400 uppercase tracking-widest bg-white mt-auto text-center">
            © 2026 DECOR DESIGNER PORTAL. ALL RIGHTS RESERVED.
        </footer>
    </div>

    <script>
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            const mainContent = document.getElementById('main-content');
            sidebar.classList.toggle('sidebar-closed');
            mainContent.classList.toggle('main-full');
        }

        function toggleReply(id) {
            const box = document.getElementById(id);
            box.classList.toggle('open');
        }
    </script>
</body>
</html>