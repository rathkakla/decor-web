<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Customer;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    // 1. LIHAT PROFILE (GET)
    public function show(Request $request)
    {
        $user = $request->user();
        $customer = Customer::with('mainAddress')->firstOrCreate(
            ['user_id' => $user->id]
        );

        $data = [
            'id' => $user->id,
            'username' => $user->username,
            'full_name' => $user->full_name,
            'email' => $user->email,
            'phone' => $customer->phone ?? '',
            'profile_image' => $customer->profile_image ?? '',
            'address' => $customer->mainAddress->full_address ?? '',
        ];

        return response()->json([
            'status' => 'success',
            'data' => $data
        ]);
    }

    // 2. UPDATE PROFILE (POST)
    public function update(Request $request)
    {
        $user = $request->user();
        $customer = Customer::where('user_id', $user->id)->first();

        $request->validate([
            'full_name' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string',
            'profile_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Update User
        if ($request->full_name) {
            $user->full_name = $request->full_name;
            $user->save();
        }

        // Update Customer
        if (!$customer) {
            $customer = new Customer();
            $customer->user_id = $user->id;
        }

        $customer->phone = $request->phone;

        // Handle Image Upload
        if ($request->hasFile('profile_image')) {
            // Delete old image if exists
            if ($customer->profile_image) {
                Storage::disk('public')->delete($customer->profile_image);
            }
            $path = $request->file('profile_image')->store('profiles', 'public');
            $customer->profile_image = $path;
        }

        $customer->save();

        // Update Address Table
        if ($request->address) {
            \App\Models\Address::updateOrCreate(
                ['customer_id' => $customer->id, 'is_main' => true],
                [
                    'label' => 'Main',
                    'full_address' => $request->address
                ]
            );
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Profil berhasil diperbarui.',
            'data' => [
                'full_name' => $user->full_name,
                'phone' => $customer->phone,
                'address' => $request->address,
                'profile_image' => $customer->profile_image ? asset('storage/' . $customer->profile_image) : null,
            ]
        ]);
    }
}