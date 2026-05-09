<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('chats', function (Blueprint $col) {
            $col->id();
            $col->unsignedBigInteger('sender_id');
            $col->unsignedBigInteger('receiver_id');
            $col->unsignedBigInteger('product_id')->nullable();
            $col->text('message');
            $col->boolean('is_read')->default(false);
            $col->timestamps();

            $col->foreign('sender_id')->references('id')->on('users')->onDelete('cascade');
            $col->foreign('receiver_id')->references('id')->on('users')->onDelete('cascade');
            $col->foreign('product_id')->references('id')->on('products')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('chats');
    }
};