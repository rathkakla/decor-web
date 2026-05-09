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
        
        body { font-family: 'Plus Jakarta Sans', sans-serif; overflow-x: hidden; }
        .font-playfair { font-family: 'Playfair Display', serif; }
        .bg-primary { background-color: #B5733A; }
        .text-primary { color: #B5733A; }
        
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
        
        .step-dot { width: 8px; height: 8px; border-radius: 50%; background: #E5E7EB; transition: all 0.5s ease; }
        .step-dot.active { background: #B5733A; box-shadow: 0 0 10px rgba(181, 115, 58, 0.4); width: 24px; border-radius: 10px; }
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
                    <p class="text-2xl font-playfair leading-relaxed mb-6">"Design is not just what it looks like, it's how it works."</p>
                    <div class="w-16 h-[1.5px] bg-white/40"></div>
                </div>
            </div>
        </div>

        <div class="w-full lg:w-7/12 flex flex-col p-8 md:p-16 lg:p-20">
            <div class="max-w-xl mx-auto w-full">
                
                <div class="flex items-center space-x-3 mb-16">
                    <div id="dot-1" class="step-dot active"></div>
                    <div id="dot-2" class="step-dot"></div>
                    <div id="dot-3" class="step-dot"></div>
                    <span id="progress-text" class="text-[9px] font-black uppercase tracking-[0.3em] text-gray-300 ml-4 font-bold">Progress 33%</span>
                </div>

                <div class="mb-12">
                    <h2 class="text-4xl lg:text-5xl font-light text-gray-800 leading-tight">Join the</h2>
                    <h2 class="text-4xl lg:text-5xl font-black text-gray-800 italic font-playfair tracking-tight">Interior Designer</h2>
                </div>

                <form action="#" method="POST" class="space-y-12">
                    @csrf
                    
                    <div id="section-1" class="space-y-10 animate-in fade-in duration-700">
                        <span class="text-[10px] font-black uppercase text-primary tracking-[0.4em]">01 — Identity</span>
                        <div class="grid gap-8">
                            <div class="flex flex-col">
                                <label class="text-[9px] font-black uppercase tracking-widest text-gray-400 mb-1">Studio / Brand Name</label>
                                <input type="text" placeholder="Vane Interior Studio" class="input-underline text-sm font-bold">
                            </div>
                            <div class="flex flex-col">
                                <label class="text-[9px] font-black uppercase tracking-widest text-gray-400 mb-1">Professional Email</label>
                                <input type="email" placeholder="design@studio.com" class="input-underline text-sm font-bold">
                            </div>
                            <div class="flex flex-col relative group">
                                <label class="text-[9px] font-black uppercase tracking-widest text-gray-400 mb-1">Password</label>
                                <input type="password" id="passInput" placeholder="••••••••••••" class="input-underline text-sm font-bold pr-10">
                                <button type="button" onclick="togglePass()" class="absolute right-0 bottom-3 text-gray-300 hover:text-primary transition-colors">
                                    <i id="eyeIcon" class="fa-solid fa-eye-slash text-xs"></i>
                                </button>
                            </div>
                        </div>
                        <div class="pt-6">
                            <button type="button" onclick="nextStep(2)" class="w-full bg-primary text-white py-5 rounded-sm text-[10px] font-black uppercase tracking-[0.4em] shadow-2xl shadow-primary/30 hover:scale-[1.02] active:scale-95 transition-all flex items-center justify-center group">
                                Continue Professional Info
                                <i class="fa-solid fa-arrow-right ml-4 transform group-hover:translate-x-2 transition-transform"></i>
                            </button>
                        </div>
                    </div>

                    <div id="section-2" class="hidden space-y-10 animate-in fade-in duration-700">
                        <span class="text-[10px] font-black uppercase text-primary tracking-[0.4em]">02 — Professional</span>
                        <div class="grid gap-8">
                            <div class="flex flex-col">
                                <label class="text-[9px] font-black uppercase tracking-widest text-gray-400 mb-1">Designer's Full Name</label>
                                <input type="text" placeholder="Julian Vane" class="input-underline text-sm font-bold">
                            </div>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                                <div class="flex flex-col">
                                    <label class="text-[9px] font-black uppercase tracking-widest text-gray-400 mb-1">Phone Number</label>
                                    <input type="text" placeholder="+62 812..." class="input-underline text-sm font-bold">
                                </div>
                                <div class="flex flex-col">
                                    <label class="text-[9px] font-black uppercase tracking-widest text-gray-400 mb-1">Bank Name</label>
                                    <input type="text" placeholder="BCA / Mandiri" class="input-underline text-sm font-bold">
                                </div>
                            </div>
                            <div class="flex flex-col">
                                <label class="text-[9px] font-black uppercase tracking-widest text-gray-400 mb-1">Account Number</label>
                                <input type="text" id="accNumber" placeholder="0000 0000 0000" class="input-underline text-sm font-black tracking-widest">
                            </div>
                        </div>
                        <div class="flex items-center space-x-4 pt-6">
                            <button type="button" onclick="nextStep(1)" class="w-1/3 py-5 text-[9px] font-black uppercase text-gray-400 tracking-widest hover:text-gray-800 transition-colors">Back</button>
                            <button type="button" onclick="nextStep(3)" class="w-2/3 bg-primary text-white py-5 rounded-sm text-[10px] font-black uppercase tracking-[0.4em] shadow-2xl shadow-primary/30 hover:scale-[1.02] active:scale-95 transition-all flex items-center justify-center group">
                                Continue Logistics
                                <i class="fa-solid fa-arrow-right ml-4 transform group-hover:translate-x-2 transition-transform"></i>
                            </button>
                        </div>
                    </div>

                    <div id="section-3" class="hidden space-y-10 animate-in fade-in duration-700">
                        <span class="text-[10px] font-black uppercase text-primary tracking-[0.4em]">03 — Portfolio & Office</span>
                        <div class="space-y-8">
                            <div class="flex flex-col">
                                <div class="flex justify-between items-center mb-1">
                                    <label class="text-[9px] font-black uppercase tracking-widest text-gray-400">Office / Studio Address</label>
                                    <button type="button" class="text-[8px] font-black uppercase tracking-widest text-primary flex items-center">
                                        <i class="fa-solid fa-location-dot mr-1"></i> Pin Location
                                    </button>
                                </div>
                                <input type="text" placeholder="Street name, City, State..." class="input-underline w-full text-sm font-medium">
                            </div>
                            <div class="flex flex-col">
                                <label class="text-[9px] font-black uppercase tracking-widest text-gray-400 mb-1">Portfolio Link (Behance/Instagram)</label>
                                <input type="url" placeholder="https://behance.net/username" class="input-underline text-sm font-bold text-primary">
                            </div>
                        </div>
                        <div class="flex items-center space-x-4 pt-6">
                            <button type="button" onclick="nextStep(2)" class="w-1/3 py-5 text-[9px] font-black uppercase text-gray-400 tracking-widest hover:text-gray-800 transition-colors">Back</button>
                            <button type="submit" class="w-2/3 bg-primary text-white py-5 rounded-sm text-[10px] font-black uppercase tracking-[0.4em] shadow-2xl shadow-primary/30 hover:scale-[1.02] active:scale-95 transition-all flex items-center justify-center">
                                Complete Registration
                            </button>
                        </div>
                    </div>
                </form>

                <div class="mt-20 pt-8 border-t border-gray-50 flex flex-col md:flex-row justify-between items-center gap-4 text-[8px] font-black uppercase tracking-widest text-gray-300">
                    <p>© 2026 DECOR. ARCHITECTURAL PRESTIGE & DESIGNER UTILITY.</p>
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
            for(let i=1; i<=3; i++) {
                document.getElementById('dot-' + i).classList.remove('active');
            }
            document.getElementById('dot-' + step).classList.add('active');
            
            const progressTexts = ["Progress 33%", "Progress 66%", "Progress 100%"];
            document.getElementById('progress-text').innerText = progressTexts[step-1];

            // Scroll to top of form for mobile
            window.scrollTo({ top: 0, behavior: 'smooth' });
        }

        // 2. Password Toggle
        function togglePass() {
            const pass = document.getElementById('passInput');
            const icon = document.getElementById('eyeIcon');
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