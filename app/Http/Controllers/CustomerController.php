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

    public function profile() 
{ 
    $user = Auth::user();
    
    // Gunakan firstOrCreate: 
    // Ini akan mencari data customer. Kalau tidak ada, Laravel akan 
    // OTOMATIS membuatkan satu baris baru di database untuk user ini.
    $customer = \App\Models\Customer::firstOrCreate(
        ['user_id' => $user->id]
    );

    return view('customer.profile', compact('user', 'customer')); 
}
    
    public function orders() {
        $user = Auth::user();
        $customer = Customer::where('user_id', $user->id)->first();

        // Ambil semua pesanan milik customer ini, urutkan dari yang terbaru
        // Jangan lupa bawa relasi orderItems dan product-nya
        $orders = Order::with('orderItems.product.images')
                       ->where('customer_id', $customer->id)
                       ->latest()
                       ->get();

        return view('customer.order-tracking', compact('user', 'customer', 'orders'));
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
    
    // Fungsi untuk Toggle Checkbox Keranjang
    public function toggleCartItem(Request $request, $id)
    {
        // Cari item keranjangnya
        $cartItem = \App\Models\CartItem::findOrFail($id);
        
        // Update status is_selected sesuai nilai dari checkbox (1 atau 0)
        $cartItem->is_selected = $request->is_selected;
        $cartItem->save();

        // Kembalikan ke halaman keranjang (halaman akan otomatis memuat ulang hitungan total)
        return back();
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

    // 1. FUNGSI INI TETAP ADA (JANGAN DIHAPUS YAA)
    public function checkout() { 
        $customer = Customer::where('user_id', Auth::id())->first();
        $cart = Cart::with('cartItems.product')->where('customer_id', $customer->id)->first();

        if (!$cart || $cart->cartItems->count() == 0) {
            return redirect()->route('customer.cart')->with('error', 'Keranjang kosong.');
        }

        return view('customer.checkout', compact('cart', 'customer')); 
    }

    // 2. FUNGSI INI YANG BARU (GANTIKAN YANG LAMA DENGAN INI)
    public function placeOrder(Request $request)
    {
        // Validasi data input form
        $request->validate([
            'payment_method' => 'required|in:bank_transfer,cod',
        ]);

        $customer = Customer::where('user_id', Auth::id())->first();
        $cart = Cart::with('cartItems.product')->where('customer_id', $customer->id)->first();

        // Pastikan ada barang yang dipilih
        if (!$cart || $cart->cartItems->where('is_selected', true)->count() == 0) {
            return back()->with('error', 'Belum ada barang yang dipilih untuk dicheckout.');
        }

        // Variabel penampung ID Order
        $createdOrderId = null;

        DB::transaction(function () use ($customer, $cart, $request, &$createdOrderId) {
            
            $selectedItems = $cart->cartItems->where('is_selected', true);

            $groupedItems = $selectedItems->groupBy(function ($item) {
                return $item->product->seller_id;
            });

            foreach ($groupedItems as $sellerId => $items) {
                
                $subtotal = $items->sum(function($item) {
                    return $item->quantity * $item->product->price;
                });
                
                $shipping = 150000;
                $tax = $subtotal * 0.11;
                $totalPrice = $subtotal + $shipping + $tax;

                $order = Order::create([
                    'customer_id' => $customer->id,
                    'total_price' => $totalPrice, 
                    'shipping_courier' => 'Curated White-Glove Delivery',
                    'payment_method' => $request->payment_method, 
                    'status' => 'paid', 
                ]);

                // Simpan ID Order yang baru dibuat
                $createdOrderId = $order->id;

                foreach ($items as $item) {
                    OrderItem::create([
                        'order_id' => $order->id,
                        'product_id' => $item->product_id,
                        'quantity' => $item->quantity,
                        'price' => $item->product->price,
                    ]);

                    $item->product->decrement('stock', $item->quantity);
                    $item->delete();
                }
            }
        });

        // Redirect ke halaman Success yang baru kita buat
        return redirect()->route('customer.success', ['id' => $createdOrderId]);
    }

    public function returnRequest() {
        // Pastikan foldernya benar (customer/return-request.blade.php)
        return view('customer.return-request'); 
    }

    public function storeProfile($id) {
        // 1. Cari data Seller berdasarkan ID
        $seller = \App\Models\Seller::with('user')->findOrFail($id);

        // 2. Ambil semua produk milik Seller tersebut
        $products = \App\Models\Product::with(['images', 'category'])
                            ->where('seller_id', $seller->id)
                            ->latest()
                            ->get();

        // 3. (Opsional) Hitung total produk
        $totalProducts = $products->count();

        return view('customer.seller-store', compact('seller', 'products', 'totalProducts'));
    }

   public function updateAddress(Request $request)
{
    // Cek apakah ini update alamat utama atau secondary
    if ($request->type == 'secondary') {
        $data = [
            'address_2' => $request->address,
            'city_2'    => $request->city
        ];
    } else {
        $data = [
            'address' => $request->address,
            'city'    => $request->city
        ];
    }

    \App\Models\Customer::updateOrCreate(
        ['user_id' => Auth::id()],
        $data
    );

    return back()->with('success', 'Alamat berhasil diperbarui!');
}
public function deleteAddress()
{
    $customer = \App\Models\Customer::where('user_id', Auth::id())->first();
    // Kita set null saja karena ini alamat default di tabel customer
    $customer->update(['address' => null, 'city' => null]);

    return back()->with('success', 'Alamat berhasil dihapus.');
}

public function updateInfo(Request $request)
{
    $user = Auth::user();
    
    // Update nama di tabel Users
    $user->update(['full_name' => $request->full_name]);

    // Update atau Buat data di tabel Customers
    Customer::updateOrCreate(
        ['user_id' => $user->id],
        ['phone' => $request->phone]
    );

    return back()->with('success', 'Informasi pribadi berhasil diperbarui.');
}
}