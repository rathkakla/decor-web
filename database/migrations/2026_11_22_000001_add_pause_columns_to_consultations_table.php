<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('consultations', function (Blueprint $table) {
            if (! Schema::hasColumn('consultations', 'is_chat_paused')) {
                $table->boolean('is_chat_paused')->default(false)->after('chat_expires_at');
            }
            if (! Schema::hasColumn('consultations', 'chat_paused_at')) {
                $table->timestamp('chat_paused_at')->nullable()->after('is_chat_paused');
            }
            if (! Schema::hasColumn('consultations', 'remaining_chat_seconds')) {
                $table->unsignedInteger('remaining_chat_seconds')->nullable()->after('chat_paused_at');
            }
        });
    }

    public function down(): void
    {
        Schema::table('consultations', function (Blueprint $table) {
            if (Schema::hasColumn('consultations', 'remaining_chat_seconds')) {
                $table->dropColumn('remaining_chat_seconds');
            }
            if (Schema::hasColumn('consultations', 'chat_paused_at')) {
                $table->dropColumn('chat_paused_at');
            }
            if (Schema::hasColumn('consultations', 'is_chat_paused')) {
                $table->dropColumn('is_chat_paused');
            }
        });
    }
};
