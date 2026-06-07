<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Address;
use App\Models\Customer;

class AddressController extends Controller
{
    // 1. LIHAT SEMUA ALAMAT
    public function index(Request $request)
    {
        $customer = Customer::firstOrCreate(
            ['user_id' => $request->user()->id]
        );

        $addresses = Address::where('customer_id', $customer->id)->get();

        return response()->json([
            'status' => 'success',
            'data' => $addresses
        ]);
    }

    // 2. TAMBAH ALAMAT BARU
    public function store(Request $request)
    {
        $request->validate([
            'label' => 'required|string|max:50',
            'recipient_name' => 'nullable|string|max:100',
            'phone_number' => 'nullable|string|max:20',
            'city' => 'nullable|string|max:100',
            'province' => 'nullable|string|max:100',
            'postal_code' => 'nullable|string|max:20',
            'full_address' => 'required|string',
            'is_main' => 'boolean'
        ]);

        $customer = Customer::where('user_id', $request->user()->id)->first();
        
        // Jika ini diset sebagai alamat utama, reset yang lain
        if ($request->is_main) {
            Address::where('customer_id', $customer->id)->update(['is_main' => false]);
        }

        $address = Address::create([
            'customer_id' => $customer->id,
            'label' => $request->label,
            'recipient_name' => $request->recipient_name,
            'phone_number' => $request->phone_number,
            'city' => $request->city,
            'province' => $request->province,
            'postal_code' => $request->postal_code,
            'full_address' => $request->full_address,
            'is_main' => $request->is_main ?? false
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Alamat berhasil ditambahkan.',
            'data' => $address
        ]);
    }

    // 3. EDIT ALAMAT
    public function update(Request $request, $id)
    {
        $request->validate([
            'label' => 'required|string|max:50',
            'recipient_name' => 'nullable|string|max:100',
            'phone_number' => 'nullable|string|max:20',
            'city' => 'nullable|string|max:100',
            'province' => 'nullable|string|max:100',
            'postal_code' => 'nullable|string|max:20',
            'full_address' => 'required|string',
            'is_main' => 'boolean'
        ]);

        $customer = Customer::where('user_id', $request->user()->id)->first();
        $address = Address::where('id', $id)->where('customer_id', $customer->id)->firstOrFail();

        if ($request->is_main) {
            Address::where('customer_id', $customer->id)->update(['is_main' => false]);
        }

        $address->update([
            'label' => $request->label,
            'recipient_name' => $request->recipient_name,
            'phone_number' => $request->phone_number,
            'city' => $request->city,
            'province' => $request->province,
            'postal_code' => $request->postal_code,
            'full_address' => $request->full_address,
            'is_main' => $request->is_main ?? false
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Alamat berhasil diperbarui.',
            'data' => $address
        ]);
    }

    // 4. HAPUS ALAMAT
    public function destroy(Request $request, $id)
    {
        $customer = Customer::where('user_id', $request->user()->id)->first();
        $address = Address::where('id', $id)->where('customer_id', $customer->id)->firstOrFail();

        $address->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Alamat berhasil dihapus.'
        ]);
    }
}