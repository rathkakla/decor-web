{{-- Flash messages: dipakai di halaman customer agar error/success dari controller terlihat --}}
@if(session('error') || session('success'))
    <div class="content-container px-6 pt-6">
        @if(session('error'))
            <div class="flex items-start gap-3 bg-red-50 border border-red-100 rounded-xl px-4 py-3">
                <i class="fa-solid fa-circle-exclamation text-red-500 text-sm mt-0.5"></i>
                <p class="text-[12px] text-red-600 leading-relaxed font-medium">{{ session('error') }}</p>
            </div>
        @endif
        @if(session('success'))
            <div class="flex items-start gap-3 bg-emerald-50 border border-emerald-100 rounded-xl px-4 py-3 {{ session('error') ? 'mt-3' : '' }}">
                <i class="fa-solid fa-circle-check text-emerald-500 text-sm mt-0.5"></i>
                <p class="text-[12px] text-emerald-700 leading-relaxed font-medium">{{ session('success') }}</p>
            </div>
        @endif
    </div>
@endif
