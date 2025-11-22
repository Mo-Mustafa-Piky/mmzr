<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Contact extends Model
{
    public function utms(): HasMany
    {
        return $this->hasMany(ContactUtm::class);
    }

    public function feedbacks(): HasMany
    {
        return $this->hasMany(AgentFeedback::class);
    }

    public function directMarketings(): HasMany
    {
        return $this->hasMany(DirectMarketing::class);
    }

    protected $fillable = [
        'title_id', 'first_name', 'family_name', 'mobile_country_code', 'mobile_area_code',
        'mobile_phone', 'telephone_country_code', 'telephone_area_code', 'telephone', 'email',
        'nationality_id', 'company_id', 'remarks', 'requirement_type', 'contact_type',
        'country_id', 'state_id', 'city_id', 'district_id', 'community_id', 'sub_community_id',
        'property_id', 'unit_id', 'unit_type', 'method_of_contact', 'media_type', 'media_name',
        'referred_by_id', 'referred_to_id', 'deactivate_notification', 'bedroom', 'budget',
        'budget2', 'requirement_country_id', 'existing_client', 'compaign_source',
        'compaign_medium', 'company', 'number_of_employee', 'lead_stage_id', 'activity_date',
        'activity_time', 'activity_type_id', 'activity_subject', 'activity_remarks',
        'contact_sub_type', 'utm_id', 'utm_term', 'utm_content', 'utm_ad_set', 'utm_campaign_name',
        'goyzer_response'
    ];
}
