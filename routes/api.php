<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\CartController;
use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\Api\ProfileController;
use App\Http\Controllers\Api\AddressController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

/// --- RUTE PUBLIK (Tanpa Token) ---
Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);
Route::get('/products', [ProductController::class, 'index']); 
Route::get('/products/{id}', [ProductController::class, 'show']); 
Route::get('/sellers/{id}', [ProductController::class, 'sellerStore']); 

// --- RUTE TERPROTEKSI (Wajib Token) ---
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);

    // API Keranjang Belanja (Customer)
    Route::get('/cart', [CartController::class, 'index']);           
    Route::post('/cart/add', [CartController::class, 'add']);        
    Route::delete('/cart/{id}', [CartController::class, 'remove']);  
    
    // API Pesanan & Checkout (Customer)
    Route::get('/orders', [OrderController::class, 'index']);        
    Route::get('/orders/{id}', [OrderController::class, 'show']);    
    Route::post('/orders/{id}/pay', [OrderController::class, 'payOrder']); 
    Route::post('/orders/{id}/return', [OrderController::class, 'submitReturn']); 
    Route::post('/checkout', [OrderController::class, 'checkout']);  

    // CRUD Produk khusus Seller
    Route::post('/products', [ProductController::class, 'store']); 
    Route::put('/products/{id}', [ProductController::class, 'update']); 
    Route::delete('/products/{id}', [ProductController::class, 'destroy']); 
    
    // Profile
    Route::get('/profile', [ProfileController::class, 'show']);
    Route::post('/profile/update', [ProfileController::class, 'update']);
    
    // Addresses
    Route::get('/address-list', [AddressController::class, 'index']);
    Route::post('/addresses', [AddressController::class, 'store']);
    Route::put('/addresses/{id}', [AddressController::class, 'update']);
    Route::delete('/addresses/{id}', [AddressController::class, 'destroy']);

    // Reviews
    Route::post('/products/{id}/reviews', [ProductController::class, 'storeReview']);

    // Chats
    Route::get('/chats/conversations', [\App\Http\Controllers\Api\ChatController::class, 'getConversations']);
    Route::get('/chats/{receiverId}', [\App\Http\Controllers\Api\ChatController::class, 'getMessages']);
    Route::post('/chats/send', [\App\Http\Controllers\Api\ChatController::class, 'sendMessage']);
});