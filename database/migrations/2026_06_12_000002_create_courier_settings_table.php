<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('courier_settings', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('store_id');
            $table->unsignedBigInteger('courier_id');

            $table->string('store_code')->nullable();
            $table->string('phone', 20)->nullable();
            $table->string('email')->nullable();

            // login / api credentials
            $table->string('password')->nullable();
            $table->text('api_key')->nullable();
            $table->text('secret_key')->nullable();

            // oauth tokens (optional)
            $table->text('access_token')->nullable();
            $table->text('refresh_token')->nullable();

            $table->string('client_id')->nullable();
            $table->string('client_secret')->nullable();

            $table->text('client_context')->nullable();

            $table->tinyInteger('status')->default(1);

            $table->timestamps();

            $table->unique(['store_id', 'courier_id'], 'unique_store_courier');
            $table->index('store_id');
            $table->index('courier_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('courier_settings');
    }
};
