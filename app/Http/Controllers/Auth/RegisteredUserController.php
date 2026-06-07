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
            // Pastikan menggunakan view di folder designer/register
            return view('designer.register.index');
        }
        
        return view('register-customer');
    }

    /**
     * Menangani proses simpan data registrasi.
     */
    public function store(Request $request): RedirectResponse
    {
        $role = session('selected_role', 'customer');

        $rules = [
            'username' => ['required', 'string', 'max:100', 'unique:users'],
            'name'     => ['required', 'string', 'max:150'],
            'email'    => ['required', 'string', 'lowercase', 'email', 'max:100', 'unique:users'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ];

        // Add role-specific validation
        if ($role === 'seller') {
            $rules['phone'] = ['nullable', 'string', 'max:20'];
        } elseif ($role === 'designer') {
            $rules['specialty'] = ['required', 'string', 'max:100'];
            $rules['bio'] = ['nullable', 'string'];
            $rules['experience_years'] = ['nullable', 'integer', 'min:0'];
        }

        if ($role === 'seller') {
            $rules['bank_name'] = ['nullable', 'string', 'max:50'];
            $rules['account_number'] = ['nullable', 'string', 'max:50'];
        }

        $messages = [
            'email.email' => 'Format email tidak valid. Pastikan menggunakan format yang benar (contoh: nama@domain.com).'
        ];

        $request->validate($rules, $messages);

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
            Designer::create([
                'user_id'          => $user->id,
                'studio_name'      => $request->username,
                'specialty'        => $request->specialty,
                'bio'              => $request->bio,
                'experience_years' => $request->experience_years ?? 0,
                'instagram_url'    => $request->portfolio_link,
            ]);
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

        Auth::login($user);

        // Redirect ke dashboard sesuai role
        if ($user->role === 'seller') {
            return redirect()->route('seller.dashboard')->with('status', 'Registration successful! Your account is pending approval.');
        } elseif ($user->role === 'designer') {
            return redirect()->route('designer.dashboard')->with('status', 'Registration successful! Your account is pending approval.');
        }

        return redirect()->route('customer.homepage')->with('status', 'Registration successful!');
    }
}