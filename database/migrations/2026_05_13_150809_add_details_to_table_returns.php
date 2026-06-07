<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('returns', function (Blueprint $table) {
            $table->string('return_type', 20)->nullable()->after('order_id'); // 'refund', 'exchange'
            $table->string('video_proof')->nullable()->after('reason');
            $table->string('photo_proof')->nullable()->after('video_proof');
            $table->string('bank_account_number', 50)->nullable()->after('photo_proof');
        });
    }

    public function down(): void
    {
        Schema::table('returns', function (Blueprint $table) {
            $table->dropColumn(['return_type', 'video_proof', 'photo_proof', 'bank_account_number']);
        });
    }
};