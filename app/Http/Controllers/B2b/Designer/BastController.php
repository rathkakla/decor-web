<?php

namespace App\Http\Controllers\B2b\Designer;

use App\Http\Controllers\Controller;
use App\Models\B2bBast;
use App\Models\B2bOrder;
use App\Models\Designer;
use Illuminate\Http\Request;
use Illuminate\View\View;

class BastController extends Controller
{
    public function show(B2bOrder $order): View
    {
        $designer = $this->getDesigner();
        $this->authorizeOrder($order, $designer);

        abort_unless($order->status === B2bOrder::STATUS_DELIVERED || $order->status === B2bOrder::STATUS_COMPLETED, 403, 'BAST hanya bisa diakses setelah barang dikirim.');

        $order->load([
            'rfq.seller.user',
            'rfq.designer.user',
            'quotation',
            'delivery',
            'bast',
            'milestones',
        ]);

        // Default inspection checklist
        $defaultChecklist = [
            ['label' => 'Konstruksi sambungan kokoh & tidak goyang', 'checked' => false],
            ['label' => 'Finishing rata, halus, tanpa cacat/retak', 'checked' => false],
            ['label' => 'Warna & tekstur kayu sesuai sampel yang disetujui', 'checked' => false],
            ['label' => 'Jumlah unit & item sesuai BoQ', 'checked' => false],
            ['label' => 'Perakitan di lokasi selesai & rapi', 'checked' => false],
            ['label' => 'Area kerja dibersihkan setelah instalasi', 'checked' => false],
            ['label' => 'Dokumentasi foto hasil instalasi lengkap', 'checked' => false],
        ];

        $checklist = $order->bast?->inspection_checklist ?? $defaultChecklist;

        return view('designer.b2b.bast_serah_terima', compact('order', 'checklist'));
    }

    public function sign(Request $request, B2bOrder $order): \Illuminate\Http\RedirectResponse
    {
        $designer = $this->getDesigner();
        $this->authorizeOrder($order, $designer);

        abort_unless($order->status === B2bOrder::STATUS_DELIVERED || $order->status === B2bOrder::STATUS_COMPLETED, 403, 'BAST hanya bisa ditandatangani setelah barang dikirim.');

        $validated = $request->validate([
            'inspection_checklist' => ['required', 'array'],
            'inspection_checklist.*.label' => ['required', 'string'],
            'inspection_checklist.*.checked' => ['required', 'boolean'],
            'signature' => ['required', 'file', 'mimes:png,jpg,jpeg', 'max:2048'],
            'photo_installed' => ['required', 'file', 'mimes:jpg,jpeg,png', 'max:5120'],
            'is_accepted' => ['required', 'boolean'],
        ]);

        $signatureUrl = $request->file('signature')->store('b2b/bast/signatures', 'public');
        $photoUrl = $request->file('photo_installed')->store('b2b/bast/photos', 'public');

        $bast = B2bBast::updateOrCreate(
            ['order_id' => $order->id],
            [
                'designer_signature_url' => $signatureUrl,
                'photo_installed_url' => $photoUrl,
                'inspection_checklist' => $validated['inspection_checklist'],
                'is_accepted' => $validated['is_accepted'],
                'signed_at' => now(),
            ]
        );

        if ($validated['is_accepted']) {
            // Release escrow to seller
            $order->update([
                'payment_status' => B2bOrder::PAYMENT_RELEASED,
                'escrow_balance' => 0,
                'status' => B2bOrder::STATUS_COMPLETED,
                'payout_status' => B2bOrder::PAYOUT_AVAILABLE,
            ]);
            $message = 'BAST ditandatangani. Dana escrow dilepaskan ke seller. Status: SELESAI.';
        } else {
            $order->update([
                'status' => B2bOrder::STATUS_DELIVERED,
            ]);
            $message = 'BAST ditandatangani dengan catatan tidak disetujui. Silakan hubungi pabrik untuk perbaikan.';
        }

        return redirect()
            ->route('designer.b2b.bast', $order)
            ->with('success', $message);
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