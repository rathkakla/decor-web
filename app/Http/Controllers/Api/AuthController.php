<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required'
        ]);

        if (Auth::attempt($request->only('email', 'password'))) {
            $user = Auth::user();
            // Membuat Bearer Token untuk Flutter
            $token = $user->createToken('decor-mobile-app')->plainTextToken;

            $data = [
                'user'  => $user,
                'token' => $token
            ];

            return $this->successResponse($data, 'Login berhasil');
        }

        return $this->errorResponse('Email atau password salah', 401);
    }

    public function logout(Request $request)
    {
        // Menghapus token yang sedang digunakan
        $request->user()->currentAccessToken()->delete();
        return $this->successResponse(null, 'Logout berhasil');
    }
}