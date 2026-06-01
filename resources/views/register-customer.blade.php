<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Decor - Begin Your Collection</title>
    
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#B5733A', /* Cokelat Tan/Kayu */
                        secondary: '#E3DCD6', /* Warm Grey / Off-White */
                    },
                    fontFamily: {
                        sans: ['Inter', 'ui-sans-serif', 'system-ui', 'sans-serif'],
                    }
                }
            }
        }
    </script>
    <style>
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
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-white min-h-screen flex font-sans antialiased overflow-x-hidden">

    <section class="hidden lg:flex lg:w-1/2 relative bg-gray-900 items-end p-20 overflow-hidden">
        <img src="https://images.unsplash.com/photo-1593455483017-ed620ec55640?ixlib=rb-4.0.3&auto=format&fit=crop&w=1200&q=80" 
             class="absolute inset-0 w-full h-full object-cover opacity-70" alt="Interior">
        
        <div class="relative z-10 w-full">
            <div class="flex items-center gap-4 mb-24 absolute top-[-30rem]">
                <span class="text-white font-extrabold text-2xl tracking-widest uppercase">DECOR</span>
                <div class="h-[2px] w-12 bg-white mt-1"></div>
            </div>

            <div class="space-y-6">
                <p class="text-gray-300 text-xs font-bold uppercase tracking-[0.2em]">The Digital Curator</p>
                <h1 class="text-white text-6xl font-extrabold leading-tight">
                    Space is the breath <br> of art.
                </h1>
                <p class="text-gray-300 text-sm italic font-medium max-w-md leading-relaxed border-l-2 border-primary pl-4">
                    "Join a community where every piece of furniture tells a story of craftsmanship and curated intent."
                </p>
            </div>
        </div>
    </section>

    <section class="w-full lg:w-1/2 flex flex-col p-8 md:p-16 lg:p-20 relative">
        
        <div class="absolute top-10 right-10">
            <a href="/" class="text-[10px] font-bold text-gray-500 uppercase tracking-widest flex items-center gap-2 hover:text-primary transition">
                Back to gallery <i class="fa-solid fa-xmark text-sm"></i>
            </a>
        </div>

        <div class="max-w-md mx-auto w-full flex-grow flex flex-col justify-center">
            <div class="mb-10">
                <h2 class="text-3xl font-extrabold text-gray-900 mb-2">Begin your collection.</h2>
                <p class="text-gray-500 text-sm">Enter your details to create an account.</p>
            </div>
    @if ($errors->any())
    <div class="bg-red-100 text-red-700 p-3 rounded mb-5 text-xs font-bold uppercase">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
    <form action="{{ route('register') }}" method="POST">
    @csrf
    
    <div class="mb-6">
        <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-2">Username</label>
        <input type="text" name="username" required class="w-full border-b border-gray-200 py-2 focus:outline-none focus:border-primary text-sm">
    </div>

    <div class="mb-6">
        <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-2">Full Name</label>
        <input type="text" name="name" required class="w-full border-b border-gray-200 py-2 focus:outline-none focus:border-primary text-sm">
    </div>

    <div class="mb-6">
        <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-2">Email Address</label>
        <input type="email" name="email" required class="w-full border-b border-gray-200 py-2 focus:outline-none focus:border-primary text-sm">
    </div>

    <div class="mb-6 relative">
        <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-2">Password</label>
        <div class="relative">
            <input type="password" id="password" name="password" required class="w-full border-b border-gray-200 py-2 pr-10 focus:outline-none focus:border-primary text-sm">
            <button type="button" onclick="togglePasswordVisibility('password', 'password-toggle-icon')" class="absolute right-2 top-1/2 -translate-y-1/2 text-gray-400 hover:text-primary focus:outline-none">
                <i id="password-toggle-icon" class="fa-solid fa-eye-slash"></i>
            </button>
        </div>
    </div>

    <div class="mb-10 relative">
        <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-2">Confirm Password</label>
        <div class="relative">
            <input type="password" id="password_confirmation" name="password_confirmation" required class="w-full border-b border-gray-200 py-2 pr-10 focus:outline-none focus:border-primary text-sm">
            <button type="button" onclick="togglePasswordVisibility('password_confirmation', 'confirm-password-toggle-icon')" class="absolute right-2 top-1/2 -translate-y-1/2 text-gray-400 hover:text-primary focus:outline-none">
                <i id="confirm-password-toggle-icon" class="fa-solid fa-eye-slash"></i>
            </button>
        </div>
    </div>

    <div class="flex items-start space-x-3 mb-8">
        <input type="checkbox" id="terms-checkbox" disabled class="mt-1 w-4 h-4 text-primary border-gray-300 rounded focus:ring-primary cursor-not-allowed">
        <label for="terms-checkbox" class="text-[10px] text-gray-400 leading-relaxed uppercase font-bold tracking-wider">
            Saya menyetujui <button type="button" onclick="openTerms()" class="text-primary underline">Syarat & Ketentuan</button> yang berlaku pada platform DECOR.
        </label>
    </div>

    <button type="submit" id="submit-reg" disabled class="w-full bg-primary text-white py-4 rounded font-bold text-sm shadow-xl shadow-primary/20 opacity-50 cursor-not-allowed transition-all">
        Create Account
    </button>
</form>

<!-- Terms Modal -->
<div id="terms-modal" class="fixed inset-0 z-50 hidden overflow-y-auto bg-black/60 backdrop-blur-sm">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-white rounded-2xl max-w-2xl w-full p-8 shadow-2xl">
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-2xl font-bold text-gray-800">Syarat & Ketentuan Customer</h3>
                <button type="button" onclick="closeTerms()" class="text-gray-400 hover:text-gray-600 transition-colors"><i class="fa-solid fa-xmark text-xl"></i></button>
            </div>
            <div id="modal-terms-box" class="terms-container h-[400px] custom-scrollbar">
                <p class="mb-4">Dengan membuat akun dan menggunakan platform marketplace, pengguna dianggap telah membaca, memahami, dan menyetujui seluruh syarat dan ketentuan yang berlaku.</p>
                
                <h4 class="font-bold text-gray-800 mt-6 mb-2 uppercase text-[11px] tracking-wider">Ketentuan Penggunaan Akun</h4>
                <ul class="list-disc pl-5 space-y-2 text-gray-600">
                    <li>Customer wajib menjaga keamanan akun, termasuk menjaga kerahasiaan password dan data akun yang dimiliki. Segala aktivitas yang terjadi pada akun menjadi tanggung jawab pemilik akun.</li>
                    <li>Customer wajib memberikan data pribadi yang benar, lengkap, dan sesuai dengan identitas asli saat melakukan pendaftaran maupun transaksi pada platform.</li>
                    <li>Customer dilarang menggunakan platform untuk aktivitas yang melanggar hukum, merugikan pengguna lain, maupun mengganggu kenyamanan dan keamanan platform.</li>
                </ul>

                <h4 class="font-bold text-gray-800 mt-6 mb-2 uppercase text-[11px] tracking-wider">Ketentuan Transaksi</h4>
                <ul class="list-disc pl-5 space-y-2 text-gray-600">
                    <li>Customer wajib melakukan transaksi dengan itikad baik dan mengikuti seluruh prosedur transaksi, pembayaran, pengiriman, serta pengembalian barang yang berlaku pada platform.</li>
                    <li>Customer dilarang melakukan tindakan penipuan, manipulasi transaksi, penyalahgunaan voucher, spam pesanan, maupun aktivitas lain yang dapat merugikan seller, designer, maupun platform.</li>
                    <li>Customer wajib memberikan komplain, ulasan, maupun penilaian secara jujur, sopan, dan bertanggung jawab.</li>
                </ul>

                <h4 class="font-bold text-gray-800 mt-6 mb-2 uppercase text-[11px] tracking-wider">Etika Penggunaan Platform</h4>
                <ul class="list-disc pl-5 space-y-2 text-gray-600">
                    <li>Customer dilarang menggunakan kata-kata kasar, ujaran kebencian, penghinaan, ancaman, maupun tindakan yang mengandung unsur SARA, diskriminasi, pelecehan, atau perilaku tidak pantas terhadap pengguna lain maupun pihak platform.</li>
                    <li>Customer wajib menjaga etika dan kenyamanan bersama selama menggunakan fitur komunikasi, ulasan, maupun interaksi lainnya di dalam platform.</li>
                </ul>

                <h4 class="font-bold text-gray-800 mt-6 mb-2 uppercase text-[11px] tracking-wider">Hak Platform</h4>
                <ul class="list-disc pl-5 space-y-2 text-gray-600">
                    <li>Platform berhak memberikan peringatan, membatasi akses, melakukan suspend sementara, maupun menonaktifkan akun customer yang terbukti melanggar syarat dan ketentuan yang berlaku.</li>
                    <li>Platform berhak menghapus konten, komentar, ulasan, maupun aktivitas pengguna yang dianggap tidak pantas, merugikan pihak lain, atau melanggar ketentuan platform.</li>
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
    function togglePasswordVisibility(inputId, iconId) {
        const input = document.getElementById(inputId);
        const icon = document.getElementById(iconId);
        if (input.type === 'password') {
            input.type = 'text';
            icon.classList.remove('fa-eye-slash');
            icon.classList.add('fa-eye');
        } else {
            input.type = 'password';
            icon.classList.remove('fa-eye');
            icon.classList.add('fa-eye-slash');
        }
    }

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
</script>

            <div class="mt-10">
                <div class="relative flex items-center justify-center mb-8">
                    <div class="border-t border-gray-100 w-full"></div>
                    <span class="bg-white px-4 text-[9px] font-bold text-gray-300 uppercase tracking-[0.2em] absolute">Or join with</span>
                </div>

                <div class="flex gap-4">
                    <button class="flex-1 border border-gray-100 py-3 rounded flex items-center justify-center gap-3 hover:bg-gray-50 transition text-[11px] font-bold text-gray-600 shadow-sm">
                        <img src="https://www.svgrepo.com/show/475656/google-color.svg" class="w-4 h-4" alt="Google"> Google
                    </button>
                    <button class="flex-1 border border-gray-100 py-3 rounded flex items-center justify-center gap-3 hover:bg-gray-50 transition text-[11px] font-bold text-gray-600 shadow-sm">
                        <i class="fa-brands fa-apple text-lg"></i> Apple
                    </button>
                </div>
            </div>

            <div class="mt-auto pt-10 text-center">
                <p class="text-[11px] text-gray-400 font-bold tracking-wide">
                    Already part of the studio? <a href="/login" class="text-primary hover:underline underline-offset-4">Sign in here.</a>
                </p>
            </div>
        </div>

        <div class="absolute bottom-0 left-0 right-0 pointer-events-none -mb-10 text-center">
            <h3 class="text-[150px] font-extrabold text-gray-50 tracking-tighter leading-none opacity-50">DECOR</h3>
        </div>
    </section>

</body>
</html>