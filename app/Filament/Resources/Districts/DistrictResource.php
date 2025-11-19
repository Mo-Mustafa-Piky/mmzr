<?php

namespace App\Filament\Resources\Districts;

use App\Filament\Resources\Districts\Pages\ListDistricts;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Support\Icons\Heroicon;
use UnitEnum;

class DistrictResource extends Resource
{
    protected static ?string $model = \App\Models\District::class;
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleGroup;
    protected static string | UnitEnum | null $navigationGroup = 'Goyzer CRM';
    protected static ?string $navigationLabel = 'Districts';

    public static function getPages(): array
    {
        return [
            'index' => ListDistricts::route('/'),
        ];
    }
}