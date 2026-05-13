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
        Schema::create('product_variants', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->string('variant_sku')->unique();
            $table->decimal('variant_price', 10, 2)->default(0.00);
            $table->decimal('purchase_cost', 10, 2)->default(0.00);
            $table->integer('variant_stock')->default(0);
            $table->json('attribute_item_ids')->nullable();
            $table->timestamps();

            $table->index(['product_id', 'variant_sku']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_variants');
    }
};
