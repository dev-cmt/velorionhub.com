<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Drops the legacy sale_id column from handovers.
     * If sale_id has data, copies it to order_id first.
     */
    public function up(): void
    {
        Schema::table('handovers', function (Blueprint $table) {
            // Ensure order_id exists
            if (!Schema::hasColumn('handovers', 'order_id')) {
                $table->unsignedBigInteger('order_id')->nullable()->after('id');
            }
        });

        // Copy any existing sale_id data into order_id
        if (Schema::hasColumn('handovers', 'sale_id')) {
            DB::statement('UPDATE handovers SET order_id = sale_id WHERE order_id IS NULL AND sale_id IS NOT NULL');

            Schema::table('handovers', function (Blueprint $table) {
                $table->dropColumn('sale_id');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('handovers', function (Blueprint $table) {
            if (!Schema::hasColumn('handovers', 'sale_id')) {
                $table->unsignedBigInteger('sale_id')->nullable()->after('order_id');
            }
        });
    }
};
