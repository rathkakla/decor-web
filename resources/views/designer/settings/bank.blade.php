<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Decor Designer - Bank & Payout</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap');
        body { background-color: #F8F6F4; font-family: 'Plus Jakarta Sans', sans-serif; overflow-x: hidden; }
        .bg-primary { background-color: #B5733A; }
        .text-primary { color: #B5733A; }
        .active-link { background-color: rgba(181, 115, 58, 0.1); color: #B5733A; border-right: 4px solid #B5733A; }
        .sidebar-transition { transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1); }
        .sidebar-closed { transform: translateX(-100%); }
        .main-full { margin-left: 0 !important; }
        
        .tab-active { color: #B5733A; border-bottom: 3px solid #B5733A; }
        
        ::-webkit-scrollbar { width: 5px; }
        ::-webkit-scrollbar-thumb { background: #B5733A; border-radius: 10px; }
        
        .modal-overlay { transition: opacity 0.3s ease; }
        .modal-content { transition: transform 0.3s ease, opacity 0.3s ease; }
    </style>
</head>
<body class="text-gray-800 flex h-screen">

    @include('designer.partials.sidebar')

    <div id="main-content" class="flex-1 flex flex-col min-w-0 overflow-hidden ml-64 sidebar-transition">
        <form action="{{ route('designer.settings.bank.update') }}" method="POST" enctype="multipart/form-data" class="flex-1 flex flex-col min-w-0 overflow-hidden">
            @csrf
            
            <header class="h-16 bg-primary flex items-center justify-between px-8 sticky top-0 z-40 shadow-sm text-white">
                <div class="flex items-center">
                    <i onclick="toggleSidebar()" class="fa-solid fa-bars-staggered text-xl mr-4 cursor-pointer hover:scale-110 transition-transform"></i>
                    <h2 class="font-black text-[10px] uppercase tracking-[0.3em] leading-none">Financial Settings</h2>
                </div>
                <div class="flex items-center space-x-6">
                    <button type="submit" class="bg-white text-primary px-8 py-2.5 rounded-xl text-[10px] font-black uppercase shadow-lg hover:bg-white/90 transition-all">Save Changes</button>
                    <div class="flex items-center space-x-3 bg-white/10 px-3 py-1.5 rounded-xl border border-white/20">
                        <span class="text-[10px] font-black uppercase tracking-widest">{{ Auth::user()->full_name }}</span>
                        <div class="w-8 h-8 rounded-lg bg-white text-primary flex items-center justify-center font-bold">
                            {{ strtoupper(substr(Auth::user()->full_name, 0, 2)) }}
                        </div>
                    </div>
                </div>
            </header>

            <main class="flex-1 overflow-y-auto p-10 space-y-8 custom-scroll pb-24">
                <div class="max-w-5xl mx-auto space-y-8">
                    @if(session('success'))
                    <div class="bg-green-50 border border-green-100 text-green-600 px-6 py-4 rounded-2xl text-[10px] font-black uppercase tracking-widest shadow-sm">
                        {{ session('success') }}
                    </div>
                    @endif

                    <!-- TAB NAVIGATION -->
                    <div class="flex border-b border-gray-200">
                        <a href="{{ route('designer.settings') }}" class="px-8 py-4 text-[10px] font-black uppercase tracking-widest text-gray-400 hover:text-primary transition-all">Studio Info</a>
                        <a href="{{ route('designer.settings.bank') }}" class="px-8 py-4 text-[10px] font-black uppercase tracking-widest tab-active transition-all">Bank & Payout</a>
                    </div>

                    <!-- BANK ACCOUNT SECTION -->
                    <section class="bg-white p-10 rounded-[48px] border border-gray-100 shadow-sm space-y-8 mt-6">
                        <h3 class="text-xs font-black uppercase tracking-widest text-gray-300 italic leading-none">Primary Bank Account</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="space-y-3">
                                <label class="text-[9px] font-black uppercase tracking-widest text-gray-400 ml-2">Bank Name</label>
                                <input type="text" name="bank_name" value="{{ old('bank_name', $designer->bank_name) }}" placeholder="e.g. BCA, Mandiri, BNI" class="w-full bg-gray-50 border-none rounded-2xl py-4 px-6 text-sm font-bold outline-none focus:ring-2 focus:ring-primary/10">
                            </div>
                            <div class="space-y-3">
                                <label class="text-[9px] font-black uppercase tracking-widest text-gray-400 ml-2">Account Number</label>
                                <input type="text" name="account_number" value="{{ old('account_number', $designer->account_number) }}" placeholder="e.g. 8820xxxxxx" class="w-full bg-gray-50 border-none rounded-2xl py-4 px-6 text-sm font-bold outline-none focus:ring-2 focus:ring-primary/10">
                            </div>
                        </div>
                    </section>

                    <!-- SIGNATURE SECTION -->
                    <section class="bg-white p-10 rounded-[48px] border border-gray-100 shadow-sm space-y-8">
                        <h3 class="text-xs font-black uppercase tracking-widest text-gray-300 italic leading-none">Digital Signature</h3>
                        
                        <label class="h-48 border-2 border-dashed border-gray-100 rounded-[32px] flex flex-col items-center justify-center space-y-4 hover:border-primary transition-all cursor-pointer group bg-gray-50/30 relative overflow-hidden">
                            <input type="file" name="digital_signature" class="hidden" accept="image/*" onchange="previewImage(this, 'signature-preview')">
                            
                            <img id="signature-preview" src="{{ $designer->digital_signature ? asset('storage/' . $designer->digital_signature) : '' }}" class="h-full w-auto object-contain {{ $designer->digital_signature ? '' : 'hidden' }}">
                            
                            <div id="signature-placeholder" class="flex flex-col items-center justify-center space-y-4 {{ $designer->digital_signature ? 'hidden' : '' }}">
                                <div class="w-16 h-16 bg-white rounded-full flex items-center justify-center text-gray-300 group-hover:text-primary shadow-sm transition-all">
                                    <i class="fa-solid fa-signature text-2xl"></i>
                                </div>
                                <div class="text-center">
                                    <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest italic">Upload studio signature for official invoices</p>
                                    <p class="text-[8px] text-gray-300 font-bold uppercase mt-1">PNG, JPG up to 2MB</p>
                                </div>
                            </div>
                        </label>
                    </section>
                </div>
            </main>

            <footer class="p-8 border-t border-gray-100 text-[9px] font-black text-gray-400 uppercase tracking-widest bg-white mt-auto text-center">
                © 2026 DECOR DESIGNER PORTAL. ALL RIGHTS RESERVED.
            </footer>
        </form>
    </div>

    <script>
        function toggleSidebar() { document.getElementById('sidebar').classList.toggle('sidebar-closed'); document.getElementById('main-content').classList.toggle('main-full'); }
        
        function previewImage(input, previewId) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();
                reader.onload = function(e) {
                    var preview = document.getElementById(previewId);
                    preview.src = e.target.result;
                    preview.classList.remove('hidden');
                    
                    var placeholder = document.getElementById('signature-placeholder');
                    if (placeholder) {
                        placeholder.classList.add('hidden');
                    }
                }
                reader.readAsDataURL(input.files[0]);
            }
        }
    </script>
</body>
</html>