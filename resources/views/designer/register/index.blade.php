<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Decor - Join the Designer Community</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;600;700;800&family=Playfair+Display:italic,wght@700&display=swap');

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            overflow-x: hidden;
        }

        .font-playfair {
            font-family: 'Playfair Display', serif;
        }

        .bg-primary {
            background-color: #B5733A;
        }

        .text-primary {
            color: #B5733A;
        }

        /* Underline Style focus effect */
        .input-underline {
            border: none;
            border-bottom: 1.5px solid #E5E7EB;
            border-radius: 0;
            padding: 0.75rem 0;
            background: transparent;
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .input-underline:focus {
            outline: none;
            border-bottom: 1.5px solid #B5733A;
            background: linear-gradient(to top, rgba(181, 115, 58, 0.02), transparent);
        }

        .step-dot {
            width: 8px;
            height: 8px;
            border-radius: 50%;
            background: #E5E7EB;
            transition: all 0.5s ease;
        }

        .step-dot.active {
            background: #B5733A;
            box-shadow: 0 0 10px rgba(181, 115, 58, 0.4);
            width: 24px;
            border-radius: 10px;
        }

        .terms-container {
            height: 300px;
            overflow-y: scroll;
            padding: 20px;
            background: #F9FAFB;
            border: 1px solid #E5E7EB;
            border-radius: 8px;
            font-size: 0.875rem;
            line-height: 1.6;
            color: #374151;
        }
        .terms-container::-webkit-scrollbar { width: 6px; }
        .terms-container::-webkit-scrollbar-track { background: #F1F1F1; }
        .terms-container::-webkit-scrollbar-thumb { background: #B5733A; border-radius: 10px; }
    </style>
</head>

<body class="bg-white antialiased">

    <div class="flex flex-col lg:flex-row min-h-screen">
        <div class="w-full lg:w-5/12 h-64 lg:h-auto relative overflow-hidden bg-[#2D3E35]">
            <img src="https://images.unsplash.com/photo-1618221195710-dd6b41faaea6?q=80&w=1200"
                class="absolute inset-0 w-full h-full object-cover opacity-70 scale-110" alt="Interior Design">
            <div class="relative z-10 p-8 lg:p-16 flex flex-col justify-between h-full text-white">
                <h1 class="text-xl lg:text-2xl font-bold tracking-[0.4em]">DECOR</h1>
                <div class="hidden lg:block max-w-xs">
                    <p class="text-2xl font-playfair leading-relaxed mb-6">"Design is not just what it looks like, it's
                        how it works."</p>
                    <div class="w-16 h-[1.5px] bg-white/40"></div>
                </div>
            </div>
        </div>

        <div class="w-full lg:w-7/12 flex flex-col p-8 md:p-16 lg:p-20">
            <div class="max-w-xl mx-auto w-full">

                @if ($errors->any())
                    <div class="mb-6 p-4 bg-red-50 border-l-4 border-red-500 text-red-700 text-xs font-bold rounded">
                        <p class="mb-2 uppercase tracking-widest">Please fix the following errors:</p>
                        <ul class="list-disc pl-5">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div class="flex items-center space-x-3 mb-16">
                    <div id="dot-1" class="step-dot active"></div>
                    <div id="dot-2" class="step-dot"></div>
                    <div id="dot-3" class="step-dot"></div>
                    <span id="progress-text"
                        class="text-[9px] font-black uppercase tracking-[0.3em] text-gray-300 ml-4 font-bold">Progress
                        33%</span>
                </div>

                <div class="mb-12">
                    <h2 class="text-4xl lg:text-5xl font-light text-gray-800 leading-tight">Join the</h2>
                    <h2 class="text-4xl lg:text-5xl font-black text-gray-800 italic font-playfair tracking-tight">
                        Interior Designer</h2>
                </div>

                <form action="{{ route('register') }}" method="POST" class="space-y-12" novalidate>
                    @csrf
                    <input type="hidden" name="role" value="designer">

                    <div id="section-1" class="space-y-10 animate-in fade-in duration-700">
                        <span class="text-[10px] font-black uppercase text-primary tracking-[0.4em]">01 —
                            Identity</span>
                        <div class="grid gap-8">
                            <div class="flex flex-col">
                                <label class="text-[9px] font-black uppercase tracking-widest text-gray-400 mb-1">Studio
                                    / Brand Name (Username)</label>
                                <input type="text" name="username" value="{{ old('username') }}"
                                    placeholder="Vane Interior Studio" class="input-underline text-sm font-bold">
                                @error('username')
                                    <span class="text-red-500 text-[10px] mt-1 uppercase font-bold">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="flex flex-col">
                                <label
                                    class="text-[9px] font-black uppercase tracking-widest text-gray-400 mb-1">Professional
                                    Email</label>
                                <input type="email" name="email" value="{{ old('email') }}"
                                    placeholder="design@studio.com" class="input-underline text-sm font-bold">
                                @error('email')
                                    <span class="text-red-500 text-[10px] mt-1 uppercase font-bold">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="flex flex-col relative group">
                                <label
                                    class="text-[9px] font-black uppercase tracking-widest text-gray-400 mb-1">Password</label>
                                <input type="password" name="password" id="passInput" placeholder="••••••••••••"
                                    class="input-underline text-sm font-bold pr-10">
                                <button type="button" onclick="togglePass('passInput', 'eyeIcon')"
                                    class="absolute right-0 bottom-3 text-gray-300 hover:text-primary transition-colors">
                                    <i id="eyeIcon" class="fa-solid fa-eye-slash text-xs"></i>
                                </button>
                                @error('password')
                                    <span class="text-red-500 text-[10px] mt-1 uppercase font-bold">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="flex flex-col relative group">
                                <label
                                    class="text-[9px] font-black uppercase tracking-widest text-gray-400 mb-1">Confirm
                                    Password</label>
                                <input type="password" name="password_confirmation" id="passConfirmInput"
                                    placeholder="••••••••••••" class="input-underline text-sm font-bold pr-10">
                                <button type="button" onclick="togglePass('passConfirmInput', 'eyeConfirmIcon')"
                                    class="absolute right-0 bottom-3 text-gray-300 hover:text-primary transition-colors">
                                    <i id="eyeConfirmIcon" class="fa-solid fa-eye-slash text-xs"></i>
                                </button>
                            </div>
                        </div>
                        <div class="pt-6">
                            <button type="button" onclick="nextStep(2)"
                                class="w-full bg-primary text-white py-5 rounded-sm text-[10px] font-black uppercase tracking-[0.4em] shadow-2xl shadow-primary/30 hover:scale-[1.02] active:scale-95 transition-all flex items-center justify-center group">
                                Continue Professional Info
                                <i
                                    class="fa-solid fa-arrow-right ml-4 transform group-hover:translate-x-2 transition-transform"></i>
                            </button>
                        </div>
                    </div>

                    <div id="section-2" class="hidden space-y-10 animate-in fade-in duration-700">
                        <span class="text-[10px] font-black uppercase text-primary tracking-[0.4em]">02 —
                            Professional</span>
                        <div class="grid gap-8">
                            <div class="flex flex-col">
                                <label
                                    class="text-[9px] font-black uppercase tracking-widest text-gray-400 mb-1">Designer's
                                    Full Name</label>
                                <input type="text" name="name" value="{{ old('name') }}" placeholder="Julian Vane"
                                    class="input-underline text-sm font-bold">
                                @error('name')
                                    <span class="text-red-500 text-[10px] mt-1 uppercase font-bold">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                                <div class="flex flex-col">
                                    <label
                                        class="text-[9px] font-black uppercase tracking-widest text-gray-400 mb-1">Phone
                                        Number</label>
                                    <input type="text" name="phone_number" value="{{ old('phone_number') }}"
                                        placeholder="+62 812..." class="input-underline text-sm font-bold">
                                </div>
                                <div class="flex flex-col">
                                    <label
                                        class="text-[9px] font-black uppercase tracking-widest text-gray-400 mb-1">Specialty</label>
                                    <input type="text" name="specialty" value="{{ old('specialty') }}"
                                        placeholder="Modern Interior / minimalist" class="input-underline text-sm font-bold">
                                    @error('specialty')
                                        <span class="text-red-500 text-[10px] mt-1 uppercase font-bold">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="flex flex-col">
                                <label
                                    class="text-[9px] font-black uppercase tracking-widest text-gray-400 mb-1">Experience Years</label>
                                <input type="number" name="experience_years"
                                    value="{{ old('experience_years') }}" placeholder="5"
                                    class="input-underline text-sm font-bold">
                                @error('experience_years')
                                    <span class="text-red-500 text-[10px] mt-1 uppercase font-bold">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="flex items-center space-x-4 pt-6">
                            <button type="button" onclick="nextStep(1)"
                                class="w-1/3 py-5 text-[9px] font-black uppercase text-gray-400 tracking-widest hover:text-gray-800 transition-colors">Back</button>
                            <button type="button" onclick="nextStep(3)"
                                class="w-2/3 bg-primary text-white py-5 rounded-sm text-[10px] font-black uppercase tracking-[0.4em] shadow-2xl shadow-primary/30 hover:scale-[1.02] active:scale-95 transition-all flex items-center justify-center group">
                                Continue Logistics
                                <i
                                    class="fa-solid fa-arrow-right ml-4 transform group-hover:translate-x-2 transition-transform"></i>
                            </button>
                        </div>
                    </div>

                    <div id="section-3" class="hidden space-y-10 animate-in fade-in duration-700">
                        <span class="text-[10px] font-black uppercase text-primary tracking-[0.4em]">03 — Portfolio &
                            Office</span>
                        <div class="space-y-8">
                            <div class="flex flex-col">
                                <div class="flex justify-between items-center mb-1">
                                    <label class="text-[9px] font-black uppercase tracking-widest text-gray-400">Office
                                        / Studio Address</label>
                                    <button type="button"
                                        class="text-[8px] font-black uppercase tracking-widest text-primary flex items-center">
                                        <i class="fa-solid fa-location-dot mr-1"></i> Pin Location
                                    </button>
                                </div>
                                <input type="text" name="address" value="{{ old('address') }}"
                                    placeholder="Street name, City, State..."
                                    class="input-underline w-full text-sm font-medium">
                            </div>
                            <div class="flex flex-col">
                                <label
                                    class="text-[9px] font-black uppercase tracking-widest text-gray-400 mb-1">Professional Bio</label>
                                <textarea name="bio" placeholder="Tell us about your design philosophy..."
                                    class="input-underline text-sm font-medium h-24 resize-none">{{ old('bio') }}</textarea>
                                @error('bio')
                                    <span class="text-red-500 text-[10px] mt-1 uppercase font-bold">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="flex flex-col">
                                <label
                                    class="text-[9px] font-black uppercase tracking-widest text-gray-400 mb-1">Portfolio
                                    Link (Behance/Instagram)</label>
                                <input type="url" name="portfolio_link" value="{{ old('portfolio_link') }}"
                                    placeholder="https://behance.net/username"
                                    class="input-underline text-sm font-bold text-primary">
                            </div>

                            <div class="flex items-start space-x-3 py-4">
                                <input type="checkbox" id="terms-checkbox" disabled class="mt-1 w-4 h-4 text-primary border-gray-300 rounded focus:ring-primary cursor-not-allowed">
                                <label for="terms-checkbox" class="text-[10px] text-gray-500 leading-relaxed uppercase font-bold tracking-wider">
                                    Saya menyetujui <button type="button" onclick="openTerms()" class="text-primary underline">Syarat & Ketentuan</button> yang berlaku pada platform DECOR.
                                </label>
                            </div>
                        </div>
                        <div class="flex items-center space-x-4 pt-6">
                            <button type="button" onclick="nextStep(2)"
                                class="w-1/3 py-5 text-[9px] font-black uppercase text-gray-400 tracking-widest hover:text-gray-800 transition-colors">Back</button>
                            <button type="submit" id="submit-reg" disabled
                                class="w-2/3 bg-primary text-white py-5 rounded-sm text-[10px] font-black uppercase tracking-[0.4em] shadow-2xl shadow-primary/30 flex items-center justify-center opacity-50 cursor-not-allowed transition-all">
                                Complete Registration
                            </button>
                        </div>
                    </div>
                </form>

                <div
                    class="mt-20 pt-8 border-t border-gray-50 flex flex-col md:flex-row justify-between items-center gap-4 text-[8px] font-black uppercase tracking-widest text-gray-300">
                    <p>© 2026 DECOR. ARCHITECTURAL PRESTIGE & DESIGNER UTILITY.</p>
                </div>
            </div>
        </div>
    <!-- Terms Modal -->
    <div id="terms-modal" class="fixed inset-0 z-50 hidden overflow-y-auto bg-black/60 backdrop-blur-sm">
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="bg-white rounded-2xl max-w-2xl w-full p-8 shadow-2xl">
                <div class="flex justify-between items-center mb-6">
                    <h3 class="text-2xl font-bold text-gray-800">Syarat & Ketentuan Designer</h3>
                    <button type="button" onclick="closeTerms()" class="text-gray-400 hover:text-gray-600 transition-colors"><i class="fa-solid fa-xmark text-xl"></i></button>
                </div>
                <div id="modal-terms-box" class="terms-container h-[400px] custom-scrollbar">
                    <p class="mb-4">Dengan mendaftarkan akun sebagai Designer, pengguna dianggap telah membaca, memahami, dan menyetujui seluruh syarat dan ketentuan yang berlaku pada platform marketplace.</p>
                    
                    <h4 class="font-bold text-gray-800 mt-6 mb-2 uppercase text-[11px] tracking-wider">Kewajiban Designer</h4>
                    <ul class="list-disc pl-5 space-y-2 text-gray-600">
                        <li>Designer wajib mengunggah karya desain yang merupakan hasil karya sendiri dan bukan hasil plagiarisme atau pencurian karya pihak lain.</li>
                        <li>Designer wajib memastikan desain yang diunggah tidak mengandung unsur yang melanggar hukum, mengandung unsur SARA, pornografi, kekerasan, maupun konten yang tidak pantas.</li>
                        <li>Designer bertanggung jawab penuh atas seluruh desain dan konten yang diunggah ke dalam platform.</li>
                    </ul>

                    <h4 class="font-bold text-gray-800 mt-6 mb-2 uppercase text-[11px] tracking-wider">Larangan Bagi Designer</h4>
                    <ul class="list-disc pl-5 space-y-2 text-gray-600">
                        <li>Designer dilarang mengunggah karya yang melanggar hak cipta, merek dagang, atau hak kekayaan intelektual milik pihak lain tanpa izin resmi.</li>
                        <li>Designer dilarang mengunggah desain palsu, hasil copy, atau desain yang dapat menimbulkan sengketa hukum di kemudian hari.</li>
                        <li>Designer dilarang menggunakan platform untuk aktivitas yang merugikan pengguna lain maupun platform.</li>
                    </ul>

                    <h4 class="font-bold text-gray-800 mt-6 mb-2 uppercase text-[11px] tracking-wider">Hak Platform</h4>
                    <ul class="list-disc pl-5 space-y-2 text-gray-600">
                        <li>Platform berhak meninjau, membatasi, atau menghapus desain yang dianggap melanggar syarat dan ketentuan maupun ketentuan hukum yang berlaku.</li>
                        <li>Platform berhak memberikan peringatan, suspend sementara, maupun suspend permanen terhadap akun designer yang terbukti melakukan pelanggaran.</li>
                        <li>Platform berhak menghapus konten atau desain yang dilaporkan bermasalah oleh pengguna lain maupun pihak terkait.</li>
                    </ul>
                    
                    <div class="mt-8 p-4 bg-primary/5 border border-primary/20 rounded-lg">
                        <p class="text-primary font-bold text-center italic text-xs">Silakan scroll hingga akhir untuk mengaktifkan persetujuan.</p>
                    </div>
                </div>
                <div class="mt-6 flex justify-end">
                    <button type="button" id="btn-modal-close" disabled onclick="acceptTerms()" class="bg-gray-200 text-gray-500 px-8 py-3 rounded-lg font-bold text-sm uppercase tracking-widest cursor-not-allowed transition-all duration-300">Close & Accept</button>
                </div>
            </div>
        </div>
    </div>


    <script>
        // 1. Navigation Logic
        function nextStep(step) {
            // Hide all
            document.getElementById('section-1').classList.add('hidden');
            document.getElementById('section-2').classList.add('hidden');
            document.getElementById('section-3').classList.add('hidden');

            // Show target
            document.getElementById('section-' + step).classList.remove('hidden');

            // Update Progress Dots
            for (let i = 1; i <= 3; i++) {
                document.getElementById('dot-' + i).classList.remove('active');
            }
            document.getElementById('dot-' + step).classList.add('active');

            const progressTexts = ["Progress 33%", "Progress 66%", "Progress 100%"];
            document.getElementById('progress-text').innerText = progressTexts[step - 1];

            // Scroll to top of form for mobile
            window.scrollTo({ top: 0, behavior: 'smooth' });
        }

        // Terms Logic
        function openTerms() {
            document.getElementById('terms-modal').classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }

        function closeTerms() {
            document.getElementById('terms-modal').classList.add('hidden');
            document.body.style.overflow = 'auto';
        }

        function acceptTerms() {
            termsCheckbox.checked = true;
            submitBtn.disabled = false;
            submitBtn.classList.remove('opacity-50', 'cursor-not-allowed');
            closeTerms();
        }

        const modalTermsBox = document.getElementById('modal-terms-box');
        const modalCloseBtn = document.getElementById('btn-modal-close');
        const termsCheckbox = document.getElementById('terms-checkbox');
        const submitBtn = document.getElementById('submit-reg');

        if (modalTermsBox) {
            modalTermsBox.addEventListener('scroll', function() {
                // More reliable scroll to bottom detection
                const isAtBottom = modalTermsBox.scrollHeight - modalTermsBox.scrollTop <= modalTermsBox.clientHeight + 10;
                
                if (isAtBottom) {
                    if (modalCloseBtn) {
                        modalCloseBtn.disabled = false;
                        modalCloseBtn.classList.remove('bg-gray-200', 'text-gray-500', 'cursor-not-allowed');
                        modalCloseBtn.classList.add('bg-primary', 'text-white');
                    }
                    
                    if (termsCheckbox) {
                        termsCheckbox.disabled = false;
                        termsCheckbox.classList.remove('cursor-not-allowed');
                    }
                }
            });
        }

        if (termsCheckbox) {
            termsCheckbox.addEventListener('change', function() {
                if (this.checked) {
                    submitBtn.disabled = false;
                    submitBtn.classList.remove('opacity-50', 'cursor-not-allowed');
                } else {
                    submitBtn.disabled = true;
                    submitBtn.classList.add('opacity-50', 'cursor-not-allowed');
                }
            });
        }

        // 2. Password Toggle
        function togglePass(inputId, iconId) {
            const pass = document.getElementById(inputId);
            const icon = document.getElementById(iconId);
            if (pass.type === "password") {
                pass.type = "text";
                icon.classList.replace('fa-eye-slash', 'fa-eye');
            } else {
                pass.type = "password";
                icon.classList.replace('fa-eye', 'fa-eye-slash');
            }
        }

        // 3. Auto-Format Acc Number
        document.getElementById('accNumber').addEventListener('input', function (e) {
            e.target.value = e.target.value.replace(/[^\d]/g, '').replace(/(.{4})/g, '$1 ').trim();
        });
    </script>
</body>
</html>