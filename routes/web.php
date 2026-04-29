<?php

use App\Http\Controllers\CustomerController;
use App\Http\Controllers\SellerController;
use App\Http\Controllers\ProfileController;
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
        Route::get('/homepage', [CustomerController::class, 'index'])->name('homepage');
        Route::get('/catalog', [CustomerController::class, 'catalog'])->name('catalog');
        Route::get('/product/{id}', [CustomerController::class, 'productDetail'])->name('product-detail');
        Route::get('/cart', [CustomerController::class, 'cart'])->name('cart');
        Route::get('/checkout', [CustomerController::class, 'checkout'])->name('checkout');
        Route::get('/order-tracking', [CustomerController::class, 'orders'])->name('orders');
        Route::get('/profile', [CustomerController::class, 'profile'])->name('profile');
        Route::get('/designers', [CustomerController::class, 'designers'])->name('designers');
        Route::get('/return-request', [CustomerController::class, 'returnRequest'])->name('returns');
        Route::post('/cart/add/{productId}', [CustomerController::class, 'addToCart'])->name('cart.add');
        Route::post('/cart/store', [App\Http\Controllers\CustomerController::class, 'addToCart'])->name('cart.store');
        Route::patch('/cart/increment/{id}', [CustomerController::class, 'incrementCart'])->name('cart.increment');
        Route::patch('/cart/decrement/{id}', [CustomerController::class, 'decrementCart'])->name('cart.decrement');
        Route::delete('/cart/remove/{id}', [CustomerController::class, 'removeItem'])->name('cart.remove');
        Route::post('/checkout/place-order', [CustomerController::class, 'placeOrder'])->name('place-order');
        

        // 2. Halaman yang langsung memanggil View (Sesuai foto folder kamu)
        Route::get('/about', fn () => view('customer.about'))->name('about');
        Route::get('/chat', fn () => view('customer.chat'))->name('chat');
        Route::get('/chat-seller', fn () => view('customer.chat-seller'))->name('chat-seller');
        Route::get('/design-lab', fn () => view('customer.design-lab'))->name('design-lab');
        Route::get('/help-center', fn () => view('customer.help-center'))->name('help-center');
        Route::get('/invoice', fn () => view('customer.invoice'))->name('invoice');
        Route::get('/portofolio', fn () => view('customer.portofolio'))->name('portofolio');
        Route::get('/product-favorite', fn () => view('customer.product-favorite'))->name('product-favorite');
        Route::get('/return-request', fn () => view('customer.return-request'))->name('return-request');
        Route::get('/riwayat-chat', fn () => view('customer.riwayat-chat'))->name('riwayat-chat');
        Route::get('/success', fn () => view('customer.success'))->name('success');

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

    // 4. Fitur Support & Komplain (Sesuai error 'Route not defined' tadi)
    Route::get('/complaints', [SellerController::class, 'support'])->name('complaint.index');
    Route::get('/support', [SellerController::class, 'support'])->name('support');

    // 5. Fitur Lainnya (Bisa tetap statis dulu)
    Route::get('/reports', fn() => view('seller.reports'))->name('reports');
    Route::get('/reviews', fn() => view('seller.reviews'))->name('reviews');
    Route::get('/chats', fn() => view('seller.chats.index'))->name('chats');
    Route::get('/settings', fn() => view('seller.Settings.index'))->name('settings');
});

// ==========================================
// 🎨 AREA DESIGNER
// ==========================================
Route::prefix('designer')->name('designer.')->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard-designer'); // Jika file masih di root views
    })->name('dashboard');
});

require __DIR__.'/auth.php';
