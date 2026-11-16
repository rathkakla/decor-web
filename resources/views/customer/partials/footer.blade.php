@php
    // Halaman dengan layout flex-col memakai mt-auto agar footer menempel di bawah;
    // halaman biasa memakai mt-12 seperti homepage.
    $footerClass = $footerClass ?? 'mt-12';
@endphp

<footer class="bg-primary text-white py-12 px-6 {{ $footerClass }}">
    <div class="max-w-6xl mx-auto">
        <div class="flex flex-col md:flex-row justify-between items-start border-b border-white/20 pb-10 gap-10">
            <div class="space-y-4">
                <div class="flex items-center space-x-2 text-white">
                    <div class="w-8 h-8 bg-white/20 rounded flex items-center justify-center">
                        <i class="fa-solid fa-couch text-sm"></i>
                    </div>
                    <span class="text-xl font-bold tracking-widest uppercase">DECOR</span>
                </div>
                <div class="text-sm text-white/90 space-y-2">
                    <p class="flex items-center"><i class="fa-regular fa-envelope mr-3 text-xs"></i>
                        decorofficial@gmail.com</p>
                    <p class="flex items-center"><i class="fa-brands fa-instagram mr-3 text-xs"></i> @decor_official
                    </p>
                </div>
            </div>

            <div
                class="grid grid-cols-2 gap-x-16 gap-y-4 text-[10px] font-bold tracking-widest uppercase text-white/90">
                <div class="flex flex-col space-y-3">
                    <a href="#" class="hover:text-white/70">Terms of Service</a>
                    <a href="#" class="hover:text-white/70">Privacy Policy</a>
                    <a href="{{ route('customer.credits') }}" class="hover:text-white/70">Image Credits</a>
                </div>
                <div class="flex flex-col space-y-3">
                    <a href="{{ route('download') }}" class="hover:text-white/70 transition-colors">App</a>
                    <a href="{{ route('customer.help-center') }}" class="hover:text-white/70 transition-colors">Help
                        Center</a>
                    <a href="#" class="hover:text-white/70">FAQ</a>
                </div>
            </div>
        </div>
        <div class="pt-8 text-[10px] text-white/60 font-medium tracking-widest">
            <p>&copy; 2026 DECOR MARKETPLACE. ALL RIGHTS RESERVED.</p>
        </div>
    </div>
</footer>
