<?php

namespace App\Filament\Resources\Countries;

use App\Filament\Resources\Countries\Pages\ListCountries;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Support\Icons\Heroicon;
use UnitEnum;

class CountryResource extends Resource
{
    protected static ?string $model = \App\Models\Country::class;
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedGlobeAmericas;
    protected static string | UnitEnum | null $navigationGroup = 'Goyzer Data';
    protected static ?string $navigationLabel = 'Countries';

    public static function getPages(): array
    {
        return [
            'index' => ListCountries::route('/'),
        ];
    }
}