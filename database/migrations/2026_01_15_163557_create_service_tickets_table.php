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
        Schema::create('service_tickets', function (Blueprint $table) {
            $table->id();
            $table->string('customer_name');
            $table->string('customer_phone');
            $table->text('customer_address');
            $table->text('customer_notes')->nullable();
            $table->foreignId('assigned_to')->nullable()->constrained('users');
            $table->date('visit_date')->nullable();
            $table->text('assignment_notes')->nullable();
            $table->text('technician_notes')->nullable();
            $table->text('admin_notes')->nullable();
            $table->foreignId('approved_by')->nullable()->constrained('users');
            $table->timestamp('approved_at')->nullable();
            $table->enum('status', ['requested', 'assigned', 'inspected', 'approved', 'completed', 'rejected', 'cancelled'])->default('requested');
            $table->foreignId('requested_by')->constrained('users');
            
            // Add these fields if you want to store them separately
            $table->string('priority')->default('medium');
            $table->string('visit_time')->nullable();
            $table->decimal('estimated_hours', 4, 2)->nullable();
            $table->text('required_tools')->nullable();

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('service_tickets');
    }
};
