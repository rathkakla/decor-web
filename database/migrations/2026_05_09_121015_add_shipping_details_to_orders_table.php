<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->string('shipping_recipient')->nullable()->after('customer_id');
            $table->string('shipping_phone')->nullable()->after('shipping_recipient');
            $table->string('shipping_city')->nullable()->after('shipping_phone');
            $table->string('shipping_province')->nullable()->after('shipping_city');
            $table->string('shipping_postal_code')->nullable()->after('shipping_province');
            $table->text('shipping_address')->nullable()->after('shipping_postal_code');
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            //
        });
    }
};