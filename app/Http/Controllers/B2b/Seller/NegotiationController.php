<?php

namespace App\Http\Controllers\B2b\Seller;

use App\Http\Controllers\Controller;
use App\Models\B2bNegotiation;
use App\Models\B2bRfq;
use App\Models\Seller;
use Illuminate\Http\Request;
use Illuminate\View\View;

class NegotiationController extends Controller
{
    public function show(B2bRfq $rfq): View
    {
        $seller = $this->getSeller();
        $this->authorizeRfq($rfq, $seller);

        $rfq->load([
            'designer.user',
            'seller',
            'latestQuotation',
            'negotiations.sender',
        ]);

        $rfq->negotiations()
            ->where('sender_id', '!=', auth()->id())
            ->where('is_read', false)
            ->update(['is_read' => true]);

        return view('seller.b2b.negosiasi_seller', compact('rfq'));
    }

    public function sendMessage(Request $request, B2bRfq $rfq): \Illuminate\Http\RedirectResponse
    {
        $seller = $this->getSeller();
        $this->authorizeRfq($rfq, $seller);

        $validated = $request->validate([
            'message' => ['required', 'string', 'max:5000'],
            'attachment' => ['nullable', 'file', 'mimes:jpg,jpeg,png,pdf,dwg,dxf', 'max:10240'],
        ]);

        $attachmentUrl = null;
        if ($request->hasFile('attachment')) {
            $attachmentUrl = $request->file('attachment')->store('b2b/negotiations', 'public');
        }

        B2bNegotiation::create([
            'rfq_id' => $rfq->id,
            'sender_id' => auth()->id(),
            'message' => $validated['message'],
            'attachment_url' => $attachmentUrl,
        ]);

        return back()->with('success', 'Pesan terkirim.');
    }

    public function updateSample(Request $request, B2bRfq $rfq): \Illuminate\Http\RedirectResponse
    {
        $seller = $this->getSeller();
        $this->authorizeRfq($rfq, $seller);

        $validated = $request->validate([
            'sample_status' => ['required', 'in:PREPARING,SENT_BY_COURIER'],
            'tracking_number' => ['nullable', 'string', 'max:100'],
            'courier_name' => ['nullable', 'string', 'max:50'],
        ]);

        $rfq->update([
            'sample_status' => $validated['sample_status'],
            'tracking_number' => $validated['tracking_number'] ?? null,
            'courier_name' => $validated['courier_name'] ?? null,
        ]);

        // Also add a system message to negotiations
        $statusLabels = [
            'PREPARING' => 'Sampel sedang disiapkan.',
            'SENT_BY_COURIER' => 'Sampel dikirim via ' . ($validated['courier_name'] ?? '-') . ' (Resi: ' . ($validated['tracking_number'] ?? '-') . ').',
        ];

        B2bNegotiation::create([
            'rfq_id' => $rfq->id,
            'sender_id' => auth()->id(),
            'message' => '[Sistem] ' . ($statusLabels[$validated['sample_status']] ?? 'Status sampel diperbarui.'),
        ]);

        return back()->with('success', 'Status sampel diperbarui dan notifikasi terkirim ke desainer.');
    }

    private function getSeller(): Seller
    {
        $seller = auth()->user()->seller;
        abort_unless($seller, 403, 'Akun seller tidak ditemukan.');
        return $seller;
    }

    private function authorizeRfq(B2bRfq $rfq, Seller $seller): void
    {
        abort_unless($rfq->seller_id === $seller->id, 403, 'RFQ ini bukan milik Anda.');
    }
}