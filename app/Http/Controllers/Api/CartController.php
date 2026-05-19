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
        $customer = Customer::firstOrCreate(['user_id' => $user->id]);

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
    public function addToCart(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity'   => 'nullable|integer|min:1'
        ]);

        $user = $request->user();
        $customer = Customer::firstOrCreate(['user_id' => $user->id]);

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

    // 3. UPDATE JUMLAH BARANG
    public function updateCartItem(Request $request, $id)
    {
        $request->validate(['quantity' => 'required|integer|min:1']);
        
        $cartItem = CartItem::find($id);
        if (!$cartItem) return $this->errorResponse('Barang tidak ditemukan.', 404);

        $cartItem->update(['quantity' => $request->quantity]);
        return $this->successResponse($cartItem, 'Jumlah barang diperbarui.');
    }

    // 4. HAPUS BARANG DARI KERANJANG
    public function removeCartItem($id)
    {
        $cartItem = CartItem::find($id);

        if (!$cartItem) {
            return $this->errorResponse('Barang tidak ditemukan di keranjang.', 404);
        }

        $cartItem->delete();
        return $this->successResponse(null, 'Barang berhasil dihapus dari keranjang.');
    }

    // 5. TOGGLE SELECTION
    public function toggleSelection(Request $request)
    {
        $request->validate([
            'cart_item_id' => 'required|exists:cart_items,id',
            'is_selected' => 'required|boolean'
        ]);

        $cartItem = CartItem::find($request->cart_item_id);
        $cartItem->update(['is_selected' => $request->is_selected]);

        return $this->successResponse($cartItem, 'Status seleksi diperbarui.');
    }
}