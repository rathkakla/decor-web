<?php

use App\Http\Controllers\B2b\Designer\BastController;
use App\Http\Controllers\B2b\Designer\CatalogController;
use App\Http\Controllers\B2b\Designer\MilestoneController;
use App\Http\Controllers\B2b\Designer\NegotiationController as DesignerNegotiationController;
use App\Http\Controllers\B2b\Designer\QuotationController as DesignerQuotationController;
use App\Http\Controllers\B2b\Designer\RfqController as DesignerRfqController;
use App\Http\Controllers\B2b\Seller\DeliveryController;
use App\Http\Controllers\B2b\Seller\NegotiationController as SellerNegotiationController;
use App\Http\Controllers\B2b\Seller\ProductionController;
use App\Http\Controllers\B2b\Seller\QuotationController as SellerQuotationController;
use App\Http\Controllers\B2b\Seller\RfqController as SellerRfqController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| B2B Custom Furniture Procurement Routes
|--------------------------------------------------------------------------
|
| Phase 2: Functional controllers with data binding and form handling.
| All routes protected by auth + role middleware.
|
*/

// ============================================================
// SELLER / PABRIK MEBEL
// ============================================================
Route::middleware(['auth', 'role:seller'])
    ->prefix('seller/b2b')
    ->name('seller.b2b.')
    ->group(function () {

    // Inbox RFQ
    Route::get('/inbox', [SellerRfqController::class, 'index'])->name('inbox');

    // Review Spesifikasi (detail RFQ)
    Route::get('/review/{rfq}', [SellerRfqController::class, 'show'])->name('rfq.review');

    // Quotation Builder
    Route::get('/quotation/create/{rfq}', [SellerQuotationController::class, 'create'])->name('quotation.create');
    Route::post('/quotation', [SellerQuotationController::class, 'store'])->name('quotation.store');
    Route::get('/quotation/{quotation}/edit', [SellerQuotationController::class, 'edit'])->name('quotation.edit');
    Route::put('/quotation/{quotation}', [SellerQuotationController::class, 'update'])->name('quotation.update');

    // Negosiasi Teknis & Sampel
    Route::get('/negosiasi/{rfq}', [SellerNegotiationController::class, 'show'])->name('negosiasi');
    Route::post('/negosiasi/{rfq}/message', [SellerNegotiationController::class, 'sendMessage'])->name('negosiasi.message');
    Route::post('/negosiasi/{rfq}/sample', [SellerNegotiationController::class, 'updateSample'])->name('negosiasi.sample');

    // Manajemen Produksi & QC
    Route::get('/produksi/{order}', [ProductionController::class, 'show'])->name('produksi');
    Route::post('/produksi/{order}/milestone/{milestone}', [ProductionController::class, 'updateMilestone'])->name('produksi.milestone.update');
    Route::post('/produksi/{order}/milestone/{milestone}/photo', [ProductionController::class, 'uploadQcPhoto'])->name('produksi.milestone.photo');

    // Surat Jalan & Payout
    Route::get('/surat-jalan/{order}', [DeliveryController::class, 'show'])->name('surat_jalan');
    Route::post('/surat-jalan', [DeliveryController::class, 'store'])->name('surat_jalan.store');
    Route::put('/surat-jalan/{delivery}', [DeliveryController::class, 'update'])->name('surat_jalan.update');
    Route::post('/surat-jalan/{delivery}/signature', [DeliveryController::class, 'signature'])->name('surat_jalan.signature');
    Route::post('/payout/{order}/request', [DeliveryController::class, 'requestPayout'])->name('payout.request');
});

// ============================================================
// DESIGNER INTERIOR
// ============================================================
Route::middleware(['auth', 'role:designer'])
    ->prefix('designer/b2b')
    ->name('designer.b2b.')
    ->group(function () {

    // Katalog Pabrik
    Route::get('/katalog', [CatalogController::class, 'index'])->name('katalog');
    Route::get('/katalog/{seller}', [CatalogController::class, 'show'])->name('katalog.show');

    // RFQ Create
    Route::get('/rfq/create', [DesignerRfqController::class, 'create'])->name('rfq.create');
    Route::get('/rfq/create/{seller}', [DesignerRfqController::class, 'create'])->name('rfq.create.seller');
    Route::post('/rfq', [DesignerRfqController::class, 'store'])->name('rfq.store');

    // Quotation Review
    Route::get('/quotation/{quotation}', [DesignerQuotationController::class, 'show'])->name('quotation');
    Route::post('/quotation/{quotation}/accept', [DesignerQuotationController::class, 'accept'])->name('quotation.accept');
    Route::post('/quotation/{quotation}/pay-dp', [DesignerQuotationController::class, 'payDp'])->name('quotation.pay_dp');

    // Negosiasi Teknis & Sampel
    Route::get('/negosiasi/{rfq}', [DesignerNegotiationController::class, 'show'])->name('negosiasi');
    Route::post('/negosiasi/{rfq}/message', [DesignerNegotiationController::class, 'sendMessage'])->name('negosiasi.message');
    Route::post('/negosiasi/{rfq}/approve-sample', [DesignerNegotiationController::class, 'approveSample'])->name('negosiasi.approve_sample');

    // Milestone Tracker & Pelunasan
    Route::get('/milestone/{order}', [MilestoneController::class, 'show'])->name('milestone');
    Route::post('/milestone/{order}/pay-final', [MilestoneController::class, 'payFinal'])->name('milestone.pay_final');

    // BAST & Rilis Escrow
    Route::get('/bast/{order}', [BastController::class, 'show'])->name('bast');
    Route::post('/bast/{order}/sign', [BastController::class, 'sign'])->name('bast.sign');
});