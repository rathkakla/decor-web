<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Voucher;
use App\Models\VoucherClaim;
use Illuminate\Support\Facades\Auth;

class VoucherController extends Controller
{
    public function sellerVouchers($sellerId)
    {
        $vouchers = Voucher::where('seller_id', $sellerId)
            ->where('start_date', '<=', now())
            ->where('end_date', '>=', now())
            ->where('quota', '>', 0)
            ->get();

        // Check which ones are already claimed by the user
        $userId = Auth::id();
        $vouchers->map(function ($v) use ($userId) {
            $v->is_claimed = VoucherClaim::where('user_id', $userId)
                ->where('voucher_id', $v->id)
                ->exists();
            return $v;
        });

        return response()->json([
            'success' => true,
            'data' => $vouchers
        ]);
    }

    public function claim(Request $request, $id)
    {
        $voucher = Voucher::findOrFail($id);
        $user = Auth::user();

        // 1. Check if already claimed
        $alreadyClaimed = VoucherClaim::where('user_id', $user->id)
            ->where('voucher_id', $voucher->id)
            ->exists();

        if ($alreadyClaimed) {
            return response()->json([
                'success' => false,
                'message' => 'Voucher sudah Anda claim sebelumnya.'
            ], 400);
        }

        // 2. Check quota
        $usedCount = VoucherClaim::where('voucher_id', $voucher->id)->count();
        if ($usedCount >= $voucher->quota) {
            return response()->json([
                'success' => false,
                'message' => 'Maaf, kuota voucher ini sudah habis.'
            ], 400);
        }

        // 3. Save claim
        VoucherClaim::create([
            'user_id' => $user->id,
            'voucher_id' => $voucher->id,
            'is_used' => false
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Voucher berhasil diclaim!'
        ]);
    }

    public function apply(Request $request)
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
            return response()->json([
                'success' => false,
                'message' => 'Voucher tidak valid atau sudah kadaluarsa.'
            ], 404);
        }

        $user = Auth::user();
        $claim = VoucherClaim::where('user_id', $user->id)
            ->where('voucher_id', $voucher->id)
            ->where('is_used', false)
            ->first();

        if (!$claim) {
            return response()->json([
                'success' => false,
                'message' => 'Anda belum mengclaim voucher ini atau voucher sudah digunakan.'
            ], 400);
        }

        return response()->json([
            'success' => true,
            'data' => [
                'id' => $voucher->id,
                'code' => $voucher->code,
                'discount_type' => $voucher->discount_type,
                'discount_value' => $voucher->discount_value,
                'min_purchase' => $voucher->min_purchase,
                'max_discount' => $voucher->max_discount,
            ],
            'message' => 'Voucher berhasil diterapkan!'
        ]);
    }
}