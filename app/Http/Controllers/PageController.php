<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PageController extends Controller
{
    public function showRegisterForm($role) {
    // Simpan role ke session agar terbaca saat proses register
    session(['selected_role' => $role]);
    return view('register-customer'); // Ini file blade regis yang kita buat tadi
}
}
