<?php

namespace Database\Seeders;

use App\Models\B2bBast;
use App\Models\B2bDelivery;
use App\Models\B2bMilestone;
use App\Models\B2bNegotiation;
use App\Models\B2bOrder;
use App\Models\B2bQuotation;
use App\Models\B2bRfq;
use App\Models\Designer;
use App\Models\Seller;
use Illuminate\Database\Seeder;

/**
 * Data demo alur B2B end-to-end supaya 12 halaman punya isi.
 *
 * Pemakaian: php artisan db:seed --class=B2bDemoSeeder
 *
 * Seeder ini menghapus seluruh data B2B lalu membuat ulang, jadi aman
 * dijalankan berkali-kali selama masih tahap pengembangan.
 */
class B2bDemoSeeder extends Seeder
{
    public function run(): void
    {
        $seller = Seller::whereHas('user', fn ($q) => $q->where('email', 'teststore@gmail.com'))->first()
            ?? Seller::firstOrFail();

        $designer = Designer::whereHas('user', fn ($q) => $q->where('email', 'arsitek@gmail.com'))->first()
            ?? Designer::firstOrFail();

        $seller->update([
            'wood_specialties' => ['Jati Solid', 'Mahoni', 'Sungkai'],
            'monthly_capacity' => 25,
            'city' => 'Jepara',
            'svlk_verified' => true,
            'b2b_enabled' => true,
        ]);

        $this->clearDemoData();

        $buyer = $designer->user;
        $factory = $seller->user;

        // ---------------------------------------------------------------
        // 1. RFQ baru masuk (PENDING) — untuk inbox & review spesifikasi
        // ---------------------------------------------------------------
        $pending = B2bRfq::create([
            'designer_id' => $designer->id,
            'seller_id' => $seller->id,
            'project_name' => 'Kafe Urban Loft — Set Meja & Kursi',
            'wood_type' => 'Jati Solid',
            'budget_ceiling' => 185000000,
            'deadline' => now()->addDays(45)->toDateString(),
            'delivery_address' => 'Jl. Cihampelas No. 112, Bandung, Jawa Barat',
            'description' => 'Set meja komunal 6 unit (240x90x75 cm) dan kursi 32 unit. Finishing natural matt, sambungan mortise-tenon.',
            'items' => [
                ['name' => 'Meja Komunal 240x90x75', 'qty' => 6, 'unit' => 'unit', 'note' => 'Top 4 cm jati solid'],
                ['name' => 'Kursi Makan Tanpa Sandaran', 'qty' => 32, 'unit' => 'unit', 'note' => 'Rangka jati, dudukan rotan'],
            ],
            'sample_status' => B2bRfq::SAMPLE_NOT_REQUESTED,
            'status' => B2bRfq::STATUS_PENDING,
        ]);

        // ---------------------------------------------------------------
        // 2. RFQ sudah dikirim quotation (QUOTED) — untuk quotation review
        // ---------------------------------------------------------------
        $quoted = B2bRfq::create([
            'designer_id' => $designer->id,
            'seller_id' => $seller->id,
            'project_name' => 'Apartemen Senopati — Kitchen Set & Wardrobe',
            'wood_type' => 'Plywood HPL',
            'budget_ceiling' => 240000000,
            'deadline' => now()->addDays(60)->toDateString(),
            'delivery_address' => 'Jl. Senopati Raya No. 88, Jakarta Selatan',
            'description' => 'Kitchen set L-shape 4,2 m dan wardrobe 3 pintu tinggi 3 m. HPL motif oak natural, engsel soft close.',
            'items' => [
                ['name' => 'Kitchen Set L-Shape 4,2 m', 'qty' => 1, 'unit' => 'set', 'note' => 'Termasuk top table granit'],
                ['name' => 'Wardrobe 3 Pintu 3 m', 'qty' => 1, 'unit' => 'unit', 'note' => 'HPL oak natural'],
            ],
            'sample_status' => B2bRfq::SAMPLE_PREPARING,
            'status' => B2bRfq::STATUS_QUOTED,
        ]);

        $quotation = B2bQuotation::create([
            'rfq_id' => $quoted->id,
            'material_cost' => 152000000,
            'labor_cost' => 38000000,
            'shipping_cost' => 6500000,
            'tax_percent' => 11,
            'dp_percentage' => 50,
            'estimated_days' => 50,
            'valid_until' => now()->addDays(14)->toDateString(),
            'warranty_notes' => 'Garansi konstruksi 12 bulan, garansi finishing 6 bulan.',
            'notes' => 'Harga sudah termasuk instalasi di lokasi proyek.',
            'items' => [
                ['name' => 'Kitchen Set L-Shape 4,2 m', 'qty' => 1, 'unit_price' => 118000000],
                ['name' => 'Wardrobe 3 Pintu 3 m', 'qty' => 1, 'unit_price' => 72000000],
            ],
            'status' => B2bQuotation::STATUS_SENT,
        ])->recalculate();
        $quotation->save();

        // ---------------------------------------------------------------
        // 3. RFQ sudah jadi order, produksi berjalan (IN_ORDER)
        // ---------------------------------------------------------------
        $active = B2bRfq::create([
            'designer_id' => $designer->id,
            'seller_id' => $seller->id,
            'project_name' => 'Hotel Boutique Ubud — Lobby Lounge',
            'wood_type' => 'Sungkai',
            'budget_ceiling' => 310000000,
            'deadline' => now()->addDays(30)->toDateString(),
            'delivery_address' => 'Jl. Raya Ubud No. 21, Gianyar, Bali',
            'description' => 'Reception desk 3,6 m, sofa frame 4 unit, dan coffee table 6 unit. Finishing warna walnut.',
            'items' => [
                ['name' => 'Reception Desk 3,6 m', 'qty' => 1, 'unit' => 'unit', 'note' => 'Panel ukir tepi'],
                ['name' => 'Sofa Frame', 'qty' => 4, 'unit' => 'unit', 'note' => 'Rangka sungkai + busa'],
                ['name' => 'Coffee Table', 'qty' => 6, 'unit' => 'unit', 'note' => 'Top marmer 2 cm'],
            ],
            'sample_status' => B2bRfq::SAMPLE_APPROVED,
            'tracking_number' => 'JNE-8823104551',
            'courier_name' => 'JNE Trucking',
            'status' => B2bRfq::STATUS_IN_ORDER,
        ]);

        $activeQuotation = B2bQuotation::create([
            'rfq_id' => $active->id,
            'material_cost' => 198000000,
            'labor_cost' => 62000000,
            'shipping_cost' => 12000000,
            'tax_percent' => 11,
            'dp_percentage' => 50,
            'estimated_days' => 40,
            'valid_until' => now()->addDays(20)->toDateString(),
            'warranty_notes' => 'Garansi konstruksi 12 bulan.',
            'items' => [
                ['name' => 'Reception Desk 3,6 m', 'qty' => 1, 'unit_price' => 95000000],
                ['name' => 'Sofa Frame', 'qty' => 4, 'unit_price' => 28500000],
                ['name' => 'Coffee Table', 'qty' => 6, 'unit_price' => 12500000],
            ],
            'status' => B2bQuotation::STATUS_ACCEPTED,
        ])->recalculate();
        $activeQuotation->save();

        $activeOrder = B2bOrder::create([
            'rfq_id' => $active->id,
            'quotation_id' => $activeQuotation->id,
            'total_amount' => $activeQuotation->total_price,
            'escrow_balance' => $activeQuotation->dp_amount,
            'payment_status' => B2bOrder::PAYMENT_DP_HELD,
            'dp_paid_at' => now()->subDays(18),
            'status' => B2bOrder::STATUS_IN_PRODUCTION,
        ]);
        $activeOrder->createMilestones();

        B2bMilestone::where('order_id', $activeOrder->id)
            ->where('stage_order', '<=', 2)
            ->update(['progress_percent' => 100, 'is_approved_by_designer' => true]);

        B2bMilestone::where('order_id', $activeOrder->id)
            ->where('stage_order', 3)
            ->update(['progress_percent' => 60, 'target_date' => now()->addDays(7)]);

        B2bMilestone::where('order_id', $activeOrder->id)
            ->where('stage_order', 4)
            ->update(['target_date' => now()->addDays(16)]);

        B2bMilestone::where('order_id', $activeOrder->id)
            ->where('stage_order', 5)
            ->update(['target_date' => now()->addDays(24)]);

        B2bNegotiation::create([
            'rfq_id' => $active->id,
            'sender_id' => $buyer->id,
            'message' => 'Untuk finishing, bisa pakai warna walnut natural ya? Sampel yang dikirim sudah sesuai.',
        ]);

        B2bNegotiation::create([
            'rfq_id' => $active->id,
            'sender_id' => $factory->id,
            'message' => 'Baik, kami pakai walnut natural. Sampel sudah kami kirim via JNE Trucking dengan resi JNE-8823104551.',
        ]);

        B2bNegotiation::create([
            'rfq_id' => $active->id,
            'sender_id' => $buyer->id,
            'message' => 'Sampel sudah diterima dan saya setujui. Silakan lanjut produksi pos 3.',
        ]);

        // ---------------------------------------------------------------
        // 4. Order selesai penuh: BAST, escrow cair, payout sudah dibayar
        // ---------------------------------------------------------------
        $done = B2bRfq::create([
            'designer_id' => $designer->id,
            'seller_id' => $seller->id,
            'project_name' => 'Rumah Pak Bramantyo — Ruang Makan',
            'wood_type' => 'Mahoni',
            'budget_ceiling' => 95000000,
            'deadline' => now()->subDays(5)->toDateString(),
            'delivery_address' => 'Jl. Dago Atas No. 7, Bandung',
            'description' => 'Meja makan 8 kursi dan sideboard 2 m warna mahoni natural.',
            'items' => [
                ['name' => 'Meja Makan 8 Kursi', 'qty' => 1, 'unit' => 'set', 'note' => 'Top 3 cm'],
                ['name' => 'Sideboard 2 m', 'qty' => 1, 'unit' => 'unit', 'note' => '3 pintu + laci'],
            ],
            'sample_status' => B2bRfq::SAMPLE_APPROVED,
            'tracking_number' => 'SICEPAT-7712008830',
            'courier_name' => 'SiCepat Cargo',
            'status' => B2bRfq::STATUS_IN_ORDER,
        ]);

        $doneQuotation = B2bQuotation::create([
            'rfq_id' => $done->id,
            'material_cost' => 52000000,
            'labor_cost' => 18500000,
            'shipping_cost' => 3200000,
            'tax_percent' => 11,
            'dp_percentage' => 30,
            'estimated_days' => 25,
            'valid_until' => now()->subDays(25)->toDateString(),
            'warranty_notes' => 'Garansi konstruksi 12 bulan.',
            'items' => [
                ['name' => 'Meja Makan 8 Kursi', 'qty' => 1, 'unit_price' => 48500000],
                ['name' => 'Sideboard 2 m', 'qty' => 1, 'unit_price' => 25200000],
            ],
            'status' => B2bQuotation::STATUS_ACCEPTED,
        ])->recalculate();
        $doneQuotation->save();

        $doneOrder = B2bOrder::create([
            'rfq_id' => $done->id,
            'quotation_id' => $doneQuotation->id,
            'total_amount' => $doneQuotation->total_price,
            'escrow_balance' => 0,
            'payment_status' => B2bOrder::PAYMENT_RELEASED,
            'dp_paid_at' => now()->subDays(40),
            'settled_at' => now()->subDays(18),
            'status' => B2bOrder::STATUS_COMPLETED,
            'payout_status' => B2bOrder::PAYOUT_PAID,
            'payout_requested_at' => now()->subDays(6),
            'payout_paid_at' => now()->subDays(5),
        ]);
        $doneOrder->createMilestones();

        B2bMilestone::where('order_id', $doneOrder->id)
            ->update(['progress_percent' => 100, 'is_approved_by_designer' => true]);

        B2bDelivery::create([
            'order_id' => $doneOrder->id,
            'courier_service' => 'SiCepat Cargo',
            'driver_name' => 'Budi Santoso',
            'vehicle_plate' => 'D 8831 KA',
            'delivery_date' => now()->subDays(12)->toDateString(),
            'destination_address' => 'Jl. Dago Atas No. 7, Bandung',
            'recipient_name' => 'Bramantyo',
            'recipient_phone' => '081234567890',
            'status' => B2bDelivery::STATUS_DELIVERED,
        ]);

        B2bBast::create([
            'order_id' => $doneOrder->id,
            'photo_installed_url' => null,
            'inspection_checklist' => [
                ['label' => 'Konstruksi sambungan kokoh', 'checked' => true],
                ['label' => 'Finishing rata tanpa cacat', 'checked' => true],
                ['label' => 'Jumlah unit sesuai pesanan', 'checked' => true],
                ['label' => 'Perakitan di lokasi selesai', 'checked' => true],
            ],
            'is_accepted' => true,
            'signed_at' => now()->subDays(10),
        ]);

        $this->command->info('B2B demo data siap: 4 RFQ, 3 quotation, 2 order, 10 milestone.');
    }

    private function clearDemoData(): void
    {
        B2bBast::query()->delete();
        B2bDelivery::query()->delete();
        B2bMilestone::query()->delete();
        B2bNegotiation::query()->delete();
        B2bOrder::query()->delete();
        B2bQuotation::query()->delete();
        B2bRfq::query()->delete();
    }
}
