<?php

namespace App\Filament\Resources\States;

use App\Filament\Resources\States\Pages\ListStates;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Support\Icons\Heroicon;
use UnitEnum;

class StateResource extends Resource
{
    protected static ?string $model = \App\Models\State::class;
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedMapPin;
    protected static string | UnitEnum | null $navigationGroup = 'Goyzer Data';
    protected static ?string $navigationLabel = 'States';

    public static function getPages(): array
    {
        return [
            'index' => ListStates::route('/'),
        ];
    }
}