<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Customer;
use App\Models\Product;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    // --- NAVIGATION ---
    public function index() { 
        return view('customer.homepage'); 
    }

    public function designers() { 
        return view('customer.designers'); 
    }

    public function profile() { 
        return view('customer.profile'); 
    }

    public function orders() { 
        return view('customer.order-tracking'); 
    }

    public function productDetail($id) { 
        $product = Product::with(['images', 'category', 'seller'])->findOrFail($id);
        
        // Mengambil produk serupa untuk bagian "Curated Pairings"
        $relatedProducts = Product::with('images')
                            ->where('category_id', $product->category_id)
                            ->where('id', '!=', $id)
                            ->inRandomOrder()
                            ->limit(3)
                            ->get();

        return view('customer.product-detail', compact('product', 'relatedProducts')); 
    }

    public function catalog() {
        $products = Product::with('images', 'category')->get();
        return view('customer.catalog', compact('products'));
    }
    
    // --- CART LOGIC ---

    public function cart() { 
        $customer = Customer::where('user_id', Auth::id())->first();
        
        // Load keranjang beserta item dan detail produknya
        $cart = Cart::with(['cartItems.product.images', 'cartItems.product.category'])
                    ->where('customer_id', $customer->id)
                    ->first();

        return view('customer.cart', compact('cart')); 
    }

    public function addToCart(Request $request) // Mengubah parameter agar bisa membaca form
    {
        $customer = Customer::where('user_id', Auth::id())->first();

        // Ambil ID dan Quantity dari Request (form)
        $productId = $request->input('product_id');
        $quantity = $request->input('quantity', 1); // Default 1 jika tidak ada

        // Pastikan produknya ada dan stok mencukupi
        $product = Product::findOrFail($productId);
        if ($quantity > $product->stock) {
            return back()->with('error', 'Stok tidak mencukupi.');
        }

        // Cek atau Buat Cart untuk customer ini
        $cart = Cart::firstOrCreate([
            'customer_id' => $customer->id
        ]);

        // Cek apakah barang sudah ada di keranjang
        $cartItem = CartItem::where('cart_id', $cart->id)
                            ->where('product_id', $productId)
                            ->first();

        if ($cartItem) {
            // Jika sudah ada, tambahkan quantity-nya
            $cartItem->increment('quantity', $quantity);
        } else {
            // Jika belum, buat baru
            CartItem::create([
                'cart_id' => $cart->id,
                'product_id' => $productId,
                'quantity' => $quantity,
                'is_selected' => true
            ]);
        }

        return redirect()->route('customer.cart')->with('success', 'Item ditambahkan ke keranjang!');
    }

    public function incrementCart($id) {
        $item = CartItem::findOrFail($id);
        $item->increment('quantity');
        return back();
    }

    public function decrementCart($id) {
        $item = CartItem::findOrFail($id);
        if ($item->quantity > 1) {
            $item->decrement('quantity');
        } else {
            $item->delete();
        }
        return back();
    }

    public function removeItem($id) {
        CartItem::findOrFail($id)->delete();
        return back()->with('success', 'Item berhasil dihapus');
    }

    // --- CHECKOUT & ORDER LOGIC ---

    public function checkout() { 
        $customer = Customer::where('user_id', Auth::id())->first();
        $cart = Cart::with('cartItems.product')->where('customer_id', $customer->id)->first();

        if (!$cart || $cart->cartItems->count() == 0) {
            return redirect()->route('customer.cart')->with('error', 'Keranjang kosong.');
        }

        return view('customer.checkout', compact('cart', 'customer')); 
    }

    public function placeOrder(Request $request)
{
    // Validasi data input form
    $request->validate([
        'payment_method' => 'required|in:bank_transfer,cod',
    ]);

    $customer = Customer::where('user_id', Auth::id())->first();
    $cart = Cart::with('cartItems.product')->where('customer_id', $customer->id)->first();

    DB::transaction(function () use ($customer, $cart, $request) {
        $subtotal = 0;
        foreach ($cart->cartItems as $item) {
            if ($item->is_selected) {
                $subtotal += ($item->product->price * $item->quantity);
            }
        }

        $shipping = $subtotal > 0 ? 150000 : 0;
        $tax = $subtotal * 0.11;
        $totalPrice = $subtotal + $shipping + $tax;

        // 1. Buat data Order beserta Metode Pembayaran
        $order = Order::create([
            'customer_id' => $customer->id,
            'total_price' => $totalPrice, 
            'shipping_courier' => 'Curated White-Glove Delivery',
            // Pastikan kolom 'payment_method' ada di tabel 'orders' kamu ya!
            'payment_method' => $request->payment_method, 
            'status' => 'pending',
        ]);

        // 2. Pindahkan CartItems ke OrderItems
        foreach ($cart->cartItems as $item) {
            if ($item->is_selected) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item->product_id,
                    'quantity' => $item->quantity,
                    'price' => $item->product->price,
                ]);

                $item->product->decrement('stock', $item->quantity);
            }
        }

        // 3. Kosongkan item yang di-checkout dari Keranjang
        $cart->cartItems()->where('is_selected', true)->delete();
    });

    return redirect()->route('customer.homepage')->with('success', 'Pesanan Anda berhasil dibuat!');
}

    public function returnRequest() {
    // Pastikan foldernya benar (customer/return-request.blade.php)
    return view('customer.return-request'); 
}
}