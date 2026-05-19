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
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&display=swap');
        body { background-color: #F8F6F4; font-family: 'Plus Jakarta Sans', sans-serif; overflow-x: hidden; }
        .custom-scroll::-webkit-scrollbar { width: 4px; }
        .custom-scroll::-webkit-scrollbar-thumb { background: #E5E7EB; border-radius: 10px; }
    </style>
</head>
<body class="text-gray-800 flex flex-col min-h-screen">

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
        
        <!-- STEPS LOGIC -->
        <div class="flex items-center space-x-10 text-gray-400">
            <div class="flex items-center space-x-3 {{ in_array($status, [0, 5, 7]) ? 'text-primary' : ( $status > 0 ? 'text-green-500' : 'opacity-50') }}">
                <span class="w-6 h-6 rounded-full {{ in_array($status, [0, 5, 7]) ? 'bg-primary text-white' : ( $status > 0 ? 'bg-green-500 text-white' : 'border border-gray-300') }} flex items-center justify-center text-[10px] font-black">
                    @if($status > 0 && !in_array($status, [5,7])) <i class="fa-solid fa-check"></i> @else 1 @endif
                </span>
                <span class="text-[10px] font-black uppercase tracking-widest">Setup</span>
            </div>
            <i class="fa-solid fa-chevron-right text-[10px] text-gray-200"></i>
            <div class="flex items-center space-x-3 {{ $status == 1 ? 'text-primary' : ( $status > 1 ? 'text-green-500' : 'opacity-50') }}">
                <span class="w-6 h-6 rounded-full {{ $status == 1 ? 'bg-primary text-white' : ( $status > 1 ? 'bg-green-500 text-white' : 'border border-gray-300') }} flex items-center justify-center text-[10px] font-black">
                    @if($status > 1) <i class="fa-solid fa-check"></i> @else 2 @endif
                </span>
                <span class="text-[10px] font-black uppercase tracking-widest">Active</span>
            </div>
            <i class="fa-solid fa-chevron-right text-[10px] text-gray-200"></i>
            <div class="flex items-center space-x-3 {{ in_array($status, [2, 3]) ? 'text-primary' : ( $status == 4 ? 'text-green-500' : 'opacity-50') }}">
                <span class="w-6 h-6 rounded-full {{ in_array($status, [2, 3]) ? 'bg-primary text-white' : ( $status == 4 ? 'bg-green-500 text-white' : 'border border-gray-300') }} flex items-center justify-center text-[10px] font-black">
                    @if($status == 4) <i class="fa-solid fa-check"></i> @else 3 @endif
                </span>
                <span class="text-[10px] font-black uppercase tracking-widest">Review</span>
            </div>
            <i class="fa-solid fa-chevron-right text-[10px] text-gray-200"></i>
            <div class="flex items-center space-x-3 {{ $status == 4 ? 'text-green-500' : 'opacity-50' }}">
                <span class="w-6 h-6 rounded-full {{ $status == 4 ? 'bg-green-500 text-white' : 'border border-gray-300' }} flex items-center justify-center text-[10px] font-black">4</span>
                <span class="text-[10px] font-black uppercase tracking-widest">Done</span>
            </div>
            
            <div class="h-8 w-px bg-gray-200 mx-2"></div>
            <div class="flex items-center space-x-3 bg-gray-50 px-3 py-1.5 rounded-xl border border-gray-100">
                <span class="text-[10px] font-black uppercase tracking-widest text-gray-600">{{ Auth::user()->full_name }}</span>
                <img src="{{ Auth::user()->avatar_url }}" class="w-8 h-8 rounded-lg object-cover">
            </div>
        </div>
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
                        <p class="text-[11px] font-black text-primary">{{ \App\Models\Consultation::getStatusLabel($status) }}</p>
                    </div>
                </div>
            </div>

        </aside>

        <!-- CANVAS AREA: PROJECT WORKSPACE -->
        <section class="flex-1 bg-[#FDFCFB] flex flex-col overflow-hidden relative">
            <!-- Project Details Header -->
            <div class="p-8 border-b border-gray-100 bg-white/80 backdrop-blur-sm z-10 flex justify-between items-center">
                <div class="max-w-2xl">
                    <h3 class="text-2xl font-black text-gray-900 uppercase tracking-tight">{{ $activeConsultation->title }}</h3>
                    <p class="text-xs text-gray-500 leading-relaxed mt-2 line-clamp-2">{{ $activeConsultation->description ?? 'No brief description provided.' }}</p>
                </div>
                
                @if($status == \App\Models\Consultation::STATUS_WAITING_CONSULTATION_FEE)
                    @php $fee = $activeConsultation->consultation_type == 'request_proposal' ? 250000 : 50000; @endphp
                    <form action="{{ route('customer.consultation.pay-fee', $activeConsultation->id) }}" method="POST">
                        @csrf
                        <button type="submit" class="bg-primary text-white px-8 py-4 rounded-2xl text-[10px] font-black uppercase tracking-[0.2em] shadow-xl shadow-primary/20 hover:scale-[1.03] transition-all flex items-center gap-3">
                            <span>Bayar Fee: Rp {{ number_format($fee, 0, ',', '.') }}</span>
                            <i class="fa-solid fa-arrow-right"></i>
                        </button>
                    </form>
                @endif
            </div>

            <!-- Overlays -->
            @if($status == \App\Models\Consultation::STATUS_WAITING_APPROVAL)
            <div class="absolute inset-0 z-20 bg-white/60 backdrop-blur-md flex items-center justify-center p-6">
                <div class="max-w-sm w-full bg-white border border-gray-100 rounded-[3rem] p-12 shadow-2xl text-center">
                    <div class="w-20 h-20 bg-orange-50 rounded-[2rem] flex items-center justify-center text-orange-400 mx-auto mb-8 animate-pulse">
                        <i class="fa-solid fa-hourglass-half text-3xl"></i>
                    </div>
                    <h3 class="text-2xl font-bold italic mb-3">Menunggu Approval</h3>
                    <p class="text-[11px] text-gray-400 leading-relaxed italic">Desainer sedang meninjau permintaan konsultasi Anda. Mohon tunggu sebentar.</p>
                </div>
            </div>
            @endif

            @if($status == \App\Models\Consultation::STATUS_WAITING_BRIEF)
            <div class="absolute inset-0 z-20 bg-white/80 backdrop-blur-lg flex items-center justify-center p-6">
                <div class="max-w-md w-full bg-white border border-gray-100 rounded-[3rem] p-12 shadow-2xl">
                    <h3 class="text-2xl font-bold italic mb-3 text-center">Isi Brief Proyek</h3>
                    <p class="text-[11px] text-gray-400 leading-relaxed mb-8 text-center italic">Berikan detail ruangan agar desainer bisa memberikan penawaran harga yang sesuai.</p>
                    <form action="{{ route('customer.consultation.submit-brief', $activeConsultation->id) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                        @csrf
                        <div>
                            <textarea name="description" rows="4" placeholder="Detail ruangan, ukuran, preferensi warna..." required class="w-full bg-gray-50 border border-gray-100 rounded-2xl px-6 py-4 text-sm font-medium focus:border-primary/30 outline-none transition-all"></textarea>
                        </div>
                        <button type="submit" class="w-full bg-primary text-white py-5 rounded-2xl text-[11px] font-black uppercase tracking-[0.3em] shadow-xl shadow-primary/20 hover:scale-[1.03] transition-all">Submit Brief</button>
                    </form>
                </div>
            </div>
            @endif

            <!-- Chat Area -->
            <div class="flex-1 overflow-y-auto p-8 custom-scroll bg-gray-50/30 {{ in_array($status, [0, 5, 6, 7]) ? 'blur-md opacity-20 pointer-events-none' : '' }}">
                <div class="max-w-4xl mx-auto space-y-8">
                    
                    @php
                        $chatItems = collect();
                        foreach($activeConsultation->messages as $m) {
                            $chatItems->push((object)['type' => 'message', 'data' => $m, 'time' => $m->created_at]);
                        }
                        foreach($activeConsultation->attachments as $a) {
                            $chatItems->push((object)['type' => 'attachment', 'data' => $a, 'time' => $a->created_at]);
                        }
                        $chatItems = $chatItems->sortBy('time');
                    @endphp

                    @forelse($chatItems as $item)
                        @if($item->type == 'message')
                            <div class="flex {{ $item->data->sender_id == Auth::id() ? 'justify-end' : 'justify-start' }}">
                                <div class="max-w-[70%] {{ $item->data->sender_id == Auth::id() ? 'bg-primary text-white rounded-t-[24px] rounded-bl-[24px] shadow-lg shadow-primary/10' : 'bg-white border border-gray-100 text-gray-800 rounded-t-[24px] rounded-br-[24px] shadow-sm' }} px-6 py-4 text-[13px] leading-relaxed break-words">
                                    {{ $item->data->message }}
                                    <span class="block text-[8px] font-black uppercase tracking-widest mt-2 {{ $item->data->sender_id == Auth::id() ? 'text-white/60' : 'text-gray-400' }}">{{ $item->data->created_at->format('H:i') }}</span>
                                </div>
                            </div>
                        @elseif($item->type == 'attachment')
                            <div class="flex {{ $item->data->uploaded_by == Auth::id() ? 'justify-end' : 'justify-start' }}">
                                <div class="max-w-[70%] p-2 rounded-[24px] {{ $item->data->uploaded_by == Auth::id() ? 'bg-primary/5 border border-primary/20 rounded-bl-[24px]' : 'bg-white border border-gray-100 rounded-br-[24px]' }} flex flex-col items-center gap-2">
                                    @if($item->data->file_type == 'image')
                                        <a href="{{ $item->data->file_url }}" target="_blank">
                                            <img src="{{ $item->data->file_url }}" class="max-w-xs max-h-60 rounded-xl object-cover">
                                        </a>
                                    @else
                                        <div class="flex items-center gap-3 px-4 py-2">
                                            <i class="fa-solid fa-file-pdf text-2xl text-red-500"></i>
                                            <a href="{{ $item->data->file_url }}" target="_blank" class="text-xs font-bold text-primary hover:underline line-clamp-1">Attachment File</a>
                                        </div>
                                    @endif
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

                    {{-- Handle Offers --}}
                    @if($status == \App\Models\Consultation::STATUS_OFFER_RECEIVED || $status == \App\Models\Consultation::STATUS_UNDER_REVIEW || $status == \App\Models\Consultation::STATUS_WAITING_FINAL_PAYMENT)
                        @php $quote = $activeConsultation->quotes->first(); @endphp
                        @if($quote && $quote->status !== 'revision')
                        <div class="flex justify-center my-10">
                            <div class="max-w-2xl w-full bg-white border-2 border-primary/20 rounded-[2.5rem] p-10 shadow-2xl relative overflow-hidden">
                                <div class="absolute top-0 right-0 p-4"><span class="text-[8px] font-black uppercase tracking-widest bg-primary text-white px-3 py-1 rounded-full">New RAB / Agreement</span></div>
                                <h4 class="text-[12px] font-black uppercase tracking-[0.3em] text-primary mb-6 text-center">Project Agreement & RAB</h4>
                                
                                @if($quote->items)
                                    <div class="mb-6 flex justify-center">
                                        <a href="{{ route('consultation.download-rab.public', $quote->id) }}" target="_blank" class="flex items-center gap-2 bg-amber-50 text-amber-700 px-6 py-3 rounded-xl border border-amber-200 hover:bg-amber-100 transition-colors">
                                            <i class="fa-solid fa-file-arrow-down text-xl"></i>
                                            <span class="text-[11px] font-black uppercase tracking-widest">Download RAB File</span>
                                        </a>
                                    </div>
                                @endif

                                <div class="text-center mb-6 border-t border-gray-100 pt-6">
                                    <h4 class="text-[10px] font-bold uppercase text-gray-400 tracking-widest">Total Design Fee</h4>
                                    <h3 class="text-3xl font-black italic text-primary mt-2">Rp {{ number_format($quote->amount, 0, ',', '.') }}</h3>
                                </div>

                                @if($quote->notes)
                                    <div class="bg-gray-50 p-4 rounded-xl mb-8">
                                        <p class="text-[11px] text-gray-600 leading-relaxed italic">"{{ $quote->notes }}"</p>
                                    </div>
                                @endif

                                @if($status == \App\Models\Consultation::STATUS_OFFER_RECEIVED || $status == \App\Models\Consultation::STATUS_UNDER_REVIEW)
                                <div class="flex gap-4 mb-4">
                                    <form action="{{ route('customer.consultation.reject-offer', $activeConsultation->id) }}" method="POST" class="flex-1">
                                        @csrf
                                        <button type="submit" class="w-full py-4 rounded-xl border border-gray-100 text-[10px] font-black uppercase tracking-widest hover:bg-red-50 hover:text-red-500 transition-all">Reject Project</button>
                                    </form>
                                    <form action="{{ route('customer.consultation.accept-offer', $activeConsultation->id) }}" method="POST" class="flex-1">
                                        @csrf
                                        <button type="submit" class="w-full bg-primary text-white py-4 rounded-xl text-[10px] font-black uppercase tracking-widest shadow-lg shadow-primary/20">Accept Project</button>
                                    </form>
                                </div>
                                <div class="mt-4 border-t border-gray-100 pt-4">
                                    <button type="button" onclick="document.getElementById('revision-form-container').classList.toggle('hidden')" class="w-full py-3 rounded-xl border border-dashed border-gray-300 text-[10px] font-black text-gray-500 uppercase tracking-widest hover:bg-gray-50 transition-all">
                                        <i class="fa-solid fa-pen-to-square mr-2"></i> Request Revision
                                    </button>
                                    <div id="revision-form-container" class="hidden mt-4 bg-gray-50 p-4 rounded-xl">
                                        <form action="{{ route('customer.consultation.request-revision', $activeConsultation->id) }}" method="POST">
                                            @csrf
                                            <textarea name="revision_notes" rows="3" required placeholder="Tuliskan bagian mana yang perlu direvisi..." class="w-full bg-white border border-gray-200 rounded-lg p-3 text-xs font-medium outline-none focus:border-primary mb-3"></textarea>
                                            <button type="submit" class="w-full bg-gray-800 text-white py-3 rounded-lg text-[10px] font-black uppercase tracking-widest">Kirim Revisi</button>
                                        </form>
                                    </div>
                                </div>
                                @else
                                <form action="{{ route('customer.consultation.pay-final', $activeConsultation->id) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="w-full bg-green-500 text-white py-5 rounded-xl text-[10px] font-black uppercase tracking-widest shadow-lg shadow-green-500/20">Pay Now</button>
                                </form>
                                @endif
                            </div>
                        </div>
                        @endif
                    @endif
                </div>
            </div>

            <!-- Action Footer -->
            <div class="p-6 bg-white border-t border-gray-100 {{ in_array($status, [0, 5, 6, 7]) ? 'opacity-20 pointer-events-none' : '' }}">
                <div class="max-w-4xl mx-auto">
                    <form action="{{ route('customer.consultation.messages.send', $activeConsultation->id) }}" method="POST" enctype="multipart/form-data" class="bg-gray-50 rounded-[2rem] flex items-center px-4 py-2 border border-gray-100 focus-within:border-primary/30 focus-within:bg-white transition-all shadow-sm">
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
        </section>
    </div>
    @endif
</body>
</html>