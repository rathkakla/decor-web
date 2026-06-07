<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\CartController;
use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\Api\ProfileController;
use App\Http\Controllers\Api\AddressController;
use App\Http\Controllers\Api\ConsultationController;
use App\Http\Controllers\Api\VoucherController;
use App\Http\Controllers\AiDesignController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

/// --- RUTE PUBLIK (Tanpa Token) ---
Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);
Route::get('/products', [ProductController::class, 'index']); 
Route::get('/products/{id}', [ProductController::class, 'show']); 
Route::get('/sellers/{id}', [ProductController::class, 'sellerStore']); 
Route::get('/designers', [\App\Http\Controllers\Api\DesignerController::class, 'index']); 
Route::get('/designers/{id}', [\App\Http\Controllers\Api\DesignerController::class, 'show']); 
Route::post('/generate-room', [AiDesignController::class, 'generate']); 

// --- RUTE TERPROTEKSI (Wajib Token) ---
Route::middleware('auth:sanctum')->group(function () {  
    Route::post('/logout', [AuthController::class, 'logout']);

    // Profile & Address
    Route::get('/profile', [ProfileController::class, 'index']);
    Route::post('/profile/update', [ProfileController::class, 'update']);
    Route::get('/addresses', [AddressController::class, 'index']);
    Route::get('/address-list', [AddressController::class, 'index']); // Alias for mobile
    Route::post('/addresses', [AddressController::class, 'store']);
    Route::put('/addresses/{id}', [AddressController::class, 'update']);
    Route::delete('/addresses/{id}', [AddressController::class, 'destroy']);

    // Cart
    Route::get('/cart', [CartController::class, 'index']);
    Route::post('/cart/add', [CartController::class, 'addToCart']);
    Route::patch('/cart/update/{id}', [CartController::class, 'updateCartItem']);
    Route::delete('/cart/remove/{id}', [CartController::class, 'removeCartItem']);
    Route::post('/cart/toggle-select', [CartController::class, 'toggleSelection']);

    // Orders
    Route::get('/orders', [OrderController::class, 'index']);
    Route::get('/order-list', [OrderController::class, 'index']); // Alias for mobile
    Route::post('/checkout', [OrderController::class, 'checkout']);
    Route::get('/orders/{id}', [OrderController::class, 'show']);
    Route::post('/orders/{id}/confirm-payment', [OrderController::class, 'confirmPayment']);
    Route::post('/orders/{id}/pay', [OrderController::class, 'confirmPayment']); // Alias for mobile
    Route::post('/orders/{id}/received', [OrderController::class, 'receivedOrder']);
    Route::post('/orders/{id}/return', [OrderController::class, 'submitReturn']); // Add return route
    Route::post('/orders/{id}/review', [OrderController::class, 'submitReview']);
    Route::post('/orders/{id}/reviews', [OrderController::class, 'submitReview']); 
    Route::post('/order/{id}/review', [OrderController::class, 'submitReview']);  
    Route::post('/reviews', [OrderController::class, 'submitReview']); // Direct POST to /reviews
    Route::post('/products/{id}/reviews', [OrderController::class, 'submitReview']); // MATCH FOR MOBILE
    Route::post('/product/{id}/review', [OrderController::class, 'submitReview']); 

    // Favorites
    Route::get('/favorites', [ProductController::class, 'getFavorites']);
    Route::post('/favorites/toggle/{productId}', [ProductController::class, 'toggleFavorite']);

    // Chats
    Route::get('/chats/conversations', [\App\Http\Controllers\Api\ChatController::class, 'getConversations']);
    Route::get('/chats/{receiverId}', [\App\Http\Controllers\Api\ChatController::class, 'getMessages']);
    Route::post('/chats/send', [\App\Http\Controllers\Api\ChatController::class, 'sendMessage']);
    
    // Consultations
    Route::get('/consultations', [ConsultationController::class, 'index']);
    Route::post('/consultations', [ConsultationController::class, 'store']);
    Route::get('/consultations/{id}', [ConsultationController::class, 'show']);
    Route::post('/consultations/{id}/messages', [ConsultationController::class, 'sendMessage']);
    Route::post('/consultations/{id}/attachments', [ConsultationController::class, 'uploadAttachment']);
    Route::post('/consultations/{id}/pay', [ConsultationController::class, 'pay']);
    Route::post('/consultations/{id}/submit-brief', [ConsultationController::class, 'submitBrief']);
    Route::post('/consultations/{id}/free-chat', [ConsultationController::class, 'startFreeChat']);
    Route::get('/consultations/{id}/invoice', [ConsultationController::class, 'downloadInvoice']);
    Route::get('/quotes/{id}/download-rab', [ConsultationController::class, 'downloadRab']);
    Route::post('/quotes/{id}/respond', [ConsultationController::class, 'respondToQuote']);
    Route::post('/consultations/{id}/review', [ConsultationController::class, 'submitReview']);

    // Vouchers
    Route::get('/vouchers/seller/{id}', [VoucherController::class, 'sellerVouchers']);
    Route::post('/vouchers/claim/{id}', [VoucherController::class, 'claim']);
    Route::post('/vouchers/apply', [VoucherController::class, 'apply']);

    // Supports (Help Center)
    Route::get('/supports', [\App\Http\Controllers\Api\SupportController::class, 'index']);
    Route::post('/supports', [\App\Http\Controllers\Api\SupportController::class, 'store']);
});