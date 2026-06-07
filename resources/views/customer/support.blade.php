@extends('customer.layouts.app')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm border-0" style="border-radius: 15px;">
                <div class="card-body p-5">
                    <h2 class="fw-bold mb-4">Pusat Bantuan & Komplain</h2>
                    <p class="text-muted mb-4">Punya kendala dengan pesanan atau akun Anda? Sampaikan kepada tim kami.</p>

                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <form action="{{ route('customer.support.submit') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label for="subject" class="form-label fw-600">Subjek Komplain</label>
                            <input type="text" name="subject" id="subject" class="form-control" placeholder="Contoh: Kendala Pembayaran, Ganti Alamat, dll" required>
                        </div>

                        <div class="mb-4">
                            <label for="message" class="form-label fw-600">Pesan / Detail Kendala</label>
                            <textarea name="message" id="message" rows="5" class="form-control" placeholder="Jelaskan kendala Anda secara detail agar kami dapat membantu lebih cepat..." required></textarea>
                        </div>

                        <button type="submit" class="btn btn-primary px-4 py-2" style="background: #B5733A; border: none; border-radius: 8px;">
                            Kirim Komplain
                        </button>
                    </form>
                </div>
            </div>

            <div class="mt-5">
                <h4 class="fw-bold mb-3">Riwayat Komplain</h4>
                @php
                    $mySupports = \App\Models\Support::where('user_id', Auth::id())->latest()->get();
                @endphp

                @forelse($mySupports as $support)
                    <div class="card border-0 shadow-sm mb-3" style="border-radius: 12px;">
                        <div class="card-body p-4">
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <h5 class="fw-bold mb-0">{{ $support->subject }}</h5>
                                <span class="badge bg-{{ $support->status === 'pending' ? 'warning' : ($support->status === 'replied' ? 'info' : 'success') }}">
                                    {{ ucfirst($support->status) }}
                                </span>
                            </div>
                            <p class="text-muted small mb-3">{{ $support->created_at->format('d M Y, H:i') }}</p>
                            <div class="bg-light p-3 rounded mb-3">
                                <p class="mb-0">{{ $support->message }}</p>
                            </div>

                            @if($support->admin_reply)
                                <div class="mt-3 p-3 rounded" style="background: #F7F0E8; border-left: 4px solid #B5733A;">
                                    <p class="fw-bold mb-1"><i class="fas fa-reply me-2"></i>Balasan Admin:</p>
                                    <p class="mb-0">{{ $support->admin_reply }}</p>
                                </div>
                            @endif
                        </div>
                    </div>
                @empty
                    <div class="text-center py-4 text-muted">
                        <p>Belum ada riwayat komplain.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection