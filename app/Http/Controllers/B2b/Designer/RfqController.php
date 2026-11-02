<?php

namespace App\Http\Controllers\B2b\Designer;

use App\Http\Controllers\Controller;
use App\Models\B2bRfq;
use App\Models\Designer;
use App\Models\Seller;
use Illuminate\Http\Request;
use Illuminate\View\View;

class RfqController extends Controller
{
    public function create(Request $request, Seller $seller = null): View
    {
        $designer = $this->getDesigner();

        if ($seller) {
            abort_unless($seller->b2b_enabled && $seller->status === 'approved', 404);
        } else {
            // If no seller specified, redirect to katalog
            return redirect()->route('designer.b2b.katalog');
        }

        return view('designer.b2b.pengajuan_rfq', compact('seller'));
    }

    public function store(Request $request): \Illuminate\Http\RedirectResponse
    {
        $designer = $this->getDesigner();

        // Buang baris item kosong yang tidak sengaja terkirim dari form dinamis.
        $items = collect($request->input('items', []))
            ->filter(fn ($item) => is_array($item) && filled($item['name'] ?? null))
            ->values()
            ->all();

        $request->merge(['items' => $items]);

        $validated = $request->validate([
            'seller_id' => ['required', 'exists:sellers,id'],
            'project_name' => ['required', 'string', 'max:255'],
            'wood_type' => ['required', 'string', 'max:100'],
            'budget_ceiling' => ['nullable', 'numeric', 'min:0'],
            'deadline' => ['required', 'date', 'after_or_equal:today'],
            'delivery_address' => ['required', 'string'],
            'description' => ['nullable', 'string'],
            'items' => ['nullable', 'array'],
            'items.*.name' => ['required_with:items', 'string'],
            'items.*.qty' => ['required_with:items', 'integer', 'min:1'],
            'items.*.unit' => ['required_with:items', 'string'],
            'items.*.note' => ['nullable', 'string'],
            'cad_file' => ['nullable', 'file', 'mimes:dwg,dxf,pdf', 'max:20480'],
            'boq_file' => ['nullable', 'file', 'mimes:xlsx,xls,csv', 'max:10240'],
            'moodboard_file' => ['nullable', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:10240'],
        ]);

        $seller = Seller::findOrFail($validated['seller_id']);
        abort_unless($seller->b2b_enabled && $seller->status === 'approved', 403, 'Pabrik tidak menerima RFQ saat ini.');

        $cadUrl = $request->file('cad_file')?->store('b2b/rfq/cad', 'public');
        $boqUrl = $request->file('boq_file')?->store('b2b/rfq/boq', 'public');
        $moodboardUrl = $request->file('moodboard_file')?->store('b2b/rfq/moodboard', 'public');

        $rfq = B2bRfq::create([
            'designer_id' => $designer->id,
            'seller_id' => $seller->id,
            'project_name' => $validated['project_name'],
            'wood_type' => $validated['wood_type'],
            'budget_ceiling' => $validated['budget_ceiling'] ?? 0,
            'deadline' => $validated['deadline'],
            'delivery_address' => $validated['delivery_address'],
            'description' => $validated['description'] ?? null,
            'items' => $validated['items'] ?? null,
            'cad_url' => $cadUrl,
            'boq_url' => $boqUrl,
            'moodboard_url' => $moodboardUrl,
            'sample_status' => B2bRfq::SAMPLE_NOT_REQUESTED,
            'status' => B2bRfq::STATUS_PENDING,
        ]);

        return redirect()
            ->route('designer.b2b.negosiasi', $rfq)
            ->with('success', "RFQ {$rfq->rfq_number} berhasil dikirim ke {$seller->store_name}. Menunggu penawaran dari pabrik.");
    }

    private function getDesigner(): Designer
    {
        $designer = auth()->user()->designer;
        abort_unless($designer, 403, 'Akun designer tidak ditemukan.');
        return $designer;
    }
}