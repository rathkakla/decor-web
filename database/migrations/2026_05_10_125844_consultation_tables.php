<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('consultations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id')->constrained('customers')->onDelete('cascade');
            $table->foreignId('designer_id')->constrained('designers')->onDelete('cascade');
            $table->string('title', 150);
            $table->text('description');
            $table->string('budget_range', 100);
            $table->tinyInteger('status')->default(0)->comment('0: Waiting for Brief, 1: Drafting, 2: Under Review, 3: Revision Requested, 4: Completed');
            $table->string('cover_image')->nullable();
            $table->timestamps();
        });

        Schema::create('consultation_messages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('consultation_id')->constrained('consultations')->onDelete('cascade');
            $table->foreignId('sender_id')->constrained('users')->onDelete('cascade');
            $table->text('message');
            $table->timestamps();
        });

        Schema::create('consultation_attachments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('consultation_id')->constrained('consultations')->onDelete('cascade');
            $table->foreignId('uploaded_by')->constrained('users')->onDelete('cascade');
            $table->string('file_url');
            $table->string('file_type', 50);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('consultation_attachments');
        Schema::dropIfExists('consultation_messages');
        Schema::dropIfExists('consultations');
    }
};