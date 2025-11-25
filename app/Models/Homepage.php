<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Homepage extends Model implements HasMedia
{
    use InteractsWithMedia;

    protected static function booted()
    {
        static::saved(function () {
            \Cache::forget('homepage_data');
        });

        static::deleted(function () {
            \Cache::forget('homepage_data');
        });
    }

    protected $table = 'homepage';

    protected $fillable = [
        'hero_title', 'hero_description', 'hero_tab_1_label', 'hero_tab_2_label', 'hero_tab_3_label',
        'about_section_label', 'about_title', 'about_description', 'about_button_text', 'about_button_link',
        'global_section_title', 'global_section_subtitle',
        'card_1_title', 'card_1_description', 'card_2_title', 'card_2_description',
        'card_3_title', 'card_3_description', 'card_4_title', 'card_4_description',
        'stat_1_label', 'stat_1_value', 'stat_2_label', 'stat_2_value',
        'stat_3_label', 'stat_3_value', 'stat_4_label', 'stat_4_value',
        'partners_title',
        'insights_section_label', 'insights_title', 'insights_description',
        'insights_button_text', 'insights_button_link', 'insights_background_color',
        'testimonials_title', 'testimonials_description',
        'careers_section_label', 'careers_title', 'careers_subtitle',
        'careers_button_text', 'careers_button_link', 'careers_background_color',
        'featured_section_title', 'show_off_plan_tab', 'show_buy_tab', 'show_rent_tab',
        'show_commercial_tab', 'show_featured_projects_tab', 'properties_per_section',
        'contact_section_label', 'contact_title', 'contact_title_highlight', 'contact_description',
        'contact_form_name_placeholder', 'contact_form_email_placeholder', 'contact_form_phone_placeholder',
        'contact_form_interest_placeholder', 'contact_form_message_placeholder', 'contact_form_button_text',
        'contact_form_interest_options',
        'newsletter_section_label', 'newsletter_title', 'newsletter_description',
        'newsletter_firstname_placeholder', 'newsletter_lastname_placeholder', 'newsletter_email_placeholder',
        'newsletter_button_text', 'newsletter_background_color',
        'meta_title', 'meta_description', 'meta_keywords',
    ];

    protected $casts = [
        'show_off_plan_tab' => 'boolean',
        'show_buy_tab' => 'boolean',
        'show_rent_tab' => 'boolean',
        'show_commercial_tab' => 'boolean',
        'show_featured_projects_tab' => 'boolean',
        'properties_per_section' => 'integer',
        'contact_form_interest_options' => 'array',
    ];

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('hero_background_image')->singleFile();
        $this->addMediaCollection('hero_property_image')->singleFile();
        $this->addMediaCollection('about_image')->singleFile();
        $this->addMediaCollection('card_1_icon')->singleFile();
        $this->addMediaCollection('card_2_icon')->singleFile();
        $this->addMediaCollection('card_3_icon')->singleFile();
        $this->addMediaCollection('card_4_icon')->singleFile();
        $this->addMediaCollection('partners_logos');
        $this->addMediaCollection('insights_report_image')->singleFile();
        $this->addMediaCollection('careers_images');
        $this->addMediaCollection('newsletter_image')->singleFile();
        $this->addMediaCollection('og_image')->singleFile();
    }

    public function clearCache()
    {
        \Cache::forget('homepage_data');
    }
}
