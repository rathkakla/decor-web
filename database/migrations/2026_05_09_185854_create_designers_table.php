<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Tabel ini sudah dibuat oleh create_decor_final_schema (dan dipakai
        // sebagai foreign key tabel lain). Lewati bila sudah ada supaya
        // `php artisan migrate` tetap jalan di database baru.
        if (Schema::hasTable('designers')) {
            return;
        }

        Schema::create('designers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('specialty', 100);
            $table->text('bio')->nullable();
            $table->integer('experience_years')->default(0);
            $table->string('designer_image')->nullable(); // Profile image designer
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('designers');
    }
};
