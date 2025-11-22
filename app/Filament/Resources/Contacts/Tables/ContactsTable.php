<?php

namespace App\Filament\Resources\Contacts\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use App\Services\GoyzerService;

class ContactsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('first_name')->searchable()->sortable(),
                TextColumn::make('family_name')->searchable()->sortable(),
                TextColumn::make('email')->searchable(),
                TextColumn::make('mobile_phone')->searchable(),
                TextColumn::make('company')->searchable()->toggleable(),
                TextColumn::make('requirement_type')->searchable()->toggleable(),
                TextColumn::make('bedroom')->toggleable(),
                TextColumn::make('budget')->toggleable(),
                TextColumn::make('created_at')->dateTime()->sortable()->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                SelectFilter::make('title_id')->label('Title')->options(self::getTitleOptions()),
                SelectFilter::make('nationality_id')->label('Nationality')->options(self::getNationalityOptions()),
                SelectFilter::make('contact_type')->options(['Individual' => 'Individual', 'Corporate' => 'Corporate']),
                SelectFilter::make('country_id')->label('Country')->options(self::getCountryOptions()),
                SelectFilter::make('state_id')->label('State')->options(self::getStateOptions()),
                SelectFilter::make('city_id')->label('City')->options(self::getCityOptions()),
                SelectFilter::make('requirement_type')->label('Requirement Type')->options(self::getRequirementTypeOptions()),
                SelectFilter::make('property_id')->label('Property')->options(self::getPropertyOptions()),
                SelectFilter::make('unit_type')->label('Unit Type')->options(self::getUnitCategoryOptions()),
                SelectFilter::make('existing_client')->label('Existing Client')->options(['Yes' => 'Yes', 'No' => 'No']),
                SelectFilter::make('method_of_contact')->label('Contact Method')->options(self::getContactMethodOptions()),
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }

    protected static function getTitleOptions(): array
    {
        $result = \Illuminate\Support\Facades\Cache::rememberForever('goyzer_titles', fn() => app(GoyzerService::class)->getTitle());
        if (!$result || !isset($result['GetTitleData'])) return [];
        $titles = is_array($result['GetTitleData']) && isset($result['GetTitleData'][0]) ? $result['GetTitleData'] : [$result['GetTitleData']];
        $options = [];
        foreach ($titles as $title) {
            $options[$title['TitleID'] ?? ''] = $title['TitleName'] ?? '';
        }
        return $options;
    }

    protected static function getNationalityOptions(): array
    {
        $result = \Illuminate\Support\Facades\Cache::rememberForever('goyzer_nationalities', fn() => app(GoyzerService::class)->getNationality());
        if (!$result || !isset($result['GetNationalityData'])) return [];
        $nationalities = is_array($result['GetNationalityData']) && isset($result['GetNationalityData'][0]) ? $result['GetNationalityData'] : [$result['GetNationalityData']];
        $options = [];
        foreach ($nationalities as $nationality) {
            $options[$nationality['NationalityID'] ?? ''] = $nationality['NationalityName'] ?? '';
        }
        return $options;
    }

    protected static function getCountryOptions(): array
    {
        $result = \Illuminate\Support\Facades\Cache::rememberForever('goyzer_countries', fn() => app(GoyzerService::class)->getCountry());
        if (!$result || !isset($result['GetCountryData'])) return [];
        $countries = is_array($result['GetCountryData']) && isset($result['GetCountryData'][0]) ? $result['GetCountryData'] : [$result['GetCountryData']];
        $options = [];
        foreach ($countries as $country) {
            $options[$country['CountryID'] ?? ''] = $country['CountryName'] ?? '';
        }
        return $options;
    }

    protected static function getRequirementTypeOptions(): array
    {
        $result = \Illuminate\Support\Facades\Cache::rememberForever('goyzer_requirement_types', fn() => app(GoyzerService::class)->getRequirementType());
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
        $result = \Illuminate\Support\Facades\Cache::rememberForever('goyzer_unit_categories', fn() => app(GoyzerService::class)->getUnitCategory());
        if (!$result || !isset($result['GetUnitCategoryData'])) return [];
        $categories = is_array($result['GetUnitCategoryData']) && isset($result['GetUnitCategoryData'][0]) ? $result['GetUnitCategoryData'] : [$result['GetUnitCategoryData']];
        $options = [];
        foreach ($categories as $category) {
            $options[$category['CategoryID'] ?? ''] = $category['CategoryName'] ?? '';
        }
        return $options;
    }

    protected static function getStateOptions(): array
    {
        $result = \Illuminate\Support\Facades\Cache::rememberForever('goyzer_states', fn() => app(GoyzerService::class)->getStates());
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
        $result = \Illuminate\Support\Facades\Cache::rememberForever('goyzer_cities', fn() => app(GoyzerService::class)->getCities());
        if (!$result || !isset($result['GetCitiesData'])) return [];
        $cities = is_array($result['GetCitiesData']) && isset($result['GetCitiesData'][0]) ? $result['GetCitiesData'] : [$result['GetCitiesData']];
        $options = [];
        foreach ($cities as $city) {
            $options[$city['CityID'] ?? ''] = $city['CityName'] ?? '';
        }
        return $options;
    }

    protected static function getPropertyOptions(): array
    {
        $result = \Illuminate\Support\Facades\Cache::rememberForever('goyzer_properties', fn() => app(GoyzerService::class)->getProperties());
        if (!$result || !isset($result['GetPropertiesData'])) return [];
        $properties = is_array($result['GetPropertiesData']) && isset($result['GetPropertiesData'][0]) ? $result['GetPropertiesData'] : [$result['GetPropertiesData']];
        $options = [];
        foreach ($properties as $property) {
            $options[$property['PropertyID'] ?? ''] = $property['PropertyName'] ?? '';
        }
        return $options;
    }

    protected static function getContactMethodOptions(): array
    {
        $result = \Illuminate\Support\Facades\Cache::rememberForever('goyzer_contact_methods', fn() => app(GoyzerService::class)->getContactMethods());
        if (!$result || !isset($result['GetContactMethodsData'])) return [];
        $methods = is_array($result['GetContactMethodsData']) && isset($result['GetContactMethodsData'][0]) ? $result['GetContactMethodsData'] : [$result['GetContactMethodsData']];
        $options = [];
        foreach ($methods as $method) {
            $options[$method['ContactMethodID'] ?? ''] = $method['ContactMethodName'] ?? '';
        }
        return $options;
    }
}
