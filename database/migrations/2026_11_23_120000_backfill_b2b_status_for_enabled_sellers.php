<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Backfill: seller yang modul B2B-nya sudah aktif sebelum fitur pendaftaran
     * mandiri ada harus dianggap "approved", bukan "not_registered".
     */
    public function up(): void
    {
        if (! Schema::hasTable('sellers') || ! Schema::hasColumn('sellers', 'b2b_status')) {
            return;
        }

        DB::table('sellers')
            ->where('b2b_enabled', true)
            ->update([
                'b2b_status' => 'approved',
                'b2b_rejection_reason' => null,
            ]);
    }

    public function down(): void
    {
        // Tidak ada yang perlu dibalik: status hanya penanda.
    }
};