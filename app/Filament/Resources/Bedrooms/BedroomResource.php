<?php

namespace App\Filament\Resources\Bedrooms;

use App\Filament\Resources\Bedrooms\Pages\ListBedrooms;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Support\Icons\Heroicon;
use UnitEnum;

class BedroomResource extends Resource
{
    protected static ?string $model = \App\Models\Bedroom::class;
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedHome;
    protected static string | UnitEnum | null $navigationGroup = 'Goyzer CRM';
    protected static ?string $navigationLabel = 'Bedrooms';

    public static function getPages(): array
    {
        return [
            'index' => ListBedrooms::route('/'),
        ];
    }
}