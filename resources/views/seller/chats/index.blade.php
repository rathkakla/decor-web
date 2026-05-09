<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Decor Seller - Customer Chat</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap');
        body { background-color: #F8F6F4; font-family: 'Plus Jakarta Sans', sans-serif; overflow: hidden; }
        .bg-primary { background-color: #B5733A; }
        .text-primary { color: #B5733A; }
        .active-link { background-color: rgba(181, 115, 58, 0.1); color: #B5733A; border-right: 4px solid #B5733A; }
        .sidebar-transition { transition: transform 0.3s ease-in-out, margin 0.3s ease-in-out; }
        .sidebar-hidden { transform: translateX(-100%); }
        .main-expanded { margin-left: 0 !important; }
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

    <main id="main-content" class="flex-1 flex flex-col ml-64 sidebar-transition h-screen relative">
        
        <header class="h-16 bg-primary flex items-center justify-between px-8 sticky top-0 z-40 shadow-sm shrink-0">
            <div class="flex items-center">
                <button id="toggle-sidebar" class="text-white hover:opacity-80 mr-4 transition-transform active:scale-95">
                    <i class="fa-solid fa-bars-staggered text-xl"></i>
                </button>
                <h2 class="font-bold text-xs uppercase tracking-widest text-white leading-none">Customer Messages</h2>
            </div>
            <div class="flex items-center space-x-6 text-white font-bold text-[10px] tracking-widest uppercase">
                <span>{{ Auth::user()->full_name }}</span>
                <img src="{{ Auth::user()->avatar_url }}" class="w-8 h-8 rounded-lg border border-white/20">
            </div>
        </header>

        <div class="flex-1 flex overflow-hidden">
            <!-- CONVERSATION LIST -->
            <div class="w-80 bg-white border-r border-gray-100 flex flex-col">
                <div class="p-6 border-b border-gray-50">
                    <h3 class="text-lg font-black tracking-tight text-gray-800">Conversations</h3>
                    <div class="relative mt-4">
                        <i class="fa-solid fa-magnifying-glass absolute left-3 top-1/2 -translate-y-1/2 text-gray-300 text-[10px]"></i>
                        <input type="text" placeholder="Search chats..." class="w-full bg-gray-50 rounded-xl py-2 pl-9 pr-4 text-[10px] font-bold outline-none border border-transparent focus:border-primary/20">
                    </div>
                </div>
                <div class="flex-1 overflow-y-auto">
                    @forelse($conversations as $conv)
                        <a href="{{ route('seller.chats', $conv->id) }}" class="flex items-center p-6 border-b border-gray-50 hover:bg-gray-50 transition-all {{ $activeChat && $activeChat->id == $conv->id ? 'bg-amber-50/30' : '' }}">
                            <div class="w-12 h-12 rounded-2xl bg-gray-100 flex items-center justify-center font-black text-gray-400 text-sm overflow-hidden shrink-0">
                                @if($conv->avatar_url)
                                    <img src="{{ $conv->avatar_url }}" class="w-full h-full object-cover">
                                @else
                                    {{ substr($conv->full_name, 0, 1) }}
                                @endif
                            </div>
                            <div class="ml-4 flex-1 overflow-hidden">
                                <div class="flex justify-between items-start">
                                    <h4 class="text-xs font-black text-gray-800 truncate">{{ $conv->full_name }}</h4>
                                    <span class="text-[8px] font-bold text-gray-300 uppercase">Live</span>
                                </div>
                                <p class="text-[10px] text-gray-400 mt-1 truncate font-medium">Click to view messages</p>
                            </div>
                        </a>
                    @empty
                        <div class="p-10 text-center">
                            <p class="text-[10px] font-black text-gray-300 uppercase tracking-widest">No chats yet</p>
                        </div>
                    @endforelse
                </div>
            </div>

            <!-- CHAT WINDOW -->
            <div class="flex-1 flex flex-col bg-[#FCFBFB]">
                @if($activeChat)
                    <!-- CHAT HEADER -->
                    <div class="px-8 py-5 border-b border-gray-50 bg-white flex justify-between items-center shrink-0">
                        <div class="flex items-center space-x-4">
                            <div class="w-10 h-10 rounded-xl bg-primary/10 flex items-center justify-center font-black text-primary text-xs">
                                {{ substr($activeChat->full_name, 0, 1) }}
                            </div>
                            <div>
                                <h4 class="text-xs font-black text-gray-800 uppercase tracking-tight">{{ $activeChat->full_name }}</h4>
                                <p class="text-[9px] text-green-500 font-bold uppercase tracking-widest">Online</p>
                            </div>
                        </div>
                    </div>

                    <!-- MESSAGES AREA -->
                    <div class="flex-1 overflow-y-auto p-10 space-y-8" id="message-container">
                        @foreach($messages as $msg)
                            <div class="flex {{ $msg->sender_id == Auth::id() ? 'justify-end' : 'justify-start' }}">
                                <div class="max-w-[70%] space-y-2">
                                    @if($msg->product)
                                        <div class="bg-white p-3 rounded-2xl border border-gray-100 shadow-sm mb-2 flex items-center space-x-3">
                                            <img src="{{ $msg->product->images->first() ? asset('storage/' . $msg->product->images->first()->image_path) : 'https://placehold.co/50' }}" class="w-10 h-10 rounded-lg object-cover">
                                            <div>
                                                <p class="text-[9px] font-black text-gray-800 uppercase truncate w-32">{{ $msg->product->name }}</p>
                                                <p class="text-[8px] font-bold text-primary">Rp {{ number_format($msg->product->price, 0, ',', '.') }}</p>
                                            </div>
                                        </div>
                                    @endif
                                    <div class="p-4 text-[11px] font-semibold leading-relaxed shadow-sm {{ $msg->sender_id == Auth::id() ? 'bg-primary text-white chat-bubble-me' : 'bg-white text-gray-600 chat-bubble-them border border-gray-50' }}">
                                        {{ $msg->message }}
                                    </div>
                                    <p class="text-[8px] font-bold text-gray-300 uppercase {{ $msg->sender_id == Auth::id() ? 'text-right' : 'text-left' }}">
                                        {{ $msg->created_at->format('H:i') }}
                                    </p>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <!-- INPUT AREA -->
                    <div class="p-8 bg-white border-t border-gray-100 shrink-0">
                        <form action="{{ route('seller.chats.send') }}" method="POST" class="flex items-center space-x-6">
                            @csrf
                            <input type="hidden" name="receiver_id" value="{{ $activeChat->id }}">
                            <button type="button" class="text-gray-300 hover:text-primary transition-all"><i class="fa-solid fa-paperclip text-xl"></i></button>
                            <div class="flex-1 relative">
                                <input type="text" name="message" required placeholder="Type your message here..." class="w-full bg-gray-50 rounded-2xl py-4 px-6 text-[11px] font-bold outline-none border-2 border-transparent focus:border-primary/10 focus:bg-white transition-all">
                                <button type="submit" class="absolute right-2 top-1/2 -translate-y-1/2 bg-primary text-white w-10 h-10 rounded-xl flex items-center justify-center shadow-xl shadow-primary/20 hover:scale-105 transition-all">
                                    <i class="fa-solid fa-paper-plane text-sm"></i>
                                </button>
                            </div>
                        </form>
                    </div>
                @else
                    <div class="flex-1 flex flex-col items-center justify-center text-center p-20">
                        <div class="w-20 h-20 bg-gray-50 rounded-[32px] flex items-center justify-center text-gray-200 mb-6">
                            <i class="fa-solid fa-message text-3xl"></i>
                        </div>
                        <h3 class="text-lg font-black text-gray-800">Select a Conversation</h3>
                        <p class="text-[10px] text-gray-400 mt-2 font-bold uppercase tracking-[0.2em] max-w-xs leading-loose">Choose a customer from the left list to start replying to their questions.</p>
                    </div>
                @endif
            </div>
        </div>
    </main>

    <script>
        const btn = document.getElementById('toggle-sidebar');
        const sidebar = document.getElementById('sidebar');
        const main = document.getElementById('main-content');
        const msgContainer = document.getElementById('message-container');

        if(msgContainer) {
            msgContainer.scrollTop = msgContainer.scrollHeight;
        }

        btn.addEventListener('click', () => { 
            sidebar.classList.toggle('sidebar-hidden'); 
            main.classList.toggle('main-expanded'); 
        });

        // Simple Polling (Refresh every 10 seconds if in active chat)
        @if($activeChat)
            setTimeout(() => {
                // location.reload(); // Uncomment if you want auto-refresh
            }, 10000);
        @endif
    </script>
</body>
</html>