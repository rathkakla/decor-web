<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Decor Seller - Merchant Support</title>
    <script src="https://cdn.tailwindcss.com"></script>
    @include('seller.partials.head')
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body class="text-gray-800">

    @include('seller.partials.sidebar')

    <main id="main-content" class="flex-1 flex flex-col ml-0 md:ml-64 sidebar-transition min-h-screen relative">
        @include('seller.partials.header', ['title' => 'Support Center'])

        <div class="p-4 sm:p-6 lg:p-8 space-y-6 flex-1">
            <div class="max-w-4xl">
                <div class="text-[10px] font-bold text-primary uppercase tracking-widest mb-1">Help &amp; Assistance</div>
                <h2 class="text-2xl sm:text-3xl font-bold text-gray-900 tracking-tight">Merchant Support</h2>
                <p class="text-xs sm:text-sm text-gray-500 mt-2 max-w-xl leading-relaxed">How can we help you today? Our specialized support team is here to ensure your boutique remains exceptional.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
                <div class="bg-white p-5 sm:p-6 rounded-xl border border-gray-100 shadow-sm space-y-5">
                    <div class="w-11 h-11 bg-primary/10 rounded-xl flex items-center justify-center text-primary"><i class="fa-solid fa-book-open"></i></div>
                    <div>
                        <h3 class="text-base font-bold text-gray-900">Help Center</h3>
                        <p class="text-[11px] text-gray-400 font-medium leading-relaxed mt-2">Browse our curated guides on logistics, inventory management, and photography standards.</p>
                    </div>
                    <div class="w-8 h-[2px] bg-primary/20"></div>
                </div>

                <div class="bg-white p-5 sm:p-6 rounded-xl border border-gray-100 shadow-sm space-y-5">
                    <div class="w-11 h-11 bg-primary/10 rounded-xl flex items-center justify-center text-primary"><i class="fa-solid fa-envelope"></i></div>
                    <div>
                        <h3 class="text-base font-bold text-gray-900">Contact Us</h3>
                        <p class="text-[11px] text-gray-400 font-medium leading-relaxed mt-2">Submit a formal inquiry regarding your partnership or bespoke order fulfillment.</p>
                    </div>
                    <div class="w-8 h-[2px] bg-primary/20"></div>
                </div>

                <div class="bg-white p-5 sm:p-6 rounded-xl border border-gray-100 shadow-sm space-y-5">
                    <div class="w-11 h-11 bg-primary/10 rounded-xl flex items-center justify-center text-primary"><i class="fa-solid fa-comments"></i></div>
                    <div>
                        <h3 class="text-base font-bold text-gray-900">Live Chat</h3>
                        <p class="text-[11px] text-gray-400 font-medium leading-relaxed mt-2">Connect instantly with a dedicated Merchant Success Manager for real-time assistance.</p>
                    </div>
                    <div class="w-8 h-[2px] bg-primary/20"></div>
                </div>
            </div>

            <div class="bg-secondary/40 p-5 sm:p-8 rounded-xl flex flex-col md:flex-row md:items-center justify-between gap-5 border border-white">
                <div class="max-w-xl space-y-2">
                    <h3 class="text-lg sm:text-xl font-bold text-gray-900">Dedicated Support for Curators</h3>
                    <p class="text-[11px] font-semibold text-gray-500 leading-relaxed">Our premium support tier ensures that high-end merchants receive priority response times and strategic consultation for business growth.</p>
                </div>
                <a href="{{ route('seller.support.chat') }}" class="inline-flex items-center justify-center bg-primary text-white px-6 py-3 rounded-xl text-[10px] font-bold uppercase tracking-[0.2em] shadow-lg shadow-primary/20 hover:opacity-90 transition-all shrink-0">
                    <i class="fa-solid fa-headset mr-2"></i> Chat with Admin
                </a>
            </div>

            <!-- COMPLAINT FORM -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div class="bg-white p-5 sm:p-6 rounded-xl border border-gray-100 shadow-sm space-y-5">
                    <div>
                        <h3 class="text-base font-bold text-gray-900">Kirim Komplain</h3>
                        <p class="text-[11px] text-gray-400 mt-0.5">Sampaikan kendala operasional atau pertanyaan ke tim admin DECOR.</p>
                    </div>

                    @if(session('success'))
                        <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-xl text-xs font-semibold" role="alert">
                            <i class="fa-solid fa-circle-check mr-1.5"></i>{{ session('success') }}
                        </div>
                    @endif

                    <form action="{{ route('seller.support.submit') }}" method="POST" class="space-y-4">
                        @csrf
                        <div>
                            <label class="block text-xs font-semibold text-gray-700 mb-1.5">Subjek</label>
                            <input type="text" name="subject" class="w-full h-10 bg-white @error('subject') border-red-500 @else border-gray-200 @enderror border rounded-lg px-3.5 text-xs font-medium outline-none focus:border-primary focus:ring-2 focus:ring-primary/10 transition-all" placeholder="Contoh: Masalah Pembayaran">
                            @error('subject')
                                <p class="text-red-500 text-[10px] font-semibold mt-1.5"><i class="fa-solid fa-circle-exclamation mr-1"></i> Kolom subjek tidak boleh kosong.</p>
                            @enderror
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-gray-700 mb-1.5">Pesan Detail</label>
                            <textarea name="message" rows="4" class="w-full bg-white @error('message') border-red-500 @else border-gray-200 @enderror border rounded-lg p-3.5 text-xs font-medium outline-none focus:border-primary focus:ring-2 focus:ring-primary/10 transition-all" placeholder="Jelaskan kendala Anda..."></textarea>
                            @error('message')
                                <p class="text-red-500 text-[10px] font-semibold mt-1.5"><i class="fa-solid fa-circle-exclamation mr-1"></i> Kolom pesan detail tidak boleh kosong.</p>
                            @enderror
                        </div>
                        <button type="submit" class="bg-primary text-white px-6 py-2.5 rounded-lg text-[10px] font-bold uppercase tracking-widest hover:opacity-90 transition-all shadow-lg shadow-primary/20">
                            Kirim Sekarang
                        </button>
                    </form>
                </div>

                <div class="bg-white p-5 sm:p-6 rounded-xl border border-gray-100 shadow-sm flex flex-col">
                    <div class="mb-4">
                        <h3 class="text-base font-bold text-gray-900">Riwayat Komplain</h3>
                        <p class="text-[11px] text-gray-400 mt-0.5">Balasan admin akan muncul di kartu yang sesuai.</p>
                    </div>
                    <div class="flex-1 max-h-[420px] overflow-y-auto space-y-3 pr-1">
                        @php
                            $supports = \App\Models\Support::where('user_id', Auth::id())->latest()->get();
                        @endphp
                        @forelse($supports as $s)
                        <div class="p-4 rounded-xl border border-gray-100 bg-gray-50/60">
                            <div class="flex justify-between items-start gap-2 mb-1.5">
                                <h4 class="text-xs font-bold uppercase tracking-tight text-gray-800">{{ $s->subject }}</h4>
                                <span class="text-[8px] font-bold uppercase px-2 py-1 rounded bg-{{ $s->status === 'pending' ? 'yellow-100 text-yellow-700' : ($s->status === 'replied' ? 'blue-100 text-blue-700' : 'green-100 text-green-700') }}">
                                    {{ $s->status }}
                                </span>
                            </div>
                            <p class="text-[10px] text-gray-500 font-medium mb-2 leading-relaxed">{{ $s->message }}</p>
                            @if($s->admin_reply)
                            <div class="mt-2 p-3 rounded-lg bg-primary/5 border-l-4 border-primary">
                                <p class="text-[9px] font-bold uppercase text-primary mb-1">Admin Reply:</p>
                                <p class="text-[10px] text-gray-600 font-medium">{{ $s->admin_reply }}</p>
                            </div>
                            @endif
                            <div class="mt-2 text-[8px] text-gray-400 font-bold uppercase">{{ $s->created_at->diffForHumans() }}</div>
                        </div>
                        @empty
                        <div class="text-center py-10">
                            <i class="fa-solid fa-inbox text-3xl text-gray-200 mb-3 block"></i>
                            <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Belum ada riwayat</p>
                        </div>
                        @endforelse
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-12 gap-5">
                <div class="md:col-span-8 rounded-xl overflow-hidden shadow-sm h-56 sm:h-72">
                    <img src="https://images.unsplash.com/photo-1497366216548-37526070297c?q=80&w=1200" class="w-full h-full object-cover grayscale opacity-90" alt="Merchant workspace">
                </div>
                <div class="md:col-span-4 bg-[#1A1A1A] p-6 sm:p-8 rounded-xl flex flex-col justify-between text-white relative overflow-hidden">
                    <div class="relative z-10">
                        <div class="bg-primary/20 w-10 h-10 rounded-lg flex items-center justify-center mb-6 text-primary">
                            <i class="fa-solid fa-certificate"></i>
                        </div>
                        <h4 class="text-lg font-bold leading-tight">Verified Merchant Program</h4>
                        <p class="text-[10px] font-medium text-gray-400 mt-4 leading-relaxed">As a premium member of The Curator, you have access to 24/7 technical oversight and curated marketing insights.</p>
                    </div>
                    <div class="absolute -right-10 -bottom-10 w-40 h-40 bg-white/5 rounded-full"></div>
                </div>
            </div>
        </div>

        @include('partials.footer')
    </main>
</body>
</html>
