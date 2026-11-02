<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * B2B Custom Furniture Procurement: 7 main tables.
     *
     * Status columns use string (not enum) to stay consistent with the rest of
     * the schema and to allow new statuses without a migration.
     */
    public function up(): void
    {
        // ---------------------------------------------------------------- 1. RFQ
        Schema::create('b2b_rfqs', function (Blueprint $table) {
            $table->id();
            $table->string('rfq_number', 50)->unique();

            $table->foreignId('designer_id')->constrained('designers')->cascadeOnDelete();
            $table->foreignId('seller_id')->constrained('sellers')->cascadeOnDelete();

            $table->string('project_name', 255);
            $table->string('wood_type', 100);
            $table->decimal('budget_ceiling', 15, 2)->default(0);
            $table->date('deadline');
            $table->text('delivery_address')->nullable();
            $table->text('description')->nullable();

            // Detail item (dimensi, kuantitas, finishing) disimpan sebagai JSON.
            $table->json('items')->nullable();

            $table->string('cad_url', 255)->nullable();
            $table->string('boq_url', 255)->nullable();
            $table->string('moodboard_url', 255)->nullable();

            // Pengiriman sampel kayu fisik.
            $table->string('sample_status', 30)->default('NOT_REQUESTED');
            $table->string('tracking_number', 100)->nullable();
            $table->string('courier_name', 50)->nullable();

            $table->string('status', 30)->default('PENDING');

            $table->timestamps();

            $table->index(['seller_id', 'status']);
            $table->index(['designer_id', 'status']);
        });

        // ---------------------------------------------------------- 2. Quotation
        Schema::create('b2b_quotations', function (Blueprint $table) {
            $table->id();
            $table->string('quotation_number', 50)->unique();

            $table->foreignId('rfq_id')->constrained('b2b_rfqs')->cascadeOnDelete();

            $table->decimal('material_cost', 15, 2)->default(0);
            $table->decimal('labor_cost', 15, 2)->default(0);
            $table->decimal('shipping_cost', 15, 2)->default(0);
            $table->decimal('tax_percent', 5, 2)->default(11);
            $table->decimal('total_price', 15, 2)->default(0);

            $table->unsignedTinyInteger('dp_percentage')->default(30);
            $table->decimal('dp_amount', 15, 2)->default(0);

            $table->unsignedInteger('estimated_days')->default(0);
            $table->date('valid_until')->nullable();
            $table->string('warranty_notes', 255)->nullable();
            $table->text('notes')->nullable();

            $table->json('items')->nullable();

            $table->string('status', 30)->default('DRAFT');

            $table->timestamps();

            $table->index(['rfq_id', 'status']);
        });

        // ------------------------------------------------------------- 3. Order
        Schema::create('b2b_orders', function (Blueprint $table) {
            $table->id();
            $table->string('order_number', 50)->unique();

            $table->foreignId('rfq_id')->constrained('b2b_rfqs');
            $table->foreignId('quotation_id')->constrained('b2b_quotations');

            $table->decimal('total_amount', 15, 2)->default(0);
            $table->decimal('escrow_balance', 15, 2)->default(0);

            $table->string('payment_status', 30)->default('UNPAID');
            $table->timestamp('dp_paid_at')->nullable();
            $table->timestamp('settled_at')->nullable();

            $table->string('status', 30)->default('DP_PENDING');

            $table->string('payout_status', 30)->default('NOT_ELIGIBLE');
            $table->timestamp('payout_requested_at')->nullable();
            $table->timestamp('payout_paid_at')->nullable();

            $table->timestamps();

            $table->index(['status', 'payment_status']);
        });

        // ------------------------------------------------------- 4. Negotiations
        Schema::create('b2b_negotiations', function (Blueprint $table) {
            $table->id();

            $table->foreignId('rfq_id')->constrained('b2b_rfqs')->cascadeOnDelete();
            $table->foreignId('sender_id')->constrained('users')->cascadeOnDelete();

            $table->text('message');
            $table->string('attachment_url', 255)->nullable();

            $table->timestamps();

            $table->index(['rfq_id', 'created_at']);
        });

        // --------------------------------------------------------- 5. Milestones
        Schema::create('b2b_milestones', function (Blueprint $table) {
            $table->id();

            $table->foreignId('order_id')->constrained('b2b_orders')->cascadeOnDelete();

            $table->string('stage_name', 100);
            $table->unsignedTinyInteger('stage_order')->default(1);
            $table->unsignedTinyInteger('progress_percent')->default(0);
            $table->date('target_date')->nullable();
            $table->string('photo_url', 255)->nullable();
            $table->text('qc_notes')->nullable();
            $table->boolean('is_approved_by_designer')->default(false);

            $table->timestamps();

            $table->unique(['order_id', 'stage_name']);
        });

        // --------------------------------------------------------- 6. Deliveries
        Schema::create('b2b_deliveries', function (Blueprint $table) {
            $table->id();
            $table->string('surat_jalan_number', 50)->unique();

            $table->foreignId('order_id')->unique()->constrained('b2b_orders')->cascadeOnDelete();

            $table->string('courier_service', 100);
            $table->string('driver_name', 100)->nullable();
            $table->string('vehicle_plate', 20)->nullable();
            $table->date('delivery_date')->nullable();
            $table->text('destination_address');
            $table->string('recipient_name', 100)->nullable();
            $table->string('recipient_phone', 30)->nullable();

            $table->string('status', 30)->default('PREPARING');

            $table->timestamps();
        });

        // -------------------------------------------------------------- 7. BAST
        Schema::create('b2b_basts', function (Blueprint $table) {
            $table->id();
            $table->string('bast_number', 50)->unique();

            $table->foreignId('order_id')->unique()->constrained('b2b_orders')->cascadeOnDelete();

            $table->string('designer_signature_url', 255)->nullable();
            $table->string('photo_installed_url', 255)->nullable();
            $table->json('inspection_checklist')->nullable();
            $table->boolean('is_accepted')->default(true);
            $table->timestamp('signed_at')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('b2b_basts');
        Schema::dropIfExists('b2b_deliveries');
        Schema::dropIfExists('b2b_milestones');
        Schema::dropIfExists('b2b_negotiations');
        Schema::dropIfExists('b2b_orders');
        Schema::dropIfExists('b2b_quotations');
        Schema::dropIfExists('b2b_rfqs');
    }
};
