<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    /**
     * Menampilkan halaman Payment Successful
     */
   public function success($id)
{
    // Gunakan 'with' agar data customer dan user-nya ikut terambil
    $order = Order::with('customer.user')->findOrFail($id); 
    
    $suggestions = Product::where('stock', '>', 0)
                          ->inRandomOrder()
                          ->take(3)
                          ->get();

    return view('customer.success', compact('order', 'suggestions'));
}
    }

    // Nanti kalau kamu mau buat fitur Track Order (Riwayat Pesanan), 
    // Return Request, atau Invoice, tinggal tambahkan function baru di bawah sini.
    // Jadi semuanya rapi ngumpul di satu tempat!
