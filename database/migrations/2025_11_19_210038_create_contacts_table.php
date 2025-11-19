<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('contacts', function (Blueprint $table) {
            $table->id();
            $table->string('title_id')->nullable();
            $table->string('first_name');
            $table->string('family_name');
            $table->string('mobile_country_code')->nullable();
            $table->string('mobile_area_code')->nullable();
            $table->string('mobile_phone')->nullable();
            $table->string('telephone_country_code')->nullable();
            $table->string('telephone_area_code')->nullable();
            $table->string('telephone')->nullable();
            $table->string('email')->nullable();
            $table->string('nationality_id')->nullable();
            $table->string('company_id')->nullable();
            $table->text('remarks')->nullable();
            $table->string('requirement_type')->nullable();
            $table->string('contact_type')->nullable();
            $table->string('country_id')->nullable();
            $table->string('state_id')->nullable();
            $table->string('city_id')->nullable();
            $table->string('district_id')->nullable();
            $table->string('community_id')->nullable();
            $table->string('sub_community_id')->nullable();
            $table->string('property_id')->nullable();
            $table->string('unit_type')->nullable();
            $table->string('method_of_contact')->nullable();
            $table->string('media_type')->nullable();
            $table->string('media_name')->nullable();
            $table->string('referred_by_id')->nullable();
            $table->string('referred_to_id')->nullable();
            $table->string('deactivate_notification')->nullable();
            $table->string('bedroom')->nullable();
            $table->string('budget')->nullable();
            $table->string('budget2')->nullable();
            $table->string('requirement_country_id')->nullable();
            $table->string('existing_client')->nullable();
            $table->string('compaign_source')->nullable();
            $table->string('compaign_medium')->nullable();
            $table->string('company')->nullable();
            $table->string('number_of_employee')->nullable();
            $table->string('lead_stage_id')->nullable();
            $table->string('activity_date')->nullable();
            $table->string('activity_time')->nullable();
            $table->string('activity_type_id')->nullable();
            $table->string('activity_subject')->nullable();
            $table->text('activity_remarks')->nullable();
            $table->string('goyzer_response')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('contacts');
    }
};
