<?php

namespace App\Filament\Resources\Nationalities;

use App\Filament\Resources\Nationalities\Pages\ListNationalities;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Support\Icons\Heroicon;
use UnitEnum;

class NationalityResource extends Resource
{
    protected static ?string $model = \App\Models\Nationality::class;
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedFlag;
    protected static string | UnitEnum | null $navigationGroup = 'Goyzer CRM';
    protected static ?string $navigationLabel = 'Nationalities';

    public static function getPages(): array
    {
        return [
            'index' => ListNationalities::route('/'),
        ];
    }
}