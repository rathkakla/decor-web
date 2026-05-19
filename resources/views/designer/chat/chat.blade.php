<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Decor Designer - Chat Center</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&display=swap');
        body { background-color: #F8F6F4; font-family: 'Plus Jakarta Sans', sans-serif; overflow-x: hidden; }
        .bg-primary { background-color: #B5733A; }
        .text-primary { color: #B5733A; }
        .active-link { background-color: rgba(181, 115, 58, 0.1); color: #B5733A; border-right: 4px solid #B5733A; }
        .sidebar-transition { transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1); }
        .sidebar-closed { transform: translateX(-100%); }
        .main-full { margin-left: 0 !important; }
        ::-webkit-scrollbar { width: 4px; }
        ::-webkit-scrollbar-thumb { background: #E5E7EB; border-radius: 10px; }
        .chat-bubble-me { border-radius: 18px 2px 18px 18px; }
        .chat-bubble-them { border-radius: 2px 18px 18px 18px; }
    </style>
</head>
<body class="text-gray-800">

    <aside id="sidebar" class="w-64 bg-white border-r border-gray-200 flex flex-col fixed h-full z-50 sidebar-transition">
        <div class="p-8">
            <h1 class="text-2xl font-bold tracking-widest text-primary uppercase leading-none">DECOR</h1>
            <p class="text-[10px] text-gray-400 mt-1 uppercase tracking-widest italic font-bold">Designer Portal</p>
        </div>
        
        <nav class="flex-1 px-4 space-y-1">
            <a href="{{ route('designer.dashboard') }}" class="flex items-center px-4 py-3 text-xs font-bold text-gray-400 hover:text-primary transition-all rounded-lg"><i class="fa-solid fa-table-columns mr-3 w-5 text-center"></i> Dashboard</a>
            <a href="{{ route('designer.portfolio.index') }}" class="flex items-center px-4 py-3 text-xs font-bold text-gray-400 hover:text-primary transition-all rounded-lg"><i class="fa-solid fa-briefcase mr-3 w-5 text-center"></i> Kelola Portofolio</a>
            <a href="{{ route('designer.consultations.index') }}" class="flex items-center px-4 py-3 text-xs font-bold text-gray-400 hover:text-primary transition-all rounded-lg"><i class="fa-solid fa-calendar-check mr-3 w-5 text-center"></i>Konsultasi</a>
            <a href="{{ route('designer.chats') }}" class="flex items-center px-4 py-3 text-xs font-bold active-link transition-all rounded-lg"><i class="fa-solid fa-comment-dots mr-3 w-5 text-center"></i>Chat</a>
            <a href="{{ route('designer.reviews') }}" class="flex items-center px-4 py-3 text-xs font-bold text-gray-400 hover:text-primary transition-all rounded-lg"><i class="fa-solid fa-star mr-3 w-5 text-center"></i> Review & Rating</a>
            <a href="{{ route('designer.reports') }}" class="flex items-center px-4 py-3 text-xs font-bold text-gray-400 hover:text-primary transition-all rounded-lg"><i class="fa-solid fa-chart-line mr-3 w-5 text-center"></i> Laporan</a>
        </nav>
        <div class="p-4 border-t border-gray-100 space-y-1">
            <a href="{{ route('designer.settings') }}" class="flex items-center px-4 py-3 text-xs font-bold text-gray-400 hover:text-primary transition-all rounded-lg"><i class="fa-solid fa-gear mr-3 w-5 text-center"></i> Settings</a>
            <a href="{{ route('designer.support') }}" class="flex items-center px-4 py-3 text-xs font-bold text-gray-400 hover:text-primary transition-all rounded-lg"><i class="fa-solid fa-circle-question mr-3 w-5 text-center"></i> Support</a>
        </div>
    </aside>

    <main id="main-content" class="ml-64 flex flex-col h-screen sidebar-transition">
        <header class="h-16 bg-primary flex items-center justify-between px-8 sticky top-0 z-40 shadow-sm text-white flex-shrink-0">
            <div class="flex items-center">
                <i onclick="toggleSidebar()" class="fa-solid fa-bars-staggered text-xl mr-4 cursor-pointer hover:scale-110 transition-transform"></i>
                <h2 class="font-black text-[10px] uppercase tracking-widest leading-none">Message Center</h2>
            </div>
            <div class="flex items-center space-x-6">
                <i class="fa-regular fa-bell text-xl"></i>
                <div class="flex items-center space-x-3 bg-white/10 px-3 py-1.5 rounded-xl border border-white/20">
                    <span class="text-[10px] font-black uppercase tracking-widest">{{ Auth::user()->full_name }}</span>
                    <div class="w-8 h-8 rounded-lg bg-white text-primary flex items-center justify-center font-bold">{{ substr(Auth::user()->full_name, 0, 2) }}</div>
                </div>
            </div>
        </header>

        <div class="flex-1 flex overflow-hidden">
            <aside class="w-80 bg-white border-r border-gray-100 flex flex-col flex-shrink-0">
                <div class="p-6 border-b border-gray-50">
                    <input type="text" placeholder="Search conversations..." class="w-full bg-gray-50 border-none rounded-xl py-3 px-4 text-[10px] font-black uppercase tracking-widest outline-none focus:ring-1 focus:ring-primary/20">
                </div>
                <div class="flex-1 overflow-y-auto">
                    @forelse($conversations as $conv)
                    <a href="{{ route('designer.chats', $conv->id) }}" class="px-6 py-5 flex items-center space-x-4 cursor-pointer border-b border-gray-50 hover:bg-gray-50 {{ $activeChat && $activeChat->id == $conv->id ? 'bg-primary/5 border-r-4 border-primary' : '' }}">
                        <div class="w-12 h-12 rounded-2xl bg-gray-900 text-white flex items-center justify-center font-bold">
                            @if($conv->avatar_url)
                                <img src="{{ $conv->avatar_url }}" class="w-full h-full object-cover rounded-2xl">
                            @else
                                {{ substr($conv->full_name, 0, 2) }}
                            @endif
                        </div>
                        <div class="flex-1 min-w-0">
                            <h4 class="text-xs font-black text-gray-900 uppercase">{{ $conv->full_name }}</h4>
                            <p class="text-[10px] text-primary font-bold truncate">Click to view messages</p>
                        </div>
                    </a>
                    @empty
                        <div class="p-10 text-center">
                            <p class="text-[10px] font-black text-gray-300 uppercase tracking-widest">No chats yet</p>
                        </div>
                    @endforelse
                </div>
            </aside>

            <section class="flex-1 flex flex-col bg-[#FDFDFD]">
                @if($activeChat)
                <div class="px-8 py-4 bg-white border-b border-gray-50 flex justify-between items-center">
                    <div>
                        <h4 class="text-[11px] font-black text-gray-900 uppercase tracking-widest">{{ $activeChat->full_name }}</h4>
                        <p class="text-[8px] font-black text-green-500 uppercase tracking-widest italic">● Online Now</p>
                    </div>
                </div>

                <div class="flex-1 overflow-y-auto p-10 space-y-8" id="message-container">
                    @foreach($messages as $msg)
                        <div class="flex {{ $msg->sender_id == Auth::id() ? 'justify-end' : 'justify-start' }}">
                            <div class="max-w-[70%] space-y-2">
                                <div class="p-4 text-xs font-semibold leading-relaxed shadow-sm {{ $msg->sender_id == Auth::id() ? 'bg-primary text-white chat-bubble-me' : 'bg-white text-gray-600 chat-bubble-them border border-gray-50' }}">
                                    @if($msg->attachment)
                                        @php
                                            $ext = pathinfo($msg->attachment, PATHINFO_EXTENSION);
                                            $isImage = in_array(strtolower($ext), ['jpg', 'jpeg', 'png', 'gif']);
                                        @endphp
                                        <div class="mb-2">
                                            @if($isImage)
                                                <a href="{{ asset('storage/' . $msg->attachment) }}" target="_blank">
                                                    <img src="{{ asset('storage/' . $msg->attachment) }}" class="rounded-xl max-w-full h-auto border border-white/20 shadow-sm">
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
                    @endforeach
                </div>

                <div class="p-8 bg-white border-t border-gray-50">
                    <form action="{{ route('designer.chats.send') }}" method="POST" enctype="multipart/form-data" class="flex items-center space-x-4">
                        @csrf
                        <input type="hidden" name="receiver_id" value="{{ $activeChat->id }}">
                        
                        <input type="file" name="attachment" id="chat-attachment" class="hidden" onchange="document.getElementById('attachment-preview').classList.remove('hidden'); document.getElementById('file-name').textContent = this.files[0].name;">
                        
                        <button type="button" onclick="document.getElementById('chat-attachment').click()" class="text-gray-300 hover:text-primary transition-all">
                            <i class="fa-solid fa-paperclip text-xl"></i>
                        </button>

                        <div id="attachment-preview" class="hidden flex items-center gap-2 bg-primary/10 px-3 py-1 rounded-full border border-primary/20">
                            <span id="file-name" class="text-[9px] text-primary font-black truncate max-w-[120px]"></span>
                            <button type="button" onclick="document.getElementById('chat-attachment').value=''; document.getElementById('attachment-preview').classList.add('hidden');" class="text-primary hover:text-red-500">
                                <i class="fa-solid fa-xmark text-[10px]"></i>
                            </button>
                        </div>

                        <div class="flex-1 relative">
                            <input type="text" name="message" placeholder="Type message..." class="w-full bg-gray-50 rounded-2xl py-4 px-6 text-[11px] font-bold outline-none border-2 border-transparent focus:border-primary/10 focus:bg-white transition-all" autocomplete="off">
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
            </section>
        </div>
        <footer class="p-8 border-t border-gray-100 text-[9px] font-black text-gray-400 uppercase tracking-widest bg-white mt-auto text-center">
            © 2026 DECOR DESIGNER PORTAL. ALL RIGHTS RESERVED.
        </footer>
    </main>

    <script>
        function toggleSidebar() { 
            document.getElementById('sidebar').classList.toggle('sidebar-closed'); 
            document.getElementById('main-content').classList.toggle('main-full'); 
        }
        
        const msgContainer = document.getElementById('message-container');
        if(msgContainer) {
            msgContainer.scrollTop = msgContainer.scrollHeight;
        }
    </script>
</body>
</html>