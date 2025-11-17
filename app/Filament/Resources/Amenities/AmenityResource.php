<?php

namespace App\Filament\Resources\Amenities;

use App\Filament\Resources\Amenities\Pages\ListAmenities;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Support\Icons\Heroicon;
use UnitEnum;

class AmenityResource extends Resource
{
    protected static ?string $model = \App\Models\Amenity::class;
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedSparkles;
    protected static string | UnitEnum | null $navigationGroup = 'Goyzer Data';
    protected static ?string $navigationLabel = 'Amenities';

    public static function getPages(): array
    {
        return [
            'index' => ListAmenities::route('/'),
        ];
    }
}