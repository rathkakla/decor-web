<?php

namespace App\Http\Controllers\B2b\Seller;

use App\Http\Controllers\Controller;
use App\Models\Seller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

/**
 * Pendaftaran mandiri Layanan Pesanan Proyek (B2B) oleh seller.
 *
 * Alur: seller mengisi formulir workshop -> status "pending" ->
 * Admin memverifikasi di Seller Monitor / Detail Seller.
 */
class RegistrationController extends Controller
{
    /**
     * Tampilkan halaman pendaftaran / status layanan pesanan proyek.
     */
    public function show(): View
    {
        $seller = $this->getSeller();

        // Kategori material modern yang fleksibel (tidak hanya kayu)
        $materialOptions = [
            'Kayu & Olahan (Jati, Mahoni, Sungkai, Plywood HPL, Duco)',
            'Besi, Logam & Stainless Steel (Rangka, Partisi, Aksesori)',
            'Upholstery (Kain, Kulit & Busa Sofa/Kursi)',
            'Kaca, Cermin & Akrilik (Partisi, Pintu Lemari, Display)',
            'Batu Alam & Marmer / Granit (Top Table, Kitchen Counter)',
            'Rotan & Anyaman (Sintetis / Alami)',
            'Multi-Material (Kombinasi Seluruh Material)',
        ];

        return view('seller.b2b.pendaftaran_layanan', compact('seller', 'materialOptions'));
    }

    /**
     * Proses pengajuan pendaftaran layanan pesanan proyek ke Admin.
     */
    public function submit(Request $request): RedirectResponse
    {
        $seller = $this->getSeller();

        if ($seller->b2b_status === 'approved' && $seller->b2b_enabled) {
            return redirect()
                ->route('seller.b2b.register')
                ->with('success', 'Toko Anda sudah terverifikasi sebagai Mitra Workshop untuk Layanan Pesanan Proyek.');
        }

        $validated = $request->validate([
            'materials' => ['required', 'array', 'min:1'],
            'materials.*' => ['string', 'max:120'],
            'monthly_capacity' => ['required', 'integer', 'min:1', 'max:10000'],
            'city' => ['required', 'string', 'max:100'],
            'workshop_address' => ['required', 'string', 'max:500'],
            'workshop_photo' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
            'terms_agreed' => ['accepted'],
        ], [
            'materials.required' => 'Pilih minimal 1 kategori material / keahlian workshop Anda.',
            'monthly_capacity.required' => 'Masukkan estimasi kapasitas produksi bulanan.',
            'monthly_capacity.min' => 'Kapasitas produksi minimal 1 unit per bulan.',
            'city.required' => 'Kota workshop wajib diisi.',
            'workshop_address.required' => 'Alamat workshop fisik wajib diisi.',
            'workshop_photo.image' => 'Foto workshop harus berupa gambar.',
            'workshop_photo.max' => 'Ukuran foto workshop maksimal 5 MB.',
            'terms_agreed.accepted' => 'Anda wajib menyetujui Syarat & Ketentuan Layanan Pesanan Proyek.',
        ]);

        $photoPath = $seller->portfolio_url;
        if ($request->hasFile('workshop_photo')) {
            if ($photoPath && Storage::disk('public')->exists($photoPath)) {
                Storage::disk('public')->delete($photoPath);
            }
            $photoPath = $request->file('workshop_photo')->store('b2b/workshops', 'public');
        }

        $seller->update([
            'wood_specialties' => array_values($validated['materials']),
            'monthly_capacity' => $validated['monthly_capacity'],
            'city' => $validated['city'],
            'store_address' => $validated['workshop_address'],
            'portfolio_url' => $photoPath,
            'b2b_status' => 'pending',
            'b2b_rejection_reason' => null,
            'b2b_terms_agreed_at' => now(),
        ]);

        return redirect()
            ->route('seller.b2b.register')
            ->with('success', 'Pengajuan Layanan Pesanan Proyek berhasil dikirim! Admin DECOR akan memverifikasi workshop Anda dalam 1x24 jam.');
    }

    private function getSeller(): Seller
    {
        $seller = auth()->user()->seller;
        abort_unless($seller, 403, 'Akun seller tidak ditemukan.');

        return $seller;
    }
}