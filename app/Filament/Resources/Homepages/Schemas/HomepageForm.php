<?php

namespace App\Filament\Resources\Homepages\Schemas;

use Filament\Forms\Components\ColorPicker;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Schema;

class HomepageForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Tabs::make('Homepage')
                    ->tabs([
                        Tabs\Tab::make('Hero Section')
                            ->icon('heroicon-o-sparkles')
                            ->schema([
                                TextInput::make('hero_title')->label('Title')->columnSpanFull(),
                                Textarea::make('hero_description')->label('Description')->rows(3)->columnSpanFull(),
                                SpatieMediaLibraryFileUpload::make('hero_background_image')->collection('hero_background_image')->image()->columnSpanFull(),
                                SpatieMediaLibraryFileUpload::make('hero_property_image')->collection('hero_property_image')->image()->columnSpanFull(),
                                TextInput::make('hero_tab_1_label')->label('Tab 1 Label'),
                                TextInput::make('hero_tab_2_label')->label('Tab 2 Label'),
                                TextInput::make('hero_tab_3_label')->label('Tab 3 Label'),
                            ])->columns(3),
                        
                        Tabs\Tab::make('About Section')
                            ->icon('heroicon-o-information-circle')
                            ->schema([
                                TextInput::make('about_section_label')->label('Section Label'),
                                TextInput::make('about_title')->label('Title')->columnSpanFull(),
                                Textarea::make('about_description')->label('Description')->rows(4)->columnSpanFull(),
                                SpatieMediaLibraryFileUpload::make('about_image')->collection('about_image')->image()->columnSpanFull(),
                                TextInput::make('about_button_text')->label('Button Text'),
                                TextInput::make('about_button_link')->label('Button Link')->url(),
                            ])->columns(2),
                        
                        Tabs\Tab::make('Global Section')
                            ->icon('heroicon-o-globe-alt')
                            ->schema([
                                TextInput::make('global_section_title')->label('Title')->columnSpanFull(),
                                TextInput::make('global_section_subtitle')->label('Subtitle')->columnSpanFull(),
                                Section::make('Card 1')->schema([
                                    SpatieMediaLibraryFileUpload::make('card_1_icon')->collection('card_1_icon')->image()->label('Card 1 Icon')->columnSpanFull(),
                                    TextInput::make('card_1_title')->label('Title')->columnSpanFull(),
                                    Textarea::make('card_1_description')->label('Description')->rows(3)->columnSpanFull(),
                                ]),
                                Section::make('Card 2')->schema([
                                    SpatieMediaLibraryFileUpload::make('card_2_icon')->collection('card_2_icon')->image()->label('Card 2 Icon')->columnSpanFull(),
                                    TextInput::make('card_2_title')->label('Title')->columnSpanFull(),
                                    Textarea::make('card_2_description')->label('Description')->rows(3)->columnSpanFull(),
                                ]),
                                Section::make('Card 3')->schema([
                                    SpatieMediaLibraryFileUpload::make('card_3_icon')->collection('card_3_icon')->image()->label('Card 3 Icon')->columnSpanFull(),
                                    TextInput::make('card_3_title')->label('Title')->columnSpanFull(),
                                    Textarea::make('card_3_description')->label('Description')->rows(3)->columnSpanFull(),
                                ]),
                                Section::make('Card 4')->schema([
                                    SpatieMediaLibraryFileUpload::make('card_4_icon')->collection('card_4_icon')->image()->label('Card 4 Icon')->columnSpanFull(),
                                    TextInput::make('card_4_title')->label('Title')->columnSpanFull(),
                                    Textarea::make('card_4_description')->label('Description')->rows(3)->columnSpanFull(),
                                ]),
                            ]),
                        
                        Tabs\Tab::make('Statistics')
                            ->icon('heroicon-o-chart-bar')
                            ->schema([
                                TextInput::make('stat_1_label')->label('Stat 1 Label'),
                                TextInput::make('stat_1_value')->label('Stat 1 Value'),
                                TextInput::make('stat_2_label')->label('Stat 2 Label'),
                                TextInput::make('stat_2_value')->label('Stat 2 Value'),
                                TextInput::make('stat_3_label')->label('Stat 3 Label'),
                                TextInput::make('stat_3_value')->label('Stat 3 Value'),
                                TextInput::make('stat_4_label')->label('Stat 4 Label'),
                                TextInput::make('stat_4_value')->label('Stat 4 Value'),
                            ])->columns(2),
                        
                        Tabs\Tab::make('Partners')
                            ->icon('heroicon-o-building-office')
                            ->schema([
                                TextInput::make('partners_title')->label('Title')->columnSpanFull(),
                                SpatieMediaLibraryFileUpload::make('partners_logos')->collection('partners_logos')->image()->multiple()->columnSpanFull(),
                            ]),
                        
                        Tabs\Tab::make('Insights')
                            ->icon('heroicon-o-light-bulb')
                            ->schema([
                                TextInput::make('insights_section_label')->label('Section Label'),
                                TextInput::make('insights_title')->label('Title')->columnSpanFull(),
                                Textarea::make('insights_description')->label('Description')->rows(3)->columnSpanFull(),
                                SpatieMediaLibraryFileUpload::make('insights_report_image')->collection('insights_report_image')->image()->columnSpanFull(),
                                TextInput::make('insights_button_text')->label('Button Text'),
                                TextInput::make('insights_button_link')->label('Button Link')->url(),
                                ColorPicker::make('insights_background_color')->label('Background Color'),
                            ])->columns(3),
                        
                        Tabs\Tab::make('Testimonials')
                            ->icon('heroicon-o-chat-bubble-left-right')
                            ->schema([
                                TextInput::make('testimonials_title')->label('Title')->columnSpanFull(),
                                Textarea::make('testimonials_description')->label('Description')->rows(3)->columnSpanFull(),
                            ]),
                        
                        Tabs\Tab::make('Careers')
                            ->icon('heroicon-o-briefcase')
                            ->schema([
                                TextInput::make('careers_section_label')->label('Section Label'),
                                TextInput::make('careers_title')->label('Title')->columnSpanFull(),
                                TextInput::make('careers_subtitle')->label('Subtitle')->columnSpanFull(),
                                SpatieMediaLibraryFileUpload::make('careers_images')->collection('careers_images')->image()->multiple()->columnSpanFull(),
                                TextInput::make('careers_button_text')->label('Button Text'),
                                TextInput::make('careers_button_link')->label('Button Link')->url(),
                                ColorPicker::make('careers_background_color')->label('Background Color'),
                            ])->columns(3),
                        
                        Tabs\Tab::make('Featured Properties')
                            ->icon('heroicon-o-building-office-2')
                            ->schema([
                                TextInput::make('featured_section_title')->label('Section Title')->columnSpanFull(),
                                Toggle::make('show_off_plan_tab')->label('Show Off Plan Tab')->inline(false),
                                Toggle::make('show_buy_tab')->label('Show Buy Tab')->inline(false),
                                Toggle::make('show_rent_tab')->label('Show Rent Tab')->inline(false),
                                Toggle::make('show_commercial_tab')->label('Show Commercial Tab')->inline(false),
                                Toggle::make('show_featured_projects_tab')->label('Show Featured Projects Tab')->inline(false),
                                TextInput::make('properties_per_section')->label('Properties Per Section')->numeric()->default(6),
                            ])->columns(3),
                        
                        Tabs\Tab::make('Contact Form')
                            ->icon('heroicon-o-envelope')
                            ->schema([
                                TextInput::make('contact_section_label')->label('Section Label'),
                                TextInput::make('contact_title')->label('Title'),
                                TextInput::make('contact_title_highlight')->label('Title Highlight'),
                                Textarea::make('contact_description')->label('Description')->rows(2)->columnSpanFull(),
                                TextInput::make('contact_form_name_placeholder')->label('Name Placeholder'),
                                TextInput::make('contact_form_email_placeholder')->label('Email Placeholder'),
                                TextInput::make('contact_form_phone_placeholder')->label('Phone Placeholder'),
                                TextInput::make('contact_form_interest_placeholder')->label('Interest Placeholder'),
                                TextInput::make('contact_form_message_placeholder')->label('Message Placeholder'),
                                TextInput::make('contact_form_button_text')->label('Button Text'),
                                Repeater::make('contact_form_interest_options')->label('Interest Options')->simple(TextInput::make('option'))->columnSpanFull(),
                            ])->columns(2),
                        
                        Tabs\Tab::make('Newsletter')
                            ->icon('heroicon-o-newspaper')
                            ->schema([
                                TextInput::make('newsletter_section_label')->label('Section Label'),
                                TextInput::make('newsletter_title')->label('Title')->columnSpanFull(),
                                Textarea::make('newsletter_description')->label('Description')->rows(3)->columnSpanFull(),
                                SpatieMediaLibraryFileUpload::make('newsletter_image')->collection('newsletter_image')->image()->columnSpanFull(),
                                TextInput::make('newsletter_firstname_placeholder')->label('First Name Placeholder'),
                                TextInput::make('newsletter_lastname_placeholder')->label('Last Name Placeholder'),
                                TextInput::make('newsletter_email_placeholder')->label('Email Placeholder'),
                                TextInput::make('newsletter_button_text')->label('Button Text'),
                                ColorPicker::make('newsletter_background_color')->label('Background Color'),
                            ])->columns(3),
                        
                        Tabs\Tab::make('SEO')
                            ->icon('heroicon-o-magnifying-glass')
                            ->schema([
                                TextInput::make('meta_title')->label('Meta Title')->columnSpanFull(),
                                Textarea::make('meta_description')->label('Meta Description')->rows(3)->columnSpanFull(),
                                TextInput::make('meta_keywords')->label('Meta Keywords')->columnSpanFull(),
                                SpatieMediaLibraryFileUpload::make('og_image')->collection('og_image')->image()->label('OG Image')->columnSpanFull(),
                            ]),
                    ])
                    ->columnSpanFull()
                    ->persistTabInQueryString(),
            ]);
    }
}
