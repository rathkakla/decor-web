<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\CartController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

/// --- RUTE PUBLIK (Tanpa Token) ---
Route::post('/login', [AuthController::class, 'login']);
Route::get('/products', [ProductController::class, 'index']); // Lihat semua barang
Route::get('/products/{id}', [ProductController::class, 'show']); // Lihat detail 1 barang

// --- RUTE TERPROTEKSI (Wajib Token) ---
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);

    // API Keranjang Belanja (Customer)
    Route::get('/cart', [CartController::class, 'index']);           // Lihat keranjang
    Route::post('/cart/add', [CartController::class, 'add']);        // Tambah ke keranjang
    Route::delete('/cart/{id}', [CartController::class, 'remove']);  // Hapus dari keranjang
    
    // CRUD Produk khusus Seller
    Route::post('/products', [ProductController::class, 'store']); // Tambah
    Route::put('/products/{id}', [ProductController::class, 'update']); // Edit
    Route::delete('/products/{id}', [ProductController::class, 'destroy']); // Hapus
});