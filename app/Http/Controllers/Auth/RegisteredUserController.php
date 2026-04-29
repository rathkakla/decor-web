<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Customer; // Tambahkan ini jika sudah buat modelnya
use App\Models\Designer; // Tambahkan ini jika sudah buat modelnya
use App\Models\Seller;   // Tambahkan ini jika sudah buat modelnya
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Menampilkan halaman registrasi sesuai role di session.
     */
    public function create(): View
    {
        $role = session('selected_role', 'customer');

        // Arahkan ke view yang benar berdasarkan role
        if ($role === 'designer') {
            return view('register-designer');
        }
        
        return view('register-customer');
    }

    /**
     * Menangani proses simpan data registrasi.
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'username' => ['required', 'string', 'max:100', 'unique:users'],
            'name'     => ['required', 'string', 'max:150'],
            'email'    => ['required', 'string', 'lowercase', 'email', 'max:100', 'unique:users'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        // 1. Simpan ke tabel USERS
        $user = User::create([
            'username'  => $request->username,
            'full_name' => $request->name, 
            'email'     => $request->email,
            'password'  => Hash::make($request->password),
            'role'      => session('selected_role', 'customer'), // Menyimpan 'customer', 'seller', atau 'designer'
        ]);

        // 2. OTOMATIS buatkan profil di tabel terkait
        if ($user->role === 'customer') {
            Customer::create(['user_id' => $user->id]);
        } elseif ($user->role === 'designer') {
            Designer::create(['user_id' => $user->id, 'specialty' => 'Generalist']);
        } elseif ($user->role === 'seller') {
            Seller::create([
                'user_id'        => $user->id,
                'store_name'     => $request->store_name,
                'phone_number'   => $request->phone,
                'bank_name'      => $request->bank_name,
                // Kita hapus spasi yang dibuat oleh script auto-format JS temanmu
                'account_number' => str_replace(' ', '', $request->account_number), 
                'store_address'  => $request->store_address,
            ]);
        }

        event(new Registered($user));

        // Redirect ke login dengan pesan sukses
        return redirect()->route('login')->with('status', 'Registration successful! Please login to continue.');
    }
}