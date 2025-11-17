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
         Schema::create('properties', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('unit_pk')->unique();
            $table->string('unit_reference_no')->nullable();
            $table->enum('type', ['sale', 'rental'])->default('sale');
            $table->string('property_name')->nullable();
            $table->string('marketing_title')->nullable();
            $table->string('marketing_title_ar')->nullable();
            $table->text('description')->nullable();
            $table->text('description_ar')->nullable();
            $table->integer('bedrooms')->nullable();
            $table->integer('bathrooms')->nullable();
            $table->integer('rooms')->nullable();
            $table->string('unit_type')->nullable();
            $table->string('unit_subtype')->nullable();
            $table->string('unit_model')->nullable();
            $table->decimal('builtup_area', 12, 2)->nullable();
            $table->decimal('plot_area', 12, 2)->nullable();
            $table->string('unit_measure')->nullable();
            $table->integer('floor_number')->nullable();
            $table->decimal('selling_price', 12, 2)->nullable();
            $table->decimal('rent_per_annum', 12, 2)->nullable();
            $table->decimal('maintenance_fee', 12, 2)->nullable();
            $table->string('city')->nullable();
            $table->string('state')->nullable();
            $table->string('community')->nullable();
            $table->string('sub_community')->nullable();
            $table->string('district')->nullable();
            $table->string('primary_view')->nullable();
            $table->string('secondary_view')->nullable();
            $table->json('images')->nullable();
            $table->string('floor_plan')->nullable();
            $table->json('external_images')->nullable();
            $table->string('listing_agent')->nullable();
            $table->string('listing_agent_phone')->nullable();
            $table->string('listing_agent_email')->nullable();
            $table->string('branch_name')->nullable();
            $table->string('branch_phone')->nullable();
            $table->integer('company_id')->nullable();
            $table->string('company_name')->nullable();
            $table->string('company_email')->nullable();
            $table->string('company_phone')->nullable();
            $table->string('company_logo')->nullable();
            $table->string('company_registration_number')->nullable();
            $table->string('group_web_url')->nullable();
            $table->string('map_coordinates')->nullable();
            $table->json('facilities')->nullable();
            $table->text('site_info_amenities')->nullable();
            $table->text('local_area_amenities')->nullable();
            $table->text('usp')->nullable();
            $table->string('freehold_leasehold')->nullable();
            $table->string('permit_number')->nullable();
            $table->timestamp('handover_date')->nullable();
            $table->timestamp('listing_date')->nullable();
            $table->timestamp('last_updated')->nullable();
            $table->enum('status', ['active', 'sold', 'leased', 'reserved'])->default('active');
            $table->boolean('is_synced')->default(false);
            $table->json('goyzer_data')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['type', 'status']);
            $table->index('bedrooms');
            $table->index('selling_price');
            $table->index('rent_per_annum');
            $table->index('city');
            $table->index('community');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('properties');
    }
};
