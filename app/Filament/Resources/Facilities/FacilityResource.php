<?php

namespace App\Filament\Resources\Facilities;

use App\Filament\Resources\Facilities\Pages\ListFacilities;
use BackedEnum;
use Filament\Resources\Resource;
use UnitEnum;

class FacilityResource extends Resource
{
    protected static ?string $model = \App\Models\Facility::class;
    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-building-office';
    protected static string | UnitEnum | null $navigationGroup = 'Goyzer CRM';
    protected static ?string $navigationLabel = 'Facilities';

    public static function getPages(): array
    {
        return [
            'index' => ListFacilities::route('/'),
        ];
    }
}