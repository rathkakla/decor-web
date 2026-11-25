<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Decor Seller - Chat Admin Support</title>
    <script src="https://cdn.tailwindcss.com"></script>
    @include('seller.partials.head')
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        .bubble-admin { border-radius: 2px 18px 18px 18px; }
        .bubble-seller { border-radius: 18px 2px 18px 18px; }
    </style>
</head>
<body class="text-gray-800">

    @include('seller.partials.sidebar')

    @php
        $supports = \App\Models\Support::where('user_id', Auth::id())->latest()->take(20)->get();
    @endphp

    <main id="main-content" class="flex-1 flex flex-col ml-0 md:ml-64 sidebar-transition min-h-screen">
        @include('seller.partials.header', ['title' => 'Admin Support Chat'])

        <div class="p-4 sm:p-6 lg:p-8 flex-1 flex flex-col">
            <div class="mb-5">
                <div class="text-[10px] font-bold text-primary uppercase tracking-widest mb-1">Support Center</div>
                <h2 class="text-2xl font-bold tracking-tight text-gray-900">Chat with Admin</h2>
                <p class="text-xs text-gray-500 mt-1">Riwayat komunikasi Anda dengan tim support resmi DECOR.</p>
            </div>

            <div class="bg-white rounded-xl border border-gray-100 shadow-sm flex flex-1 overflow-hidden min-h-[480px] flex-col">
                <div class="px-5 sm:px-8 py-4 border-b border-gray-100 bg-white flex justify-between items-center">
                    <div class="flex items-center space-x-3">
                        <div class="w-11 h-11 rounded-xl bg-primary/10 flex items-center justify-center font-bold text-primary text-sm tracking-tight">
                            AD
                        </div>
                        <div>
                            <h4 class="text-sm font-bold text-gray-800 uppercase tracking-tight">Official Admin Support</h4>
                            <p class="text-[10px] text-gray-400 font-semibold">DECOR Merchant Success Team</p>
                        </div>
                    </div>
                    <a href="{{ route('seller.support') }}" class="text-[10px] font-bold uppercase tracking-widest text-gray-400 hover:text-primary transition-colors">
                        <i class="fa-solid fa-chevron-left mr-1.5"></i> Back to Center
                    </a>
                </div>

                <div class="flex-1 overflow-y-auto p-5 sm:p-8 space-y-6 bg-[#FCFBFB]">
                    <div class="flex items-start space-x-3 max-w-[85%] sm:max-w-[70%]">
                        <div class="bg-white bubble-admin p-4 text-xs font-medium leading-relaxed border border-gray-100 shadow-sm text-gray-600">
                            Halo {{ Auth::user()->full_name }}! Ini kanal riwayat komplain Anda. Kirim komplain baru dari Support Center, balasan admin akan tampil di sini.
                        </div>
                    </div>

                    @forelse($supports as $s)
                        <div class="flex items-end space-x-3 max-w-[90%] sm:max-w-[70%] ml-auto flex-row-reverse">
                            <div class="bg-primary text-white bubble-seller p-4 text-xs font-medium leading-relaxed shadow-lg shadow-primary/10">
                                <p class="text-[9px] font-bold uppercase tracking-widest opacity-80 mb-1">{{ $s->subject }}</p>
                                {{ $s->message }}
                                <p class="text-[9px] opacity-70 mt-2">{{ $s->created_at->format('d M Y, H:i') }}</p>
                            </div>
                        </div>

                        @if($s->admin_reply)
                            <div class="flex items-start space-x-3 max-w-[90%] sm:max-w-[70%]">
                                <div class="bg-white bubble-admin p-4 text-xs font-medium leading-relaxed border border-gray-100 shadow-sm text-gray-600">
                                    <p class="text-[9px] font-bold uppercase tracking-widest text-primary mb-1">Admin Reply</p>
                                    {{ $s->admin_reply }}
                                </div>
                            </div>
                        @else
                            <div class="flex items-start space-x-3 max-w-[90%] sm:max-w-[70%]">
                                <div class="bg-amber-50 bubble-admin p-3 text-[11px] font-semibold text-amber-700 border border-amber-100">
                                    <i class="fa-solid fa-clock mr-1.5"></i> Menunggu balasan admin.
                                </div>
                            </div>
                        @endif
                    @empty
                        <div class="text-center py-12">
                            <i class="fa-solid fa-comments text-3xl text-gray-200 mb-3 block"></i>
                            <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Belum ada percakapan</p>
                        </div>
                    @endforelse
                </div>

                <div class="p-4 sm:p-6 bg-white border-t border-gray-100 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                    <p class="text-[11px] text-gray-400 font-medium">
                        <i class="fa-solid fa-circle-info text-primary mr-1.5"></i>
                        Kanal ini bersifat riwayat. Kirim komplain baru untuk memulai percakapan.
                    </p>
                    <a href="{{ route('seller.support') }}" class="inline-flex items-center justify-center bg-primary text-white h-10 px-5 rounded-lg text-[10px] font-bold uppercase tracking-widest hover:opacity-90 transition-all shrink-0">
                        <i class="fa-solid fa-paper-plane mr-2"></i> Kirim Komplain Baru
                    </a>
                </div>
            </div>
        </div>

        @include('partials.footer')
    </main>
</body>
</html>
