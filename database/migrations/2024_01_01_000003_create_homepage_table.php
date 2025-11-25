<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('homepage', function (Blueprint $table) {
            $table->id();
            
            // Hero Section
            $table->string('hero_title')->nullable();
            $table->text('hero_description')->nullable();
            $table->string('hero_tab_1_label')->nullable();
            $table->string('hero_tab_2_label')->nullable();
            $table->string('hero_tab_3_label')->nullable();
            
            // About Section
            $table->string('about_section_label')->nullable();
            $table->string('about_title')->nullable();
            $table->text('about_description')->nullable();
            $table->string('about_button_text')->nullable();
            $table->string('about_button_link')->nullable();
            
            // Global Section
            $table->string('global_section_title')->nullable();
            $table->string('global_section_subtitle')->nullable();
            
            // Cards
            $table->string('card_1_title')->nullable();
            $table->text('card_1_description')->nullable();
            $table->string('card_2_title')->nullable();
            $table->text('card_2_description')->nullable();
            $table->string('card_3_title')->nullable();
            $table->text('card_3_description')->nullable();
            $table->string('card_4_title')->nullable();
            $table->text('card_4_description')->nullable();
            
            // Statistics
            $table->string('stat_1_label')->nullable();
            $table->string('stat_1_value')->nullable();
            $table->string('stat_2_label')->nullable();
            $table->string('stat_2_value')->nullable();
            $table->string('stat_3_label')->nullable();
            $table->string('stat_3_value')->nullable();
            $table->string('stat_4_label')->nullable();
            $table->string('stat_4_value')->nullable();
            
            // Partners
            $table->string('partners_title')->nullable();
            
            // Insights Section
            $table->string('insights_section_label')->nullable();
            $table->string('insights_title')->nullable();
            $table->text('insights_description')->nullable();
            $table->string('insights_button_text')->nullable();
            $table->string('insights_button_link')->nullable();
            $table->string('insights_background_color')->nullable();
            
            // Testimonials
            $table->string('testimonials_title')->nullable();
            $table->text('testimonials_description')->nullable();
            
            // Careers
            $table->string('careers_section_label')->nullable();
            $table->string('careers_title')->nullable();
            $table->string('careers_subtitle')->nullable();
            $table->string('careers_button_text')->nullable();
            $table->string('careers_button_link')->nullable();
            $table->string('careers_background_color')->nullable();
            
            // Featured Properties
            $table->string('featured_section_title')->nullable();
            $table->boolean('show_off_plan_tab')->default(true);
            $table->boolean('show_buy_tab')->default(true);
            $table->boolean('show_rent_tab')->default(true);
            $table->boolean('show_commercial_tab')->default(true);
            $table->boolean('show_featured_projects_tab')->default(true);
            $table->integer('properties_per_section')->default(6);
            
            // Contact Form
            $table->string('contact_section_label')->nullable();
            $table->string('contact_title')->nullable();
            $table->string('contact_title_highlight')->nullable();
            $table->text('contact_description')->nullable();
            $table->string('contact_form_name_placeholder')->nullable();
            $table->string('contact_form_email_placeholder')->nullable();
            $table->string('contact_form_phone_placeholder')->nullable();
            $table->string('contact_form_interest_placeholder')->nullable();
            $table->string('contact_form_message_placeholder')->nullable();
            $table->string('contact_form_button_text')->nullable();
            $table->json('contact_form_interest_options')->nullable();
            
            // Newsletter
            $table->string('newsletter_section_label')->nullable();
            $table->string('newsletter_title')->nullable();
            $table->text('newsletter_description')->nullable();
            $table->string('newsletter_firstname_placeholder')->nullable();
            $table->string('newsletter_lastname_placeholder')->nullable();
            $table->string('newsletter_email_placeholder')->nullable();
            $table->string('newsletter_button_text')->nullable();
            $table->string('newsletter_background_color')->nullable();
            
            // SEO
            $table->string('meta_title')->nullable();
            $table->text('meta_description')->nullable();
            $table->string('meta_keywords')->nullable();
            
            $table->timestamps();
        });

        DB::table('homepage')->insert([
            'hero_title' => 'Easy way to find a perfect property',
            'hero_description' => 'We provide a complete service for the sale, purchase or rental of real estate.',
            'hero_tab_1_label' => 'Buy',
            'hero_tab_2_label' => 'Rent',
            'hero_tab_3_label' => 'Off Plan',
            'about_section_label' => 'Who We Are',
            'about_title' => 'Discover Sustainable Luxury Living with EcoHaven Realty.',
            'about_description' => 'EcoHaven Realty is a real estate agency specialising in eco-friendly homes and sustainable living. We offer a range of eco-friendly properties, including energy-efficient homes, homes built with eco-friendly materials, and homes equipped with sustainable technologies such as solar panels.',
            'about_button_text' => 'Discover',
            'about_button_link' => '/about',
            'global_section_title' => "We're Local. We're Global.",
            'global_section_subtitle' => 'WE MARKET YOUR PROPERTY TO THE WORLD.',
            'card_1_title' => 'Our Philosophy',
            'card_1_description' => 'Our vision is to offer exclusive after-sales client services which helps our company to create well-established client network in our key markets.',
            'card_2_title' => 'Our Vision & Mission',
            'card_2_description' => 'Our vision is to offer exclusive after-sales client services which helps our company to create well-established client network in our key markets.',
            'card_3_title' => 'Our Experties',
            'card_3_description' => 'What truly differentiates us from the competition is our commitment to providing premium and comprehensive after-sales client services.',
            'card_4_title' => 'Property Consultant',
            'card_4_description' => 'Buyown House Properties is currently seeking talented individuals to join our thriving team as Property Consultants.',
            'stat_1_label' => 'PRODUCT',
            'stat_1_value' => '10,0000+',
            'stat_2_label' => 'LIKES',
            'stat_2_value' => '45600',
            'stat_3_label' => 'SALE',
            'stat_3_value' => '576864',
            'stat_4_label' => 'CUSTOMERS',
            'stat_4_value' => '947444',
            'partners_title' => 'partners',
            'insights_section_label' => 'Shaping Skylines',
            'insights_title' => 'Dubai Real Estate Market Q2 2025',
            'insights_description' => 'Discover our Q2 2025 market report offering key insights into Dubai\'s property trends, future supply, and the shifting dynamics of the residential sales and rental markets.',
            'insights_button_text' => 'Discover',
            'insights_button_link' => '/insights/q2-2025-report',
            'insights_background_color' => '#1a3b6b',
            'testimonials_title' => 'We always go the extra mile for our clients.',
            'testimonials_description' => 'We are to help you build a excellent build, with us nothing is impossible. See what we have done and what they have to say about our work perform.',
            'careers_section_label' => 'Careers',
            'careers_title' => 'Interested in a career in real estate?',
            'careers_subtitle' => 'At MAMZR, we shape exceptional talent for a successful future.',
            'careers_button_text' => 'Join Our Team',
            'careers_button_link' => '/careers',
            'careers_background_color' => '#1a3b6b',
            'featured_section_title' => 'Featured Properties',
            'show_off_plan_tab' => true,
            'show_buy_tab' => true,
            'show_rent_tab' => true,
            'show_commercial_tab' => true,
            'show_featured_projects_tab' => true,
            'properties_per_section' => 6,
            'contact_section_label' => 'Contact Us',
            'contact_title' => 'Did You Find Your',
            'contact_title_highlight' => 'Dream Home?',
            'contact_description' => 'Thank you for getting in touch! if you find your dream home connect with us',
            'contact_form_name_placeholder' => 'Your Name',
            'contact_form_email_placeholder' => 'Your Email',
            'contact_form_phone_placeholder' => 'Phone Number',
            'contact_form_interest_placeholder' => 'Interested in',
            'contact_form_message_placeholder' => 'Message',
            'contact_form_button_text' => 'Submit',
            'contact_form_interest_options' => json_encode(['Buying', 'Renting', 'Selling', 'Off Plan', 'Commercial']),
            'newsletter_section_label' => 'Newsletter',
            'newsletter_title' => 'Sign up for exclusive updates!',
            'newsletter_description' => 'Subscribe to our newsletter to be the first to know about exclusive deals, property market trends, and real estate news in the UAE.',
            'newsletter_firstname_placeholder' => 'Your Name',
            'newsletter_lastname_placeholder' => 'Last Name',
            'newsletter_email_placeholder' => 'Email',
            'newsletter_button_text' => 'Sign up',
            'newsletter_background_color' => '#1a3b6b',
            'meta_title' => 'MAMZR - Find Your Perfect Property',
            'meta_description' => 'Discover sustainable luxury living with MAMZR. We provide complete real estate services for buying, selling, and renting properties.',
            'meta_keywords' => 'real estate, property, buy, rent, Dubai, sustainable homes',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('homepage');
    }
};
