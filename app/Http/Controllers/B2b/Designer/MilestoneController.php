<?php

namespace App\Http\Controllers\B2b\Designer;

use App\Http\Controllers\Controller;
use App\Models\B2bOrder;
use App\Models\B2bMilestone;
use App\Models\Designer;
use Illuminate\Http\Request;
use Illuminate\View\View;

class MilestoneController extends Controller
{
    public function show(B2bOrder $order): View
    {
        $designer = $this->getDesigner();
        $this->authorizeOrder($order, $designer);

        $order->load([
            'rfq.seller.user',
            'rfq.designer.user',
            'quotation',
            'milestones',
            'delivery',
            'bast',
        ]);

        return view('designer.b2b.milestone_tracker', compact('order'));
    }

    public function payFinal(Request $request, B2bOrder $order): \Illuminate\Http\RedirectResponse
    {
        $designer = $this->getDesigner();
        $this->authorizeOrder($order, $designer);

        abort_unless($order->payment_status === B2bOrder::PAYMENT_DP_HELD, 403, 'Pelunasan tidak tersedia.');
        abort_unless($order->remaining_amount > 0, 403, 'Tidak ada sisa tagihan yang perlu dilunasi.');

        // Brief tahap 5: pelunasan hanya boleh setelah seluruh pos produksi lulus QC.
        $milestones = $order->milestones;
        abort_unless(
            $milestones->isNotEmpty() && $milestones->every(fn ($m) => $m->progress_percent >= 100),
            403,
            'Seluruh milestone produksi harus 100% sebelum pelunasan.'
        );

        $finalAmount = (float) $order->total_amount - (float) ($order->quotation->dp_amount ?? 0);

        // Simulate final payment
        $validated = $request->validate([
            'payment_proof' => ['nullable', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:5120'],
        ]);

        $paymentProofUrl = $request->file('payment_proof')?->store('b2b/payments', 'public');

        $order->update([
            'escrow_balance' => $order->total_amount,
            'payment_status' => B2bOrder::PAYMENT_FULL_HELD,
            'settled_at' => now(),
            'status' => B2bOrder::STATUS_READY_FOR_DELIVERY,
        ]);

        return redirect()
            ->route('designer.b2b.milestone', $order)
            ->with('success', 'Pelunasan Rp ' . number_format($finalAmount, 0, ',', '.') . ' berhasil disetor ke escrow. Dana 100% aman — menunggu Surat Jalan dari pabrik.');
    }

    private function getDesigner(): Designer
    {
        $designer = auth()->user()->designer;
        abort_unless($designer, 403, 'Akun designer tidak ditemukan.');
        return $designer;
    }

    private function authorizeOrder(B2bOrder $order, Designer $designer): void
    {
        abort_unless($order->rfq->designer_id === $designer->id, 403, 'Order ini bukan milik Anda.');
    }
}