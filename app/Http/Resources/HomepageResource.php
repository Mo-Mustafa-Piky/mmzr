<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class HomepageResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'hero' => [
                'title' => $this->hero_title,
                'description' => $this->hero_description,
                'background_image' => $this->getFirstMediaUrl('hero_background_image'),
                'property_image' => $this->getFirstMediaUrl('hero_property_image'),
                'tabs' => [$this->hero_tab_1_label, $this->hero_tab_2_label, $this->hero_tab_3_label],
            ],
            'about' => [
                'section_label' => $this->about_section_label,
                'title' => $this->about_title,
                'description' => $this->about_description,
                'image' => $this->getFirstMediaUrl('about_image'),
                'button_text' => $this->about_button_text,
                'button_link' => $this->about_button_link,
            ],
            'global' => [
                'title' => $this->global_section_title,
                'subtitle' => $this->global_section_subtitle,
                'cards' => [
                    ['icon' => $this->getFirstMediaUrl('card_1_icon'), 'title' => $this->card_1_title, 'description' => $this->card_1_description],
                    ['icon' => $this->getFirstMediaUrl('card_2_icon'), 'title' => $this->card_2_title, 'description' => $this->card_2_description],
                    ['icon' => $this->getFirstMediaUrl('card_3_icon'), 'title' => $this->card_3_title, 'description' => $this->card_3_description],
                    ['icon' => $this->getFirstMediaUrl('card_4_icon'), 'title' => $this->card_4_title, 'description' => $this->card_4_description],
                ],
            ],
            'statistics' => [
                ['label' => $this->stat_1_label, 'value' => $this->stat_1_value],
                ['label' => $this->stat_2_label, 'value' => $this->stat_2_value],
                ['label' => $this->stat_3_label, 'value' => $this->stat_3_value],
                ['label' => $this->stat_4_label, 'value' => $this->stat_4_value],
            ],
            'partners' => [
                'title' => $this->partners_title,
                'logos' => $this->getMedia('partners_logos')->map(fn($media) => $media->getUrl()),
            ],
            'insights' => [
                'section_label' => $this->insights_section_label,
                'title' => $this->insights_title,
                'description' => $this->insights_description,
                'report_image' => $this->getFirstMediaUrl('insights_report_image'),
                'button_text' => $this->insights_button_text,
                'button_link' => $this->insights_button_link,
                'background_color' => $this->insights_background_color,
            ],
            'testimonials' => [
                'title' => $this->testimonials_title,
                'description' => $this->testimonials_description,
            ],
            'careers' => [
                'section_label' => $this->careers_section_label,
                'title' => $this->careers_title,
                'subtitle' => $this->careers_subtitle,
                'images' => $this->getMedia('careers_images')->map(fn($media) => $media->getUrl()),
                'button_text' => $this->careers_button_text,
                'button_link' => $this->careers_button_link,
                'background_color' => $this->careers_background_color,
            ],
            'featured_properties' => [
                'title' => $this->featured_section_title,
                'tabs' => [
                    'off_plan' => $this->show_off_plan_tab,
                    'buy' => $this->show_buy_tab,
                    'rent' => $this->show_rent_tab,
                    'commercial' => $this->show_commercial_tab,
                    'featured_projects' => $this->show_featured_projects_tab,
                ],
                'properties_per_section' => $this->properties_per_section,
            ],
            'contact' => [
                'section_label' => $this->contact_section_label,
                'title' => $this->contact_title,
                'title_highlight' => $this->contact_title_highlight,
                'description' => $this->contact_description,
                'form' => [
                    'name_placeholder' => $this->contact_form_name_placeholder,
                    'email_placeholder' => $this->contact_form_email_placeholder,
                    'phone_placeholder' => $this->contact_form_phone_placeholder,
                    'interest_placeholder' => $this->contact_form_interest_placeholder,
                    'message_placeholder' => $this->contact_form_message_placeholder,
                    'button_text' => $this->contact_form_button_text,
                    'interest_options' => $this->contact_form_interest_options,
                ],
            ],
            'newsletter' => [
                'section_label' => $this->newsletter_section_label,
                'title' => $this->newsletter_title,
                'description' => $this->newsletter_description,
                'image' => $this->getFirstMediaUrl('newsletter_image'),
                'firstname_placeholder' => $this->newsletter_firstname_placeholder,
                'lastname_placeholder' => $this->newsletter_lastname_placeholder,
                'email_placeholder' => $this->newsletter_email_placeholder,
                'button_text' => $this->newsletter_button_text,
                'background_color' => $this->newsletter_background_color,
            ],
            'seo' => [
                'meta_title' => $this->meta_title,
                'meta_description' => $this->meta_description,
                'meta_keywords' => $this->meta_keywords,
                'og_image' => $this->getFirstMediaUrl('og_image'),
            ],
        ];
    }
}
