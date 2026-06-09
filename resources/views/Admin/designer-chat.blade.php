<?php
$admin_name = Auth::user()->full_name;
$admin_role = "ADMIN";

$customerSupportCount = \App\Models\Support::whereHas('user', fn($q) => $q->where('role', 'customer'))->where('status', 'pending')->count();
$sellerSupportCount = \App\Models\Support::whereHas('user', fn($q) => $q->where('role', 'seller'))->where('status', 'pending')->count();
$designerSupportCount = \App\Models\Support::whereHas('user', fn($q) => $q->where('role', 'designer'))->where('status', 'pending')->count();

$menu_items = [
    ["label" => "Dashboard",              "path" => route('admin.dashboard'),              "icon" => "grid"],
    ["label" => "User Management",        "path" => route('admin.user-management'),        "icon" => "users"],
    ["label" => "Account Validation",     "path" => route('admin.account.validation'),     "icon" => "shield"],
    ["label" => "Seller Monitor",         "path" => route('admin.seller-monitor'),         "icon" => "shopping-bag"],
    ["label" => "Designer Monitor",       "path" => route('admin.designer-monitor'),       "icon" => "pen-tool"],
    ["label" => "Seller Support",         "path" => route('admin.seller-support'),         "icon" => "headphones", "badge" => $sellerSupportCount],
    ["label" => "Designer Support",       "path" => route('admin.designer-support'),       "icon" => "pen-tool", "badge" => $designerSupportCount],
    ["label" => "Customer Support",       "path" => route('admin.customer-support'),       "icon" => "message-circle", "badge" => $customerSupportCount],
    ["label" => "Product Validation",     "path" => route('admin.product.validation'),     "icon" => "check-circle"],
    ["label" => "Portofolio Validation",  "path" => route('admin.portfolio-validation'),  "icon" => "image"],
];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Live Chat Designer — DECOR Admin</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=DM+Mono:wght@400;500&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/feather-icons"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800;900&display=swap');
        body { font-family: 'Plus Jakarta Sans', sans-serif; overflow: hidden;}
        .bg-primary { background-color: #B5733A; }
        .text-primary { color: #B5733A; }
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
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body class="text-gray-800 bg-[#F8F6F4]">

@include("Admin.partials.sidebar")

<main id="main-content" class="flex-1 flex flex-col ml-64 sidebar-transition h-screen bg-[#F8F6F4] relative">
    @include("Admin.partials.header", ["title" => "Live Chat Designer"])
    
    <div class="flex-1 flex overflow-hidden">
        <!-- CONVERSATION LIST -->
        <div class="w-80 bg-white border-r border-gray-100 flex flex-col">
            <div class="p-6 border-b border-gray-50 flex items-center justify-between">
                <h3 class="text-lg font-black tracking-tight text-gray-800">Chats</h3>
                <a href="{{ route('admin.designer-support') }}" class="text-[10px] font-bold text-gray-400 uppercase tracking-widest hover:text-primary transition-colors flex items-center">
                    <i data-feather="corner-up-left" class="w-3 h-3 mr-1"></i> Back
                </a>
            </div>
            <div class="p-4 border-b border-gray-50 bg-gray-50/50">
                <form action="{{ route('admin.designer-chat') }}" method="GET" class="relative">
                    <i class="fa-solid fa-magnifying-glass absolute left-3 top-1/2 -translate-y-1/2 text-gray-300 text-[10px]"></i>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Search designers..." class="w-full bg-white rounded-xl py-2 pl-9 pr-4 text-[10px] font-bold outline-none border border-gray-100 focus:border-primary/50 shadow-sm transition-all">
                </form>
            </div>
            
            <div class="flex-1 overflow-y-auto">
                @forelse($conversations as $conv)
                    <a href="{{ route('admin.designer-chat', $conv->id) }}" class="flex items-center p-6 border-b border-gray-50 hover:bg-gray-50 transition-all {{ $activeChat && $activeChat->id == $conv->id ? 'bg-orange-50' : '' }}">
                        <div class="w-12 h-12 rounded-2xl bg-gray-100 flex items-center justify-center font-black text-gray-400 text-sm overflow-hidden shrink-0">
                            @if($conv->avatar_url)
                                <img src="{{ $conv->avatar_url }}" class="w-full h-full object-cover">
                            @else
                                {{ substr($conv->full_name, 0, 1) }}
                            @endif
                        </div>
                        <div class="ml-4 flex-1 overflow-hidden">
                            <div class="flex justify-between items-start">
                                <h4 class="text-xs font-black text-gray-800 truncate {{ $activeChat && $activeChat->id == $conv->id ? 'text-primary' : '' }}">{{ $conv->full_name }}</h4>
                                <span class="text-[8px] font-bold text-green-500 uppercase tracking-widest">Live</span>
                            </div>
                            <p class="text-[10px] text-gray-400 mt-1 truncate font-medium">Click to view messages</p>
                        </div>
                    </a>
                @empty
                    <div class="p-10 text-center">
                        <i data-feather="inbox" class="w-10 h-10 mx-auto text-gray-200 mb-4"></i>
                        <p class="text-[10px] font-black text-gray-300 uppercase tracking-widest">No active chats</p>
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
                        <div class="w-10 h-10 rounded-xl bg-primary/10 flex items-center justify-center font-black text-primary text-xs overflow-hidden">
                            @if($activeChat->avatar_url)
                                <img src="{{ $activeChat->avatar_url }}" class="w-full h-full object-cover">
                            @else
                                {{ substr($activeChat->full_name, 0, 1) }}
                            @endif
                        </div>
                        <div>
                            <h4 class="text-xs font-black text-gray-800 uppercase tracking-tight">{{ $activeChat->full_name }}</h4>
                            <p class="text-[9px] text-green-500 font-bold uppercase tracking-widest">Designer</p>
                        </div>
                    </div>
                </div>

                <!-- MESSAGES AREA -->
                <div class="flex-1 overflow-y-auto p-10 space-y-8" id="message-container">
                    @forelse($messages as $msg)
                        <div class="flex {{ $msg->sender_id == Auth::id() ? 'justify-end' : 'justify-start' }}">
                            <div class="max-w-[70%] space-y-2">
                                <div class="p-5 text-[11px] font-semibold leading-relaxed shadow-sm {{ $msg->sender_id == Auth::id() ? 'bg-primary text-white chat-bubble-me' : 'bg-white text-gray-600 chat-bubble-them border border-gray-100' }}">
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
                                                <a href="{{ asset('storage/' . $msg->attachment) }}" target="_blank" class="flex items-center gap-2 p-3 {{ $msg->sender_id == Auth::id() ? 'bg-white/10 text-white' : 'bg-gray-50 text-gray-800' }} rounded-xl border border-white/20 hover:scale-[1.02] transition-all">
                                                    <i class="fa-solid fa-file-lines text-lg"></i>
                                                    <span class="truncate font-bold tracking-tight">{{ basename($msg->attachment) }}</span>
                                                </a>
                                            @endif
                                        </div>
                                    @endif
                                    
                                    @if($msg->message)
                                        {{ $msg->message }}
                                    @endif
                                </div>
                                <p class="text-[9px] font-bold text-gray-400 tracking-widest {{ $msg->sender_id == Auth::id() ? 'text-right' : 'text-left' }}">
                                    {{ $msg->created_at->format('H:i') }}
                                </p>
                            </div>
                        </div>
                    @empty
                        <div class="text-center text-gray-400 text-xs italic mt-10">Belum ada percakapan. Silakan membalas pesan.</div>
                    @endforelse
                </div>

                <!-- INPUT AREA -->
                <div class="p-8 bg-white border-t border-gray-100 shrink-0">
                    @if(session('error'))
                        <div class="bg-red-100 text-red-600 text-xs font-bold px-4 py-2 rounded-lg mb-4">
                            {{ session('error') }}
                        </div>
                    @endif
                    <form action="{{ route('admin.designer-chat.send') }}" method="POST" enctype="multipart/form-data" class="flex items-center space-x-6">
                        @csrf
                        <input type="hidden" name="receiver_id" value="{{ $activeChat->id }}">
                        
                        <input type="file" name="attachment" id="chat-attachment" class="hidden" onchange="document.getElementById('attachment-preview').classList.remove('hidden'); document.getElementById('file-name').textContent = this.files[0].name;">
                        
                        <button type="button" onclick="document.getElementById('chat-attachment').click()" class="text-gray-300 hover:text-primary transition-all">
                            <i class="fa-solid fa-paperclip text-xl"></i>
                        </button>

                        <div id="attachment-preview" class="hidden flex items-center gap-2 bg-primary/10 px-3 py-2 rounded-xl border border-primary/20">
                            <i class="fa-solid fa-file text-primary text-[10px]"></i>
                            <span id="file-name" class="text-[10px] text-primary font-bold truncate max-w-[150px]"></span>
                            <button type="button" onclick="document.getElementById('chat-attachment').value=''; document.getElementById('attachment-preview').classList.add('hidden');" class="text-primary hover:text-red-500 ml-1">
                                <i class="fa-solid fa-xmark text-[11px]"></i>
                            </button>
                        </div>

                        <div class="flex-1 relative">
                            <input type="text" name="message" placeholder="Type your reply here..." class="w-full bg-gray-50 rounded-2xl py-4 px-6 text-[11px] font-bold outline-none border border-transparent focus:border-primary/20 focus:bg-white transition-all" autocomplete="off">
                            <button type="submit" class="absolute right-2 top-1/2 -translate-y-1/2 bg-primary text-white w-10 h-10 rounded-xl flex items-center justify-center shadow-lg shadow-primary/20 hover:scale-105 transition-transform">
                                <i class="fa-solid fa-paper-plane text-sm"></i>
                            </button>
                        </div>
                    </form>
                </div>
            @else
                <div class="flex-1 flex flex-col items-center justify-center text-center p-20 bg-[#FCFBFB]">
                    <div class="w-24 h-24 bg-white shadow-sm border border-gray-50 rounded-[40px] flex items-center justify-center text-primary mb-6">
                        <i data-feather="message-circle" class="w-8 h-8"></i>
                    </div>
                    <h3 class="text-xl font-black text-gray-800 italic">Select a Designer</h3>
                    <p class="text-[10px] text-gray-400 mt-3 font-bold uppercase tracking-[0.2em] max-w-sm leading-loose">Choose a designer from the list to view their support messages and reply in real-time.</p>
                </div>
            @endif
        </div>
    </div>
</main>

<script>
    feather.replace({ 'stroke-width': 2 });

    const btn = document.getElementById("toggle-sidebar");
    const sidebar = document.getElementById("sidebar");
    const main = document.getElementById("main-content");
    if(btn) {
        btn.addEventListener("click", () => { 
            sidebar.classList.toggle("sidebar-hidden"); 
            main.classList.toggle("main-expanded"); 
        });
    }

    const msgContainer = document.getElementById('message-container');
    if(msgContainer) {
        msgContainer.scrollTop = msgContainer.scrollHeight;
    }
</script>
</body>
</html>
