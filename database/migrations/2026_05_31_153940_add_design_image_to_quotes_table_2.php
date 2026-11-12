<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {

    public function up(): void
    {
        // Duplikat dari add_design_image_to_quotes_table; lewati bila kolom
        // sudah ada agar migrasi di database baru tidak gagal.
        if (Schema::hasColumn('consultation_quotes', 'design_image')) {
            return;
        }

        Schema::table('consultation_quotes', function (Blueprint $table) {
            $table->text('design_image')->nullable()->after('items');
        });
    }

    public function down(): void
    {
        if (! Schema::hasColumn('consultation_quotes', 'design_image')) {
            return;
        }

        Schema::table('consultation_quotes', function (Blueprint $table) {
            $table->dropColumn('design_image');
        });
    }
};