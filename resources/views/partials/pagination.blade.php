@if ($paginator->total() > 0)
    @php
        $standalone = $standalone ?? false;
        $label = $label ?? 'data';
    @endphp
    <div class="{{ $standalone ? 'bg-white rounded-xl border border-gray-100 shadow-sm px-6 py-4' : 'px-6 py-4 border-t border-gray-100 bg-gray-50/50' }} flex flex-col sm:flex-row items-center justify-between gap-3">
        <div class="text-xs font-semibold text-gray-500">
            Halaman {{ $paginator->currentPage() }} dari {{ $paginator->lastPage() }} — Menampilkan {{ $paginator->firstItem() }}–{{ $paginator->lastItem() }} dari {{ $paginator->total() }} {{ $label }}
        </div>
        <div class="flex items-center gap-1.5">
            @if ($paginator->onFirstPage())
                <span class="w-8 h-8 rounded-lg bg-white border border-gray-200 text-gray-300 flex items-center justify-center cursor-not-allowed" aria-disabled="true">
                    <i class="fa-solid fa-chevron-left text-[10px]"></i>
                </span>
            @else
                <a href="{{ $paginator->previousPageUrl() }}" rel="prev" aria-label="Halaman sebelumnya" class="w-8 h-8 rounded-lg bg-white border border-gray-200 text-gray-600 hover:border-primary hover:text-primary flex items-center justify-center transition-colors">
                    <i class="fa-solid fa-chevron-left text-[10px]"></i>
                </a>
            @endif

            @if ($paginator->hasPages())
                @foreach ($paginator->linkCollection()->slice(1, -1) as $link)
                    @if ($link['active'])
                        <span class="w-8 h-8 rounded-lg text-xs font-bold bg-primary text-white shadow-sm flex items-center justify-center">{{ $link['label'] }}</span>
                    @elseif ($link['url'])
                        <a href="{{ $link['url'] }}" class="w-8 h-8 rounded-lg text-xs font-bold bg-white border border-gray-200 text-gray-600 hover:border-primary hover:text-primary flex items-center justify-center">{{ $link['label'] }}</a>
                    @else
                        <span class="w-8 h-8 rounded-lg text-xs font-bold bg-white border border-gray-200 text-gray-400 flex items-center justify-center">{{ $link['label'] }}</span>
                    @endif
                @endforeach
            @else
                <span class="w-8 h-8 rounded-lg text-xs font-bold bg-primary text-white shadow-sm flex items-center justify-center">1</span>
            @endif

            @if ($paginator->hasMorePages())
                <a href="{{ $paginator->nextPageUrl() }}" rel="next" aria-label="Halaman berikutnya" class="w-8 h-8 rounded-lg bg-white border border-gray-200 text-gray-600 hover:border-primary hover:text-primary flex items-center justify-center transition-colors">
                    <i class="fa-solid fa-chevron-right text-[10px]"></i>
                </a>
            @else
                <span class="w-8 h-8 rounded-lg bg-white border border-gray-200 text-gray-300 flex items-center justify-center cursor-not-allowed" aria-disabled="true">
                    <i class="fa-solid fa-chevron-right text-[10px]"></i>
                </span>
            @endif
        </div>
    </div>
@endif