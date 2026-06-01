<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Support;
use Illuminate\Support\Facades\Auth;

class SupportController extends Controller
{
    // Fetch support ticket history
    public function index(Request $request)
    {
        $supports = Support::where('user_id', $request->user()->id)
            ->latest()
            ->get();

        return response()->json([
            'status' => 'success',
            'data' => $supports
        ]);
    }

    // Submit new support ticket
    public function store(Request $request)
    {
        $request->validate([
            'subject' => 'required|string|max:255',
            'message' => 'required|string',
        ]);

        $support = Support::create([
            'user_id' => $request->user()->id,
            'subject' => $request->subject,
            'message' => $request->message,
            'status' => 'pending',
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Komplain Anda telah dikirim ke Admin. Mohon tunggu respon kami.',
            'data' => $support
        ]);
    }
}