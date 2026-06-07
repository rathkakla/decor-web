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
use App\Models\Voucher;
use App\Models\VoucherClaim;
use App\Models\Support;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    // --- NAVIGATION ---
    public function index()
    {
        $featuredDesigners = \App\Models\Designer::with('user')
            ->where('status', 'approved')
            ->take(3)
            ->get();
        return view('customer.homepage', compact('featuredDesigners'));
    }

    public function designers()
    {
        $designers = \App\Models\Designer::with(['user', 'consultations'])
            ->where('status', 'approved')
            ->get();
        return view('customer.designers', compact('designers'));
    }

    public function designerProfile($id)
    {
        $designer = \App\Models\Designer::with([
            'user',
            'portfolios' => function ($query) {
                $query->where('status', 'approved')->with('consultation.review.customer');
            }
        ])
        ->where('status', 'approved')
        ->findOrFail($id);

        $canFreeChat = true;
        if (Auth::check()) {
            $customer = \App\Models\Customer::where('user_id', Auth::id())->first();
            if ($customer) {
                $existingFreeChat = \App\Models\FreeConsultation::where('customer_id', $customer->id)
                    ->where('designer_id', $id)
                    ->first();
                
                if ($existingFreeChat) {
                    if ($existingFreeChat->is_completed || ($existingFreeChat->expires_at && $existingFreeChat->expires_at->isPast())) {
                        $canFreeChat = false;
                    }
                } else {
                    $totalFreeUsed = \App\Models\FreeConsultation::where('customer_id', $customer->id)->count();
                    if ($totalFreeUsed >= 3) {
                        $canFreeChat = false;
                    }
                }
            }
        }

        $reviews = \App\Models\ConsultationReview::where('designer_id', $designer->id)->get();
        $overallRating = $reviews->count() > 0 ? number_format($reviews->avg('rating'), 1) : 4.9;
        
        $avgDuration = '-';
        if ($reviews->count() > 0) {
            $avgDuration = $reviews->groupBy('project_duration')->sortByDesc(function ($group) {
                return $group->count();
            })->keys()->first();
        }

        return view('customer.designer-profile', compact('designer', 'canFreeChat', 'overallRating', 'avgDuration'));
    }

    public function orders()
    {
        $user = Auth::user();

        $customer = \App\Models\Customer::firstOrCreate(
            ['user_id' => $user->id]
        );

        $orders = \App\Models\Order::with(['orderItems.product.images', 'productReturn'])
            ->where('customer_id', $customer->id)
            ->whereDoesntHave('productReturn')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('customer.order-tracking', compact('user', 'customer', 'orders'));
    }

    public function productDetail($id)
    {
        $product = Product::with(['images', 'category', 'seller', 'reviews.customer.user'])
            ->where('status', 'approved')
            ->whereHas('seller', function ($q) {
                $q->where('status', 'approved');
            })
            ->findOrFail($id);

        $relatedProducts = Product::with('images')
            ->where('category_id', $product->category_id)
            ->where('status', 'approved')
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

        $query = Product::with(['images', 'category'])
            ->where('status', 'approved')
            ->whereHas('seller', function ($q) {
                $q->where('status', 'approved');
            });

        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }
        if ($request->filled('category')) {
            $query->whereHas('category', function ($q) use ($request) {
                $q->where('name', $request->category);
            });
        }

        if ($request->filled('material')) {
            $query->whereIn('material', (array) $request->material);
        }
        if ($request->filled('style')) {
            $query->whereIn('style', (array) $request->style);
        }

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

        $products = $query->get();

        return view('customer.catalog', compact('products', 'categories', 'materials', 'styles'));
    }

    // --- CART LOGIC ---

    public function cart()
    {
        $customer = Customer::where('user_id', Auth::id())->first();

        $cart = Cart::with(['cartItems.product.images', 'cartItems.product.category'])
            ->where('customer_id', $customer->id)
            ->first();

        return view('customer.cart', compact('cart'));
    }

    public function addToCart(Request $request)
    {
        $customer = Customer::where('user_id', Auth::id())->first();

        $productId = $request->input('product_id');
        $quantity = $request->input('quantity', 1);

        $product = Product::findOrFail($productId);
        if ($quantity > $product->stock) {
            return back()->with('error', 'Stok tidak mencukupi.');
        }

        $cart = Cart::firstOrCreate([
            'customer_id' => $customer->id
        ]);

        $cartItem = CartItem::where('cart_id', $cart->id)
            ->where('product_id', $productId)
            ->first();

        if ($cartItem) {
            $cartItem->increment('quantity', $quantity);
        } else {
            CartItem::create([
                'cart_id' => $cart->id,
                'product_id' => $productId,
                'quantity' => $quantity,
                'is_selected' => true
            ]);
        }

        return redirect()->route('customer.cart')->with('success', 'Item ditambahkan ke keranjang!');
    }

    public function toggleCartItem(Request $request, $id)
    {
        $cartItem = \App\Models\CartItem::findOrFail($id);
        $cartItem->is_selected = $request->is_selected;
        $cartItem->save();
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

    public function checkout()
    {
        $user = Auth::user();
        $customer = Customer::with('addresses')->where('user_id', $user->id)->first();
        $cart = Cart::with('cartItems.product.seller.user')->where('customer_id', $customer->id)->first();

        if (!$cart || $cart->cartItems->count() == 0) {
            return redirect()->route('customer.cart')->with('error', 'Keranjang kosong.');
        }

        // Fetch claimed and unused vouchers for this user
        $claimedVouchers = VoucherClaim::with(['voucher.seller.user'])
            ->where('user_id', $user->id)
            ->where('is_used', false)
            ->get()
            ->map(function ($claim) {
                return $claim->voucher;
            })
            ->filter();

        return view('customer.checkout', compact('cart', 'customer', 'user', 'claimedVouchers'));
    }

    public function placeOrder(Request $request)
    {
        $request->validate([
            'payment_method' => 'required|in:bank_transfer,cod',
            'address_id' => 'required|exists:addresses,id'
        ]);

        $customer = Customer::where('user_id', Auth::id())->first();
        $cart = Cart::with('cartItems.product')->where('customer_id', $customer->id)->first();

        $address = \App\Models\Address::where('id', $request->address_id)
            ->where('customer_id', $customer->id)
            ->firstOrFail();

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

            // Voucher logic
            $discount = 0;
            $voucherId = null;
            if ($request->filled('voucher_id')) {
                $voucher = Voucher::find($request->voucher_id);
                if ($voucher && $subtotal >= $voucher->min_purchase) {
                    $claim = VoucherClaim::where('user_id', Auth::id())
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

                        $voucherId = $voucher->id;
                        $claim->update(['is_used' => true, 'used_at' => now()]);
                    }
                }
            }

            $totalPrice = $subtotal + $shipping + $tax - $discount;

            $order = Order::create([
                'customer_id' => $customer->id,
                'total_price' => $totalPrice,
                'voucher_id' => $voucherId,
                'discount_amount' => $discount,
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

        // Notify sellers
        $order = Order::with('orderItems.product.seller.user')->find($createdOrderId);
        $sellers = $order->orderItems->map(function ($item) {
            return $item->product->seller->user ?? null;
        })->filter()->unique('id');

        foreach ($sellers as $sellerUser) {
            $sellerUser->notify(new \App\Notifications\NewOrderNotification($order));
        }

        return redirect()->route('customer.payment', ['id' => $createdOrderId]);
    }

    public function payment($id)
    {
        $user = Auth::user();
        $customer = Customer::where('user_id', $user->id)->first();
        $order = Order::with('orderItems.product')->where('customer_id', $customer->id)->findOrFail($id);

        if ($order->status !== 'pending' && $order->status !== 'waiting_verification') {
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
            if ($order->payment_method === 'bank_transfer') {
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
                    return redirect()->route('customer.payment', $order->id)->with('success', 'Bukti pembayaran berhasil diupload, mohon tunggu verifikasi seller.');
                }
            } else {
                // COD Flow
                $order->update(['status' => 'paid']);
                return redirect()->route('customer.success', ['id' => $order->id]);
            }
        }

        return redirect()->route('customer.payment', $order->id);
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
        $seller = \App\Models\Seller::with('user')
            ->where('status', 'approved')
            ->findOrFail($id);
        $products = \App\Models\Product::with('images')
            ->where('seller_id', $id)
            ->where('status', 'approved')
            ->get();
        $totalProducts = $products->count();

        $allReviews = \App\Models\Review::whereHas('product', function ($q) use ($id) {
            $q->where('seller_id', $id);
        })->get();

        $averageRating = $allReviews->avg('rating') ?: 0;
        $totalReviews = $allReviews->count();

        // Ambil voucher aktif milik seller ini
        $vouchers = Voucher::where('seller_id', $id)
            ->where('start_date', '<=', now())
            ->where('end_date', '>=', now())
            ->where('quota', '>', 0)
            ->get();

        // Cek mana saja yang sudah di-claim user
        $claimedVoucherIds = [];
        if (Auth::check()) {
            $claimedVoucherIds = VoucherClaim::where('user_id', Auth::id())
                ->whereIn('voucher_id', $vouchers->pluck('id'))
                ->pluck('voucher_id')
                ->toArray();
        }

        return view('customer.seller-store', compact('seller', 'products', 'totalProducts', 'averageRating', 'totalReviews', 'vouchers', 'claimedVoucherIds'));
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
        if ($request->has('is_main')) {
            \App\Models\Address::where('customer_id', $customer->id)->update(['is_main' => false]);
        }

        \App\Models\Address::create([
            'customer_id' => $customer->id,
            'label' => $request->label,
            'recipient_name' => $request->recipient_name,
            'phone_number' => $request->phone_number,
            'city' => $request->city,
            'full_address' => $request->full_address,
            'is_main' => $request->has('is_main')
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

        if ($request->has('is_main')) {
            \App\Models\Address::where('customer_id', $customer->id)->update(['is_main' => false]);
        }

        $address->update([
            'label' => $request->label,
            'recipient_name' => $request->recipient_name,
            'phone_number' => $request->phone_number,
            'city' => $request->city,
            'full_address' => $request->full_address,
            'is_main' => $request->has('is_main')
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
        $user->update(['full_name' => $request->full_name]);

        \App\Models\Customer::updateOrCreate(
            ['user_id' => $user->id],
            ['phone' => $request->phone]
        );

        return back()->with('success', 'Informasi pribadi berhasil diperbarui.');
    }

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
            $favorite->delete();
            return back()->with('success', 'Produk dihapus dari wishlist.');
        } else {
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
            'notes' => 'nullable|string',
            'return_type' => 'required|in:refund,exchange',
            'video_proof' => 'required|file|mimes:mp4,mov,avi,wmv|max:20480', // max 20MB
            'photo_proof' => 'required|image|mimes:jpeg,png,jpg|max:2048',
            'bank_account_number' => 'required_if:return_type,refund|nullable|string|max:50',
        ]);

        $order = Order::findOrFail($order_id);

        try {
            DB::beginTransaction();

            $videoPath = null;
            if ($request->hasFile('video_proof')) {
                $videoPath = $request->file('video_proof')->store('return_proofs/videos', 'public');
            }

            $photoPath = null;
            if ($request->hasFile('photo_proof')) {
                $photoPath = $request->file('photo_proof')->store('return_proofs/photos', 'public');
            }

            \App\Models\ProductReturn::create([
                'order_id' => $order->id,
                'return_type' => $request->return_type,
                'reason' => $request->reason . ($request->notes ? ' - ' . $request->notes : ''),
                'status' => 'pending',
                'return_date' => now(),
                'video_proof' => $videoPath,
                'photo_proof' => $photoPath,
                'bank_account_number' => $request->bank_account_number,
            ]);

            // Update order status to returning
            $order->update(['status' => 'returning']);

            DB::commit();
            return redirect()->route('customer.return-request')->with('success', 'Return request submitted successfully.');

        } catch (\Exception $e) {
            DB::rollBack();
            \Illuminate\Support\Facades\Log::error('Return Request Submission Error: ' . $e->getMessage());
            return back()->withErrors(['error' => 'An error occurred while submitting your request: ' . $e->getMessage()])->withInput();
        }
    }

    public function returnDetail($id)
    {
        $user = Auth::user();
        $customer = \App\Models\Customer::where('user_id', $user->id)->first();
        $return = \App\Models\ProductReturn::with(['order.orderItems.product.images', 'order.customer.user'])
            ->findOrFail($id);
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
        $customer = \App\Models\Customer::firstOrCreate(['user_id' => $user->id]);

        if ($request->hasFile('profile_image')) {
            if ($customer->profile_image && \Illuminate\Support\Facades\Storage::disk('public')->exists($customer->profile_image)) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($customer->profile_image);
            }
            $path = $request->file('profile_image')->store('profiles', 'public');
            $customer->update(['profile_image' => $path]);
        }
        return back()->with('success', 'Profile image updated successfully!');
    }

    public function submitSupport(Request $request)
    {
        $request->validate([
            'subject' => 'required|string|max:255',
            'message' => 'required|string',
        ]);

        Support::create([
            'user_id' => Auth::id(),
            'subject' => $request->subject,
            'message' => $request->message,
            'status' => 'pending',
        ]);

        return back()->with('success', 'Komplain Anda telah dikirim ke Admin. Mohon tunggu respon kami.');
    }

    public function freeChat($id)
    {
        $designer = \App\Models\Designer::with('user')->findOrFail($id);
        $customer = \App\Models\Customer::where('user_id', \Illuminate\Support\Facades\Auth::id())->first();

        $freeConsultation = \App\Models\FreeConsultation::where('customer_id', $customer->id)
            ->where('designer_id', $id)
            ->first();

        if (!$freeConsultation) {
            $totalFreeUsed = \App\Models\FreeConsultation::where('customer_id', $customer->id)->count();
            if ($totalFreeUsed >= 3) {
                return redirect()->route('customer.designers.book', $id)
                    ->with('error', 'Anda telah mencapai batas maksimal 3x Free Consultation. Silakan Booking Consultation untuk melanjutkan.');
            }

            $freeConsultation = \App\Models\FreeConsultation::create([
                'customer_id' => $customer->id,
                'designer_id' => $id,
                'expires_at' => now()->addMinutes(30),
            ]);
        }

        if ($freeConsultation->is_completed || $freeConsultation->expires_at->isPast()) {
            if (!$freeConsultation->is_completed) {
                $freeConsultation->update(['is_completed' => true]);
            }
            return redirect()->route('customer.designers.book', $id);
        }

        $timeLeft = (int) now()->diffInSeconds($freeConsultation->expires_at);

        $messages = \App\Models\Chat::where(function ($q) use ($designer) {
            $q->where('sender_id', \Illuminate\Support\Facades\Auth::id())->where('receiver_id', $designer->user_id);
        })
            ->orWhere(function ($q) use ($designer) {
                $q->where('sender_id', $designer->user_id)->where('receiver_id', \Illuminate\Support\Facades\Auth::id());
            })
            ->oldest()
            ->get();

        return view('customer.free-chat', compact('designer', 'timeLeft', 'messages'));
    }

    public function bookConsultation($id)
    {
        $designer = \App\Models\Designer::with('user')->findOrFail($id);
        return view('customer.book-consultation', compact('designer'));
    }

    public function storeConsultation(Request $request, $id)
    {
        $request->validate([
            'title' => 'required|string|max:150',
            'consultation_type' => 'required|string',
            'description' => 'nullable|string',
            'budget_range' => 'required_if:consultation_type,request_proposal|nullable|string',
            'cover_image' => 'nullable|image|max:2048'
        ]);

        $customer = \App\Models\Customer::where('user_id', Auth::id())->first();

        $path = null;
        if ($request->hasFile('cover_image')) {
            $path = $request->file('cover_image')->store('consultations', 'public');
        }

        $typeLabel = $request->consultation_type === 'chat_consultation' ? 'Chat Consultation' : 'Request Proposal';
        $finalDescription = "Jenis Konsultasi: " . $typeLabel . "\n\n" . ($request->description ?? 'Tidak ada brief tambahan.');

        // Awal request statusnya WAITING_APPROVAL
        \App\Models\Consultation::create([
            'customer_id' => $customer->id,
            'designer_id' => $id,
            'title' => $request->title,
            'description' => $finalDescription,
            'budget_range' => $request->budget_range ?? '-',
            'cover_image' => $path,
            'consultation_type' => $request->consultation_type,
            'status' => \App\Models\Consultation::STATUS_WAITING_APPROVAL
        ]);

        // Mark any free consultation as completed
        \App\Models\FreeConsultation::where('customer_id', $customer->id)
            ->where('designer_id', $id)
            ->update(['is_completed' => true]);

        return redirect()->route('customer.my-consultations')->with('success', 'Request konsultasi telah dikirim! Menunggu approval desainer.');
    }

    public function myConsultations()
    {
        $customer = \App\Models\Customer::where('user_id', Auth::id())->first();
        $consultations = \App\Models\Consultation::with('designer.user')
            ->where('customer_id', $customer->id)
            ->latest()
            ->get();
        return view('customer.my-consultations', compact('consultations'));
    }

    public function designerWorkspace($consultation_id = null)
    {
        $user = Auth::user();
        $customer = \App\Models\Customer::where('user_id', $user->id)->first();

        $consultations = \App\Models\Consultation::with('designer.user')
            ->where('customer_id', $customer->id)
            ->latest()
            ->get();

        $activeConsultation = null;
        if ($consultation_id) {
            $activeConsultation = \App\Models\Consultation::with(['designer.user', 'messages', 'quotes', 'attachments'])
                ->where('id', $consultation_id)
                ->where('customer_id', $customer->id)
                ->firstOrFail();
        } elseif ($consultations->isNotEmpty()) {
            $activeConsultation = $consultations->first();
        }

        return view('customer.chat', compact('user', 'customer', 'consultations', 'activeConsultation'));
    }

    public function trackConsultationList(Request $request)
    {
        $user = Auth::user();
        $customer = \App\Models\Customer::where('user_id', $user->id)->first();

        $tab = $request->query('tab', 'semua');
        $query = \App\Models\Consultation::with(['designer.user', 'messages', 'quotes', 'attachments'])
            ->where('customer_id', $customer->id)
            ->latest();

        if ($tab == 'menunggu_pembayaran') {
            $query->whereIn('status', [\App\Models\Consultation::STATUS_WAITING_CONSULTATION_FEE, \App\Models\Consultation::STATUS_WAITING_FINAL_PAYMENT]);
        } elseif ($tab == 'aktif') {
            $query->whereIn('status', [
                \App\Models\Consultation::STATUS_WAITING_APPROVAL,
                \App\Models\Consultation::STATUS_WAITING_BRIEF,
                \App\Models\Consultation::STATUS_ACTIVE,
                \App\Models\Consultation::STATUS_UNDER_REVIEW,
                \App\Models\Consultation::STATUS_REVISION_REQUESTED,
                \App\Models\Consultation::STATUS_OFFER_RECEIVED
            ]);
        } elseif ($tab == 'selesai') {
            $query->where('status', \App\Models\Consultation::STATUS_COMPLETED);
        } elseif ($tab == 'dibatalkan') {
            $query->where('status', \App\Models\Consultation::STATUS_REJECTED);
        }

        $consultations = $query->get();

        $hasNewRab = \App\Models\Consultation::where('customer_id', $customer->id)
            ->whereIn('status', [\App\Models\Consultation::STATUS_OFFER_RECEIVED, \App\Models\Consultation::STATUS_UNDER_REVIEW])
            ->exists();
            
        if ($hasNewRab) {
            session()->now('show_rab_alert', true);
        }

        return view('customer.track-consultation', compact('user', 'customer', 'consultations', 'tab'));
    }

    public function downloadRab($quote_id)
    {
        $quote = \App\Models\ConsultationQuote::findOrFail($quote_id);

        $items = is_string($quote->items) ? json_decode($quote->items, true) : $quote->items;
        if ($items && isset($items['file_path'])) {
            $filePath = $items['file_path'];
            $fileName = $items['file_name'] ?? 'RAB-Project-' . $quote->consultation_id;

            if (\Illuminate\Support\Facades\Storage::disk('public')->exists($filePath)) {
                return \Illuminate\Support\Facades\Storage::disk('public')->download($filePath, $fileName);
            }
        }

        return back()->with('error', 'File RAB tidak ditemukan.');
    }

    // Alur Baru: Bayar Consultation Fee
    public function payConsultationFee(Request $request, $id)
    {
        $request->validate([
            'payment_proof' => 'required|file|image|max:10240',
        ]);

        $consultation = \App\Models\Consultation::findOrFail($id);

        if ($request->hasFile('payment_proof')) {
            $path = $request->file('payment_proof')->store('payment_proofs', 'public');
            $consultation->payment_proof = $path;
            $consultation->save();

            // Tidak perlu kirim pesan otomatis ke chat
        }

        return back()->with('success', 'Bukti pembayaran fee berhasil dikirim! Menunggu validasi desainer.');
    }

    // Alur Baru: Submit Brief (jika di awal kosong)
    public function submitConsultationBrief(Request $request, $id)
    {
        $consultation = \App\Models\Consultation::findOrFail($id);
        $consultation->description = $request->description;

        if ($request->hasFile('cover_image')) {
            $consultation->cover_image = $request->file('cover_image')->store('consultations', 'public');
        }

        $consultation->status = \App\Models\Consultation::STATUS_ACTIVE;
        $consultation->save();

        return back()->with('success', 'Brief berhasil dikirim! Silakan mulai chat dengan desainer.');
    }

    public function sendConsultationMessage(Request $request, $id)
    {
        $request->validate([
            'message' => 'nullable|string',
            'attachment' => 'nullable|file|max:5120'
        ]);

        if (!$request->message && !$request->hasFile('attachment')) {
            return back();
        }

        $consultation = \App\Models\Consultation::findOrFail($id);
        if ($consultation->is_chat_expired) {
            return back()->with('error', 'Waktu chat konsultasi telah habis.');
        }

        if ($request->message) {
            \App\Models\ConsultationMessage::create([
                'consultation_id' => $consultation->id,
                'sender_id' => Auth::id(),
                'message' => $request->message
            ]);
        }

        if ($request->hasFile('attachment')) {
            $path = $request->file('attachment')->store('consultations/attachments', 'public');
            $extension = $request->file('attachment')->getClientOriginalExtension();

            $fileType = 'document';
            if (in_array(strtolower($extension), ['jpg', 'jpeg', 'png', 'gif', 'webp'])) {
                $fileType = 'image';
            }

            \App\Models\ConsultationAttachment::create([
                'consultation_id' => $consultation->id,
                'uploaded_by' => Auth::id(),
                'file_url' => $path,
                'file_type' => $fileType
            ]);
        }

        return back();
    }

    // Alur Baru: Accept Offer
    public function acceptConsultationOffer($id)
    {
        $consultation = \App\Models\Consultation::findOrFail($id);
        $consultation->status = \App\Models\Consultation::STATUS_WAITING_FINAL_PAYMENT;
        $consultation->save();

        $quote = $consultation->quotes()->latest()->first();
        if ($quote) {
            $quote->update(['status' => 'accepted']);

            // Post RAB file to chat history as a message & attachment from the designer
            $items = is_string($quote->items) ? json_decode($quote->items, true) : $quote->items;
            if ($items && isset($items['file_path'])) {
                $filePath = $items['file_path'];

                // 1. Create text message from designer
                \App\Models\ConsultationMessage::create([
                    'consultation_id' => $consultation->id,
                    'sender_id' => $consultation->designer->user_id,
                    'message' => "Halo! Project Agreement & RAB telah disetujui. Berikut adalah file RAB resmi untuk proyek kita.",
                ]);

                // 2. Create attachment message from designer
                \App\Models\ConsultationAttachment::create([
                    'consultation_id' => $consultation->id,
                    'uploaded_by' => $consultation->designer->user_id,
                    'file_url' => $filePath,
                    'file_type' => 'document',
                ]);
            }
        }

        return back()->with('success', 'Project Agreement diterima! Silakan lakukan pembayaran akhir.');
    }

    // Alur Baru: Reject Offer
    public function rejectConsultationOffer($id)
    {
        $consultation = \App\Models\Consultation::findOrFail($id);
        $consultation->status = \App\Models\Consultation::STATUS_REJECTED; // Workspace locked
        $consultation->save();

        $quote = $consultation->quotes()->latest()->first();
        if ($quote) {
            $quote->update(['status' => 'rejected']);
        }

        return back()->with('info', 'Project ditolak dan workspace ditutup.');
    }

    // Alur Baru: Request Revision
    public function requestRevision(Request $request, $id)
    {
        $request->validate([
            'revision_notes' => 'required|string',
        ]);

        $consultation = \App\Models\Consultation::findOrFail($id);
        $consultation->status = \App\Models\Consultation::STATUS_ACTIVE;
        $consultation->save();

        $quote = $consultation->quotes()->latest()->first();
        if ($quote) {
            $quote->update([
                'status' => 'revision',
                'revision_notes' => $request->revision_notes,
            ]);

            // Post revision request to chat history as a message from the customer
            \App\Models\ConsultationMessage::create([
                'consultation_id' => $consultation->id,
                'sender_id' => Auth::id(),
                'message' => "🔄 Permintaan Revisi RAB:\n\"" . $request->revision_notes . "\"",
            ]);
        }

        return back()->with('success', 'Permintaan revisi telah dikirim ke desainer.');
    }

    // Alur Baru: Final Project Payment
    public function payFinalProject(Request $request, $id)
    {
        $request->validate([
            'payment_proof' => 'required|file|image|max:10240',
        ]);

        $consultation = \App\Models\Consultation::findOrFail($id);
        $quote = $consultation->quotes()->latest()->first();
        $amount = $quote ? $quote->amount : 0;

        if ($request->hasFile('payment_proof')) {
            $path = $request->file('payment_proof')->store('payment_proofs', 'public');
            $consultation->payment_proof = $path;
            $consultation->save();

            // Tidak perlu kirim pesan otomatis ke chat
        }

        return back()->with('success', 'Bukti pembayaran proyek berhasil dikirim! Menunggu validasi desainer.');
    }

    public function chatWithSeller($seller_id = null)
    {
        $user = Auth::user();
        $customer = \App\Models\Customer::where('user_id', $user->id)->first();

        // Get all sellers this customer has ordered from
        $orderedSellers = \App\Models\Order::with('orderItems.product.seller.user')
            ->where('customer_id', $customer->id)
            ->get()
            ->flatMap(function ($order) {
                return $order->orderItems->map(fn($item) => $item->product->seller);
            })
            ->filter()
            ->unique('id');

        // Also get sellers they have chatted with
        $chattedSellers = \App\Models\Chat::where('sender_id', $user->id)
            ->orWhere('receiver_id', $user->id)
            ->get()
            ->map(function ($chat) use ($user) {
                $otherUserId = ($chat->sender_id == $user->id) ? $chat->receiver_id : $chat->sender_id;
                return \App\Models\Seller::where('user_id', $otherUserId)->first();
            })
            ->filter()
            ->unique('id');

        $conversations = $orderedSellers->merge($chattedSellers)->unique('id');

        $activeSeller = null;
        $messages = [];

        if ($seller_id) {
            $activeSeller = \App\Models\Seller::with('user')->findOrFail($seller_id);

            $messages = \App\Models\Chat::where(function ($q) use ($user, $activeSeller) {
                $q->where('sender_id', $user->id)->where('receiver_id', $activeSeller->user_id);
            })->orWhere(function ($q) use ($user, $activeSeller) {
                $q->where('sender_id', $activeSeller->user_id)->where('receiver_id', $user->id);
            })->orderBy('created_at', 'asc')->get();

            // Mark as read
            \App\Models\Chat::where('sender_id', $activeSeller->user_id)
                ->where('receiver_id', $user->id)
                ->where('is_read', false)
                ->update(['is_read' => true]);
        }

        return view('customer.chat-seller', compact('user', 'customer', 'conversations', 'activeSeller', 'messages'));
    }

    public function sendChatMessage(Request $request)
    {
        $request->validate([
            'receiver_id' => 'required|exists:users,id',
            'message' => 'nullable|string',
            'product_id' => 'nullable|exists:products,id',
            'attachment' => 'nullable|file|mimes:jpeg,png,jpg,pdf,doc,docx,zip|max:5120'
        ]);

        if (!$request->message && !$request->hasFile('attachment')) {
            return back()->with('error', 'Pesan atau lampiran harus diisi.');
        }

        $attachmentPath = null;
        if ($request->hasFile('attachment')) {
            $attachmentPath = $request->file('attachment')->store('chat_attachments', 'public');
        }

        \App\Models\Chat::create([
            'sender_id' => Auth::id(),
            'receiver_id' => $request->receiver_id,
            'message' => $request->message ?? '',
            'product_id' => $request->product_id,
            'attachment' => $attachmentPath,
        ]);

        return back();
    }

    // ==========================================
    // 🎫 VOUCHER SYSTEM
    // ==========================================

    public function claimVoucher($id)
    {
        $voucher = Voucher::findOrFail($id);
        $user = Auth::user();

        // 1. Cek apakah sudah pernah claim
        $alreadyClaimed = VoucherClaim::where('user_id', $user->id)
            ->where('voucher_id', $voucher->id)
            ->exists();

        if ($alreadyClaimed) {
            return back()->with('error', 'Voucher sudah Anda claim sebelumnya.');
        }

        // 2. Cek kuota
        $usedCount = VoucherClaim::where('voucher_id', $voucher->id)->count();
        if ($usedCount >= $voucher->quota) {
            return back()->with('error', 'Maaf, kuota voucher ini sudah habis.');
        }

        // 3. Simpan claim
        VoucherClaim::create([
            'user_id' => $user->id,
            'voucher_id' => $voucher->id,
            'is_used' => false
        ]);

        return back()->with('success', 'Voucher berhasil diclaim! Gunakan saat checkout.');
    }

    public function applyVoucher(Request $request)
    {
        $request->validate([
            'code' => 'required|string',
            'seller_id' => 'required|exists:sellers,id'
        ]);

        $voucher = Voucher::where('code', strtoupper($request->code))
            ->where('seller_id', $request->seller_id)
            ->where('start_date', '<=', now())
            ->where('end_date', '>=', now())
            ->first();

        if (!$voucher) {
            return response()->json(['success' => false, 'message' => 'Voucher tidak valid atau sudah kadaluarsa.']);
        }

        $user = Auth::user();
        $claim = VoucherClaim::where('user_id', $user->id)
            ->where('voucher_id', $voucher->id)
            ->where('is_used', false)
            ->first();

        if (!$claim) {
            return response()->json(['success' => false, 'message' => 'Anda belum mengclaim voucher ini atau voucher sudah digunakan.']);
        }

        // Return data voucher untuk dihitung di frontend/backend checkout
        return response()->json([
            'success' => true,
            'voucher_id' => $voucher->id,
            'discount_type' => $voucher->discount_type,
            'discount_value' => $voucher->discount_value,
            'min_purchase' => $voucher->min_purchase,
            'max_discount' => $voucher->max_discount,
            'message' => 'Voucher berhasil dipasang!'
        ]);
    }
    public function completeOrder($id)
    {
        $user = Auth::user();
        $customer = Customer::where('user_id', $user->id)->first();
        $order = Order::where('customer_id', $customer->id)
            ->where('status', 'shipped')
            ->findOrFail($id);

        $order->update(['status' => 'completed']);

        return back()->with([
            'success' => 'Pesanan telah diterima! Terima kasih telah berbelanja.',
            'show_review_modal' => true,
            'completed_order_id' => $order->id
        ]);
    }

    public function submitOrderReviews(Request $request)
    {
        $request->validate([
            'order_id' => 'required|exists:orders,id',
            'reviews' => 'required|array',
            'reviews.*.product_id' => 'required|exists:products,id',
            'reviews.*.rating' => 'required|integer|min:1|max:5',
            'reviews.*.comment' => 'nullable|string|max:1000'
        ]);

        $customer = Customer::where('user_id', Auth::id())->first();
        
        $order = Order::where('id', $request->order_id)
            ->where('customer_id', $customer->id)
            ->firstOrFail();

        foreach ($request->reviews as $reviewData) {
            \App\Models\Review::create([
                'product_id' => $reviewData['product_id'],
                'customer_id' => $customer->id,
                'rating' => $reviewData['rating'],
                'comment' => $reviewData['comment'] ?? null
            ]);
        }

        return redirect()->route('customer.orders')->with('success', 'Ulasan Anda berhasil dikirim! Terima kasih.');
    }

    public function submitConsultationReview(Request $request, $id)
    {
        $request->validate([
            'rating'           => 'required|integer|min:1|max:5',
            'comment'          => 'nullable|string|max:2000',
            'project_duration' => 'required|string|max:100',
        ]);

        $consultation = \App\Models\Consultation::with('customer')->findOrFail($id);

        // Only the owning customer can review
        $customer = \App\Models\Customer::where('user_id', Auth::id())->firstOrFail();
        if ($consultation->customer_id !== $customer->id) {
            abort(403);
        }

        // Must be completed
        if ($consultation->status !== \App\Models\Consultation::STATUS_COMPLETED) {
            return back()->with('error', 'Hanya konsultasi yang sudah selesai yang dapat diulas.');
        }

        // One review per consultation
        if (\App\Models\ConsultationReview::where('consultation_id', $id)->exists()) {
            return back()->with('error', 'Anda sudah memberikan ulasan untuk konsultasi ini.');
        }

        \App\Models\ConsultationReview::create([
            'consultation_id'  => $id,
            'customer_id'      => Auth::id(),
            'designer_id'      => $consultation->designer_id,
            'rating'           => $request->rating,
            'comment'          => $request->comment,
            'project_duration' => $request->project_duration,
        ]);

        // Update the portfolio's duration based on customer input
        $portfolio = \App\Models\DesignerPortfolio::where('consultation_id', $id)->first();
        if ($portfolio) {
            $portfolio->update([
                'duration' => $request->project_duration
            ]);
        }

        return back()->with('success', 'Terima kasih! Ulasan Anda berhasil dikirim.');
    }
}