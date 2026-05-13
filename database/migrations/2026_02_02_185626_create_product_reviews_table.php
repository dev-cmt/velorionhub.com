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
        Schema::create('product_reviews', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->onDelete('cascade'); // links to products
            $table->unsignedBigInteger('user_id')->nullable(); // reviewer
            $table->string('name')->nullable();
            $table->string('email')->nullable();
            $table->tinyInteger('rating')->unsigned()->default(5); // 1-5 stars
            $table->text('comment')->nullable(); // review comment
            $table->boolean('status')->default(0); // 1=approved, 0=pending
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_reviews');
    }
};
