<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('b2b_negotiations', function (Blueprint $table) {
            $table->boolean('is_read')->default(false)->after('attachment_url');
            $table->index(['rfq_id', 'is_read']);
        });
    }

    public function down(): void
    {
        Schema::table('b2b_negotiations', function (Blueprint $table) {
            $table->dropIndex(['rfq_id', 'is_read']);
            $table->dropColumn('is_read');
        });
    }
};
