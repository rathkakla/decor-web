<?php

namespace App\Http\Controllers\B2b\Seller;

use App\Http\Controllers\Controller;
use App\Models\B2bMilestone;
use App\Models\B2bOrder;
use App\Models\Seller;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProductionController extends Controller
{
    public function show(B2bOrder $order): View
    {
        $seller = $this->getSeller();
        $this->authorizeOrder($order, $seller);

        $order->load([
            'rfq.designer.user',
            'rfq.seller',
            'quotation',
            'milestones',
            'delivery',
        ]);

        return view('seller.b2b.manajemen_produksi', compact('order'));
    }

    public function updateMilestone(Request $request, B2bOrder $order, B2bMilestone $milestone): \Illuminate\Http\RedirectResponse
    {
        $seller = $this->getSeller();
        $this->authorizeOrder($order, $seller);

        abort_unless($milestone->order_id === $order->id, 403, 'Milestone tidak valid.');

        $validated = $request->validate([
            'progress_percent' => ['required', 'integer', 'min:0', 'max:100'],
            'target_date' => ['nullable', 'date'],
            'qc_notes' => ['nullable', 'string', 'max:1000'],
        ]);

        $milestone->update($validated);

        // Auto-update order status based on milestones
        $this->updateOrderStatus($order);

        return back()->with('success', "Progress {$milestone->stage_name} diperbarui ke {$validated['progress_percent']}%.");
    }

    public function uploadQcPhoto(Request $request, B2bOrder $order, B2bMilestone $milestone): \Illuminate\Http\RedirectResponse
    {
        $seller = $this->getSeller();
        $this->authorizeOrder($order, $seller);

        abort_unless($milestone->order_id === $order->id, 403, 'Milestone tidak valid.');

        $validated = $request->validate([
            'photo' => ['required', 'file', 'mimes:jpg,jpeg,png', 'max:5120'],
        ]);

        $path = $request->file('photo')->store('b2b/qc', 'public');

        $milestone->update(['photo_url' => $path]);

        return back()->with('success', 'Foto QC berhasil diunggah.');
    }

    private function updateOrderStatus(B2bOrder $order): void
    {
        $milestones = $order->milestones;

        if ($milestones->isEmpty()) {
            return;
        }

        $allComplete = $milestones->every(fn ($m) => $m->progress_percent >= 100);

        if ($allComplete) {
            // Check if full payment received
            if ($order->payment_status === B2bOrder::PAYMENT_FULL_HELD) {
                $order->update(['status' => B2bOrder::STATUS_READY_FOR_DELIVERY]);
            } else {
                $order->update(['status' => B2bOrder::STATUS_IN_PRODUCTION]);
            }
        } else {
            $order->update(['status' => B2bOrder::STATUS_IN_PRODUCTION]);
        }
    }

    private function getSeller(): Seller
    {
        $seller = auth()->user()->seller;
        abort_unless($seller, 403, 'Akun seller tidak ditemukan.');
        return $seller;
    }

    private function authorizeOrder(B2bOrder $order, Seller $seller): void
    {
        abort_unless($order->rfq->seller_id === $seller->id, 403, 'Order ini bukan milik Anda.');
    }
}