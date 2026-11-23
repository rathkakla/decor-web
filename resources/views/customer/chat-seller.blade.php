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

    @include('customer.partials.navbar')

    <main class="flex-grow flex content-container w-full bg-white">
        @include('customer.partials.sidebar')

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
                            <div class="max-w-[70%] space-y-2">
                                @if($msg->product)
                                    @php
                                        $productImage = $msg->product->images->first()->img_url ?? null;
                                    @endphp
                                    <a href="{{ route('customer.product-detail', $msg->product_id) }}" class="block bg-white p-3 rounded-2xl border border-gray-100 shadow-sm hover:border-primary/40 transition-colors">
                                        <div class="flex items-center gap-3">
                                            @if($productImage)
                                                <img src="{{ $productImage }}" alt="{{ $msg->product->name }}" class="w-12 h-12 rounded-xl object-cover shrink-0">
                                            @else
                                                <div class="w-12 h-12 rounded-xl bg-gray-100 flex items-center justify-center text-gray-300 shrink-0">
                                                    <i class="fa-solid fa-image"></i>
                                                </div>
                                            @endif
                                            <div class="min-w-0">
                                                <p class="text-[10px] font-black text-gray-800 uppercase truncate">{{ $msg->product->name }}</p>
                                                <p class="text-[10px] font-bold text-primary">Rp {{ number_format($msg->product->price, 0, ',', '.') }}</p>
                                                <p class="text-[9px] text-gray-400 font-semibold">Lihat produk <i class="fa-solid fa-arrow-right text-[8px] ml-1"></i></p>
                                            </div>
                                        </div>
                                    </a>
                                @endif
                                <div class="{{ $msg->sender_id == Auth::id() ? 'bg-primary text-white rounded-tr-none' : 'bg-gray-50 border border-gray-100 text-gray-700 rounded-tl-none' }} px-6 py-4 rounded-[2rem] text-[13px] shadow-sm">
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
                        </div>
                    @empty
                        <div class="flex flex-col items-center justify-center h-full opacity-30">
                            <i class="fa-solid fa-comments text-4xl mb-4"></i>
                            <p class="text-xs font-bold uppercase tracking-widest">No messages yet</p>
                        </div>
                    @endforelse
                </div>

                <div class="p-6 bg-white border-t border-gray-50">
                    @if($activeProduct)
                        @php
                            $activeProductImage = $activeProduct->images->first()->img_url ?? null;
                        @endphp
                        <div class="mb-3 flex items-center gap-3 bg-primary/5 border border-primary/20 rounded-2xl p-3">
                            @if($activeProductImage)
                                <img src="{{ $activeProductImage }}" alt="{{ $activeProduct->name }}" class="w-11 h-11 rounded-xl object-cover shrink-0">
                            @else
                                <div class="w-11 h-11 rounded-xl bg-white flex items-center justify-center text-gray-300 shrink-0">
                                    <i class="fa-solid fa-image"></i>
                                </div>
                            @endif
                            <div class="min-w-0 flex-grow">
                                <p class="text-[9px] font-bold uppercase tracking-widest text-gray-400">Menanyakan produk</p>
                                <p class="text-[11px] font-black text-gray-800 truncate">{{ $activeProduct->name }}</p>
                                <p class="text-[10px] font-bold text-primary">Rp {{ number_format($activeProduct->price, 0, ',', '.') }}</p>
                            </div>
                            <a href="{{ route('customer.product-detail', $activeProduct->id) }}" class="shrink-0 text-[9px] font-black uppercase tracking-widest text-primary hover:underline">Lihat</a>
                        </div>
                    @endif
                    <form action="{{ route('customer.chat-seller.send') }}" method="POST" enctype="multipart/form-data" class="bg-gray-50 rounded-full flex items-center px-4 py-1 focus-within:bg-white focus-within:border-gray-100 transition-all border border-transparent">
                        @csrf
                        <input type="hidden" name="receiver_id" value="{{ $activeSeller->user_id }}">
                        @if($activeProduct)
                            <input type="hidden" name="product_id" value="{{ $activeProduct->id }}">
                        @endif
                        
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

    @include('customer.partials.footer', ['footerClass' => 'mt-auto'])
</body>
</html>