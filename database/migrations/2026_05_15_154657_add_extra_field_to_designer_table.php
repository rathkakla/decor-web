<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('designers', function (Blueprint $table) {
            $table->text('education')->nullable()->after('bio');
            $table->text('awards')->nullable()->after('education');
        });
    }

    public function down(): void
    {
        Schema::table('designers', function (Blueprint $table) {
            //
        });
    }
};