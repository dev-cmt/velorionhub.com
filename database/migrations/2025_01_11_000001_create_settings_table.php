<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->string('company_name')->nullable();
            $table->string('logo')->nullable();
            $table->string('logo_dark')->nullable();
            $table->string('logo_light')->nullable();
            $table->string('favicon')->nullable();
            $table->string('phone')->nullable();
            $table->string('phone2')->nullable();
            $table->string('email')->nullable();
            $table->string('email2')->nullable();
            $table->string('alert_email')->nullable();
            $table->text('address')->nullable();
            $table->text('map_url')->nullable();
            $table->text('description')->nullable();
            $table->text('copyright_text')->nullable();

            $table->json('social_links')->nullable();

            $table->json('currency_rates')->nullable();
            $table->json('currency_symbols')->nullable();

            $table->decimal('shipping_inside', 10, 2)->default(0);
            $table->decimal('shipping_outside', 10, 2)->default(0);
            $table->boolean('shipping_active')->default(false);

            $table->text('fb_pixel')->nullable();
            $table->text('gtm_head')->nullable();
            $table->text('gtm_body')->nullable();

            $table->boolean('is_loading')->default(true);
            $table->boolean('is_slider')->default(false);
            $table->string('active_theme')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('settings');
    }
};
