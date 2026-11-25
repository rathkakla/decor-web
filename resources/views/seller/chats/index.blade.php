<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Decor Seller - Chat</title>
    <script src="https://cdn.tailwindcss.com"></script>
    @include('seller.partials.head')
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        body { overflow: hidden; }
        .chat-bubble-me { border-radius: 18px 2px 18px 18px; }
        .chat-bubble-them { border-radius: 2px 18px 18px 18px; }
        ::-webkit-scrollbar { width: 5px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: #E3DCD6; border-radius: 10px; }
        ::-webkit-scrollbar-thumb:hover { background: #B5733A; }
    </style>
</head>
<body class="text-gray-800">

    @include('seller.partials.sidebar')

    <main id="main-content" class="flex-1 flex flex-col ml-0 md:ml-64 sidebar-transition h-screen relative">

        @include('seller.partials.header', ['title' => 'Chat'])

        @php
            $chatOpen = (bool) ($activeChat || $activeRfq);
        @endphp

        <!-- Tabs: Customer / B2B Partner -->
        <div class="bg-white border-b border-gray-100 px-4 sm:px-8 flex items-center space-x-2 flex-shrink-0 overflow-x-auto hide-scrollbar">
            <a href="{{ route('seller.chats', ['tab' => 'customer']) }}" class="px-4 sm:px-5 py-4 text-[10px] font-bold uppercase tracking-widest border-b-2 transition-all whitespace-nowrap {{ $tab === 'customer' ? 'border-primary text-primary' : 'border-transparent text-gray-400 hover:text-primary' }}">
                <i class="fa-regular fa-user mr-1.5"></i> Customer
            </a>
            @if($b2bEnabled ?? false)
                <a href="{{ route('seller.chats', ['tab' => 'b2b']) }}" class="px-4 sm:px-5 py-4 text-[10px] font-bold uppercase tracking-widest border-b-2 transition-all flex items-center gap-2 whitespace-nowrap {{ $tab === 'b2b' ? 'border-primary text-primary' : 'border-transparent text-gray-400 hover:text-primary' }}">
                    <i class="fa-solid fa-industry"></i> B2B Partner
                    @if(($b2bUnread ?? 0) > 0)
                        <span class="bg-primary text-white text-[9px] px-2 py-0.5 rounded-full">{{ $b2bUnread }}</span>
                    @endif
                </a>
            @endif
        </div>

        <div class="flex-1 flex overflow-hidden">
            <!-- CONVERSATION LIST -->
            <div class="w-full md:w-80 bg-white border-r border-gray-100 flex-col {{ $chatOpen ? 'hidden md:flex' : 'flex' }}">
                <div class="p-4 sm:p-6 border-b border-gray-50">
                    <h3 class="text-lg font-bold tracking-tight text-gray-800">{{ $tab === 'b2b' ? 'Proyek B2B' : 'Conversations' }}</h3>
                    <form action="{{ route('seller.chats') }}" method="GET" class="relative mt-4">
                        <input type="hidden" name="tab" value="{{ $tab }}">
                        <i class="fa-solid fa-magnifying-glass absolute left-3 top-1/2 -translate-y-1/2 text-gray-300 text-[10px]"></i>
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Search chats..." class="w-full bg-gray-50 rounded-xl py-2 pl-9 pr-4 text-[10px] font-bold outline-none border border-transparent focus:border-primary/20" onchange="this.form.submit()">
                    </form>
                </div>
                <div class="flex-1 overflow-y-auto">
                    @if($tab === 'customer')
                        @forelse($conversations as $conv)
                            <a href="{{ route('seller.chats', ['userId' => $conv->id, 'tab' => 'customer']) }}" class="flex items-center p-4 sm:p-6 border-b border-gray-50 hover:bg-gray-50 transition-all {{ $activeChat && $activeChat->id == $conv->id ? 'bg-amber-50/30 border-r-4 border-primary' : '' }}">
                                <div class="w-12 h-12 rounded-xl bg-gray-100 flex items-center justify-center font-bold text-gray-400 text-sm overflow-hidden shrink-0">
                                    @if($conv->avatar_url)
                                        <img src="{{ $conv->avatar_url }}" alt="{{ $conv->full_name }}" class="w-full h-full object-cover">
                                    @else
                                        {{ substr($conv->full_name, 0, 1) }}
                                    @endif
                                </div>
                                <div class="ml-4 flex-1 overflow-hidden">
                                    <div class="flex justify-between items-start">
                                        <h4 class="text-xs font-bold text-gray-800 truncate">{{ $conv->full_name }}</h4>
                                        <span class="text-[8px] font-bold text-gray-300 uppercase">Live</span>
                                    </div>
                                    <p class="text-[10px] text-gray-400 mt-1 truncate font-medium">Click to view messages</p>
                                </div>
                            </a>
                        @empty
                            <div class="p-6 sm:p-8 text-center">
                                <p class="text-[10px] font-bold text-gray-300 uppercase tracking-widest">No chats yet</p>
                            </div>
                        @endforelse
                    @else
                        @forelse($b2bConversations as $rfq)
                            @php $lastB2b = $rfq->negotiations->first(); @endphp
                            <a href="{{ route('seller.chats', ['tab' => 'b2b', 'rfq' => $rfq->id]) }}" class="flex items-center p-4 sm:p-6 border-b border-gray-50 hover:bg-gray-50 transition-all {{ $activeRfq && $activeRfq->id == $rfq->id ? 'bg-amber-50/30 border-r-4 border-primary' : '' }}">
                                <div class="w-12 h-12 rounded-xl bg-gray-100 flex items-center justify-center font-bold text-gray-400 text-[10px] overflow-hidden shrink-0 text-center leading-tight">
                                    {{ strtoupper(substr($rfq->designer->user->full_name ?? 'DSN', 0, 2)) }}
                                </div>
                                <div class="ml-4 flex-1 overflow-hidden">
                                    <div class="flex justify-between items-start gap-2">
                                        <h4 class="text-xs font-bold text-gray-800 truncate">{{ $rfq->project_name }}</h4>
                                        @if(($rfq->unread_count ?? 0) > 0)
                                            <span class="bg-primary text-white text-[9px] px-2 py-0.5 rounded-full flex-shrink-0">{{ $rfq->unread_count }}</span>
                                        @endif
                                    </div>
                                    <p class="text-[10px] text-primary mt-0.5 truncate font-bold">{{ $rfq->designer->user->full_name ?? 'Designer' }} ({{ $rfq->designer->studio_name ?? 'Studio' }})</p>
                                    @if($lastB2b)
                                        <p class="text-[9px] text-gray-400 truncate mt-0.5">{{ \Illuminate\Support\Str::limit(str_replace('[Sistem] ', '', $lastB2b->message), 42) }}</p>
                                    @endif
                                </div>
                            </a>
                        @empty
                            <div class="p-6 sm:p-8 text-center">
                                <p class="text-[10px] font-bold text-gray-300 uppercase tracking-widest">Belum ada obrolan B2B</p>
                            </div>
                        @endforelse
                    @endif
                </div>
            </div>

            <!-- CHAT WINDOW -->
            <div class="flex-1 flex-col bg-[#FCFBFB] {{ $chatOpen ? 'flex' : 'hidden md:flex' }}">
                @if($tab === 'customer')
                    @if($activeChat)
                        <!-- CHAT HEADER -->
                        <div class="px-4 sm:px-8 py-4 sm:py-5 border-b border-gray-50 bg-white flex justify-between items-center gap-3 shrink-0">
                            <div class="flex items-center space-x-3 sm:space-x-4 min-w-0">
                                <a href="{{ route('seller.chats', ['tab' => 'customer']) }}" class="md:hidden w-9 h-9 rounded-lg bg-gray-50 text-gray-400 hover:text-primary flex items-center justify-center shrink-0" aria-label="Back to conversations">
                                    <i class="fa-solid fa-chevron-left text-xs"></i>
                                </a>
                                <div class="w-10 h-10 rounded-xl bg-primary/10 flex items-center justify-center font-bold text-primary text-xs shrink-0">
                                    {{ substr($activeChat->full_name, 0, 1) }}
                                </div>
                                <div class="min-w-0">
                                    <h4 class="text-xs font-bold text-gray-800 uppercase tracking-tight truncate">{{ $activeChat->full_name }}</h4>
                                    <p class="text-[9px] text-green-500 font-bold uppercase tracking-widest">Online</p>
                                </div>
                            </div>
                        </div>

                        <!-- MESSAGES AREA -->
                        <div class="flex-1 overflow-y-auto p-4 sm:p-8 space-y-6 sm:space-y-8" id="message-container">
                            @forelse($messages as $msg)
                                <div class="flex {{ $msg->sender_id == Auth::id() ? 'justify-end' : 'justify-start' }}">
                                    <div class="max-w-[85%] sm:max-w-[70%] space-y-2">
                                        @if($msg->product)
                                            @php
                                                $chatProductImage = $msg->product->images->first()->img_url ?? null;
                                            @endphp
                                            <a href="{{ route('customer.product-detail', $msg->product_id) }}" target="_blank" class="bg-white p-3 rounded-xl border border-gray-100 shadow-sm mb-2 flex items-center space-x-3 hover:border-primary/40 transition-colors">
                                                @if($chatProductImage)
                                                    <img src="{{ $chatProductImage }}" alt="{{ $msg->product->name }}" class="w-10 h-10 rounded-lg object-cover">
                                                @else
                                                    <div class="w-10 h-10 rounded-lg bg-gray-100 flex items-center justify-center text-gray-300">
                                                        <i class="fa-solid fa-image text-[10px]"></i>
                                                    </div>
                                                @endif
                                                <div>
                                                    <p class="text-[9px] font-bold text-gray-800 uppercase truncate w-32">{{ $msg->product->name }}</p>
                                                    <p class="text-[8px] font-bold text-primary">Rp {{ number_format($msg->product->price, 0, ',', '.') }}</p>
                                                </div>
                                            </a>
                                        @endif
                                        <div class="p-4 text-[11px] font-semibold leading-relaxed shadow-sm {{ $msg->sender_id == Auth::id() ? 'bg-primary text-white chat-bubble-me' : 'bg-white text-gray-600 chat-bubble-them border border-gray-50' }}">
                                            @if($msg->attachment)
                                                @php
                                                    $ext = pathinfo($msg->attachment, PATHINFO_EXTENSION);
                                                    $isImage = in_array(strtolower($ext), ['jpg', 'jpeg', 'png', 'gif']);
                                                @endphp
                                                <div class="mb-2">
                                                    @if($isImage)
                                                        <a href="{{ asset('storage/' . $msg->attachment) }}" target="_blank">
                                                            <img src="{{ asset('storage/' . $msg->attachment) }}" alt="Attachment" class="rounded-xl max-w-full h-auto border border-white/20 shadow-sm">
                                                        </a>
                                                    @else
                                                        <a href="{{ asset('storage/' . $msg->attachment) }}" target="_blank" class="flex items-center gap-2 p-3 {{ $msg->sender_id == Auth::id() ? 'bg-white/10' : 'bg-gray-50' }} rounded-xl border border-white/20 hover:scale-[1.02] transition-all">
                                                            <i class="fa-solid fa-file-lines"></i>
                                                            <span class="truncate">{{ basename($msg->attachment) }}</span>
                                                        </a>
                                                    @endif
                                                </div>
                                            @endif

                                            @if($msg->message)
                                                {{ $msg->message }}
                                            @endif
                                        </div>
                                        <p class="text-[8px] font-bold text-gray-300 uppercase {{ $msg->sender_id == Auth::id() ? 'text-right' : 'text-left' }}">
                                            {{ $msg->created_at->format('H:i') }}
                                        </p>
                                    </div>
                                </div>
                            @empty
                                <div class="flex flex-col items-center justify-center text-center py-16">
                                    <div class="w-16 h-16 bg-gray-50 rounded-xl flex items-center justify-center text-gray-200 mb-4">
                                        <i class="fa-solid fa-message text-2xl"></i>
                                    </div>
                                    <p class="text-[10px] text-gray-400 font-bold uppercase tracking-widest">Belum ada pesan</p>
                                </div>
                            @endforelse
                        </div>

                        <!-- INPUT AREA -->
                        <div class="p-4 sm:p-8 bg-white border-t border-gray-100 shrink-0">
                            <form action="{{ route('seller.chats.send') }}" method="POST" enctype="multipart/form-data" class="flex items-center space-x-3 sm:space-x-6">
                                @csrf
                                <input type="hidden" name="receiver_id" value="{{ $activeChat->id }}">

                                <input type="file" name="attachment" id="chat-attachment" class="hidden" onchange="document.getElementById('attachment-preview').classList.remove('hidden'); document.getElementById('file-name').textContent = this.files[0].name;">

                                <button type="button" onclick="document.getElementById('chat-attachment').click()" class="text-gray-300 hover:text-primary transition-all shrink-0" aria-label="Attach file">
                                    <i class="fa-solid fa-paperclip text-lg sm:text-xl"></i>
                                </button>

                                <div id="attachment-preview" class="hidden flex items-center gap-2 bg-primary/10 px-3 py-1 rounded-full border border-primary/20">
                                    <span id="file-name" class="text-[9px] text-primary font-bold truncate max-w-[120px]"></span>
                                    <button type="button" onclick="document.getElementById('chat-attachment').value=''; document.getElementById('attachment-preview').classList.add('hidden');" class="text-primary hover:text-red-500" aria-label="Remove attachment">
                                        <i class="fa-solid fa-xmark text-[10px]"></i>
                                    </button>
                                </div>

                                <div class="flex-1 relative min-w-0">
                                    <input type="text" name="message" placeholder="Type your message here..." class="w-full bg-gray-50 rounded-xl py-3.5 sm:py-4 pl-4 sm:pl-6 pr-14 text-[11px] font-bold outline-none border-2 border-transparent focus:border-primary/10 focus:bg-white transition-all" autocomplete="off">
                                    <button type="submit" class="absolute right-2 top-1/2 -translate-y-1/2 bg-primary text-white w-10 h-10 rounded-xl flex items-center justify-center shadow-xl shadow-primary/20 hover:scale-105 transition-all" aria-label="Send message">
                                        <i class="fa-solid fa-paper-plane text-sm"></i>
                                    </button>
                                </div>
                            </form>
                        </div>
                    @else
                        <div class="flex-1 flex flex-col items-center justify-center text-center p-10 sm:p-20">
                            <div class="w-20 h-20 bg-gray-50 rounded-xl flex items-center justify-center text-gray-200 mb-6">
                                <i class="fa-solid fa-message text-3xl"></i>
                            </div>
                            <h3 class="text-lg font-bold text-gray-800">Pilih Percakapan</h3>
                            <p class="text-[10px] text-gray-400 mt-2 font-bold uppercase tracking-[0.2em] max-w-xs leading-loose">Pilih customer di daftar kiri untuk mulai membalas pertanyaan mereka.</p>
                        </div>
                    @endif
                @else
                    @if($activeRfq)
                        <!-- B2B CHAT HEADER -->
                        <div class="px-4 sm:px-8 py-4 sm:py-5 border-b border-gray-50 bg-white flex justify-between items-center gap-3 shrink-0">
                            <div class="flex items-center space-x-3 sm:space-x-4 min-w-0">
                                <a href="{{ route('seller.chats', ['tab' => 'b2b']) }}" class="md:hidden w-9 h-9 rounded-lg bg-gray-50 text-gray-400 hover:text-primary flex items-center justify-center shrink-0" aria-label="Back to projects">
                                    <i class="fa-solid fa-chevron-left text-xs"></i>
                                </a>
                                <div class="w-10 h-10 rounded-xl bg-primary/10 flex items-center justify-center font-bold text-primary text-xs shrink-0">
                                    {{ strtoupper(substr($activeRfq->designer->user->full_name ?? 'DSN', 0, 1)) }}
                                </div>
                                <div class="min-w-0">
                                    <h4 class="text-xs font-bold text-gray-800 uppercase tracking-tight truncate">{{ $activeRfq->project_name }}</h4>
                                    <p class="text-[9px] text-primary font-bold uppercase tracking-widest truncate">{{ $activeRfq->designer->user->full_name ?? 'Designer' }} • #{{ $activeRfq->rfq_number }}</p>
                                </div>
                            </div>
                            <a href="{{ b2b_route('seller.b2b.negosiasi', $activeRfq) }}" class="text-[9px] font-bold uppercase tracking-widest text-primary hover:underline shrink-0 hidden sm:inline">
                                Buka Detail RFQ <i class="fa-solid fa-arrow-up-right-from-square ml-1"></i>
                            </a>
                        </div>

                        <!-- B2B MESSAGES AREA -->
                        <div class="flex-1 overflow-y-auto p-4 sm:p-8 space-y-6 sm:space-y-8" id="message-container">
                            @forelse($b2bMessages as $msg)
                                @php
                                    $mine = $msg->sender_id === Auth::id();
                                    $isSystem = str_starts_with($msg->message, '[Sistem]');
                                @endphp
                                <div class="flex {{ $isSystem ? 'justify-center' : ($mine ? 'justify-end' : 'justify-start') }}">
                                    <div class="max-w-[85%] sm:max-w-[70%] space-y-2">
                                        @if($isSystem)
                                            <div class="p-3 text-[10px] font-bold text-amber-700 bg-amber-50 border border-amber-100 rounded-xl text-center">
                                                <i class="fa-solid fa-circle-info mr-1"></i> {{ str_replace('[Sistem] ', '', $msg->message) }}
                                            </div>
                                        @else
                                            <div class="p-4 text-[11px] font-semibold leading-relaxed shadow-sm {{ $mine ? 'bg-primary text-white chat-bubble-me' : 'bg-white text-gray-600 chat-bubble-them border border-gray-50' }}">
                                                @if($msg->attachment_url)
                                                    @php
                                                        $ext = pathinfo($msg->attachment_url, PATHINFO_EXTENSION);
                                                        $isImage = in_array(strtolower($ext), ['jpg', 'jpeg', 'png', 'gif']);
                                                    @endphp
                                                    <div class="mb-2">
                                                        @if($isImage)
                                                            <a href="{{ asset('storage/' . $msg->attachment_url) }}" target="_blank">
                                                                <img src="{{ asset('storage/' . $msg->attachment_url) }}" alt="Attachment" class="rounded-xl max-w-full h-auto border border-white/20 shadow-sm">
                                                            </a>
                                                        @else
                                                            <a href="{{ asset('storage/' . $msg->attachment_url) }}" target="_blank" class="flex items-center gap-2 p-3 {{ $mine ? 'bg-white/10' : 'bg-gray-50' }} rounded-xl border border-white/20 hover:scale-[1.02] transition-all">
                                                                <i class="fa-solid fa-file-lines"></i>
                                                                <span class="truncate">{{ basename($msg->attachment_url) }}</span>
                                                            </a>
                                                        @endif
                                                    </div>
                                                @endif
                                                {{ $msg->message }}
                                            </div>
                                        @endif
                                        <p class="text-[8px] font-bold text-gray-300 uppercase {{ $isSystem ? 'text-center' : ($mine ? 'text-right' : 'text-left') }}">
                                            {{ $msg->sender->full_name ?? 'Sistem' }} • {{ $msg->created_at->format('H:i') }}
                                        </p>
                                    </div>
                                </div>
                            @empty
                                <div class="flex flex-col items-center justify-center text-center py-16">
                                    <div class="w-16 h-16 bg-gray-50 rounded-xl flex items-center justify-center text-gray-200 mb-4">
                                        <i class="fa-solid fa-comments text-2xl"></i>
                                    </div>
                                    <p class="text-[10px] text-gray-400 font-bold uppercase tracking-widest">Belum ada pesan. Mulai diskusi teknis dengan desainer.</p>
                                </div>
                            @endforelse
                        </div>

                        <!-- B2B INPUT AREA -->
                        <div class="p-4 sm:p-8 bg-white border-t border-gray-100 shrink-0">
                            <form action="{{ b2b_route('seller.b2b.negosiasi.message', $activeRfq) }}" method="POST" enctype="multipart/form-data" class="flex items-center space-x-3 sm:space-x-6">
                                @csrf
                                <input type="file" name="attachment" id="b2b-attachment" class="hidden" onchange="document.getElementById('b2b-attachment-preview').classList.remove('hidden'); document.getElementById('b2b-file-name').textContent = this.files[0].name;">

                                <button type="button" onclick="document.getElementById('b2b-attachment').click()" class="text-gray-300 hover:text-primary transition-all shrink-0" aria-label="Attach file">
                                    <i class="fa-solid fa-paperclip text-lg sm:text-xl"></i>
                                </button>

                                <div id="b2b-attachment-preview" class="hidden flex items-center gap-2 bg-primary/10 px-3 py-1 rounded-full border border-primary/20">
                                    <span id="b2b-file-name" class="text-[9px] text-primary font-bold truncate max-w-[120px]"></span>
                                    <button type="button" onclick="document.getElementById('b2b-attachment').value=''; document.getElementById('b2b-attachment-preview').classList.add('hidden');" class="text-primary hover:text-red-500" aria-label="Remove attachment">
                                        <i class="fa-solid fa-xmark text-[10px]"></i>
                                    </button>
                                </div>

                                <div class="flex-1 relative min-w-0">
                                    <input type="text" name="message" required placeholder="Tulis pesan untuk desainer..." class="w-full bg-gray-50 rounded-xl py-3.5 sm:py-4 pl-4 sm:pl-6 pr-14 text-[11px] font-bold outline-none border-2 border-transparent focus:border-primary/10 focus:bg-white transition-all" autocomplete="off">
                                    <button type="submit" class="absolute right-2 top-1/2 -translate-y-1/2 bg-primary text-white w-10 h-10 rounded-xl flex items-center justify-center shadow-xl shadow-primary/20 hover:scale-105 transition-all" aria-label="Send message">
                                        <i class="fa-solid fa-paper-plane text-sm"></i>
                                    </button>
                                </div>
                            </form>
                        </div>
                    @else
                        <div class="flex-1 flex flex-col items-center justify-center text-center p-10 sm:p-20">
                            <div class="w-20 h-20 bg-gray-50 rounded-xl flex items-center justify-center text-gray-200 mb-6">
                                <i class="fa-solid fa-industry text-3xl"></i>
                            </div>
                            <h3 class="text-lg font-bold text-gray-800">Pilih Proyek B2B</h3>
                            <p class="text-[10px] text-gray-400 mt-2 font-bold uppercase tracking-[0.2em] max-w-xs leading-loose">Pilih proyek pengadaan di daftar kiri untuk melihat obrolan dengan desainer.</p>
                        </div>
                    @endif
                @endif
            </div>
        </div>
    </main>

    <script>
        const msgContainer = document.getElementById('message-container');
        if (msgContainer) {
            msgContainer.scrollTop = msgContainer.scrollHeight;
        }
    </script>
</body>
</html>
