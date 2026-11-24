<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Decor Seller - Reviews & Ratings</title>
    <script src="https://cdn.tailwindcss.com"></script>
    @include('seller.partials.head')
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body class="text-gray-800">

    @include('seller.partials.sidebar')

    <main id="main-content" class="flex-1 flex flex-col ml-0 md:ml-64 sidebar-transition min-h-screen relative">
        @include('seller.partials.header', ['title' => 'Reviews Management'])

        <div class="p-4 sm:p-6 lg:p-8 space-y-8 flex-1">
            @if(session('success'))
            <div class="bg-green-50 text-green-600 p-4 rounded-xl text-sm font-bold border border-green-100 flex items-center shadow-sm">
                <i class="fa-solid fa-circle-check mr-3 text-lg"></i> {{ session('success') }}
            </div>
            @endif

            <div class="flex flex-col lg:flex-row lg:items-end justify-between gap-4">
                <div>
                    <div class="text-[10px] font-bold text-primary uppercase tracking-widest mb-1">Customer Feedback</div>
                    <h2 class="text-2xl font-bold text-gray-900 tracking-tight">Reviews &amp; Ratings</h2>
                    <p class="text-xs text-gray-500 mt-1">Pantau ulasan dan kepuasan pelanggan terhadap produk Anda.</p>
                </div>
                <form method="GET" action="{{ route('seller.reviews') }}" class="flex flex-wrap items-center gap-2.5">
                    <div class="relative">
                        <i class="fa-solid fa-filter absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-xs pointer-events-none"></i>
                        <select name="rating" onchange="this.form.submit()" aria-label="Filter Bintang" class="h-10 appearance-none bg-white text-xs font-medium text-gray-600 border border-gray-200 rounded-lg pl-9 pr-9 focus:outline-none focus:border-primary focus:ring-2 focus:ring-primary/10 cursor-pointer">
                            <option value="">Semua Bintang</option>
                            @for($i = 5; $i >= 1; $i--)
                            <option value="{{ $i }}" {{ (string) request('rating') === (string) $i ? 'selected' : '' }}>{{ $i }} Bintang</option>
                            @endfor
                        </select>
                        <i class="fa-solid fa-chevron-down absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 text-[10px] pointer-events-none"></i>
                    </div>
                    <div class="relative w-full sm:w-64">
                        <i class="fa-solid fa-magnifying-glass absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-xs pointer-events-none"></i>
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari ulasan, produk, customer..." class="w-full h-10 bg-white border border-gray-200 rounded-lg pl-9 pr-3 text-xs font-medium text-gray-700 focus:outline-none focus:border-primary focus:ring-2 focus:ring-primary/10">
                    </div>
                    <button type="submit" class="h-10 px-4 bg-primary text-white rounded-lg text-xs font-semibold hover:bg-opacity-90 transition-all">Cari</button>
                    @if(request()->filled('search') || request()->filled('rating'))
                    <a href="{{ route('seller.reviews') }}" class="h-10 px-3 inline-flex items-center justify-center border border-gray-200 text-gray-600 hover:border-primary hover:text-primary rounded-lg text-xs font-semibold transition-all">Reset</a>
                    @endif
                </form>
            </div>

            <!-- STATS SECTION -->
            <div class="flex flex-col lg:flex-row gap-6">
                <!-- Overall Rating & Breakdown -->
                <div class="flex-1 bg-white p-6 sm:p-8 rounded-xl border border-gray-100 shadow-sm flex flex-col md:flex-row items-center gap-8 relative overflow-hidden">
                    <div class="absolute bottom-0 left-0 right-0 h-1 bg-amber-600"></div>
                    <div class="text-center md:text-left shrink-0">
                        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-2">Overall Rating</p>
                        <h3 class="text-5xl sm:text-6xl font-bold text-gray-900 tracking-tight">
                            {{ $totalReviews > 0 ? number_format($averageRating, 1) : '—' }}
                            <span class="text-xl text-gray-300 font-semibold">/ 5.0</span>
                        </h3>
                        <div class="flex text-amber-400 text-sm mt-3 justify-center md:justify-start gap-0.5">
                            @for($i=1; $i<=5; $i++)
                            <i class="fa-{{ $i <= round($averageRating) ? 'solid' : 'regular' }} fa-star"></i>
                            @endfor
                        </div>
                        <p class="text-[10px] font-bold text-gray-400 uppercase mt-3 tracking-widest">
                            Berdasarkan {{ number_format($totalReviews) }} Ulasan
                        </p>
                    </div>
                    <div class="flex-1 w-full space-y-3">
                        @foreach([5, 4, 3, 2, 1] as $star)
                        @php
                            $count = $ratingCounts[$star] ?? 0;
                            $percentage = $totalReviews > 0 ? round(($count / $totalReviews) * 100) : 0;
                        @endphp
                        <div class="flex items-center space-x-3">
                            <span class="text-xs font-bold text-gray-500 w-4">{{ $star }}</span>
                            <div class="flex-1 h-2 bg-gray-100 rounded-full overflow-hidden">
                                <div class="h-full bg-primary rounded-full transition-all duration-500" style="width: {{ $percentage }}%"></div>
                            </div>
                            <span class="text-xs font-semibold text-gray-500 w-10 text-right">{{ $percentage }}%</span>
                            <span class="text-xs text-gray-400 w-8">({{ $count }})</span>
                        </div>
                        @endforeach
                    </div>
                </div>

                <!-- Sentiment Card -->
                <div class="w-full lg:w-80 bg-primary p-6 sm:p-8 rounded-xl shadow-xl shadow-primary/20 text-white flex flex-col justify-between relative overflow-hidden">
                    <div class="relative z-10 space-y-6">
                        <div>
                            <p class="text-[10px] font-bold text-white/60 uppercase tracking-widest mb-1">Sentiment Score</p>
                            <h3 class="text-2xl font-bold tracking-tight">{{ $sentimentScore }}</h3>
                        </div>
                        <div>
                            <p class="text-[10px] font-bold text-white/60 uppercase tracking-widest mb-1">Response Rate</p>
                            <h3 class="text-2xl font-bold tracking-tight">{{ number_format($responseRate, 1) }}%</h3>
                        </div>
                        <div>
                            <p class="text-[10px] font-bold text-white/60 uppercase tracking-widest mb-1">Total Reviews</p>
                            <h3 class="text-2xl font-bold tracking-tight">{{ number_format($totalReviews) }}</h3>
                        </div>
                    </div>
                    <div class="absolute -right-10 -bottom-10 w-40 h-40 bg-white/10 rounded-full"></div>
                    <div class="absolute -top-6 -left-6 w-24 h-24 bg-white/5 rounded-full"></div>
                </div>
            </div>

            <!-- REVIEW LIST -->
            <div class="space-y-6">
                @forelse($reviews as $r)
                @php
                    $customerName = $r->customer->user->full_name ?? 'Customer';
                    $initials = strtoupper(substr($customerName, 0, 2));
                @endphp
                <div class="bg-white p-6 sm:p-8 rounded-xl border border-gray-100 shadow-sm flex flex-col lg:flex-row gap-6 lg:gap-8">
                    <div class="flex-1 space-y-5 min-w-0">
                        <!-- Review Header -->
                        <div class="flex items-start justify-between gap-4">
                            <div class="flex items-center space-x-3.5 min-w-0">
                                <div class="w-11 h-11 rounded-xl bg-primary/10 text-primary flex items-center justify-center font-bold text-sm overflow-hidden shrink-0">
                                    @if($r->customer->profile_image)
                                    <img src="{{ asset('storage/' . $r->customer->profile_image) }}" class="w-full h-full object-cover">
                                    @else
                                    {{ $initials }}
                                    @endif
                                </div>
                                <div class="min-w-0">
                                    <div class="flex items-center flex-wrap gap-2">
                                        <h4 class="text-sm font-bold text-gray-900 tracking-tight">{{ $customerName }}</h4>
                                        <div class="flex gap-0.5 text-amber-400 text-xs">
                                            @for($i=0; $i<$r->rating; $i++) <i class="fa-solid fa-star"></i> @endfor
                                            @for($i=$r->rating; $i<5; $i++) <i class="fa-regular fa-star text-gray-200"></i> @endfor
                                        </div>
                                        <span class="text-[10px] font-bold text-white bg-primary px-2 py-0.5 rounded-full">
                                            {{ $r->rating }}/5
                                        </span>
                                    </div>
                                    <p class="text-[11px] font-medium text-gray-400 mt-1">
                                        Verified Purchase &bull; {{ $r->created_at->diffForHumans() }}
                                    </p>
                                </div>
                            </div>

                            @if($r->reply)
                            <span class="text-[10px] font-semibold text-green-700 bg-green-50 border border-green-200 px-3 py-1 rounded-lg shrink-0 inline-flex items-center gap-1.5">
                                <i class="fa-solid fa-circle-check"></i> Sudah Dibalas
                            </span>
                            @else
                            <span class="text-[10px] font-semibold text-amber-700 bg-amber-50 border border-amber-200 px-3 py-1 rounded-lg shrink-0 inline-flex items-center gap-1.5">
                                <i class="fa-solid fa-clock"></i> Belum Dibalas
                            </span>
                            @endif
                        </div>

                        <!-- Comment -->
                        @if($r->comment)
                        <div class="bg-gray-50/70 px-5 py-4 rounded-xl border border-gray-100">
                            <p class="text-xs font-medium text-gray-700 leading-relaxed">"{{ $r->comment }}"</p>
                        </div>
                        @else
                        <p class="text-xs text-gray-400">— Tidak ada komentar —</p>
                        @endif

                        <!-- Reply / Form -->
                        @if($r->reply)
                        <div class="bg-primary/5 border border-primary/10 p-5 rounded-xl space-y-2">
                            <div class="flex flex-wrap items-center justify-between gap-2">
                                <p class="text-[10px] font-bold text-primary uppercase tracking-widest">
                                    <i class="fa-solid fa-reply mr-1.5"></i>Balasan Anda
                                </p>
                                <span class="text-[11px] text-gray-400">{{ $r->updated_at->format('d M Y') }}</span>
                            </div>
                            <p class="text-xs font-medium text-gray-700 leading-relaxed">{{ $r->reply }}</p>
                        </div>
                        @else
                        <div class="bg-gray-50/60 p-5 rounded-xl border border-gray-100 space-y-3">
                            <p class="text-[10px] font-bold text-gray-500 uppercase tracking-widest">Tulis Balasan</p>
                            <form action="{{ route('seller.reviews.reply', $r->id) }}" method="POST" class="space-y-3">
                                @csrf
                                <textarea name="reply" rows="3" placeholder="Tulis balasan untuk {{ explode(' ', $customerName)[0] }}..." class="w-full bg-white border @error('reply') border-red-500 @else border-gray-200 @enderror rounded-lg p-3.5 text-xs font-medium text-gray-700 placeholder-gray-400 focus:outline-none focus:border-primary focus:ring-2 focus:ring-primary/10 resize-none transition-all"></textarea>
                                @error('reply')
                                <p class="text-red-500 text-xs font-medium"><i class="fa-solid fa-circle-exclamation mr-1"></i> Kolom balasan tidak boleh kosong.</p>
                                @enderror
                                <div class="flex justify-end">
                                    <button type="submit" class="h-10 px-4 bg-primary text-white rounded-lg text-xs font-semibold hover:bg-opacity-90 transition-all inline-flex items-center gap-2">
                                        <i class="fa-solid fa-paper-plane"></i>
                                        <span>Kirim Balasan</span>
                                    </button>
                                </div>
                            </form>
                        </div>
                        @endif
                    </div>

                    <!-- Reviewed Product Card -->
                    <div class="w-full lg:w-52 shrink-0 lg:border-l lg:border-gray-100 lg:pl-6 flex lg:flex-col items-center lg:text-center gap-4">
                        <img src="{{ $r->product->images->first()->img_url ?? 'https://via.placeholder.com/200' }}" class="w-20 h-20 lg:w-full lg:h-36 rounded-xl object-cover border border-gray-100 shrink-0">
                        <div class="min-w-0 flex-1 lg:flex-initial">
                            <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Produk Diulas</p>
                            <h5 class="text-xs font-bold text-gray-800 mt-1 line-clamp-2">{{ $r->product->name }}</h5>
                            <p class="text-sm font-bold text-primary mt-1">Rp {{ number_format($r->product->price, 0, ',', '.') }}</p>
                            <a href="{{ route('customer.product-detail', $r->product_id) }}" target="_blank" class="inline-flex items-center justify-center gap-1.5 text-[11px] font-semibold text-gray-500 hover:text-primary mt-2.5 transition-colors">
                                <i class="fa-regular fa-eye"></i>
                                <span>Lihat Produk</span>
                            </a>
                        </div>
                    </div>
                </div>
                @empty
                <div class="bg-white p-14 rounded-xl border border-gray-100 text-center shadow-sm">
                    <div class="w-16 h-16 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fa-regular fa-star text-2xl text-gray-300"></i>
                    </div>
                    <h4 class="text-base font-bold text-gray-700">Belum ada ulasan</h4>
                    <p class="text-xs text-gray-400 mt-1">
                        @if(request('search') || request('rating'))
                        Tidak ada ulasan yang cocok dengan filter Anda.
                        @else
                        Ulasan dari customer akan muncul di sini setelah pesanan selesai.
                        @endif
                    </p>
                </div>
                @endforelse
            </div>

            @include('partials.pagination', ['paginator' => $reviews, 'label' => 'review', 'standalone' => true])
        </div>

        @include('partials.footer')
    </main>
</body>
</html>