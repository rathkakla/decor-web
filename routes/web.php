<?php

use App\Http\Controllers\CustomerController;
use App\Http\Controllers\SellerController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\OrderController;
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

// PUBLIC INVOICE ROUTE
Route::get('/invoice/{id}/download', [SellerController::class, 'downloadPdfInvoice'])->name('invoice.download');
Route::get('/invoice/{id}/view', [SellerController::class, 'printInvoice'])->name('invoice.view');

// Registrasi dinamis berdasarkan role
Route::get('/register/{role}', function ($role) {
    session(['selected_role' => $role]);

    // Arahkan ke view yang sesuai
    if ($role === 'seller') {
        return view('seller.register.index'); // Halaman register mewah seller
    } elseif ($role === 'designer') {
        return view('register-designer');
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
    Route::get('/about', fn() => view('customer.about'))->name('about');
    Route::get('/design-lab', fn() => view('customer.design-lab'))->name('design-lab');
    Route::get('/help-center', fn() => view('customer.help-center'))->name('help-center');
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

    // Profile Settings (Breeze Default)
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
        Route::get('/chat', fn() => view('customer.chat'))->name('chat');
        Route::get('/chat-seller', fn() => view('customer.chat-seller'))->name('chat-seller');
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
});


// ==========================================
// 🎨 AREA DESIGNER
// ==========================================
//Designer
Route::prefix('designer')->group(function () {
    // Auth & Dashboard
    Route::get('/register', function () { return view('designer.register.index'); })->name('designer.register');
    Route::get('/dashboard', function () { return view('designer.dashboard'); })->name('designer.dashboard');

    // Portfolio CRUD System
    Route::get('/portfolio', fn() => view('designer.portfolio.index'))->name('designer.portfolio.index');
    Route::get('/portfolio/create', fn() => view('designer.portfolio.create'))->name('designer.portfolio.create');
    Route::post('/portfolio/store', fn() => 'Logic simpan data baru')->name('designer.portfolio.store');
    Route::get('/portfolio/{id}/edit', fn($id) => view('designer.portfolio.edit', ['id' => $id]))->name('designer.portfolio.edit');
    Route::put('/portfolio/{id}/update', fn($id) => 'Logic update data')->name('designer.portfolio.update');
    Route::delete('/portfolio/{id}/destroy', fn($id) => 'Logic hapus data')->name('designer.portfolio.destroy');

    // Consultation System
    Route::get('/consultations', function () { return view('designer.consultation.index'); })->name('designer.consultations.index');
    Route::get('/consultations/detail', function () { return view('designer.consultation.show'); })->name('designer.consultations.show');
    
    // Invoice Download
    Route::get('/consultations/{id}/invoice', function ($id) { 
        return view('designer.consultation.invoice', ['consultation_id' => $id]); 
    })->name('invoice.download');

    // Chat Project (Folder: chat)
    Route::get('/chats', function () { return view('designer.chat.chat'); })->name('designer.chats');
    Route::get('/chats/detail', function () { return view('designer.chat.show'); })->name('designer.chat.show');

    // Performance & Reviews
    Route::get('/reviews', function () { return view('designer.reviews'); })->name('designer.reviews');

    // Reports System (Folder: report)
    Route::prefix('reports')->group(function () {
        // Halaman Utama Laporan (Grafik)
        Route::get('/', function () { return view('designer.report.index'); })->name('designer.reports');

        // Halaman Template PDF untuk di-download
        Route::get('/export', function () { 
            return view('designer.report.export_pdf', [
                'start_date' => request('start_date', '2026-04-01'),
                'end_date' => request('end_date', '2026-05-04')
        ]); })->name('designer.report.export'); });

    // Settings (Folder: settings)
    Route::get('/settings', function () { return view('designer.settings.index'); })->name('designer.settings');
    Route::get('/settings/bank', function () { return view('designer.settings.bank'); })->name('designer.settings.bank');

    // Support (Folder: Support)
    Route::get('/support', function () { return view('designer.Support.support'); })->name('designer.support');
    Route::get('/support/chat', function () { return view('designer.Support.chat'); })->name('designer.support.chat');
});


// AREA ADMIN
Route::get('/dashboard', function () {
    return view('Admin.dashboard'); // folder Admin, file dashboard
})->name('admin.dashboard');



Route::get('/user-management', function () {
    return view('Admin.user-management'); // folder Admin, file user-management
})->name('admin.user-management');


Route::get('/admin-revenue', function () {
    return view('Admin.admin-revenue'); // folder Admin, file admin-revenue
})->name('admin-revenue');

Route::get('/seller-monitor', function () {
    return view('Admin.seller-monitor'); // folder Admin, file seller-monitor
})->name('seller-monitor');

Route::get('/seller-detail', function () {
    return view('Admin.seller-detail'); // folder Admin, file seller-detail
})->name('seller-detail');

Route::get('/seller-support', function () {
    return view('Admin.seller-support'); // folder Admin, file seller-support
})->name('seller-support');

Route::get('/designer-monitor', function () {
    return view('Admin.designer-monitor'); // folder Admin, file designer-monitor
})->name('designer-monitor');

Route::get('/customer-support', function () {
    return view('Admin.customer-support'); // folder Admin, file customer-support
})->name('customer-support');

Route::get('/designer-detail', function () {
    return view('Admin.designer-detail'); // folder Admin, file designer-detail
})->name('designer-detail');

Route::get('/product-validation', function () {
    return view('Admin.product-validation'); // folder Admin, file product-validation
})->name('product-validation');

Route::get('/designer-support', function () {
    return view('Admin.designer-support'); // folder Admin, file designer-support
})->name('designer-support');

Route::get('/portofolio-validation', function () {
    return view('Admin.portofolio-validation'); // folder Admin, file portofolio-validation
})->name('portofolio-validation');

Route::get('/settings', function () {
    return view('Admin.settings'); // folder Admin, file admin-settings
})->name('settings');

require __DIR__.'/auth.php';