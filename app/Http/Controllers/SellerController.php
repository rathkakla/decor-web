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
use Barryvdh\DomPDF\Facade\Pdf;

class SellerController extends Controller
{
    // ==========================================
    // 1. DASHBOARD & MENU LAINNYA
    // ==========================================
    
    public function dashboard() {
        $seller = Seller::where('user_id', Auth::id())->first();
        
        // 1. Total Produk Aktif
        $totalProducts = Product::where('seller_id', $seller->id)->count();
        
        // 2. Pesanan Baru (Status 'pending' atau 'unpaid')
        $newOrdersCount = Order::whereHas('orderItems.product', function($q) use ($seller) {
            $q->where('seller_id', $seller->id);
        })->whereIn('status', ['pending', 'unpaid'])->count();
        
        // 3. Estimasi Pendapatan (Hanya yang sudah diproses ke atas)
        $estimatedRevenue = OrderItem::whereHas('product', function($q) use ($seller) {
            $q->where('seller_id', $seller->id);
        })->whereHas('order', function($q) {
            $q->whereIn('status', ['processing', 'shipped', 'delivered', 'completed']);
        })->sum(DB::raw('price * quantity'));
        
        // 4. Rating Toko (Rata-rata review produk)
        $averageRating = Review::whereHas('product', function($q) use ($seller) {
            $q->where('seller_id', $seller->id);
        })->avg('rating') ?: 0;
        
        // 5. Data Chart (6 bulan terakhir)
        $months = [];
        $revenueData = [];
        for ($i = 5; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $monthName = $date->format('M');
            $months[] = strtoupper($monthName);
            
            $start = $date->copy()->startOfMonth()->toDateTimeString();
            $end = $date->copy()->endOfMonth()->toDateTimeString();

            $rev = OrderItem::whereHas('product', function($q) use ($seller) {
                $q->where('seller_id', $seller->id);
            })->whereHas('order', function($q) use ($start, $end) {
                $q->whereIn('status', ['processing', 'shipped', 'delivered', 'completed'])
                  ->whereBetween('created_at', [$start, $end]);
            })->sum(DB::raw('price * quantity'));
            
            $revenueData[] = (float) $rev;
        }

        // 6. Pesanan Terbaru
        $recentOrders = Order::whereHas('orderItems.product', function($q) use ($seller) {
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
            'recentOrders'
        ));
    }

    public function reportIndex(Request $request) {
        $seller = Seller::where('user_id', Auth::id())->first();
        
        $startDate = $request->get('start_date', now()->startOfMonth()->toDateString());
        $endDate = $request->get('end_date', now()->endOfMonth()->toDateString());

        $transactions = OrderItem::whereHas('product', function($q) use ($seller) {
            $q->where('seller_id', $seller->id);
        })->whereHas('order', function($q) use ($startDate, $endDate) {
            $q->whereBetween('created_at', [$startDate . ' 00:00:00', $endDate . ' 23:59:59']);
        })->with(['order', 'product.category'])
          ->latest()
          ->get();

        $totalRevenue = $transactions->whereIn('order.status', ['processing', 'shipped', 'delivered', 'completed'])
                                     ->sum(fn($item) => $item->price * $item->quantity);
        
        $newOrdersCount = $transactions->whereIn('order.status', ['pending', 'unpaid'])->count();
        
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

    public function downloadReport(Request $request) {
        $seller = Seller::where('user_id', Auth::id())->first();
        
        $startDate = $request->get('start_date', now()->startOfMonth()->toDateString());
        $endDate = $request->get('end_date', now()->endOfMonth()->toDateString());

        $transactions = OrderItem::whereHas('product', function($q) use ($seller) {
            $q->where('seller_id', $seller->id);
        })->whereHas('order', function($q) use ($startDate, $endDate) {
            $q->whereBetween('created_at', [$startDate . ' 00:00:00', $endDate . ' 23:59:59']);
        })->with(['order', 'product.category'])
          ->latest()
          ->get();

        $totalRevenue = $transactions->whereIn('order.status', ['processing', 'shipped', 'delivered', 'completed'])
                                     ->sum(fn($item) => $item->price * $item->quantity);

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
    public function support() {
        return view('seller.Support.support'); 
    }

    public function supportChat() {
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
            'message' => 'required|string',
        ]);

        \App\Models\Chat::create([
            'sender_id' => Auth::id(),
            'receiver_id' => $request->receiver_id,
            'message' => $request->message,
        ]);

        return redirect()->back();
    }

    // ==========================================
    // 2. KELOLA PRODUK (CRUD)
    // ==========================================

    // READ: Daftar Produk
    public function productIndex() {
        $seller = Seller::where('user_id', Auth::id())->first();
        $products = Product::with(['images', 'category'])
                           ->where('seller_id', $seller->id)
                           ->latest()
                           ->get();
        return view('seller.products.index', compact('products'));
    }

    // CREATE: Form Tambah
    public function createProduct() {
        $categories = Category::all();
        $styles = ['Minimalist', 'Modern', 'Industrial', 'Japandi', 'Classic', 'Bohemian'];
        return view('seller.products.create', compact('categories', 'styles'));
    }

    // STORE: Simpan Tambah
    public function storeProduct(Request $request) {
    $seller = Seller::where('user_id', Auth::id())->first();

    // 1. Simpan data produk utama
    $product = Product::create([
        'seller_id'   => $seller->id,
        'category_id' => $request->category_id,
        'style'       => $request->style,
        'name'        => $request->name,
        'description' => $request->description,
        'price'       => $request->price,
        'stock'       => $request->stock,
    ]);

    // 2. Simpan gambar ke database (BAGIAN INI YANG KRUSIAL)
    if ($request->hasFile('image')) {
        // Simpan file fisik
        $path = $request->file('image')->store('products', 'public');
        
        // Simpan record ke tabel product_images
        // Pastikan nama model dan kolomnya tepat
        ProductImage::create([
            'product_id' => $product->id,
            'img_url'    => '/storage/' . $path
        ]);
    }

    return redirect()->route('seller.products.index')->with('success', 'Produk berhasil ditambahkan!');
}

    // EDIT: Form Ubah
    public function editProduct($id) 
    {
        $product = Product::with('images')->findOrFail($id);
        $seller = Seller::where('user_id', Auth::id())->first();
        
        // Keamanan: Cek apakah ini produk milik dia sendiri
        if($product->seller_id !== $seller->id) {
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

        if($product->seller_id !== $seller->id) {
            return redirect()->route('seller.products.index')->with('error', 'Akses ditolak!');
        }

        $product->update([
            'category_id' => $request->category_id,
            'style'       => $request->style,
            'name'        => $request->name,
            'description' => $request->description,
            'price'       => $request->price,
            'stock'       => $request->stock,
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
                'img_url'    => '/storage/' . $path
            ]);
        }

        return redirect()->route('seller.products.index')->with('success', 'Detail produk berhasil diperbarui!');
    }

    // DELETE: Hapus Produk
    public function deleteProduct($id) 
    {
        $product = Product::findOrFail($id);
        $seller = Seller::where('user_id', Auth::id())->first();

        if($product->seller_id !== $seller->id) {
            return redirect()->route('seller.products.index')->with('error', 'Akses ditolak!');
        }

        // Hapus gambar fisik dari storage sebelum menghapus data
        $images = ProductImage::where('product_id', $product->id)->get();
        foreach($images as $img) {
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

    public function orderIndex() {
    $seller = Seller::where('user_id', Auth::id())->first();


    $orders = Order::whereHas('orderItems.product', function($query) use ($seller) {
    $query->where('seller_id', $seller->id);
        })->with(['customer.user', 'orderItems.product.images'])->latest()->get();

    return view('seller.orders.index', compact('orders'));
    }

    public function updateOrderStatus(Request $request, $id)
{
    $order = Order::findOrFail($id);
    $order->update([
        'status' => $request->status
    ]);

    return back()->with('success', 'Status pesanan #' . $id . ' berhasil diperbarui!');
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

    public function complaintIndex(Request $request) {
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

    public function complaintDetail($id) {
        $seller = Seller::where('user_id', Auth::id())->first();
        $return = \App\Models\ProductReturn::with(['order.customer.user', 'order.orderItems.product.images'])
            ->findOrFail($id);

        return view('seller.complaint.detail', compact('return'));
    }

    public function approveReturn($id) {
        $return = \App\Models\ProductReturn::findOrFail($id);
        $return->update(['status' => 'approved']);
        
        return redirect()->route('seller.complaint.index')->with('success', 'Return request has been approved.');
    }

    public function rejectReturn($id) {
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
        if ($averageRating >= 4.5) $sentimentScore = 'Excellent';
        elseif ($averageRating >= 4.0) $sentimentScore = 'Very Good';
        elseif ($averageRating >= 3.0) $sentimentScore = 'Good';
        elseif ($averageRating >= 2.0) $sentimentScore = 'Fair';
        else $sentimentScore = 'Poor';

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
            'bank_name' => 'nullable|string|max:100',
            'account_number' => 'nullable|string|max:50',
            'store_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'store_banner' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120',
        ]);

        $data = $request->only(['store_name', 'store_description', 'bank_name', 'account_number']);

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

        return back()->with('success', 'Pengaturan toko berhasil diperbarui!');
    }
}