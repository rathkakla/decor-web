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
            $user->load(['customer', 'seller', 'designer']);
            
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

    public function register(Request $request)
    {
        $request->validate([
            'username'  => 'required|string|max:100|unique:users',
            'email'     => 'required|email|unique:users',
            'password'  => 'required|min:6',
            'full_name' => 'required|string|max:150',
        ]);

        $user = \App\Models\User::create([
            'username'  => $request->username,
            'email'     => $request->email,
            'password'  => \Illuminate\Support\Facades\Hash::make($request->password),
            'full_name' => $request->full_name,
            'role'      => 'customer',
        ]);

        // Otomatis buat profile customer
        \App\Models\Customer::create([
            'user_id' => $user->id,
        ]);

        // Langsung login-kan
        $token = $user->createToken('decor-mobile-app')->plainTextToken;
        $user->load(['customer', 'seller', 'designer']);

        $data = [
            'user'  => $user,
            'token' => $token
        ];

        return $this->successResponse($data, 'Registrasi berhasil', 201);
    }

    public function logout(Request $request)
    {
        // Menghapus token yang sedang digunakan
        $request->user()->currentAccessToken()->delete();
        return $this->successResponse(null, 'Logout berhasil');
    }
}