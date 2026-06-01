@php
    $site_name = "DECOR"; 
    $avatar_url = Auth::user()->avatar_url;
@endphp
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Return Request — {{ $site_name }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#B5733A',
                        secondary: '#E3DCD6',
                    }
                }
            }
        }
    </script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;600;700&display=swap');
        body { font-family: 'Plus Jakarta Sans', sans-serif; background-color: #ffffff; }
        .content-container { max-width: 1200px; margin: 0 auto; }
    </style>
</head>
<body class="text-gray-800 flex flex-col min-h-screen">

    @include('customer.partials.navbar')

    <main class="flex-grow flex content-container w-full bg-white">
        <!-- ASIDE: SIDEBAR NAVIGATION -->
        @include('customer.partials.sidebar')

        <!-- CONTENT AREA -->
        <div class="flex-grow p-12">
            <div class="max-w-3xl">
                <header class="mb-12">
                    <h1 class="text-4xl font-bold tracking-tighter mb-2 text-gray-900">Return Request</h1>
                    <p class="text-[11px] text-gray-400 uppercase tracking-[0.2em] font-bold">Manage your product returns and refunds.</p>
                </header>

                @if(session('success'))
                <div class="mb-8 p-4 bg-green-50 border border-green-100 rounded-2xl flex items-center gap-4 text-green-600 animate-pulse">
                    <i class="fa-solid fa-circle-check"></i>
                    <p class="text-xs font-bold uppercase tracking-widest">{{ session('success') }}</p>
                </div>
                @endif

                @if($errors->any())
                <div class="mb-8 p-6 bg-red-50 border border-red-100 rounded-2xl space-y-2">
                    <div class="flex items-center gap-4 text-red-600 mb-2">
                        <i class="fa-solid fa-triangle-exclamation"></i>
                        <p class="text-xs font-black uppercase tracking-widest">Submission Error</p>
                    </div>
                    <ul class="list-disc list-inside text-[10px] text-red-500 font-bold space-y-1">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                @endif

                @if($order)
                <form action="{{ route('customer.return-request.submit', $order->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <section class="mb-12">
                        <h3 class="text-[10px] font-black uppercase tracking-widest text-primary border-b border-primary/10 pb-2 mb-6">Select Order</h3>
                        <div class="p-6 bg-gray-50/50 rounded-2xl border border-gray-100 flex items-center gap-6">
                            <div class="w-20 h-20 bg-white rounded-xl overflow-hidden border border-gray-100 shrink-0 flex items-center justify-center">
                                <i class="fa-solid fa-box text-3xl text-gray-300"></i>
                            </div>
                            <div class="flex-grow">
                                <p class="text-[10px] text-gray-400 uppercase font-bold tracking-widest">Order #DEC-{{ $order->id }}</p>
                                <h4 class="font-bold text-sm">{{ $order->orderItems->count() }} items from this order</h4>
                                <p class="text-[10px] text-gray-500">Delivered via {{ $order->shipping_courier }}</p>
                            </div>
                            <div class="text-right">
                                <p class="text-sm font-bold text-primary">Rp {{ number_format($order->total_price, 0, ',', '.') }}</p>
                            </div>
                        </div>
                    </section>

                    <section class="space-y-8">
                        <div class="space-y-6">
                            <h3 class="text-[10px] font-black uppercase tracking-widest text-primary border-b border-primary/10 pb-2">Return Details</h3>
                            
                            <div class="grid grid-cols-1 gap-6">
                                <div class="space-y-2">
                                    <label class="text-[10px] font-bold uppercase text-gray-400 tracking-widest">Jenis Return</label>
                                    <div class="grid grid-cols-2 gap-0 rounded-xl p-1 bg-gray-100/80 border border-gray-200/30 w-full">
                                        <label class="cursor-pointer select-none">
                                            <input type="radio" name="return_type" value="refund" class="sr-only peer" required onchange="toggleBankInput(this.value)">
                                            <div class="py-3.5 text-xs font-bold rounded-lg text-gray-500 peer-checked:bg-white peer-checked:text-primary peer-checked:shadow-sm hover:text-gray-900 transition-all duration-200 text-center flex items-center justify-center gap-2">
                                                <i class="fa-solid fa-coins text-[10px]"></i> Refund
                                            </div>
                                        </label>
                                        <label class="cursor-pointer select-none">
                                            <input type="radio" name="return_type" value="exchange" class="sr-only peer" onchange="toggleBankInput(this.value)">
                                            <div class="py-3.5 text-xs font-bold rounded-lg text-gray-500 peer-checked:bg-white peer-checked:text-primary peer-checked:shadow-sm hover:text-gray-900 transition-all duration-200 text-center flex items-center justify-center gap-2">
                                                <i class="fa-solid fa-rotate text-[10px]"></i> Ganti Barang Baru
                                            </div>
                                        </label>
                                    </div>
                                </div>

                                <div id="bank_account_wrapper" class="hidden space-y-2">
                                    <label class="text-[10px] font-bold uppercase text-gray-400 tracking-widest">Nomor Rekening</label>
                                    <input type="text" name="bank_account_number" placeholder="Masukkan nomor rekening Anda..." class="w-full bg-gray-50 border border-gray-100 rounded-xl px-4 py-3 text-xs outline-none focus:border-primary transition-all">
                                    <p class="text-[9px] text-gray-400 italic mt-1">*Refund akan diproses dalam 3-5 hari kerja setelah return disetujui.</p>
                                </div>

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div class="space-y-2">
                                        <label class="text-[10px] font-bold uppercase text-gray-400 tracking-widest">Bukti Video (Wajib)</label>
                                        <input type="file" name="video_proof" accept="video/*" required class="w-full bg-gray-50 border border-gray-100 rounded-xl px-4 py-3 text-xs outline-none focus:border-primary transition-all file:mr-4 file:py-1 file:px-4 file:rounded-full file:border-0 file:text-[10px] file:font-bold file:bg-primary/10 file:text-primary hover:file:bg-primary/20">
                                    </div>
                                    <div class="space-y-2">
                                        <label class="text-[10px] font-bold uppercase text-gray-400 tracking-widest">Bukti Foto (Wajib)</label>
                                        <input type="file" name="photo_proof" accept="image/*" required class="w-full bg-gray-50 border border-gray-100 rounded-xl px-4 py-3 text-xs outline-none focus:border-primary transition-all file:mr-4 file:py-1 file:px-4 file:rounded-full file:border-0 file:text-[10px] file:font-bold file:bg-primary/10 file:text-primary hover:file:bg-primary/20">
                                    </div>
                                </div>

                                <div class="space-y-2">
                                    <label class="text-[10px] font-bold uppercase text-gray-400 tracking-widest">Reason for Return</label>
                                    <select name="reason" required class="w-full bg-gray-50 border border-gray-100 rounded-xl px-4 py-3 text-xs outline-none focus:border-primary transition-all">
                                        <option value="">Select a reason...</option>
                                        <option value="Damaged upon arrival">Damaged upon arrival</option>
                                        <option value="Different from description">Different from description</option>
                                        <option value="Quality not as expected">Quality not as expected</option>
                                        <option value="Changed my mind">Changed my mind</option>
                                    </select>
                                </div>

                                <div class="space-y-2">
                                    <label class="text-[10px] font-bold uppercase text-gray-400 tracking-widest">Additional Notes</label>
                                    <textarea name="notes" rows="4" placeholder="Describe the issue in detail..." class="w-full bg-gray-50 border border-gray-100 rounded-xl px-4 py-4 text-xs outline-none focus:border-primary transition-all resize-none"></textarea>
                                </div>
                            </div>
                        </div>

                        <div class="flex items-center justify-end gap-4 pt-4">
                            <a href="{{ route('customer.orders') }}" class="px-8 py-3 text-[10px] font-bold uppercase tracking-widest text-gray-400 hover:text-gray-600 transition-all">Cancel</a>
                            <button type="submit" class="px-10 py-3 bg-primary text-white text-[10px] font-bold uppercase tracking-widest rounded-xl shadow-lg shadow-primary/20 hover:opacity-90 transition-all">Submit Request</button>
                        </div>
                    </section>
                </form>

                <script>
                    function toggleBankInput(value) {
                        const wrapper = document.getElementById('bank_account_wrapper');
                        const input = wrapper.querySelector('input');
                        if (value === 'refund') {
                            wrapper.classList.remove('hidden');
                            input.setAttribute('required', 'required');
                        } else {
                            wrapper.classList.add('hidden');
                            input.removeAttribute('required');
                        }
                    }
                </script>
                @else
                <section class="space-y-6">
                    <h3 class="text-[10px] font-black uppercase tracking-widest text-primary border-b border-primary/10 pb-2 mb-6">My Return History</h3>
                    
                    @forelse($returns as $return)
                    <a href="{{ route('customer.return-detail', $return->id) }}" class="p-6 bg-gray-50/50 rounded-2xl border border-gray-100 flex flex-col md:flex-row items-start md:items-center gap-6 relative hover:bg-white hover:shadow-xl hover:shadow-primary/5 transition-all group block">
                        <div class="w-16 h-16 bg-white rounded-xl overflow-hidden border border-gray-100 shrink-0 flex items-center justify-center">
                            @if($return->order->orderItems->first() && $return->order->orderItems->first()->product->images->isNotEmpty())
                                <img src="{{ $return->order->orderItems->first()->product->images->first()->img_url }}" class="w-full h-full object-cover">
                            @else
                                <i class="fa-solid fa-rotate-left text-2xl text-gray-300"></i>
                            @endif
                        </div>
                        <div class="flex-grow">
                            <p class="text-[10px] text-gray-400 uppercase font-bold tracking-widest">Order #DEC-{{ $return->order_id }}</p>
                            <h4 class="font-bold text-sm">{{ $return->reason }}</h4>
                            <p class="text-[10px] text-gray-500 mt-1">Requested on {{ \Carbon\Carbon::parse($return->return_date)->format('d M Y') }}</p>
                        </div>
                        <div class="text-right flex items-center gap-4">
                            <span class="px-3 py-1 rounded text-[10px] font-black uppercase tracking-widest 
                                @if($return->status == 'pending') bg-yellow-100 text-yellow-600 
                                @elseif($return->status == 'approved') bg-green-100 text-green-600 
                                @elseif($return->status == 'rejected') bg-red-100 text-red-600 
                                @endif">
                                {{ $return->status }}
                            </span>
                        </div>
                    </a>
                    @empty
                    <div class="text-center py-20 bg-gray-50 rounded-2xl border border-gray-100">
                        <div class="w-16 h-16 bg-white rounded-full flex items-center justify-center mx-auto mb-4 border border-gray-200">
                            <i class="fa-solid fa-box-open text-2xl text-gray-300"></i>
                        </div>
                        <p class="text-gray-500 mb-4 font-medium">Belum ada pengajuan pengembalian barang.</p>
                        <a href="{{ route('customer.orders') }}" class="inline-block bg-primary text-white px-6 py-2 text-[10px] font-bold uppercase tracking-widest rounded-xl shadow-lg shadow-primary/20 hover:opacity-90 transition-all">Lihat Riwayat Pesanan</a>
                    </div>
                    @endforelse
                </section>
                @endif
            </div>
        </div>
    </main>

    <footer class="bg-primary text-white py-12 px-6 mt-12">
        <div class="max-w-6xl mx-auto"> 
            <div class="flex flex-col md:flex-row justify-between items-start border-b border-white/20 pb-10 gap-10">
                <div class="space-y-4">
                    <div class="flex items-center space-x-2 text-white">
                        <div class="w-8 h-8 bg-white/20 rounded flex items-center justify-center">
                            <i class="fa-solid fa-couch text-sm"></i>
                        </div>
                        <span class="text-xl font-bold tracking-widest uppercase">{{ $site_name }}</span>
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

                <div class="flex flex-col items-end space-y-6">
                    <span class="text-[10px] font-bold tracking-widest uppercase text-white/40">Portal Version 1.0</span>
                    <div class="flex space-x-4">
                        <a href="#" class="w-10 h-10 border border-white/40 flex items-center justify-center rounded-full hover:bg-white hover:text-primary transition-all"><i class="fa-brands fa-instagram"></i></a>
                        <a href="#" class="w-10 h-10 border border-white/40 flex items-center justify-center rounded-full hover:bg-white hover:text-primary transition-all"><i class="fa-regular fa-paper-plane"></i></a>
                    </div>
                </div>
            </div>
            <div class="pt-8 text-[10px] text-white/60 font-medium tracking-widest">
                <p>©️ 2026 DECOR MARKETPLACE. ALL RIGHTS RESERVED.</p>
            </div>
        </div>
    </footer>

</body>
</html>