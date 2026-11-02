<?php

namespace App\Http\Controllers\B2b;

use App\Http\Controllers\Controller;
use Illuminate\View\View;

class DesignerController extends Controller
{
    public function catalog(): View
    {
        return view('designer.b2b.katalog_pabrik');
    }

    public function createRfq(): View
    {
        return view('designer.b2b.pengajuan_rfq');
    }

    public function quotation(): View
    {
        return view('designer.b2b.quotation_review');
    }

    public function negotiation(): View
    {
        return view('designer.b2b.negosiasi_teknis');
    }

    public function milestone(): View
    {
        return view('designer.b2b.milestone_tracker');
    }

    public function bast(): View
    {
        return view('designer.b2b.bast_serah_terima');
    }
}
