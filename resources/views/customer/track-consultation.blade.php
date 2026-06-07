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
                <div class="bg-white rounded-2xl border border-gray-100 shadow-[0_2px_10px_-4px_rgba(0,0,0,0.05)] mb-8 p-8">
                    
                    <!-- Header Card -->
                    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-8">
                        <div>
                            <span class="inline-block px-3 py-1 bg-[#FDF8F4] text-[#B5733A] text-[10px] font-black uppercase tracking-widest rounded mb-3">{{ \App\Models\Consultation::getStatusLabel($status) }}</span>
                            <h2 class="text-2xl font-bold text-gray-900">{{ $activeConsultation->title }}</h2>
                            <p class="text-sm text-gray-500 mt-1">
                                Desainer: <span class="text-gray-900 font-medium">{{ $activeConsultation->designer->user->full_name }}</span>
                            </p>
                            @if($activeConsultation->description)
                                <p class="text-xs text-gray-500 mt-2 line-clamp-2 max-w-2xl">{{ $activeConsultation->description }}</p>
                            @endif
                        </div>
                        <div class="flex gap-3 flex-wrap shrink-0">
                            <a href="{{ route('customer.chat', $activeConsultation->id) }}" 
                               class="px-6 py-3 rounded-xl text-sm font-bold tracking-wide transition-colors inline-flex items-center {{ $canChat ? 'bg-[#B5733A] text-white hover:bg-[#9a6130]' : 'bg-gray-50 border border-gray-100 text-gray-400 cursor-not-allowed' }}"
                               @if(!$canChat) onclick="event.preventDefault(); alert('Chat belum tersedia di tahap ini.');" @endif>
                                Buka Obrolan
                            </a>
                            @if($isCompleted)
                                @if($existingReview)
                                    <div class="px-5 py-3 rounded-xl text-sm font-bold tracking-wide bg-gray-50 text-gray-600 border border-gray-200 inline-flex items-center gap-2">
                                        <i class="fa-solid fa-star text-[#B5733A]"></i>
                                        <span>Sudah Diulas ({{ $existingReview->rating }}/5)</span>
                                    </div>
                                @else
                                    <button onclick="document.getElementById('review-modal-{{ $activeConsultation->id }}').classList.remove('hidden')" 
                                            class="px-5 py-3 rounded-xl text-sm font-bold tracking-wide border border-[#B5733A] text-[#B5733A] hover:bg-[#B5733A] hover:text-white transition-colors inline-flex items-center gap-2">
                                        <i class="fa-solid fa-star"></i> Beri Ulasan
                                    </button>
                                @endif
                            @endif
                        </div>
                    </div>

                    @php
                        // Determine 6 steps based on current status for timeline UI
                        $st = $activeConsultation->status;
                        $stepActive = 1;
                        if ($st == \App\Models\Consultation::STATUS_COMPLETED) $stepActive = 6;
                        elseif ($st == \App\Models\Consultation::STATUS_WAITING_FINAL_PAYMENT) $stepActive = 5;
                        elseif (in_array($st, [\App\Models\Consultation::STATUS_OFFER_RECEIVED, \App\Models\Consultation::STATUS_UNDER_REVIEW, \App\Models\Consultation::STATUS_REVISION_REQUESTED])) $stepActive = 4;
                        elseif ($st == \App\Models\Consultation::STATUS_ACTIVE) $stepActive = 3;
                        elseif ($st == \App\Models\Consultation::STATUS_WAITING_BRIEF) $stepActive = 2;
                        
                        $getProgressWidth = function($active) {
                            if($active == 1) return '0%';
                            if($active == 2) return '20%';
                            if($active == 3) return '40%';
                            if($active == 4) return '60%';
                            if($active == 5) return '80%';
                            return '100%';
                        };
                    @endphp

                    <!-- Horizontal Timeline -->
                    <div class="border border-gray-100 rounded-2xl p-6 md:p-8 mb-8 relative overflow-hidden">
                        <!-- Line backgrounds -->
                        <div class="hidden md:block absolute left-[8%] right-[8%] top-[3.25rem] h-[2px] bg-gray-200 z-0"></div>
                        <div class="hidden md:block absolute left-[8%] top-[3.25rem] h-[2px] bg-[#B5733A] z-0 transition-all duration-500" style="width: {{ $getProgressWidth($stepActive) }};"></div>
                        
                        <div class="flex flex-col md:flex-row justify-between items-start md:items-center relative z-10 gap-8 md:gap-0">
                            <!-- Step 1: Setup -->
                            <div class="flex flex-col items-center bg-white px-2 w-full md:w-auto {{ $stepActive < 1 ? 'opacity-40' : '' }}">
                                <div class="w-12 h-12 rounded-full {{ $stepActive > 1 ? 'bg-[#B5733A] text-white' : ($stepActive == 1 ? 'bg-white border-2 border-[#B5733A] text-[#B5733A]' : 'bg-white text-gray-300 border-2 border-gray-200') }} flex items-center justify-center ring-[10px] ring-white transition-colors">
                                    @if($stepActive > 1)
                                        <i class="fa-solid fa-check text-sm"></i>
                                    @else
                                        <span class="text-sm font-bold">1</span>
                                    @endif
                                </div>
                                <h4 class="font-bold text-sm {{ $stepActive == 1 ? 'text-[#B5733A]' : 'text-gray-900' }} mt-4">Setup</h4>
                                <p class="text-[11px] {{ $stepActive == 1 ? 'text-[#B5733A]' : 'text-gray-400' }} mt-1 text-center">{{ $stepActive > 1 ? 'Selesai' : ($stepActive == 1 ? 'Aktif' : 'Menunggu') }}</p>
                            </div>

                            <!-- Step 2: Briefing -->
                            <div class="flex flex-col items-center bg-white px-2 w-full md:w-auto {{ $stepActive < 2 ? 'opacity-40' : '' }}">
                                <div class="w-12 h-12 rounded-full {{ $stepActive > 2 ? 'bg-[#B5733A] text-white' : ($stepActive == 2 ? 'bg-white border-2 border-[#B5733A] text-[#B5733A]' : 'bg-white text-gray-300 border-2 border-gray-200') }} flex items-center justify-center ring-[10px] ring-white transition-colors">
                                    @if($stepActive > 2)
                                        <i class="fa-solid fa-check text-sm"></i>
                                    @else
                                        <span class="text-sm font-bold">2</span>
                                    @endif
                                </div>
                                <h4 class="font-bold text-sm {{ $stepActive == 2 ? 'text-[#B5733A]' : 'text-gray-900' }} mt-4">Briefing</h4>
                                <p class="text-[11px] {{ $stepActive == 2 ? 'text-[#B5733A]' : 'text-gray-400' }} mt-1 text-center">{{ $stepActive > 2 ? 'Selesai' : ($stepActive == 2 ? 'Aktif' : 'Menunggu') }}</p>
                            </div>

                            <!-- Step 3: Drafting -->
                            <div class="flex flex-col items-center bg-white px-2 w-full md:w-auto {{ $stepActive < 3 ? 'opacity-40' : '' }}">
                                <div class="w-12 h-12 rounded-full {{ $stepActive > 3 ? 'bg-[#B5733A] text-white' : ($stepActive == 3 ? 'bg-white border-2 border-[#B5733A] text-[#B5733A]' : 'bg-white text-gray-300 border-2 border-gray-200') }} flex items-center justify-center ring-[10px] ring-white transition-colors">
                                    @if($stepActive > 3)
                                        <i class="fa-solid fa-check text-sm"></i>
                                    @else
                                        <span class="text-sm font-bold">3</span>
                                    @endif
                                </div>
                                <h4 class="font-bold text-sm {{ $stepActive == 3 ? 'text-[#B5733A]' : 'text-gray-900' }} mt-4">Drafting</h4>
                                <p class="text-[11px] {{ $stepActive == 3 ? 'text-[#B5733A]' : 'text-gray-400' }} mt-1 text-center">{{ $stepActive > 3 ? 'Selesai' : ($stepActive == 3 ? 'Aktif' : 'Menunggu') }}</p>
                            </div>

                            <!-- Step 4: Review RAB -->
                            <div class="flex flex-col items-center bg-white px-2 w-full md:w-auto {{ $stepActive < 4 ? 'opacity-40' : '' }}">
                                <div class="w-12 h-12 rounded-full {{ $stepActive > 4 ? 'bg-[#B5733A] text-white' : ($stepActive == 4 ? 'bg-white border-2 border-[#B5733A] text-[#B5733A]' : 'bg-white text-gray-300 border-2 border-gray-200') }} flex items-center justify-center ring-[10px] ring-white transition-colors">
                                    @if($stepActive > 4)
                                        <i class="fa-solid fa-check text-sm"></i>
                                    @else
                                        <span class="text-sm font-bold">4</span>
                                    @endif
                                </div>
                                <h4 class="font-bold text-sm {{ $stepActive == 4 ? 'text-[#B5733A]' : 'text-gray-900' }} mt-4">Review RAB</h4>
                                <p class="text-[11px] {{ $stepActive == 4 ? 'text-[#B5733A]' : 'text-gray-400' }} mt-1 text-center">{{ $stepActive > 4 ? 'Selesai' : ($stepActive == 4 ? 'Aktif' : 'Menunggu') }}</p>
                            </div>

                            <!-- Step 5: Pembayaran Akhir -->
                            <div class="flex flex-col items-center bg-white px-2 w-full md:w-auto {{ $stepActive < 5 ? 'opacity-40' : '' }}">
                                <div class="w-12 h-12 rounded-full {{ $stepActive > 5 ? 'bg-[#B5733A] text-white' : ($stepActive == 5 ? 'bg-white border-2 border-[#B5733A] text-[#B5733A]' : 'bg-white text-gray-300 border-2 border-gray-200') }} flex items-center justify-center ring-[10px] ring-white transition-colors">
                                    @if($stepActive > 5)
                                        <i class="fa-solid fa-check text-sm"></i>
                                    @else
                                        <span class="text-sm font-bold">5</span>
                                    @endif
                                </div>
                                <h4 class="font-bold text-sm {{ $stepActive == 5 ? 'text-[#B5733A]' : 'text-gray-900' }} mt-4">Pembayaran Akhir</h4>
                                <p class="text-[11px] {{ $stepActive == 5 ? 'text-[#B5733A]' : 'text-gray-400' }} mt-1 text-center">{{ $stepActive > 5 ? 'Selesai' : ($stepActive == 5 ? 'Aktif' : 'Menunggu') }}</p>
                            </div>

                            <!-- Step 6: Selesai -->
                            <div class="flex flex-col items-center bg-white px-2 w-full md:w-auto {{ $stepActive < 6 ? 'opacity-40' : '' }}">
                                <div class="w-12 h-12 rounded-full {{ $stepActive == 6 ? 'bg-[#B5733A] text-white' : 'bg-white text-gray-300 border-2 border-gray-200' }} flex items-center justify-center ring-[10px] ring-white transition-colors">
                                    @if($stepActive == 6)
                                        <i class="fa-solid fa-check text-sm text-white"></i>
                                    @else
                                        <span class="text-sm font-bold">6</span>
                                    @endif
                                </div>
                                <h4 class="font-bold text-sm {{ $stepActive == 6 ? 'text-[#B5733A]' : 'text-gray-900' }} mt-4">Selesai</h4>
                                <p class="text-[11px] {{ $stepActive == 6 ? 'text-[#B5733A]' : 'text-gray-400' }} mt-1 text-center">{{ $stepActive == 6 ? 'Selesai' : 'Menunggu' }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Bottom Actions Area -->
                    <div class="border border-gray-100 rounded-2xl p-6 md:p-8 bg-white">
                        @if($status == \App\Models\Consultation::STATUS_WAITING_CONSULTATION_FEE)
                            @if($activeConsultation->payment_proof)
                                <div class="bg-gray-50 text-gray-700 px-6 py-4 rounded-xl border border-gray-200 flex items-center gap-3">
                                    <i class="fa-solid fa-clock"></i>
                                    <span class="text-sm font-bold tracking-wide">Bukti Pembayaran Fee Dikirim. Menunggu Verifikasi.</span>
                                </div>
                            @else
                                <form action="{{ route('customer.consultation.pay-fee', $activeConsultation->id) }}" method="POST" enctype="multipart/form-data" class="flex flex-col md:flex-row items-center justify-between gap-6">
                                    @csrf
                                    <div>
                                        <span class="text-[10px] font-bold text-gray-400 uppercase tracking-widest block mb-1">Total Fee</span>
                                        <span class="text-2xl font-black text-[#B5733A]">Rp {{ number_format($activeConsultation->consultation_fee, 0, ',', '.') }}</span>
                                    </div>
                                    <div class="flex flex-col md:flex-row gap-4 w-full md:w-auto items-center">
                                        <input type="file" name="payment_proof" accept="image/*" required class="text-xs text-gray-500 file:mr-4 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:text-[10px] file:font-black file:uppercase file:bg-gray-100 file:text-gray-600 hover:file:bg-gray-200 cursor-pointer transition-colors w-full md:w-auto">
                                        <button type="submit" class="w-full md:w-auto bg-[#B5733A] hover:bg-[#9a6130] transition-colors text-white px-8 py-3.5 rounded-xl text-sm font-bold tracking-wide">Kirim Bukti</button>
                                    </div>
                                </form>
                            @endif
                        @endif

                        @if($status == \App\Models\Consultation::STATUS_WAITING_APPROVAL)
                            <div class="bg-gray-50 text-gray-600 px-6 py-4 rounded-xl border border-gray-200 flex items-center gap-3">
                                <i class="fa-solid fa-hourglass-half"></i>
                                <span class="text-sm font-bold tracking-wide">Menunggu Approval Desainer</span>
                            </div>
                        @endif

                        @if($status == \App\Models\Consultation::STATUS_WAITING_BRIEF)
                            <form action="{{ route('customer.consultation.submit-brief', $activeConsultation->id) }}" method="POST" class="space-y-4">
                                @csrf
                                <div>
                                    <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest block mb-2">Isi Brief Proyek</label>
                                    <textarea name="description" rows="3" placeholder="Detail ruangan, preferensi warna, ukuran..." required class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-sm outline-none focus:border-[#B5733A] transition-colors"></textarea>
                                </div>
                                <button type="submit" class="w-full bg-[#B5733A] hover:bg-[#9a6130] transition-colors text-white py-3.5 rounded-xl text-sm font-bold tracking-wide">Submit Brief</button>
                            </form>
                        @endif

                        @if($status == \App\Models\Consultation::STATUS_OFFER_RECEIVED || $status == \App\Models\Consultation::STATUS_UNDER_REVIEW || $status == \App\Models\Consultation::STATUS_WAITING_FINAL_PAYMENT || $status == \App\Models\Consultation::STATUS_COMPLETED || $status == \App\Models\Consultation::STATUS_REVISION_REQUESTED)
                            @php 
                                $quote = $activeConsultation->quotes->first(); 
                                $hasRequestedRevision = $activeConsultation->quotes->where('status', 'revision')->count() > 0;
                            @endphp
                            @if($quote && $quote->status !== 'revision')
                                <div class="flex flex-col gap-8 relative">
                                    @if($status == \App\Models\Consultation::STATUS_OFFER_RECEIVED)
                                        <div class="absolute -top-12 -right-4"><span class="text-[9px] font-black uppercase tracking-widest bg-red-500 text-white px-3 py-1.5 rounded-full shadow-sm">Baru</span></div>
                                    @endif
                                    
                                    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-6">
                                        <div>
                                            <h4 class="text-[10px] font-bold uppercase text-gray-500 tracking-widest mb-1">Total Anggaran Jasa</h4>
                                            <h3 class="text-3xl font-black text-[#B5733A]">Rp {{ number_format($quote->amount, 0, ',', '.') }}</h3>
                                        </div>
                                        <div class="flex gap-3 flex-wrap">
                                            @if($quote->items)
                                                @php
                                                    $rabData = is_string($quote->items) ? json_decode($quote->items, true) : $quote->items;
                                                    $rabExt = 'xlsx';
                                                    if (is_array($rabData) && !empty($rabData['file_name'])) {
                                                        $rabExt = pathinfo($rabData['file_name'], PATHINFO_EXTENSION);
                                                    } elseif (is_array($rabData) && !empty($rabData['file_path'])) {
                                                        $rabExt = pathinfo($rabData['file_path'], PATHINFO_EXTENSION);
                                                    }
                                                @endphp
                                                <a href="{{ route('consultation.download-rab.public', $quote->id) }}" target="_blank" class="flex items-center gap-2 bg-white text-gray-700 px-5 py-2.5 rounded-xl hover:border-[#B5733A] hover:text-[#B5733A] transition-colors border border-gray-200 text-xs font-bold shadow-sm">
                                                    <i class="fa-solid fa-upload text-green-600"></i>
                                                    <span>RAB.{{ strtolower($rabExt ?: 'xlsx') }}</span>
                                                </a>
                                            @endif
                                            @if($quote->design_image)
                                                @php 
                                                    $designImages = json_decode($quote->design_image, true);
                                                    if (!is_array($designImages)) $designImages = [$quote->design_image];
                                                    $designImages = array_filter($designImages);
                                                @endphp
                                                <a href="{{ route('consultation.download-designs.public', $quote->id) }}" class="flex items-center gap-2 bg-white text-gray-700 px-5 py-2.5 rounded-xl hover:border-[#B5733A] hover:text-[#B5733A] transition-colors border border-gray-200 text-xs font-bold shadow-sm">
                                                    <i class="fa-solid fa-image text-blue-600"></i>
                                                    <span>Desain</span>
                                                </a>
                                            @endif
                                        </div>
                                    </div>

                                    @if($status == \App\Models\Consultation::STATUS_OFFER_RECEIVED || $status == \App\Models\Consultation::STATUS_UNDER_REVIEW)
                                        <div class="flex flex-col md:flex-row gap-4">
                                            <form action="{{ route('customer.consultation.accept-offer', $activeConsultation->id) }}" method="POST" class="flex-1">
                                                @csrf
                                                <button type="submit" class="w-full bg-[#B5733A] text-white py-4 rounded-xl text-sm font-bold transition-colors hover:bg-[#9a6130] shadow-sm">Setujui Proposal & Bayar</button>
                                            </form>
                                            <button type="button" onclick="document.getElementById('rev-{{ $activeConsultation->id }}').classList.toggle('hidden')" class="flex-1 py-4 rounded-xl border border-gray-200 text-sm font-bold text-gray-700 hover:bg-gray-50 transition-colors shadow-sm">Minta Revisi</button>
                                        </div>
                                        
                                        <div id="rev-{{ $activeConsultation->id }}" class="hidden mt-2 bg-gray-50 p-5 rounded-2xl border border-gray-200">
                                            <form action="{{ route('customer.consultation.request-revision', $activeConsultation->id) }}" method="POST">
                                                @csrf
                                                <textarea name="revision_notes" rows="3" required placeholder="Tulis catatan revisi untuk desainer..." class="w-full bg-white border border-gray-200 rounded-xl p-4 text-sm mb-4 outline-none focus:border-[#B5733A] transition-colors"></textarea>
                                                <button type="submit" class="w-full bg-[#B5733A] text-white hover:bg-[#9a6130] transition-colors py-3.5 rounded-xl text-sm font-bold">Kirim Revisi</button>
                                            </form>
                                        </div>
                                    @elseif($status == \App\Models\Consultation::STATUS_WAITING_FINAL_PAYMENT)
                                        @if($activeConsultation->payment_proof)
                                            <div class="bg-gray-50 text-gray-600 px-6 py-4 rounded-xl border border-gray-200 text-center">
                                                <span class="text-sm font-bold tracking-wide">Bukti Pembayaran Proyek Dikirim. Menunggu Verifikasi.</span>
                                            </div>
                                        @else
                                            <form action="{{ route('customer.consultation.pay-final', $activeConsultation->id) }}" method="POST" enctype="multipart/form-data" class="w-full flex flex-col gap-3 mt-4">
                                                @csrf
                                                <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Upload Bukti Pembayaran Akhir</label>
                                                <div class="flex gap-4">
                                                    <input type="file" name="payment_proof" accept="image/*" required class="flex-1 text-xs text-gray-500 file:mr-4 file:py-3 file:px-5 file:rounded-xl file:border-0 file:text-[10px] file:font-black file:uppercase file:bg-gray-100 file:text-gray-600 hover:file:bg-gray-200 cursor-pointer transition-colors">
                                                    <button type="submit" class="bg-[#B5733A] hover:bg-[#9a6130] transition-colors text-white px-8 py-3 rounded-xl text-sm font-bold">Kirim Bukti</button>
                                                </div>
                                            </form>
                                        @endif
                                    @elseif($status == \App\Models\Consultation::STATUS_REVISION_REQUESTED)
                                        <div class="bg-gray-50 text-gray-600 px-6 py-4 rounded-xl border border-gray-200 text-center">
                                            <span class="text-sm font-bold tracking-wide">Revisi Telah Diajukan. Menunggu RAB Baru dari Desainer.</span>
                                        </div>
                                    @endif
                                </div>
                            @elseif($status == \App\Models\Consultation::STATUS_ACTIVE)
                                <div class="bg-gray-50 text-gray-600 px-6 py-4 rounded-xl border border-gray-200 flex items-center justify-center gap-3">
                                    <i class="fa-solid fa-compass-drafting text-lg"></i>
                                    <span class="text-sm font-bold tracking-wide">Desainer sedang mengerjakan drafting & penawaran...</span>
                                </div>
                            @endif
                        @endif
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