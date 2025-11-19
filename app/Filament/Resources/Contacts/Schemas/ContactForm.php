<?php

namespace App\Filament\Resources\Contacts\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Select;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Schemas\Schema;
use App\Services\GoyzerService;

class ContactForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Tabs::make('Contact Information')
                    ->tabs([
                        Tabs\Tab::make('Personal Info')
                            ->icon('heroicon-o-user')
                            ->schema([
                                Select::make('title_id')
                                    ->label('Title')
                                    ->options(self::getTitleOptions())
                                    ->searchable()
                                    ->preload(),
                                TextInput::make('first_name')
                                    ->required()
                                    ->label('First Name')
                                    ->maxLength(255),
                                TextInput::make('family_name')
                                    ->required()
                                    ->label('Family Name')
                                    ->maxLength(255),
                                TextInput::make('email')
                                    ->email()
                                    ->label('Email')
                                    ->maxLength(255),
                                Select::make('nationality_id')
                                    ->label('Nationality')
                                    ->options(self::getNationalityOptions())
                                    ->searchable()
                                    ->preload(),
                                Select::make('contact_type')
                                    ->options([
                                        'Individual' => 'Individual',
                                        'Corporate' => 'Corporate'
                                    ])
                                    ->label('Contact Type')
                                    ->default('Individual'),
                                TextInput::make('company')
                                    ->label('Company')
                                    ->maxLength(255)
                                    ->visible(fn ($get) => $get('contact_type') === 'Corporate'),
                                Select::make('company_id')
                                    ->label('Company ID')
                                    ->options([])
                                    ->searchable()
                                    ->visible(fn ($get) => $get('contact_type') === 'Corporate'),
                                TextInput::make('number_of_employee')
                                    ->numeric()
                                    ->label('Number of Employees')
                                    ->minValue(1)
                                    ->visible(fn ($get) => $get('contact_type') === 'Corporate'),
                            ])->columns(2),

                        Tabs\Tab::make('Contact Details')
                            ->icon('heroicon-o-phone')
                            ->schema([
                                TextInput::make('mobile_country_code')
                                    ->label('Country Code')
                                    ->prefix('+')
                                    ->maxLength(5),
                                TextInput::make('mobile_area_code')
                                    ->label('Area Code')
                                    ->maxLength(10),
                                TextInput::make('mobile_phone')
                                    ->tel()
                                    ->label('Mobile Phone')
                                    ->maxLength(20),
                                TextInput::make('telephone_country_code')
                                    ->label('Tel Country Code')
                                    ->prefix('+')
                                    ->maxLength(5),
                                TextInput::make('telephone_area_code')
                                    ->label('Tel Area Code')
                                    ->maxLength(10),
                                TextInput::make('telephone')
                                    ->tel()
                                    ->label('Telephone')
                                    ->maxLength(20),
                                Select::make('method_of_contact')
                                    ->label('Preferred Contact Method')
                                    ->options(self::getContactMethodOptions())
                                    ->searchable()
                                    ->preload(),
                                TextInput::make('media_type')
                                    ->label('Media Type')
                                    ->maxLength(255),
                                TextInput::make('media_name')
                                    ->label('Media Name')
                                    ->maxLength(255),
                            ])->columns(3),

                        Tabs\Tab::make('Location')
                            ->icon('heroicon-o-map-pin')
                            ->schema([
                                Select::make('country_id')
                                    ->label('Country')
                                    ->options(self::getCountryOptions())
                                    ->searchable()
                                    ->preload()
                                    ->reactive(),
                                Select::make('state_id')
                                    ->label('State')
                                    ->options(self::getStateOptions())
                                    ->searchable()
                                    ->preload()
                                    ->reactive(),
                                Select::make('city_id')
                                    ->label('City')
                                    ->options(self::getCityOptions())
                                    ->searchable()
                                    ->preload()
                                    ->reactive(),
                                Select::make('district_id')
                                    ->label('District')
                                    ->options(self::getDistrictOptions())
                                    ->searchable()
                                    ->preload()
                                    ->reactive(),
                                Select::make('community_id')
                                    ->label('Community')
                                    ->options(self::getCommunityOptions())
                                    ->searchable()
                                    ->preload()
                                    ->reactive(),
                                Select::make('sub_community_id')
                                    ->label('Sub Community')
                                    ->options(self::getSubCommunityOptions())
                                    ->searchable()
                                    ->preload(),
                            ])->columns(3),

                        Tabs\Tab::make('Requirements')
                            ->icon('heroicon-o-clipboard-document-list')
                            ->schema([
                                Select::make('requirement_type')
                                    ->options(self::getRequirementTypeOptions())
                                    ->searchable()
                                    ->label('Requirement Type')
                                    ->preload(),
                                Select::make('requirement_country_id')
                                    ->label('Requirement Country')
                                    ->options(self::getCountryOptions())
                                    ->searchable()
                                    ->preload(),
                                Select::make('property_id')
                                    ->label('Property')
                                    ->options(self::getPropertyOptions())
                                    ->searchable()
                                    ->preload(),
                                Select::make('unit_type')
                                    ->label('Unit Type')
                                    ->options(self::getUnitCategoryOptions())
                                    ->searchable()
                                    ->preload(),
                                TextInput::make('bedroom')
                                    ->numeric()
                                    ->label('Bedrooms')
                                    ->minValue(0)
                                    ->suffix('BR'),
                                TextInput::make('budget')
                                    ->numeric()
                                    ->label('Min Budget')
                                    ->prefix('$')
                                    ->minValue(0),
                                TextInput::make('budget2')
                                    ->numeric()
                                    ->label('Max Budget')
                                    ->prefix('$')
                                    ->minValue(0),
                                Select::make('existing_client')
                                    ->label('Existing Client')
                                    ->options([
                                        'Yes' => 'Yes',
                                        'No' => 'No'
                                    ])
                                    ->default('No'),
                                Textarea::make('remarks')
                                    ->rows(3)
                                    ->columnSpanFull()
                                    ->label('Additional Remarks')
                                    ->maxLength(1000),
                            ])->columns(3),

                        Tabs\Tab::make('Campaign & Lead')
                            ->icon('heroicon-o-megaphone')
                            ->schema([
                                TextInput::make('compaign_source')
                                    ->label('Campaign Source')
                                    ->maxLength(255),
                                TextInput::make('compaign_medium')
                                    ->label('Campaign Medium')
                                    ->maxLength(255),
                                TextInput::make('lead_stage_id')
                                    ->label('Lead Stage ID')
                                    ->maxLength(255),
                                Select::make('referred_by_id')
                                    ->label('Referred By')
                                    ->options(self::getAgentOptions())
                                    ->searchable()
                                    ->preload(),
                                Select::make('referred_to_id')
                                    ->label('Referred To')
                                    ->options(self::getAgentOptions())
                                    ->searchable()
                                    ->preload(),
                                Select::make('deactivate_notification')
                                    ->label('Deactivate Notification')
                                    ->options([
                                        'Yes' => 'Yes',
                                        'No' => 'No'
                                    ])
                                    ->default('No'),
                            ])->columns(2),

                        Tabs\Tab::make('Activity')
                            ->icon('heroicon-o-calendar')
                            ->schema([
                                TextInput::make('activity_date')
                                    ->label('Activity Date')
                                    ->type('date'),
                                TextInput::make('activity_time')
                                    ->label('Activity Time')
                                    ->type('time'),
                                TextInput::make('activity_type_id')
                                    ->label('Activity Type ID')
                                    ->maxLength(255),
                                TextInput::make('activity_subject')
                                    ->label('Activity Subject')
                                    ->maxLength(255)
                                    ->columnSpanFull(),
                                Textarea::make('activity_remarks')
                                    ->rows(4)
                                    ->columnSpanFull()
                                    ->label('Activity Remarks')
                                    ->maxLength(1000),
                            ])->columns(2),
                    ])
                    ->columnSpanFull()
                    ->persistTabInQueryString()
            ]);
    }

    protected static function getCountryOptions(): array
    {
        $result = \Illuminate\Support\Facades\Cache::remember('goyzer_countries', 3600, function () {
            return app(GoyzerService::class)->getCountry();
        });
        
        if (!$result || !isset($result['GetCountryData'])) return [];
        
        $countries = is_array($result['GetCountryData']) && isset($result['GetCountryData'][0]) ? $result['GetCountryData'] : [$result['GetCountryData']];
        
        $options = [];
        foreach ($countries as $country) {
            $options[$country['CountryID'] ?? ''] = $country['CountryName'] ?? '';
        }
        return $options;
    }

    protected static function getStateOptions(): array
    {
        $result = \Illuminate\Support\Facades\Cache::remember('goyzer_states', 3600, function () {
            return app(GoyzerService::class)->getStates();
        });
        
        if (!$result || !isset($result['GetStatesData'])) return [];
        
        $states = is_array($result['GetStatesData']) && isset($result['GetStatesData'][0]) ? $result['GetStatesData'] : [$result['GetStatesData']];
        
        $options = [];
        foreach ($states as $state) {
            $options[$state['StateID'] ?? ''] = $state['StateName'] ?? '';
        }
        return $options;
    }

    protected static function getCityOptions(): array
    {
        $result = \Illuminate\Support\Facades\Cache::remember('goyzer_cities', 3600, function () {
            return app(GoyzerService::class)->getCities();
        });
        
        if (!$result || !isset($result['GetCitiesData'])) return [];
        
        $cities = is_array($result['GetCitiesData']) && isset($result['GetCitiesData'][0]) ? $result['GetCitiesData'] : [$result['GetCitiesData']];
        
        $options = [];
        foreach ($cities as $city) {
            $options[$city['CityID'] ?? ''] = $city['CityName'] ?? '';
        }
        return $options;
    }

    protected static function getCommunityOptions(): array
    {
        $result = \Illuminate\Support\Facades\Cache::remember('goyzer_communities', 3600, function () {
            return app(GoyzerService::class)->getCommunities();
        });
        
        if (!$result || !isset($result['GetCommunitiesData'])) return [];
        
        $communities = is_array($result['GetCommunitiesData']) && isset($result['GetCommunitiesData'][0]) ? $result['GetCommunitiesData'] : [$result['GetCommunitiesData']];
        
        $options = [];
        foreach ($communities as $community) {
            $options[$community['CommunityID'] ?? ''] = $community['Community'] ?? '';
        }
        return $options;
    }

    protected static function getNationalityOptions(): array
    {
        $result = \Illuminate\Support\Facades\Cache::remember('goyzer_nationalities', 3600, function () {
            return app(GoyzerService::class)->getNationality();
        });
        
        if (!$result || !isset($result['GetNationalityData'])) return [];
        
        $nationalities = is_array($result['GetNationalityData']) && isset($result['GetNationalityData'][0]) ? $result['GetNationalityData'] : [$result['GetNationalityData']];
        
        $options = [];
        foreach ($nationalities as $nationality) {
            $options[$nationality['NationalityID'] ?? ''] = $nationality['NationalityName'] ?? '';
        }
        return $options;
    }

    protected static function getTitleOptions(): array
    {
        $result = \Illuminate\Support\Facades\Cache::remember('goyzer_titles', 3600, fn() => app(GoyzerService::class)->getTitle());
        if (!$result || !isset($result['GetTitleData'])) return [];
        $titles = is_array($result['GetTitleData']) && isset($result['GetTitleData'][0]) ? $result['GetTitleData'] : [$result['GetTitleData']];
        $options = [];
        foreach ($titles as $title) {
            $options[$title['TitleID'] ?? ''] = $title['TitleName'] ?? '';
        }
        return $options;
    }

    protected static function getDistrictOptions(): array
    {
        $result = \Illuminate\Support\Facades\Cache::remember('goyzer_districts', 3600, fn() => app(GoyzerService::class)->getDistricts());
        if (!$result || !isset($result['GetDistrictsData'])) return [];
        $districts = is_array($result['GetDistrictsData']) && isset($result['GetDistrictsData'][0]) ? $result['GetDistrictsData'] : [$result['GetDistrictsData']];
        $options = [];
        foreach ($districts as $district) {
            $options[$district['DistrictID'] ?? ''] = $district['DistrictName'] ?? '';
        }
        return $options;
    }

    protected static function getSubCommunityOptions(): array
    {
        $result = \Illuminate\Support\Facades\Cache::remember('goyzer_sub_communities', 3600, fn() => app(GoyzerService::class)->getSubCommunity());
        if (!$result || !isset($result['GetSubCommunityData'])) return [];
        $subCommunities = is_array($result['GetSubCommunityData']) && isset($result['GetSubCommunityData'][0]) ? $result['GetSubCommunityData'] : [$result['GetSubCommunityData']];
        $options = [];
        foreach ($subCommunities as $subCommunity) {
            $options[$subCommunity['SubCommunityID'] ?? ''] = $subCommunity['SubCommunity'] ?? '';
        }
        return $options;
    }

    protected static function getRequirementTypeOptions(): array
    {
        $result = \Illuminate\Support\Facades\Cache::remember('goyzer_requirement_types', 3600, fn() => app(GoyzerService::class)->getRequirementType());
        if (!$result || !isset($result['GetRequirementTypeData'])) return [];
        $types = is_array($result['GetRequirementTypeData']) && isset($result['GetRequirementTypeData'][0]) ? $result['GetRequirementTypeData'] : [$result['GetRequirementTypeData']];
        $options = [];
        foreach ($types as $type) {
            $options[$type['RequirementTypeID'] ?? ''] = $type['RequirementType'] ?? '';
        }
        return $options;
    }

    protected static function getUnitCategoryOptions(): array
    {
        $result = \Illuminate\Support\Facades\Cache::remember('goyzer_unit_categories', 3600, fn() => app(GoyzerService::class)->getUnitCategory());
        if (!$result || !isset($result['GetUnitCategoryData'])) return [];
        $categories = is_array($result['GetUnitCategoryData']) && isset($result['GetUnitCategoryData'][0]) ? $result['GetUnitCategoryData'] : [$result['GetUnitCategoryData']];
        $options = [];
        foreach ($categories as $category) {
            $options[$category['CategoryID'] ?? ''] = $category['CategoryName'] ?? '';
        }
        return $options;
    }

    protected static function getContactMethodOptions(): array
    {
        $result = \Illuminate\Support\Facades\Cache::remember('goyzer_contact_methods', 3600, fn() => app(GoyzerService::class)->getContactMethods());
        if (!$result || !isset($result['GetContactMethodsData'])) return [];
        $methods = is_array($result['GetContactMethodsData']) && isset($result['GetContactMethodsData'][0]) ? $result['GetContactMethodsData'] : [$result['GetContactMethodsData']];
        $options = [];
        foreach ($methods as $method) {
            $options[$method['ContactMethodID'] ?? ''] = $method['ContactMethodName'] ?? '';
        }
        return $options;
    }

    protected static function getAgentOptions(): array
    {
        $result = \Illuminate\Support\Facades\Cache::remember('goyzer_agents', 3600, fn() => app(GoyzerService::class)->getAgents());
        if (!$result || !isset($result['GetAgentData'])) return [];
        $agents = is_array($result['GetAgentData']) && isset($result['GetAgentData'][0]) ? $result['GetAgentData'] : [$result['GetAgentData']];
        $options = [];
        foreach ($agents as $agent) {
            $options[$agent['AgentID'] ?? ''] = $agent['AgentName'] ?? '';
        }
        return $options;
    }

    protected static function getPropertyOptions(): array
    {
        $result = \Illuminate\Support\Facades\Cache::remember('goyzer_properties', 3600, fn() => app(GoyzerService::class)->getProperties());
        if (!$result || !isset($result['GetPropertiesData'])) return [];
        $properties = is_array($result['GetPropertiesData']) && isset($result['GetPropertiesData'][0]) ? $result['GetPropertiesData'] : [$result['GetPropertiesData']];
        $options = [];
        foreach ($properties as $property) {
            $options[$property['PropertyID'] ?? ''] = $property['PropertyName'] ?? '';
        }
        return $options;
    }
}