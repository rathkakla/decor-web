<?php

namespace App\Http\Controllers\B2b\Seller;

use App\Http\Controllers\Controller;
use App\Models\B2bDelivery;
use App\Models\B2bOrder;
use App\Models\Seller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class DeliveryController extends Controller
{
    public function show(B2bOrder $order): View
    {
        $seller = $this->getSeller();
        $this->authorizeOrder($order, $seller);

        $order->load([
            'rfq.designer.user',
            'rfq.seller',
            'quotation',
            'delivery',
            'bast',
        ]);

        return view('seller.b2b.surat_jalan_payout', compact('order'));
    }

    public function store(Request $request): \Illuminate\Http\RedirectResponse
    {
        $seller = $this->getSeller();

        $validated = $request->validate([
            'order_id' => ['required', 'exists:b2b_orders,id'],
            'courier_service' => ['required', 'string', 'max:100'],
            'driver_name' => ['nullable', 'string', 'max:100'],
            'vehicle_plate' => ['nullable', 'string', 'max:20'],
            'delivery_date' => ['required', 'date'],
            'destination_address' => ['required', 'string'],
            'recipient_name' => ['nullable', 'string', 'max:100'],
            'recipient_phone' => ['nullable', 'string', 'max:30'],
        ]);

        $order = B2bOrder::with('rfq')->findOrFail($validated['order_id']);
        $this->authorizeOrder($order, $seller);

        abort_unless($order->status === B2bOrder::STATUS_READY_FOR_DELIVERY, 403, 'Order belum siap untuk dikirim.');

        $delivery = B2bDelivery::create([
            'order_id' => $order->id,
            'courier_service' => $validated['courier_service'],
            'driver_name' => $validated['driver_name'],
            'vehicle_plate' => $validated['vehicle_plate'],
            'delivery_date' => $validated['delivery_date'],
            'destination_address' => $validated['destination_address'],
            'recipient_name' => $validated['recipient_name'],
            'recipient_phone' => $validated['recipient_phone'],
            'status' => B2bDelivery::STATUS_PREPARING,
        ]);

        $order->update(['status' => B2bOrder::STATUS_DELIVERED]);

        return redirect()
            ->route('seller.b2b.surat_jalan', $order)
            ->with('success', "Surat Jalan {$delivery->surat_jalan_number} dibuat. Status order: DIKIRIM.");
    }

    public function update(Request $request, B2bDelivery $delivery): \Illuminate\Http\RedirectResponse
    {
        $seller = $this->getSeller();
        $this->authorizeDelivery($delivery, $seller);

        $validated = $request->validate([
            'status' => ['required', 'in:PREPARING,IN_TRANSIT,DELIVERED'],
            'driver_name' => ['nullable', 'string', 'max:100'],
            'vehicle_plate' => ['nullable', 'string', 'max:20'],
        ]);

        $delivery->update($validated);

        if ($validated['status'] === B2bDelivery::STATUS_DELIVERED) {
            $delivery->order->update(['status' => B2bOrder::STATUS_DELIVERED]);
        }

        return back()->with('success', 'Status pengiriman diperbarui.');
    }

    /**
     * Simpan tanda tangan digital (canvas) untuk petugas workshop, driver, atau penerima.
     */
    public function signature(Request $request, B2bDelivery $delivery): \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
    {
        $seller = $this->getSeller();
        $this->authorizeDelivery($delivery, $seller);

        $validated = $request->validate([
            // Tanda tangan via sistem hanya untuk petugas workshop (seller).
            // Driver & penerima menandatangani dokumen saat barang tiba.
            'role' => ['required', 'in:workshop'],
            'signature' => ['required', 'string', 'max:400000'],
        ]);

        $role = $validated['role'];
        $data = $validated['signature'];
        $prefix = 'data:image/png;base64,';

        abort_unless(str_starts_with($data, $prefix), 422, 'Format tanda tangan tidak valid.');

        $binary = base64_decode(substr($data, strlen($prefix)), true);
        abort_if($binary === false || strlen($binary) < 100, 422, 'Tanda tangan masih kosong.');

        $urlColumn = $role . '_signature_url';
        $atColumn = $role . '_signed_at';

        // Bersihkan file tanda tangan lama agar storage tidak menumpuk.
        if ($delivery->{$urlColumn}) {
            Storage::disk('public')->delete($delivery->{$urlColumn});
        }

        $path = 'b2b/deliveries/signatures/' . $delivery->id . '-' . $role . '-' . now()->format('YmdHis') . '-' . \Illuminate\Support\Str::lower(\Illuminate\Support\Str::random(6)) . '.png';
        Storage::disk('public')->put($path, $binary);

        $delivery->update([
            $urlColumn => $path,
            $atColumn => now(),
        ]);

        $labels = ['workshop' => 'Petugas workshop', 'driver' => 'Driver', 'recipient' => 'Penerima'];
        $message = 'Tanda tangan ' . $labels[$role] . ' berhasil disimpan.';

        if ($request->expectsJson()) {
            return response()->json([
                'ok' => true,
                'url' => asset('storage/' . $path),
                'signed_at' => $delivery->{$atColumn}?->format('d M Y H:i'),
                'message' => $message,
            ]);
        }

        return back()->with('success', $message);
    }

    public function requestPayout(Request $request, B2bOrder $order): \Illuminate\Http\RedirectResponse
    {
        $seller = $this->getSeller();
        $this->authorizeOrder($order, $seller);

        abort_unless($order->status === B2bOrder::STATUS_COMPLETED, 403, 'Order belum selesai.');
        abort_unless($order->payout_status === B2bOrder::PAYOUT_AVAILABLE, 403, 'Payout tidak tersedia.');

        $order->update([
            'payout_status' => B2bOrder::PAYOUT_REQUESTED,
            'payout_requested_at' => now(),
        ]);

        return back()->with('success', 'Permintaan pencairan dana diajukan. Dana akan diproses dalam 2x24 jam.');
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

    private function authorizeDelivery(B2bDelivery $delivery, Seller $seller): void
    {
        abort_unless($delivery->order->rfq->seller_id === $seller->id, 403, 'Surat jalan ini bukan milik Anda.');
    }
}