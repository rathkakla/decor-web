<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Profil B2B pabrik mebel. Dipakai untuk filter katalog pabrik di sisi
     * designer (spesialisasi material, kapasitas bulanan, kota, SVLK).
     */
    public function up(): void
    {
        Schema::table('sellers', function (Blueprint $table) {
            $table->json('wood_specialties')->nullable()->after('store_description');
            $table->unsignedInteger('monthly_capacity')->nullable()->after('wood_specialties');
            $table->string('city', 100)->nullable()->after('monthly_capacity');
            $table->boolean('svlk_verified')->default(false)->after('city');
            $table->boolean('b2b_enabled')->default(false)->after('svlk_verified');
            $table->string('portfolio_url', 255)->nullable()->after('b2b_enabled');
        });
    }

    public function down(): void
    {
        Schema::table('sellers', function (Blueprint $table) {
            $table->dropColumn([
                'wood_specialties',
                'monthly_capacity',
                'city',
                'svlk_verified',
                'b2b_enabled',
                'portfolio_url',
            ]);
        });
    }
};
