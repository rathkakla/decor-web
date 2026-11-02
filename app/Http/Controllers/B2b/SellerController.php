<?php

namespace App\Http\Controllers\B2b;

use App\Http\Controllers\Controller;
use Illuminate\View\View;

class SellerController extends Controller
{
    public function inbox(): View
    {
        return view('seller.b2b.inbox');
    }

    public function review(): View
    {
        return view('seller.b2b.review_spesifikasi');
    }

    public function quotationBuilder(): View
    {
        return view('seller.b2b.quotation_builder');
    }

    public function negotiation(): View
    {
        return view('seller.b2b.negosiasi_seller');
    }

    public function production(): View
    {
        return view('seller.b2b.manajemen_produksi');
    }

    public function delivery(): View
    {
        return view('seller.b2b.surat_jalan_payout');
    }
}
