<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Customer;
use App\Models\Product;

class CartController extends Controller
{
    // 1. LIHAT ISI KERANJANG
    public function index(Request $request)
    {
        $user = $request->user();
        $customer = Customer::where('user_id', $user->id)->first();

        if (!$customer) {
            return $this->errorResponse('Profil customer tidak ditemukan.', 404);
        }

        // Ambil keranjang beserta item, detail produk, dan gambarnya
        $cart = Cart::with(['cartItems.product.images'])
                    ->where('customer_id', $customer->id)
                    ->first();

        // Jika keranjang masih kosong atau belum pernah dibuat
        if (!$cart || $cart->cartItems->isEmpty()) {
            return $this->successResponse([], 'Keranjang belanja masih kosong.');
        }

        return $this->successResponse($cart, 'Data keranjang berhasil diambil.');
    }

    // 2. TAMBAH BARANG KE KERANJANG
    public function add(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity'   => 'nullable|integer|min:1'
        ]);

        $user = $request->user();
        $customer = Customer::where('user_id', $user->id)->first();

        $productId = $request->product_id;
        $quantity = $request->quantity ?? 1; // Default beli 1 kalau Flutter tidak ngirim quantity

        // Cek stok produk
        $product = Product::find($productId);
        if ($quantity > $product->stock) {
            return $this->errorResponse('Stok produk tidak mencukupi.', 400);
        }

        // Cari keranjang customer ini, kalau belum ada, buatkan baru
        $cart = Cart::firstOrCreate([
            'customer_id' => $customer->id
        ]);

        // Cek apakah barang ini sudah ada di keranjang sebelumnya
        $cartItem = CartItem::where('cart_id', $cart->id)
                            ->where('product_id', $productId)
                            ->first();

        if ($cartItem) {
            // Kalau sudah ada, tambahkan aja jumlahnya (quantity)
            $cartItem->increment('quantity', $quantity);
        } else {
            // Kalau belum ada, bikin item baru
            CartItem::create([
                'cart_id'     => $cart->id,
                'product_id'  => $productId,
                'quantity'    => $quantity,
                'is_selected' => true
            ]);
        }

        return $this->successResponse(null, 'Barang berhasil ditambahkan ke keranjang.', 201);
    }

    // 3. HAPUS BARANG DARI KERANJANG
    public function remove($id)
    {
        $cartItem = CartItem::find($id);

        if (!$cartItem) {
            return $this->errorResponse('Barang tidak ditemukan di keranjang.', 404);
        }

        $cartItem->delete();

        return $this->successResponse(null, 'Barang berhasil dihapus dari keranjang.');
    }
}