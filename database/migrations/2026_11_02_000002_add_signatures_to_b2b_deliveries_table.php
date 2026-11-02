<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('b2b_deliveries', function (Blueprint $table) {
            $table->string('workshop_signature_url', 255)->nullable()->after('recipient_phone');
            $table->timestamp('workshop_signed_at')->nullable()->after('workshop_signature_url');

            $table->string('driver_signature_url', 255)->nullable()->after('workshop_signed_at');
            $table->timestamp('driver_signed_at')->nullable()->after('driver_signature_url');

            $table->string('recipient_signature_url', 255)->nullable()->after('driver_signed_at');
            $table->timestamp('recipient_signed_at')->nullable()->after('recipient_signature_url');
        });
    }

    public function down(): void
    {
        Schema::table('b2b_deliveries', function (Blueprint $table) {
            $table->dropColumn([
                'workshop_signature_url',
                'workshop_signed_at',
                'driver_signature_url',
                'driver_signed_at',
                'recipient_signature_url',
                'recipient_signed_at',
            ]);
        });
    }
};
