<?php

namespace App\Http\Controllers\B2b\Designer;

use App\Http\Controllers\Controller;
use App\Models\B2bNegotiation;
use App\Models\B2bRfq;
use App\Models\Designer;
use Illuminate\Http\Request;
use Illuminate\View\View;

class NegotiationController extends Controller
{
    public function show(B2bRfq $rfq): View
    {
        $designer = $this->getDesigner();
        $this->authorizeRfq($rfq, $designer);

        $rfq->load([
            'seller.user',
            'designer.user',
            'latestQuotation',
            'negotiations.sender',
        ]);

        $rfq->negotiations()
            ->where('sender_id', '!=', auth()->id())
            ->where('is_read', false)
            ->update(['is_read' => true]);

        return view('designer.b2b.negosiasi_teknis', compact('rfq'));
    }

    public function sendMessage(Request $request, B2bRfq $rfq): \Illuminate\Http\RedirectResponse
    {
        $designer = $this->getDesigner();
        $this->authorizeRfq($rfq, $designer);

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

    public function approveSample(Request $request, B2bRfq $rfq): \Illuminate\Http\RedirectResponse
    {
        $designer = $this->getDesigner();
        $this->authorizeRfq($rfq, $designer);

        abort_unless($rfq->sample_status === B2bRfq::SAMPLE_SENT, 403, 'Sampel belum dikirim.');

        $rfq->update(['sample_status' => B2bRfq::SAMPLE_APPROVED]);

        B2bNegotiation::create([
            'rfq_id' => $rfq->id,
            'sender_id' => auth()->id(),
            'message' => '[Sistem] Desainer menyetujui sampel fisik kayu. Produksi bisa dilanjutkan.',
        ]);

        return back()->with('success', 'Sampel disetujui. Pabrik telah diberitahu untuk melanjutkan produksi.');
    }

    private function getDesigner(): Designer
    {
        $designer = auth()->user()->designer;
        abort_unless($designer, 403, 'Akun designer tidak ditemukan.');
        return $designer;
    }

    private function authorizeRfq(B2bRfq $rfq, Designer $designer): void
    {
        abort_unless($rfq->designer_id === $designer->id, 403, 'RFQ ini bukan milik Anda.');
    }
}