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
        // STEP 1: Drop the old foreign key (orders.customer_id -> users)
        try {
            Schema::table('orders', function (Blueprint $table) {
                $table->dropForeign('orders_customer_id_foreign');
            });
        } catch (\Throwable $e) {
            // FK didn't exist or was already removed — safe to continue
        }

        // STEP 2: Add missing columns to orders table
        Schema::table('orders', function (Blueprint $table) {
            if (!Schema::hasColumn('orders', 'p_id')) {
                $table->unsignedBigInteger('p_id')->nullable()->after('id');
            }
            if (!Schema::hasColumn('orders', 'date')) {
                $table->timestamp('date')->nullable()->after('status');
            }
            if (!Schema::hasColumn('orders', 'customer_note')) {
                $table->text('customer_note')->nullable()->after('notes');
            }
            if (!Schema::hasColumn('orders', 'note')) {
                $table->text('note')->nullable()->after('customer_note');
            }
        });

        // STEP 3: Add new foreign key (orders.customer_id -> customers) as a separate ALTER
        try {
            Schema::table('orders', function (Blueprint $table) {
                $table->foreign('customer_id')
                      ->references('id')
                      ->on('customers')
                      ->nullOnDelete();
            });
        } catch (\Throwable $e) {
            // FK already exists — safe to continue
        }

        // STEP 4: Add item_out to order_items
        Schema::table('order_items', function (Blueprint $table) {
            if (!Schema::hasColumn('order_items', 'item_out')) {
                $table->integer('item_out')->default(1)->after('quantity');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // STEP 1: Drop new FK (orders.customer_id -> customers)
        try {
            Schema::table('orders', function (Blueprint $table) {
                $table->dropForeign(['customer_id']);
            });
        } catch (\Throwable $e) {}

        // STEP 2: Drop the added columns
        Schema::table('orders', function (Blueprint $table) {
            $cols = [];
            foreach (['p_id', 'date', 'customer_note', 'note'] as $col) {
                if (Schema::hasColumn('orders', $col)) {
                    $cols[] = $col;
                }
            }
            if (!empty($cols)) {
                $table->dropColumn($cols);
            }
        });

        // STEP 3: Re-add old FK pointing back to users
        try {
            Schema::table('orders', function (Blueprint $table) {
                $table->foreign('customer_id')
                      ->references('id')
                      ->on('users')
                      ->nullOnDelete();
            });
        } catch (\Throwable $e) {}

        // STEP 4: Drop item_out from order_items
        Schema::table('order_items', function (Blueprint $table) {
            if (Schema::hasColumn('order_items', 'item_out')) {
                $table->dropColumn('item_out');
            }
        });
    }
};
