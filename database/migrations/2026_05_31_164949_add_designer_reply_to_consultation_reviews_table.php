<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('consultation_reviews', function (Blueprint $table) {
            $table->text('designer_reply')->nullable()->after('project_duration');
            $table->timestamp('designer_replied_at')->nullable()->after('designer_reply');
        });
    }

    public function down(): void
    {
        Schema::table('consultation_reviews', function (Blueprint $table) {
            $table->dropColumn(['designer_reply', 'designer_replied_at']);
        });
    }
};
