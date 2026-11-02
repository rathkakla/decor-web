<?php

namespace App\Http\Controllers\B2b\Seller;

use App\Http\Controllers\Controller;
use App\Models\B2bOrder;
use App\Models\B2bQuotation;
use App\Models\B2bRfq;
use App\Models\Seller;
use Illuminate\Http\Request;
use Illuminate\View\View;

class QuotationController extends Controller
{
    public function create(B2bRfq $rfq): View
    {
        $seller = $this->getSeller();
        $this->authorizeRfq($rfq, $seller);

        // Only allow if RFQ is PENDING or QUOTED (for revision)
        abort_unless(in_array($rfq->status, [B2bRfq::STATUS_PENDING, B2bRfq::STATUS_QUOTED]), 403, 'RFQ tidak dapat dikutip saat ini.');

        $rfq->load(['designer.user']);

        // Pre-fill items from RFQ if available
        $items = $rfq->items ?? [];

        return view('seller.b2b.quotation_builder', [
            'rfq' => $rfq,
            'quotation' => null,
            'items' => $items,
            'isEdit' => false,
        ]);
    }

    public function store(Request $request): \Illuminate\Http\RedirectResponse
    {
        $seller = $this->getSeller();

        $validated = $request->validate([
            'rfq_id' => ['required', 'exists:b2b_rfqs,id'],
            'material_cost' => ['required', 'numeric', 'min:0'],
            'labor_cost' => ['required', 'numeric', 'min:0'],
            'shipping_cost' => ['nullable', 'numeric', 'min:0'],
            'estimated_days' => ['required', 'integer', 'min:1'],
            'valid_until' => ['required', 'date', 'after_or_equal:today'],
            'warranty_notes' => ['nullable', 'string', 'max:255'],
            'notes' => ['nullable', 'string'],
            'items' => ['nullable', 'array'],
            'items.*.name' => ['required_with:items', 'string'],
            'items.*.qty' => ['required_with:items', 'integer', 'min:1'],
            'items.*.unit_price' => ['required_with:items', 'numeric', 'min:0'],
        ]);

        $rfq = B2bRfq::findOrFail($validated['rfq_id']);
        $this->authorizeRfq($rfq, $seller);

        // PPN dan DP adalah ketentuan platform yang tetap (tidak bisa diubah seller).
        $materialCost = (float) $validated['material_cost'];
        $laborCost = (float) $validated['labor_cost'];
        $shippingCost = (float) ($validated['shipping_cost'] ?? 0);
        $taxPercent = 11.0;

        $subtotal = $materialCost + $laborCost + $shippingCost;
        $tax = $subtotal * ($taxPercent / 100);
        $totalPrice = round($subtotal + $tax, 2);
        $dpPercentage = 50;
        $dpAmount = round($totalPrice * ($dpPercentage / 100), 2);

        $quotation = B2bQuotation::create([
            'rfq_id' => $rfq->id,
            'material_cost' => $materialCost,
            'labor_cost' => $laborCost,
            'shipping_cost' => $shippingCost,
            'tax_percent' => $taxPercent,
            'total_price' => $totalPrice,
            'dp_percentage' => $dpPercentage,
            'dp_amount' => $dpAmount,
            'estimated_days' => $validated['estimated_days'],
            'valid_until' => $validated['valid_until'],
            'warranty_notes' => $validated['warranty_notes'] ?? null,
            'notes' => $validated['notes'] ?? null,
            'items' => $validated['items'] ?? null,
            'status' => B2bQuotation::STATUS_SENT,
        ]);

        // Update RFQ status
        $rfq->update(['status' => B2bRfq::STATUS_QUOTED]);

        return redirect()
            ->route('seller.b2b.inbox')
            ->with('success', "Quotation {$quotation->quotation_number} berhasil dikirim ke desainer.");
    }

    public function edit(B2bQuotation $quotation): View
    {
        $seller = $this->getSeller();
        $this->authorizeQuotation($quotation, $seller);

        abort_unless(
            in_array($quotation->status, [B2bQuotation::STATUS_SENT, B2bQuotation::STATUS_REVISED, B2bQuotation::STATUS_DRAFT], true),
            403,
            'Quotation yang sudah disetujui tidak bisa diubah.'
        );

        $quotation->load(['rfq.designer.user']);
        $items = $quotation->items ?? $quotation->rfq->items ?? [];

        return view('seller.b2b.quotation_builder', [
            'rfq' => $quotation->rfq,
            'quotation' => $quotation,
            'items' => $items,
            'isEdit' => true,
        ]);
    }

    public function update(Request $request, B2bQuotation $quotation): \Illuminate\Http\RedirectResponse
    {
        $seller = $this->getSeller();
        $this->authorizeQuotation($quotation, $seller);

        abort_unless($quotation->status === B2bQuotation::STATUS_SENT || $quotation->status === B2bQuotation::STATUS_REVISED, 403, 'Quotation tidak bisa diubah.');

        $validated = $request->validate([
            'material_cost' => ['required', 'numeric', 'min:0'],
            'labor_cost' => ['required', 'numeric', 'min:0'],
            'shipping_cost' => ['nullable', 'numeric', 'min:0'],
            'estimated_days' => ['required', 'integer', 'min:1'],
            'valid_until' => ['required', 'date', 'after_or_equal:today'],
            'warranty_notes' => ['nullable', 'string', 'max:255'],
            'notes' => ['nullable', 'string'],
            'items' => ['nullable', 'array'],
        ]);

        // PPN dan DP adalah ketentuan platform yang tetap (tidak bisa diubah seller).
        $materialCost = (float) $validated['material_cost'];
        $laborCost = (float) $validated['labor_cost'];
        $shippingCost = (float) ($validated['shipping_cost'] ?? 0);
        $taxPercent = 11.0;

        $subtotal = $materialCost + $laborCost + $shippingCost;
        $tax = $subtotal * ($taxPercent / 100);
        $totalPrice = round($subtotal + $tax, 2);
        $dpPercentage = 50;
        $dpAmount = round($totalPrice * ($dpPercentage / 100), 2);

        $quotation->update([
            'material_cost' => $materialCost,
            'labor_cost' => $laborCost,
            'shipping_cost' => $shippingCost,
            'tax_percent' => $taxPercent,
            'total_price' => $totalPrice,
            'dp_percentage' => $dpPercentage,
            'dp_amount' => $dpAmount,
            'estimated_days' => $validated['estimated_days'],
            'valid_until' => $validated['valid_until'],
            'warranty_notes' => $validated['warranty_notes'] ?? $quotation->warranty_notes,
            'notes' => $validated['notes'] ?? $quotation->notes,
            'items' => $validated['items'] ?? $quotation->items,
            'status' => B2bQuotation::STATUS_REVISED,
        ]);

        return redirect()
            ->route('seller.b2b.inbox')
            ->with('success', "Quotation {$quotation->quotation_number} diperbarui.");
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

    private function authorizeQuotation(B2bQuotation $quotation, Seller $seller): void
    {
        abort_unless($quotation->rfq->seller_id === $seller->id, 403, 'Quotation ini bukan milik Anda.');
    }
}