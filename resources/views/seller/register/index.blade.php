<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Decor - Join the Curator Community</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;600;700;800&family=Playfair+Display:italic,wght@700&display=swap');
        body { font-family: 'Plus Jakarta Sans', sans-serif; overflow-x: hidden; }
        .font-playfair { font-family: 'Playfair Display', serif; }
        .bg-primary { background-color: #B5733A; }
        .text-primary { color: #B5733A; }
        
        .input-underline {
            border: none; border-bottom: 1.5px solid #E5E7EB; border-radius: 0;
            padding: 0.75rem 0; background: transparent; transition: all 0.4s ease;
        }
        .input-underline:focus { outline: none; border-bottom: 1.5px solid #B5733A; }
        
        .step-dot { width: 8px; height: 8px; border-radius: 50%; background: #E5E7EB; transition: all 0.5s ease; }
        .step-dot.active { background: #B5733A; box-shadow: 0 0 10px rgba(181, 115, 58, 0.4); width: 24px; border-radius: 10px; }
    </style>
</head>
<body class="bg-white antialiased">

    <div class="flex flex-col lg:flex-row min-h-screen">
        <div class="hidden lg:flex lg:w-5/12 relative overflow-hidden bg-[#2D3E35]">
            <img src="https://images.unsplash.com/photo-1586023492125-27b2c045efd7?q=80&w=1200" class="absolute inset-0 w-full h-full object-cover opacity-70 scale-110">
            <div class="relative z-10 p-16 flex flex-col justify-between h-full w-full text-white">
                <h1 class="text-2xl font-bold tracking-[0.4em]">DECOR.</h1>
                <div class="max-w-xs">
                    <p class="text-2xl font-playfair leading-relaxed mb-6">"Curating space is an act of intellectual luxury."</p>
                    <div class="w-16 h-[1.5px] bg-white/40"></div>
                </div>
            </div>
        </div>

        <div class="w-full lg:w-7/12 flex flex-col p-8 md:p-16 lg:p-20">
            <div class="max-w-xl mx-auto w-full">
                
                <div class="flex items-center space-x-3 mb-12">
                    <div id="dot-1" class="step-dot active"></div>
                    <div id="dot-2" class="step-dot"></div>
                    <div id="dot-3" class="step-dot"></div>
                    <span id="progress-text" class="text-[9px] font-black uppercase tracking-[0.3em] text-gray-300 ml-4">Progress 33%</span>
                </div>

                <div class="mb-10">
                    <h2 class="text-4xl lg:text-5xl font-light text-gray-800 leading-tight">Join the</h2>
                    <h2 class="text-4xl lg:text-5xl font-black text-gray-800 italic font-playfair">Curator Community</h2>
                </div>

                @if ($errors->any())
                    <div class="mb-6 p-4 bg-red-50 border-l-4 border-red-500 text-red-700 text-xs font-bold rounded">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('register') }}" method="POST" class="space-y-12">
                    @csrf
                    
                    <div id="section-1" class="space-y-8">
                        <span class="text-[10px] font-black uppercase text-primary tracking-[0.4em]">01 — Identity</span>
                        <div class="grid gap-6">
                            <div class="grid grid-cols-2 gap-6">
                                <div class="flex flex-col"><label class="text-[9px] font-black tracking-widest text-gray-400 uppercase">Username</label><input type="text" name="username" value="{{ old('username') }}" placeholder="atelier_luxe" required class="input-underline text-sm font-bold"></div>
                                <div class="flex flex-col"><label class="text-[9px] font-black tracking-widest text-gray-400 uppercase">Store Name</label><input type="text" name="store_name" value="{{ old('store_name') }}" placeholder="Atelier de Luxe" required class="input-underline text-sm font-bold"></div>
                            </div>
                            <div class="flex flex-col"><label class="text-[9px] font-black tracking-widest text-gray-400 uppercase">Business Email</label><input type="email" name="email" value="{{ old('email') }}" placeholder="curate@studio.com" required class="input-underline text-sm font-bold"></div>
                            <div class="grid grid-cols-2 gap-6">
                                <div class="flex flex-col relative"><label class="text-[9px] font-black tracking-widest text-gray-400 uppercase">Password</label><input type="password" name="password" placeholder="••••••••••••" required class="input-underline text-sm font-bold"></div>
                                <div class="flex flex-col relative"><label class="text-[9px] font-black tracking-widest text-gray-400 uppercase">Confirm Password</label><input type="password" name="password_confirmation" placeholder="••••••••••••" required class="input-underline text-sm font-bold"></div>
                            </div>
                        </div>
                        <button type="button" onclick="nextStep(2)" class="w-full bg-primary text-white py-5 rounded-sm text-[10px] font-black uppercase tracking-[0.4em] shadow-2xl shadow-primary/30 flex items-center justify-center group">
                            Continue Professional Info <i class="fa-solid fa-arrow-right ml-4 group-hover:translate-x-2 transition-transform"></i>
                        </button>
                    </div>

                    <div id="section-2" class="hidden space-y-8">
                        <span class="text-[10px] font-black uppercase text-primary tracking-[0.4em]">02 — Professional</span>
                        <div class="grid gap-6">
                            <div class="flex flex-col"><label class="text-[9px] font-black tracking-widest text-gray-400 uppercase">Owner's Full Name</label><input type="text" name="name" value="{{ old('name') }}" placeholder="Julian Vane" required class="input-underline text-sm font-bold"></div>
                            <div class="grid grid-cols-2 gap-8">
                                <div class="flex flex-col"><label class="text-[9px] font-black tracking-widest text-gray-400 uppercase">Phone Number</label><input type="text" name="phone" value="{{ old('phone') }}" placeholder="+62 812..." class="input-underline text-sm font-bold"></div>
                                <div class="flex flex-col"><label class="text-[9px] font-black tracking-widest text-gray-400 uppercase">Bank Name</label><input type="text" name="bank_name" placeholder="BCA / Mandiri" class="input-underline text-sm font-bold"></div>
                            </div>
                            <div class="flex flex-col"><label class="text-[9px] font-black tracking-widest text-gray-400 uppercase">Account Number</label><input type="text" id="accNumber" name="account_number" placeholder="0000 0000 0000" class="input-underline text-sm font-black tracking-widest"></div>
                        </div>
                        <div class="flex items-center space-x-4">
                            <button type="button" onclick="nextStep(1)" class="w-1/3 py-5 text-[9px] font-black uppercase text-gray-400 tracking-widest">Back</button>
                            <button type="button" onclick="nextStep(3)" class="w-2/3 bg-primary text-white py-5 rounded-sm text-[10px] font-black uppercase tracking-[0.4em] shadow-2xl shadow-primary/30 flex items-center justify-center group">
                                Continue Logistics <i class="fa-solid fa-arrow-right ml-4 group-hover:translate-x-2 transition-transform"></i>
                            </button>
                        </div>
                    </div>

                    <div id="section-3" class="hidden space-y-8">
                        <span class="text-[10px] font-black uppercase text-primary tracking-[0.4em]">03 — Logistics</span>
                        <div class="space-y-6">
                            <div class="flex flex-col">
                                <div class="flex justify-between items-center mb-1">
                                    <label class="text-[9px] font-black tracking-widest text-gray-400 uppercase">Warehouse Address</label>
                                    <button type="button" class="text-[8px] font-black text-primary uppercase"><i class="fa-solid fa-location-dot"></i> Pin Location</button>
                                </div>
                                <textarea name="store_address" required placeholder="Street name, Building No, City..." class="input-underline text-sm font-medium h-24 resize-none">{{ old('store_address') }}</textarea>
                            </div>
                        </div>
                        <div class="flex items-center space-x-4">
                            <button type="button" onclick="nextStep(2)" class="w-1/3 py-5 text-[9px] font-black uppercase text-gray-400 tracking-widest">Back</button>
                            <button type="submit" class="w-2/3 bg-primary text-white py-5 rounded-sm text-[10px] font-black uppercase tracking-[0.4em] shadow-2xl shadow-primary/30 flex items-center justify-center">
                                Complete Registration
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function nextStep(step) {
            document.getElementById('section-1').classList.add('hidden');
            document.getElementById('section-2').classList.add('hidden');
            document.getElementById('section-3').classList.add('hidden');
            
            document.getElementById('section-' + step).classList.remove('hidden');
            
            for(let i=1; i<=3; i++) {
                document.getElementById('dot-' + i).classList.remove('active');
            }
            document.getElementById('dot-' + step).classList.add('active');
            
            const progressTexts = ["Progress 33%", "Progress 66%", "Progress 100%"];
            document.getElementById('progress-text').innerText = progressTexts[step-1];
        }

        document.getElementById('accNumber').addEventListener('input', function (e) {
            e.target.value = e.target.value.replace(/[^\d]/g, '').replace(/(.{4})/g, '$1 ').trim();
        });
    </script>
</body>
</html>