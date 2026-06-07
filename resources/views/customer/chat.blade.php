@php 
    $site_name = "DECOR"; 
    $status = $activeConsultation ? $activeConsultation->status : 0; 
    $consultation_id = $activeConsultation ? "DEC-" . str_pad($activeConsultation->id, 5, '0', STR_PAD_LEFT) : ''; 
@endphp
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Workspace — {{ $site_name }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/@fancyapps/ui@5.0/dist/fancybox/fancybox.umd.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fancyapps/ui@5.0/dist/fancybox/fancybox.css" />
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            Fancybox.bind('[data-fancybox]', {});
        });
        tailwind.config = {
            theme: {
                extend: {
                    colors: { primary: '#B5733A', secondary: '#E3DCD6' }
                }
            }
        }
    </script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&display=swap');
        body { background-color: #F8F6F4; font-family: 'Plus Jakarta Sans', sans-serif; overflow-x: hidden; }
        .custom-scroll::-webkit-scrollbar { width: 4px; }
        .custom-scroll::-webkit-scrollbar-thumb { background: #E5E7EB; border-radius: 10px; }
    </style>
</head>
<body class="text-gray-800 flex flex-col h-screen overflow-hidden">

    @if(!$activeConsultation)
        <div class="flex-grow flex flex-col items-center justify-center bg-gray-50/30 min-h-screen">
            <div class="w-20 h-20 bg-gray-100 rounded-full flex items-center justify-center text-gray-300 mb-6">
                <i class="fa-solid fa-comment-slash text-2xl"></i>
            </div>
            <h3 class="text-sm font-black uppercase tracking-[0.2em] text-gray-400">Consultation Not Found</h3>
            <a href="{{ route('customer.my-consultations') }}" class="mt-8 bg-primary text-white px-8 py-3 rounded-xl text-[10px] font-black uppercase tracking-widest shadow-xl shadow-primary/20">Back to My Consultations</a>
        </div>
    @else

    <!-- HEADER: ADAPTIVE PROGRESS STEPS -->
    <header class="h-16 bg-white border-b border-gray-100 flex items-center justify-between px-8 sticky top-0 z-40 shadow-sm flex-shrink-0">
        <div class="flex items-center">
            <a href="{{ route('customer.my-consultations') }}" class="flex items-center space-x-3 group mr-8 border-r border-gray-100 pr-8 py-2 text-gray-500 hover:text-primary transition-colors">
                <i class="fa-solid fa-arrow-left text-lg group-hover:-translate-x-1 transition-transform"></i>
                <span class="text-[10px] font-black uppercase tracking-[0.2em]">Back</span>
            </a>
            <h2 class="font-bold text-[10px] uppercase tracking-widest leading-none text-gray-900">Workspace <span class="text-gray-400 ml-4 font-black">#{{ $consultation_id }}</span></h2>
        </div>
        
        <!-- TRACK BUTTON -->
        @if($activeConsultation->consultation_type != 'chat_consultation')
        <div class="flex items-center space-x-4">
            <a href="{{ route('customer.track-consultation.list') }}" class="bg-primary/10 hover:bg-primary/20 text-primary px-4 py-2 rounded-xl text-[10px] font-black uppercase tracking-widest transition-colors flex items-center space-x-2">
                <i class="fa-solid fa-list-check"></i>
                <span>Track Status</span>
            </a>
            <div class="h-8 w-px bg-gray-200 mx-2"></div>
            <div class="flex items-center space-x-3 bg-gray-50 px-3 py-1.5 rounded-xl border border-gray-100">
                <span class="text-[10px] font-black uppercase tracking-widest text-gray-600">{{ Auth::user()->full_name }}</span>
                <img src="{{ Auth::user()->avatar_url }}" class="w-8 h-8 rounded-lg object-cover">
            </div>
        </div>
        @else
        <div class="flex items-center space-x-4">
            @if($activeConsultation->consultation_type == 'chat_consultation' && $activeConsultation->chat_expires_at && !$activeConsultation->is_chat_expired && $status == 1)
                <div id="chat-countdown" class="flex items-center space-x-2 bg-red-50 text-red-600 px-3 py-1.5 rounded-xl border border-red-100 font-mono text-xs font-black shrink-0">
                    <i class="fa-solid fa-clock animate-pulse"></i>
                    <span id="countdown-timer">--:--</span>
                </div>
            @endif
            <div class="flex items-center space-x-3 bg-gray-50 px-3 py-1.5 rounded-xl border border-gray-100">
                <span class="text-[10px] font-black uppercase tracking-widest text-gray-600">{{ Auth::user()->full_name }}</span>
                <img src="{{ Auth::user()->avatar_url }}" class="w-8 h-8 rounded-lg object-cover">
            </div>
        </div>
        @endif
    </header>

    <div class="flex-1 flex overflow-hidden">
        <!-- LEFT PANEL: PROJECT INFO -->
        <aside class="w-80 bg-white border-r border-gray-100 p-8 space-y-10 overflow-y-auto flex-shrink-0 custom-scroll">
            <div class="space-y-4">
                <h3 class="text-[9px] font-black text-gray-300 uppercase tracking-[0.3em] leading-none italic">Designer Profile</h3>
                <div class="bg-gray-50 rounded-[32px] p-6 border border-gray-100 shadow-inner flex items-center gap-4">
                    <img src="{{ $activeConsultation->designer->designer_image ? asset('storage/'.$activeConsultation->designer->designer_image) : 'https://ui-avatars.com/api/?name='.urlencode($activeConsultation->designer->user->full_name) }}" class="w-12 h-12 rounded-2xl object-cover border border-gray-200">
                    <div>
                        <p class="text-[11px] font-black text-gray-900 uppercase leading-none mb-1">{{ $activeConsultation->designer->user->full_name }}</p>
                        <p class="text-[10px] font-black text-primary uppercase tracking-tighter">{{ $activeConsultation->designer->specialty ?? 'Interior Designer' }}</p>
                    </div>
                </div>
            </div>

            <div class="space-y-4">
                <h3 class="text-[9px] font-black text-gray-300 uppercase tracking-[0.3em] leading-none italic">Project Info</h3>
                <div class="space-y-3">
                    <div>
                        <p class="text-[8px] font-bold text-gray-400 uppercase tracking-widest">Type</p>
                        <p class="text-[11px] font-black text-gray-800">
                            {{ $activeConsultation->consultation_type == 'chat_consultation' ? 'Chat Consultation' : 'Request Proposal' }}
                        </p>
                    </div>
                    <div>
                        <p class="text-[8px] font-bold text-gray-400 uppercase tracking-widest">Budget</p>
                        <p class="text-[11px] font-black text-gray-800">{{ $activeConsultation->budget_range }}</p>
                    </div>
                    <div>
                        <p class="text-[8px] font-bold text-gray-400 uppercase tracking-widest">Status</p>
                        <p class="text-[11px] font-black {{ $activeConsultation->is_chat_expired ? 'text-red-500' : ($status == 4 ? 'text-green-500' : 'text-primary') }}">
                            @if($activeConsultation->is_chat_expired)
                                Expired
                            @else
                                {{ \App\Models\Consultation::getStatusLabel($status) }}
                            @endif
                        </p>
                    </div>
                </div>
            </div>

        </aside>

        <!-- CANVAS AREA: PROJECT WORKSPACE -->
        <section class="flex-1 bg-[#FDFCFB] flex flex-col overflow-hidden relative">

            <!-- Chat Header -->
            <header class="bg-white px-8 py-4 border-b border-gray-100 flex justify-between items-center z-10 shadow-sm relative">
                <div>
                    <h2 class="text-lg font-bold text-gray-800">{{ $activeConsultation->title }}</h2>
                    <p class="text-[10px] text-gray-400 font-bold uppercase tracking-widest mt-0.5">Workspace</p>
                </div>
                <button onclick="document.getElementById('media-drawer').classList.remove('translate-x-full');" class="text-[10px] font-black text-gray-600 bg-gray-50 hover:bg-primary/10 hover:text-primary px-5 py-2.5 rounded-xl border border-gray-200 hover:border-primary/20 uppercase tracking-widest flex items-center gap-2 transition-all">
                    <i class="fa-solid fa-folder-open"></i>
                    Media & Assets
                </button>
            </header>

            <!-- Chat Area -->
            <div class="flex-1 overflow-y-auto p-8 pb-28 custom-scroll bg-gray-50/30 {{ in_array($status, [0, 5, 6, 7]) ? 'blur-md opacity-20 pointer-events-none' : '' }}">
                <div class="max-w-4xl mx-auto space-y-8">
                    
                    @php
                        $rawItems = collect();
                        foreach($activeConsultation->messages as $m) {
                            $rawItems->push((object)['type' => 'message', 'data' => $m, 'time' => $m->created_at, 'sender_id' => $m->sender_id]);
                        }
                        foreach($activeConsultation->attachments as $a) {
                            $rawItems->push((object)['type' => 'attachment', 'data' => $a, 'time' => $a->created_at, 'sender_id' => $a->uploaded_by]);
                        }
                        $rawItems = $rawItems->sortBy('time')->values();

                        $chatItems = collect();
                        $currentCollage = null;

                        foreach ($rawItems as $item) {
                            if ($item->type == 'attachment' && $item->data->file_type == 'image') {
                                if ($currentCollage && 
                                    $currentCollage->sender_id == $item->sender_id && 
                                    $currentCollage->time->diffInMinutes($item->time) < 2) {
                                    $currentCollage->images[] = $item->data;
                                } else {
                                    if ($currentCollage) {
                                        $chatItems->push($currentCollage);
                                    }
                                    $currentCollage = (object)[
                                        'type' => 'collage',
                                        'sender_id' => $item->sender_id,
                                        'time' => $item->time,
                                        'images' => [$item->data]
                                    ];
                                }
                            } else {
                                if ($currentCollage) {
                                    $chatItems->push($currentCollage);
                                    $currentCollage = null;
                                }
                                $chatItems->push($item);
                            }
                        }
                        if ($currentCollage) {
                            $chatItems->push($currentCollage);
                        }
                    @endphp

                    @forelse($chatItems as $item)
                        @if($item->type == 'message')
                            <div class="flex {{ $item->data->sender_id == Auth::id() ? 'justify-end' : 'justify-start' }}">
                                <div class="max-w-[70%] {{ $item->data->sender_id == Auth::id() ? 'bg-primary text-white rounded-t-[24px] rounded-bl-[24px] shadow-lg shadow-primary/10' : 'bg-white border border-gray-100 text-gray-800 rounded-t-[24px] rounded-br-[24px] shadow-sm' }} px-6 py-4 text-[13px] leading-relaxed break-words">
                                    {{ $item->data->message }}
                                    <span class="block text-[8px] font-black uppercase tracking-widest mt-2 {{ $item->data->sender_id == Auth::id() ? 'text-white/60' : 'text-gray-400' }}">{{ $item->data->created_at->format('H:i') }}</span>
                                </div>
                            </div>
                        @elseif($item->type == 'collage')
                            <div class="flex {{ $item->sender_id == Auth::id() ? 'justify-end' : 'justify-start' }}">
                                <div class="max-w-[70%] p-2 rounded-[24px] {{ $item->sender_id == Auth::id() ? 'bg-primary/5 border border-primary/20 rounded-bl-[24px]' : 'bg-white border border-gray-100 rounded-br-[24px]' }} flex flex-col gap-2">
                                    <div class="grid {{ count($item->images) > 1 ? 'grid-cols-2' : 'grid-cols-1' }} gap-1 overflow-hidden rounded-xl">
                                        @foreach($item->images as $img)
                                            @php $fileUrl = str_starts_with($img->file_url, 'http') ? $img->file_url : asset('storage/' . $img->file_url); @endphp
                                            <a href="{{ $fileUrl }}" data-fancybox="gallery-{{ $item->time->timestamp }}" class="block {{ count($item->images) > 1 ? 'aspect-square' : '' }}">
                                                <img src="{{ $fileUrl }}" class="w-full h-full object-cover hover:opacity-90 transition-opacity {{ count($item->images) == 1 ? 'max-w-xs max-h-60 rounded-xl' : '' }}">
                                            </a>
                                        @endforeach
                                    </div>
                                    <span class="text-[8px] text-gray-400 font-bold px-2 w-full text-right">{{ $item->time->format('H:i') }}</span>
                                </div>
                            </div>
                        @elseif($item->type == 'attachment')
                            <div class="flex {{ $item->data->uploaded_by == Auth::id() ? 'justify-end' : 'justify-start' }}">
                                <div class="max-w-[70%] p-2 rounded-[24px] {{ $item->data->uploaded_by == Auth::id() ? 'bg-primary/5 border border-primary/20 rounded-bl-[24px]' : 'bg-white border border-gray-100 rounded-br-[24px]' }} flex flex-col items-center gap-2">
                                    @php $fileUrl = str_starts_with($item->data->file_url, 'http') ? $item->data->file_url : asset('storage/' . $item->data->file_url); @endphp
                                    <div class="flex items-center gap-3 px-4 py-2">
                                        <i class="fa-solid fa-file-pdf text-2xl text-red-500"></i>
                                        <a href="{{ $fileUrl }}" target="_blank" class="text-xs font-bold text-primary hover:underline line-clamp-1">Attachment File</a>
                                    </div>
                                    <span class="text-[8px] text-gray-400 font-bold px-2 w-full text-right">{{ $item->data->created_at->format('H:i') }}</span>
                                </div>
                            </div>
                        @endif
                    @empty
                        <div class="flex flex-col items-center justify-center h-full opacity-30 mt-20">
                            <i class="fa-solid fa-comments text-4xl mb-4"></i>
                            <p class="text-xs font-bold uppercase tracking-widest">No messages yet</p>
                        </div>
                    @endforelse


                </div>
            </div>

            <!-- Action Footer: Flying Chat Input -->
            @if($status == 4)
                <div class="absolute bottom-0 left-0 right-0 p-4 flex justify-center">
                    <div class="bg-green-50/90 backdrop-blur-md border border-green-200 rounded-2xl px-6 py-3 shadow-lg">
                        <p class="text-xs font-black text-green-700 uppercase tracking-widest"><i class="fa-solid fa-lock mr-2"></i> Proyek Selesai. Chat telah dinonaktifkan.</p>
                    </div>
                </div>
            @elseif($activeConsultation->is_chat_expired)
                <div class="absolute bottom-0 left-0 right-0 p-4 flex flex-col items-center gap-3">
                    <div class="bg-red-50/90 backdrop-blur-md border border-red-200 rounded-2xl px-6 py-3 shadow-lg flex flex-col items-center gap-3">
                        <p class="text-xs font-black text-red-700 uppercase tracking-widest"><i class="fa-solid fa-lock mr-2"></i> Waktu Konsultasi Habis. Chat telah dinonaktifkan.</p>
                        <a href="{{ route('customer.designers.book', $activeConsultation->designer_id) }}" class="px-6 py-2.5 bg-primary text-white rounded-xl text-[10px] font-black uppercase tracking-widest hover:scale-105 transition-all">Book Consultation Lagi</a>
                    </div>
                </div>
            @else
                <div class="absolute bottom-0 left-0 right-0 p-4 {{ in_array($status, [0, 5, 6, 7]) ? 'opacity-20 pointer-events-none' : '' }}">
                    <div class="max-w-4xl mx-auto">
                        <form action="{{ route('customer.consultation.messages.send', $activeConsultation->id) }}" method="POST" enctype="multipart/form-data" class="bg-white/80 backdrop-blur-xl rounded-[2rem] flex items-center px-4 py-2 border border-gray-200/60 focus-within:border-primary/40 focus-within:bg-white/95 transition-all shadow-xl shadow-black/5">
                            @csrf
                            <label for="chat-attachment" class="w-10 h-10 flex items-center justify-center text-gray-400 hover:text-primary transition-colors cursor-pointer rounded-full hover:bg-gray-100">
                                <i class="fa-solid fa-paperclip"></i>
                            </label>
                            <input type="file" id="chat-attachment" name="attachment" class="hidden" onchange="this.form.submit()">
                            <input type="text" name="message" placeholder="Tulis pesan atau instruksi untuk desainer..." class="flex-grow bg-transparent border-none outline-none px-4 py-3 text-xs font-medium">
                            <button type="submit" class="bg-primary text-white px-8 py-3 rounded-[1.5rem] text-[10px] font-black uppercase tracking-widest hover:scale-105 transition-all shadow-lg shadow-primary/20">
                                Kirim
                            </button>
                        </form>
                    </div>
                </div>
            @endif
            @php
                $actionRequiredStatuses = [
                    \App\Models\Consultation::STATUS_WAITING_CONSULTATION_FEE,
                    \App\Models\Consultation::STATUS_WAITING_BRIEF,
                    \App\Models\Consultation::STATUS_OFFER_RECEIVED,
                    \App\Models\Consultation::STATUS_UNDER_REVIEW,
                    \App\Models\Consultation::STATUS_WAITING_FINAL_PAYMENT
                ];
                $popupMessage = "";
                if ($status == \App\Models\Consultation::STATUS_WAITING_CONSULTATION_FEE) {
                    $popupMessage = "Harap unggah bukti pembayaran fee konsultasi.";
                } elseif ($status == \App\Models\Consultation::STATUS_WAITING_BRIEF) {
                    $popupMessage = "Pembayaran divalidasi! Harap isi brief ruangan Anda.";
                } elseif ($status == \App\Models\Consultation::STATUS_OFFER_RECEIVED || $status == \App\Models\Consultation::STATUS_UNDER_REVIEW) {
                    $popupMessage = "Desainer telah mengirimkan RAB (Penawaran Harga) untuk direview.";
                } elseif ($status == \App\Models\Consultation::STATUS_WAITING_FINAL_PAYMENT) {
                    $popupMessage = "RAB disetujui! Harap unggah bukti pembayaran akhir.";
                }
            @endphp

            @if(in_array($status, $actionRequiredStatuses))
                <div class="absolute inset-0 z-50 bg-white/70 backdrop-blur-sm flex items-center justify-center p-6">
                    <div class="max-w-sm w-full bg-white border border-gray-100 rounded-3xl p-10 shadow-2xl text-center">
                        <div class="w-20 h-20 bg-primary/10 rounded-2xl flex items-center justify-center text-primary mx-auto mb-6 animate-bounce">
                            <i class="fa-solid fa-bell text-3xl"></i>
                        </div>
                        <h3 class="text-xl font-bold mb-2">Tindakan Diperlukan</h3>
                        <p class="text-xs text-gray-500 leading-relaxed mb-8">
                            {{ $popupMessage }}
                        </p>
                        <a href="{{ route('customer.track-consultation.list') }}" class="inline-block bg-primary text-white w-full py-4 rounded-xl text-[10px] font-black uppercase tracking-[0.1em] shadow-lg shadow-primary/20 hover:scale-[1.02] transition-transform">
                            Buka Track Consultation
                        </a>
                        <button onclick="this.closest('.absolute').remove()" class="w-full mt-3 py-3 rounded-xl text-[10px] font-black text-gray-400 uppercase tracking-widest hover:bg-gray-50 hover:text-gray-600 transition-colors">
                            Nanti Saja
                        </button>
                    </div>
                </div>
            @endif
        </section>
    </div>
    @endif

    @if($activeConsultation && $activeConsultation->consultation_type == 'chat_consultation' && $activeConsultation->chat_expires_at && !$activeConsultation->is_chat_expired && $status == 1)
    <script>
        (function() {
            const expiresAt = new Date("{{ $activeConsultation->chat_expires_at->toIso8601String() }}").getTime();
            const timerSpan = document.getElementById('countdown-timer');
            
            function updateTimer() {
                const now = new Date().getTime();
                const distance = expiresAt - now;
                
                if (distance < 0) {
                    clearInterval(x);
                    if (timerSpan) timerSpan.innerHTML = "00:00";
                    window.location.reload();
                    return;
                }
                
                const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                const seconds = Math.floor((distance % (1000 * 60)) / 1000);
                
                if (timerSpan) {
                    timerSpan.innerHTML = 
                        String(minutes).padStart(2, '0') + ":" + 
                        String(seconds).padStart(2, '0');
                }
            }
            
            updateTimer();
            const x = setInterval(updateTimer, 1000);
        })();
    </script>
    @endif

    @if($activeConsultation)
    <!-- MEDIA DRAWER (WHATSAPP STYLE) -->
    <div id="media-drawer" class="fixed inset-y-0 right-0 w-96 bg-white border-l border-gray-200 shadow-2xl z-[60] transform translate-x-full transition-transform duration-300 ease-in-out flex flex-col">
        <div class="p-6 border-b border-gray-100 flex justify-between items-center bg-gray-50/50">
            <div>
                <h3 class="font-bold text-gray-900 text-lg">Media & Assets</h3>
                <p class="text-[10px] uppercase tracking-widest text-gray-400 font-bold mt-1">Files shared in this project</p>
            </div>
            <button onclick="document.getElementById('media-drawer').classList.add('translate-x-full');" class="w-8 h-8 rounded-full bg-white border border-gray-200 flex items-center justify-center text-gray-500 hover:text-red-500 hover:border-red-200 transition-colors">
                <i class="fa-solid fa-times"></i>
            </button>
        </div>
        <div class="flex-1 overflow-y-auto p-6 space-y-4 custom-scroll">
            @forelse($activeConsultation->attachments as $attachment)
                @php 
                    $fileUrl = str_starts_with($attachment->file_url, 'http') ? $attachment->file_url : asset('storage/'.$attachment->file_url); 
                @endphp
                <div class="flex items-start gap-4 p-4 bg-gray-50 rounded-2xl border border-gray-100 hover:border-primary/30 transition-colors group">
                    <div class="w-12 h-12 rounded-xl flex items-center justify-center shrink-0 {{ $attachment->file_type == 'image' || str_contains($attachment->file_url, 'image') ? 'bg-black overflow-hidden' : 'bg-red-50 text-red-500' }}">
                        @if($attachment->file_type == 'image' || str_contains($attachment->file_url, 'image'))
                            <img src="{{ $fileUrl }}" class="w-full h-full object-cover opacity-90 group-hover:opacity-100 group-hover:scale-110 transition-all duration-500">
                        @else
                            <i class="fa-solid fa-file-pdf text-xl"></i>
                        @endif
                    </div>
                    <div class="flex-1 min-w-0">
                        <a href="{{ $fileUrl }}" target="_blank" class="text-xs font-bold text-gray-800 hover:text-primary truncate block mb-1">
                            {{ basename($attachment->file_url) }}
                        </a>
                        <p class="text-[9px] text-gray-400 font-bold uppercase tracking-widest">
                            {{ $attachment->created_at->format('d M Y, H:i') }} • 
                            {{ $attachment->uploaded_by == Auth::id() ? 'You' : 'Designer' }}
                        </p>
                    </div>
                </div>
            @empty
                @if($activeConsultation->quotes->isEmpty())
                <div class="flex flex-col items-center justify-center py-12 text-center opacity-50">
                    <i class="fa-solid fa-folder-open text-4xl text-gray-300 mb-4"></i>
                    <p class="text-[10px] font-black uppercase tracking-widest text-gray-400">No media shared yet</p>
                </div>
                @endif
            @endforelse
            
            <!-- RAB Quotes as assets -->
            @foreach($activeConsultation->quotes as $quote)
                @if($quote->items)
                    @php 
                        $items = is_string($quote->items) ? json_decode($quote->items, true) : $quote->items; 
                    @endphp
                    @if(isset($items['file_path']))
                        <div class="flex items-start gap-4 p-4 bg-amber-50/50 rounded-2xl border border-amber-100 hover:border-amber-300 transition-colors group">
                            <div class="w-12 h-12 rounded-xl flex items-center justify-center shrink-0 bg-amber-100 text-amber-600">
                                <i class="fa-solid fa-file-invoice-dollar text-xl"></i>
                            </div>
                            <div class="flex-1 min-w-0">
                                <a href="{{ route('consultation.download-rab.public', $quote->id) }}" target="_blank" class="text-xs font-bold text-gray-800 hover:text-amber-600 truncate block mb-1">
                                    {{ $items['file_name'] ?? 'RAB_Project.pdf' }}
                                </a>
                                <p class="text-[9px] text-amber-600/60 font-bold uppercase tracking-widest">
                                    {{ $quote->created_at->format('d M Y, H:i') }} • Designer
                                </p>
                            </div>
                        </div>
                    @endif
                @endif
            @endforeach
        </div>
    </div>
    @endif

</body>
</html>