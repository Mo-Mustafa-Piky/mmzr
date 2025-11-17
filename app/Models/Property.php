<?php

// 1. app/Models/Property.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Property extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'unit_pk',
        'unit_reference_no',
        'type', // 'sale' or 'rental'
        'property_name',
        'marketing_title',
        'marketing_title_ar',
        'description',
        'description_ar',
        'bedrooms',
        'bathrooms',
        'rooms',
        'unit_type',
        'unit_subtype',
        'unit_model',
        'builtup_area',
        'plot_area',
        'unit_measure',
        'floor_number',
        'selling_price',
        'rent_per_annum',
        'maintenance_fee',
        'city',
        'state',
        'community',
        'sub_community',
        'district',
        'primary_view',
        'secondary_view',
        'images',
        'floor_plan',
        'external_images',
        'listing_agent',
        'listing_agent_phone',
        'listing_agent_email',
        'branch_name',
        'branch_phone',
        'company_id',
        'company_name',
        'company_email',
        'company_phone',
        'company_logo',
        'company_registration_number',
        'group_web_url',
        'map_coordinates',
        'facilities',
        'site_info_amenities',
        'local_area_amenities',
        'usp',
        'freehold_leasehold',
        'permit_number',
        'handover_date',
        'listing_date',
        'last_updated',
        'status', // 'active', 'sold', 'leased', 'reserved'
        'is_synced',
        'goyzer_data',
    ];

    protected $casts = [
        'images' => 'array',
        'external_images' => 'array',
        'facilities' => 'array',
        'goyzer_data' => 'array',
        'is_synced' => 'boolean',
        'listing_date' => 'datetime',
        'handover_date' => 'datetime',
        'last_updated' => 'datetime',
        'selling_price' => 'decimal:2',
        'rent_per_annum' => 'decimal:2',
        'maintenance_fee' => 'decimal:2',
        'builtup_area' => 'decimal:2',
        'plot_area' => 'decimal:2',
    ];

    public function scopeForSale($query)
    {
        return $query->where('type', 'sale');
    }

    public function scopeForRent($query)
    {
        return $query->where('type', 'rental');
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }
}
