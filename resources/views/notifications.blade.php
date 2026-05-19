@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto py-12 px-6">
    <div class="flex items-center justify-between mb-10">
        <div>
            <h1 class="text-3xl font-black text-gray-900 tracking-tight uppercase">Notifikasi</h1>
            <p class="text-sm text-gray-500 mt-2">Pusat informasi dan peringatan sistem.</p>
        </div>
    </div>

    <div class="space-y-4">
        @forelse($notifications as $notification)
        <div class="bg-white p-6 rounded-2xl border {{ $notification->read_at ? 'border-gray-100 opacity-60' : 'border-primary/20 shadow-lg shadow-primary/5' }} transition-all">
            <div class="flex items-start gap-4">
                <div class="w-10 h-10 rounded-xl {{ $notification->data['type'] == 'warning' ? 'bg-red-50 text-red-500' : 'bg-primary/10 text-primary' }} flex items-center justify-center flex-shrink-0">
                    <i class="fa-solid fa-{{ $notification->data['icon'] ?? 'bell' }}"></i>
                </div>
                <div class="flex-1">
                    <div class="flex justify-between items-start">
                        <h3 class="font-bold text-gray-900">{{ $notification->data['title'] }}</h3>
                        <span class="text-[10px] text-gray-400 font-bold uppercase tracking-widest">{{ $notification->created_at->diffForHumans() }}</span>
                    </div>
                    <p class="text-sm text-gray-600 mt-1 leading-relaxed">{{ $notification->data['message'] }}</p>
                </div>
            </div>
        </div>
        @empty
        <div class="text-center py-20 bg-white rounded-3xl border border-dashed border-gray-200">
            <div class="w-16 h-16 bg-gray-50 text-gray-300 rounded-2xl flex items-center justify-center mx-auto mb-4">
                <i class="fa-solid fa-bell-slash text-2xl"></i>
            </div>
            <p class="text-gray-400 font-bold uppercase tracking-widest text-xs">Belum ada notifikasi</p>
        </div>
        @endforelse
        
        <div class="mt-8">
            {{ $notifications->links() }}
        </div>
    </div>
</div>
@endsection