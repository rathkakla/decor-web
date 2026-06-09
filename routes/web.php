<?php

use App\Http\Controllers\CustomerController;
use App\Http\Controllers\SellerController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\AiDesignController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return view('customer.homepage'); // Homepage utama DECOR
})->name('homepage');

Route::get('/choose-role', function () {
    return view('role-selection');
})->name('role.selection');

Route::get('/design-lab', function () {
    return view('design-lab'); 
})->name('design-lab');

// Jalur AI murni ke Controller

// PUBLIC INVOICE ROUTE
Route::get('/invoice/{id}/download', [SellerController::class, 'downloadPdfInvoice'])->name('invoice.download');
Route::get('/consultation/invoice/{id}', [App\Http\Controllers\DesignerController::class, 'downloadInvoice'])->name('consultation.invoice.public');
Route::get('/consultation/quote/{id}/download-rab/public', [App\Http\Controllers\CustomerController::class, 'downloadRab'])->name('consultation.download-rab.public');
Route::get('/consultation/quote/{id}/download-designs', [App\Http\Controllers\DesignerController::class, 'downloadDesignImages'])->name('consultation.download-designs.public');
Route::get('/invoice/{id}/view', [SellerController::class, 'printInvoice'])->name('invoice.view');

// Registrasi dinamis berdasarkan role
Route::get('/register/{role}', function ($role) {
    session(['selected_role' => $role]);

    // Arahkan ke view yang sesuai
    if ($role === 'seller') {
        return view('seller.register.index'); // Halaman register mewah seller
    } elseif ($role === 'designer') {
        // Menggunakan halaman register mewah yang baru
        return view('designer.register.index');
    }

    // Default: Customer
    return view('register-customer');
})->name('register.role');

/*
|--------------------------------------------------------------------------
| Customer Public Routes (Tanpa Login)
|--------------------------------------------------------------------------
*/
Route::prefix('customer')->name('customer.')->group(function () {
    Route::get('/homepage', [CustomerController::class, 'index'])->name('homepage');
    Route::get('/catalog', [CustomerController::class, 'catalog'])->name('catalog');
    Route::get('/product/{id}', [CustomerController::class, 'productDetail'])->name('product-detail');
    Route::get('/designers', [CustomerController::class, 'designers'])->name('designers');
    Route::get('/designer/{id}', [CustomerController::class, 'designerProfile'])->name('designer.profile');
    Route::get('/about', fn() => view('customer.about'))->name('about');
    Route::get('/credits', fn() => view('customer.credits'))->name('credits');
    Route::get('/design-lab', fn() => view('customer.design-lab'))->name('design-lab');
    Route::get('/help-center', function () {
        $supports = collect();
        if (auth()->check()) {
            $supports = \App\Models\Support::where('user_id', auth()->id())->latest()->get();
        }
        return view('customer.help-center', compact('supports'));
    })->name('help-center');
    Route::get('/store/{id}', [CustomerController::class, 'storeProfile'])->name('store');
});

/*
|--------------------------------------------------------------------------
| Authenticated Routes (Harus Login)
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'verified'])->group(function () {

    // Default Dashboard (Redirector)
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    Route::get('/notifications', [App\Http\Controllers\NotificationController::class, 'index'])->name('notifications');
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // ==========================================
    // 👤 AREA CUSTOMER
    // ==========================================

    Route::prefix('customer')->name('customer.')->group(function () {

        // 1. Rute yang dikelola CustomerController
        Route::get('/cart', [CustomerController::class, 'cart'])->name('cart');
        Route::get('/checkout', [CustomerController::class, 'checkout'])->name('checkout');
        Route::get('/order-tracking', [CustomerController::class, 'orders'])->name('orders');
        Route::get('/profile', [CustomerController::class, 'profile'])->name('profile');
        Route::get('/return-request', [CustomerController::class, 'returnRequest'])->name('returns');
        Route::post('/cart/add/{productId}', [CustomerController::class, 'addToCart'])->name('cart.add');
        Route::post('/cart/store', [App\Http\Controllers\CustomerController::class, 'addToCart'])->name('cart.store');
        Route::patch('/cart/increment/{id}', [CustomerController::class, 'incrementCart'])->name('cart.increment');
        Route::patch('/cart/decrement/{id}', [CustomerController::class, 'decrementCart'])->name('cart.decrement');
        Route::delete('/cart/remove/{id}', [CustomerController::class, 'removeItem'])->name('cart.remove');
        Route::post('/checkout/place-order', [CustomerController::class, 'placeOrder'])->name('place-order');
        Route::get('/payment/{id}', [CustomerController::class, 'payment'])->name('payment');
        Route::post('/payment/{id}/confirm', [CustomerController::class, 'confirmPayment'])->name('payment.confirm');
        Route::patch('/cart/toggle/{id}', [CustomerController::class, 'toggleCartItem'])->name('cart.toggle');
        Route::get('/order/success/{id}', [OrderController::class, 'success'])->name('success');
        // Rute untuk Update Alamat (yang tadi kita buat di modal)
        Route::post('/address', [CustomerController::class, 'storeAddress'])->name('store-address');
        Route::patch('/address/{id}', [CustomerController::class, 'updateAddress'])->name('update-address');
        Route::delete('/address/{id}', [CustomerController::class, 'deleteAddress'])->name('delete-address');

        // Rute untuk Update Info Personal (nama & telepon)
        Route::patch('/profile/update-info', [CustomerController::class, 'updateInfo'])->name('profile.update-info');
        Route::post('/profile/update-avatar', [CustomerController::class, 'updateAvatar'])->name('profile.update-avatar');

        // 2. Halaman yang langsung memanggil View (Sesuai foto folder kamu)
        Route::get('/chat/{consultation_id?}', [CustomerController::class, 'designerWorkspace'])->name('chat');
        Route::get('/chat-seller/{seller_id?}', [CustomerController::class, 'chatWithSeller'])->name('chat-seller.with');
        Route::get('/invoice', fn() => view('customer.invoice'))->name('invoice');
        Route::get('/portofolio', fn() => view('customer.portofolio'))->name('portofolio');
        Route::get('/product-favorite', [CustomerController::class, 'productFavorite'])->name('product-favorite');
        Route::post('/favorite/toggle/{productId}', [CustomerController::class, 'toggleFavorite'])->name('favorite.toggle');
        Route::get('/return-request/{order_id?}', [CustomerController::class, 'returnRequest'])->name('return-request');
        Route::get('/return-detail/{id}', [CustomerController::class, 'returnDetail'])->name('return-detail');
        Route::post('/return-request/{order_id}/submit', [CustomerController::class, 'submitReturnRequest'])->name('return-request.submit');

        Route::get('/product/{id}/review', [CustomerController::class, 'reviewProduct'])->name('review');
        Route::post('/product/{id}/review', [CustomerController::class, 'submitReview'])->name('review.submit');

        Route::get('/riwayat-chat', fn() => view('customer.riwayat-chat'))->name('riwayat-chat');
        
        // Consultation & Booking Flow
        Route::get('/designers/{id}/free-chat', [CustomerController::class, 'freeChat'])->name('designers.free-chat');
        Route::get('/designers/{id}/book', [CustomerController::class, 'bookConsultation'])->name('designers.book');
        Route::post('/designers/{id}/book', [CustomerController::class, 'storeConsultation'])->name('designers.book.store');
        Route::get('/my-consultations', [CustomerController::class, 'myConsultations'])->name('my-consultations');
        
        // New Workflow Routes
        Route::get('/track-consultations', [CustomerController::class, 'trackConsultationList'])->name('track-consultation.list');
        Route::post('/consultation/{id}/pay-fee', [CustomerController::class, 'payConsultationFee'])->name('consultation.pay-fee');
        Route::post('/consultation/{id}/upload-payment-proof', [CustomerController::class, 'uploadPaymentProof'])->name('consultation.upload-payment-proof');
        Route::post('/consultation/{id}/submit-brief', [CustomerController::class, 'submitConsultationBrief'])->name('consultation.submit-brief');
        Route::post('/consultation/{id}/accept-offer', [CustomerController::class, 'acceptConsultationOffer'])->name('consultation.accept-offer');
        Route::post('/consultation/{id}/reject-offer', [CustomerController::class, 'rejectConsultationOffer'])->name('consultation.reject-offer');
        Route::post('/consultation/{id}/request-revision', [CustomerController::class, 'requestRevision'])->name('consultation.request-revision');
        Route::get('/consultation/quote/{id}/download-rab', [CustomerController::class, 'downloadRab'])->name('consultation.download-rab');
        Route::post('/consultation/{id}/pay-final', [CustomerController::class, 'payFinalProject'])->name('consultation.pay-final');
        Route::post('/consultation/{id}/messages', [CustomerController::class, 'sendConsultationMessage'])->name('consultation.messages.send');
        Route::post('/consultation/{id}/review', [CustomerController::class, 'submitConsultationReview'])->name('consultation.review.submit');

        Route::get('/chat-seller/{seller_id?}', [CustomerController::class, 'chatWithSeller'])->name('chat-seller.with');
        Route::post('/chat-seller/send', [CustomerController::class, 'sendChatMessage'])->name('chat-seller.send');


        // Vouchers
        Route::post('/vouchers/claim/{id}', [CustomerController::class, 'claimVoucher'])->name('vouchers.claim');
        Route::post('/vouchers/apply', [CustomerController::class, 'applyVoucher'])->name('vouchers.apply');
        Route::post('/orders/{id}/complete', [CustomerController::class, 'completeOrder'])->name('orders.complete');
        Route::post('/orders/submit-reviews', [CustomerController::class, 'submitOrderReviews'])->name('orders.submit-reviews');
        Route::post('/orders/{id}/buy-again', [CustomerController::class, 'buyAgainOrder'])->name('orders.buy-again');
        
        // Support Submission
        Route::get('/support', fn() => view('customer.support'))->name('support');
        Route::post('/support/submit', [CustomerController::class, 'submitSupport'])->name('support.submit');
    });
});
// ==========================================
// 🏪 AREA SELLER 
// ==========================================
Route::middleware(['auth'])->prefix('seller')->name('seller.')->group(function () {

    // 1. Dashboard Utama
    Route::get('/dashboard', [SellerController::class, 'dashboard'])->name('dashboard');

    // 2. Management Produk (CRUD)
    Route::get('/products', [SellerController::class, 'productIndex'])->name('products.index');
    Route::get('/products/create', [SellerController::class, 'createProduct'])->name('products.create');
    Route::post('/products/store', [SellerController::class, 'storeProduct'])->name('products.store');

    // Rute Edit & Delete (Tambahan supaya Kelola Produk lengkap)
    Route::get('/products/{id}/edit', [SellerController::class, 'editProduct'])->name('products.edit');
    Route::put('/products/{id}/update', [SellerController::class, 'updateProduct'])->name('products.update');
    Route::delete('/products/{id}/delete', [SellerController::class, 'deleteProduct'])->name('products.delete');

    // 3. Pesanan (Dinamis)
    // Kita buat alias 'orders' dan 'orders.index' agar link di Blade tidak error
    Route::get('/orders', [SellerController::class, 'orderIndex'])->name('orders');
    Route::get('/orders-list', [SellerController::class, 'orderIndex'])->name('orders.index');
    Route::patch('/orders/{id}/status', [SellerController::class, 'updateOrderStatus'])->name('orders.update-status');
    Route::post('/orders/{id}/validate-payment', [SellerController::class, 'validatePayment'])->name('orders.validate-payment');
    Route::get('/orders/{id}/show', [SellerController::class, 'showOrder'])->name('orders.show');
    Route::get('/orders/{id}/invoice', [SellerController::class, 'printInvoice'])->name('orders.invoice');
    Route::get('/orders/{id}/label', [SellerController::class, 'printLabel'])->name('orders.label');

    // Returns & Complaints (Merged)
    Route::get('/complaints', [SellerController::class, 'complaintIndex'])->name('complaint.index');
    Route::get('/complaints/{id}/detail', [SellerController::class, 'complaintDetail'])->name('complaint.detail');
    Route::post('/complaints/{id}/approve', [SellerController::class, 'approveReturn'])->name('returns.approve');
    Route::post('/complaints/{id}/reject', [SellerController::class, 'rejectReturn'])->name('returns.reject');

    // Fitur Support & Komplain (Sesuai error 'Route not defined' tadi)
    Route::get('/support', [SellerController::class, 'support'])->name('support');
    Route::post('/support/submit', [SellerController::class, 'submitSupport'])->name('support.submit');
    Route::get('/support/chat', [SellerController::class, 'supportChat'])->name('support.chat');

    Route::get('/reports', [SellerController::class, 'reportIndex'])->name('reports');
    Route::get('/reports/download', [SellerController::class, 'downloadReport'])->name('reports.download');
    Route::get('/reviews', [SellerController::class, 'reviewIndex'])->name('reviews');
    Route::post('/reviews/{id}/reply', [SellerController::class, 'replyReview'])->name('reviews.reply');
    Route::get('/chats/{userId?}', [SellerController::class, 'chatIndex'])->name('chats');
    Route::post('/chats/send', [SellerController::class, 'sendChatMessage'])->name('chats.send');
    Route::get('/settings', [SellerController::class, 'settings'])->name('settings');
    Route::post('/settings/update', [SellerController::class, 'updateSettings'])->name('settings.update');
    Route::get('/settings/bank', fn() => view('seller.Settings.bank'))->name('settings.bank');

    // 4. Voucher Management
    Route::get('/vouchers', [SellerController::class, 'voucherIndex'])->name('vouchers.index');
    Route::get('/vouchers/create', [SellerController::class, 'createVoucher'])->name('vouchers.create');
    Route::post('/vouchers/store', [SellerController::class, 'storeVoucher'])->name('vouchers.store');
    Route::get('/vouchers/{id}/edit', [SellerController::class, 'editVoucher'])->name('vouchers.edit');
    Route::put('/vouchers/{id}/update', [SellerController::class, 'updateVoucher'])->name('vouchers.update');
    Route::delete('/vouchers/{id}/delete', [SellerController::class, 'deleteVoucher'])->name('vouchers.delete');
});


// ==========================================
// 🎨 AREA DESIGNER
// ==========================================
// ==========================================
// 🎨 AREA DESIGNER
// ==========================================
Route::middleware(['auth'])->prefix('designer')->name('designer.')->group(function () {
    // Auth & Dashboard
    Route::get('/register', function () {
        session(['selected_role' => 'designer']);
        return view('designer.register.index');
    })->name('register');

    Route::get('/dashboard', [App\Http\Controllers\DesignerController::class, 'dashboard'])->name('dashboard');

    // Portfolio CRUD System
    Route::get('/portfolio', [App\Http\Controllers\DesignerController::class, 'portfolioIndex'])->name('portfolio.index');
    Route::get('/portfolio/create', [App\Http\Controllers\DesignerController::class, 'portfolioCreate'])->name('portfolio.create');
    Route::post('/portfolio/store', [App\Http\Controllers\DesignerController::class, 'portfolioStore'])->name('portfolio.store');
    Route::get('/portfolio/{id}/edit', [App\Http\Controllers\DesignerController::class, 'portfolioEdit'])->name('portfolio.edit');
    Route::put('/portfolio/{id}/update', [App\Http\Controllers\DesignerController::class, 'portfolioUpdate'])->name('portfolio.update');
    Route::delete('/portfolio/{id}/destroy', [App\Http\Controllers\DesignerController::class, 'portfolioDestroy'])->name('portfolio.destroy');
    Route::post('/portfolio/{id}/update-area', [App\Http\Controllers\DesignerController::class, 'portfolioUpdateArea'])->name('portfolio.update-area');

    // Consultation System
    Route::get('/consultations', [App\Http\Controllers\DesignerController::class, 'consultationIndex'])->name('consultations.index');
    Route::get('/consultations/{id}', [App\Http\Controllers\DesignerController::class, 'consultationShow'])->name('consultations.show');
    Route::post('/consultations/{id}/quote', [App\Http\Controllers\DesignerController::class, 'sendQuote'])->name('consultations.send-quote');
    Route::post('/consultations/{id}/messages', [App\Http\Controllers\DesignerController::class, 'sendConsultationMessage'])->name('consultations.messages.send');
    Route::post('/consultations/{id}/attachments', [App\Http\Controllers\DesignerController::class, 'uploadConsultationAttachment'])->name('consultations.attachments.upload');
    Route::patch('/consultations/{id}/status', [App\Http\Controllers\DesignerController::class, 'updateConsultationStatus'])->name('consultations.update-status');
    Route::post('/consultations/{id}/validate-payment', [App\Http\Controllers\DesignerController::class, 'validatePayment'])->name('consultations.validate-payment');
    Route::get('/consultations/rab-template/download', [App\Http\Controllers\DesignerController::class, 'downloadRabTemplate'])->name('consultations.download-rab-template');

    // Invoice Download
    Route::get('/consultations/{id}/invoice', [App\Http\Controllers\DesignerController::class, 'downloadInvoice'])->name('invoice.download');

    // Chat Project (Folder: chat)
    Route::get('/chats/{userId?}', [App\Http\Controllers\DesignerController::class, 'chatIndex'])->name('chats');
    Route::post('/chats/send', [App\Http\Controllers\DesignerController::class, 'sendChatMessage'])->name('chats.send');

    // Performance & Reviews
    Route::get('/reviews', [App\Http\Controllers\DesignerController::class, 'reviews'])->name('reviews');
    Route::post('/reviews/{reviewId}/reply', [App\Http\Controllers\DesignerController::class, 'replyReview'])->name('reviews.reply');

    // Reports System (Folder: report)
    Route::prefix('reports')->group(function () {
        Route::get('/', [App\Http\Controllers\DesignerController::class, 'reportIndex'])->name('reports');
        Route::get('/export', [App\Http\Controllers\DesignerController::class, 'reportExport'])->name('report.export');
    });

    // Settings (Folder: settings)
    Route::get('/settings', [App\Http\Controllers\DesignerController::class, 'settings'])->name('settings');
    Route::post('/settings/update', [App\Http\Controllers\DesignerController::class, 'updateSettings'])->name('settings.update');
    Route::get('/settings/bank', [App\Http\Controllers\DesignerController::class, 'settingsBank'])->name('settings.bank');
    Route::post('/settings/bank/update', [App\Http\Controllers\DesignerController::class, 'updateBankSettings'])->name('settings.bank.update');

    // Support (Folder: Support)
    Route::get('/support', function () {
        return view('designer.Support.support');
    })->name('support');
    Route::post('/support/submit', [App\Http\Controllers\DesignerController::class, 'submitSupport'])->name('support.submit');
    Route::get('/support/chat', [App\Http\Controllers\DesignerController::class, 'supportChat'])->name('support.chat');
    Route::post('/support/chat/send', [App\Http\Controllers\DesignerController::class, 'sendSupportChat'])->name('support.chat.send');
});


// AREA ADMIN
Route::middleware(['auth', 'role:admin'])->prefix('admin')->group(function () {
    Route::get('/dashboard', [App\Http\Controllers\AdminController::class, 'dashboard'])->name('admin.dashboard');

    Route::get('/user-management', [App\Http\Controllers\AdminController::class, 'userManagement'])->name('admin.user-management');
    Route::post('/user-management/{id}/warn', [App\Http\Controllers\AdminController::class, 'warnUser'])->name('admin.user.warn');
    Route::delete('/user-management/{id}', [App\Http\Controllers\AdminController::class, 'deleteUser'])->name('admin.user.delete');

    Route::get('/product-validation', [App\Http\Controllers\AdminController::class, 'productValidation'])->name('admin.product.validation');
    Route::post('/product-validation/{id}/approve', [App\Http\Controllers\AdminController::class, 'approveProduct'])->name('admin.product.approve');
    Route::post('/product-validation/{id}/reject', [App\Http\Controllers\AdminController::class, 'rejectProduct'])->name('admin.product.reject');
    
    // Account Validation
    Route::get('/account-validation', [App\Http\Controllers\AdminController::class, 'accountValidation'])->name('admin.account.validation');
    Route::post('/account-validation/{id}/approve', [App\Http\Controllers\AdminController::class, 'approveAccount'])->name('admin.account.approve');
    Route::post('/account-validation/{id}/reject', [App\Http\Controllers\AdminController::class, 'rejectAccount'])->name('admin.account.reject');

    Route::get('/revenue', function () {
        return view('Admin.admin-revenue');
    })->name('admin.revenue');

    Route::get('/seller-monitor', [App\Http\Controllers\AdminController::class, 'sellerMonitor'])->name('admin.seller-monitor');

    Route::get('/seller-detail/{id}', [App\Http\Controllers\AdminController::class, 'sellerDetail'])->name('admin.seller-detail');
    Route::get('/designer-detail/{id}', [App\Http\Controllers\AdminController::class, 'designerDetail'])->name('admin.designer-detail');

    Route::get('/designer-monitor', [App\Http\Controllers\AdminController::class, 'designerMonitor'])->name('admin.designer-monitor');

    Route::get('/customer-support', [App\Http\Controllers\AdminController::class, 'customerSupport'])->name('admin.customer-support');
    Route::get('/seller-support', [App\Http\Controllers\AdminController::class, 'sellerSupport'])->name('admin.seller-support');
    Route::get('/designer-support', [App\Http\Controllers\AdminController::class, 'designerSupport'])->name('admin.designer-support');
    Route::get('/designer-chat/{userId?}', [App\Http\Controllers\AdminController::class, 'designerChat'])->name('admin.designer-chat');
    Route::post('/designer-chat/send', [App\Http\Controllers\AdminController::class, 'sendDesignerChat'])->name('admin.designer-chat.send');
    Route::post('/support/{id}/reply', [App\Http\Controllers\AdminController::class, 'replySupport'])->name('admin.support.reply');
    Route::patch('/support/{id}/status', [App\Http\Controllers\AdminController::class, 'updateSupportStatus'])->name('admin.support.status');

    Route::get('/portofolio-validation', [App\Http\Controllers\AdminController::class, 'portfolioValidation'])->name('admin.portfolio-validation');
    Route::post('/portofolio-validation/{id}/approve', [App\Http\Controllers\AdminController::class, 'approvePortfolio'])->name('admin.portfolio.approve');
    Route::post('/portofolio-validation/{id}/reject', [App\Http\Controllers\AdminController::class, 'rejectPortfolio'])->name('admin.portfolio.reject');

    Route::get('/settings', function () {
        return view('Admin.settings');
    })->name('admin.settings');
    Route::post('/settings/update', [App\Http\Controllers\AdminController::class, 'updateSettings'])->name('admin.settings.update');
});

require __DIR__ . '/auth.php';