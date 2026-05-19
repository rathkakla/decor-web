<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
    Schema::create('ai_designs', function (Blueprint $table) {
        $table->id();
        $table->foreignId('user_id')->nullable()->constrained()->onDelete('cascade'); // Jika user harus login
        $table->string('original_image'); // Path foto asli yang diupload
        $table->string('generated_image')->nullable(); // Path/URL hasil dari AI
        $table->string('style_chosen'); // Gaya yang dipilih (e.g., Scandinavian, Industrial)
        $table->enum('status', ['pending', 'processing', 'completed', 'failed'])->default('pending');
        $table->timestamps();
    });
}
};
