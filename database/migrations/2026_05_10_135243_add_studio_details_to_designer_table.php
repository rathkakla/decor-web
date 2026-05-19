<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('designers', function (Blueprint $table) {
            $table->string('studio_name', 150)->nullable()->after('user_id');
            $table->string('banner_image')->nullable()->after('designer_image');
            $table->boolean('is_open')->default(true)->after('experience_years');
        });
    }

    public function down(): void
    {
        Schema::table('designers', function (Blueprint $table) {
            $table->dropColumn(['studio_name', 'banner_image', 'is_open']);
        });
    }
};