<footer class="bg-primary text-white py-5 px-6 mt-auto">
    <div class="max-w-6xl mx-auto">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center border-b border-white/20 pb-4 gap-4">
            <div class="space-y-1.5">
                <div class="flex items-center space-x-2 text-white">
                    <div class="w-6 h-6 bg-white/20 rounded flex items-center justify-center">
                        <i class="fa-solid fa-couch text-xs"></i>
                    </div>
                    <span class="text-base font-bold tracking-widest uppercase">DECOR</span>
                </div>
                <div class="flex flex-wrap items-center gap-x-5 gap-y-1 text-xs text-white/90">
                    <p class="flex items-center"><i class="fa-regular fa-envelope mr-2 text-[11px]"></i>
                        decorofficial@gmail.com</p>
                    <p class="flex items-center"><i class="fa-brands fa-instagram mr-2 text-[11px]"></i> @decor_official
                    </p>
                </div>
            </div>

            <div
                class="grid grid-cols-2 sm:grid-cols-3 gap-x-10 gap-y-1.5 text-[10px] font-bold tracking-widest uppercase text-white/90">
                <a href="#" class="hover:text-white/70">Terms of Service</a>
                <a href="{{ route('download') }}" class="hover:text-white/70 transition-colors">App</a>
                <a href="#" class="hover:text-white/70">Privacy Policy</a>
                <a href="{{ route('customer.help-center') }}" class="hover:text-white/70 transition-colors">Help
                    Center</a>
                <a href="{{ route('customer.credits') }}" class="hover:text-white/70">Image Credits</a>
                <a href="#" class="hover:text-white/70">FAQ</a>
            </div>
        </div>
        <div class="pt-3 text-[9px] text-white/60 font-medium tracking-widest">
            <p>&copy; 2026 DECOR MARKETPLACE. ALL RIGHTS RESERVED.</p>
        </div>
    </div>
</footer>
