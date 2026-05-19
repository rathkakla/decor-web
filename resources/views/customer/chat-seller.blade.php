@php 
    $site_name = "DECOR"; 
    $avatar_url = Auth::user()->avatar_url;
@endphp
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Seller Messages — {{ $site_name }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: { primary: '#B5733A', secondary: '#E3DCD6' }
                }
            }
        }
    </script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;600;700;800&display=swap');
        body { font-family: 'Plus Jakarta Sans', sans-serif; background-color: #ffffff; }
        .content-container { max-width: 1200px; margin: 0 auto; }
        .chat-scroll::-webkit-scrollbar { width: 4px; }
        .chat-scroll::-webkit-scrollbar-thumb { background: #f1f1f1; border-radius: 10px; }
    </style>
</head>
<body class="text-gray-800 flex flex-col min-h-screen">

    <header class="bg-white border-b border-gray-100 sticky top-0 z-50">
        <div class="content-container flex justify-between items-center py-4 px-6 mx-auto max-w-[1200px]">
            <div class="flex items-center space-x-8 flex-1">
                <a href="{{ route('homepage') }}" class="text-2xl font-black tracking-tighter uppercase text-primary hover:opacity-80 transition-all">
                    {{ $site_name }}
                </a>
            </div>

            <nav class="hidden md:flex items-center space-x-10 text-[13px] font-medium text-gray-500 tracking-wide">
                <a href="{{ route('customer.catalog') }}" class="hover:text-primary transition-all">Collections</a>
                <a href="{{ route('customer.designers') }}" class="hover:text-primary transition-all">Designers</a>
                <a href="{{ route('customer.design-lab') }}" class="hover:text-primary transition-all">AI Studio</a>
            </nav>

            <div class="flex items-center space-x-6 flex-1 justify-end">
                @auth
                    <div class="flex items-center gap-4 border-r pr-6 border-gray-100">
                        <div class="text-right hidden sm:block">
                            <p class="text-[9px] uppercase tracking-widest text-gray-400 font-bold leading-none mb-1">Welcome back</p>
                            <p class="text-xs font-bold text-primary capitalize">{{ Auth::user()->full_name }}</p>
                        </div>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="text-gray-400 hover:text-red-500 transition-colors" title="Logout">
                                <i class="fa-solid fa-power-off text-sm"></i>
                            </button>
                        </form>
                    </div>
                    <a href="{{ route('customer.cart') }}" class="text-primary hover:scale-110 transition-transform">
                        <i class="fa-solid fa-bag-shopping text-lg"></i>
                    </a>
                    <div class="w-9 h-9 rounded-md overflow-hidden border border-gray-200 cursor-pointer hover:border-primary transition-all">
                        <a href="{{ route('customer.profile') }}" class="block w-full h-full">
                            <img src="{{ $avatar_url }}" class="w-full h-full bg-slate-100 object-cover">
                        </a>
                    </div>
                @endauth
            </div>
        </div>
    </header>

    <main class="flex-grow flex content-container w-full bg-white">
        <aside class="w-72 border-r border-gray-50 p-10 bg-gray-50/20 shrink-0">
            <div class="text-center mb-10">
                <img src="{{ $avatar_url }}" 
                     class="w-20 h-20 rounded-2xl mx-auto mb-4 bg-white shadow-sm border border-gray-100 object-cover">
                <h3 class="font-bold text-lg">{{ $user->full_name }}</h3>
                <p class="text-[9px] text-gray-400 uppercase tracking-widest mt-1">Member since {{ $user->created_at->format('Y') }}</p>
            </div>

            <nav class="space-y-1">
                <a href="{{ route('customer.profile') }}" class="flex items-center space-x-4 px-4 py-3 text-gray-400 hover:text-primary transition-colors">
                    <i class="fa-regular fa-user text-xs"></i> <span class="text-[11px] uppercase tracking-widest">Profile</span>
                </a>
                <a href="{{ route('customer.orders') }}" class="flex items-center space-x-4 px-4 py-3 text-gray-400 hover:text-primary transition-colors">
                    <i class="fa-solid fa-box-archive text-xs"></i> <span class="text-[11px] uppercase tracking-widest">Orders</span>
                </a>
                <a href="{{ route('customer.my-consultations') }}" class="flex items-center space-x-4 px-4 py-3 text-gray-400 hover:text-primary transition-colors">
                    <i class="fa-solid fa-wand-magic-sparkles text-xs"></i> <span class="text-[11px] uppercase tracking-widest">Consultations</span>
                </a>
                <a href="{{ route('customer.return-request') }}" class="flex items-center space-x-4 px-4 py-3 text-gray-400 hover:text-primary transition-colors">
                    <i class="fa-solid fa-rotate-left text-xs"></i> <span class="text-[11px] uppercase tracking-widest">Returns</span>
                </a>
                <a href="{{ route('customer.product-favorite') }}" class="flex items-center space-x-4 px-4 py-3 text-gray-400 hover:text-primary transition-colors">
                    <i class="fa-regular fa-heart text-xs"></i> <span class="text-[11px] uppercase tracking-widest">Product Favorite</span>
                </a>
                <div class="pt-6 mt-6 border-t border-gray-100">
                    <p class="px-4 text-[9px] font-black text-gray-300 uppercase tracking-widest mb-2">Chat History</p>

                    <a href="{{ route('customer.chat-seller.with') }}" class="flex items-center space-x-4 px-4 py-3 rounded-xl bg-white text-primary font-bold shadow-sm border border-gray-100">
                        <i class="fa-solid fa-shop text-xs"></i> 
                        <span class="text-[11px] uppercase tracking-widest">Seller</span>
                    </a>
                </div>
            </nav>
        </aside>

        <div class="flex-grow flex overflow-hidden" style="height: calc(100vh - 80px);">
            @if($conversations->isEmpty())
            <div class="flex-grow flex flex-col items-center justify-center bg-gray-50/30">
                <div class="w-20 h-20 bg-gray-100 rounded-full flex items-center justify-center text-gray-300 mb-6">
                    <i class="fa-solid fa-comments text-2xl"></i>
                </div>
                <h3 class="text-sm font-black uppercase tracking-[0.2em] text-gray-400">No Seller Messages</h3>
                <p class="text-[11px] text-gray-400 mt-2 italic">Connect with sellers to discuss products!</p>
                <a href="{{ route('customer.catalog') }}" class="mt-8 bg-primary text-white px-8 py-3 rounded-xl text-[10px] font-black uppercase tracking-widest shadow-xl shadow-primary/20">Explore Products</a>
            </div>
            @else
            <aside class="w-80 border-r border-gray-100 flex flex-col bg-white">
                <div class="p-6">
                    <h2 class="text-lg font-bold tracking-tighter mb-4">Seller Messages</h2>
                    <div class="relative group">
                        <input type="text" placeholder="Search sellers..." class="w-full bg-gray-50 border-none rounded-lg py-2 px-4 text-[10px] outline-none focus:ring-1 focus:ring-primary/20 transition-all">
                    </div>
                </div>
                <div class="flex-grow overflow-y-auto chat-scroll p-2">
                    @foreach($conversations as $s)
                    <a href="{{ route('customer.chat-seller.with', $s->id) }}" class="p-4 flex items-center gap-3 {{ $activeSeller && $activeSeller->id == $s->id ? 'bg-gray-50/80 border-r-2 border-primary' : 'hover:bg-gray-50' }} cursor-pointer rounded-xl mb-1">
                        <div class="w-10 h-10 rounded-full overflow-hidden shrink-0">
                            <img src="{{ $s->store_image_url }}" class="w-full h-full object-cover">
                        </div>
                        <div class="flex-grow min-w-0">
                            <div class="flex justify-between items-center mb-1">
                                <h4 class="text-xs font-bold truncate">{{ $s->store_name }}</h4>
                                <span class="text-[9px] text-gray-400">Now</span>
                            </div>
                            <p class="text-[10px] text-gray-500 truncate font-medium">Click to chat...</p>
                        </div>
                    </a>
                    @endforeach
                </div>
            </aside>

            <section class="flex-grow flex flex-col bg-white">
                @if($activeSeller)
                <div class="px-8 py-4 border-b border-gray-50 flex justify-between items-center bg-white/80 backdrop-blur-sm">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 rounded-full overflow-hidden">
                            <img src="{{ $activeSeller->store_image_url }}" class="w-full h-full object-cover">
                        </div>
                        <div>
                            <h3 class="text-xs font-black uppercase tracking-wider">{{ $activeSeller->store_name }}</h3>
                            <p class="text-[9px] font-bold text-green-500 uppercase tracking-widest">Online Now</p>
                        </div>
                    </div>
                </div>

                <div class="flex-grow p-10 overflow-y-auto space-y-8 chat-scroll">
                    @forelse($messages as $msg)
                        <div class="flex {{ $msg->sender_id == Auth::id() ? 'justify-end' : 'justify-start' }}">
                            <div class="max-w-[70%] {{ $msg->sender_id == Auth::id() ? 'bg-primary text-white rounded-tr-none' : 'bg-gray-50 border border-gray-100 text-gray-700 rounded-tl-none' }} px-6 py-4 rounded-[2rem] text-[13px] shadow-sm">
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
                                            <a href="{{ asset('storage/' . $msg->attachment) }}" target="_blank" class="flex items-center gap-2 p-3 bg-white/10 rounded-xl border border-white/20 hover:bg-white/20 transition-all">
                                                <i class="fa-solid fa-file-lines"></i>
                                                <span class="truncate">{{ basename($msg->attachment) }}</span>
                                            </a>
                                        @endif
                                    </div>
                                @endif
                                
                                @if($msg->message)
                                    {{ $msg->message }}
                                @endif

                                <div class="text-[9px] mt-1 {{ $msg->sender_id == Auth::id() ? 'text-white/70' : 'text-gray-400' }}">
                                    {{ $msg->created_at->format('H:i') }}
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="flex flex-col items-center justify-center h-full opacity-30">
                            <i class="fa-solid fa-comments text-4xl mb-4"></i>
                            <p class="text-xs font-bold uppercase tracking-widest">No messages yet</p>
                        </div>
                    @endforelse
                </div>

                <div class="p-6 bg-white border-t border-gray-50">
                    <form action="{{ route('customer.chat-seller.send') }}" method="POST" enctype="multipart/form-data" class="bg-gray-50 rounded-full flex items-center px-4 py-1 focus-within:bg-white focus-within:border-gray-100 transition-all border border-transparent">
                        @csrf
                        <input type="hidden" name="receiver_id" value="{{ $activeSeller->user_id }}">
                        
                        <input type="file" name="attachment" id="chat-attachment" class="hidden" onchange="document.getElementById('attachment-preview').classList.remove('hidden'); document.getElementById('file-name').textContent = this.files[0].name;">
                        
                        <button type="button" onclick="document.getElementById('chat-attachment').click()" class="p-2 text-gray-400 hover:text-primary transition-colors">
                            <i class="fa-solid fa-paperclip"></i>
                        </button>

                        <div id="attachment-preview" class="hidden flex items-center gap-2 bg-primary/10 px-3 py-1 rounded-full ml-2">
                            <span id="file-name" class="text-[10px] text-primary font-bold truncate max-w-[100px]"></span>
                            <button type="button" onclick="document.getElementById('chat-attachment').value=''; document.getElementById('attachment-preview').classList.add('hidden');" class="text-primary hover:text-red-500">
                                <i class="fa-solid fa-xmark text-[10px]"></i>
                            </button>
                        </div>

                        <input type="text" name="message" placeholder="Tulis pesan..." class="flex-grow bg-transparent border-none outline-none px-4 py-3 text-xs" autocomplete="off">
                        <button type="submit" class="w-9 h-9 bg-primary text-white rounded-full flex items-center justify-center shadow-md hover:scale-105 transition-all">
                            <i class="fa-solid fa-paper-plane text-[10px]"></i>
                        </button>
                    </form>
                </div>
                @else
                <div class="flex-grow flex items-center justify-center bg-gray-50/10">
                    <p class="text-xs text-gray-300 font-bold uppercase tracking-widest italic">Select a seller to start chat</p>
                </div>
                @endif
            </section>
            @endif
        </div>
    </main>

    <footer class="bg-primary text-white py-12 px-6">
        <div class="max-w-6xl mx-auto"> 
            <div class="flex flex-col md:flex-row justify-between items-start border-b border-white/20 pb-10 gap-10">
                <div class="space-y-4 text-left">
                    <div class="flex items-center space-x-2 text-white">
                        <div class="w-8 h-8 bg-white/20 rounded flex items-center justify-center">
                            <i class="fa-solid fa-couch text-sm"></i>
                        </div>
                        <span class="text-xl font-bold tracking-widest uppercase">DECOR</span>
                    </div>
                    <div class="text-sm text-white/90 space-y-2">
                        <p class="flex items-center"><i class="fa-regular fa-envelope mr-3 text-xs"></i> decorofficial@gmail.com</p>
                        <p class="flex items-center"><i class="fa-brands fa-instagram mr-3 text-xs"></i> @decor_official</p>
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-x-16 gap-y-4 text-[10px] font-bold tracking-widest uppercase text-white/90">
                    <div class="flex flex-col space-y-3">
                        <a href="#" class="hover:text-white/70">Terms of Service</a>
                        <a href="#" class="hover:text-white/70">Privacy Policy</a>
                    </div>
                    <div class="flex flex-col space-y-3">
                         <a href="{{ route('customer.help-center') }}" class="hover:text-white/70 transition-colors">Help Center</a>
                        <a href="#" class="hover:text-white/70">FAQ</a>
                    </div>
                </div>
            </div>
            <div class="pt-8 text-[10px] text-white/60 font-medium tracking-widest text-center">
                <p>©️ 2026 DECOR MARKETPLACE. ALL RIGHTS RESERVED.</p>
            </div>
        </div>
    </footer>
</body>
</html>