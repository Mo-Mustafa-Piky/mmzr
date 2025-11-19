<?php

namespace App\Filament\Resources\Properties;

use App\Filament\Resources\Properties\Pages\ListProperties;
use BackedEnum;
use Filament\Resources\Resource;
use UnitEnum;

class PropertiesResource extends Resource
{
    protected static ?string $model = \App\Models\Properties::class;
    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-home';
    protected static string | UnitEnum | null $navigationGroup = 'Goyzer CRM';
    protected static ?string $navigationLabel = 'Properties';

    public static function getPages(): array
    {
        return [
            'index' => ListProperties::route('/'),
        ];
    }
}