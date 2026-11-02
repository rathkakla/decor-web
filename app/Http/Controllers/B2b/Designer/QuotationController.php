<?php

namespace App\Http\Controllers\B2b\Designer;

use App\Http\Controllers\Controller;
use App\Models\B2bOrder;
use App\Models\B2bQuotation;
use App\Models\B2bRfq;
use App\Models\Designer;
use Illuminate\Http\Request;
use Illuminate\View\View;

class QuotationController extends Controller
{
    public function show($id): View
    {
        $designer = $this->getDesigner();

        // Try to find by quotation ID first, then by RFQ ID
        $quotation = B2bQuotation::with([
            'rfq.designer.user',
            'rfq.seller.user',
        ])->find($id);

        if (!$quotation) {
            // Maybe it's an RFQ ID
            $rfq = B2bRfq::with('latestQuotation')->findOrFail($id);
            $quotation = $rfq->latestQuotation;
            abort_unless($quotation, 404, 'Belum ada quotation untuk RFQ ini.');
        }

        $this->authorizeQuotation($quotation, $designer);

        $quotation->load(['rfq.designer.user', 'rfq.seller.user']);

        return view('designer.b2b.quotation_review', compact('quotation'));
    }

    public function accept(Request $request, B2bQuotation $quotation): \Illuminate\Http\RedirectResponse
    {
        $designer = $this->getDesigner();
        $this->authorizeQuotation($quotation, $designer);

        abort_unless(
            in_array($quotation->status, [B2bQuotation::STATUS_SENT, B2bQuotation::STATUS_REVISED], true),
            403,
            'Quotation tidak bisa disetujui.'
        );

        $quotation->update(['status' => B2bQuotation::STATUS_ACCEPTED]);
        $quotation->rfq->update(['status' => B2bRfq::STATUS_IN_ORDER]);

        // Create order
        $order = B2bOrder::create([
            'rfq_id' => $quotation->rfq_id,
            'quotation_id' => $quotation->id,
            'total_amount' => $quotation->total_price,
            'escrow_balance' => 0, // Will be updated after DP payment
            'payment_status' => B2bOrder::PAYMENT_UNPAID,
            'status' => B2bOrder::STATUS_DP_PENDING,
        ]);

        $order->createMilestones();

        return redirect()
            ->route('designer.b2b.quotation', $quotation)
            ->with('success', "Penawaran {$quotation->quotation_number} disetujui. Order {$order->order_number} dibuat. Silakan bayar DP ke escrow.");
    }

    public function payDp(Request $request, B2bQuotation $quotation): \Illuminate\Http\RedirectResponse
    {
        $designer = $this->getDesigner();
        $this->authorizeQuotation($quotation, $designer);

        $order = B2bOrder::where('quotation_id', $quotation->id)->firstOrFail();

        abort_unless($order->payment_status === B2bOrder::PAYMENT_UNPAID, 403, 'DP sudah dibayar.');

        // Simulate DP payment - in real app this would integrate with payment gateway
        $validated = $request->validate([
            'payment_proof' => ['nullable', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:5120'],
        ]);

        $paymentProofUrl = $request->file('payment_proof')?->store('b2b/payments', 'public');

        $order->update([
            'escrow_balance' => $quotation->dp_amount,
            'payment_status' => B2bOrder::PAYMENT_DP_HELD,
            'dp_paid_at' => now(),
            'status' => B2bOrder::STATUS_IN_PRODUCTION,
        ]);

        return redirect()
            ->route('designer.b2b.negosiasi', $quotation->rfq)
            ->with('success', "DP Rp " . number_format($quotation->dp_amount, 0, ',', '.') . " berhasil disetor ke escrow. Produksi dimulai.");
    }

    private function getDesigner(): Designer
    {
        $designer = auth()->user()->designer;
        abort_unless($designer, 403, 'Akun designer tidak ditemukan.');
        return $designer;
    }

    private function authorizeQuotation(B2bQuotation $quotation, Designer $designer): void
    {
        abort_unless($quotation->rfq->designer_id === $designer->id, 403, 'Quotation ini bukan milik Anda.');
    }
}