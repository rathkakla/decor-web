<?php

namespace App\Http\Controllers\B2b\Seller;

use App\Http\Controllers\Controller;
use App\Models\B2bRfq;
use App\Models\Seller;
use Illuminate\Http\Request;
use Illuminate\View\View;

class RfqController extends Controller
{
    public function index(Request $request): View
    {
        $seller = $this->getSeller();

        $query = B2bRfq::with(['designer.user', 'latestQuotation'])
            ->where('seller_id', $seller->id)
            ->latest();

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('project_name', 'like', "%{$search}%")
                    ->orWhere('rfq_number', 'like', "%{$search}%")
                    ->orWhereHas('designer.user', fn ($q) => $q->where('full_name', 'like', "%{$search}%"));
            });
        }

        $rfqs = $query->paginate(15)->withQueryString();

        // Stats for header cards
        $stats = [
            'pending_quotation' => B2bRfq::where('seller_id', $seller->id)
                ->where('status', B2bRfq::STATUS_PENDING)
                ->count(),
            'potential_value' => B2bRfq::where('seller_id', $seller->id)
                ->where('status', B2bRfq::STATUS_PENDING)
                ->sum('budget_ceiling'),
            'capacity_available' => $seller->monthly_capacity ?? 0,
        ];

        return view('seller.b2b.inbox', compact('rfqs', 'stats'));
    }

    public function show(B2bRfq $rfq): View
    {
        $seller = $this->getSeller();
        $this->authorizeRfq($rfq, $seller);

        $rfq->load([
            'designer.user',
            'latestQuotation',
            'negotiations.sender',
            'seller',
        ]);

        return view('seller.b2b.review_spesifikasi', compact('rfq'));
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