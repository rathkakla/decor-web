<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use App\Models\Seller;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\ProductImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage; // Tambahan untuk menghapus gambar fisik

class SellerController extends Controller
{
    // ==========================================
    // 1. DASHBOARD & MENU LAINNYA
    // ==========================================
    
    public function dashboard() {
        $seller = Seller::where('user_id', Auth::id())->first();
        $totalProducts = Product::where('seller_id', $seller->id)->count();
        return view('seller.dashboard', compact('seller', 'totalProducts'));
    }

    // Fungsi Support/Komplain agar tidak error 'Route not defined'
    public function support() {
        return view('seller.Support.support'); 
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
        return view('seller.orders.invoice', compact('order'));
    }

    // CETAK: Label Pengiriman (label.blade.php)
    public function printLabel($id)
    {
        $order = Order::with(['customer.user'])->findOrFail($id);
        return view('seller.orders.label', compact('order'));
    }
}