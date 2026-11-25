<?php

namespace App\Http\Controllers\B2b\Seller;

use App\Http\Controllers\Controller;
use App\Models\B2bOrder;
use App\Models\B2bRfq;
use App\Models\Seller;
use Illuminate\Http\Request;
use Illuminate\View\View;

class RfqController extends Controller
{
    public function index(Request $request): View
    {
        $seller = $this->getSeller();

        $query = B2bRfq::with(['designer.user', 'latestQuotation', 'order'])
            ->where('seller_id', $seller->id)
            ->latest();

        // Filter by status (mendukung status RFQ dan status pesanan lanjutan)
        if ($request->filled('status')) {
            $status = $request->status;
            if ($status === 'COMPLETED') {
                $query->whereHas('order', fn ($q) => $q->where('status', B2bOrder::STATUS_COMPLETED));
            } elseif ($status === 'SHIPPING') {
                $query->whereHas('order', fn ($q) => $q->whereIn('status', [
                    B2bOrder::STATUS_READY_FOR_DELIVERY,
                    B2bOrder::STATUS_DELIVERED,
                ]));
            } elseif ($status === 'IN_PRODUCTION') {
                $query->where('status', B2bRfq::STATUS_IN_ORDER)
                    ->whereHas('order', fn ($q) => $q->whereIn('status', [
                        B2bOrder::STATUS_DP_PENDING,
                        B2bOrder::STATUS_IN_PRODUCTION,
                    ]));
            } else {
                $query->where('status', $status);
            }
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

        $rfqs = $query->paginate(5)->withQueryString();

        // Stats for header cards
        $completedOrders = B2bOrder::whereHas('rfq', fn ($q) => $q->where('seller_id', $seller->id))
            ->whereIn('status', [B2bOrder::STATUS_COMPLETED, B2bOrder::STATUS_DELIVERED]);

        $stats = [
            'pending_quotation' => B2bRfq::where('seller_id', $seller->id)
                ->where('status', B2bRfq::STATUS_PENDING)
                ->count(),
            'potential_value' => B2bRfq::where('seller_id', $seller->id)
                ->where('status', B2bRfq::STATUS_PENDING)
                ->sum('budget_ceiling'),
            'capacity_available' => $seller->monthly_capacity ?? 0,
            'completed_projects' => (clone $completedOrders)->count(),
            'paid_projects' => B2bOrder::whereHas('rfq', fn ($q) => $q->where('seller_id', $seller->id))
                ->where('payout_status', B2bOrder::PAYOUT_PAID)
                ->count(),
            'paid_revenue' => B2bOrder::whereHas('rfq', fn ($q) => $q->where('seller_id', $seller->id))
                ->where('payout_status', B2bOrder::PAYOUT_PAID)
                ->sum('total_amount'),
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
            'order',
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