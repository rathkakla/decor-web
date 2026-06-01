<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('consultation_reviews', function (Blueprint $table) {
            $table->id();
            $table->foreignId('consultation_id')->constrained('consultations')->onDelete('cascade');
            $table->foreignId('customer_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('designer_id')->constrained('designers')->onDelete('cascade');
            $table->tinyInteger('rating')->unsigned(); // 1–5
            $table->text('comment')->nullable();
            $table->string('project_duration')->nullable(); // e.g. "2 minggu", "1 bulan"
            $table->timestamps();

            $table->unique('consultation_id'); // one review per consultation
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('consultation_reviews');
    }
};