<?php

namespace App\Http\Controllers\B2b\Designer;

use App\Http\Controllers\Controller;
use App\Models\B2bRfq;
use App\Models\Seller;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CatalogController extends Controller
{
    public function index(Request $request): View
    {
        $designer = $this->getDesigner();

        $query = Seller::where('b2b_enabled', true)
            ->where('status', 'approved')
            ->with('user');

        // Search by wood specialties, store_name, city
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('store_name', 'like', "%{$search}%")
                    ->orWhere('city', 'like', "%{$search}%")
                    ->orWhereJsonContains('wood_specialties', $search);
            });
        }

        // Filter by city/region
        if ($request->filled('city')) {
            $query->where('city', $request->city);
        }

        // Filter by minimum capacity
        if ($request->filled('min_capacity')) {
            $query->where('monthly_capacity', '>=', $request->min_capacity);
        }

        // Filter by wood specialty
        if ($request->filled('wood_type')) {
            $query->whereJsonContains('wood_specialties', $request->wood_type);
        }

        $sellers = $query->latest()->paginate(12)->withQueryString();

        $rfqsBySeller = $this->latestRfqsBySeller($designer, $sellers->pluck('id')->all());

        // Get unique values for filter dropdowns
        $cities = Seller::where('b2b_enabled', true)
            ->whereNotNull('city')
            ->pluck('city')
            ->unique()
            ->sort()
            ->values();

        $woodTypes = Seller::where('b2b_enabled', true)
            ->whereNotNull('wood_specialties')
            ->pluck('wood_specialties')
            ->flatten()
            ->unique()
            ->sort()
            ->values();

        return view('designer.b2b.katalog_pabrik', compact('sellers', 'cities', 'woodTypes', 'rfqsBySeller'));
    }

    public function show(Seller $seller): View
    {
        $designer = $this->getDesigner();

        abort_unless($seller->b2b_enabled && $seller->status === 'approved', 404);

        $seller->load('user');

        // Tampilkan hanya pabrik terpilih, tetap memakai paginator agar view konsisten.
        $sellers = Seller::whereKey($seller->id)->paginate(12);
        $rfqsBySeller = $this->latestRfqsBySeller($designer, [$seller->id]);

        $cities = Seller::where('b2b_enabled', true)
            ->whereNotNull('city')
            ->pluck('city')
            ->unique()
            ->sort()
            ->values();

        $woodTypes = Seller::where('b2b_enabled', true)
            ->whereNotNull('wood_specialties')
            ->pluck('wood_specialties')
            ->flatten()
            ->unique()
            ->sort()
            ->values();

        return view('designer.b2b.katalog_pabrik', compact('sellers', 'cities', 'woodTypes', 'seller', 'rfqsBySeller'));
    }

    /**
     * RFQ terbaru milik desainer per pabrik, untuk tombol "Lihat Detail RFQ".
     *
     * @param  array<int, int>  $sellerIds
     * @return \Illuminate\Support\Collection<int, B2bRfq>
     */
    private function latestRfqsBySeller(\App\Models\Designer $designer, array $sellerIds)
    {
        if ($sellerIds === []) {
            return collect();
        }

        return B2bRfq::where('designer_id', $designer->id)
            ->whereIn('seller_id', $sellerIds)
            ->orderByDesc('id')
            ->get()
            ->groupBy('seller_id')
            ->map(fn ($group) => $group->first());
    }

    private function getDesigner(): \App\Models\Designer
    {
        $designer = auth()->user()->designer;
        abort_unless($designer, 403, 'Akun designer tidak ditemukan.');
        return $designer;
    }
}