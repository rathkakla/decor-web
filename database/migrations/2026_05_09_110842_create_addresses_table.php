<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {

    public function up(): void
    {
        if (!Schema::hasTable('addresses')) {
            Schema::create('addresses', function (Blueprint $table) {
                $table->id();
                $table->foreignId('customer_id')->constrained('customers')->onDelete('cascade');
                $table->string('label');
                $table->string('recipient_name')->nullable();
                $table->string('phone_number')->nullable();
                $table->string('city')->nullable();
                $table->text('full_address');
                $table->boolean('is_main')->default(false);
                $table->timestamps();
            });
        }

        if (Schema::hasColumn('customers', 'address')) {
            Schema::table('customers', function (Blueprint $table) {
                $table->dropColumn(['address', 'city', 'address_2', 'city_2']);
            });
        }
    }

    public function down(): void
    {
        Schema::table('customers', function (Blueprint $table) {
            $table->text('address')->nullable();
            $table->string('city', 100)->nullable();
            $table->text('address_2')->nullable();
            $table->string('city_2', 100)->nullable();
        });

        Schema::dropIfExists('addresses');
    }
};