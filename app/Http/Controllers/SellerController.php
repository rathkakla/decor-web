<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use App\Models\Seller;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\ProductImage;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use App\Models\Voucher;
use App\Models\Support;
use Barryvdh\DomPDF\Facade\Pdf;

class SellerController extends Controller
{
    // ==========================================
    // 1. DASHBOARD & MENU LAINNYA
    // ==========================================

    public function dashboard(Request $request)
    {
        $seller = Seller::where('user_id', Auth::id())->first();
        $selectedYear = $request->get('year', date('Y'));

        // 1. Total Produk Aktif
        $totalProducts = Product::where('seller_id', $seller->id)->count();

        // 2. Pesanan Pending (Status 'pending' atau 'unpaid')
        $newOrdersCount = Order::whereHas('orderItems.product', function ($q) use ($seller) {
            $q->where('seller_id', $seller->id);
        })->whereIn('status', ['pending', 'unpaid'])->count();

        // 3. Estimasi Pendapatan (Hanya yang sudah diproses ke atas)
        $estimatedRevenue = OrderItem::whereHas('product', function ($q) use ($seller) {
            $q->where('seller_id', $seller->id);
        })->whereHas('order', function ($q) {
            $q->whereIn('status', ['processing', 'shipped', 'delivered', 'completed']);
        })->sum(DB::raw('price * quantity'));

        // 4. Rating Toko (Rata-rata review produk)
        $averageRating = Review::whereHas('product', function ($q) use ($seller) {
            $q->where('seller_id', $seller->id);
        })->avg('rating') ?: 0;

        // 5. Data Chart (Januari sampai Desember pada tahun terpilih)
        $months = ['JAN', 'FEB', 'MAR', 'APR', 'MAY', 'JUN', 'JUL', 'AUG', 'SEP', 'OCT', 'NOV', 'DEC'];
        $revenueData = [];
        
        for ($m = 1; $m <= 12; $m++) {
            $start = "$selectedYear-" . str_pad($m, 2, '0', STR_PAD_LEFT) . "-01 00:00:00";
            $end = date("Y-m-t 23:59:59", strtotime($start));

            $rev = OrderItem::whereHas('product', function ($q) use ($seller) {
                $q->where('seller_id', $seller->id);
            })->whereHas('order', function ($q) use ($start, $end) {
                $q->whereIn('status', ['processing', 'shipped', 'delivered', 'completed'])
                    ->whereBetween('created_at', [$start, $end]);
            })->sum(DB::raw('price * quantity'));

            $revenueData[] = (float) $rev;
        }

        // Ambil daftar tahun dari order (untuk filter) + Tambahkan minimal 3 tahun terakhir
        $dbYears = Order::selectRaw('YEAR(created_at) as year')
            ->distinct()
            ->pluck('year')
            ->toArray();
            
        $currentYear = (int)date('Y');
        $defaultYears = [$currentYear, $currentYear - 1, $currentYear - 2];
        
        $availableYears = array_unique(array_merge($dbYears, $defaultYears));
        rsort($availableYears); // Urutkan dari yang terbaru

        // 6. Pesanan Terbaru
        $recentOrders = Order::whereHas('orderItems.product', function ($q) use ($seller) {
            $q->where('seller_id', $seller->id);
        })->with(['customer.user'])
            ->latest()
            ->take(5)
            ->get();

        return view('seller.dashboard', compact(
            'seller',
            'totalProducts',
            'newOrdersCount',
            'estimatedRevenue',
            'averageRating',
            'months',
            'revenueData',
            'recentOrders',
            'selectedYear',
            'availableYears'
        ));
    }

    public function reportIndex(Request $request)
    {
        if ($request->has('start_date') && $request->has('end_date')) {
            $request->validate([
                'start_date' => 'required|date',
                'end_date' => 'required|date|after_or_equal:start_date',
            ], [
                'end_date.after_or_equal' => 'Periode tidak valid: Sampai Tanggal tidak boleh mendahului Dari Tanggal.',
            ]);
        }

        $seller = Seller::where('user_id', Auth::id())->first();

        $startDate = $request->get('start_date', now()->startOfMonth()->toDateString());
        $endDate = $request->get('end_date', now()->endOfMonth()->toDateString());

        $transactions = OrderItem::whereHas('product', function ($q) use ($seller) {
            $q->where('seller_id', $seller->id);
        })->whereHas('order', function ($q) use ($startDate, $endDate) {
            $q->where('status', 'completed')
                ->whereBetween('created_at', [$startDate . ' 00:00:00', $endDate . ' 23:59:59']);
        })->with(['order', 'product.category'])
            ->latest()
            ->get();

        $totalRevenue = $transactions->sum(fn($item) => $item->price * $item->quantity);

        $newOrdersCount = 0; // Transactions are now filtered to completed only

        $orderCount = $transactions->groupBy('order_id')->count();
        $averageOrder = $orderCount > 0 ? $totalRevenue / $orderCount : 0;

        return view('seller.reports', compact(
            'transactions',
            'totalRevenue',
            'newOrdersCount',
            'averageOrder',
            'startDate',
            'endDate'
        ));
    }

    public function downloadReport(Request $request)
    {
        if ($request->has('start_date') && $request->has('end_date')) {
            $request->validate([
                'start_date' => 'required|date',
                'end_date' => 'required|date|after_or_equal:start_date',
            ], [
                'end_date.after_or_equal' => 'Periode tidak valid: Sampai Tanggal tidak boleh mendahului Dari Tanggal.',
            ]);
        }

        $seller = Seller::where('user_id', Auth::id())->first();

        $startDate = $request->get('start_date', now()->startOfMonth()->toDateString());
        $endDate = $request->get('end_date', now()->endOfMonth()->toDateString());

        $transactions = OrderItem::whereHas('product', function ($q) use ($seller) {
            $q->where('seller_id', $seller->id);
        })->whereHas('order', function ($q) use ($startDate, $endDate) {
            $q->where('status', 'completed')
                ->whereBetween('created_at', [$startDate . ' 00:00:00', $endDate . ' 23:59:59']);
        })->with(['order', 'product.category'])
            ->latest()
            ->get();

        $totalRevenue = $transactions->sum(fn($item) => $item->price * $item->quantity);

        $pdf = Pdf::loadView('seller.pdf_report', compact(
            'transactions',
            'totalRevenue',
            'startDate',
            'endDate',
            'seller'
        ));

        return $pdf->download('Sales_Report_' . $startDate . '_to_' . $endDate . '.pdf');
    }

    // Fungsi Support/Komplain agar tidak error 'Route not defined'
    public function support()
    {
        return view('seller.Support.support');
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

    public function supportChat()
    {
        return view('seller.Support.chat');
    }

    public function chatIndex($userId = null)
    {
        $sellerId = Auth::id();

        // Get unique users who have messaged this seller
        $conversations = \App\Models\Chat::where('receiver_id', $sellerId)
            ->orWhere('sender_id', $sellerId)
            ->with(['sender', 'receiver'])
            ->latest()
            ->get()
            ->map(function ($chat) use ($sellerId) {
                return $chat->sender_id == $sellerId ? $chat->receiver : $chat->sender;
            })
            ->unique('id')
            ->values();

        $activeChat = null;
        $messages = [];

        if ($userId) {
            $activeChat = \App\Models\User::findOrFail($userId);
            $messages = \App\Models\Chat::where(function ($q) use ($sellerId, $userId) {
                $q->where('sender_id', $sellerId)->where('receiver_id', $userId);
            })
                ->orWhere(function ($q) use ($sellerId, $userId) {
                    $q->where('sender_id', $userId)->where('receiver_id', $sellerId);
                })
                ->with('product')
                ->oldest()
                ->get();

            // Mark as read
            \App\Models\Chat::where('sender_id', $userId)
                ->where('receiver_id', $sellerId)
                ->where('is_read', false)
                ->update(['is_read' => true]);
        }

        return view('seller.chats.index', compact('conversations', 'activeChat', 'messages'));
    }

    public function sendChatMessage(Request $request)
    {
        $request->validate([
            'receiver_id' => 'required|exists:users,id',
            'message' => 'nullable|string',
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
            'attachment' => $attachmentPath,
        ]);

        return redirect()->back();
    }

    // ==========================================
    // 2. KELOLA PRODUK (CRUD)
    // ==========================================

    // READ: Daftar Produk
    public function productIndex(Request $request)
    {
        $seller = Seller::where('user_id', Auth::id())->first();
        $query = Product::with(['images', 'category'])
            ->where('seller_id', $seller->id);

        if ($request->has('search') && $request->search != '') {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        $products = $query->latest()->get();
        return view('seller.products.index', compact('products'));
    }

    // CREATE: Form Tambah
    public function createProduct()
    {
        $categories = Category::all();
        $styles = ['Minimalist', 'Modern', 'Industrial', 'Japandi', 'Classic', 'Bohemian'];
        return view('seller.products.create', compact('categories', 'styles'));
    }

    // STORE: Simpan Tambah
    public function storeProduct(Request $request)
    {
        $seller = Seller::where('user_id', Auth::id())->first();

        $request->validate([
            'price' => 'required|numeric|min:0|max:1000000000',
        ], [
            'price.max' => 'Harga produk tidak valid (terlalu mahal). Maksimal Rp 1.000.000.000',
            'price.min' => 'Harga produk tidak boleh kurang dari 0',
        ]);

        // 1. Simpan data produk utama
        $product = Product::create([
            'seller_id' => $seller->id,
            'category_id' => $request->category_id,
            'style' => $request->style,
            'name' => $request->name,
            'description' => $request->description,
            'price' => $request->price,
            'stock' => $request->stock,
        ]);

        // 2. Simpan gambar ke database (BAGIAN INI YANG KRUSIAL)
        if ($request->hasFile('image')) {
            // Simpan file fisik
            $path = $request->file('image')->store('products', 'public');

            // Simpan record ke tabel product_images
            // Pastikan nama model dan kolomnya tepat
            ProductImage::create([
                'product_id' => $product->id,
                'img_url' => '/storage/' . $path
            ]);
        }

        return redirect()->route('seller.products.index')->with('success', 'Produk ' . $product->name . ' berhasil ditambahkan!');
    }

    // EDIT: Form Ubah
    public function editProduct($id)
    {
        $product = Product::with('images')->findOrFail($id);
        $seller = Seller::where('user_id', Auth::id())->first();

        // Keamanan: Cek apakah ini produk milik dia sendiri
        if ($product->seller_id !== $seller->id) {
            return redirect()->route('seller.products.index')->with('error', 'Akses ditolak!');
        }

        $categories = Category::all();
        $styles = ['Minimalist', 'Modern', 'Industrial', 'Japandi', 'Classic', 'Bohemian'];

        return view('seller.products.edit', compact('product', 'categories', 'styles'));
    }

    // UPDATE: Simpan Ubah
    public function updateProduct(Request $request, $id)
    {
        $product = Product::findOrFail($id);
        $seller = Seller::where('user_id', Auth::id())->first();

        if ($product->seller_id !== $seller->id) {
            return redirect()->route('seller.products.index')->with('error', 'Akses ditolak!');
        }

        $request->validate([
            'price' => 'required|numeric|min:0|max:1000000000',
        ], [
            'price.max' => 'Harga produk tidak valid (terlalu mahal). Maksimal Rp 1.000.000.000',
            'price.min' => 'Harga produk tidak boleh kurang dari 0',
        ]);

        $product->update([
            'category_id' => $request->category_id,
            'style' => $request->style,
            'name' => $request->name,
            'description' => $request->description,
            'price' => $request->price,
            'stock' => $request->stock,
        ]);

        // Proses jika Seller mengupload gambar baru saat edit
        if ($request->hasFile('image')) {
            // Hapus gambar lama dari storage (Opsional tapi direkomendasikan)
            $oldImage = ProductImage::where('product_id', $product->id)->first();
            if ($oldImage) {
                $oldPath = str_replace('/storage/', '', $oldImage->img_url);
                Storage::disk('public')->delete($oldPath);
                $oldImage->delete();
            }

            // Simpan gambar baru
            $path = $request->file('image')->store('products', 'public');
            ProductImage::create([
                'product_id' => $product->id,
                'img_url' => '/storage/' . $path
            ]);
        }

        return redirect()->route('seller.products.index')->with('success', 'Detail produk berhasil diperbarui!');
    }

    // DELETE: Hapus Produk
    public function deleteProduct($id)
    {
        $product = Product::findOrFail($id);
        $seller = Seller::where('user_id', Auth::id())->first();

        if ($product->seller_id !== $seller->id) {
            return redirect()->route('seller.products.index')->with('error', 'Akses ditolak!');
        }

        // Hapus gambar fisik dari storage sebelum menghapus data
        $images = ProductImage::where('product_id', $product->id)->get();
        foreach ($images as $img) {
            $path = str_replace('/storage/', '', $img->img_url);
            Storage::disk('public')->delete($path);
        }

        // Hapus data produk (otomatis hapus product_images jika database pakai onDelete('cascade'))
        $product->delete();

        return redirect()->route('seller.products.index')->with('success', 'Produk berhasil dihapus dari galeri.');
    }

    // ==========================================
    // 3. KELOLA PESANAN
    // ==========================================

    public function orderIndex(Request $request)
    {
        $seller = Seller::where('user_id', Auth::id())->first();

        $query = Order::whereHas('orderItems.product', function ($q) use ($seller) {
            $q->where('seller_id', $seller->id);
        })->with(['customer.user', 'orderItems.product.images'])->latest();

        if ($request->has('status') && $request->status != 'all') {
            $query->where('status', $request->status);
        }

        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('id', 'like', "%{$search}%")
                  ->orWhere('return_code', 'like', "%{$search}%")
                  ->orWhereHas('customer.user', function($qUser) use ($search) {
                      $qUser->where('full_name', 'like', "%{$search}%")
                            ->orWhere('username', 'like', "%{$search}%");
                  });
            });
        }

        $orders = $query->get();
        $currentStatus = $request->query('status', 'all');

        return view('seller.orders.index', compact('orders', 'currentStatus'));
    }

    public function updateOrderStatus(Request $request, $id)
    {
        $order = Order::findOrFail($id);
        $order->update([
            'status' => $request->status
        ]);

        return back()->with('success', 'Status pesanan #' . $id . ' berhasil diperbarui!');
    }

    public function validatePayment(Request $request, $id)
    {
        $request->validate([
            'action' => 'required|in:approve,reject',
        ]);

        $order = Order::findOrFail($id);

        if ($request->action === 'approve') {
            $order->update(['status' => 'paid']);
            return back()->with('success', 'Pembayaran untuk pesanan #' . $id . ' berhasil dikonfirmasi!');
        } else {
            $order->update(['status' => 'pending', 'payment_proof' => null]);
            return back()->with('error', 'Pembayaran untuk pesanan #' . $id . ' ditolak. Customer diminta mengupload ulang.');
        }
    }

    // READ: Detail Pesanan (show.blade.php)
    public function showOrder($id)
    {
        $order = Order::with(['customer.user', 'orderItems.product.images'])->findOrFail($id);
        return view('seller.orders.show', compact('order'));
    }

    // CETAK: Invoice (invoice.blade.php)
    public function printInvoice($id)
    {
        $order = Order::with(['customer.user', 'orderItems.product'])->findOrFail($id);
        return view('pdf.invoice', compact('order'));
    }

    public function downloadPdfInvoice($id)
    {
        $order = Order::with(['customer.user', 'orderItems.product'])->findOrFail($id);
        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('pdf.invoice-pdf', compact('order'));
        return $pdf->download('invoice-' . $order->id . '.pdf');
    }

    // CETAK: Label Pengiriman (label.blade.php)
    public function printLabel($id)
    {
        $order = Order::with(['customer.user'])->findOrFail($id);
        return view('seller.orders.label', compact('order'));
    }

    // ==========================================
    // 4. KELOLA KOMPLAIN / PENGEMBALIAN (RETURNS)
    // ==========================================

    public function complaintIndex(Request $request)
    {
        $seller = Seller::where('user_id', Auth::id())->first();

        $allReturns = \App\Models\ProductReturn::with(['order.customer.user', 'order.orderItems.product'])
            ->whereHas('order.orderItems.product', function ($query) use ($seller) {
                $query->where('seller_id', $seller->id);
            })
            ->latest()
            ->get();

        $statusFilter = $request->query('status');
        $returns = $allReturns;
        if ($statusFilter == 'pending') {
            $returns = $allReturns->where('status', 'pending');
        } elseif ($statusFilter == 'resolved') {
            $returns = $allReturns->whereIn('status', ['approved', 'rejected']);
        }

        $counts = [
            'all' => $allReturns->count(),
            'pending' => $allReturns->where('status', 'pending')->count(),
            'resolved' => $allReturns->whereIn('status', ['approved', 'rejected'])->count(),
        ];

        return view('seller.complaint.index', [
            'returns' => $returns,
            'counts' => $counts,
            'currentStatus' => $statusFilter ?? 'all'
        ]);
    }

    public function complaintDetail($id)
    {
        $seller = Seller::where('user_id', Auth::id())->first();
        $return = \App\Models\ProductReturn::with(['order.customer.user', 'order.orderItems.product.images'])
            ->findOrFail($id);

        return view('seller.complaint.detail', compact('return'));
    }

    public function approveReturn($id)
    {
        $return = \App\Models\ProductReturn::with('order.orderItems')->findOrFail($id);
        
        DB::transaction(function () use ($return) {
            $return->update(['status' => 'approved']);
            
            $originalOrder = $return->order;
            $originalOrder->update(['status' => 'return approved']);

            if ($return->return_type === 'exchange') {
                // Create new order
                $newOrder = Order::create([
                    'customer_id' => $originalOrder->customer_id,
                    'total_price' => $originalOrder->total_price,
                    'voucher_id' => $originalOrder->voucher_id,
                    'discount_amount' => $originalOrder->discount_amount,
                    'shipping_courier' => $originalOrder->shipping_courier,
                    'shipping_recipient' => $originalOrder->shipping_recipient,
                    'shipping_phone' => $originalOrder->shipping_phone,
                    'shipping_city' => $originalOrder->shipping_city,
                    'shipping_province' => $originalOrder->shipping_province,
                    'shipping_postal_code' => $originalOrder->shipping_postal_code,
                    'shipping_address' => $originalOrder->shipping_address,
                    'payment_method' => $originalOrder->payment_method,
                    'status' => 'paid', // Automatically paid as it's an exchange
                    'payment_proof' => $originalOrder->payment_proof,
                    'return_code' => 'RET-' . $originalOrder->id,
                ]);

                foreach ($originalOrder->orderItems as $item) {
                    OrderItem::create([
                        'order_id' => $newOrder->id,
                        'product_id' => $item->product_id,
                        'quantity' => $item->quantity,
                        'price' => $item->price,
                    ]);
                    
                    // Decrement stock for the new order
                    $item->product->decrement('stock', $item->quantity);
                }
            }
        });

        return redirect()->route('seller.complaint.index')->with('success', 'Return request has been approved.');
    }

    public function rejectReturn($id)
    {
        $return = \App\Models\ProductReturn::findOrFail($id);
        $return->update(['status' => 'rejected']);

        return redirect()->route('seller.complaint.index')->with('success', 'Return request has been rejected.');
    }

    public function reviewIndex()
    {
        $seller = Seller::where('user_id', Auth::id())->first();

        // Ambil semua review dari produk milik seller ini
        $reviews = \App\Models\Review::with(['product.images', 'customer.user'])
            ->whereHas('product', function ($q) use ($seller) {
                $q->where('seller_id', $seller->id);
            })
            ->latest()
            ->get();

        // Hitung Rating Rata-rata Seller
        $averageRating = $reviews->avg('rating') ?: 0;
        $totalReviews = $reviews->count();

        // Data untuk Bar Rating (5 star, 4 star, dll)
        $ratingCounts = [
            5 => $reviews->where('rating', 5)->count(),
            4 => $reviews->where('rating', 4)->count(),
            3 => $reviews->where('rating', 3)->count(),
            2 => $reviews->where('rating', 2)->count(),
            1 => $reviews->where('rating', 1)->count(),
        ];

        // Sentiment Score Logic
        $sentimentScore = 'Neutral';
        if ($averageRating >= 4.5)
            $sentimentScore = 'Excellent';
        elseif ($averageRating >= 4.0)
            $sentimentScore = 'Very Good';
        elseif ($averageRating >= 3.0)
            $sentimentScore = 'Good';
        elseif ($averageRating >= 2.0)
            $sentimentScore = 'Fair';
        else
            $sentimentScore = 'Poor';

        // Response Rate Logic
        $repliedReviews = $reviews->whereNotNull('reply')->count();
        $responseRate = $totalReviews > 0 ? ($repliedReviews / $totalReviews) * 100 : 0;

        return view('seller.reviews', compact('reviews', 'averageRating', 'totalReviews', 'ratingCounts', 'sentimentScore', 'responseRate'));
    }
    public function replyReview(Request $request, $id)
    {
        $request->validate([
            'reply' => 'required|string|max:1000',
        ]);

        $review = \App\Models\Review::findOrFail($id);
        $review->update([
            'reply' => $request->reply,
        ]);

        return redirect()->back()->with('success', 'Reply posted successfully.');
    }

    public function settings()
    {
        $user = Auth::user();
        $seller = Seller::where('user_id', $user->id)->firstOrFail();
        return view('seller.Settings.index', compact('seller'));
    }

    public function updateSettings(Request $request)
    {
        $user = Auth::user();
        $seller = Seller::where('user_id', $user->id)->firstOrFail();

        $request->validate([
            'store_name' => 'required|string|max:255',
            'store_description' => 'nullable|string|max:1000',
            'store_address' => 'nullable|string|max:500',
            'bank_name' => 'nullable|string|max:100',
            'account_number' => 'nullable|string|max:50',
            'store_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp,svg|max:2048',
            'store_banner' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp,svg|max:5120',
            'current_password' => 'nullable|string',
            'new_password' => 'nullable|string|min:8|confirmed',
        ], [
            'new_password.confirmed' => 'Konfirmasi password baru tidak cocok.',
            'new_password.min' => 'Password baru minimal 8 karakter.',
        ]);

        $data = $request->only(['store_name', 'store_description', 'store_address', 'bank_name', 'account_number']);

        if ($request->hasFile('store_image')) {
            if ($seller->store_image) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($seller->store_image);
            }
            $data['store_image'] = $request->file('store_image')->store('sellers/logos', 'public');
        }

        if ($request->hasFile('store_banner')) {
            if ($seller->store_banner) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($seller->store_banner);
            }
            $data['store_banner'] = $request->file('store_banner')->store('sellers/banners', 'public');
        }

        $seller->update($data);

        $successMessage = 'Pengaturan toko berhasil diperbarui!';

        if ($request->filled('current_password')) {
            if (!\Illuminate\Support\Facades\Hash::check($request->current_password, $user->password)) {
                return back()->withErrors(['current_password' => 'Password saat ini salah.']);
            }
            if (!$request->filled('new_password')) {
                return back()->withErrors(['new_password' => 'Password baru harus diisi.']);
            }
            
            $user->update(['password' => \Illuminate\Support\Facades\Hash::make($request->new_password)]);
            $successMessage = 'Pengaturan toko dan password berhasil diperbarui!';
        } elseif ($request->filled('new_password')) {
            return back()->withErrors(['current_password' => 'Password saat ini harus diisi untuk mengubah password.']);
        }

        return back()->with('success', $successMessage);
    }

    // ==========================================
    // 5. KELOLA VOUCHER
    // ==========================================

    public function voucherIndex()
    {
        $seller = Seller::where('user_id', Auth::id())->first();
        $vouchers = Voucher::where('seller_id', $seller->id)->latest()->get();
        return view('seller.vouchers.index', compact('vouchers'));
    }

    public function createVoucher()
    {
        return view('seller.vouchers.create');
    }

    public function storeVoucher(Request $request)
    {
        $seller = Seller::where('user_id', Auth::id())->first();

        $request->validate([
            'code' => [
                'required', 
                'string', 
                'max:50', 
                \Illuminate\Validation\Rule::unique('vouchers', 'code')->where(function ($query) use ($seller) {
                    return $query->where('seller_id', $seller->id);
                })
            ],
            'name' => 'required|string|max:255',
            'discount_type' => 'required|in:fixed,percentage',
            'discount_value' => 'required|numeric|min:0',
            'min_purchase' => 'nullable|numeric|min:0',
            'max_discount' => 'nullable|numeric|min:0',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'quota' => 'required|integer|min:1',
        ], [
            'code.unique' => 'Kode voucher ini sudah Anda gunakan sebelumnya. Silakan buat kode lain.',
            'end_date.after' => 'Tanggal kadaluarsa harus lebih lambat dari tanggal mulai.',
        ]);

        Voucher::create([
            'seller_id' => $seller->id,
            'code' => strtoupper($request->code),
            'name' => $request->name,
            'discount_type' => $request->discount_type,
            'discount_value' => $request->discount_value,
            'min_purchase' => $request->min_purchase ?? 0,
            'max_discount' => $request->max_discount,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'quota' => $request->quota,
        ]);

        return redirect()->route('seller.vouchers.index')->with('success', 'Voucher berhasil dibuat!');
    }

    public function editVoucher($id)
    {
        $voucher = Voucher::findOrFail($id);
        $seller = Seller::where('user_id', Auth::id())->first();

        if ($voucher->seller_id !== $seller->id) {
            return redirect()->route('seller.vouchers.index')->with('error', 'Akses ditolak!');
        }

        return view('seller.vouchers.edit', compact('voucher'));
    }

    public function updateVoucher(Request $request, $id)
    {
        $voucher = Voucher::findOrFail($id);
        $seller = Seller::where('user_id', Auth::id())->first();

        if ($voucher->seller_id !== $seller->id) {
            return redirect()->route('seller.vouchers.index')->with('error', 'Akses ditolak!');
        }

        $request->validate([
            'code' => [
                'required', 
                'string', 
                'max:50', 
                \Illuminate\Validation\Rule::unique('vouchers', 'code')->where(function ($query) use ($seller) {
                    return $query->where('seller_id', $seller->id);
                })->ignore($id)
            ],
            'name' => 'required|string|max:255',
            'discount_type' => 'required|in:fixed,percentage',
            'discount_value' => 'required|numeric|min:0',
            'min_purchase' => 'nullable|numeric|min:0',
            'max_discount' => 'nullable|numeric|min:0',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'quota' => 'required|integer|min:1',
        ], [
            'code.unique' => 'Kode voucher ini sudah Anda gunakan sebelumnya. Silakan gunakan kode lain.',
            'end_date.after' => 'Tanggal kadaluarsa harus lebih lambat dari tanggal mulai.',
        ]);

        $voucher->update([
            'code' => strtoupper($request->code),
            'name' => $request->name,
            'discount_type' => $request->discount_type,
            'discount_value' => $request->discount_value,
            'min_purchase' => $request->min_purchase ?? 0,
            'max_discount' => $request->max_discount,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'quota' => $request->quota,
        ]);

        return redirect()->route('seller.vouchers.index')->with('success', 'Voucher berhasil diperbarui!');
    }

    public function deleteVoucher($id)
    {
        $voucher = Voucher::findOrFail($id);
        $seller = Seller::where('user_id', Auth::id())->first();

        if ($voucher->seller_id !== $seller->id) {
            return redirect()->route('seller.vouchers.index')->with('error', 'Akses ditolak!');
        }

        $voucher->delete();
        return redirect()->route('seller.vouchers.index')->with('success', 'Voucher berhasil dihapus.');
    }
}