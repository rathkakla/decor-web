<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasColumn('product_images', 'sort_order')) {
            return;
        }

        Schema::table('product_images', function (Blueprint $table) {
            $table->unsignedInteger('sort_order')->default(0)->after('img_url');
            $table->index('sort_order');
        });
    }

    public function down(): void
    {
        if (! Schema::hasColumn('product_images', 'sort_order')) {
            return;
        }

        Schema::table('product_images', function (Blueprint $table) {
            $table->dropIndex(['sort_order']);
            $table->dropColumn('sort_order');
        });
    }
};