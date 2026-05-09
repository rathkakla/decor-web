<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Customer;
use App\Models\Product;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Category;
use App\Models\Favorite;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
class CustomerController extends Controller
{
    // --- NAVIGATION ---
    public function index()
    {
        return view('customer.homepage');
    }

    public function designers()
    {
        return view('customer.designers');
    }



    public function orders()
    {
        $user = Auth::user();

        // Ambil data customer (termasuk alamatnya untuk ditampilkan di UI)
        $customer = \App\Models\Customer::firstOrCreate(
            ['user_id' => $user->id]
        );

        // Ambil semua pesanan milik customer ini, urutkan dari yang terbaru
        // Kita panggil juga relasi orderItems, product, dan images agar tidak error di looping Blade
        // Kecualikan pesanan yang sudah memiliki pengajuan return (pindah ke tab Return)
        $orders = \App\Models\Order::with(['orderItems.product.images', 'productReturn'])
            ->where('customer_id', $customer->id)
            ->whereDoesntHave('productReturn')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('customer.order-tracking', compact('user', 'customer', 'orders'));
    }


    public function productDetail($id)
    {
        $product = Product::with(['images', 'category', 'seller', 'reviews.customer.user'])->findOrFail($id);

        // Mengambil produk serupa untuk bagian "Curated Pairings"
        $relatedProducts = Product::with('images')
            ->where('category_id', $product->category_id)
            ->where('id', '!=', $id)
            ->inRandomOrder()
            ->limit(3)
            ->get();

        $isFavorite = false;
        if (Auth::check()) {
            $customer = Customer::where('user_id', Auth::id())->first();
            if ($customer) {
                $isFavorite = Favorite::where('customer_id', $customer->id)
                    ->where('product_id', $product->id)
                    ->exists();
            }
        }

        return view('customer.product-detail', compact('product', 'relatedProducts', 'isFavorite'));
    }

    public function catalog(Request $request)
    {
        $categories = \App\Models\Category::all();
        $materials = ['Wood', 'Metal', 'Leather', 'Fabric', 'Marble'];
        $styles = ['Minimalist', 'Modern', 'Industrial', 'Classic', 'Scandinavian'];

        $query = Product::with(['images', 'category']);

        // 1. Filter Search & Category
        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }
        if ($request->filled('category')) {
            $query->whereHas('category', function ($q) use ($request) {
                $q->where('name', $request->category);
            });
        }

        // 2. Filter Material & Style
        if ($request->filled('material')) {
            $query->whereIn('material', (array) $request->material);
        }
        if ($request->filled('style')) {
            $query->whereIn('style', (array) $request->style);
        }

        // 3. Logika Sorting (Hanya menyusun query, belum mengambil data)
        if ($request->filled('sort')) {
            if ($request->sort === 'price_low') {
                $query->orderBy('price', 'asc');
            } elseif ($request->sort === 'price_high') {
                $query->orderBy('price', 'desc');
            } else {
                $query->latest();
            }
        } else {
            $query->latest();
        }

        // 4. EKSEKUSI DATA (Hanya panggil ini SEKALI di akhir)
        $products = $query->get();

        return view('customer.catalog', compact('products', 'categories', 'materials', 'styles'));
    }

    // --- CART LOGIC ---

    public function cart()
    {
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

    public function incrementCart($id)
    {
        $item = CartItem::findOrFail($id);
        $item->increment('quantity');
        return back();
    }

    public function decrementCart($id)
    {
        $item = CartItem::findOrFail($id);
        if ($item->quantity > 1) {
            $item->decrement('quantity');
        } else {
            $item->delete();
        }
        return back();
    }

    public function removeItem($id)
    {
        CartItem::findOrFail($id)->delete();
        return back()->with('success', 'Item berhasil dihapus');
    }

    // --- CHECKOUT & ORDER LOGIC ---

    // 1. FUNGSI INI TETAP ADA (JANGAN DIHAPUS YAA)
    public function checkout()
    {
        $user = Auth::user();
        $customer = Customer::with('addresses')->where('user_id', $user->id)->first();
        $cart = Cart::with('cartItems.product')->where('customer_id', $customer->id)->first();

        if (!$cart || $cart->cartItems->count() == 0) {
            return redirect()->route('customer.cart')->with('error', 'Keranjang kosong.');
        }

        return view('customer.checkout', compact('cart', 'customer', 'user'));
    }

    // 2. FUNGSI INI YANG BARU (GANTIKAN YANG LAMA DENGAN INI)
    public function placeOrder(Request $request)
    {
        // Validasi data input form
        $request->validate([
            'payment_method' => 'required|in:bank_transfer,cod',
            'address_id' => 'required|exists:addresses,id'
        ]);

        $customer = Customer::where('user_id', Auth::id())->first();
        $cart = Cart::with('cartItems.product')->where('customer_id', $customer->id)->first();
        
        // Ambil alamat yang dipilih
        $address = \App\Models\Address::where('id', $request->address_id)
                                    ->where('customer_id', $customer->id)
                                    ->firstOrFail();

        // Pastikan ada barang yang dipilih
        if (!$cart || $cart->cartItems->where('is_selected', true)->count() == 0) {
            return back()->with('error', 'Belum ada barang yang dipilih untuk dicheckout.');
        }

        $createdOrderId = null;

        DB::transaction(function () use ($customer, $cart, $request, $address, &$createdOrderId) {

            $selectedItems = $cart->cartItems->where('is_selected', true);

            $subtotal = $selectedItems->sum(function ($item) {
                return $item->quantity * $item->product->price;
            });

            $shipping = 150000;
            $tax = $subtotal * 0.11;
            $totalPrice = $subtotal + $shipping + $tax;

            $order = Order::create([
                'customer_id' => $customer->id,
                'total_price' => $totalPrice,
                'shipping_courier' => 'Curated White-Glove Delivery',
                'shipping_recipient' => $address->recipient_name,
                'shipping_phone' => $address->phone_number,
                'shipping_city' => $address->city,
                'shipping_province' => $address->province,
                'shipping_postal_code' => $address->postal_code,
                'shipping_address' => $address->full_address,
                'payment_method' => $request->payment_method,
                'status' => 'pending',
            ]);

            $createdOrderId = $order->id;

            foreach ($selectedItems as $item) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item->product_id,
                    'quantity' => $item->quantity,
                    'price' => $item->product->price,
                ]);

                $item->product->decrement('stock', $item->quantity);
                $item->delete();
            }
        });

        // Redirect ke halaman Payment
        return redirect()->route('customer.payment', ['id' => $createdOrderId]);
    }

    public function payment($id)
    {
        $user = Auth::user();
        $customer = Customer::where('user_id', $user->id)->first();
        $order = Order::with('orderItems.product')->where('customer_id', $customer->id)->findOrFail($id);

        if ($order->status !== 'pending') {
            return redirect()->route('customer.success', ['id' => $order->id]);
        }

        return view('customer.payment', compact('order', 'customer'));
    }

    public function confirmPayment(Request $request, $id)
    {
        $user = Auth::user();
        $customer = Customer::where('user_id', $user->id)->first();
        $order = Order::where('customer_id', $customer->id)->findOrFail($id);

        if ($order->status === 'pending') {
            $order->update(['status' => 'paid']);
        }

        return redirect()->route('customer.success', ['id' => $order->id]);
    }

    public function returnRequest($order_id = null)
    {
        $user = Auth::user();
        $customer = \App\Models\Customer::where('user_id', $user->id)->first();

        $order = null;
        if ($order_id) {
            $order = \App\Models\Order::with(['orderItems.product.images'])
                ->where('id', $order_id)
                ->where('customer_id', $customer->id ?? 0)
                ->first();
        }

        // Ambil riwayat return customer ini
        $returns = \App\Models\ProductReturn::with('order.orderItems.product')
            ->whereHas('order', function ($q) use ($customer) {
                $q->where('customer_id', $customer->id ?? 0);
            })
            ->latest()
            ->get();

        return view('customer.return-request', compact('user', 'order', 'returns'));
    }

    public function storeProfile($id)
    {
        $seller = \App\Models\Seller::with('user')->findOrFail($id);
        $products = \App\Models\Product::with('images')->where('seller_id', $id)->get();
        $totalProducts = $products->count();

        // Ambil semua review dari produk-produk milik seller ini
        $allReviews = \App\Models\Review::whereHas('product', function ($q) use ($id) {
            $q->where('seller_id', $id);
        })->get();

        $averageRating = $allReviews->avg('rating') ?: 0;
        $totalReviews = $allReviews->count();

        return view('customer.seller-store', compact('seller', 'products', 'totalProducts', 'averageRating', 'totalReviews'));
    }

    public function profile()
    {
        $user = Auth::user();

        $customer = \App\Models\Customer::with('addresses')->firstOrCreate(
            ['user_id' => $user->id]
        );

        return view('customer.profile', compact('user', 'customer'));
    }

    public function storeAddress(Request $request)
    {
        $request->validate([
            'label' => 'required|string|max:50',
            'recipient_name' => 'nullable|string|max:100',
            'phone_number' => 'nullable|string|max:20',
            'city' => 'nullable|string|max:100',
            'full_address' => 'required|string',
            'is_main' => 'nullable'
        ]);

        $customer = \App\Models\Customer::where('user_id', Auth::id())->first();
        $isMain = $request->has('is_main');

        if ($isMain) {
            \App\Models\Address::where('customer_id', $customer->id)->update(['is_main' => false]);
        }

        \App\Models\Address::create([
            'customer_id' => $customer->id,
            'label' => $request->label,
            'recipient_name' => $request->recipient_name,
            'phone_number' => $request->phone_number,
            'city' => $request->city,
            'full_address' => $request->full_address,
            'is_main' => $isMain
        ]);

        return back()->with('success', 'Alamat baru berhasil ditambahkan!');
    }

    public function updateAddress(Request $request, $id)
    {
        $request->validate([
            'label' => 'required|string|max:50',
            'recipient_name' => 'nullable|string|max:100',
            'phone_number' => 'nullable|string|max:20',
            'city' => 'nullable|string|max:100',
            'full_address' => 'required|string',
            'is_main' => 'nullable'
        ]);

        $customer = \App\Models\Customer::where('user_id', Auth::id())->first();
        $address = \App\Models\Address::where('id', $id)->where('customer_id', $customer->id)->firstOrFail();
        $isMain = $request->has('is_main');

        if ($isMain) {
            \App\Models\Address::where('customer_id', $customer->id)->update(['is_main' => false]);
        }

        $address->update([
            'label' => $request->label,
            'recipient_name' => $request->recipient_name,
            'phone_number' => $request->phone_number,
            'city' => $request->city,
            'full_address' => $request->full_address,
            'is_main' => $isMain
        ]);

        return back()->with('success', 'Alamat berhasil diperbarui!');
    }

    public function deleteAddress($id)
    {
        $customer = \App\Models\Customer::where('user_id', Auth::id())->first();
        $address = \App\Models\Address::where('id', $id)->where('customer_id', $customer->id)->firstOrFail();

        $address->delete();

        return back()->with('success', 'Alamat berhasil dihapus!');
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

    // --- FAVORITE LOGIC ---
    public function toggleFavorite(Request $request, $productId)
    {
        $customer = Customer::where('user_id', Auth::id())->first();

        if (!$customer) {
            return back()->with('error', 'Silakan lengkapi profil Anda terlebih dahulu.');
        }

        $favorite = Favorite::where('customer_id', $customer->id)
            ->where('product_id', $productId)
            ->first();

        if ($favorite) {
            // Remove from favorite
            $favorite->delete();
            return back()->with('success', 'Produk dihapus dari wishlist.');
        } else {
            // Add to favorite
            Favorite::create([
                'customer_id' => $customer->id,
                'product_id' => $productId
            ]);
            return back()->with('success', 'Produk ditambahkan ke wishlist!');
        }
    }

    public function productFavorite()
    {
        $user = Auth::user();
        $customer = Customer::firstOrCreate(['user_id' => $user->id]);

        $favorites = Favorite::with('product.images', 'product.category')
            ->where('customer_id', $customer->id)
            ->latest()
            ->get();

        return view('customer.product-favorite', compact('user', 'customer', 'favorites'));
    }

    // --- REVIEWS & RETURNS ---
    public function reviewProduct($id)
    {
        $user = Auth::user();
        $product = Product::with('images')->findOrFail($id);

        return view('customer.review', compact('user', 'product'));
    }

    public function submitReview(Request $request, $id)
    {
        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:1000'
        ]);

        $customer = Customer::where('user_id', Auth::id())->first();

        \App\Models\Review::create([
            'product_id' => $id,
            'customer_id' => $customer->id,
            'rating' => $request->rating,
            'comment' => $request->comment
        ]);

        return redirect()->route('customer.product-detail', $id)->with('success', 'Thank you for your review!');
    }

    public function submitReturnRequest(Request $request, $order_id)
    {
        $request->validate([
            'reason' => 'required|string',
            'notes' => 'nullable|string'
        ]);

        $order = Order::findOrFail($order_id);

        \App\Models\ProductReturn::create([
            'order_id' => $order->id,
            'reason' => $request->reason . ($request->notes ? ' - ' . $request->notes : ''),
            'status' => 'pending',
            'return_date' => now()
        ]);

        return redirect()->route('customer.return-request')->with('success', 'Return request submitted successfully. Waiting for seller approval.');
    }

    public function returnDetail($id)
    {
        $user = Auth::user();
        $customer = \App\Models\Customer::where('user_id', $user->id)->first();

        $return = \App\Models\ProductReturn::with(['order.orderItems.product.images', 'order.customer.user'])
            ->findOrFail($id);

        // Check if return belongs to customer
        if ($return->order->customer_id !== $customer->id) {
            abort(403);
        }

        return view('customer.return-detail', compact('user', 'customer', 'return'));
    }

    public function updateAvatar(Request $request)
    {
        $request->validate([
            'profile_image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $user = Auth::user();
        $customer = Customer::where('user_id', $user->id)->first();

        if ($request->hasFile('profile_image')) {
            // Hapus foto lama jika ada (dan bukan dummy)
            if ($customer->profile_image) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($customer->profile_image);
            }

            $path = $request->file('profile_image')->store('profiles', 'public');

            $customer->update([
                'profile_image' => $path
            ]);
        }

        return back()->with('success', 'Profile image updated successfully!');
    }
}