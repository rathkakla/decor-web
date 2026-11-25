<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Decor Seller - Update Bank Info</title>
    <script src="https://cdn.tailwindcss.com"></script>
    @include('seller.partials.print-head')
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body class="text-gray-800">
    @php
        $banks = ['Bank Central Asia (BCA)', 'Bank Mandiri', 'Bank Rakyat Indonesia (BRI)', 'Bank Negara Indonesia (BNI)', 'Bank CIMB Niaga', 'Bank Permata', 'Bank Tabungan Negara (BTN)'];
        $currentBank = $seller->bank_name;
        $isCustomBank = $currentBank && !in_array($currentBank, $banks, true);
    @endphp

    <main class="max-w-2xl mx-auto px-4 py-10 sm:py-16 space-y-6">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
            <a href="{{ route('seller.settings') }}" class="text-[10px] font-bold uppercase tracking-widest text-gray-400 hover:text-primary transition-colors">
                <i class="fa-solid fa-arrow-left mr-2"></i> Back to Settings
            </a>
            <h2 class="text-xs font-bold uppercase tracking-[0.3em] text-primary">Financial Security</h2>
        </div>

        @if(session('success'))
            <div class="bg-green-50 border border-green-200 text-green-700 px-5 py-3.5 rounded-xl text-xs font-semibold flex items-center gap-2.5">
                <i class="fa-solid fa-circle-check text-green-600"></i>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        @if($errors->any())
            <div class="bg-red-50 border border-red-200 text-red-700 px-5 py-3.5 rounded-xl text-xs font-semibold space-y-1.5">
                <div class="flex items-center gap-2">
                    <i class="fa-solid fa-circle-exclamation"></i>
                    <span>Terdapat beberapa kesalahan:</span>
                </div>
                <ul class="list-disc list-inside ml-5 font-medium">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="bg-white p-6 sm:p-10 rounded-xl border border-gray-100 shadow-sm space-y-8">
            <div>
                <h3 class="text-xl sm:text-2xl font-bold tracking-tight text-gray-900">Update Bank Account</h3>
                <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mt-1">This info is used for your revenue withdrawal</p>
            </div>

            <form action="{{ route('seller.settings.update') }}" method="POST" class="space-y-6">
                @csrf
                <input type="hidden" name="store_name" value="{{ $seller->store_name }}">
                <input type="hidden" name="store_description" value="{{ $seller->store_description }}">
                <input type="hidden" name="store_address" value="{{ $seller->store_address }}">

                <div class="space-y-2">
                    <label class="text-[10px] font-bold uppercase text-gray-400 ml-1 tracking-widest">Select Bank</label>
                    <select name="bank_name" class="w-full h-12 bg-gray-50 border border-gray-100 rounded-xl px-4 text-xs font-semibold outline-none focus:border-primary focus:ring-2 focus:ring-primary/20 transition-all">
                        <option value="">Pilih bank...</option>
                        @foreach($banks as $bank)
                            <option value="{{ $bank }}" {{ $currentBank === $bank ? 'selected' : '' }}>{{ $bank }}</option>
                        @endforeach
                        @if($isCustomBank)
                            <option value="{{ $currentBank }}" selected>{{ $currentBank }}</option>
                        @endif
                    </select>
                </div>
                <div class="space-y-2">
                    <label class="text-[10px] font-bold uppercase text-gray-400 ml-1 tracking-widest">Account Number</label>
                    <input type="text" name="account_number" value="{{ $seller->account_number }}" placeholder="e.g. 8820033102" class="w-full h-12 bg-gray-50 border border-gray-100 rounded-xl px-4 text-xs font-semibold outline-none focus:border-primary focus:ring-2 focus:ring-primary/20 transition-all">
                </div>
                <div class="space-y-2">
                    <label class="text-[10px] font-bold uppercase text-gray-400 ml-1 tracking-widest">Account Holder Name</label>
                    <input type="text" value="{{ $seller->user->full_name }}" readonly class="w-full h-12 bg-gray-100 border border-gray-100 rounded-xl px-4 text-xs font-semibold text-gray-500 outline-none cursor-not-allowed">
                    <p class="text-[10px] text-gray-400 ml-1">Nama pemilik rekening mengikuti nama akun Anda dan tidak dapat diubah di sini.</p>
                </div>

                <button type="submit" class="w-full bg-primary text-white h-12 rounded-xl text-[10px] font-bold uppercase tracking-[0.3em] shadow-lg shadow-primary/20 hover:opacity-90 transition-all">
                    Verify &amp; Save Details
                </button>
            </form>
        </div>
    </main>
</body>
</html>
