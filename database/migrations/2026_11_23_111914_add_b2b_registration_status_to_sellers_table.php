<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Status pengajuan mandiri Layanan Pesanan Proyek (B2B) oleh seller.
     * Alur: not_registered -> pending -> approved / rejected
     */
    public function up(): void
    {
        Schema::table('sellers', function (Blueprint $table) {
            $table->enum('b2b_status', ['not_registered', 'pending', 'approved', 'rejected'])
                ->default('not_registered')
                ->after('b2b_enabled');
            $table->text('b2b_rejection_reason')->nullable()->after('b2b_status');
            $table->timestamp('b2b_terms_agreed_at')->nullable()->after('b2b_rejection_reason');
        });
    }

    public function down(): void
    {
        Schema::table('sellers', function (Blueprint $table) {
            $table->dropColumn(['b2b_status', 'b2b_rejection_reason', 'b2b_terms_agreed_at']);
        });
    }
};