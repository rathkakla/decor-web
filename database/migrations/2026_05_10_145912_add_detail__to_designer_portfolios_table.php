<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('designer_portfolios', function (Blueprint $table) {
            $table->string('title')->nullable();
            $table->string('category')->nullable();
            $table->string('budget')->nullable();
            $table->string('area')->nullable();
            $table->string('duration')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('designer_portfolios', function (Blueprint $table) {
            $table->dropColumn(['title', 'category', 'budget', 'area', 'duration']);
        });
    }
};