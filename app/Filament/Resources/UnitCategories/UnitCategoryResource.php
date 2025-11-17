<?php

namespace App\Filament\Resources\UnitCategories;

use App\Filament\Resources\UnitCategories\Pages\ListUnitCategories;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Support\Icons\Heroicon;
use UnitEnum;

class UnitCategoryResource extends Resource
{
    protected static ?string $model = \App\Models\UnitCategory::class;
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedSquares2x2;
    protected static string | UnitEnum | null $navigationGroup = 'Goyzer Data';
    protected static ?string $navigationLabel = 'Unit Categories';

    public static function getPages(): array
    {
        return [
            'index' => ListUnitCategories::route('/'),
        ];
    }
}