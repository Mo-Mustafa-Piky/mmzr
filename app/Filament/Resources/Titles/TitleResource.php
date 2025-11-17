<?php

namespace App\Filament\Resources\Titles;

use App\Filament\Resources\Titles\Pages\ListTitles;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Support\Icons\Heroicon;
use UnitEnum;

class TitleResource extends Resource
{
    protected static ?string $model = \App\Models\Title::class;
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedIdentification;
    protected static string | UnitEnum | null $navigationGroup = 'Goyzer Data';
    protected static ?string $navigationLabel = 'Titles';

    public static function getPages(): array
    {
        return [
            'index' => ListTitles::route('/'),
        ];
    }
}