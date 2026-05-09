<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Customer;
use App\Models\Product;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    // 1. LIHAT RIWAYAT PESANAN (CUSTOMER)
    public function index(Request $request)
    {
        $user = $request->user();
        $customer = Customer::where('user_id', $user->id)->first();

        if (!$customer) {
            return $this->errorResponse('Profil customer tidak ditemukan.', 404);
        }

        $orders = Order::with(['orderItems.product.images', 'productReturn'])
                        ->where('customer_id', $customer->id)
                        ->latest()
                        ->get();

        return $this->successResponse($orders, 'Data riwayat pesanan berhasil diambil.');
    }

    // 1.5 SUBMIT PENGEMBALIAN (RETURN)
    public function submitReturn(Request $request, $id)
    {
        $request->validate([
            'reason' => 'required|string',
        ]);

        $user = $request->user();
        $customer = Customer::where('user_id', $user->id)->first();
        $order = Order::where('id', $id)->where('customer_id', $customer->id)->first();

        if (!$order) {
            return $this->errorResponse('Pesanan tidak ditemukan.', 404);
        }

        // Cek apakah sudah pernah mengajukan return
        if (\App\Models\ProductReturn::where('order_id', $order->id)->exists()) {
            return $this->errorResponse('Anda sudah mengajukan pengembalian untuk pesanan ini.', 400);
        }

        $return = \App\Models\ProductReturn::create([
            'order_id' => $order->id,
            'reason' => $request->reason,
            'status' => 'pending',
            'return_date' => now(),
        ]);

        // Update status pesanan jadi returning
        $order->update(['status' => 'returning']);

        return $this->successResponse($return, 'Pengajuan pengembalian berhasil dikirim.');
    }

    // 2. PROSES CHECKOUT
    public function checkout(Request $request)
    {
        $request->validate([
            'shipping_courier' => 'required|string',
            'payment_method'   => 'required|string',
        ]);

        $user = $request->user();
        $customer = Customer::where('user_id', $user->id)->first();

        if (!$customer) {
            return $this->errorResponse('Profil customer tidak ditemukan.', 404);
        }

        // Ambil cart customer
        $cart = Cart::where('customer_id', $customer->id)->first();

        if (!$cart) {
            return $this->errorResponse('Keranjang belanja kosong.', 400);
        }

        // Ambil item yang terpilih (is_selected = true)
        // Kita asumsikan di mobile juga ada fitur pilih item, atau defaultnya semua true
        $selectedItems = CartItem::where('cart_id', $cart->id)
                                ->where('is_selected', true)
                                ->get();

        if ($selectedItems->isEmpty()) {
            return $this->errorResponse('Tidak ada item yang dipilih untuk checkout.', 400);
        }

        try {
            DB::beginTransaction();

            $totalPrice = 0;
            $orderItemsData = [];

            foreach ($selectedItems as $item) {
                $product = Product::find($item->product_id);

                // Cek stok
                if ($product->stock < $item->quantity) {
                    throw new \Exception("Stok produk {$product->name} tidak mencukupi.");
                }

                $price = $product->price * $item->quantity;
                $totalPrice += $price;

                $orderItemsData[] = [
                    'product_id' => $item->product_id,
                    'quantity'   => $item->quantity,
                    'price'      => $product->price,
                ];

                // Kurangi stok
                $product->decrement('stock', $item->quantity);
            }

            // Buat Order
            $order = Order::create([
                'customer_id'      => $customer->id,
                'total_price'      => $totalPrice,
                'shipping_courier' => $request->shipping_courier,
                'payment_method'   => $request->payment_method,
                'status'           => 'pending',
            ]);

            // Buat Order Items
            foreach ($orderItemsData as $itemData) {
                $itemData['order_id'] = $order->id;
                OrderItem::create($itemData);
            }

            // Hapus item dari cart
            CartItem::where('cart_id', $cart->id)
                    ->where('is_selected', true)
                    ->delete();

            DB::commit();

            return $this->successResponse($order->load('orderItems.product.images'), 'Checkout berhasil dilakukan.', 201);

        } catch (\Exception $e) {
            DB::rollBack();
            return $this->errorResponse($e->getMessage(), 400);
        }
    }

    // 3. DETAIL PESANAN
    public function show(Request $request, $id)
    {
        $user = $request->user();
        $customer = Customer::where('user_id', $user->id)->first();

        $order = Order::with(['orderItems.product.images'])
                      ->where('customer_id', $customer->id)
                      ->find($id);

        if (!$order) {
            return $this->errorResponse('Pesanan tidak ditemukan.', 404);
        }

        return $this->successResponse($order, 'Detail pesanan berhasil diambil.');
    }

    // 4. BAYAR PESANAN
    public function payOrder(Request $request, $id)
    {
        $user = $request->user();
        $customer = Customer::where('user_id', $user->id)->first();

        if (!$customer) {
            return $this->errorResponse('Profil customer tidak ditemukan.', 404);
        }

        $order = Order::where('customer_id', $customer->id)->find($id);

        if (!$order) {
            return $this->errorResponse('Pesanan tidak ditemukan.', 404);
        }

        if ($order->status !== 'pending') {
            return $this->errorResponse('Pesanan ini sudah dibayar atau tidak valid untuk dibayar.', 400);
        }

        $order->update(['status' => 'paid']);

        return $this->successResponse($order->load('orderItems.product.images'), 'Pembayaran berhasil dikonfirmasi.');
    }
}