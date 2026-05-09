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
        /** 
         * LOGIC FOR BACK-END TEAM 
         * $status options: 'brief', 'revision', 'finish'
         */
        $status = 'revision'; 
        $consultation_id = "DEC-88219"; 
        $revision_note = "Warna palet di area ruang tamu tolong dibuat lebih hangat (earth tones), dan ukuran mejanya diperkecil sedikit ya."; 
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
            
            <!-- STEPS LOGIC -->
            <div class="flex items-center space-x-10">
                <!-- STEP 1: BRIEF -->
                <div class="flex items-center space-x-3 {{ $status == 'brief' ? '' : 'opacity-30' }}">
                    <span class="w-6 h-6 rounded-full {{ $status == 'brief' ? 'bg-white text-primary' : 'border border-white' }} flex items-center justify-center text-[10px] font-black shadow-lg">1</span>
                    <span class="text-[10px] font-black uppercase tracking-widest">Brief</span>
                </div>

                <i class="fa-solid fa-chevron-right text-[10px] text-white/20"></i>

                <!-- STEP 2: REVISION -->
                <div class="flex items-center space-x-3 {{ $status == 'revision' ? '' : 'opacity-30' }}">
                    <span class="w-6 h-6 rounded-full {{ $status == 'revision' ? 'bg-red-500 text-white animate-pulse' : 'border border-white' }} flex items-center justify-center text-[10px] font-black {{ $status == 'revision' ? 'shadow-lg shadow-red-500/50' : '' }}">2</span>
                    <span class="text-[10px] font-black uppercase tracking-widest">Revision</span>
                </div>

                <i class="fa-solid fa-chevron-right text-[10px] text-white/20"></i>

                <!-- STEP 3: FINISH -->
                <div class="flex items-center space-x-3 {{ $status == 'finish' ? '' : 'opacity-30' }}">
                    <span class="w-6 h-6 rounded-full {{ $status == 'finish' ? 'bg-green-500 text-white' : 'border border-white' }} flex items-center justify-center text-[10px] font-black {{ $status == 'finish' ? 'shadow-lg shadow-green-500/50' : '' }}">3</span>
                    <span class="text-[10px] font-black uppercase tracking-widest">Finish</span>
                </div>
                
                <div class="h-8 w-px bg-white/10 mx-2"></div>
                <div class="flex items-center space-x-3 bg-white/10 px-3 py-1.5 rounded-xl border border-white/20">
                    <span class="text-[10px] font-black uppercase tracking-widest">Elena Vance</span>
                    <div class="w-8 h-8 rounded-lg bg-white text-primary flex items-center justify-center font-bold">EV</div>
                </div>
            </div>
        </header>

        <div class="flex-1 flex overflow-hidden">
            <!-- LEFT PANEL: PROJECT INFO (Always Consistent) -->
            <aside class="w-80 bg-white border-r border-gray-100 p-8 space-y-10 overflow-y-auto flex-shrink-0 custom-scroll">
                <div class="space-y-4">
                    <h3 class="text-[9px] font-black text-gray-300 uppercase tracking-[0.3em] leading-none italic">Client Profile</h3>
                    <div class="bg-gray-50 rounded-[32px] p-6 border border-gray-100 shadow-inner">
                        <p class="text-[11px] font-black text-gray-900 uppercase leading-none mb-1">Elena Rodriguez</p>
                        <p class="text-[10px] font-black text-primary uppercase tracking-tighter">Budget: $1,250.00</p>
                    </div>
                </div>

                <div class="space-y-4">
                    <h3 class="text-[9px] font-black text-gray-300 uppercase tracking-[0.3em] leading-none italic">Project Assets</h3>
                    <div class="grid grid-cols-2 gap-3">
                        <div class="aspect-square bg-gray-50 rounded-2xl border-2 border-dashed border-gray-200 flex flex-col items-center justify-center group hover:border-primary transition-all cursor-pointer">
                            <i class="fa-solid fa-image text-gray-300 group-hover:text-primary text-xl mb-2"></i>
                            <span class="text-[8px] font-black text-gray-400 uppercase tracking-tighter">Concept.jpg</span>
                        </div>
                        <div class="aspect-square bg-gray-50 rounded-2xl border-2 border-dashed border-gray-200 flex flex-col items-center justify-center group hover:border-red-400 transition-all cursor-pointer">
                            <i class="fa-solid fa-file-pdf text-gray-300 group-hover:text-red-400 text-xl mb-2"></i>
                            <span class="text-[8px] font-black text-gray-400 uppercase tracking-tighter">Layout.pdf</span>
                        </div>
                    </div>
                </div>

                <!-- MEMO: POST-IT STYLE -->
                <div class="pt-6">
                    <div class="bg-amber-50 p-7 rounded-[24px] border border-amber-100 shadow-xl shadow-amber-900/5 rotate-2 relative">
                        <i class="fa-solid fa-thumbtack absolute top-4 right-4 text-amber-200 text-xl"></i>
                        <h3 class="text-[9px] font-black text-amber-700 uppercase tracking-widest mb-3 italic">Private Memo</h3>
                        <p class="text-[11px] font-bold text-amber-900/70 leading-relaxed italic">"Cek pencahayaan alami dari jendela utama untuk memaksimalkan nuansa earth tones."</p>
                    </div>
                </div>

                <div class="pt-10">
                     <p class="text-[8px] font-black text-gray-200 uppercase tracking-[0.4em] italic text-center leading-loose">© 2026 DECOR STUDIO<br>ALL RIGHTS RESERVED</p>
                </div>
            </aside>

            <!-- CANVAS AREA: FOCUS MODE -->
            <section class="flex-1 bg-[#FDFCFB] p-12 overflow-y-auto custom-scroll relative">
                <div class="max-w-5xl mx-auto space-y-12 pb-24">
                    
                    @if($status == 'brief')
                        <!-- PHASE 1: BRIEF/UPLOAD STATE -->
                        <div class="py-24 border-4 border-dashed border-gray-100 rounded-[64px] flex flex-col items-center justify-center space-y-6 bg-white/50">
                            <div class="w-24 h-24 bg-gray-50 rounded-full flex items-center justify-center text-gray-200 text-4xl shadow-inner">
                                <i class="fa-solid fa-cloud-arrow-up"></i>
                            </div>
                            <div class="text-center space-y-2">
                                <h3 class="text-2xl font-black text-gray-400 uppercase tracking-tight">No Concept Uploaded Yet</h3>
                                <p class="text-sm text-gray-300 font-bold italic">Start this project by uploading your first creative concept.</p>
                            </div>
                        </div>

                    @elseif($status == 'revision')
                        <!-- PHASE 2: REVISION STATE -->
                        <div class="flex items-start space-x-6">
                            <div class="w-14 h-14 bg-red-500 rounded-3xl flex items-center justify-center text-white shadow-2xl shadow-red-200 flex-shrink-0 animate-bounce">
                                <i class="fa-solid fa-comment-dots text-xl"></i>
                            </div>
                            <div class="glass-card p-10 rounded-[48px] shadow-sm relative flex-1">
                                <p class="text-[14px] font-bold text-gray-800 leading-relaxed italic">"{{ $revision_note }}"</p>
                                <p class="text-[9px] text-gray-400 font-black uppercase tracking-[0.2em] mt-6 border-t border-gray-50 pt-4">— ELENA RODRIGUEZ (09:45 AM)</p>
                            </div>
                        </div>

                        <div class="relative group">
                            <div class="bg-white p-4 rounded-[64px] shadow-[0_32px_64px_-16px_rgba(0,0,0,0.1)] border border-gray-100 overflow-hidden relative transition-all duration-700 hover:shadow-2xl">
                                <div class="aspect-video bg-gray-100 rounded-[52px] overflow-hidden">
                                    <img src="https://images.unsplash.com/photo-1618221195710-dd6b41faaea6?q=80&w=2000" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-[2s]">
                                </div>
                                <div class="absolute top-12 left-12 bg-black/60 backdrop-blur-xl px-6 py-2.5 rounded-full text-white text-[10px] font-black uppercase tracking-[0.3em] shadow-2xl">
                                    Active Draft v1.0
                                </div>
                            </div>
                        </div>

                    @elseif($status == 'finish')
                        <!-- PHASE 3: FINISH STATE -->
                        <div class="text-center space-y-8">
                            <div class="inline-block bg-green-50 text-green-600 px-10 py-4 rounded-full text-sm font-black uppercase tracking-widest shadow-sm">
                                <i class="fa-solid fa-circle-check mr-3"></i> Project Successfully Completed
                            </div>
                            
                            <div class="bg-white p-4 rounded-[64px] shadow-2xl border border-gray-100 overflow-hidden relative grayscale hover:grayscale-0 transition-all duration-1000">
                                <div class="aspect-video bg-gray-100 rounded-[52px] overflow-hidden">
                                    <img src="https://images.unsplash.com/photo-1618221195710-dd6b41faaea6?q=80&w=2000" class="w-full h-full object-cover">
                                </div>
                                <div class="absolute inset-0 bg-gradient-to-t from-black/40 to-transparent"></div>
                                <div class="absolute bottom-12 left-1/2 -translate-x-1/2">
                                    <p class="text-white text-[10px] font-black uppercase tracking-[0.5em] opacity-80">Final Masterpiece</p>
                                </div>
                            </div>
                        </div>
                    @endif

                </div>
            </section>
        </div>

        <!-- FOOTER: ADAPTIVE ACTIONS -->
        <footer class="h-20 bg-white/95 backdrop-blur-md border-t border-gray-100 px-10 flex items-center justify-between sticky bottom-0 z-40 flex-shrink-0">
            <a href="{{ route('invoice.download', $consultation_id) }}" class="flex items-center space-x-3 text-gray-400 hover:text-primary transition-all group italic">
                <i class="fa-solid fa-file-invoice-dollar text-xl group-hover:rotate-12 transition-transform"></i>
                <span class="text-[10px] font-black uppercase tracking-widest leading-none">Invoice System</span>
            </a>

            <div class="flex items-center space-x-4">
                @if($status == 'brief')
                    <a href="{{ route('designer.chats') }}" class="px-8 py-3.5 bg-[#2D3E35] text-white rounded-2xl text-[10px] font-black uppercase tracking-widest hover:scale-105 transition-all">Contact Client</a>
                    <button class="px-14 py-3.5 bg-primary text-white rounded-2xl text-[10px] font-black uppercase tracking-[0.2em] shadow-2xl shadow-primary/40 hover:scale-105 transition-all">
                        Upload Initial Concept
                    </button>
                
                @elseif($status == 'revision')
                    <button class="px-8 py-3.5 border border-gray-200 rounded-2xl text-[10px] font-black uppercase tracking-widest text-gray-400 hover:bg-gray-50 transition-all italic">Update Progress</button>
                    <a href="{{ route('designer.chats') }}" class="px-8 py-3.5 bg-[#2D3E35] text-white rounded-2xl text-[10px] font-black uppercase tracking-widest hover:scale-105 transition-all">Contact Client</a>
                    <button class="px-14 py-3.5 bg-primary text-white rounded-2xl text-[10px] font-black uppercase tracking-[0.2em] shadow-2xl shadow-primary/40 hover:scale-105 transition-all">
                        Resubmit Final Work
                    </button>

                @elseif($status == 'finish')
                    <button class="px-8 py-3.5 border border-gray-200 rounded-2xl text-[10px] font-black uppercase text-gray-400 hover:bg-gray-50 italic">Review Client</button>
                    <button class="px-14 py-3.5 bg-gray-900 text-white rounded-2xl text-[10px] font-black uppercase tracking-[0.2em] shadow-xl hover:scale-105 transition-all">
                        Download Master Assets (.zip)
                    </button>
                @endif
            </div>
        </footer>
    </main>

</body>
</html>