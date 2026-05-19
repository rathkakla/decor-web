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
use App\Models\Voucher;
use App\Models\VoucherClaim;
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
            'return_type' => 'required|in:refund,exchange',
            'video_proof' => 'required|file|mimes:mp4,mov,avi,wmv|max:20480',
            'photo_proof' => 'required|image|mimes:jpeg,png,jpg|max:2048',
            'bank_account_number' => 'required_if:return_type,refund|nullable|string|max:50',
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

        $videoPath = null;
        if ($request->hasFile('video_proof')) {
            $videoPath = $request->file('video_proof')->store('return_proofs/videos', 'public');
        }

        $photoPath = null;
        if ($request->hasFile('photo_proof')) {
            $photoPath = $request->file('photo_proof')->store('return_proofs/photos', 'public');
        }

        $return = \App\Models\ProductReturn::create([
            'order_id' => $order->id,
            'return_type' => $request->return_type,
            'reason' => $request->reason,
            'status' => 'pending',
            'return_date' => now(),
            'video_proof' => $videoPath,
            'photo_proof' => $photoPath,
            'bank_account_number' => $request->bank_account_number,
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
            'payment_method' => 'required|string',
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

            $subtotal = 0;
            $orderItemsData = [];

            foreach ($selectedItems as $item) {
                $product = Product::find($item->product_id);

                // Cek stok
                if ($product->stock < $item->quantity) {
                    throw new \Exception("Stok produk {$product->name} tidak mencukupi.");
                }

                $price = $product->price * $item->quantity;
                $subtotal += $price;

                $orderItemsData[] = [
                    'product_id' => $item->product_id,
                    'quantity' => $item->quantity,
                    'price' => $product->price,
                ];

                // Kurangi stok
                $product->decrement('stock', $item->quantity);
            }

            // Voucher logic
            $discount = 0;
            $voucherId = $request->voucher_id;
            if ($voucherId) {
                $voucher = Voucher::find($voucherId);
                if ($voucher && $subtotal >= $voucher->min_purchase) {
                    $claim = VoucherClaim::where('user_id', $user->id)
                        ->where('voucher_id', $voucher->id)
                        ->where('is_used', false)
                        ->first();
                    
                    if ($claim) {
                        if ($voucher->discount_type === 'percentage') {
                            $discount = $subtotal * ($voucher->discount_value / 100);
                            if ($voucher->max_discount && $discount > $voucher->max_discount) {
                                $discount = $voucher->max_discount;
                            }
                        } else {
                            $discount = $voucher->discount_value;
                        }
                        $claim->update(['is_used' => true, 'used_at' => now()]);
                    } else {
                        $voucherId = null; // Claim tidak valid/sudah digunakan
                    }
                } else {
                    $voucherId = null; // Tidak memenuhi syarat
                }
            }

            $totalPrice = $subtotal - $discount;
            if ($totalPrice < 0) $totalPrice = 0;

            // Buat Order
            $order = Order::create([
                'customer_id' => $customer->id,
                'total_price' => $totalPrice,
                'voucher_id' => $voucherId,
                'discount_amount' => $discount,
                'shipping_courier' => $request->shipping_courier,
                'payment_method' => $request->payment_method,
                'status' => 'pending',
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
 
            // Notify sellers
            $order->load('orderItems.product.seller.user');
            $sellers = $order->orderItems->map(function($item) {
                return $item->product->seller->user ?? null;
            })->filter()->unique('id');

            foreach ($sellers as $sellerUser) {
                $sellerUser->notify(new \App\Notifications\NewOrderNotification($order));
            }
 
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

    // 4. BAYAR PESANAN (Upload Bukti)
    public function confirmPayment(Request $request, $id)
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
            return $this->errorResponse('Pesanan ini sudah dibayar atau sedang dalam verifikasi.', 400);
        }

        $request->validate([
            'payment_proof' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        if ($request->hasFile('payment_proof')) {
            $file = $request->file('payment_proof');
            $path = $file->store('payment_proofs', 'public');
            $order->update([
                'payment_proof' => $path,
                'status' => 'waiting_verification'
            ]);
        }

        return $this->successResponse($order->load('orderItems.product.images'), 'Bukti pembayaran berhasil diupload, mohon tunggu verifikasi admin.');
    }

    public function submitReview(Request $request, $id = null)
    {
        // Jika product_id tidak ada di body tapi ada di URL, masukkan ke request
        if (!$request->has('product_id') && $id) {
            $request->merge(['product_id' => $id]);
        }

        $request->validate([
            'product_id' => 'required|exists:products,id',
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string',
            'order_id' => 'nullable|exists:orders,id',
        ]);

        $user = $request->user();
        $customer = Customer::where('user_id', $user->id)->first();

        if (!$customer) {
            return $this->errorResponse('Customer profile not found.', 404);
        }

        // Verifikasi apakah ini review untuk order tertentu
        $orderId = $request->order_id;
        
        // Jika URL ID bukan product_id tapi order_id (dari rute /orders/{id}/reviews)
        // Kita cek apakah $id yang dikirim di URL adalah order_id yang valid
        if (!$orderId && $id && Order::where('id', $id)->where('customer_id', $customer->id)->exists()) {
            $orderId = $id;
        }

        $review = \App\Models\Review::create([
            'product_id' => $request->product_id,
            'customer_id' => $customer->id,
            'rating' => $request->rating,
            'comment' => $request->comment,
            // Jika ada order_id, kita bisa simpan atau update status order_items 
            // Namun di model Review kita belum punya field order_id. 
            // Kita biarkan saja dulu atau tambahkan nanti jika perlu.
        ]);

        // Opsional: Tandai order ini sudah di-review jika ada orderId
        if ($orderId) {
            Order::where('id', $orderId)->update(['has_reviewed' => true]);
        }

        return $this->successResponse($review, 'Review berhasil dikirim.');
    }

    // 5. KONFIRMASI BARANG DITERIMA (CUSTOMER)
    public function receivedOrder(Request $request, $id)
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

        if ($order->status !== 'shipped') {
            return $this->errorResponse('Pesanan hanya bisa dikonfirmasi jika statusnya sudah dikirim.', 400);
        }

        $order->update(['status' => 'completed']);

        return $this->successResponse($order->load('orderItems.product.images'), 'Terima kasih! Pesanan Anda telah selesai.');
    }

    // Helper responses
    protected function successResponse($data, $message = null, $code = 200)
    {
        return response()->json([
            'success' => true,
            'message' => $message,
            'data' => $data
        ], $code);
    }

    protected function errorResponse($message, $code)
    {
        return response()->json([
            'success' => false,
            'message' => $message
        ], $code);
    }
}