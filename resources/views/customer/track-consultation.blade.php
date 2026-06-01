<?php $site_name = "DECOR"; ?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Track Consultation — {{ $site_name }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#B5733A',
                    }
                }
            }
        }
    </script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;600;700;800;900&display=swap');
        body { font-family: 'Plus Jakarta Sans', sans-serif; background-color: #ffffff; }
        .content-container { max-width: 1200px; margin: 0 auto; }
        /* Star rating: Flex row-reverse so clicking star 5 highlights all without RTL text issues */
        .rating-stars label { color: #d1d5db; cursor: pointer; font-size: 2rem; transition: color 0.15s; }
        .rating-stars label:hover,
        .rating-stars label:hover ~ label,
        .rating-stars input[type="radio"]:checked ~ label,
        .rating-stars input[type="radio"]:checked + label { color: #B5733A !important; }
    </style>
</head>
<body class="text-gray-800 flex flex-col min-h-screen">

    @include('customer.partials.navbar')

    <main class="flex-grow flex content-container w-full bg-white">
        @include('customer.partials.sidebar')

        <div class="flex-grow p-12 bg-[#fafafa]/30">
            <div class="max-w-5xl mx-auto">
                <h1 class="text-3xl font-bold tracking-tighter mb-1 text-gray-900">Track Consultations</h1>
                <p class="text-sm text-gray-500 mb-10">Lacak progres dan status konsultasi desain Anda.</p>

                <!-- TABS (Seperti Shopee) -->
                <div class="flex overflow-x-auto border-b border-gray-100 mb-8 pb-px no-scrollbar gap-8">
                    @php 
                        $tabs = [
                            'semua' => 'Semua',
                            'menunggu_pembayaran' => 'Menunggu Pembayaran',
                            'aktif' => 'Aktif / Berjalan',
                            'selesai' => 'Selesai',
                            'dibatalkan' => 'Dibatalkan'
                        ];
                    @endphp
                    @foreach($tabs as $key => $label)
                        <a href="{{ route('customer.track-consultation.list', ['tab' => $key]) }}" 
                           class="pb-4 text-xs font-bold uppercase tracking-widest whitespace-nowrap {{ $tab == $key ? 'text-primary border-b-2 border-primary' : 'text-gray-400 hover:text-gray-600' }}">
                            {{ $label }}
                        </a>
                    @endforeach
                </div>

                @forelse($consultations as $activeConsultation)
                @php 
                    $status = $activeConsultation->status;
                    $isCompleted = $status == \App\Models\Consultation::STATUS_COMPLETED;
                    $isActive = in_array($status, [\App\Models\Consultation::STATUS_ACTIVE, \App\Models\Consultation::STATUS_OFFER_RECEIVED, \App\Models\Consultation::STATUS_UNDER_REVIEW, \App\Models\Consultation::STATUS_REVISION_REQUESTED, \App\Models\Consultation::STATUS_WAITING_FINAL_PAYMENT, \App\Models\Consultation::STATUS_COMPLETED]);
                    $isReview = in_array($status, [\App\Models\Consultation::STATUS_UNDER_REVIEW, \App\Models\Consultation::STATUS_REVISION_REQUESTED, \App\Models\Consultation::STATUS_WAITING_FINAL_PAYMENT, \App\Models\Consultation::STATUS_COMPLETED]);
                    $canChat = !in_array($status, [\App\Models\Consultation::STATUS_WAITING_APPROVAL, \App\Models\Consultation::STATUS_WAITING_CONSULTATION_FEE, \App\Models\Consultation::STATUS_WAITING_BRIEF, \App\Models\Consultation::STATUS_REJECTED]);
                    $existingReview = $isCompleted ? \App\Models\ConsultationReview::where('consultation_id', $activeConsultation->id)->first() : null;
                @endphp

                <!-- CARD TRACKING KONSULTASI -->
                <div class="bg-white rounded-2xl border border-gray-100 shadow-[0_2px_10px_-4px_rgba(0,0,0,0.05)] mb-8 overflow-hidden">
                    
                    <!-- Header Card -->
                    <div class="p-8 border-b border-gray-100 flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
                        <div>
                            <span class="text-[10px] font-black text-primary uppercase tracking-widest">{{ \App\Models\Consultation::getStatusLabel($status) }}</span>
                            <h2 class="text-xl font-bold text-gray-900 mt-1">Konsultasi #{{ $activeConsultation->id }}</h2>
                            <p class="text-xs text-gray-500 mt-1 font-medium">
                                Designer: <span class="font-bold text-gray-900">{{ $activeConsultation->designer->user->full_name }}</span>
                            </p>
                        </div>
                        <div class="flex gap-3 flex-wrap">
                            <a href="{{ route('customer.chat', $activeConsultation->id) }}" 
                               class="px-5 py-2.5 rounded-lg text-xs font-bold tracking-wide transition-colors shadow-sm inline-flex items-center {{ $canChat ? 'bg-[#1a1a1a] text-white hover:bg-black' : 'bg-gray-100 text-gray-400 cursor-not-allowed' }}"
                               @if(!$canChat) onclick="event.preventDefault(); alert('Chat belum tersedia di tahap ini.');" @endif>
                                <i class="fa-solid fa-comment-dots mr-2"></i> Chat Designer
                            </a>
                            @if($isCompleted)
                                @if($existingReview)
                                    <div class="px-5 py-2.5 rounded-lg text-xs font-bold tracking-wide bg-green-50 text-green-700 border border-green-200 inline-flex items-center gap-2">
                                        <i class="fa-solid fa-star text-amber-400"></i>
                                        <span>Sudah Diulas ({{ $existingReview->rating }}/5)</span>
                                    </div>
                                @else
                                    <button onclick="document.getElementById('review-modal-{{ $activeConsultation->id }}').classList.remove('hidden')" 
                                            class="px-5 py-2.5 rounded-lg text-xs font-bold tracking-wide bg-amber-500 hover:bg-amber-600 text-white transition-colors shadow-sm inline-flex items-center gap-2">
                                        <i class="fa-solid fa-star"></i> Beri Ulasan
                                    </button>
                                @endif
                            @endif
                        </div>
                    </div>

                    <!-- Body Card (2 Kolom) -->
                    <div class="p-8 grid grid-cols-1 lg:grid-cols-12 gap-12">
                        
                        <!-- Kolom Kiri: Tracking Timeline -->
                        <div class="lg:col-span-4">
                            <h3 class="text-[10px] font-black text-gray-400 tracking-widest uppercase mb-8">Consultation Status</h3>
                            
                            <div class="relative pl-3 space-y-8">
                                <div class="absolute left-[17px] top-2 bottom-2 w-[2px] {{ $isCompleted ? 'bg-primary' : 'bg-gray-100' }}"></div>

                                <!-- Step 1: Setup -->
                                <div class="relative flex items-start gap-5">
                                    <div class="z-10 w-5 h-5 rounded-full bg-primary flex items-center justify-center ring-[6px] ring-white">
                                        <i class="fa-solid fa-check text-[9px] text-white"></i>
                                    </div>
                                    <div>
                                        <h4 class="font-bold text-sm text-gray-900">Setup</h4>
                                        <p class="text-xs text-gray-400 mt-0.5">Permintaan dibuat</p>
                                    </div>
                                </div>

                                <!-- Step 2: Active -->
                                <div class="relative flex items-start gap-5 {{ !$isActive ? 'opacity-40' : '' }}">
                                    <div class="z-10 w-5 h-5 rounded-full {{ $isActive ? 'bg-primary' : 'bg-gray-200' }} flex items-center justify-center ring-[6px] ring-white">
                                        <i class="fa-solid fa-check text-[9px] {{ $isActive ? 'text-white' : 'text-transparent' }}"></i>
                                    </div>
                                    <div>
                                        <h4 class="font-bold text-sm text-gray-900">Active</h4>
                                        <p class="text-xs text-gray-400 mt-0.5">Sedang berjalan</p>
                                    </div>
                                </div>

                                <!-- Step 3: Review -->
                                <div class="relative flex items-start gap-5 {{ !$isReview ? 'opacity-40' : '' }}">
                                    <div class="z-10 w-5 h-5 rounded-full {{ $isReview ? 'bg-primary' : 'bg-gray-200' }} flex items-center justify-center ring-[6px] ring-white">
                                        <i class="fa-solid fa-check text-[9px] {{ $isReview ? 'text-white' : 'text-transparent' }}"></i>
                                    </div>
                                    <div>
                                        <h4 class="font-bold text-sm text-gray-900">Review</h4>
                                        <p class="text-xs text-gray-400 mt-0.5">Penawaran RAB & Review</p>
                                    </div>
                                </div>

                                <!-- Step 4: Done -->
                                <div class="relative flex items-start gap-5 {{ !$isCompleted ? 'opacity-40' : '' }}">
                                    <div class="z-10 w-5 h-5 rounded-full {{ $isCompleted ? 'bg-primary' : 'bg-gray-100' }} flex items-center justify-center ring-[6px] ring-white">
                                        <i class="fa-solid fa-check text-[9px] {{ $isCompleted ? 'text-white' : 'text-transparent' }}"></i>
                                    </div>
                                    <div>
                                        <h4 class="font-bold text-sm text-gray-900">Done</h4>
                                        <p class="text-xs text-gray-400 mt-0.5">Proyek Selesai</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Kolom Kanan: Actions & Summary -->
                        <div class="lg:col-span-8">
                            <h3 class="text-[10px] font-black text-gray-400 tracking-widest uppercase mb-6">Action & Details</h3>
                            
                            <div class="bg-gray-50 rounded-xl p-6 mb-6">
                                <h4 class="font-bold text-sm text-gray-900">{{ $activeConsultation->title }}</h4>
                                <p class="text-xs text-gray-500 mt-1 line-clamp-2">{{ $activeConsultation->description ?? 'No brief description provided.' }}</p>
                            </div>

                            <!-- ACTIONS BERDASARKAN STATUS -->
                            @if($status == \App\Models\Consultation::STATUS_WAITING_CONSULTATION_FEE)
                                @if($activeConsultation->payment_proof)
                                    <div class="bg-amber-50 text-amber-700 px-6 py-4 rounded-xl border border-amber-200 flex items-center gap-3">
                                        <i class="fa-solid fa-clock animate-pulse"></i>
                                        <span class="text-[10px] font-black uppercase tracking-widest">Bukti Pembayaran Fee Dikirim. Menunggu Verifikasi.</span>
                                    </div>
                                @else
                                    <form action="{{ route('customer.consultation.pay-fee', $activeConsultation->id) }}" method="POST" enctype="multipart/form-data" class="bg-white border border-primary/20 p-6 rounded-xl flex flex-col md:flex-row items-center justify-between gap-4">
                                        @csrf
                                        <div>
                                            <span class="text-[9px] font-bold text-gray-400 uppercase tracking-wider block mb-1">Upload Bukti Transfer Fee</span>
                                            <span class="font-bold text-primary">Rp {{ number_format($activeConsultation->consultation_fee, 0, ',', '.') }}</span>
                                        </div>
                                        <input type="file" name="payment_proof" accept="image/*" required class="text-xs text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-[10px] file:font-black file:uppercase file:bg-primary/10 file:text-primary cursor-pointer">
                                        <button type="submit" class="bg-primary text-white px-6 py-3 rounded-xl text-[10px] font-black uppercase tracking-widest">Kirim Bukti</button>
                                    </form>
                                @endif
                            @endif

                            @if($status == \App\Models\Consultation::STATUS_WAITING_APPROVAL)
                                <div class="bg-orange-50 text-orange-600 px-6 py-4 rounded-xl border border-orange-100 flex items-center gap-3">
                                    <i class="fa-solid fa-hourglass-half animate-pulse"></i>
                                    <span class="text-[10px] font-black uppercase tracking-widest">Menunggu Approval Desainer</span>
                                </div>
                            @endif

                            @if($status == \App\Models\Consultation::STATUS_WAITING_BRIEF)
                                <form action="{{ route('customer.consultation.submit-brief', $activeConsultation->id) }}" method="POST" class="bg-white border border-gray-200 p-6 rounded-xl space-y-4">
                                    @csrf
                                    <div>
                                        <label class="text-[10px] font-black text-gray-600 uppercase tracking-widest block mb-2">Isi Brief Proyek</label>
                                        <textarea name="description" rows="3" placeholder="Detail ruangan, preferensi warna, ukuran..." required class="w-full bg-gray-50 border border-gray-100 rounded-xl px-4 py-3 text-xs outline-none focus:border-primary"></textarea>
                                    </div>
                                    <button type="submit" class="w-full bg-primary text-white py-3 rounded-xl text-[10px] font-black uppercase tracking-widest">Submit Brief</button>
                                </form>
                            @endif

                            @if($status == \App\Models\Consultation::STATUS_OFFER_RECEIVED || $status == \App\Models\Consultation::STATUS_UNDER_REVIEW || $status == \App\Models\Consultation::STATUS_WAITING_FINAL_PAYMENT)
                                @php 
                                    $quote = $activeConsultation->quotes->first(); 
                                    $hasRequestedRevision = $activeConsultation->quotes->where('status', 'revision')->count() > 0;
                                @endphp
                                @if($quote && $quote->status !== 'revision')
                                    <div class="bg-white border-2 border-primary/20 p-6 rounded-xl relative">
                                        <div class="absolute -top-3 -right-3"><span class="text-[9px] font-black uppercase tracking-widest bg-primary text-white px-4 py-2 rounded-full shadow-lg animate-bounce">RAB Baru</span></div>
                                        <h4 class="text-[11px] font-black uppercase tracking-widest text-primary mb-4">Project Agreement & RAB</h4>
                                        
                                        <div class="flex justify-between items-center mb-6 pb-6 border-b border-gray-100">
                                            <div>
                                                <h4 class="text-[10px] font-bold uppercase text-gray-400 tracking-widest">Total Design Fee</h4>
                                                <h3 class="text-2xl font-black italic text-primary mt-1">Rp {{ number_format($quote->amount, 0, ',', '.') }}</h3>
                                            </div>
                                            <div class="flex gap-2 flex-wrap justify-end">
                                                @if($quote->design_image)
                                                    @php 
                                                        $designImages = json_decode($quote->design_image, true);
                                                        if (!is_array($designImages)) $designImages = [$quote->design_image];
                                                        $designImages = array_filter($designImages);
                                                    @endphp
                                                    <a href="{{ route('consultation.download-designs.public', $quote->id) }}" class="flex items-center gap-2 bg-blue-50 text-blue-700 px-4 py-2 rounded-lg hover:bg-blue-100 shadow-sm border border-blue-200">
                                                        <i class="fa-solid fa-images"></i>
                                                        <span class="text-[10px] font-black uppercase tracking-widest">
                                                            Download Desain {{ count($designImages) > 1 ? '(' . count($designImages) . ' gambar)' : '' }}
                                                        </span>
                                                    </a>
                                                @endif
                                                @if($quote->items)
                                                    <a href="{{ route('consultation.download-rab.public', $quote->id) }}" target="_blank" class="flex items-center gap-2 bg-amber-50 text-amber-700 px-4 py-2 rounded-lg hover:bg-amber-100 shadow-sm border border-amber-200">
                                                        <i class="fa-solid fa-file-arrow-down"></i>
                                                        <span class="text-[10px] font-black uppercase tracking-widest">Download RAB</span>
                                                    </a>
                                                @endif
                                            </div>
                                        </div>

                                        @if($status == \App\Models\Consultation::STATUS_OFFER_RECEIVED || $status == \App\Models\Consultation::STATUS_UNDER_REVIEW)
                                            <div class="flex gap-4">
                                                <form action="{{ route('customer.consultation.accept-offer', $activeConsultation->id) }}" method="POST" class="flex-1">
                                                    @csrf
                                                    <button type="submit" class="w-full bg-primary text-white py-3 rounded-lg text-[10px] font-black uppercase tracking-widest shadow-md">Accept Project</button>
                                                </form>
                                                <button type="button" onclick="document.getElementById('rev-{{ $activeConsultation->id }}').classList.toggle('hidden')" class="flex-1 py-3 rounded-lg border border-gray-300 text-[10px] font-black text-gray-500 uppercase tracking-widest hover:bg-gray-50">Request Revision</button>
                                            </div>
                                            
                                            <div id="rev-{{ $activeConsultation->id }}" class="hidden mt-4 bg-gray-50 p-4 rounded-xl">
                                                <form action="{{ route('customer.consultation.request-revision', $activeConsultation->id) }}" method="POST">
                                                    @csrf
                                                    <textarea name="revision_notes" rows="2" required placeholder="Tulis catatan revisi..." class="w-full bg-white border border-gray-200 rounded-lg p-3 text-xs mb-3 outline-none"></textarea>
                                                    <button type="submit" class="w-full bg-gray-800 text-white py-3 rounded-lg text-[10px] font-black uppercase tracking-widest">Kirim Revisi</button>
                                                </form>
                                            </div>
                                        @else
                                            @if($activeConsultation->payment_proof)
                                                <div class="bg-amber-50 text-amber-600 px-6 py-4 rounded-xl border border-amber-100 text-center">
                                                    <span class="text-[10px] font-black uppercase tracking-widest">Bukti Pembayaran Proyek Dikirim. Menunggu Verifikasi.</span>
                                                </div>
                                            @else
                                                <form action="{{ route('customer.consultation.pay-final', $activeConsultation->id) }}" method="POST" enctype="multipart/form-data" class="w-full flex flex-col gap-3">
                                                    @csrf
                                                    <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Upload Bukti Pembayaran Akhir</label>
                                                    <div class="flex gap-4">
                                                        <input type="file" name="payment_proof" accept="image/*" required class="flex-1 text-xs text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-[10px] file:font-black file:uppercase file:bg-green-500/10 file:text-green-600 cursor-pointer">
                                                        <button type="submit" class="bg-green-500 text-white px-6 py-3 rounded-lg text-[10px] font-black uppercase tracking-widest">Kirim</button>
                                                    </div>
                                                </form>
                                            @endif
                                        @endif
                                    </div>
                                @endif
                            @endif
                        </div>
                    </div>
                </div>
                @if($isCompleted && !$existingReview)
                <!-- REVIEW MODAL -->
                <div id="review-modal-{{ $activeConsultation->id }}" class="hidden fixed inset-0 bg-black/60 backdrop-blur-sm z-[100] flex items-center justify-center p-6">
                    <div class="bg-white rounded-3xl w-full max-w-lg shadow-2xl overflow-hidden">
                        <!-- Modal Header -->
                        <div class="bg-primary px-8 py-6 flex justify-between items-center">
                            <div>
                                <h3 class="text-lg font-black text-white">Ulasan Proyek</h3>
                                <p class="text-[10px] text-white/80 font-bold uppercase tracking-widest mt-0.5">{{ $activeConsultation->title }}</p>
                            </div>
                            <button onclick="document.getElementById('review-modal-{{ $activeConsultation->id }}').classList.add('hidden')" 
                                    class="w-8 h-8 rounded-full bg-white/20 hover:bg-white/30 flex items-center justify-center text-white transition-colors">
                                <i class="fa-solid fa-xmark"></i>
                            </button>
                        </div>

                        <!-- Designer Info -->
                        <div class="px-8 py-4 bg-gray-50 border-b border-gray-100 flex items-center gap-4">
                            <img src="{{ $activeConsultation->designer->designer_image ? asset('storage/'.$activeConsultation->designer->designer_image) : 'https://ui-avatars.com/api/?name='.urlencode($activeConsultation->designer->user->full_name) }}" 
                                 class="w-10 h-10 rounded-xl object-cover border border-gray-200">
                            <div>
                                <p class="text-xs font-black text-gray-900">{{ $activeConsultation->designer->user->full_name }}</p>
                                <p class="text-[10px] text-primary font-bold uppercase tracking-widest">{{ $activeConsultation->designer->specialty ?? 'Interior Designer' }}</p>
                            </div>
                        </div>

                        <form action="{{ route('customer.consultation.review.submit', $activeConsultation->id) }}" method="POST" class="px-8 py-6 space-y-6">
                            @csrf

                            <!-- Star Rating -->
                            <div class="text-center space-y-2">
                                <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest block">Rating Desainer</label>
                                <div class="rating-stars text-4xl justify-center flex-row-reverse" style="display:inline-flex; gap:4px;">
                                    <input type="radio" id="r5-{{ $activeConsultation->id }}" name="rating" value="5" required class="hidden">
                                    <label for="r5-{{ $activeConsultation->id }}" class="fa-solid fa-star cursor-pointer text-gray-300 hover:text-primary transition-colors peer-checked:text-primary"></label>
                                    <input type="radio" id="r4-{{ $activeConsultation->id }}" name="rating" value="4" class="hidden">
                                    <label for="r4-{{ $activeConsultation->id }}" class="fa-solid fa-star cursor-pointer text-gray-300 hover:text-primary transition-colors"></label>
                                    <input type="radio" id="r3-{{ $activeConsultation->id }}" name="rating" value="3" class="hidden">
                                    <label for="r3-{{ $activeConsultation->id }}" class="fa-solid fa-star cursor-pointer text-gray-300 hover:text-primary transition-colors"></label>
                                    <input type="radio" id="r2-{{ $activeConsultation->id }}" name="rating" value="2" class="hidden">
                                    <label for="r2-{{ $activeConsultation->id }}" class="fa-solid fa-star cursor-pointer text-gray-300 hover:text-primary transition-colors"></label>
                                    <input type="radio" id="r1-{{ $activeConsultation->id }}" name="rating" value="1" class="hidden">
                                    <label for="r1-{{ $activeConsultation->id }}" class="fa-solid fa-star cursor-pointer text-gray-300 hover:text-primary transition-colors"></label>
                                </div>
                            </div>

                            <!-- Project Duration -->
                            <div class="space-y-2">
                                <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest block">Berapa Lama Proyek Selesai?</label>
                                <select name="project_duration" required 
                                        class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-xs font-bold text-gray-700 outline-none focus:border-primary transition-colors">
                                    <option value="" disabled selected>Pilih estimasi durasi...</option>
                                    <option value="1-3 Months">1-3 Months</option>
                                    <option value="1-6 Months">1-6 Months</option>
                                    <option value="1-9 Months">1-9 Months</option>
                                    <option value="1-12 Months">1-12 Months</option>
                                </select>
                            </div>

                            <!-- Comment -->
                            <div class="space-y-2">
                                <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest block">Ulasan (Opsional)</label>
                                <textarea name="comment" rows="4" 
                                          placeholder="Ceritakan pengalaman Anda bekerja dengan desainer ini..."
                                          class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-xs outline-none focus:border-primary transition-colors resize-none"></textarea>
                            </div>

                            <!-- Actions -->
                            <div class="flex gap-3 pt-2">
                                <button type="button" 
                                        onclick="document.getElementById('review-modal-{{ $activeConsultation->id }}').classList.add('hidden')"
                                        class="flex-1 py-3 rounded-xl border border-gray-200 text-xs font-black text-gray-500 uppercase tracking-widest hover:bg-gray-50 transition-colors">
                                    Batal
                                </button>
                                <button type="submit" 
                                        class="flex-1 py-3 rounded-xl bg-primary hover:bg-primary/90 text-white text-xs font-black uppercase tracking-widest transition-colors shadow-lg shadow-primary/20">
                                    <i class="fa-solid fa-paper-plane mr-2"></i>Kirim Ulasan
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
                @endif

                @empty
                <div class="bg-white rounded-2xl border border-gray-100 p-12 text-center shadow-sm">
                    <p class="text-gray-500">Belum ada data konsultasi di kategori ini.</p>
                </div>
                @endforelse
            </div>
        </div>
    </main>

    @if(session('show_rab_alert'))
    <div id="rabAlert" class="fixed bottom-6 right-6 bg-white border border-gray-100 shadow-2xl rounded-2xl p-6 z-50 animate-bounce max-w-sm">
        <div class="flex items-start gap-4">
            <div class="w-12 h-12 bg-primary/10 text-primary rounded-xl flex items-center justify-center shrink-0">
                <i class="fa-solid fa-file-invoice-dollar text-xl"></i>
            </div>
            <div>
                <h4 class="font-bold text-gray-900">RAB Baru Diterima!</h4>
                <p class="text-xs text-gray-500 mt-1">Desainer telah mengirimkan penawaran RAB untuk proyek Anda. Silakan cek di tab "Aktif".</p>
                <button onclick="document.getElementById('rabAlert').remove()" class="text-[10px] font-black uppercase tracking-widest text-primary mt-3 hover:underline">Tutup</button>
            </div>
        </div>
    </div>
    @endif

</body>
</html>