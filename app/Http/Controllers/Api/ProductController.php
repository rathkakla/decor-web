<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    // READ ALL (Sudah kita buat sebelumnya)
    public function index()
    {
        $products = Product::with(['images', 'seller'])->where('status', 'approved')->get();
        return $this->successResponse($products, 'Data produk berhasil diambil');
    }

    // READ DETAIL (Mengambil 1 barang saja secara spesifik)
    public function show($id)
    {
        $product = Product::with(['images', 'seller', 'category', 'reviews.customer.user'])
            ->where('status', 'approved')
            ->find($id);
        
        if (!$product) {
            return $this->errorResponse('Produk tidak ditemukan atau belum disetujui.', 404);
        }

        return $this->successResponse($product, 'Detail produk berhasil diambil');
    }

    // CREATE (Menambah barang baru)
    public function store(Request $request)
    {
        // Validasi input dari Flutter
        $request->validate([
            'name'        => 'required|string',
            'price'       => 'required|numeric',
            'stock'       => 'required|integer',
            'description' => 'nullable|string',
            'category_id' => 'required|exists:categories,id',
            // seller_id diambil otomatis dari user yang sedang login
        ]);

        // Proses simpan ke database
        $product = Product::create([
            'name'        => $request->name,
            'price'       => $request->price,
            'stock'       => $request->stock,
            'description' => $request->description,
            'category_id' => $request->category_id,
            // Kita asumsikan user yang login punya relasi ke seller
            'seller_id'   => $request->user()->seller->id ?? 1, 
        ]);

        return $this->successResponse($product, 'Produk berhasil ditambahkan', 201);
    }

    // UPDATE (Mengedit barang)
    public function update(Request $request, $id)
    {
        $product = Product::find($id);

        if (!$product) {
            return $this->errorResponse('Produk tidak ditemukan', 404);
        }

        $product->update($request->all());

        return $this->successResponse($product, 'Produk berhasil diperbarui');
    }

    // DELETE (Menghapus barang)
    public function destroy($id)
    {
        $product = Product::find($id);

        if (!$product) {
            return $this->errorResponse('Produk tidak ditemukan', 404);
        }

        $product->delete();

        return $this->successResponse(null, 'Produk berhasil dihapus');
    }
    // SUBMIT REVIEW
    public function storeReview(Request $request, $id)
    {
        $request->validate([
            'rating'   => 'required|integer|min:1|max:5',
            'comment'  => 'nullable|string',
            'order_id' => 'nullable|exists:orders,id'
        ]);

        $user = $request->user();
        $customer = \App\Models\Customer::where('user_id', $user->id)->first();

        if (!$customer) {
            return $this->errorResponse('Profil customer tidak ditemukan.', 404);
        }

        $review = \App\Models\Review::create([
            'product_id'  => $id,
            'customer_id' => $customer->id,
            'rating'      => $request->rating,
            'comment'     => $request->comment,
        ]);

        // Jika ada order_id, tandai order tersebut sudah diulas
        if ($request->has('order_id')) {
            \App\Models\Order::where('id', $request->order_id)
                ->where('customer_id', $customer->id)
                ->update(['has_reviewed' => true]);
        }

        return $this->successResponse($review, 'Ulasan berhasil dikirim.');
    }

    public function sellerStore($id)
    {
        $seller = \App\Models\Seller::with('user')->find($id);

        if (!$seller) {
            return $this->errorResponse('Seller tidak ditemukan', 404);
        }

        $products = \App\Models\Product::with('images')
            ->where('seller_id', $id)
            ->where('status', 'approved')
            ->get();

        $allReviews = \App\Models\Review::whereHas('product', function ($q) use ($id) {
            $q->where('seller_id', $id);
        })->get();

        $averageRating = $allReviews->avg('rating') ?: 0;
        $totalReviews = $allReviews->count();

        return $this->successResponse([
            'seller'         => $seller,
            'products'       => $products,
            'total_products' => $products->count(),
            'average_rating' => round($averageRating, 1),
            'total_reviews'  => $totalReviews
        ], 'Data seller berhasil diambil');
    }
}