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
        $products = Product::with(['images', 'seller'])->get();
        return $this->successResponse($products, 'Data produk berhasil diambil');
    }

    // READ DETAIL (Mengambil 1 barang saja secara spesifik)
    public function show($id)
    {
        $product = Product::with(['images', 'seller', 'category'])->find($id);
        
        if (!$product) {
            return $this->errorResponse('Produk tidak ditemukan', 404);
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
}