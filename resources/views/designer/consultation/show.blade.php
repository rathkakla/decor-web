<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Decor Designer - Project Workspace</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&display=swap');
        body { background-color: #F8F6F4; font-family: 'Plus Jakarta Sans', sans-serif; overflow-x: hidden; }
        .bg-primary { background-color: #B5733A; }
        .text-primary { color: #B5733A; }
        .custom-scroll::-webkit-scrollbar { width: 4px; }
        .custom-scroll::-webkit-scrollbar-thumb { background: #E5E7EB; border-radius: 10px; }
        .glass-card { background: rgba(255, 255, 255, 0.9); backdrop-filter: blur(10px); border: 1px solid rgba(255, 255, 255, 0.5); }
    </style>
</head>
<body class="text-gray-800">

    @php 
        $status = $consultation->status; 
        $consultation_id = "DEC-" . str_pad($consultation->id, 5, '0', STR_PAD_LEFT); 
    @endphp

    <main class="flex flex-col min-h-screen">
        <!-- HEADER: ADAPTIVE PROGRESS STEPS -->
        <header class="h-16 bg-primary flex items-center justify-between px-8 sticky top-0 z-40 shadow-xl text-white flex-shrink-0">
            <div class="flex items-center">
                <a href="{{ route('designer.consultations.index') }}" class="flex items-center space-x-3 group mr-8 border-r border-white/20 pr-8 py-2">
                    <i class="fa-solid fa-arrow-left text-lg group-hover:-translate-x-1 transition-transform"></i>
                    <span class="text-[10px] font-black uppercase tracking-[0.2em]">Back to History</span>
                </a>
                <h2 class="font-bold text-[10px] uppercase tracking-widest leading-none">Workspace <span class="text-white/40 ml-4 font-black">#{{ $consultation_id }}</span></h2>
            </div>
            
            <!-- STEPS LOGIC (0: Waiting Brief, 1: Drafting, 2: Review, 3: Revision, 4: Completed) -->
            <div class="flex items-center space-x-10">
                <div class="flex items-center space-x-3 {{ $status == 0 ? '' : 'opacity-30' }}">
                    <span class="w-6 h-6 rounded-full {{ $status == 0 ? 'bg-white text-primary' : 'border border-white' }} flex items-center justify-center text-[10px] font-black">1</span>
                    <span class="text-[10px] font-black uppercase tracking-widest">Brief</span>
                </div>
                <i class="fa-solid fa-chevron-right text-[10px] text-white/20"></i>
                <div class="flex items-center space-x-3 {{ $status == 1 ? '' : 'opacity-30' }}">
                    <span class="w-6 h-6 rounded-full {{ $status == 1 ? 'bg-white text-primary' : 'border border-white' }} flex items-center justify-center text-[10px] font-black">2</span>
                    <span class="text-[10px] font-black uppercase tracking-widest">Drafting</span>
                </div>
                <i class="fa-solid fa-chevron-right text-[10px] text-white/20"></i>
                <div class="flex items-center space-x-3 {{ in_array($status, [2, 3]) ? '' : 'opacity-30' }}">
                    <span class="w-6 h-6 rounded-full {{ in_array($status, [2, 3]) ? 'bg-white text-primary' : 'border border-white' }} flex items-center justify-center text-[10px] font-black">3</span>
                    <span class="text-[10px] font-black uppercase tracking-widest">Review</span>
                </div>
                <i class="fa-solid fa-chevron-right text-[10px] text-white/20"></i>
                <div class="flex items-center space-x-3 {{ $status == 4 ? '' : 'opacity-30' }}">
                    <span class="w-6 h-6 rounded-full {{ $status == 4 ? 'bg-white text-primary' : 'border border-white' }} flex items-center justify-center text-[10px] font-black">4</span>
                    <span class="text-[10px] font-black uppercase tracking-widest">Done</span>
                </div>
                
                <div class="h-8 w-px bg-white/10 mx-2"></div>
                <div class="flex items-center space-x-3 bg-white/10 px-3 py-1.5 rounded-xl border border-white/20">
                    <span class="text-[10px] font-black uppercase tracking-widest">{{ Auth::user()->full_name }}</span>
                    <div class="w-8 h-8 rounded-lg bg-white text-primary flex items-center justify-center font-bold">
                        {{ strtoupper(substr(Auth::user()->full_name, 0, 2)) }}
                    </div>
                </div>
            </div>
        </header>

        <div class="flex-1 flex overflow-hidden">
            <!-- LEFT PANEL: PROJECT INFO -->
            <aside class="w-80 bg-white border-r border-gray-100 p-8 space-y-10 overflow-y-auto flex-shrink-0 custom-scroll">
                <div class="space-y-4">
                    <h3 class="text-[9px] font-black text-gray-300 uppercase tracking-[0.3em] leading-none italic">Client Profile</h3>
                    <div class="bg-gray-50 rounded-[32px] p-6 border border-gray-100 shadow-inner">
                        <p class="text-[11px] font-black text-gray-900 uppercase leading-none mb-1">{{ $consultation->customer->user->full_name }}</p>
                        <p class="text-[10px] font-black text-primary uppercase tracking-tighter">Budget: {{ $consultation->budget_range }}</p>
                    </div>
                </div>

                <div class="space-y-4">
                    <h3 class="text-[9px] font-black text-gray-300 uppercase tracking-[0.3em] leading-none italic">Update Status</h3>
                    <form action="{{ route('designer.consultations.update-status', $consultation->id) }}" method="POST" class="space-y-4">
                        @csrf
                        @method('PATCH')
                        <div>
                            <select name="status" class="w-full bg-gray-50 border border-gray-100 rounded-xl px-4 py-2.5 text-[10px] font-black uppercase tracking-widest outline-none focus:ring-2 focus:ring-primary/10 transition-all">
                                <option value="5" {{ $status == 5 ? 'selected' : '' }}>Pending Approval</option>
                                <option value="7" {{ $status == 7 ? 'selected' : '' }}>Waiting Payment</option>
                                <option value="0" {{ $status == 0 ? 'selected' : '' }}>Waiting for Brief</option>
                                <option value="1" {{ $status == 1 ? 'selected' : '' }}>Drafting</option>
                                <option value="2" {{ $status == 2 ? 'selected' : '' }}>Under Review</option>
                                <option value="3" {{ $status == 3 ? 'selected' : '' }}>Revision Requested</option>
                                <option value="4" {{ $status == 4 ? 'selected' : '' }}>Completed</option>
                                <option value="6" {{ $status == 6 ? 'selected' : '' }}>Rejected</option>
                            </select>
                        </div>
                        <button type="submit" class="w-full bg-primary text-white py-2.5 rounded-xl text-[9px] font-black uppercase tracking-widest hover:bg-primary/90 transition-all">Update Work State</button>
                    </form>
                </div>

            </aside>

            <!-- CANVAS AREA: PROJECT WORKSPACE -->
            <section class="flex-1 bg-[#FDFCFB] flex flex-col overflow-hidden relative">
                <!-- Project Details Header -->
                <div class="p-12 border-b border-gray-100 bg-white/50">
                    <div class="max-w-4xl mx-auto">
                        <h3 class="text-3xl font-black text-gray-900 uppercase tracking-tight">{{ $consultation->title }}</h3>
                        <p class="text-sm text-gray-500 leading-relaxed mt-4">{{ $consultation->description }}</p>
                    </div>
                </div>

                <!-- Chat & Progress Area -->
                <div class="flex-1 overflow-y-auto p-12 custom-scroll bg-gray-50/30">
                    <div class="max-w-4xl mx-auto space-y-8">
                    @php
                        $chatItems = collect();
                        foreach($consultation->messages as $m) {
                            $chatItems->push((object)['type' => 'message', 'data' => $m, 'time' => $m->created_at]);
                        }
                        foreach($consultation->attachments as $a) {
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
                                    @if(Str::contains($item->data->file_type, 'image'))
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
                            <p class="text-[10px] font-bold uppercase tracking-widest">No messages yet</p>
                        </div>
                    @endforelse

                        @foreach($consultation->quotes as $quote)
                            <div class="flex justify-center my-8">
                                <div class="bg-amber-50 border border-amber-100 rounded-3xl p-8 max-w-2xl w-full shadow-sm">
                                    <div class="w-12 h-12 bg-amber-100 text-amber-600 rounded-full flex items-center justify-center mx-auto mb-4">
                                        <i class="fa-solid fa-file-invoice-dollar text-xl"></i>
                                    </div>
                                    <h4 class="text-[10px] font-black text-amber-800 uppercase tracking-widest mb-4 text-center">Project Agreement & RAB</h4>
                                    
                                    @if($quote->items)
                                        <div class="mb-6 flex justify-center">
                                            <a href="{{ route('consultation.download-rab.public', $quote->id) }}" target="_blank" class="flex items-center gap-2 bg-white text-amber-700 px-6 py-3 rounded-xl border border-amber-200 hover:bg-amber-100 transition-colors shadow-sm">
                                                <i class="fa-solid fa-file-arrow-down text-xl"></i>
                                                <span class="text-[11px] font-black uppercase tracking-widest">Download RAB File</span>
                                            </a>
                                        </div>
                                    @endif

                                    <div class="text-center mb-6 border-t border-amber-200/50 pt-4">
                                        <p class="text-[10px] text-amber-800 font-bold uppercase tracking-widest">Total Design Fee</p>
                                        <p class="text-2xl font-black text-amber-900 mt-1">Rp {{ number_format($quote->amount, 0, ',', '.') }}</p>
                                    </div>

                                    @if($quote->notes)
                                        <p class="text-[11px] text-amber-700/70 font-bold mt-2 leading-relaxed italic text-center">"{{ $quote->notes }}"</p>
                                    @endif

                                    <div class="mt-6 pt-6 border-t border-amber-200/50 flex flex-col items-center">
                                        <span class="px-4 py-1.5 rounded-full text-[9px] font-black uppercase tracking-widest 
                                            {{ $quote->status == 'pending' ? 'bg-amber-200 text-amber-800' : '' }}
                                            {{ $quote->status == 'accepted' ? 'bg-green-200 text-green-800' : '' }}
                                            {{ $quote->status == 'rejected' ? 'bg-red-200 text-red-800' : '' }}
                                            {{ $quote->status == 'revision' ? 'bg-blue-200 text-blue-800' : '' }}">
                                            Status: {{ ucfirst($quote->status) }}
                                        </span>
                                        
                                        @if($quote->status == 'accepted')
                                            <p class="text-[9px] font-black text-green-600 uppercase mt-3 italic">Client has accepted this project agreement!</p>
                                        @elseif($quote->status == 'rejected')
                                            <p class="text-[9px] font-black text-red-600 uppercase mt-3 italic">Client has rejected the project.</p>
                                        @elseif($quote->status == 'revision' && $quote->revision_notes)
                                            <div class="mt-4 bg-white p-4 rounded-xl border border-blue-100 text-left w-full">
                                                <p class="text-[9px] font-black text-blue-800 uppercase tracking-widest mb-1"><i class="fa-solid fa-pen-to-square mr-1"></i> Revision Requested:</p>
                                                <p class="text-[11px] text-gray-700 font-bold">"{{ $quote->revision_notes }}"</p>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- RAB Modal -->
                <div id="quoteModal" class="fixed inset-0 bg-black/60 backdrop-blur-sm z-50 hidden items-center justify-center p-6">
                    <div class="bg-white rounded-[32px] w-full max-w-2xl overflow-hidden shadow-2xl flex flex-col max-h-[90vh]">
                        <div class="p-8 border-b border-gray-50 flex justify-between items-center bg-gray-50/50 flex-shrink-0">
                            <div>
                                <h3 class="text-xl font-black text-gray-900 uppercase tracking-tight">Create RAB & Project Agreement</h3>
                                <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mt-1">Send RAB file and agreement details to client</p>
                            </div>
                            <button type="button" onclick="toggleQuoteModal()" class="text-gray-400 hover:text-red-500 transition-colors">
                                <i class="fa-solid fa-xmark text-2xl"></i>
                            </button>
                        </div>
                        <form action="{{ route('designer.consultations.send-quote', $consultation->id) }}" method="POST" enctype="multipart/form-data" class="flex flex-col flex-1 overflow-hidden" id="rabForm">
                            @csrf
                            <div class="p-8 space-y-6 overflow-y-auto custom-scroll flex-1">
                                <!-- Download Template Banner -->
                                <div class="bg-amber-50 border border-amber-100 rounded-2xl p-6 flex justify-between items-center">
                                    <div>
                                        <h4 class="text-[11px] font-black text-amber-800 uppercase tracking-widest">Template & Contoh RAB Decor</h4>
                                        <p class="text-[10px] text-amber-700 mt-1 font-bold">Unduh template kosong dan contoh pengisian RAB kami, lengkapi, lalu upload kembali di bawah ini.</p>
                                    </div>
                                    <a href="{{ route('designer.consultations.download-rab-template') }}?v={{ time() }}" class="bg-amber-600 hover:bg-amber-700 text-white px-5 py-3 rounded-xl text-[10px] font-black uppercase tracking-widest transition-all shrink-0">
                                        <i class="fa-solid fa-download mr-1"></i> Download Template & Contoh RAB
                                    </a>
                                </div>

                                <!-- File Upload -->
                                <div class="space-y-2">
                                    <label class="text-[9px] font-black text-gray-400 uppercase tracking-widest">Upload Completed RAB File</label>
                                    <input type="file" name="rab_file" required class="w-full bg-gray-50 border border-gray-100 rounded-2xl px-6 py-4 text-[11px] font-bold outline-none focus:ring-2 focus:ring-primary/10 transition-all">
                                    <p class="text-[8px] text-gray-400 font-bold uppercase">Format didukung: PDF, Excel (XLSX, XLS), CSV, Gambar (Max 10MB)</p>
                                </div>

                                <!-- Amount -->
                                <div class="space-y-2 pt-2 border-t border-gray-100">
                                    <label class="text-[9px] font-black text-gray-400 uppercase tracking-widest">Total Project Fee / Design Fee (IDR)</label>
                                    <div class="relative">
                                        <span class="absolute left-6 top-1/2 -translate-y-1/2 text-gray-400 font-bold">Rp</span>
                                        <input type="number" name="amount" required placeholder="0" min="0" class="w-full bg-gray-50 border border-gray-100 rounded-2xl pl-14 pr-6 py-4 text-[13px] font-bold outline-none focus:ring-2 focus:ring-primary/10 transition-all">
                                    </div>
                                </div>

                                <!-- Notes -->
                                <div class="space-y-2">
                                    <label class="text-[9px] font-black text-gray-400 uppercase tracking-widest">Additional Notes</label>
                                    <textarea name="notes" placeholder="Tuliskan catatan tambahan atau syarat & ketentuan..." class="w-full bg-gray-50 border border-gray-100 rounded-2xl px-6 py-4 text-[11px] font-bold outline-none focus:ring-2 focus:ring-primary/10 transition-all h-24"></textarea>
                                </div>
                            </div>
                            <div class="p-8 border-t border-gray-50 bg-white flex-shrink-0">
                                <button type="submit" class="w-full bg-primary text-white py-5 rounded-2xl text-[11px] font-black uppercase tracking-[0.2em] shadow-xl shadow-primary/30 hover:scale-[1.02] transition-all">
                                    Kirim RAB & Project Agreement
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <script>
                    function toggleQuoteModal() {
                        const modal = document.getElementById('quoteModal');
                        if (modal.classList.contains('hidden')) {
                            modal.classList.remove('hidden');
                            modal.classList.add('flex');
                        } else {
                            modal.classList.remove('flex');
                            modal.classList.add('hidden');
                        }
                    }
                </script>

                <!-- Action Footer -->
                <div class="p-6 bg-white border-t border-gray-100">
                    <div class="max-w-4xl mx-auto flex items-center space-x-4">
                        <form action="{{ route('designer.consultations.messages.send', $consultation->id) }}" method="POST" enctype="multipart/form-data" class="flex-1 flex space-x-2 bg-gray-50 border border-gray-100 rounded-[2rem] px-4 py-2 items-center focus-within:border-primary/30 focus-within:bg-white transition-all shadow-sm">
                            @csrf
                            <label for="chat-attachment" class="w-10 h-10 flex items-center justify-center text-gray-400 hover:text-primary transition-colors cursor-pointer rounded-full hover:bg-gray-100 shrink-0">
                                <i class="fa-solid fa-paperclip"></i>
                            </label>
                            <input type="file" id="chat-attachment" name="attachment" class="hidden" onchange="this.form.submit()">
                            
                            <input type="text" name="message" placeholder="Type your instruction or update for the client..." class="flex-1 bg-transparent border-none outline-none text-[11px] font-bold px-2">
                            <button type="submit" class="bg-primary text-white px-8 py-3 rounded-[1.5rem] text-[10px] font-black uppercase tracking-widest hover:scale-105 transition-all shadow-lg shadow-primary/20 shrink-0">
                                Send
                            </button>
                        </form>
                        <button onclick="toggleQuoteModal()" class="bg-amber-100 text-amber-700 px-8 py-4 rounded-2xl text-[10px] font-black uppercase tracking-widest hover:bg-amber-200 transition-all border border-amber-200 shrink-0">
                            <i class="fa-solid fa-file-invoice mr-2"></i> Project Agreement (RAB)
                        </button>
                    </div>
                </div>
            </section>
        </div>

        <!-- STICKY FOOTER: ACTIONS -->
        <footer class="h-20 bg-white/95 backdrop-blur-md border-t border-gray-100 px-10 flex items-center justify-between flex-shrink-0">
             <a href="{{ route('consultation.invoice.public', $consultation->id) }}" class="flex items-center space-x-3 text-gray-400 hover:text-primary transition-all group italic">
                <i class="fa-solid fa-file-invoice-dollar text-xl group-hover:rotate-12 transition-transform"></i>
                <span class="text-[10px] font-black uppercase tracking-widest leading-none">Generate Invoice</span>
            </a>

            <div class="flex items-center space-x-4">
                @if($status == 5)
                    <div class="flex space-x-3">
                        <form action="{{ route('designer.consultations.update-status', $consultation->id) }}" method="POST">
                            @csrf @method('PATCH')
                            <input type="hidden" name="status" value="7">
                            <button type="submit" class="px-8 py-3.5 bg-green-600 text-white rounded-2xl text-[10px] font-black uppercase tracking-widest hover:bg-green-700 transition-all shadow-xl shadow-green-200">
                                <i class="fa-solid fa-check mr-2"></i> Approve Request
                            </button>
                        </form>
                        <form action="{{ route('designer.consultations.update-status', $consultation->id) }}" method="POST">
                            @csrf @method('PATCH')
                            <input type="hidden" name="status" value="6">
                            <button type="submit" class="px-8 py-3.5 bg-red-600 text-white rounded-2xl text-[10px] font-black uppercase tracking-widest hover:bg-red-700 transition-all shadow-xl shadow-red-200">
                                <i class="fa-solid fa-xmark mr-2"></i> Reject
                            </button>
                        </form>
                    </div>
                @elseif($status == 7)
                    <div class="bg-amber-50 text-amber-600 px-6 py-3 rounded-xl border border-amber-100">
                        <p class="text-[10px] font-black uppercase tracking-widest">
                            <i class="fa-solid fa-clock mr-2"></i> Approved. Waiting for Client Payment
                        </p>
                    </div>
                @elseif($status == 6)
                    <div class="bg-red-50 text-red-600 px-6 py-3 rounded-xl border border-red-100">
                        <p class="text-[10px] font-black uppercase tracking-widest">
                            <i class="fa-solid fa-ban mr-2"></i> Consultation Rejected
                        </p>
                    </div>
                @elseif($status == 0)
                    <p class="text-[10px] font-black text-gray-400 uppercase italic mr-4">Waiting for client brief...</p>
                    <form action="{{ route('designer.consultations.update-status', $consultation->id) }}" method="POST">
                        @csrf @method('PATCH')
                        <input type="hidden" name="status" value="1">
                        <button type="submit" class="px-10 py-3.5 bg-gray-900 text-white rounded-2xl text-[10px] font-black uppercase tracking-widest">Start Project</button>
                    </form>
                @elseif($status == 1)
                    <form action="{{ route('designer.consultations.update-status', $consultation->id) }}" method="POST">
                        @csrf @method('PATCH')
                        <input type="hidden" name="status" value="4">
                        <button type="submit" class="px-14 py-3.5 bg-primary text-white rounded-2xl text-[10px] font-black uppercase tracking-[0.2em] shadow-2xl shadow-primary/40 hover:scale-105 transition-all">
                            Submit Final Concept
                        </button>
                    </form>
                @elseif($status == 4)
                    <div class="bg-green-50 text-green-600 px-6 py-3 rounded-xl text-[10px] font-black uppercase tracking-widest">
                        Project Completed
                    </div>
                @endif
            </div>
        </footer>
    </main>

</body>
</html>