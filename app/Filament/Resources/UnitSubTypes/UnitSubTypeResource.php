<?php

namespace App\Filament\Resources\UnitSubTypes;

use App\Filament\Resources\UnitSubTypes\Pages\ListUnitSubTypes;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Support\Icons\Heroicon;
use UnitEnum;

class UnitSubTypeResource extends Resource
{
    protected static ?string $model = \App\Models\UnitSubType::class;
    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-squares-2x2';
    protected static string | UnitEnum | null $navigationGroup = 'Goyzer CRM';
    protected static ?string $navigationLabel = 'Unit Sub Types';

    public static function getPages(): array
    {
        return [
            'index' => ListUnitSubTypes::route('/'),
        ];
    }
}