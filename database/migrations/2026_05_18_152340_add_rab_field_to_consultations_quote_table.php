<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('consultation_quotes', function (Blueprint $table) {
            $table->json('items')->nullable();
            $table->text('revision_notes')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('consultation_quotes', function (Blueprint $table) {
            $table->dropColumn(['items', 'revision_notes']);
        });
    }
};